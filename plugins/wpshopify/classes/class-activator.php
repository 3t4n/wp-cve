<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Options;
use ShopWP\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class Activator
{
    public $DB_Settings_Connection;
    public $DB_Settings_General;
    public $DB_Settings_License;
    public $DB_Products;
    public $DB_Variants;
    public $DB_Collects;
    public $DB_Options;
    public $DB_Collections_Custom;
    public $DB_Collections_Smart;
    public $DB_Images;
    public $DB_Tags;
    public $DB_Settings_Syncing;
    public $Async_Processing_Database;
    public $Compatibility;

    public function __construct(
        $DB_Settings_Connection,
        $DB_Settings_General,
        $DB_Settings_License,
        $DB_Products,
        $DB_Variants,
        $DB_Collects,
        $DB_Options,
        $DB_Collections_Custom,
        $DB_Collections_Smart,
        $DB_Images,
        $DB_Tags,
        $DB_Settings_Syncing,
        $Async_Processing_Database,
        $Compatibility
    ) {
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_License = $DB_Settings_License;
        $this->DB_Products = $DB_Products;
        $this->DB_Variants = $DB_Variants;
        $this->DB_Collects = $DB_Collects;
        $this->DB_Options = $DB_Options;
        $this->DB_Collections_Custom = $DB_Collections_Custom;
        $this->DB_Collections_Smart = $DB_Collections_Smart;
        $this->DB_Images = $DB_Images;
        $this->DB_Tags = $DB_Tags;
        $this->DB = $DB_Tags; // alias only

        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Async_Processing_Database = $Async_Processing_Database;
        $this->Compatibility = $Compatibility;
    }

    /*

	Create DB Tables

	*/
    public function create_db_tables()
    {
        $results = [];

        $results[
            'DB_Settings_Connection'
        ] = $this->DB_Settings_Connection->create_table();
        $results[
            'DB_Settings_General'
        ] = $this->DB_Settings_General->create_table();
        $results[
            'DB_Settings_License'
        ] = $this->DB_Settings_License->create_table();
        $results['DB_Products'] = $this->DB_Products->create_table();
        $results['DB_Variants'] = $this->DB_Variants->create_table();
        $results['DB_Collects'] = $this->DB_Collects->create_table();
        $results['DB_Options'] = $this->DB_Options->create_table();
        $results[
            'DB_Collections_Custom'
        ] = $this->DB_Collections_Custom->create_table();
        $results[
            'DB_Collections_Smart'
        ] = $this->DB_Collections_Smart->create_table();
        $results['DB_Images'] = $this->DB_Images->create_table();
        $results['DB_Tags'] = $this->DB_Tags->create_table();
        $results[
            'DB_Settings_Syncing'
        ] = $this->DB_Settings_Syncing->create_table();

        return $results;
    }

    /*

	Sets default plugin settings and inserts default rows

	*/
    public function set_default_table_values()
    {
        $results = [];

        $results['DB_Settings_General'] = $this->DB_Settings_General->init();
        $results['DB_Settings_Syncing'] = $this->DB_Settings_Syncing->init();

        return $results;
    }

    public function set_table_charset_cache()
    {
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Settings_Connection->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Settings_General->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Settings_License->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Settings_Syncing->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Products->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Variants->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Collects->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Options->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Collections_Custom->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Collections_Smart->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Images->get_table_name()
        );
        $this->DB_Settings_General->get_table_charset(
            $this->DB_Tags->get_table_name()
        );
    }

    public function bootstrap_tables()
    {
        $results = [];

        $results['create_db_tables'] = $this->create_db_tables();
        $results[
            'set_default_table_values'
        ] = $this->set_default_table_values();
        $results['set_table_charset_cache'] = $this->set_table_charset_cache();

        return $results;
    }

    public function get_ready()
    {
        $results = [];

        $results['bootstrap_wp_cache_flush'] = wp_cache_flush();

        $results[
            'bootstrap_transient_delete_all_cache'
        ] = Transients::delete_plugin_cache();

        // Builds the custom tables
        $results['bootstrap_tables'] = $this->bootstrap_tables();

        $results[
            'bootstrap_compatibility'
        ] = $this->Compatibility->make_compatibility_mu();

        $results[
            'bootstrap_default_pages'
        ] = $this->maybe_create_default_pages();

        $results[
            'flush_rewrite_rules'
        ] = update_option('shopwp_should_flush_rewrite_rules', 1);

        return $results;
    }

    public function bootstrap_blogs()
    {
        $blog_ids = \get_sites();
        $results = [];

        foreach ($blog_ids as $blog_id) {
            $results[] = $this->bootstrap_blog($blog_id);
        }

        return $results;
    }

    /*

	Runs when the plugin is activated as a result of register_activation_hook.

	Runs for both Free and Pro versions

	*/
    public function on_plugin_activate($network_wide)
    {
        if (is_multisite() && $network_wide) {
            return $this->bootstrap_blogs();
        }

        return $this->get_ready();
    }

    public function bootstrap_blog($blog_id)
    {
        switch_to_blog($blog_id);

        // Bootstraps tables, creates CPTs, and flushes rewrites
        $ready_result = $this->get_ready();

        restore_current_blog();

        return $ready_result;
    }

    public function create_default_products_page()
    {
        $default_page_title = 'ShopWP Products';
        $existing_page = \get_page_by_title($default_page_title);

        if (!empty($existing_page)) {
            return $existing_page->ID;
        }

        return \wp_insert_post([
            'post_title' => \wp_strip_all_tags($default_page_title),
            'post_status' => 'publish',
            'post_author' => \get_current_user_id(),
            'post_type' => 'page',
            'post_name' => 'products',
        ]);
    }

    public function create_default_collections_page()
    {
        $default_page_title = 'ShopWP Collections';
        $existing_page = \get_page_by_title($default_page_title);

        if (!empty($existing_page)) {
            return $existing_page->ID;
        }

        return \wp_insert_post([
            'post_title' => \wp_strip_all_tags($default_page_title),
            'post_status' => 'publish',
            'post_author' => \get_current_user_id(),
            'post_type' => 'page',
            'post_name' => 'collections',
        ]);
    }

    public function maybe_create_default_pages()
    {
        $settings = $this->DB_Settings_General->get_all_rows()[0];

        if (empty($settings)) {
            return [];
        }

        if ($settings->default_pages_created) {
            return [];
        }

        $created_successfully = false;

        $results = [
            'products' => $this->create_default_products_page(),
            'collections' => $this->create_default_collections_page(),
        ];

        if ($results['products'] && $results['collections']) {
            $created_successfully = true;
        }

        // Sets the flag even if default pages weren't created to prevent this running more than needed
        $this->DB_Settings_General->update_col('default_pages_created', true);

        if ($created_successfully) {
            if (
                !is_wp_error($results['products']) &&
                $results['products'] !== 0
            ) {
                $this->DB_Settings_General->update_col('page_products', $results['products']);
                $this->DB_Settings_General->update_col('page_products_default', $results['products']);
                $this->DB_Settings_General->update_col('url_products', get_permalink($results['products']));
            }

            if (
                !is_wp_error($results['collections']) &&
                $results['collections'] !== 0
            ) {
                $this->DB_Settings_General->update_col('page_collections', $results['collections']);
                $this->DB_Settings_General->update_col('page_collections_default', $results['collections']);
                $this->DB_Settings_General->update_col('url_collections', \get_permalink($results['collections']));
            }
        }

        return $results;
    }

    /*

	Runs when a new blog is created within a multi-site setup. NOT when activated network wide.

	*/
    public function on_blog_create(
        $blog_id,
        $user_id,
        $domain,
        $path,
        $site_id,
        $meta
    ) {
        if (Utils::is_network_wide()) {
            $this->bootstrap_blog($blog_id);
        }
    }

    public function on_blog_create_from_wp_site($blog)
    {
        if (is_object($blog) && isset($blog->blog_id)) {
            if (Utils::is_network_wide()) {
                $this->bootstrap_blog($blog->blog_id);
            }
        }
    }

    /*

	Deletes custom tables when blog is deleted

	$tables is an array containing a list of table names in string format

	*/
    public function on_blog_delete($tables)
    {
        $tables[] = $this->DB_Settings_Connection->get_table_name();
        $tables[] = $this->DB_Settings_General->get_table_name();
        $tables[] = $this->DB_Settings_License->get_table_name();
        $tables[] = $this->DB_Products->get_table_name();
        $tables[] = $this->DB_Variants->get_table_name();
        $tables[] = $this->DB_Collects->get_table_name();
        $tables[] = $this->DB_Options->get_table_name();
        $tables[] = $this->DB_Collections_Custom->get_table_name();
        $tables[] = $this->DB_Collections_Smart->get_table_name();
        $tables[] = $this->DB_Images->get_table_name();
        $tables[] = $this->DB_Tags->get_table_name();
        $tables[] = $this->DB_Settings_Syncing->get_table_name();

        return $tables;
    }

    public function is_dev_version($current_version_number)
    {
        return \version_compare($current_version_number, '10.0.0', '>=');
    }

    public function is_outdated($current, $new)
    {
        return \version_compare($current, $new, '<');
    }

    /*

     Runs when the plugin updates.

     Will only run once since we're updating the plugin version after everything gets executed.

     TODO: This functions gets executed many times.

     */
    public function on_plugins_loaded()
    {
        $new_version_number = SHOPWP_NEW_PLUGIN_VERSION;

        $current_version_number = $this->DB_Settings_General->get_col_val(
            'plugin_version',
            'string'
        );

        if (!$current_version_number) {
            return;
        }

        if (
            $this->is_dev_version($current_version_number) ||
            $this->is_outdated($current_version_number, $new_version_number)
        ) {

            $this->Async_Processing_Database->sync_table_deltas();

            if (\version_compare($current_version_number, '3.4.0', '<')) {
                $this->Compatibility->make_compatibility_mu();
            }

            $this->DB_Settings_General->update_plugin_version(
                $new_version_number
            );

            Transients::delete_plugin_cache();
            Options::delete('shopwp_migration_needed');
        }
    }

    public function on_upgrade()
    {
        if (get_option('has_upgraded_to_shop_pro')) {
            $this->get_ready();
            delete_option('has_upgraded_to_shop_pro');
        }

        $this->Compatibility->muplugin_version_check();
    }

    public function init()
    {
        global $wp_version;

        // plugins_loaded loads before activation before before init
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
        add_action('shopwp_on_plugin_activate', [$this, 'on_plugin_activate']);
        add_filter('wpmu_drop_tables', [$this, 'on_blog_delete']);
        add_action('admin_init', [$this, 'on_upgrade'], 1);

        if (is_multisite()) {
            if (version_compare($wp_version, 5.1, '<')) {
                add_action('wpmu_new_blog', [$this, 'on_blog_create'], 10, 6);
            } else {
                add_action('wp_initialize_site', [
                    $this,
                    'on_blog_create_from_wp_site',
                ]);
            }
        }
    }
}
