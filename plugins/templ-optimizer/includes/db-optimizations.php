<?php
defined('ABSPATH') or die();

class templOptimizerDb extends templOptimizer {

    function __construct() {}

    function get_database_size() {
        global $wpdb;
        $size = $wpdb->get_var("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) FROM information_schema.tables WHERE TABLE_SCHEMA = '{$wpdb->dbname}' GROUP BY table_schema");
        return $size . 'MB';
    }

    function count_trashed_posts() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_status = 'trash'");
        return $count;
    }

    function delete_trashed_posts() {
        global $wpdb;
        $count = $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_status = 'trash'");
        return $count;
    }

    function count_revisions() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'revision'");
        return $count;
    }

    function delete_revisions() {
        global $wpdb;
        $count = $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'revision'");
        return $count;
    }

    function count_auto_drafts() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_status = 'auto-draft'");
        return $count;
    }

    function delete_auto_drafts() {
        global $wpdb;
        $count = $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_status = 'auto-draft'");
        return $count;
    }

    function count_orphaned_postmeta() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) pm FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
        return $count;
    }

    function delete_orphaned_postmeta() {
        global $wpdb;
        $count = $wpdb->query("DELETE pm FROM {$wpdb->prefix}postmeta pm LEFT JOIN {$wpdb->prefix}posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
        return $count;
    }

    function count_tables_with_different_prefix() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(TABLE_NAME) FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$wpdb->dbname}' AND TABLE_NAME NOT LIKE '{$wpdb->base_prefix}%'");
        return $count;
    }

    function list_tables_with_different_prefix() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$wpdb->dbname}' AND TABLE_NAME NOT LIKE '{$wpdb->base_prefix}%'",
            $output = 'ARRAY_A'
        );

        $tables = array();

        foreach( $query as $table ) {
            $tables []= $table['TABLE_NAME'];
        }

        $list = implode( ', ', $tables );

        return $list;

    }

    function drop_tables_with_different_prefix() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$wpdb->dbname}' AND TABLE_NAME NOT LIKE '{$wpdb->base_prefix}%'",
            $output = 'ARRAY_A'
        );

        $count = 0;

        foreach( $query as $table ) {
            $table_name = $table['TABLE_NAME'];
            $wpdb->query("DROP TABLE {$table_name}");
            $count++;
        }
        
        return $count;

    }

    function count_expired_transients() {

        global $wpdb;

        // Get all transients as an array
        $query = $wpdb->get_results(
            "SELECT 'option_name', 'option_value' FROM {$wpdb->prefix}options WHERE 'option_name' LIKE '_transient_timeout%'",
            $output = 'ARRAY_A'
        );
        
        $count = 0;
        
        foreach( $query as $transient ) {

            $expiration_time = $transient['option_value'];

            if( $expiration_time < time() ) {
                $count++;
            }

        }

        return strval($count);

    }

    function delete_expired_transients() {

        global $wpdb;

        // Get all transients as an array
        $query = $wpdb->get_results(
            "SELECT 'option_name', 'option_value' FROM {$wpdb->prefix}options WHERE 'option_name' LIKE '_transient_timeout%'",
            $output = 'ARRAY_A'
        );

        $count = 0;
        
        foreach( $query as $transient ) {

            $expiration_time = $transient['option_value'];

            if( $expiration_time < time() ) {

                $transient_name = str_replace( '_transient_timeout_', '', $transient['option_name'] );

                // Delete transient & transient timeout
                $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE 'option_name' LIKE '_transient_{$transient_name}'");
                $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE 'option_name' LIKE '_transient_timeout_{$transient_name}'");

                $count++;

            }

        }

        return $count;

    }

    function count_myisam_tables() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SHOW TABLE STATUS WHERE Engine = 'MyISAM'",
            $output = 'ARRAY_A'
        );

        $count = 0;

        foreach( $query as $table ) {
            $count++;
        }
        
        return $count;

    }

    function list_myisam_tables() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SHOW TABLE STATUS WHERE Engine = 'MyISAM'",
            $output = 'ARRAY_A'
        );

        $my_isam_tables = array();

        foreach( $query as $table ) {
            $my_isam_tables []= $table['Name'];
        }

        $list = implode( ', ', $my_isam_tables );
        
        return $list;

    }

    function convert_to_innodb() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SHOW TABLE STATUS WHERE Engine = 'MyISAM'",
            $output = 'ARRAY_A'
        );

        $count = 0;

        foreach( $query as $table ) {
            $table_name = $table['Name'];
            $wpdb->query("ALTER TABLE {$table_name} ENGINE=InnoDB");
            $count++;
        }
        
        return $count;

    }

    function optimize_tables() {

        global $wpdb;

        $query = $wpdb->get_results(
            "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$wpdb->dbname}'",
            $output = 'ARRAY_A'
        );

        $count = 0;

        foreach( $query as $table ) {
            $table_name = $table['TABLE_NAME'];
            $wpdb->query("OPTIMIZE TABLE {$table_name}");
            $count++;
        }
        
        return $count;

    }

    function count_tables() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(TABLE_NAME) FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$wpdb->dbname}'");
        return $count;
    }
    
    function optimize_all() {
        $this->delete_trashed_posts();
        $this->delete_revisions();
        $this->delete_auto_drafts();
        $this->delete_orphaned_postmeta();
        $this->drop_tables_with_different_prefix();
        $this->delete_expired_transients();
        $this->convert_to_innodb();
        $this->optimize_tables();
    }

}
