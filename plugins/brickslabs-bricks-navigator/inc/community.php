<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Community
$wp_admin_bar->add_node(
	array(
		'id'    => 'bn-bricks-community',
		'title' => __( 'Community', 'bricks-navigator' ),
		'parent' => 'bn-bricks',
		'meta'  => array(
			'class' => 'bn-has-top-border'
		),
	)
);

	// Advanced Themer
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-advanced-themer',
			'title' => __( 'Advanced Themer', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://advancedthemer.com/',
			'meta'  => array(
				// 'title' => __( 'my-webcraftdesign.at', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
	// Advanced Themer FB Group
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-advanced-themer-fb-grp',
			'title' => __( 'AT Facebook Group', 'bricks-navigator' ),
			'parent' => 'bn-bricks-advanced-themer',
			'href'  => 'https://www.facebook.com/groups/advancedthemercommunity/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);

	// ACSS
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-acss',
			'title' => __( 'Automatic.css (ACSS)', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://automaticcss.com/',
			'meta'  => array(
				// 'title' => __( 'Bricksable', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
		// ACSS Cheat Sheet
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-acss-cheat-sheet',
				'title' => __( 'ACSS Cheat Sheet', 'bricks-navigator' ),
				'parent' => 'bn-bricks-acss',
				'href'  => 'https://automaticcss.com/cheat-sheet/',
				'meta'  => array(
					'target' => '_blank',
				),
			)
		);

	// BricksExtras
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricksextras',
			'title' => __( 'BricksExtras', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://bricksextras.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Bricks directory
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricksdirectory',
			'title' => __( 'Bricks directory', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://bricksdirectory.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Bricksforge
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricksforge',
			'title' => __( 'Bricksforge', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://bricksforge.io/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);

	// BricksLabs
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-brickslabs',
			'title' => __( 'BricksLabs', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://brickslabs.com/',
			'meta'  => array(
				// 'title' => __( 'BricksLabs', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
		
		// BricksLabs Facebook Group
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-brickslabs-facebook',
				'title' => __( 'BricksLabs Facebook Group', 'bricks-navigator' ),
				'parent' => 'bn-bricks-brickslabs',
				'href'  => 'https://www.facebook.com/groups/brickslabs',
				'meta'  => array(
					// 'title' => __( 'BricksLabs Facebook Group in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
				),
			)
		);

	// Bricks Library
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricks-library',
			'title' => __( 'Bricks Library', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://brickslibrary.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Bricksmaven
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricksmaven',
			'title' => __( 'Bricksmaven', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://bricksmaven.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// BricksUltimate
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-bricksultimate',
			'title' => __( 'BricksUltimate', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://bricksultimate.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Build with Bricks
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-build-with-bricks',
			'title' => __( 'Build with Bricks', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://www.davefoy.com/p/build-with-bricks/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Core Framework
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-coreframework',
			'title' => __( 'Core Framework', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://coreframework.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);

	// Discord Chat
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-discord',
			'title' => __( 'Discord Chat', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://discord.gg/AyJGAnpSRc',
			'meta'  => array(
				// 'title' => __( 'Discord Chat', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
	
	// Frames
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-frames',
			'title' => __( 'Frames', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://getframes.io/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);

	// Inner Circle
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-inner-circle',
			'title' => __( "Kevin Geary's Bricks Builder Talk Circle", 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://circle.digitalambition.co/c/bricks-builder-talk/',
			'meta'  => array(
				// 'title' => __( 'BricksBuilder Reddit', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
	
	// Ivan Nugraha
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-ivan-nugraha',
			'title' => __( 'Ivan Nugraha', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://www.youtube.com/channel/UCTbvvhNu7RZWxmYPswra_QA/search?query=bricks',
			'meta'  => array(
				// 'title' => __( 'Ivan Nugraha', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// Max Addons Pro
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-max-addons-pro',
			'title' => __( 'Max Addons Pro', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://wpbricksaddons.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// OxyProps (BricksProps)
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-oxyprops',
			'title' => __( 'OxyProps (BricksProps)', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://oxyprops.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);
	
	// Structeezy
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-structeezy',
			'title' => __( 'Structeezy', 'bricks-navigator' ),
			'parent' => 'bn-bricks-community',
			'href'  => 'https://structeezy.com/',
			'meta'  => array(
				'target' => '_blank',
			),
		)
	);