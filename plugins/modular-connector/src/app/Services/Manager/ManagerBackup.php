<?php

namespace Modular\Connector\Services\Manager;

use Modular\Connector\Events\Backup\ManagerBackupFailedCreation;
use Modular\Connector\Facades\Backup;
use Modular\Connector\Facades\Core;
use Modular\Connector\Facades\Database;
use Modular\Connector\Facades\Plugin;
use Modular\Connector\Facades\Server;
use Modular\Connector\Facades\Theme;
use Modular\Connector\Services\Backup\BackupOptions;
use Modular\Connector\Services\Backup\BackupWorker;
use Modular\Connector\Services\Helpers\File;
use Modular\Connector\Services\Helpers\Utils;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\File as FileFacade;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;

/**
 * Handles all functionality related backup system.
 */
class ManagerBackup
{
    public const STATUS_ZIP_PART_READY = 'ready';

    public const STATUS_FAILED_IN_CREATION = 'failed_in_creation';

    public const STATUS_FAILED_EXPORT_DATABASE = 'failed_export_database';
    public const STATUS_FAILED_EXPORT_FILES = 'failed_export_files';

    public const STATUS_DONE = 'done';

    /**
     * Return core WordPress dir
     *
     * @return string
     */
    public function getCoreDir()
    {
        return untrailingslashit(ABSPATH);
    }

    /**
     * Return plugin WordPress dir
     *
     * @return string
     */
    public function getPluginsDir()
    {
        return untrailingslashit(WP_PLUGIN_DIR);
    }

    /**
     * Return theme WordPress dir
     *
     * @return string
     */
    public function getThemesDir()
    {
        return untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'themes';
    }

    /**
     * Return upload WordPress dir
     *
     * @return string
     */
    public function getUploadsDir()
    {
        if (function_exists('wp_upload_dir')) {
            $uploadDir = wp_upload_dir();

            if (isset($uploadDir['basedir'])) {
                return untrailingslashit($uploadDir['basedir']);
            }
        }

        return untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'uploads';
    }

    /**
     * Get relative backups path
     *
     * @param string|null $path
     * @return string
     */
    public function path(?string $path = null)
    {
        $backupPath = Server::getContentDir() . DIRECTORY_SEPARATOR . 'modular_backups';

        return $backupPath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Makes the initializations needed to let the WordPress work.
     *
     * It creates a dedicated folder to backups, with an empty 'index.html' file inside, and a '.htaccess' file ('deny
     * from all') also inside it.
     *
     * This method must be called when the plugin is installed.
     *
     * @param BackupOptions $options
     * @return void
     * @throws \Exception
     */
    public function init(BackupOptions $options)
    {
        if (!Storage::exists($this->path('index.html'))) {
            Storage::put($this->path('index.html'), '<!-- // Silence is golden. -->');
        }

        if (!Storage::exists($this->path('index.php'))) {
            Storage::put($this->path('index.php'), '<?php // Silence is golden.');
        }

        if (!Storage::exists($this->path('.htaccess'))) {
            Storage::put($this->path('.htaccess'), 'deny from all');
        }

        if (!Storage::exists($this->path('web.config'))) {
            $webConfig = '<configuration>';
            $webConfig .= '<system.webServer>';
            $webConfig .= '<authorization>';
            $webConfig .= '<deny users="*" />';
            $webConfig .= '</authorization>';
            $webConfig .= '</system.webServer>';
            $webConfig .= '</configuration>';

            Storage::put($this->path('web.config'), $webConfig);
        }
    }

    /**
     * Returns the WordPress site paths tree as an object in which keys with content value represent folders and keys
     * with 'null' content value represent files.
     *
     * This method is useful when the frontend needs to represent the folders and files tree of its WordPress site in
     * order to allow excluding or including into the backup.
     *
     * @return \Modular\ConnectorDependencies\Illuminate\Support\Collection
     */
    public function getDirectoryTree($path)
    {
        $path = Storage::path($path);

        return File::getTree($path);
    }

    /**
     * Makes a backup in the Modular backups folder that includes the provided $options (if valid) as sub folders. Valid
     * options are: 'plugins', 'themes', 'uploads', 'others', 'mu_plugins', 'wp_core' and 'database
     *
     * We assume options come not empty and valid.
     *
     * This process changes the current backups status and also the specific backup status with the 3 steps: 'trying to
     * create zip', 'error creating zip' and 'zip successfully created'.
     *
     * @return array
     * @throws \Exception
     */
    public function information()
    {
        return [
            'posts' => wp_count_posts(),
            'attachment' => wp_count_posts('attachment'),
            'core' => Core::get(),
            'plugins' => Collection::make(Plugin::all())
                ->map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'basename' => $item['basename'],
                        'version' => $item['version'],
                        'status' => $item['status'],
                    ];
                }),
            'themes' => Collection::make(Theme::all())
                ->map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'basename' => $item['basename'],
                        'version' => $item['version'],
                        'status' => $item['status'],
                    ];
                }),
            'database' => Database::get(),
        ];
    }

    /**
     * @param array $excludedFiles
     * @return Collection
     */
    public function getExcludedFiles(array $excludedFiles): Collection
    {
        $excluded = Collection::make($excludedFiles);

        return $excluded->merge($this->getCoreExcluded());
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getFilesByBlob(string $name)
    {
        $path = Backup::path(sprintf('%s', $name));

        return FileFacade::glob(Storage::path($path));
    }

    /**
     * @var BackupOptions
     */
    protected BackupOptions $options;

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function make(BackupOptions $options)
    {
        Utils::configMaxLimit();

        try {
            $this->init($options);

            $worker = BackupWorker::getInstance();
            $worker = $worker->calculateParts($options);

            if (!is_null($worker)) {
                $worker->dispatch();
            }
        } catch (\Throwable $e) {
            ManagerBackupFailedCreation::dispatch($options, $e);

            throw $e;
        }
    }

    /**
     * Deletes the backup with the provided $backupName from the backups folder if existing.
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function remove(string $name, bool $removeAll = false)
    {
        $files = Backup::getFilesByBlob(sprintf('%s*', $name));

        // delete the previous
        if (!empty($files)) {
            FileFacade::delete($files);
        }

        if ($removeAll) {
            BackupWorker::getInstance()->deleteAll();
        }
    }
}
