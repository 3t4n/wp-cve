<?php

class Themify_Store_Locator {

	private $post_type = 'themify_storelocator';
	private $post_slug = 'store';
    public $repeating=false;
	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
	    static $instance=null;
	    if($instance===null){
			$instance=new self;
	    }
	    return $instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'i18n' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'custom_image_size' ));
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'query_vars',  array( $this, 'add_query_vars_filter') );
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'activation_redirect' ) );
			add_action( 'admin_menu', array( $this,'setting_menu' ) );
			add_action( 'admin_notices', array($this,'error_notice') );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
			add_filter( 'themify_specific_post_types', array( $this, 'specific_post_types' ) );
			add_filter( 'themify_metabox/fields/themify-meta-boxes', array( $this, 'meta_box' ), 999 );
			add_action( 'wp_ajax_themify_sl_ajax', array( $this, 'get_store_locations' ));
			add_action( 'wp_ajax_nopriv_themify_sl_ajax', array( $this, 'get_store_locations' ));
			if ( function_exists( 'themify_is_themify_theme' ) ) {
				add_filter( 'themify_exclude_CPT_for_sidebar', array( $this, 'exclude_sidebar_setting' ) );
			}
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'user_enqueue' ));
			add_filter( 'single_template', array( $this, 'single_post_template' ), 1 );
			add_shortcode( 'tsl_map', array( $this, 'shortcode_map' ) );
			add_shortcode( 'tsl_stores', array( $this, 'shortcode_store' ) );
		}
		if ( current_user_can( 'publish_posts' ) && get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
			add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
			add_action( 'wp_enqueue_editor', array( $this, 'tinymce_localize' ) );
		}
	}

	public function i18n() {
		load_plugin_textdomain( 'themify-store-locator', false, plugin_basename( THEMIFY_STORE_LOCATOR_DIR ) . '/languages' );
	}

	public function error_notice() {
		$map_key = self::get_api_key();
		if ( empty( $map_key ) ) { ?>
			<div class="notice notice-error">
				<p><?php printf( __('Themify Store Locator: missing Google Map Api Key, <a href="%s">click Here</a> to add Map Api.','themify-store-locator'), admin_url( 'edit.php?post_type=' . $this->post_type . '&page=themify-sl-setting' ) ); ?></p>
			</div>
		<?php }
	}

	function register_post_type() {
		$this->post_slug = get_option( 'themify_store_base_slug','store' );
		$labels = array(
			'name'               => _x( 'Store Locations', 'post type general name', 'themify-store-locator' ),
			'singular_name'      => _x( 'Store Locator', 'post type singular name', 'themify-store-locator' ),
			'menu_name'          => _x( 'Store Locations', 'admin menu', 'themify-store-locator' ),
			'name_admin_bar'     => _x( 'Store Locations', 'add new on admin bar', 'themify-store-locator' ),
			'add_new'            => _x( 'Add New', 'book', 'themify-store-locator' ),
			'add_new_item'       => __( 'Add New Location', 'themify-store-locator' ),
			'new_item'           => __( 'New Store Location', 'themify-store-locator' ),
			'edit_item'          => __( 'Edit Location', 'themify-store-locator' ),
			'all_items'          => __( 'Manage Locations', 'themify-store-locator' ),
			'not_found'          => __( 'No store location found.', 'themify-store-locator' ),
			'not_found_in_trash' => __( 'No store location found in Trash.', 'themify-store-locator' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Store Locations for Themify Store Locator map.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->post_slug ),
			'capability_type'    => 'post',
			'menu_position'      => 6, /* below Posts */
			'has_archive'        => false,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( $this->post_type , $args );

        // Register Category Taxonomy
        $labels = array(
            'name'              => _x( 'Categories', 'Store taxonomy general name', 'themify-store-locator' ),
            'singular_name'     => _x( 'Category', 'Store taxonomy singular name', 'themify-store-locator' ),
            'search_items'      => __( 'Search Categories', 'themify-store-locator' ),
            'all_items'         => __( 'All Categories', 'themify-store-locator' ),
            'parent_item'       => __( 'Parent Category', 'themify-store-locator' ),
            'parent_item_colon' => __( 'Parent Category:', 'themify-store-locator' ),
            'edit_item'         => __( 'Edit Category', 'themify-store-locator' ),
            'update_item'       => __( 'Update Category', 'themify-store-locator' ),
            'add_new_item'      => __( 'Add New Category', 'themify-store-locator' ),
            'new_item_name'     => __( 'New Category Name', 'themify-store-locator' ),
            'menu_name'         => __( 'Categories', 'themify-store-locator' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'store-category' ),
        );

        register_taxonomy( 'store_category', array( $this->post_type ), $args );

        unset( $args );
        unset( $labels );
	}
	
	function setting_menu(){
		add_submenu_page( 'edit.php?post_type='.$this->post_type, __('Themify Store Locator Settings','themify-store-locator'), __('Settings','themify-store-locator'), 'administrator', 'themify-sl-setting', array($this,'setting_page') );
		add_submenu_page( 'edit.php?post_type='.$this->post_type, __('About Themify Store Locator','themify-store-locator'), __('About','themify-store-locator'), 'administrator', 'themify-sl-about', array($this,'about_page') );
		
	}

	function custom_image_size(){
		add_image_size( 'tsl-single-size', get_option('single_image_size_w',1024), get_option('single_image_size_h',768), true );
		add_image_size( 'tsl-archive-size', get_option('archive_image_size_w',250), get_option('archive_image_size_h',250), true );
	}

	function setting_page(){
		if(isset($_POST['action']) && $_POST['action'] == 'themify_sl_data'){
			update_option( 'themify_sl_map_api', esc_sql($_REQUEST['google_map_api']) );
			update_option( 'themify_store_base_slug', esc_sql($_REQUEST['store_locator_slug']) );
			update_option( 'archive_image_size_w', esc_sql($_REQUEST['archive_image_size_width']) );
			update_option( 'archive_image_size_h', esc_sql($_REQUEST['archive_image_size_height']) );
			update_option( 'single_image_size_w', esc_sql($_REQUEST['single_image_size_width']) );
			update_option( 'single_image_size_h', esc_sql($_REQUEST['single_image_size_height']) );
		}
		$arhive_width  = get_option('archive_image_size_w',250);
		$arhive_height = get_option('archive_image_size_h',250);
		$single_width  = get_option('single_image_size_w',1024);
		$single_height  = get_option('single_image_size_h',768);
		?>
		
	<div class="wrap">
		<h2><?php echo __( 'Themify Store Locator Settings', 'themify-store-locator' ); ?></h2>
		<form action="" method="post">
			<style>
				.sl_table {
					text-align:left;
					width:100%;
				}
				.first_th h3{
					vertical-align: text-top;
					margin-top:0;
				}
			</style>
			<input type="hidden" name="action" value="themify_sl_data" />
			<br>
			<table class="sl_table">
				<tr>
					<th class="first_th" style="width:25%;"><h3><label for="google_map_api"><?php echo __( 'Google Map Key', 'themify-store-locator' ); ?></label></h3></th>
					<td >
						<?php echo sprintf('<input type="text" id="google_map_api" size="55" name="google_map_api" placeholder="%s" value="%s" autofocus><br>',__( 'Google Map API Goes Here', 'themify-store-locator' ), esc_attr(get_option('themify_sl_map_api',''))); ?>
						<span class="pushlabel">
							<small>
								<?php printf( __( 'Google API Key is required to use the plugin. <a href="%s">Generate an API key</a> and insert it here.', 'themify-store-locator' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ); ?>
							</small><br>
							<small>
								<?php printf( __( 'Geocoding API must be enabled. Go to your <a href="%s">GCP dashboard</a> and click on "ENABLE APIS AND SERVICES", find "Geocoding API" and make sure it\'s enabled.', 'themify-store-locator' ), 'https://console.cloud.google.com/apis/dashboard' ); ?>
							</small>
							</span>
					</td>
				</tr>
				<tr>
					<th class="first_th" style="width:25%;"><h3><label for="store_slug"><?php echo __( 'Store Base Slug', 'themify-store-locator' ); ?></label></h3></th>
					<td >
						<?php echo sprintf('<input type="text" id="store_slug" size="55" name="store_locator_slug" value="%s" autofocus><br>', esc_attr($this->post_slug)); ?>
						<span class="pushlabel"><small><?php echo __('Use only lowercase letters, numbers, underscores and dashes.', 'themify-store-locator'); ?> </small></span>
						<br />
						<span class="pushlabel"><small><?php echo sprintf(__('After changing this, go to <a href="%s">permalinks</a> and click "Save changes" to refresh them.', 'themify-store-locator'), admin_url('options-permalink.php')); ?></small></span><br />
					</td>
				</tr>
				<tr>
					<th class="first_th" style="width:25%;"><h3><label for="archive_image_size"><?php echo __( 'Archive Image Size', 'themify-store-locator' ); ?></label></h3></th>
					<td >
						<?php echo sprintf('<input type="text" id="archive_image_size" size="small" name="archive_image_size_width" value="%s"> X <input type="text" id="archive_image_size_height" size="small" name="archive_image_size_height" value="%s">', esc_attr($arhive_width), esc_attr($arhive_height)); ?>
						<br /><span class="pushlabel"><small><?php _e( 'Width X Height', 'themify-store-locator' ); ?></small></span>
					</td>
				</tr>
				<tr>
					<th class="first_th" style="width:25%;"><h3><label for="single_image_size"><?php echo __( 'Single Post Image Size', 'themify-store-locator' ); ?></label></h3></th>
					<td >
						<?php echo sprintf('<input type="text" id="single_image_size" size="small" name="single_image_size_width" value="%s"> X <input type="text" id="single_image_size_height" size="small" name="single_image_size_height" value="%s">', esc_attr($single_width), esc_attr($single_height)); ?>
						<br /><span class="pushlabel"><small><?php _e( 'Width X Height', 'themify-store-locator' ); ?></small></span>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	
	<?php
	
	}

	function about_page(){
		include (THEMIFY_STORE_LOCATOR_DIR.'assets/doc/about.html');
	}

	function specific_post_types( $types ) {
		$types[] = $this->post_type;
		return $types;
	}

	function meta_box( $panels ) {
		/* disabled in Page Options frontend editor */
		if ( ! empty( $_GET['tf-meta-opts'] ) ) {
			return $panels;
		}

		$options = include( $this->get_view_path( 'config-for-include.php' ) );
		$panels = array_merge( [ array(
			'name'=> __( 'Store Info', 'themify' ),
			'id' => 'themify-store-locator',
			'options' => $options,
			'pages' => $this->post_type,
			'default_active' => true,
		) ], $panels );
		return $panels;
	}

	function is_admin_screen() {
		$screen = get_current_screen();
		return $screen->base === 'post' && $screen->post_type === $this->post_type;
	}

	public function admin_enqueue() {
		if( ! $this->is_admin_screen() )
			return;
		self::themify_enque_style( 'themify-icons', THEMIFY_STORE_LOCATOR_URI . 'includes/themify-icons/themify-icons.css', null, THEMIFY_STORE_LOCATOR_VERSION);
		wp_enqueue_script( 'themify-store-locator-marker', self::themify_enque(THEMIFY_STORE_LOCATOR_URI . 'assets/js/marker.js'), null, THEMIFY_STORE_LOCATOR_VERSION, true );
		wp_enqueue_script( 'themify-store-locator', self::themify_enque(THEMIFY_STORE_LOCATOR_URI . 'assets/js/admin_js.js'), null, THEMIFY_STORE_LOCATOR_VERSION, true );
		wp_localize_script( 'themify-store-locator', 'ThemifyStoreLocator', array(
			'map_key' => self::get_api_key(),
		));
	}

	function user_enqueue(){
		wp_enqueue_script( 'themify-store-locator', self::themify_enque(THEMIFY_STORE_LOCATOR_URI . 'assets/js/user_js.js'), null, THEMIFY_STORE_LOCATOR_VERSION, true );
		wp_localize_script( 'themify-store-locator', 'themifyStoreLocator', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'marker_js' => self::themify_enque( THEMIFY_STORE_LOCATOR_URI . 'assets/js/marker.js' ),
			'map_js' => '//maps.googleapis.com/maps/api/js?key=' . self::get_api_key(),
		) );
		self::themify_enque_style( 'themify-store-locator', THEMIFY_STORE_LOCATOR_URI . 'assets/css/themify-store-locator-style.css', null, THEMIFY_STORE_LOCATOR_VERSION, 'all' );
	}

	function tinymce_localize() {
		$fields = include( $this->get_view_path( 'shortcode-fields.php' ) );
		wp_localize_script( 'editor', 'mceThemifyStoreLocator', array(
			'shortcodes' => $fields,
			'editor' => array(
				'menuTooltip' => __('Themify Store Locator Shortcodes', 'themify-store-locator'),
				'menuName' => __('Themify Store Locator Shortcodes', 'themify-store-locator'),
			),
			'url' => THEMIFY_STORE_LOCATOR_URI
		));
	}

	function mce_external_plugins( $mce_external_plugins ) {
		$mce_external_plugins['mceThemifyStoreLocator'] = THEMIFY_STORE_LOCATOR_URI . 'assets/js/tinymce.js';

		return $mce_external_plugins;
	}

	function mce_buttons( $mce_buttons ) {
		array_push( $mce_buttons, 'separator', 'mceThemifyStoreLocator' );
		return $mce_buttons;
	}

	function get_store_locations(){
        $category='';
        if(!empty($_POST['category'])){
            $category = sanitize_text_field($_POST['category']);
        }
		$stores = $this->get_stores($category);
		$locations = array();
		foreach($stores as $store){
			$location = array('position'=>'','content'=>'');
			$post_meta = get_post_meta($store->ID);
			$temp = json_decode(get_post_meta($store->ID,'themify_storelocator_address',true),true);
			if ( empty( $temp['position'] ) ) {
				continue;
			}
			$location['position'] = $temp['position'];
			$location['content'] = '<div style="text-align:center;width:100%;padding:10px 0px 10px 14px"><a href="'.esc_attr(get_post_permalink($store->ID)).'" target="_blank" style="text-decoration:none;color:black"><h3><b>'.esc_html($store->post_title).'</b></h3></a><div style="line-height:16px;font-size:13px;">'.esc_html($temp['address']).'<br>';
			$temp = json_decode(get_post_meta($store->ID,'themify_storelocator_numbers',true),true);
			foreach($temp as $data){
				$location['content'] .= $data['lable'].' '.$data['number'].'<br>';
			}
			$location['content'] .= '</div><div style="margin-top:5px;line-height:16px;">';
			$temp = json_decode(get_post_meta($store->ID,'themify_storelocator_timing',true),true);
			foreach($temp as $data){
				$location['content'] .= $data['lable'].': '.$data['open'];
				if(!empty($data['close'])){
					$location['content'] .= ' - '.$data['close'];
				}
				$location['content'] .= '<br>';
			}
			$location['content'] .= '</div></div>';
			$locations[] = $location;
		}
		echo json_encode($locations);
	}

	function single_post_template($template){
		global $post;
		if($post->post_type === $this->post_type){
			$template = $this->get_view_path("single-loop.php","template");
		}
		return $template;
	}

	private function get_stores($category=null) {
        $args = array(
			'post_type' => $this->post_type,
			'post_per_page' => -1,
			'post_status' => 'publish',
			'nopaging'	=>	true
		);
        if(!empty($category)){
            $args['tax_query'] = array(
                array (
                    'taxonomy' => 'store_category',
                    'field' => 'slug',
                    'terms' => $category,
                )
            );
        }
		$the_query = new WP_Query();
		$posts = $the_query->query( $args );
		return $posts;
	}
	
	public function get_view_path( $name, $template = false ) {
		if( locate_template( 'themify-store-locator/' . $name ) ) {
			return locate_template( 'themify-store-locator/' . $name );
		} elseif($template && file_exists( THEMIFY_STORE_LOCATOR_DIR . $template .'/' . $name )){
			return THEMIFY_STORE_LOCATOR_DIR . $template .'/' . $name;
		} elseif( file_exists( THEMIFY_STORE_LOCATOR_DIR . 'views/' . $name ) ) {
			return THEMIFY_STORE_LOCATOR_DIR . 'views/' . $name;
		}

		return false;
	}

	function shortcode_defaults($for){
		$default = array(
			'map' => array(
				'category' => '',
				'width' => '100%',
				'height' => '500px',
				'map_controls' => 'yes',
				'scrollwheel' => 'no',
				'mobile_draggable' => 'no'
			),
			'store' => array(
				'category' => '',
				'posts_per_page' => '',
				'layout' => 'fullwidth',
				'hours' => 'yes',
				'contact' => 'yes',
				'description' => 'yes',
				'feature_image' => 'yes',
				'unlink_title' => 'no',
				'pagination' => 'no',
				'order' => '',
				'orderby' => ''
			)
		);
		return ($default[$for])? $default[$for] : array();
	}

	function add_query_vars_filter( $vars ){
	  $vars[] = 'tsl_paged';
	  return $vars;
	}

	function shortcode_store( $atts, $content = null ) {
		
		extract(shortcode_atts( $this->shortcode_defaults('store'), $atts ));
		
		$layout_types = array('fullwidth','grid2', 'grid3', 'grid4');	// allowed layouts
		
		if(empty($posts_per_page) || !is_numeric($posts_per_page)){
			$posts_per_page = get_option( 'posts_per_page' );
		}
		if(!in_array($layout,$layout_types,true)){
			$layout = 'fullwidth';
		}
		
		$args = array(
			'post_type' => $this->post_type,
			'post_status' => 'publish',
			'posts_per_page' => $posts_per_page
		);
		if(!empty($category)){
			$args['tax_query'] = array(
				array (
					'taxonomy' => 'store_category',
					'field' => 'slug',
					'terms' => sanitize_text_field($category),
				)
			);
		}
		if(!empty($order)){
			$args['order'] = sanitize_text_field($order);
		}
		if(!empty($orderby)){
			$args['orderby'] = sanitize_text_field($orderby);
		}
		if($pagination === 'yes'){
			if(is_front_page()){
				$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
			}else
				$paged = ( get_query_var('tsl_paged') ) ? get_query_var('tsl_paged') : 1;
			$args['paged'] = $paged;
			$args['page'] = $paged;
		}
		$the_query = new WP_Query();
		$stores = $the_query->query( $args );
		$is_single = false;
		$loop_tmpl = $this->get_view_path('archive-loop.php','template');
		$this->repeating = true;
		ob_start();
			include $loop_tmpl;
		$html = ob_get_contents();
		ob_end_clean();
		$this->repeating = false;
		
		return $html;
	}

	function shortcode_map( $atts, $content = null ) {
		$atts = shortcode_atts( $this->shortcode_defaults('map'), $atts );
		$atts['single_post_map'] = false;
		ob_start();
		?>
		<div class="themify_SL_map_container themify_SL_scripts" style="display:none;" data-settings="<?php echo base64_encode(json_encode($atts))?>">
			<span class="wait_sl"><?php _e( 'Wait Loading Map...', 'themify-store-locator' ); ?></span>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}

	public function activation_redirect() {
		if( get_option( 'themify_store_locator_activation_redirect', false ) ) {
			$this->register_post_type();
			flush_rewrite_rules();
			delete_option( 'themify_store_locator_activation_redirect' );
			wp_redirect( admin_url( 'edit.php?post_type=themify_storelocator&page=themify-sl-about' ) );
		}
	}
	
	private static function themify_enque($url){
	    static $is=null;
	    if($is===null){
		$is=  function_exists('themify_enque');
	    }
	    if($is===true){
		return themify_enque($url);
	    }
	    return $url;
	}
	
	private static function themify_enque_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all' ){
	    static $is=null;
	    if($is===null){
		$is=  function_exists('themify_is_themify_theme') && themify_is_themify_theme();
	    }
	    if($is===true){
		themify_enque_style($handle,$src,$deps,$ver,$media);
	    }
	    else{
		wp_enqueue_style($handle,$src,$deps,$ver,$media);
	    }
	}

    public function exclude_sidebar_setting( $types ) {
        unset($types[$this->post_type]);
        $types[]=$this->post_type;
        return $types;
    }

	/**
	 * Returns the Google Maps api key
	 *
	 * @return string|null
	 */
	public static function get_api_key() {
		static $key = null;
		if ( $key === null ) {
			$key = get_option( 'themify_sl_map_api', '' );
		}

		return $key;
	}
}
