<?php
/*
 * Plugin Name: B Timeline
 * Plugin URI:  https://bplugins.com/
 * Description: Easily display interactive Data Timeline.
 * Version: 1.0.4
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * Text Domain:  b-timeline
 * Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
    exit;
}
// SOME INITIAL SETUP
define('BPTL_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
define('BPTL_VER', '1.0.4');

// LOAD PLUGIN TEXT-DOMAIN
function bptl_load_textdomain(){
    load_plugin_textdomain('b-timeline', false, dirname(__FILE__) . "/languages");
}
add_action("plugins_loaded", 'bptl_load_textdomain');

// CHART ASSETS
function bptl_assets(){
    wp_register_script('bptl-timeline', BPTL_PLUGIN_DIR .'public/assets/js/timeline.min.js', ['jquery'], BPTL_VER, true );
    wp_register_script('bptl-timeline-config', BPTL_PLUGIN_DIR .'public/assets/js/public.js', ['jquery','bptl-timeline'], BPTL_VER, true );
    wp_enqueue_script('bptl-timeline');
    wp_enqueue_script('bptl-timeline-config');

    wp_register_style( 'timeline-style', BPTL_PLUGIN_DIR .'public/assets/css/timeline.min.css', NULL, 'v0.0.1', 'all');
    wp_enqueue_style('timeline-style');
}
add_action('wp_enqueue_scripts', 'bptl_assets');

// Additional admin style
function bptl_admin_style()
{
    wp_register_style('bptl-admin-style', BPTL_PLUGIN_DIR . 'public/assets/css/admin-style.css');
    wp_enqueue_style('bptl-admin-style');
}
add_action('admin_enqueue_scripts', 'bptl_admin_style');


// Timeline Shortcode
function bptl_shortcode( $atts ) {
	extract( shortcode_atts( array(
        'id'    => null
	), $atts ) ); ob_start(); ?>

    <!-- Timeline Meta Data -->
    <?php  $bptl_datas = get_post_meta( $id, '_bptimeline_', true ); ?>

    <!-- Start Parent Container -->
    <div id="btimeline-<?php echo esc_attr($id); ?>" >
        <div class="timeline bp_titleline" data-timeline='<?php echo esc_attr(wp_json_encode($bptl_datas)); ?>'>
            <div class="timeline__wrap">
                <div class="timeline__items">

                    <?php foreach($bptl_datas['item_datas'] as $item_data ) : 

                        $timeline_label =     $item_data['date_label'] ?? 'January';
                        $timeline_desc =      $item_data['item_details'] ?? 'Timeline Description';
                        $timeline_position =  $item_data['item_position'] ?? '';
                    ?>

                    <div class="timeline__item <?php echo esc_attr($timeline_position); ?> fadeIn">
                        <div class="timeline__item__inner">
                            <div class="timeline__content__wrap">
                                <div class="timeline__content">
                                    <h2><?php echo esc_html($timeline_label) ?> </h2>
                                    <p><?php  echo wp_kses_post($timeline_desc) ?> </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div> <!-- End Parent Container -->
    <style>
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__content {
            background: <?php echo esc_attr($bptl_datas['item_bg']);?>;
            border: <?php echo esc_attr($bptl_datas['item_border_size']);?>px solid <?php echo esc_attr($bptl_datas['item_border_color']);?>;
    
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__content p{
            font-size: <?php echo esc_attr($bptl_datas['item_fontSize']);?>px;
            color: <?php echo esc_attr($bptl_datas['item_color']);?>;
            font-style: <?php echo esc_attr($bptl_datas['item_fontStyle']);?>;
            font-weight: <?php echo esc_attr($bptl_datas['item_fontWeight']);?>
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__content h2{
            font-size: <?php echo esc_attr($bptl_datas['label_fontSize']);?>px;
            color: <?php echo esc_attr($bptl_datas['label_color']);?>;
            font-style: <?php echo esc_attr($bptl_datas['label_fontStyle']);?>;
            font-weight: <?php echo esc_attr($bptl_datas['lebel_fontWeight']);?>
        }
        /* Timeline Dot */
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item::after {
        background-color: '#fff';
        border: 5px solid <?php echo esc_attr($bptl_datas['bar_dot_color']);?>;
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline--horizontal .timeline-divider,
        <?php echo esc_attr($id); ?> .timeline:not(.timeline--horizontal)::before  {
        background-color: <?php echo esc_attr($bptl_datas['bar_bg_color']);?>;}

        <?php echo esc_attr($id); ?> .timeline__item--left .timeline__content::before {
        border-left: 11px solid <?php echo esc_attr($bptl_datas['item_border_color']);?>;}

        <?php echo esc_attr($id); ?> .timeline__item--right .timeline__content::before {
            border-right: 12px solid <?php echo esc_attr($bptl_datas['item_border_color']);?>;
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item--left .timeline__content::after {
        border-left: 11px solid <?php echo esc_attr($bptl_datas['item_bg']);?>;}

        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item--right .timeline__content::after {
            border-right: 12px solid <?php echo esc_attr($bptl_datas['item_bg']);?>;
        }

        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item.timeline__item--top .timeline__content::before {
        border-top: 14px solid <?php echo esc_attr($bptl_datas['item_border_color']);?> !important;}

        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item.timeline__item--bottom .timeline__content::before {
            border-bottom: 14px solid <?php echo esc_attr($bptl_datas['item_border_color']);?>!important;
            border-top: none;
        }

        /* Horizontal view */
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item.timeline__item--top .timeline__content::after {
        border-top: 12px solid <?php echo esc_attr($bptl_datas['item_bg']);?>;
        }

        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline__item.timeline__item--bottom .timeline__content::after {
            border-bottom: 12px solid <?php echo esc_attr($bptl_datas['item_bg']);?>;
            border-top: none;
        }

        /* Mobaile view */
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline--mobile .timeline__item .timeline__content::before {
            border-left: none;
            border-right: 12px solid <?php echo esc_attr( $bptl_datas['item_border_color'] ); ?>;
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline--mobile .timeline__item .timeline__content::after {
            border-left: none;
            border-right: 12px solid <?php echo esc_attr( $bptl_datas['item_bg'] ); ?>;
        }
        <?php echo '#btimeline-'.esc_attr($id); ?> .timeline-nav-button {
            background-color: #fff;
            border: 2px solid <?php echo esc_attr($bptl_datas['bar_bg_color']);?>;
        }
    
    </style>
  
<?php   
return ob_get_clean(); 

}
add_shortcode( 'btimeline', 'bptl_shortcode' );


// Custom post-type
function bptl_post_type()
{
    $labels = array(
        'name'                  => __('B-Timeline', 'b-timeline'),
        'menu_name'             => __('B-Timeline', 'b-timeline'),
        'name_admin_bar'        => __('B-Timeline', 'b-timeline'),
        'add_new'               => __('Add New', 'b-timeline'),
        'add_new_item'          => __('Add New ', 'b-timeline'),
        'new_item'              => __('New Timeline ', 'b-timeline'),
        'edit_item'             => __('Edit Timeline ', 'b-timeline'),
        'view_item'             => __('View Timeline ', 'b-timeline'),
        'all_items'             => __('All Timeline', 'b-timeline'),
        'not_found'             => __('Sorry, we couldn\'t find the Feed you are looking for.')
    );
    $args = array(
        'labels'             => $labels,
        'description'        => __('B-Timeline Options.', 'b-timeline'),
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => BPTL_PLUGIN_DIR . 'public/assets/images/timeline.png',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'b-timeline'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title'),
    );
    register_post_type('btimeline', $args);
}
add_action('init', 'bptl_post_type');


/*-------------------------------------------------------------------------------*/
/*   Include External Files
/*-------------------------------------------------------------------------------*/

// Option panel
require_once 'inc/codestar/csf-config.php';
if( class_exists( 'CSF' )) {
    require_once 'inc/btimeline-options.php';
}

//
/*-------------------------------------------------------------------------------*/
/*   Additional Features
/*-------------------------------------------------------------------------------*/

// Hide & Disabled View, Quick Edit and Preview Button 
function bptl_remove_row_actions($idtions)
{
    global $post;
    if ($post->post_type == 'btimeline' ) {
        unset($idtions['view']);
        unset($idtions['inline hide-if-no-js']);
    }
    return $idtions;
}

if (is_admin()) {
    add_filter('post_row_actions', 'bptl_remove_row_actions', 10, 2);
}

// HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
function bptl_hide_publishing_actions()
{
    $my_post_type = 'btimeline';
    global $post;
    if ($post->post_type == $my_post_type) {
        echo '
            <style type="text/css">
                #misc-publishing-actions,
                #minor-publishing-actions{
                    display:none;
                }
            </style>
        ';
    }
}
add_action('admin_head-post.php', 'bptl_hide_publishing_actions');
add_action('admin_head-post-new.php', 'bptl_hide_publishing_actions');

/*-------------------------------------------------------------------------------*/
// Remove post update massage and link 
/*-------------------------------------------------------------------------------*/

function bptl_updated_messages($messages)
{
    $messages['btimeline'][1] = __('Timeline Item updated ', 'btimeline');
    return $messages;
}
add_filter('post_updated_messages', 'bptl_updated_messages');

/*-------------------------------------------------------------------------------*/
/* Change publish button to save.
/*-------------------------------------------------------------------------------*/
add_filter('gettext', 'bptl_change_publish_button', 10, 2);
function bptl_change_publish_button($translation, $text)
{
    if ('btimeline' == get_post_type() )
        if ($text == 'Publish')
            return 'Save';

    return $translation;
}

/*-------------------------------------------------------------------------------*/
/* Footer Review Request .
/*-------------------------------------------------------------------------------*/

add_filter('admin_footer_text', 'bptl_admin_footer');
function bptl_admin_footer($text)
{
    if ('btimeline' === get_post_type()) {
        $url = 'https://wordpress.org/plugins/b-timeline/reviews/?filter=5#new-post';
        $text = sprintf(__('If you like <strong> B-Timeline </strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'b-timeline'), $url);
    }
    return $text;
}

/*-------------------------------------------------------------------------------*/
/* Shortcode Generator area  .
/*-------------------------------------------------------------------------------*/

add_action('edit_form_after_title', 'bptl_shortcode_area');
function bptl_shortcode_area()
{
    global $post;	
    if($post->post_type == 'btimeline') :    ?>	
    <div class="bptl_shortcode">
            <div class="shortcode-heading">
                <div class="icon"><span class="dashicons dashicons-shortcode"></span> <?php _e("B-Timeline", "b-timeline") ?></div>
                <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e("Supports", "pdfp") ?></a></div>
            </div>
            <div class="shortcode-left">
                <h3><?php _e("Shortcode", "pdfp") ?></h3>
                <p><?php _e("Copy and paste this shortcode into your posts, pages and widget:", "b-timeline") ?></p>
                <div class="shortcode" selectable>[btimeline id="<?php echo esc_attr($post->ID); ?>"]</div>
            </div>
            <div class="shortcode-right">
                <h3><?php _e("Template Include", "pdfp") ?></h3>
                <p><?php _e("Copy and paste the PHP code into your template file:", "b-timeline"); ?></p>
                <div class="shortcode">&lt;?php echo do_shortcode('[btimeline id="<?php echo esc_html($post->ID); ?>"]');
                ?&gt;</div>
            </div>
        </div>

    <?php endif;
}

// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
add_filter('manage_btimeline_posts_columns', 'bptl_columns_head_only', 10);
add_action('manage_btimeline_posts_custom_column', 'bptl_columns_content_only', 10, 2);


// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function bptl_columns_head_only($defaults) {
        unset($defaults['date']);
        $defaults['directors_name'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function bptl_columns_content_only($column_name, $post_ID) {
        if ($column_name == 'directors_name') {
            echo '<div class="bptl_front_shortcode"><input onfocus="this.select();" style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[btimeline  id='."'".esc_attr($post_ID)."'".']" ></div>';
    }
}


