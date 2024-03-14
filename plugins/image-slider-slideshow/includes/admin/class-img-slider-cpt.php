<?php
/**
 * The cpt plugin class.
 *
 * This is used to define the custom post type that will be used for galleries
 *
 * @since      1.0.0
 */
class Img_Slider_CPT {
    
	private $labels    = array();
	private $args      = array();
	private $metaboxes = array();
	private $cpt_name;
    private $builder;
    
    
	public function __construct() {

        $this->labels = apply_filters('img_slider_cpt_labels', array(
            'singular_name'         => esc_html__( 'Image Slider', 'img-slider' ),
			'menu_name'             => esc_html__( 'Image Slider', 'img-slider' ),
			'name_admin_bar'        => esc_html__( 'Image Slider', 'img-slider' ),
			'archives'              => esc_html__( 'Item Archives', 'img-slider' ),
			'attributes'            => esc_html__( 'Item Attributes', 'img-slider' ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', 'img-slider' ),
			'all_items'             => esc_html__( 'Galleries', 'img-slider' ),
			'add_new_item'          => esc_html__( 'Add New Item', 'img-slider' ),
			'add_new'               => esc_html__( 'Add New', 'img-slider' ),
			'new_item'              => esc_html__( 'New Item', 'img-slider' ),
			'edit_item'             => esc_html__( 'Edit Item', 'img-slider' ),
			'update_item'           => esc_html__( 'Update Item', 'img-slider' ),
			'view_item'             => esc_html__( 'View Item', 'img-slider' ),
			'view_items'            => esc_html__( 'View Items', 'img-slider' ),
			'search_items'          => esc_html__( 'Search Item', 'img-slider' ),
			'not_found'             => esc_html__( 'Not found', 'img-slider' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'img-slider' ),
			'featured_image'        => esc_html__( 'Featured Image', 'img-slider' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'img-slider' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'img-slider' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'img-slider' ),
			'insert_into_item'      => esc_html__( 'Insert into item', 'img-slider' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'img-slider' ),
			'items_list'            => esc_html__( 'Items list', 'img-slider' ),
			'items_list_navigation' => esc_html__( 'Items list navigation', 'img-slider' ),
			'filter_items_list'     => esc_html__( 'Filter items list', 'img-slider' ),
        ));

        $this->args = apply_filters( 'img_slider_cpt_args', array(
			'label'                 => esc_html__( 'Image Slider', 'img-slider' ),
			'description'           => esc_html__( 'Image Slider Post Type Description.', 'img-slider' ),
			'supports'              => array( 'title' ),
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-images-alt2',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'rewrite'               => false,
			'show_in_rest'          => true,
        ) );
        
        $this->metaboxes = apply_filters( 'img_slider_cpt_metaboxes', array(
			'img-slider-builder' => array(
				'title' => esc_html__( 'Gallery Images', 'img-slider' ),
				'callback' => 'output_portfolio_builder',
				'context' => 'normal',
			),
			'img-slider-settings' => array(
				'title' => esc_html__( 'Settings', 'img-slider' ),
				'callback' => 'output_gallery_settings',
				'context' => 'normal',
			),
			 'img-slider-shortcode' => array(
				'title' => esc_html__( 'Shortcode', 'img-slider' ),
			 	'callback' => 'output_gallery_shortcode',
			 	'context' => 'side',
			 	'priority' => 'default',
			 ),
			 'img-slider-upgrade-to-pro' => array(
				'title' => esc_html__( 'Upgrade to pro', 'img-slider' ),
			 	'callback' => 'output_upgrade_to_pro',
			 	'context' => 'side',
			 ),
        ) );
        
		$this->cpt_name = apply_filters( 'img_slider_cpt_name', 'img_slider' );

        add_action( 'init', array( $this, 'register_cpt' ) );

        /* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'meta_boxes_setup' ) );
        add_action( 'load-post-new.php', array( $this, 'meta_boxes_setup' ) );

        // Action to add admin menu
		add_action( 'admin_menu', array($this, 'imgs_register_menu'), 12 );
   
        
		// Post Table Columns
		add_filter( "manage_{$this->cpt_name}_posts_columns", array( $this, 'add_columns' ) );
		add_action( "manage_{$this->cpt_name}_posts_custom_column" , array( $this, 'output_column' ), 10, 2 );

		/* Load Fields Helper */
		require_once IMG_SLIDER_ADMIN . 'class-img-slider-cpt-fields-helper.php';

		/* Load Builder */
		require_once IMG_SLIDER_ADMIN . 'class-img-slider-field-builder.php';
		$this->builder = Img_Slider_Field_Builder::get_instance();

		/* Initiate Image Resizer */
		$this->resizer = new Img_Slider_Image();

	}
    
	public function register_cpt() {

		$args = $this->args;
		$args['labels'] = $this->labels;
		register_post_type( $this->cpt_name, $args );

    }
    public function meta_boxes_setup() {
		/* Add meta boxes on the 'add_meta_boxes' hook. */
  		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

  		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 10, 2 );
    }

