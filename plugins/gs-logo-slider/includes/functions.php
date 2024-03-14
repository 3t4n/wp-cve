<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function is_divi_active() {
    if (!defined('ET_BUILDER_PLUGIN_ACTIVE') || !ET_BUILDER_PLUGIN_ACTIVE) return false;
    return et_core_is_builder_used_on_current_request();
}

function is_divi_editor() {
    if ( !empty($_POST['action']) && $_POST['action'] == 'et_pb_process_computed_property' && !empty($_POST['module_type']) && $_POST['module_type'] == 'gs_logo_slider' ) return true;
}

function is_pro_active() {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    return defined('GSL_PRO_VERSION') && is_plugin_active( GSL_PRO_PLUGIN );
}

function gs_echo_return($content, $echo = false) {

    if ($echo) {
        echo gs_wp_kses($content);
    } else {
        return $content;
    }
}

function minimize_css_simple($css) {
    // https://datayze.com/howto/minify-css-with-php
    $css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
    $css = preg_replace('/\s{2,}/', ' ', $css);
    $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
    $css = preg_replace('/;}/', '}', $css);
    return $css;
}

function gs_wp_kses($content) {

    $allowed_tags = wp_kses_allowed_html('post');

    $input_common_atts = ['class' => true, 'id' => true, 'style' => true, 'novalidate' => true, 'name' => true, 'width' => true, 'height' => true, 'data' => true, 'title' => true, 'placeholder' => true, 'value' => true];

    $allowed_tags = array_merge_recursive($allowed_tags, [
        'select' => $input_common_atts,
        'input' => array_merge($input_common_atts, ['type' => true, 'checked' => true]),
        'option' => ['class' => true, 'id' => true, 'selected' => true, 'data' => true, 'value' => true]
    ]);

    return wp_kses(stripslashes_deep($content), $allowed_tags);
}

function gs_allowed_tags($tags) {
    return $tags;
}

function gs_validate_boolean( $var ) {

    if (empty($var)) return false;

    if (gettype($var) == 'string' && strtolower($var) == 'on') return true;
    if (gettype($var) == 'string' && strtolower($var) == 'off') return false;

    return wp_validate_boolean($var);
}

function get_gs_logo_query( $atts ) {

    $args = shortcode_atts([
        'order'                => 'DESC',
        'orderby'            => 'date',
        'posts_per_page'    => -1,
        'tax_query' => [],
    ], $atts);

    $args['post_type'] = 'gs-logo-slider';

    return new \WP_Query(apply_filters('gs_logo_wp_query_args', $args));
}

function gs_get_option( $option, $default = '' ) {

    $options = get_option('gs_logo_slider_shortcode_prefs');

    if (isset($options[$option])) {
        return $options[$option];
    }

    return $default;
}

