<?php
/**
 * The cpt plugin class.
 *
 * This is used to define the custom post type that will be used for galleries
 *
 * @since      1.0.0
 */
class Timeline_WP_CPT {
    
	private $labels    = array();
	private $args      = array();
	private $metaboxes = array();
	private $cpt_name;
    private $builder;
    
    
	public function __construct() {

		
        $this->labels = apply_filters('timeline_wp_cpt_labels', array(
            'singular_name'         => esc_html__( 'Timeline WP', timeline_wp ),
			'menu_name'             => esc_html__( 'Timeline WP', timeline_wp ),
			'name_admin_bar'        => esc_html__( 'Timeline WP', timeline_wp ),
			'archives'              => esc_html__( 'Item Archives', timeline_wp ),
			'attributes'            => esc_html__( 'Item Attributes', timeline_wp ),
			'parent_item_colon'     => esc_html__( 'Parent Item:', timeline_wp ),
			'all_items'             => esc_html__( 'Timelines', timeline_wp ),
			'add_new_item'          => esc_html__( 'Add New Item', timeline_wp ),
			'add_new'               => esc_html__( 'Add New', timeline_wp ),
			'new_item'              => esc_html__( 'New Item', timeline_wp ),
			'edit_item'             => esc_html__( 'Edit Item', timeline_wp ),
			'update_item'           => esc_html__( 'Update Item', timeline_wp ),
			'view_item'             => esc_html__( 'View Item', timeline_wp ),
			'view_items'            => esc_html__( 'View Items', timeline_wp ),
			'search_items'          => esc_html__( 'Search Item', timeline_wp ),
			'not_found'             => esc_html__( 'Not found', timeline_wp ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', timeline_wp ),
			'featured_image'        => esc_html__( 'Featured Image', timeline_wp ),
			'set_featured_image'    => esc_html__( 'Set featured image', timeline_wp ),
			'remove_featured_image' => esc_html__( 'Remove featured image', timeline_wp ),
			'use_featured_image'    => esc_html__( 'Use as featured image', timeline_wp ),
			'insert_into_item'      => esc_html__( 'Insert into item', timeline_wp ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', timeline_wp ),
			'items_list'            => esc_html__( 'Items list', timeline_wp ),
			'items_list_navigation' => esc_html__( 'Items list navigation', timeline_wp ),
			'filter_items_list'     => esc_html__( 'Filter items list', timeline_wp ),
        ));

        $this->args = apply_filters( 'timeline_wp_cpt_args', array(
			'label'                 => esc_html__( 'Timeline WP', timeline_wp ),
			'description'           => esc_html__( 'Timeline Post Type Description.', timeline_wp ),
			'supports'              => array( 'title' ),
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => TIMELINE_WP_IMAGES . 'timeline-icon-small.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'rewrite'               => false,
			'show_in_rest'          => true,
        ) );
        
        $this->metaboxes = apply_filters( 'timeline_wp_cpt_metaboxes', array(
			'timeline-wp-builder' => array(
				'title' => esc_html__( 'Timeline Settings Panel ', timeline_wp ),
				'callback' => 'output_timeline_builder',
				'context' => 'normal',
			),
			
			'timeline-wp-settings' => array(
				'title' => esc_html__( 'Settings', timeline_wp ),
				'callback' => 'output_gallery_settings',
				'context' => 'normal',
			),

			'timeline-wp-upgrade-to-pro' => array(
				'title' => esc_html__( 'Upgrade To Pro', timeline_wp ),
				'callback' => 'output_upgrade_to_pro',
				'context' => 'side',
			),
			
        ) );
        
		$this->cpt_name = apply_filters( 'timeline_wp_cpt_name', 'timeline_wp' );

        add_action( 'init', array( $this, 'register_cpt' ) );

        /* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'meta_boxes_setup' ) );
        add_action( 'load-post-new.php', array( $this, 'meta_boxes_setup' ) );
        
        // Action to add admin menu
		add_action( 'admin_menu', array($this, 'teh_register_menu'), 12 );
        
		// Post Table Columns
		add_filter( "manage_{$this->cpt_name}_posts_columns", array( $this, 'add_columns' ) );
		add_action( "manage_{$this->cpt_name}_posts_custom_column" , array( $this, 'outpu_column' ), 10, 2 );

		/* Load Fields Helper */
		require_once TIMELINE_WP_ADMIN . 'class-timeline-wp-cpt-fields-helper.php';

		/* Load Builder */
		require_once TIMELINE_WP_ADMIN . 'class-timeline-wp-field-builder.php';
		$this->builder = Timline_WP_Field_Builder::get_instance();

		// /* Initiate Image Resizer */
		// $this->resizer = new Timline_WP_Image();

		// Ajax for removing notices
        // add_action( 'wp_ajax_timeline-wp-edit-notice', array( $this, 'dismiss_edit_notice' ) );
        

    }
    
	public function register_cpt() {

		$args = $this->args;
		$args['labels'] = $this->labels;
		register_post_type( $this->cpt_name, $args );

    }

    public function teh_register_menu() {

		// How It Work Page
		add_submenu_page( 'edit.php?post_type='.timeline_wp, __('Documentation, our plugins and offers', timeline_wp), __('Documentation', timeline_wp), 'manage_options', 'teh-designs', array($this, 'teh_designs_page') );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.timeline_wp, __('Upgrade To Premium -  Timeline Event History', timeline_wp), '<span style="color:#57a7c9">'.__('Upgrade To Premium', timeline_wp).'</span>', 'manage_options', 'teh-premium', array($this, 'teh_premium_page') );

		// Installation Other WPdiscover plugin
		add_submenu_page( 'edit.php?post_type='.timeline_wp, __('Install Popular Plugins From WPdiscover', timeline_wp), '<span style="color:#ff2700">'.__('Install Popular Plugins From WPdiscover', timeline_wp).'</span>', 'manage_options', 'teh-dashboard', array($this, 'teh_dashboard_page') );


	}

	function teh_designs_page() {
		include_once( TIMELINE_WP_INCLUDES . 'admin/teh-how-it-work.php' );
	}

	function teh_premium_page() {
		include_once( TIMELINE_WP_INCLUDES . 'admin/teh-premium.php' );
	}

	function teh_dashboard_page() {
		include_once( TIMELINE_WP_INCLUDES . 'admin/teh-dashboard.php' );
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
            
            if ( 'timeline-wp-shortcode' == $metabox_id && 'auto-draft' == $post->post_status ) {
				break;
			}
            
			add_meta_box(
                $metabox_id,      // Unique ID
			    $metabox['title'],    // Title
			    array( $this, $metabox['callback'] ),   // Callback function
			    'timeline_wp',         // Admin page (or post type)
			    $metabox['context'],         // Context
			    'high'         // Priority
			);
		}

    }
    
