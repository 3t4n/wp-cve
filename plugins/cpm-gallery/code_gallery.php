<?php
/*
Plugin Name: Code Pixelz Simple Responsive Image Gallery Plugin
Plugin URI: http://codepixelzmedia.com.np
Description: Simple Gallery Plugin. Easy to use gallery plugin that lets you add gallery feature to your website. Supports 3 lightbox types and shortcodes to display your gallery images anywhere.
Author: Code Pixelz Media
Version: 2.3
Author URI: http://www.codepixelzmedia.com.np
*/
/*  Copyright 2014  codepixelzmedia  (email : info@codepixelz.market)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/******************************************************************/
/*         Enqueuing custom javascript and stylesheets            */
/******************************************************************/

add_action('admin_enqueue_scripts', 'cpm_gallery_admin_scripts');
function cpm_gallery_admin_scripts() {
    wp_enqueue_media();
    wp_register_script('my-admin-js', plugins_url('/js/upload.js',__FILE__), array('jquery'));
    wp_enqueue_script('my-admin-js');
 	wp_enqueue_style( 'code-gallery', plugins_url('/css/codegallery.css',__FILE__) );
}
add_action( 'wp_enqueue_scripts', 'cpm_gallery_frontend_script_load' );
function cpm_gallery_frontend_script_load() {
    wp_enqueue_style( 'code-gallery', plugins_url('/css/codegallery.css',__FILE__) );
    wp_enqueue_style('blueimp-gallery-min',plugins_url('/css/blueimp-gallery.min.css',__FILE__));
    wp_enqueue_style('blueimp-gallery-indicator',plugins_url('/css/blueimp-gallery-indicator.css',__FILE__));
    wp_register_script('blueimp-gallery', plugins_url('/js/blueimp-gallery.js',__FILE__), array('jquery'));
    wp_enqueue_script('blueimp-gallery');
    wp_register_script('gallery-vimeo', plugins_url('/js/blueimp-gallery-vimeo.js',__FILE__), array('jquery'));
    wp_enqueue_script('gallery-vimeo');
}

/**************************************/
/*        Registering post type       */
/**************************************/

add_action('init','cpm_gallery_register_post_type_gallery');

function cpm_gallery_register_post_type_gallery() {
 	$labels = array(
		'name'               => __('Gallery','_cp'),
	    'singular_name'      => __('Gallery','_cp'),
	    'add_new'            => __('Add New','_cp'),
	    'add_new_item'       => __('Add New Gallery','_cp'),
	    'edit_item'          => __('Edit Gallery','_cp'),
	    'new_item'           => __('New Gallery','_cp'),
	    'all_items'          => __('All Galleries','_cp'),
	    'view_item'          => __('View Gallery','_cp'),
	    'search_items'       => __('Search Galley','_cp'),
	    'not_found'          => __('No Gallery found','_cp'),
	    'not_found_in_trash' => __('No Gallery found in Trash','_cp'),
	    'parent_item_colon'  => '',
	    'menu_name'          => __('Gallery','_cp')
		);

  	$args = array(
	    	'labels'             => $labels,
			'description'   => __('Holds all the galley','_cp'),
			'public'        => true,
			'supports'      => array( 'title' ),
			'has_archive'   => true,
			'hierarchical'	=> true,
			// 'menu_icon' => get_bloginfo('template_url').'/images/icons/interview-icon.png',
  	);
  	register_post_type( 'code_gallery', $args );
}

/**********************************************************/
/* Customizing Message Output of code_gallery Post Type   */
/**********************************************************/
add_filter( 'post_updated_messages', 'cpm_gallery_updated_messages' );

