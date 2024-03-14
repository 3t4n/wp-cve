<?php

use Carbon_Fields\Field;

/**
 * Register a custom post type called "MKGD".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_book_init()
{
    $labels = array(
        'name' => _x('MK-GD', 'Post type general name', 'mkgd'),
        'singular_name' => _x('MK-GD', 'Post type singular name', 'mkgd'),
        'menu_name' => _x('MK-GD', 'Admin Menu text', 'mkgd'),
        'name_admin_bar' => _x('MK-GD', 'Add New on Toolbar', 'mkgd'),
        'add_new' => __('Add New', 'mkgd'),
        'add_new_item' => __('Add MK-GD', 'mkgd'),
        'new_item' => __('New MK-GD', 'mkgd'),
        'edit_item' => __('Edit MK-GD', 'mkgd'),
        'view_item' => __('View MK-GD', 'mkgd'),
        'all_items' => __('All MK-GD', 'mkgd'),
        'search_items' => __('Search MK-GD', 'mkgd'),
        'parent_item_colon' => __('Parent MK-GD:', 'mkgd'),
        'not_found' => __('No MK-GD found.', 'mkgd'),
        'not_found_in_trash' => __('No MK-GD found in Trash.', 'mkgd'),
        'featured_image' => _x('MK-GD Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'mkgd'),
        'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'mkgd'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'mkgd'),
        'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'mkgd'),
        'archives' => _x('MK-GD archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'mkgd'),
        'insert_into_item' => _x('Insert into MK-GD', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'mkgd'),
        'uploaded_to_this_item' => _x('Uploaded to this MK-GD', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'mkgd'),
        'filter_items_list' => _x('Filter MK-GD list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'mkgd'),
        'items_list_navigation' => _x('MK-GD list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'mkgd'),
        'items_list' => _x('MK-GD list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'mkgd'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'mkgd'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-location-alt',
    );

    register_post_type('mkgd', $args);
}

add_action('init', 'wpdocs_codex_book_init');


add_action('cmb2_admin_init', 'mkgd_register_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function mkgd_register_metabox()
{
    $prefix = "mkgd_";
    /**
     * Sample metabox to demonstrate each field type included
     */
    $mkgd_fields = new_cmb2_box(array(
        'id' => $prefix . 'metabox',
        'title' => esc_html__('Google Maps Settings', 'cmb2'),
        'object_types' => array('mkgd'), // Post type
    ));


    $mkgd_fields->add_field(array(
        'name' => esc_html__('Origin', 'cmb2'),
        'desc' => esc_html__('Default origin for Google map', 'cmb2'),
        'id' => $prefix . 'origin',
        'type' => 'text_medium',
        'options' => array(
            "onFocus" => "geolocate()",
        ),
    ));

    $mkgd_fields->add_field(array(
        'name' => esc_html__('Destination', 'cmb2'),
        'desc' => esc_html__('Default destination for Google map', 'cmb2'),
        'id' => $prefix . 'destination',
        'type' => 'text_medium',
    ));


    $mkgd_fields->add_field(array(
        'name' => esc_html__('Unit System', 'cmb2'),
        'desc' => esc_html__('Default unit system for Google map', 'cmb2'),
        'id' => $prefix . 'unit_system',
        'type' => 'select',
        'default' => 'metric',
        'options' => array(
            'metric' => __('Metric', 'cmb2'),
            'imperial' => __('Imperial', 'cmb2'),
        ),
    ));

    $mkgd_fields->add_field(array(
        'name' => esc_html__('Map width', 'cmb2'),
        'desc' => esc_html__('Default width for Google map', 'cmb2'),
        'id' => $prefix . 'width',
        'type' => 'text_medium',
    ));

    $mkgd_fields->add_field(array(
        'name' => esc_html__('Map Height', 'cmb2'),
        'desc' => esc_html__('Default height for Google map', 'cmb2'),
        'id' => $prefix . 'height',
        'type' => 'text_medium',
    ));

    $mkgd_fields->add_field(array(
        'name' => esc_html__('Hide Origin', 'cmb2'),
        'desc' => esc_html__('Hide origin on Google map', 'cmb2'),
        'id' => $prefix . 'hide_origin',
        'type' => 'checkbox',
    ));

    $mkgd_fields->add_field(array(
        'name' => esc_html__('Hide Destination', 'cmb2'),
        'desc' => esc_html__('Hide destination on Google map', 'cmb2'),
        'id' => $prefix . 'hide_destination',
        'type' => 'checkbox',
    ));
    

}

