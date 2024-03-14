<?php
 /*

 * Plugin Name: DSGVO All in one for WP

 * Version: 4.5

 * Plugin URI: http://www.dsgvo-for-wp.com

 * Description: Cookie Notice - Halten Sie Ihre WordPress Website DSGVO konform. Alles über ein Plugin - einfache Handhabung. Macht viele externe Dienste DSGVO konform nutzbar.

 * Author: Michael Leithold

 * Author URI: http://www.mlfactory.de

 * Requires at least: 4.5

 * Tested up to: 6.4

 * License: GPLv2

 * Text Domain: dsgvo-all-in-one-for-wp

 * Domain Path: /languages

 *

*/

if ( ! defined( 'WPINC' ) ) {

	die;

}

add_action( 'plugins_loaded', 'dsgvoaio_loaded_textdomain');

function dsgvoaio_loaded_textdomain(){

    $loadfiles = load_plugin_textdomain('dsgvo-all-in-one-for-wp', false, 

    dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

class dsdvo_wp_backend {

    public static function init() {
		
		add_action( 'admin_menu', __CLASS__ . '::dsgvo_aio_admin_menu' );

		add_action('admin_enqueue_scripts', __CLASS__ . '::dsgvo_aio_load_admin_css');

		add_action('wp_ajax_dsgvo_delete_usr_ip', __CLASS__ . '::dsgvo_ajax_remove_usr_ip');

		add_action( 'wp_ajax_reset_policy_service', __CLASS__ .'::dsgvo_reset_policy_service_func' );

		add_action( 'wp_ajax_dsgvoaio_export_log', __CLASS__ .'::dsgvoaio_export_log' );

		add_action( 'wp_ajax_dsgvoaio_write_log', __CLASS__ .'::dsgvoaio_write_log' );
		
		add_action( 'wp_ajax_nopriv_dsgvoaio_write_log', __CLASS__ .'::dsgvoaio_write_log' );
				
		add_action( 'wp_ajax_dsgvoaio_get_service_policy', __CLASS__ .'::dsgvoaio_get_service_policy' );

		add_action( 'wp_ajax_nopriv_dsgvoaio_get_service_policy', __CLASS__ .'::dsgvoaio_get_service_policy' );		

		add_action('wp_ajax_dsgvoaio_dismiss_cache_msg', __CLASS__ . '::dsgvoaio_dismiss_cache_msg');

		add_action( 'wp_ajax_nopriv_dsgvoaio_dismiss_cache_msg', __CLASS__ . '::dsgvoaio_dismiss_cache_msg' );		

		add_action( 'admin_init', __CLASS__ .'::dsgvoaio_check_autoptimize' );

		add_action( 'admin_init', __CLASS__ . '::dsgvoaiofree_process_settings_export' );
		
		add_action( 'admin_init', __CLASS__ . '::dsgvoaiofree_process_settings_import' );		
		
		add_action( 'admin_notices', __CLASS__ . '::dsgvoaiofree_settings_import_success' );
		
		add_action( 'wp_ajax_dsgvoaiofree_dismissed_notice_handler_import', __CLASS__ . '::dsgvoaiofree_dismissed_notice_handler_import' );	

		add_action( 'wp_ajax_dsgvoaiofree_delete_log_full', __CLASS__ .'::dsgvoaiofree_delete_log_full' );

		add_action( 'wp_ajax_reset_layertext_service', __CLASS__ .'::dsgvoaiofree_reset_layertext_service' );

		add_action( 'admin_init', __CLASS__ . '::dsgvoaiofree_update_policy_changes' );		

		if ( function_exists( 'register_block_type' ) ) {
			
			include( plugin_dir_path(__FILE__ )."/core/inc/blocks.php");	
			
		}		
	}
	
	
	public static function dsgvoaiofree_update_policy_changes() {
		
		include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");
		
		$fbpolicyupdated = get_option('dsgvo_fb_policy_updated', 'false');
		
		if ($fbpolicyupdated == 'false') {
			
			update_option('dsdvo_facebook_policy', $facebook_policy_sample, false);
			
			update_option('dsdvo_facebook_policy_en', $facebook_policy_sample_en, false);
			
			update_option('dsdvo_facebook_policy_it', $facebook_policy_sample_it, false);
			
			update_option('dsgvo_fb_policy_updated', 'true', false);
			
		}
		$fastcounterpolicyupdated = get_option('dsgvo_fastcounter_policy_updated', 'false');
		
		if ($fastcounterpolicyupdated == 'false') {
			
			update_option('dsdvo_fastcounter_policy', $fastcounter_policy_sample, false);
			
			update_option('dsdvo_fastcounter_policy_en', $fastcounter_policy_sample_en, false);
			
			update_option('dsdvo_fastcounter_policy_it', $fastcounter_policy_sample_it, false);
			
			update_option('dsgvo_fastcounter_policy_updated', 'true', false);
			
		}		
		
		$piwikpolicyupdated = get_option('dsgvo_piwik_policy_updated', 'false');
		
		if ($piwikpolicyupdated == 'false') {
			
			update_option('dsdvo_piwik_policy', $matomo_policy_sample, false);
			
			update_option('dsdvo_piwik_policy_en', $matomo_policy_sample_en, false);
			
			update_option('dsdvo_piwik_policy_it', $matomo_policy_sample_it, false);
			
			update_option('dsgvo_piwik_policy_updated', 'true', false);
			
			
		}		
	}	
	

	public static function dsgvoaiofree_dismissed_notice_handler_import() {

		update_option( 'dismissed-dsgvo_msg_import', TRUE );

	}
	
		
	public static function dsgvoaiofree_process_settings_export() {

		if( empty( $_POST['dsgvoaiofree_action'] ) || 'export_settings' != $_POST['dsgvoaiofree_action'] )
			return;

		if( ! wp_verify_nonce( $_POST['dsgvoaiofree_export_nonce'], 'dsgvoaiofree_export_nonce' ) )
			return;

		if( ! current_user_can( 'manage_options' ) )
			return;
		
		include(dirname(__FILE__).'/core/inc/exporter_fetch_datas.php');

		ignore_user_abort( true );

		nocache_headers();
		
		header( 'Content-Type: application/json; charset=utf-8' );
		
		header( 'Content-Disposition: attachment; filename=dsgvoaio-free-settings-export-' . date( 'm-d-Y' ) . '.json' );
		
		header( "Expires: 0" );

		echo json_encode( $settings );
		
		exit;
	}


	public static function dsgvoaiofree_process_settings_import() {
	
		if( empty( $_POST['dsgvoaiofree_action'] ) || 'import_settings' != $_POST['dsgvoaiofree_action'] )
			return;
		
		if( ! wp_verify_nonce( $_POST['dsgvoaiofree_import_nonce'], 'dsgvoaiofree_import_nonce' ) )
			return;

		if( ! current_user_can( 'manage_options' ) )
			return;

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );

		if( $extension != 'json' ) {
			
			wp_die( __( 'Please upload a .json file', 'dsgvo-all-in-one-for-wp' ) );
			
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if( empty( $import_file ) ) {
			
			wp_die( __( 'Please select a file', 'dsgvo-all-in-one-for-wp' ) );
			
		}
		
		$settings = json_decode( file_get_contents( $import_file ), true );
	
		if (isset($settings)) {
			
			foreach($settings as $optionname => $optionvalue) {
				
				update_option($optionname, $optionvalue, false);
				
			}
			
		}
		
		update_option( 'dismissed-dsgvo_msg_import', FALSE );

		wp_safe_redirect( admin_url( 'admin.php?page=dsgvoaio-free-settings-page&parm=importsuccess' ) ); exit;

	}	


	public static function dsgvoaiofree_reset_layertext_service() {
		
		if (isset($_POST['service'])) {
			
			include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");		
			
			if ($_POST['service'] == "vgwort") {
				
				update_option("dsdvo_vgwort_layer", $vgwort_layer_sample, false);
				
				update_option("dsdvo_vgwort_layer_en", $vgwort_layer_sample_en, false);
				
				update_option("dsdvo_vgwort_layer_it", $vgwort_layer_sample_it, false);
				
			}		

			if ($_POST['service'] == "youtube") {
				
				update_option("dsdvo_youtube_layer", $youtube_layer_sample, false);
				
				update_option("dsdvo_youtube_layer_en", $youtube_layer_sample_en, false);
				
				update_option("dsdvo_youtube_layer_it", $youtube_layer_sample_it, false);
				
			}	
			
			if ($_POST['service'] == "shareaholic") {
				
				update_option("dsdvo_shareaholic_layer", $shareaholic_layer_sample, false);
				
				update_option("dsdvo_shareaholic_layer_en", $shareaholic_layer_sample_en, false);
				
				update_option("dsdvo_shareaholic_layer_it", $shareaholic_layer_sample_it, false);
				
			}	
			
			if ($_POST['service'] == "vimeo") {
				
				update_option("dsdvo_vimeo_layer", $vimeo_layer_sample, false);
				
				update_option("dsdvo_vimeo_layer_en", $vimeo_layer_sample_en, false);
				
				update_option("dsdvo_vimeo_layer_it", $vimeo_layer_sample_it, false);
				
			}				
			
			if ($_POST['service'] == "linkedin") {
				
				update_option("dsdvo_linkedin_layer", $linkedin_layer_sample, false);
				
				update_option("dsdvo_linkedin_layer_en", $linkedin_layer_sample_en, false);
				
				update_option("dsdvo_linkedin_layer_it", $linkedin_layer_sample_it, false);
				
			}	
			
			if ($_POST['service'] == "twitter") {
				
				update_option("dsdvo_twitter_layer", $twitter_layer_sample, false);
				
				update_option("dsdvo_twitter_layer_en", $twitter_layer_sample_en, false);
				
				update_option("dsdvo_twitter_layer_it", $twitter_layer_sample_it, false);
				
			}			
		}
		
		echo __("The respective text was reloaded. The page is now reloaded to make the changes effective.", "dsgvo-all-in-one-for-wp");
			
		die();
	}	


	public static function dsgvoaiofree_delete_log_full() {

		if (isset($_POST['nonce']) && check_ajax_referer( 'dsgvoaio-delete-log-full-nonce', 'nonce' ) == 1) {
			
			delete_option('dsgvoaio_log');
		
		}
		
		wp_die();
		
	}
	
	
	public static function dsgvoaiofree_settings_import_success() {
			
		if (isset($_GET['parm'])) {
				
			if ($_GET['parm'] == 'importsuccess') {
					
				if ( ! get_option('dismissed-dsgvo_msg_import', FALSE ) ) {
						
					echo '
					<script>
					  jQuery(function($) {
						$( document ).on( \'click\', \'.dsgvoimportsuccess .notice-dismiss\', function () {
							var type = $( this ).closest( \'.dsgvoimportsuccess\' ).data( \'notice\' );
							$.ajax( ajaxurl,
							  {
								type: \'POST\',
								data: {
								  action: \'dsgvoaiofree_dismissed_notice_handler_import\',
								  type: type,
								}
							  } );
						  } );
					  });		
					</script>  
					';	
					
					?>
					
					<div class="notice dsgvoimportsuccess is-dismissible notice-success" data-notice="dsgvo_msg_after_import">			
						
						<p><span class="dashicons dashicons-yes"></span><?php echo __( 'The settings were successfully imported.', 'dsgvo-all-in-one-for-wp' ); ?></p>
					
					</div>
					
					<?php
				}
					
			}
				
		}
			
	}	


	public static function dsgvoaio_dismiss_cache_msg() {

		update_option( 'dsgvoaio_dismiss_chache_msg', true );

		exit();

	}		
	
	
	public static function dsgvoaio_check_autoptimize($array_in) {

		if ( is_plugin_active( 'autoptimize/autoptimize.php' ) ) {

			add_filter('autoptimize_filter_js_dontmove', __CLASS__ . '::add_dsgvoaio_autoptimize');

		}

	}
		

	public static function add_dsgvoaio_autoptimize($array_in) {

		$array_in[] = '/wp-content/plugins/dsgvo-all-in-one-for-wp/';

		return $array_in;

	}


	public static function dsgvoaio_get_service_policy() {

		$policytext = "";

		if (isset($_POST['key'])) {

			include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");

			if (!isset($language)) $language = wf_get_language();

			$key = sanitize_text_field($_POST['key']);

			if ($key == "wordpressmain") {

				$plugins_policy = "";

				if ($language == "de") {

					$policytext = get_option('dsdvo_wordpress_policy');

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= $woocommerce_policy_text;

					}	

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= $polylang_policy_text;

					}	

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= $wpml_policy_text;

					}

					$plugins_policy .= $dsgvoaio_policy;

				} else {

					$policytext = get_option('dsdvo_wordpress_policy_en');

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= $woocommerce_policy_text_en;

					}			

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= $polylang_policy_text_en;

					}

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= $wpml_policy_text_en;

					}	

						$plugins_policy .= $dsgvoaio_policy_en;

				}

				$policytext = str_replace('[dsgvoaio_plugins]', $plugins_policy, $policytext);

			}

			if ($key == "analytics") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_ga_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_ga_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_ga_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_ga_policy_en');
					
				}

			}

			if ($key == "facebookpixel") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_fbpixel_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_fbpixel_policy_en');

				} else if ($language == "it") {

					$policytext = get_option('dsdvo_fbpixel_policy_it');

				} else {
					
					$policytext = get_option('dsdvo_fbpixel_policy_en');
					
				}

			}
			
			if ($key == "koko") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_koko_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_koko_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_koko_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_koko_policy_en');
					
				}

			}			

			if ($key == "matomo") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_piwik_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_piwik_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_piwik_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_piwik_policy_en');
					
				}

			}

			if ($key == "vgwort") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_vgwort_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_vgwort_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_vgwort_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_vgwort_policy_en');
					
				}

			}

			if ($key == "googletagmanager") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_gtagmanager_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_gtagmanager_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_gtagmanager_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_gtagmanager_policy_en');
					
				}

			}

			if ($key == "facebookcomment") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_facebook_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_facebook_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_facebook_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_facebook_policy_en');
					
				}

			}			

			if ($key == "facebook") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_facebook_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_facebook_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_facebook_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_facebook_policy_en');
					
				}

			}

			if ($key == "linkedin") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_linkedin_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_linkedin_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_linkedin_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_linkedin_policy_en');
					
				}

			}
			
			if ($key == "youtube") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_youtube_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_youtube_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_youtube_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_youtube_policy_en');
					
				}

			}		

			if ($key == "vimeo") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_vimeo_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_vimeo_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_vimeo_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_vimeo_policy_en');
					
				}

			}				

			if ($key == "shareaholic") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_shareaholic_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_shareaholic_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_shareaholic_policy_it');
					
				} else {
					
					$policytext = get_option('dsdvo_shareaholic_policy_en');
					
				}

			}			

			if ($key == "twitter") {

				if ($language == "de") {

					$policytext = get_option('dsdvo_twitter_policy');

				} else if ($language == "en") {

					$policytext = get_option('dsdvo_twitter_policy_en');

				} else if ($language == "it") {
					
					$policytext = get_option('dsdvo_twitter_policy_it');
					
				}  else {
					
					$policytext = get_option('dsdvo_twitter_policy_en');
					
				}

			}			

			if ($policytext == "") {

				$policytext = __( '<p>We are sorry. There is no content available. Please try again later.<br >If you are the administrator of this website - save the plugin settings!</p>', 'dsgvo-all-in-one-for-wp' );

			}

			echo wpautop(html_entity_decode(stripslashes($policytext), ENT_COMPAT, get_option('blog_charset')));

		} else {

			echo __( 'No key was passed. If this error still occurs, please contact the plugin developer.', 'dsgvo-all-in-one-for-wp' );

		}

		wp_die();

	}
	

	public static function dsgvoaio_write_log() {

		if (isset($_POST['key']) && isset($_POST['state']) && isset($_POST['id']) && isset($_POST['name'])) {

			$datetime = current_time('H:i:s - d.m.Y');

			$clientip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
			
			if ($clientip == "::1" or empty($clientip)) { $clientip = "127.0.0.1"; }

			$clientipsplit = explode(".", $clientip);
			
			$clientip = $clientipsplit[0].'.'.$clientipsplit[1].'.'.$clientipsplit[2].'.XXX';

			$currentdatas = get_option('dsgvoaio_log', array());

			end($currentdatas);

			$lastkey = key($currentdatas);

			if (isset($lastkey) && $lastkey != 0) {

				$lastkey = $lastkey+1;

			} else {

				$lastkey = 0;

			}

			$all_value = $_POST['allvalue'];
			
			if( is_string($all_value) ) {
				
				$all_value_sanitize = sanitize_text_field($all_value);
				
			} elseif( is_array($all_value) ) {
				
				$all_value_sanitize = array();
				
				foreach ( $all_value as $key => &$value ) {
					
						$all_value_sanitize[$key] = sanitize_text_field($value);
						
				}
				
			} else {
				
				$all_value_sanitize = "";
				
			}
			
			$datas = array($lastkey => array('key' => sanitize_text_field($_POST['key']), 'name' => sanitize_text_field($_POST['name']), 'state' => sanitize_text_field($_POST['state']), 'id' => sanitize_text_field($_POST['id']), 'timestep' => $datetime, 'ip' => $clientip, 'allvalue' => $all_value_sanitize));

			if (isset($currentdatas[0])) {

				$newdata = array_merge_recursive($currentdatas, $datas);

				update_option('dsgvoaio_log', $newdata, false);

			} else {

				update_option('dsgvoaio_log', $datas, false);

			}

		}

		return '';

		wp_die();

	}	


	public static function dsgvoaio_export_log() {

		if (isset($_POST['nonce']) && check_ajax_referer( 'dsgvoaio-export-log-nonce', 'nonce' ) == 1) {

			if (!isset($_POST['uid'])) {

				$log_datas = get_option('dsgvoaio_log');

			} else {

				$log_datas = get_option('dsgvoaio_log');

				$newdatas = array();

				if (isset($log_datas) && $log_datas != "") {

					foreach ($log_datas as $log_entry_key => $log_entry_value) {

						if ($log_entry_value['id'] == $_POST['uid']) {

						if (isset($log_entry_value['allvalue']) && $log_entry_value['allvalue'] != "") {

							$allvalue = $log_entry_value['allvalue'];

							$allvalue = implode(',', $allvalue);

						} else {

							$allvalue = $log_entry_value['name'];

						}							

						$newdatas[] = array('id' => $log_entry_value['id'], 'ip' => $log_entry_value['ip'], 'name' => $allvalue, 'timestep' => $log_entry_value['timestep']);

						}

					}

					if (!isset($newdatas[0])) {

							wp_die(__( 'Error: No entries were found for the specified UID. Please check the UID.', 'dsgvo-all-in-one-for-wp' ));

					} else {

							$log_datas = $newdatas;

					}

				}

			}	

			if (isset($log_datas) && $log_datas != "") {

				//***Create Log PDF and Save file***//

				require('core/inc/pdf/fpdf2.php');

				$pdf = new PDF_MC_Table();

				$pdf->AliasNbPages();

				$pdf->AddPage();

				$pdf->SetWidths(Array(15,35,35,60,25,25));

				$pdf->SetLineHeight(5);

				$pdf->SetAligns(Array('','','','','',''));

				$pdf->SetFont('Arial','B',10);

				/***Create header***/

				$pdf->Row(Array(

					'ID',

					"UID",

					"IP Adresse",

					"Dienst(e)",

					"Aktion",

					"Zeitpunkt"

				 ));	

				$pdf->SetFont('Arial','',10);

				foreach ($log_datas as $log_entry_key => $log_entry_value) {

					if (isset($log_entry_value['allvalue']) && $log_entry_value['allvalue'] != "") {

						$allvalue = $log_entry_value['allvalue'];

						$allvalue = implode(',', $allvalue);

					} else {

						$allvalue = $log_entry_value['name'];

					}

					if ($log_entry_value['state'] == "true") {

						$stateval = __( 'Approved', 'dsgvo-all-in-one-for-wp' );

					} else {

						$stateval = __( 'Rejected', 'dsgvo-all-in-one-for-wp' );

					}

					/**Add values to cell**/

					$pdf->Row(Array(

					  $log_entry_key,

					  $log_entry_value['id'],

					  $log_entry_value['ip'],

					  $allvalue,

					  $stateval,

					  $log_entry_value['timestep']

					));					

				}	

				/**Check if log dir exist  if not create it & create filename**/

				$maindir = WP_CONTENT_DIR."/dsgvo-all-in-one-wp-pro/";

				$logdir = WP_CONTENT_DIR."/dsgvo-all-in-one-wp-pro/logs/";

				$filename = "optin_outout_log_".rand(10000000000000000,90000000000000000).".pdf";

				if(!file_exists($maindir)) {

					mkdir( $maindir );

				}

				if(!file_exists($logdir)) {

					mkdir( $logdir );

				}

				$pdf->Output('F', $logdir.$filename);

				echo '<p>'.__( 'The log file was successfully exported as PDF file.', 'dsgvo-all-in-one-for-wp' ).'<br /><a href="'.content_url().'/dsgvo-all-in-one-wp-pro/logs/'.$filename.'" target="_blank" class="button button-primary">'.__( 'Download Log', 'dsgvo-all-in-one-for-wp' ).'</a></p>';

			}

		} else {

			echo __( 'An error has occurred. Please contact the support.', 'dsgvo-all-in-one-for-wp' );

		}

		wp_die();

	}


	public static function dsgvo_reset_policy_service_func() {

		if (isset($_POST['service'])) {

			include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");

				if ($_POST['service'] == "updatev31") {
					
					update_option('dsdvo_updatev31', '1' ,false);
					
				}

				if ($_POST['service'] == "ga" or $_POST['service'] == "allpolicys" or $_POST['service'] == "updatev31") {

					update_option("dsdvo_ga_policy", wp_kses_post($ga_policy_sample), false);

					update_option("dsdvo_ga_policy_en", wp_kses_post($ga_policy_sample_en), false);

				}

				if ($_POST['service'] == "mainpolicy" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_policy_text_1", wp_kses_post($policy_demo_text), false);

					update_option("dsdvo_policy_text_en", wp_kses_post($policy_demo_text_en), false);

				}		

				if ($_POST['service'] == "matomo" or $_POST['service'] == "allpolicys" or $_POST['service'] == "updatev31") {

					update_option("dsdvo_piwik_policy", wp_kses_post($matomo_policy_sample), false);

					update_option("dsdvo_piwik_policy_en", wp_kses_post($matomo_policy_sample_en), false);

				}		

				if ($_POST['service'] == "fbpixel" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_fbpixel_policy", wp_kses_post($fbpixel_policy_sample), false);

					update_option("dsdvo_fbpixel_policy_en", wp_kses_post($fbpixel_policy_sample_en), false);

				}	

				if ($_POST['service'] == "gtag" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_gtagmanager_policy", wp_kses_post($gtagmanager_policy_sample), false);

					update_option("dsdvo_gtagmanager_policy_en", wp_kses_post($gtagmanager_policy_sample_en), false);

				}	

				if ($_POST['service'] == "vgwort" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_vgwort_policy", wp_kses_post($vgwort_policy_sample), false);

					update_option("dsdvo_vgwort_policy_en", wp_kses_post($vgwort_policy_sample_en), false);

				}	
					
				if ($_POST['service'] == "koko" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_koko_policy", wp_kses_post($koko_policy_sample), false);

					update_option("dsdvo_koko_policy_en", wp_kses_post($koko_policy_sample_en), false);

				}	
				
				if ($_POST['service'] == "wordpress" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_wordpress_policy", wp_kses_post($wordpress_policy_sample), false);

					update_option("dsdvo_wordpress_policy_en", wp_kses_post($wordpress_policy_sample_en), false);

				}			

				if ($_POST['service'] == "shareaholic" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_shareaholic_policy", wp_kses_post($shareaholic_policy_sample), false);

					update_option("dsdvo_shareaholic_policy_en", wp_kses_post($shareaholic_policy_sample_en), false);

				}	

				if ($_POST['service'] == "fb" or $_POST['service'] == "allpolicys") {

					update_option("dsdvo_facebook_policy", wp_kses_post($facebook_policy_sample), false);

					update_option("dsdvo_facebook_policy_en", wp_kses_post($facebook_policy_sample_en), false);

				}	

				if ($_POST['service'] == "twitter" or $_POST['service'] == "allpolicys" or $_POST['service'] == "updatev31") {

					update_option("dsdvo_twitter_policy", hwp_kses_post($twitter_policy_sample), false);

					update_option("dsdvo_twitter_policy_en", wp_kses_post($twitter_policy_sample_en), false);

				}		

				if ($_POST['service'] == "linkedin" or $_POST['service'] == "allpolicys" or $_POST['service'] == "updatev31") {

					update_option("dsdvo_linkedin_policy", wp_kses_post($linkedin_policy_sample), false);

					update_option("dsdvo_linkedin_policy_en", wp_kses_post($linkedin_policy_sample_en), false);

				}

				if ($_POST['service'] == "youtube" or $_POST['service'] == "allpolicys" or $_POST['service'] == "updatev31") {

					update_option("dsdvo_youtube_policy", wp_kses_post($youtube_policy_sample), false);

					update_option("dsdvo_youtube_policy_en", wp_kses_post($youtube_policy_sample_en), false);

				}

				if ($_POST['service'] == "allpolicys") {

					update_option("dsdvo_policy_text_1", wp_kses_post($policy_demo_text), false);

					update_option("dsdvo_policy_text_en", wp_kses_post($policy_demo_text_en), false);

					update_option("dsdvo_allpolicyreloaded", "1", false);

				}

				if ($_POST['service'] == "cookietext") {

					update_option("dsdvo_cookie_text", wp_kses_post("Wir verwenden technisch notwendige Cookies auf unserer Webseite sowie externe Dienste.<br />Standardmäßig sind alle externen Dienste deaktiviert. Sie können diese jedoch nach belieben aktivieren & deaktivieren.<br/>Für weitere Informationen lesen Sie unsere Datenschutzbestimmungen."), false);

					update_option("dsdvo_cookie_text_en", wp_kses_post("We use technically necessary cookies on our website and external services.<br/>By default, all services are disabled. You can turn or off each service if you need them or not.<br />For more informations please read our privacy policy."), false);

					update_option("dsdvo_cookietextreloaded", "1", false);

				}		

			echo __("The respective policy text was reloaded. The page will now reload to make the changes effective.", "dsgvo-all-in-one-for-wp");

		}

		die();

	}


	public static function dsgvo_ajax_remove_usr_ip(){

		$reponse = array();

		global $wpdb;

		$db_prefix = $wpdb->base_prefix;

		if(!empty($_POST['param']) && !empty($_POST['nonce'])) {

			if (is_admin()) {
				
				if ( wp_verify_nonce( $_POST['nonce'], 'dsgvo-delete-ip-adresses' ) ) {

					$countupdaterows = $wpdb->query($wpdb->prepare("UPDATE ".$db_prefix."comments SET comment_author_IP = '%s'", ' '));

					if($countupdaterows == 0) {

						 $response['response'] = __("The query was successful but there are no IP addresses to delete in the database because there are none stored.", "dsgvo-all-in-one-for-wp");

					} else {

						$response['response'] = __("The IP addresses stored in the comments were successfully deleted. ".$countupdaterows." IP addresses were deleted.", "dsgvo-all-in-one-for-wp");			

					}
				} else {
					
					$response['response'] = __("Nonce not valid! Please reload the page.", "dsgvo-all-in-one-for-wp");
					
				}

			} else {

				$response['response'] = __("You lack the necessary rights to perform this action!", "dsgvo-all-in-one-for-wp");

			}

		} else {

			 $response['response'] = __("An error occurred, no parameter was passed.", "dsgvo-all-in-one-for-wp");
			 
		}

		header( "Content-Type: application/json" );

		echo json_encode($response);

		exit();

	}	

	
	public static function dsgvo_aio_load_admin_css($hook) {

		$screen = get_current_screen();
		
		if ( $screen->id == 'toplevel_page_dsgvoaio-free-settings-page' or  $screen->id == 'dsgvo-aio_page_dsgvoaiofree-show-log' or  $screen->id == 'dsgvo-aio_page_dsgvoaio-free-import-export' or $screen->id == 'dsgvo-aio_page_dsgvoaiofree-changelog'){

			wp_enqueue_style('dsgvo_admin_css', plugins_url('assets/css/admin.css',__FILE__ ));

			wp_enqueue_script('dsgvoaio_adminjs', plugins_url('assets/js/admin.js',__FILE__ ));	

			wp_enqueue_script('dsgvoaio_datatables_js', plugins_url('assets/js/datatables.min.js',__FILE__ ));

			wp_enqueue_style('dsgvoaio_datatables_css', plugins_url('assets/css/datatables.min.css',__FILE__ ));

			wp_enqueue_style('dsgvoaio_admin_tooltipster_css', plugins_url('assets/css/tooltipster.bundle.min.css',__FILE__ ));	

			wp_enqueue_script('dsgvoaio_admin_tooltipster_js', plugins_url('assets/js/tooltipster.bundle.min.js',__FILE__ ), array('jquery'));			

		}
		
	}


	public static function dsgvo_aio_admin_menu(){
		
		$notification_count_changelog = get_option('dsgvoaio_notification_count_v45', '1');

		add_menu_page( 'DSGVO All in one for WP', 'DSGVO AIO ', 'manage_options', 'dsgvoaio-free-settings-page', __CLASS__ . '::dsdvo_settings' );

		add_submenu_page('dsgvoaio-free-settings-page', 'Log', 'Opt-in/Opt-out Log', 'manage_options', 'dsgvoaiofree-show-log', __CLASS__ . '::dsgvoaiofree_backend_show_log');		
		
		add_submenu_page('dsgvoaio-free-settings-page', 'Einstellungen importieren/exportieren', 'Import / Export', 'manage_options', 'dsgvoaio-free-import-export', __CLASS__ . '::dsgvoaiofree_backend_import_export');			

		add_submenu_page('dsgvoaio-free-settings-page', 'Changelog', $notification_count_changelog ? sprintf( 'Changelog <span class="awaiting-mod">%d</span>', $notification_count_changelog ) : 'Changelog', 'manage_options', 'dsgvoaiofree-changelog', __CLASS__ . '::dsgvoaiofree_backend_changelog');

	}
	
	
	public static function dsgvoaiofree_backend_changelog() {
		
		include('core/inc/changelog.php');
		
	}


	public static function dsgvoaiofree_backend_import_export() {	
	
		include('core/inc/import_export.php');
		
	}		


	public static function dsgvoaiofree_backend_show_log() {	

		include('core/inc/backend_show_log.php');

	}	

 	
	public static function dsdvo_settings() {

		include('core/inc/backend_settings.php');

	}

}

