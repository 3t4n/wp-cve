<?php

/**
 * Class CloudTools
 */
class CloudActions implements  iCloudActions 
{
    const  MOVE_FROM_LOCAL_TO_CLOUD_CRON_HOOK = 'upcasted_move_from_local_to_cloud_using_cron__premium_only' ;
    const  MOVE_FROM_CLOUD_TO_LOCAL_HOOK = 'upcasted_move_from_cloud_to_local_using_cron__premium_only' ;
    const  CRON_RECURRENCE = '20_seconds' ;
    const  META_COMPARE_FROM_LOCAL_TO_CLOUD = 'NOT LIKE' ;
    const  META_COMPARE_FROM_CLOUD_TO_LOCAL = 'LIKE' ;
    /**
     * @var AmazonCloudManipulator
     */
    public  $cloudManipulator ;
    /**
     * @var string
     */
    private  $bucket ;
    /**
     * @var string
     */
    private  $uploadDir ;
    /**
     * @var CronManagement
     */
    private  $cronManagement ;
    private  $loader ;
    /**
     * @var string
     */
    private  $removeFileFromCloudFlag = false ;
    /**
     * @var string
     */
    private  $cloudManipulatorBasePath ;
    /**
     * @var array
     */
    protected  $tempFilesToCleanup = [] ;
    /**
     * CloudActions constructor.
     * @param iCloudManipulator $cloudManipulator
     */
    public function __construct( iCloudManipulator $cloudManipulator )
    {
        
        if ( $options = get_option( UPCASTED_S3_OFFLOAD_SETTINGS ) ) {
            $this->cloudManipulator = new $cloudManipulator(
                $options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID],
                $options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY],
                $options[UPCASTED_OFFLOAD_REGION] ?? null,
                $options[UPCASTED_CUSTOM_ENDPOINT] ?? null
            );
            $this->uploadDir = wp_get_upload_dir()['basedir'];
            $this->cronManagement = new CronManagement();
            $cloudToolsController = new CloudToolsController();
            $this->loader = new Upcasted_S3_Offload_Loader();
            
            if ( isset( $options[UPCASTED_S3_OFFLOAD_BUCKET] ) ) {
                $this->bucket = $options[UPCASTED_S3_OFFLOAD_BUCKET];
                $this->cloudManipulatorBasePath = $this->cloudManipulator->get_base_path();
            }
            
