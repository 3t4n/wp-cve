<?php
/******************************
	-Admin Panel goes here
******************************/
function cwfav_options_page() {

	global $cwfav_options;

	ob_start(); ?>
	<div class="wrap">
		<h2>Favicons</h2>
		<?php
			$input = array(
				"site_favicon" => array("text"=>__('Site Favicon Url', 'cwfav'),"class"=>"cwfav_file","placeholder" => "Example: %BLOG_URL%/wpcontent/site.png"),
				"login_screen_favicon" => array("text"=>__('Login Screen Favicon Url', 'cwfav'),"class"=>"cwfav_file","placeholder" => "Example: %BLOG_URL%/wpcontent/login.png"),
				"admin_favicon" => array("text"=>__('Admin Favicon Url', 'cwfav'),"class"=>"cwfav_file","placeholder" => "Example: %BLOG_URL%/wpcontent/admin.png"),
			);
		?>
		<form method="post" action="options.php">
			<?php settings_fields('cwfav_settings_group'); ?>
			<table>
				<?php foreach ($input as $name => $data) : ?>
				<tr>
					<td style="text-align:;"><label class="description" for="cwfav_settings[<?php echo $name; ?>]"><?php _e($data["text"], 'cwfav_domain'); ?></label></td>
					<td><input class="<?php echo $data["class"]; ?>" id="cwfav_settings[<?php echo $name; ?>]" size="45" name="cwfav_settings[<?php echo $name; ?>]" type="text" value="<?php echo $cwfav_options[$name]; ?>" placeholder="<?php echo $data["placeholder"]; ?>"/></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td></td>
						<td>
							<br />
				<iframe src="//www.facebook.com/plugins/follow.php?href=https%3A%2F%2Fwww.facebook.com%2Fnazmul.hossain.nihal&amp;width&amp;height=35&amp;colorscheme=light&amp;layout=standard&amp;show_faces=false&amp;appId=715408735224516" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:35px;" allowTransparency="true"></iframe>
				<br />
				If you find this plugin useful then please rate this plugin <a style="text-decoration:none;" href="http://wordpress.org/extend/plugins/wpfavicon" target="_blank">here</a> <br /> and don't forget to visit my website <a style="text-decoration:none;" href="https://Nihal.ONE/" target="_blank">Nihal.ONE</a>.
				<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=nazmul.hossain.nihal%40gmail.com&item_name=WordPress+Plugins&currency_code=USD&source=url" target="_blank"><img style="width:100px;height:30px;" alt="Donate" src="<?php echo plugin_dir_url( __FILE__ ); ?>images/donate.gif" /></a></p>

						</td>
					</tr>
				<tr>
					<td></td>
					<td class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Options', 'cwfav'); ?>" /></td>
				</tr>
			</table>
		</form>
	</div>
	<?php
	echo ob_get_clean();
}

function cwfav_add_options_link() {
	add_options_page('Favicons', 'Favicons', 'manage_options', 'cwfav-options', 'cwfav_options_page');
}
add_action('admin_menu', 'cwfav_add_options_link');

function cwfav_register_settings() {
	register_setting('cwfav_settings_group', 'cwfav_settings');
}
add_action('admin_init', 'cwfav_register_settings');

?>