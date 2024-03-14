<?php
if (!current_user_can('administrator'))  {
	wp_die( __('You do not have sufficient permissions to access this page.', 'xt-visitor-counter') );
}

if ($_POST['reset_xtvc']) {
	xt_visitor_counter_truncate();
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
    <h2>Plugin Options XT Visitor Counter</h2><br/>
    <div class="xtvc_plugins_wrap"><!-- start mvc wrap -->
	<div class="xtvc_right_sidebar"><!-- start right sidebar -->
		<div class="xtvc_plugins_text">
        	<div class="xtvc_option_wrap">
				<h3 class="hndle">Color Picker</h3>
<?php
wp_enqueue_script('wp-color-picker');
wp_enqueue_style( 'wp-color-picker' );
?>
				<input name="mv_cr_section_color" type="text" id="mv_cr_section_color" value="#ffffff" data-default-color="#ffffff"/>
				<script type="text/javascript">
				jQuery(document).ready(function($) {   
					$('#mv_cr_section_color').wpColorPicker();
				});             
				</script>
			</div>
		</div>
		
		<div class="xtvc_plugins_text">
        	<div class="xtvc_option_wrap">
				<h3 class="hndle">Donate</h3>
				<p>If you like and helped with my plugins, please donate to the developer. how much your nominal will help developers to develop these plugins. Also, dont forget to follow me on <a href="https://twitter.com/xtrsyz" target="_blank">Twitter</a>.</p>
				<div>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick"/>
					<input type="hidden" name="hosted_button_id" value="MWJXZM6HCFNQ4"/>
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
					</form>
				</div>
			</div>
		</div>
        <!-- Support Banner -->
        <div class="xtvc_plugins_text">
        	<div class="xtvc_option_wrap">
				<h3 class="hndle">Recommended Web Hosting</h3>
				<p><a href="https://www.digitalocean.com/?refcode=8e3c5e4f7a4c" target="_blank"><img src="https://xtrsyz.org/wp-content/uploads/2015/01/digitalocean-banner.jpg" width="468" height="60"></a></p>
			</div>
		</div>
        <!-- Sidebar Space -->
        <div class="xtvc_plugins_text">
        	<div class="xtvc_option_wrap">
				<h3 class="hndle">Recommended Monetization</h3>
				<p><a href="https://xtrsyz.org/popcash" target="_blank">Review Cara Mendapatkan Penghasilan Uang Dari PopCash.</a></p>
			</div>
		</div>
    </div><!-- End Right sidebar -->
    <div class="xtvc_left_sidebar"><!-- start Left sidebar -->
    <div class="xtvc_plugins_text">
    <div class="xtvc_option_wrap">
		<h3 class="hndle">Google AdSense</h3>
		<div><a href="https://monetag.com/?ref_id=TD7g" target="_blank"><img src="https://promo.propellerads.com/728x90_06.gif" alt="PropellerAds"></a></div>
<div><a href="https://popcash.net/home/11984" target="_blank" title="PopCash - The Popunder network">
    <img src="https://static.popcash.net/img/affiliate/728x90.jpg" alt="PopCash.net">
</a></div>
<div><a href="https://www.revenuehits.com/lps/pubref/?ref=@RH@G4PHPtURucXpnIt7oUn3vGWSQsQHXjql" target="_blank"><img src="https://revenuehits.com/publishers/media/img/v4/728x90_v4.gif" border="0"></a></div>
	</div>
	</div>
	<div class="xtvc_option_wrap">
	<div class="xtvc_plugins_text">
	<h3 class="hndle">Image Counter</h3>
	<form method="post" action="options.php">

<?php settings_fields( 'xtvc_options_general' ); ?>
       <?php
            $data = xt_visitor_counter_styles(WP_CONTENT_DIR . '/plugins/xt-visitor-counter/styles/');
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
                		<input type="radio" id="img1" name="xt_visitor_counter_style" value="<?php echo $style_name . '/' . $name; ?>" <?php echo checked($style_name . '/' . $name, get_option ('xt_visitor_counter_style')) ?> />
                		<img src='<?php echo WP_PLUGIN_URL?>/xt-visitor-counter/styles/<?php echo $style_name . '/' . $name . '/'; ?>0.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/xt-visitor-counter/styles/<?php echo $style_name . '/' . $name . '/'; ?>1.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/xt-visitor-counter/styles/<?php echo $style_name . '/' . $name . '/'; ?>2.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/xt-visitor-counter/styles/<?php echo $style_name . '/' . $name . '/'; ?>3.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/xt-visitor-counter/styles/<?php echo $style_name . '/' . $name . '/'; ?>4.gif'>
                		</td>
                	</tr>
					  <?php
                }
			?>
          
		  </table>
         
<?php
            }
        ?>    
		<p><?php _e('Show powered by XT Visitor Counter? ', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" id="xt_visitor_counter_attribution" name="xt_visitor_counter_attribution" <?php echo  checked('on', get_option ('xt_visitor_counter_attribution')); ?>/></p>
        <p style="margin-top:20px;" >
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'xt-visitor-counter') ?>" />
        </p>
	</form>
	</div>
	</div>
	
	
	
	<div class="xtvc_option_wrap">
	<div class="xtvc_plugins_text">
	<h3 class="hndle">Reset Plugin Data</h3>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

<?php settings_fields( 'xtvc_options_general' ); ?>

        <p style="margin-top:20px;" >
		<?php _e('Check for reset', 'xt-visitor-counter'); ?> <input type="checkbox" class="checkbox" id="reset_xtvc" name="reset_xtvc" />
        <input type="submit" class="button-primary" value="<?php _e('Reset Data', 'xt-visitor-counter') ?>" />
        </p>
	</form>
	</div>
	</div>
	

	</div><!-- End Left sidebar -->
	</div><!-- End mvc wrap -->
</div>
<style type="text/css">
/*ADMIN STYLING*/
.form-table {
	clear: none;
}
.form-table td {
	vertical-align: top;
	padding: 16px 20px 5px;
	line-height: 10px;
	font-size: 12px;
}
.form-table th {
	width: 200px;
	padding: 10px 0 12px 9px;
}
.xtvc_right_sidebar {
	width: 42%;
	float: right;
}
.xtvc_left_sidebar {
	width: 55%;
	margin-left: 10px;
}
.xtvc_plugins_text {
	margin-bottom: 0px;
}
.xtvc_plugins_text p {
	padding: 5px 10px 10px 10px;
	width: 90%;
}
.xtvc_plugins_text h2 {
	font-size: 14px;
	padding: 0px;
	font-weight: bold;
	line-height: 29px;
}
.xtvc_plugins_wrap .hndle {
	font-size: 15px;
	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	font-weight: normal;
	padding: 7px 10px;
	margin: 0;
	line-height: 1;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-color: rgb(223, 223, 223);
    text-shadow: 0px 1px 0px rgb(255, 255, 255);
    box-shadow: 0px 1px 0px rgb(255, 255, 255);
	background: linear-gradient(to top, rgb(236, 236, 236), rgb(249, 249, 249)) repeat scroll 0% 0% rgb(241, 241, 241);
	margin-top: 1px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	-moz-user-select: none;
}
.xtvc_option_wrap {
	border:1px solid rgb(223, 223, 223);
	width:100%;
	margin-bottom:30px;
	height:auto;
}
</style>
