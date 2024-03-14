<?php

/**
 * Adds scripts and styles to the front-end
 */
function acwp_front_assets() {
    $assets_dir_url = AWP_DIR . 'assets';
	
	// Toolbar scripts
    wp_enqueue_script(  'acwp-toolbar',   $assets_dir_url . '/js/toolbar.js',  array( 'jquery' ), '', true );
    wp_localize_script( 'acwp-toolbar', 'acwp_attr',
		array(
			'fontsizer_customtags'  => get_option('acwp_fontsize_customtags') == 'yes' ? get_option( 'acwp_fontsize_tags' ) : '',
			'fontsize_excludetags'  => get_option('acwp_fontsize_customexcludetags') == 'yes' ? get_option( 'acwp_fontsize_excludetags' ) : '',
			'fontsizer_max'         => get_option( 'acwp_incfont_size' ),
			'fontsizer_min'         => get_option( 'acwp_decfont_size' ),
			'fontsizer_nolineheight'=> get_option( 'acwp_fontsize_nolineheight' ), 
			'hide_fontsize'         => get_option( 'acwp_hide_fontsize' ),
			'no_btn_drage'          => get_option( 'acwp_no_btn_drage' ),
			'contrast_exclude'      => get_option( 'acwp_contrast_exclude' ),
            'nocookies'             => get_option( 'awp_nocookies' ),
			'blogurl'               => get_bloginfo('url'),
		)
	);
	
	// Main CSS file for the front-end
    wp_enqueue_style( 'acwp-main-css',      $assets_dir_url . '/css/main.css' );
    
    // Material Icons
    if( get_option('acwp_hide_icons') != 'yes' )
        wp_enqueue_style( 'acwp-mdi',          'https://fonts.googleapis.com/icon?family=Material+Icons' );
}
add_action( 'wp_enqueue_scripts', 'acwp_front_assets' );

/**
 * Adds scripts and styles for the admin panel
 */
function acwp_admin_assets() {
    
    // Admin panel scripts
    wp_enqueue_script( 'acwp-admin',    AWP_DIR . 'assets/js/admin.js', array( 'jquery' ), '', true );
    
    // Admin panel styles
    wp_enqueue_style( 'acwp-admin-css', AWP_DIR . 'assets/css/admin.css' );
    
    // Adds media scripts
    // wp_enqueue_media();
    
    // Adds color picler scripts & styles
    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');
}
add_action('admin_enqueue_scripts', 'acwp_admin_assets');

function acwp_readable_customfont_tahoma( $classes ) {
    return array_merge( $classes, array( 'acwp-readable-tahoma' ) );
}
function acwp_readable_customfont_arial( $classes ) {
    return array_merge( $classes, array( 'acwp-readable-arial' ) );
}
function acwp_readable_customfont_custom( $classes ) {
    return array_merge( $classes, array( 'acwp-readable-custom' ) );
}

function acwp_readable_custom_font() {
    $font = get_option('acwp_readable_custom');
    if( $font != '' ) :
        ?>
        <style>
            body.acwp-readable:not(.acwp-readable-hardcss), body.acwp-readable:not(.acwp-readable-hardcss) * {
                font-family: <?php echo $font;?>;
            }
            body.acwp-readable.acwp-readable-hardcss, body.acwp-readable.acwp-readable-hardcss * {
                font-family: <?php echo $font;?> !important;
            }
        </style>
    <?php
    endif;
}

if( get_option('acwp_readable_font') == 'tahome' )
    add_filter( 'body_class', 'acwp_readable_customfont_tahoma');
elseif( get_option('acwp_readable_font') != 'custom' && get_option('acwp_readable_font') != 'tahoma' )
    add_filter( 'body_class', 'acwp_readable_customfont_arial');
elseif( get_option('acwp_readable_font') == 'custom' ) {
    add_filter( 'body_class', 'acwp_readable_customfont_custom');
    add_action('wp_head', 'acwp_readable_custom_font');
}



