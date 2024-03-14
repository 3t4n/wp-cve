<?php
/*
 * Plugin Name: WooCommerce - Store Exporter
 * Plugin URI: https://visser.com.au/woocommerce/plugins/exporter/
 * Description: Export Products, Orders, Users, Categories, Tags and other store details out of WooCommerce into Excel spreadsheets and other simple formatted files (e.g. CSV, TSV, Excel formats including XLS and XLSX, XML, etc.)
 * Version: 2.7.2.1
 * Author: Visser Labs
 * Author URI: https://visser.com.au/solutions/
 * License: GPL2
 *
 * Text Domain: woocommerce-exporter
 * Domain Path: /languages/
 *
 * WC requires at least: 2.3
 * WC tested up to: 8.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WOO_CE_DIRNAME', basename( __DIR__ ) );
define( 'WOO_CE_RELPATH', basename( __DIR__ ) . '/' . basename( __FILE__ ) );
define( 'WOO_CE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOO_CE_PREFIX', 'woo_ce' );
define( 'WOO_CE_VERSION', '2.7.2.1' );

// Turn this on to enable additional debugging options at export time.
if ( ! defined( 'WOO_CE_DEBUG' ) ) {
	define( 'WOO_CE_DEBUG', false );
}

// Avoid conflicts if Store Exporter Deluxe is activated.
require_once WOO_CE_PATH . 'common/common.php';
if ( defined( 'WOO_CD_PREFIX' ) === false ) {
	require_once WOO_CE_PATH . 'includes/functions.php';
}

/**
 * Plugin language support.
 */
function woo_ce_i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-exporter' );
	load_textdomain( 'woocommerce-exporter', WP_LANG_DIR . '/woocommerce-exporter/woocommerce-exporter-' . $locale . '.mo' );
	load_plugin_textdomain( 'woocommerce-exporter', false, plugin_basename( __DIR__ ) . '/languages' );
}
add_action( 'init', 'woo_ce_i18n', 11 );

