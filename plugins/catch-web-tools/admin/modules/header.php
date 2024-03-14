<?php
/**
 * @package Admin
 * @sub-package Header
 */
 ?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Catch Web Tools', 'catch-ids' ); ?></h1>
    <div id="plugin-description">
	    <p>
	        <?php _e( 'Catch Web Tools is a simple and lightweight WordPress plugin to help you manage your WordPress site. Power up your WordPress site with powerful features that were till now only available to Catch Themes users. We currently offer Webmaster Tools, Open Graph, Custom CSS, Social Icons, Catch IDs and basic SEO Optimization.', 'catch-web-tools' ); ?>
	    </p>
	</div>
    
    <div class="catchp-content-wrapper">
        <div class="catchp_widget_settings">

            <?php include ( 'navigation.php' ); ?>
            <div id="dashboard" class="wpcatchtab  nosave active">
