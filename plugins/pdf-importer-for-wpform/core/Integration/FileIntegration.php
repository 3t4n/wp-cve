<?php


namespace rnpdfimporter\core\Integration;


use rnpdfimporter\core\Loader;

class FileIntegration
{
    public $Loader;

    /**
     * FileIntegration constructor.
     * @param $loader Loader
     */
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetUploadDir(){
        $uploadDir=wp_upload_dir();
        return $uploadDir['basedir'];
    }

    public function GetUploadURL(){
        $uploadDir=wp_upload_dir();
        return $uploadDir['baseurl'];
    }

    public function GetUniqueFileName($dir,$fileName)
    {
        return wp_unique_filename($dir,$fileName);
    }

    public function CheckFileType($tempFileName,$name,$mimes){
        return wp_check_filetype_and_ext( $tempFileName, $name, false );
    }

    public function GetAttachmentPathById($attahcmentId)
    {
        return \get_attached_file($attahcmentId);
    }




}