<?php 
/*
 * Plugin Name: Logo Carousel
 * Plugin URI:  http://bplugins.com
 * Description: Add scrolling logo carosel to wordpress website. display your client logo in a nice way.
 * Version: 1.2
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 */

 
 /*Some Set-up*/
define('SLC_PLUGIN_DIR', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' ); 




/* Latest jQuery of wordpress */
if ( ! function_exists( 'slc_add_jquery' ) ) :
function slc_add_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'slc_add_jquery');
endif;


/* Carousel JS*/
if ( ! function_exists( 'slc_get_script' ) ) :
function slc_get_script(){    
    wp_enqueue_script( 'ppm-customscrollbar-js', plugin_dir_url( __FILE__ ) . 'js/crawler.js', array('jquery'), '20120206', false );
}
add_action('wp_enqueue_scripts', 'slc_get_script');
endif;

/*-------------------------------------------------------------------------------*/
/* HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
/*-------------------------------------------------------------------------------*/

function slc_hide_publishing_actions(){
        $my_post_type = 'scrollingcarousel';
        global $post;
        if($post->post_type == $my_post_type){
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
add_action('admin_head-post.php', 'slc_hide_publishing_actions');
add_action('admin_head-post-new.php', 'slc_hide_publishing_actions');	








 
//Remove post update massage and link 
function slc_updated_messages( $messages ) {
    $messages['scrollingcarousel'][1] = __('Updated');
    return $messages;
}
add_filter('post_updated_messages','slc_updated_messages');
 


/*-------------------------------------------------------------------------------*/
/*  Metabox
/*-------------------------------------------------------------------------------*/			
//include the main class file

include_once('metabox/meta-box-class/my-meta-box-class.php');
include_once('metabox/class-usage-demo.php');

 

/* Register Custom Post Types********************************************/
     
            add_action( 'init', 'slc_create_post_type' );
            function slc_create_post_type() {
                    register_post_type( 'scrollingcarousel',
                            array(
                                    'labels' => array(
                                            'name' => __( 'Client Logo Carousel'),
                                            'singular_name' => __( 'Client Logo Carousel' ),
                                            'add_new' => __( 'Add New' ),
                                            'add_new_item' => __( 'Add new item' ),
                                            'edit_item' => __( 'Edit' ),
                                            'new_item' => __( 'New' ),
                                            'view_item' => __( 'View' ),
											'search_items'       => __( 'Search'),
                                            'not_found' => __( 'Sorry, we couldn\'t find any item you are looking for.' )
                                    ),
                            'public' => false,
							'show_ui' => true, 									
                            'publicly_queryable' => true,
                            'exclude_from_search' => true,
                            'menu_position' => 14,
							'menu_icon' =>SLC_PLUGIN_DIR .'/img/icon.png',
                            'has_archive' => false,
                            'hierarchical' => false,
                            'capability_type' => 'page',
                            'rewrite' => array( 'slug' => 'scrollingcarousel' ),
                            'supports' => array( 'title','thumbonail' )
                            )
                    );
            }	
			
// ONLY OUR CUSTOM TYPE POSTS
add_filter('manage_scrollingcarousel_posts_columns', 'slc_column_handler', 10);
add_action('manage_scrollingcarousel_posts_custom_column', 'slc_column_content_handler', 10, 2);
 
// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
function slc_column_handler($defaults) {
    $defaults['directors_name'] = 'ShortCode';
    return $defaults;
}
function slc_column_content_handler($column_name, $post_ID) {
    if ($column_name == 'directors_name') {
        // show content of 'directors_name' column
		echo '<input onClick="this.select();" value="[carousel id='. $post_ID . ']" >';
    }
}			
			
//Lets register our shortcode 
function slc_shortcode_content_func($atts){
	extract( shortcode_atts( array(

		'id' => null,

	), $atts ) );

?>
<?php ob_start(); ?>
 <div id="marqueediv" <?php if(get_post_meta($id,'ba_bg_color',true)!=="#"){echo "style='background-color:".get_post_meta($id,'ba_bg_color',true).";'";} ?>>                                                   
    <div id="mycarouse<?php echo $id; ?>">
 <?php
$saved_data = get_post_meta($id,'ba_re_',true);
// check if image empty 
if(empty($saved_data)){echo "<h2>OOps ! You forgot to add images in the carousel.</h2>";}

foreach ($saved_data as $arr){
    if(isset($arr['ba_image_field_id']['url'])){
        echo "<img src='".$arr['ba_image_field_id']['url']."'class='scrollimg' style='";
        if(get_post_meta($id,'ba_width',true)!=="0"){echo "width:".get_post_meta($id,'ba_width',true)."px; ";}
        if(get_post_meta($id,'ba_height',true)!=="0"){echo "height:".get_post_meta($id,'ba_height',true)."px; ";} 
        if(get_post_meta($id,'ba_padding',true)!=="0"){echo "margin-left:".get_post_meta($id,'ba_padding',true)."px; ";} 
        if(get_post_meta($id,'ba_boarder_size',true)!=="0"){echo "border:".get_post_meta($id,'ba_boarder_size',true)."px solid; ";}
        if(get_post_meta($id,'ba_boarder_color',true)!=="#"){echo "border-color:".get_post_meta($id,'ba_boarder_color',true).";";} 		 
         
        echo "'/>";	

    }
	
} 

echo "</div></div>"; ?>

		<script>
              marqueeInit({
                    uniqueid: 'mycarouse<?php echo $id; ?>',
                    style: {						
                    },
					<?php if(get_post_meta($id,'ba_speed',true)){echo "moveatleast:".get_post_meta($id,'ba_speed',true).",";} ?>
					
                    <?php if(get_post_meta($id,'ba_behavior',true)){echo "mouse:'".get_post_meta($id,'ba_behavior',true)."',";} ?>

                    <?php if(get_post_meta($id,'ba_mouse_direction',true)=='on'){echo "savedirection: false,";}else{echo "savedirection: true,";} ?>
					
					<?php if(get_post_meta($id,'ba_speed',true)){echo "inc:".get_post_meta($id,'ba_speed',true).",";} ?>					
                    neutral: 150,
                    random: true
                });

        </script>
<?php  $output = ob_get_clean();return $output;//print $output; // debug ?>
<?php
}
add_shortcode('carousel','slc_shortcode_content_func');
		/**
	 * Review Request Text
	 *
	 *
	 * @return string
	 */
	add_filter( 'admin_footer_text','slc_admin_footer');	 
	function slc_admin_footer( $text ) {
		if ( 'scrollingcarousel' == get_post_type() ) {
			$url = 'https://wordpress.org/support/plugin/responsive-client-logo-carousel-slider/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Responsive Client Logo Carousel</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'post-carousel' ), $url );
		}

		return $text;
	}
				
add_action('edit_form_after_title','slc_shortcode_area');
function slc_shortcode_area(){
global $post;   
if($post->post_type=='scrollingcarousel'){
?>  
<div>
    <label style="cursor: pointer;font-size: 13px; font-style: italic;" for="slc_shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>
    <span style="display: block; margin: 5px 0; background:#1e8cbe; ">
        <input type="text" id="slc_shortcode" style="font-size: 12px; border: none; box-shadow: none;padding: 4px 8px; width:100%; background:transparent; color:white;"  onfocus="this.select();" readonly="readonly"  value="[carousel id=<?php echo $post->ID; ?>]" /> 
        
    </span>
</div>
 <?php   
}}