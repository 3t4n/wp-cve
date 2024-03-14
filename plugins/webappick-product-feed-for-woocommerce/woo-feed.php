<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://webappick.com
 * @since             1.0.0
 * @package           Woo_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       CTX Feed
 * Plugin URI:        https://webappick.com/
 * Description:       Easily generate woocommerce product feed for any marketing channel like Google Shopping(Merchant), Facebook Remarketing, Bing, eBay & more. Support 100+ Merchants.
 * Version:           6.4.16
 * Author:            WebAppick
 * Author URI:        https://webappick.com/
 * License:           GPL v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:       woo-feed
 * Domain Path:       /languages
 *
 * WP Requirement & Test
 * Requires at least: 4.4
 * Tested up to: 6.4
 * Requires PHP: 5.6
 *
 * WC Requirement & Test
 * WC requires at least: 3.3
 * WC tested up to: 8.6.1
 */

use CTXFeed\V5\API\RestController;

if ( ! defined( 'ABSPATH' ) ) {
	die(); // If this file is called directly, abort.
}


if ( ! defined( 'WOO_FEED_FREE_FILE' ) ) {
	/**
	 * Plugin Base File
	 *
	 * @since 3.1.41
	 * @var string
	 */
	define( 'WOO_FEED_FREE_FILE', __FILE__ );
}

if ( ! defined( 'WOO_FEED_IS_FREE' ) ) {
    /**
     * Plugin Base File NAME
     *
     * @since 4.7.1
     * @var string
     */
    define( 'WOO_FEED_IS_FREE', true );
}

if ( ! defined( 'WOO_FEED_FREE_ADMIN_URL' ) ) {
	/**
	 * Admin File url
	 *
	 * @since 4.2.0
	 * @var   string
	 */
	define( 'WOO_FEED_FREE_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin/' );
}
if ( ! defined( 'WOO_FEED_API_NAMESPACE' ) ) {
	/**
	 * API NAMESPACE
	 *
	 * @since 4.5.13
	 * @var   String
	 */
	define( 'WOO_FEED_API_NAMESPACE', 'ctxfeed' );
}
if ( ! defined( 'WOO_FEED_API_VERSION' ) ) {
	/**
	 * API VERSION
	 *
	 * @since 4.5.13
	 * @var   String
	 */
	define( 'WOO_FEED_API_VERSION', 'v1' );
}


if ( ! defined( 'WOO_FEED_V5_URL' ) ) {
	/**
	 * V5 url
	 *
	 * @since 6.0.0
	 * @var   string
	 */
	define( 'WOO_FEED_V5_URL', plugin_dir_url( __FILE__ ) . 'V5/' );

}

if ( ! defined( 'WOO_FEED_PLUGIN_FILE' ) ) {
	$plugin_file = explode( DIRECTORY_SEPARATOR, __FILE__ );
	$plugin_file = end( $plugin_file );
	/**
	 * plugin file
	 *
	 * @since 5.0.0
	 * @var   string
	 */
	define( 'WOO_FEED_PLUGIN_FILE', $plugin_file );
}

require_once __DIR__ . '/includes/classes/class-woo-feed-constants.php';

