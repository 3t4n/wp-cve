<?php
/*
 Plugin Name: Sortable Word Count Reloaded
 Plugin URI: https://apasionados.es/blog/
 Description: Adds a sortable column to the posts and pages admin with the word count.
 Author: <a href="https://apasionados.es">Apasionados</a>
 Author URI: https://apasionados.es/
 Version: 1.0.3
 Text Domain: sortable-word-count-reloaded
 Domain Path: /languages
 License: GPL v3
 License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/
/*
 This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined( 'ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!is_admin()) {
    return false;
} // Don't run if not admin page

/**
 * Define variables
 */
define('APA_SWCR_META_FIELD_KEY', 'apa_swcr_meta_word_count');
define('APA_SWCR_OPTION_FIELD_KEY', 'apa_swcr_option_word_count');

/**
 * Deactivate plugin
 */
function apa_swcr_deactivation() {
    if (!current_user_can('activate_plugins')) {
        return;
    }

    $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
    check_admin_referer("deactivate-plugin_{$plugin}");

    // Delete WC init option
    delete_option(APA_SWCR_OPTION_FIELD_KEY);
}
register_deactivation_hook( __FILE__, 'apa_swcr_deactivation' );

/**
 * Init plugin
 */
function apa_swcr_sortable_word_count_run() {

    // Update posts/pages word count
    add_action('init', 'apa_swcr_update_posts_wc_value');

    // Add columns
    add_filter('manage_posts_columns', 'apa_swcr_add_wc_column_table_head');
    add_filter('manage_page_posts_columns', 'apa_swcr_add_wc_column_table_head');

    // Fill word count value
    add_action('manage_posts_custom_column', 'apa_swcr_add_wc_column_table_content', 10, 2);
    add_action('manage_page_posts_custom_column', 'apa_swcr_add_wc_column_table_content', 10, 2);

    // Enable sorting for columns
    add_filter('manage_edit-post_sortable_columns', 'apa_swcr_add_wc_table_sorting');
    add_filter('manage_edit-page_sortable_columns', 'apa_swcr_add_wc_table_sorting');

    // Sort values
    add_filter('request', 'apa_swcr_wc_column_sort');

    // Update word count value on save
    add_action('save_post', 'apa_swcr_update_post_wc_value');

    // Add custom styles
    add_action('admin_head', 'apa_swcr_styles');
}

/**
 * Add Word Count column to post type
 * @param $defaults
 * @return mixed
 */
function apa_swcr_add_wc_column_table_head( $defaults ) {
    $defaults['word_count'] = __('Word Count', 'sortable-word-count-reloaded');
    return $defaults;
}

/**
 * Show word count for post type
 * @param $column_name
 */
function apa_swcr_add_wc_column_table_content($column_name) {
    global $post;
    if ($column_name == 'word_count') {
        echo get_post_meta($post->ID, APA_SWCR_META_FIELD_KEY, true);
    }
}

/**
 * Make column word count sortable
 * @param $columns
 * @return mixed
 */
function apa_swcr_add_wc_table_sorting($columns) {
    $columns['word_count'] = 'word_count';
    return $columns;
}

/**
 * Sort values by word count
 * @param $vars
 * @return array
 */
function apa_swcr_wc_column_sort($vars) {
    if (isset( $vars['orderby']) && 'word_count' == $vars['orderby']) {
        $vars = array_merge( $vars, array(
            'meta_key' => APA_SWCR_META_FIELD_KEY,
            'orderby' => 'meta_value_num'
        ));
    }
    return $vars;
}

/**
 * Set word count for post type
 * @param string $post_type
 */
function apa_swcr_update_wc_posts($post_type='post') {
    $query = new WP_Query('post_type='.$post_type.'&posts_per_page=-1');
    if ( $query->have_posts() ) {
        while ($query->have_posts()) {
            $query->the_post();
            $content = get_post_field('post_content', get_the_ID());
			$words = apa_swcr_wpwc_word_count($content);
            update_post_meta(get_the_ID(), APA_SWCR_META_FIELD_KEY, $words);
        }
    }
    wp_reset_postdata();
}

/**
 * We try to get the correct word count by filtering comments and page builder code.
 */
function apa_swcr_wpwc_word_count($content) {
	$content = preg_replace( '/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $content );
	$content = strip_tags( nl2br( $content ) );
	if ( preg_match( "/[\x{4e00}-\x{9fa5}]+/u", $content ) ) {
		$content = preg_replace( '/[\x80-\xff]{1,3}/', ' ', $content, -1, $n );
		$n += str_word_count($content);
		return $n;
	} else {
		return count( preg_split( '/\s+/', $content ) );
	}
}

/**
 * Update for all post types
 */
function apa_swcr_update_posts_wc_value() {
    if (get_option(APA_SWCR_OPTION_FIELD_KEY) == false) {
        // Posts
        apa_swcr_update_wc_posts('post');
        // Pages
        apa_swcr_update_wc_posts('page');
        // Initialized
        add_option(APA_SWCR_OPTION_FIELD_KEY, true);
    }
}

/**
 * Update Word Count on save
 * @param $post_id
 */
function apa_swcr_update_post_wc_value( $post_id ) {
    $content = get_post_field('post_content', $post_id);
    update_post_meta($post_id, APA_SWCR_META_FIELD_KEY, str_word_count($content));
}

/**
 * Styles
 */
function apa_swcr_styles() {
    echo '<style type="text/css">
           #word_count { width: 12%; }
           .word_count { text-align: center; }
         </style>';
}

/**
 * Run plugin
 */
apa_swcr_sortable_word_count_run();


/**
 * Do some check on plugin activation.
*/
function apa_swcr_f_activation() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	$plugin_name = $plugin_data['Name'];
	$php_minimum = '5.6';
	if ( version_compare( PHP_VERSION, $php_minimum, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( '<h1>' . __('Could not activate plugin: PHP version error', 'sortable-word-count-reloaded' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'apa-cf7sdomt' ) . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions', 'apa-cf7sdomt' ) . ' ' . $php_minimum . ' ' . __( 'and greater.', 'apa-cf7sdomt' ) . '</p><p>' . __('WordPress itself <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using PHP version 7.3 or greater</a>. Please upgrade your PHP version or contact your Server administrator.', 'apa-cf7sdomt' ) . '</p>', __('Could not activate plugin: PHP version error', 'apa-cf7sdomt' ), array( 'back_link' => true ) );
	}
}
register_activation_hook( __FILE__, 'apa_swcr_f_activation' );

/**
 * Read translations.
 */
function apa_swcr_f_init() {
 load_plugin_textdomain( 'sortable-word-count-reloaded', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'apa_swcr_f_init');