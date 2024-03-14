<?php
/**
 * Style Manager List Class
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$upgrade_link = add_query_arg( array('page' => 'logo-showcase-with-slick-slider-pricing'), admin_url('admin.php') );
?>

<div class="wrap">

	<h1 class="wp-heading-inline"><?php _e( 'Logo Showcase Style Manager', 'logo-showcase-with-slick-slider' ); ?></h1>	
	<hr class="wp-header-end">

	<div class="lswssp-pro-btn-wrp">
		<a class="lswssp-pro-btn" href="<?php echo esc_url($upgrade_link); ?>"><?php esc_html_e('Upgrade To Premium', 'logo-showcase-with-slick-slider'); ?></a>
		<div class="lswssp-pro-img">
			<img src="<?php echo LSWSS_URL; ?>/assets/images/style-mngr-settings.png" alt="Style Manager" />
		</div>
	</div>
</div><!-- end .wrap -->