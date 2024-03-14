<?php


namespace rnpdfimporter\core\Integration;


use rnpdfimporter\core\Loader;

class FileManager
{
    /** @var Loader */
    public $Loader;

    private $_rootPath='';
    private $_rootURL='';

    public function __construct($loader)
    {
        $this->Loader = $loader;

    }


    public function GetRootFolderPath()
    {
        if($this->_rootPath=='')
        {
            $uploadDir=wp_upload_dir();
            $this->_rootPath=$uploadDir['basedir'].'/'.$this->Loader->Prefix.'/';
            $this->_rootURL=$uploadDir['baseurl'].'/'.$this->Loader->Prefix.'/';
            $this->MaybeCreateFolder($this->_rootPath,false);
        }
        return $this->_rootPath;
    }

    public function GetRootFolderURL()
    {
        if($this->_rootPath=='')
        {
            $uploadDir=wp_upload_dir();
            $this->_rootPath=$uploadDir['basedir'].'/'.$this->Loader->Prefix.'/';
            $this->_rootURL=$uploadDir['baseurl'].'/'.$this->Loader->Prefix.'/';
            $this->MaybeCreateFolder($this->_rootPath,false);
        }
        return $this->_rootURL;
    }

    public function GetPDFFolderPath(){
        return $this->GetRootFolderPath().'PDFTemplates/';
    }


    public function GetPDFFolderURL(){
        return $this->GetRootFolderURL().'PDFTemplates/';
    }

    public function GetFontURL(){
        $dir=wp_upload_dir();
        return $dir['baseurl'].'/'.$this->Loader->Prefix.'/';
    }


    private function GetTempFolderRootPath()
    {
        $tempFolder=$this->GetRootFolderPath().'temp/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;

    }


    public function GetFontMetricsPath(){
        $metricsPath=$this->GetFontFolderPath().'Metrics/';
        $this->MaybeCreateFolder($metricsPath);
        return $metricsPath;
    }

    public function GetFontFolderPath(){
        $tempFolder=$this->GetRootFolderPath().'fonts/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function GetCustomFontFolderPath(){
        $tempFolder=$this->GetRootFolderPath().'custom_font/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }

    public function RemoveTempFolders(){
        $path=$this->GetTempFolderRootPath();
        foreach(( glob( $path.'*' ) ? glob( $path.'*' ) : array() ) as $path)
        {
            if(!\is_dir($path))
                continue;
            $this->recursiveRemove($path);


        }
    }

    public function recursiveRemove($dir) {
        $structure = glob(rtrim($dir, "/").'/*');
        if (is_array($structure)) {
            foreach($structure as $file) {
                if (is_dir($file)) $this->recursiveRemove($file);
                elseif (is_file($file)) unlink($file);
            }
        }
        rmdir($dir);
    }
    public function GetTemporalFolderPath(){
        $tempPath=$this->GetTempFolderRootPath();
        $i=1;
        $tempFolderToReturn='';
        while(is_dir($tempFolderToReturn=$tempPath.'temp'.$i.'/'))
        {
            $i++;
        }

        if(!\mkdir($tempFolderToReturn))
            throw new Exception('Could not create folder '.$tempFolderToReturn);

        return $tempFolderToReturn;
    }

    public function MaybeCreateFolder($directory,$secure=false)
    {
        if(!is_dir($directory))
            if(!mkdir($directory,0777,true))
                throw new Exception('Could not create folder '.$this->_rootPath);
            else{
                if($secure)
                {
                    @file_put_contents( $directory . '.htaccess', 'deny from all' );
                    @touch( $directory . 'index.php' );
                }
            }


    }


}