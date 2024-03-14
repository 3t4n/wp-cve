<div class="wrap">
    
		<img width="50" height="50" src="<?PHP echo  plugins_url('../images/icon-512x512.png', __FILE__); ?>" style="vertical-align:middle;" alt="Slovenská pošta - ePodací hárok" itemprop="logo">
		<h1 style="display:inline-block;padding-left: 20px;"><?php _e('ITMan Page Speed Insights','itman-page-speed-insights'); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

				<!-- Display warning if run on localhost -->
				<?php
					if (itps_is_localhost()) {
				?>

				<div class="postbox">
					<h2><?php _e('You are on localhost','itman-page-speed-insights'); ?></h2>
					<div class="inside">
						<p><?php _e('We are sorry, performance of your site can not be measured on localhost.','itman-page-speed-insights'); ?></p>
					</div>  					
				</div>

				<?php
					} else {
				?>				
				<!-- History data chart -->
				<div class="postbox">
					<h2><?php _e('History data chart','itman-page-speed-insights'); ?></h2>
					<div class="inside">
						<p><?php _e('Performance index for desktop and mobile over last 30 days.','itman-page-speed-insights'); ?></p>
					</div>  
				</div>
				<div class="postbox" id="resize" style="padding: 5px;">
					<!--Google Chart -->
					<div id="page_speed_history_chart" style="height: 60vh; border: 1px solid #ebebeb;"></div>
				</div>
				<?php
					} // end of itps_is_localhost check
				?>	
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h3><?php _e('About plugin','itman-page-speed-insights'); ?></h3>

						<div class="inside">
					<p><?php _e('Plugin was developed as a support tool for <a href="http://www.itman.sk/en/" target="_blank">ITMan.sk</a>. We take care of your WP web and make sure it is fast, secured and always up to date.','itman-page-speed-insights'); ?></p>
					<h3><?php _e('Idea for improvement?','itman-page-speed-insights'); ?></h3>
					<p><?php _e('Write me a <a href="mailto:matej.podstrelenec@gmail.com">message</a>. I will gladly implement any suggested improvement.','itman-page-speed-insights'); ?></h3></p>
					<h3><?php _e('Author','itman-page-speed-insights'); ?></h3>
					<center>
						<img src="<?PHP echo plugins_url('../images/author.png', __FILE__);  ?>">
					</center>
					<p>Matej Podstrelenec<br><a href="https://www.matejpodstrelenec.sk" target="_blank">matejpodstrelenec.sk</a></p>
					<p><?php _e('Need help with Wordpress? <a href="mailto:matej.podstrelenec@gmail.com">Let me know.</a>','itman-page-speed-insights'); ?></p>                    
					<p></p>	
				</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->