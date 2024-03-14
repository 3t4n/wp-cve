<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    Easy Header Footer
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_action( 'init', 'ehf_header_footer_code_output' );

function ehf_header_footer_code_output() {
    $options = get_option('rm_plugin_global_settings');

    $header_prio = !empty( $options['rm_header_code_priority'] ) ? $options['rm_header_code_priority'] : 10;
    $footer_prio = !empty( $options['rm_footer_code_priority'] ) ? $options['rm_footer_code_priority'] : 10;

    add_action( 'wp_head', 'ehf_add_custom_header_code', $header_prio );
    add_action( 'wp_footer', 'ehf_add_custom_footer_code', $footer_prio );

    if( function_exists( 'wp_body_open' ) ) {
        add_action( 'wp_body_open', 'ehf_add_body_open_code' );
    }

    if( defined( 'AMP__VERSION' ) ) {
        add_action( 'amp_post_template_css', 'ehf_amp_post_template_css', 100 );
        add_action( 'amp_post_template_footer', 'ehf_amp_post_template_footer', 100 );
        add_action( 'amp_post_template_css', 'ehf_amp_post_template_css', 100 );
    }
}

function ehf_add_custom_header_code() {
    // Ignore admin, feed, robots or trackbacks
	if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
		return;
    }
        
    $options = get_option('rm_plugin_global_settings');
    $site_head_code = !empty($options['rm_custom_header_ta']) ? $options['rm_custom_header_ta'] : '';
    $meta_head_code = ( is_singular() && !empty( get_post_meta( get_the_ID(), '_rm_header_code', true ) ) ) ? get_post_meta( get_the_ID(), '_rm_header_code', true ) : '';

    $code = $site_head_code . "\n" . $meta_head_code . "\n";
    if ( is_singular() && get_post_meta( get_the_ID(), '_rm_header_disable', true ) == 'yes'  ) {
        $code = $meta_head_code . "\n";
    }

    if ( !empty( $code ) ) {
        echo wp_unslash( ehf_php_lang_execute( ehf_replace( $code ) ) );
    }
}

function ehf_add_custom_footer_code() {
    // Ignore admin, feed, robots or trackbacks
	if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
		return;
    }
    $options = get_option('rm_plugin_global_settings');
    $site_footer_code = !empty($options['rm_custom_footer_ta']) ? $options['rm_custom_footer_ta'] : '';
    $meta_footer_code = ( is_singular() && !empty( get_post_meta( get_the_ID(), '_rm_footer_code', true ) ) ) ? get_post_meta( get_the_ID(), '_rm_footer_code', true ) : '';

    $code = $site_footer_code . "\n" . $meta_footer_code . "\n";
    if ( is_singular() && get_post_meta( get_the_ID(), '_rm_footer_disable', true ) == 'yes'  ) {
        $code = $meta_footer_code . "\n";
    }

    if ( !empty( $code ) ) {
        echo wp_unslash( ehf_php_lang_execute( ehf_replace( $code ) ) );
    }
}

function ehf_add_body_open_code() {
    // Ignore admin, feed, robots or trackbacks
	if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
		return;
    }
    $options = get_option('rm_plugin_global_settings');
    $site_footer_code = !empty($options['rm_custom_body_open_code']) ? $options['rm_custom_body_open_code'] : '';
    $meta_footer_code = ( is_singular() && !empty( get_post_meta( get_the_ID(), '_rm_body_open_code', true ) ) ) ? get_post_meta( get_the_ID(), '_rm_body_open_code', true ) : '';

    $code = $site_footer_code . "\n" . $meta_footer_code . "\n";
    if ( is_singular() && get_post_meta( get_the_ID(), '_rm_body_open_disable', true ) == 'yes'  ) {
        $code = $meta_footer_code . "\n";
    }

    if ( !empty( $code ) ) {
        echo wp_unslash( ehf_php_lang_execute( ehf_replace( $code ) ) );
    }
}

function ehf_amp_post_template_head() {
    $options = get_option('rm_plugin_global_settings');

    echo ehf_php_lang_execute( $options['rm_amp_header_code'] );
}

function ehf_amp_post_template_footer() {
    $options = get_option('rm_plugin_global_settings');

    echo "\n";
    echo ehf_php_lang_execute( $options['rm_amp_footer_code'] );
    echo "\n";
}

function ehf_amp_post_template_css() {
    $options = get_option('rm_plugin_global_settings');

    echo "\n";
    echo ehf_php_lang_execute( $options['rm_amp_css_code'] );
    echo "\n";
}

function ehf_replace( $buffer ) {
    global $post;
    if ( empty( $buffer ) ) {
        return '';
    }

    // For 404 pages and maybe others...
    if ( !is_object( $post ) ) {
        return $buffer;
    }

    $permalink = urlencode( get_permalink() );
    $title = urlencode( $post->post_title );

    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
    $buffer = str_replace( '[facebook_share_url]', $facebook_url, $buffer );

    // Twitter
    $twitter_url = 'https://twitter.com/intent/tweet?text=' . $title;
    $twitter_url .= '&url=' . $permalink;
    $buffer = str_replace( '[twitter_share_url]', $twitter_url, $buffer );

    // Pinterest
    $pinterest_url = 'https://www.pinterest.com/pin/create/button/?url=' . $permalink;
    $image_id = function_exists('get_post_thumbnail_id') ? get_post_thumbnail_id( $post->ID ) : false;
    if ( $image_id ) {
        $image = wp_get_attachment_image_src( $image_id, 'full' );
        $pinterest_url .= '&media=' . urlencode( $image[0] );
    }
    $pinterest_url .= '&description=' . $title;
    $buffer = str_replace( '[pinterest_share_url]', $pinterest_url, $buffer );

    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink;
    $linkedin_url .= '&title=' . $title . '&source=' . urlencode( get_option('blogname') );
    $buffer = str_replace( '[linkedin_share_url]', $linkedin_url, $buffer );

    return $buffer;
}

function ehf_php_lang_execute( $buffer ) {
    $options = get_option('rm_plugin_global_settings');

    global $post;
    if ( empty( $buffer ) ) {
        return '';
    }

    if( isset( $options['rm_allow_php_exec'] ) && $options['rm_allow_php_exec'] == 1 ) {
        ob_start();
        eval( '?>' . $buffer );
        $buffer = ob_get_clean();
    }

    return $buffer;
}

?>