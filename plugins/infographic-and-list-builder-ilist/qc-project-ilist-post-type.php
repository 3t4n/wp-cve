<?php 
defined('ABSPATH') or die("No direct script access!");

//<--Registering custom post and Taxonomie for iList

if(!function_exists('qcilist_custom_post_text')){
	function qcilist_custom_post_text() {
		//Registering Custom Post
			$qc_list_labels = array(
			'name'               => esc_html( 'Manage iList Items', 'iList' ),
			'singular_name'      => esc_html( 'Manage iList Item', 'iList' ),
			'add_new'            => esc_html( 'New iList', 'iList' ),
			'add_new_item'       => esc_html( 'Add New iList', 'iList' ),
			'edit_item'          => esc_html( 'Edit iList Item', 'iList' ),
			'new_item'           => esc_html( 'New iList Item', 'iList' ),
			'all_items'          => esc_html( 'Manage iList Items', 'iList' ),
			'view_item'          => esc_html( 'View iList Item', 'iList' ),
			'search_items'       => esc_html( 'Search List Item', 'iList' ),
			'not_found'          => esc_html( 'No List Item found', 'iList' ),
			'not_found_in_trash' => esc_html( 'No List Item found in the Trash', 'iList' ), 
			'parent_item_colon'  => '',
			'menu_name'			 => __('iList')
			
		);
		
		$qc_list_args = array(
			'labels'        		=> $qc_list_labels,
			'description'   		=> esc_html('This post type holds all posts for your directory items.', 'iList'),
			'public'        		=> true,
			'publicly_queryable' 	=> false,  // you should be able to query it
			
			'exclude_from_search' 	=> true,
			
			'show_in_menu' 			=> true,
			'supports'      		=> array( 'title' ),
			'has_archive'   		=> true,
			'menu_icon' 			=> 'dashicons-editor-ol',
		);
		
		register_post_type( 'ilist', $qc_list_args );
	// Registering Taxonomies
		// $labels = array(
		// 	'name'              => _x( 'iList Categories', 'iList Categories', 'iList' ),
		// 	'singular_name'     => _x( 'Category', 'taxonomy singular name', 'iList' ),
		// 	'search_items'      => __( 'Search iList Categories', 'iList' ),
		// 	'all_items'         => __( 'All iList Categories', 'iList' ),
		// 	'parent_item'       => __( 'Parent iList Categories', 'iList' ),
		// 	'parent_item_colon' => __( 'Parent iList Category:', 'iList' ),
		// 	'edit_item'         => __( 'Edit iList Category', 'iList' ),
		// 	'update_item'       => __( 'Update iList Category', 'iList' ),
		// 	'add_new_item'      => __( 'Add New iList Category', 'iList' ),
		// 	'new_item_name'     => __( 'New iList Category Name', 'iList' ),
		// 	'menu_name'         => __( 'iList Categories', 'iList' ),
		// );

		// $args = array(
		// 	'hierarchical'      => true,
		// 	'labels'            => $labels,
		// 	'show_ui'           => true,
		// 	'show_admin_column' => true,
		// 	'query_var'         => true,
		// 	'rewrite'           => array( 'slug' => 'sl_cat' ),
		// );

		// register_taxonomy( 'sl_cat', array( 'ilist' ), $args );
	}
}
add_action('init', 'qcilist_custom_post_text');
//End Custom Post and Taxonomie-->

//File Required for metabox

if(function_exists('is_plugin_active')){
	if(!is_plugin_active('CMB2/init.php')){
		require_once QCOPD_INC_DIR1 . '/CMB2/init.php';
	}
}else{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(!is_plugin_active('CMB2/init.php')){
		require_once QCOPD_INC_DIR1 . '/CMB2/init.php';
	}
}
require_once( QCOPD_INC_DIR1 . '/CMB2/cmb2-conditionals.php' );


//<--Registering repeatable group field metabox.

