<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Baqend;
use Baqend\SDK\Exception\NeedsAuthorizationException;
use Baqend\SDK\Model\File;

/**
 * Class UploadService
 *
 * @package Baqend\WordPress\Service
 * @author Konstantin Simon Maria Möllers
 */
class UploadService {

    /**
     * @var Baqend
     */
    private $db;

    /**
     * @var IOService
     */
    private $IO_service;

    /**
     * UploadService constructor.
     *
     * @param Baqend $db
     * @param IOService $IO_service
     */
    public function __construct( Baqend $db, IOService $IO_service ) {
        $this->db         = $db;
        $this->IO_service = $IO_service;
    }

    /**
     * Cleans a bucket recursively.
     *
     * @param string $bucket
     * @param string $path
     *
     * @return bool
     */
    public function clean_bucket( $bucket, $path = '/' ) {
        try {
            $files = $this->db->file()->listFiles( $bucket, $path, '', - 1, true );
            foreach ( $files as $file ) {
                $this->db->file()->deleteFile( $file );
            }
        } catch ( NeedsAuthorizationException $e ) {
            return false;
        }

        return true;
    }

    /**
     * Builds a bucket dictionary recursively.
     *
     * @param string $bucket
     * @param string $path
     *
     * @return File[]
     * @throws NeedsAuthorizationException
     */
    public function get_bucket_dictionary( $bucket, $path = '/' ) {
        $out_files = [];
        $files     = $this->db->file()->listFiles( $bucket, $path, '', - 1, true );
        foreach ( $files as $file ) {
            $out_files[ $file->getId() ] = $file;
        }

        return $out_files;
    }

    /**
     * Uploads a directory to Baqend.
     *
     * @param string $directory
     * @param string $bucket
     *
     * @return bool|array[]
     * @throws NeedsAuthorizationException
     */
    public function find_files_to_upload_from_directory( $directory, $bucket ) {
        // Get all files within the bucket
        $remote_files = $this->get_bucket_dictionary( 'www' );

        // Collect all files in the directory
        $files      = $this->IO_service->find_files_in_directory( $directory );
        $file_count = count( $files );

        // Create file references out of the filenames
        $files_to_upload = array_map(
            [ $this, 'create_local_file_ref' ],
            $files,
            array_fill( 0, $file_count, $bucket ),
            array_fill( 0, $file_count, $directory )
        );

        // Filter all files which are already online
        $files_to_upload = array_filter( $files_to_upload, function ( array $file ) use ( $remote_files ) {
            // Retrieve remote file
            $remote_file = isset( $remote_files[ $file['id'] ] ) ? $remote_files[ $file['id'] ] : null;

            // When ETags match, don't upload the new file
            return null === $remote_file || $remote_file->getETag() !== $file['e_tag'];
        } );

        return $files_to_upload;
    }

    /**
     * Guesses the MIME type of a filename.
     *
     * @param string $filename
     *
     * @return string
     */
    public function guess_mime_type( $filename ) {
        $extension = substr( $filename, strrpos( $filename, '.' ) + 1 );
        switch ( $extension ) {
            case 'html':
            case 'shtml':
            case 'htm':
                return 'text/html';
            case 'css':
                return 'text/css';
            case 'js':
                return 'text/javascript';
            case 'txt':
                return 'text/plain';
            case 'png':
                return 'image/png';
            case 'jpeg':
            case 'jpg':
            case 'jpe':
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            default:
                return mime_content_type( $filename );
        }
    }

    /**
     * @param string[] $filenames
     * @param string $directory
     * @param string $bucket
     *
     * @throws NeedsAuthorizationException
     */
    public function upload_files_to_bucket( array $filenames, $directory, $bucket ) {
        // Create archive
        $tar_filename     = $this->IO_service->create_temp_file( sys_get_temp_dir() . '/baqend-$HASH$.tar' );
        $archive_filename = $this->IO_service->create_tar_gz( $tar_filename, $filenames, $directory );

        // Upload the archive
        $this->db->file()->uploadArchiveFile( $bucket, $archive_filename );

        // Remove the uploaded TAR/GZ
        unlink( $archive_filename );
    }

    /**
     * Creates a local file reference out of a file.
     *
     * @param string $filename Name of the file to create the reference of.
     * @param string $bucket The bucket to write the files to.
     * @param string $bucket_local_directory The directory where the bucket is located locally.
     *
     * @return array The file reference.
     */
    private function create_local_file_ref( $filename, $bucket, $bucket_local_directory ) {
        return [
            'id'             => str_replace( $bucket_local_directory, "/file/$bucket/", $filename ),
            'e_tag'          => $this->IO_service->calculateEntityTag( $filename ),
            'local_filename' => $filename,
        ];
    }
}
