<?php
/*
 * Database Upgarade File
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Database Update Notice
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */
function popupaoc_db_upgrade_notice() {

	global $current_screen;

	// Get Some Variables
	$screen_id = isset( $current_screen->id ) ? $current_screen->id : '';

	if( $screen_id != 'aoc_popup_page_popupaoc-db-update' ) {

		// Taking some variable
		$update_url	= add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'page' => 'popupaoc-db-update' ), admin_url( 'edit.php' ) );

		echo '<div class="notice notice-error">
				<p><strong>'. esc_html__('Popup Anything database update required.', 'popup-anything-on-click'). '</strong></p>
				<p><strong>'. esc_html__('Popup Anything needs to be updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'popup-anything-on-click'). '</strong></p>
				<p><a class="button button-primary" href="'.esc_url( $update_url ).'">'.esc_html__('Update Database', 'popup-anything-on-click').'</a></p>
			</div>';
	}
}

// Action to display DB update notice
add_action( 'admin_notices', 'popupaoc_db_upgrade_notice' );

/**
 * Function to register database upgrade page
 * 
 * @since 2.0
 */
function popupaoc_db_update_page() {

	// Registring Database Update Page
	add_submenu_page( 'edit.php?post_type='.POPUPAOC_POST_TYPE, __('Update Database - Popup Anything', 'popup-anything-on-click'), "<span style='color:#FCB214;'>".__('Update Database', 'popup-anything-on-click')."</span>", 'edit_posts', 'popupaoc-db-update', 'popupaoc_db_update_page_html' );
}
add_action( 'admin_menu', 'popupaoc_db_update_page', 35 );

/**
 * Function to handle database update process
 * 
 * @since 2.0
 */