/*
 * Create Side Meta Box
 */
add_action('cmb2_admin_init', 'mkgd_side_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function mkgd_side_metabox()
{
    $prefix = "mkgd_";

    $post_id = 0;

    // Get the current ID
    if ( isset( $_GET['post'] ) && !is_array( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'])  ) {
        $post_id = $_POST['post_ID'];
    }

    /**
     * Sample metabox to demonstrate each field type included
     */
    $mkgd_side_meta_fields = new_cmb2_box(array(
        'id' => $prefix . 'side-metabox',
        'title' => esc_html__('MKGD Shortcode', 'cmb2'),
        'context' => 'side',
        'priority' => 'low',
        'object_types' => array('mkgd'), // Post type
    ));

    $mkgd_side_meta_fields->add_field(array(
        'name' => esc_html__('[MKGD id="'.$post_id.'"]', 'cmb2'),
        'desc' => esc_html__('Use this shorcode in posts/pages', 'cmb2'),
        'id' => $prefix . 'sc',
        'type' => 'title',
    ));
}


/*
 * Create Donation Meta Box
 */
add_action('cmb2_admin_init', 'mkgd_donation_metabox');
function mkgd_donation_metabox()
{
    $prefix = "mkgd_";

    $post_id = 0;

    // Get the current ID
    if ( isset( $_GET['post'] ) && !is_array( $_GET['post'] ) ) {
        $post_id = $_GET['post'];
    } elseif ( isset( $_POST['post_ID'])  ) {
        $post_id = $_POST['post_ID'];
    }

    
    $mkgd_side_meta_fields = new_cmb2_box(array(
        'id' => $prefix . 'donation-metabox',
        'title' => esc_html__('DONATE', 'cmb2'),
        'context' => 'side',
        'priority' => 'low',
        'object_types' => array('mkgd'), // Post type
    ));

    $mkgd_side_meta_fields->add_field(array(
        'name' => esc_html__('If you like the plugin, you can buy me a beer !!!', 'cmb2'),
        'desc' => '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7N283YV4KLEQ2" title="Donate" target="_blank"><img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" alt="Donate" title="Donate" /></a>',        
        'id' => $prefix . 'donate',
        'type' => 'title',
    ));
}

/*
 *
 * Creat Settings Page
 *
 */

add_action('cmb2_admin_init', 'mkgd_settings_page');
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function mkgd_settings_page()
{
    $prefix = "mkgd_";

    /**
     * Registers options page menu item and form.
     */
    $mkgd_settings = new_cmb2_box(array(
        'id' => $prefix . 'settings_page',
        'title' => esc_html__('MKGD Settings', 'cmb2'),
        'object_types' => array('options-page'),
        'menu_title' => esc_html__('Settings', 'cmb2'),
        'parent_slug' => 'edit.php?post_type=mkgd',
        'option_key' => $prefix . 'settings', // The option key and admin menu page slug.
        'save_button'     => esc_html__( 'Save', 'cmb2' ),

    ));

    $mkgd_settings->add_field(array(
        'name' => esc_html__('Google Maps API Key', 'cmb2'),
        'desc' => esc_html__('Add you Google Maps API key', 'cmb2'),
        'id' => $prefix . 'gmaps_api_key',
        'type' => 'text',
    ));

    $mkgd_settings->add_field(array(
        'name' => esc_html__('Language', 'cmb2'),
        'desc' => esc_html__('Default language for Google map', 'cmb2'),
        'id' => $prefix . 'language',
        'type' => 'select',
        'default' => 'en',
        'options' => array(
            'af' => __('Afrikaans', 'cmb2'),
            'sq' => __('Albanian', 'cmb2'),
            'am' => __('Amharic', 'cmb2'),
            'ar' => __('Arabic', 'cmb2'),
            'hy' => __('Armenian', 'cmb2'),
            'az' => __('Azerbaijani', 'cmb2'),
            'eu' => __('Basque', 'cmb2'),
            'be' => __('Belarusian', 'cmb2'),
            'bn' => __('Bengali', 'cmb2'),
            'bs' => __('Bosnian', 'cmb2'),
            'bg' => __('Bulgarian', 'cmb2'),
            'my' => __('Burmese', 'cmb2'),
            'ca' => __('Catalan', 'cmb2'),
            'zh' => __('Chinese', 'cmb2'),
            'zh-CN' => __('Chinese (Simplified)', 'cmb2'),
            'zh-HK' => __('Chinese (Hong Kong)', 'cmb2'),
            'zh-TW' => __('Chinese (Traditional)', 'cmb2'),
            'hr' => __('Croatian', 'cmb2'),
            'cs' => __('Czech', 'cmb2'),
            'da' => __('Danish', 'cmb2'),
            'nl' => __('Dutch', 'cmb2'),
            'en' => __('English', 'cmb2'),
            'en-AU' => __('English (Australian)', 'cmb2'),
            'en-GB' => __('English (Great Britain)', 'cmb2'),
            'et' => __('Estonian', 'cmb2'),
            'fa' => __('Farsi', 'cmb2'),
            'fi' => __('Finnish', 'cmb2'),
            'fil' => __('Filipino', 'cmb2'),
            'fr' => __('French', 'cmb2'),
            'fr-CA' => __('French (Canada)', 'cmb2'),
            'gl' => __('Galician', 'cmb2'),
            'ka' => __('Georgian', 'cmb2'),
            'de' => __('German', 'cmb2'),
            'el' => __('Greek', 'cmb2'),
            'gu' => __('Gujarati', 'cmb2'),
            'iw' => __('Hebrew', 'cmb2'),
            'hi' => __('Hindi', 'cmb2'),
            'hu' => __('Hungarian', 'cmb2'),
            'is' => __('Icelandic', 'cmb2'),
            'id' => __('Indonesian', 'cmb2'),
            'it' => __('Italian', 'cmb2'),
            'ja' => __('Japanese', 'cmb2'),
            'kn' => __('Kannada', 'cmb2'),
            'kk' => __('Kazakh', 'cmb2'),
            'km' => __('Khmer', 'cmb2'),
            'ko' => __('Korean', 'cmb2'),
            'ky' => __('Kyrgyz', 'cmb2'),
            'lo' => __('Lao', 'cmb2'),
            'lv' => __('Latvian', 'cmb2'),
            'lt' => __('Lithuanian', 'cmb2'),
            'mk' => __('Macedonian', 'cmb2'),
            'ms' => __('Malay', 'cmb2'),
            'ml' => __('Malayalam', 'cmb2'),
            'mr' => __('Marathi', 'cmb2'),
            'mn' => __('Mongolian', 'cmb2'),
            'ne' => __('Nepali', 'cmb2'),
            'no' => __('Norwegian', 'cmb2'),
            'pl' => __('Polish', 'cmb2'),
            'pt' => __('Portuguese', 'cmb2'),
            'pt-BR' => __('Portuguese (Brazil)', 'cmb2'),
            'pt-PT' => __('Portuguese (Portugal)', 'cmb2'),
            'pa' => __('Punjabi', 'cmb2'),
            'ro' => __('Romanian', 'cmb2'),
            'ru' => __('Russian', 'cmb2'),
            'sr' => __('Serbian', 'cmb2'),
            'si' => __('Sinhalese', 'cmb2'),
            'sk' => __('Slovak', 'cmb2'),
            'sl' => __('Slovenian', 'cmb2'),
            'es' => __('Spanish', 'cmb2'),
            'es-419' => __('Spanish (Latin America)', 'cmb2'),
            'sw' => __('Swahili', 'cmb2'),
            'sv' => __('Swedish', 'cmb2'),
            'ta' => __('Tamil', 'cmb2'),
            'te' => __('Telugu', 'cmb2'),
            'th' => __('Thai', 'cmb2'),
            'tr' => __('Turkish', 'cmb2'),
            'uk' => __('Ukrainian', 'cmb2'),
            'ur' => __('Urdu', 'cmb2'),
            'uz' => __('Uzbek', 'cmb2'),
            'vi' => __('Vietnamese', 'cmb2'),
            'zu' => __('Zulu', 'cmb2'),
        ),
    ));
    /*$mkgd_settings->add_field(array(
        'name' => '',//esc_html__('If you like the plugin, you can buy me a beer', 'cmb2'),
        'desc' => '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7N283YV4KLEQ2" title="Donate" target="_blank"><img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" alt="Donate" title="Donate" /></a>',
        'id' => $prefix . 's',
        'type' => 'title',
    ));*/
}



