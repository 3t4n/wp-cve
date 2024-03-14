<?php
/******************************
	-Favicon For site
******************************/
function cwfav_site() {
	
	global $cwfav_options;

	$shortcodes = "%BLOG_URL%";
	$longcodes = get_bloginfo("wpurl");
	
	$cwfav_options["site_favicon"] = str_replace($shortcodes,$longcodes,$cwfav_options["site_favicon"]);
	
	
	ob_start(); ?>
	<!-- Favicon Start -->
		<!-- Favicon Version 2.1 : Site : Visit Superbcodes.com-->
		<?php if(!empty($cwfav_options["site_favicon"])): ?>
		<link rel="icon" href="<?php echo $cwfav_options["site_favicon"];  ?>" type="image/x-icon" />
		<?php endif; ?>
	<!-- Favicom End -->
	<?php
		echo ob_get_clean();
	}
	if(!empty($cwfav_options["site_favicon"])){
		add_action('wp_head', 'cwfav_site');
}
?>