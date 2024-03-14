<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Service\IOService as BaseIOService;

/**
 * Class IOService created on 29.06.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Service
 */
class IOService extends BaseIOService {

    /**
     * Retrieves all files within a directory recursively.
     *
     * @param string $directory The path of a directory.
     *
     * @return string[] An array of realpaths
     */
    public function find_files_in_directory( $directory ) {
        $files    = [];
        $iterator = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $directory, \RecursiveDirectoryIterator::SKIP_DOTS ) );
        foreach ( $iterator as $file_name => $file_object ) {
            $files[] = realpath( $file_name );
        }

        return $files;
    }

    /**
     * Creates a temporary file.
     *
     * @param string $template A file template.
     *
     * @return null|string
     * @see https://stackoverflow.com/a/8971248/3866583
     */
    function create_temp_file( $template ) {
        $attempts = 238328; // 62 x 62 x 62
        $letters  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length   = strlen( $letters ) - 1;

        if ( mb_strlen( $template ) < 6 || ! strstr( $template, '$HASH$' ) ) {
            return null;
        }

        for ( $count = 0; $count < $attempts; ++ $count ) {
            $random = '';

            for ( $p = 0; $p < 6; $p ++ ) {
                $random .= $letters[ mt_rand( 0, $length ) ];
            }

            $random_file = str_replace( '$HASH$', $random, $template );

            // Check if file can be written
            if ( ! ( $fd = @fopen( $random_file, 'x+' ) ) ) {
                continue;
            }
            @fclose( $fd );
            unlink( $random_file );

            return $random_file;
        }

        return null;
    }

    /**
     * @param string $dst_file The file to write the archive to.
     * @param string[] $filenames The files to archive.
     * @param string $prefix_to_remove The prefix to remove from each file.
     *
     * @return string The filename of the created compressed archive.
     * @see https://stackoverflow.com/a/20062628/3866583
     */
    public function create_tar_gz( $dst_file, array $filenames, $prefix_to_remove = '' ) {
        $tar = new \PharData( $dst_file );

        // Collect all files in the directory
        foreach ( $filenames as $filename ) {
            $local_name = str_replace( $prefix_to_remove, '', $filename );
            $tar->addFile( $filename, $local_name );
        }

        $tar->compress( \Phar::GZ );

        unlink( $dst_file );

        return $dst_file . '.gz';
    }

    /**
     * Writes some contents to a file.
     *
     * @param string $filename The filename to write the contents to.
     * @param string $contents The contents to write.
     *
     * @throws \Exception When the writing is not possible.
     */
    public function write_file_contents( $filename, $contents ) {
        $result = @file_put_contents( $filename, $contents );
        if ( $result === false ) {
            if ( @file_exists( $filename ) && ! @is_writable( $filename ) ) {
                /* translators: %s: Filename */
                $message = __( 'File is not writable: %s', 'baqend' );

                throw new \Exception( sprintf( $message, $filename ) );
            }

            /* translators: %s: Filename */
            $message = __( 'An unexpected error occurred while writing %s', 'baqend' );

            throw new \Exception( sprintf( $message, $filename ) );
        }
    }

    /**
     * Moves a file from A to B. If A cannot be removed, just copy A to B.
     *
     * @param string $old_filename The file to copy.
     * @param string $new_filename The location to copy the file to.
     *
     * @throws \Exception When moving and copying failed.
     */
    public function move_or_copy( $old_filename, $new_filename ) {
        if ( @rename( $old_filename, $new_filename ) !== true ) {
            $this->copy( $old_filename, $new_filename );
        }
    }

    /**
     * Copies a file from A to B.
     *
     * @param string $old_filename The file to copy.
     * @param string $new_filename The location to copy the file to.
     *
     * @throws \Exception When copying failed.
     */
    public function copy( $old_filename, $new_filename ) {
        if ( ! @copy( $old_filename, $new_filename ) ) {
            throw new \Exception( sprintf( 'Could not copy file from %s to %s', $old_filename, $new_filename ) );
        }
    }

    /**
     * Ensures a file exists. If it does not, it can be supplied by a callback.
     *
     * @param string $filename The file to be supplied.
     * @param callable $supplier The supplier to get the file's contents.
     *
     * @throws \Exception When writing the supplied file failed.
     */
    public function supply( $filename, callable $supplier ) {
        if ( ! @file_exists( $filename ) ) {
            $contents = $supplier();
            $this->write_file_contents( $filename, $contents );
        }
    }
}
