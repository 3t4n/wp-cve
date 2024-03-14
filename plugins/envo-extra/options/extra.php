<?php

/**
 * Reset some theme mod from url in admin area - administrators only
 */
add_action( 'admin_init', 'envo_extra_reset_mod' );

function envo_extra_reset_mod() {
  $current_user = wp_get_current_user();
  // This will help you reset some theme mods from URL. Perfect if you put something wrong into custom code and it will break your page
  // Code example:
  // Reset code added to the HEAD: ?reset-theme-mods=1&option=header-code
  // Reset code added to the footer: ?reset-theme-mods=1&option=footer-code
  // You can use it if you are site administrator and your are in admin area.
  if ( isset( $_GET['reset-theme-mods'] ) && '1' === $_GET['reset-theme-mods'] && $_GET['option'] != '' && is_admin() !== false && current_user_can('administrator') ) {
      remove_theme_mod( $_GET['option'] );
  }
}

function envo_extra_fonts() {
    if (envo_extra_check_for_enwoo_pro()) {
        $fonts = array();
    } else {
        $fonts = array(
                'google' => array(
                    'Roboto',
                    'Open Sans',
                    'Lato',
                    'Roboto Condensed',
                    'Slabo 27px',
                    'Montserrat',
                    'Oswald',
                    'Source Sans Pro',
                    'Raleway',
                    'Merriweather',
                ),
            );
    }
    return $fonts;
}
add_action( 'admin_init', 'envo_extra_fonts' );

function envo_extra_envo_pro_is_activated() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	return is_plugin_active( 'envo-pro/envo-pro.php' );
}

$theme = wp_get_theme();
if ( 'Entr' == $theme->name || 'entr' == $theme->template ) {
		
	if (!envo_extra_envo_pro_is_activated()) {	
		Kirki::add_section( 'envo-pro', array(
			'title'       => esc_html__( 'More Options and Features', 'kirki' ),
			'type'        => 'link',
			'button_text' => esc_html__( 'Envo PRO', 'envo-extra' ),
			'button_url'  => 'https://envothemes.com/product/envo-pro/',
			'priority'	 => 1,
		) );
	}
	
	Kirki::add_section( 'envo_documentation', array(
		'title'		 => esc_attr__( 'Documentation and Demo', 'envo-extra' ),
		'priority'	 => 7,
	) );
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'envo_documentation_demo',
		'section'	 => 'envo_documentation',
		'priority'	 => 9,
		'default'	 => 'You can use this theme to create a website like this <a href="https://envothemes.com/'. strtolower($theme) .'-free-wp-theme/" target="_blank">demo</a><br/>For step-by-step tutorials, see <a href="https://envothemes.com/docs/docs/entr/" target="_blank">Documentation</a>',
	) );
	
	Kirki::add_section( 'envo_demo_import', array(
		'title'		 => esc_attr__( 'One Click Demo Import', 'envo-extra' ),
		'priority'	 => 7,
	) );
	
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'envo_demo_import_demo',
		'section'	 => 'envo_demo_import',
		'priority'	 => 9,
		'default'	 => sprintf( __( 'You can import the demo content with just one click. For step-by-step tutorial, see %1$s', 'envo-extra' ), '<a class="documentation" href="' . esc_url( 'https://envothemes.com/docs/docs/entr/one-click-demo-import/' ) . '" target="_blank">' . esc_html__( 'documentation', 'envo-extra' ) . '</a>' ),
	) );
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'envo_demo_import_button',
		'section'	 => 'envo_demo_import',
		'priority'	 => 9,
		'default'	 => '<p><a class="button button-primary" href="' . esc_url( admin_url( 'themes.php?page=envothemes-panel-install-demos' ) ) . '" title="">' . esc_html__( 'Import demo data', 'envo-extra' ) . '</a></p>',
	) );
}