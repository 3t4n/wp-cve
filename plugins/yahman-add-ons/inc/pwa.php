<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_load_pwa($option){

	
	?>

	<link rel="manifest" href="/<?php echo $option['pwa']['manifest']; ?>">
	<meta name="theme-color" content="<?php echo $option['pwa']['theme_color']; ?>"/>
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo( 'name' ); ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="apple-touch-icon" href="/<?php echo $option['pwa']['apple_touch_icon']; ?>"/>
	<?php


}

function yahman_addons_pwa_sw($sw){

	
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if ('serviceWorker' in navigator) {
				window.addEventListener('load', function() {
					navigator.serviceWorker.register('/<?php echo $sw; ?>').then(function(registration) {
						console.log('ServiceWorker registration successful with scope: ', registration.scope);
					}, function(err) {
						console.log('ServiceWorker registration failed: ', err);
					});
				});
			}
		}, false);
	</script>
	<?php


}