add_action( 'cmb2_admin_init', 'ilist_shortcode_register_appearance_metabox' );
if(!function_exists('ilist_shortcode_register_appearance_metabox')){
	function ilist_shortcode_register_appearance_metabox() {
		
		$cmb_shortcode = new_cmb2_box( array(
			'id'            => 'ilist_shortcode_conf',
			'title'         => esc_html( 'Shortcode Settings', 'iList' ),
			'object_types'  => array( 'ilist' ), // Post type
			'closed'     	=> true,
			'classes'    	=> 'extra-class',
			 

		) );


		// shortcode feature...

		$cmb_shortcode->add_field( array(
			'name'    => esc_html( 'Column', 'iList' ),
			'id'      => 'shortcode_column',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => esc_html( '1', 'iList' ),
				'2'   => esc_html( '2', 'iList' ),
			),
			'default' => '2',
		) );

		$cmb_shortcode->add_field( array(
			'name'    => esc_html( 'Upvote', 'iList' ),
			'id'      => 'shortcode_upvote',
			'type'    => 'radio_inline',
			'options' => array(
				'on'  => esc_html( 'Yes', 'iList' ),
				'off' => esc_html( 'No', 'iList' ),
			),
			'default' => 'on',
		) );

		$cmb_shortcode->add_field( array(
			'name'    => esc_html( 'Lightbox', 'iList' ),
			'id'      => 'shortcode_disable_lightbox',
			'type'    => 'radio_inline',
			'options' => array(
				'true' => esc_html( 'Yes', 'iList' ),
				'false' => esc_html( 'No', 'iList' ),
			),
			'default' => 'true',
		) );


	// shortcode feature...
	//Code for create column
	// $cmb2Grid = new \Cmb2Grid\Grid\Cmb2Grid($cmb_shortcode);
	// // shortcode generate...
	// $row = $cmb2Grid->addRow();
	// $row->addColumns(array($shortcode_1, $shortcode_2,$shortcode_3));
	// $row = $cmb2Grid->addRow();
	// $row->addColumns(array($shortcode_4, $shortcode_5,$shortcode_6));
		



	}
}

//<--Registering repeatable group field metabox.
add_action( 'cmb2_admin_init', 'qcilist_register_sl_repeatable_group_field_metabox' );
if(!function_exists('qcilist_register_sl_repeatable_group_field_metabox')){
function qcilist_register_sl_repeatable_group_field_metabox(){

	$prefix = '_sl_';
	$cmb_group = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => esc_html( 'List Elements', 'iList' ),
		'object_types' => array( 'ilist' ),
	) );
//Creating post type
	$cmb_group->add_field( array(
		'name'    => esc_html( 'Select List Type', 'iList' ),
		'desc'    => esc_html( 'Please select list type.', 'iList' ),
		'id'      => 'post_type_radio_sl',
		'type'    => 'radio_inline',
		'options' => array(
			'textlist' 	=> esc_html( 'Info Lists', 'iList' ),
			'imagelist' => esc_html( 'Graphic Lists', 'iList' ),
			'elegant' 	=> esc_html( 'Infographic Lists', 'iList' ),
		),
		'default' 		=> 'elegant',
	) );
