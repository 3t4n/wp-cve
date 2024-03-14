<?php
require_once 'Traits/Plugin_Constants_Legacy_Methods.php';
require_once 'Traits/Singleton.php';
require_once 'Helpers/Plugin_Constants.php';

use ACFWF\Helpers\Plugin_Constants;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
exit();
}

/**
 * Function that houses the code that cleans up the plugin on un-installation.
 *
 * @since 1.0
 */
function acfw_plugin_cleanup() {

    global $wpdb;

    // skip if the clean up setting is not enabled.
    if ( get_option( Plugin_Constants::CLEAN_UP_PLUGIN_OPTIONS ) !== 'yes' ) {
        return;
    }

    // Delete options.
    $wpdb->query(
        "DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE 'acfwf\_%' 
            OR option_name LIKE 'acfw\_%' 
            OR option_name LIKE '%\_acfwf\_%' 
            OR option_name LIKE '%\_acfw\_%';"
    );

    // Drop store credits table.
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}acfw_store_credits" );
    delete_option( Plugin_Constants::STORE_CREDITS_DB_CREATED );

    // Delete user, product, coupon, categories and order metas.
    $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '%\_acfw\_%' OR meta_key LIKE 'acfw\_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%\_acfw\_%' OR meta_key LIKE 'acfw\_%'" );
    $wpdb->query( "DELETE FROM {$wpdb->termmeta} WHERE meta_key LIKE '%\_acfw\_%' OR meta_key LIKE 'acfw\_%'" );

    // Delete coupon category taxonomy.
    $wpdb->delete(
        $wpdb->term_taxonomy,
        array(
            'taxonomy' => Plugin_Constants::COUPON_CAT_TAXONOMY,
        )
    );

    // Delete orphan term relationships.
    $wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

    // Delete orphan terms.
    $wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

    $wc_tables       = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}woocommerce_%'" );
    $order_item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';

    // Delete meta data under the WC order items meta table.
    if ( isset( $wc_tables[ $order_item_meta ] ) ) {
        $wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%\_acfw\_%' OR meta_key LIKE '%acfw\_%'" );
    }

    // Help settings section options.
    delete_option( Plugin_Constants::CLEAN_UP_PLUGIN_OPTIONS );

    // Delete other options with different prefixes.
    delete_option( Plugin_Constants::AFTER_APPLY_COUPON_REDIRECT_URL );
}

if ( function_exists( 'is_multisite' ) && is_multisite() ) {

    global $wpdb;

    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

    foreach ( $blog_ids as $blogid ) {

        switch_to_blog( $blogid );
        acfw_plugin_cleanup();

    }

    restore_current_blog();

} else {
    acfw_plugin_cleanup();
}
