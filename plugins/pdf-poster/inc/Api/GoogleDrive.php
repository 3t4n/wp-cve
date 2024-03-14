<?php
namespace PDFPro\Api;

use PDFPro\Api\GoogleDriveApi;

class GoogleDrive{

    private $fieldId = null;
    private $appKey = null;

    public function register(){
        $drive = new GoogleDriveApi('AIzaSyDgt4FzilM45vpspyL9SfOZeOxGIx2GiRo', '282957762539-4oiur3kgqnme1650uf7n1npe20mn5r9p.apps.googleusercontent.com', '282957762539');
    }
}