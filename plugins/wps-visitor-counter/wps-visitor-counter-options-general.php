<?php
if (!current_user_can('administrator'))  {
	wp_die( __('You do not have sufficient permissions to access this page.', 'wps-visitor-counter') );
}



$wps_option_data = wps_visitor_option_data(1);
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('Plugin Options WPS Visitor Counter', 'wps-visitor-counter')?></h2><br/>
    <div class="wpsvc_plugins_wrap"><!-- start mvc wrap -->
	<div class="wpsvc_right_sidebar"><!-- start right sidebar -->
		
		
        <!-- Support Banner -->
        <div class="wpsvc_plugins_text">
        	<div class="wpsvc_option_wrap">
				<h3 class="hndle"><?php _e('Recommended Web Hosting', 'wps-visitor-counter')?></h3>
				
				
				<!----fastcomet----->
				<script type="text/javascript">document.write("<iframe name='banner' src='https://affiliate.fastcomet.com/scripts/banner.php?a_aid=5bd818a517dc6&a_bid=a881856b&w=1&refx2s6d="+encodeURIComponent(encodeURIComponent(document.URL))+"' framespacing='0' frameborder='no' scrolling='no' width='264' height='264' allowtransparency='true'><a href='https://affiliate.fastcomet.com/scripts/click.php?a_aid=5bd818a517dc6&amp;a_bid=a881856b' target='_top'>264x264 Robot Clipart (animated)</a></iframe>");
</script>
<noscript>
<h2><a href="http://www.fastcomet.com/">264x264 Robot Clipart (animated)</a></h2>
</noscript>
				<!----fastcomet end----->
				<a href="https://www.a2hosting.com?aid=5bf7c623aa2a9&amp;bid=d6664600" target="_top"><img src="//affiliates.a2hosting.com/accounts/default1/banners/d6664600.jpg" alt="" title="" width="336" height="280" /></a><img style="border:0" src="https://affiliates.a2hosting.com/scripts/imp.php?aid=5bf7c623aa2a9&amp;bid=d6664600" width="1" height="1" alt="" />
				
			</div>
		</div>
        
		
		<div class="wpsvc_plugins_text">
        	<div class="wpsvc_option_wrap">
        		<?php 
        		$wps_display_field = $wps_option_data['display_field'];
        		$wps_display_field_arr = explode(",",$wps_display_field);
        		?>
        		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="wps_plugin_main_form">
        			<?php wp_nonce_field('wps_my_front_end_setting'); ?>
				<div><label for="wps_visitor_title"><?php _e('Title:', 'wps-visitor-counter')?> <input class="widefat" id="wps_visitor_titletitle" name="wps_visitor_title" type="text" value="<?php echo esc_html($wps_option_data['visitor_title']);?>" /></label></div>
				<?php
wp_enqueue_script('wp-color-picker');
wp_enqueue_style( 'wp-color-picker' );
?>
	<div><label for="wps_visitor_font_color"><?php _e('Font Color:', 'wps-visitor-counter')?> </label><input class="widefat" id="wps_visitor_font_color" data-default-color="#000000" name="wps_visitor_font_color" type="text" value="<?php echo esc_html($wps_option_data['font_color']);?>" /></div>
