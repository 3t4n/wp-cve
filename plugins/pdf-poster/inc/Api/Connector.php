<?php 
// require 'vendor/autoload.php';
if(!class_exists('H5VP_S3_Connector')){
class H5VP_S3_Connector{
    private $connectionStatus = false;
    private $nonAllowedFiles = "php,asp,aspx,jsp";
    private $currDir = '';
    private $delimiter = '';
    private $currDirPrev = '';
    private $bucketName;
    private $reagon;
    private $accessKey;
    private $accessSecret;
    private $lists = [];

    public function __construct($bucketName = '', $reagon = '', $accessKey = '', $accessSecret = ''){
        $this->bucketName = $bucketName;
        $this->reagon = $reagon;
        $this->accessKey = $accessKey;
        $this->accessSecret = $accessSecret;
        $this->getObjects();
    }

    public function getObjects(){
        if('' == $this->bucketName || '' == '$reagon' || '' == $this->accessKey || '' == $this->accessSecret){
            $this->connectionStatus == false;
        }else {
            $s3 = new Aws\S3\S3Client(['region' => $this->reagon, 'version' => 'latest', 'credentials' => ['key' => $this->accessKey, 'secret' => $this->accessSecret, ]]);
            if($s3->listBuckets()){
                $this->connectionStatus = true;
                $lists = $s3->listObjects(['Bucket' => $this->bucketName, 'Delimiter' => $this->delimiter, 'Prefix' => $this->currDir]);
                if(is_array($lists['Contents'])){
                    foreach($lists['Contents'] as $list){
                        array_push($this->lists, $list['Key']);
                    }
                }
            }
            //return $this->lists;
        }
    }

    public function getLists(){
        return $this->lists;
    }

    //rename big file name to small name
    function fileNaming($fileName) {
        $textLengt = 0;
        $textLength = strlen($fileName);
        $maxChars = 16;
        $fileName = substr_replace($fileName, '...', $maxChars / 2, $textLength - $maxChars);
        return $fileName;
    }

    public function getStatus(){
        return $this->connectionStatus;
    }

    function httpGet($url) {
        $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $output = curl_exec($ch);
      curl_close($ch);
      return $output;
    }
}
}