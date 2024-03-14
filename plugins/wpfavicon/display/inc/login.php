<?php
/******************************
	-Favicon For Login Screen
******************************/
	function cwfav_login_screen() {
	
	global $cwfav_options;

	$shortcodes = "%BLOG_URL%";
	$longcodes = get_bloginfo("wpurl");
	
	$cwfav_options["login_screen_favicon"] = str_replace($shortcodes,$longcodes,$cwfav_options["login_screen_favicon"]);
	
	ob_start(); ?>
	<!-- Favicon Start -->
		<!-- Favicon Version 2.1 : Login Screen : Visit Superbcodes.com-->
		<?php if(!empty($cwfav_options["login_screen_favicon"])): ?>
		<link rel="icon" href="<?php echo $cwfav_options["login_screen_favicon"];  ?>" type="image/x-icon" />
		<?php endif; ?>
	<!-- Favicom End -->
	<?php
		echo ob_get_clean();
	}
	add_action('login_head', 'cwfav_login_screen');
?>