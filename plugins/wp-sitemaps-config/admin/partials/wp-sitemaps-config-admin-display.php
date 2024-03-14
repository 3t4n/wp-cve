<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.kybernetik-services.com/
 * @since      1.0.0
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/admin/partials
 */


// Get the active tab from the $_GET param
$default_tab = 'general';
$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
?>

<div class="wrap">
	<!-- Page title -->
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<!-- Tab navigation -->
    <nav class="nav-tab-wrapper">
<?php
// print tab navigation
$tabs = array();
$text = 'General';
$tabs[ 'general' ] = __( $text );
$text = 'Posts';
$tabs[ 'posts' ] = __( $text );

foreach ( $tabs as $key => $label ) {
	$attr = $key === $tab ? ' nav-tab-active' : '';
	printf( '<a href="?page=%s&amp;tab=%s" class="nav-tab%s">%s</a>', $this->plugin_slug, $key, $attr, esc_html__( $label )  );
}
?>
    </nav>
	
	<!-- Content -->
	<div class="wpsc_wrapper<?php if ( is_rtl() ) { ?> wpsc_rtl<?php } ?>">
		<div id="wpsc_main">
			<div class="wpsc_content">
				<p><?php esc_html_e( 'WordPress automatically provides multiple sitemaps for your website.', 'wp-sitemaps-config' ); ?> <a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>"><?php esc_html_e( 'View the current XML sitemap index.', 'wp-sitemaps-config' ); ?></a></p>
<?php 
do_action( 'wp_sitemaps_config_' . $tab );
?>
			</div><!-- .wpsc_content -->
		</div><!-- #wpsc_main -->
		<div id="wpsc_footer">
			<div class="wpsc_content">
				<h2><?php esc_html_e( 'Share your thoughts!', 'wp-sitemaps-config' ); ?></h2>
				<p><?php esc_html_e( 'Do you like the plugin?', 'wp-sitemaps-config' ); ?> <a href="https://wordpress.org/support/view/plugin-reviews/wp-sitemaps-config"><?php esc_html_e( 'Rate it at wordpress.org!', 'wp-sitemaps-config' ); ?></a></p>
				<p><?php esc_html_e( 'Do you need support or have an idea for the plugin?', 'wp-sitemaps-config' ); ?> <a href="https://wordpress.org/support/plugin/wp-sitemaps-config"><?php esc_html_e( 'Post your questions and ideas in the forum at wordpress.org!', 'wp-sitemaps-config' ); ?></a></p>
				<p><img src="<?php echo esc_url( WP_SITEMAPS_CONFIG_URL . 'admin/images/icon-128x128.png' ); ?>" alt="<?php esc_attr_e( 'A human eye looking at you' ); ?>" width="128" height="128"></p>
			</div><!-- .wpsc_content -->
		</div><!-- #wpsc_footer -->
	</div><!-- .wpsc_wrapper -->
</div><!-- .wrap -->
