<?php

namespace S2WPImporter;

use FilesystemIterator;

class Files
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    protected $optionsPrefix = 'shopify2wp_';

    /**
     * Files constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = sanitize_file_name($type);
    }

    public function getLastFileNumber()
    {
        $optionsPrefix = $this->optionsPrefix;
        $saved = (int)get_option("{$optionsPrefix}last_{$this->type}_file_number");

        return $saved > 0 ? $saved : 1;
    }

    public function setLastFileNumber()
    {
        $optionsPrefix = $this->optionsPrefix;
        $saved = self::getLastFileNumber();

        return update_option("{$optionsPrefix}last_{$this->type}_file_number", $saved + 1);
    }

    public function getLastFilePath()
    {
        return $this->getPath($this->getLastFileNumber() . '.json');
    }

    public function getPath($file = '')
    {
        $uploadsPath = "/s2wp-data/{$this->type}/" . sanitize_file_name($file);
        if ( strpos( $uploadsPath, '../' ) !== false ) {
            wp_die( esc_html__('Attempted path traversal.', 'import-shopify-to-wp') );
        }
        $path = untrailingslashit( wp_get_upload_dir()['basedir'] ) . '/' . trim( $uploadsPath, '/' );

        return $path;
    }

    public function pathExists($file = '')
    {
        return file_exists($this->getPath($file));
    }

    public function getTotalFiles()
    {
        if (!$this->pathExists()) {
            return 0;
        }

        $fi = new FilesystemIterator($this->getPath(), FilesystemIterator::SKIP_DOTS);

        return iterator_count($fi);
    }

    public function getLastFileData()
    {
        $data = [];

        if (file_exists($this->getLastFilePath())) {
            $json = file_get_contents($this->getLastFilePath());
            $object = (object)json_decode($json);

            $data = !empty($object->{$this->type}) ? $object->{$this->type} : $data;
        }

        return $data;
    }

    public function reset()
    {
        $optionsPrefix = $this->optionsPrefix;

        update_option("{$optionsPrefix}last_{$this->type}_file_number", 1);
        get_option('shopify2wp_current_step', 'upload');
    }

}
