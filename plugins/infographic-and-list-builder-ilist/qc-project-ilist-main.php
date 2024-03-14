<?php 
/*
* Plugin Name: Infographic Maker iList
* Plugin URI: https://wordpress.org/plugins/infographic-and-list-builder-iList
* Description: Infographics & elegant Lists with charts and graphs. Build HTML, Responsive infographics & elegant Text or Image Lists quickly.
* Version: 4.6.8
* Author: QuantumCloud
* Author URI: https://www.quantumcloud.com/
* Requires at least: 4.6
* Tested up to: 6.4
* Domain Path: /lang/
* Text Domain: iList
* License: GPL2
*/


defined('ABSPATH') or die("No direct script access!");
 
//Custom Constants
if ( ! defined( 'QCOPD_URL1' ) ) {
    define('QCOPD_URL1', plugin_dir_url(__FILE__));
}

if ( ! defined( 'QCOPD_IMG_URL1' ) ) {
    define('QCOPD_IMG_URL1', QCOPD_URL1 . "/assets/images");
}

if ( ! defined( 'QCOPD_ASSETS_URL1' ) ) {
    define('QCOPD_ASSETS_URL1', QCOPD_URL1 . "/assets");
}

if ( ! defined( 'QCOPD_DIR1' ) ) {
    define('QCOPD_DIR1', dirname(__FILE__));
}

if ( ! defined( 'QCOPD_INC_DIR1' ) ) {
    define('QCOPD_INC_DIR1', QCOPD_DIR1 . "/inc");
}

/**
 * Do not forget about translating your plugin
 */
