<?php

namespace ShopWP\API\Items;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;

class Media_Uploader extends \ShopWP\API
{
    public $Processing_Media_Uploader;
    public $Shopify_API;
    public $DB_Settings_Syncing;
    public $DB_Images;

    public function __construct(
        $Processing_Media_Uploader,
        $Shopify_API,
        $DB_Settings_Syncing,
        $DB_Images
    ) {
        $this->Processing_Media_Uploader = $Processing_Media_Uploader;
        $this->Shopify_API = $Shopify_API;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Images = $DB_Images;
    }

    public function maybe_increment_media_difference($images)
    {
        $gross_totals = $this->DB_Settings_Syncing->get_syncing_totals_media();
        $real_totals = count($images);

        if ($gross_totals > $real_totals) {
            $difference = $gross_totals - $real_totals;
            $this->DB_Settings_Syncing->increment_current_amount(
                'media',
                $difference
            );
        }
    }

    public function handle_media_upload($request)
    {
        $images = $this->DB_Images->get_all_images();

        $this->maybe_increment_media_difference($images);

        $this->handle_response([
            'response' => $images,
            'process_fns' => [$this->Processing_Media_Uploader],
        ]);
    }

    public function handle_media_counts($request)
    {
        return $this->handle_response([
            'response' => [
                'media' => $this->DB_Settings_Syncing->get_syncing_totals_media(),
            ],
        ]);
    }

    public function register_routes() {
        $this->api_route('/media/counts', 'POST', [$this, 'handle_media_counts']);
        $this->api_route('/media/upload', 'POST', [$this, 'handle_media_upload']);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }
}