if ( is_admin() ) {

	/* Start of: WordPress Administration */

	// Register our install script for first time install.
	include_once WOO_CE_PATH . 'includes/install.php';
	register_activation_hook( __FILE__, 'woo_ce_install' );

	/**
     * Initial scripts and export process.
     */
	function woo_ce_admin_init() {

		global $export, $wp_roles;

		$action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );

		$troubleshooting_url = 'https://visser.com.au/documentation/store-exporter-deluxe/troubleshooting/';
		// Now is the time to de-activate Store Exporter if Store Exporter Deluxe is activated.
		if ( defined( 'WOO_CD_PREFIX' ) ) {
			include_once WOO_CE_PATH . 'includes/install.php';
			woo_ce_deactivate_ce();
			return;
		}

		// Set the Plugin debug and logging levels if not already set.
		if ( ! WOO_CE_DEBUG && ! defined( 'WOO_CE_LOGGING' ) ) {
			define( 'WOO_CE_LOGGING', false );
		} elseif ( WOO_CE_DEBUG && ! defined( 'WOO_CE_LOGGING' ) ) {
			define( 'WOO_CE_LOGGING', true );
		}

		// An effort to reduce the memory load at export time.
		if ( 'export' !== $action ) {

			// Check the User has the activate_plugins capability.
			$user_capability = 'activate_plugins';
			if ( current_user_can( $user_capability ) ) {

				// Detect if another e-Commerce platform is activated.
				if ( ! woo_is_woo_activated() && ( woo_is_jigo_activated() || woo_is_wpsc_activated() ) ) {
					$message  = __( 'We have detected another e-Commerce Plugin than WooCommerce activated, please check that you are using Store Exporter for the correct platform.', 'woocommerce-exporter' );
					$message .= sprintf( ' <a href="%s" target="_blank">%s</a>', $troubleshooting_url . '?utm_source=wse&utm_medium=errornotice&utm_campaign=notwoohelplink', __( 'Need help?', 'woocommerce-exporter' ) );
					woo_ce_admin_notice( $message, 'error', 'plugins.php' );
				} elseif ( ! woo_is_woo_activated() ) {
					$message  = __( 'We have been unable to detect the WooCommerce Plugin activated on this WordPress site, please check that you are using Store Exporter for the correct platform.', 'woocommerce-exporter' );
					$message .= sprintf( ' <a href="%s" target="_blank">%s</a>', $troubleshooting_url . '?utm_source=wse&utm_medium=errornotice&utm_campaign=woonotfoundhelplink', __( 'Need help?', 'woocommerce-exporter' ) );
					woo_ce_admin_notice( $message, 'error', 'plugins.php' );
				}

				// Detect if any known conflict Plugins are activated.

				// WooCommerce Subscriptions Exporter - http://codecanyon.net/item/woocommerce-subscription-exporter/6569668.
				if ( function_exists( 'wc_subs_exporter_admin_init' ) ) {
					$message  = __( 'We have detected an activated Plugin for WooCommerce that is known to conflict with Store Exporter, please de-activate WooCommerce Subscriptions Exporter to resolve export issues within Store Exporter.', 'woocommerce-exporter' );
					$message .= sprintf( '<a href="%s" target="_blank">%s</a>', $troubleshooting_url . '?utm_source=wse&utm_medium=errornotice&utm_campaign=subscriptionexporterconflicthelplink', __( 'Need help?', 'woocommerce-exporter' ) );
					woo_ce_admin_notice( $message, 'error', array( 'plugins.php', 'admin.php' ) );
				}

				// WP Easy Events Professional - https://emdplugins.com/plugins/wp-easy-events-professional/.
				if ( class_exists( 'WP_Easy_Events_Professional' ) ) {
					$message  = __( 'We have detected an activated Plugin that is known to conflict with Store Exporter Deluxe, please de-activate WP Easy Events Professional to resolve export issues within Store Exporter Deluxe.', 'woocommerce-exporter' );
					$message .= sprintf( '<a href="%s" target="_blank">%s</a>', $troubleshooting_url . '?utm_source=wse&utm_medium=errornotice&utm_campaign=wpeasyeventsconflicthelplink', __( 'Need help?', 'woocommerce-exporter' ) );
					woo_ce_admin_notice( $message, 'error', array( 'plugins.php', 'admin.php' ) );
				}

				// Plugin row notices for the Plugins screen.
				add_action( 'after_plugin_row_' . WOO_CE_RELPATH, 'woo_ce_admin_plugin_row' );

			}

			// Check the User has the view_woocommerce_reports capability.
			$user_capability = apply_filters( 'woo_ce_admin_user_capability', 'view_woocommerce_reports' );
			if ( current_user_can( $user_capability ) === false ) {
				return;
            }

			// Check that we are on the Store Exporter screen.
			$page = ( isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : false ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( strtolower( WOO_CE_PREFIX ) !== $page ) {
				return;
            }

			// Add memory usage to the screen footer of the WooCommerce > Store Export screen.
			add_filter( 'admin_footer_text', 'woo_ce_admin_footer_text' );

			woo_ce_export_init();

		}

		// Process any pre-export notice confirmations.
		switch ( $action ) {

			// This is where the magic happens.
			case 'export':
				// Make sure we play nice with other WooCommerce and WordPress exporters.
				if ( ! isset( $_POST['woo_ce_export'] ) ) {
					return;
                }

				check_admin_referer( 'manual_export', 'woo_ce_export' );

				// Hide error logging during the export process.
				if ( function_exists( 'ini_set' ) ) {
					@ini_set( 'display_errors', 0 ); // phpcs:ignore
                }

				// Welcome in the age of GZIP compression and Object caching.
				if ( ! defined( 'DONOTCACHEPAGE' ) ) {
					define( 'DONOTCACHEPAGE', true );
                }
				if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
					define( 'DONOTCACHCEOBJECT', true );
                }

				// Set artificially high because we are building this export in memory.
				if ( function_exists( 'wp_raise_memory_limit' ) ) {
					add_filter( 'export_memory_limit', 'woo_ce_raise_export_memory_limit' );
					wp_raise_memory_limit( 'export' );
				}

				$timeout   = woo_ce_get_option( 'timeout', 0 );
				$safe_mode = ( function_exists( 'safe_mode' ) ? ini_get( 'safe_mode' ) : false );
				if ( ! $safe_mode ) {
					// Double up, why not.
					if ( function_exists( 'set_time_limit' ) ) {
						@set_time_limit( $timeout ); // phpcs:ignore
                    }
					if ( function_exists( 'ini_set' ) ) {
						@ini_set( 'max_execution_time', $timeout ); // phpcs:ignore
                    }
				}
				if ( function_exists( 'ini_set' ) ) {
					@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT ); // phpcs:ignore
                }

				// Set up the basic export options.
				$export                    = new stdClass();
				$export->cron              = 0;
				$export->scheduled_export  = 0;
				$export->start_time        = time();
				$export->idle_memory_start = woo_ce_current_memory_usage();
				$export->encoding          = woo_ce_get_option( 'encoding', get_option( 'blog_charset', 'UTF-8' ) );
				// Reset the Encoding if corrupted.
				if ( '' === $export->encoding || false === $export->encoding || 'System default' === $export->encoding ) {
					$message = __( 'Encoding export option was corrupted, defaulted to UTF-8', 'woocommerce-exporter' );
					woo_ce_error_log( sprintf( 'Warning: %s', $message ) );
					$export->encoding = 'UTF-8';
					woo_ce_update_option( 'encoding', 'UTF-8' );
				}
				$export->delimiter = woo_ce_get_option( 'delimiter', ',' );
				// Reset the Delimiter if corrupted.
				if ( '' === $export->delimiter || false === $export->delimiter ) {
					$message = __( 'Delimiter export option was corrupted, defaulted to ,', 'woocommerce-exporter' );
					woo_ce_error_log( sprintf( 'Warning: %s', $message ) );
					$export->delimiter = ',';
					woo_ce_update_option( 'delimiter', ',' );
				}
				$export->category_separator = woo_ce_get_option( 'category_separator', '|' );
				// Reset the Category Separator if corrupted.
				if ( '' === $export->category_separator || false === $export->category_separator ) {
					$message = __( 'Category Separator export option was corrupted, defaulted to |', 'woocommerce-exporter' );
					woo_ce_error_log( sprintf( 'Warning: %s', $message ) );
					$export->category_separator = '|';
					woo_ce_update_option( 'category_separator', '|' );
				}
				$export->bom               = woo_ce_get_option( 'bom', 1 );
				$export->escape_formatting = woo_ce_get_option( 'escape_formatting', 'all' );
				// Reset the Escape Formatting if corrupted.
				if ( '' === $export->escape_formatting || false === $export->escape_formatting ) {
					$message = __( 'Escape Formatting export option was corrupted, defaulted to all.', 'woocommerce-exporter' );
					woo_ce_error_log( sprintf( 'Warning: %s', $message ) );
					$export->escape_formatting = 'all';
					woo_ce_update_option( 'escape_formatting', 'all' );
				}
				$date_format = woo_ce_get_option( 'date_format', 'd/m/Y' );
				// Reset the Date Format if corrupted.
				if ( '1' === $date_format || '' === $date_format || false === $date_format ) {
					$message = __( 'Date Format export option was corrupted, defaulted to d/m/Y', 'woocommerce-exporter' );
					woo_ce_error_log( sprintf( 'Warning: %s', $message ) );
					$date_format = 'd/m/Y';
					woo_ce_update_option( 'date_format', $date_format );
				}

				// Save export option changes made on the Export screen.
				$export->limit_volume = ( isset( $_POST['limit_volume'] ) ? sanitize_text_field( $_POST['limit_volume'] ) : '' );
				woo_ce_update_option( 'limit_volume', $export->limit_volume );
				if ( in_array( $export->limit_volume, array( '', '0', '-1' ), true ) ) {
					woo_ce_update_option( 'limit_volume', '' );
					$export->limit_volume = -1;
				}
				$export->offset = ( isset( $_POST['offset'] ) ? sanitize_text_field( $_POST['offset'] ) : '' );
				woo_ce_update_option( 'offset', $export->offset );
				if ( in_array( $export->offset, array( '', '0' ), true ) ) {
					woo_ce_update_option( 'offset', '' );
					$export->offset = 0;
				}
				$export->type = ( isset( $_POST['dataset'] ) ? sanitize_text_field( $_POST['dataset'] ) : false );

				// Set default values for all export options to be later passed onto the export process.
				$export->fields        = array();
				$export->fields_order  = false;
				$export->export_format = 'csv';

				// Product sorting.
				$export->product_category     = false;
				$export->product_tag          = false;
				$export->product_status       = false;
				$export->product_type         = false;
				$export->product_orderby      = false;
				$export->product_order        = false;
				$export->gallery_formatting   = false;
				$export->upsell_formatting    = false;
				$export->crosssell_formatting = false;

				// Category sorting.
				$export->category_orderby = false;
				$export->category_order   = false;

				// Tag sorting.
				$export->tag_orderby = false;
				$export->tag_order   = false;

				// User sorting.
				$export->user_orderby = false;
				$export->user_order   = false;

				if ( ! empty( $export->type ) ) {
					$export->fields       = ( isset( $_POST[ $export->type . '_fields' ] ) ? array_map( 'sanitize_text_field', $_POST[ $export->type . '_fields' ] ) : false );
					$export->fields_order = ( isset( $_POST[ $export->type . '_fields_order' ] ) ? array_map( 'absint', $_POST[ $export->type . '_fields_order' ] ) : false );
					woo_ce_update_option( 'last_export', $export->type );
				}

				woo_ce_load_export_types();

				switch ( $export->type ) {

					case 'product':
						// Set up dataset specific options.
						$export->product_category     = ( isset( $_POST['product_filter_category'] ) ? woo_ce_format_product_filters( array_map( 'absint', $_POST['product_filter_category'] ) ) : false );
						$export->product_tag          = ( isset( $_POST['product_filter_tag'] ) ? woo_ce_format_product_filters( array_map( 'absint', $_POST['product_filter_tag'] ) ) : false );
						$export->product_status       = ( isset( $_POST['product_filter_status'] ) ? woo_ce_format_product_filters( array_map( 'sanitize_text_field', $_POST['product_filter_status'] ) ) : false );
						$export->product_type         = ( isset( $_POST['product_filter_type'] ) ? woo_ce_format_product_filters( array_map( 'sanitize_text_field', $_POST['product_filter_type'] ) ) : false );
						$export->product_orderby      = ( isset( $_POST['product_orderby'] ) ? sanitize_text_field( $_POST['product_orderby'] ) : false );
						$export->product_order        = ( isset( $_POST['product_order'] ) ? sanitize_text_field( $_POST['product_order'] ) : false );
						$export->gallery_formatting   = ( isset( $_POST['product_gallery_formatting'] ) ? absint( $_POST['product_gallery_formatting'] ) : false );
						$export->upsell_formatting    = ( isset( $_POST['product_upsell_formatting'] ) ? absint( $_POST['product_upsell_formatting'] ) : false );
						$export->crosssell_formatting = ( isset( $_POST['product_crosssell_formatting'] ) ? absint( $_POST['product_crosssell_formatting'] ) : false );

						// Save dataset export specific options.
						if ( woo_ce_get_option( 'product_orderby' ) !== $export->product_orderby ) {
							woo_ce_update_option( 'product_orderby', $export->product_orderby );
                        }
						if ( woo_ce_get_option( 'product_order' ) !== $export->product_order ) {
							woo_ce_update_option( 'product_order', $export->product_order );
                        }
						if ( woo_ce_get_option( 'upsell_formatting' ) !== $export->upsell_formatting ) {
							woo_ce_update_option( 'upsell_formatting', $export->upsell_formatting );
                        }
						if ( woo_ce_get_option( 'crosssell_formatting' ) !== $export->crosssell_formatting ) {
							woo_ce_update_option( 'crosssell_formatting', $export->crosssell_formatting );
                        }
						break;

					case 'category':
						// Set up dataset specific options.
						$export->category_orderby = ( isset( $_POST['category_orderby'] ) ? sanitize_text_field( $_POST['category_orderby'] ) : false );
						$export->category_order   = ( isset( $_POST['category_order'] ) ? sanitize_text_field( $_POST['category_order'] ) : false );

						// Save dataset export specific options.
						if ( woo_ce_get_option( 'category_orderby' ) !== $export->category_orderby ) {
							woo_ce_update_option( 'category_orderby', $export->category_orderby );
                        }
						if ( woo_ce_get_option( 'category_order' ) !== $export->category_order ) {
							woo_ce_update_option( 'category_order', $export->category_order );
                        }
						break;

					case 'tag':
						// Set up dataset specific options.
						$export->tag_orderby = ( isset( $_POST['tag_orderby'] ) ? sanitize_text_field( $_POST['tag_orderby'] ) : false );
						$export->tag_order   = ( isset( $_POST['tag_order'] ) ? sanitize_text_field( $_POST['tag_order'] ) : false );

						// Save dataset export specific options.
						if ( woo_ce_get_option( 'tag_orderby' ) !== $export->tag_orderby ) {
							woo_ce_update_option( 'tag_orderby', $export->tag_orderby );
                        }
						if ( woo_ce_get_option( 'tag_order' ) !== $export->tag_order ) {
							woo_ce_update_option( 'tag_order', $export->tag_order );
                        }
						break;

					case 'user':
						// Set up dataset specific options.
						$export->user_orderby = ( isset( $_POST['user_orderby'] ) ? sanitize_text_field( $_POST['user_orderby'] ) : false );
						$export->user_order   = ( isset( $_POST['user_order'] ) ? sanitize_text_field( $_POST['user_order'] ) : false );

						// Save dataset export specific options.
						if ( woo_ce_get_option( 'user_orderby' ) !== $export->user_orderby ) {
							woo_ce_update_option( 'user_orderby', $export->user_orderby );
                        }
						if ( woo_ce_get_option( 'user_order' ) !== $export->user_order ) {
							woo_ce_update_option( 'user_order', $export->user_order );
                        }
						break;
				}

				if ( $export->type ) {

					$timeout = 600;
					if ( isset( $_POST['timeout'] ) ) {
						$timeout = absint( $_POST['timeout'] );
						if ( woo_ce_get_option( 'timeout' ) !== $timeout ) {
							woo_ce_update_option( 'timeout', $timeout );
                        }
					}
					if ( ! ini_get( 'safe_mode' ) ) {
						@set_time_limit( $timeout ); // phpcs:ignore
						@ini_set( 'max_execution_time', $timeout ); // phpcs:ignore
					}

					@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT ); // phpcs:ignore

					$export->args = array(
						'limit_volume'     => $export->limit_volume,
						'offset'           => $export->offset,
						'encoding'         => $export->encoding,
						'date_format'      => $date_format,
						'product_category' => $export->product_category,
						'product_tag'      => $export->product_tag,
						'product_status'   => $export->product_status,
						'product_type'     => $export->product_type,
						'product_orderby'  => $export->product_orderby,
						'product_order'    => $export->product_order,
						'category_orderby' => $export->category_orderby,
						'category_order'   => $export->category_order,
						'tag_orderby'      => $export->tag_orderby,
						'tag_order'        => $export->tag_order,
						'user_orderby'     => $export->user_orderby,
						'user_order'       => $export->user_order,
					);
					$export->args = apply_filters( 'woo_ce_extend_dataset_args', $export->args, $export->type );

					if ( empty( $export->fields ) ) {
						if ( function_exists( sprintf( 'woo_ce_get_%s_fields', $export->type ) ) ) {
							$export->fields = call_user_func_array( 'woo_ce_get_' . $export->type . '_fields', array( 'summary' ) );
							$message        = __( 'No export fields were selected, defaulted to include all fields for this export type.', 'woocommerce-exporter' );
							woo_ce_admin_notice( $message, 'notice' );
						} else {
							$message = __( 'No export fields were selected, please try again with at least a single export field.', 'woocommerce-exporter' );
							woo_ce_admin_notice( $message, 'error' );
							return;
						}
					}
					woo_ce_save_fields( $export->type, $export->fields, $export->fields_order );

					if ( 'csv' === $export->export_format ) {
						$export->filename = woo_ce_generate_csv_filename( $export->type );
					}

					// Print file contents to debug export screen.
					if ( WOO_CE_DEBUG ) {

						if ( in_array( $export->export_format, array( 'csv' ), true ) ) {
							woo_ce_export_dataset( $export->type );
						}
						$export->idle_memory_end = woo_ce_current_memory_usage();
						$export->end_time        = time();

					// Print file contents to browser.
					} else {

						// Hide welcome notices after the first export.
						if ( ! woo_ce_get_option( 'dismiss_quick_export_prompt', 0 ) ) {
							woo_ce_update_option( 'dismiss_quick_export_prompt', 1 );
                        }
						if ( ! woo_ce_get_option( 'dismiss_overview_prompt', 0 ) ) {
							woo_ce_update_option( 'dismiss_overview_prompt', 1 );
                        }

						// Show the upgrade notice after the first export.
						if (
							! woo_ce_get_option( 'show_upgrade_prompt', 0 ) &&
							! woo_ce_get_option( 'dismiss_upgrade_prompt', 0 )
						) {
							woo_ce_update_option( 'show_upgrade_prompt', 1 );
						}

						if ( in_array( $export->export_format, array( 'csv' ), true ) ) {

							// Generate CSV contents.
							$bits = woo_ce_export_dataset( $export->type );
							unset( $export->fields );
							if ( empty( $bits ) ) {
								$message = __( 'No export entries were found, please try again with different export filters.', 'woocommerce-exporter' );
								woo_ce_admin_notice( $message, 'error' );
								return;
							}
							if ( woo_ce_get_option( 'delete_file', 1 ) ) {

								// Print directly to browser.
								if ( 'csv' === $export->export_format ) {
									woo_ce_generate_csv_header( $export->type );
                                }
								echo $bits; // phpcs:ignore
								exit();

							} elseif ( $export->filename && $bits ) {
                                if ( 'csv' === $export->export_format ) {
                                    $post_ID = woo_ce_save_file_attachment( $export->filename, 'text/csv' );
                                }
                                $upload = wp_upload_bits( $export->filename, null, $bits );
                                if ( false === $post_ID || $upload['error'] ) {
                                    wp_delete_attachment( $post_ID, true );
                                    if ( isset( $upload['error'] ) ) {
                                        wp_safe_redirect(
                                            esc_url(
                                                add_query_arg(
                                                    array(
                                                        'failed'  => true,
                                                        'message' => urlencode( $upload['error'] ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode
                                                    )
                                                )
                                            )
                                        );
                                    } else {
                                        wp_safe_redirect( esc_url( add_query_arg( array( 'failed' => true ) ) ) );
                                    }
                                    return;
                                }
                                $attach_data = wp_generate_attachment_metadata( $post_ID, $upload['file'] );
                                wp_update_attachment_metadata( $post_ID, $attach_data );
                                update_attached_file( $post_ID, $upload['file'] );
                                if ( $post_ID ) {
                                    woo_ce_save_file_guid( $post_ID, $export->type, $upload['url'] );
                                    woo_ce_save_file_details( $post_ID );
                                }
                                $export_type = $export->type;
                                unset( $export );

                                // The end memory usage and time is collected at the very last opportunity prior to the CSV header being rendered to the screen.
                                woo_ce_update_file_detail( $post_ID, '_woo_idle_memory_end', woo_ce_current_memory_usage() );
                                woo_ce_update_file_detail( $post_ID, '_woo_end_time', time() );

                                // Generate CSV header.
                                woo_ce_generate_csv_header( $export_type );
                                unset( $export_type );

                                // Print file contents to screen.
                                if ( $upload['file'] ) {
                                    readfile( $upload['file'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
                                } else {
                                    $url = add_query_arg( 'failed', true );
                                    wp_safe_redirect( $url );
                                }
                                unset( $upload );
                            } else {
                                $url = add_query_arg( 'failed', true );
                                wp_safe_redirect( $url );
                            }
                        }
						exit();
					}
				}
				break;

			// Save changes on Settings screen.
			case 'save-settings':
				// We need to verify the nonce.
				if ( ! empty( $_POST ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'woo_ce_save_settings' ) ) {
					if ( check_admin_referer( 'woo_ce_save_settings' ) ) {
						woo_ce_export_settings_save();
                    }
				}
				break;

			// Save changes on Field Editor screen.
			case 'save-fields':
				// We need to verify the nonce.
				if ( ! empty( $_POST ) && check_admin_referer( 'save_fields', 'woo_ce_save_fields' ) ) {
					$fields       = ( isset( $_POST['fields'] ) ? array_filter( $_POST['fields'] ) : array() );
					$export_type  = ( isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '' );
					$export_types = array_keys( woo_ce_get_export_types() );
					// Check we are saving against a valid export type.
					if ( in_array( $export_type, $export_types, true ) ) {
						woo_ce_update_option( $export_type . '_labels', $fields );
						$message = __( 'Field labels have been saved.', 'woocommerce-exporter' );
						woo_ce_admin_notice( $message );
					} else {
						$message = __( 'Changes could not be saved as we could not detect a valid export type. Raise this as a Premium Support issue and include what export type you were editing.', 'woocommerce-exporter' );
						woo_ce_admin_notice( $message, 'error' );
					}
				}
				break;

		}
	}
	add_action( 'admin_init', 'woo_ce_admin_init', 11 );

	/**
     * HTML templates and form processor for Store Exporter screen.
     */
	function woo_ce_html_page() {

		// Check the User has the view_woocommerce_reports capability.
		$user_capability = apply_filters( 'woo_ce_admin_user_capability', 'view_woocommerce_reports' );
		if ( current_user_can( $user_capability ) === false ) {
			return;
        }

		global $wpdb, $export;

		$title = apply_filters( 'woo_ce_template_header', __( 'Store Exporter', 'woocommerce-exporter' ) );
		woo_ce_template_header( $title );
		$action = ( function_exists( 'woo_get_action' ) ? woo_get_action() : false );
		switch ( $action ) {

			case 'export':
				if ( WOO_CE_DEBUG ) {
                    $export_log = get_transient( WOO_CE_PREFIX . '_debug_log' );
					if ( false === $export_log ) {
						$export_log = __( 'No export entries were found within the debug Transient, please try again with different export filters.', 'woocommerce-exporter' );
					} else {
						// We take the contents of our WordPress Transient and de-base64 it back to CSV format.
						$export_log = base64_decode( $export_log ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
					}
					delete_transient( WOO_CE_PREFIX . '_debug_log' );

                    // translators: %s: Export filename.
					$output = '<h3>' . sprintf( __( 'Export Details: %s', 'woocommerce-exporter' ), esc_attr( $export->filename ) ) . '</h3>
                        <p>' . __( 'This prints the $export global that contains the different export options and filters to help reproduce this on another instance of WordPress. Very useful for debugging blank or unexpected exports.', 'woocommerce-exporter' ) . '</p>
                        <textarea id="export_log">' . esc_textarea( print_r( $export, true ) ) . '</textarea><hr />'; // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

                    if ( in_array( $export->export_format, array( 'csv' ), true ) ) {
                        $output .= '<script type="text/javascript">
                            $j(function() {
                                $j(\'#export_sheet\').CSVToTable(\'\', {
                                    startLine: 0
                                });
                            });
                        </script>
                        <h3>' . __( 'Export', 'woocommerce-exporter' ) . '</h3>
                        <p>' . __( 'We use the <a href="http://code.google.com/p/jquerycsvtotable/" target="_blank"><em>CSV to Table plugin</em></a> to see first hand formatting errors or unexpected values within the export file.', 'woocommerce-exporter' ) . '</p>
                        <div id="export_sheet">' . esc_textarea( $export_log ) . '</div>
                        <p class="description">' . __( 'This jQuery plugin can fail with <code>\'Item count (#) does not match header count\'</code> notices which simply mean the number of headers detected does not match the number of cell contents.', 'woocommerce-exporter' ) . '</p>
                        <hr />';
                    }

                    $output .= '<h3>' . __( 'Export Log', 'woocommerce-exporter' ) . '</h3>
                        <p>' . __( 'This prints the raw export contents and is helpful when the jQuery plugin above fails due to major formatting errors.', 'woocommerce-exporter' ) . '</p>
                        <textarea id="export_log" wrap="off">' . esc_textarea( $export_log ) . '</textarea>
                        <hr />';
					echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

				woo_ce_manage_form();
				break;

			case 'update':
				woo_ce_admin_custom_fields_save();

				$message = __( 'Custom field changes saved. You can now select those additional fields from the Export Fields list.', 'woocommerce-exporter' );
				woo_ce_admin_notice_html( $message );
				woo_ce_manage_form();
				break;

			default:
				woo_ce_manage_form();
				break;

		}
		woo_ce_template_footer();
	}

	/**
     * HTML template for Export screen.
     */
	function woo_ce_manage_form() {

		$tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : false ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// If Skip Overview is set then jump to Export screen.
		if ( false === $tab && woo_ce_get_option( 'skip_overview', false ) ) {
			$tab = 'export';
        }

		// Check that WC() is available.
		if ( ! function_exists( 'WC' ) ) {
			$message = __( 'We couldn\'t load the WooCommerce resource WC(), check that WooCommerce is installed and active. If this persists get in touch with us.', 'woocommerce-exporter' );
			woo_ce_admin_notice_html( $message, 'error' );
			return;
		}

		woo_ce_load_export_types();
		woo_ce_admin_fail_notices();

		include_once WOO_CE_PATH . 'templates/admin/tabs.php';
	}

	/* End of: WordPress Administration */

}

/**
 * Declare compatibility with WooCommerce HPOS.
 *
 * @since 2.7.1
 */
function woo_ce_declare_hpos_compatibility() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
}
add_action( 'before_woocommerce_init', 'woo_ce_declare_hpos_compatibility' );