//<--Template Part goes here
// Template for Elegant
/*
$cmb_group->add_field( array(
		'name'             => esc_html('Premium Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_elegant',
		'type'             => 'select',
		'show_option_none' => true,
		    'default'          => '',
			'options'          => array(
				'premium-graphic-style-01' => esc_html( 'Premium Style 01', 'iList' ),

			),
			'attributes' => array(
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'imagelist',
		)
		
	) );
	
$cmb_group->add_field( array(
		'name'             => esc_html('Premium Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_elegant1',
		'type'             => 'select',
		'show_option_none' => true,
		    'default'          => '',
			'options'          => array(
				'premium-info-01' => esc_html( 'Premium Info 01', 'iList' ),
				'premium-info-02'   => esc_html( 'Premium Info 02', 'iList' ),
				'premium-info-03'   => esc_html( 'Premium Info 03', 'iList' ),
				'premium-info-04'   => esc_html( 'Premium Info 04', 'iList' ),
				'premium-info-05'   => esc_html( 'Premium Info 05', 'iList' ),
				'premium-info-06'   => esc_html( 'Premium Info 06', 'iList' ),
				'premium-info-07'   => esc_html( 'Premium Info 07', 'iList' ),
				
			),
			'attributes' => array(
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'textlist',
		)
		
	) );
	$cmb_group->add_field( array(
		'name'             => esc_html('Premium Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_elegant2',
		'type'             => 'select',
		'show_option_none' => true,
		    'default'          => '',
			'options'          => array(
			
				'chocolate-style-01' => esc_html( 'Chocolate Style 01', 'iList' ),
				'chocolate-style-02'   => esc_html( 'Chocolate Style 02', 'iList' ),
				'origami-style-04'   => esc_html( 'Origami Style 04', 'iList' ),
				
				'origami-style-06'   => esc_html( 'Origami Style 06', 'iList' ),
				'origami-style-07'   => esc_html( 'Origami Style 07', 'iList' ),
				'origami-style-08'   => esc_html( 'Origami Style 08', 'iList' ),
				'origami-style-09'   => esc_html( 'Origami Style 09', 'iList' ),
				
				'premium-style-01'   => esc_html( 'Premium Style 01', 'iList' ),
				'premium-style-02'   => esc_html( 'Premium Style 02', 'iList' ),
				'premium-style-04'   => esc_html( 'Premium Style 04', 'iList' ),
				'premium-style-05'   => esc_html( 'Premium Style 05', 'iList' ),
				'premium-style-06'   => esc_html( 'Premium Style 06', 'iList' ),
				'premium-style-07'   => esc_html( 'Premium Style 07', 'iList' ),
				'premium-style-08'   => esc_html( 'Premium Style 08', 'iList' ),
				'premium-style-09'   => esc_html( 'Premium Style 09', 'iList' ),
				'premium-style-10'   => esc_html( 'Premium Style 10', 'iList' ),
				'premium-style-11'   => esc_html( 'Premium Style 11', 'iList' ),
				'premium-style-12'   => esc_html( 'Premium Style 12', 'iList' ),
				'premium-style-13'   => esc_html( 'Premium Style 13', 'iList' ),
				'premium-style-14'   => esc_html( 'Premium Style 14', 'iList' ),
				'premium-style-15'   => esc_html( 'Premium Style 15', 'iList' ),
				'premium-style-16'   => esc_html( 'Premium Style 16', 'iList' ),
				
			),
			'attributes' => array(
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'elegant',
		)
		
	) );
	
// Template for Image list
$cmb_group->add_field( array(
		'name'             => esc_html('Simple Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_image',
		'type'             => 'select',
		'show_option_none' => true,
		    'default'          => '',
			'options'          => array(
				'image-template-one' => esc_html( 'Image Template One', 'iList' ),
				'image-template-two'   => esc_html( 'Image Template Two', 'iList' ),
				'image-template-three'   => esc_html( 'Image Template Three', 'iList' ),
				'image-template-four'   => esc_html( 'Image Template Four', 'iList' ),
				'image-template-five'   => esc_html( 'Image Template Five', 'iList' ),
				
			),
		'attributes' => array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'imagelist',
		)
		
	) );
//Template for iList
$cmb_group->add_field( array(
		'name'             => esc_html('Simple Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_text',
		'type'             => 'select',
		'show_option_none' => true,
		'default'		   => '',
		'options'          => array(
			'simple-list-one'          => esc_html('Simple List Template One'),
			'simple-list-two'          => esc_html('Simple List Template Two'),
			'simple-list-three'          => esc_html('Simple List Template Three'),
			'simple-list-four'          => esc_html('Simple List Template Four'),
			'infographic-template-five'   => esc_html('Simple List Template Five'),
			'simple-list-six'   => esc_html('Simple List Template Six'),
			
			
		),
		'attributes' => array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'textlist',
		)
		
	) );
//Template for Elegant List
$cmb_group->add_field( array(
		'name'             => esc_html('Simple Templates'),
		'desc'    => esc_html( '', 'iList' ),
		'id'               => 'qcld_sl_template_mix',
		'type'             => 'select',
		'show_option_none' => true,
		'default'		   => '',
		'options'          => array(
			
			'infographic-template-one'   => esc_html('Infographic Template One'),
			'infographic-template-two'          => esc_html('Infographic Template Two'),
			'infographic-template-three'          => esc_html('Infographic Template Three'),
			'infographic-template-four'   => esc_html('Infographic Template Four'),
			'infographic-template-five'   => esc_html('Infographic Template Five'),
			'infographic-template-six'   => esc_html('Infographic Template Six'),
			'infographic-template-seven'   => esc_html('Infographic Template Seven'),
			'infographic-template-eight'   => esc_html('Infographic Template Eight'),
			'infographic-template-nine'   => esc_html('Infographic Template Nine'),
			'infographic-template-ten'   => esc_html('Infographic Template Ten'),
			'infographic-template-eleven'   => esc_html('Infographic Template Eleven'),
			'infographic-template-twelve'   => esc_html('Infographic Template Twelve'),
			'infographic-template-thirteen'   => esc_html('Infographic Template Thirteen'),
		),
		'attributes' => array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'elegant',
		)
		
	) );
*/


