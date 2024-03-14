<?php

/*
* Load widget data from DB
*/
function itps_load_widget_data() {
	global $wpdb;
	global $desktop_results;
	global $mobile_results;

	$table_name = $wpdb->prefix . "itman_page_speed_insights";   

	//Desktop data
	$desktop_results = $wpdb->get_results( "SELECT * FROM $table_name WHERE strategy = 1 ORDER BY measure_date DESC LIMIT 1");
	
	//Mobile data
	$mobile_results = $wpdb->get_results( "SELECT * FROM $table_name WHERE strategy = 2 ORDER BY measure_date DESC LIMIT 1");
}

/*
* Add plugin widget to dashboard
*/
function itps_add_dashboard_widgets() {
 	wp_add_dashboard_widget( 'itps_dashboard_widget', __('ITMan Page Speed Insights','itman-page-speed-insights'), 'itps_dashboard_widget_function' );
 	
 	// Globalize the metaboxes array, this holds all the widgets for wp-admin
 	global $wp_meta_boxes;
 	
 	// Get the regular dashboard widgets array 
 	// (which has new widget already but at the end)
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete new dashboard widget from the end of the array
 	$itps_dashboard_widget_backup = array( 'itps_dashboard_widget' => $normal_dashboard['itps_dashboard_widget'] );
 	unset( $normal_dashboard['itps_dashboard_widget'] );
 
 	// Merge the two arrays together so that widget is at the beginning
 	$sorted_dashboard = array_merge( $itps_dashboard_widget_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
} 

add_action( 'wp_dashboard_setup', 'itps_add_dashboard_widgets' );

/**
 * Function to output the contents of Dashboard Widget.
 */
function itps_dashboard_widget_function() {

	global $desktop_results; 
	global $mobile_results;

	$itps_status = get_option('itps_status');

?>

<div id="widget" style="border: 1px solid #ebebeb;padding: 5px;">

	<!-- Display warning if run on localhost -->
	<?php
		if (itps_is_localhost()) {
	?>
		<p><?php _e('We are sorry, performance of your site can not be measured on localhost.','itman-page-speed-insights'); ?></p>

	<?php
		} else {
	?>		

	<div style="text-align: center; padding-top:10px;">    
		<!-- Test data for availability -->
		<?php
		if(empty($desktop_results))                      
		{
			?>
				<div id="widget" style="border: 1px solid #ebebeb;padding: 5px;">
					<div style="text-align: center;">
						<?php 
							
							if ($itps_status == 1) _e('Mobile measurement is in progress, please refresh.','itman-page-speed-insights');
							else  _e('No desktop data available.','itman-page-speed-insights');
						?>
					</div>
				</div>
			<?php      
		}			
		if(empty($mobile_results))                      
		{
			?>
				<div id="widget" style="border: 1px solid #ebebeb;padding: 5px;">
					<div style="text-align: center;">
						<?php 
							if ($itps_status == 1) _e('Desktop measurement is in progress, please refresh.','itman-page-speed-insights');
							else  _e('No desktop data available.','itman-page-speed-insights');
						?>
					</div>
				</div>
			<?php      
		}	
		?>


		<?php
			if ($itps_status == 2) {
		?>

		<!-- Desktop -->
		<div class="wrapper desktop">
			<div style="text-align: center;width: inherit;"><?php _e('Desktop','itman-page-speed-insights'); ?></div>
			<svg id="meter">
				<circle r="53" cx="50%" cy="50%" stroke="#e8eaed" stroke-width="10" fill="none"></circle>
				<circle r="53" cx="50%" cy="50%" stroke="<?php echo itps_get_color($desktop_results[0]->performance_score); ?>" stroke-width="10" fill="none" class="frontCircle mobile_mainColor"></circle> <!--  transform="rotate(-90,240,240)" -->
			 </svg>
			 <div class="perf_percentage desktop_mainColor"><?php echo $desktop_results[0]->performance_score; ?></div>
		</div>

		<!-- Mobile -->
		<div class="wrapper mobile">
			<div style="text-align: center;width: inherit;"><?php _e('Mobile','itman-page-speed-insights'); ?></div>
			<svg id="meter">
				<circle r="53" cx="50%" cy="50%" stroke="#e8eaed" stroke-width="10" fill="none"></circle>
				<circle r="53" cx="50%" cy="50%" stroke="<?php echo itps_get_color($mobile_results[0]->performance_score); ?>" stroke-width="10" fill="none" class="frontCircle mobile_mainColor"></circle> <!--  transform="rotate(-90,240,240)" -->
			</svg>
			<div class="perf_percentage mobile_mainColor"><?php echo $mobile_results[0]->performance_score; ?></div>
		</div>	
	</div>

	<!-- Statistics -->
	<div style="padding:10px;font-family: Roboto,Helvetica,Arial,sans-serif; font-size: 14px;">
		<div style="border-bottom: 1px solid #ebebeb; display: flex;justify-content: space-between; padding:8px;"><span><?php _e('First Contentful Paint','itman-page-speed-insights'); ?></span><div style="padding-right: 5px; font-weight:bold;"><span class="desktop_mainColor"><?php echo $desktop_results[0]->first_contentful_paint; ?>&nbsp;s</span>&nbsp;/&nbsp;<span class="mobile_mainColor"><?php echo $mobile_results[0]->first_contentful_paint; ?>&nbsp;s</span></div></div>
		<div style="border-bottom: 1px solid #ebebeb; display: flex;justify-content: space-between; padding:8px;"><span><?php _e('Speed Index','itman-page-speed-insights'); ?></span><div style="padding-right: 5px; font-weight:bold;"><span class="desktop_mainColor"><?php echo $desktop_results[0]->speed_index; ?>&nbsp;s</span>&nbsp;/&nbsp;<span class="mobile_mainColor"><?php echo $mobile_results[0]->speed_index; ?>&nbsp;s</span></div></div>
		<div style="border-bottom: 1px solid #ebebeb; display: flex;justify-content: space-between; padding:8px;"><span><?php _e('Time to Interactive','itman-page-speed-insights'); ?></span><div style="padding-right: 5px; font-weight:bold;"><span class="desktop_mainColor"><?php echo $desktop_results[0]->interactive; ?>&nbsp;s</span>&nbsp;/&nbsp;<span class="mobile_mainColor"><?php echo $mobile_results[0]->interactive; ?>&nbsp;s</span></div></div>
		<div style="color: #0000008a; font-size: 10px; text-align:right;"><?php _e('Measured at','itman-page-speed-insights');?> <?php echo $mobile_results[0]->measure_date . " " . __('By','itman-page-speed-insights'); ?>: <a href="https://www.itman.sk/en/" target="_blank">ITMan</a></div>	    
		
		<?php
			$site_url = get_home_url();
			$google_page_speed_call = "https://developers.google.com/speed/pagespeed/insights/?url=" .  $site_url;
		?>
		
		<div style="margin-top: 10px; font-size:12px; text-align: center;"><a href="<?php echo $google_page_speed_call; ?>" target="_blank" style="outline: 0;text-decoration: none;"><?php _e('View complete results','itman-page-speed-insights'); ?></a> <?php _e('on Google PageSpeed Insights.','itman-page-speed-insights'); ?></div>
	</div>

		<?php
			} //end of itps_status check
		?>

	<?php
		} // end of itps_is_localhost check
	?>		

</div>
<?php
}

/**
 * Load dynamic CSS based on most recent mobile & desktop results
 */
function itps_load_widget_dyn_style() {

	global $desktop_results; 
	global $mobile_results;

	$desktop_performance_score = 0;
	$mobile_performance_score = 0;

	if (isset($desktop_results[0]->performance_score)) $desktop_performance_score = $desktop_results[0]->performance_score;
	if (isset($mobile_results[0]->performance_score)) $mobile_performance_score = $mobile_results[0]->performance_score;

	$desktop_main_color = itps_get_color($desktop_performance_score);
	$mobile_main_color = itps_get_color($mobile_performance_score);

	$desktop_style = "
		.desktop .frontCircle{
			stroke-linecap: round;
			-webkit-animation-name: itman-desktop; /* Safari 4.0 - 8.0 */
			-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
			-webkit-animation-timing-function: ease forwards;
			animation-name: itman-desktop;
			animation-duration: 2s;
			animation-timing-function: ease forwards;
			stroke-dasharray: " . (($desktop_performance_score*329)/100) . ", 329; 
		}	
		.desktop_mainColor {
			color: " . $desktop_main_color . ";
		}	

		@keyframes itman-desktop {
		  	from {stroke-dasharray:0, 329;}
		  	to {stroke-dasharray:" . (($desktop_performance_score*329)/100) . ", 329};
		}
		";
	
	$mobile_style = "	
		.mobile .frontCircle{
			stroke-linecap: round;
			-webkit-animation-name: itman-mobile; /* Safari 4.0 - 8.0 */
			-webkit-animation-duration: 2s; /* Safari 4.0 - 8.0 */
			-webkit-animation-timing-function: ease forwards;
			animation-name: itman-mobile;
			animation-duration: 2s;
			animation-timing-function: ease forwards;
			stroke-dasharray: " . (($mobile_performance_score*329)/100) . ", 329; 
		}	
		.mobile_mainColor {
			color: " . $mobile_main_color . ";
		}	

		@keyframes itman-mobile {
			from {stroke-dasharray:0, 329;}
		  	to {stroke-dasharray:" . (($mobile_performance_score*329)/100) . ", 329};
		}		
	";	
	wp_add_inline_style( 'Page Speed Insights', $desktop_style . $mobile_style);
}

add_action( 'admin_enqueue_scripts', 'itps_load_widget_dyn_style' );