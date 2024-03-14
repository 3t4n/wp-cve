<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Settings.
$wp_admin_bar->add_node(
	array(
		'id'    => 'bn-bricks-settings',
		'title' => __( 'Settings', 'bricks-navigator' ),
		'parent' => 'bn-bricks',
		'href'  => admin_url( 'admin.php?page=bricks-settings' ),
		// 'meta'  => array(
		// 	'title' => __( 'Bricks Settings', 'bricks-navigator' ),
		// ),
	)
);

	// General
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-general',
			'title' => __( 'General', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → General', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// General - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-general-new-tab',
			'parent' => 'bn-bricks-settings-general',
			'href'  => admin_url( 'admin.php?page=bricks-settings' ),
			'meta'  => array(
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
				'title' => __( 'Bricks Settings → General in a new tab', 'bricks-navigator' ),
			),
		)
	);

	// Builder Access
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-builder-access',
			'title' => __( 'Builder Access', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-builder-access' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Builder Access', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// Builder Access - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-builder-access-new-tab',
			'parent' => 'bn-bricks-settings-builder-access',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-builder-access' ),
			'meta'  => array(
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
				'title' => __( 'Bricks Settings → Builder Access in a new tab', 'bricks-navigator' ),
			),
		)
	);

	// Templates
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-templates',
			'title' => __( 'Templates', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-templates' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Templates', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// Templates - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-templates-new-tab',
			'parent' => 'bn-bricks-settings-templates',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-templates' ),
			'meta'  => array(
				'title' => __( 'Bricks Settings → Templates in a new tab', 'bricks-navigator' ),
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);

	// Builder
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-builder',
			'title' => __( 'Builder', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-builder' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Builder', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// Builder - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-builder-new-tab',
			'parent' => 'bn-bricks-settings-builder',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-builder' ),
			'meta'  => array(
				'title' => __( 'Bricks Settings → Builder in a new tab', 'bricks-navigator' ),
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);

	// Performance
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-performance',
			'title' => __( 'Performance', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-performance' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Performance', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// Performance - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-performance-new-tab',
			'parent' => 'bn-bricks-settings-performance',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-performance' ),
			'meta'  => array(
				'title' => __( 'Bricks Settings → Performance in new tab', 'bricks-navigator' ),
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);

	// API Keys
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-api-keys',
			'title' => __( 'API Keys', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-api-keys' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → API Keys', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// API Keys - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-api-keys-new-tab',
			'parent' => 'bn-bricks-settings-api-keys',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-api-keys' ),
			'meta'  => array(
				'title' => __( 'Bricks Settings → API Keys in a new tab', 'bricks-navigator' ),
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);

	// Custom Code
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-custom-code',
			'title' => __( 'Custom Code', 'bricks-navigator' ),
			'parent' => 'bn-bricks-settings',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-custom-code' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Custom Code', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
	// Custom Code - new tab
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-custom-code-new-tab',
			'parent' => 'bn-bricks-settings-custom-code',
			'href'  => admin_url( 'admin.php?page=bricks-settings#tab-custom-code' ),
			'meta'  => array(
				'title' => __( 'Bricks Settings → Custom Code in a new tab', 'bricks-navigator' ),
				'target' => '_blank',
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);

	// WooCommerce
    if ( class_exists( 'WooCommerce' ) ) {
        $wp_admin_bar->add_node(
            array(
                'id'    => 'bn-bricks-settings-woocommerce',
                'title' => __( 'WooCommerce', 'bricks-navigator' ),
                'parent' => 'bn-bricks-settings',
                'href'  => admin_url( 'admin.php?page=bricks-settings#tab-woocommerce' ),
                'meta'  => array(
                    // 'title' => __( 'Bricks Settings → WooCommerce', 'bricks-navigator' ),
                    'class' => 'bn-parent-of-mini-child'
                ),
            )
        );
        // WooCommerce - new tab
        $wp_admin_bar->add_node(
            array(
                'id'    => 'bn-bricks-settings-woocommerce-new-tab',
                'parent' => 'bn-bricks-settings-woocommerce',
                'href'  => admin_url( 'admin.php?page=bricks-settings#tab-woocommerce' ),
                'meta'  => array(
                    'title' => __( 'Bricks Settings → WooCommerce in a new tab', 'bricks-navigator' ),
                    'target' => '_blank',
                    'class'  => 'bn-mini-child bn-mini-child-new-tab',
                ),
            )
        );
    }

	
// Templates.
$wp_admin_bar->add_node(
	array(
		'id'    => 'bn-bricks-templates',
		'title' => __( 'Templates', 'bricks-navigator' ),
		'parent' => 'bn-bricks',
		'href'  => admin_url( 'edit.php?post_type=bricks_template' ),
		'meta'  => array(
			// 'title' => __( 'Edit Bricks Templates', 'bricks-navigator' ),
			'class' => 'bn-has-top-border'
		),
	)
);
	// Templates - child menu
	// Ref.: L1383 in /wp-content/themes/bricks/includes/admin.php of Bricks 1.4.
	require_once 'templates-edit.php';

// Pages.
$wp_admin_bar->add_node(
	array(
		'id'    => 'bn-bricks-pages',
		'title' => __( 'Pages', 'bricks-navigator' ),
		'parent' => 'bn-bricks',
		'href'  => admin_url( 'edit.php?post_type=page' ),
		'meta'  => array(
			// 'title' => __( 'Edit Pages', 'bricks-navigator' ),
			'class' => 'bn-has-top-border'
		),
	)
);
	// Pages - child menu
	require_once 'pages-edit.php';

if ( ! get_option( 'brickslabs_bricks_navigator_hide_bricks_internal' ) ) {
	// Getting Started.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-dashboard',
			'title' => __( 'Getting Started', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => admin_url( 'themes.php?page=bricks' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Dashboard', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child bn-has-top-border'
			),
		)
	);
		// Getting Started - new tab.
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-dashboard-new-tab',
				'parent' => 'bn-bricks-dashboard',
				'href'  => admin_url( 'themes.php?page=bricks' ),
				'meta'  => array(
					'title' => __( 'Getting Started in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);

	// Custom Fonts
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-custom-fonts',
			'title' => __( 'Custom Fonts', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => admin_url( 'edit.php?post_type=bricks_fonts' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Custom Fonts', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
		// Custom Fonts - new tab
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-settings-custom-fonts-new-tab',
				'parent' => 'bn-bricks-settings-custom-fonts',
				'href'  => admin_url( 'edit.php?post_type=bricks_fonts' ),
				'meta'  => array(
					'title' => __( 'Bricks Settings → Custom Fonts in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);

	// Sidebars
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-sidebars',
			'title' => __( 'Sidebars', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => admin_url( 'admin.php?page=bricks-sidebars' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → Sidebars', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
		// Sidebars - new tab
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-settings-sidebars-new-tab',
				'parent' => 'bn-bricks-settings-sidebars',
				'href'  => admin_url( 'admin.php?page=bricks-sidebars' ),
				'meta'  => array(
					'title' => __( 'Bricks Settings → Sidebars in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);

	// System Information
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-system-info',
			'title' => __( 'System Information', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => admin_url( 'admin.php?page=bricks-system-information' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → System Information', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
		// System Information - new-tab
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-settings-system-info-new-tab',
				'parent' => 'bn-bricks-settings-system-info',
				'href'  => admin_url( 'admin.php?page=bricks-system-information' ),
				'meta'  => array(
					'title' => __( 'Bricks Settings → System Information in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);

	// License
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-settings-license',
			'title' => __( 'License', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => admin_url( 'admin.php?page=bricks-license' ),
			'meta'  => array(
				// 'title' => __( 'Bricks Settings → License', 'bricks-navigator' ),
				'class' => 'bn-parent-of-mini-child'
			),
		)
	);
		// License - new tab
		$wp_admin_bar->add_node(
			array(
				'id'    => 'bn-bricks-settings-license-new-tab',
				'parent' => 'bn-bricks-settings-license',
				'href'  => admin_url( 'admin.php?page=bricks-license' ),
				'meta'  => array(
					'title' => __( 'Bricks Settings → License in a new tab', 'bricks-navigator' ),
					'target' => '_blank',
					'class'  => 'bn-mini-child bn-mini-child-new-tab',
				),
			)
		);
}

if ( ! get_option( 'brickslabs_bricks_navigator_hide_bricks_external' ) ) {
	// Idea Board.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-idea-board',
			'title' => __( 'Idea Board', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://bricksbuilder.io/ideas/',
			'meta'  => array(
				// 'title' => __( 'Idea Board', 'bricks-navigator' ),
				'target' => '_blank',
				'class' => 'bn-has-top-border'
			),
		)
	);

	// Roadmap.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-roadmap',
			'title' => __( 'Roadmap', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://bricksbuilder.io/roadmap/',
			'meta'  => array(
				// 'title' => __( 'Roadmap', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// Changelog.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-changelog',
			'title' => __( 'Changelog', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://bricksbuilder.io/changelog/',
			'meta'  => array(
				// 'title' => __( 'Changelog', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// Academy.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-academy',
			'title' => __( 'Academy', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://academy.bricksbuilder.io/',
			'meta'  => array(
				// 'title' => __( 'Bricks Academy (Documentation)', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// Forum.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-forum',
			'title' => __( 'Forum', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://forum.bricksbuilder.io/',
			'meta'  => array(
				// 'title' => __( 'Bricks Forum', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// Facebook Group.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-facebook-group',
			'title' => __( 'Facebook Group', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://www.facebook.com/groups/brickscommunity',
			'meta'  => array(
				// 'title' => __( 'Bricks Facebook Group', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);

	// YouTube Channel.
	$wp_admin_bar->add_node(
		array(
			'id'    => 'bn-bricks-youtube',
			'title' => __( 'YouTube Channel', 'bricks-navigator' ),
			'parent' => 'bn-bricks',
			'href'  => 'https://www.youtube.com/c/bricksbuilder/videos',
			'meta'  => array(
				// 'title' => __( 'Bricks YouTube', 'bricks-navigator' ),
				'target' => '_blank',
			),
		)
	);
}