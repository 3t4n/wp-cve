<?php
/*
 * Database Upgarade File
 * Update logo showcase post type meta box with new settings
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Database Update Notice
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */
function lswss_db_upgrade_notice() {

	global $current_screen;

	// Get Some Variables
	$screen_id = isset( $current_screen->id ) ? $current_screen->id : '';

	if( $screen_id != 'dashboard_page_lswssp-db-update' ) {

		$update_url = add_query_arg( array('page' => 'lswssp-db-update' ), admin_url( 'index.php' ) );

		echo '<div class="notice notice-error">
				<p><strong>'. esc_html__('Logo Showcase with Slick Slider database update required.', 'logo-showcase-with-slick-slider'). '</strong></p>
				<p><strong>'. esc_html__('Logo Showcase with Slick Slider needs to be updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'logo-showcase-with-slick-slider'). '</strong></p>
				<p><a class="button button-primary" href="'.esc_url( $update_url ).'">'.esc_html__('Update Database', 'logo-showcase-with-slick-slider').'</a></p>
			</div>';
	}
}

// Action to display DB update notice
add_action( 'admin_notices', 'lswss_db_upgrade_notice' );

/**
 * Function to register database upgrade page
 *
 * @since 2.0
 */
function lswss_db_update_page() {

	// Registring Database Update Page
	add_submenu_page( 'index.php', __('Update Database - Logo Showcase with Slick Slider', 'logo-showcase-with-slick-slider'), "<span style='color:#FCB214;'>".__('Logo Showcase - Update Database', 'logo-showcase-with-slick-slider')."</span>", 'edit_posts', 'lswssp-db-update', 'lswss_db_update_page_html' );
}
add_action( 'admin_menu', 'lswss_db_update_page', 35 );

/**
 * Function to handle database update process
 * 
 * @since 2.0
 */