//code for image list
$cmb_group->add_field( array(
		'name'             	=> esc_html('Choose Template', 'iList'),
		'desc'    			=> esc_html( '', 'iList' ),
		'id'               	=> 'qcld_sl_template_image',
		'type'             	=> 'text',
		
		'attributes' 		=> array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'imagelist',
		)
		
	) );
//Code for text List
$cmb_group->add_field( array(
		'name'             	=> esc_html('Choose Template', 'iList'),
		'desc'    			=> esc_html( '', 'iList' ),
		'id'               	=> 'qcld_sl_template_text',
		'type'             	=> 'text',
		'attributes' 		=> array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'textlist',
		)
		
	) );
//code for Elegant List
$cmb_group->add_field( array(
		'name'             	=> esc_html('Choose Template', 'iList' ),
		'desc'    			=> esc_html( '', 'iList' ),
		'id'               	=> 'qcld_sl_template_mix',
		'type'             	=> 'text',
		'attributes' 		=> array(
			'required'               => true, // Will be required only if visible.
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => 'elegant',
		)
		
	) );

	
//creating chart for ilist
$chartfield1 = $cmb_group->add_field( array(
    'name'    => __( 'Create iList Chart', 'iList' ),
    'id'      => 'ilist_chart',
    'type'    => 'text',
    

) );
//display position
$chartfield2 = $cmb_group->add_field( array(
		'name'    => __( 'Show Chart On', 'iList' ),
		'id'      => 'show_chart_position',
		'type'    => 'radio_inline',
		'options' => array(
			'top' => __( 'Top', 'iList' ),
			'bottom' => __( 'Bottom', 'iList' ),
		),
		'default' => 'top',
		
	) );
//END of template Part-->
// Code start for Group field
	$group_field_id = $cmb_group->add_field( array(
		'id'          => 'qcld_text_group',
		'type'        => 'group',
		'description' => esc_html( 'Create Text List Elements', 'iList' ),
		'options'     => array(
			'group_title'   => esc_html( 'Entry {#}', 'iList' ), // {#} gets replaced by row number
			'add_button'    => esc_html( 'Add Another Entry', 'iList' ),
			'remove_button' => esc_html( 'Remove Entry', 'iList' ),
			'sortable'      => true, // beta
			
		),
	) );

// Group Title
	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => esc_html( 'Entry Title', 'iList' ),
		'id'         => 'qcld_text_title',
		'type'       => 'text',
		
	) );
// Counter	
	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => esc_html( 'Meta ID', 'iList' ),
		'id'         => 'qcld_counter',
		'type'       => 'text',
		'default'	=>'1',
		'classes' => 'counter-class'
		
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name' => esc_html( 'thumbs up', 'iList' ),
		'id'   => 'sl_thumbs_up',
		'type' => 'text',
		'classes' => 'counter-class'
	) );
	$cmb_group->add_group_field( $group_field_id, array(
		'name' => esc_html( 'thumbs user', 'iList' ),
		'id'   => 'sl_thumbs_up_user',
		'type' => 'text',
		'classes' => 'counter-class'
	) );

