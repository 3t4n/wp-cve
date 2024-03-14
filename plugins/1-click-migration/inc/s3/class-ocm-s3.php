<?php

namespace OCM;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OCM_S3
{
    public static function s3_create_bucket_ifnot_exists($s3_bucket_name)
    {
        $params = array('ocm_key' => $s3_bucket_name);

        $response = wp_remote_post('https://9ulcorn6i3.execute-api.us-east-1.amazonaws.com/Prod/createOCMBucket', array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode($params),
            'method' => 'POST',
            'data_format' => 'body',
        ));

        if ((!is_wp_error($response) && 200 !== $response['response']['code']) || is_wp_error($response)) {
            return false;
        }


        return json_decode($response['body']);
    }

    public static function s3_generate_download_urls($s3_bucket_name)
    {
        $params = array('ocm_key' => $s3_bucket_name);

        $response = wp_remote_post('https://jbcfmgdzm6.execute-api.us-east-1.amazonaws.com/default/generateOCMDownloadLink', array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode($params),
            'method' => 'POST',
            'data_format' => 'body',
        ));

        if (is_wp_error($response) || !isset($response['body'])) {
            return false;
        }

        return json_decode($response['body']);
    }

    public static function bucket_exists()
    {
        $username = sanitize_email($_GET['username']);

        $password = sanitize_key($_GET['password']);


        $hash = md5($username . $password);
        $bucket_key = filter_var($hash, FILTER_SANITIZE_STRING);

        $params = array('ocm_key' => $bucket_key);

        $response = wp_remote_post('https://f5o1yhx7cc.execute-api.us-east-1.amazonaws.com/default/bucketExists', array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode($params),
            'method' => 'POST',
            'data_format' => 'body',
        ));

        if (is_wp_error($response) || !isset($response['response']['code']) || 200 !== $response['response']['code']) {
            return json_encode(false);
        }

        return json_encode(true);
    }

    public static function upload_zip( $zip_file )
    {
        $urls = get_option( 'ocm_presigned_urls' );

        $folder_type = explode('/', $zip_file);

        $folder_type = str_replace( '.zip.crypt', '', $folder_type[count($folder_type) - 1] );


        try {
            $request = new Request(
                'PUT',
                $urls->{$folder_type},
                [],
                fopen($zip_file, 'rb')
            );
            $client = new Client([
                'timeout' => One_Click_Migration::$process_backup_single->remaining_time(),
            ]);

            return $client->send($request);
        } catch (Exception $e) {

            One_Click_Migration::write_to_log("Process is Restarting");
            $next_retry_count = get_option('ocm_backup_upload_retry_' . $folder_type, 1);
            if($next_retry_count <= 2){
              update_option('ocm_backup_upload_retry_' . $folder_type , $next_retry_count, true);
              One_Click_Migration::$process_backup_single->restart_task();
            }else{

              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_type.  ' Uploading'));
              OCM_Backup::set_complete_backup_step($folder_type, OCM_Backup::STEP_BACKUP_CHILD_UPLOAD);
            }


        }
    }
}
