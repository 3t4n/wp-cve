<?php
// namespace PDFPro\API;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
// require_once(__DIR__.'/Connector.php');

class AWSS3 {

    private $bucketName = null;
    private $s3 = null;

    public function __construct($bucketName = '', $region = '', $accessKey = '', $accessSecret = ''){
        $this->bucketName = $bucketName;
        $config = [
            'region' => $region, 
            'version' => 'latest', 
            'credentials' => [
                'key' => $accessKey,
                'secret' => $accessSecret
            ]
        ];

        $sdk = new \Aws\Sdk($config);

        $this->s3 = $sdk->createS3();
    }

    public function getObjects(){
        return $this->s3->listBuckets();
    }

    function h5vp_aws_picker(){
        $bucketName = $this->get_access_key('h5vp_aws_bucket');
        $region = $this->get_access_key('h5vp_aws_region');
        $keyID = $this->get_access_key('h5vp_aws_key_id');
        $key = $this->get_access_key('h5vp_aws_access_key');
        $files = new H5VP_S3_Connector($bucketName, $region, $keyID, $key);
        if($files->getStatus() == true){
            echo json_encode(['bucketName' => $bucketName, 'region' => $region, 'lists' => $files->getLists()]);
        }else {
            echo "false";
        }
        die();
    }

    function get_access_key($key, $default=''){
        $options = get_option('h5vp_option', $default);
        if (isset($options[$key]) && $options[$key] != '') {
            return $options[$key];
        } else {
            return $default;
        }
    }
}