function popupaoc_db_update_page_html() { ?>

	<div class="wrap">
		<h2>
			<?php esc_html_e( 'Update Database - Popup Anything', 'popup-anything-on-click' ); ?>
		</h2>

		<div class="paoc-db-update-result-wrp">
			<p><?php esc_html_e('Popup Anything needs to be updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'popup-anything-on-click'); ?></p>
			<p><?php esc_html_e('Database update process has been started.', 'popup-anything-on-click'); ?></p>
			<p style="color:red;"><?php esc_html_e('Kindly do not refresh the page or close the browser.', 'popup-anything-on-click'); ?></p>
		</div>
	</div>

	<script type="text/javascript">

		/* DB upgrade function */
		function popupaoc_process_db_update( data ) {

			if( ! data ) {
				var data = {
					action	: 'popupaoc_popup_data_migrate',
					page	: 1,
					count	: 0,
					nonce	: "<?php echo wp_create_nonce( 'popupaoc-db-update' ); ?>",
				};
			}

			jQuery.post( ajaxurl, data, function( response ) {

				if( response.status == 0 ) {

					jQuery('.paoc-db-update-result-wrp').append( response.message );

				} else {

					jQuery('.paoc-db-update-result-wrp').append( response.result_message );
					jQuery('.paoc-db-update-result-percent').html( response.percentage );

					/* If data is there then process again */
					if( response.data_process != 0 && ( response.data_process < response.total_count ) ) {
						data['page']			= response.page;
						data['total_count']		= response.total_count;
						data['data_process']	= response.data_process;

						setTimeout(function () {
							popupaoc_process_db_update( data );
						}, 2000);
					}

					/* If process is done */
					if( response.data_process >= response.total_count || response.url ) {
						
						setTimeout(function () {
							window.location = response.url;
						}, 4000);
					}
				}
			});
		}

		popupaoc_process_db_update();

	</script>
	<?php
}

/**
 * Function to get popup count
 * 
 * @since 2.0
 */
function popupaoc_popup_data_migrate() {

	global $wpdb, $popupaoc_options;

	// Taking some defaults
	$limit				= 10;
	$count				= 0;
	$prefix				= POPUPAOC_META_PREFIX;
	$popup_post_type	= POPUPAOC_POST_TYPE;
	$page				= ! empty( $_POST['page'] )			? popupaoc_clean_number( $_POST['page'] )			: 1;
	$data_process		= ! empty( $_POST['data_process'] )	? popupaoc_clean_number( $_POST['data_process'] )	: 0;
	$total_count		= ! empty( $_POST['total_count'] )	? popupaoc_clean_number( $_POST['total_count'] )	: 0;
	$nonce				= isset( $_POST['nonce'] )			? popupaoc_clean( $_POST['nonce'] )					: '';
	$result				= array(
							'status'			=> 0,
							'result_message'	=> '',
							'message'			=> esc_html__('Sorry, Something happened wrong.', 'popup-anything-on-click'),
						);

	// Verify Nonce
	if( wp_verify_nonce( $nonce, 'popupaoc-db-update' ) ) {

		// Database Upgrade File for post data migration
		$plugin_version = get_option( 'popupaoc_plugin_version' );

		// Migrate Plugin Settings
		if ( $page <= 1 ) {

			/* Update newly created settings */
			if ( ! isset( $popupaoc_options['enable'] ) ) {

				$default_options = array(
									'add_js'				=> '',
									'enable'				=> 1,
									'cookie_prefix'			=> 'paoc_popup',
									'welcome_popup'			=> '',
									'welcome_display_in'	=> array(),
								);
				$setting_options = wp_parse_args( $popupaoc_options, $default_options );

				// Update default options
				update_option( 'popupaoc_options', $setting_options );
			}
		}

		// Get All Old Popup		
		$popup_query_sql = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS  p.ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON ( p.ID = pm.post_id AND pm.meta_key = '{$prefix}design' ) WHERE 1=1  AND ( pm.post_id IS NULL ) AND p.post_type = '{$popup_post_type}' AND ((p.post_status <> 'auto-draft')) GROUP BY p.ID ORDER BY p.post_date DESC LIMIT 0, %d", $limit );
		$popup_query_res = $wpdb->get_results( $popup_query_sql, ARRAY_A );

		if( $page < 2 ) {

			$total_count = $wpdb->get_var( 'SELECT FOUND_ROWS();' );

			$result['result_message'] .= '<p>'. sprintf( __( 'Total %d Popup found for update.', 'popup-anything-on-click' ), $total_count ) .'</p>';
			$result['result_message'] .= '<p style="color:green;">'. __('Percentage Completed', 'popup-anything-on-click') .' : <span class="paoc-db-update-result-percent">0</span>% '.__('Please Wait...', 'popup-anything-on-click').'</p>';
		}

		if( ! empty( $popup_query_res ) ) {
			foreach ($popup_query_res as $popup_post_key => $popup_data) {

				$count++;

				// Taking some Old Popup Meta
				$popup_id		= $popup_data['ID'];
				$behaviour_meta = get_post_meta( $popup_id, $prefix.'behaviour', true );
				$content_meta	= get_post_meta( $popup_id, $prefix.'content', true );
				$advance_meta	= get_post_meta( $popup_id, $prefix.'advance', true );
				$design_meta 	= get_post_meta( $popup_id, $prefix.'design', true );
				$popup_type		= get_post_meta( $popup_id, $prefix.'popup_type', true );

				// Behaviour Meta
				if( empty( $behaviour_meta ) ) {

					// Taking old popup meta
					$delay			= get_post_meta( $popup_id, $prefix.'delay', true );

					$image_title	= get_post_meta( $popup_id, $prefix.'image_title', true );
					$image_title	= ( $image_title == 'true' ) ? 1 : 0;

					$image_caption	= get_post_meta( $popup_id, $prefix.'image_caption', true );
					$image_caption	= ( $image_caption == 'true' ) ? 1 : 0;

					$enable_loader	= get_post_meta( $popup_id, $prefix.'enable_loader', true );
					$enable_loader	= ( $enable_loader == 'true' ) ? 1 : 0;

					$enable_ovelay	= get_post_meta( $popup_id, $prefix.'enable_ovelay', true );
					$enable_ovelay	= ( $enable_ovelay == 'false' ) ? 1 : 0;

					$behaviour['close_overlay']	= 1;
					$behaviour['image_title']	= $image_title;
					$behaviour['image_caption']	= $image_caption;
					$behaviour['enable_loader']	= $enable_loader;
					$behaviour['hide_overlay']	= $enable_ovelay;
					$behaviour['link_text']		= get_post_meta( $popup_id, $prefix.'popup_link_txt', true );
					$behaviour['image_url']		= get_post_meta( $popup_id, $prefix.'popup_image_url', true );
					$behaviour['popup_img_id']	= get_post_meta( $popup_id, $prefix.'popup_image_id', true );
					$behaviour['btn_text']		= get_post_meta( $popup_id, $prefix.'popup_button_txt', true );
					$behaviour['open_delay']	= ! empty( $delay )			? ( $delay / 1000 )			: '';

					update_post_meta( $popup_id, $prefix.'behaviour', $behaviour );
				}

				// Content Meta
				if( empty( $content_meta ) ) {
				
					// Taking some variable
					$popup_link_txt		= get_post_meta( $popup_id, $prefix.'popup_link_txt', true );
					$popup_button_txt	= get_post_meta( $popup_id, $prefix.'popup_button_txt', true );

					$content['link_text']	= ! empty( $popup_link_txt )	? $popup_link_txt	: __('Click Here!!!', 'popup-anything-on-click');
					$content['btn_text']	= ! empty( $popup_button_txt )	? $popup_button_txt	: __('Click Me!!!', 'popup-anything-on-click');

					update_post_meta( $popup_id, $prefix.'content', $content );
				}

				// Design Meta
				if( empty( $design_meta ) ) {

					// Taking some variable
					$effect_data = popupaoc_popup_effects();

					// Taking old popup meta
					$speedin		= get_post_meta( $popup_id, $prefix.'speedin', true );
					$speedout		= get_post_meta( $popup_id, $prefix.'speedout', true );
					$full_screen	= get_post_meta( $popup_id, $prefix.'full_screen', true );
					$full_screen	= ( $full_screen == 'true' ) ? 1 : 0;

					$popup_positionx	= get_post_meta( $popup_id, $prefix.'popup_positionx', true );
					$popup_positiony	= get_post_meta( $popup_id, $prefix.'popup_positiony', true );
					$popup_effect		= get_post_meta( $popup_id, $prefix.'popup_effect', true );
					$popup_effect		= ( in_array( $popup_effect, $effect_data ) ) ? $popup_effect : 'fadein';

					$design['template']			= 'design-3';
					$design['fullscreen_popup']	= $full_screen;
					$design['effect'] 			= $popup_effect;
					$design['mn_position'] 		= $popup_positionx.'-'.$popup_positiony;
					$design['width'] 			= get_post_meta( $popup_id, $prefix.'popupwidth', true );
					$design['speed_in'] 		= ! empty( $speedin )	? ( $speedin / 1000 )	: 0.5;
					$design['speed_out'] 		= ! empty( $speedout )	? ( $speedout / 1000 )	: 0.25;
					
					update_post_meta( $popup_id, $prefix.'design', $design );
				}

				// Advance Meta
				if ( empty( $advance_meta ) ) {

					$advance['show_credit'] = 0;

					update_post_meta( $popup_id, $prefix.'advance', $advance );
				}

				// Update New Popup Meta
				update_post_meta( $popup_id, $prefix.'popup_goal', 'announcement' );
				update_post_meta( $popup_id, $prefix.'display_type', 'modal' );
				update_post_meta( $popup_id, $prefix.'tab', '#paoc_behaviour_sett' );
				update_post_meta( $popup_id, $prefix.'popup_appear', $popup_type );
			}

			// Record total process data
			$data_process = ( $data_process + $count );

			// Calculate percentage
			$percentage = 100;

			if( $total_count > 0 ) {
				$percentage = ( ( $limit * $page ) / $total_count ) * 100;
			}

			if( $percentage >= 100 ) {
				$percentage = 100;

				$result['result_message'] .= '<p>'.__( 'All looks good. All records has been updated.', 'popup-anything-on-click' ).'</p>';
				$result['result_message'] .= '<p>'.__( 'Please Wait... Redirecting...', 'popup-anything-on-click' ).'</p>';

				// Update plugin db version to latest
				if ( version_compare( $plugin_version, '1.3' ) < 0 ) {
					update_option( 'popupaoc_plugin_version', '1.3' );
				}
			}

			/* If process is done */
			if( $data_process >= $total_count ) {
				$result['url'] = add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'message' => 'popupaoc-db-update' ), admin_url('edit.php') );
			}

			$result['status']			= 1;
			$result['total_count'] 		= $total_count;
			$result['data_process']		= $data_process;
			$result['percentage'] 		= $percentage;
			$result['page']				= ( $page + 1 );

		} else {

			// Update plugin db version to latest
			if ( version_compare( $plugin_version, '1.3' ) < 0 ) {
				update_option( 'popupaoc_plugin_version', '1.3' );
			}

			$result['status']			= 1;
			$result['percentage']		= 100;
			$result['result_message'] .= '<p>'.__( 'All looks good. No old records found.', 'popup-anything-on-click' ).'</p>';
			$result['result_message'] .= '<p>'.__( 'Please Wait... Redirecting...', 'popup-anything-on-click' ).'</p>';
			$result['url']				= add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'message' => 'popupaoc-db-update' ), admin_url('edit.php') );
		}
	}

	wp_send_json( $result );
}

// Database Upgrade Action
add_action( 'wp_ajax_popupaoc_popup_data_migrate', 'popupaoc_popup_data_migrate' );