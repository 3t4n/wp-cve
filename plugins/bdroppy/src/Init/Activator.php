<?php

namespace BDroppy\Init;

use BDroppy\CronJob\CronJob;
use BDroppy\Models\Catalog;
use BDroppy\Models\Log;
use BDroppy\Models\Order;
use BDroppy\Models\Product;
use BDroppy\Models\ProductModel;
use BDroppy\Models\Queue;
use BDroppy\Services\System\SystemInfo;
use BDroppy\Services\System\SystemLanguage;


if ( ! defined( 'ABSPATH' ) ) exit;

class Activator {

    public static function activate()
    {
        self::activationCheck();
        self::createDataBase();
        self::defaultOptions();
        CronJob::scheduleEvents();
        update_option( 'bdroppy_cron_lock', false );

        self::changeSetting();
        self::checkTable();
    }

    public static function checkTable()
    {
        global $wpdb;
        $products_table_name = $wpdb->prefix . Product::TABLE_NAME;
        $table = $wpdb->query("SHOW TABLES LIKE '$products_table_name';");

        if(!$table){
            update_option('bdroppy_db_version',0);
            deactivate_plugins('bdroppy/bdroppy.php',true,false );
            wp_redirect(get_admin_url(),301);
        }

    }

    public static function activationCheck()
    {
        if (
            !SystemInfo::getWcVersion(1) ||
            !SystemInfo::getWpVersion(1)
        ) {
            add_settings_error('title_long_error', '','BDroppy requires WordPress 5.5 and WooCommerce 4.3 or higher!','error');
            settings_errors( 'title_long_error' );
            deactivate_plugins( plugin_basename( __FILE__ ) );
            die();
        }
    }

    public static function changeSetting()
    {
        $api = get_option( 'dropshipping-api' );
        $catalog = get_option( 'dropshipping-catalog' );
        
        if($api)
        {
            update_option('bdroppy-api',$api);
            delete_option('dropshipping-api');
        }

        if($catalog)
        {
            update_option('bdroppy-catalog',$catalog);
            delete_option('dropshipping-catalog');
        }
    }

    public static function defaultOptions()
    {
        if (SystemLanguage::hasWpmlSupport()) {
            global $sitepress;
            $iclsettings['custom_posts_sync_option']['product'] = 1;
            $iclsettings['custom_posts_sync_option']['product_variation'] = 1;
            $iclsettings['taxonomies_sync_option']['product_cat'] = 1;
            $iclsettings['taxonomies_sync_option']['product_tag'] = 1;
            $sitepress->save_settings($iclsettings);
        }
    }

