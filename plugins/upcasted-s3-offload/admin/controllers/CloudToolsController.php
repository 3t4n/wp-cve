<?php

/**
 * Class CloudToolsController
 */
class CloudToolsController
{
    public function upcasted_offload_connect()
    {
        try {
            $accessKeyId = ( !empty($_POST['access_key_id']) ? CloudCredentialsEncryption::getInstance()->encrypt( sanitize_post( $_POST['access_key_id'] ) ) : null );
            $secretAccessKey = ( !empty($_POST['secret_access_key']) ? CloudCredentialsEncryption::getInstance()->encrypt( sanitize_post( $_POST['secret_access_key'] ) ) : null );
            $region = ( !empty($_POST['region']) ? sanitize_post( $_POST['region'] ) : null );
            $customEndpoint = ( !empty($_POST['custom_endpoint']) ? sanitize_post( preg_replace( "(^https?://)", "", $_POST['custom_endpoint'] ) ) : null );
            CloudApplication::destroy();
            CloudApplication::getInstance(
                $accessKeyId,
                $secretAccessKey,
                $region,
                $customEndpoint
            );
            wp_send_json( CloudApplication::getInstance()->cloudManipulator->get_buckets(), 200 );
        } catch ( Exception $exception ) {
            wp_send_json( sprintf( '<p>There was an error connecting to the object storage server:<p> 
                <code>%s</code>
                <p>Usually, if you get an error here you should double check: </p>
                            <ol>
                                <li>Check if your credentials are correct (Key and Secret)</li>
                                <li>Check if the region is correct</li>
                                <li>Check if your custom endpoint is correct. If you don\'t use AWS S3 then you should set a custom endpoint.</li>
                                <li>Check if IAM user has full permissions over the bucket</li>
                            </ol>
                ', $exception->getMessage() ) );
        }
    }
    
    public function upcasted_init()
    {
        try {
            CloudApplication::getInstance()->upcasted_init( sanitize_post( $_POST['bucket'] ) );
            wp_send_json( 'Success', 200 );
        } catch ( Exception $exception ) {
            wp_send_json( $exception->getMessage(), $exception->getCode() );
        }
    }
    
    public function upcasted_get_number_of_files()
    {
        try {
            wp_send_json( CloudApplication::getInstance()->upcasted_get_number_of_files( sanitize_post( $_POST['meta_type'] ) ), 200 );
        } catch ( Exception $exception ) {
            wp_send_json( $exception->getMessage(), $exception->getCode() );
        }
    }

}