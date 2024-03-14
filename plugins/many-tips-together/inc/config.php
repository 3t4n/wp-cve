<?php
namespace ADTW;

defined( 'ABSPATH' ) || exit;

if (!class_exists('Redux')) {
    return;
}
$adtw_option = "b5f_admin_tweaks";

$args_adtw = array(
    'opt_name'                  => $adtw_option,
	'dev_mode'                  => false,
	'display_name'              => AdminTweaks::NAME,    
    'display_version'           => '<span class="version-number">'.AdminTweaks::VERSION.'</span>',
    'menu_type'                 => 'submenu',
	'allow_sub_menu'            => false,
    'menu_title'                => AdminTweaks::NAME,
    'page_title'                => AdminTweaks::NAME,
	'disable_google_fonts_link' => false,
	'customizer'                => false,
	'open_expanded'             => false,
	'disable_save_warn'         => true,
	'page_priority'             => 90,
    'page_parent'               => 'options-general.php',
	'page_permissions'          => 'manage_options',
	'menu_icon'                 => 'dashicons dashicons-portfolio',
    'ajax_save'                 => false,
    'allow_tracking'            => false,
    'page_slug'                 => 'admintweaks',
	'save_defaults'             => true,
	'default_show'              => false,
	'default_mark'              => '*',
	'show_import_export'        => false,
	'transient_time'            => 60 * MINUTE_IN_SECONDS,
	'output'                    => true,
	'output_tag'                => true,
	'footer_credit'             => '-',
	'use_cdn'                   => true,
	'admin_theme'               => 'classic',
	// Mode to display fonts (auto|block|swap|fallback|optional)
	// See: https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display.
	'font_display'              => 'swap',
	'hints'                     => array(
		'icon'          => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color'    => 'lightgray',
		'icon_size'     => 'normal',
		'tip_style'     => array(
			'color'   => 'dark',
			'shadow'  => true,
			'rounded' => false,
			'style'   => 'jtools',
		),
		'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect'    => array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'click mouseleave',
			),
		),
	),
	// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'network_admin'     => true,
    'network_sites'     => false,
    'database'          => '', // "network" for multisite
	'search'            => true,
);

// SOCIAL ICONS
$args_adtw['share_icons'][] = array(
    'url'   => '//t.me/brasofilo',
	'title' => 'Telegram',
	'icon'  => 'el el-telegram',
);
$args_adtw['share_icons'][] = array(
    'url'   => '//www.linkedin.com/in/rodolfo-buaiz/',
	'title' => 'LinkedIn',
	'icon'  => 'el el-linkedin',
);
$args_adtw['share_icons'][] = array(
    'url'   => '//stackoverflow.com/users/1287812/brasofilo',
	'title' => 'Stack Overflow',
	'icon'  => 'el el-stackoverflow',
);
$args_adtw['share_icons'][] = array(
    'url'   => '//github.com/brasofilo',
    'title' => 'GitHub',
    'icon'  => 'el el-github',
);

\Redux::set_args( $adtw_option, $args_adtw );

$adtw_sections = [
    'adminbar', 
    'adminmenu', 
    'appearance', 
    'dashboard',
    'general',
    'listings',
    'media',
    'plugins',
    'profile',
    'login',
    'maintenance',
    'backup',
    'credits',
];

foreach ($adtw_sections as $sec) {
    require_once ADTW_PATH . "/inc/sections/$sec.php";
}