if ( ! function_exists( 'qcld_ilist_languages' ) ) {
  function qcld_ilist_languages(){
    load_plugin_textdomain( 'qc-iList', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
  }
}
add_action('init', 'qcld_ilist_languages');


require_once( 'qc-project-ilist-frameworks.php' );
require_once( 'qc-project-ilist-post-type.php' );
require_once( 'qc-project-ilist-asset.php' );
require_once( 'qc-project-ilist-ajax.php' );
require_once( 'qc-project-ilist-shortcode.php' );
require_once( 'qc-project-ilist-hook.php' );
require_once( 'qc-project-ilist-chart.php' );
require_once( 'qc-project-ilist-fa.php' );
require_once('class-plugin-deactivate-feedback.php');
require_once('qc-support-promo-page/class-qc-support-promo-page.php');
require_once('class-qc-free-plugin-upgrade-notice.php');

if(!function_exists('qcld_ilist_order_index_catalog_menu_page')){
    function qcld_ilist_order_index_catalog_menu_page( $menu_ord ){

     global $submenu;

     // Enable the next line to see a specific menu and it's order positions
     //echo '<pre>'; print_r( $submenu['edit.php?post_type=ilist'] ); echo '</pre>'; exit();

     // Sort the menu according to your preferences
     //Original order was 5,11,12,13,14,15

    	$arr = array();

        if( isset($submenu['edit.php?post_type=ilist'][5]) && !empty($submenu['edit.php?post_type=ilist'][5]) ){
        	$arr[] = $submenu['edit.php?post_type=ilist'][5];
        }
        if( isset($submenu['edit.php?post_type=ilist'][10]) && !empty($submenu['edit.php?post_type=ilist'][10]) ){
            $arr[] = $submenu['edit.php?post_type=ilist'][10];
        }
        if( isset($submenu['edit.php?post_type=ilist'][11]) && !empty($submenu['edit.php?post_type=ilist'][11]) ){
            $arr[] = $submenu['edit.php?post_type=ilist'][11];
        }
        if( isset($submenu['edit.php?post_type=ilist'][12]) && !empty($submenu['edit.php?post_type=ilist'][12]) ){
            $arr[] = $submenu['edit.php?post_type=ilist'][12];
        }
        if( isset($submenu['edit.php?post_type=ilist'][250]) && !empty($submenu['edit.php?post_type=ilist'][250]) ){
            $arr[] = $submenu['edit.php?post_type=ilist'][250];
        }
     
    	if( isset($submenu['edit.php?post_type=ilist'][300]) && !empty($submenu['edit.php?post_type=ilist'][300]) ){
    		$arr[] = $submenu['edit.php?post_type=ilist'][300];
    	}

     	$submenu['edit.php?post_type=ilist'] = $arr;

     	return $menu_ord;

    }
}

// add the filter to wordpress
add_filter( 'custom_menu_order', 'qcld_ilist_order_index_catalog_menu_page' );



add_action( 'admin_menu' , 'qcilist_help_link_submenu', 20 );
if(!function_exists('qcilist_help_link_submenu')){
    function qcilist_help_link_submenu(){
    	global $submenu;
    	
    	$link_text = esc_html("Help");
    	$submenu["edit.php?post_type=ilist"][250] = array( $link_text, 'activate_plugins' , admin_url('edit.php?post_type=ilist&page=ilist_settings#help') );
    	ksort($submenu["edit.php?post_type=ilist"]);
    	
    	return ($submenu);
    }
}

if(!function_exists('ilist_options_instructions_example')){
    function ilist_options_instructions_example() {
        global $my_admin_page;
        $screen = get_current_screen();
        
        if ( is_admin() && ( $screen->post_type == 'ilist' ) ) {

            ?>
            <div class="notice notice-info is-dismissible ilist-notice" style="display:none"> 
                <div class="ilist_info_carousel">

                    <div class="ilist_info_item"><?php echo esc_html( 'Now create ' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Infographics Automatically' , 'iList' ); ?></strong> <?php echo esc_html( ' with ' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'OpenAI ChatGPT' , 'iList' ); ?></strong> <?php echo esc_html( 'with iList Pro!' , 'iList' ); ?></div>

                    <div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip: Did you know that' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( '75+' , 'iList' ); ?></strong> <?php echo esc_html( 'template available in iList pro version? Upgrade to iList Pro.' , 'iList' ); ?></div>
                    
                    <div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip: After creating list you can display infographic with any of our' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Available Templates' , 'iList' ); ?></strong> <?php echo esc_html( 'by creating shortcode with' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'shortcode generator' , 'iList' ); ?></strong>.</div>
                    
                    <div class="ilist_info_item"><?php echo esc_html( '**iList Tip: You can create 3 types of list. Info Lists, Graphic Lists, Infographic Lists.' , 'iList' ); ?></div>
                    
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Background color, Text color, Font size, Font family' , 'iList' ); ?></strong> <?php echo esc_html( 'customization option is available in iList pro. Display infographic list in your way.' , 'iList' ); ?></div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Progress Bar option' , 'iList' ); ?></strong> <?php echo esc_html( 'is available in Pro version that makes your infographic list more informative.' , 'iList' ); ?></div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Infographic List Compare' , 'iList' ); ?></strong> <?php echo esc_html( 'option is available in Pro version that allow you to compare two lists in one template.' , 'iList' ); ?></div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Two Compare Template' , 'iList' ); ?></strong> <?php echo esc_html( 'is available in Pro version.' , 'iList' ); ?></div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Awesome Boxed Layout' , 'iList' ); ?></strong> <?php echo esc_html( 'is available in Pro version.' , 'iList' ); ?></div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip:' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Embed Option' , 'iList' ); ?></strong> <?php echo esc_html( 'is available in Pro version that allow you to add your infographic lists any' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Web Page' , 'iList' ); ?></strong>. </div>
    				
    				<div class="ilist_info_item"><?php echo esc_html( '**iList Pro Tip: iChart with advance 4 types of chart only available in pro version.' , 'iList' ); ?> <strong style="color: yellow"><?php echo esc_html( 'Radar, Polar Area, Pie, Doughnut' , 'iList' ); ?></strong>.</div>
    				
                </div>
                <?php 
                wp_enqueue_script( 'jq-slick.min-js', QCOPD_ASSETS_URL1 . '/js/slick.min.js', array('jquery'));

                $notice_js = "jQuery(document).ready(function($){

                        $('.ilist-notice').show();
                        $('.ilist_info_carousel').slick({
                            dots: false,
                            infinite: true,
                            speed: 1200,
                            slidesToShow: 1,
                            autoplaySpeed: 11000,
                            autoplay: true,
                            slidesToScroll: 1,
                        });
                    });";

                wp_add_inline_script( 'jq-slick.min-js', $notice_js );
                ?>
                
            </div>
            <?php
        }
    }
}

add_action( 'admin_notices', 'ilist_options_instructions_example' );


