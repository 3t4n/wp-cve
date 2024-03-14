<?php
/** 
 *	WP Admin page
 */

class YESNO_Admin_Page {
	/**
	 *
	 */
	public static function load() {
		add_action('init', array('YESNO_Admin_Page', 'init') );
	}

	/**
	 *	Initialize
	 */
	public static function init() {
		add_action('admin_menu', array('YESNO_Admin_Page', 'add_menu') );
		add_action('admin_enqueue_scripts', array('YESNO_Admin_Page', 'script') );
		add_filter( YESNO::PLUGIN_ID.'_admin_header', array('YESNO_Admin_Page', 'option_header') );
		add_action( YESNO::PLUGIN_ID.'_plugin_info', array('YESNO_Info', 'plugin_info') );
		add_action( YESNO::PLUGIN_ID.'_update_info', array('YESNO_Info', 'update_info') );

		add_filter( YESNO::PLUGIN_ID.'_allow_menu', array('YESNO_Admin_Page', 'allow_menu') );
	}

	/**
	 *	CSS for admin page
	 */
	public static function script() {
		global $yesno, $wp_scripts;

		if ( isset( $yesno->page['qs']['page'] ) && strpos( $yesno->page['qs']['page'], YESNO::PLUGIN_ID ) !== false ) {
			wp_enqueue_style( YESNO::PLUGIN_ID.'_admin_style', $yesno->mypluginurl.'css/style-admin.css');
			wp_enqueue_script( YESNO::PLUGIN_ID.'_admin_script', $yesno->mypluginurl.'js/yesno-admin.js', array('jquery'), YESNO::PLUGIN_VERSION, true );
		}
	}

	/** 
	 *	Plugin menu
	 */
	public static function add_menu() {
		$role = null;
		add_menu_page(
			__('Yes/No', 'yesno'),
			__('Yes/No', 'yesno'),
			apply_filters('yesno_allow_menu', $role ),
			YESNO::PLUGIN_ID.'-set',
			array('YESNO_Admin_Page', 'setting_page')
		);
		add_submenu_page(
			YESNO::PLUGIN_ID.'-set',
			__('Yes/No', 'yesno'),
			__('Question Set', 'yesno'),
			apply_filters('yesno_allow_menu', $role ),
			YESNO::PLUGIN_ID.'-set',
			array('YESNO_Admin_Page', 'setting_page')
		);
		add_submenu_page(
			YESNO::PLUGIN_ID.'-set',
			__('Yes/No', 'yesno').' '.__('Question', 'yesno'),
			__('Question', 'yesno'),
			apply_filters('yesno_allow_menu', $role ),
			YESNO::PLUGIN_ID.'-question',
			array('YESNO_Admin_Page', 'setting_page')
		);
	}

	/**
	 *	Which roles are allowed menus
	 */
	public static function allow_menu( $role ) {
		return 'administrator';
	}

	/**
	 *	Option header in Admin page
	 */
	public static function option_header( $option_header ) {
		extract( $option_header );		// $header, $current_page, $current_tab, $tabs
		foreach( $tabs as $tab => $label ) {
			$nav_class = array();
			$nav_class[] = sprintf('nav-%s-%s', $current_page, $tab );
			if ( $current_tab == $tab ) {
				$nav_class[] = 'nav-tab-active';
			}
			$class = ( ! empty( $nav_class ) ) ? ' '.implode(' ', $nav_class ) : '';
			$header .= sprintf('<a class="nav-tab%s" href="?page=%s&amp;tab=%s">%s</a>'."\n", $class, $current_page, $tab, $label );
		}
		$option_header['header'] = $header;
		return $option_header;
	}

	/**
	 *	Plugin setting page
	 */
	public static function setting_page( $args = null ) {
		global $wpdb, $yesno, $current_user;
		extract(
			wp_parse_args(
				$args,
				array(
					'title' => __('Yes/No', 'yesno'),
					'options_key' => YESNO::PLUGIN_ID,
				)
			)
		);
		$plugin_option = get_option( $options_key );
		$message = '';
		$options_group = '';

		/**
		 *	Current Page & Tab
		 */
		$current_page = YESNO::PLUGIN_ID.'-set';
		$current_tab = '';
		if ( isset( $yesno->page['qs'] ) ) {
			if ( isset( $yesno->page['qs']['page'] ) && strpos( $yesno->page['qs']['page'], YESNO::PLUGIN_ID ) !== false ) {
				$current_page = $yesno->page['qs']['page'];
				if ( isset( $yesno->page['qs']['tab'] ) ) {
					$current_tab = $yesno->page['qs']['tab'];
				}
			}
		}
		/**
		 *	Option Header (TAB control)
		 */
		$option_header = array(
			'header'       => '',
			'current_page' => $current_page,
			'current_tab'  => $current_tab,
			'tabs'         => array(),
		);

		/**
		 *	Form Action
		 */
		$param = array(
			'options_group'  => '',
			'message'        => '',
			'option_header'  => array(
				'header'       => '',
				'current_page' => $current_page,
				'current_tab'  => $current_tab,
				'tabs'         => array(),
			),
		);
		// Set
		if ( YESNO::PLUGIN_ID.'-set' == $current_page ) {
			$param = YESNO_Set::admin_action( $param );
			extract( $param );
		}
		// Question
		if ( YESNO::PLUGIN_ID.'-question' == $current_page ) {
			$param = YESNO_Question::admin_action( $param );
			extract( $param );
			$title = __('Question', 'yesno');
		}
		// Message
		if ( isset( $param['message'] ) && ! empty( $param['message'] ) ) {
			$message = $param['message'] = sprintf('<div class="alert alert-success">%s</div>', $param['message'] );
		}
		$options_key = YESNO::PLUGIN_ID;
		$plugin_option = get_option( $options_key );

		// URL
		$base_url = get_admin_url().basename( $_SERVER['SCRIPT_NAME'] );
		$url = add_query_arg( $yesno->page['qs'], $base_url );

		// 
		$option_header = apply_filters( YESNO::PLUGIN_ID.'_admin_header', $option_header );

?>
<div id="<?php echo esc_html( $options_key ); ?>" class="wrap">
<h2><?php echo esc_html( $title ); ?></h2>
<h3 class="nav-tab-wrapper">
<?php echo wp_kses_post( $option_header['header'] ); ?>
</h3>
<?php
		switch ( $current_page ) :
			/**
			 *	Set
			 */
			case YESNO::PLUGIN_ID.'-set' :  
				YESNO_Set::admin_page( $param );
				break;

			/**
			 *	Question
			 */
			case YESNO::PLUGIN_ID.'-question' :  
				YESNO_Question::admin_page( $param );
				break;

		endswitch;
?>
</div><!-- .wrap -->
<?php
	}
}
?>
