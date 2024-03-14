<?php
/**
 * Dashboard manager
 *
 * Package: Skt_Addons_Elementor
 * @since 1.0
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class Dashboard {

    const PAGE_SLUG = 'skt-addons';

    const LICENSE_PAGE_SLUG = 'skt-addons-license';

    const WIDGETS_NONCE = 'skt_addons_elementor_save_dashboard';

    static $menu_slug = '';

    public static $catwise_widget_map = [];
    public static $catwise_free_widget_map = [];

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ], 21 );
        add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], 99 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_' . self::WIDGETS_NONCE, [ __CLASS__, 'save_data' ] );

        add_filter( 'plugin_action_links_' . plugin_basename( SKT_ADDONS_ELEMENTOR__FILE__ ), [ __CLASS__, 'add_action_links' ] );

        add_action( 'sktaddonselementor_save_dashboard_data', [ __CLASS__, 'save_widgets_data' ], 1);
        add_action( 'sktaddonselementor_save_dashboard_data', [ __CLASS__, 'save_features_data' ] );
        add_action( 'sktaddonselementor_save_dashboard_data', [ __CLASS__, 'save_credentials_data' ] );
        add_action( 'sktaddonselementor_save_dashboard_data', [ __CLASS__, 'disable_unused_widget' ], 10);

        add_action( 'in_admin_header', [ __CLASS__, 'remove_all_notices' ], PHP_INT_MAX );
    }

    public static function is_page() {
        return ( isset( $_GET['page'] ) && ( $_GET['page'] === self::PAGE_SLUG || $_GET['page'] === self::LICENSE_PAGE_SLUG ) );
    }

    public static function remove_all_notices() {
        if ( self::is_page() ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }

    public static function add_action_links( $links ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $links;
        }

        $links = array_merge( [
            sprintf( '<a href="%s">%s</a>',
                skt_addons_elementor_get_dashboard_link(),
                esc_html__( 'Settings', 'skt-addons-elementor' )
            )
        ], $links );

        return $links;
    }

    public static function save_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::WIDGETS_NONCE, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST['data'] ) ? $_POST['data'] : '';
        $data = [];
        parse_str( $posted_data, $data );

        do_action( 'sktaddonselementor_save_dashboard_data', $data );

        wp_send_json_success();
    }

    public static function save_widgets_data( $data ) {
        $widgets = ! empty( $data['widgets'] ) ? $data['widgets'] : [];
        $inactive_widgets = array_values( array_diff( array_keys( self::get_real_widgets_map() ), $widgets ) );
        Widgets_Manager::save_inactive_widgets( $inactive_widgets );
    }

    public static function save_features_data( $data ) {
        $features = ! empty( $data['features'] ) ? $data['features'] : [];

        /* Check whether Pro is available and allow to disable pro features */
        $widgets_map = self::get_real_features_map();
        if ( skt_addons_elementor_has_pro() ) {
            $widgets_map = array_merge( $widgets_map, Extensions_Manager::get_pro_features_map() );
        }

        $inactive_features = array_values( array_diff( array_keys( $widgets_map ), $features ) );

        Extensions_Manager::save_inactive_features( $inactive_features );
    }

    public static function save_credentials_data( $data ) {
        $credentials = ! empty( $data['credentials'] ) ? $data['credentials'] : [];
        Credentials_Manager::save_credentials( $credentials );
    }

    public static function disable_unused_widget( $data ) {
        $disable_unused_widgets = ( ( ! empty( $data['disable-unused-widgets'] ) ) && ( 'true' == $data['disable-unused-widgets'] ) ) ? true : false;

		if( $disable_unused_widgets ){
			$inactive_widgets = \Skt_Addons_Elementor\Elementor\Widgets_Manager::get_inactive_widgets();
			$unuse_widget = self::get_un_usage();
			$disable = array_unique(array_merge( $inactive_widgets, $unuse_widget ));
            Widgets_Manager::save_inactive_widgets( $disable );
		}
    }

    public static function enqueue_scripts( $hook ) {
        // css for dashboard widget
		$screen = \get_current_screen();
		if($screen->id == 'dashboard') {
			wp_enqueue_style(
				'skt-addons-elementor-wp-dashboard',
				SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/css/wp-dashboard.min.css',
				null,
				SKT_ADDONS_ELEMENTOR_VERSION
			);
		}

        if ( self::$menu_slug !== $hook || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_enqueue_style(
            'skt-icons',
            SKT_ADDONS_ELEMENTOR_ASSETS . 'fonts/style.min.css',
            null,
            SKT_ADDONS_ELEMENTOR_VERSION
        );

        wp_enqueue_style(
            'google-nunito-font',
            SKT_ADDONS_ELEMENTOR_ASSETS . 'fonts/nunito/stylesheet.css',
            null,
            SKT_ADDONS_ELEMENTOR_VERSION
        );

        wp_enqueue_style(
            'skt-addons-elementor-dashboard',
            SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/css/dashboard.min.css',
            null,
            SKT_ADDONS_ELEMENTOR_VERSION
        );

        wp_enqueue_script(
            'skt-addons-elementor-dashboard',
            SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/js/dashboard.min.js',
            [ 'jquery' ],
            SKT_ADDONS_ELEMENTOR_VERSION,
            true
        );

        wp_localize_script(
            'skt-addons-elementor-dashboard',
            'SktDashboard',
            [
                'nonce' => wp_create_nonce( self::WIDGETS_NONCE ),
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'action' => self::WIDGETS_NONCE,
                'saveChangesLabel' => esc_html__( 'Save Changes', 'skt-addons-elementor' ),
                'savedLabel' => esc_html__( 'Changes Saved', 'skt-addons-elementor' ),
            ]
        );
    }

    private static function get_real_widgets_map() {
        $widgets_map = Widgets_Manager::get_widgets_map();
        unset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] );
        return $widgets_map;
    }

    public static function get_widgets() {
        $widgets_map = self::get_real_widgets_map();

        if ( ! skt_addons_elementor_has_pro() ) {
            $widgets_map = array_merge( $widgets_map, Widgets_Manager::get_pro_widget_map() );
        }
        elseif( skt_addons_elementor_has_pro() && version_compare( SKT_ADDONS_ELEMENTOR_PRO_VERSION, '2.1.0', '<=' ) ) {
			$widgets_map = array_merge( $widgets_map, Widgets_Manager::get_pro_widget_map() );
		}

        uksort( $widgets_map, [ __CLASS__, 'sort_widgets' ] );
        return $widgets_map;
    }

	public static function get_widget_map_catwise() {
		$widgets = self::get_widgets();
		array_walk($widgets, function($item, $key){
			$item["cat"] = isset($item["cat"]) ? $item["cat"] : 'general'; // this code will be remove after next 2 release
		    self::$catwise_widget_map[$item["cat"]][$key] = [
		        'demo' => isset($item["demo"])? $item["demo"]: '',
		        'title' => $item["title"],
		        'icon' => $item["icon"],
		        'is_pro' => isset($item["is_pro"])? $item["is_pro"]: false,
		    	];
			}
		);

		return self::$catwise_widget_map;
	}

    public static function get_free_widget_map_catwise() {
		$widgets = self::get_real_widgets_map();
		array_walk($widgets, function($item, $key){
		    self::$catwise_free_widget_map[$item["cat"]][$key] = [
		        'demo' => isset($item["demo"])? $item["demo"]: '',
		        'title' => $item["title"],
		        'icon' => $item["icon"],
		        'is_pro' => isset($item["is_pro"])? $item["is_pro"]: false,
		    ];
		});
		return self::$catwise_free_widget_map;
	}

    private static function get_real_features_map() {
        $widgets_map = Extensions_Manager::get_features_map();
        return $widgets_map;
    }

    public static function get_features() {
        $widgets_map = self::get_real_features_map();

        $widgets_map = array_merge( $widgets_map, Extensions_Manager::get_pro_features_map() );

        uksort( $widgets_map, [ __CLASS__, 'sort_widgets' ] );
        return $widgets_map;
    }

    public static function get_credentials() {

        $credentail_map = Credentials_Manager::get_credentials_map();

        $credentail_map = array_merge( $credentail_map, Credentials_Manager::get_pro_credentials_map() );

        return $credentail_map;
    }

    public static function sort_widgets( $k1, $k2 ) {
        return strcasecmp( $k1, $k2 );
    }

    public static function add_menu() {
		self::$menu_slug = add_menu_page(
			__( 'SKT Addons for Elementor', 'skt-addons-elementor' ),
			__( 'SKT Addons', 'skt-addons-elementor' ),
			'manage_options',
			self::PAGE_SLUG,
			[ __CLASS__, 'render_main' ],
			SKT_ADDONS_ELEMENTOR_ASSETS.'imgs/left-logo.png',
			'75'
		);

        $tabs = self::get_tabs();
        if ( is_array( $tabs ) ) {
            foreach ( $tabs as $key => $data ) {
                if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                    continue;
                }

                add_submenu_page(
                    self::PAGE_SLUG,
                    sprintf( __( '%s - SKT Addons for Elementor', 'skt-addons-elementor' ), $data['title'] ),
                    $data['title'],
                    'manage_options',
                    self::PAGE_SLUG . '#' . $key,
                    [ __CLASS__, 'render_main' ]
                );
            }
        }
    }

    public static function update_menu_items() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        global $submenu;
        $menu = $submenu[ self::PAGE_SLUG ];
        array_shift( $menu );
        $submenu[ self::PAGE_SLUG ] = $menu;
    }

	public static function get_raw_usage( $format = 'raw' ) {
		/** @var Module $module */
		$module = \Elementor\Modules\Usage\Module::instance();
		$usage = PHP_EOL;
		$widgets_list = [];
		if( skt_addons_elementor_has_pro() ){
			$all_widgets = self::get_widgets();
		}else{
			$all_widgets = Widgets_Manager::get_local_widgets_map();
		}
        if(is_array($module->get_formatted_usage( $format ))||is_object($module->get_formatted_usage( $format ))){
            foreach ( $module->get_formatted_usage( $format ) as $doc_type => $data ) {
                $usage .= "\t{$data['title']} : " . $data['count'] . PHP_EOL;

                if(is_array($data['elements'])||is_object($data['elements'])){
                    foreach ( $data['elements'] as $element => $count ) {
                        $usage .= "\t\t{$element} : {$count}" . PHP_EOL;
                        $is_skt_addons_elementor_widget = strpos( $element , "skt-") !== false;
                        $widget_key = str_replace('skt-','',$element);

                        if( $is_skt_addons_elementor_widget && array_key_exists( $widget_key, $all_widgets ) ) {

                            $widgets_list[ $widget_key ] = $count;
                        }
                    }
                }
            }
        }
		return $widgets_list;
	}

	public static function get_un_usage() {
		if( skt_addons_elementor_has_pro() ){
			$all_widgets = self::get_widgets();
		}else{
			$all_widgets = Widgets_Manager::get_local_widgets_map();
		}
		$used_widgets = self::get_raw_usage();
		$get_diff = array_diff( array_keys( $all_widgets ), array_keys( $used_widgets ) );
		// return $get_diff;
		return array_values($get_diff);
	}

    public static function get_tabs() {
        $tabs = [
            'home' => [
                'title' => esc_html__( 'Home', 'skt-addons-elementor' ),
                'renderer' => [ __CLASS__, 'render_home' ],
            ],
            'widgets' => [
                'title' => esc_html__( 'Widgets', 'skt-addons-elementor' ),
                'renderer' => [ __CLASS__, 'render_widgets' ],
            ],
            'features' => [
                'title' => esc_html__( 'Features', 'skt-addons-elementor' ),
                'renderer' => [ __CLASS__, 'render_features' ],
            ],
            'credentials' => [
                'title' => esc_html__( 'Credentials', 'skt-addons-elementor' ),
                'renderer' => [ __CLASS__, 'render_credentials' ],
            ],
            'analytics' => [
                'title' => esc_html__( 'Analytics', 'skt-addons-elementor' ),
                'renderer' => [ __CLASS__, 'render_analytics' ],
            ],
        ];

        return apply_filters( 'sktaddonselementor_dashboard_get_tabs', $tabs );
    }

    private static function load_template( $template ) {
        $file = SKT_ADDONS_ELEMENTOR_DIR_PATH . 'templates/admin/dashboard-' . $template . '.php';
        if ( is_readable( $file ) ) {
            include( $file );
        }
    }

    public static function render_main() {
        self::load_template( 'main' );
    }

    public static function render_home() {
        self::load_template( 'home' );
    }

    public static function render_widgets() {
        self::load_template( 'widgets' );
    }

    public static function render_features() {
        self::load_template( 'features' );
    }

    public static function render_credentials() {
        self::load_template( 'credentials' );
    }

    public static function render_analytics() {
        self::load_template( 'analytics' );
    }

    public static function render_pro() {
        self::load_template( 'pro' );
    }

}

Dashboard::init();