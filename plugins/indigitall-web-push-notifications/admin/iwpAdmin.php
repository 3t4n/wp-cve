<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	/*
	 * Indigitall Admin Class
	 */
	class iwpAdmin {
		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {}

		/**
		 * Inicia las configuraciones de la parte de administración
		 * @return void
		 */
		public static function init() {
			define('IWP_ADMIN_URL', plugin_dir_url( __FILE__ ));
			define('IWP_ADMIN_PATH', plugin_dir_path( __FILE__ ));
			define('IWP_PUBLIC_URL', IWP_PLUGIN_URL . "public/");
			define('IWP_PUBLIC_PATH', IWP_PLUGIN_PATH . "public/");

			// Add menu to WordPress Admin
			add_action('admin_menu', array(__CLASS__, 'add_admin_page'));

			self::iwpPrepareWidget();

			add_action('admin_enqueue_scripts', array(__CLASS__, 'load_iwp_media_files'));

			add_filter( 'run_wptexturize', '__return_false' );
		}

		public static function load_iwp_media_files() {
			wp_enqueue_media();
		}

		/**
		 * Añade en el menú principal de WordPress el link a nuestro plugin
		 * @return void
		 */
		public static function add_admin_page() {
			add_menu_page('iurny by indigitall',
				'iurny by indigitall',
				'manage_options',
				'indigitall-push',
				array(__CLASS__, 'admin_menu'),
				IWP_ADMIN_URL . 'images/menu_iwp.svg'
			);
		}

		/**
		 * Se controla qué página del plugin se debe mostrar
		 * @return void
		 */
		public static function admin_menu() {
			require_once IWP_ADMIN_PATH . 'controllers/iwpAdminController.php';
			$adminController = new iwpAdminController();
			$adminController->render();
		}

		public static function iwpPrepareWidget() {
			add_action('add_meta_boxes', static function() {
				if (get_option("iwp_application_id")) {
					add_meta_box(
						'iwp_meta_box_push',
						__('iurny by indigitall', 'iwp-text-domain'),
						array(__CLASS__, 'iwpWidgetRender'),
						'post',
						'side',
						'high'
						//Este array es pasado al callback, pero no usaremos
						//array('key' => 'value')
					);
				}
			});
			require_once IWP_ADMIN_PATH . 'controllers/iwpWidgetController.php';
			add_action('save_post_post', array('iwpWidgetController', 'sendPush'), 10, 3);
		}
		public static function iwpWidgetRender($post, $args = array()) {
			// Los argumentos pasados se ubican dentro de args['args'], pero no las usaremos
			require_once IWP_ADMIN_PATH . 'controllers/iwpWidgetController.php';
			$widgetController = new iwpWidgetController();
			$widgetController->renderHtml($post);
		}
	}
