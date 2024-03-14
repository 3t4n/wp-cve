<?php

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ADMIN PAGE */

add_action( 'admin_menu', 'iprm_add_main_page_link' );

function iprm_main_page() {

	/* LOAD CURRENT */
	if ( iprm_get_option( 'iprm_active_product' ) ) {
		$podcast = new IPRM_Podcast( iprm_get_option( 'iprm_active_product' ) );
	} else {
		/* COULD NOT LOAD, USE DEFAULT */
		$podcast = new IPRM_Podcast( 'https://podcasts.apple.com/us/' );
	}

	/* DISABLES FOR NON-ADMINISTRATORS */
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$alert  = '';
	$notice = '';

	/* LOAD CURRENT SELECTED PRODUCT */
	if ( isset( $_POST['iprm_product_select'] ) ) {
		if ( isset( $_POST['iprm_update_product'] ) ) {
			$podcast = new IPRM_Podcast( $_POST['iprm_product_select'] );
		} elseif ( isset( $_POST['iprm_delete_product'] ) ) {
			/* DEV FAILSAFE.. NEED NEW CONSTRUCTOR THAT TAKES 0 */
			$podcast               = new IPRM_Podcast( 'https://podcasts.apple.com/us/' );
			$product_to_delete_url = $_POST['iprm_product_select'];
			iprm_delete_product( $product_to_delete_url );
			$notice = 'Product removed: ' . $_POST['iprm_product_select'];
		}
	} else {
		$podcast = new IPRM_Podcast( iprm_get_option( 'iprm_active_product' ) );
	}

	// IF NEW ITUNES URL IS ENTERED, UPDATE ACTIVE
	if ( isset( $_POST['iprm_update_url'] ) && ( '' !== $_POST['iprm_add_url'] ) ) {
		if ( filter_var( $_POST['iprm_add_url'], FILTER_VALIDATE_URL ) && ( 'https://podcasts.apple.com/us/' !== $_POST['iprm_add_url'] ) ) {
			$itunes_url = esc_url( $_POST['iprm_add_url'] );
			iprm_update_option( 'iprm_active_product', $itunes_url );
			$podcast = new IPRM_Podcast( $itunes_url );
			if ( '' !== $podcast->itunes_id ) {
				$notice  = __( 'Your iTunes URL has successfully been updated.<br>', 'iprm_domain' );
				$notice .= __( 'Please click CHECK MANUALLY to check for new reviews now. Otherwise, the next auto-check will be in approximately 4 hours.', 'iprm_domain' );
			} else {
				$alert  = __( 'iTunes URL could not be updated.  Please check the URL and try again.', 'iprm_domain' ) . '<br />';
				$alert .= '<i>' . __( 'Example: https://podcasts.apple.com/us/podcast/wpcast-fm-the-professional-wordpress-podcast/id885696994.', 'iprm_domain' ) . '</i>';
			}
		} else {
			$alert  = __( 'Invalid iTunes URL.', 'iprm_domain' ) . '<br />';
			$alert .= '<i>' . __( 'Example: https://podcasts.apple.com/us/podcast/wpcast-fm-the-professional-wordpress-podcast/id885696994.', 'iprm_domain' ) . '</i>';
		}
	} elseif ( '' === $podcast->itunes_id ) {
		/* IF NO ITUNES URL IS FOUND, DISPLAY NOTICE ASKING FOR THE URL */
		$notice  = __( 'Please enter your iTunes URL.', 'iprm_domain' ) . '<br />';
		$notice .= '<i>' . __( 'Example: https://podcasts.apple.com/us/podcast/wpcast-fm-the-professional-wordpress-podcast/id885696994.', 'iprm_domain' ) . '</i>';
	}
	/* IF CHECK MANUALLY BUTTON IS PRESSED, CHECK FOR REVIEWS */
	if ( isset( $_POST['iprm_check_manually'] ) && ( '' !== $_POST['iprm_check_manually'] ) ) {
		$podcast->get_itunes_feed_contents();
	} elseif ( isset( $_POST['iprm_reset_all_data'] ) && ( '' !== $_POST['iprm_reset_all_data'] ) ) {
		/* IF RESET ALL BUTTON IS PRESSED, DELETE OPTIONS AND CRON JOBS */
		$podcast_array = iprm_get_option( 'iprm_podcasts' );
		/* IF ITS EMPTY IT EQUALS 1 FOR SOME REASON */
		if ( is_array( $podcast_array ) ) {
			foreach ( $podcast_array as $url_str ) {
				iprm_delete_product( $url_str );
			}
		}
		iprm_delete_option( 'iprm_active_product' );
		$notice = __( 'All settings and cache have been cleared.', 'iprm_domain' );
	}

	/* IF CACHE IS EMPTY, DISPLAY NOTICE */
	if ( empty( $podcast->reviews ) && ( isset( $podcast->itunes_id ) ) ) {
		$podcast->get_itunes_feed_contents();
	}

	/* START OUTPUT */
	echo "<div id='iprm_wrapper'>";
	echo iprm_display_alert( $alert );
	echo iprm_display_notice( $notice );
	if ( isset( $podcast->itunes_id ) ) {
		echo $podcast->display_itunes_feed_summary();
	};

	ob_start(); ?>
		<div id="iprm_settings_bar" class="iprm_panel">
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
			<div id="iprm_input_settings">
				<h2><?php _e( 'Settings', 'iprm_domain' ); ?></h2>
				<div class="iprm_panel_content">
					<h3><?php _e( 'Product URL', 'iprm_domain' ); ?></h3>	
					<p><?php _e( 'Adding a new product or manually checking may take a few minutes.', 'iprm_domain' ); ?></p>
					<input type="url" id="iprm_add_url" name="iprm_add_url" size="80" value="<?php echo $podcast->itunes_url; ?>">
					<input class="iprm_button_small" type="submit" name="iprm_update_url" value="UPDATE">
				</div>
			</div>
			<div id="iprm_crawl_settings">
				<h2><?php _e( 'History', 'iprm_domain' ); ?></h2>
				<div class="iprm_panel_content">
					<h3><?php _e( 'Recent History', 'iprm_domain' ); ?></h3>
					<p>
						<?php
						$i = 1;
						if ( is_array( $podcast->review_cache_history ) ) {
							foreach ( array_reverse( $podcast->review_cache_history ) as $item ) {
								$i++;
								echo $item['time'] . ' Reviews: ' . $item['count'] . '<br />';
								if ( $i > 5 ) {
									break;
								}
							}
						}
						?>
					</p>
					<p><?php _e( 'Reviews update automatically every 4 hours.', 'iprm_domain' ); ?></p>
					<input class="iprm_button_small" type="submit" name="iprm_check_manually" id="iprm_check_manually_btn"value="<?php _e( 'CHECK MANUALLY', 'iprm_domain' ); ?>">
				</div>
			</div>
		</form>
	</div>
	<?php
		/* DISPLAY REVIEWS FROM CACHE */
	if ( ! empty( $podcast->reviews ) ) {
		echo $podcast->display_page_reviews();
	}
	?>
		<footer>
			<p>Flag icons by <a href="http://www.icondrawer.com" target="_blank">IconDrawer</a>.</p>
		</footer>
	</div>
	<?php
	echo ob_get_clean();
}

function iprm_add_main_page_link() {
	add_menu_page( 'Podcast Reviews', 'Podcast Reviews', 'manage_options', 'iprm_main_page', 'iprm_main_page', 'dashicons-star-filled' );
}
