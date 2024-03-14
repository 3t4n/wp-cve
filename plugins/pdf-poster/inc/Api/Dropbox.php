<?php
namespace PDFPro\Api;

use PDFPro\Api\DropboxApi;

class Dropbox{

    private $fieldId = null;
    private $appKey = null;

    public function register(){
        $dropbox = new DropboxApi('m4f8cgysa55edg8');
    }
}