    public function imgs_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.'img_slider', __('Upgrade To Premium -  Slider Slideshow Pro', 'img-slider'), '<span style="color:#57a7c9">'.__('Upgrade To Premium', 'img-slider').'</span>', 'manage_options', 'imgs-premium', array($this, 'imgs_premium_page') );
	}

	function imgs_premium_page() {
		include_once( IMG_SLIDER_INCLUDES . 'admin/imgs-premium.php' );
	}
    
    
	public function add_meta_boxes() {

		global $post;

		foreach ( $this->metaboxes as $metabox_id => $metabox ) {
            
            if ( 'img-slider-shortcode' == $metabox_id && 'auto-draft' == $post->post_status ) {
				break;
			}
            
			add_meta_box(
                $metabox_id,      // Unique ID
			    $metabox['title'],    // Title
			    array( $this, $metabox['callback'] ),   // Callback function
			    'img_slider',         // Admin page (or post type)
			    $metabox['context'],         // Context
			    'high'         // Priority
			);
		}

    }
    
    public function output_portfolio_builder() {
 		 $this->builder->render( 'gallery' );
	}

	public function output_gallery_settings() {
        $this->builder->render( 'settings' );	
	}

	public function output_gallery_shortcode( $post ) {
		$this->builder->render( 'shortcode', $post );
	}

	public function output_upgrade_to_pro() {
		$this->builder->render( 'upgrade-to-pro');
	}

    
	public function save_meta_boxes( $post_id, $post ) {

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) || 'img_slider' != $post_type->name ) {
			return $post_id;
		}

		// We need to resize our images
		$images = get_post_meta( $post_id, 'slider-images', true );
		if ( $images && is_array( $images ) ) {
			if ( isset( $_POST['img-slider-settings']['img_size'] ) && apply_filters( 'portfolio_wp_resize_images', true, $_POST['img-slider-settings'] ) ) {

				$gallery_type = isset( $_POST['img-slider-settings']['type'] ) ? sanitize_text_field($_POST['img-slider-settings']['type']) : 'creative-gallery';
				$img_size = absint( $_POST['img-slider-settings']['img_size'] );
				
				foreach ( $images as $image ) {
					$grid_sizes = array(
						'width' => isset( $image['width'] ) ? absint( $image['width'] ) : 1,
						'height' => isset( $image['height'] ) ? absint( $image['height'] ) : 1,
					);
					$sizes = $this->resizer->get_image_size( $image['id'], $img_size, $gallery_type, $grid_sizes );
					if ( ! is_wp_error( $sizes ) ) {
						$this->resizer->resize_image( $sizes['url'], $sizes['width'], $sizes['height'] );
					}

				}

			}
		}

		if ( isset( $_POST['img-slider-settings'] ) ) {

			
			$fields_with_tabs = Img_Slider_WP_CPT_Fields_Helper::get_fields( 'all' );

			// Here we will save all our settings
			$img_slider_settings = array();

			// We will save only our settings.
			foreach ( $fields_with_tabs as $tab => $fields ) {

			    // We will iterate throught all fields of current tab
				foreach ( $fields as $field_id => $field ) {

					if ( isset( $_POST['img-slider-settings'][ $field_id ] ) ) {

						
						switch ( $field_id ) {
							case 'description':
								$img_slider_settings[ $field_id ] = wp_filter_post_kses( $_POST['img-slider-settings'][ $field_id ] );
								break;
							
							default:
								if( is_array( $_POST['img-slider-settings'][ $field_id ] ) ){
									$sanitized = array_map( 'sanitize_text_field', $_POST['img-slider-settings'][ $field_id ] );
									$img_slider_settings[ $field_id ] = apply_filters( 'img_slider_settings_field_sanitization', $sanitized,$field_id, $field );
								}else{
									$img_slider_settings[ $field_id ] = apply_filters( 'img_slider_settings_field_sanitization', sanitize_text_field( $_POST['img-slider-settings'][ $field_id ] ), $field_id, $field );
								}

								break;
						}

					}else{
						if ( 'toggle' == $field['type'] ) {
							$img_slider_settings[ $field_id ] = '0';
						}else{
							$img_slider_settings[ $field_id ] = '';
						}
					}

				}

			}

			// Add settings to gallery meta
			update_post_meta( $post_id, 'img-slider-settings', $img_slider_settings );

		}

	}

    

    public function add_columns( $columns ){

		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['shortcode'] = esc_html__( 'Shortcode', 'img-slider' );
		$columns['date'] = $date;

		return $columns;

	}

	public function output_column( $column, $post_id ){

		if ( 'shortcode' == $column ) {
			$shortcode = '[img-slider id="' . $post_id . '"]';
			echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly style="width:32%;">';
            /*echo '<a href="#" class="copy-img-slider-shortcode button button-primary" style="margin-left:15px;">'.esc_html__('Copy shortcode','img-slider').'</a><span style="margin-left:15px;"></span>';*/
		}

	}

}