if ( ! function_exists( 'request_filesystem_credentials' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}

Woo_Feed_Constants::defined_constants();

/*
 * Autoloader
 *
 * @since 4.7
 *
 * */
require_once __DIR__ . DIRECTORY_SEPARATOR
			 . 'libs' . DIRECTORY_SEPARATOR
			 . 'autoload.php';

/**
 * Load V5 Module
 */
require_once WOO_FEED_FREE_PATH . 'V5/autoload.php';

// Attributes executable file [Manages newly added attributes]
// @TODO Refactor all the attributes to a single file.
require_once __DIR__ . DIRECTORY_SEPARATOR
			 . 'V5' . DIRECTORY_SEPARATOR
			 . 'CustomFields' . DIRECTORY_SEPARATOR
			 . 'Attributes.php';

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if ( is_plugin_active( 'webappick-product-feed-for-woocommerce-pro/webappick-product-feed-for-woocommerce-pro.php' ) ) {
	deactivate_plugins( 'webappick-product-feed-for-woocommerce-pro/webappick-product-feed-for-woocommerce-pro.php', false, null );
}
/**
 * Load Uses Tracker
 */
require_once WOO_FEED_FREE_PATH . 'includes/classes/class-woo-feed-webappick-api.php';
/**
 * Load Helper functions
 */
require_once WOO_FEED_FREE_PATH . 'includes/hooks.php';
require_once WOO_FEED_FREE_PATH . 'includes/log-helper.php';
require_once WOO_FEED_FREE_PATH . 'includes/helper.php';
require_once WOO_FEED_FREE_PATH . 'includes/cron-helper.php';

/**
 * Load filter, sanitizer
 */
require_once WOO_FEED_FREE_PATH . 'includes/filters/filters.php';

/**
 * Installer
 */
require_once WOO_FEED_FREE_PATH . 'includes/class-woo-feed-installer.php';


if ( ! class_exists( 'Woo_Feed' ) ) {
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require WOO_FEED_FREE_PATH . 'includes/class-woo-feed.php';
}

if ( ! function_exists( 'run_woo_feed' ) ) {
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_woo_feed() {
		$plugin = new Woo_Feed();
		register_activation_hook( WOO_FEED_FREE_FILE, array( 'Woo_Feed_installer', 'install' ) );
		register_shutdown_function( 'woo_feed_log_errors_at_shutdown' );
		add_action( 'woo_feed_cleanup_logs', 'woo_feed_cleanup_logs' );
		/**
		 * Ensure Feed Plugin runs only if WooCommerce loaded (installed and activated)
		 *
		 * @since 3.1.41
		 */
		add_action( 'plugins_loaded', array( $plugin, 'run' ), PHP_INT_MAX );
		add_action( 'admin_notices', 'wooFeed_Admin_Notices' );

        //HPOS compatibility
        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'before_woocommerce_init', function () {
                if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
                }
            } );
        }

		WooFeedWebAppickAPI::getInstance();

	}

	run_woo_feed();
}

add_action('init', function(){
	/**
	 * Notices
	 */
	require_once WOO_FEED_FREE_PATH . 'includes/classes/class-woo-feed-admin-notices.php';
	Woo_Feed_Notices::getInstance();

});

// Handle Ajax Actions
require_once WOO_FEED_FREE_PATH . 'includes/action-handler.php';

// ======================================================================================================================*
//
// Ajax Feed Making Development Start.
//
// ======================================================================================================================*

