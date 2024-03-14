<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Server;

class Collections_Smart_Collects extends
    \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_smart_collections_collects';

    public function __construct(
        $DB_Settings_Syncing,
        $DB_Collects,
        $Shopify_API,
        $DB_Settings_General
    ) {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Collects = $DB_Collects;
        $this->Shopify_API = $Shopify_API;
        $this->DB_Settings_General = $DB_Settings_General;

        parent::__construct($DB_Settings_Syncing);
    }

    public function create_collect_id($product_id, $collection_id)
    {
        return (string) $product_id . (string) $collection_id;
    }

    public function get_products_from(
        $collection_id,
        $limit,
        $newCollects = [],
        $page_link = false
    ) {
      //   if (!$this->DB_Settings_Syncing->is_syncing()) {
      //       $this->complete();
      //       return false;
      //   }

        $response = $this->Shopify_API->get_products_from_collection(
            $collection_id,
            $limit,
            $page_link
        );

        if (is_wp_error($response)) {
            return $newCollects;
        }

        // No additional pages left
        if (!$response) {
            return $newCollects;
        }

        $resp = $this->Shopify_API->sanitize_response($response['body']);

        foreach ($resp->products as $product) {
            $newCollects[] = [
                'collect_id' => $this->create_collect_id(
                    $product->id,
                    $collection_id
                ),
                'product_id' => $product->id,
                'collection_id' => $collection_id,
            ];
        }

        if (!$this->Shopify_API->has_pagination($response)) {
            return $newCollects;
        }

        $new_link = $this->Shopify_API->get_pagination_link($response);

        return $this->get_products_from(
            $collection_id,
            $limit,
            $newCollects,
            $new_link
        );
    }

    public function get_sync_by_collections()
    {
        return maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );
    }

    public function get_only_collection_ids($smart_collections)
    {
        return array_map(function ($collection) {
            return $collection->id;
        }, $smart_collections);
    }

    public function filter_collections_by_seleective_sync(
        $collection_ids,
        $sync_by_collections
    ) {
        if (!empty($sync_by_collections)) {
            $collection_ids = array_values(
                array_filter($collection_ids, function ($collection_id) use (
                    $sync_by_collections
                ) {
                    return in_array(
                        $collection_id,
                        array_column($sync_by_collections, 'id')
                    );
                })
            );

            if (empty($collection_ids)) {
                return false;
            }
        }

        return $collection_ids;
    }

    public function fetch_and_insert_smart_collects($smart_collections)
    {
        $sync_by_collections = $this->get_sync_by_collections();

        $collection_ids = $this->get_only_collection_ids($smart_collections);

        $final_collection_ids = $this->filter_collections_by_seleective_sync(
            $collection_ids,
            $sync_by_collections
        );

        $this->dispatch_items($final_collection_ids);
    }

    /*

	Entry point. Initial call before processing starts.

	*/
    public function process($smart_collections)
    {
        if (
            $this->expired_from_server_issues(
                $smart_collections,
                __METHOD__,
                __LINE__
            )
        ) {
            return;
        }

        $this->fetch_and_insert_smart_collects($smart_collections);
    }

    /*

	Performs actions required for each item in the queue

	*/
    protected function task($collection_id)
    {

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $collection_id;
        }

        $limit = $this->DB_Settings_General->get_items_per_request();

        $collects = $this->get_products_from($collection_id, $limit);
        

        $insertion_results = [];

        if (empty($collects)) {
            return false;
        }

        foreach ($collects as $collect) {
            $insertion_results[] = $this->DB_Collects->insert_collect($collect);
        }

        return false;
    }

    /*

	After an individual task item is removed from the queue

	*/
    //  protected function after_queue_item_removal($collect)
    //  {
    //      //   $this->DB_Settings_Syncing->increment_current_amount('collects');
    //  }
}