/*
	Description field with wysiwyg Editor
	It will be visible only for Simple and Elegant List
*/
	$cmb_group->add_group_field( $group_field_id, array(
		'name'        => esc_html( 'Description', 'iList' ),
		'description' => esc_html( 'Write a short description for this entry', 'iList' ),
		'id'          => 'qcld_text_desc',

		'type'    => 'wysiwyg',
		'options' => array(
			'wpautop' => false,
			'media_buttons' => true, // show insert/upload button(s)
			//'textarea_rows' => 2, // rows="..."
			'tabindex' => '',
			'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
			'editor_class' => '', // add extra class(es) to the editor textarea
			'teeny' => false, // output the minimal editor config used in Press This
			'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
			'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
			'quicktags' => true,
			// 'editor_height' => 80, // In pixels, takes precedence and has no default value
			'textarea_rows' => 1,
		),
		'attributes' => array(
			
			'rows'    => '2',
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => wp_json_encode( array( 'textlist', 'elegant', 'imagelist' ) ),
		),




	) );
	/*$cmb_group->add_group_field( $group_field_id, array(
		'name'        => 'Long Description <span style="color:red;font-size:12px">(pro)</span>',
		'description' => esc_html( 'This field is optional. If you write a long description for this entry that will display in a lightbox when clicked in the pro version.', 'iList' ),
		'id'          => 'qcld_text_long_desc',
		'type'    => 'textarea_small',
		

	) );*/

	/*$cmb_group->add_group_field( $group_field_id, array(
		'name'       => 'Entry Background Color <span style="color:red;font-size:12px">(pro)</span>',
		'id'         => 'qcld_entrybg_color',
		'type'       => 'colorpicker',
		
	) );*/
	
	/*$cmb_group->add_group_field( $group_field_id, array(
		'name' => 'Font Awesome Icon <span style="color:red;font-size:12px">(pro)</span>',
		'id'   => 'qcld_text_image_fa',
		
		'type' => 'text_medium',
		'classes' => 'ilist_fa_icon',
		'attributes' => array(
			
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => wp_json_encode( array( 'textlist', 'elegant' ) ),
		)
	) );
	*/
	/*$cmb_group->add_group_field( $group_field_id, array(
		'name' => 'Progress Bar <span style="color:red;font-size:12px">(pro)</span>',
		'id'   => 'qcld_progress_bar',
		'description' => esc_html( 'Supported Value integer Ex: 50', 'iList' ),
		'type' => 'text_medium',
		'attributes' => array(
			
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => wp_json_encode( array( 'textlist', 'elegant' ) ),
		)
	) );
	*/
/*
	Image Uploader
	It will be visible only for Image and Elegant List
*/
	$cmb_group->add_group_field( $group_field_id, array(
		'name' => esc_html( 'Image', 'iList' ),
		'id'   => 'qcld_text_image',
		'type' => 'file',
		'repeatable' => true,
		'attributes' => array(
			
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => wp_json_encode( array( 'imagelist', 'elegant' ) ),
		)
	) );
	
	/*$cmb_group->add_group_field( $group_field_id, array(
		'name' => 'Image Link <span style="color:red;font-size:12px">(pro)</span>',
		'id'   => 'qcld_image_link',
		'description' => esc_html( '', 'iList' ),
		'type' => 'text',
		'attributes' => array(
			
			'data-conditional-id'    => 'post_type_radio_sl',
			'data-conditional-value' => wp_json_encode( array( 'textlist', 'elegant' ) ),
		)
	) );*/
}
}
//End of repeatable group field metabox.-->

// <--Taxonomie Filter
// function qcilist_pippin_add_taxonomy_filters() {
// 	global $typenow;
 
// 	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
// 	$taxonomies = array('sl_cat');
 
// 	// must set this to the post type you want the filter(s) displayed on
// 	if( $typenow == 'ilist' ){
 
// 		foreach ($taxonomies as $tax_slug) {
// 			$tax_obj = get_taxonomy($tax_slug);
			
// 			$tax_name = $tax_obj->labels->name;
// 			$terms = get_terms($tax_slug);
			
// 			if(count($terms) > 0) {
// 				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
// 				echo "<option value=''>Show All $tax_name</option>";
// 				foreach ($terms as $term) { 
// 					echo '<option value='. $term->slug.'>' . esc_html($term->name) .' (' . $term->count .')</option>'; 
// 				}
// 				echo "</select>";
// 			}
// 		}
// 	}
// }
// add_action( 'restrict_manage_posts', 'qcilist_pippin_add_taxonomy_filters' );
// End of Taxonomie Filter-->