function gs_get_meta_values( $meta_key = '', $post_type = 'gs-logo-slider', $status = 'publish', $order_by = true, $order = 'ASC' ) {

    global $wpdb;

    if (empty($meta_key)) return [];

    if ($order_by) {
        $order == 'ASC' ? $order : 'DESC';
        $order_by = sprintf('ORDER BY pm.meta_value %s', $order);
    } else {
        $order_by = '';
    }

    $result = $wpdb->get_col($wpdb->prepare("
        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = %s 
        AND p.post_status = %s 
        AND p.post_type = %s 
        {$order_by}
    ", $meta_key, $status, $post_type));

    return $result;
}

function gs_get_meta_values_options( $meta_key = '', $post_type = 'gs-logo-slider', $status = 'publish', $echo = true ) {

    $meta_values = gs_get_meta_values( $meta_key, $post_type, $status );

    $html = '';

    foreach ($meta_values as $meta_value) {
        $html .= sprintf('<option value=".%s">%s</option>', sanitize_title($meta_value), esc_html($meta_value));
    }

    return gs_echo_return( $html, $echo );

}

function gs_get_terms( $term_name, $order = 'ASC', $orderby = 'name' ) {

    $terms = get_terms([
        'taxonomy' => $term_name,
        'orderby'  => $orderby,
        'order'    => $order,
    ]);

    return wp_list_pluck($terms, 'name', 'slug');
}

function gs_get_terms_options( $term_name, $echo = true, $order = 'ASC', $orderby = 'name' ) {

    $terms = gs_get_terms( $term_name, $order, $orderby );
    
    $html = '';

    foreach ( $terms as $term_slug => $term_name ) {
        $html.= sprintf( '<option value=".%s">%s</option>', $term_slug, $term_name );
    }

    return gs_echo_return( $html, $echo );

}

function get_shortcodes() {
    return plugin()->builder->_get_shortcodes( null, false, true );
}

function is_preview() {
    return isset( $_REQUEST['gslogo_shortcode_preview'] ) && !empty($_REQUEST['gslogo_shortcode_preview']);
}

// GET FEATURED IMAGE
function gs_get_featured_image( $post_ID ) {
    $post_thumbnail_id = get_post_thumbnail_id( $post_ID );
    if ( $post_thumbnail_id ) {
        $post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'medium' );
        return $post_thumbnail_img[0];
    }
}

function gs_update_plugin_version() {
    if ( GSL_VERSION !==  get_option('gs_logo_slider_version') ) {
        update_option( 'gs_logo_slider_version', GSL_VERSION );
        return true;
    }
    return false;
}

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function gs_appsero_init() {

    if ( !class_exists('GSLogoAppSero\Insights') ) {
        require_once GSL_PLUGIN_DIR . 'includes/appsero/Client.php';
    }

    $client = new \GSLogoAppSero\Client('2f95117b-b1c6-4486-88c0-6b6d815856bf', 'GS Logo Slider', __FILE__);
    // Active insights
    $client->insights()->init();
}

function get_item_terms_slugs( $term_name, $separator = ' ' ) {

    global $post;

    $terms = get_the_terms( $post->ID, $term_name );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        $terms = implode( $separator, wp_list_pluck( $terms, 'slug' ) );
        return $terms;
    }

    return $terms;

}

function gs_str_replace_first($search, $replace, $subject) {
    $search = '/'.preg_quote($search, '/').'/';
    return preg_replace($search, $replace, $subject, 1);
}

function change_key($settings, $old_key, $new_key) {

    if (!array_key_exists($old_key, $settings)) return $settings;

    $settings[$new_key] = $settings[$old_key];
    unset($settings[$old_key]);

    return $settings;
}

/**
 * Upgrade notice if compatibility fails
 */
function pro_compatibility_notice() {

    $screen = get_current_screen();
    
    if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) return;
    if ( 'update' === $screen->base && 'update' === $screen->id ) return;

    if ( ! current_user_can( 'update_plugins' ) ) return;

    $upgrade_url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . GSL_PRO_PLUGIN ), 'upgrade-plugin_' . GSL_PRO_PLUGIN );
    $message = '<p>' . __( 'GS Logo Slider is not working because you need to upgrade the GS Logo Slider Pro plugin to latest version.', 'gslogo' ) . '</p>';
    $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_url, __( 'Upgrade GS Logo Slider Pro Now', 'gslogo' ) ) . '</p>';

    echo '<div class="error"><p>' . $message . '</p></div>';
    
}

/**
 * Compatibility check with Pro plugin
 */
function is_pro_compatible() {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    if ( defined('GSL_PRO_VERSION') && is_plugin_active( GSL_PRO_PLUGIN ) ) {
        if ( version_compare( GSL_PRO_VERSION, GSL_MIN_PRO_VERSION, '<' ) ) {
            add_action( 'admin_notices', 'GSLOGO\pro_compatibility_notice' );
            return false;
        }
    }
    return true;
}

/**
 * Activation redirects
 */
function on_activation() {
    add_option('gslogo_activation_redirect', true);
}

/**
 * Remove Reviews Metadata on plugin Deactivation.
 */
function on_deactivation() {
    delete_option('gslogo_active_time');
    delete_option('gslogo_maybe_later');
    delete_option('gsadmin_maybe_later');
}

/**
 * Plugins action links
 */
function add_pro_link( $links ) {
    if ( ! is_pro_active() ) {
        $links[] = '<a style="color: red; font-weight: bold;" class="gs-pro-link" href="https://www.gsplugins.com/product/gs-logo-slider" target="_blank">Go Pro!</a>';
    }
    $links[] = '<a href="https://www.gsplugins.com/wordpress-plugins" target="_blank">GS Plugins</a>';
    return $links;
}

/**
 * Plugins Load Text Domain
 */
function gs_load_textdomain() {
    load_plugin_textdomain( 'gslogo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}