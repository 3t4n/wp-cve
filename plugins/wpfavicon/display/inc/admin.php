<?php
/******************************
	-Favicon For Admin Dashboard
******************************/
function cwfav_admin() {
	
	global $cwfav_options;
	
	$favicon = $cwfav_options["admin_favicon"];
	
	$shortcodes = "%BLOG_URL%";
	$longcodes = get_bloginfo("wpurl");
	
	$favicon = str_replace($shortcodes,$longcodes,$favicon);
	
	ob_start(); ?>
	<!-- Favicon Start -->
		<!-- Favicon Version 2.1 : Admin : Visit SuperbCodes.com -->
		<?php if(!empty($cwfav_options["admin_favicon"])): ?>
		<link rel="icon" href="<?php echo $favicon;  ?>" type="image/x-icon" />
		<?php endif; ?>
		<link rel='stylesheet' href="<?php echo plugin_dir_url(''); ?>/wpfavicon/style.css" type="text/css" />
	<!-- Favicom End -->
	<?php
		echo ob_get_clean();
	}
	add_action('admin_head', 'cwfav_admin');

?>