//Custom Columns for Directory Listing
if(!function_exists('qcilist_text_list_column_head')){
	function qcilist_text_list_column_head($defaults) {

	    $new_columns['cb'] 						= '<input type="checkbox" />';
	    $new_columns['title'] 					= esc_html('Title', 'iList' );
	    $new_columns['qcld_item_text_count'] 	= esc_html('Number of Elements', 'iList' );
	    $new_columns['shortcode_text_col'] 		= esc_html('Shortcode', 'iList' );
	    $new_columns['date'] 					= esc_html('Date', 'iList' );

	    return $new_columns;
	}
}
 
//Custom Columns Data for Backend Listing
if(!function_exists('qcilist_list_text_columns_content')){
	function qcilist_list_text_columns_content($column_name, $post_ID) {
	    

	    if ($column_name == 'qcld_item_text_count') {
	        $cntpost = get_post_meta( $post_ID, 'qcld_text_group' );
			if(isset($cntpost[0]) && is_array($cntpost[0])){
				echo count($cntpost[0]);
			}else{
				echo '0';
			}
	    }

	    if ($column_name == 'shortcode_text_col') {
	        echo esc_attr('[qcld-ilist mode="one" list_id="'.esc_attr($post_ID).'"]');
	    }
	}
}

add_filter('manage_ilist_posts_columns', 'qcilist_text_list_column_head');
add_action('manage_ilist_posts_custom_column', 'qcilist_list_text_columns_content', 10, 2);


/*TinyMCE button for Inserting Shortcode*/
/* Add Slider Shortcode Button on Post Visual Editor */
if(!function_exists('qcilist_tinymce_button_function')){
	function qcilist_tinymce_button_function() {
		add_filter ("mce_external_plugins", "qcilist_sld_btn_js");
		add_filter ("mce_buttons", "qcilist_sld_btn");
	}
}

if(!function_exists('qcilist_sld_btn_js')){
	function qcilist_sld_btn_js($plugin_array) {
		$plugin_array['ilist_short_btn'] = plugins_url('assets/js/qcld-tinymce-button.js', __FILE__);
		return $plugin_array;
	}
}

if(!function_exists('qcilist_sld_btn')){
	function qcilist_sld_btn($buttons) {
		array_push ($buttons, 'ilist_short_btn');
		return $buttons;
	}
}

add_action ('init', 'qcilist_tinymce_button_function'); 
if(!function_exists('qcilist_get_file_path')){
	function qcilist_get_file_path(){
	?>
	<div id="ilist_path" data-path="<?php echo QCOPD_ASSETS_URL1; ?>"></div>
	<?php
	}
}
add_action ('admin_footer', 'qcilist_get_file_path'); 

if(!function_exists('qcilist_pro_openai_meta_box')){
	function qcilist_pro_openai_meta_box(){

		// sl_openai_auto_generate_enable
		$sl_openai_auto_generate_enable = get_option( 'sl_openai_auto_generate_enable' );
		if( isset( $sl_openai_auto_generate_enable ) && $sl_openai_auto_generate_enable == 'on' ){
	    	add_meta_box("qcilist-openai-box", esc_html("Generate with OpenAI"), "qcilist_pro_custom_meta_box_openai", "ilist", "side", "high");

		}


	}
}
add_action("add_meta_boxes", "qcilist_pro_openai_meta_box");

if(!function_exists('qcilist_pro_custom_meta_box_openai')){
	function qcilist_pro_custom_meta_box_openai($object){
	    ?>

	    	<div class="qcld_openai_title_generate_wrap">
	    		<p><?php echo esc_html('Auto Generate Items with OpenAI', 'iList' ); ?> </p>
	    		<p><?php echo esc_html('Please add your title first.', 'iList' ); ?></p>
	    		<label for="qcld_openai_number"><?php echo esc_html('Number Of Items:', 'iList' ); ?></label>
	    		<input type="number" name="qcld_openai_number" id="qcld_openai_number" class="qcld_openai_number" value="6" />
	    		<div class="qcld_openai_title_generate_btn"><?php echo esc_html('Generate', 'iList' ); ?></div>
	    	</div>

	    <?php  
	}
}

if(!function_exists('qcilist_add_custom_meta_box')){
	function qcilist_add_custom_meta_box(){

	    add_meta_box("qcilist-meta-box", esc_html("Preview Box", 'iList' ), "qcilist_custom_meta_box_markup", "ilist", "side");
	}
}

add_action("add_meta_boxes", "qcilist_add_custom_meta_box");

