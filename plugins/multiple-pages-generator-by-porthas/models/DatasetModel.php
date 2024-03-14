<?php

if (!defined('ABSPATH')) exit;

use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Box\Spout\Common\Type;

require_once(realpath(__DIR__ . '/../helpers/Constant.php'));

class MPG_DatasetModel
{

    public static function download_file($link, $destination_path)
    {
        try {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
            global $wp_filesystem;
            $content = $wp_filesystem->get_contents( $link );
            if ( ! empty( $content ) ) {
                // Make dir if not exists.
                $wp_filesystem->mkdir( MPG_UPLOADS_DIR, FS_CHMOD_DIR );
                // Update project source file.
                $updated = $wp_filesystem->put_contents( $destination_path, $content, FS_CHMOD_FILE );

                // File delete and re-fetch in case of the file is not writeable.
                if ( ! $updated ) {
                    $wp_filesystem->delete( $destination_path );
                    $wp_filesystem->put_contents( $destination_path, $content, FS_CHMOD_FILE );
                }
            }
            return true;
        } catch (Exception $e) {
            do_action( 'themeisle_log_event', MPG_NAME, $e->getMessage(), 'debug', __FILE__, __LINE__ );
            return $e->getMessage();
        }
    }


    public static function get_dataset_path_by_project_id($project_id)
    {

        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT source_path FROM {$wpdb->prefix}" .  MPG_Constant::MPG_PROJECTS_TABLE . " WHERE id=%d", $project_id)
        );
        if ( false === strpos( $results[0]->source_path, 'wp-content' ) ) {
            $results[0]->source_path = MPG_UPLOADS_DIR . $results[0]->source_path;
        }
        return $results[0]->source_path;
    }

    public static function mpg_read_dataset_hub()
    {
        $path_to_dataset_hub = plugin_dir_path(__DIR__) . 'temp/dataset_hub.xlsx';

        if ( ! wp_doing_ajax() ) {
            $download_result = MPG_DatasetModel::download_file(MPG_Constant::DATASET_SPREADSHEET_CSV_URL, $path_to_dataset_hub);

            if (!$download_result) {
                do_action( 'themeisle_log_event', MPG_NAME, sprintf( 'Unable to download hub data sheet %s', MPG_Constant::DATASET_SPREADSHEET_CSV_URL ), 'debug', __FILE__, __LINE__ );
                throw '';
            }
        }

        $reader = ReaderFactory::createFromType(Type::XLSX); // for XLSX files
        // Мы знаем, что датасет-хаб всегда будет xlsx;

        $reader->open($path_to_dataset_hub);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $row = $row->toArray();
                if ($row[0] !== NULL) {
                    $dataset_array[] = $row;
                }
            }
        }

        $reader->close();

        return $dataset_array;
    }
}