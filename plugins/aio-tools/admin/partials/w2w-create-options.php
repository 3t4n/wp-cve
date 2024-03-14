<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
	// Set a unique slug-like ID
	$prefix = 'w2w_options';
	// Create options
	CSF::createOptions( $prefix,
		[
			// framework title
			'framework_title' => '<img src="'. W2W_URL .'/admin/assets/images/aio-tools.svg" height="60" atl="All in One Tools Plugin WordPress"/>',
			'framework_class' => 'w2w-settings',

			// menu settings
			'menu_title'      => 'AIO Tools',
			'menu_icon'       => W2W_URL . 'admin/assets/images/favicon.png',
			'menu_position'   => 4,
			'menu_slug'       => 'w2w-settings',
			'menu_type'       => 'menu',
			'menu_capability' => 'manage_options',

			'theme'                   => 'light',
			'ajax_save'               => true,
			'show_search'             => false,
			'show_reset_section'      => true,
			'show_all_options'        => false,
			'sticky_header'           => false,

			// menu extras
			'show_bar_menu'           => false,
			'show_sub_menu'           => true,
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 80,

			// footer
			'footer_text'             => sprintf( __( 'Version %1$s. Thank you for using %2$s! If you like our plugin, be sure to %3$sleave a fair review%4$s. We made with %5$s', 'w2w' ),W2W_VERSION, W2W_PLUGIN_NAME, '<a href="https://wordpress.org/support/plugin/aio-tools/reviews/#new-post" rel="external noopener" target="_blank">', '</a>','<i class="fas fa-heart"></i>' ),
			'footer_after'            => '',					
			/* translators: 1: plugin name 2: opening tag for the hyperlink 3: closing tag for the hyperlink  */
			'footer_credit'           => '&nbsp;',
		] );