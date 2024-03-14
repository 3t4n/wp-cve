<?php
/**
 * @param version without first '1' or '2'
 *
 */

function wdi_update_diff($new_v, $old_v = 0.0)
{
  global $wpdb;
  set_time_limit(60);

  // no need to prepare a constant WDI_FEED_TABLE
  if (version_compare($old_v, "0.6", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `conditional_filters` varchar(10000) NOT NULL DEFAULT ''"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `conditional_filter_type` varchar(32) NOT NULL DEFAULT 'none'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `show_username_on_thumb` varchar(32) NOT NULL DEFAULT '0'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `conditional_filter_enable` varchar(32) NOT NULL DEFAULT '0'"); //db call ok

    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `th_thumb_user_bg_color` varchar(32) NOT NULL DEFAULT '#429FFF'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `th_thumb_user_color` varchar(32) NOT NULL DEFAULT '#FFFFFF'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `mas_thumb_user_bg_color` varchar(32) NOT NULL DEFAULT '#429FFF'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `mas_thumb_user_color` varchar(32) NOT NULL DEFAULT '#FFFFFF'"); //db call ok
  }

  if (version_compare($old_v, "0.7", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `th_photo_img_hover_effect` varchar(32) NOT NULL DEFAULT 'none'"); //db call ok
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " ADD `mas_photo_img_hover_effect` varchar(32) NOT NULL DEFAULT 'none'"); //db call ok
  }
  if (version_compare($old_v, "1.0", '<')) {
    /*add api update notice*/
    $admin_notices_option = get_option('wdi_admin_notice', array());
    $admin_notices_option['api_update_token_reset'] = array(
        'start' => current_time("n/j/Y"),
        'int' => 0,
    );
    update_option('wdi_admin_notice', $admin_notices_option);
  }
  if (version_compare($old_v, "1.2", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_THEME_TABLE) . " convert to character set latin1 collate latin1_general_ci"); //db call ok
  }
  if (version_compare($old_v, "1.12", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `liked_feed` varchar(30) NOT NULL DEFAULT 'userhash'"); //db call ok
  }
  if (version_compare($old_v, "1.17", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `mobile_breakpoint` varchar(10) NOT NULL DEFAULT '640'"); //db call ok
  }
  if (version_compare($old_v, "2.2", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `redirect_url` varchar(255) NOT NULL DEFAULT ''"); //db call ok
  }
  if (version_compare($old_v, "2.8", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " MODIFY `feed_users` VARCHAR(2000) NOT NULL"); //db call ok
  }
  if (version_compare($old_v, "2.12", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `feed_resolution` varchar(30) NOT NULL DEFAULT 'optimal'"); //db call ok
  }
  if (version_compare($old_v, "3.6", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " MODIFY COLUMN 	feed_thumb varchar(800) NOT NULL"); //db call ok
  }

  if (version_compare($old_v, "3.13", '<')) {
    /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange */
    $wpdb->query("ALTER TABLE " . esc_sql($wpdb->prefix . WDI_FEED_TABLE) . " ADD `hashtag_top_recent` varchar(10) NOT NULL DEFAULT '1'"); //db call ok
  }

}

