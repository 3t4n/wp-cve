<?php
/*
Plugin Name: MailChimp Campaigns Manager
Plugin Script: mailchimp-campaigns-manager.php
Plugin URI:   https://matthieuscarset.com/en/portfolio/wp-mailchimp
Description: Import and display your MailChimp campaigns in WordPress with simple shortcodes.
Author: MatthieuScarset
Author URI: https://matthieuscarset.com/
License: GPL
Version: 3.2.4
Text Domain: mailchimp_campaigns_manager
Domain Path: languages/

Import and display your MailChimp campaigns in WordPress with simple shortcodes.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {exit;}

// Define globals
define('MCC_VERSION', '3.2.4');
define('MCC_API_VERSION', '3.0');
define('MCC_DEFAULT_CPT', 'newsletter');
define('MCC_DEFAULT_CPT_STATUS', 'publish');
define('MCC_TEXT_DOMAIN', 'mailchimp_campaigns_manager');
define('MCC_META_PRE', 'mcc_');
define('MCC_META_KEY_ID', MCC_META_PRE . 'id');
define('MCC_PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__));
define('MCC_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__));
define('MCC_CUSTOM_CAPABILITY', 'manage_campaigns');

$post_type = mailchimp_campaigns_manager_get_post_type();

/* ============================================================================
ADMIN THINGS.
============================================================================ */

/**
 * Helper function to print admin messages.
 *
 * @param [type] $message
 * @param string $status
 * @return void
 */
function mailchimp_campaigns_admin_notice($message, $status = 'updated') {?>
  <div class="<?php print $status;?>"><p><?php echo $message; ?></p></div>
<?php }

/**
 * Helper function to register Campaigns Meta keys' labels.
 */
function mailchimp_campaigns_manager_register_labels() {
  $metas_fields = mailchimp_campaigns_manager_get_meta_fields();
  if (get_option('mailchimp_campaigns_manager_labels', false)) {
    update_option('mailchimp_campaigns_manager_labels', $metas_fields);
  } else {
    add_option('mailchimp_campaigns_manager_labels', $metas_fields);
  }
  return $metas_fields;
}
register_activation_hook(__FILE__, 'mailchimp_campaigns_manager_register_labels');

/**
 * Enqueue plugin style-file.
 */
function mailchimp_campaigns_manager_add_css() {
  wp_register_style('mailchimp_campaigns_manager_metaboxes', plugins_url('css/mailchimp_campaigns_manager_metaboxes.css', __FILE__));
  wp_enqueue_style('mailchimp_campaigns_manager_metaboxes');

  wp_register_style('mailchimp_campaigns_manager_admin', plugins_url('css/mailchimp_campaigns_manager_admin.css', __FILE__));
  wp_enqueue_style('mailchimp_campaigns_manager_admin');

  wp_register_style('mailchimp_campaigns_manager_list', plugins_url('css/mailchimp_campaigns_manager_list.css', __FILE__));
  wp_enqueue_style('mailchimp_campaigns_manager_list');
}
add_action('admin_enqueue_scripts', 'mailchimp_campaigns_manager_add_css');
add_action('wp_enqueue_scripts', 'mailchimp_campaigns_manager_add_css');

/**
 * Enqueue plugin style-file
 */
