<?php
/*
Plugin Name:	Custom Category Image
Description:	Upload image to any category/taxonomy
Author: 		Mingocommerce
Author URI:		http://www.mingocommerce.com
Text Domain: 	advanced-permalink
Domain Path: 	/i18n/
Version: 		1.2.0
*/

class MGC_Custom_Category_Image{
	
	var $categories;
	var $image_sizes;
	
	function __construct(){
		
		add_action('init', array($this, 'init'), 99);
		add_action( 'admin_enqueue_scripts', array($this,	'load_admin_things'	) );
		
		//$this->categories	=	array('faq_category');
	}
	
	function init(){
		$this->categories	=	get_taxonomies();
		$this->image_sizes	=	array(
			array('40','40'),
			array('55','55'),
			array('100','100'),
		);
		
		foreach($this->image_sizes as $image_size){
			$image_size_name	=	'cc_'.$image_size[0].'_'.$image_size[0];
			add_image_size($image_size_name, $image_size[0], $image_size[1]);
		}
		foreach($this->categories as $category){
			add_action($category.'_edit_form_fields', 	array($this,	'image_field'),	99,	2);
			add_action($category.'_add_form_fields', 	array($this,	'image_field'),	20,	2);
			add_action( 'created_'.$category, 			array($this,	'save_image_field' ),	10,	2);
			add_action( 'edited_'.$category, 			array( $this,	'save_image_field' ),	10, 2);
			
			add_filter( 'manage_edit-'.$category.'_columns', array($this, 'add_image_column'), 10);
			add_filter('manage_'.$category.'_custom_column', array($this, 'add_image_column_content'),10,3);
		}
	}
	
	function image_field($term){
		$attachment_image_id	=	'';
		$current_image = '';
		$remove_button = '';
		if($term instanceof WP_Term){ // Edit mode
			$current_image	=	MGC_Custom_Category_Image::get_category_image($term->term_id,array(100,100));
			$attachment_image_id = get_term_meta($term->term_id, "_category_image_id", 1);
			$remove_button	=	'<input type="button" class="button" name="custom_category_image_remove_button" id="custom_category_image_remove_button" value="Remove Image">';
		}
		echo '<tr class="form-field">
				<th scope="row" valign="top"><label for="show_on_provider">' . __('Image') . '</label></th>
				<td>
				<div id="uploaded_image">'.$current_image.'</div>
				<input class="upload_image_button button" name="add_category_image_button" id="add_category_image_button" type="button" value="Select/Upload Image"/>
				'.$remove_button.'
				<input type="hidden" name="category_image_id" id="category_image_id" value="'.$attachment_image_id.'">
				</td>
			</tr>';
		?>
		<script>
			jQuery(document).ready(function() {
				jQuery('#add_category_image_button').click(function() {
					
					wp.media.editor.send.attachment = function(props, attachment) {
						jQuery('#uploaded_image').html('<img src="'+attachment.url+'">');
						jQuery('#category_image_id').val(attachment.id);						
						//jQuery('.series-image').val(attachment.url);alert(attachment.url);alert(attachment.id);
					}
					wp.media.editor.open('add_category_image_button');
					return false;
					
					
				});
				
				jQuery('#custom_category_image_remove_button').click(function(){
					jQuery('#uploaded_image').html('');
					jQuery('#category_image_id').val('');
				});
			});
		</script>
		<?php
	}
	
	function save_image_field($term_id, $tt_id){
		if(isset($_POST['category_image_id']) && $_POST['category_image_id'] !== ''){
			$image_id	=	$_POST['category_image_id'];
			update_term_meta ( $term_id, '_category_image_id', $image_id );
		}else{
			update_term_meta ( $term_id, '_category_image_id', '' );
		}
	}
	
	function add_image_column($columns){
		$new_columns	=	array(
			'cb'	=>	$columns['cb'],
			'cc_image'	=>	'Image',
		);
		
		$columns 	=	array_merge($new_columns,	$columns);
		return $columns;
	}
	
	function add_image_column_content($content,	$column_name,	$term_id){
		$content	=	'';
		switch($column_name){
			case 'cc_image':
				$content	=	MGC_Custom_Category_Image::get_category_image($term_id);
				break;
				
			default:
				break;
		}
		return $content;
	}
	
	static function get_category_image($term_id, $size	=	array(40,40)){
		$attachment_image_id	=	get_term_meta($term_id, "_category_image_id", 1);
		$image					=	wp_get_attachment_image( $attachment_image_id,	$size);
		return $image;
	}
	
	static function get_category_image_src($term_id, $size	=	array(40,40)){
		$attachment_image_id	=	get_term_meta($term_id, "_category_image_id", 1);
		$src					=	wp_get_attachment_image_src( $attachment_image_id,	$size);
		return $src;
	}
	
	function load_admin_things() {
		wp_enqueue_media();
		//wp_enqueue_script('media-upload');
		//wp_enqueue_script('thickbox');
		//wp_enqueue_style('thickbox');
	}
}
new MGC_Custom_Category_Image();