<?php

/*
Plugin Name: LPagery
Plugin URI: https://lpagery.io/
Description: Create hundreds or even thousands of landingpages for local businesses, services etc.
Version: 1.4.11
Author: LPagery
License: GPLv2 or later
*/
// Create a helper function for easy SDK access.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'lpagery_fs' ) ) {
    lpagery_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'lpagery_fs' ) ) {
        function lpagery_fs()
        {
            global  $lpagery_fs ;
            
            if ( !isset( $lpagery_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $lpagery_fs = fs_dynamic_init( array(
                    'id'              => '9985',
                    'slug'            => 'lpagery',
                    'premium_slug'    => 'lpagery-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_708ce9268236202bb1fd0aceb0be2',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'customers',
                    'menu'            => array(
                    'slug' => 'lpagery',
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $lpagery_fs;
        }
        
        // Init Freemius.
        lpagery_fs();
        // Signal that SDK was initiated.
        do_action( 'lpagery_fs_loaded' );
    }
    
    require_once plugin_dir_path( __FILE__ ) . '/data/LPageryDao.php';
    require_once plugin_dir_path( __FILE__ ) . '/io/Mapper.php';
    add_action( 'admin_menu', 'lpagery_setup_menu' );
    function lpagery_setup_menu()
    {
        $icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI2LjIuMSwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCA1MjcuMTYgNjc0LjQ1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MjcuMTYgNjc0LjQ1OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+Cgkuc3Qwe2ZpbGw6I0ZGRkZGRjt9Cgkuc3Qxe2ZpbGw6bm9uZTtzdHJva2U6I0ZGRkZGRjtzdHJva2Utd2lkdGg6MztzdHJva2UtbWl0ZXJsaW1pdDoxMDt9Cjwvc3R5bGU+CjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yNTAuNDUsMzQ3LjYySDExMi4zOWMwLTAuMDEsMC0wLjAyLDAtMC4wMmwtMC4wMSwwLjAxbDAtMTg0LjQ5YzAtMzEuMDMtMjUuMTUtNTYuMTgtNTYuMTgtNTYuMTgKCWMwLDAsMCwwLTAuMDEsMEMyNS4xNiwxMDYuOTMsMCwxMzIuMDksMCwxNjMuMTFsMCwyNDAuNjJjMCwyOS44OSwyMi4wOCw1NC4yOSw1MS40OSw1Ni4wNGMxLjU4LDAuMTMsMy4xNiwwLjIyLDQuNzcsMC4yMgoJbDg5LjkxLTAuMTRsMzQuMzktMC4wMmwwLjAzLTAuMDNsMi4wMSwwTDI1MC40NSwzNDcuNjJ6Ii8+CjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik01MDMuODcsMjg2Ljc1Yy0xLjMyLTAuOTYtMi42OC0xLjg5LTQuMS0yLjc1TDM4OC43LDIxNi43OGwtMC4wMSwwbDAsMGwtMTAuNTUtNi4zOWwtMzIuMDItMTcuOTlsLTI5LjU1LDQ4LjMzCglsLTI4LjM5LDQ2LjMxbDEwNS4zMSw2My45YzAsMC4wMS0wLjAxLDAuMDMtMC4wMSwwLjAzbDAuMDIsMGwtOTUuNzIsMTU3LjcyYy0xNi4wOSwyNi41My03LjY0LDYxLjA5LDE4Ljg5LDc3LjE4CgljMjYuNTMsMTYuMSw2MS4wOSw3LjY0LDc3LjE4LTE4Ljg5bDEyNC44My0yMDUuNzFDNTM0LjE2LDMzNS43Nyw1MjcuOTgsMzAzLjUyLDUwMy44NywyODYuNzV6Ii8+CjxsaW5lIGNsYXNzPSJzdDEiIHgxPSI1Ni45NyIgeTE9IjY2NS4yNCIgeDI9IjQ2My43OCIgeTI9IjAiLz4KPC9zdmc+Cg==';
        $icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;
        add_menu_page(
            'LPagery',
            'LPagery',
            'manage_options',
            'lpagery',
            'init',
            $icon_data_uri
        );
    }
    
    include_once plugin_dir_path( __FILE__ ) . '/io/AjaxActions.php';
    include_once plugin_dir_path( __FILE__ ) . '/controller/SettingsController.php';
    include_once plugin_dir_path( __FILE__ ) . '/data/LPageryDao.php';
    function lpagery_get_placeholder_counts()
    {
        global  $wpdb ;
        $table_name_process = $wpdb->prefix . 'lpagery_process';
        $result = $wpdb->get_row( "SELECT exists(select *\n              FROM INFORMATION_SCHEMA.TABLES\n              WHERE table_name = '{$table_name_process}'\n                and create_time <= '2023-09-04 00:00:00') as created" );
        if ( $result->created ) {
            return null;
        }
        if ( lpagery_fs()->is_free_plan() ) {
            return array(
                "plan"         => "free",
                "placeholders" => 3,
            );
        }
        return array(
            "plan"         => "extended",
            "placeholders" => null,
        );
    }
    
    function lpagery_enqueue_scripts()
    {
        include_once plugin_dir_path( __FILE__ ) . 'includes/Enqueues.php';
    }
    
    add_action( 'admin_enqueue_scripts', 'lpagery_enqueue_scripts' );
    function init()
    {
        LPageryDao::init_db();
        include_once plugin_dir_path( __FILE__ ) . 'views/main.php';
    }
    
    add_filter( 'posts_where', 'lpagery_source_filter' );
    function lpagery_source_filter( $where )
    {
        global  $wpdb ;
        $table_name_process = $wpdb->prefix . 'lpagery_process';
        $table_name_process_post = $wpdb->prefix . 'lpagery_process_post';
        if ( !isset( $_GET['lpagery_process'] ) && !isset( $_GET['lpagery_template'] ) ) {
            return $where;
        }
        
        if ( isset( $_GET['lpagery_template'] ) ) {
            $lpagery_template_id = $_GET['lpagery_template'];
            
            if ( $lpagery_template_id != '' ) {
                $lpagery_template_id = intval( $lpagery_template_id );
                $where .= " AND EXISTS (select pp.id\n                    from {$table_name_process_post} pp\n                             inner join {$table_name_process} p on p.id = pp.lpagery_process_id\n                    where p.post_id = {$lpagery_template_id} and pp.post_id = {$wpdb->posts}.id)";
                return $where;
            }
        
        } else {
            $lpagery_process_id = $_GET['lpagery_process'];
            
            if ( $lpagery_process_id != '' ) {
                $lpagery_process_id = intval( $lpagery_process_id );
                $where .= " AND EXISTS (select pp.id\n                    from {$table_name_process_post} pp\n                          \n                    where pp.lpagery_process_id = {$lpagery_process_id} and pp.post_id = {$wpdb->posts}.id)";
                return $where;
            }
        
        }
        
        return $where;
    }
    
    add_action( 'restrict_manage_posts', 'lpagery_customized_filters' );
    function lpagery_customized_filters()
    {
        ?>
        <input id="lpagery_reset_filter" class="button" type="button" value="Reset LPagery Filter"
               style="display: none">
        <?php 
    }
    
    add_action( 'admin_footer', 'lpagery_add_filter_text_process' );
    add_action( 'admin_footer', 'lpagery_add_filter_text_template_post' );
    function lpagery_add_filter_text_process()
    {
        if ( !isset( $_GET['lpagery_process'] ) ) {
            return;
        }
        $lpagery_process_id = $_GET['lpagery_process'];
        $process = LPageryDao::lpagery_get_process_by_id( $lpagery_process_id );
        if ( empty($process) ) {
            return;
        }
        $process = LpageryMapper::lpagery_map_process( $process );
        $post_id = $process["post_id"];
        $purpose = $process["display_purpose"];
        $post_title = get_post( $post_id )->post_title;
        $permalink = get_permalink( $post_id );
        
        if ( $post_title ) {
            ?>
            <script>
                jQuery(function ($) {
                    let test = $('<span><?php 
            echo  $purpose ;
            ?> with Template: <a href=<?php 
            echo  $permalink ;
            ?>> <?php 
            echo  $post_title ;
            ?><a/></span')
                    $('<div style="margin-bottom:5px;"></div>').append(test).insertAfter('#wpbody-content .wrap h2:eq(0)');
                });
            </script><?php 
        }
    
    }
    
    function lpagery_add_filter_text_template_post()
    {
        if ( !isset( $_GET['lpagery_template'] ) ) {
            return;
        }
        $lpagery_template_id = $_GET['lpagery_template'];
        $post = get_post( $lpagery_template_id );
        $post_title = $post->post_title;
        $permalink = get_permalink( $post );
        
        if ( $post_title ) {
            ?>
            <script>
                jQuery(function ($) {
                    let test = $('<span>Show all created pages with Template: <a href=<?php 
            echo  $permalink ;
            ?>> <?php 
            echo  $post_title ;
            ?><a/></span')
                    $('<div style="margin-bottom:5px;"></div>').append(test).insertAfter('#wpbody-content .wrap h2:eq(0)');
                });
            </script><?php 
        }
    
    }
    
    if ( !function_exists( 'str_contains' ) ) {
        function str_contains( $haystack, $needle )
        {
            return '' === $needle || false !== strpos( $haystack, $needle );
        }
    
    }
    if ( !function_exists( 'str_starts_with' ) ) {
        function str_starts_with( $haystack, $needle )
        {
            if ( '' === $needle ) {
                return true;
            }
            return 0 === strpos( $haystack, $needle );
        }
    
    }
    if ( !function_exists( 'str_ends_with' ) ) {
        function str_ends_with( $haystack, $needle )
        {
            if ( '' === $haystack && '' !== $needle ) {
                return false;
            }
            $len = strlen( $needle );
            return 0 === substr_compare(
                $haystack,
                $needle,
                -$len,
                $len
            );
        }
    
    }
    add_shortcode( 'lpagery_urls', 'add_lpagery_urls_shortcode' );
    function add_lpagery_urls_shortcode( $atts )
    {
        
        if ( isset( $atts["id"] ) ) {
            $post_ids = LPageryDao::lpagery_get_posts_by_process( $atts["id"] );
            
            if ( !empty($post_ids) ) {
                $list_items = '';
                foreach ( $post_ids as $record ) {
                    $post_id = $record->id;
                    $post_title = get_the_title( $post_id );
                    $post_permalink = get_permalink( $post_id );
                    $list_items .= "<li class='lpagery_created_page_item'><a class='lpagery_created_page_anchor' href='{$post_permalink}'>{$post_title}</a></li>";
                }
                return "<ul class='lpagery_created_page_list'>{$list_items}</ul>";
            }
        
        }
        
        return null;
    }
    
    function lpagery_time_ago( $timestamp )
    {
        $current_time = new DateTime();
        $time_to_compare = DateTime::createFromFormat( 'U', $timestamp );
        $time_difference = $current_time->getTimestamp() - $time_to_compare->getTimestamp();
        $is_future = $time_difference < 0;
        $time_difference = abs( $time_difference );
        $units = [
            "year"   => 365 * 24 * 60 * 60,
            "month"  => 30 * 24 * 60 * 60,
            "week"   => 7 * 24 * 60 * 60,
            "day"    => 24 * 60 * 60,
            "hour"   => 60 * 60,
            "minute" => 60,
            "second" => 1,
        ];
        foreach ( $units as $unit => $value ) {
            
            if ( $time_difference >= $value ) {
                $unit_value = floor( $time_difference / $value );
                $suffix = ( $unit_value == 1 ? "" : "s" );
                $direction = ( $is_future ? "from now" : "ago" );
                return "{$unit_value} {$unit}{$suffix} {$direction}";
            }
        
        }
        return "just now";
    }
    
    function lpagery_add_replace_filename( $form_fields, $post )
    {
        if ( LPagerySettingsController::lpagery_get_image_processing_enabled() ) {
            $form_fields['lpagery_replace_filename'] = array(
                'label' => '<img width="25px" height ="25px" src="' . plugin_dir_url( dirname( __FILE__ ) ) . "/" . plugin_basename( dirname( __FILE__ ) ) . '/freemius/assets/img/lpagery.png"/>Download Filename',
                'input' => 'text',
                'value' => get_post_meta( $post->ID, '_lpagery_replace_filename', true ),
                'helps' => 'The name for LPagery to be taken for downloading images when using this image as an placeholder. The ending will be populated automatically. Please add placeholders from the input file here (e.g. "my-image-in-{city}")',
            );
        }
        return $form_fields;
    }
    
    add_filter(
        'attachment_fields_to_edit',
        'lpagery_add_replace_filename',
        10,
        2
    );
    function lpagery_save_replace_filename_field( $post, $attachment )
    {
        if ( isset( $attachment['lpagery_replace_filename'] ) ) {
            // Update or add the custom field value
            update_post_meta( $post['ID'], '_lpagery_replace_filename', $attachment['lpagery_replace_filename'] );
        }
        return $post;
    }
    
    add_filter(
        'attachment_fields_to_save',
        'lpagery_save_replace_filename_field',
        10,
        2
    );
    if ( lpagery_fs()->is_free_plan() ) {
        wp_clear_scheduled_hook( "lpagery_sync_google_sheet" );
    }
}
