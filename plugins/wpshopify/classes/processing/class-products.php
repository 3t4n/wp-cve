<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Server;

class Products extends \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_products';

    protected $DB_Settings_Syncing;
    protected $DB_Settings_General;
    protected $DB_Products;
    protected $compatible_charset;
    protected $CPT_Model;

    public function __construct(
        $DB_Settings_Syncing,
        $DB_Settings_General,
        $DB_Products,
        $CPT_Model
    ) {
        $this->DB = $DB_Settings_Syncing; // used only for readability
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Products = $DB_Products;
        $this->CPT_Model = $CPT_Model;
        $this->compatible_charsets = true;

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
    protected function task($product)
    {
        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $product;
        }

        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        if (empty($product)) {
            return false;
        }


        $product_id = Utils::convert_storefront_id_to_numeric($product->id);

        $post_id = $this->DB_Products->get_post_id_by_product_id($product_id);


        if (empty($post_id)) {
            $post_id = false;
            
        } else {
            $post_id = $post_id[0];
        }

        $new_post_id = $this->CPT_Model->insert_or_update_product_post($product, $product_id, $post_id);

        if (is_wp_error($new_post_id)) {
            $this->DB_Settings_Syncing->save_notice_and_expire_sync($new_post_id);
            $this->complete();
        }

        return false;
    }

    /*

	Used to ensure proper table encoding before inserting

	*/
    protected function before_queue_item_save($items)
    {
        return $this->DB->encode_data(json_encode($items));
    }

    /*

	Used to increment the syncing current amounts

	*/
    protected function after_queue_item_removal($product)
    {
        $this->DB_Settings_Syncing->increment_current_amount('products');
    }
}