add_action( 'add_meta_boxes', 'ilist_meta_box_video' );

if(!function_exists('ilist_meta_box_video')){
    function ilist_meta_box_video(){
    					                  // --- Parameters: ---
        add_meta_box( 'qc-sld-meta-box-id', // ID attribute of metabox
                      esc_html( 'Shortcode Generator for iList', 'iList' ),       // Title of metabox visible to user
                      'ilist_meta_box_callback', // Function that prints box in wp-admin
                      'page',              // Show box for posts, pages, custom, etc.
                      'side',            // Where on the page to show the box
                      'high' );            // Priority of box in display order
    }
}

if(!function_exists('ilist_meta_box_callback')){
    function ilist_meta_box_callback( $post ){

        ?>
        <p>
            <label for="sh_meta_box_bg_effect"><p><?php echo esc_html( 'Click the button below to generate shortcode' , 'iList' ); ?></p></label>
    		<input type="button" id="ilist_shortcode_generator_meta" class="button button-primary button-large" value="<?php echo esc_html( 'Generate Shortcode' , 'iList' ); ?>" />
        </p>
        
        <?php
    }
}

add_action( 'plugins_loaded', 'ilist_plugin_loaded_fnc' );

if(!function_exists('ilist_plugin_loaded_fnc')){
    function ilist_plugin_loaded_fnc(){

    	if(!get_option('ilist_ot_convrt')){
    		$prevOptions = get_option('option_tree');		
    		if(is_array($prevOptions) && array_key_exists('sl_enable_rtl', $prevOptions)){
    			
    			foreach($prevOptions as $key=>$val){
    				
    				update_option( $key, $val);
    			}
    		}	
    		add_option( 'ilist_ot_convrt', 'yes');
    	}

    }
}

if(!function_exists('ilist_activation_redirect')){
    function ilist_activation_redirect( $plugin ) {

        
        if(empty(get_option('sl_openai_engines')))
            update_option( 'sl_openai_engines', 'gpt-3.5-turbo-instruct');
        
        if(empty(get_option('sl_openai_max_token')))
            update_option( 'sl_openai_max_token', 4000);
        
        if(empty(get_option('sl_openai_temperature')))
            update_option( 'sl_openai_temperature', 1);
        
        if(empty(get_option('sl_openai_presence_penalty')))
            update_option( 'sl_openai_presence_penalty', 2);
        
        if(empty(get_option('sl_openai_frequency_penalty')))
            update_option( 'sl_openai_frequency_penalty', 1);


        if( $plugin == plugin_basename( __FILE__ ) ) {
            exit( wp_redirect( admin_url( 'edit.php?post_type=ilist&page=ilist_settings#help') ) );
        }
    }
}
add_action( 'activated_plugin', 'ilist_activation_redirect' );

if( function_exists('register_block_type') ){
	function qcopd_ilist_gutenberg_block() {
	    require_once plugin_dir_path( __FILE__ ).'/gutenberg/ilist-block/plugin.php';
	}
	add_action( 'init', 'qcopd_ilist_gutenberg_block' );
}
$ilist_feedback = new Wp_Usage_ilist_Feedback(
			__FILE__,
			'plugins@quantumcloud.com',
			false,
			true

		);




//add_action( 'admin_notices', 'qcopd_ilist_pro_notice', 100 );
if(!function_exists('qcopd_ilist_pro_notice')){
    function qcopd_ilist_pro_notice(){
        global $pagenow, $typenow;
        $screen = get_current_screen();

        // var_dump($typenow);
        // wp_die();

        if( ( isset($typenow) && $typenow == 'ilist' )  || ( isset($screen->base) && ($screen->base == 'ilist_page_qcpro-promo-page-ilist-free-page-123za' || $screen->base == 'ilist_page_ilist_settings' ) ) ){
        ?>
        <div id="message-ilist" class="notice notice-info is-dismissible" style="padding:4px 0px 0px 4px;background:#C13825;">
            <?php
                printf(
                    __('%s  %s  %s', 'dna88-wp-notice'),
                    '<a href="'.esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/').'" target="_blank">',
                    '<img src="'.esc_url(QCOPD_IMG_URL1).'/new-year-23.gif" >',
                    '</a>'
                );
            ?>
        </div>
    <?php
    	}
    }
}