dsdvo_wp_backend::init();


class dsdvo_wp_frontend {

	 public static function init() {
		 
		if ( ! is_admin() ) {
	 	
			include(dirname(__FILE__).'/core/inc/google_fonts.php');
			
		}		 

		add_action( 'init', __CLASS__ . '::dsgvoaiofree_downoad_pdf' );	

		if (get_option("dsdvo_language_reloaded_44") !== "1") {		
		
			add_action( 'wp_loaded', __CLASS__ .'::dsgvoaio_renew_language_files' );
			
		}
		
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  && get_option('dsdvo_use_fbpixel') == "on" && (! is_admin() || wp_doing_ajax())) {
			
			add_action( 'wp_ajax_dsgvoaiofree_fbpixelevent_ajaxhandle', __CLASS__ . '::dsgvoaiofree_fbpixelevent_ajaxhandle' );
			
			add_action( 'wp_ajax_nopriv_dsgvoaiofree_fbpixelevent_ajaxhandle', __CLASS__ .'::dsgvoaiofree_fbpixelevent_ajaxhandle' );	
			
			add_action( 'wp_head', __CLASS__ . '::dsgvoaiofree_fbpixelevent_addcartajax' );
			
			add_action('woocommerce_add_to_cart', __CLASS__ . '::dsgvoaiofree_fbpixelevent_addcart', 10, 6);
			
			add_action( 'woocommerce_thankyou',  __CLASS__ . '::dsgvoaiofree_fbpixelevent_purchase' );		
			
			add_action( 'woocommerce_new_order', __CLASS__ . '::dsgvoaiofree_fbpixelevent_purchase1',  1, 1  );
			
			add_action( 'wp_ajax_dsgvoaiofree_change_session', __CLASS__ .'::dsgvoaiofree_change_session' );
			
			add_action( 'wp_ajax_nopriv_dsgvoaiofree_change_session', __CLASS__ .'::dsgvoaiofree_change_session' );	
		
		}		
		 
		if (get_option("dsdvo_show_policy") == "on") {

			add_action( 'wp_footer', __CLASS__ . '::dsdvo_cookie_banner' );

		}

		if (get_option("dsdvo_show_policy") == "on" && (! is_admin() || wp_doing_ajax())) {

			add_action( 'wp_enqueue_scripts', __CLASS__ . '::dsdvo_wp_add_scripts');

			add_action( 'wp_enqueue_scripts', __CLASS__ . '::dsgvoaio_control_func' );

		}

		if (get_option("dsdvo_show_rejectbtn") == "on") {

			add_action('wp_head', __CLASS__ . '::style_rejectbtn', 100);			

		}			

		$blog_agb = get_option("dsdvo_blog_agb");

		if ($blog_agb == "on") {

			add_filter('comment_form_after_fields', __CLASS__ . '::dsdvo_my_comment_form_field_comment');

			add_filter('comment_form_logged_in_after', __CLASS__ . '::dsdvo_my_comment_form_field_comment');

			add_action('wp_footer',__CLASS__ . '::dsdvo_valdate_privacy_comment_javascript');

			if (!is_admin()) {

				add_filter( 'preprocess_comment', __CLASS__ . '::dsdvo_verify_comment_privacy' );

			}			

			add_action( 'comment_post', __CLASS__ . '::dsdvo_save_comment_privacy' );

		}	

		add_shortcode('dsgvo_service_control', array( 'dsdvo_wp_frontend', 'dsgvo_service_control_func' ));

		add_shortcode('dsgvo_twitter_button', array( 'dsdvo_wp_frontend', 'dsgvo_twitter_button_func' ));

		add_shortcode('dsgvo_linkedin', array( 'dsdvo_wp_frontend', 'dsgvo_linkedin_func' ));

		add_shortcode('dsgvo_addthis', array( 'dsdvo_wp_frontend', 'dsgvo_addthis_func' ));

		add_shortcode('dsgvo_facebook_like', array( 'dsdvo_wp_frontend', 'dsgvo_facebooklike_func' ));

		add_shortcode( 'dsgvo_facebook_comments', array( 'dsdvo_wp_frontend', 'dsgvo_facebookcommentar_func'));

		add_shortcode( 'dsgvo_vgwort', array( 'dsdvo_wp_frontend', 'dsgvo_vgwort_func'));

		add_shortcode( 'dsgvo_shareaholic', array( 'dsdvo_wp_frontend', 'dsgvo_shareaholic_func'));

		add_shortcode( 'dsgvo_youtube', array( 'dsdvo_wp_frontend', 'dsgvo_youtube_func' ));
		