if ( ! function_exists( 'woo_feed_make_batch_feed' ) ) {
	add_action( 'wp_ajax_make_batch_feed', 'woo_feed_make_batch_feed' );
	/**
	 * Ajax Batch Callback
	 *
	 * @return void
	 */
	function woo_feed_make_batch_feed() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			die();
		}
		if ( ! isset( $_REQUEST['feed'] ) ) {
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
			die();
		}

		$feedName = woo_feed_extract_feed_option_name( sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) ) );
		$feedInfo = get_option( 'wf_config' . $feedName, false );

		if ( ! $feedInfo ) {
			$getFeedConfig = maybe_unserialize( get_option( 'wf_feed_' . $feedName ) );
			$feedInfo      = $getFeedConfig['feedrules'];
		}

		$feedInfo['productIds'] = isset( $_REQUEST['products'] ) ? array_map( 'absint', $_REQUEST['products'] ) : array();
		$offset                 = isset( $_REQUEST['loop'] ) ? absint( $_REQUEST['loop'] ) : 0;
		if ( woo_feed_is_debugging_enabled() ) {
			if ( 0 === $offset ) {
				woo_feed_log_feed_process( $feedInfo['filename'], 'Generating Feed... ' );
			}
			if ( woo_feed_is_debugging_enabled() ) {
				woo_feed_log_feed_process( $feedInfo['filename'], sprintf( 'Processing Loop %d.', ( $offset + 1 ) ) );
				$m = 'Processing Product Following Product (IDs) : ' . PHP_EOL;
				foreach ( array_chunk( $feedInfo['productIds'], 10 ) as $productIds ) { // pretty print log [B-)=
					$m .= implode( ', ', $productIds ) . PHP_EOL;
				}
				woo_feed_log_feed_process( $feedInfo['filename'], $m );
			}
		}

		if ( 0 === $offset ) {
			woo_feed_unlink_tempFiles( $feedInfo, $feedName );
		}

		if ( isset( $feedInfo['provider'] ) && 'googlereview' === $feedInfo['provider'] ) {
			$feed_data = true;
		} else {
			$feed_data = woo_feed_generate_batch_data( $feedInfo, $feedName );
		}

		if ( $feed_data ) {
			woo_feed_log_feed_process( $feedInfo['filename'], sprintf( 'Done Processing Loop %d.', ( $offset + 1 ) ) );
			wp_send_json_success(
				array(
					'success'  => true,
					'products' => 'yes',
				)
			);
		} else {
			woo_feed_log_feed_process( $feedInfo['filename'], sprintf( 'No Products found @ Loop %d.', $offset ) );
			wp_send_json_success(
				array(
					'success'  => true,
					'products' => 'no',
					'config'   => $feedInfo,
				)
			);
		}
		wp_die();
	}
}
if ( ! function_exists( 'woo_feed_save_feed_file' ) ) {
	add_action( 'wp_ajax_save_feed_file', 'woo_feed_save_feed_file' );
	/**
	 * Ajax Response for Save Feed File
	 *
	 * @return void
	 * @throws Exception
	 */
	function woo_feed_save_feed_file() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			die();
		}
		if ( ! isset( $_REQUEST['feed'] ) ) {
			wp_send_json_error( esc_html__( 'Invalid Feed.', 'woo-feed' ) );
			die();
		}

		$feedName = woo_feed_extract_feed_option_name( sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) ) );
		$info     = get_option( 'wf_config' . $feedName, false );

		if ( ! $info ) {
			$getFeedConfig = maybe_unserialize( get_option( 'wf_feed_' . $feedName ) );
			$info          = $getFeedConfig['feedrules'];
		}

		$feedService = $info['provider'];
		$type        = $info['feedType'];
		woo_feed_log_feed_process( $info['filename'], sprintf( 'Preparing Final Feed (%s) File...', $type ) );
		woo_feed_log_feed_process( $info['filename'], 'Getting Batch Chunks' );
		$feedHeader = woo_feed_get_batch_feed_info( $feedService, $type, 'wf_store_feed_header_info_' . $feedName );
		if ( ! $feedHeader ) {
			woo_feed_log_feed_process( $info['filename'], 'Unable to Get Header Chunk' );
		}
		$feedBody = woo_feed_get_batch_feed_info( $feedService, $type, 'wf_store_feed_body_info_' . $feedName );
		if ( ! $feedBody ) {
			woo_feed_log_feed_process( $info['filename'], 'Unable to Get Body Chunk' );
		}
		$feedFooter = woo_feed_get_batch_feed_info( $feedService, $type, 'wf_store_feed_footer_info_' . $feedName );
		if ( ! $feedFooter ) {
			woo_feed_log_feed_process( $info['filename'], 'Unable to Get Footer Chunk' );
		}

		// make file xml string
		if ( isset( $info['provider'] ) && 'googlereview' === $info['provider'] ) {
			$reviewObj = new Woo_Feed_Review( $info );
			$feedBody  = $reviewObj->make_review_xml_feed();
			$string    = $feedBody;

		} elseif ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type ) {
			$csvHead[0] = $feedHeader;
			if ( ! empty( $csvHead ) && ! empty( $feedBody ) ) {
				$string = array_merge( $csvHead, $feedBody );
			} else {
				$string = array();
			}
		} elseif ( 'json' === $type ) {
			$string = $feedBody;
		} else {
			$string = $feedHeader . $feedBody . $feedFooter;
		}

		$upload_dir = wp_get_upload_dir();
		$path       = $upload_dir['basedir'] . '/woo-feed/' . $feedService . '/' . $type;
		$saveFile   = false;
		$file       = '';
		// Check If any products founds
		if ( $string && ! empty( $string ) ) {
			// Save File
			$file = $path . '/' . $feedName . '.' . $type;
			try {
				$save = new Woo_Feed_Savefile();
				if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type || 'json' === $type ) {
					$saveFile = $save->saveValueFile( $path, $file, $string, $info, $type );
				} else {
					$saveFile = $save->saveFile( $path, $file, $string );
				}
				if ( $saveFile ) {
					$message = 'Feed File Successfully Saved.';
				} else {
					$message = 'Unable to save Feed file. Check Directory Permission.';
				}
				woo_feed_log_feed_process( $info['filename'], $message );
			} catch ( Exception $e ) {
				$message = 'Error Saving Feed File' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
				woo_feed_log( $info['filename'], $message, 'critical', $e, true );
				woo_feed_log_fatal_error( $message, $e );
			}
		} else {
			woo_feed_log_feed_process( $info['filename'], 'No Product Found... Exiting File Save Process...' );
			if ( isset( $info['fattribute'] ) && count( $info['fattribute'] ) ) {
				$data = array(
					'success' => false,
					'message' => esc_html__( 'Products not found with your filtering condition.', 'woo-feed' ),
				);
			} else {
				$data = array(
					'success' => false,
					'message' => esc_html__( 'No Product Found with your feed configuration. Please Update And Generate the feed again.', 'woo-feed' ),
				);
			}
			wp_send_json_error( $data );
			wp_die();
		}

		$feed_URL = woo_feed_get_file_url( $feedName, $feedService, $type );
		// Save Info into database.
		$feedInfo    = array(
			'feedrules'    => $info,
			'url'          => $feed_URL,
			'last_updated' => date( 'Y-m-d H:i:s', strtotime( current_time( 'mysql' ) ) ),
		);
		$feedOldInfo = maybe_unserialize( get_option( 'wf_feed_' . $feedName ) );
		if ( isset( $feedOldInfo['status'] ) ) {
			$feedInfo['status'] = $feedOldInfo['status'];
		} else {
			$feedInfo['status'] = 1;
		}

		woo_feed_unlink_tempFiles( $info, $feedName );

		woo_feed_log_feed_process( $info['filename'], 'Updating Feed Information.' );

		update_option( 'wf_feed_' . $feedName, serialize( $feedInfo ), false ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize

		if ( $saveFile ) {
			// FTP File Upload Info
			$ftpEnabled = sanitize_text_field( $info['ftpenabled'] );

			if ( $ftpEnabled ) {
				woo_feed_handle_file_transfer( $file, $feedName . '.' . $type, $info );
			}

			$cat  = woo_feed_check_google_category( $feedInfo );
			$data = array(
				'info'    => $feedInfo,
				'url'     => $feed_URL,
				'cat'     => $cat,
				'message' => esc_html__( 'Feed Making Complete', 'woo-feed' ),
			);
			woo_feed_log_feed_process( $info['filename'], 'Done Processing Feed. Exiting Process...' );
			wp_send_json_success( $data );
		} else {
			woo_feed_log_feed_process( $info['filename'], 'Done Processing Feed. Exiting Process...' );
			$data = array(
				'success' => false,
				'message' => esc_html__( 'Failed to save feed file. Please confirm that your WordPress directory have read and write permission.', 'woo-feed' ),
			);
			wp_send_json_error( $data );
		}
		wp_die();
	}
}
// Ajax Helper.
if ( ! function_exists( 'woo_feed_generate_batch_data' ) ) {
	/**
	 * Generate Feed Data
	 *
	 * @param array  $info     Feed info.
	 * @param string $feedSlug feed option slug.
	 *
	 * @return bool
	 */
	function woo_feed_generate_batch_data( $info, $feedSlug ) {
		// parse rules.
		$info = woo_feed_parse_feed_rules( isset( $info['feedrules'] ) ? $info['feedrules'] : $info );

		try {
			do_action( 'before_woo_feed_generate_batch_data', $info );
			$status = false;
			if ( ! empty( $info['provider'] ) ) {
				// Get Post data.
				$feedService = sanitize_text_field( $info['provider'] );
				$type        = sanitize_text_field( $info['feedType'] );
				$feedRules   = $info;
				// Get Feed info.
				$products = new Woo_Generate_Feed( $feedService, $feedRules );
				woo_feed_log_feed_process( $info['filename'], sprintf( 'Initializing merchant Class %s for %s', $feedService, $info['provider'] ) );
				$feed = $products->getProducts();
				if ( ! empty( $feed['body'] ) ) {
					$feedBody = 'wf_store_feed_body_info_' . $feedSlug;
					$prevFeed = woo_feed_get_batch_feed_info( $feedService, $type, $feedBody );
					if ( $prevFeed ) {
						if ( 'csv' === $type || 'tsv' === $type || 'xls' === $type || 'xlsx' === $type || 'json' === $type ) {
							if ( ! empty( $prevFeed ) ) {
								$newFeed = array_merge( $prevFeed, $feed['body'] );
								woo_feed_save_batch_feed_info( $feedService, $type, $newFeed, $feedBody, $info );
							}
						} else {
							$newFeed = $prevFeed . $feed['body'];
							woo_feed_save_batch_feed_info( $feedService, $type, $newFeed, $feedBody, $info );
						}
					} else {
						woo_feed_save_batch_feed_info( $feedService, $type, $feed['body'], $feedBody, $info );
					}
					woo_feed_save_batch_feed_info( $feedService, $type, $feed['header'], 'wf_store_feed_header_info_' . $feedSlug, $info );
					woo_feed_save_batch_feed_info( $feedService, $type, $feed['footer'], 'wf_store_feed_footer_info_' . $feedSlug, $info );
					$status = true;
				} else {
					$status = false;
				}
			}
			do_action( 'after_woo_feed_generate_batch_data', $info );

			return $status;
		} catch ( Exception $e ) {
			$message = 'Error Generating Product Data.' . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
			woo_feed_log( $info['filename'], $message, 'critical', $e, true );
			woo_feed_log_fatal_error( $message, $e );

			return false;
		}
	}
}

