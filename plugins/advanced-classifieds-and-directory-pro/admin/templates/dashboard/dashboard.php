<?php

/**
 * Plugin Dashboard.
 *
 * @link    http://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div id="acadp-dashboard" class="acadp acadp-dashboard acadp-require-js wrap about-wrap full-width-layout" data-script="dashboard">
	<h1>
        <?php 
esc_html_e( 'Advanced Classifieds and Directory Pro', 'advanced-classifieds-and-directory-pro' );
?>
    </h1>
    
    <p class="about-text">
		<?php 
esc_html_e( 'Build any kind of directory site: classifieds, cars, bikes & other vehicles dealers site, pets, real estate portal, yellow pages, etc...', 'advanced-classifieds-and-directory-pro' );
?>
    </p>
        
    <?php 
?>

	<div class="wp-badge">
        <?php 
printf( esc_html__( 'Version %s', 'advanced-classifieds-and-directory-pro' ), ACADP_VERSION_NUM );
?>
    </div>
    
    <h2 class="nav-tab-wrapper wp-clearfix">
		<?php 
foreach ( $tabs as $tab => $title ) {
    $classes = array( 'nav-tab' );
    if ( $tab == $active_tab ) {
        $classes[] = 'nav-tab-active';
    }
    $title = esc_html( $title );
    
    if ( 'issues' == $tab ) {
        $classes[] = 'acadp-text-error';
        $title .= sprintf( '&nbsp;<span class="count">(%d)</span>', count( $issues['found'] ) );
    }
    
    $url = add_query_arg( 'tab', $tab, 'admin.php?page=advanced-classifieds-and-directory-pro' );
    $url = admin_url( $url );
    printf(
        '<a href="%s" class="%s">%s</a>',
        esc_url( $url ),
        implode( ' ', $classes ),
        $title
    );
}
?>
    </h2>

    <?php 
require_once ACADP_PLUGIN_DIR . "admin/templates/dashboard/{$active_tab}.php";
?>    
</div>