		add_shortcode( 'dsgvo_vimeo', array( 'dsdvo_wp_frontend', 'dsgvo_vimeo_func' ));

		add_action( 'wp_ajax_dsgvoaio_dismissed_notice_handler', __CLASS__ . '::dsgvo_ajax_notice_handler' );

		add_action( 'upgrader_process_complete', __CLASS__ . '::dsgvoaio_upgrade_completed', 10, 2 );

		register_activation_hook( __FILE__, __CLASS__ . '::dsgvoaio_activation_hook' );

		if( get_option( 'dsgvoaio_dismiss_chache_msg' ) != true ) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( is_plugin_active( 'autoptimize/autoptimize.php' ) or is_plugin_active( 'wp-rocket/wp-rocket.php' )) {

				add_action('admin_notices',   __CLASS__ . '::dsgvoaio_notice_cacheplugins');

			}

		}

		add_shortcode( 'dsgvo_imprint', array( 'dsdvo_wp_frontend', 'dsgvo_show_imprint' ) );
		
		add_shortcode( 'dsgvo_user_remove_form', array( 'dsdvo_wp_frontend', 'dsdvo_user_remove_form_func' ) );				

		add_shortcode( 'dsgvo_policy', array( 'dsdvo_wp_frontend', 'dsgvo_show_policy' ) );

		add_shortcode( 'dsgvo_show_user_data', array( 'dsdvo_wp_frontend', 'dsgvo_get_user_datas' ) );

		if (get_option("dsgvo_remove_ipaddr_auto") == "on") {

			add_filter( 'pre_comment_user_ip', __CLASS__ . '::dsgvo_remove_commentsip' );

		}

		if (!function_exists('wf_get_language')) {

			function wf_get_language() {

				$language = null;

				if (function_exists('pll_current_language')) {

					$language = pll_current_language();

				} else if (defined('ICL_LANGUAGE_CODE')) {

					$language = ICL_LANGUAGE_CODE;

				} else {

					$language = substr(get_locale(),0,2);

					if ($language != "de" && $language != "it") {

						$language = "en";

					}
				}

				return $language;

			}

		}		

		if (get_option("dsgvo_notice_design") == "clear") {	

		add_action('wp_head', __CLASS__ . '::style_dsgvoaio', 1);

		}

		if (get_option("dsdvo_use_vgwort") == "on" && get_option("dsdvo_remove_vgwort") == "on" && !is_admin()) {

			add_action("wp_loaded", __CLASS__ . '::dsgvoaio_disable_vgwort_ob_start');

		}		

		if (get_option("dsdvo_use_gtagmanager") == "on" && get_option("dsdvo_remove_gtagmanager") == "on" && !is_admin()) {

			add_action("wp_loaded", __CLASS__ . '::dsgvoaio_disable_gtagmanager_ob_start');

		}				

		function dsgvo_show_policy_popup() {

			$notice_style = get_option("dsgvo_notice_style", "3");

			if ($notice_style == "3") {

			if (!isset($language)) $language = wf_get_language();

			if ($language == "de") {

				$policy_text_1 = get_option("dsdvo_policy_text_1");

			}

			if ($language == "en") {

				$policy_text_1 = get_option("dsdvo_policy_text_en");

			}

			if ($language == "it") {

				$policy_text_1 = get_option("dsdvo_policy_text_it");

			}		

			if (!isset($policy_text_1) && empty($policy_text_1)) {
				
				$policy_text_1 = "";
				
			}		

			$now = new DateTime();

			$update_date = $now->format('d.m.Y');

			$content = "";

			if ($policy_text_1) {

				$content .= str_replace("[dsgvo_save_date]", $update_date, "<div class='dsgvo_aio_policy'>".wp_kses_post(stripcslashes($policy_text_1))."</div>");

				include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");
				
				$plugins_policy = "";

				if ($language == "de") {

					$policytext = wpautop(wp_kses_post(stripslashes(get_option("dsdvo_wordpress_policy"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($woocommerce_policy_text)));

					}	

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($polylang_policy_text)));

					}	

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($wpml_policy_text)));

					}

					$plugins_policy .= wpautop(wp_kses_post(stripslashes($dsgvoaio_policy)));

				} else if ($language == "it") {
					
					$policytext = wpautop(wp_kses_post(stripslashes(get_option("dsdvo_wordpress_policy_it"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($woocommerce_policy_text_it)));

					}	

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($polylang_policy_text_it)));

					}	

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($wpml_policy_text_it)));

					}

					$plugins_policy .= wpautop(wp_kses_post(stripslashes($dsgvoaio_policy_it)));
					
					
				} else {

					$policytext = wpautop(wp_kses_post(stripslashes(get_option("dsdvo_wordpress_policy_en"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($woocommerce_policy_text_en)));

					}			

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($polylang_policy_text_en)));

					}

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($wpml_policy_text_en)));

					}	

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($dsgvoaio_policy_en)));

				}				
				
				if (isset($policytext) && !empty($policytext)) {
					
					$policytext = str_replace('[dsgvoaio_plugins]', $plugins_policy, $policytext);
					
					$content .= $policytext;
					
				}
				
				if (get_option('dsdvo_use_vgwort') == "on" && !empty(get_option("dsdvo_vgwort_policy")) or get_option('dsdvo_use_vgwort') == "on" && !empty(get_option("dsdvo_vgwort_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripslashes(get_option("dsdvo_vgwort_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripslashes(get_option("dsdvo_vgwort_policy_en")));

					} 	

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_vgwort_policy_it")));

					} 					

				}

				if (get_option('dsdvo_use_koko') == "on" && !empty(get_option("dsdvo_koko_policy")) or get_option('dsdvo_use_koko') == "on" && !empty(get_option("dsdvo_koko_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_koko_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_koko_policy_en")));

					} 	

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_koko_policy_it")));

					} 					

				}
				
				if (get_option('dsdvo_use_fbpixel') == "on" && !empty(get_option("dsdvo_fbpixel_policy")) or get_option('dsdvo_use_fbpixel') == "on" && !empty(get_option("dsdvo_fbpixel_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_fbpixel_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_fbpixel_policy_en")));

					} 		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_fbpixel_policy_it")));

					}					

				}

				if (get_option('dsdvo_use_facebooklike') == "on" && !empty(get_option("dsdvo_facebook_policy")) or get_option('dsdvo_use_facebookcomments') == "on" && !empty(get_option("dsdvo_facebook_policy")) or get_option('dsdvo_use_facebooklike') == "on" && !empty(get_option("dsdvo_facebook_policy_en")) or get_option('dsdvo_use_facebookcomments') == "on" && !empty(get_option("dsdvo_facebook_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_facebook_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_facebook_policy_en")));

					} 	

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_facebook_policy_it")));

					} 					

				}	

				if (get_option('dsdvo_use_twitter') == "on" && !empty(get_option("dsdvo_twitter_policy")) or get_option('dsdvo_use_twitter') == "on" && !empty(get_option("dsdvo_twitter_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_twitter_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_twitter_policy_en")));

					} 		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_twitter_policy_it")));

					} 						

				}		

				if (get_option('dsdvo_use_ga') == "on" && !empty(get_option("dsdvo_ga_policy")) or get_option('dsdvo_use_ga') == "on" && !empty(get_option("dsdvo_ga_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_ga_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_ga_policy_en")));

					}	

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_ga_policy_it")));

					}						

				}

				if (get_option('dsdvo_use_disqus') == "on" && !empty(get_option("dsdvo_disqus_policy")) or get_option('dsdvo_use_disqus') == "on" && !empty(get_option("dsdvo_disqus_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_disqus_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_disqus_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_disqus_policy_it")));

					}						

				}

				if (get_option('dsdvo_use_pinterest') == "on" && !empty(get_option("dsdvo_pinterest_policy")) or get_option('dsdvo_use_pinterest') == "on" && !empty(get_option("dsdvo_pinterest_policy_en")) or get_option('dsdvo_use_pinterestpin') == "on" && !empty(get_option("dsdvo_pinterest_policy")) or get_option('dsdvo_use_pinterestpin') == "on" && !empty(get_option("dsdvo_pinterest_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_pinterest_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_pinterest_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_pinterest_policy_it")));

					}					

				}	

				if (get_option('dsdvo_use_sharethis') == "on" && !empty(get_option("dsdvo_sharethis_policy")) or get_option('dsdvo_use_sharethis') == "on" && !empty(get_option("dsdvo_sharethis_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_sharethis_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_sharethis_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_sharethis_policy_it")));

					}						

				}

				if (get_option('dsdvo_use_shareaholic') == "on" && !empty(get_option("dsdvo_shareaholic_policy")) or get_option('dsdvo_use_shareaholic') == "on" && !empty(get_option("dsdvo_shareaholic_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_shareaholic_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_shareaholic_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_shareaholic_policy_it")));

					}						

				}	

				if (get_option('dsdvo_use_addthis') == "on" && !empty(get_option("dsdvo_addthis_policy")) or get_option('dsdvo_use_addthis') == "on" && !empty(get_option("dsdvo_addthis_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addthis_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addthis_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addthis_policy_it")));

					}						

				}	

				if (get_option('dsdvo_use_addtoanyshare') == "on" && !empty(get_option("dsdvo_addtoany_policy")) or get_option('dsdvo_use_addtoanyshare') == "on" && !empty(get_option("dsdvo_addtoany_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addtoany_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addtoany_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_addtoany_policy_it")));

					}					

				}	

				if (get_option('dsdvo_use_statcounter') == "on" && !empty(get_option("dsdvo_statcounter_policy")) or get_option('dsdvo_use_statcounter') == "on" && !empty(get_option("dsdvo_statcounter_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_statcounter_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_statcounter_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_statcounter_policy_it")));

					}						

				}	

				if (get_option('dsdvo_use_piwik') == "on" && !empty(get_option("dsdvo_piwik_policy")) or get_option('dsdvo_use_piwik') == "on" && !empty(get_option("dsdvo_piwik_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_piwik_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_piwik_policy_en")));

					}		

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_piwik_policy_it")));

					}						

				}		

				if (get_option('dsdvo_use_komoot') == "on" && !empty(get_option("dsdvo_komoot_policy")) or get_option('dsdvo_use_komoot') == "on" && !empty(get_option("dsdvo_komoot_policy_en"))) { 

					$content .= "<p>&nbsp;</p>";

					if ($language == "de") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_komoot_policy")));

					} 

					if ($language == "en") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_komoot_policy_en")));

					}	

					if ($language == "it") {

						$content .= wp_kses_post(stripcslashes(get_option("dsdvo_komoot_policy_it")));

					}					

				}		

				if (get_option('dsgvoaiocompanyname')) {

					$content = str_replace('[company]',stripslashes(esc_html(get_option('dsgvoaiocompanyname'))),$content);

				} else {

					$content = str_replace('[company]','',$content);

				}		

				if (get_option('dsgvoaioperson')) {

					$content = str_replace('[owner]',stripslashes(esc_html(get_option('dsgvoaioperson'))),$content);

				} else {

					$content = str_replace('[owner]','',$content);

				}

				if (get_option('dsgvoaiostreet')) {

					$content = str_replace('[adress]',stripslashes(esc_html(get_option('dsgvoaiostreet'))),$content);

				} else {

					$content = str_replace('[adress]','',$content);

				}

				if (get_option('dsgvoaiozip')) {

					$content = str_replace('[zip]',stripslashes(esc_html(get_option('dsgvoaiozip'))),$content);

				} else {

					$content = str_replace('[zip]','',$content);

				}

				if (get_option('dsgvoaiocity')) {

					$content = str_replace('[city]',stripslashes(esc_html(get_option('dsgvoaiocity'))),$content);

				} else {

					$content = str_replace('[city]','',$content);

				}

				if (get_option('dsgvoaiocountry')) {

					$content = str_replace('[country]',stripslashes(esc_html(get_option('dsgvoaiocountry'))),$content);

				} else {

					$content = str_replace('[country]','',$content);

				}

				if (get_option('dsgvoaiophone')) {

					$content = str_replace('[phone]',stripslashes(esc_html(get_option('dsgvoaiophone'))),$content);

				} else {

					$content = str_replace('[phone]','',$content);

				}
				
				if (get_option('dsgvoaiofax')) {

					$content = str_replace('[fax]',stripslashes(esc_html(get_option('dsgvoaiofax'))),$content);

				} else {

					$content = str_replace('[fax]','',$content);

				}				

				if (get_option('dsgvoaiomail')) {
						
					$content = str_replace('[mail]', "<p>".__("E-Mail:", "dsgvo-all-in-one-for-wp")."&nbsp;".html_entity_decode(stripcslashes(get_option('dsgvoaiomail')), ENT_COMPAT, 'utf-8')."</p>" ,$content);						
					
				} else {
					
					$content = str_replace('[mail]','',$content);
					
				}				

				if (get_option('dsdvo_legalform_ustid')) {

					$content = str_replace('[ust]',stripslashes(esc_html(get_option('dsdvo_legalform_ustid'))),$content);

				} else {

					$content = str_replace('[ust]','',$content);

				}				

			} else {

				$content = "<b>".__("Info", "dsgvo-all-in-one-for-wp").":</b> ".__("Please save the settings in the backend under \"DSGVO AIO\" to output the text of the privacy policy here", "dsgvo-all-in-one-for-wp").".";

			}

			return wpautop($content);    

			} else {

				return " ";

			}

		}		

		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		
		if (get_option("dsdvo_remove_analytis") == "on" && !is_admin()) {
			
			add_action("wp_loaded", __CLASS__ . '::dsgvoaio_disable_analytics_ob_start');
			
		}
		
		if (get_option('dsdvo_ga_type', 'manual') == "monterinsights" && !is_admin() && get_option("dsdvo_use_ga", "off") == "on" && is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) or get_option('dsdvo_ga_type', 'manual') == "monterinsights" && !is_admin() && get_option("dsdvo_use_ga", "off") == "on" && is_plugin_active( 'google-analytics-premium/googleanalytics-premium.php' )) {	
							
			add_filter( 'monsterinsights_frontend_output_analytics_src', __CLASS__ . '::dsgvoaiofree_remove_monsterinsights_ressource', 10, 4 );
				
			add_action("wp_loaded", __CLASS__ . '::dsgvoaio_replace_monsterinsight_js', 1);
		
		}

		if (get_option('dsdvo_ga_type', 'manual') == "analytify" && !is_admin() && get_option("dsdvo_use_ga", "off") == "on" && is_plugin_active( 'wp-analytify/wp-analytify.php' )) {
		
			add_action('plugins_loaded', __CLASS__ . '::dsgvoaio_analitify');

		}

		if (get_option("dsdvo_use_koko") == "on") {		
		
			add_action('wp_loaded', __CLASS__ . '::dsgvoaio_replace_koko_analytics');
			
		}

	}


	public static function dsgvoaiofree_fbpixelevent_addcart($cart_item_key, $productid) {
		
		global $woocommerce;
		
		$product = wc_get_product( $productid );
		
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		
		$termsoutput = "";
		
		if (!session_id()) {
			
			session_start();
			
		}
		
		foreach (get_the_terms($product->get_id(), 'product_cat') as $cat) {
			
			$termsoutput .= $cat->term_id.",";
			
		}
		
		$termsoutput = rtrim($termsoutput, ',');
		
		$_SESSION['fbpixel_product_cat'] = $termsoutput;
		
		$_SESSION['fbpixel_content_name'] = $product->get_name();
		
		$_SESSION['fbpixel_product_price'] = $product->get_price();
		
		$_SESSION['fbpixel_currency'] = get_woocommerce_currency();	
		
		$_SESSION['fbpixel_content_ids'] = $product->get_id();	
		
		$_SESSION['fbpixel_content_type'] = get_post_type();
		
		$_SESSION['fbpixelevent'] = "AddToCart";
			
	}
	
	public static function dsgvoaiofree_change_session() {	
	
		if (isset($_POST['name']) && isset($_POST['value']) && isset($_POST['orderid'])) {
			
			if ($_POST['name'] == "pixelevent" && $_POST['value'] == "Purchase") {
				
				$_SESSION['pixeleventbuyed'] = "true";
				
				update_post_meta( sanitize_text_field($_POST['orderid']), 'dsgvoaio_fbpixel_purchase', 'false' );	
			
			} else {
				
				$_SESSION['pixeleventbuyed'] = "false";
				
				
				update_post_meta( sanitize_text_field($_POST['orderid']), 'dsgvoaio_fbpixel_purchase', 'true' );	
			
			}
		
		}

		wp_die();
		
	}		
		
	public static function dsgvoaiofree_fbpixelevent_purchase1($order_id) {
		
		$order = new WC_Order( $order_id );
		
		update_post_meta( $order_id, 'dsgvoaio_fbpixel_purchase', 'true' );	
		
	}
		
	public static function dsgvoaiofree_fbpixelevent_purchase() {
		
		if (isset($_SESSION['pixeleventbuyed'])) {
			
			if ($_SESSION['pixeleventbuyed'] == "true") {
				
				$isbuyedsendet = "true";
				
			}
			
		} else {
			
			$isbuyedsendet = "false";
			
		}	
		
		if ($isbuyedsendet == "false") {
			
			$_SESSION['fbpixelevent'] = "Purchase";
			
		} else {
			
			$_SESSION['fbpixelevent'] = "PageView";
			
		}
				
	}
	
	public static function dsgvoaiofree_fbpixelevent_addcartajax() {
		
		$product = wc_get_product( get_the_ID() );

		echo '<script>' . PHP_EOL;
		
		echo '
			jQuery( document ).ready(function() {
			jQuery(".ajax_add_to_cart").on("click", function(){
			var data = {
						"action": "dsgvoaio_fbpixelevent_ajaxhandle",
						"act": "AddToCart"
					};
					jQuery.post("'.admin_url('admin-ajax.php').'", data, function(response) {
					});
			});
			});
		';
		
		echo '</script>' . PHP_EOL;

	}	 	
	
	public static function dsgvoaiofree_fbpixelevent_ajaxhandle() {
		
		if (!session_id()) {
					
			session_start();
					
		}
				
		$_SESSION['fbpixelevent'] = "AddToCart";
				
		if (isset($_POST['content_name'])) {
					
			$_SESSION['fbpixel_content_name'] = sanitize_text_field($_POST['content_name']);
					
		}
				
		if (isset($_POST['product_cat'])) {
					
			$_SESSION['fbpixel_product_cat'] = sanitize_text_field($_POST['product_cat']);
					
		}	
				
		if (isset($_POST['product_price'])) {
					
				$_SESSION['fbpixel_product_price'] = sanitize_text_field($_POST['product_price']);
					
		}
				
		if (isset($_POST['currency'])) {
					
			$_SESSION['fbpixel_currency'] = sanitize_text_field($_POST['currency']);
					
		}	
				
		if (isset($_POST['content_ids'])) {
					
			$_SESSION['fbpixel_content_ids'] = sanitize_text_field($_POST['content_ids']);
					
		}
				
		if (isset($_POST['content_type'])) {
					
			$_SESSION['fbpixel_content_type'] = sanitize_text_field($_POST['content_type']);
					
		}	
				
		die();
		
	}	
	


	public static function dsgvoaio_renew_language_files() {

		$delete_files = array();
		
		$delete_count = 0;
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE.mo')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE.mo';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE.po')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE.po';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_AT.mo')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_AT.mo';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_AT.po')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_AT.po';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_CH.mo')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_CH.mo';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_CH.po')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_CH.po';
			
		}	
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE_formal.mo')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE_formal.mo';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE_formal.po')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-de_DE_formal.po';
			
		}	
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-it_IT.mo')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-it_IT.mo';
			
		}
		
		if (file_exists(WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-it_IT.po')) {
			
			$delete_files[] = WP_CONTENT_DIR . '/languages/plugins/dsgvo-all-in-one-for-wp-it_IT.po';
			
		}	
		
		if (isset($delete_files[0])) {
			
			foreach ($delete_files as $delete_file) {
				
				if (isset($delete_file) && !empty($delete_file)) {
					
					$delete_count++;
					
					wp_delete_file( $delete_file );
					
				}
				
			}
			
		}
		
		update_option('dsdvo_language_reloaded_44', '1', false);
	
	}	
	
	
	public static function dsgvoaiofree_downoad_pdf() {
			
		if( empty( $_POST['dsgvoaiofree_action'] ) || 'download_userdatas' != $_POST['dsgvoaiofree_action'] )
			
			return;

		if( ! wp_verify_nonce( $_POST['dsgvoaiofree_download_userdata_nonce'], 'dsgvoaiofree_download_userdata_nonce' ) )
			
			return;

		if( ! is_user_logged_in() )
			
			return;
			
		if (isset($_POST['dsgvoaiofree_download_userdata_language'])) {
			
			$language = $_POST['dsgvoaiofree_download_userdata_language'];
			
		} else {
			
			$language = "de";
			
		}
					
		ignore_user_abort( true );

		nocache_headers();
		
		include(dirname(__FILE__).'/core/inc/create_pdf.php');

		header('Content-Description: File Transfer');
			 
		header("Content-type: application/octet-stream");
			
		header( 'Content-Disposition: attachment; filename='.basename($output_file_dir) );
			
		header('Expires: 0');
			
		header('Cache-Control: must-revalidate');
			
		header('Pragma: public');
			
		header('Content-Length: ' . filesize($output_file_dir));
			
		readfile( $output_file_dir);
			
		wp_delete_file($output_file_dir);
			
		exit;
			
		wp_die();

	}		

	public static function dsgvoaio_analitify() {
		
		ob_start(__CLASS__ . '::dsgvoaio_replace_analitify');
		
	}

	public static function dsgvoaio_replace_analitify($html) {
			
		if ( class_exists( 'WP_Analytify' ) ) {
					
			$analytify = WP_Analytify::get_instance();
				
			if ( class_exists( 'WP_ANALYTIFY_FUNCTIONS' ) ) {
						
				$ua_code = WP_ANALYTIFY_FUNCTIONS::get_UA_code();
						
				preg_match_all('/\<script(.*?)?\>[^<>]*GoogleAnalyticsObject[^<>]*<\/script\>/i', $html, $match);
						
				if (isset($match[0])) {
					
					$limit = 0;
					
					foreach ($match[0] as $data) {
						
						if ($data != ""  && strpos($data, 'tarteaucitron.job') === false) {
							
							if (strpos($data, 'GoogleAnalyticsObject') !== false && $limit++ < 1) {
								
								$code = str_replace('<script type="text/javascript">', '', str_replace('</script>', '', str_replace('<script>', '', $data)));
								
								update_option('dsgvo_analytify_js', $code, false);
							}
							
						}
						
					}
							
					$html = preg_replace('#<script>[^<>]*GoogleAnalyticsObject[^<>]*<\/script>#i', '', $html);
							
				}
						
			}
			
		}
		
		return $html;
		
		}

		public static function dsgvoaiofree_remove_monsterinsights_ressource() {
			
			return plugins_url('/dsgvo-all-in-one-for-wp/assets/js/analyticsdummy.js');
			
		}	

		public static function dsgvoaio_replace_monsterinsight_js(){
			
			require_once WP_PLUGIN_DIR . '/google-analytics-for-wordpress/includes/frontend/class-tracking-abstract.php';
			
			require_once ABSPATH . '/wp-content/plugins/google-analytics-for-wordpress/includes/frontend/tracking/class-tracking-gtag.php';
			
			if(class_exists('MonsterInsights_Tracking_Gtag')){
			
				$tracking = new MonsterInsights_Tracking_Gtag();
					
				$code = str_replace(plugins_url('/dsgvo-all-in-one-for-wp/assets/js/analyticsdummy.js'), 'https://www.google-analytics.com/analytics.js', $tracking->frontend_output());
				
				$code = str_replace('</script>', '', $code);
				
				preg_match('/src=(["\'])(.*?)\1/', $code, $monsterinsight_ga_url);
				
					if (isset($monsterinsight_ga_url[2])) {
						
						$code = preg_replace('/<script[^<>]*>/i', '', $code);
						
					}
				
				$code_js_file = "let dsgvoaio_monster_src = document.createElement('script');";

				$code_js_file .= "dsgvoaio_monster_src.setAttribute('src', '".$monsterinsight_ga_url[2]."');";
				
				$code_js_file .= "dsgvoaio_monster_src.setAttribute('type', 'text/javascript');";

				$code_js_file .= "document.body.appendChild(dsgvoaio_monster_src);".$code;
						
				update_option('dsgvo_monsterinsightcode', $code_js_file, false);
			
			}
			
			remove_action( 'wp_head', 'monsterinsights_tracking_script', 6 );	
				
		}
		
		public static function dsgvoaio_replace_koko_analytics(){
			
			ob_start(__CLASS__ . '::dsgvoaio_replace_koko_analytics_ob_end');
			
		}

		public static function dsgvoaio_replace_koko_analytics_ob_end($html){
			
			preg_match_all('/<script[^<>]*?koko-analytics\/assets\/dist\/js\/script.js[^<>]*><\/script>/is', $html, $match);

			if (isset($match[0])) {
				
				foreach ($match[0] as $data) {
					
					if (isset($data)) {
						
						if ($data != ""  && strpos($data, 'kokoanalyticscode') === false) {
							
							$data = str_replace("'", "\"", $data);
							
							preg_match('/src="(.*?)"/i', $data, $kokomatch);
							
							if (isset($kokomatch[1])) {
								
								update_option('dsgvo_kokocode', $kokomatch[1], false);
								
							}
							
						}
					}
				}
				
				$html = preg_replace('#<script[^<>]*?koko-analytics\/assets\/dist\/js\/script.js[^<>]*><\/script>#is', '', $html);
				
			}
			
			return $html;
		}		
			
		public static function dsgvoaio_disable_analytics_ob_start(){
			
			ob_start(__CLASS__ . '::dsgvoaio_disable_analytics_ob_end');
			
		}

		public static function dsgvoaio_disable_analytics_ob_end($html){

			$html = preg_replace('/<script[^<>]*\/\/(.*?)(googletagmanager).com\/[^<>]*><\/script>/i', '', $html);
			
			$html = preg_replace('/<script[^<>]*\/\/(.*?)(google-analytics).com\/[^<>]*><\/script>/i', '', $html);
			
			return $html;
			
		}
	 

		public static function dsgvoaio_notice_cacheplugins() {

			if (is_plugin_active( 'autoptimize/autoptimize.php' )) {

				$pluginname = "Autoptimze";

			} else if (is_plugin_active( 'wp-rocket/wp-rocket.php' )) {

				$pluginname = "WP Rocket";

			}		

		?>

			<script>

				jQuery( document ).ready( function() {

					jQuery( document ).on( 'click', '.dsgvocachepluginmsg .notice-dismiss', function() {

						var data = {

								action: 'dsgvoaio_dismiss_cache_msg',

						};

						jQuery.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(d) {

						});

					})	

				});	

			</script>

			<div class="notice notice-warning is-dismissible dsgvocachepluginmsg">

				<h3><?php echo __('Achtung - Cache / Minify Plugin gefunden!', 'dsgvo-all-in-one-for-wp'); ?></h3>

				<p><?php echo __('Sie verwenden', 'dsgvo-all-in-one-for-wp'); ?>

				<strong><?php echo esc_html($pluginname); ?></strong>.

				<?php echo __('Dies kann zu Problemen führen. Sie müssen die Plugineinstellungen eventuell anpassen  damit alles funktioniert sollte die Cookie Notice nicht angezeigt werden wenn unter Punkt #2 aktiviert. Weitere Infos dazu finden Sie auf der <a href="https://wordpress.org/plugins/dsgvo-all-in-one-for-wp/" target="blank">Wordpress Plugin Seite</a> unter FAQ.', 'dsgvo-all-in-one-for-wp'); ?>

				</p>

			</div>

		<?php 	

		}

		public static function dsgvoaio_disable_gtagmanager_ob_start(){

			ob_start(__CLASS__ . '::dsgvoaio_disable_gtagmanager_ob_end');	

		}	 

		public static function dsgvoaio_disable_gtagmanager_ob_end($html){

			if (strpos($html, 'googletagmanager.com/gtm.js') !== false) {

				$html = preg_replace('#https://(.*)googletagmanager.com/gtm.js#i', get_bloginfo('url')."/", $html);

			}

			if (strpos($html, 'googletagmanager.com/ns.html') !== false) {

				$html = preg_replace('#https://(.*)googletagmanager.com/ns.html#i', get_bloginfo('url')."/", $html);

			}			

			return $html;

		}

		public static function dsgvoaio_disable_vgwort_ob_start(){

			ob_start(__CLASS__ . '::dsgvoaio_disable_vgwort_ob_end');	

		}
		
		public static function dsgvoaio_disable_vgwort_ob_end($html){

			$debug = "";

			preg_match_all('/<img(.*?)src="(.*?)vgwort(.*?)">/', $html, $vgwortmatch);

			if (isset($vgwortmatch[0])) {

				foreach ($vgwortmatch[0] as $vgwortimg) {

					$debug .= $vgwortimg;

					if (isset($vgwortimg)) {

						$vgwortimgraw = $vgwortimg;

						$vgwortimg = str_replace('/', '\/',$vgwortimg);

						$html = preg_replace('/'.$vgwortimg.'/i' , '<div class="dsgvoaio_vgwort" data-vgwortcode="'.htmlentities($vgwortimgraw).'"></div>', $html);	

					}

				}

			}

			return $html;

		}			

	 

			public static function style_dsgvoaio($content = "") {

				if (get_option("dsgvo_notice_design") == "clear") {

					$content .= "
					.tarteaucitronInfoBox { color: #424242 !important; }
					.dsgvoaio_pol_header { background: #eaeaea;}
					.dsgvo_hide_policy_popup .dashicons {color: #424242 !important;}					

					#tarteaucitron #tarteaucitronServices .tarteaucitronMainLine {

						background: #eaeaea !important;

						border: 3px solid #eaeaea !important;

						border-left: 9px solid #eaeaea !important;

						border-top: 5px solid #eaeaea !important;

						margin-bottom: 0;

						margin-top: 21px;

						position: relative;

					}

					#tarteaucitron #tarteaucitronServices .tarteaucitronTitle a, #tarteaucitron b, #tarteaucitron #tarteaucitronServices .tarteaucitronMainLine .tarteaucitronName b, #tarteaucitron #tarteaucitronServices .tarteaucitronTitle, #tarteaucitronAlertSmall #tarteaucitronCookiesListContainer #tarteaucitronClosePanelCookie, #tarteaucitron #tarteaucitronClosePanel, #tarteaucitron #tarteaucitronServices .tarteaucitronMainLine .tarteaucitronName a, #tarteaucitron #tarteaucitronServices .tarteaucitronTitle a {

						color: #424242 !important;

					}

	

					#tarteaucitronAlertSmall #tarteaucitronCookiesListContainer #tarteaucitronCookiesList .tarteaucitronTitle, #tarteaucitron #tarteaucitronServices .tarteaucitronTitle, #tarteaucitron #tarteaucitronInfo, #tarteaucitron #tarteaucitronServices .tarteaucitronDetails {

						background: #eaeaea !important;

					}

					

					#tarteaucitronAlertSmall #tarteaucitronCookiesListContainer #tarteaucitronClosePanelCookie, #tarteaucitron #tarteaucitronClosePanel {

						background: #eaeaea !important;

						

					}

					

					#tarteaucitron .tarteaucitronBorder {

						background: #fff;

						border: 2px solid #eaeaea !important;

					}		



					#tarteaucitronAlertBig, #tarteaucitronManager {

						background: #eaeaea !important;

						color: #424242 !important;

					}	



					#tarteaucitronAlertBig #tarteaucitronCloseAlert {

						background: #ffffff !important;

						color: #424242 !important;

					}						

					.tac_activate {

						background: #eaeaea !important;

						color: #424242 !important;

					}	

					.tac_activate .tac_float b {

						color: #424242 !important;

					}

					

					

				 ";

				 if (get_option("dsgvo_notice_style") == "2") {

					$content .= ".dsdvo-cookie-notice.style2 #tarteaucitronAlertBig #tarinner {background: #eaeaea !important; border-radius: 5px;}";

					$content .= ".dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert, .dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert h1, .dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert h2, .dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert h3, .dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert h4, .dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert a  { color: #828080 !important; }";

					$content .= ".dsdvo-cookie-notice.style2 #tarteaucitronDisclaimerAlert a {text-decoration: underline;}";

				 }

				 if (get_option("dsgvo_notice_style") == "3") {

					$content .= ".dsdvo-cookie-notice.style3 #tarteaucitronAlertBig #tarinner {background: #eaeaea !important; border-radius: 5px;}";

					$content .= ".dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert, .dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert h1, .dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert h2, .dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert h3, .dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert h4, .dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert a  { color: #828080 !important; }";

					$content .= ".dsdvo-cookie-notice.style3 #tarteaucitronDisclaimerAlert a {text-decoration: underline;}";

				 }				 				
				
				$allowed_output_html = array(
						'script' => array(),
						'noscript' => array(),
						'iframe' => array(
								'src' => array(),
								'width' => array(),
								'height' => array(),
								'style' => array(),
						),
				);

				$allowed_output_protocol = array(
						'https',
						'javascript',
				);				 

				
				echo  "<style type='text/css'>".wp_kses( $content, $allowed_output_html, $allowed_output_protocol)."</style>";				

				}



			}	 



		public static function  dsgvo_remove_commentsip( $comment_author_ip ) {

			return '127.0.0.1';

		}

	 

		public static function dsgvoaio_activation_hook() {

			set_transient( 'dsgvoaioinstall-admin-notice', true, 5 );

		}
		
		
		public static function dsgvo_vimeo_func($atts) {
			
			$videoID = $atts['videoid'];
			
			if (isset($atts['width'])) {
				
				$width = $atts['width'];
				
				$nowidthclass = "withwidth";
				
			} else {
				
				$width = "100%";
				
				$nowidthclass = "nowidth";
				
			}

			if (isset($atts['height'])) {
				
				$height = $atts['height'];
				
			} else {
				
				$height = "350px";
				
			}			
			
			if (!$videoID) {
				
				$videoID = "x2p0ate";
				
			}
			
			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {
					
				$maindir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp/";
				
				$thumbnaildir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp/thumbnails/";
				
				$videodir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp/thumbnails/".$videoID;
				
					
				if(!file_exists($videodir)) {
					
					if(!file_exists($maindir)) {
						
						mkdir( $maindir );
						
					}

					if(!file_exists($thumbnaildir)) {
						
						mkdir( $thumbnaildir );
						
					}
					
					mkdir( $videodir );
				}
					
				$vimeo = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$videoID.".php"));
				
				$url = $vimeo[0]['thumbnail_large'];					
				
				$request = wp_remote_get( $url, array( 'timeout' => 220, 'httpversion' => '1.1' , 'stream' => true, 'filename'    => $videodir."/".$videoID.'.png' )  );
				
				$body = wp_remote_retrieve_body( $request );				
				
				$response = json_decode( $body );
				
				$error = ! ( isset ( $response->success ) && 1 == $response->success );				
				
			}

			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {
				
				$class = "display_bottom";
					
			} else {
				
				$class = "display_top";
				
			}	

			$uniqe = uniqid();
			
			$return = "";
			
			if (isset($width)) {
				
				$return .= "
					<style>
						.dsgvovimeo".$uniqe." { width: ".$width."; height: ".$height."; }
						.".$class.".dsgvovimeo".$uniqe." .tac_activate { width: ".$width."; }
					@media only screen and (max-width: $width) {
						.".$class.".dsgvovimeo".$uniqe." .tac_activate { width: 100%; }
						.dsgvovimeo".$uniqe." { width: 100% !important;}
					}
					</style>";	
					
			}			
				
			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {	
				
				$return .= '<style>.display_bottom.dsgvovimeo'.$uniqe.' .tac_activate {background: transparent !Important;position: absolute; bottom: 0px;} .display_bottom.dsgvovimeo'.$uniqe.' .tac_activate {    position: absolute; bottom: 0px;} .display_bottom.dsgvovimeo'.$uniqe.' .tac_float { display: block; bottom: 0px; position: absolute; padding: 15px 0px 15px 0px; background: rgb(51, 51, 51, .8); width: 100%; } .display_bottom.dsgvovimeo'.$uniqe.' {     float: left; position: relative; } </style>';
			
				$return .= '<div class="dsgvoaio_vimeo_wrap" style="width:'.$width.'; height:'.$height.';"><div class="vimeo_player '.$class.' dsgvovimeo'.$uniqe.'" thumb="'.content_url().'/dsgvo-all-in-one-wp/thumbnails/'.$videoID.'/'.$videoID.'.png"   videoID="'.$videoID.'" width="'.$width.'" height="'.$height.'"></div></div>';
				
				return $return;
				
			} else {
				
				return '<div class="dsgvoaio_vimeo_wrap" style="width:'.$width.'; height:'.$height.';"><div class="vimeo_player shortcodenothumb '.$nowidthclass.' dsgvovimeo'.$uniqe.'"  videoID="'.$videoID.'" width="'.$width.'" height="'.$height.'" style="width:'.$width.'; height:'.$height.';"></div></div>';
				
			}
			
		}			
		
		
		public static function dsgvo_youtube_func($atts) {
			
			$videoID = $atts['videoid'];
			
			$r = "";
			
			if (isset($atts['width'])) {
				
				$width = $atts['width'];
				
				$nowidthclass = "withwidth";
				
			} else {
				
				$width = "100%";
				
				$nowidthclass = "nowidth";
				
			}

			if (isset($atts['height'])) {
				
				$height = $atts['height'];
				
			} else {
				
				$height = "350px";
				
			}

			if (isset($atts['controls'])) {
				
				$controls = $atts['controls'];
				
			} else {
				
				$controls = "1";
				
			}

			if (isset($atts['autoplay'])) {
				
				$autoplay = $atts['autoplay'];
				
			} else {
				
				$autoplay = "0";
				
			}	

			if (isset($atts['rel'])) {
				
				$rel = $atts['rel'];
				
			} else {
				
				$rel = "0";
				
			}	
		
			
			if (!$videoID) {
				
				$videoID = "Bey4XXJAqS8";
				
			}
			
			
			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {
					
				$maindir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp-pro/";
				
				$thumbnaildir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp-pro/thumbnails/";
				
				$videodir = WP_CONTENT_DIR ."/dsgvo-all-in-one-wp-pro/thumbnails/".$videoID;
					
				if(!file_exists($videodir)) {
					
					if(!file_exists($maindir)) {
						
						mkdir( $maindir );
						
					}

					if(!file_exists($thumbnaildir)) {
						
						mkdir( $thumbnaildir );
						
					}
					
					mkdir( $videodir );
				}
					
				$url = "http://img.youtube.com/vi/".$videoID."/maxresdefault.jpg";
				
				$request = wp_remote_get( $url, array( 'timeout' => 220, 'httpversion' => '1.1' , 'stream' => true, 'filename'    => $videodir."/".$videoID.'.png' )  );
				
				$body = wp_remote_retrieve_body( $request );
				
				$response = json_decode( $body );
				
				$error = ! ( isset ( $response->success ) && 1 == $response->success );				
			
			}
			
			
			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {
				
				$class = "display_bottom";
				
			} else {
				
				$class = "display_top";
				
			}				
			
			$uniqe = uniqid();
			
			if (isset($width)) {
				$r .= "
					<style>
						.ytu_".$uniqe." { width: ".$width."; height: ".$height."; }
						.".$class.".ytu_".$uniqe." .tac_activate { width: ".$width."; }
					@media only screen and (max-width: $width) {
						.".$class.".ytu_".$uniqe." .tac_activate { width: 100%; }
						.ytu_".$uniqe." { width: 100% !important;}
					}
					</style>";
			}
			
			if (get_option("dsgvo_notice_design") == "clear") { $notice_design = "clear"; } else { $notice_design = "dark"; }

			if (isset($atts['thumbnail']) && $atts['thumbnail'] == "true") {
				
				$r .= '<style>.display_bottom.ytu_'.$uniqe.' .tac_activate {background: transparent !Important;position: absolute; bottom: 0px;} .display_bottom.ytu_'.$uniqe.' {position: relative; } </style>';
				
				$r .=  '<div class="youtube_player '.$class.' ytu_'.$uniqe.' yt_player_'.$notice_design.'" thumb="'.content_url().'/dsgvo-all-in-one-wp-pro/thumbnails/'.$videoID.'/'.$videoID.'.png"  videoID="'.$videoID.'" width="'.$width.'" height="'.$height.'" theme="light" controls="'.$controls.'" rel="'.$rel.'" autoplay="'.$autoplay.'"></div>';

			} else if (filter_var($atts['thumbnail'], FILTER_VALIDATE_URL)) {
				
				$r .= '<style>.display_bottom.ytu_'.$uniqe.' .tac_activate {background: transparent !Important;position: absolute; bottom: 0px;} .display_bottom.ytu_'.$uniqe.' {position: relative; } </style>';
				
				$r .=  '<div class="youtube_player '.$class.' ytu_'.$uniqe.' yt_player_'.$notice_design.'" thumb="'.$atts['thumbnail'].'"  videoID="'.$videoID.'" width="'.$width.'" height="'.$height.'" theme="light" controls="'.$controls.'" rel="'.$rel.'" autoplay="'.$autoplay.'"></div>';
				
			} else {
				
				$r .=  '<div class="youtube_player shortcodenothumb '.$nowidthclass.' ytu_'.$uniqe.' yt_player_'.$notice_design.'" style="width: '.$width.'; height: '.$height.'" videoID="'.$videoID.'" width="'.$width.'" height="'.$height.'" theme="light" controls="'.$controls.'" rel="'.$rel.'" autoplay="'.$autoplay.'"></div>';
				
			}

			return $r;
		}		


		public static function dsgvo_shareaholic_func($atts) {
			

				return '<div class="shareaholic-canvas" data-app="share_buttons" data-app-id="'.get_option("dsdvo_shareaholicappid").'"></div>';
			
		}

			

		public static function dsgvo_linkedin_func(){

			return '<div><div class="tacLinkedin"></div><script type="IN/Share" data-counter="right"></script></div>';

		}

		

		public static function dsgvo_addthis_func(){

			return '<div class="addthis_sharing_toolbox"></div>';

		}

		

		public static function dsgvo_twitter_button_func(){

			$twitter_username = get_option('dsdvo_twitterusername');



			if (isset($atts['datacount'])) {

				$datacount = $atts['datacount'];

			} else {

				$datacount = "vertical";

			}	



			if (isset($atts['height'])) {

				$height = $atts['height'];

			} else {

				$height = "640px";

			}

				

			if (empty($twitter_username)) {

				$twitter_username = "";

			}

			return '<div><div class="tacTwitter"></div><a href="https://twitter.com/share" class="twitter-share-button" data-via="'.$twitter_username.'" data-count="'.$datacount.'" data-dnt="true"></a></div>';

		}

		

		public static function dsgvo_service_control_func(){

			return '<style>#tarteaucitronAlertSmall, #tarteaucitronManager { display: none !important;} #dsgvo_service_control #tarteaucitronServices {position: relative; float: left; width: 100%;}  #tarteaucitronBack, #tarteaucitronAlertBig { display: none !important;}</style><div id="dsgvo_service_control"></div>';

		}

		

		public static function dsgvo_facebooklike_func(){

			return '<div  class="fb-like" data-layout="box_count" data-action="like" data-share="true"></div>';

		}

		

		public static function dsgvo_facebookcommentar_func(){

			return '<div class="fb-comments" data-numposts="5" data-colorscheme="light" data-href="'.get_site_url().'"></div>';

		}		 

		

		public static function dsgvo_vgwort_func($atts){

			if (isset($atts['id'])) {

				$code = htmlentities('<img src="https://ssl-vg03.met.vgwort.de/na/'.$atts['id'].'" class="wp-worthy-pixel-img" data-no-lazy="1" height="1" width="1" alt="" />');

				return '<div class="dsgvoaio_vgwort" data-vgwortcode="'.$code.'"></div>';

			} else {

				return __("No ID defined in shortcode!", "dsgvo-all-in-one-for-wp");

			}				

		}
	 

		public static function dsgvo_ajax_notice_handler() {

			update_option( 'dismissed-dsgvo_msg_after_update_31', TRUE );

			delete_transient( 'dsgvoaioupdate-admin-notice31' );

		}


		public static function dsgvoaio_upgrade_completed( $upgrader_object, $options ) {

		 $our_plugin = plugin_basename( __FILE__ );

		 if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

		  foreach( $options['plugins'] as $plugin ) {

		   if( $plugin == $our_plugin ) {

				
			$is_showed_31 = get_option( 'dismissed-dsgvo_msg_after_update_31', 'empty');
			$is_showed_32 = get_option( 'dismissed-dsgvoaio_update_msg_32', 'empty');
			
			if ($is_showed_31 == 'empty') {
				set_transient( 'dsgvoaioupdate-admin-notice31' , '1' );				
			}
			if ($is_showed_32 == 'empty') {
				update_option( 'dsgvoaioupdate-admin-notice32', '1', false );				
			}
			
			update_option( 'dsgvoaioupdate-admin-notice33', '1', false );

		   }

		  }

		 }

		}	 


		public static function dsdvo_my_comment_form_field_comment( $comment_field ) {

            $dsdvo_policy_site = get_option("dsdvo_policy_site");
			
			$dsdvo_kses_allowed = dsdvo_wp_frontend::dsdvo_kses_allowed();

			if (!isset($language)) $language = wf_get_language();

				if ($language == "de") {
					$text = html_entity_decode(wp_kses(get_option("dsgvo_policy_blog_text"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));
				} else if ($language == "en") {
					$text = html_entity_decode(wp_kses(get_option("dsgvo_policy_blog_text_en"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				} else if ($language == "it"){
					$text = html_entity_decode(wp_kses(get_option("dsgvo_policy_blog_text_it"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				} else {
					$text = html_entity_decode(wp_kses(get_option("dsgvo_policy_blog_text"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				}


	        echo '<div id="comment_datenschutz"><p class="pprivacy"><input type="checkbox" name="privacy" value="privacy-key" class="privacyBox" aria-req="true"><span class="required">*</span> '.$text.' <p></div>';

		}


		public static function dsdvo_valdate_privacy_comment_javascript(){

			if (is_single() && comments_open()){
				
				$dsdvo_kses_allowed = dsdvo_wp_frontend::dsdvo_kses_allowed();
				
				wp_enqueue_script('jquery');

				if (!isset($language)) $language = wf_get_language();

				if ($language == "de") {
					
					
					$text = html_entity_decode(wp_kses(get_option("dsgvo_error_policy_blog"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));
				
				} else if ($language == "en") {
					
					$text = html_entity_decode(wp_kses(get_option("dsgvo_error_policy_blog_en"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				
				} else if ($language == "it"){
					
					$text = html_entity_decode(wp_kses(get_option("dsgvo_error_policy_blog_it"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				
				} else {
					
					$text = html_entity_decode(wp_kses(get_option("dsgvo_error_policy_blog"), $dsdvo_kses_allowed), ENT_COMPAT, get_option('blog_charset'));				
				
				}
				
				?>

				<script type="text/javascript">

				jQuery(document).ready(function($){

					$("#submit").click(function(e){

						if (!$('.privacyBox').prop('checked')){

							e.preventDefault();

							alert('<?php echo $text; ?>');

							return false;

						}

					})

				});

				</script>

				<?php

			}

		}


		public static function dsdvo_verify_comment_privacy( $commentdata ) {

			if ( ! isset( $_POST['privacy'] ) )

				wp_die( __( 'Error: You must accept the privacy policy to post a comment...' , "dsgvo-all-in-one-for-wp") );



			return $commentdata;

		}


		public static function dsdvo_save_comment_privacy( $comment_id ) {

			add_comment_meta( $comment_id, 'privacy', sanitize_text_field($_POST[ 'privacy' ]) );

		}		

		
		public static function dsdvo_kses_allowed() {

			$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i' => array(),
				'img' => array(
					'alt'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
			);
			
			return $allowed_tags;
		}
		

		public static function dsgvoaio_control_func() {

			if (get_option("dsdvo_show_servicecontrol") == "on") {

				$dsgvoaio_control_style = "";

				if (get_option("dsgvo_position_service_control")) {

					$position_service_control = get_option("dsgvo_position_service_control");

				} else {

					$position_service_control = "topright";

				}

				if ($position_service_control == "bottomleft") {

					$dsgvoaio_control_style .= "

						.tarteaucitronAlertSmallTop {

							top: auto !important;

							bottom: 0 !important;

							left: 0 !important;

							right: auto !important;

						}			

					";

				}	

				if ($position_service_control == "bottomright") {

					$dsgvoaio_control_style .= "

						.tarteaucitronAlertSmallTop {

							top: auto !important;

							bottom: 0 !important;

							left: auto !important;

							right: 0 !important;

						}			

					";

				}			

				if ($position_service_control == "topleft") {

					$dsgvoaio_control_style .= "

						.tarteaucitronAlertSmallTop {

							top: 0;

							left: 0 !important;

							right: auto !important;

						}			

					";

				}

				wp_register_style( 'dsgvoaio_control', false );

				wp_enqueue_style( 'dsgvoaio_control' );

				wp_add_inline_style( 'dsgvoaio_control', $dsgvoaio_control_style );	

			}

		}


		public static function dsdvo_wp_add_scripts() {

			$is_elementor_preview = false;

			if( is_plugin_active( 'elementor/elementor.php' ) ) {

				if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {

					 $is_elementor_preview = true;

				} else {

					$is_elementor_preview = false;

				}

			}			
			
			wp_enqueue_style('dashicons');			

			wp_register_style('dsgvoaio_frontend_css', plugins_url('assets/css/plugin.css',__FILE__ ));

			wp_enqueue_style('dsgvoaio_frontend_css');

			wp_enqueue_script('jquery');			
			
			if ($is_elementor_preview == false) {

				$cookietextscroll = "Durch das fortgesetzte bl&auml;ttern stimmen Sie der Nutzung von externen Diensten und Cookies zu.";

				wp_enqueue_script('dsdvo_tarteaucitron', plugins_url('assets/js/tarteaucitron/tarteaucitron.min.js',__FILE__ ));			

				$animation_time = get_option("dsgvo_animation_time", "1000");
					
				if (get_option("dsdvo_policy_site")) { $dsdvo_policy_site = get_option("dsdvo_policy_site"); } else { $dsdvo_policy_site = ""; }

				if (get_option("dsgvo_btn_txt_reject_url")) { $dsgvo_btn_txt_reject_url = get_option("dsgvo_btn_txt_reject_url"); } else { $dsgvo_btn_txt_reject_url = "www.google.de"; }

				if (get_option("dsdvo_show_rejectbtn")) { $dsdvo_show_rejectbtn = get_option("dsdvo_show_rejectbtn"); } else { $dsdvo_show_rejectbtn = "off"; }

				if (get_option("dsgvo_notice_design") == "clear") { $notice_design = "clear"; } else { $notice_design = "dark"; }	

				$auto_accept =  get_option("dsdvo_auto_accept");

				if ($auto_accept == "on") {

					update_option("dsdvo_auto_accept", "off");

				}

				if (!isset($language)) $language = wf_get_language();

				if ($language == "de") {

					$accepttext = "Alle erlauben";

					$denytext = "Alle ablehnen";

					$deactivatedtext = "ist deaktiviert.";

					$closetext = "Beenden";

					$cookietextusage = "Gespeicherte Cookies:";	

					$linkto = "Zur offiziellen Webseite";

					$cookietextusagebefore = "Folgende Cookies können gespeichert werden:";

					$usenocookies = "Dieser Dienst nutzt keine Cookies.";

					$nocookietext = "Dieser Dienst hat keine Cookies gespeichert.";

					$cookiedescriptiontext = "Wenn Sie diese Dienste nutzen, erlauben Sie deren 'Cookies' und Tracking-Funktionen, die zu ihrer ordnungsgemäßen Funktion notwendig sind.";

					$maincatname = "Allgemeine Cookies";

					$showpolicyname = "Datenschutzbedingungen / Cookies angezeigen";

					$yeslabel = "JA";

					$nolabel = "NEIN";	

					$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();

					if (get_option("dsdvo_outgoing_text")) { $outgoing_text = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_outgoing_text"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $outgoing_text = "<p><strong>Sie verlassen nun unsere Internetpräsenz</strong></p><p>Da Sie auf einen externen Link geklickt haben verlassen Sie nun unsere Internetpräsenz.</p><p>Sind Sie damit einverstanden so klicken Sie auf den nachfolgenden Button:</p>"; }									

					if (get_option("dsdvo_cookie_text")) { $cookietextnotice = wp_kses(wpautop(html_entity_decode(stripslashes(get_option("dsdvo_cookie_text")), ENT_COMPAT, get_option('blog_charset'))), $kses_allowed_html); } else { $cookietextnotice = "Wir verwenden technisch notwendige Cookies auf unserer Webseite sowie externe Dienste.<br />Standardmäßig sind alle externen Dienste deaktiviert. Sie können diese jedoch nach belieben aktivieren & deaktivieren.<br/>Für weitere Informationen lesen Sie unsere Datenschutzbestimmungen."; }				

					if (get_option("dsdvo_cookie_text_scroll")) { $onscrolltext = wp_kses(wpautop(html_entity_decode(stripslashes(get_option("dsdvo_cookie_text_scroll")), ENT_COMPAT, get_option('blog_charset'))), $kses_allowed_html); } else { $onscrolltext = "Durch das fortgesetzte blättern stimmen Sie der Benutzung von externen Diensten zu."; }								 
					 
					if (get_option("dsgvo_btn_txt_accept")) { $cookieaccepttext = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_accept")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $cookieaccepttext =  "Akzeptieren"; }

					if (get_option("dsgvo_btn_txt_customize")) { $btncustomizetxt = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_customize")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $btncustomizetxt =  "Personalisieren"; }

					if (get_option("dsgvo_btn_txt_reject")) { $dsgvo_btn_txt_reject = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject = "Ablehnen"; }

					if (get_option("dsgvo_btn_txt_reject_text")) { $dsgvo_btn_txt_reject_text = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject_text")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject_text = "Sie haben die Bedingungen abgelehnt. Sie werden daher auf google.de weitergeleitet."; }			

					if (get_option("dsdvo_policy_text_1")) { $policytextnotice = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_policy_text_1"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $policytextnotice = ""; }			

					$youtube_layer = wp_kses(html_entity_decode(get_option("dsdvo_youtube_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$vimeo_layer = wp_kses(html_entity_decode(get_option("dsdvo_vimeo_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$linkedin_layer = wp_kses(html_entity_decode(get_option("dsdvo_linkedin_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);	
					
					$twitter_layer = wp_kses(html_entity_decode(get_option("dsdvo_twitter_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$shareaholic_layer = wp_kses(html_entity_decode(get_option("dsdvo_shareaholic_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$vgwort_layer = wp_kses(html_entity_decode(get_option("dsdvo_vgwort_layer"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
				
				}

				if ($language == "en") {

					$accepttext = "Allow";

					$denytext = "Deny";	

					$deactivatedtext = "is inactive.";

					$closetext = "Close";

					$cookietextusage = "Used Cookies:";	

					$cookietextusagebefore = "This Cookies can be stored:";				

					$linkto = "To the official website";	

					$usenocookies = "This Servies use no Cookies.";

					$nocookietext = "This Service use currently no Cookies.";	

					$cookiedescriptiontext = "By using these services, you allow their 'cookies' and tracking features necessary for their proper functioning.";

					$maincatname = "General Cookies";

					$showpolicyname = "Show Privacy Policy / Cookie Details";

					$yeslabel = "YES";

					$nolabel = "NO";

					if (get_option("dsdvo_outgoing_text_en")) { $outgoing_text = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_outgoing_text_en"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $outgoing_text = "<p><b>You are now leaving our Internet presence</b></p><p>As you have clicked on an external link you are now leaving our website.</p><p>If you agree to this, please click on the following button:</p>"; }				

					if (get_option("dsdvo_cookie_text_en")) { $cookietextnotice = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_cookie_text_en"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $cookietextnotice = "We use technically necessary cookies on our website and external services.<br/>By default, all services are disabled. You can turn or off each service if you need them or not.<br />For more informations please read our privacy policy."; }				

					if (get_option("dsdvo_cookie_text_scroll_en")) { $onscrolltext = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_cookie_text_scroll_en"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $onscrolltext = "By continuing to scroll, you consent to the use of external services."; }				

					if (get_option("dsgvo_btn_txt_accept_en")) { $cookieaccepttext = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_accept_en")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $cookieaccepttext =  "Accept"; }

					if (get_option("dsgvo_btn_txt_customize_en")) { $btncustomizetxt = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_customize_en")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $btncustomizetxt =  "Customize"; }

					if (get_option("dsgvo_btn_txt_reject_en")) { $dsgvo_btn_txt_reject = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject_en")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject = "Reject"; }

					if (get_option("dsgvo_btn_txt_reject_text_en")) { $dsgvo_btn_txt_reject_text = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject_text_en")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject_text = "You have rejected the conditions. You will be redirected to google.com."; }			

					if (get_option("dsdvo_policy_text_en")) { $policytextnotice = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_policy_text_en"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $policytextnotice = ""; }			

					$youtube_layer = wp_kses(html_entity_decode(get_option("dsdvo_youtube_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$vimeo_layer = wp_kses(html_entity_decode(get_option("dsdvo_vimeo_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$linkedin_layer = wp_kses(html_entity_decode(get_option("dsdvo_linkedin_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);	
					
					$twitter_layer = wp_kses(html_entity_decode(get_option("dsdvo_twitter_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$shareaholic_layer = wp_kses(html_entity_decode(get_option("dsdvo_shareaholic_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);				
					
					$vgwort_layer = wp_kses(html_entity_decode(get_option("dsdvo_vgwort_layer_en"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);	
				
				}
				
				if ($language == "it") {

					$accepttext = "Permetti";

					$denytext = "Negare";	

					$deactivatedtext = "è inattivo.";

					$closetext = "Chiudere";

					$cookietextusage = "Biscotti usati:";	

					$cookietextusagebefore = "Questo Cookie può essere memorizzato:";				

					$linkto = "Al sito ufficiale";	

					$usenocookies = "This Servies use no Cookies.";

					$nocookietext = "Questo servizio non utilizza attualmente alcun cookie.";	

					$cookiedescriptiontext = "Utilizzando questi servizi, l'utente consente i loro 'cookies' e le funzioni di tracciamento necessarie per il loro corretto funzionamento.";

					$maincatname = "Cookies generali";

					$showpolicyname = "Mostra l'Informativa sulla privacy / Dettagli sui cookie";

					$yeslabel = "SI";

					$nolabel = "NO";

					if (get_option("dsdvo_outgoing_text_it")) { $outgoing_text = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_outgoing_text_it"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $outgoing_text = "<p><b>Stai lasciando la nostra presenza su Internet</b></p><p>Quando hai cliccato su un link esterno stai lasciando il nostro sito web.</p><p><p>Se sei d'accordo, clicca sul seguente pulsante:</p>."; }				

					if (get_option("dsdvo_cookie_text_it")) { $cookietextnotice = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_cookie_text_it"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $cookietextnotice = "Utilizziamo i cookie tecnicamente necessari sul nostro sito web e sui servizi esterni.<br/>Per impostazione predefinita, tutti i servizi sono disabilitati. È possibile disattivare o disattivare ogni servizio se ne avete bisogno o meno.<br /> Per ulteriori informazioni si prega di leggere la nostra informativa sulla privacy."; }				

					if (get_option("dsdvo_cookie_text_scroll_it")) { $onscrolltext = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_cookie_text_scroll_it"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $onscrolltext = "Continuando a scorrere, l'utente acconsente all'utilizzo di servizi esterni."; }				

					if (get_option("dsgvo_btn_txt_accept_it")) { $cookieaccepttext = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_accept_it")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $cookieaccepttext =  "Accetta"; }

					if (get_option("dsgvo_btn_txt_customize_it")) { $btncustomizetxt = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_customize_it")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $btncustomizetxt =  "Personalizza"; }

					if (get_option("dsgvo_btn_txt_reject_it")) { $dsgvo_btn_txt_reject = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject_it")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject = "Rifiuta"; }

					if (get_option("dsgvo_btn_txt_reject_text_it")) { $dsgvo_btn_txt_reject_text = wp_kses(html_entity_decode(stripslashes(get_option("dsgvo_btn_txt_reject_text_it")), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $dsgvo_btn_txt_reject_text = "Avete rifiutato le condizioni. Verrai reindirizzato a google.com."; }			

					if (get_option("dsdvo_policy_text_it")) { $policytextnotice = wp_kses(html_entity_decode(stripslashes(wpautop(get_option("dsdvo_policy_text_it"))), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html); } else { $policytextnotice = ""; }			

					$youtube_layer = wp_kses(html_entity_decode(get_option("dsdvo_youtube_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$vimeo_layer = wp_kses(html_entity_decode(get_option("dsdvo_vimeo_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$linkedin_layer = wp_kses(html_entity_decode(get_option("dsdvo_linkedin_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);	
					
					$twitter_layer = wp_kses(html_entity_decode(get_option("dsdvo_twitter_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$shareaholic_layer = wp_kses(html_entity_decode(get_option("dsdvo_shareaholic_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
					
					$vgwort_layer = wp_kses(html_entity_decode(get_option("dsdvo_vgwort_layer_it"), ENT_COMPAT, get_option('blog_charset')), $kses_allowed_html);
				
				}				

				if ( is_plugin_active( 'polylang/polylang.php' )) {

					$polylangcookie = "pll_language";

				} else {

					$polylangcookie = "";

				}

				if ( is_plugin_active( 'woocommerce/woocommerce.php' )) {

					$woocommerce_cookies = array('woocommerce_cart_hash', 'woocommerce_items_in_cart', 'wp_woocommerce_session_{}', 'woocommerce_recently_viewed', 'store_notice[notice id]', 'tk_ai');

				} else {

					$woocommerce_cookies = " ";

				}

				$gaid = get_option("dsdvo_gaid");

				$fbpixelid = get_option("dsdvo_fbpixelid");	

				$cookie_time = get_option('dsdvo_cookie_time');

				if (!$cookie_time) { $cookie_time = 1;}

				$auto_accept =  get_option("dsdvo_auto_accept");

				if ($auto_accept == "on") { $highprivacy = "false";} else { $highprivacy = "true"; }

				if (!$auto_accept) { $highprivacy = "true"; }

				$cookie_not_acceptet = wp_kses(get_option('cookie_not_acceptet_text'), $kses_allowed_html);

				$cookie_not_acceptet_url_1 = wp_kses(get_option('cookie_not_acceptet_url'), $kses_allowed_html);

				$use_dnt = get_option("dsdvo_use_dnt");

				if ($use_dnt == "" or $use_dnt == "on") { $use_dnt = "true";} else { $use_dnt = "false"; }

				$use_dnt = "false";
				
				if ( is_plugin_active( 'polylang/polylang.php' ) or is_plugin_active( 'translatepress-multilingual/index.php' )) {

					if ( is_plugin_active( 'polylang/polylang.php' )) {
						
					if ( !function_exists( 'pll_the_languages' ) ) { 
					
						require_once WP_PLUGIN_DIR .'/polylang/include/api.php'; 
						
					} 
					
					$switcher = pll_the_languages([
					
						'echo' => 0,
						
						'hide_if_empty' => 1,
						
						'dropdown' => 0,
						
						'show_names' => 0,
						
						'show_flags' => 1,
						
						'hide_current' => 0,
						
						]);		

					}
					
					if ( is_plugin_active( 'translatepress-multilingual/index.php' )) {
						
					 $switcher = "";
					 
					}			

					$languageswitcher = '<ul class="dsgvo_lang_switcher">'.$switcher.'</ul>';
			
				} else {
					
					$languageswitcher = ' ';
					
				}	

				$show_outgoing_notice = wp_kses(get_option('dsdvo_show_outgoing_notice', 'empty'), $kses_allowed_html);

				$script = 'jQuery( document ).ready(function() {';

				$script .= '
					
					tarteaucitron.init({

						"hashtag": "#tarteaucitron",

						"cookieName": "dsgvoaiowp_cookie", 

						"highPrivacy": '.$highprivacy.',

						"orientation": "center",

						"adblocker": false, 

						"showAlertSmall": true, 

						"cookieslist": true, 

						"removeCredit": true, 

						"expireCookie": '.$cookie_time.', 

						"handleBrowserDNTRequest": '.$use_dnt.', 

						//"cookieDomain": ".'.$_SERVER['SERVER_NAME'].'" 

						"removeCredit": true, 

						"moreInfoLink": false, 

						});
					});	
				';
				
				if ($show_outgoing_notice == 'on') {
					
				$script .= '
					jQuery(document).on("click", \'a[href^="http"]:not([href*="://\' + document.domain + \'"])\', function(e) {
						var dsgvoaioclass = jQuery(this).attr("class");
						if (dsgvoaioclass !== "dsgvoaio_btn_1 dsgvoaio_outgoing_btn") {
							tarteaucitron.userInterface.showOutgoingMsg(jQuery(this).attr(\'href\'));
							event.preventDefault();	
						}						
					});
				';
				
				}				
				
				if ($language == "en") {

					$script .= "var tarteaucitronForceLanguage = 'en'";
					
					$accepttext = "Allow";
					
					$policytextbtn = "Privacy Policy";
					
				}

				if ($language == "de") {

					$script .= "var tarteaucitronForceLanguage = 'de'";
					
					$accepttext = "Zulassen";
					
					$policytextbtn = "Datenschutzbedingungen";
					
				}	
				
				if ($language == "it") {

					$script .= "var tarteaucitronForceLanguage = 'it'";
					
					$accepttext = "Consentire";
					
					$policytextbtn = "Informativa sulla privacy";
					
				}	
				
				$close_popup_auto = get_option("dsdvo_close_popup_auto", "off");

				$pixelorderid = "";
				
				$pixelevent = "";
				
				$isbuyedsendet = "";
				
				$pixeleventamount = "";
				
				$pixeleventcurrency = "";
				
				$fbpixel_content_name = "";
				
				$fbpixel_product_cat = "";
				
				$fbpixel_product_price = "";
				
				$fbpixel_currency = "";
				
				$fbpixel_content_ids = "";
				
				$fbpixel_content_type = "";				
				
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					
					if (isset($_SESSION['fbpixel_content_name'])) {
						
						$fbpixel_content_name = esc_html($_SESSION['fbpixel_content_name']);
						
					} else {
						
						$fbpixel_content_name = "";
						
					}
					
					if (isset($_SESSION['fbpixel_product_cat'])) {
						
						$fbpixel_product_cat = esc_html($_SESSION['fbpixel_product_cat']);
						
					} else {
						
						$fbpixel_product_cat = "";
						
					}					
					if (isset($_SESSION['fbpixel_product_price'])) {
						
						$fbpixel_product_price = esc_html($_SESSION['fbpixel_product_price']);
						
					} else {
						
						$fbpixel_product_price = "";
						
					}		
					
					if (isset($_SESSION['fbpixel_currency'])) {
						
						$fbpixel_currency = esc_html($_SESSION['fbpixel_currency']);
						
					} else {
						
						$fbpixel_currency = "";
						
					}
					
					if (isset($_SESSION['fbpixel_content_ids'])) {
						
						$fbpixel_content_ids = esc_html($_SESSION['fbpixel_content_ids']);
						
					} else {
						
						$fbpixel_content_ids = "";
						
					}
					
					if (isset($_SESSION['fbpixel_content_type'])) {
						
						$fbpixel_content_type = esc_html($_SESSION['fbpixel_content_type']);
						
					} else {
						
						$fbpixel_content_type = "";
						
					}		
					
					if (is_checkout() == true && !is_order_received_page()) {
						
						$pixelevent = "InitiateCheckout";
						
					} else if (isset($_SESSION['fbpixelevent']) && $_SESSION['fbpixelevent'] == "AddToCart") {
						
						$pixelevent = "AddToCart";
						
					} else if (is_order_received_page()) {
						
						if( is_wc_endpoint_url( 'order-received' ) ) {
							
							global $wp;
							
							$order_id  = absint( $wp->query_vars['order-received'] );
							
							$pixelorderid = $order_id;
							
							if ($pixelorderid == "") {
								
								$pixelorderid = " ";
								
							}
							
							$shouldpurchasesend = get_post_meta( $order_id, 'dsgvoaio_fbpixel_purchase', 'false' );
							
							if ($shouldpurchasesend == "true") {
								
								$isbuyedsendet = "true";
									
							} else {
								
								$isbuyedsendet = "false";
								
							}	
							
							if ($isbuyedsendet == "true") {
								
								$pixelevent = "Purchase";
								
							} else {
								
								$pixelevent = "PageView";
								
							}
							
							$pixelevent = "Purchase";
							
							$amount = "";
							
							if ( empty($order_id) || $order_id == 0 ) {
								
								$amount = "0";
							
							} else {
								
								$order = wc_get_order( $order_id );
								
								$pixeleventamount = $order->get_total();
								
								$pixeleventcurrency = $order->get_currency();
								
							}
						} else {
							
							$pixelevent = "PageView";
							
						} 	
					} else  {
						
						$pixelevent = "PageView";
						
					}
			
				}	

				$show_layertext = get_option("dsdvo_show_layertext");	

				$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
				
				$plugin_version = $plugin_data['Version'];				
			
				wp_localize_script( 'dsdvo_tarteaucitron', 'parms', array('version' => $plugin_version, 'close_popup_auto' => $close_popup_auto, 'animation_time' => $animation_time, 'nolabel' => $nolabel, 'yeslabel' => $yeslabel, 'showpolicyname' => $showpolicyname,'maincatname' => $maincatname, 'language' => $language, 'woocommercecookies' => $woocommerce_cookies, 'polylangcookie' => $polylangcookie, 'usenocookies' => $usenocookies, 'nocookietext' => $nocookietext, 'cookietextusage' => $cookietextusage, 'cookietextusagebefore' => $cookietextusagebefore, 'adminajaxurl' => admin_url('admin-ajax.php'), 'vgwort_defaultoptinout' => get_option('dsdvo_vgwort_optinoutsetting'), 'koko_defaultoptinout' => get_option('dsdvo_koko_optinoutsetting'), 'ga_defaultoptinout' => get_option('dsdvo_ga_optinoutsetting'), 'notice_design' =>  $notice_design, 'expiretime' =>  get_option("dsdvo_cookie_time"), 'noticestyle' =>  'style'.get_option("dsgvo_notice_style", "3"), 'backgroundcolor' => '#333', 'textcolor' => '#ffffff', 'buttonbackground' => '#fff', 'buttontextcolor' => '#333', 'buttonlinkcolor' => get_option("dsgvo_cookienotice_linkcolor"), 'cookietext' => wp_kses($cookietextnotice, $kses_allowed_html), 'cookieaccepttext' => $cookieaccepttext, 'btn_text_customize' => $btncustomizetxt, 'cookietextscroll' => $cookietextscroll, 'policyurl' => esc_url( get_permalink($dsdvo_policy_site) ), 'policyurltext' => 'Hier finden Sie unsere Datenschutzbestimmungen', 'ablehnentxt' => $dsgvo_btn_txt_reject, 'ablehnentext' => $dsgvo_btn_txt_reject_text, 'ablehnenurl' => $dsgvo_btn_txt_reject_url, 'showrejectbtn' => $dsdvo_show_rejectbtn, 'popupagbs' => dsgvo_show_policy_popup(), 'languageswitcher' => $languageswitcher, 'pixelorderid' => $pixelorderid, 'fbpixel_content_type' => $fbpixel_content_type, 'fbpixel_content_ids' => $fbpixel_content_ids, 'fbpixel_currency' => $fbpixel_currency, 'fbpixel_product_cat' => $fbpixel_product_cat, 'fbpixel_content_name' => $fbpixel_content_name, 'fbpixel_product_price' => $fbpixel_product_price, 'isbuyedsendet' => $isbuyedsendet, 'pixelevent' => $pixelevent, 'pixeleventcurrency' => $pixeleventcurrency, 'pixeleventamount' => $pixeleventamount, 'outgoing_text' => $outgoing_text, 'youtube_spt' => $youtube_layer, 'twitter_spt' => $twitter_layer, 'linkedin_spt' => $linkedin_layer, 'shareaholic_spt' => $shareaholic_layer, 'vimeo_spt' => $vimeo_layer, 'vgwort_spt' => $vgwort_layer, 'accepttext' => $accepttext, 'policytextbtn' => $policytextbtn, 'show_layertext' => $show_layertext));

				wp_enqueue_script('dsdvo_tarteaucitron');			

				wp_register_script( 'dsgvoaio_inline_js', '' );

				wp_enqueue_script( 'dsgvoaio_inline_js');

				wp_add_inline_script( 'dsgvoaio_inline_js', $script );
				
				$_SESSION['fbpixelevent'] = "";
				
			}

		}	 


		public static function style_rejectbtn() {

			echo "

			<style>
					
			@media screen and (min-width: 800px) {

				.dsdvo-cookie-notice.style1 #tarteaucitronDisclaimerAlert {

					float: left;

					width: 100% !important;
					
					text-align: center !important;
					
					padding-top: 15px;

				}
				
				.dsdvo-cookie-notice.style1 #tarteaucitronAlertBig .dsgvonoticebtns {
					
					float: left;
					
					width: 100%;
					
					text-align: center;
					
					margin-bottom: 25px;
					
				}
								
				.dsdvo-cookie-notice.style1 #tarteaucitronAlertBig #tarteaucitronPersonalize, .dsdvo-cookie-notice.style1 #tarteaucitronAlertBig #tarteaucitronCloseAlert {
					
					float: none !important;
					
				}	

			}
			
			@media screen and (max-width: 800px) {

				.dsdvo-cookie-notice.style1 #tarteaucitronDisclaimerAlert {

					float: left;

					width: 60% !important;

				}

			}
			
			</style>

			";

		}	 


		public static function dsdvo_cookie_banner() {

			$display_notice = "yes";

			if( is_plugin_active( 'elementor/elementor.php' ) ) {

				if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {

					 $display_notice = "no";

				} else {

					$display_notice = "yes";

				}

			}			
			
			if ($display_notice == "yes") {
				
				$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();

				$dsdvo_policy_site = get_option("dsdvo_policy_site");

				$notice_style = get_option("dsgvo_notice_style");

				if (!$notice_style) { $notice_style = "3"; }

				?>

				<?php if(get_option("dsdvo_show_closebtn") == "off") { ?>

					<style>.dsgvoaio_close_btn {display: none;}</style>

				<?php }
				
				if(get_option("dsdvo_show_servicecontrol") == "on") { ?>

					<style>#tarteaucitronManager {display: block;}</style>

				<?php }

				if(get_option("dsdvo_show_servicecontrol") != "on") {		?>

					<style>#tarteaucitronAlertSmall #tarteaucitronManager {display: none !important;}</style>

				<?php } ?>			

				<script type="text/javascript">

					jQuery( document ).ready(function() {
						
					<?php if (get_option("dsdvo_use_youtube") == "on") { ?>
					
							(tarteaucitron.job = tarteaucitron.job || []).push('youtube');
							
					<?php } ?>	

					<?php if (get_option("dsdvo_use_vimeo") == "on") { ?>		
					
						(tarteaucitron.job = tarteaucitron.job || []).push('vimeo');
						
					<?php } ?>					
					
					<?php if (get_option("dsdvo_use_shareaholic")) { ?>
					
							tarteaucitron.user.shareaholicSiteId = '<?php echo wp_kses(get_option("dsdvo_shareaholicsiteid"), $kses_allowed_html); ?>';
						
							(tarteaucitron.job = tarteaucitron.job || []).push('shareaholic');
					
					<?php } ?>					

					<?php if (get_option("dsdvo_use_twitter")) { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('twitter');

					<?php } ?>

					<?php if (get_option("dsdvo_use_vgwort")) { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('vgwort');

					<?php } ?>	

					<?php if (get_option("dsdvo_use_piwik") == "on") { ?>

							tarteaucitron.user.matomoId = '<?php echo html_entity_decode(wp_kses(get_option("dsgvo_piwik_siteid"), $kses_allowed_html), ENT_COMPAT, get_option('blog_charset')); ?>';

							tarteaucitron.user.matomoHost = '<?php echo html_entity_decode(wp_kses(get_option("dsgvo_piwik_host"), $kses_allowed_html), ENT_COMPAT, get_option('blog_charset')); ?>';
							
							tarteaucitron.user.matomoEndpoint = '<?php echo wp_kses(wp_kses(get_option("dsgvo_piwik_phpfile", "matomophp"), $kses_allowed_html), $kses_allowed_html); ?>';
							
							(tarteaucitron.job = tarteaucitron.job || []).push('matomo');

					<?php } ?>				

					<?php if ( get_option("dsdvo_use_ga") == "on") { ?>
					
							tarteaucitron.user.analytifycode = '<?php echo urlencode(get_option('dsgvo_analytify_js', 'empty'));?>';
							
							tarteaucitron.user.useanalytify = '<?php if (get_option('dsdvo_ga_type', 'manual') == "analytify") { echo "true"; } else { echo "false";} ?>';
							
							tarteaucitron.user.monsterinsightcode = '<?php echo urlencode(get_option('dsgvo_monsterinsightcode', 'empty'));?>';
							
							tarteaucitron.user.usemonsterinsight = '<?php if (get_option('dsdvo_ga_type', 'manual') == "monterinsights") { echo "true"; } else { echo "false";} ?>';
							
							tarteaucitron.user.analyticsAnonymizeIp = 'true';

							tarteaucitron.user.analyticsUa = '<?php echo wp_kses(get_option("dsdvo_gaid"), $kses_allowed_html); ?>';

							tarteaucitron.user.defaultoptinout = '<?php echo wp_kses(get_option("dsdvo_ga_optinoutsetting"), $kses_allowed_html); ?>';

							tarteaucitron.user.analyticsMore = function () { 

							};

							(tarteaucitron.job = tarteaucitron.job || []).push('analytics');

					<?php } ?>
					
					<?php if (get_option("dsdvo_use_koko") == "on") { ?>	
					
							(tarteaucitron.job = tarteaucitron.job || []).push('koko');
							
							tarteaucitron.user.kokoanalyticscode = '<?php echo urlencode(get_option('dsgvo_kokocode', ''));?>';
							
					<?php } ?>	
					
					<?php if (get_option("dsdvo_use_vgwort") == "on") { ?>		
					
							(tarteaucitron.job = tarteaucitron.job || []).push('vgwort');
							
					<?php } ?>					

					<?php if (get_option("dsdvo_use_gtagmanager")) { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('googletagmanager');

							tarteaucitron.user.googletagmanagerId = '<?php echo get_option("dsdvo_gtagmanagerid"); ?>';

					<?php } ?>			

					<?php if (get_option("dsdvo_use_gtagmanager")) { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('googletagmanager');

							tarteaucitron.user.googletagmanagerId = '<?php echo get_option("dsdvo_gtagmanagerid"); ?>';

					<?php } ?>				

					<?php if ( get_option("dsdvo_fbpixelid") && get_option("dsdvo_use_fbpixel") == "on") { ?>

							tarteaucitron.user.facebookpixelId = '<?php echo get_option("dsdvo_fbpixelid"); ?>'; 

							tarteaucitron.user.facebookpixelMore = function () {};

							(tarteaucitron.job = tarteaucitron.job || []).push('facebookpixel');

					<?php } ?>

					<?php if (get_option("dsdvo_use_facebookcomments") == "on") { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('facebookcomment');

					<?php } ?>

					<?php if (get_option("dsdvo_use_facebooklike") == "on") { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('facebook');

					<?php } ?>

					<?php if (get_option("dsdvo_use_twitter") == "on") { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('twitter');

					<?php } ?>

					<?php if (get_option("dsdvo_use_addthis") == "on") { ?>

							tarteaucitron.user.addthisPubId = '<?php echo get_option("dsdvo_addthisid"); ?>';

							(tarteaucitron.job = tarteaucitron.job || []).push('addthis');

					<?php } ?>

					<?php if (get_option("dsdvo_use_linkedin") == "on") { ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('linkedin');

					<?php } ?>

							(tarteaucitron.job = tarteaucitron.job || []).push('wordpressmain');

					});

				</script>

				<?php	

			}

		}


		public static function dsdvo_user_remove_form_func( $atts, $content = "" ) {

			if ( is_user_logged_in() ) {

			   return include("core/inc/user_remove_form.php");

			} else {

				$content .= "<p>".__("You must be logged in to perform this action.", "dsgvo-all-in-one-for-wp")."</p>"; 

			}			

			return $content;    

		}


		public static function dsgvo_show_imprint( $atts, $content = "" ) {
			
			$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();
			
			include(dirname(__FILE__).'/core/inc/imprint.php');	
			
			$content = $imprint_template;
			
			$content = str_replace('[dsgvoustid]', stripcslashes(wp_kses(get_option("dsdvo_legalform_ustid", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvowid]', stripcslashes(wp_kses(get_option("dsdvo_legalform_wid", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvosupervisoryauthority]', stripcslashes(wp_kses(get_option("dsdvo_legalform_supervisoryauthority", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvocity]', stripcslashes(wp_kses(get_option("dsdvo_legalform_city", ""), $kses_allowed_html)), $content);
			
			$register_val = wp_kses(get_option("dsdvo_legalform_register", ""), $kses_allowed_html);
			
			if ($register_val == "1") {
				
				$register = __("Commercial register", "dsgvo-all-in-one-for-wp");
				
			} else if ($register_val == "2") {
				
				$register = __("Association register", "dsgvo-all-in-one-for-wp");
				
			} else if ($register_val == "3") {
				
				$register = __("Partnership register", "dsgvo-all-in-one-for-wp");
				
			} else if ($register_val == "4") {
				
				$register = __("Cooperative register", "dsgvo-all-in-one-for-wp");
				
			} else {
				
				$register = "";
				
			}	
			
			$content = str_replace('[dsgvoregister]', $register, $content);
			
			$content = str_replace('[dsgvolegalformcountry]', stripcslashes(wp_kses(get_option("dsdvo_legalform_state", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvoregisternr]', stripcslashes(wp_kses(get_option("dsdvo_legalform_registernumber", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvochamber]', stripcslashes(wp_kses(get_option("dsdvo_legalform_chamber", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvophone]', stripcslashes(wp_kses(get_option("dsgvoaiophone", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvofax]', stripcslashes(wp_kses(get_option("dsgvoaiofax", ""), $kses_allowed_html)), $content);
			
			if(extension_loaded('gd') && get_option("dsdvo_spamemail", "yes") == "yes"){
				
				if (!file_exists(WP_CONTENT_DIR ."/dsgvo-all-in-one-wp")) {
					
					mkdir( WP_CONTENT_DIR ."/dsgvo-all-in-one-wp" );
					
				}				
				
				$mailstringcount = strlen(wp_kses(get_option("dsgvoaiomail", ""), $kses_allowed_html));
				
				if ($mailstringcount < 1) {
					
					$mailstringcount = 1;
					
				}

				$im = imagecreatetruecolor($mailstringcount*9, 23);

				$white = imagecolorallocatealpha($im, 255, 255, 255,0);

				$grey = imagecolorallocate($im, 128, 128, 128);

				$black = imagecolorallocate($im, 0, 0, 0);

				imagefilledrectangle($im, 0, 0, 399, 29, $white);

				$font = plugin_dir_path(__FILE__ )."/assets/font/arial.ttf";

				imagettftext($im, 14, 0, 10, 20, $black, $font, wp_kses(get_option("dsgvoaiomail", ""), $kses_allowed_html));		

				imagepng($im, WP_CONTENT_DIR .'/dsgvo-all-in-one-wp/sserdaliame.png');			
				
				$content = str_replace('[dsgvoemail]', '<img class="dsgvoaio_emailpng" src="'.content_url().'/dsgvo-all-in-one-wp/sserdaliame.png'.'">', $content);
				
			} else {
				
				$content = str_replace('[dsgvoemail]', stripslashes(wp_kses(get_option("dsgvoaiomail", ""), $kses_allowed_html)), $content);
			
			}

			$content = str_replace('[dsgvocompany]', stripslashes(wp_kses(get_option("dsgvoaiocompanyname", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvoperson]', stripslashes(wp_kses(get_option("dsgvoaioperson", ""), $kses_allowed_html))."<br/>", $content);
			
			if (null !== get_option("dsgvoaiostreet") && get_option("dsgvoaiostreet") != ""){
			
				$content = str_replace('[dsgvostreet]', stripslashes(wp_kses(get_option("dsgvoaiostreet", ""), $kses_allowed_html))."<br/>", $content);
			
			} else {
			
				$content = str_replace('[dsgvostreet]', '', $content);	
			
			}
			
			if (null !== get_option("dsgvoaiozip") && get_option("dsgvoaiozip") != ""){
				
				$content = str_replace('[dsgvozip]', stripslashes(wp_kses(get_option("dsgvoaiozip", ""), $kses_allowed_html)), $content);
			
			} else {
				
				$content = str_replace('[dsgvozip]', '', $content);	
			
			}	
			
			if (null !== get_option("dsgvoaiocity") && get_option("dsgvoaiocity") != ""){
				
				$content = str_replace('[dsgvocityowner]', stripslashes(wp_kses(get_option("dsgvoaiocity", ""), $kses_allowed_html))."<br/>", $content);
			
			} else {
				
				$content = str_replace('[dsgvocityowner]', '', $content);	
				
			}
			
			if (null !== get_option("dsgvoaiocountry") && get_option("dsgvoaiocountry") != ""){
			
				$content = str_replace('[dsgvocountryowner]', stripslashes(wp_kses(get_option("dsgvoaiocountry", ""), $kses_allowed_html))."<br/>", $content);
			
			} else {
			
				$content = str_replace('[dsgvocountryowner]', '', $content);	
			
			}	
			
			$content = str_replace('[dsgvoperson_journalist]', stripslashes(wp_kses(get_option("dsdvo_legalform_personname_jornalist", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvostreet_journalist]', stripslashes(wp_kses(get_option("dsdvo_legalform_adress_jornalist", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvozip_journalist]', stripslashes(wp_kses(get_option("dsdvo_legalform_zip_jornalist", ""), $kses_allowed_html)), $content);
			
			$content = str_replace('[dsgvocity_journalist]', stripslashes(wp_kses(get_option("dsdvo_legalform_city_jornalist", ""), $kses_allowed_html)), $content);			
			
			$content = str_replace('[dsgvocountry_journalist]', stripslashes(wp_kses(get_option("dsdvo_legalform_country_jornalist", ""), $kses_allowed_html)), $content);
			
			$inforule_val = get_option("dsdvo_legalform_inforule", "0");
			
			$inforule = "";
			
			if ($inforule_val == "2") {
				
				$inforule = __("Doctor", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "3") {
				
				$inforule = __("Dentist", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "4") {
				$inforule = __("Architect", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "5") {
				
				$inforule = __("Tax Consultant", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "6") {
				
				$inforule = __("Lawyer", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "7") {
				
				$inforule = __("Notary", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "8") {
				
				$inforule = __("Duditor", "dsgvo-all-in-one-for-wp");
				
			} else if ($inforule_val == "9") {
				
				$inforule = __("Pharmacists", "dsgvo-all-in-one-for-wp");
				
			}
			
			$content = str_replace('[dsgvoinforule]', $inforule, $content);
			
			$state_val = get_option("dsdvo_legalform_state", "");
			
			if ($state_val == "1") {
				
				$state = __("Germany", "dsgvo-all-in-one-for-wp");
				
			} else if ($state_val == "2") {
				
				$state = __("Austria", "dsgvo-all-in-one-for-wp");
				
			} else if ($state_val == "3") {
				
				$state = __("Switzerland", "dsgvo-all-in-one-for-wp");
				
			} else {
				
				$state = "";
				
			}
			
			$content = str_replace('[dsgvocountry]', $state, $content);
			
			return $content;
		}


		public static function dsgvo_show_policy( $atts, $content = "" ) {
			
			$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();			

			if (!isset($language)) $language = wf_get_language();

			if ($language == "de") {

				$policy_text_1 = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_policy_text_1"))));

			}	

			if ($language == "en") {

				$policy_text_1 = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_policy_text_en"))));

			}

			if ($language == "it") {

				$policy_text_1 = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_policy_text_it"))));

			}									

			$now = new DateTime();

			$update_date = $now->format('d.m.Y');

			$content = "";

			if ($policy_text_1) {

				$content = str_replace("[dsgvo_save_date]", $update_date,$policy_text_1);

				$content = "<div class='dsgvoaio_policy_shortcode'>".$content;

				include( plugin_dir_path(__FILE__ )."/core/inc/texts.php");
				
				$plugins_policy = "";

				if ($language == "de") {

					$policytext = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_wordpress_policy"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($woocommerce_policy_text)));

					}	

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($polylang_policy_text)));

					}	

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($wpml_policy_text)));

					}

					$plugins_policy .= wpautop(stripslashes(wp_kses_post($dsgvoaio_policy)));

				} else if ($language == "it") {
					
					$policytext = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_wordpress_policy_it"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($woocommerce_policy_text_it)));

					}			

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($polylang_policy_text_it)));

					}

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(wp_kses_post(stripslashes($wpml_policy_text_it)));

					}	

					$plugins_policy .= wpautop(stripslashes(wp_kses_post($dsgvoaio_policy_it)));
					
					
				} else {

					$policytext = wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_wordpress_policy_en"))));

					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($woocommerce_policy_text_en)));

					}			

					if ( is_plugin_active( 'polylang/polylang.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($polylang_policy_text_en)));

					}

					if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) or is_plugin_active( 'sitepress-multilingual-cms-develop/sitepress.php' ) ) {

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($wpml_policy_text_en)));

					}	

						$plugins_policy .= wpautop(stripslashes(wp_kses_post($dsgvoaio_policy_en)));

				}				
				
				if (isset($policytext) && !empty($policytext)) {
					
					$policytext = str_replace('[dsgvoaio_plugins]', $plugins_policy, $policytext);
					
					$content .= $policytext;
					
				}

				if (get_option('dsdvo_use_fbpixel') == "on" && !empty(get_option("dsdvo_fbpixel_policy")) or get_option('dsdvo_use_fbpixel') == "on" && !empty(get_option("dsdvo_fbpixel_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_fbpixel_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_fbpixel_policy_en"))));

					} 					


					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_fbpixel_policy_it"))));

					} 

				}

				if (get_option('dsdvo_use_facebooklike') == "on" && !empty(get_option("dsdvo_facebook_policy")) or get_option('dsdvo_use_facebookcomments') == "on" && !empty(get_option("dsdvo_facebook_policy")) or get_option('dsdvo_use_facebooklike') == "on" && !empty(get_option("dsdvo_facebook_policy_en")) or get_option('dsdvo_use_facebookcomments') == "on" && !empty(get_option("dsdvo_facebook_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_facebook_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_facebook_policy_en"))));

					} 		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_facebook_policy_it"))));

					} 						

				}				

				if (get_option('dsdvo_use_twitter') == "on" && !empty(get_option("dsdvo_twitter_policy")) or get_option('dsdvo_use_twitter') == "on" && !empty(get_option("dsdvo_twitter_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_twitter_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_twitter_policy_en"))));

					} 		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_twitter_policy_it"))));

					} 					

				}				

				if (get_option('dsdvo_use_ga') == "on" && !empty(get_option("dsdvo_ga_policy")) or get_option('dsdvo_use_ga') == "on" && !empty(get_option("dsdvo_ga_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_ga_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_ga_policy_en"))));

					}		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_ga_policy_it"))));

					}						

				}

				if (get_option('dsdvo_use_gtagmanager') == "on" && !empty(get_option("dsdvo_gtagmanager_policy")) or get_option('dsdvo_use_gtagmanager') == "on" && !empty(get_option("dsdvo_gtagmanager_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_gtagmanager_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_gtagmanager_policy_en"))));

					}	

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_gtagmanager_policy_it"))));

					}					

				}	

				if (get_option('dsdvo_use_piwik') == "on" && !empty(get_option("dsdvo_piwik_policy")) or get_option('dsdvo_use_piwik') == "on" && !empty(get_option("dsdvo_piwik_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_piwik_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_piwik_policy_en"))));

					}


					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_piwik_policy_it"))));

					}					

				}				

				if (get_option('dsdvo_use_linkedin') == "on" && !empty(get_option("dsdvo_linkedin_policy")) or get_option('dsdvo_use_linkedin') == "on" && !empty(get_option("dsdvo_linkedin_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_linkedin_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_linkedin_policy_en"))));

					}		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_linkedin_policy_it"))));

					}						

				}	

				if (get_option('dsdvo_use_youtube') == "on" && !empty(get_option("dsdvo_youtube_policy")) or get_option('dsdvo_use_youtube') == "on" && !empty(get_option("dsdvo_youtube_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_youtube_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_youtube_policy_en"))));

					}		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_youtube_policy_it"))));

					}						

				}					

				if (get_option('dsdvo_use_vgwort') == "on" && !empty(get_option("dsdvo_vgwort_policy")) or get_option('dsdvo_use_vgwort') == "on" && !empty(get_option("dsdvo_vgwort_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_vgwort_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_vgwort_policy_en"))));

					} 	

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_vgwort_policy_it"))));

					} 					

				}
				
				if (get_option('dsdvo_use_koko') == "on" && !empty(get_option("dsdvo_koko_policy")) or get_option('dsdvo_use_koko') == "on" && !empty(get_option("dsdvo_koko_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_koko_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_koko_policy_en"))));

					} 		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_koko_policy_it"))));

					} 					

				}				
				
				if (get_option('dsdvo_use_shareaholic') == "on" && !empty(get_option("dsdvo_shareaholic_policy")) or get_option('dsdvo_use_shareaholic') == "on" && !empty(get_option("dsdvo_shareaholic_policy_en"))) { 

					$content .= "<p></p>";

					if ($language == "de") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_shareaholic_policy"))));

					} 

					if ($language == "en") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_shareaholic_policy_en"))));

					}		

					if ($language == "it") {

						$content .= wpautop(stripcslashes(wp_kses_post(get_option("dsdvo_shareaholic_policy_it"))));

					}					

				}				

				if (get_option('dsgvoaiocompanyname')) {

					$content = str_replace('[company]',stripcslashes(wp_kses(get_option('dsgvoaiocompanyname'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[company]','',$content);

				}		

				if (get_option('dsgvoaioperson')) {

					$content = str_replace('[owner]', stripcslashes(wp_kses(get_option('dsgvoaioperson'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[owner]','',$content);

				}

				if (get_option('dsgvoaiostreet')) {

					$content = str_replace('[adress]',stripcslashes(wp_kses(get_option('dsgvoaiostreet'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[adress]','',$content);

				}

				if (get_option('dsgvoaiozip')) {

					$content = str_replace('[zip]',stripcslashes(wp_kses(get_option('dsgvoaiozip'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[zip]','',$content);

				}

				if (get_option('dsgvoaiocity')) {

					$content = str_replace('[city]',stripcslashes(wp_kses(get_option('dsgvoaiocity'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[city]','',$content);

				}

				if (get_option('dsgvoaiocountry')) {

					$content = str_replace('[country]',stripcslashes(wp_kses(get_option('dsgvoaiocountry'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[country]','',$content);

				}

				if (get_option('dsgvoaiophone')) {

					$content = str_replace('[phone]',stripcslashes(wp_kses(get_option('dsgvoaiophone'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[phone]','',$content);

				}

				if (get_option('dsgvoaiomail')) {
					
					if(extension_loaded('gd') && get_option("dsdvo_spamemail", "yes") == "yes"){
						
						if (!file_exists(WP_CONTENT_DIR ."/dsgvo-all-in-one-wp")) {
							
							mkdir( WP_CONTENT_DIR ."/dsgvo-all-in-one-wp" );
							
						}
						
						$mailstringcount = strlen(stripcslashes(wp_kses(get_option('dsgvoaiomail'), $kses_allowed_html)));

						if ($mailstringcount < 1) {
							
							$mailstringcount = 1;
							
						}

						$im = imagecreatetruecolor($mailstringcount*9, 23);

						$white = imagecolorallocatealpha($im, 255, 255, 255,127);

						$grey = imagecolorallocate($im, 128, 128, 128);

						$black = imagecolorallocate($im, 0, 0, 0);

						imagefilledrectangle($im, 0, 0, 399, 29, $white);

						$font = plugin_dir_path(__FILE__ )."/assets/font/arial.ttf";

						imagettftext($im, 14, 0, 10, 20, $black, $font, wp_kses(get_option("dsgvoaiomail", ""), $kses_allowed_html));		

						imagepng($im, WP_CONTENT_DIR .'/dsgvo-all-in-one-wp/sserdaliame.png');	
						
						$content = str_replace('[mail]',"<p>".__("E-Mail:", "dsgvo-all-in-one-for-wp")."&nbsp;<img class='dsgvoaio_emailpng' src='".content_url()."/dsgvo-all-in-one-wp/sserdaliame.png'>" ,$content);
					
					} else {
						
						$content = str_replace('[mail]', "<p>".__("E-Mail:", "dsgvo-all-in-one-for-wp")."&nbsp;".stripcslashes(wp_kses(get_option('dsgvoaiomail'), $kses_allowed_html))."</p>" ,$content);						
					
					}
				} else {
					
					$content = str_replace('[mail]','',$content);
					
				}				

				if (get_option('dsgvoaiofax')) {

					$content = str_replace('[fax]',stripcslashes(wp_kses(get_option('dsgvoaiofax'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[fax]','',$content);

				}	

				if (get_option('dsdvo_legalform_ustid')) {

					$content = str_replace('[ust]',stripcslashes(wp_kses(get_option('dsdvo_legalform_ustid'), $kses_allowed_html)),$content);

				} else {

					$content = str_replace('[ust]','',$content);

				}	

				$content .= "</div>";

			} else {

				$content = "<b>INFO:</b> Bitte speichern Sie die Einstellungen im Backend unter \"DSGVO AIO\" um den Text der Datenschutzbedingungen hier auszugeben.";

			}

			return $content;    

		}
		

		public static function dsgvo_get_user_datas($atts, $out = "") {

			$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();

			$users = get_users( array( 'fields' => array( 'ID' ) ) );

			$out = "";

			if ( is_user_logged_in() ) {

			$out .= "<div class='dsgvoaio_notice_info'><span class='dashicons dashicons-info'></span>".__("Here is a list of all data that is stored in our system about you.", "dsgvo-all-in-one-for-wp")."</div>"; 

			$out .= "<table>";

			foreach($users as $user_id){

				if ($user_id->ID == get_current_user_id()) {

					$out .= "<tr>";

					$out .= "<td>".__("Username", "dsgvo-all-in-one-for-wp").":</td>";

					$out .= "<td>".get_user_meta ( $user_id->ID)['nickname'][0]."</td>";

					$out .= "</tr>";

					if (get_user_meta ( $user_id->ID)['first_name'][0]) {

						$out .= "<tr>";

						$out .= "<td>".__("Firstname", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta ( $user_id->ID)['first_name'][0]."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta ( $user_id->ID)['last_name'][0]) {

					$out .= "<tr>";

					$out .= "<td>".__("Lastname", "dsgvo-all-in-one-for-wp").":</td>";

					$out .= "<td>".get_user_meta ( $user_id->ID)['last_name'][0]."</td>";

					$out .= "</tr>";

					}

					$user_id = get_current_user_id(); 

					$user_info = get_userdata($user_id);

					$mailadresje = $user_info->user_email;		

					if ($mailadresje) {

						$out .= "<tr>";

						$out .= "<td>".__("E-mail Adress", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".$mailadresje."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta( $user_info->ID, 'billing_address_1', true )) {

						$out .= "<tr>";

						$out .= "<td colspan='2'><b>".__("Billing Adress", "dsgvo-all-in-one-for-wp")."</b></td>";

						$out .= "</tr>";

					}

					if ($user_info->first_name) {

						$out .= "<tr>";

						$out .= "<td>".__("Firstname", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".$user_info->first_name."</td>";

						$out .= "</tr>";

					}

					if ($user_info->last_name) {

						$out .= "<tr>";

						$out .= "<td>".__("Lastname", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".$user_info->last_name."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta( $user_info->ID, 'billing_address_1', true )) {

						$out .= "<tr>";

						$out .= "<td>".__("Adress", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta( $user_info->ID, 'billing_address_1', true )."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta( $user_info->ID, 'billing_city', true )) {

						$out .= "<tr>";

						$out .= "<td>".__("City", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta( $user_info->ID, 'billing_city', true )."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta( $user_info->ID, 'billing_postcode', true )) {

						$out .= "<tr>";

						$out .= "<td>".__("Zip Code", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta( $user_info->ID, 'billing_postcode', true )."</td>";

						$out .= "</tr>";

					}
					
					if (get_user_meta( $user_info->ID, 'billing_country', true )) {

						$out .= "<tr>";

						$out .= "<td>".__("Country", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta( $user_info->ID, 'billing_country', true )."</td>";

						$out .= "</tr>";

					}

					if (get_user_meta( $user_info->ID, 'billing_email', true )) {

						$out .= "<tr>";

						$out .= "<td>".__("E-mail Adress", "dsgvo-all-in-one-for-wp").":</td>";

						$out .= "<td>".get_user_meta( $user_info->ID, 'billing_email', true )."</td>";

						$out .= "</tr>";

					}					

					$user_meta = get_user_meta ( $user_id);

					if (isset($user_meta['community-events-location'])) {

						$useripdata = explode(":", $user_meta['community-events-location'][0]);

						$userip = str_replace('"',"", $useripdata);

						$userip = str_replace(';}',"", $userip);					
						
						if (isset($userip[6])) {

							$userip = preg_replace('/([0-9]+\\.[0-9]+\\.[0-9]+)\\.[0-9]+/', '\\1.xxx', $userip[6]);

							$out .= "<tr>";

							$out .= "<td><b>".__("IP Adress", "dsgvo-all-in-one-for-wp")."</b></td>";

							$out .= "</tr>";

							$out .= "<tr>";

							$out .= "<td>".__("Saved IP Adress", "dsgvo-all-in-one-for-wp").":</td>";

							$out .= "<td>".$userip."</td>";

							$out .= "</tr>";

						}

					}

				}

			}	

			$out .= "</table>";
			
			if (!isset($language)) $language = wf_get_language();
			
			$out .= "<div>";
			
			$nonce = wp_create_nonce( 'dsgvoaiofree_download_userdata_nonce' );
			
			$out .= "<form method='post' action='".esc_url( admin_url('admin-post.php') )."' enctype='multipart/form-data' class='dsgvoaio_download_userdata'>";
			
			$out .= "<input type='hidden' name='dsgvoaiofree_action' value='download_userdatas' />";
			
			$out .= "<input type='hidden' id='dsgvoaiofree_download_userdata_nonce' name='dsgvoaiofree_download_userdata_nonce' value='".$nonce."'>";
			
			$out .= "<input type='hidden' id='dsgvoaiofree_download_userdata_language' name='dsgvoaiofree_download_userdata_language' value='".$language."'>";
			
			$out .= "<button type='submit' name='submit' id='submit' class='button dsgvobtn' data-class='dsgvoaio_export_settings_btn'><span class='dashicons dashicons-media-text'></span>".__("Download as PDF", "dsgvo-all-in-one-for-wp")."</button>";
			
			$out .= "&nbsp;&nbsp;&nbsp;&nbsp;";
			
			$out .= "<a href='".esc_url( get_permalink(wp_kses(get_option("dsdvo_delete_account_page"), $kses_allowed_html)))."'><button type='button' class='button submit btn-primary dsgvobtn'><span class='dashicons dashicons-trash'></span>".__("Delete user account and all data", "dsgvo-all-in-one-for-wp")."</button></a>";
			
			$out .= "</form>";
			
			$out .= "</div>";

			} else {

				if (!isset($language)) $language = wf_get_language();

				if ($language == "de") {

					$notlogged = wp_kses(get_option("dsgvo_notloggedintext"), $kses_allowed_html);

				}

				if ($language == "en") {

					$notlogged = wp_kses(get_option("dsgvo_notloggedintext_en"), $kses_allowed_html);

				}		

				if ($language == "it") {

					$notlogged = wp_kses(get_option("dsgvo_notloggedintext_it"), $kses_allowed_html);

				}					

				if ($notlogged) {

					$out .= "<div class='dsgvoaio_notice_info'><span class='dashicons dashicons-info'></span>".html_entity_decode($notlogged)."</div>";

				} else {

					$out .= "<div class='dsgvoaio_notice_info'><span class='dashicons dashicons-info'></span><b>".__("Error", "dsgvo-all-in-one-for-wp").":</b>".__("You must be logged in to perform this action", "dsgvo-all-in-one-for-wp").".</div>";	

				}

			}

		return $out;

		}

}

dsdvo_wp_frontend::init();
