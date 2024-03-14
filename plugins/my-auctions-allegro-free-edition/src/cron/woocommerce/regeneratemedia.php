<?php
declare(strict_types=1);

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

require_once GJMAA_PATH_CODE . 'cron/abstract_cron.php';

class GJMAA_Cron_Woocommerce_Regeneratemedia extends GJMAA_Cron_Abstract
{
    public function getCode() : string
    {
        return 'gjmaa_cron_regenerate_media_metadata';
    }

    public function runJob() : void
    {
        /** @var GJMAA_Model_Attachments $attachments */
        $attachments = GJMAA::getModel('attachments');
        $toRegenerate = $attachments->getAll(30);

        if(empty($toRegenerate)) {
            return;
        }

        if ( ! function_exists('wp_generate_attachment_metadata')) {
            include_once(ABSPATH . 'wp-admin/includes/image.php');
        }

        foreach($toRegenerate as $attach)
        {
            try {
                $attachId        = $attach['attach_id'];
                $destinationPath = $attach['destination_path'];

                $attach_data = wp_generate_attachment_metadata($attachId, $destinationPath); // Generate the necessary attachment data, filesize, height, width etc.
                wp_update_attachment_metadata($attachId, $attach_data); // Add the above meta data data to our new image post

                $attachments->deleteById($attach['id']);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
            }
        }
    }

    public static function run()
    {
        (new self())->execute();
    }
}