<?php

namespace FluentSupport\App\Services\Csv;

use FluentSupport\App\Services\Includes\FileSystem;

class CsvWriter
{
    protected $path;
    protected $fileName;
    protected $filePath;
    protected $delimiter = ',';
    protected $enclosure = '"';
    protected $file;
    protected $fileMode = 'w+';

    public function __construct()
    {
        $this->fileName = uniqid().'.csv';
        $this->path = $this->_getDir().'/_tempCSV';
        $this->_mkdir();
        $this->file = $this->_make_file();
    }

    public function _getDir()
    {
        $uploadDir = wp_upload_dir();

        return $uploadDir['basedir'] .'/'. FLUENT_SUPPORT_UPLOAD_DIR;
    }

    public function _mkdir()
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function _make_file(){
        $this->filePath = $this->path.'/'.$this->fileName;
        file_exists($this->filePath) ?? unlink($this->filePath);

        $f = fopen($this->filePath, $this->fileMode);

        if ($f === false) {
            die('Error opening the file ' . $this->filePath);
        }

        return $f;
    }

    public function insertOne($row){
        $this->file = fopen($this->filePath, 'a+');
        fputcsv($this->file, $row, $this->delimiter, $this->enclosure);
        fclose($this->file);
    }

    public function insertAll($data){
        $this->file = fopen($this->filePath, 'a+');
        foreach ($data as $row) {
            fputcsv($this->file, $row, $this->delimiter, $this->enclosure);
        }
        fclose($this->file);
    }

    public function output($filename)
    {
        if (!is_null($filename) && file_exists($this->filePath)) {
            $filename = filter_var($filename, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            header('Cache-control: private');
            header('Content-Type: application/octet-stream');
            header('Content-Length: '.filesize($this->filePath));
            header('Content-Disposition: filename='.$filename);
            //Read the size of the file
            readfile($this->filePath);
            unlink($this->filePath);
            die();
        }
        die('Error opening the file ' . $this->filePath);
    }
}