// Menu Callback.
if ( ! function_exists( 'woo_feed_generate_new_feed' ) ) {
	/**
	 * Generate Feed
	 */
	function woo_feed_generate_new_feed() {

		if ( isset( $_POST['provider'], $_POST['_wpnonce'], $_POST['filename'], $_POST['feedType'] ) ) {
			// Verify Nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'woo_feed_form_nonce' ) ) {
				wp_die( esc_html__( 'Failed security check', 'woo-feed' ), 403 );
			}
			// Check feed type (file ext).
			if ( ! woo_feed_check_valid_extension( sanitize_text_field( wp_unslash( $_POST['feedType'] ) ) ) ) {
				wp_die( esc_html__( 'Invalid Feed Type!', 'woo-feed' ), 400 );
			}

			$fileName = woo_feed_save_feed_config_data( $_POST );

			wp_safe_redirect(
				add_query_arg(
					array(
						'feed_created'    => (int) false !== $fileName,
						'feed_regenerate' => 1,
						'feed_name'       => $fileName ? $fileName : '',
					),
					admin_url( 'admin.php?page=webappick-manage-feeds' )
				)
			);
			wp_die();
		}

		require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-admin-display.php';
	}
}
if ( ! function_exists( 'woo_feed_manage_feed' ) ) {
	/**
	 * Manage Feeds
	 */
	function woo_feed_manage_feed() {


		$provider         = isset( $_POST['provider'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['provider'], 'text' ) : "";
		$feed_id          = isset( $_POST['feed_id'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['feed_id'], 'text' ) : "";
		$feed_option_name = isset( $_POST['feed_option_name'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['feed_option_name'], 'text' ) : "";
		$filename         = isset( $_POST['filename'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['filename'], 'file_name' ) : "";
		$feedType         = isset( $_POST['feedType'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['feedType'], 'text' ) : "";

		$getaction = isset( $_GET['action'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'GET', @$_GET['action'], 'text' ) : "";
		$getfeed   = isset( $_GET['action'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'GET', @$_GET['feed'], 'text' ) : "";

		// @TODO use admin_post_ action for form handling.
		// Manage action for category mapping.
		if ( 'edit-feed' === $getaction ) {
			if ( ! defined( 'WOO_FEED_EDIT_CONFIG' ) ) {
				define( 'WOO_FEED_EDIT_CONFIG', true );
			}

			if ( count( $_POST ) && $provider && $feed_id && $feed_option_name && $filename && $feedType ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$nonce = isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
				if ( ! wp_verify_nonce( $nonce, 'wf_edit_feed' ) ) {
					wp_die( esc_html__( 'Failed security check', 'woo-feed' ), 403 );
				}
				// Check feed type (file ext)
				if ( ! woo_feed_check_valid_extension( wp_unslash( $_POST['feedType'] ) ) ) {
					wp_die( esc_html__( 'Invalid Feed Type!', 'woo-feed' ), 400 );
				}

				$feed_option_name = wp_unslash( $feed_option_name );
				$fileName = woo_feed_save_feed_config_data( $_POST, $feed_option_name, isset( $_POST['edit-feed'] ) );
				// redirect to the feed list with status
				// @TODO this should be handled in admin_init action for proper redirection to work...
				wp_safe_redirect(
					add_query_arg(
						array(
							'feed_updated'    => (int) false !== $fileName,
							'feed_regenerate' => (int) isset( $_POST['edit-feed'] ),
							'feed_name'       => $fileName ? $fileName : '',
						),
						admin_url( 'admin.php?page=webappick-manage-feeds' )
					)
				);
				die();
			}
			if ( $getfeed ) {
				global $wpdb, $feedRules, $feedName, $feedId, $provider;
				$feedName = wp_unslash( $getfeed );
					// @TODO Initialize react UI
					wp_safe_redirect(
						add_query_arg(
							array(
								'action' => 'edit-feed',
								'feed'   => $feedName,
							),
							admin_url( 'admin.php?page=webappick-new-feed' )
						)
					);
			}
		} else {
			// Update Interval.
			$wf_schedule = isset( $_POST['wf_schedule'] ) ? apply_filters( 'CTXFEED_filter_securing_input', 'POST', @$_POST['wf_schedule'], 'text' ) : "" ;
			if ( $wf_schedule ) {
				if ( isset( $_POST['wf_schedule_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wf_schedule_nonce'] ) ), 'wf_schedule' ) ) {
					$interval = absint( $wf_schedule );
					if ( $interval >= woo_feed_get_minimum_interval_option() ) {
						if ( update_option( 'wf_schedule', wp_unslash( $wf_schedule ), false ) ) {
							wp_clear_scheduled_hook( 'woo_feed_update' );
							add_filter( 'cron_schedules', 'Woo_Feed_installer::cron_schedules' ); // phpcs:ignore
							// wp_schedule_event( time(), 'woo_feed_corn', 'woo_feed_update' );
							$update = 1; // success.
						} else {
							$update = 2; // db fail.
						}
					} else {
						$update = 3; // invalid value.
					}
				} else {
					$update = 4; // invalid nonce.
				}
				wp_safe_redirect( add_query_arg( array( 'schedule_updated' => $update ), admin_url( 'admin.php?page=webappick-manage-feeds' ) ) );
				die();
			}
			//require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-manage-list.php';
		}
	}
}

// Get Merchant template.
if ( ! function_exists( 'feed_merchant_view' ) ) {
	// Load Feed Templates.
	add_action( 'wp_ajax_get_feed_merchant', 'feed_merchant_view' );
	/**
	 * Ajax response for Create/Add Feed config table for selected Merchant/Provider
	 *
	 * @return void
	 */
	function feed_merchant_view() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			die();
		}
		global $feedRules, $wooFeedDropDown, $merchant, $provider;
		$provider = isset( $_REQUEST['merchant'] ) && ! empty( $_REQUEST['merchant'] ) ? strtolower( sanitize_text_field( wp_unslash( $_REQUEST['merchant'] ) ) ) : '';
		if ( empty( $provider ) ) {
			wp_send_json_error( esc_html__( 'Invalid Merchant', 'woo-feed' ) );
			wp_die();
		}
		$merchant        = new Woo_Feed_Merchant( $provider );
		$feedRules       = $merchant->get_template();
		$wooFeedDropDown = new Woo_Feed_Dropdown();
		ob_start();
		require_once WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-edit-tabs.php';
		wp_send_json_success(
			array(
				'tabs'         => ob_get_clean(),
				'feedType'     => strtolower( $merchant->get_feed_types( true ) ),
				'itemsWrapper' => $feedRules['itemsWrapper'],
				'itemWrapper'  => $feedRules['itemWrapper'],
				'delimiter'    => $feedRules['delimiter'],
				'enclosure'    => $feedRules['enclosure'],
				'extraHeader'  => $feedRules['extraHeader'],
			)
		);
		wp_die();
	}
}
// Get facebook Categories.
if ( ! function_exists( 'woo_feed_get_facebook_categories' ) ) {
	add_action( 'wp_ajax_get_facebook_categories', 'woo_feed_get_facebook_categories' );
	/**
	 * Ajax Response for Facebook Category Dropdown Data
	 *
	 * @return void
	 */
	function woo_feed_get_facebook_categories() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			wp_die();
		}
		$wooFeedDropDown = new Woo_Feed_Dropdown();
		wp_send_json_success( $wooFeedDropDown->facebookTaxonomyArray() );
		die();
	}
}
// Get Google Categories.
if ( ! function_exists( 'woo_feed_get_google_categories' ) ) {
	add_action( 'wp_ajax_get_google_categories', 'woo_feed_get_google_categories' );
	/**
	 * Ajax Response for Google Category Dropdown Data
	 *
	 * @return void
	 */
	function woo_feed_get_google_categories() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			wp_die();
		}
		$wooFeedDropDown = new Woo_Feed_Dropdown();
		wp_send_json_success( $wooFeedDropDown->googleTaxonomyArray() );
		die();
	}
}
// sftp status detection.
if ( ! function_exists( 'woo_feed_get_ssh2_status' ) ) {
	add_action( 'wp_ajax_get_ssh2_status', 'woo_feed_get_ssh2_status' );
	/**
	 * Ajax Response for ssh2 status check
	 *
	 * @return void
	 */
	function woo_feed_get_ssh2_status() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			wp_die();
		}
		if ( extension_loaded( 'ssh2' ) ) {
			wp_send_json_success( 'exists' );
		} else {
			wp_send_json_success( 'not_exists' );
		}
		wp_die();
	}
}
// Feed cron status update.
if ( ! function_exists( 'woo_feed_update_feed_status' ) ) {
	/**
	 * Update feed status
	 */
	add_action( 'wp_ajax_update_feed_status', 'woo_feed_update_feed_status' );
	/**
	 * Ajax Response for Update Feed Status
	 *
	 * @return void
	 */
	function woo_feed_update_feed_status() {
		check_ajax_referer( 'wpf_feed_nonce' );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			woo_feed_log_debug_message( 'User doesnt have enough permission.' );
			wp_send_json_error( esc_html__( 'Unauthorized Action.', 'woo-feed' ) );
			wp_die();
		}

		$feedName = isset( $_POST['feedName'] ) ? sanitize_text_field( wp_unslash( $_POST['feedName'] ) ) : '';
		if ( ! empty( $feedName ) ) {
			$feedInfo           = maybe_unserialize( get_option( $feedName ) );
			$feedInfo['status'] = isset( $_POST['status'] ) && 1 === (int) $_POST['status'] ? 1 : 0;

			$feed_slug = str_replace( 'wf_feed_', 'wf_config', $feedName );
			if ( 1 === $feedInfo['status'] ) {
				if ( ! wp_next_scheduled( 'woo_feed_update_single_feed', array( $feed_slug ) ) ) {
					wp_schedule_event( time(), 'woo_feed_corn', 'woo_feed_update_single_feed', array( $feed_slug ) );
				}
			} else {
				wp_clear_scheduled_hook( 'woo_feed_update_single_feed', array( $feed_slug ) );
			}

			update_option( $feedName, serialize( $feedInfo ), false ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			wp_send_json_success( array( 'status' => true ) );
		} else {
			wp_send_json_error( array( 'status' => false ) );
		}
		wp_die();
	}
}

