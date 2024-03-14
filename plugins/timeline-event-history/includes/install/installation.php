<?php
add_action( 'admin_notices', 'teh_cns_review' );
function teh_cns_review() {

	// Verify that we can do a check for reviews.
	$review = get_option( 'teh_cns_review' );
	$time	= time();
	$load	= false;
	if ( ! $review ) {
		$review = array(
			'time' 		=> $time,
			'dismissed' => false
		);
		add_option('teh_cns_review', $review);
		//$load = true;
	} else {
		// Check if it has been dismissed or not.
		if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) && (isset( $review['time'] ) && (($review['time'] + (DAY_IN_SECONDS * 2)) <= $time)) ) {
			$load = true;
		}
	}
	// If we cannot load, return early.
	if ( ! $load ) {
		return;
	}

	// We have a candidate! Output a review message.
	?>
	<div class="notice notice-info is-dismissible teh-cns-review-notice">
		<div style="float:left;margin-right:10px;margin-bottom:5px;">
			<img style="width:100%;width: 150px;height: auto;" src="<?php echo esc_url(TIMELINE_WP_IMAGES.'icon-128x128.jpg'); ?>" />
		</div>
		<p style="font-size:18px;"><?php esc_html_e('Hi! We saw you have been using ',timeline_wp); ?><strong><?php esc_html_e('Timeline Event History',timeline_wp); ?></strong><?php esc_html_e(' for a few days and wanted to ask for your help to ',timeline_wp); ?><strong><?php esc_html_e('make the plugin better',timeline_wp); ?></strong><?php esc_html_e('. We just need a minute of your time to rate the plugin. Thank you!',timeline_wp); ?></p>
		<p style="font-size:18px;"><strong><?php _e( '~ wpdiscover', '' ); ?></strong></p>
		<p style="font-size:19px;"> 
			<a style="color: #fff;background: #ef4238;padding: 5px 7px 4px 6px;border-radius: 4px; text-decoration: none;" href="https://wordpress.org/support/plugin/timeline-event-history/reviews/" class="teh-cns-dismiss-review-notice teh-cns-review-out" target="_blank" rel="noopener"><?php esc_html_e('Rate the plugin',timeline_wp); ?></a>&nbsp; &nbsp;
			<a style="color: #fff;background: #27d63c;padding: 5px 7px 4px 6px;border-radius: 4px; text-decoration: none;" href="#"  class="teh-cns-dismiss-review-notice teh-rate-later" target="_self" rel="noopener"><?php _e( 'Nope, maybe later', '' ); ?></a>&nbsp; &nbsp;
			<a style="color: #fff;background: #31a3dd;padding: 5px 7px 4px 6px;border-radius: 4px; text-decoration: none;" href="#" class="teh-cns-dismiss-review-notice teh-rated" target="_self" rel="noopener"><?php _e( 'I already did', '' ); ?></a>
		<a style="    color: #fff;
    background: #5c60d0;
    padding: 5px 7px 4px 6px;
    border-radius: 4px;
    margin-left: 10px;
    text-decoration: none;" href="<?php echo TIMELINE_WP_PLUGIN_UPGRADE; ?>" class="btn btn-primary" target="_blank" rel="noopener"><?php _e( 'Upgrade To Timeline Event History Pro Plugin', '' ); ?></a>
		</p>
	</div>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$(document).on('click', '.teh-cns-dismiss-review-notice, .teh-cns-dismiss-notice .notice-dismiss', function( event ) {
				if ( $(this).hasClass('teh-cns-review-out') ) {
					var teh_rate_data_val = "1";
				}
				if ( $(this).hasClass('teh-rate-later') ) {
					var teh_rate_data_val =  "2";
					event.preventDefault();
				}
				if ( $(this).hasClass('teh-rated') ) {
					var teh_rate_data_val =  "3";
					event.preventDefault();
				}

				$.post( ajaxurl, {
					action: 'teh_cns_dismiss_review',
					teh_rate_data_cns : teh_rate_data_val
				});
				
				$('.teh-cns-review-notice').hide();
				//location.reload();
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_teh_cns_dismiss_review', 'teh_cns_dismiss_review' );
function teh_cns_dismiss_review() {
	if ( ! $review ) {
		$review = array();
	}
	
	if($_POST['teh_rate_data_cns']=="1"){
		
	}
	if($_POST['teh_rate_data_cns']=="2"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		update_option( 'teh_cns_review', $review );
	}
	if($_POST['teh_rate_data_cns']=="3"){
		$review['time'] 	 = time();
		$review['dismissed'] = true;
		update_option( 'teh_cns_review', $review );
	}
	
	die;
}
?>