function mailchimp_campaigns_manager_add_js() {
  wp_enqueue_script('ajax-script', plugins_url('/js/mailchimp_campaigns_manager_admin.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'mailchimp_campaigns_manager_add_js');

/*
 * Compatibility issue management
 * Aims to solve issues with previous versions
 * @return void
 */
function mailchimp_campaigns_manager_compatibilty() {
  // Save the previous API Key and delete it
  $settings     = get_option('mailchimp_campaigns_manager_settings', []);
  $old_api_key  = get_option('ola_mccp_api_key', false) || get_option('olalaweb_mailchimp_api_key', false);
  $old_settings = get_option('mailchimpcampaigns_settings', []);

  if ($old_api_key) {
    // Delete previous option entries
    if (get_option('ola_mccp_api_key', false)) {
      delete_option('ola_mccp_api_key');
    }

    if (get_option('olalaweb_mailchimp_api_key', false)) {
      delete_option('olalaweb_mailchimp_api_key');
    }

    // Save old API key in the settings new array.
    $settings['api_key'] = $old_api_key;
  }

  if (!empty($old_settings)) {
    $settings = array_merge($settings, $old_settings);
    delete_option('mailchimpcampaigns_settings');
  }

  if ($old_api_key || !empty($old_settings)) {
    // Save new settings
    add_option('mailchimp_campaigns_manager_settings', $settings); // return nothing it already exists
    update_option('mailchimp_campaigns_manager_settings', $settings); // update it just in case
  }
}
register_activation_hook(__FILE__, 'mailchimp_campaigns_manager_compatibilty');

/*
 * Detect missing dependencies issue.
 */
function mailchimp_campaigns_manager_dependencies() {
  $required = array(
    'bypass_iframe_height_limit_init' => 'Bypass Iframe Height Limit',
  );
  foreach ($required as $function_name => $plugin_name) {
    if (!function_exists($function_name)) {
      echo '<div class="notice notice-warning is-dismissible">';
      echo '<p>' . __('Mailchimp Campaigns Manager works better with:', MCC_TEXT_DOMAIN) . ' <a href="/wp-admin/plugin-install.php?s=' . $plugin_name . '&tab=search&type=term">' . $plugin_name . '</a></p>';
      echo '</div>';
    }
  }
}

/*
 * Register new user roles.
 */
function mailchimp_campaigns_manager_custom_roles() {
  $post_type = mailchimp_campaigns_manager_get_post_type();
  $caps      = array(
    // Disable defaults.
    'read'                => FALSE,
    'edit_posts'          => FALSE,
    'delete_posts'        => FALSE,
    'publish_posts'       => FALSE,
    'upload_files'        => FALSE,
    // Custom caps.
    'level_2'             => TRUE,
    'level_1'             => TRUE,
    'level_0'             => TRUE,
    MCC_CUSTOM_CAPABILITY => TRUE,
  );
  add_role('mailchimp_campaigns_manager', 'Mailchimp Campaigns Manager', $caps);

  // Add capabilities to admin roles.
  $roles = array(
    'mailchimp_campaigns_manager',
    'editor',
    'administrator',
  );
  $capabilities = array(
    'read',
    'read_' . $post_type,
    'read_private_' . $post_type . 's',
    'edit_' . $post_type,
    'edit_' . $post_type . 's',
    'edit_others_' . $post_type . 's',
    'edit_published_' . $post_type . 's',
    'publish_' . $post_type . 's',
    'delete_others_' . $post_type . 's',
    'delete_private_' . $post_type . 's',
    'delete_published_' . $post_type . 's',
  );

  foreach ($roles as $role_name) {
    $role = get_role($role_name);
    foreach ($capabilities as $capability_name) {
      $role->add_cap($capability_name);
    }
  }

  // add $cap capability to Admin role.
  $admin_role = get_role('administrator');
  $admin_role->add_cap(MCC_CUSTOM_CAPABILITY);

}
register_activation_hook(__FILE__, 'mailchimp_campaigns_manager_custom_roles');

/**
 * Register our custom post type now.
 *
 * Can not be anywhere else than here.
 */
require_once MCC_PLUGIN_ROOT_DIR . 'lib/basic-auth.php';
require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpCampaignsManager.php';
require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpPost.php';
require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpCustomPostType.php';
$MCCPostType = new MailchimpCustomPostType();

/**
 * Instanciate our custom things on init.
 *
 * Get required files if is WordPress admin area.
 *
 * Fires after WordPress has finished loading but before any headers are sent.
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/init
 */
function mailchimp_campaigns_manager_init() {
  mailchimp_campaigns_manager_custom_roles();
  mailchimp_campaigns_manager_compatibilty();

  if (is_admin()) {
    require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpAdmin.php';
    require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpCampaign.php';
    require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpCampaigns.php';
    require_once MCC_PLUGIN_ROOT_DIR . 'class/MailchimpCampaignMetabox.php';
    $MCCAdmin = new MailchimpAdmin();
  }
}
add_action('init', 'mailchimp_campaigns_manager_init');

/**
 * Add Metaboxes to CPT admin screens.
 */
function mailchimp_campaigns_manager_edit_screen() {
  global $post;
  $MCCampaignsMetabox = new MailchimpCampaignMetabox($post);
}
add_action('load-post.php', 'mailchimp_campaigns_manager_edit_screen', 10, 2);
add_action('load-post-new.php', 'mailchimp_campaigns_manager_edit_screen', 10, 2);

/**
 * Displays a direct link to the WordPress plugin admin page.
 */
function mailchimp_campaigns_manager_settings_link($links, $file) {
  static $this_plugin;
  if (!$this_plugin) {
    $this_plugin = plugin_basename(__FILE__);
  }

  $post_type = mailchimp_campaigns_manager_get_post_type();

  if ($file == $this_plugin) {
    $setting_page_url = 'edit.php?post_type=' . $post_type . '&page=mailchimp_campaigns_manager-admin';
    $settings_link    = '<a href="' . $setting_page_url . '">' . __('Settings', MCC_TEXT_DOMAIN) . '</a>';
    array_unshift($links, $settings_link);
  }
  return $links;
}
add_filter('plugin_action_links', 'mailchimp_campaigns_manager_settings_link', 10, 2);

/**
 * Rewrite flush on plugin activation hook to register our post type.
 */
function mailchimp_campaigns_manager_rewrite_flush() {
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mailchimp_campaigns_manager_rewrite_flush');

/**
 *
 */
function mailchimp_campaigns_manager_embed_filter() {
  global $post;
  $metas  = get_post_meta($post->ID);
  $output = '' .
    $metas['mcc_content_html'][0] .
    '';
  echo $output;
};
add_action('embed_content', 'mailchimp_campaigns_manager_embed_filter', 10, 0);

/* ============================================================================
REST API related things.
============================================================================ */

/**
 * Activate REST API on our Custom Post Type.
 */
function mailchimp_campaigns_manager_cpt_activate_rest($args, $post_type) {
  $custom_post_type = mailchimp_campaigns_manager_get_post_type();

  if ($custom_post_type === $post_type) {
    $args['show_in_rest'] = true;
  }

  return $args;
}
add_filter('register_post_type_args', 'mailchimp_campaigns_manager_cpt_activate_rest', 10, 2);

/**
 * Define the rest_<post_type>_collection_params callback
 */
function mailchimp_campaigns_manager_filter_rest_post_type_collection_params($allowed_query_params, $post_type) {
  // Add remote_id metadata filtering.
  $allowed_query_params['remote_id'] = array(
    'description'       => __("Limit results to a specific Mailchimp campaign ID.", MCC_TEXT_DOMAIN),
    'type'              => "string",
    'sanitize_callback' => "sanitize_text_field",
    'validate_callback' => "rest_validate_request_arg",
  );
  return $allowed_query_params;
};
add_filter('rest_' . $post_type . '_collection_params', 'mailchimp_campaigns_manager_filter_rest_post_type_collection_params', 10, 2);

/**
 * Add remote_id query parameter
 *
 * @param [type] $args
 * @param [type] $request
 * @return void
 */
function mailchimp_campaigns_manager_query_campaign_by_remote_id($args, $request) {
  if (isset($request['remote_id'])) {
    $remote_id_metakey  = MCC_META_PRE . 'id';
    $args['meta_key']   = $remote_id_metakey;
    $args['meta_value'] = strval($request['remote_id']);
  }
  return $args;
}
add_filter('rest_' . $post_type . '_query', 'mailchimp_campaigns_manager_query_campaign_by_remote_id', 10, 2);

/**
 * Register all Mailchimp fields as post metas.
 */
function mailchimp_campaigns_manager_register_metas() {
  $post_type    = mailchimp_campaigns_manager_get_post_type();
  $metas_fields = mailchimp_campaigns_manager_get_meta_fields();
  foreach ($metas_fields as $name => $description) {
    // Add prefix to meta fields.
    $meta_key = MCC_META_PRE . $name;

    // Register this meta field into WordPress API.
    register_rest_field($post_type, $meta_key, array(
      'get_callback'    => 'mailchimp_campaigns_manager_get_meta',
      'update_callback' => 'mailchimp_campaigns_manager_update_meta',
      // 'schema'          => array($meta_key => __($description, MCC_TEXT_DOMAIN)),
    ));
  }
}
function mailchimp_campaigns_manager_get_meta($post, $field_name, $request) {
  $meta = get_post_meta($post['id'], $field_name);
  return !empty($meta) ? reset($meta) : NULL;
}
function mailchimp_campaigns_manager_update_meta($value, $post, $field_name) {
  if ($field_name == MCC_META_PRE . 'content_html') {
    $sanitized_value = $value;
  } else {
    $sanitized_value = sanitize_text_field($value);
  }

  if (!get_post_meta($post->ID, $field_name)) {
    $result = add_post_meta($post->ID, $field_name, $sanitized_value);
  } else {
    $result = update_post_meta($post->ID, $field_name, $sanitized_value);
  }
  return $result;
}
add_action('rest_api_init', 'mailchimp_campaigns_manager_register_metas');

/* ============================================================================
HELPERS
============================================================================ */

/**
 * Get list of all meta field from Mailchimp.
 *
 * @return array
 */
function mailchimp_campaigns_manager_get_meta_fields() {
  return array(
    'id'               => __('ID', MCC_TEXT_DOMAIN),
    'type'             => __('Type', MCC_TEXT_DOMAIN),
    'status'           => __('Status', MCC_TEXT_DOMAIN),
    'create_time'      => __('Created on', MCC_TEXT_DOMAIN),
    'send_time'        => __('Sent on', MCC_TEXT_DOMAIN),
    'emails_sent'      => __('Emails sent', MCC_TEXT_DOMAIN),
    'delivery_status'  => __('Delivery status', MCC_TEXT_DOMAIN),
    'content_type'     => __('Content type', MCC_TEXT_DOMAIN),
    'archive_url'      => __('Archive URL', MCC_TEXT_DOMAIN),
    'long_archive_url' => __('Archive URL (long)', MCC_TEXT_DOMAIN),
    // Content
    'plain_text'       => __('Plain text', MCC_TEXT_DOMAIN),
    'content_html'     => __('HTML', MCC_TEXT_DOMAIN),
    // Lists related
    'recipients'       => __('Recipients', MCC_TEXT_DOMAIN),
    'list_id'          => __('List ID', MCC_TEXT_DOMAIN),
    'list_name'        => __('List name', MCC_TEXT_DOMAIN),
    'segment_text'     => __('Segment', MCC_TEXT_DOMAIN),
    'recipient_count'  => __('Recipients', MCC_TEXT_DOMAIN),
    // Extra campaign settings
    'settings'         => __('Settings', MCC_TEXT_DOMAIN),
    'tracking'         => __('Tracking', MCC_TEXT_DOMAIN),
    'social_card'      => __('Social card', MCC_TEXT_DOMAIN),
    'report_summary'   => __('Report summary', MCC_TEXT_DOMAIN),
    // Help related
    '__links'          => __('Action links', MCC_TEXT_DOMAIN),
    '_edit_lock'       => __('Edit lock', MCC_TEXT_DOMAIN),
    '_edit_last'       => __('Edit last', MCC_TEXT_DOMAIN),
  );
}

/**
 * Print our logo.
 *
 * @return string
 */
function mailchimp_campaigns_manager_logo($css) {
  $img_path = MCC_PLUGIN_ROOT_URL . 'assets/icon-128x128.png';
  return '<img src="' . $img_path . '" style="' . $css . '" />';
}

/**
 * Print our banner.
 *
 * @return string
 */
function mailchimp_campaigns_manager_banner() {
  $app_url = mailchimp_campaigns_manager_get_app_url('/user/register');
  return '<div class="notice notice-success is-dismissible" id="mailchimp_campaigns_manager_prompt">
    <p><strong>Need help with your import?</strong><br>Want faster download and automatic updates? <br>Subscribe to our online platform and synchronize WordPress with Mailchimp, the easy way.</p>
    <p><a class="button button-primary" href="' . $app_url . '" target="_blank">Subscribe now</a></p>
  </div>';
}

/**
 * Check if user is premium.
 *
 * @return array
 */
function mailchimp_campaigns_manager_is_pro() {
  $settings           = get_option('mailchimp_campaigns_manager_settings', []);
  $license_expiration = isset($settings['license_expiration']) && !empty($settings['license_expiration']);
  return $license_expiration && (date('U') >= $license_expiration);
}

/**
 * Check license and update settings.
 *
 * @return void
 */
function mailchimp_campaigns_manager_update_license() {
  $expiration_timestamp = 0;
  $settings             = get_option('mailchimp_campaigns_manager_settings', []);
  $app_url              = mailchimp_campaigns_manager_get_app_url('/mcsync/license');
  $response             = wp_remote_get($app_url);
  if (is_wp_error($response)) {
    // Error on remote server.
    $error_message = $response->get_error_message();
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>' . $error_message . '</p>';
    echo '</div>';

    return FALSE;
  } else {
    // Get expiration date.
    $found_users = json_decode($response['body']);
    foreach ($found_users as $email => $info) {
      $active = ($email == $settings['rest_user']) && ($info->status == 1);
      if ($active) {
        $expiration_timestamp = $info->expiration;
        break;
      }
    }
    $settings['license_expiration'] = (string) $expiration_timestamp;
  }

  // Update license expiration.
  update_option('mailchimp_campaigns_manager_settings', $settings); // update it just in case
  $test = get_option('mailchimp_campaigns_manager_settings', []);

  return $settings['license_expiration'];
}

/**
 * Remove license information from settings.
 */
function mailchimp_campaigns_manager_remove_license() {
  $options  = [];
  $app_url  = mailchimp_campaigns_manager_get_app_url('/mcsync/disconnect');
  $response = wp_remote_post($app_url, $options);
  if (is_wp_error($response)) {
    // Error on remote server.
    $error_message = $response->get_error_message();
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>' . $error_message . '</p>';
    echo '</div>';

    return FALSE;
  }

  $settings = get_option('mailchimp_campaigns_manager_settings', []);
  if (isset($settings['license_expiration'])) {
    unset($settings['license_expiration']);
  }
  update_option('mailchimp_campaigns_manager_settings', $settings);

  return TRUE;
}

/**
 * Tell distant app about the new REST User.
 *
 * @todo
 */
function mailchimp_campaigns_manager_update_rest_user() {
  return;
}

/**
 * Undocumented function
 *
 * @param string $path
 * @param boolean $add_query
 * @return void
 */
function mailchimp_campaigns_manager_get_app_url($path = '', $add_query = TRUE) {
  $app_url = 'https://mailchimp-campaigns-manager.com';
  $query   = mailchimp_campaigns_manager_get_app_query();
  return $app_url . $path . ($add_query ? '/?' . http_build_query($query) : '');
}

/**
 * Undocumented function
 *
 * @return void
 */
function mailchimp_campaigns_manager_get_app_query() {
  $settings  = get_option('mailchimp_campaigns_manager_settings', []);
  $post_type = mailchimp_campaigns_manager_get_post_type();
  $query     = array(
    'referer_mail'      => isset($settings['rest_user']) ? $settings['rest_user'] : NULL,
    'referer_url'       => home_url(),
    'referer_post_type' => $post_type,
    'destination'       => admin_url('edit.php?post_type=' . $post_type),
  );
  return $query;
}

/**
 * Get post type slug.
 *
 * @return string
 */
function mailchimp_campaigns_manager_get_post_type() {
  $settings  = get_option('mailchimp_campaigns_manager_settings', []);
  $post_type = isset($settings['cpt_name']) ? $settings['cpt_name'] : MCC_DEFAULT_CPT;
  return $post_type;
}

/* ============================================================================
AJAX callbacks
============================================================================ */

/**
 * Main import script return.
 */
function mailchimp_campaigns_manager_import() {
  $mcc     = new MailchimpCampaigns();
  $results = $mcc->import();
  echo $results;
  wp_die();
}
add_action('wp_ajax_mailchimp_campaigns_manager_import', 'mailchimp_campaigns_manager_import');

/**
 * Recalculate total items from Mailchimp.
 */
function mailchimp_campaigns_manager_recalculate() {
  $settings = get_option('mailchimp_campaigns_manager_settings', []);
  $is_pro   = isset($settings['license_key']) && !empty($settings['license_key']);
  $is_free  = !$is_pro && (isset($settings['api_key']) && !empty($settings['api_key']));

  if ($is_pro) {
    $total = mailchimp_campaigns_manager_get_total();
  }
  if ($is_free) {
    $mcc   = new MailchimpCampaigns();
    $total = $mcc->getTotal();
  }

  // Update new total.
  $settings                = get_option('mailchimp_campaigns_manager_settings', []);
  $settings['total_items'] = (string) $total;
  update_option('mailchimp_campaigns_manager_settings', $settings);

  echo $total;
  wp_die();
}
add_action('wp_ajax_mailchimp_campaigns_manager_recalculate', 'mailchimp_campaigns_manager_recalculate');

/**
 * Get total items from the app.
 */
function mailchimp_campaigns_manager_get_total() {
  // @todo
  return 0;
}

/**
 * Ajax callback to connect to remote app.
 */
function mailchimp_campaigns_manager_connect_app() {
  mailchimp_campaigns_manager_update_license();

  if (mailchimp_campaigns_manager_is_pro()) {
    $settings   = get_option('mailchimp_campaigns_manager_settings', []);
    $plugin_url = 'https://wordpress.org/support/plugin/olalaweb-mailchimp-campaign-manager/reviews/#new-post';
    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>' . __('Congrats! Your license is activated. You are using Mailchimp Campaign Manager as premium.') . '</p>';
    echo '<p>' . __('You don\'t have to worry about import anymore. Our app will synchronize campaigns automatically with your WordPress site.') . '</p>';
    echo '<p>' . __('Enjoy life now! And please consider to give this plugin a <a target="_blank" class="button button-primary" href="' . $plugin_url . '">5-stars rating</a>') . '</p>';
    echo '</div>';
  } else {
    $support_url = mailchimp_campaigns_manager_get_app_url('/support');
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>' . __('Sorry but we could not validate your account information.') . '</p>';
    echo '<p>' . __('Please <a href="' . $support_url . '">contact us</a> if you think it\'s an error.') . '</p>';
    echo '</div>';
  }

  wp_die();
}
add_action('wp_ajax_mailchimp_campaigns_manager_connect_app', 'mailchimp_campaigns_manager_connect_app');

/**
 * Ajax callback to disconnect from remote app.
 */
function mailchimp_campaigns_manager_disconnect_app() {
  mailchimp_campaigns_manager_remove_license();

  print '<div class="notice notice-warning is-dismissible">';
  print '<p>';
  print '<span>' . __('Disconnected from Mailchimp synchronization app.', MCC_TEXT_DOMAIN) . '</span>' . '<br>';
  print '<span>' . __('We are sorry to see you go.', MCC_TEXT_DOMAIN) . ' <strong>' . __('No more automatic updates for you.', MCC_TEXT_DOMAIN) . '</strong>';
  print '</p>';
  print '</div>';
  wp_die();
}
add_action('wp_ajax_mailchimp_campaigns_manager_disconnect_app', 'mailchimp_campaigns_manager_disconnect_app');

/**
 * Ajax callback to update remote app information.
 */
function mailchimp_campaigns_manager_update_app() {
  // @todo
  wp_die();
}
add_action('wp_ajax_mailchimp_campaigns_manager_update_app', 'mailchimp_campaigns_manager_update_app');

/* ============================================================================
SHORTCODES
============================================================================ */

/**
 * WordPress Embed shortcodes.
 */
function mailchimp_campaigns_manager_shutdown_shortcode() {
  return;
}

/**
 * Undocumented function
 *
 * @return void
 */
function mailchimp_campaigns_manager_compatibilty_shortcode() {
  // re-register old shortcodes with a dummy return function
  // for back-compatibility with v1.0.0
  $old_shortcodes = array(
    'campaign-title',
    'campaign-stats-list',
    'campaign-stats-table',
    'campaign-html',
    'campaign-text',
    'campaign-id',
    'cid',
  );
  foreach ($old_shortcodes as $shortcode) {
    add_shortcode($shortcode, 'mailchimp_campaigns_manager_shutdown_shortcode');
  }
}

/**
 * Undocumented function
 *
 * @param [type] $atts
 * @return void
 */
function mailchimp_campaigns_manager_campaign_shortcode($atts) {
  // Attributes with default width and height.
  extract(shortcode_atts(array('id' => '', 'height' => 600, 'width' => 800), $atts));

  // Get Posts.
  if (isset($id)) {
    $args = array(
      'post_type'      => mailchimp_campaigns_manager_get_post_type(),
      'posts_per_page' => 1,
      'meta_query'     => array(
        array(
          'key'   => MCC_META_KEY_ID,
          'value' => $id,
        ),
      ),
    );
    $posts = get_posts($args);
    $post  = !empty($posts) && isset($posts[0]) ? reset($posts) : NULL;
  }

  return (isset($post) && $post) ? get_post_embed_html($width, $height, $post) : '';
}
add_shortcode('campaign', 'mailchimp_campaigns_manager_campaign_shortcode');