if(!function_exists('qcilist_custom_meta_box_markup')){
	function qcilist_custom_meta_box_markup($object){

	    ?>
	        <div>
	            <br>
	            <?php if(isset($object->ID) && !empty($object->ID )): ?>
	            <a class="ilist-fancybox-show" post-id="<?php echo esc_attr($object->ID); ?>" href="#"><?php esc_html_e( 'Save & Preview', 'iList' ); ?></a>
	            <br>
	            <p><?php esc_html_e( 'You can also save as Image from the preview in the Pro version.', 'iList' ); ?></p>

	            <a style="display: none" href="<?php echo site_url(); ?>/embed-ilist/?order=ASC&mode=one&list_id=<?php echo esc_attr($object->ID); ?>&column=1&upvote=on&capture=true" data-fancybox-type="iframe" class="fancyboxIframe"><?php esc_html_e( 'Save & Preview', 'iList' ); ?></a>
	        	<?php endif; ?>

	        </div>

	    <?php  
	}
}

if(!function_exists('qcilist_shortcode_add_custom_meta_box')){
	function qcilist_shortcode_add_custom_meta_box(){
	    add_meta_box("qcilist-shortcode-meta-box", esc_html("Shortcode", 'iList' ), "qcilist_shortcode_custom_meta_box_markup", "ilist", "side");
	}
}
add_action("add_meta_boxes", "qcilist_shortcode_add_custom_meta_box");

if(!function_exists('qcilist_shortcode_custom_meta_box_markup')){
	function qcilist_shortcode_custom_meta_box_markup($object) {

	    ?>
	        <div>
	            <?php 

		            if(isset($object->ID) && !empty($object->ID) && ( isset($_GET['action']) && $_GET['action'] == 'edit')): 

					    $shortcode_column = get_post_meta( $object->ID, 'shortcode_column' );
					    $shortcode_disable_lightbox = get_post_meta( $object->ID, 'shortcode_disable_lightbox' );
					    $shortcode_upvote = get_post_meta( $object->ID, 'shortcode_upvote' );

					    $shortcode_column = ( isset($shortcode_column[0]) && !empty( $shortcode_column[0] ) ) ? $shortcode_column[0] : '';
					    $shortcode_disable_lightbox = ( isset($shortcode_disable_lightbox[0]) && !empty( $shortcode_disable_lightbox[0] ) ) ? $shortcode_disable_lightbox[0] : '';

		            	$qcld_ilist_shortcode =  '[qcld-ilist mode="one" list_id="'.esc_attr($object->ID).'"  column="'.esc_attr( $shortcode_column ).'" upvote="'.esc_attr($shortcode_upvote[0]).'" disable_lightbox="'.esc_attr($shortcode_disable_lightbox).'"]  <br> <p> '.esc_html('You can also use the iList Shortcode Generator in your page for additional options.', 'iList' ).' </p>'; 
						echo $qcld_ilist_shortcode;
					elseif(isset($object->ID) && !empty($object->ID)):
						$qcld_ilist_shortcode =  '[qcld-ilist mode="one" list_id="'.esc_attr($object->ID).'"  column="2" upvote="on" disable_lightbox="true"]  <br> <p> '.esc_html('You can also use the iList Shortcode Generator in your page for additional options.', 'iList' ).' </p>'; 
						echo $qcld_ilist_shortcode;
		            
		        	endif; 

	        	?>

	        </div>

	    <?php  
	}
}


if(!function_exists('qcilist_noted_add_custom_meta_box')){
	function qcilist_noted_add_custom_meta_box(){

	    add_meta_box("qcilist-noted-meta-box", esc_html("Note Box", 'iList' ), "qcilist_noted_custom_meta_box_markup", "ilist", "side");
	}
}
add_action("add_meta_boxes", "qcilist_noted_add_custom_meta_box");

if(!function_exists('qcilist_noted_custom_meta_box_markup')){
	function qcilist_noted_custom_meta_box_markup($object){

	    ?>
	        <div>
	            <?php if(isset($object->ID) && !empty($object->ID )): 

	            	$qcld_ilist_shortcode =  "<p style='color:indianred'>".esc_html("NB: iLists must be published before you add the shortcode to a page. iLists won't display on your page if it is in Draft mode. Don't worry, your iLists won't show until you add the shortcode to a page or post.", 'iList' )."</p>"; 
					echo $qcld_ilist_shortcode;
	            
	        	endif; ?>

	        </div>


	    <?php  
	}
}



