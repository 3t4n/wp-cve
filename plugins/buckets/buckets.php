<?php
/*
Plugin Name: Buckets
Plugin URI: http://www.matthewrestorff.com
Description: A Widget Alternative. Add reusable content inside of content. On a per page basis.
Author: Matthew Restorff
Version: 0.3.9
Author URI: http://www.matthewrestorff.com
*/

/*--------------------------------------------------------------------------------------
*
*	Buckets
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/
$bucket_version = '0.3.9';
add_action('init', 'buckets_init');
add_action('admin_head', 'buckets_admin_head');
add_shortcode('bucket', 'buckets_shortcode');
add_action('add_meta_boxes', 'bucket_shortcode_meta_box', 10, 1);
add_filter('manage_edit-buckets_columns', 'bucket_columns');
add_filter('contextual_help', 'add_bucket_help_tab', 10, 2);
add_action('manage_buckets_posts_custom_column', 'bucket_columns_content', 10, 2);
include_once ABSPATH.'wp-admin/includes/plugin.php'; //used for is_plugin_active function

/*--------------------------------------------------------------------------------------
*
*	init
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/

function buckets_init()
{
    $icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSI3MnB4IiBoZWlnaHQ9IjcycHgiIHZpZXdCb3g9IjAgMCA3MiA3MiIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNzIgNzIiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwYXRoIGZpbGw9Im5vbmUiIGQ9Ik0xMC42MDIsMzMuNDQzTDkuODY2LDIyLjQ0NEg5LjY3NGMtMS43MTYsMC0zLjMwNS0wLjQxLTQuNjcyLTEuMDgzQzUuNTM4LDI2LjA2Myw3LjQyNCwzMC4xLDEwLjYwMiwzMy40NDN6Ii8+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTY3LjMsMjAuNmMtMS4wNTcsMC43NTUtMi4zMjQsMS4zMTItMy43MzgsMS42bC0wLjUwNCwxMS4yODRjMC4zNTQtMC40MzYsMC42Ny0wLjg5NiwwLjk3Ny0xLjM1OGMxLjQ0MS0yLjgyLDIuMzM0LTYuMjI4LDIuMzkxLTEwLjM2bDAuODIsMC4wMDlDNjcuMjY3LDIxLjM4MSw2Ny4zLDIxLDY3LjMsMjAuNnoiLz48cGF0aCBmaWxsPSIjNzg3ODc4IiBkPSJNMzUuODA1LDUwLjM4MWMtNS40NDMtMC4wMTQtMTUuNzY0LTEuMDEyLTI0LjU1OS03LjMyNGwxLjM0MiwyMC4wNTFjMCwwLDEzLjY4MSwzLjg3OSwyNy4xODMsMy44NzljMTEuOTk2LDAsMjEuOTY5LTMuODc5LDIxLjk2OS0zLjg3OWwwLjkyNC0yMC43M2MtNy4yNDQsNS42ODQtMTcuNCw4LjAwNC0yNi42OTEsOC4wMDRDMzUuOTE3LDUwLjM4MSwzNS44NjEsNTAuMzgxLDM1LjgwNSw1MC4zODF6Ii8+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTY0LjAzNCwzMi4xMjVjMS45NjEtMi45MjYsMy4wMjktNi4zODUsMy4yMTEtMTAuMzUxbC0wLjgyLTAuMDA5QzY2LjM2OCwyNS44OTcsNjUuNDc2LDI5LjMwNSw2NC4wMzQsMzIuMTI1eiIvPjxwYXRoIGZpbGw9IiM3ODc4NzgiIGQ9Ik03MC40MzgsMTMuODA3Yy0wLjc4My0zLjM3LTQuNjc4LTUuODg3LTkuMDc2LTUuODg3YzAsMC0xMi41ODgtMi45MDYtMjYuMzg3LTIuOTA2QzIyLjcyOSw1LjAxNCw5LjY3NCw3LjkyLDkuNjc0LDcuOTJjLTQuOTcyLDAtOS4wMDMsMy4yNDctOS4wMDMsNy4yNjJjMCwwLjUxOCwwLjA3MiwxLjAyLDAuMiwxLjUwN2MtMC4xNjcsMi45NjEsMC4wNjYsNS43NDUsMC43MTksOC4zM2MxLjEzNyw0LjUsMy40ODgsOC40MzMsNy4wODcsMTEuNzI1YzcuMzQyLDYuNzEzLDE4LjQ4Nyw5LjQwNywyNi40NjksOS42OTRjMC41NzcsMC4wMiwxLjE3MSwwLjAyOSwxLjc3NCwwLjAyOWM4Ljc3NSwwLDIwLjAwNC0yLjIzOCwyNy4wOTQtOC42MTdjNC41OTgtNC4xMzksNi44NzktOS41Myw3LjI4Ny0xNi4wMzlDNzEuNTExLDE4LjUwMiw3MC40MzgsMTMuODA3LDcwLjQzOCwxMy44MDd6IE01LjAwMiwyMS4zNjFjMS4zNjcsMC42NzMsMi45NTYsMS4wODMsNC42NzIsMS4wODNoMC4xOTFsMC43MzYsMTAuOTk5QzcuNDI0LDMwLjEsNS41MzgsMjYuMDYzLDUuMDAyLDIxLjM2MXogTTM0LjkxNSwxNy44NDljLTEyLjYyOS0wLjA0MS0yMi44NTItMS43ODgtMjIuODQ0LTMuODk3YzAuMDEzLTIuMTE4LDEwLjI1LTMuNzk5LDIyLjg2OS0zLjc1MWMxMi42MzUsMC4wMzYsMjIuODU5LDEuNzc2LDIyLjg1NCwzLjg5NEM1Ny43NzIsMTYuMjAzLDQ3LjUzNCwxNy44ODUsMzQuOTE1LDE3Ljg0OXogTTY3LjI0NSwyMS43NzRjLTAuMTgyLDMuOTY2LTEuMjUsNy40MjUtMy4yMTEsMTAuMzUxYy0wLjMwNywwLjQ2Mi0wLjYyMywwLjkyMi0wLjk3NywxLjM1OEw2My41NjIsMjIuMmMxLjQxNC0wLjI4OCwyLjY4Mi0wLjg0NSwzLjczOC0xLjZDNjcuMywyMSw2Ny4yNjcsMjEuMzgxLDY3LjI0NSwyMS43NzR6Ii8+PC9nPjwvc3ZnPg==';

    // Setup Buckets
    $labels = array(
        'name' => __('Buckets', 'buckets'),
        'singular_name' => __('Bucket', 'buckets'),
        'add_new' => __('Add New', 'buckets'),
        'add_new_item' => __('Add New Bucket', 'buckets'),
        'edit_item' => __('Edit Bucket', 'buckets'),
        'new_item' => __('New Bucket', 'buckets'),
        'view_item' => __('View Bucket', 'buckets'),
        'search_items' => __('Search Buckets', 'buckets'),
        'not_found' => __('No Buckets found', 'buckets'),
        'not_found_in_trash' => __('No Buckets found in Trash', 'buckets'),
    );

    register_post_type('buckets', array(
        'labels' => $labels,
        'show_ui' => true,
        'menu_icon' => $icon_svg,
        '_builtin' => false,
        'capability_type' => 'page',
        'hierarchical' => true,
        'rewrite' => false,
        'query_var' => 'buckets',
        'exclude_from_search' => true,
        'supports' => array(
            'title', 'editor', 'revisions',
        ),
        'show_in_menu' => true,
        'show_in_nav_menu' => true,
        'publicly_queryable' => false,
    ));
}

function bucket_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'shortcode' => __('Shortcode'),
        'related' => __('Featured On'),
        'type' => __('Type'),
        'date' => __('Date'),
    );

    return $columns;
}

function bucket_columns_content($column, $post_id)
{
    global $post;
    global $wpdb;

    switch ($column) {

        case 'shortcode' :

            echo "<input type=\"text\" readonly value='[bucket id=\"".$post_id.'" title="'.get_the_title($post_id)."\"]' onFocus=\"this.select()\" style=\"width: 100%;\" />";

            break;

        case 'related':

            $related = get_posts(array(
                'post_type' => 'any',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'sidebar',
                        'value' => '"'.get_the_ID().'"',
                        'compare' => 'LIKE',
                    ),
                ),
            ));

            if ($related) {
                echo 'Sidebar: ';
                $c = 0;
                foreach ($related as $p) {
                    if ($c == 0) {
                        ++$c;
                    } else {
                        echo ' | ';
                    }
                    echo '<a href="'.get_edit_post_link($p->ID).'">'.$p->post_title.'</a> ';
                }
                echo '<br />';
            }

            $sc = '[bucket id="'.$post_id.'"';
            $shortcodes = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type!='revision' AND post_content LIKE '%$sc%'");

            if ($shortcodes) {
                echo 'Shortcode: ';
                $c = 0;
                foreach ($shortcodes as $s) {
                    if ($c == 0) {
                        ++$c;
                    } else {
                        echo ' | ';
                    }
                    echo '<a href="'.get_edit_post_link($s->ID).'">'.$s->post_title.'</a> ';
                }
            }

            break;

        case 'type':

            echo get_bucket_type($post_id);

            break;

        default :
            break;
    }
}

function add_bucket_help_tab()
{
    if (get_current_screen()->post_type == 'buckets') {
        get_current_screen()->add_help_tab(array(
            'id' => 'buckets-help-tab',
            'title' => __('Documentation'),
            'content' => __('<p>Confused? View the documentation on <a href="http://goo.gl/nf8WTY">Google Docs</a> or <a href="mailto:m@matthewrestorff.com?subject=Buckets">email me</a> if you have any questions or comments.</p>'),
            ));
    }
}

/*--------------------------------------------------------------------------------------
*
*	include_field_types_buckets
*	Registers the Buckets fields and adds the default field groups.
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/
function include_field_types_buckets()
{
    global $acf_version;
    remove_post_type_support('buckets', 'editor');
    include_once WP_PLUGIN_DIR.'/buckets/fields/acf-buckets-v'.$acf_version.'.php';
    create_bucket_field_groups($acf_version);
}
// If ACF is loaded
if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
    add_action('acf/include_field_types', 'include_field_types_buckets');
    $acf_version = '5';
} elseif (is_plugin_active('advanced-custom-fields/acf.php') && is_plugin_active('acf-flexible-content/acf-flexible-content.php')) {
    add_action('acf/register_fields', 'include_field_types_buckets');
    $acf_version = '4';
}

/*--------------------------------------------------------------------------------------
*
*   buckets_pre_save_post
*
*   @author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/
function buckets_pre_save_post( $post_id ) {

    // check if this is to be a new post
    if( $post_id != 'new_post' )
    {
        return $post_id;
    }

    $title = (isset($_POST['bucket_title']) && $_POST['bucket_title'] != '') ? $_POST['bucket_title'] : 'New Bucket';

    // Create a new post
    $post = array(
        'post_status'  => 'publish',
        'post_title'  => $title,
        'post_type'  => 'buckets',
    );

    // insert the post
    $post_id = wp_insert_post( $post );

    // update $_POST['return']
    $_POST['return'] = add_query_arg( array('post_id' => $post_id), $_POST['return'] );

    // return the new ID
    return $post_id;
}

add_filter('acf/pre_save_post' , 'buckets_pre_save_post' );


/*--------------------------------------------------------------------------------------
*
*	create_tinymce_button
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/

function create_tinymce_button()
{
    // Don't bother doing this stuff if the current user lacks permissions
   if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
       return;
   }

   // Add only in Rich Editor mode
   if (get_user_option('rich_editing') == 'true') {
       add_filter('mce_external_plugins', 'bucket_add_tinymce_plugin');
       add_filter('mce_buttons', 'bucket_register_tinymce_button');
   }
    wp_enqueue_style('buckets-tinymce', plugins_url('css/tinymce.css', __FILE__));
}

function bucket_add_tinymce_plugin($plugin_array)
{
    $plugin_array['buckets'] = plugins_url().'/buckets/js/tinymce/bucketshortcode.js';

    return $plugin_array;
}

function bucket_register_tinymce_button($buttons)
{
    array_push($buttons, '|', 'buckets');

    return $buttons;
}


/*--------------------------------------------------------------------------------------
*
*	create_field_group
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/
function create_bucket_field_groups($acf_version)
{
    // ACF Pro v5+
    if (intval($acf_version) >= 5) {
        $args = array(
          'name' => 'group_buckets',
          'post_type' => 'acf-field-group',
          'numberposts' => 1,
        );
        $field_group_exists = get_posts($args);

        if (empty($field_group_exists)) {

            //The Buckets Field Group
            $buckets_field_group = array(
                'post_title' => 'Buckets',
                'post_name' => 'group_buckets',
                'post_type' => 'acf-field-group',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_content' => 'a:6:{s:8:"location";a:1:{i:0;a:1:{i:0;a:3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:7:"buckets";}}}s:8:"position";s:6:"normal";s:5:"style";s:8:"seamless";s:15:"label_placement";s:3:"top";s:21:"instruction_placement";s:5:"label";s:14:"hide_on_screen";a:14:{i:0;s:9:"permalink";i:1;s:11:"the_content";i:2;s:7:"excerpt";i:3;s:13:"custom_fields";i:4;s:10:"discussion";i:5;s:8:"comments";i:6;s:4:"slug";i:7;s:6:"author";i:8;s:6:"format";i:9;s:15:"page_attributes";i:10;s:14:"featured_image";i:11;s:10:"categories";i:12;s:4:"tags";i:13;s:15:"send-trackbacks";}}',
            );
            $buckets_field_group_id = wp_insert_post($buckets_field_group);

            // //The Buckets Field Group Fields
            $buckets_field_group_field = array(
                'post_title' => 'Buckets',
                'post_excerpt' => 'buckets',
                'post_name' => 'field_buckets',
                'post_type' => 'acf-field',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => $buckets_field_group_id,
                'post_content' => 'a:9:{s:4:"type";s:16:"flexible_content";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:12:"button_label";s:10:"Add Bucket";s:3:"min";s:0:"";s:3:"max";s:0:"";s:7:"layouts";a:1:{i:0;a:6:{s:3:"key";s:13:"54e64d2c4340c";s:5:"label";s:13:"Visual Editor";s:4:"name";s:13:"visual_editor";s:7:"display";s:5:"block";s:3:"min";s:0:"";s:3:"max";s:0:"";}}}',
            );
            $buckets_field_group_field_id = wp_insert_post($buckets_field_group_field);

            $buckets_field_group_sub_field = array(
                'post_title' => 'Content',
                'post_excerpt' => 'content',
                'post_name' => 'field_buckets_visual_editor',
                'post_type' => 'acf-field',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => $buckets_field_group_field_id,
                'post_content' => 'a:10:{s:4:"type";s:7:"wysiwyg";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"54e64d2c4340c";s:13:"default_value";s:0:"";s:4:"tabs";s:3:"all";s:7:"toolbar";s:4:"full";s:12:"media_upload";i:1;}',
            );
            $buckets_field_group_field_id = wp_insert_post($buckets_field_group_sub_field);

            //The Buckets Sidebar Field Group
            $buckets_sidebar_field_group = array(
                'post_title' => 'Sidebars',
                'post_name' => 'group_buckets_sidebars',
                'post_type' => 'acf-field-group',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_content' => 'a:6:{s:8:"location";a:1:{i:0;a:1:{i:0;a:3:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"page";}}}s:8:"position";s:6:"normal";s:5:"style";s:8:"seamless";s:15:"label_placement";s:3:"top";s:21:"instruction_placement";s:5:"label";s:14:"hide_on_screen";s:0:"";}',
            );
            $buckets_sidebar_field_group_id = wp_insert_post($buckets_sidebar_field_group);

            //The Buckets Sidebar Field Group Fields
            $buckets_sidebar_field_group_field = array(
                'post_title' => 'Sidebar',
                'post_excerpt' => 'sidebar',
                'post_name' => 'field_buckets_sidebar',
                'post_type' => 'acf-field',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_parent' => $buckets_sidebar_field_group_id,
                'post_content' => 'a:9:{s:4:"type";s:7:"buckets";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:3:"max";s:0:"";s:9:"post_type";a:1:{i:0;s:7:"buckets";}s:7:"filters";a:1:{i:0;s:6:"search";}s:13:"return_format";s:2:"ID";}',
            );
            $buckets_sidebar_field_group_field_id = wp_insert_post($buckets_sidebar_field_group_field);
        }
    } elseif ($acf_version == '4') {
        $args = array(
          'name' => 'acf_buckets',
          'post_type' => 'acf',
          'numberposts' => 1,
        );
        $field_group_exists = get_posts($args);

        if (empty($field_group_exists)) {
            $buckets = array(
                'post_title' => 'Buckets',
                'post_name' => 'acf_buckets',
                'post_status' => 'publish',
                'post_type' => 'acf',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
            );
            $post_id = wp_insert_post($buckets);

            add_post_meta($post_id, 'field_bucketskey777', 'a:10:{s:3:"key";s:19:"field_bucketskey777";s:5:"label";s:7:"Buckets";s:4:"name";s:7:"buckets";s:4:"type";s:16:"flexible_content";s:12:"instructions";s:0:"";s:8:"required";s:1:"0";s:7:"layouts";a:1:{i:0;a:4:{s:5:"label";s:13:"Visual Editor";s:4:"name";s:13:"visual_editor";s:7:"display";s:5:"table";s:10:"sub_fields";a:1:{i:0;a:7:{s:5:"label";s:7:"Content";s:4:"name";s:7:"content";s:4:"type";s:7:"wysiwyg";s:7:"toolbar";s:4:"full";s:12:"media_upload";s:3:"yes";s:3:"key";s:19:"field_50402dcb0fb1b";s:8:"order_no";s:1:"0";}}}}s:10:"sub_fields";a:1:{i:0;a:1:{s:3:"key";s:19:"field_50402dbe9787c";}}s:12:"button_label";s:12:"+ Add Bucket";s:8:"order_no";s:1:"0";}');
            add_post_meta($post_id, 'allorany', 'all');
            add_post_meta($post_id, 'rule', 'a:4:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:7:"buckets";s:8:"order_no";s:1:"0";}');
            add_post_meta($post_id, 'position', 'normal');
            add_post_meta($post_id, 'layout', 'no_box');
            add_post_meta($post_id, 'hide_on_screen', 'a:9:{i:0;s:11:"the_content";i:1;s:7:"excerpt";i:2;s:13:"custom_fields";i:3;s:10:"discussion";i:4;s:8:"comments";i:5;s:4:"slug";i:6;s:6:"author";i:7;s:6:"format";i:8;s:14:"featured_image";}');

            $sidebars = array(
                'post_title' => 'Sidebars',
                'post_name' => 'acf_sidebars',
                'post_status' => 'publish',
                'post_type' => 'acf',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
            );
            $post_id = wp_insert_post($sidebars);

            add_post_meta($post_id, 'field_bucketskey778', 'a:9:{s:3:"key";s:19:"field_bucketskey778";s:5:"label";s:7:"Sidebar";s:4:"name";s:7:"sidebar";s:4:"type";s:7:"buckets";s:12:"instructions";s:0:"";s:8:"required";s:1:"0";s:3:"max";s:0:"";s:17:"conditional_logic";a:3:{s:6:"status";s:1:"0";s:5:"rules";a:1:{i:0;a:2:{s:5:"field";s:4:"null";s:8:"operator";s:2:"==";}}s:8:"allorany";s:3:"all";}s:8:"order_no";i:0;}');
            add_post_meta($post_id, 'allorany', 'all');
            add_post_meta($post_id, 'rule', 'a:4:{s:5:"param";s:9:"post_type";s:8:"operator";s:2:"==";s:5:"value";s:4:"page";s:8:"order_no";s:1:"0";}');
            add_post_meta($post_id, 'position', 'normal');
            add_post_meta($post_id, 'layout', 'no_box');
            add_post_meta($post_id, 'hide_on_screen', '');
        }
    }
}

/*--------------------------------------------------------------------------------------
*
*	admin_head
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/

function buckets_admin_head()
{
    global $bucket_version, $acf_version;

    if (isset($GLOBALS['post_type']) && $GLOBALS['post_type'] == 'buckets') {
        wp_enqueue_style('buckets', plugins_url('', __FILE__).'/css/buckets.css?v='.$bucket_version);
    }

    if ($GLOBALS['pagenow'] == 'post.php' || (isset($GLOBALS['_GET']['page']) && $GLOBALS['_GET']['page'] == 'acf-options')) {
        wp_enqueue_style('bucket-field', plugins_url('', __FILE__).'/css/bucket_field.css?v='.$bucket_version, array('thickbox'));
        wp_enqueue_script('buckets', plugins_url('', __FILE__).'/js/buckets.js?v='.$bucket_version);
    }
    if (!empty($acf_version)){
        wp_enqueue_script('bucket-field-script', plugins_url('', __FILE__).'/js/bucket_field_v' . $acf_version . '.js?v='.$bucket_version, array('jquery-ui-sortable'));
    }

    // The WP Thickbox dimensions are hard coded into the media-upload. With this we strip it and make our own.
    wp_deregister_script('media-upload');
    wp_enqueue_script('media-upload',plugins_url('', __FILE__).'/js/media-upload.js?v='.$bucket_version,array('thickbox'));

    // Create TinyMCE Button
    create_tinymce_button();
}

/*--------------------------------------------------------------------------------------
*
*	buckets_shortcode
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/

function buckets_shortcode($arg)
{
    $return = get_bucket($arg['id']);
    foreach ($arg as $key => $value) {
        $return = str_replace("%%$key%%", $value, $return);
    }

    return $return;
}

/*--------------------------------------------------------------------------------------
*
*	shortcode_meta_box
*
*	@author Matthew Restorff
*
*-------------------------------------------------------------------------------------*/

