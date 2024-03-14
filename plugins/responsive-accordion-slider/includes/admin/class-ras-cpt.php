<?php
/**
 * The cpt plugin class.
 *
 * This is used to define the custom post type that will be used for galleries
 *
 * @since      1.0.1
 */
class Resp_Accordion_Slider_CPT {
    
	private $labels    = array();
	private $args      = array();
	private $metaboxes = array();
	private $cpt_name;
    private $builder;
    private $resizer;
    
    
	public function __construct() {

        $this->labels = apply_filters('resp_accordion_slider_cpt_labels', array(
            'singular_name'         => esc_html__( 'Accordion Slider', 'responsive-accordion-slider' ),
			'menu_name'             => esc_html__( 'Accordion Slider', 'responsive-accordion-slider' ),
			'name_admin_bar'        => esc_html__( 'Accordion Slider', 'responsive-accordion-slider' ),
			'archives'              => esc_html__( 'Item Archives', 'responsive-accordion-slider' ),
			'attributes'            => esc_html__( 'Item Attributes', 'responsive-accordion-slider' ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', 'responsive-accordion-slider' ),
			'all_items'             => esc_html__( 'All Accordions', 'responsive-accordion-slider' ),
			'add_new_item'          => esc_html__( 'Add New Item', 'responsive-accordion-slider' ),
			'add_new'               => esc_html__( 'Add New', 'responsive-accordion-slider' ),
			'new_item'              => esc_html__( 'New Item', 'responsive-accordion-slider' ),
			'edit_item'             => esc_html__( 'Edit Item', 'responsive-accordion-slider' ),
			'update_item'           => esc_html__( 'Update Item', 'responsive-accordion-slider' ),
			'view_item'             => esc_html__( 'View Item', 'responsive-accordion-slider' ),
			'view_items'            => esc_html__( 'View Items', 'responsive-accordion-slider' ),
			'search_items'          => esc_html__( 'Search Item', 'responsive-accordion-slider' ),
			'not_found'             => esc_html__( 'Not found', 'responsive-accordion-slider' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'responsive-accordion-slider' ),
			'featured_image'        => esc_html__( 'Featured Image', 'responsive-accordion-slider' ),
			'set_featured_image'    => esc_html__( 'Set featured image', 'responsive-accordion-slider' ),
			'remove_featured_image' => esc_html__( 'Remove featured image', 'responsive-accordion-slider' ),
			'use_featured_image'    => esc_html__( 'Use as featured image', 'responsive-accordion-slider' ),
			'insert_into_item'      => esc_html__( 'Insert into item', 'responsive-accordion-slider' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'responsive-accordion-slider' ),
			'items_list'            => esc_html__( 'Items list', 'responsive-accordion-slider' ),
			'items_list_navigation' => esc_html__( 'Items list navigation', 'responsive-accordion-slider' ),
			'filter_items_list'     => esc_html__( 'Filter items list', 'responsive-accordion-slider' ),
        ));

        $this->args = apply_filters( 'resp_accordion_slider_cpt_args', array(
			'label'                 => esc_html__( 'Accordion Slider', 'responsive-accordion-slider' ),
			'description'           => esc_html__( 'Accordion Slider Post Type Description.', 'responsive-accordion-slider' ),
			'supports'              => array( 'title' ),
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-images-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'rewrite'               => false,
			'show_in_rest'          => true,
        ) );
        
        $this->metaboxes = apply_filters( 'resp_accordion_slider_cpt_metaboxes', array(
			'ras-slider-builder' => array(
				'title' => esc_html__( 'Slider Images', 'responsive-accordion-slider' ),
				'callback' => 'output_accordion_slider_builder',
				'context' => 'normal',
			),
			'ras-accordion-slider-settings' => array(
				'title' => esc_html__( 'Settings', 'responsive-accordion-slider' ),
				'callback' => 'output_gallery_settings',
				'context' => 'normal',
			),
			'ras-pro-upgrade' => array(
				'title' => esc_html__( 'Upgrade To PRO', 'responsive-accordion-slider' ),
				'callback' => 'output_pro_upgrade',
				'context' => 'side',
			),
			'ras-review' => array(
				'title' => esc_html__( 'Rate Our Plugin', 'responsive-accordion-slider' ),
				'callback' => 'output_gallery_review',
				'context' => 'side',
			 	'priority' => 'default',
			),
			 'ras-shortcode' => array(
				'title' => esc_html__( 'Shortcode', 'responsive-accordion-slider' ),
			 	'callback' => 'output_gallery_shortcode',
			 	'context' => 'side',
			 	'priority' => 'default',
			 ),
        ) );
        
		$this->cpt_name = apply_filters( 'resp_accordion_slider_cpt_name', 'ras-accordion-slider' );

        add_action( 'init', array( $this, 'register_cpt' ) );

        /* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'meta_boxes_setup' ) );
        add_action( 'load-post-new.php', array( $this, 'meta_boxes_setup' ) );

        // Add Menu Page
  		add_action( 'admin_menu', array($this, 'ras_register_premium_menu'), 10 ); 
        
		// Post Table Columns
		add_filter( "manage_{$this->cpt_name}_posts_columns", array( $this, 'add_columns' ) );
		add_action( "manage_{$this->cpt_name}_posts_custom_column" , array( $this, 'output_column' ), 10, 2 );

		/* Load Fields Helper */
		require_once RESP_ACCORDION_SLIDER_ADMIN_PATH . 'class-ras-cpt-fields-helper.php';

		/* Load Builder */
		require_once RESP_ACCORDION_SLIDER_ADMIN_PATH . 'class-ras-field-builder.php';
		$this->builder = Resp_Accordion_Slider_Field_Builder::get_instance();

		/* Initiate Image Resizer */
		$this->resizer = new Resp_Accordion_Slider_Image();

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
    
    
	public function add_meta_boxes() {

		global $post;

		foreach ( $this->metaboxes as $metabox_id => $metabox ) {
            
            if ( 'ras-shortcode' == $metabox_id && 'auto-draft' == $post->post_status ) {
				break;
			}
            
			add_meta_box(
                $metabox_id,      // Unique ID
			    $metabox['title'],    // Title
			    array( $this, $metabox['callback'] ),   // Callback function
			    'ras-accordion-slider',         // Admin page (or post type)
			    $metabox['context'],         // Context
			    'high'         // Priority
			);
		}

    }
    
    public function output_accordion_slider_builder() {
 		 $this->builder->render( 'gallery' );
	}

	public function output_gallery_settings() {
        $this->builder->render( 'settings' );	
	}

	public function output_pro_upgrade() {
		$this->builder->render( 'upgrade-to-pro' );
	}

	public function output_gallery_review() {
		$this->builder->render( 'review' );
	}
	
	public function output_gallery_shortcode( $post ) {
		$this->builder->render( 'shortcode', $post );
	}

    
	public function save_meta_boxes( $post_id, $post ) {

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) || 'ras-accordion-slider' != $post_type->name ) {
			return $post_id;
		}

		// We need to resize our images
		$images = get_post_meta( $post_id, 'slider-images', true );
		if ( $images && is_array( $images ) ) {
			if ( isset( $_POST['ras-accordion-slider-settings']['img_size'] ) && apply_filters( 'resp_accordion_slider_resize_images', true, $_POST['ras-accordion-slider-settings'] ) ) {

				$gallery_type = isset( $_POST['ras-accordion-slider-settings']['type'] ) ? sanitize_text_field($_POST['ras-accordion-slider-settings']['type']) : 'creative-gallery';
				$img_size = absint( $_POST['ras-accordion-slider-settings']['img_size'] );
				
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


		if ( isset( $_POST['ras-accordion-slider-settings'] ) ) {

			
			$fields_with_tabs = RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_fields( 'all' );

			// Here we will save all our settings
			$accordion_slider_settings = array();

			// We will save only our settings.
			foreach ( $fields_with_tabs as $tab => $fields ) {

			    // We will iterate throught all fields of current tab
				foreach ( $fields as $field_id => $field ) {

					if ( isset( $_POST['ras-accordion-slider-settings'][ $field_id ] ) ) {

						
						switch ( $field_id ) {
							case 'description':
								$accordion_slider_settings[ $field_id ] = wp_filter_post_kses( $_POST['ras-accordion-slider-settings'][ $field_id ] );
								break;
							case 'img_size':
							
							case 'captionFontSize':
							case 'titleFontSize':
							case 'captionColor':
							
							default:
								if( is_array( $_POST['ras-accordion-slider-settings'][ $field_id ] ) ){
									$sanitized = array_map( 'sanitize_text_field', $_POST['ras-accordion-slider-settings'][ $field_id ] );
									$accordion_slider_settings[ $field_id ] = apply_filters( 'accordion_slider_settings_field_sanitization', $sanitized, $field_id, $field );
								}else{
									$accordion_slider_settings[ $field_id ] = apply_filters( 'accordion_slider_settings_field_sanitization', sanitize_text_field( $_POST['ras-accordion-slider-settings'][ $field_id ] ), $field_id, $field );
								}

								break;
						}

					}else{
						if ( 'toggle' == $field['type'] ) {
							$accordion_slider_settings[ $field_id ] = '0';
						}else{
							$accordion_slider_settings[ $field_id ] = '';
						}
					}

				}

			}

			// Add settings to gallery meta
			update_post_meta( $post_id, 'ras-accordion-slider-settings', $accordion_slider_settings );

		}

	}

	function ras_register_premium_menu() {

		// Register help page
		add_submenu_page( 'edit.php?post_type='.'ras-accordion-slider', 
			__('help', 'responsive-accordion-slider'), 
			'<span>'.__('Help', 'responsive-accordion-slider').'</span>', 
			'manage_options', 
			'ras-premium', 
			array($this, 'ras_premium_menu') );

		// Register premium features
		add_submenu_page( 'edit.php?post_type='.'ras-accordion-slider', 
			__('Upgrade To Premium', 'responsive-accordion-slider'), 
			'<span class="sp-go-pro-icon" style="background-image: url('.RESP_ACCORDION_SLIDER_IMAGES_PATH.'premium.svg);width: 20px;
    			height: 20px;display: block;float: left;margin-right: 1px;margin-left: -2px; margin-top: -2px;"></span> <span style="color: #11b916; font-weight: 700;">'.__('Go Premium', 'responsive-accordion-slider').'</span>', 
			'manage_options', 
			'https://techknowprime.com/product/accordion-slider-pro/'
			);
	}

	function ras_premium_menu(){
		include_once( RESP_ACCORDION_SLIDER_INCLUDES_PATH . 'helper/ras-premium-menu.php' );
	}

    

    public function add_columns( $columns ){

		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['shortcode'] = esc_html__( 'Shortcode', 'responsive-accordion-slider' );
		$columns['date'] = $date;

		return $columns;

	}

	public function output_column( $column, $post_id ){

		if ( 'shortcode' == $column ) {
			$shortcode = '[resp-slider id="' . $post_id . '"]';
			echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
		}

	}

}