function lswss_db_update_page_html() { ?>

	<div class="wrap">
		<h2>
			<?php _e( 'Update Database - Logo Showcase with Slick Slider', 'logo-showcase-with-slick-slider' ); ?>
		</h2>

		<div class="lswssp-db-update-result-wrp">
			<p><?php _e('Logo Showcase Pro needs to be updated! To keep things running smoothly, we have to update your database to the newest version. The database update process runs in the background and may take a little while, so please be patient.', 'logo-showcase-with-slick-slider'); ?></p>
			<p><?php _e('Database update process has been started.', 'logo-showcase-with-slick-slider'); ?></p>
			<p style="color:red;"><?php _e('Kindly do not refresh the page or close the browser.', 'logo-showcase-with-slick-slider'); ?></p>
		</div>
	</div>

	<script type="text/javascript">

		/* DB upgrade function */
		function lswss_process_db_update( data ) {

			if( ! data ) {
				var data = {
					action	: 'lswss_process_db_migrate',
					page	: 1,
					count	: 0,
					nonce	: "<?php echo wp_create_nonce( 'lswssp-db-update' ); ?>",
				};
			}

			jQuery.post( ajaxurl, data, function( response ) {

				if( response.status == 0 ) {

					jQuery('.lswssp-db-update-result-wrp').append( response.message );

				} else {

					jQuery('.lswssp-db-update-result-wrp').append( response.result_message );
					jQuery('.lswssp-db-update-result-percent').html( response.percentage );

					/* If data is there then process again */
					if( response.data_process != 0 && ( response.data_process < response.total_count ) ) {
						data['page']            = response.page;
						data['total_count']     = response.total_count;
						data['data_process']    = response.data_process;

						setTimeout(function () {
							lswss_process_db_update( data );
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

		lswss_process_db_update();

	</script>
	<?php
}

/**
 * Process database migration
 * 
 * @since 2.0
 */
function lswss_process_db_migrate() {

	global $wpdb;

	// Taking some defaults
	$limit				= 10;
	$count				= 0;
	$prefix				= LSWSS_META_PREFIX;
	$logo_post_type		= LSWSS_POST_TYPE;
	$page				= ! empty( $_POST['page'] )			? lswss_clean_number( $_POST['page'] )			: 1;
	$data_process		= ! empty( $_POST['data_process'] )	? lswss_clean_number( $_POST['data_process'] )	: 0;
	$total_count		= ! empty( $_POST['total_count'] )	? lswss_clean_number( $_POST['total_count'] )	: 0;
	$nonce				= isset( $_POST['nonce'] )			? lswss_clean( $_POST['nonce'] )				: '';
	$result				= array(
							'status'			=> 0,
							'result_message'	=> '',
							'message'			=> __('Sorry, Something happened wrong.', 'logo-showcase-with-slick-slider'),
						);

	// Verify Nonce
	if( wp_verify_nonce( $nonce, 'lswssp-db-update' ) ) {

		// Database Upgrade File for post data migration
		$plugin_version = get_option( 'lswss_version' );

		// Get All Old Logo Showcase
		/*$args = array(
			'post_type' 				=> LSWSS_POST_TYPE,
			'post_status'				=> array( 'any', 'inherit', 'trash' ),
			'fields'					=> 'ids',
			'order' 					=> 'DESC',
			'orderby' 					=> 'date',
			'paged'						=> 1,
			'posts_per_page' 			=> $limit,
			'cache_results'				=> false,
			'update_post_meta_cache'	=> false,
			'update_post_term_cache'	=> false,
			'meta_query'				=> array(
												array(
													'key'		=> $prefix.'sett',
													'value'		=> '',
													'compare'	=> 'NOT EXISTS',
												),
											)
		);
		$logo_query = new WP_Query( $args );*/

		$query_sql = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS  p.ID FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->postmeta} AS pm ON ( p.ID = pm.post_id AND pm.meta_key = '{$prefix}sett' ) WHERE 1=1  AND ( pm.post_id IS NULL ) AND p.post_type = '{$logo_post_type}' AND ((p.post_status <> 'auto-draft')) GROUP BY p.ID ORDER BY p.post_date DESC LIMIT 0, %d", $limit );
		$query_res = $wpdb->get_results( $query_sql, ARRAY_A );

		if( $page < 2 ) {

			$total_count = $wpdb->get_var( 'SELECT FOUND_ROWS();' );

			$result['result_message'] .= '<p>'. sprintf( __( 'Total %d Logo Showcase found for update.', 'logo-showcase-with-slick-slider' ), $total_count ) .'</p>';
			$result['result_message'] .= '<p style="color:green;">'. __('Percentage Completed', 'logo-showcase-with-slick-slider') .' : <span class="lswssp-db-update-result-percent">0</span>% '.__('Please Wait...', 'logo-showcase-with-slick-slider').'</p>';
		}

		if( ! empty( $query_res ) ) {
			foreach ($query_res as $logo_post_key => $logo_post_data) {

				$count++;

				// Taking some old Meta
				$logo_post_id		= $logo_post_data['ID'];
				$logo_sett			= get_post_meta( $logo_post_id, $prefix.'sett', true );
				$display_type		= get_post_meta( $logo_post_id, $prefix.'display_type', true );
				$show_title			= get_post_meta( $logo_post_id, $prefix.'show_title', true );
				$show_description	= get_post_meta( $logo_post_id, $prefix.'show_description', true );
				$logo_grid			= get_post_meta($logo_post_id, $prefix.'logo_grid', true );
				$ipad				= get_post_meta( $logo_post_id, $prefix.'ipad', true );
				$tablet				= get_post_meta( $logo_post_id, $prefix.'tablet', true );
				$mobile				= get_post_meta( $logo_post_id, $prefix.'mobile', true );
				$slides_show		= get_post_meta( $logo_post_id, $prefix.'slide_to_show_carousel', true );
				$slides_scroll		= get_post_meta( $logo_post_id, $prefix.'slide_to_column_carousel', true );
				$arrow_carousel		= get_post_meta( $logo_post_id, $prefix.'arrow_carousel', true );
				$dots_carousel		= get_post_meta( $logo_post_id, $prefix.'pagination_carousel', true );
				$autoplay_carousel	= get_post_meta( $logo_post_id, $prefix.'autoplay_carousel', true );
				$autoplay_speed		= get_post_meta( $logo_post_id, $prefix.'autoplay_speed_carousel', true );
				$speed_carousel		= get_post_meta( $logo_post_id, $prefix.'speed_carousel', true );
				$loop_carousel		= get_post_meta( $logo_post_id, $prefix.'loop_carousel', true );
				$centermode			= get_post_meta( $logo_post_id, $prefix.'centermode_carousel', true );
				$center_padding		= get_post_meta( $logo_post_id, $prefix.'space_between_carousel', true );
				$new_tab			= get_post_meta( $logo_post_id, $prefix.'new_tab', true );
				$new_tab			= ( $new_tab == 'true' ) ? '_blank' : '_self';

				// Setting Metabox
				if( empty( $logo_sett ) ) {

					// Grid Settings
					$logo_showcase_sett['grid']['design']				= 'design-1';
					$logo_showcase_sett['grid']['show_title']			= $show_title;
					$logo_showcase_sett['grid']['show_desc']			= $show_description;
					$logo_showcase_sett['grid']['link_target']			= $new_tab;
					$logo_showcase_sett['grid']['grid']					= lswss_clean_number( $logo_grid, 5 );
					$logo_showcase_sett['grid']['min_height']			= '';
					$logo_showcase_sett['grid']['ipad']					= $ipad;
					$logo_showcase_sett['grid']['tablet']				= $tablet;
					$logo_showcase_sett['grid']['mobile']				= $mobile;

					// Slider Settings
					$logo_showcase_sett['slider']['design']				= 'design-1';
					$logo_showcase_sett['slider']['show_title']			= $show_title;
					$logo_showcase_sett['slider']['show_desc']			= $show_description;
					$logo_showcase_sett['slider']['link_target']		= $new_tab;
					$logo_showcase_sett['slider']['min_height']			= '';
					$logo_showcase_sett['slider']['slides_show']		= lswss_clean_number( $slides_show, 5 );
					$logo_showcase_sett['slider']['slides_scroll']		= lswss_clean_number( $slides_scroll, 1 );
					$logo_showcase_sett['slider']['arrow']				= $arrow_carousel;
					$logo_showcase_sett['slider']['dots']				= $dots_carousel;
					$logo_showcase_sett['slider']['autoplay']			= $autoplay_carousel;
					$logo_showcase_sett['slider']['autoplay_speed']		= lswss_clean_number( $autoplay_speed, 3000 );
					$logo_showcase_sett['slider']['speed']				= lswss_clean_number( $speed_carousel, 600 );
					$logo_showcase_sett['slider']['loop']				= $loop_carousel;
					$logo_showcase_sett['slider']['centermode']			= $centermode;
					$logo_showcase_sett['slider']['swipemode']			= 'true';
					$logo_showcase_sett['slider']['center_padding']		= lswss_clean_number( $center_padding, '' );
					$logo_showcase_sett['slider']['ipad']				= $ipad;
					$logo_showcase_sett['slider']['tablet']				= $tablet;
					$logo_showcase_sett['slider']['mobile']				= $mobile;

					update_post_meta( $logo_post_id, $prefix.'sett', $logo_showcase_sett );
				}
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

				$result['result_message'] .= '<p>'.__( 'All looks good. All records has been updated.', 'logo-showcase-with-slick-slider' ).'</p>';
				$result['result_message'] .= '<p>'.__( 'Please Wait... Redirecting...', 'logo-showcase-with-slick-slider' ).'</p>';

				// Update plugin db version to latest
				if ( version_compare( $plugin_version, '1.0' ) <= 0 ) {
					update_option( 'lswss_version', '1.1' );
				}
			}

			/* If process is done */
			if( $data_process >= $total_count ) {
				$result['url'] = add_query_arg( array('message' => 'lswssp-db-update' ), admin_url('index.php') );
			}

			$result['status']		= 1;
			$result['total_count'] 	= $total_count;
			$result['data_process']	= $data_process;
			$result['percentage'] 	= $percentage;
			$result['page']			= ( $page + 1 );

		} else {

			// Update plugin db version to latest
			if ( version_compare( $plugin_version, '1.0' ) <= 0 ) {
				update_option( 'lswss_version', '1.1' );
			}

			$result['status']			= 1;
			$result['percentage']		= 100;
			$result['result_message']	.= '<p>'.__( 'All looks good. No old records found.', 'logo-showcase-with-slick-slider' ).'</p>';
			$result['result_message']	.= '<p>'.__( 'Please Wait... Redirecting...', 'logo-showcase-with-slick-slider' ).'</p>';
			$result['url']				= add_query_arg( array('message' => 'lswssp-db-update' ), admin_url('index.php') );
		}
	}

	wp_send_json( $result );
}

// Database Upgrade Action
add_action( 'wp_ajax_lswss_process_db_migrate', 'lswss_process_db_migrate' );