function bucket_shortcode_meta_box()
{
    add_meta_box('buckets-shortcode', 'Shortcode', 'bucket_shortcode_meta_box_template', 'buckets', 'normal', 'high');
}

function bucket_shortcode_meta_box_template()
{
    include 'admin/shortcode.php';
}

/*--------------------------------------------------------------------------------------
*
*	get_bucket_type
*	outputs the ACF Flexible Content Field Layout for the first Bucket type
*
*	@author Matthew Restorff
*	@params bucket_id - post id of the bucket element
*
*-------------------------------------------------------------------------------------*/
function get_bucket_type($bucket_id)
{
    $meta = get_post_meta($bucket_id, 'buckets');
    $type = false;
    if (!empty($meta[0])) {
        foreach ($meta[0] as $m) {
            $type .= ucwords(str_replace('_', ' ', $m)).', ';
        }
    }

    return substr($type, 0, -2);
}

/*--------------------------------------------------------------------------------------
*
*	get_bucket
*	outputs the bucket template
*
*	@author Matthew Restorff
*	@params bucket_id - post id of the bucket element
*
*-------------------------------------------------------------------------------------*/

function get_bucket($bucket_id)
{
    $return = apply_filters('the_content', get_post_field('post_content', $bucket_id));

    //If ACF is Active perform some wizardry
    if ((is_plugin_active('advanced-custom-fields/acf.php') && is_plugin_active('acf-flexible-content/acf-flexible-content.php')) || is_plugin_active('advanced-custom-fields-pro/acf.php')) {
        while ( have_rows('buckets', $bucket_id) ) { the_row();
            ob_start();
            $layout = get_row_layout();

            $file = str_replace(' ', '', $layout).'.php';
            $path = (file_exists(TEMPLATEPATH.'/buckets/'.$file)) ? TEMPLATEPATH.'/buckets/'.$file : WP_PLUGIN_DIR.'/buckets/templates/'.$file;
            if (file_exists($path)) {
                include $path;
            } else {
                echo 'Bucket template does not exist.';
            }

            $return .= ob_get_clean();
        }
    }

    return $return;
}
