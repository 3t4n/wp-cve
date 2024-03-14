<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// template begin
if ( ( ($vswc_preview == 'yes') && current_user_can( 'manage_options' ) ) || ($vswc_sunday == 'yes' && $vswc_day == '0') || ($vswc_monday == 'yes' && $vswc_day == '1') || ($vswc_tuesday == 'yes' && $vswc_day == '2') || ($vswc_wednesday == 'yes' && $vswc_day == '3') || ($vswc_thursday == 'yes' && $vswc_day == '4') || ($vswc_friday == 'yes' && $vswc_day == '5') || ($vswc_saturday == 'yes' && $vswc_day == '6') ) {
	if ( ($vswc_preview != 'yes') && ($vswc_exclude_admin == 'yes') && current_user_can( 'manage_options' ) ) {
		return;
	} else {
		// 503 header begin
		$protocol = 'HTTP/1.0';
		if ( $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1' ) {
			$protocol = 'HTTP/1.1';
		}
		header( $protocol . ' 503 Service Unavailable', true, 503 );
		// 503 header end
		?>
		<!doctype html>
		<html>
		<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo esc_attr($vswc_page_title); ?></title>
		<?php
		// admin bar scripts begin
		if ( current_user_can( 'manage_options' ) ) {
			// default scripts
			wp_enqueue_style('dashicons', '/wp-includes/css/dashicons.min.css',__FILE__);
			wp_enqueue_style('admin-bar', '/wp-includes/css/admin-bar.min.css',__FILE__);
			wp_enqueue_script('admin-bar', '/wp-includes/js/admin-bar.min.js',__FILE__);
			wp_print_styles('dashicons');
			wp_print_styles('admin-bar');
			wp_print_scripts('admin-bar');
			// custom script
			wp_enqueue_style('vswc-admin-bar-style', plugins_url('/css/vswc-admin-bar-style.min.css',__FILE__));
			wp_print_styles('vswc-admin-bar-style');
		}
		// admin bar scripts end
		// styling begin
		wp_enqueue_style('vswc-style', plugins_url('/css/vswc-style.min.css',__FILE__));
		wp_print_styles('vswc-style');
		?>
		<style type="text/css">body {background-color:<?php echo esc_attr($vswc_background_color); ?>;<?php echo $vswc_background_img; ?>;color:<?php echo esc_attr($vswc_color); ?>;font-size:<?php echo esc_attr($vswc_font_size); ?>px;text-align:<?php echo esc_attr($vswc_align); ?>;}h1.vswc-title {color:<?php echo esc_attr($vswc_color_title); ?>;font-size:<?php echo esc_attr($vswc_font_size_title); ?>px;}</style>
		<?php if ( !empty($vswc_custom_css) ) { ?>
		<style type="text/css"><?php echo wp_strip_all_tags($vswc_custom_css, true); ?></style>
		<?php }
		// styling end
		?>
		</head>
		<body>
		<?php
		// admin bar begin
		if ( current_user_can( 'manage_options' ) ) {
			wp_admin_bar_render();
		}
		// admin bar end
		// page content begin
		?>
		<div id="vswc" class="vswc">
			<?php if (!empty($vswc_logo_image)) {
				echo '<img class="vswc-logo" src="'.esc_url($vswc_logo_image[0]).'" width="'.esc_attr($vswc_logo_image_width).'" alt="'.esc_attr($vswc_logo_image_alt).'"/>';
			}
			echo '<h1 class="vswc-title">'.esc_attr($vswc_content_title).'</h1>';
			echo '<div class="vswc-content">'.wpautop(wp_kses_post($vswc_content)).'</div>';
			if ( ($vswc_preview == 'yes') && current_user_can( 'manage_options' ) ) {
				echo '<div class="vswc-preview-notice">'.esc_attr($vswc_preview_notice).'</div>';
			} ?>
		</div>
		<?php
		// page content end
		?>
		</body>
		</html>
		<?php exit;
	}
}
// template end