    public function output_timeline_builder() {
		
 		$this->builder->render( 'builder' );
	}

	public function output_gallery_settings() {
        $this->builder->render( 'settings' );
	}

	public function output_upgrade_to_pro() {
        $this->builder->render( 'upgrade-to-pro' );
	}

    
	public function save_meta_boxes( $post_id, $post ) {

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );
		
		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) || 'timeline_wp' != $post_type->name ) {
			return $post_id;
		}
        if (empty($_POST)) {
            return ;
		}
		extract($_POST);
		
		$timelinesPostData = array();
		for ( $i = 0; $i < count( $order ) ; $i++ ) {

            $twpData =  array( 
				'twp_index' 		=> $i,
				'twp_title' 		=> isset( $twp_title[ $i ] ) ? $twp_title[ $i ] : '',
				'twp_date' 			=> isset( $twp_date[ $i ] ) ? $twp_date[ $i ] : '',
				'icon_type' 		=> isset( $icon_type[ $i ] ) ? $icon_type[ $i ] : '',
				'twp_icon' 			=> isset( $twp_icon[ $i ] ) ? $twp_icon[ $i ] : '',
				'twp_image' 		=> isset( $twp_image[ $i ] ) ? $twp_image[ $i ] : '',
				'twp_description' 	=> isset( $twp_description[ $i ] ) ? $twp_description[ $i ] : '',
			);
			
			array_push( $timelinesPostData , $twpData );
		}
        $timelinesPostData = serialize($timelinesPostData);
		
		update_post_meta( $post_id, 'timelines-data', $timelinesPostData );
		// $timelines = get_post_meta( $post_id, 'timelines-data', true );
		
		
		if ( isset( $_POST['timeline-wp-settings'] ) ) {
			
			$fields_with_tabs = Timline_WP_CPT_Fields_Helper::get_fields( 'all' );

			// Here we will save all our settings
			$timeline_wp_settings = array();

			// We will save only our settings.
			foreach ( $fields_with_tabs as $tab => $fields ) {

				// We will iterate throught all fields of current tab
				foreach ( $fields as $field_id => $field ) {

					if ( isset( $_POST['timeline-wp-settings'][ $field_id ] ) ) {

						// Values for selects
						$lightbox_values = apply_filters( 'timeline_wp_lightbox_values', array( 'no-link', 'direct', 'lightbox2', 'attachment-page' ) );
						$effect_values   = apply_filters( 'timeline_wp_effect_values', array( 'none', 'pufrobo' ) );

						switch ( $field_id ) {
							case 'description':
								$timeline_wp_settings[ $field_id ] = wp_filter_post_kses( $_POST['timeline-wp-settings'][ $field_id ] );
								break;
							case 'margin':
							case 'captionFontSize':
							case 'titleFontSize':
							case 'borderSize':
							case 'borderRadius':
							case 'shadowSize':
								$timeline_wp_settings[ $field_id ] = absint( $_POST['timeline-wp-settings'][ $field_id ] );
								break;
							case 'lightbox' :
								if ( in_array( $_POST['timeline-wp-settings'][ $field_id ], $lightbox_values ) ) {
									$timeline_wp_settings[ $field_id ] = sanitize_text_field( $_POST['timeline-wp-settings'][ $field_id ] );
								}else{
									$timeline_wp_settings[ $field_id ] = 'lightbox2';
								}
								break;
							case 'captionColor':
							case 'socialIconColor':
							case 'borderColor':
							case 'shadowColor':
								$timeline_wp_settings[ $field_id ] = sanitize_hex_color( $_POST['timeline-wp-settings'][ $field_id ] );
								break;
							case 'Effect' :
								if ( in_array( $_POST['timeline-wp-settings'][ $field_id ], $effect_values ) ) {
									$timeline_wp_settings[ $field_id ] = $_POST['timeline-wp-settings'][ $field_id ];
								}else{
									$timeline_wp_settings[ $field_id ] = 'pufrobo';
								}
								break;
							default:
								if( is_array( $_POST['timeline-wp-settings'][ $field_id ] ) ){
									$sanitized = array_map( 'sanitize_text_field', $_POST['timeline-wp-settings'][ $field_id ] );
									$timeline_wp_settings[ $field_id ] = apply_filters( 'timeline_wp_settings_field_sanitization', $sanitized, $_POST['timeline-wp-settings'][ $field_id ] ,$field_id, $field );
								}else{
									$timeline_wp_settings[ $field_id ] = apply_filters( 'timeline_wp_settings_field_sanitization', sanitize_text_field( $_POST['timeline-wp-settings'][ $field_id ] ), $_POST['timeline-wp-settings'][ $field_id ] ,$field_id, $field );
								}

								break;
						}

					} else {
						if ( 'toggle' == $field['type'] ) {
							$timeline_wp_settings[ $field_id ] = '0';
						}else{
							$timeline_wp_settings[ $field_id ] = '';
						}
					}

				}

			}


			// Add settings to gallery meta
			update_post_meta( $post_id, 'timeline-wp-settings',  $timeline_wp_settings  );

		}

	}

    

    public function add_columns( $columns ){

		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['shortcode'] = esc_html__( 'Shortcode', timeline_wp );
		$columns['date'] = $date;
		return $columns;

	}

	public function outpu_column( $column, $post_id ){

		if ( 'shortcode' == $column ) {
			$shortcode = '[timeline_wp id="' . $post_id . '"]';
			echo '<input type="text" value="' . esc_attr( $shortcode ) . '"  onclick="select()" readonly>';
            echo '<a href="#" class="copy-timeline-wp-shortcode button button-primary button-timeline" style="margin-left:15px;">'.esc_html__('Copy shortcode',timeline_wp).'</a><span style="margin-left:15px;"></span>';
		}

	}

}

