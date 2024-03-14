<?php

namespace ShopWP\Processing;

use ShopWP\Utils;
use ShopWP\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class Database extends \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_deletions';

    protected $Config;
    protected $DB_Collections_Custom;
    protected $DB_Collections_Smart;
    protected $DB_Collects;
    protected $DB_Images;
    protected $DB_Options;
    protected $DB_Products;
    protected $DB_Settings_Connection;
    protected $DB_Settings_General;
    protected $DB_Settings_License;
    protected $DB_Settings_Syncing;
    protected $DB_Tags;
    protected $DB_Variants;
    protected $Transients;
    protected $DB_Posts;
    protected $API_Settings_License;
    protected $Compatibility;

    public function __construct(
        $Config,
        $DB_Collections_Custom,
        $DB_Collections_Smart,
        $DB_Collects,
        $DB_Images,
        $DB_Options,
        $DB_Products,
        $DB_Settings_Connection,
        $DB_Settings_General,
        $DB_Settings_License,
        $DB_Settings_Syncing,
        $DB_Tags,
        $DB_Variants,
        $Transients,
        $DB_Posts,
        $API_Settings_License,
        $Compatibility
    ) {
        $this->Config = $Config;
        $this->DB_Collections_Custom = $DB_Collections_Custom;
        $this->DB_Collections_Smart = $DB_Collections_Smart;
        $this->DB_Collects = $DB_Collects;
        $this->DB_Images = $DB_Images;
        $this->DB_Options = $DB_Options;
        $this->DB_Products = $DB_Products;
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_License = $DB_Settings_License;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Tags = $DB_Tags;
        $this->DB_Variants = $DB_Variants;
        $this->Transients = $Transients;

        $this->DB_Posts = $DB_Posts;
        $this->API_Settings_License = $API_Settings_License;

        $this->DB = $DB_Variants; // Convenience

        $this->Compatibility = $Compatibility;

        $this->total = $this->DB_Settings_Syncing->get_col_val('syncing_totals_removal', 'int');
        $this->current_total = 0;

        parent::__construct($DB_Settings_Syncing);
    }

    /*

	When uninstalling the plugin

	*/
    public function uninstall_plugin()
    {
        $results = [];

        if (
            Utils::is_plugin_installed(SHOPWP_FREE_BASENAME) &&
            Utils::is_plugin_installed(SHOPWP_PRO_BASENAME)
        ) {
            return $results;
        }

        $results['delete_posts'] = $this->delete_posts();
        $results['delete_default_pages'] = $this->delete_default_pages();
        $results['drop_custom_tables'] = $this->drop_custom_tables();
        $results['delete_media'] = $this->DB_Images->delete_media();
        $results['delete_custom_options'] = Transients::delete_custom_options();
        $results['delete_plugin_cache'] = Transients::delete_plugin_cache();
        $results[
            'delete_compatibility'
        ] = $this->Compatibility->delete_compatibility_mu();

        return $results;
    }

    /*

	When uninstalling the plugin

	*/
    public function uninstall_plugin_multisite()
    {
        $results = [];
        $blog_ids = \get_sites();

        foreach ($blog_ids as $site_blog_id) {
            switch_to_blog($site_blog_id);

            $results['blog_' . $site_blog_id] = $this->uninstall_plugin();

            restore_current_blog();
        }

        return $results;
    }

    /*

	Drop custom tables

	Tested

	NOT USING BACKGROUND PROCESS

	*/
    public function drop_custom_tables()
    {
        $results = [];

        $results[
            'settings_general'
        ] = $this->DB_Settings_General->delete_table();
        $results[
            'settings_license'
        ] = $this->DB_Settings_License->delete_table();
        $results[
            'settings_connection'
        ] = $this->DB_Settings_Connection->delete_table();
        $results[
            'settings_syncing'
        ] = $this->DB_Settings_Syncing->delete_table();
        $results[
            'collections_smart'
        ] = $this->DB_Collections_Smart->delete_table();
        $results[
            'collections_custom'
        ] = $this->DB_Collections_Custom->delete_table();
        $results['products'] = $this->DB_Products->delete_table();
        $results['variants'] = $this->DB_Variants->delete_table();
        $results['options'] = $this->DB_Options->delete_table();
        $results['tags'] = $this->DB_Tags->delete_table();
        $results['collects'] = $this->DB_Collects->delete_table();
        $results['images'] = $this->DB_Images->delete_table();

        return Utils::return_only_error_messages(
            Utils::return_only_errors($results)
        );
    }

    /*

	Drop databases used during uninstall

	Tested

	*/
    public function delete_posts()
    {
        return $this->DB_Posts->delete_posts();
    }

    public function delete_default_pages()
    {
        $results = [];

        $default_products_page_id = $this->DB_Settings_General->get_col_val(
            'page_products_default',
            'int'
        );
        $default_collections_page_id = $this->DB_Settings_General->get_col_val(
            'page_collections_default',
            'int'
        );

        if ($default_products_page_id) {
            $results['default_products_page'] = wp_delete_post(
                $default_products_page_id,
                true
            );
        }

        if ($default_collections_page_id) {
            $results['default_collections_page'] = wp_delete_post(
                $default_collections_page_id,
                true
            );
        }

        return $results;
    }

    /*

	Deletes both synced data AND custom post types but no:

	- Connection data
	- License data

	*/
    public function delete_posts_and_synced_data()
    {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Removing old data ...');
        $this->DB_Settings_Syncing->set_syncing_totals_removal(12);

        $this->push_to_queue('DB_Posts');
        $this->push_to_queue('DB_Media');
        $this->push_to_queue('DB_Settings_Syncing');
        $this->push_to_queue('DB_Collections_Custom');
        $this->push_to_queue('DB_Collections_Smart');
        $this->push_to_queue('DB_Collects');
        $this->push_to_queue('DB_Images');
        $this->push_to_queue('DB_Options');
        $this->push_to_queue('DB_Products');
        $this->push_to_queue('DB_Tags');
        $this->push_to_queue('DB_Variants');
        $this->push_to_queue('Transients');

        return $this->save()->dispatch();
    }

    /*

	Deletes only synced Shopify data. Keeps custom post types, license, etc.

	*/
    public function delete_only_synced_data()
    {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Removing old data ...');
        $this->DB_Settings_Syncing->set_syncing_totals_removal(9);

        $selective_sync = $this->DB_Settings_General->selective_sync_status();

        if ($selective_sync['products'] === 1 || $selective_sync['all'] === 1) {
            $this->push_to_queue('DB_Products');
            $this->push_to_queue('DB_Variants');
            $this->push_to_queue('DB_Tags');
            $this->push_to_queue('DB_Collects');
            $this->push_to_queue('DB_Images');
            $this->push_to_queue('DB_Options');
        }

        if (
            $selective_sync['smart_collections'] === 1 ||
            $selective_sync['all'] === 1
        ) {
            $this->push_to_queue('DB_Collections_Smart');
        }

        if (
            $selective_sync['custom_collections'] === 1 ||
            $selective_sync['all'] === 1
        ) {
            $this->push_to_queue('DB_Collections_Custom');
        }

        $this->push_to_queue('Transients');

        return $this->save()->dispatch();
    }

    private function get_current_table_name($class_object) {
        return str_replace('wps ', '', str_replace('_', ' ', $class_object->table_name_suffix));
    }

    /*

	Override this method to perform any actions required during the async request.

	*/
    protected function task($object_name)
    {

        $this->current_total = $this->current_total + 1;

        if ($object_name !== 'DB_Media') {
            $class_object = $this->$object_name;
        } else {
            $class_object = 'DB_Media';
        }

        if ($class_object) {

            if ($object_name === 'DB_Posts') {
                $class_object->delete_posts();

            } elseif ($object_name === 'DB_Settings_Syncing') {
                $class_object->reset_syncing_current_amounts();

            } elseif ($object_name === 'Transients') {

                $this->DB_Settings_Syncing->increment_percent_completed_removal($this->total, $this->current_total);
                
                return false;

            } elseif ($object_name === 'DB_Images') {
                $class_object->truncate();

            } elseif ($object_name === 'DB_Media') {
                $this->DB_Images->delete_media();

            } else {
                $table_name = $this->get_current_table_name($class_object);
                $class_object->truncate();
            }
        }

        $this->DB_Settings_Syncing->increment_percent_completed_removal($this->total, $this->current_total);

        return false;
    }

    /*
   
   ** Important ** Tables are only updated when # of cols are different
   
   */
    public function should_update_cols($new_cols, $current_cols)
    {
        if (Utils::new_cols_greater_than_old($new_cols, $current_cols)) {
            return true;
        }

        if (Utils::new_cols_less_than_old($new_cols, $current_cols)) {
            return true;
        }

        return false;
    }

    /*

	Find the difference between tables in the database
	and tables in the database schema. Used during plugin updates
	to dynamically update the database.

	*/
    public function get_table_delta()
    {
        $tables = [];
        $final_delta = [];

        $tables[] = $this->DB_Products;
        $tables[] = $this->DB_Variants;
        $tables[] = $this->DB_Tags;
        $tables[] = $this->DB_Options;
        $tables[] = $this->DB_Images;
        $tables[] = $this->DB_Collects;
        $tables[] = $this->DB_Collections_Smart;
        $tables[] = $this->DB_Collections_Custom;
        $tables[] = $this->DB_Settings_License;
        $tables[] = $this->DB_Settings_Connection;
        $tables[] = $this->DB_Settings_General;
        $tables[] = $this->DB_Settings_Syncing;

        foreach ($tables as $key => $table) {

            // Contains full table name /w prefix
            $table_name = $table->get_table_name();
            $table_exists = $table->table_exists($table_name);

            if (is_wp_error($table_exists) || $table_exists === false) {
                
                // Create table since it doesn't exist
                $result = $table->create_table();

            } else {
                if ($this->should_update_cols($table->get_columns(), $table->get_columns_current($table_name))) {
                    $final_delta[$table_name] = $table;
                }
            }

        }

        return array_filter($final_delta);
    }

    /*

    Useful for creating new tables and updating existing tables to a new structure.
    Does NOT remove columns or delete tables.

    Requires that tables already exist.

    */
    public function sync_table_deltas()
    {

        // Next get all tables
        $tables = $this->get_table_delta();

        if (empty($tables)) {
            return;
        }

        require_once Utils::get_abs_admin_path('includes/upgrade.php');

        foreach ($tables as $table) {
            $results = \dbDelta($table->create_table_query($table->table_name));
        }

        return $results;
    }

    /*

	When the background process completes ...

	*/
    protected function complete()
    {
        parent::complete();

        // Important to call this after parent::complete since the latter resets the syncing table
        $this->DB_Settings_Syncing->set_finished_data_deletions(1);
    }
}
