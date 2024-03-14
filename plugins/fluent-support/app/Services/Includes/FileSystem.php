<?php

namespace FluentSupport\App\Services\Includes;

use FluentSupport\Framework\Support\Arr;

class FileSystem
{

    protected $subDir = '';


    public function _setSubDir($subDir)
    {
        $this->subDir = $subDir;
        return $this;
    }

    /**
     * Read file content from custom upload dir of this application
     * @return string [path]
     */
    public function _get($file)
    {
        $arr = explode('/', $file);
        $fileName = end($arr);
        return file_get_contents(
            $this->getDir() . '/' . $fileName
        );
    }

    /**
     * Get custom upload dir name of this application
     * @return string [directory path]
     */
    public function _getDir()
    {
        $uploadDir = wp_upload_dir();

        return $uploadDir['basedir'] . DIRECTORY_SEPARATOR . FLUENT_SUPPORT_UPLOAD_DIR;
    }

    /**
     * Get absolute path of file using custom upload dir name of this application
     * @return string [file path]
     */
    public function _getAbsolutePathOfFile($file)
    {
        return $this->_getDir() . '/' . $file;
    }

    /**
     * Upload files into custom upload dir of this application
     * @return array
     */
    public function _uploadFromRequest()
    {
        return $this->_put(\FluentSupport\App\Services\Helper::FluentSupport('request')->files());
    }

    /**
     * Upload files into custom upload dir of this application
     * @param array $files
     * @return array
     */
    public function _put($files)
    {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $this->overrideUploadDir();

        $uploadOverrides = ['test_form' => false];

        foreach ((array)$files as $file) {
            $filesArray = $file->toArray();
            $extraData = Arr::only($filesArray, ['name', 'size']);
            $uploadsData = \wp_handle_upload($filesArray, $uploadOverrides);
            $uploadedFiles[] = array_merge($extraData, $uploadsData);
        }

        return $uploadedFiles;
    }

    public function _putAsContent($fileName, $fileContent)
    {
        add_filter('upload_dir', [$this, '_setCustomUploadDir']);
        $item = wp_upload_bits($fileName, null, $fileContent);
        remove_filter('upload_dir', [$this, '_setCustomUploadDir']);
        return $item;
    }

    /**
     * Copy a file from custom upload directory of this application
     * @param string $source file path
     * @param int $ticketId
     * @return array | boolean
     */
    public function _copy($source)
    {
        if (!file_exists($source)) {
            return false;
        }

        [$destDir, $folderName] = $this->_getCustomDir();

        $fileName = basename($source);
        $destFile = $destDir . DIRECTORY_SEPARATOR . $fileName;

        if (@copy($source, $destFile)) {
            return $this->_updateUploadDirForCopy($fileName, $folderName);
        }

        return false;
    }

    private function _getCustomDir()
    {
        $folderName = '';
        $directory = $this->_getDir();

        if ($this->subDir) {
            $folderName .= DIRECTORY_SEPARATOR . $this->subDir;
            $directory .= $folderName;
            if (!is_dir($directory)) {
                @mkdir($directory, 0755);
            }
        }

        return [$directory, $folderName];
    }

    private function _updateUploadDirForCopy($fileName, $folderName)
    {
        $uploadDir = wp_upload_dir();
        $destDir = $this->_getDir() . $folderName;
        $uploadDir['file_path'] = $destDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadDir['basedir'] = $destDir;
        $uploadDir['subdir'] = $folderName;
        $uploadDir['url'] = $uploadDir['baseurl'] . DIRECTORY_SEPARATOR . FLUENT_SUPPORT_UPLOAD_DIR . $folderName . DIRECTORY_SEPARATOR . $fileName;
        $uploadDir['baseurl'] = $uploadDir['baseurl'] . DIRECTORY_SEPARATOR . FLUENT_SUPPORT_UPLOAD_DIR . $folderName;

        return $uploadDir;
    }

    /**
     * Delete a file from custom upload directory of this application
     * @param array $files
     * @return void
     */
    public function _delete($files)
    {
        $files = (array)$files;

        foreach ($files as $file) {
            $arr = explode('/', $file);
            $fileName = end($arr);
            @unlink($this->getDir() . '/' . $fileName);
        }
    }

    /**
     * Register filters for custom upload dir
     */
    public function _overrideUploadDir()
    {
        add_filter('wp_handle_upload_prefilter', function ($file) {
            add_filter('upload_dir', [$this, '_setCustomUploadDir']);

            add_filter('wp_handle_upload', function ($fileinfo) {
                remove_filter('upload_dir', [$this, '_setCustomUploadDir']);
                $filePath = $fileinfo['file'];
                $fileinfo['file'] = basename($filePath);
                $fileinfo['file_path'] = $filePath;
                return $fileinfo;
            });

            return $this->_renameFileName($file);
        });
    }

    /**
     * Set plugin's custom upload dir
     * @param array $param
     * @return array $param
     */
    public function _setCustomUploadDir($param)
    {
        $folderName = '/' . FLUENT_SUPPORT_UPLOAD_DIR;

        if ($this->subDir) {
            $folderName .= '/' . $this->subDir;
            if (!is_dir($param['basedir'] . $folderName)) {
                @mkdir($param['basedir'] . $folderName, 0755);
            }
        }

        $param['url'] = $param['baseurl'] . $folderName;

        $param['path'] = $param['basedir'] . $folderName;


        if (!is_dir($param['path'])) {
            @mkdir($param['path'], 0755);
        }

        return $param;
    }

    /**
     * Rename the uploaded file name before saving
     * @param array $file
     * @return array $file
     */
    public function _renameFileName($file)
    {
        $prefix = 'fluent_support-' . md5(uniqid(rand())) . '___';
        $prefix = apply_filters('fluent_support/uploaded_file_name_prefix', $prefix);
        $file['name'] = $prefix . $file['name'];
        return $file;
    }

    public static function __callStatic($method, $params)
    {
        $instance = new static;

        return call_user_func_array([$instance, $method], $params);
    }

    public function __call($method, $params)
    {
        $hiddenMethod = "_" . $method;

        $method = method_exists($this, $hiddenMethod) ? $hiddenMethod : $method;

        return call_user_func_array([$this, $method], $params);
    }
}
