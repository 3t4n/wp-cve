<?php


namespace rednaoformpdfbuilder\htmlgenerator\generators;


use Exception;
use rednaoformpdfbuilder\core\Loader;

class FileManager
{
    /** @var Loader */
    public $Loader;

    private $_rootPath='';

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
            $this->MaybeCreateFolder($this->_rootPath,true);
        }
        return $this->_rootPath;
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

    public function GetLoggerPath(){
        $tempFolder=$this->GetRootFolderPath().'log/';
        $this->MaybeCreateFolder($tempFolder,true);
        return $tempFolder;
    }


}