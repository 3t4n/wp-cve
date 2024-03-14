<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( '\BricksExtras\BricksExtrasMain' ) || class_exists( '\Automatic_CSS\Autoloader' ) || class_exists( '\Advanced_Themer_Bricks\AT__Init' ) || class_exists( 'Bricksforge' ) || class_exists( '\CoreFramework\Config\Plugin' ) || class_exists( '\OxyProps\Inc\Oxyprops' ) || class_exists( '\Structeezy\Inc\Structeezy' ) ) {
// Settings.
$wp_admin_bar->add_node(
	array(
		'id'    => 'bn-bricks-plugin-settings',
		'title' => __( 'Plugin Settings', 'bricks-navigator' ),
		'parent' => 'bn-bricks',
		'meta'  => array(
			'class' => 'bn-has-top-border',
		),
		
	)
);
}

	// ACSS
	if ( class_exists( '\Automatic_CSS\Autoloader' ) ) {
		// ACSS.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-acss-settings',
				'title' => __( 'ACSS', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=automatic-css' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// ACSS - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-acss-settings-new-tab',
					'parent' => 'bn-bricks-acss-settings',
					'href'  => admin_url( 'admin.php?page=automatic-css' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// AT
	if ( class_exists( '\Advanced_Themer_Bricks\AT__Init' ) ) {
		// AT.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-at-settings',
				'title' => __( 'AT (Theme Settings)', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=bricks-advanced-themer' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// AT - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-at-settings-new-tab',
					'parent' => 'bn-bricks-at-settings',
					'href'  => admin_url( 'admin.php?page=bricks-advanced-themer' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// BricksExtras
	if ( class_exists( '\BricksExtras\BricksExtrasMain' ) ) {
		// BricksExtras.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-bricksextras',
				'title' => __( 'BricksExtras', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=bricksextras_menu' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// BricksExtras - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-bricksextras-new-tab',
					'parent' => 'bn-bricks-bricksextras',
					'href'  => admin_url( 'admin.php?page=bricksextras_menu' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// Bricksforge
	if ( class_exists( 'Bricksforge' ) ) {
		// Bricksforge.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-bricksforge-settings',
				'title' => __( 'Bricksforge', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=bricksforge' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// Bricksforge - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-bricksforge-new-tab',
					'parent' => 'bn-bricks-bricksforge-settings',
					'href'  => admin_url( 'admin.php?page=bricksforge' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// Bricks Navigator
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-navigator-settings',
			'title' => __( 'Bricks Navigator', 'bricks-navigator' ),
			'parent' => 'bn-bricks-plugin-settings',
			'href'  => admin_url( 'admin.php?page=brickslabs-bricks-navigator' ),
			'meta'  => array(
				'target' => '_self',
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
		// Bricks Navigator - new tab.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-navigator-settings-new-tab',
				'parent' => 'bn-bricks-navigator-settings',
				'href'  => admin_url( 'admin.php?page=brickslabs-bricks-navigator' ),
				'meta'  => array(
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);
	
	// Core Framework
	if ( class_exists( '\CoreFramework\Config\Plugin' ) ) {
		// CF.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-cf-settings',
				'title' => __( 'Core Framework', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=core-framework' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// CF - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-cf-new-tab',
					'parent' => 'bn-bricks-cf-settings',
					'href'  => admin_url( 'admin.php?page=core-framework' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// OxyProps
	if ( class_exists( '\OxyProps\Inc\Oxyprops' ) ) {
		// OP.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-op-settings',
				'title' => __( 'OxyProps', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=oxyprops' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// OP - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-op-new-tab',
					'parent' => 'bn-bricks-op-settings',
					'href'  => admin_url( 'admin.php?page=oxyprops' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
	
	// Structeezy
	if ( class_exists( '\Structeezy\Inc\Structeezy' ) ) {
		// Structeezy.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-structeezy-settings',
				'title' => __( 'Structeezy', 'bricks-navigator' ),
				'parent' => 'bn-bricks-plugin-settings',
				'href'  => admin_url( 'admin.php?page=structeezy' ),
				'meta'  => array(
					'target' => '_self',
					'class' => 'bn-parent-of-mini-child'
				),
			)
		);
			// Structeezy - new tab.
			$wp_admin_bar->add_node(
				array(
					'id'    => 'bn-bricks-structeezy-new-tab',
					'parent' => 'bn-bricks-structeezy-settings',
					'href'  => admin_url( 'admin.php?page=structeezy' ),
					'meta'  => array(
						'target' => '_blank',
						'class'  => 'bn-mini-child bn-mini-child-new-tab',
					),
				)
			);
	}
