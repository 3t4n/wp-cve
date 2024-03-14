<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

function storeone_extension_cpt_init(){
	$labels = array(
		'name'               => _x('Home Slider', 'Post Type General Name', 'storeone-extension'),
		'all_items'          => esc_html__('All Slides', 'storeone-extension'),
		'add_new_item'       => esc_html__('Add New Slide', 'storeone-extension'),
		'add_new'            => esc_html__('Add Slide', 'storeone-extension'),
		'new_item'           => esc_html__('New Slide', 'storeone-extension'),
		'edit_item'          => esc_html__('Edit Slide', 'storeone-extension'),
		'update_item'        => esc_html__('Update Slide', 'storeone-extension'),
		'view_item'          => esc_html__('View Slide', 'storeone-extension'),
		'search_items'       => esc_html__('Search Slide', 'storeone-extension'),
		'not_found'          => esc_html__('No Slides found', 'storeone-extension'),
		'not_found_in_trash' => esc_html__('No Slide found in Trash', 'storeone-extension'),
	);
	$args = array(
		'label'               => esc_html__('Slider', 'storeone-extension'),
		'description'         => esc_html__('Add Slider for home page', 'storeone-extension'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-images-alt2',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('tf_slider', $args);

	$labels = array(
		'name'               => _x('Blog Slider', 'Post Type General Name', 'storeone-extension'),
		'all_items'          => esc_html__('All Slides', 'storeone-extension'),
		'add_new_item'       => esc_html__('Add New Slide', 'storeone-extension'),
		'add_new'            => esc_html__('Add Slide', 'storeone-extension'),
		'new_item'           => esc_html__('New Slide', 'storeone-extension'),
		'edit_item'          => esc_html__('Edit Slide', 'storeone-extension'),
		'update_item'        => esc_html__('Update Slide', 'storeone-extension'),
		'view_item'          => esc_html__('View Slide', 'storeone-extension'),
		'search_items'       => esc_html__('Search Slide', 'storeone-extension'),
		'not_found'          => esc_html__('No Slides found', 'storeone-extension'),
		'not_found_in_trash' => esc_html__('No Slide found in Trash', 'storeone-extension'),
	);
	$args = array(
		'label'               => esc_html__('Slider', 'storeone-extension'),
		'description'         => esc_html__('Add Slider for home page', 'storeone-extension'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-images-alt2',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('tf_blog_slider', $args);
	
	$labels = array(
		'name'               => _x('Shop Slider', 'Post Type General Name', 'storeone-extension'),
		'all_items'          => esc_html__('All Slides', 'storeone-extension'),
		'add_new_item'       => esc_html__('Add New Slide', 'storeone-extension'),
		'add_new'            => esc_html__('Add Slide', 'storeone-extension'),
		'new_item'           => esc_html__('New Slide', 'storeone-extension'),
		'edit_item'          => esc_html__('Edit Slide', 'storeone-extension'),
		'update_item'        => esc_html__('Update Slide', 'storeone-extension'),
		'view_item'          => esc_html__('View Slide', 'storeone-extension'),
		'search_items'       => esc_html__('Search Slide', 'storeone-extension'),
		'not_found'          => esc_html__('No Slides found', 'storeone-extension'),
		'not_found_in_trash' => esc_html__('No Slide found in Trash', 'storeone-extension'),
	);
	$args = array(
		'label'               => esc_html__('Slider', 'storeone-extension'),
		'description'         => esc_html__('Add Slider for home page', 'storeone-extension'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-images-alt2',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('tf_shop_slider', $args);
	

	$labels = array(
		'name'               => _x('Testimonials', 'Post Type General Name', 'storeone-extension'),
		'parent_item_colon'  => esc_html__('Parent Item:', 'storeone-extension'),
		'all_items'          => esc_html__('All Testmonial', 'storeone-extension'),
		'add_new_item'       => esc_html__('Add New Testimonial', 'storeone-extension'),
		'add_new'            => esc_html__('Add Testimonial', 'storeone-extension'),
		'new_item'           => esc_html__('New Testimonial', 'storeone-extension'),
		'edit_item'          => esc_html__('Edit Testimonial', 'storeone-extension'),
		'update_item'        => esc_html__('Update Testimonial', 'storeone-extension'),
		'view_item'          => esc_html__('View Testimonial', 'storeone-extension'),
		'search_items'       => esc_html__('Search Testimonial', 'storeone-extension'),
		'not_found'          => esc_html__('Not found Testimonial', 'storeone-extension'),
		'not_found_in_trash' => esc_html__('Not found Testimonial in Trash', 'storeone-extension'),
	);

	$args = array(
		'label'               => esc_html__('Testmonial', 'storeone-extension'),
		'description'         => esc_html__('Add Testimonials for home page', 'storeone-extension'),
		'labels'              => $labels,
		'supports'            => array('title', 'thumbnail', 'editor'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-awards',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	register_post_type('tf_testimonials', $args);
}
add_action('init', 'storeone_extension_cpt_init');



function storeone_extension_register_slider_meta_box() {
    add_meta_box( 'storeone-extension-slider', __( 'Slider Data', 'storeone-extension' ), 'storeone_extension_slider_meta_box', array('tf_slider', 'tf_shop_slider', 'tf_blog_slider') );
}
add_action( 'add_meta_boxes', 'storeone_extension_register_slider_meta_box' );

function storeone_extension_slider_meta_box($post){
	$slider_data 	= get_post_meta($post->ID, 'tf_slider_data', true);
	$slide_link     = isset($slider_data['button_one_link'])?$slider_data['button_one_link']:'';
	?>
		<?php wp_nonce_field( 'storeone_meta_nonce', 'storeone_meta_nonce' ); ?>
		<table>
			<tbody>
				<tr>
					<th><?php esc_html_e('Button Link', 'storeone-extension'); ?></th>
					<td><input type="text" name="button_one_link" value="<?php echo esc_url($slide_link) ?>"></td>
				</tr>
			</tbody>
		</table>
	<?php
}

function storeone_extension_slider_meta_box_save($post_id, $post){
	
	if(isset( $_POST['storeone_meta_nonce'] ) && wp_verify_nonce( $_POST['storeone_meta_nonce'], 'storeone_meta_nonce' )) {
		$tf_slider_data['button_one_link'] = esc_url_raw($_POST['button_one_link']);
		update_post_meta( $post_id, 'tf_slider_data', $tf_slider_data);
	}
	
}
add_action('save_post', 'storeone_extension_slider_meta_box_save', 10, 2);


