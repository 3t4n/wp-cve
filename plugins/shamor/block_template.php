<?php 
	$times = $shamor->get_shabbat_times();
	$shamor->shamor_site_get_headers_503($times['havdalah']);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php 
	wp_head(); 
	?>
	<script>
		shouldShammor = false;
	</script>
</head>
<body <?php body_class(); ?>>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main" data-nosnippet>
		<?php
		$template_id = get_option('shamor_display_template');
		if(empty($template_id)) {
			$shamor_text = get_option('shamor_display_text');
	        echo '<div style="text-align: center; padding: 100px;"><h1>';
			if ($shamor_text){
	        	echo do_shortcode($shamor_text);
			}
			else {
				echo ' שבת שלום! נשוב לפעילות עם צאת השבת בשעה ' . $shamor->get_havdalah_hour();
			}
	        echo '</h1><div>';
	    } else {
	        echo do_shortcode('[elementor-template id="' . esc_html($template_id) . '"]');
	    }
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
</body>
</html>