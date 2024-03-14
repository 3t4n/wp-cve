<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly
use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Class WFFN_Common
 * Handles Common Functions For Admin as well as front end interface
 */
if ( ! class_exists( 'WFFN_Common' ) ) {
	class WFFN_Common {

		public static $start_time = 0;

		public static function init() {
			add_action( 'wp', array( __CLASS__, 'setup_schedule_to_remove_orphaned_transients' ) );
			/**
			 * schedule setup to remove expired transients
			 */
			add_action( 'wffn_remove_orphaned_transients', array( __CLASS__, 'remove_orphaned_transients' ), 999999 );
			add_action( 'wffn_remove_orphaned_transients', array( __CLASS__, 'remove_wffn_logs_files' ), 999999 );
			add_filter( 'bwf_fb_templates', array( __CLASS__, 'update_template_list' ), 10, 1 );


		}

		/**
		 * @param $arr
		 */
		public static function pr( $arr ) {
			echo '<pre>';
			print_r( $arr );  // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			echo '</pre>';
		}

		/**
		 * @return array
		 */
		public static function get_steps_data() {
			$steps        = WFFN_Core()->steps->get_supported_steps();
			$sorted_steps = self::sort_steps( $steps );
			$steps_data   = array();
			foreach ( $sorted_steps as $step ) {
				$steps_data[ $step->slug ] = $step->get_step_data();
			}

			return $steps_data;
		}

		/**
		 * @param $steps
		 *
		 * @return mixed
		 */
		public static function sort_steps( $steps ) {
			usort( $steps, function ( $a, $b ) {
				if ( $a->list_priority === $b->list_priority ) {
					return 0;
				}

				return ( $a->list_priority < $b->list_priority ) ? - 1 : 1;
			} );

			return $steps;
		}

		/**
		 * @return array
		 */
		public static function get_substeps_data() {
			$substeps      = WFFN_Core()->substeps->get_supported_substeps();
			$substeps_data = array();
			foreach ( $substeps as $substep ) {
				$substeps_data[ $substep->slug ] = $substep->get_substep_data();
			}

			return $substeps_data;
		}


		/**
		 * @return array
		 */
		public static function get_funnel_delete_data() {
			$funnel = new WFFN_Funnel( '0' );

			return $funnel->get_delete_data();
		}

		/**
		 * @return array
		 */
		public static function get_funnel_duplicate_data() {
			$funnel = WFFN_Core()->admin->get_funnel();

			return $funnel->get_duplicate_data();
		}


		public static function get_funnel_slug() {
			return 'funnel';
		}


		public static function is_admin_action() {
			if ( is_admin() && ( 'bwf_funnels' === filter_input( INPUT_GET, 'page' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX && 1 === filter_input( INPUT_POST, 'is_funnel_action' ) ) ) ) {
				return true;
			}

			return false;
		}


		public static function get_font_weights() {
			return array(
				array(
					'id'   => '400',
					'name' => __( 'Normal 400', 'funnel-builder' )
				),
				array(
					'id'   => '700',
					'name' => __( 'Bold 700', 'funnel-builder' )
				),
			);
		}

		public static function search_page( $term, $post_types_override = [] ) {
			global $wpdb;
			$like_term     = '%' . $wpdb->esc_like( $term ) . '%';
			$post_statuses = array( 'publish' );

			if ( ! empty( $post_types_override ) ) {
				$post_types = $post_types_override;
			} else {
				/**
				 * get all public post type in search
				 */
				$args          = array(
					'public' => true,
				);
				$get_all_types = get_post_types( $args, 'objects' );
				$post_types    = [ 'page' ];

				if ( is_array( $get_all_types ) && count( $get_all_types ) > 0 ) {
					$post_types = array_keys( $get_all_types );
				}

				$excludes = apply_filters( 'wffn_exclude_post_types_from_search', array(
					'attachment'
				), $post_types );

				$post_types = array_diff( $post_types, $excludes );

			}

			$query = $wpdb->prepare( "SELECT DISTINCT posts.ID FROM {$wpdb->posts} posts WHERE ( posts.post_title LIKE %s or posts.ID = %s ) AND posts.post_type IN ('" . implode( "','", $post_types ) . "')  AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "') ORDER BY posts.post_parent ASC, posts.post_title ASC", $like_term, $like_term ); //phpcs:ignore

			$post_ids = $wpdb->get_col( $query ); //phpcs:ignore

			if ( is_numeric( $term ) ) {
				$post_id    = absint( $term );
				$post_ids[] = $post_id;
			}

			return wp_parse_id_list( $post_ids );
		}

		public static function admin_user() {
			$user = array(
				'admin_email' => get_option( 'admin_email' ),
				'name'        => get_bloginfo( 'name', 'display' ),
			);

			return $user;
		}

		public static function maybe_elementor_template( $page_id, $new_page_id ) {

			if ( ! class_exists( 'WFFN_Elementor_Importer' ) ) {
				return;
			}

			$contents = get_post_meta( $page_id, '_elementor_data', true );
			$data     = [
				'_elementor_version'       => get_post_meta( $page_id, '_elementor_version', true ),
				'_elementor_template_type' => get_post_meta( $page_id, '_elementor_template_type', true ),
				'_elementor_edit_mode'     => get_post_meta( $page_id, '_elementor_edit_mode', true ),

			];
			foreach ( $data as $meta_key => $meta_value ) {
				update_post_meta( $new_page_id, $meta_key, $meta_value );
			}

			if ( ! function_exists( 'wp_read_video_metadata' ) ) {
				require_once ABSPATH . '/wp-admin/includes/media.php';
			}
			$instance = new WFFN_Elementor_Importer();
			if ( ! is_null( $instance ) ) {
				if ( is_array( $contents ) ) {
					$contents = wp_json_encode( $contents );

				}
				$instance->import( $new_page_id, $contents );
			}
		}

		/**
		 * Remove action for without instance method  class found and return object of class
		 *
		 * @param $hook
		 * @param $cls string
		 * @param string $function
		 *
		 * @return |null
		 */
		public static function remove_actions( $hook, $cls, $function = '' ) {

			global $wp_filter;
			$object = null;
			if ( class_exists( $cls ) && isset( $wp_filter[ $hook ] ) && ( $wp_filter[ $hook ] instanceof WP_Hook ) ) {
				$hooks = $wp_filter[ $hook ]->callbacks;
				foreach ( $hooks as $priority => $reference ) {
					if ( is_array( $reference ) && count( $reference ) > 0 ) {
						foreach ( $reference as $index => $calls ) {
							if ( isset( $calls['function'] ) && is_array( $calls['function'] ) && count( $calls['function'] ) > 0 ) {
								if ( is_object( $calls['function'][0] ) ) {
									$cls_name = get_class( $calls['function'][0] );
									if ( $cls_name === $cls && $calls['function'][1] === $function ) {
										$object = $calls['function'][0];
										unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $index ] );
									}
								} elseif ( $index === $cls . '::' . $function ) {
									$object = $cls;
									unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $cls . '::' . $function ] );
								}
							}
						}
					}
				}
			} elseif ( function_exists( $cls ) && isset( $wp_filter[ $hook ] ) && ( $wp_filter[ $hook ] instanceof WP_Hook ) ) {

				$hooks = $wp_filter[ $hook ]->callbacks;
				foreach ( $hooks as $priority => $reference ) {
					if ( is_array( $reference ) && count( $reference ) > 0 ) {
						foreach ( $reference as $index => $calls ) {
							$remove = false;
							if ( $index === $cls ) {
								$remove = true;
							} elseif ( isset( $calls['function'] ) && $cls === $calls['function'] ) {
								$remove = true;
							}
							if ( true === $remove ) {
								unset( $wp_filter[ $hook ]->callbacks[ $priority ][ $cls ] );
							}
						}
					}
				}
			}

			return $object;

		}

		public static function get_discount_type_keys() {

			$discounted = [
				'fixed_on_reg'          => sprintf( __( '%s Fixed Amount on Regular Price', 'funnel-builder' ), get_woocommerce_currency_symbol() ),
				'fixed_on_sale'         => sprintf( __( '%s Fixed Amount on Sale Price', 'funnel-builder' ), get_woocommerce_currency_symbol() ),
				'percentage_on_reg'     => __( '% on Regular Price', 'funnel-builder' ),
				'percentage_on_sale'    => __( '% on Sale Price', 'funnel-builder' ),
				'fixed_discount_reg'    => sprintf( __( '%s Fixed Amount on Regular Price', 'woofunnels-aero-checkout' ), get_woocommerce_currency_symbol() ),
				'fixed_discount_sale'   => sprintf( __( '%s Fixed Amount on Sale Price', 'woofunnels-aero-checkout' ), get_woocommerce_currency_symbol() ),
				'percent_discount_reg'  => __( '% on Regular Price', 'woofunnels-aero-checkout' ),
				'percent_discount_sale' => __( '% on Sale Price', 'woofunnels-aero-checkout' ),
			];

			return $discounted;

		}

		public static function get_funnel_edit_link( $funnel_id, $path = '/steps' ) {
			if ( empty( $funnel_id ) ) {
				return '#';
			}

			return add_query_arg( array(
				'page' => 'bwf',
				'path' => "/funnels/$funnel_id$path",
			), admin_url( 'admin.php' ) );
		}

		public static function get_store_checkout_edit_link( $path = '' ) {
			return add_query_arg( array(
				'page' => 'bwf',
				'path' => "/store-checkout" . $path,
			), admin_url( 'admin.php' ) );
		}

		public static function get_step_edit_link( $step_id, $type, $funnel_id = '', $is_timeline = false ) {

			if ( empty( $step_id ) || empty( $type ) ) {
				return '#';
			}

			if ( intval( $funnel_id ) > 0 ) {
				switch ( $type ) {
					case 'landing':
						$slug = '/funnel-optin/' . $step_id . '/design';
						break;
					case 'thankyou':
						$slug = '/funnel-thankyou/' . $step_id . '/design';
						break;
					case 'aero':
						$slug = '/funnel-checkout/' . $step_id . '/design';
						break;
					case 'upsell':
						$slug = '/funnel-offer/' . $step_id . '/design';
						break;
					case 'bump':
						$slug = '/funnel-bump/' . $step_id . '/product';
						break;
					case 'optin':
						$slug = ( $is_timeline ) ? '/funnels/' . $funnel_id . '/orders/optins' : '/funnel-optin/' . $step_id . '/design';
						break;
					case 'optin_ty':
						$slug = '/funnel-optin-confirmation/' . $step_id . '/design';
						break;
					default:
						$slug = '';
						break;
				}

				if ( empty( $slug ) ) {
					return '#';
				}

				return add_query_arg( [
					'page'      => 'bwf',
					'path'      => $slug,
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) );

			} else {
				switch ( $type ) {
					case 'aero':
						$step_args = [
							'page'     => 'wfacp',
							'wfacp_id' => $step_id,
						];
						break;
					case 'upsell':
						$step_args = [
							'page'    => 'upstroke',
							'section' => 'offers',
							'edit'    => $step_id,
						];
						break;
					case 'bump':
						$step_args = [
							'page'    => 'wfob',
							'section' => 'products',
							'wfob_id' => $step_id,
						];
						break;
					default:
						$step_args = '';
						break;
				}
				if ( empty( $step_args ) ) {
					return '#';
				}

				return add_query_arg( $step_args, admin_url( 'admin.php' ) );
			}

		}

		public static function get_experiment_edit_link( $funnel_id, $step_id, $path = '/experiments' ) {
			if ( empty( $step_id ) ) {
				return '#';
			}

			return add_query_arg( array(
				'page' => 'bwf',
				'path' => "/funnels/$funnel_id$path/$step_id",
			), admin_url( 'admin.php' ) );
		}

		public static function modify_content_emogrifier( $content ) {
			if ( empty( $content ) ) {
				return $content;
			}

			$content = self::prepare_email_content( $content );

			if ( false === self::supports_emogrifier() ) {
				return $content;
			}

			ob_start();
			include WFFN_PLUGIN_DIR . '/includes/libraries/email-styles.php'; //phpcs:ignore
			$css = ob_get_clean();

			$emogrifier_class = '\\Pelago\\Emogrifier';
			if ( ! class_exists( $emogrifier_class ) ) {
				include_once WFFN_PLUGIN_DIR . '/includes/libraries/class-emogrifier.php'; //phpcs:ignore
			}
			try {
				/** @var \Pelago\Emogrifier $emogrifier */
				$emogrifier = new $emogrifier_class( $content, $css );
				$content    = $emogrifier->emogrify();

				return $content;
			} catch ( Exception $e ) {
				BWF_Logger::get_instance()->log( 'Optin test email failure. Message: ' . $e->getMessage(), 'send_email_emogrifier' );
			}

			return $content;
		}

		/**
		 * Return if emogrifier library is supported.
		 *
		 * @return bool
		 * @since 3.5.0
		 */
		public static function supports_emogrifier() {
			return class_exists( 'DOMDocument' ) && version_compare( PHP_VERSION, '5.5', '>=' );
		}

		public static function prepare_email_content( $content ) {
			$has_body = stripos( $content, '<body' ) !== false;

			/** Check if body tag exists */
			if ( ! $has_body ) {
				return '<html><head></head><body><div id="body_content">' . $content . '</div></body></html>';
			}

			$pattern     = "/<body(.*?)>(.*?)<\/body>/is";
			$replacement = '<body$1><div id="body_content">$2</div></body>';

			return preg_replace( $pattern, $replacement, $content );
		}

		/**
		 * Check if funnel builder PRO version is active and license is active
		 * @return mixed|void
		 */
		public static function wffn_is_funnel_pro_active() {
			return defined( 'WFFN_PRO_FILE' ) && WFFN_Core()->admin->get_license_status();
		}


		/**
		 * @return string
		 */
		public static function get_wffn_container_attrs() {

			$attributes   = apply_filters( 'wffn_container_attrs', array() );
			$attrs_string = '';

			foreach ( $attributes as $key => $value ) {

				if ( ! $value ) {
					continue;
				}

				if ( true === $value ) {
					$attrs_string .= esc_html( $key ) . ' ';
				} else {
					$attrs_string .= sprintf( '%s=%s ', esc_html( $key ), esc_attr( $value ) );
				}
			}

			return $attrs_string;
		}

		/**
		 * @param $step_id
		 *
		 * @return false|mixed
		 */
		public static function maybe_override_tracking( $step_id ) {
			$funnel = WFFN_Core()->data->get_session_funnel();

			if ( WFFN_Core()->data->has_valid_session() && $step_id > 0 && wffn_is_valid_funnel( $funnel ) ) {
				$steps = $funnel->get_steps();

				$search     = array_search( intval( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
				$is_variant = get_post_meta( $step_id, '_bwf_ab_variation_of', true );

				if ( false !== $search || $is_variant > 0 ) {
					$settings = WFFN_Core()->get_dB()->get_meta( $funnel->get_id(), '_settings' );
					if ( is_array( $settings ) && count( $settings ) > 0 ) {
						return ( isset( $settings['override_tracking_ids'] ) && $settings['override_tracking_ids'] === true ) ? $settings : false;
					}
				}
			}

			return false;
		}

		public static function maybe_wpdb_error( $wpdb ) {
			$status = array(
				'db_error' => false,
			);

			if ( ! empty( $wpdb->last_error ) ) {
				$status = array(
					'db_error'  => true,
					'msg'       => $wpdb->last_error,
					'query'     => $wpdb->last_query,
					'backtrace' => wp_debug_backtrace_summary() //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_wp_debug_backtrace_summary
				);

				WFFN_Core()->logger->log( "Get wpdb last error for query : " . print_r( $status, true ), 'woofunnel-failed-actions', true ); // phpcs:ignore
			}

			return $status;
		}

		/**
		 *  Check if page builder preview mode is showing up.
		 */
		public static function is_page_builder_preview() {

			if ( self::is_elementor_preview_mode() || self::is_divi_builder_preview() || self::is_oxy_builder_preview() || apply_filters( 'wffn_maybe_preview_mode', false ) ) {
				return true;
			}

			return false;
		}

		/**
		 *  Check if page builder preview mode is showing up.
		 */
		public static function is_page_builder_editor() {
			$elementor = WFFN_Common::check_builder_status( 'elementor' );
			if ( true === $elementor['found'] && class_exists( '\Elementor\Plugin' ) && ! is_null( \Elementor\Plugin::instance()->editor ) && \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				return true;
			}

			$divi = WFFN_Common::check_builder_status( 'divi' );
			if ( true === $divi['found'] && isset( $_REQUEST['et_load_builder_modules'] ) && '1' === $_REQUEST['et_load_builder_modules'] ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}

			$oxy = WFFN_Common::check_builder_status( 'oxy' );
			if ( true === $oxy['found'] && isset( $_REQUEST['action'] ) && false !== strpos( $_REQUEST['action'], 'oxy_render' ) ) {//phpcs:ignore
				return true;
			}

			return false;
		}

		/**
		 *  Check if elementor preview mode is showing.
		 */
		public static function is_elementor_preview_mode() {
			if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview instanceof \Elementor\Preview && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				return true;
			}

			return false;
		}


		/**
		 *  Check if divi builder page is showing.
		 */
		public static function is_divi_builder_preview() {

			if ( '1' === filter_input( INPUT_GET, 'et_fb' ) ) {
				return true;
			}

			return false;
		}

		/**
		 *  Check if oxygen builder page is showing.
		 */
		public static function is_oxy_builder_preview() {

			if ( '1' === filter_input( INPUT_GET, 'ct_builder' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Replace the duplicate http from a string.
		 *
		 * @param $string
		 *
		 * @return mixed
		 */
		public static function wffn_correct_protocol_url( $string ) {
			$string = str_replace( 'http://https://', 'https://', $string );
			$string = str_replace( 'https://http://', 'http://', $string );
			$string = str_replace( 'https://https://', 'https://', $string );
			$string = str_replace( 'http://http://', 'http://', $string );

			return $string;
		}

		public static function is_array_assoc( $arr ) {
			if ( array() === $arr ) {
				return false;
			}

			return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
		}

		public static function check_builder_status( $builder = '' ) {
			// Divi Builder Plugin Exists
			$response = [ 'found' => false, 'error' => '', 'is_old_version' => 'no', 'version' => '' ];
			if ( empty( $builder ) ) {
				$response['error'] = __( 'No Builder Specified', 'funnel-builder' );
			} else if ( 'oxy' === $builder ) {
				$supported_version   = '3.7';
				$oxy_exist           = false;
				$oxy_builder_version = '1.0';
				if ( class_exists( 'CT_Component' ) ) {
					$oxy_exist = true;
					if ( defined( 'CT_VERSION' ) ) {
						$oxy_builder_version = CT_VERSION;
					}
				}

				if ( true === $oxy_exist ) {
					$response['found'] = true;
					if ( ! version_compare( $oxy_builder_version, $supported_version, '>=' ) ) {
						$response['is_old_version'] = 'yes';
						$response['version']        = $oxy_builder_version;
						$response['error']          = sprintf( __( 'Site has an older version of Oxygen Builder. Templates are supported for v%s or greater.<br /> Please update.', 'funnel-builder' ), $supported_version );
					}
				}

			} else if ( 'divi' === $builder ) {
				$supported_version    = '4.1';
				$divi_exist           = false;
				$divi_builder_version = 0;
				// Detect Divi Builder Plugin is Active
				if ( class_exists( 'ET_Builder_Plugin' ) ) {
					$divi_exist = true;

					if ( defined( 'ET_BUILDER_PLUGIN_VERSION' ) ) {
						$divi_builder_version = ET_BUILDER_PLUGIN_VERSION;
					}


				} else if ( function_exists( 'et_setup_theme' ) ) { // Detect Theme Active
					$divi_exist = true;
					$theme      = wp_get_theme();
					if ( $theme instanceof WP_Theme ) {
						$parent = $theme->parent();
						if ( $parent instanceof WP_Theme ) {
							$divi_builder_version = $parent->get( 'Version' );
						} else {
							$divi_builder_version = $theme->get( 'Version' );
						}

					}
				}
				// available in Both Theme & Plugin
				if ( 0 === $divi_builder_version && defined( 'ET_BUILDER_PRODUCT_VERSION' ) ) {
					$divi_builder_version = ET_BUILDER_PRODUCT_VERSION;
				}

				//ET_Builder_Plugin
				if ( true === $divi_exist && class_exists( 'ET_Core_Portability' ) ) {
					$response['found']   = true;
					$response['version'] = $divi_builder_version;
					if ( ! version_compare( $divi_builder_version, $supported_version, '>=' ) ) {
						$response['is_old_version'] = 'yes';
						$response['error']          = sprintf( __( 'Site has an older version of Divi Builder. Templates are supported for v%s or greater.<br /> Please update.', 'funnel-builder' ), $supported_version );
					}
				}
			} else if ( 'elementor' ) {
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					$response['found']   = true;
					$response['version'] = ELEMENTOR_VERSION;
				}
			}

			return $response;

		}


		public static function setup_schedule_to_remove_orphaned_transients() {
			if ( false === wp_next_scheduled( 'wffn_remove_orphaned_transients' ) ) {
				wp_schedule_event( time(), 'daily', 'wffn_remove_orphaned_transients' );
			}
		}

		public static function remove_orphaned_transients() {

			if ( ! class_exists( 'WooFunnels_File_Api' ) ) {
				return;
			}

			clearstatcache();
			$file_api = new WooFunnels_File_Api( 'wffn-transient' );

			$woofunnels_core_dir = $file_api->woofunnels_core_dir . '/wffn-transient';
			$dir                 = @opendir( $woofunnels_core_dir . '/' );

			if ( empty( $dir ) ) {
				return;
			}
			$yesdate = strtotime( '-2 hours' );

			self::$start_time = time();
			$i                = 0;
			if ( is_dir( $woofunnels_core_dir ) ) {
				while ( false !== ( $file = @readdir( $dir ) ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition,Generic.PHP.NoSilencedErrors.Forbidden

					if ( $file === '.' || $file === '..' ) {
						continue;
					}
					if ( @filemtime( $woofunnels_core_dir . '/' . '' . $file ) <= $yesdate ) { //phpcs:ignore Generic.PHP.NoSilencedErrors.Forbidden
						$file_api->delete( $woofunnels_core_dir . '/' . '' . $file );
						$i ++;
					}

					if ( true === self::time_exceeded() || true === self::memory_exceeded() ) {
						break;
					}
				}
			}
		}

		/**
		 * Remove logs files before 4 days
		 * @return void
		 */
		public static function remove_wffn_logs_files() {

			if ( ! class_exists( 'WooFunnels_File_Api' ) ) {
				return;
			}

			clearstatcache();
			$file_api = new WooFunnels_File_Api( 'funnel-builder-logs' );

			$woofunnels_core_dir = $file_api->woofunnels_core_dir . '/funnel-builder-logs';
			$dir                 = @opendir( $woofunnels_core_dir . '/' );

			if ( empty( $dir ) ) {
				return;
			}

			/**
			 * delete old woofunnels folder
			 */
			$upload      = wp_upload_dir();
			$folder_path = $upload['basedir'] . '/woofunnels';

			if ( is_dir( $folder_path ) ) {
				$file_api->delete_folder( $folder_path, true );
			}

			$yesdate = strtotime( '-4 day' );

			self::$start_time = time();
			$i                = 0;
			if ( is_dir( $woofunnels_core_dir ) ) {
				while ( false !== ( $file = @readdir( $dir ) ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition,Generic.PHP.NoSilencedErrors.Forbidden

					if ( $file === '.' || $file === '..' ) {
						continue;
					}
					if ( @filemtime( $woofunnels_core_dir . '/' . '' . $file ) <= $yesdate ) { //phpcs:ignore Generic.PHP.NoSilencedErrors.Forbidden
						$file_api->delete( $woofunnels_core_dir . '/' . '' . $file );
						$i ++;
					}

					if ( true === self::time_exceeded() || true === self::memory_exceeded() ) {
						break;
					}
				}
			}
		}

		public static function time_exceeded() {
			$finish = self::$start_time + 20; // 20 seconds
			$return = false;

			if ( time() >= $finish ) {
				$return = true;
			}

			return $return;
		}

		public static function memory_exceeded() {
			$memory_limit   = self::get_memory_limit() * 0.9; // 90% of max memory
			$current_memory = memory_get_usage( true );
			$return         = false;

			if ( $current_memory >= $memory_limit ) {
				$return = true;
			}

			return $return;
		}

		public static function get_memory_limit() {
			if ( function_exists( 'ini_get' ) ) {
				$memory_limit = ini_get( 'memory_limit' );
			} else {
				// Sensible default.
				$memory_limit = '128M';
			}

			if ( ! $memory_limit || - 1 === $memory_limit || '-1' === $memory_limit ) {
				// Unlimited, set to 32GB.
				$memory_limit = '32G';
			}

			return self::convert_hr_to_bytes( $memory_limit ) * 1024 * 1024;
		}

		/**
		 * Converts a shorthand byte value to an integer byte value.
		 *
		 * Wrapper for wp_convert_hr_to_bytes(), moved to load.php in WordPress 4.6 from media.php
		 *
		 * @link https://secure.php.net/manual/en/function.ini-get.php
		 * @link https://secure.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
		 *
		 * @param string $value A (PHP ini) byte value, either shorthand or ordinary.
		 *
		 * @return int An integer byte value.
		 */
		public static function convert_hr_to_bytes( $value ) {
			if ( function_exists( 'wp_convert_hr_to_bytes' ) ) {
				return wp_convert_hr_to_bytes( $value );
			}

			$value = strtolower( trim( $value ) );
			$bytes = (int) $value;

			if ( false !== strpos( $value, 'g' ) ) {
				$bytes *= GB_IN_BYTES;
			} elseif ( false !== strpos( $value, 'm' ) ) {
				$bytes *= MB_IN_BYTES;
			} elseif ( false !== strpos( $value, 'k' ) ) {
				$bytes *= KB_IN_BYTES;
			}

			// Deal with large (float) values which run into the maximum integer size.
			return min( $bytes, PHP_INT_MAX );
		}

		public static function generate_hash_key() {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$hasher = new PasswordHash( 2, false );

			return bin2hex( $hasher->get_random_bytes( 4 ) );
		}

		/**
		 * Get Format for Success Response
		 *
		 * @param $result_array
		 * @param string $message
		 * @param int $response_code
		 *
		 * @return array
		 */
		public static function format_success_response( $result_array, $message = '', $response_code = 200 ) {
			return array(
				'code'    => $response_code,
				'message' => $message,
				'result'  => $result_array,
			);
		}

		/**
		 * @param $templates
		 * update template list data to be compatible with old pro version
		 *
		 * @return array|mixed
		 */
		public static function update_template_list( $templates ) {
			if ( empty( $templates ) || ( defined( 'WFFN_PRO_VERSION' ) && version_compare( WFFN_PRO_VERSION, '2.4.0', '>=' ) ) ) {
				return $templates;
			}
			if ( isset( $templates['divi']['divi_funnel_1']['import_button_text'] ) ) {
				$templates = WooFunnels_Dashboard::get_all_templates( true );
				$templates = isset( $templates['funnel'] ) ? $templates['funnel'] : [];
			}
			if ( isset( $templates['wc_checkout'] ) ) {
				foreach ( $templates['wc_checkout'] as &$checkout_data ) {
					if ( is_array( $checkout_data ) ) {
						foreach ( $checkout_data as &$ch_val ) {
							if ( isset( $ch_val['build_from_scratch'] ) ) {
								$ch_val['build_from_scratch'] = ( true === $ch_val['build_from_scratch'] ) ? 'yes' : 'no';
							}
							$ch_val['import'] = 'yes';
						}
					}
				}
			}
			if ( isset( $templates['upsell'] ) ) {
				foreach ( $templates['upsell'] as $k => &$upsell_data ) {
					if ( 'customizer' !== $k && is_array( $upsell_data ) ) {
						foreach ( $upsell_data as &$up_val ) {
							if ( ! isset( $up_val['build_from_scratch'] ) ) {
								$up_val['preview_url']    = "test_preview";
								$up_val['import_allowed'] = true;
							}
						}
					}
				}
			}

			return $templates;
		}

		/**
		 * update store checkout meta and option key
		 *
		 * @param $funnel_id
		 * @param $status
		 *
		 * @return void
		 */
		public static function update_store_checkout_meta( $funnel_id, $status = 0 ) {
			update_option( '_bwf_global_funnel', $funnel_id, true );
			WFFN_Core()->get_dB()->update_meta( $funnel_id, '_is_global', 'yes' );
			WFFN_Core()->get_dB()->update_meta( $funnel_id, 'status', $status );
		}

		/**
		 *  override checkout id in aero global setting
		 *
		 * @param $funnel_id
		 *
		 * @return true|void
		 */
		public static function override_store_checkout_option( $funnel_id ) {
			$funnel = new WFFN_Funnel( $funnel_id );
			if ( ! $funnel instanceof WFFN_Funnel ) {
				return;
			}
			if ( absint( $funnel->get_id() ) !== WFFN_Common::get_store_checkout_id() ) {
				return;
			}
			if ( is_array( $funnel->get_steps() ) && count( $funnel->get_steps() ) > 0 ) {
				$steps = $funnel->get_steps();
				foreach ( $steps as &$step ) {
					if ( isset( $step['type'] ) && 'wc_checkout' === $step['type'] ) {
						/** restore global substeps */
						$bumps = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );
						if ( is_array( $bumps ) && count( $bumps ) > 0 ) {
							$step['substeps'] = $bumps;
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_is_global_substeps', [] );
						}
					}

				}
				$funnel->set_steps( $steps );
				$funnel->save();

				return true;
			}
		}

		/**
		 * get store checkout substeps
		 *
		 * @param $store_checkout_id
		 *
		 * @return array
		 */
		public static function get_store_checkout_global_substeps( $store_checkout_id ) {
			$bumps = WFFN_Core()->get_dB()->get_meta( $store_checkout_id, '_is_global_substeps' );

			return is_array( $bumps ) ? $bumps : [];
		}

		/**
		 * Save order bump in funnel meta after delete checkout step
		 *
		 * @param $store_checkout_id
		 * @param $bumps
		 *
		 * @return void
		 */
		public static function update_substeps_store_checkout_meta( $store_checkout_id, $bumps ) {
			WFFN_Core()->get_dB()->update_meta( $store_checkout_id, '_is_global_substeps', $bumps );
		}

		/**
		 * Get store checkout funnel id by option value
		 * @return int
		 */
		public static function get_store_checkout_id() {
			$global_funnel_id = get_option( '_bwf_global_funnel', false );
			if ( absint( $global_funnel_id ) > 0 ) {
				return absint( $global_funnel_id );
			}

			return 0;
		}

		/**
		 * Get Store checkout funnel native checkout step slug
		 * @return string
		 */
		public static function store_native_checkout_slug() {
			return 'wc_native';
		}

		/**
		 * Join a string with a natural language conjunction at the end.
		 *
		 */
		public static function natural_language_join( array $list, $conjunction = 'and' ) {
			$last = array_pop( $list );
			if ( $list ) {
				return implode( ', ', $list ) . ' ' . $conjunction . ' ' . $last;
			}

			return $last;
		}

		/**
		 * Create facebook advanced matching data
		 * @return mixed|null
		 */
		public static function pixel_advanced_matching_data() {
			$args = array();

			if ( ! class_exists( 'BWF_Admin_General_Settings' ) ) {
				return $args;
			}

			$advanced_tracking = BWF_Admin_General_Settings::get_instance()->get_option( 'is_fb_advanced_event' );

			if ( ! is_array( $advanced_tracking ) || count( $advanced_tracking ) === 0 || 'yes' !== $advanced_tracking[0] ) {
				return $args;
			}

			$params = self::advanced_matching_data();

			if ( ! is_array( $params ) || 0 === count( $params ) ) {
				return $args;
			}

			foreach ( $params as $key => &$value ) {
				if ( ! empty( $value ) ) {
					$params[ $key ] = WFFN_Common::sanitize_advanced_matching_param( $value, $key );
				}
			}

			return $params;
		}

		/**
		 * Create tiktok advanced matching data
		 * @return mixed|null
		 */
		public static function tiktok_advanced_matching_data() {
			$args = array();

			$params = self::advanced_matching_data();

			if ( ! is_array( $params ) || 0 === count( $params ) ) {
				return $args;
			}

			if ( isset( $params['em'] ) ) {
				$args['sha256_email'] = hash( 'sha256', $params['em'] );
			}
			if ( isset( $params['ph'] ) ) {
				$args['sha256_phone_number'] = hash( 'sha256', $params['ph'] );
			}

			return $args;
		}

		public static function advanced_matching_data() {
			$params = array();

			$user = wp_get_current_user();

			if ( ! empty( $user ) && $user->ID !== 0 ) {
				// get user regular data
				$params['fn']          = $user->get( 'user_firstname' );
				$params['ln']          = $user->get( 'user_lastname' );
				$params['em']          = $user->get( 'user_email' );
				$params['ph']          = $user->get( 'user_phone' );
				$params['external_id'] = $user->ID;
			}

			/**
			 * Add common WooCommerce Advanced Matching params
			 */

			if ( class_exists( 'woocommerce' ) ) {

				if ( ! empty( $user ) && $user->ID !== 0 ) {
					// if first name is not set in regular wp user meta
					if ( empty( $params['fn'] ) ) {
						$params['fn'] = $user->get( 'billing_first_name' );
					}

					// if last name is not set in regular wp user meta
					if ( empty( $params['ln'] ) ) {
						$params['ln'] = $user->get( 'billing_last_name' );
					}

					$params['ph'] = $user->get( 'billing_phone' );
					$params['ct'] = $user->get( 'billing_city' );
					$params['st'] = $user->get( 'billing_state' );

					$params['country'] = $user->get( 'billing_country' );
				}
				/**
				 * Add purchase WooCommerce Advanced Matching params
				 */

				if ( is_order_received_page() ) {

					$order_id = WFFN_Common::get_woo_order_id();
					$order    = wc_get_order( $order_id );

					if ( $order instanceof WC_Order ) {
						$params = array(
							'em'          => $order->get_billing_email(),
							'ph'          => $order->get_billing_phone(),
							'fn'          => $order->get_billing_first_name(),
							'ln'          => $order->get_billing_last_name(),
							'ct'          => $order->get_billing_city(),
							'st'          => $order->get_billing_state(),
							'country'     => $order->get_billing_country(),
							'external_id' => $order->get_customer_id(),
						);
					}

				}

			}

			if ( empty( $params['external_id'] ) && ! empty( $_COOKIE['wffn_flt'] ) ) {
				$params['external_id'] = bwf_clean( $_COOKIE['wffn_flt'] );
			}
			$params = apply_filters( 'wffn_advanced_matching_data', $params );

			if ( ! is_array( $params ) || count( $params ) === 0 ) {
				return array();
			}

			return $params;
		}

		public static function get_woo_order_id() {
			// @codingStandardsIgnoreStart
			if ( isset( $_REQUEST['key'] ) && $_REQUEST['key'] != "" ) {
				$order_key = sanitize_key( $_REQUEST['key'] );
				$order_id  = (int) wc_get_order_id_by_order_key( $order_key );

				return $order_id;
			}
			if ( isset( $_REQUEST['referenceCode'] ) && $_REQUEST['referenceCode'] != "" ) {
				return (int) $_REQUEST['referenceCode'];
			}
			if ( isset( $_REQUEST['ref_venta'] ) && $_REQUEST['ref_venta'] != "" ) {
				return (int) $_REQUEST['ref_venta'];
			}
			if ( ! empty( $_REQUEST['wfty_source'] ) && ! empty( $_REQUEST['order_id'] ) ) {
				return (int) $_REQUEST['order_id'];
			}

			if ( ! empty( $_REQUEST['wcf-order'] ) ) {
				return (int) $_REQUEST['wcf-order'];
			}
			// @codingStandardsIgnoreEnd
			return - 1;
		}

		public static function sanitize_advanced_matching_param( $value, $key ) {
			$value = strtolower( $value );
			if ( $key == 'ph' ) {
				$value = preg_replace( '/\D/', '', $value );
			} elseif ( $key == 'em' ) {
				$value = preg_replace( '/[^a-z0-9._+-@]+/i', '', $value );
			} else {
				// only letters with unicode support
				$value = preg_replace( '/[^\w\p{L}]/u', '', $value );
			}

			return $value;

		}


		/**
		 * Check if automation page is skipped
		 *
		 * @return bool
		 */
		public static function skip_automation_page() {
			$fb_site_options = get_option( 'fb_site_options', [] );
			if ( ! isset( $fb_site_options['skip_automation_page'] ) || 1 !== intval( $fb_site_options['skip_automation_page'] ) ) {
				return false;
			}

			return true;
		}

		public static function wffn_round( $value, $precision = 2 ) {
			if ( ! is_numeric( $value ) ) {
				$value = floatval( $value );
			}

			return round( $value, $precision );
		}


		public static function get_plugin_status( $plugin_init_file ) {
			$plugins = get_plugins();

			if ( ! is_array( $plugins ) || ! isset( $plugins[ $plugin_init_file ] ) ) {
				return 'install';
			}

			if ( ! is_plugin_active( $plugin_init_file ) ) {
				return 'activate';
			}

			if ( isset( $plugins[ $plugin_init_file ] ) ) {
				return 'activated';
			}

			return '';
		}

		public static function install_plugin( $plugin_slug ) {


			$resp = array(
				'status' => false,
				'msg'    => __( 'Unable to install plugin', 'funnel-builder' )
			);

			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			include_once ABSPATH . '/wp-admin/includes/admin.php';
			include_once ABSPATH . '/wp-admin/includes/plugin-install.php';
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
			include_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . '/wp-admin/includes/class-plugin-upgrader.php';

			$api = plugins_api( 'plugin_information', array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'sections' => false,
				),
			) );

			if ( is_wp_error( $api ) ) {
				$resp['msg'] = $api->get_error_message();

				return $resp;
			}

			$upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				$resp['msg'] = $result->get_error_message();

				return $resp;
			}

			if ( is_null( $result ) ) {
				global $wp_filesystem;
				$resp['msg'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'funnel-builder' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
					$resp['msg'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				return $resp;
			}

			$resp = install_plugin_install_status( $api );

			return $resp;
		}

		public static function get_compare_operator_amount( $operand ) {
			$operator = '';
			switch ( $operand ) {
				case 'eq':
					$operator = '=';
					break;
				case 'ge':
					$operator = '>=';
					break;
				case 'le':
					$operator = '<=';
					break;
				case 'gt':
					$operator = '>';
					break;
				case 'lt':
					$operator = '<';
					break;
				default:
					break;

			}

			return $operator;
		}

		public static function get_refs( $all = false, $filter = false ) {
			$refs = array(
				'Facebook'  => [
					'://fb.com',
					'://m.me',
					'messenger.com',
					'facebook.com',
					'l.facebook.com',
					'meta.com',
				],
				'Google'    => [ 'google' ],
				'Instagram' => [
					'instagram.com',
					'l.instagram.com',
					'://ig.me',
				],
				'YouTube'   => [ 'youtube' ],
				'Tiktok'    => [ 'tiktok.com' ],
				'Pinterest' => [ 'pinterest.com' ],
				'SnapChat'  => [ 'snapchat.com' ],
				'Yahoo'     => [ 'yahoo' ],
				'X/Twitter' => [
					'://t.co',
					'twitter.com'
				]


			);

			if ( true === $all ) {
				$refs           = array_merge( [ 'direct' => [] ], $refs );
				$refs['others'] = [];
			}
			if ( true === $filter ) {
				$filters = [];
				foreach ( $refs as $key => $ref ) {
					$filters[ $key ] = ucwords( $key );
				}

				return $filters;
			}

			return $refs;
		}

		/**
		 * Check if wc HPOS custom order table enabled.
		 *
		 * @return bool
		 */
		public static function is_wc_hpos_enabled() {
			return ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && method_exists( '\Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) ? true : false;
		}


		public static function get_step_type( $post_type ) {
			switch ( $post_type ) {
				case 'wfacp_checkout':
					$type = 'wc_checkout';
					break;
				case 'wfocu_offer':
					$type = 'wc_upsells';
					break;
				case 'wfocu_funnel':
					$type = 'wc_upsells';
					break;
				case 'wffn_landing':
					$type = 'landing';
					break;
				case 'wffn_ty':
					$type = 'wc_thankyou';
					break;
				case 'wffn_optin':
					$type = 'optin';
					break;
				case 'wffn_oty':
					$type = 'optin_ty';
					break;
				default:
					$type = $post_type;
			}

			return $type;
		}

	}
}