    public static function createDataBase()
    {
        global $wpdb;
        $wpdb->show_errors = false;
        $installed_ver   = get_option( 'bdroppy_db_version', 0 );
        $charset_collate = $wpdb->get_charset_collate();

        if ( $installed_ver < BDROPPY_DB_VERSION )
        {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $products_table_name = $wpdb->prefix . Product::TABLE_NAME;
            $table = $wpdb->query("SHOW TABLES LIKE '$products_table_name';");

            if($table)
            {
                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `last_update_at` TIMESTAMP NULL DEFAULT NULL ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `lang` VARCHAR(128) NULL DEFAULT NULL ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_base` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_images` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_categories` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_tags` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_attributes` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `import_models` INT(1) NOT NULL DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `parent` INT(10) UNSIGNED DEFAULT '0' ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD `create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                DROP INDEX `rewix_product_id`");

                $query = $wpdb->query("ALTER TABLE $products_table_name 
                ADD UNIQUE(`rewix_product_id`,`rewix_catalog_id`,`lang`)");

                $query = $wpdb->query("ALTER TABLE $products_table_name
                DROP `sync_status`, DROP `sync_message`, DROP `imported`, DROP `options`,DROP `last_sync_at`;");

            }else{
                dbDelta( "DROP TABLE $products_table_name;" );

                $sql = "CREATE TABLE $products_table_name (
				`id` INT(10) UNSIGNED AUTO_INCREMENT,
				`parent` INT(10) UNSIGNED DEFAULT '0',
				`rewix_product_id` INT(10) UNSIGNED NOT NULL,
				`rewix_catalog_id` VARCHAR(128) NOT NULL,
				`wc_product_id` INT(10) UNSIGNED NOT NULL,
				`import_base` INT(1) NOT NULL DEFAULT '0',
				`import_images` INT(1) NOT NULL DEFAULT '0',
				`import_categories` INT(1) NOT NULL DEFAULT '0',
				`import_tags` INT(1) NOT NULL DEFAULT '0',
				`import_attributes` INT(1) NOT NULL DEFAULT '0',
				`import_models` INT(1) NOT NULL DEFAULT '0',
                `last_update_at` TIMESTAMP NULL DEFAULT NULL,
				`lang` VARCHAR(128) NULL DEFAULT NULL,
				`create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE (`rewix_product_id`,`rewix_catalog_id`,`lang`),
				INDEX (`wc_product_id`)
			)ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;;";
                dbDelta( $sql );
                $wpdb->print_error();
            }


            $combination_table_name = $wpdb->prefix . ProductModel::$table;
            $table = $wpdb->query("SHOW TABLES LIKE '$combination_table_name';");
            if(!$table){
                dbDelta( "DROP TABLE $combination_table_name;" );

                $sql = "CREATE TABLE $combination_table_name (
				`id` INT(10) UNSIGNED AUTO_INCREMENT,
				`rewix_product_id` INT(10) UNSIGNED NOT NULL,
				`rewix_model_id` INT(10) UNSIGNED NOT NULL,
				`lang` VARCHAR(128) NOT NULL,
				`wc_model_id` INT(10) UNSIGNED NOT NULL,
				`wc_product_id` INT(10) UNSIGNED NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE (`rewix_model_id`, `wc_model_id`,`lang`),
				INDEX (`wc_model_id`),
				FOREIGN KEY(`rewix_product_id`) REFERENCES `$products_table_name`(`rewix_product_id`)
					ON DELETE CASCADE
			) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;;";
                dbDelta( $sql );
            }

            $order_table_name = $wpdb->prefix . Order::$table;
            $table = $wpdb->query("SHOW TABLES LIKE '$order_table_name';");
            if(!$table) {
                dbDelta("DROP TABLE $order_table_name;");

                $sql = "CREATE TABLE $order_table_name (
				`id` INT(10) UNSIGNED AUTO_INCREMENT,
				`rewix_order_key` VARCHAR(128) NOT NULL,
				`rewix_order_id` INT(10) UNSIGNED NOT NULL,
				`wc_order_id` INT(10) UNSIGNED NOT NULL,
				`status` VARCHAR(128) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE (`wc_order_id`, `rewix_order_id`,`rewix_order_key`)
			) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
                dbDelta($sql);
            }


            $catalogs_table_name = $wpdb->prefix .  Catalog::$table;
            $table = $wpdb->query("SHOW TABLES LIKE '$catalogs_table_name';");
            if(!$table) {
                $wpdb->query( "DROP TABLE " .$catalogs_table_name );
            }


            $queues_table_name = $wpdb->prefix .  Queue::$table;
            $sql = "CREATE TABLE $queues_table_name (
				`id` VARCHAR(128) UNIQUE ,
				`rewix_catalog_id` VARCHAR(128) NOT NULL,
				`type` VARCHAR(128) NOT NULL,
				`data` TEXT DEFAULT NULL,
				`in_process` INT(1) NOT NULL DEFAULT '0',
				`create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
            dbDelta( $sql );


            $queues_table_name = $wpdb->prefix .  Log::$table;
            $sql = "CREATE TABLE $queues_table_name (
				`id` INT(10) UNSIGNED AUTO_INCREMENT ,
				`type` INT(10) UNSIGNED NOT NULL,
				`title` VARCHAR(128) NOT NULL,
                `message` TEXT,
				`create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
            dbDelta( $sql );

            update_option( 'bdroppy_db_version', BDROPPY_DB_VERSION );
        }
    }



}
