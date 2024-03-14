<?php

class Themify_Popup {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'i18n' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'loaded' ) );
		if ( is_admin() ) {
			add_filter( 'themify_exclude_cpt_post_options', array( __CLASS__, 'exclude_post_options' ) );
			add_filter( 'themify_do_metaboxes', array( __CLASS__, 'meta_box' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue' ) );
		} else {
			add_filter( 'wp_nav_menu_objects', array( __CLASS__, 'wp_nav_menu_objects' ) );
			add_filter( 'template_include', array( __CLASS__, 'template_include' ), 100 );
			add_action( 'template_redirect', array( __CLASS__, 'hooks' ) );
			add_shortcode( 'tf_popup', array( __CLASS__, 'shortcode' ) );
		}

		add_action('themify_builder_active_enqueue',array( __CLASS__, 'themify_builder_layouts' ));
	}

	public static function hooks() {
		if( ! is_singular( 'themify_popup' ) ) {
			add_action( 'wp_footer', array( __CLASS__, 'render' ), 1 );
		} 
		elseif( ! current_user_can( 'manage_options' ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
	
	public static function loaded(){
		if(is_user_logged_in()){
			add_filter('wp_editor_settings',array(__CLASS__,'tinymce_init'));
		}
	}
	
	public static function tinymce_init($settings){
		remove_filter('wp_editor_settings',array(__CLASS__,'tinymce_init'));
		include THEMIFY_POPUP_DIR . 'includes/tinymce.php';
		return $settings;
	}

	public static function i18n() {
		load_plugin_textdomain( 'themify-popup', false, plugin_basename( THEMIFY_POPUP_DIR ) . '/languages' );
	}

	public static function register_post_type() {
		register_post_type( 'themify_popup', array(
			'labels'=> array(
				'name'               => _x( 'Popups', 'post type general name', 'themify-popup' ),
				'singular_name'      => _x( 'Popup', 'post type singular name', 'themify-popup' ),
				'menu_name'          => _x( 'Themify Popups', 'admin menu', 'themify-popup' ),
				'name_admin_bar'     => _x( 'Popup', 'add new on admin bar', 'themify-popup' ),
				'add_new'            => _x( 'Add New', 'book', 'themify-popup' ),
				'add_new_item'       => __( 'Add New Popup', 'themify-popup' ),
				'new_item'           => __( 'New Popup', 'themify-popup' ),
				'edit_item'          => __( 'Edit Popup', 'themify-popup' ),
				'all_items'          => __( 'Manage Popups', 'themify-popup' )
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'themify_popup' ),
			'capability_type'    => 'post',
			'menu_position'      => 80, /* below Settings */
			'has_archive'        => false,
			'supports'           => array( 'title', 'editor' )
		) );
	}

	public static function meta_box( $panels ) {
		$options = include self::get_view_path( 'config.php' );
		$panels[] = array(
			'name' => __( 'Popup Settings', 'themify' ),
			'id' => 'themify-popup',
			'options' => $options,
			'pages' => 'themify_popup',
			'default_active' => true,
		);
		$panels[] = array(
			'name' => __( 'Custom CSS', 'themify-popup' ),
			'id' => 'themify-popup-css',
			'options' => array(
				array(
					'name' => 'custom_css',
					'title' => __( 'Custom CSS', 'themify-popup' ),
					'type' => 'textarea',
					'size' => 55,
					'rows' => 25,
					'description' => __( 'You can use <code>%POPUP%</code> to reference this popup.', 'themify-popup' ),
				),
			),
			'pages' => 'themify_popup'
		);

		return $panels;
	}


	public static function admin_enqueue() {
            if(  get_current_screen()->post_type === 'themify_popup' ){
                wp_enqueue_script( 'themify-popup', themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/admin.js'), array( 'jquery' ), THEMIFY_POPUP_VERSION, true );
            }
	}

	protected static function get_popups() {
		$datenow = date_i18n('Y-m-d H:i:s');
		$args = array(
			'post_type' => 'themify_popup',
			'post_status' => 'publish',
			'posts_per_page' => -1,
                        'no_found_rows'=>true,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'popup_start_at',
					'value' => $datenow,
					'compare' => '<=',
					'type' => 'datetime'
				),
				array(
					'key' => 'popup_end_at',
					'value' => $datenow,
					'type' => 'datetime',
					'compare' => '>='
				)
			)
		);
		if (class_exists('SitePress')) {
			/*
			* For some unknown reason WPML 4.0.2 will not render posts for other languages if suppress_filters or posts_per_page value is not a string type.
			*/
			$args['suppress_filters'] = '0';
		}
		$the_query = new WP_Query();
		return $the_query->query( apply_filters( 'themify_popup_query_args', $args ) );
	}

	protected static function get_view_path( $name ) {
		if( locate_template( 'themify-popup/' . $name ) ) {
			return locate_template( 'themify-popup/' . $name );
		} 
                elseif( is_file( THEMIFY_POPUP_DIR . 'views/' . $name ) ) {
			return THEMIFY_POPUP_DIR . 'views/' . $name;
		}

		return false;
	}

	protected static function load_view( $name, $data = array() ) {
		extract( $data );
		if( $view = self::get_view_path( $name ) ) {
			ob_start();
			include( $view );
			return ob_get_clean();
		}

		return '';
	}

	public static function render() {

		/* disable popups on these post types and when Themify Builder editor is on */
		if (is_singular(array('tbp_template', 'tbuilder_layout_part', 'tglobal_style', ))) {
			return;
		}

		$popups = self::get_popups();
		/* add the page view counter cookie? */
		$page_view = false;

		foreach ( $popups as $k=>$popup ) {
			if ( ! self::is_popup_visible( $popup->ID ) ) {
				unset($popups[$k]);
			}
			if( $page_view === false ) {
				$page_view = get_post_meta( $popup->ID, 'popup_page_view', true ) ? true : false;
			}
		}

		self::register_assets( $page_view );

		if ( empty( $popups ) ) {
			/* there's no popup to display, but load the popup scripts.js file anyway, need it to count page views */
			if ( $page_view ) {
				wp_enqueue_script( 'themify-popup' );
			}
			return;
		}

		self::enqueue();

		do_action( 'themify_popup_before_render' );

		echo self::load_view( 'render.php', array(
			'popups' => $popups
		) );

		do_action( 'themify_popup_after_render' );
	}

	protected static function register_assets( $count_views = false ) {
		wp_register_script( 'themify-popup', themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/scripts.js'), array( 'jquery' ), THEMIFY_POPUP_VERSION, true );
		wp_localize_script( 'themify-popup', 'themifyPopup', array(
			'assets' => THEMIFY_POPUP_URI . 'assets',
			'count_views' => $count_views
		) );

		wp_register_style( 'themify-builder-animate', THEMIFY_POPUP_URI . 'assets/animate.min.css',null,'3.6.2' );
		wp_register_style( 'magnific', themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/lightbox.css'),null,THEMIFY_POPUP_VERSION );
		wp_register_style( 'themify-popup', themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/styles.css'), array( 'themify-builder-animate', 'magnific' ), THEMIFY_POPUP_VERSION );
	}

	protected static function enqueue() {
		wp_enqueue_script( 'themify-popup' );
		wp_enqueue_style( 'themify-popup' );
	}

	/**
	 * Displays the contents of the popup
	 *
	 * Themify Builder content is manually added, this is to avoid
	 * issues with WooCommerce.
	 *
	 * @return void
	 */
	protected static function the_content() {
		global $ThemifyBuilder;

		if ( isset( $ThemifyBuilder ) ) {
			add_filter( 'themify_builder_display', '__return_false' ); // disable default Builder output
			$isLoop=$ThemifyBuilder->in_the_loop===true;
			$ThemifyBuilder->in_the_loop = true;

			/* disable Row Width options: rows inside the popup cannot be displayed as fullwidth */
			add_filter( 'themify_builder_row_classes', array( __CLASS__, 'themify_builder_row_classes' ), 10, 3 );
		}

		/**
		 * do the_content() but return the result instead */
		$content = get_the_content();
		/** This filter is documented in wp-includes/post-template.php */
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		if ( isset( $ThemifyBuilder ) ) {
			remove_filter( 'themify_builder_display', '__return_false' );

		
			$content = $ThemifyBuilder->get_builder_output( get_the_id(), $content );
			$ThemifyBuilder->in_the_loop = $isLoop;
			remove_filter( 'themify_builder_row_classes', array( __CLASS__, 'themify_builder_row_classes' ), 10, 3 );
		}
		if(function_exists('themify_make_lazy')){ // lazy load images inside popups
			$content=themify_make_lazy($content,false);
		}

		echo $content;
	}

	public static function themify_builder_row_classes( $row_classes, $row, $builder_id ) {
		return str_replace( array( 'fullwidth_row_container', 'fullwidth' ), '', $row_classes );
	}

	protected static function get_element_attributes( $props ) {
		$out = '';
		foreach( $props as $atts => $val ) { 
			if( ! in_array( $atts, array( 'id', 'class', 'style' ), true ) && substr( $atts, 0, 5 ) !== 'data-' ) {
				$atts = 'data-' . $atts;
			}
			$out .= ' '. $atts . '="' . esc_attr( $val ) . '"'; 
		}
		return $out;
	}

	/**
	 * Fix URLs in menu items pointing to an inline popup
	 */
	public static function wp_nav_menu_objects( $items ) {
		foreach( $items as $item ) {
			if( $item->type === 'post_type' && $item->object === 'themify_popup' ) {
				$item->url = '#themify-popup-' . $item->object_id;
				$item->classes[] = 'tf-popup';
			}
		}

		return $items;
	}

	public static function shortcode( $atts, $content = null ) {
		if( is_singular( 'themify_popup' ) ) {
			return;
		}
		$atts= shortcode_atts( array(
			'color' => '',
			'size' 	=> '',
			'style'	=> '',
			'link' 	=> 0,
			'target'=> '',
			'text'	=> ''
		), $atts, 'tf_popup' );

		// WPML compatibility
		$atts['link'] = apply_filters( 'wpml_object_id', $atts['link'], 'post', true );
		if( ! $post = get_post( $atts['link'] ) ) {
			return;
		}
                $cl='';
                if($atts['style']!==''){
                    $cl=$atts['style'];
                }
                if($atts['size']!==''){
                    $cl.=' '.$atts['size'];
                }
                if($cl!==''){
                    $cl=esc_attr(trim($cl));
                    $cl=' '.$cl;
                }
		$html = '<a href="#themify-popup-' . $atts['link'] . '" class="tf_popup'. $cl . '"';
                unset($cl);
		if ( $atts['color']!=='' || $atts['text']!=='' ) {
                    if ( $atts['color']!=='' ) {
                        $atts['color'] = "background-color:{$atts['color']};";
                    }
                    if ( $atts['text']!=='' ) {
                        $atts['text'] = "color:{$atts['text']};";	
                    }
                    $html.=' style="'.esc_attr( $atts['color'].$atts['text'] ).'"';
		}
		if ( $atts['target']!=='' ) {
			$html.=' target="'.esc_attr( $atts['target'] ).'"';
		}
                unset($atts);
		$html.= '>' . do_shortcode( $content ) . '</a>';

		return $html;
	}

	/**
	 * Use custom template file on popup single pages
	 *
	 * @since 1.0
	 */
	public static function template_include( $template ) {
		if( is_singular( 'themify_popup' ) ) {
			$template = self::get_view_path( 'single-popup.php' );
		}

		return $template;
	}

	/**
	 * Checks whether a popup should be displayed or not
	 *
	 * @since 1.0
	 * @return bool
	 */
	protected static function is_popup_visible( $id ) {
		if ( themify_popup_get( 'popup_show_on_toggle', 'all-pages',$id ) === 'specific-pages' && themify_popup_check( 'popup_show',$id ) && ! themify_verify_assignments( themify_popup_get( 'popup_show',null,$id ) ) ) {
			return false;
		}
		$showTo = themify_popup_get( 'popup_show_to',null,$id );
		if ( ( $showTo === 'guest' && is_user_logged_in() ) || ( $showTo === 'user' && ! is_user_logged_in() ) ) {
			return false;
		}

		if ( themify_popup_get( 'popup_trigger', 'timedelay',$id ) === 'manual' ) {
			return true;
		}

		// has user seen this popup before?
		/**
		 * Migration routine: previsouly used "show_once" checkbox is converted to "limit_count" (number).
		 */
		if ( themify_popup_check( 'popup_show_once',$id ) ) {
			delete_post_meta( $id, 'popup_show_once' );
			add_post_meta( $id, 'popup_limit_count', 1 );
		}
		if ( isset( $_COOKIE["themify-popup-{$id}"] ) && themify_popup_check( 'popup_limit_count',$id ) &&  $_COOKIE["themify-popup-{$id}"] >= themify_popup_get( 'popup_limit_count',null,$id ) ) {
			return false;
		}

		// check if popup has a page view limit
		if ( ($view_count = themify_popup_get( 'popup_page_view', 0,$id )) && ( !isset( $_COOKIE['themify_popup_page_view'] ) || $_COOKIE['themify_popup_page_view'] <$view_count )) {
                    return false;
		}

		return true;
	}

	/**
	 * Add sample layouts bundled with Popup plugin to Themify Builder
	 *
	 * @since 1.0.0
	 */
	public static function themify_builder_layouts() {
		$handler='themify-popup-builder-active';
		$arr=include THEMIFY_POPUP_DIR . 'sample/layouts.php';
		themify_enque_script($handler, themify_metabox_enque(THEMIFY_POPUP_URI . 'assets/builder-active.js'),THEMIFY_POPUP_VERSION, array('themify-builder-app-js'), true );
		wp_localize_script($handler, 'themifyPopupBuilder',  array(
			'data'=>$arr,
			'title'=>__( 'Themify Popup', 'themify-popup' )
		));
	}

	public static function exclude_post_options($types){
	    $types[] = 'themify_popup';
	    return $types;
    }
}

/**
 * Check if option is set for the current popup in the loop
 *
 * @since 1.0
 */
function themify_popup_check( $var,$id=null ) {
        $res=themify_popup_get($var,null,$id);
        return !empty($res);
}

/**
 * Get an option for the current popup in the loop
 *
 * @since 1.0
 */
function themify_popup_get( $var, $default = null,$id=null ) {
        if($id===null){
            global $post;
            if(is_object( $post )){
                $id=$post->ID;
            }
        }
	$postmeta = $id!==null ?get_post_meta( $id, $var, true ):'';
	return $postmeta !== '' ?$postmeta:$default;
}

/**
 * Return the custom CSS codes for current popup (in the loop)
 *
 * @return string
 */
function themify_popup_get_custom_css() {
	return str_replace( '%POPUP%', '#themify-popup-' . get_the_id(), themify_popup_get( 'custom_css', '' ) );
}
