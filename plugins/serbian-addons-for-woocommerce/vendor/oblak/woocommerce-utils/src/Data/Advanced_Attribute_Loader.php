<?php
/**
 * Advanced_Attibute_Loader class file
 *
 * @package WooCommerce Utils
 */

namespace Oblak\WooCommerce\Data;

use Oblak\WP\Traits\Singleton;

/**
 * Handles loading and creating of the advanced attribute tables
 */
class Advanced_Attribute_Loader {
    use Singleton;

    /**
     * Constructor
     */
    protected function __construct() {
        \add_action( 'before_woocommerce_init', array( $this, 'define_tables' ), 20 );
        \add_action( 'before_woocommerce_init', array( $this, 'maybe_create_tables' ), 30 );

        \add_action( 'woocommerce_data_stores', array( $this, 'register_data_store' ), 0 );

        \add_action( 'woocommerce_attribute_deleted', array( $this, 'delete_attribute_meta' ), 20, 1 );
    }

    /**
     * Defines the tables
     */
    public function define_tables() {
        global $wpdb;

        $tables = array(
            'attribute_taxonomymeta' => 'woocommerce_attribute_taxonomymeta',
        );

        foreach ( $tables as $name => $table ) {
            $wpdb->$name    = $wpdb->prefix . $table;
            $wpdb->tables[] = $table;
        }
    }

    /**
     * Maybe create the tables
     */
    public function maybe_create_tables() {
        if ( 'yes' === \get_option( 'woocommerce_atsd_tables_created', 'no' ) ) {
            return;
        }

        $this->create_tables();
        $this->verify_tables();
    }

    /**
     * Runs the table creation
     */
    private function create_tables() {
        global $wpdb;

        $wpdb->hide_errors();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        \dbDelta( $this->get_schema() );
    }

    /**
     * Verifies if the database tables have been created.
     *
     * @param  bool $execute       Are we executing table creation.
     * @return string[]            List of missing tables.
     */
    private function verify_tables( $execute = false ) {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        if ( $execute ) {
            $this->create_tables();
        }

        $queries        = \dbDelta( $this->get_schema(), false );
        $missing_tables = array();

        foreach ( $queries as $table_name => $result ) {
            if ( "Created table {$table_name}" !== $result ) {
                continue;
            }

            $missing_tables[] = $table_name;
        }

        if ( 0 === \count( $missing_tables ) ) {
            \update_option( 'woocommerce_atsd_tables_created', 'yes' );
        }

        return $missing_tables;
    }

    /**
     * Get the table schema.
     */
    protected function get_schema() {
        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            $collate = $wpdb->get_charset_collate();
        }

        $tables =
        "CREATE TABLE {$wpdb->attribute_taxonomymeta} (
            meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            attribute_taxonomy_id bigint(20) UNSIGNED NOT NULL,
            meta_key varchar(255) DEFAULT NULL,
            meta_value longtext,
            PRIMARY KEY  (meta_id)
        ) {$collate};";

        return $tables;
    }

    /**
     * Registers the data store
     *
     * @param  Array<string, string> $stores List of data stores.
     * @return Array<string, string>         Modified list of data stores.
     */
    public function register_data_store( $stores ) {
        $stores['attribute_taxonomy'] = Attribute_Taxonomy_Data_Store::class;

        return $stores;
    }

    /**
     * Deletes the attribute meta for the given attribute
     *
     * @param  int $attribute_id Attribute ID.
     */
    public function delete_attribute_meta( $attribute_id ) {
        global $wpdb;

        $wpdb->delete( $wpdb->attribute_taxonomymeta, array( 'attribute_taxonomy_id' => $attribute_id ) );
    }
}
