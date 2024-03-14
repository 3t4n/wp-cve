<?php

namespace Modular\Connector\Services\Backup;

use Modular\Connector\Facades\Backup;
use Modular\ConnectorDependencies\Illuminate\Support\Arr;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;
use Modular\ConnectorDependencies\Illuminate\Support\Str;

/**
 * @property string $mrid
 * @property string $name
 * @property string $siteBackup ID of the backup in Modular
 * @property array $included
 * @property array $excludedFiles
 * @property array $excludedTables
 * @property string $root Root path
 * @property int $limit The maximum number of files to batch at once when writing to the zip
 * @property int $batch Zip chunk number
 * @property int $batchMaxFileSize Zip chunk max file size
 * @property int $batchMaxTimeout Zip chunk max timeout
 * @property array $connection
 */
class BackupOptions implements \JsonSerializable
{
    /**
     * @var array
     */
    private array $attributes = [
        'mrid' => null,
        'name' => null,
        'siteBackup' => null,

        'included' => [],
        'excludedFiles' => [],
        'excludedTables' => [],

        'root' => null,
        'limit' => 5000,

        'batchMaxFileSize' => 128 * 1024 * 1024, // 128 MB
        'batchMaxTimeout' => 30 * 60, // 30 minutes

        'connection' => [
            'host' => DB_HOST,
            'port' => 3306,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
        ],
    ];

    public const INCLUDE_DATABASE = 'database';
    public const INCLUDE_CORE = 'core';

    /**
     * @param string $mrid
     * @param \stdClass $payload
     */
    public function __construct(string $mrid, \stdClass $payload)
    {
        $this->mrid = $mrid;
        $this->name = trim($payload->name, '"');
        $this->siteBackup = intval($payload->site_backup);

        if (isset($payload->included)) {
            $this->included = $payload->included;
        }

        if (isset($payload->excluded->files)) {
            $this->excludedFiles = $this->getExcludedFiles($payload->excluded->files);
        } else {
            $this->excludedFiles = $this->getExcludedFiles();
        }

        if (isset($payload->excluded->tables)) {
            $this->excludedTables = $payload->excluded->tables;
        }

        if (isset($payload->batch->size)) {
            // If the size is less than 24MB, we set it to 24MB
            if ($payload->batch->size < 24 * pow(1024, 2)) {
                $payload->batch->size = 24 * pow(1024, 2);
            } elseif ($payload->batch->size > 2 * pow(1024, 3)) {
                // If the size is greater than 2GB, we set it to 2GB
                $payload->batch->size = 2 * pow(1024, 3);
            }

            $this->batchMaxFileSize = $payload->batch->size;
        }

        if (isset($payload->batch->timeout)) {
            $this->batchMaxTimeout = $payload->batch->timeout;
        }

        if (isset($payload->batch->max_files)) {
            $this->limit = $payload->batch->max_files;
        }

        if (isset($options->db->username)) {
            $this->connection['username'] = $options->db->username;
        }

        if (isset($options->db->password)) {
            $this->connection['password'] = $options->db->password;
        }

        if (isset($options->db->host)) {
            $this->connection['host'] = $options->db->host;
        }

        $host = $this->connection['host'];

        $port = $options->db->port ?? (parse_url($host)['port'] ?? 3306);
        $this->connection['host'] = str_ireplace(':' . $port, '', $host);
        $this->connection['port'] = $port;
    }

    /**
     * Check if backup is finished
     *
     * @return bool
     */
    public function finished(): bool
    {
        return count($this->included) === 1 && $this->included[0] === 'database' ||
            $this->offset >= $this->totalFiles;
    }

    /**
     * Return core WordPress exclusions
     *
     * @return string[]
     */
    private function getExcludedFiles(array $files = [])
    {
        $excluded = Collection::make([
            Backup::path(),
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'cache',
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'updraft',
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'aiowps_backups',
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'ai1wm-backups',
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'backups-dup-pro',
            untrailingslashit(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR . 'debug.log',
        ])->merge($files);

        return $excluded->map(function ($item) {
            $abs = untrailingslashit(ABSPATH);

            if (!Str::startsWith($item, $abs)) {
                $item = Storage::path($item);
            }

            return $item;
        })->toArray();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function &__get(string $name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        Arr::set($this->attributes, $name, $value);
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $attrs = $this->attributes;

        unset($attrs['connection'], $attrs['mrid']);

        foreach ($attrs as $key => $attr) {
            unset($attrs[$key]);

            $attrs[Str::snake($key)] = $attr;
        }

        return $attrs;
    }
}
