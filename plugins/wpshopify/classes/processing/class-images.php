<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Server;

class Images extends \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_images';

    protected $DB_Settings_Syncing;
    protected $DB_Images;

    public function __construct($DB_Settings_Syncing, $DB_Images)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Images = $DB_Images;

        parent::__construct($DB_Settings_Syncing);
    }

    /*

	Entry point. Initial call before processing starts.

	*/
    public function process($items, $params = false)
    {
        if ($this->expired_from_server_issues($items, __METHOD__, __LINE__)) {
            return;
        }

        $this->dispatch_items($items);
    }

    /*

	Performs actions required for each item in the queue

	*/
    protected function task($item)
    {

        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $item;
        }

        // Actual work. Handles images for both collections and items
        if (isset($item->post_type) && $item->post_type === 'wps_collections') {

            if (isset($item->image)) {
                $item->image->image_id = $item->id;
                $item->image->collection_id = $item->id;
                $item->image->position = 1; // Can manually set since collections only have 1 photo

                $result = $this->DB_Images->insert_items_of_type([
                    $item->image
                ]);
            } else {
                return false;
            }

        } else {

            if (empty($item->images)) {
                return false;

            } else {
                $result = $this->DB_Images->insert_items_of_type($item->images);
            }
            
        }

        if (is_wp_error($result)) {

            $this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
            $this->complete();
            return false;
        }

        return false;
    }

    /*

	After an individual task item is removed from the queue

	*/
    protected function after_queue_item_removal($item)
    {
      if ($item->post_type !== 'wps_collections') {
         $this->DB_Settings_Syncing->increment_current_amount('products');
      }
    }
}