function cpm_gallery_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['code_gallery'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Gallery updated.', '_cp' ),
		2  => __( 'Custom field updated.', '_cp' ),
		3  => __( 'Custom field deleted.', '_cp' ),
		4  => __( 'Gallery Post updated.', '_cp' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Gallery Post restored to revision from %s', '_cp' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Gallery published.', '_cp' ),
		7  => __( 'Gallery saved.', '_cp' ),
		8  => __( 'Gallery submitted.', '_cp' ),
		9  => sprintf(
			__( 'Gallery scheduled for: <strong>%1$s</strong>.', '_cp' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', '_cp' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Gallery draft updated.', '_cp' )
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Gallery', '_cp' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Gallery', '_cp' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}
	return $messages;
}

/*****************************************/
/*         CONTEXTUAL HELP TEXT          */
/*****************************************/
add_action( 'contextual_help', 'cpm_gallery_contextual_help', 10, 3 );

function cpm_gallery_contextual_help( $contextual_help, $screen_id, $screen ) {
	if ( 'code_gallery' == $screen->id ) {

	    $contextual_help = '<h2>'.__('Editing Gallery','_cp').'</h2>
	    <p>'.__( 'This page allows you to view/modify gallery details. Please make sure to fill out the title, content and upload images from the box labeled Custom Attachment Section on your right','_cp').'</p>';
  	} elseif ( 'edit-code_gallery' == $screen->id ) {

	    $contextual_help =
	    '<h2>'.__('Gallery','_cp').'</h2>
	    <p>'.__('Gallery show the gallery items that are created. You can see a list of them on this page in reverse chronological order - the latest one we added is first.','_cp').'</p>
	    <p>'.__('You can view/edit the details of each gallery items by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items','_cp').'</p>';
  	}
	return $contextual_help;
}

/*************************************************************************/
/*                       Adding Images Meta Boxes                        */
/*************************************************************************/

add_action('add_meta_boxes', 'cpm_gallery_add_images_meta_boxes');
function cpm_gallery_add_images_meta_boxes() {
    // Define the images attachment for gallery
    add_meta_box(
        'code-gallery-attachment',
        __('Gallery Images','_cp'),
        'code_gallery_attachment',
        'code_gallery',
        'normal'
    );
}

/********************************************************************************/
/*                            Displaying image upload Fields                    */
/********************************************************************************/

function code_gallery_attachment() {
	$k = 1;
	$j = 1;
	$m = 1;
	global $post;
	$i =1;
	 wp_nonce_field(plugin_basename(__FILE__), 'code_gallery_attachment_nonce');
    $html = '<p class="description">';
    $html .= __('Upload your images here.','_cp');
    $html .= '</p>';
    // $html .= '<input type="hidden"  id="upload_image" name="upload_gallery_image" value="'.get_post_meta($post->ID,'code_gallery_images',true).'" size="25">';
    $html .= '<input type="hidden"  id="upload_image" name="upload_gallery_image" value="" size="25">';
    $html .=' <input id="upload_image_button" class="button" type="button" value="Upload Image" />' ;
    echo $html;
    $gallery_images = get_post_meta($post->ID,'code_gallery_images');
    ?>
    <?php if(  sizeof($gallery_images) > 0  ){?>
    <div id="togglediv">
    <?php

    	if($gallery_images[0]!= NULL){foreach ($gallery_images[0] as $key=>$value ) {
    	if($value != NULL){
           $attachment_url = wp_get_attachment_image_src( $value, 'thumbnail', true );
        ?>
    	<input class="images" type="hidden" id="firstimage<?php echo $i?>" name="code_gallery_attachment[]" value="<?php echo $value; ?>" />
    	<div class="editthumb" id="imagediv<?php echo $i;?>">
    		<img src="<?php echo $attachment_url[0];?>"><span class="removebtn"><a id="removebutton<?php echo $k++;?>" onClick="removeImage(<?php echo $i;?>)"class="glyphicon glyphicon-remove buttonremove" ></a></span>
    	</div>
    	<?php $i++; } ?>
    <?php }}?>
	</div>
	<?php }?>
	<script type="text/javascript">
		function removeImage(id) {
			if(!id)
				id = "";
			jQuery('#firstimage'+(id)).remove();
			jQuery('#imagediv'+(id)).remove();
	}
	</script>
<?php }