            if ( isset( $options[UPCASTED_REMOVE_CLOUD_FILE] ) ) {
                $this->removeFileFromCloudFlag = 'no' === $options[UPCASTED_REMOVE_CLOUD_FILE];
            }
            if ( isset( $options[UPCASTED_REMOVE_LOCAL_FILE] ) ) {
                $this->removeFileFromLocalFlag = 'no' === $options[UPCASTED_REMOVE_LOCAL_FILE];
            }
            $this->loader->add_action( 'wp_ajax_upcasted_init', $cloudToolsController, 'upcasted_init' );
            $this->loader->add_action( 'wp_ajax_nopriv_upcasted_init', $cloudToolsController, 'upcasted_init' );
            $this->loader->add_action( 'wp_ajax_upcasted_create_bucket', $cloudToolsController, 'upcasted_create_bucket__premium_only' );
            $this->loader->add_action( 'wp_ajax_nopriv_upcasted_create_bucket', $cloudToolsController, 'upcasted_create_bucket__premium_only' );
            $this->loader->run();
        }
    
    }
    
    /**
     * @param string $bucket
     * Init plugin(set selected bucket / reset current instance)
     */
    public function upcasted_init( string $bucket )
    {
        $includedFiletypes = ( !empty($_POST['included_filetypes']) ? sanitize_post( $_POST['included_filetypes'] ) : null );
        
        if ( null !== $includedFiletypes ) {
            unset( $includedFiletypes['ID'] );
            unset( $includedFiletypes['filter'] );
            $options[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] = $includedFiletypes;
        }
        
        update_option( UPCASTED_S3_OFFLOAD_SETTINGS, [
            UPCASTED_REMOVE_LOCAL_FILE             => ( !empty($_POST['remove_file_from_local']) ? sanitize_post( $_POST['remove_file_from_local'] ) : false ),
            UPCASTED_REMOVE_CLOUD_FILE             => ( !empty($_POST['remove_file_from_s3']) ? sanitize_post( $_POST['remove_file_from_s3'] ) : false ),
            UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES => $includedFiletypes,
        ] );
        $options = get_option( UPCASTED_S3_OFFLOAD_SETTINGS );
        if ( !empty($_POST['remove_file_from_local']) ) {
            $options[UPCASTED_REMOVE_LOCAL_FILE] = sanitize_post( $_POST['remove_file_from_local'] );
        }
        if ( !empty($_POST['remove_file_from_s3']) ) {
            $options[UPCASTED_REMOVE_CLOUD_FILE] = sanitize_post( $_POST['remove_file_from_s3'] );
        }
        if ( !empty($_POST['access_key_id']) ) {
            $options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID] = CloudCredentialsEncryption::getInstance()->encrypt( sanitize_post( $_POST['access_key_id'] ) );
        }
        if ( !empty($_POST['secret_access_key']) ) {
            $options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY] = CloudCredentialsEncryption::getInstance()->encrypt( sanitize_post( $_POST['secret_access_key'] ) );
        }
        if ( !empty($_POST['region']) ) {
            $options[UPCASTED_OFFLOAD_REGION] = sanitize_post( $_POST['region'] );
        }
        if ( !empty($_POST['custom_endpoint']) ) {
            $options[UPCASTED_CUSTOM_ENDPOINT] = sanitize_post( $_POST['custom_endpoint'] );
        }
        $options[UPCASTED_S3_OFFLOAD_BUCKET] = $bucket;
        update_option( UPCASTED_S3_OFFLOAD_SETTINGS, $options );
        $this->bucket = $bucket;
        $this->cloudManipulatorBasePath = $this->cloudManipulator->get_base_path();
        Upcasted_S3_Offload_Init::getInstance()->define_admin_hooks();
    }
    
    private function upcasted_move_original_image( string $filePath )
    {
        
        if ( $this->cloudManipulator->check_object( $this->bucket, $filePath ) ) {
            $object = $this->cloudManipulator->get_object_from_bucket( $this->bucket, $filePath );
            file_put_contents( "{$this->uploadDir}/{$filePath}", $object['Body']->getContents() );
            if ( $this->removeFileFromCloudFlag ) {
                $this->cloudManipulator->delete( $this->bucket, $filePath );
            }
        }
    
    }
    
    private function upcasted_attachment_move_from_cloud_to_local( $fileId )
    {
        $fileAttachmentPath = get_attached_file( $fileId, true );
        $uploadDir = wp_get_upload_dir()['basedir'];
        $fileAttachmentPath = explode( $uploadDir . '/', $fileAttachmentPath )[1];
        
        if ( $this->cloudManipulator->check_object( $this->bucket, $fileAttachmentPath ) ) {
            $object = $this->cloudManipulator->get_object_from_bucket( $this->bucket, $fileAttachmentPath );
            file_put_contents( "{$this->uploadDir}/{$fileAttachmentPath}", $object['Body']->getContents() );
            if ( $this->removeFileFromCloudFlag ) {
                $this->cloudManipulator->delete( $this->bucket, $fileAttachmentPath );
            }
        }
    
    }
    
    /**
     * @param array $fileDetails
     * @param null $uploadedFiles
     */
    private function move_miniatures_from_local_to_cloud( array $fileDetails, &$uploadedFiles = null )
    {
        $fileDir = pathinfo( $fileDetails['file'], PATHINFO_DIRNAME );
        foreach ( $fileDetails['sizes'] as $miniaturePath ) {
            $cloudFilePath = "{$fileDir}/{$miniaturePath['file']}";
            $miniaturePath = "{$this->uploadDir}/{$cloudFilePath}";
            
            if ( is_file( $miniaturePath ) && filesize( $miniaturePath ) ) {
                $this->cloudManipulator->upload( $this->bucket, $cloudFilePath, $miniaturePath );
                if ( null !== $uploadedFiles ) {
                    $uploadedFiles++;
                }
            }
        
        }
    }
    
    /**
     * @param array $fileDetails
     * @param int|null $uploadedFiles
     */
    private function move_miniatures_from_cloud_to_local( array $fileDetails, &$uploadedFiles = null )
    {
        $fileDir = pathinfo( $fileDetails['file'], PATHINFO_DIRNAME );
        foreach ( $fileDetails['sizes'] as $miniature ) {
            $cloudFilePath = "{$fileDir}/{$miniature['file']}";
            
            if ( $this->cloudManipulator->check_object( $this->bucket, $cloudFilePath ) ) {
                $object = $this->cloudManipulator->get_object_from_bucket( $this->bucket, "{$fileDir}/{$miniature['file']}" );
                file_put_contents( "{$this->uploadDir}/{$fileDir}/{$miniature['file']}", $object['Body']->getContents() );
                if ( $this->removeFileFromCloudFlag ) {
                    $this->cloudManipulator->delete( $this->bucket, $cloudFilePath );
                }
                if ( null !== $uploadedFiles ) {
                    $uploadedFiles++;
                }
            }
        
        }
    }
    
    public function upcasted_calculate_image_srcset(
        array $sources,
        array $size_array,
        string $image_src,
        array $image_meta,
        int $attachment_id
    )
    {
        $metadata = wp_get_attachment_metadata( $attachment_id );
        if ( is_array( $metadata ) && array_key_exists( 'bucket', $metadata ) ) {
            foreach ( $sources as $size => $source ) {
                $basedir = explode( wp_get_upload_dir()['basedir'], pathinfo( get_attached_file( $attachment_id ) )['dirname'] )[1];
                $filename = pathinfo( $source['url'] )['basename'];
                $sources[$size]['url'] = "{$this->cloudManipulatorBasePath}{$basedir}/{$filename}";
            }
        }
        return $sources;
    }
    
    public function upcasted_image_make_intermediate_size( string $attachment_path ) : string
    {
        
        if ( isset( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] ) && is_array( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] ) ) {
            $accepted_mimes = get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES];
        } else {
            $accepted_mimes = get_allowed_mime_types();
        }
        
        $attachment_mime = mime_content_type( $attachment_path );
        
        if ( in_array( $attachment_mime, $accepted_mimes ) ) {
            $this->upcasted_upload_s3( $attachment_path );
            return "{$this->cloudManipulatorBasePath}/{$this->upcasted_get_uploaded_image_path( $attachment_path )}";
        }
        
        return $attachment_path;
    }
    
    private function upcasted_upload_s3( string $attachment_path )
    {
        $relative_path = $this->upcasted_get_uploaded_image_path( $attachment_path );
        if ( !empty(get_option( UPCASTED_S3_OFFLOAD_SETTINGS )) && isset( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_BUCKET] ) && null !== $relative_path ) {
            $this->cloudManipulator->upload( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_BUCKET], $relative_path, $attachment_path );
        }
    }
    
    private function upcasted_get_uploaded_image_path( $full_path )
    {
        $path = explode( $this->uploadDir . '/', $full_path );
        return ( isset( $path[1] ) ? $path[1] : null );
    }
    
    public function upcasted_get_attached_file( $file, $attachment_id ) : string
    {
        $attachment_details = wp_get_attachment_metadata( $attachment_id );
        
        if ( is_array( $attachment_details ) && array_key_exists( 'bucket', $attachment_details ) && array_key_exists( 'file', $attachment_details ) ) {
            $action = ( filter_input( INPUT_GET, 'action' ) ?: filter_input( INPUT_POST, 'action' ) );
            $newPath = wp_get_upload_dir()['basedir'] . '/' . $attachment_details['file'];
            if ( in_array( $action, array( 'image-editor', 'imgedit-preview' ) ) ) {
                // input var okay
                foreach ( debug_backtrace() as $caller ) {
                    
                    if ( isset( $caller['function'] ) && '_load_image_to_edit_path' == $caller['function'] ) {
                        $object = $this->cloudManipulator->get_object_from_bucket( $this->bucket, $attachment_details['file'] );
                        
                        if ( file_put_contents( $newPath, $object['Body']->getContents() ) ) {
                            $this->tempFilesToCleanup[] = $newPath;
                            return $newPath;
                        }
                    
                    }
                
                }
            }
        }
        
        return $file;
    }
    
    /**
     * @param $data
     * @param $post_id
     *
     * @return mixed
     */
    public function upcasted_update_attachment_metadata( $data, $post_id )
    {
        foreach ( $this->tempFilesToCleanup as $key => $path ) {
            
            if ( !empty($path) ) {
                wp_delete_file( $path );
            } else {
                unset( $this->tempFilesToCleanup[$key] );
            }
        
        }
        return $data;
    }
    
    /**
     * @param $path
     * @param $attachment_id
     *
     * @return string
     */
    public function upcasted_get_attachment_url( $path, $attachment_id ) : string
    {
        $metadata = wp_get_attachment_metadata( $attachment_id );
        
        if ( is_array( $metadata ) && array_key_exists( 'bucket', $metadata ) && array_key_exists( 'file', $metadata ) ) {
            return "{$this->cloudManipulatorBasePath}/{$metadata['file']}";
        } else {
            if ( is_array( $metadata ) && array_key_exists( 'bucket', $metadata ) ) {
                return "{$this->cloudManipulatorBasePath}" . str_replace( wp_get_upload_dir()['basedir'], '', get_attached_file( $attachment_id, true ) );
            }
        }
        
        return $path;
    }
    
    /**
     * @param $attachment_id
     * @return bool
     */
    public function upcasted_check_mime_type( $attachment_id )
    {
        
        if ( isset( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] ) && is_array( get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] ) && is_array( get_post_mime_type( $attachment_id ) ) ) {
            $mimeTypes = get_option( UPCASTED_S3_OFFLOAD_SETTINGS )[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES];
        } else {
            $mimeTypes = get_allowed_mime_types();
        }
        
        return $mimeTypes;
    }
    
    /**
     * @param $metadata
     * @param $attachment_id
     *
     * @return mixed
     */
    public function upcasted_generate_attachment_metadata( $metadata, $attachment_id )
    {
        
        if ( $this->upcasted_check_mime_type( $attachment_id ) ) {
            $metadata['bucket'] = $this->bucket;
            
            if ( isset( $metadata['original_image'] ) ) {
                $path = wp_get_original_image_path( $attachment_id );
            } else {
                $path = get_attached_file( $attachment_id );
            }
            
            $this->upcasted_upload_s3( $path );
        }
        
        return $metadata;
    }
    
    /**
     * @param $file_name
     *
     * @return string
     */
    public function upcasted_append_duplicate_name( $file_name ) : string
    {
        
        if ( !empty($file_name) ) {
            $upload_dir = wp_upload_dir();
            $info = pathinfo( $file_name );
            $extension = ( isset( $info['extension'] ) ? '.' . $info['extension'] : '' );
            $relative_path = ltrim( $upload_dir['subdir'], '/' ) . '/';
            if ( false === $this->cloudManipulator->check_object( $this->bucket, $relative_path . $file_name ) ) {
                return $file_name;
            }
            $increment = 1;
            while ( false !== $this->cloudManipulator->check_object( $this->bucket, $relative_path . $this->upcasted_get_new_file_name( $file_name, $extension, $increment ) ) ) {
                ++$increment;
            }
            return $this->upcasted_get_new_file_name( $file_name, $extension, $increment );
        }
        
        return '';
    }
    
    /**
     * @param $post_id
     */
    public function upcasted_delete_attachment( $post_id )
    {
        $metadata = wp_get_attachment_metadata( $post_id );
        
        if ( !empty($metadata) && array_key_exists( 'bucket', $metadata ) && array_key_exists( 'file', $metadata ) ) {
            $backup_sizes = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );
            $thumbnails = ( isset( $backup_sizes ) && is_array( $backup_sizes ) && array_key_exists( 'sizes', $metadata ) ? array_merge( $backup_sizes, $metadata['sizes'] ) : $metadata['sizes'] );
            if ( $thumbnails !== null ) {
                foreach ( $thumbnails as $thumbnailSize => $thumbnail ) {
                    $thumbnail_path = ( isset( explode( $this->cloudManipulatorBasePath . '/', wp_get_attachment_image_src( $post_id, $thumbnailSize )[0] )[1] ) ? explode( $this->cloudManipulatorBasePath . '/', wp_get_attachment_image_src( $post_id, $thumbnailSize )[0] )[1] : '' );
                    $this->cloudManipulator->delete( $this->bucket, $thumbnail_path );
                }
            }
            $this->cloudManipulator->delete( $this->bucket, $metadata['file'] );
        } elseif ( array_key_exists( 'bucket', $metadata ) ) {
            $s3FilePath = str_replace( wp_get_upload_dir()['basedir'] . '/', '', get_attached_file( $post_id, true ) );
            $this->cloudManipulator->delete( $this->bucket, $s3FilePath );
        }
    
    }
    
    /**
     * @param $fileName
     * @param $extension
     * @param $increment
     *
     * @return string
     */
    private function upcasted_get_new_file_name( $fileName, $extension, $increment )
    {
        return basename( $fileName, $extension ) . '-' . $increment . $extension;
    }
    
    /**
     * @param string $metaCompare
     * @return int
     */
    public function upcasted_get_number_of_files( string $metaCompare ) : int
    {
        return CloudRepository::getAttachments( $metaCompare )->post_count;
    }
    
    private function resetHooks()
    {
        Upcasted_S3_Offload_Init::getInstance()->define_admin_hooks();
    }

}