// Render and handle status page options.
if ( ! function_exists( 'woo_feed_system_status' ) ) {
	/**
	 * Feed Status Page
	 *
	 * @return void
	 */
	function woo_feed_system_status() {
		require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-status.php';
	}
}

// Render and handle settings page options.
if ( ! function_exists( 'woo_feed_config_feed' ) ) {
	/**
	 * Feed Settings Page
	 *
	 * @return void
	 */
	function woo_feed_config_feed() {
		if ( isset( $_POST['wa_woo_feed_config'], $_POST['_wpnonce'] ) ) {
			check_admin_referer( 'woo-feed-config' );

			$data = array(
				'per_batch'                  => isset( $_POST['batch_limit'] ) ? absint( $_POST['batch_limit'] ) : '',
				'product_query_type'         => isset( $_POST['product_query_type'] ) ? sanitize_text_field( wp_unslash( $_POST['product_query_type'] ) ) : '',
				'variation_query_type'       => isset( $_POST['variation_query_type'] ) ? sanitize_text_field( wp_unslash( $_POST['variation_query_type'] ) ) : '',
				'enable_error_debugging'     => isset( $_POST['enable_error_debugging'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_error_debugging'] ) ) : '',
				'cache_ttl'                  => isset( $_POST['cache_ttl'] ) ? absint( $_POST['cache_ttl'] ) : '',
				'overridden_structured_data' => isset( $_POST['overridden_structured_data'] ) ? sanitize_text_field( wp_unslash( $_POST['overridden_structured_data'] ) ) : '',
				'disable_mpn'                => isset( $_POST['disable_mpn'] ) ? sanitize_text_field( wp_unslash( $_POST['disable_mpn'] ) ) : '',
				'disable_brand'              => isset( $_POST['disable_brand'] ) ? sanitize_text_field( wp_unslash( $_POST['disable_brand'] ) ) : '',
				'disable_pixel'              => isset( $_POST['disable_pixel'] ) ? sanitize_text_field( wp_unslash( $_POST['disable_pixel'] ) ) : '',
				'pixel_id'                   => isset( $_POST['pixel_id'] ) ? sanitize_text_field( wp_unslash( $_POST['pixel_id'] ) ) : '',
				'disable_remarketing'        => isset( $_POST['disable_remarketing'] ) ? sanitize_text_field( wp_unslash( $_POST['disable_remarketing'] ) ) : '',
				'remarketing_id'             => isset( $_POST['remarketing_id'] ) ? sanitize_text_field( wp_unslash( $_POST['remarketing_id'] ) ) : '',
				'remarketing_label'          => isset( $_POST['remarketing_label'] ) ? sanitize_text_field( wp_unslash( $_POST['remarketing_label'] ) ) : '',
				'allow_all_shipping'         => isset( $_POST['allow_all_shipping'] ) ? sanitize_text_field( wp_unslash( $_POST['allow_all_shipping'] ) ) : '',
				'only_free_shipping'         => isset( $_POST['only_free_shipping'] ) ? sanitize_text_field( wp_unslash( $_POST['only_free_shipping'] ) ) : '',
				'only_local_pickup_shipping' => isset( $_POST['only_local_pickup_shipping'] ) ? sanitize_text_field( wp_unslash( $_POST['only_local_pickup_shipping'] ) ) : '',
				'enable_ftp_upload'          => isset( $_POST['enable_ftp_upload'] ) ? sanitize_text_field( wp_unslash( $_POST['enable_ftp_upload'] ) ) : '',
			);

			woo_feed_save_options( $data );

			// $currencyAPI = isset( $_POST['currency_api_code'] ) ? sanitize_text_field( $_POST['currency_api_code'] ) : '';
			// update_option( 'woo_feed_currency_api_code', $currencyAPI, false );

			if ( isset( $_POST['opt_in'] ) && 'on' === $_POST['opt_in'] ) {
				WooFeedWebAppickAPI::getInstance()->trackerOptIn();
			} else {
				WooFeedWebAppickAPI::getInstance()->trackerOptOut();
			}
			// Actions exec by user from settings page
			if ( isset( $_POST['clear_all_logs'] ) && 'on' === $_POST['clear_all_logs'] ) {
				woo_feed_delete_all_logs();
			}
			if ( isset( $_POST['purge_feed_cache'] ) ) {
				woo_feed_flush_cache_data();
			}

			wp_safe_redirect( admin_url( 'admin.php?page=webappick-feed-settings&settings_updated=1' ) );
			die();
		}

		require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-settings.php';
	}
}
if ( ! function_exists( 'woo_feed_flash_cache_action' ) ) {
	/**
	 * Flash cache after specific actions
	 *
	 * @return void
	 */
	function woo_feed_flash_cache_action() {
		woo_feed_flush_cache_data();
	}
}

if ( ! class_exists( 'webappick_suggest_plugin' ) ) {
	class webappick_suggest_plugin {

		static function init() {
			if ( is_admin() ) {
				if ( ! is_plugin_active( 'xt-woo-variation-swatches/xt-woo-variation-swatches.php' ) ) {
					add_filter( 'install_plugins_table_api_args_featured', array( __CLASS__, 'featured_plugins_tab' ) );
				}
			}
		} // init

		// add our plugins to recommended list
		static function plugins_api_result( $res, $action, $args ) {
			remove_filter( 'plugins_api_result', array( __CLASS__, 'plugins_api_result' ), 10, 1 );
			$res = self::add_plugin_favs( 'webappick-pdf-invoice-for-woocommerce', $res );

			return $res;
		} // plugins_api_result

		// helper function for adding plugins to fav list
		static function featured_plugins_tab( $args ) {
			add_filter( 'plugins_api_result', array( __CLASS__, 'plugins_api_result' ), 10, 3 );

			return $args;
		} // featured_plugins_tab

		// add single plugin to list of favs
		static function add_plugin_favs( $plugin_slug, $res ) {
			if ( ! empty( $res->plugins ) && is_array( $res->plugins ) ) {
				foreach ( $res->plugins as $plugin ) {
					if ( is_object( $plugin ) && ! empty( $plugin->slug ) && $plugin->slug === $plugin_slug ) {
						return $res;
					}
				} // foreach
			}
			$plugin_info = get_transient( 'wf-plugin-info-' . $plugin_slug );
			if ( $plugin_info ) {
				array_unshift( $res->plugins, $plugin_info );
			} else {
				$plugin_info = plugins_api(
					'plugin_information',
					array(
						'slug'   => $plugin_slug,
						'is_ssl' => is_ssl(),
						'fields' => array(
							'banners'           => true,
							'reviews'           => true,
							'downloaded'        => true,
							'active_installs'   => true,
							'icons'             => true,
							'short_description' => true,
						),
					)
				);
				if ( ! is_wp_error( $plugin_info ) ) {
					$res->plugins[] = $plugin_info;
					set_transient( 'wf-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7 );
				}
			}

			return $res;
		} // add_plugin_favs
	}

	add_action( 'init', array( 'webappick_suggest_plugin', 'init' ) );
}

// Suggest other plugins of webappick.

register_deactivation_hook( __FILE__, 'woo_feed_deactivate' );
if ( ! function_exists( 'woo_feed_deactivate' ) ) {
	/**
	 * Flash cache purged after plugin deactivation
	 *
	 * @return void
	 */
	function woo_feed_deactivate() {
		woo_feed_flush_cache_data(); // clean caches.
	}
}

/**
 * rest api init
 * @return void
 */
if ( !function_exists( 'init_rest_api' ) ) {

	function init_rest_api() {
		RestController::instance();
	}
}

add_action( 'init', 'init_rest_api' );
// End of file woo-feed.php