/***************************************
 * custom post edit by ajax...
 ***************************************/
if(!function_exists('qcilist_custom_post_type_xhr')){
	function qcilist_custom_post_type_xhr(){
	    global $post;
	    if(isset($post->post_type) && ('ilist' === $post->post_type) ){
	        $post_url = admin_url('post.php'); #In case we're on post-new.php
	        echo "
	        <script>
	            jQuery(document).ready(function($){
	                //Click handler - you might have to bind this click event another way
	                $('a.ilist-fancybox-show').click(function(){

	                    //Post to post.php
	                    var postURL = '$post_url';

	                    //Collate all post form data
	                    var data = $('form#post').serializeArray();

	                    //Set a trigger for our save_post action
	                    data.push({foo_doing_ajax: true});

	                    //The XHR Goodness
	                    $.post(postURL, data, function(response){
	                       
	                    });
	                    return false;
	                });
	            });
	        </script>";
	    }
	}
}
add_action('admin_head-post.php', 'qcilist_custom_post_type_xhr');
add_action('admin_head-post-new.php', 'qcilist_custom_post_type_xhr');

add_action('save_post', 'qcilist_save_my_custom_post_type');
if(!function_exists('qcilist_save_my_custom_post_type')){
	function qcilist_save_my_custom_post_type($post_id){
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	    global $post;
	    if(isset($post->post_type) && ('ilist' === $post->post_type) ){

	    #If this is your post type
	    //if( isset($_POST['post_type']) && ('ilist' === $_POST['post_type'])){
	        //Save any post meta here

	        #We conditionally exit so we don't return the full wp-admin load if foo_doing_ajax is true
	        if(isset($_POST['foo_doing_ajax']) && $_POST['foo_doing_ajax'] === true ){
	            header('Content-type: application/json');
	            #Send a response
	            echo json_encode(array('success' => true));
	            exit;
	            #You should keep this conditional to degrade gracefully for no JS
	        }
	    }
	}
}



add_action('wp_ajax_ilist_embaded_list_url_info', 'ilist_embaded_list_url_info');
add_action('wp_ajax_nopriv_ilist_embaded_list_url_info', 'ilist_embaded_list_url_info');
if(!function_exists('ilist_embaded_list_url_info')){
	function ilist_embaded_list_url_info(){

		check_ajax_referer( 'qcld-ilist', 'security');

	    $post_id  					= sanitize_text_field($_POST['post_id']);
	    $shortcode_column 			= get_post_meta( $post_id, 'shortcode_column' );
	    $shortcode_disable_lightbox = get_post_meta( $post_id, 'shortcode_disable_lightbox' );
	    $shortcode_upvote 			= get_post_meta( $post_id, 'shortcode_upvote' );

	    $shortcode_column 			= ( isset($shortcode_column[0]) && !empty( $shortcode_column[0] ) ) ? $shortcode_column[0] : '';
	    $shortcode_upvote 			= ( isset($shortcode_upvote[0]) && !empty( $shortcode_upvote[0] ) ) ? $shortcode_upvote[0] : '';
	    $shortcode_disable_lightbox = ( isset($shortcode_disable_lightbox[0]) && !empty( $shortcode_disable_lightbox[0] ) ) ? $shortcode_disable_lightbox[0] : '';

	    $embaded_url 				= site_url()."/embed-ilist/?order=ASC&mode=one&list_id=".esc_attr($post_id)."&column=".$shortcode_column."&upvote=".$shortcode_upvote."&capture=true";

	    $shortcode_generate 		= '[qcld-ilist mode="one" list_id="'.esc_attr($post_id).'" column="'.$shortcode_column.'"  upvote="'.$shortcode_upvote.'" disable_lightbox="'.$shortcode_disable_lightbox.'" ]' .'<br>'. '<p> '.esc_html('You can also use the iList Shortcode Generator in your page for additional options.', 'iList' ).' </p>' ;


	    wp_reset_query();
	    $response = array(
	        'html' => $embaded_url,
	        'short_code' => $shortcode_generate,
	    );
	    echo wp_send_json($response);
	    wp_die();

	}
}