/**********************************************************************************/
/*                              Save the Metabox Data                             */
/**********************************************************************************/

add_action('save_post', 'code_save_images_meta', 1, 2); // save the custom fields
function code_save_images_meta($post_id, $post) {
	// verify this came from the our screen and with proper authorization,
	//because save_post can be triggered at other times

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	if( isset( $_POST['code_gallery_attachment']) ) :
		$gallery_meta['images'] = $_POST['code_gallery_attachment'];
	// Add values of $gallery_meta as custom fields
	foreach ($gallery_meta as $key => $value) { // Cycle through the $gallery_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
			$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

	update_post_meta($post->ID,'code_gallery_images',$_POST['code_gallery_attachment']);
	endif; //isset End
}

/*******************************************************************************/
/*                            Loading templates                                */
/*******************************************************************************/

add_filter( 'single_template', 'get_custom_post_type_template' );
function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'code_gallery') {
          $single_template = plugin_dir_path( __FILE__ ).'templates/single-codegallery.php';
     }
     return $single_template;
}


/**********************************************************/
/* 				    Adding ShortCode		              */
/**********************************************************/
function display_code_gallery($atts) {
	extract(shortcode_atts(array(
                        'gallery_id'   => '',
                    ), $atts)
    );
    ob_start();
    $galleryimages = get_post_meta($gallery_id,'code_gallery_images');
    ?>
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
    <div class="container-cpm-gallery">
        <div class="imgdiv" id="links">
            <?php  if($galleryimages[0]!= NULL){foreach ($galleryimages[0] as $key=>$value ) {
                if($value != NULL){
                $attachment_url_thumb = wp_get_attachment_image_src($value, 'thumbnail', true);
                $attachment_url_full = wp_get_attachment_image_src($value, 'full', true);
            ?>
                    <div class="images"><a href="<?php echo $attachment_url_full[0];?>" >
                        <img src="<?php echo $attachment_url_thumb[0];?>" >
                    </a></div>
            <?php }}}?>
        </div>
    </div>
    <script>
        document.getElementById('links').onclick = function (event) {
            event = event || window.event;
            var target = event.target || event.srcElement,
                link = target.src ? target.parentNode : target,
                options = {index: link, event: event},
                links = this.getElementsByTagName('a');
            blueimp.Gallery(links, options);
        };
    </script>


<?php
return  ob_get_clean();
}
add_shortcode('code_gallery', 'display_code_gallery');


/**********************************************************/
/* 	       Adding ShortCode Display Meta Box		      */
/**********************************************************/

add_action('add_meta_boxes', 'cp_gallery_shortcode_display_box');
function cp_gallery_shortcode_display_box() {
    add_meta_box(
        'gallery-shortcode',
        __('Paste This Shortcode','_cp'),
        'code_gallery_shortcode_display',
        'code_gallery',
        'side'
    );
}
function code_gallery_shortcode_display() {
	global $post;
	echo '[code_gallery gallery_id="'.$post->ID.'"]';
}

/**
 * Shortcode column in manage Gallery Post screen
 */
add_filter('manage_code_gallery_posts_columns', 'code_gallery_posts_column_head');
function code_gallery_posts_column_head($defaults) {
    $defaults['code_gallery_shortcode'] = __('Shortcode', '_cp');
    return $defaults;
}

add_action('manage_code_gallery_posts_custom_column', 'code_gallery_columns_content', 10, 2);
function code_gallery_columns_content($column_name, $post_ID) {
    if ( $column_name == 'code_gallery_shortcode' && 'publish' === get_post_status( $post_ID ) ) {
        $generated_shortcode = '[code_gallery gallery_id="' . $post_ID . '"]';
        if ($generated_shortcode) {
            echo $generated_shortcode;
        }
    }
}