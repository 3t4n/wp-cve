<?php
namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vimeotheque PRO autoloader.
 * @ignore
 * @since 2.0
 */
class Autoload {

	/**
	 * Classes map.
	 *
	 * Maps Vimeotheque classes to file names.
	 *
	 * @since 1.6.0
	 * @access private
	 * @static
	 *
	 * @var array Classes used by Vimeotheque.
	 */
	private static $classes_map;

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	/**
	 * @return array
	 */
	public static function get_classes_map() {
		if ( ! self::$classes_map ) {
			self::init_classes_map();
		}

		return self::$classes_map;
	}

	private static function init_classes_map() {
		self::$classes_map = [
			// Admin
			/*
			'Admin\Pages\About_Page' => 'includes/admin/libs/pages/about-page.class.php',
			'Admin\Pages\Automatic_Import_Page' => 'includes/admin/libs/pages/automatic-import-page.class.php',
			'Admin\Pages\AI_Helper' => 'includes/admin/libs/pages/ai-helper.class.php',
			'Admin\Pages\Video_Import_Page' => 'includes/admin/libs/pages/video-import-page.class.php',
			'Admin\Pages\Post_Edit_Page' => 'includes/admin/libs/pages/post-edit-page.class.php',
			'Admin\Pages\Settings_Page' => 'includes/admin/libs/pages/settings-page.class.php',
			'Admin\Pages\List_Videos_Page' => 'includes/admin/libs/pages/list-videos-page.class.php',
			'Admin\Pages\Page_Init_Abstract' => 'includes/admin/libs/pages/page-init-abstract.class.php',
			'Admin\Pages\Page_Interface' => 'includes/admin/libs/pages/page-interface.class.php',

			'Admin\Helper_Admin' => 'includes/admin/libs/helper-admin.class.php',

			'Admin\Admin' => 'includes/admin/libs/admin.class.php',
			'Admin\Admin_Notices' => 'includes/admin/libs/admin-notice.class.php',
			'Admin\Ajax_Actions' => 'includes/admin/libs/ajax-actions.class.php',
			'Admin\Notice' => 'includes/admin/libs/notice.class.php',
			'Admin\Notice_Abstract' => 'includes/admin/libs/notice-abstract.class.php',
			'Admin\Notice_Interface' => 'includes/admin/libs/notice-interface.class.php',
			'Admin\Feed_Import_List_Table' => 'includes/admin/libs/feed-import-list-table.class.php',
			'Admin\Plugin_Notice' => 'includes/admin/libs/plugin-notice.class.php',
			'Admin\Posts_Import_Meta_Panels' => 'includes/admin/libs/post-edit-meta-panels.class.php',
			'Admin\Post_Type_Notice' => 'includes/admin/libs/post-type-notice.class.php',
			'Admin\Video_Import_List_Table' => 'includes/admin/libs/video-import-list-table.class.php',
			'Admin\Video_List_Table' => 'includes/admin/libs/video-list-table.class.php',
			'Admin\Vimeo_Oauth' => 'includes/admin/libs/vimeo-oauth.class.php',
			'Admin\WP_Customizer' => 'includes/admin/libs/wp-customizer.class.php',

			// Plugin
			/*
			'Plugin_Upgrade' => 'includes/libs/plugin-upgrade.class.php',
			'Plugin_Details' => 'includes/libs/plugin-upgrade.class.php',
			'Post_Type' => 'includes/libs/post-type.class.php',
			'Options' => 'includes/libs/options.class.php',
			'Options_Factory' => 'includes/libs/options-factory.class.php',
			'Rest_Api' => 'includes/libs/rest-api.class.php',
			'Video_Import' => 'includes/libs/video-import.class.php',
			'Latest_Videos_Widget' => 'includes/libs/video-widgets.class.php',
			'Video_Categories_Widget' => 'includes/libs/video-widgets.class.php',
			'Helper' => 'includes/libs/helper.class.php',
			'Front_End' => 'includes/libs/front-end.class.php',
			'Theme_Compatibility' => 'includes/libs/theme-compatibility.class.php',
			'Plugins_Compatibility' => 'includes/libs/plugins-compatibility.class.php',
			'Update_Api_Requests' => 'includes/libs/update-api-requests.class.php',
			'Video_Post' => 'includes/libs/video-post.class.php',
			'Feed\Feed' => 'includes/libs/feed/feed.class.php',
			'Feed\Feed_Base' => 'includes/libs/feed/feed-base.class.php',
			'Posts_Import' => 'includes/libs/posts-import.class.php',
			'Post_Settings' => 'includes/libs/post-settings.class.php',

			// Vimeo API
			'Vimeo_Api\Album_Resource' => 'includes/libs/vimeo-api/album-resource.class.php',
			'Vimeo_Api\Category_Resource' => 'includes/libs/vimeo-api/category-resource.class.php',
			'Vimeo_Api\Channel_Resource' => 'includes/libs/vimeo-api/channel-resource.class.php',
			'Vimeo_Api\Entry_Format' => 'includes/libs/vimeo-api/entry-format.class.php',
			'Vimeo_Api\Group_Resource' => 'includes/libs/vimeo-api/group-resource.class.php',
			'Vimeo_Api\Ondemand_Resource' => 'includes/libs/vimeo-api/ondemand-resource.class.php',
			'Vimeo_Api\Portfolio_Resource' => 'includes/libs/vimeo-api/portfolio-resource.class.php',
			'Vimeo_Api\Resource_Abstract' => 'includes/libs/vimeo-api/resource-abstract.class.php',
			'Vimeo_Api\Resource_Interface' => 'includes/libs/vimeo-api/resource-interface.class.php',
			'Vimeo_Api\Search_Resource' => 'includes/libs/vimeo-api/search-resource.class.php',
			'Vimeo_Api\Thumbnails_Resource' => 'includes/libs/vimeo-api/thumbnails-resource.class.php',
			'Vimeo_Api\User_Resource' => 'includes/libs/vimeo-api/user-resource.class.php',
			'Vimeo_Api\Video_Resource' => 'includes/libs/vimeo-api/video-resource.class.php',
			'Vimeo_Api\Vimeo' => 'includes/libs/vimeo-api/vimeo.class.php',
			'Vimeo_Api\Vimeo_Api_Query' => 'includes/libs/vimeo-api/vimeo-api-query.class.php',


			'Image_Import' => 'includes/libs/image-import.class.php',

			// Automatic Importer
			'Autoimport\Conditions' => 'includes/libs/autoimport/conditions.class.php',
			'Autoimport\Feed_Importer' => 'includes/libs/autoimport/feed-importer.class.php',
			'Autoimport\Importer' => 'includes/libs/autoimport/importer.class.php',
			'Autoimport\Messages' => 'includes/libs/autoimport/messages.class.php',
			'Autoimport\Queue' => 'includes/libs/autoimport/queue.class.php',

			// Timers
			'Timer\Timer' => 'includes/libs/timer/timer.class.php',
			'Timer\Timer_Interface' => 'includes/libs/timer/timer-interface.class.php',
			'Timer\Post_Timer' => 'includes/libs/timer/post-timer.class.php',

			// Utilities
			'Utility\Script_Timer' => 'includes/libs/utility/script-timer.class.php',
			'Utility\Script_Timer_Factory' => 'includes/libs/utility/script-timer-factory.class.php',
			*/
		];
	}

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since 1.6.0
	 * @access private
	 * @static
	 *
	 * @param string $relative_class_name Class name.
	 */
	private static function load_class( $relative_class_name ) {
		$classes_map = self::get_classes_map();

		if ( isset( $classes_map[ $relative_class_name ] ) ) {
			$filename = VIMEOTHEQUE_PATH . $classes_map[ $relative_class_name ];
		}else{
			$file = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower( $relative_class_name ) ) ) . '.class.php';
			$filename = VIMEOTHEQUE_PATH . 'includes/libs/' . $file;
		}

		if ( is_readable( $filename ) ) {
			require_once $filename;
		}
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exist and load it.
	 *
	 * @since 1.6.0
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
			return;
		}

		$relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $class );
		$final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

		if ( ! class_exists( $final_class_name ) ) {
			self::load_class( $relative_class_name );
		}
	}
}