<script type="text/javascript">
				jQuery(document).ready(function($) {   
					$('#wps_visitor_font_color').wpColorPicker();
				});             
				</script>

	<div><font size='2'><?php _e('To change the font color, select the color with color picker.', 'wps-visitor-counter')?> </font></div>
	<div><font size='3'><?php _e('<b>PLugin Options</b>', 'wps-visitor-counter')?></font></div>
	<!-- UPDATE PLAN -->
	<div><label for="wps_visitor_user_start">Users Count Start: <input class="widefat" id="wps_visitor_user_start" name="wps_visitor_user_start" type="number" min="0" value="<?php echo esc_html($wps_option_data['user_start']);?>" /></label></div>
	<div><font size='2'>Fill in with numbers to start the initial calculation of the user counter, if the empty counter will start from 1</font></div>
	<div><label for="wps_visitor_views_start">views Start: <input class="widefat" id="wps_visitor_views_start" name="wps_visitor_views_start" type="number" min="0" value="<?php echo esc_html($wps_option_data['views_start']);?>" /></label></div>
	<div><font size='2'>Fill in the numbers to start the initial calculation of the views, if the empty views will start from 1</font></div>
	<!-- END UPDATE -->
	<div><label for="wps_visitor_today_user"><?php _e('Enable Users Today display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("today_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_today_user" name="wps_visitor_today_user" /></label></div>
	<div><label for="wps_visitor_yesterday_user"><?php _e('Enable Users Yesterday display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("yesterday_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_yesterday_user" name="wps_visitor_yesterday_user" /></label></div>

	<div><label for="wps_visitor_last7_day_user"><?php _e('Enable Users Last 7 Days display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("last7_day_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_last7_day_user" name="wps_visitor_last7_day_user" /></label></div>

	<div><label for="wps_visitor_last30_day_user"><?php _e('Enable Users Last 30 Days display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("last30_day_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_last30_day_user" name="wps_visitor_last30_day_user" /></label></div>

	<div><label for="wps_visitor_month_user"><?php _e('Enable Users This Month display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("month_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_month_user" name="wps_visitor_month_user" /></label></div>
	<div><label for="wps_visitor_year_user"><?php _e('Enable Users This Year display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("year_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_year_user" name="wps_visitor_year_user" /></label></div>
	<div><label for="wps_visitor_total_user"><?php _e('Enable Total Users display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("total_user", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_total_user" name="wps_visitor_total_user" /></label></div>
	<div><label for="wps_visitor_today_view"><?php _e('Enable views Today display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("today_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_today_view" name="wps_visitor_today_view" /></label></div>

	<div><label for="wps_visitor_yesterday_view"><?php _e('Enable views Yesterday display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("yesterday_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_yesterday_view" name="wps_visitor_yesterday_view" /></label></div>

	
	<div><label for="wps_visitor_last7_day_view"><?php _e('Enable views Last 7 Days display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("last7_day_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_last7_day_view" name="wps_visitor_last7_day_view" /></label></div>

	<div><label for="wps_visitor_last30_day_view"><?php _e('Enable views Last 30 Days display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("last30_day_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_last30_day_view" name="wps_visitor_last30_day_view" /></label></div>


	<div><label for="wps_visitor_month_view"><?php _e('Enable views This Month display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("month_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_month_view" name="wps_visitor_month_view" /></label></div>
	<div><label for="wps_visitor_year_view"><?php _e('Enable views This Year display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("year_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_year_view" name="wps_visitor_year_view" /></label></div>



	<div><label for="wps_visitor_total_view"><?php _e('Enable Total views display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("total_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_total_view" name="wps_visitor_total_view" /></label></div>
	<div><label for="wps_visitor_online_view"><?php _e('Enable Whos Online display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("online_view", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_online_view" name="wps_visitor_online_view" /></label></div>
	<div><label for="wps_visitor_ip_display"><?php _e('Enable IP address display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("ip_display", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_ip_display" name="wps_visitor_ip_display" /></label></div>
	<div><label for="wps_visitor_server_time"><?php _e('Enable Server Time display?', 'wps-visitor-counter')?> <input type="checkbox" class="checkbox" <?php if (in_array("server_time", $wps_display_field_arr)) {echo "checked";} ?> id="wps_visitor_server_time" name="wps_visitor_server_time" /></label></div>
	<div><label for="wps_visitor_wpsvc_align"><?php _e('Plugins align?', 'wps-visitor-counter')?> 
	<select class="select" id="wps_visitor_wpsvc_align" name="wps_visitor_wpsvc_align" selected="<?php echo $wps_option_data['visitor_wpsvc_align'];?>">
	<option value="left"><?php _e('wps_visitor_wpsvc_align', 'wps-visitor-counter') ?></option>
	<option value="left" <?php if($wps_option_data['visitor_wpsvc_align'] == 'left'){echo "selected";}?>><?php _e('Left', 'wps-visitor-counter') ?></option>
	<option value="center" <?php if($wps_option_data['visitor_wpsvc_align'] == 'center'){echo "selected";}?>><?php _e('Center', 'wps-visitor-counter') ?></option>
	<option value="right" <?php if($wps_option_data['visitor_wpsvc_align'] == 'right'){echo "selected";}?>><?php _e('Right', 'wps-visitor-counter') ?></option>
	</select></label></div>
					<input type="submit" name="wps_view_setting" class="button-primary" value="<?php _e('Save Changes', 'wps-visitor-counter') ?>" /></form>
			</div>
		</div>
		<div class="wpsvc_plugins_text">
        	<div class="wpsvc_option_wrap">
        		<h3 class="hndle"><?php _e('The way of use', 'wps-visitor-counter') ?></h3>
        		<p><?php _e('Use this <b>"[wps_visitor_counter]"</b> shortcode or use in your registered widget.', 'wps-visitor-counter') ?></p>
        	</div>
        </div>
		<div class="wpsvc_plugins_text">
			<div class="wpsvc_option_wrap">
				<h3 class="hndle"><?php _e('Reset Plugin Data', 'wps-visitor-counter') ?></h3>
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<?php wp_nonce_field('wps_table_reset'); ?>
			        <p style="margin-top:20px;" >
					<?php _e('Check for reset', 'wps-visitor-counter'); ?> <input type="checkbox" class="checkbox" id="reset_wpsvc" name="reset_wpsvc" />
			        <input type="submit" class="button-primary" value="<?php _e('Reset Data', 'wps-visitor-counter') ?>" />
			        </p>
				</form>
			</div>
		</div>
    </div><!-- End Right sidebar -->




    <div class="wpsvc_left_sidebar"><!-- start Left sidebar -->
    <div class="wpsvc_plugins_text">
    <div class="wpsvc_option_wrap">
		<h3 class="hndle"><?php _e('Google AdSense', 'wps-visitor-counter') ?></h3>
		<a href="https://partner.pcloud.com/r/7781" title="pCloud Premium" target="_blank"><img src="https://partner.pcloud.com/media/banners/lifetime/lifetime00572890.jpg" alt="pCloud Premium"/></a>
		
		<a href="https://www.tubebuddy.com/offertail" title="TubeBuddy" target="_blank"><img src="https://www.tubebuddy.com/assets/images/AffiliateAssets/Banner-728x90.png" alt="TubeBuddy"/></a>
		
		
	</div>
	<div class="wpsvc_option_wrap wps_follow_button">
		<h3 class="hndle"><?php _e('Follow us', 'wps-visitor-counter') ?></h3>
		<a href="https://www.facebook.com/TechMix365"><img src="<?php echo plugins_url ("counter/fb.png" , __FILE__ );?>" alt=""></a>
		<a href="https://twitter.com/TechMix365"><img src="<?php echo plugins_url ("counter/twitter.png" , __FILE__ );?>" alt=""></a>
	</div>
	</div>
	<div class="wpsvc_option_wrap">
	<div class="wpsvc_plugins_text">
	<h3 class="hndle"><?php _e('Image Counter', 'wps-visitor-counter') ?></h3>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<?php wp_nonce_field('wps_my_front_end_style'); ?>


       <?php
            $data = wps_visitor_counter_styles(WP_CONTENT_DIR . '/plugins/wps-visitor-counter/styles/');
            foreach ($data as $parent_folder => $records) {
                foreach ($records as $style_folder => $style_records) {
                    foreach ($style_records as $style => $test) {
                        preg_match('/styles\/(.*?)\/(.*?)\//', $test, $match);
                        $groups[$match[1]][] = $match[2];
                    }
                }
            }
        ?>
		  <?php
            foreach ($groups as $style_name => $style) {
?>
					
 					<p><b>Choose one of the <?php echo $style_name; ?> counter styles below:</b></p>
						<table class="form-table">
						<?php
                foreach ($style as $name) {
                    ?>
                    	<tr>
                		<td>
                		<input type="radio" id="img1" name="wps_visitor_counter_style" value="<?php echo 'image/'.$name; ?>" <?php if($wps_option_data['style'] == 'image/'.$name){echo "checked";}?>/>
                		<img src='<?php echo plugin_dir_url( __FILE__ );?>styles/<?php echo $style_name . '/' . $name . '/'; ?>11.jpg'>
        
                		</td>
                	</tr>
					  <?php
                }
			?>
			<tr>
                		<td>
                		<input type="radio" id="img1" name="wps_visitor_counter_style" value="text/effect-black" <?php if($wps_option_data['style'] == 'text/effect-black'){echo "checked";}?>/>
                		<div class="wps_text_glowing effect-black">
							<span>0</span>
							<span>1</span>
							<span>2</span>
							<span>3</span>
							<span>4</span>
						</div>
        
                		</td>
                	</tr>
                	<tr>
                		<td>
                		<input type="radio" id="img1" name="wps_visitor_counter_style" value="text/effect-white" <?php if($wps_option_data['style'] == 'text/effect-white'){echo "checked";}?>/>
                		<div class="wps_text_glowing effect-white">
							<span>0</span>
							<span>1</span>
							<span>2</span>
							<span>3</span>
							<span>4</span>
						</div>
        
                		</td>
                	</tr>
          
		  </table>
         
<?php
            }
        ?>    
		<p><?php _e('Show powered by <a href="https://techmix.xyz/">WPS Visitor Counter</a>? ', 'wps-visitor-counter'); ?> <input type="checkbox" class="checkbox" id="wps_visitor_counter_attribution" name="wps_visitor_counter_attribution" <?php if($wps_option_data['show_powered_by'] == 1 ){echo "checked";} ?>/></p>
        <p style="margin-top:20px;" >
        <input type="submit" name="style_setting" class="button-primary" value="<?php _e('Save Changes', 'wps-visitor-counter') ?>" />
        </p>
	</form>
	</div>
	</div>
	
	
	
	
	

	</div><!-- End Left sidebar -->
	</div><!-- End mvc wrap -->
</div>

