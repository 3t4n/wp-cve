<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('ABOUT', 'foxtool'); ?></h2>
  <h3><i class="fa-regular fa-star"></i> <?php _e('Information about your website', 'foxtool') ?></h3>
	<div class="ft-card-note"> 
	<p><?php $theme = wp_get_theme(); echo 'Theme: ' . esc_html($theme->Name) .' <b>'. esc_html($theme->Version) .'</b>'; ?></p>
	<p onclick="ftnone(event, 'ft-hiden')"><?php $foxtool = FOXTOOL_VERSION; echo __('Foxtool:', 'foxtool'). ' <b>'. esc_html($foxtool) .'</b>'; ?></p>
	<p style="display:none" id="ft-hiden">
		<label class="nut-switch">
		<input type="checkbox" name="foxtool_settings[foxsethi]" value="1" <?php if ( isset($foxtool_options['foxsethi']) && 1 == $foxtool_options['foxsethi'] ) echo 'checked="checked"'; ?> />
		<span style="vertical-align: initial;" class="slider"></span></label>
		<label class="ft-label-right"><?php _e('Hide settings page', 'foxtool'); ?></label>
	</p>
	<p>WordPress: <b><?php echo esc_html(get_bloginfo('version')); ?></b></p>
	<p>Lang: <b><?php echo esc_html($lang=get_bloginfo("language")); ?></b></p>
	<p>PHP: <b><?php echo esc_html(phpversion()); ?></b></p>
	<p><?php foxtool_display_db_info(); ?></p>
	<p><?php echo esc_html($_SERVER['SERVER_SOFTWARE']); ?></p>
	<p>
	<?php
	$active_plugins = get_option('active_plugins');
	echo 'Plugin: ';
	foreach ($active_plugins as $plugin_path) {
		$plugin_info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_path);
		echo '<b>'. esc_html($plugin_info['Name']) . '</b>, ';
	}
	?>
	</p>
	<p>
	<?php
	$admin_users = get_users(array(
		'role' => 'administrator',
	));
	if (!empty($admin_users)) {
		echo 'User Admin: ';
		foreach ($admin_users as $admin_user) {
			echo '<b>' . esc_html($admin_user->user_nicename) . '</b>, ';
		}
	} 
	?>
	</p>
	<p>
	<?php
	$count_posts = wp_count_posts();
	$published_posts = $count_posts->publish;
	echo 'Post: ' . esc_html($published_posts);
	echo '<br>';
	$user_count = count_users();
	echo 'User: ' . esc_html($user_count['total_users']);
	?>
	</p>
	<p>
	<?php
	// check gd
	if (extension_loaded('gd') || extension_loaded('gd2')) {
		echo __('The GD library has been installed', 'foxtool');
	} else {
		echo __('The GD library has not been installed', 'foxtool');
	}
	?>
	</p>
	</div>
  <h3><i class="fa-regular fa-database"></i> <?php _e('What does your database contain?', 'foxtool') ?></h3>
	<div class="ft-card-note">
		<div class="ft-showcsdl ft-showcsdl-tit">
			<div><?php _e('Table name', 'foxtool') ?></div>
			<div><?php _e('Field', 'foxtool') ?></div>
			<div><?php _e('MB', 'foxtool') ?></div>
		</div>
		<div class="ft-scdl-scrool">
			<?php foxtool_display_wp_tables(); ?>
		</div>
		<div class="ft-csdl-tatol"><?php echo __('Capacity') .': '. esc_html(foxtool_get_database_size()); ?></div>
	</div>
  <?php if(!isset($foxtool_options['foxtool7'])){ ?>
  <h3><i class="fa-regular fa-star"></i> <?php _e('About the Foxtool plugin', 'foxtool') ?></h3>
	<div class="ft-card-note"> 
	<p><?php _e('Developed by:', 'foxtool') ?> <b><a target="_blank" href="https://foxplugin.com">Fox Plugin</a></b></p>
	<p><?php _e('Author:', 'foxtool') ?> <b><a target="_blank" href="https://www.facebook.com/adfoxtheme">IHOAN (NGUYEN NGOC HOAN)</a></b></p>
	<p><?php _e('Contributions:', 'foxtool') ?> <b>Vu Ngoc Tuan <i style="color:#999">(Code Aff)</i>, AR T RU <i style="color:#999">(Library smooth-scroll)</i>, Linh Tran <i style="color:#999">(Compress Webp)</i>, Di Hu Hoa Tung <i style="color:#999">(Code functions)</i></b></p>
	<p><?php _e('References:', 'foxtool') ?> <b>Le Van Toan <i style="color:#999">(Code functions Woocommerce)</i>, Tien Luc <i style="color:#999">(Code functions)</i></b></p>
	<p>
	<a class="ft-donate-a" onclick="ftnone(event, 'ft-donate')"><?php _e('Donate to me', 'foxtool'); ?></a>
	<span id="ft-donate" style="display:none">
		<a target="_blank" href="https://nhantien.momo.vn/0369355369">Momo: 0369355369</a><br>
		<a target="_blank" href="https://paypal.me/ihoan">Paypal</a><br>
		<a target="_blank" href="https://img.vietqr.io/image/970403-040086597979-print.png?amount=&addInfo=Donate&accountName=Nguyen%20Ngoc%20Hoan">QR Sacombank</a><br>
		NGUYEN NGOC HOAN<br>
		040086597979<br>
	</span>
	</p>
	</div>
  <?php } ?>