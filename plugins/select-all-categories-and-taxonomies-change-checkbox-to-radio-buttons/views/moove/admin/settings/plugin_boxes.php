<?php 
	$radio_controller 	= new Moove_Radioselect_Controller();
	$plugin_details 		= $radio_controller->get_plugin_details( 'select-all-categories-and-taxonomies-change-checkbox-to-radio-buttons' );
?>
<div class="moove-radioselect-plugins-info-boxes">

	<div class="m-plugin-box">
		<div class="box-header">
			<h4>GDPR / CCPA Cookie Compliance</h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank">
				<img src='<?php echo trailingslashit( ctb_get_plugin_directory_url() ); ?>assets/images/gdpr-promo-wp.png?rev=<?php echo MOOVE_RADIOSELECT_VERSION; ?>'/>
			</a>
			<hr>
			<p>Prepare your website for cookie consent requirements with this incredibly powerful, easy-to-use, well supported and 100% free WordPress plugin.</p>

			<hr />
			<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="plugin-buy-now-btn">Free trial</a>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->

	<div class="m-plugin-box">
		<div class="box-header">
			<h4><?php esc_html_e( 'User Activity Tracking and Log', 'gdpr-cookie-compliance' ); ?></h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<a href="https://www.mooveagency.com/wordpress-plugins/user-activity-tracking-and-log/" target="_blank">
				<img src='<?php echo trailingslashit( ctb_get_plugin_directory_url() ); ?>assets/images/uat-promo-wp.png?rev=<?php echo MOOVE_RADIOSELECT_VERSION; ?>'/>
			</a>
			<hr>
			<p>Track user activity & duration on your website with this incredibly powerful, easy-to-use and well supported plugin.</p>

			<hr />
			<a href="https://www.mooveagency.com/wordpress-plugins/user-activity-tracking-and-log/" target="_blank" class="plugin-buy-now-btn">Free trial</a>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->

	<?php ob_start(); ?>
		<div class="m-plugin-box m-plugin-box-highlighted	">
			<div class="box-header">
				<h4>Donations</h4>
			</div>
			<!--  .box-header -->
			<div class="box-content">
				<p>If you enjoy using this plugin and find it useful, feel free to donate a small amount to show appreciation and help us continue improving and supporting this plugin for free.</p><p>It will make our development team very happy! :)</p>
				<hr />
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" class="moove-gdpr-donate-form">
	                <input type="hidden" name="cmd" value="_s-xclick">
	                <input type="hidden" name="hosted_button_id" value="84Z843VMHAFTE">
	                <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
	                <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	            </form>
			</div>
			<!--  .box-content -->
		</div>
		<!--  .m-plugin-box -->
	<?php echo apply_filters( 'radioselect_donate_section', ob_get_clean() ); ?>

	<div class="m-plugin-box">
		<div class="box-header">
			<h4>Help to improve this plugin!</h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<p>Enjoyed this plugin? <br />You can help by <a href="https://wordpress.org/support/plugin/select-all-categories-and-taxonomies-change-checkbox-to-radio-buttons/reviews/?rate=5#new-post" target="_blank">rating this plugin on wordpress.org.</a></p>
			<hr />
			<?php if ( $plugin_details ) : ?>
			<div class="plugin-stats">
				<div class="plugin-downloads">
					Downloads: <strong><?php echo number_format( $plugin_details->downloaded, 0, '', ','); ?></strong>
				</div>
				<!--  .plugin-downloads -->
				<div class="plugin-active-installs">
					Active installations: <strong><?php echo number_format( $plugin_details->active_installs, 0, '', ','); ?>+</strong>
				</div>
				<!--  .plugin-downloads -->
				<div class="plugin-rating">
					<?php 
						$rating_val = $plugin_details->rating * 5 / 100;
						if ( $rating_val > 0 ) :
                            $args = array(
                                'rating' 	=> $rating_val,
                                'number' 	=> $plugin_details->num_ratings,
                                'echo'		=> false
                            );
                            $rating = wp_star_rating( $args );
                        endif;
					?>
					<?php if ( $rating ) : ?>
						<?php echo $rating; ?>
					<?php endif; ?>
				</div>
				<!--  .plugin-rating -->
			</div>
			<!--  .plugin-stats -->
			<?php endif; ?>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->

	<div class="m-plugin-box">
		<div class="box-header">
			<h4>Need Support or New Feature?</h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<?php 			
				$forum_link = apply_filters( 'radioselect_forum_section_link', 'https://wordpress.org/support/plugin/select-all-categories-and-taxonomies-change-checkbox-to-radio-buttons/' );
			?>
			<p>Submit your request to our <a href="<?php echo $forum_link; ?>" target="_blank">Support Forum</a>.</p>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->
	
</div>
<!--  .moove-plugins-info-boxes -->