function teh_sort_plugin_data( $a, $b ) {

	$a_active_installs = is_numeric( $a['active_installs'] ) ? $a['active_installs'] : 0;
	$b_active_installs = is_numeric( $b['active_installs'] ) ? $b['active_installs'] : 0;
	
	if ($a_active_installs == $b_active_installs) {
		return 0;
	}
	return ($a_active_installs > $b_active_installs) ? -1 : 1;
}

function teh_get_plugin_data() {

	// Get cache result
	$plugins_data = get_transient( 'teh_plugins_data' );

	// If no cache is there
	if( empty( $plugins_data ) ) {

		// Call Plugin API
		if ( ! function_exists( 'plugins_api' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin-install.php';
		}

		$plugins_data = plugins_api( 'query_plugins', array(
											'per_page'	=> 60,
											'author'	=> 'wpdiscover',
											'fields'	=> array(
																'icons'				=> true,
																'active_installs'	=> true,
															)
										) );

		if( is_wp_error( $plugins_data ) || empty( $plugins_data->plugins ) ) {

			$file = WPOS_ESPBW_DIR . 'plugins-data.json';

			// We don't need to write to the file, so just open for reading.
			$fp = fopen( $file, 'r' );

			// Pull data of the file in.
			$file_data = fread( $fp, 1024 * KB_IN_BYTES );

			// Close file handle
			fclose( $fp );

			$file_data				= utf8_encode($file_data); 
			$plugins_data_arr		= json_decode( $file_data, true );
			$plugins_data			= json_decode( $file_data );
			$plugins_data->plugins	= $plugins_data_arr['plugins'];
		}

		if( ! is_wp_error( $plugins_data ) && ! empty( $plugins_data->plugins ) ) {

			// Sort the data based on active install
			usort( $plugins_data->plugins, "teh_sort_plugin_data" );

			set_transient( 'teh_plugins_data', $plugins_data, (12 * HOUR_IN_SECONDS) );
		}
	}

	return $plugins_data;
}


function teh_plugins_filter() {

	$plugin_filters = array(
		'photo-gallery-builder'						=> array(
												'class' => 'teh-photo-gallery-builder',
												'tags'	=> 'gallery, image slider, lightbox',
											),
		'accordion-slider-gallery'			=> array(
												'class' => 'teh-accordion-slider-gallery',
												'tags'	=> 'accordion slider, Accordion Slider Block',
											),

		'blog-manager-wp'			=> array(
												'class' => 'teh-blog-manager-wp',
												'tags'	=> 'blog design, blog layout, blog template, custom blog template, wordpress blog',
											),
		'coming-soon-countdown'			=> array(
												'class' => 'teh-coming-soon-countdown',
												'tags'	=> 'admin, coming soon, offline mode, site offline',
											),
		'client-partner-showcase'			=> array(
												'class' => 'teh-client-partner-showcase',
												'tags'	=> 'Client, partner, sponsors',
											),
		'pricetable-wp'			=> array(
												'class' => 'teh-pricetable-wp',
												'tags'	=> 'price, price table, pricing, table price',
											),
		'social-feed-gallery-portfolio'			=> array(
												'class' => 'teh-social-feed-gallery-portfolio',
												'tags'	=> 'instagram gallery, instagram post, Instagram widget',
											),
		
	);
	return $plugin_filters;
}