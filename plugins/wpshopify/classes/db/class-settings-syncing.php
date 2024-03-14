<?php

namespace ShopWP\DB;

use ShopWP\Utils;
use ShopWP\Transients;
use ShopWP\Options as Plugin_Options;

if (!defined('ABSPATH')) {
    exit();
}

class Settings_Syncing extends \ShopWP\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_primary_key_value;
    public $default_is_syncing;
    public $default_syncing_totals_smart_collections;
    public $default_syncing_totals_custom_collections;
    public $default_syncing_totals_products;
    public $default_syncing_totals_collects;
    public $default_syncing_totals_webhooks;
    public $default_syncing_totals_media;
    public $default_syncing_step_total;
    public $default_syncing_step_current;
    public $default_syncing_current_amounts_smart_collections;
    public $default_syncing_current_amounts_custom_collections;
    public $default_syncing_current_amounts_products;
    public $default_syncing_current_amounts_collects;
    public $default_syncing_current_amounts_webhooks;
    public $default_syncing_current_amounts_media;
    public $default_syncing_start_time;
    public $default_syncing_end_time;
    public $default_syncing_errors;
    public $default_syncing_warnings;
    public $default_finished_webhooks_deletions;
    public $default_finished_removing_connection;
    public $default_finished_media;
    public $default_finished_data_deletions;
    public $default_published_product_ids;
    public $default_recently_syncd_media_ref;
    public $default_current_syncing_step_text;
    public $default_percent_completed_removal;

    public function __construct()
    {
        global $wpdb;

        $this->table_name_suffix = SHOPWP_TABLE_NAME_SETTINGS_SYNCING;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'id';
        $this->cache_group = 'wps_db_syncing';
        $this->type = 'settings_syncing';

        $this->default_is_syncing = 0;
        $this->default_syncing_step_total = 0;
        $this->default_syncing_step_current = 0;
        $this->default_syncing_totals_smart_collections = 0;
        $this->default_syncing_totals_custom_collections = 0;
        $this->default_syncing_totals_products = 0;
        $this->default_syncing_totals_collects = 0;
        $this->default_syncing_totals_webhooks = 0;
        $this->default_syncing_totals_media = 0;
        $this->default_syncing_current_amounts_smart_collections = 0;
        $this->default_syncing_current_amounts_custom_collections = 0;
        $this->default_syncing_current_amounts_products = 0;
        $this->default_syncing_current_amounts_collects = 0;
        $this->default_syncing_current_amounts_webhooks = 0;
        $this->default_syncing_current_amounts_media = 0;
        $this->default_syncing_start_time = 0;
        $this->default_syncing_end_time = 0;
        $this->default_syncing_errors = null;
        $this->default_syncing_warnings = null;
        $this->default_finished_webhooks_deletions = 0;
        $this->default_finished_removing_connection = 0;
        $this->default_finished_media = 0;
        $this->default_finished_data_deletions = 0;
        $this->default_published_product_ids = '';
        $this->default_recently_syncd_media_ref = '';
        $this->default_current_syncing_step_text = 'Starting...';
        $this->default_percent_completed_removal = 0;
        $this->default_syncing_totals_removal = 0;

    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'is_syncing' => '%d',
            'syncing_step_total' => '%d',
            'syncing_step_current' => '%d',
            'syncing_totals_smart_collections' => '%d',
            'syncing_totals_custom_collections' => '%d',
            'syncing_totals_products' => '%d',
            'syncing_totals_collects' => '%d',
            'syncing_totals_webhooks' => '%d',
            'syncing_totals_media' => '%d',
            'syncing_current_amounts_smart_collections' => '%d',
            'syncing_current_amounts_custom_collections' => '%d',
            'syncing_current_amounts_products' => '%d',
            'syncing_current_amounts_collects' => '%d',
            'syncing_current_amounts_webhooks' => '%d',
            'syncing_current_amounts_media' => '%d',
            'syncing_start_time' => '%d',
            'syncing_end_time' => '%d',
            'syncing_errors' => '%s',
            'syncing_warnings' => '%s',
            'finished_webhooks_deletions' => '%d',
            'finished_removing_connection' => '%d',
            'finished_media' => '%d',
            'finished_data_deletions' => '%d',
            'published_product_ids' => '%s',
            'recently_syncd_media_ref' => '%s',
            'current_syncing_step_text' => '%s',
            'percent_completed_removal' => '%d',
            'syncing_totals_removal' => '%d'
        ];
    }

    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'syncing_totals_smart_collections',
            'syncing_totals_custom_collections',
            'syncing_totals_products',
            'syncing_totals_collects',
            'syncing_totals_webhooks',
            'syncing_totals_media',
            'syncing_step_total',
            'syncing_step_current',
            'syncing_current_amounts_smart_collections',
            'syncing_current_amounts_custom_collections',
            'syncing_current_amounts_products',
            'syncing_current_amounts_collects',
            'syncing_current_amounts_webhooks',
            'syncing_current_amounts_media',
            'syncing_start_time',
            'syncing_end_time',
            'percent_completed_removal',
            'syncing_totals_removal'
        ];
    }

    public function get_column_defaults()
    {
        return [
            'is_syncing' => $this->default_is_syncing,
            'syncing_totals_smart_collections' =>
                $this->default_syncing_totals_smart_collections,
            'syncing_totals_custom_collections' =>
                $this->default_syncing_totals_custom_collections,
            'syncing_totals_products' => $this->default_syncing_totals_products,
            'syncing_totals_collects' => $this->default_syncing_totals_collects,

            'syncing_totals_webhooks' => $this->default_syncing_totals_webhooks,
            'syncing_totals_media' => $this->default_syncing_totals_media,
            'syncing_step_total' => $this->default_syncing_step_total,
            'syncing_step_current' => $this->default_syncing_step_current,
            'syncing_current_amounts_smart_collections' =>
                $this->default_syncing_current_amounts_smart_collections,
            'syncing_current_amounts_custom_collections' =>
                $this->default_syncing_current_amounts_custom_collections,
            'syncing_current_amounts_products' =>
                $this->default_syncing_current_amounts_products,
            'syncing_current_amounts_collects' =>
                $this->default_syncing_current_amounts_collects,
            'syncing_current_amounts_webhooks' =>
                $this->default_syncing_current_amounts_webhooks,
            'syncing_current_amounts_media' =>
                $this->default_syncing_current_amounts_media,
            'syncing_start_time' => $this->default_syncing_start_time,
            'syncing_end_time' => $this->default_syncing_end_time,
            'syncing_errors' => $this->default_syncing_errors,
            'syncing_warnings' => $this->default_syncing_warnings,
            'finished_webhooks_deletions' =>
                $this->default_finished_webhooks_deletions,
            'finished_removing_connection' =>
            $this->default_finished_removing_connection,
            'finished_media' => $this->default_finished_media,
            'finished_data_deletions' => $this->default_finished_data_deletions,
            'published_product_ids' => $this->default_published_product_ids,
            'recently_syncd_media_ref' =>
                $this->default_recently_syncd_media_ref,
            'current_syncing_step_text' =>
                $this->default_current_syncing_step_text,
            'percent_completed_removal' =>
                $this->default_percent_completed_removal,
            'syncing_totals_removal' =>
                $this->default_syncing_totals_removal,
        ];
    }

    public function get_syncing_totals_smart_collections()
    {
        return $this->get_col_val('syncing_totals_smart_collections', 'int');
    }

    public function get_syncing_totals_products()
    {
        return $this->get_col_val('syncing_totals_products', 'int');
    }

    public function get_syncing_totals_collects()
    {
        return $this->get_col_val('syncing_totals_collects', 'int');
    }

    public function get_syncing_totals_webhooks()
    {
        return $this->get_col_val('syncing_totals_webhooks', 'int');
    }

    public function get_syncing_totals_media()
    {
        return $this->get_col_val('syncing_totals_media', 'int');
    }

    public function syncing_totals()
    {
        return [
            'smart_collections' => $this->get_syncing_totals_smart_collections(),
            'custom_collections' => $this->get_syncing_totals_custom_collections(),
            'products' => $this->get_syncing_totals_products(),
            'collects' => $this->get_syncing_totals_collects(),
        ];
    }

    public function set_syncing_totals($counts, $exclusions = [])
    {
        $counts_smart_collections = isset($counts['smart_collections'])
            ? $counts['smart_collections']
            : 0;
        $counts_custom_collections = isset($counts['custom_collections'])
            ? $counts['custom_collections']
            : 0;
        $counts_products = isset($counts['products']) ? $counts['products'] : 0;
        $counts_collects = isset($counts['collects']) ? $counts['collects'] : 0;

        $counts_webhooks = isset($counts['webhooks']) ? $counts['webhooks'] : 0;

        if ($exclusions) {
            $exclusions = array_flip($exclusions);
        }

        $smart_collections_totals =
            $exclusions && isset($exclusions['smart_collections'])
                ? 0
                : $counts_smart_collections;
        $custom_collections_totals =
            $exclusions && isset($exclusions['custom_collections'])
                ? 0
                : $counts_custom_collections;

        $products_count_total = $counts_products;

        $products_totals =
            $exclusions && isset($exclusions['products'])
                ? 0
                : $products_count_total;

        $collects_totals =
            $exclusions && isset($exclusions['collects'])
                ? 0
                : $counts_collects;

        $webhooks_totals =
            $exclusions && isset($exclusions['webhooks'])
                ? 0
                : $counts_webhooks;

        $operations = [
            'smart_collections' => $this->update_col('syncing_totals_smart_collections', $smart_collections_totals),
            'custom_collections' => $this->update_col('syncing_totals_custom_collections', $custom_collections_totals),
            'products' => $this->update_col('syncing_totals_products', $products_totals),
            'collects' => $this->update_col('syncing_totals_collects', $collects_totals),
        ];

        if (is_wp_error($operations['smart_collections'])) {
            return $operations['smart_collections'];
        }

        if (is_wp_error($operations['custom_collections'])) {
            return $operations['custom_collections'];
        }

        if (is_wp_error($operations['products'])) {
            return $operations['products'];
        }

        if (is_wp_error($operations['collects'])) {
            return $operations['collects'];
        }

        if (is_wp_error($operations['webhooks'])) {
            return $operations['webhooks'];
        }

        return $operations;
    }

    public function reset_syncing_totals()
    {
        return [
            'smart_collections' => $this->update_col('syncing_totals_smart_collections', 0),
            'custom_collections' => $this->update_col('syncing_totals_custom_collections', 0),
            'products' => $this->update_col('syncing_totals_products', 0),
            'collects' => $this->update_col('syncing_totals_collects', 0),
        ];
    }

    public function reset_syncing_timing()
    {
        return [
            'syncing_start_time' => $this->update_col('syncing_start_time', 0),
            'syncing_end_time' => $this->update_col('syncing_end_time', 0)
        ];
    }

    public function reset_syncing_published_product_ids()
    {
        return $this->update_col('published_product_ids', '');
    }

    public function reset_syncing_notices()
    {
        return [
            'syncing_errors' => $this->update_col('syncing_errors', false),
            'syncing_warnings' => $this->update_col('syncing_warnings', false),
        ];
    }

    public function get_syncing_errors()
    {
        $syncing_errors = $this->get_column_single('syncing_errors');

        if (
            Utils::array_not_empty($syncing_errors) &&
            isset($syncing_errors[0]->syncing_errors)
        ) {
            return $syncing_errors[0]->syncing_errors;
        } else {
            return false;
        }
    }

    public function get_syncing_warnings()
    {
        $syncing_warnings = $this->get_column_single('syncing_warnings');

        if (
            Utils::array_not_empty($syncing_warnings) &&
            isset($syncing_warnings[0]->syncing_warnings)
        ) {
            return $syncing_warnings[0]->syncing_warnings;
        } else {
            return false;
        }
    }

    public function syncing_notices()
    {
        return [
            'syncing_errors' => maybe_unserialize($this->get_syncing_errors()),
            'syncing_warnings' => maybe_unserialize(
                $this->get_syncing_warnings()
            ),
        ];
    }

    public function get_syncing_current_amounts_products()
    {
        return $this->get_col_val('syncing_current_amounts_products', 'int');
    }

    public function is_syncing_products()
    {
        $total_products = $this->get_syncing_totals_products();

        if ($total_products > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_syncing_collections()
    {
        $smart_collections = $this->get_syncing_totals_smart_collections();
        $custom_collections = $this->get_syncing_totals_custom_collections();

        if ($smart_collections > 0 || $custom_collections > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_syncing_current_amounts_smart_collections()
    {
        return $this->get_col_val(
            'syncing_current_amounts_smart_collections',
            'int'
        );
    }

    public function get_syncing_current_amounts_custom_collections()
    {
        return $this->get_col_val(
            'syncing_current_amounts_custom_collections',
            'int'
        );
    }

    public function get_syncing_current_amounts_collects()
    {
        return $this->get_col_val('syncing_current_amounts_collects', 'int');
    }

    public function get_syncing_current_amounts_webhooks()
    {
        return $this->get_col_val('syncing_current_amounts_webhooks', 'int');
    }

    public function get_syncing_current_amounts_media()
    {
        return $this->get_col_val('syncing_current_amounts_media', 'int');
    }

    public function syncing_current_amounts()
    {
        return [
            'smart_collections' => $this->get_syncing_current_amounts_smart_collections(),
            'custom_collections' => $this->get_syncing_current_amounts_custom_collections(),
            'products' => $this->get_syncing_current_amounts_products(),
            'collects' => $this->get_syncing_current_amounts_collects(),
        ];
    }

    public function reset_syncing_current_amounts()
    {
        return [
            'smart_collections' => $this->update_col('syncing_current_amounts_smart_collections', 0),
            'custom_collections' => $this->update_col('syncing_current_amounts_custom_collections', 0),
            'products' => $this->update_col('syncing_current_amounts_products', 0),
            'collects' => $this->update_col('syncing_current_amounts_collects', 0),
        ];
    }

    public function is_syncing()
    {
        $syncing_row = Utils::convert_array_to_object($this->get());

        if (!Utils::has($syncing_row, 'is_syncing')) {
            return false;
        }

        if ($syncing_row->is_syncing == 0 || $syncing_row->is_syncing == '0') {
            return false;
        } else {
            return true;
        }
    }

    public function toggle_syncing($syncing_status)
    {
        return $this->update_col('is_syncing', $syncing_status);
    }

    public function get_syncing_totals_custom_collections()
    {
        return $this->get_col_val('syncing_totals_custom_collections', 'int');
    }

    public function get_published_product_ids()
    {
        $published_product_ids = $this->get_column_single(
            'published_product_ids'
        );

        if (
            Utils::array_not_empty($published_product_ids) &&
            isset($published_product_ids[0]->published_product_ids)
        ) {
            $pub_ids = $published_product_ids[0]->published_product_ids;

            return maybe_unserialize($pub_ids);
        } else {
            return [];
        }
    }

    public function syncing_totals_smart_collections_actual()
    {
        $total_gross = $this->get_syncing_totals_smart_collections();
        return $total_gross / 2;
    }

    public function is_lite_sync()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wps_settings_general';

        return $this->get_column('is_lite_sync', 1, $table_name);
    }

    public function increment_current_amount($key, $amount_to_increment = false)
    {
        global $wpdb;

        if (!$amount_to_increment) {
            $amount_to_increment = 1;
        }

        $query = 'UPDATE ' . $this->table_name . ' SET syncing_current_amounts_' . $key . ' = syncing_current_amounts_' . $key . ' + ' . $amount_to_increment;

        return $wpdb->query($query);
    }


    public function set_finished_media($status)
    {
        return $this->update_col('finished_media', $status);
    }

    public function get_recently_syncd_media_ref()
    {
        $current_chunk_string = $this->get_col_val(
            'recently_syncd_media_ref',
            'string'
        );
        return maybe_unserialize($current_chunk_string);
    }

    public function get_current_syncing_step_text()
    {
        return $this->get_col_val('current_syncing_step_text', 'string');
    }

    public function increment_percent_completed_removal($total, $current_total)
    {

        if (!$total || $total === 0) {
            return $this->update_col('percent_completed_removal', 100);
        }

        $percent = ceil(($current_total / $total) * 100);

        return $this->update_col('percent_completed_removal', $percent);

    }

    public function set_current_syncing_step_text($text)
    {
        return $this->update_col('current_syncing_step_text', $text);
    }

    public function set_syncing_totals_removal($total)
    {
        return $this->update_col('syncing_totals_removal', $total);
    }

    public function update_recently_syncd_media_ref($media_chunk)
    {
        if (empty($media_chunk)) {
            return;
        }

        $current_chunk = $this->get_recently_syncd_media_ref();

        if ($current_chunk === '') {
            $current_chunk = [];
        }

        $current_chunk[] = $media_chunk;

        return $this->update_col('recently_syncd_media_ref', maybe_serialize($current_chunk));
    }

    public function reset_webhooks_deletions_status()
    {
        return $this->update_col('finished_webhooks_deletions', 0);
    }

    public function reset_data_deletions_status()
    {   
        return $this->update_col('finished_data_deletions', 0);
    }

    public function reset_finished_media()
    {
        return $this->update_col('finished_media', 0);
    }

    public function reset_syncing_step_text() {
        return $this->update_col('current_syncing_step_text', 'Starting...');
    }

    public function reset_syncing_product_ids() {
        return [
            'published_product_ids' => $this->reset_syncing_published_product_ids()
        ];
    }

    public function reset_syncing_refs()
    {
        return [
        ];
    }

    public function reset_all_syncing_totals()
    {
        return [
            $this->reset_syncing_current_amounts(),
            $this->reset_syncing_totals(),
            $this->reset_syncing_timing(),
            $this->reset_syncing_product_ids(),
        ];
    }

    public function reset_all_syncing_status()
    {
        return [
            $this->reset_webhooks_deletions_status(),
            $this->reset_data_deletions_status(),
            $this->reset_finished_media(),
            $this->reset_syncing_step_text(),
            $this->reset_remove_connection()
        ];
    }

    public function reset_remove_connection() {
        return $this->set_finished_removing_connection(0);
    }

    public function save_notice_and_expire_sync($WP_Error)
    {
        $this->save_notice($WP_Error);
        
        return $this->expire_sync();
    }

    public function expire_sync($toggle_syncing = 0)
    {
        return [
            'syncing_status' => $this->toggle_syncing($toggle_syncing),
            'reset_all_syncing_totals' => $this->reset_all_syncing_totals(),
            'reset_all_syncing_status' => $this->reset_all_syncing_status(),
            'delete_cache' => Transients::delete_plugin_cache()
        ];
    }

    public function prepare_notice_for_save(
        $current_notices,
        $error_message,
        $type
    ) {
        $current_notices = maybe_unserialize($current_notices);

        if (empty($current_notices)) {
            $current_notices = [];
        }

        $current_notices[$type][] = $error_message;

        return maybe_serialize($current_notices);
    }

    public function save_error($error_message)
    {
        $current_errors = $this->get_syncing_errors();

        $serialized_errors = $this->prepare_notice_for_save(
            $current_errors,
            $error_message,
            'error'
        );

        $saved_Results = $this->update_col('syncing_errors', $serialized_errors);

        return $saved_Results;
    }

    public function save_warning($error_message)
    {
        $current_warnings = $this->get_syncing_warnings();
        $serialized_warnings = $this->prepare_notice_for_save(
            $current_warnings,
            $error_message,
            'warning'
        );

        return $this->update_col('syncing_warnings', $serialized_warnings);
    }

    public function save_notice($maybe_wp_error, $type = 'error')
    {
        if (!is_wp_error($maybe_wp_error)) {
            if ($type === 'error') {
                return $this->save_error($maybe_wp_error);
            } elseif ($type === 'warning') {
                return $this->save_warning($maybe_wp_error);
            } else {
                return $this->save_error($maybe_wp_error);
            }
        }

        $error_message = $maybe_wp_error->get_error_message();
        $type = $maybe_wp_error->get_error_code();

        if ($error_message) {
            if ($type === 'error') {
                return $this->save_error($error_message);
            } elseif ($type === 'warning') {
                return $this->save_warning($error_message);
            } else {
                return $this->save_error($error_message);
            }
        }
    }

    public function set_finished_webhooks_deletions($status)
    {
        return $this->update_col('finished_webhooks_deletions', $status);
    }


    public function set_finished_removing_connection($status)
    {
        return $this->update_col('finished_removing_connection', $status);
    }

    public function set_finished_data_deletions($status)
    {
        return $this->update_col('finished_data_deletions', $status);
    }

    public function maybe_save_warning_from_insert($result, $type, $identifier)
    {
        if ($result === false) {
            $this->save_warning('Unable to sync ' . $type . ': ' . $identifier);
        }
    }

    public function init()
    {
        return $this->init_table_defaults();
    }

    public function init_table_defaults()
    {
        $results = [];

        if (!$this->table_has_been_initialized('id')) {
            $results = $this->insert_default_values();
        }

        return $results;
    }

    public function api_error($error_message, $method, $line)
    {

        $this->save_error($error_message);

        $final_wp_error_obj = Utils::wp_error([
            'message_lookup'    => $error_message,
            'call_method'       => $method,
            'call_line'         => $line,
        ]);

        return wp_send_json_error($final_wp_error_obj);

    }

    public function server_error($error_message, $method, $line)
    {

        $final_error_obj = Utils::wp_error([
            'message_lookup'    => $error_message,
            'call_method'       => $method,
            'call_line'         => $line,
        ]);
        
        $this->save_notice_and_expire_sync($final_error_obj);

        return $final_error_obj;
        
    }

    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			is_syncing tinyint(1) DEFAULT '{$this->default_is_syncing}',
			syncing_totals_smart_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_smart_collections}',
			syncing_totals_custom_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_custom_collections}',
			syncing_totals_products bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_products}',
			syncing_totals_collects bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_collects}',
			syncing_totals_webhooks bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_webhooks}',
            syncing_totals_media bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_media}',
			syncing_step_total bigint(100) unsigned DEFAULT  '{$this->default_syncing_step_total}',
			syncing_step_current bigint(100) unsigned DEFAULT '{$this->default_syncing_step_current}',
			syncing_current_amounts_smart_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_smart_collections}',
			syncing_current_amounts_custom_collections bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_custom_collections}',
			syncing_current_amounts_products bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_products}',
			syncing_current_amounts_collects bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_collects}',
			syncing_current_amounts_webhooks bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_webhooks}',
            syncing_current_amounts_media bigint(100) unsigned DEFAULT '{$this->default_syncing_current_amounts_media}',
			syncing_start_time bigint(100) unsigned DEFAULT '{$this->default_syncing_start_time}',
			syncing_end_time bigint(100) unsigned DEFAULT '{$this->default_syncing_end_time}',
			syncing_errors LONGTEXT DEFAULT '{$this->default_syncing_errors}',
			syncing_warnings LONGTEXT DEFAULT '{$this->default_syncing_warnings}',
			finished_webhooks_deletions tinyint(1) DEFAULT '{$this->default_finished_webhooks_deletions}',
            finished_removing_connection tinyint(1) DEFAULT '{$this->default_finished_removing_connection}',
            finished_media tinyint(1) DEFAULT '{$this->default_finished_media}',
			finished_data_deletions tinyint(1) DEFAULT '{$this->default_finished_data_deletions}',
			published_product_ids longtext DEFAULT '{$this->default_published_product_ids}',
            recently_syncd_media_ref longtext DEFAULT '{$this->default_recently_syncd_media_ref}',
            current_syncing_step_text varchar(255) DEFAULT '{$this->default_current_syncing_step_text}',
            percent_completed_removal bigint(100) unsigned DEFAULT '{$this->default_percent_completed_removal}',
            syncing_totals_removal bigint(100) unsigned DEFAULT '{$this->default_syncing_totals_removal}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}
