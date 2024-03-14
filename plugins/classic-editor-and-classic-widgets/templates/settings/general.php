<?php

use \GRIM_CEW\Vendor\Field;

Field::load(
	'radio',
	array(
		'name'        => 'default_editor',
		'label'       => esc_html__( 'Default Editor', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Posts/Pages default editor', 'classic-editor-and-classic-widgets' ),
		'default'     => 'classic',
		'fields'      => array(
			array(
				'value' => 'classic',
				'label' => esc_html__( 'Classic Editor', 'classic-editor-and-classic-widgets' ),
			),
			array(
				'value' => 'gutenberg',
				'label' => esc_html__( 'Block Editor', 'classic-editor-and-classic-widgets' ),
			),
		),
	)
);

Field::load(
	'radio',
	array(
		'name'        => 'widgets_editor',
		'label'       => esc_html__( 'Widgets Editor', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Default widgets editor', 'classic-editor-and-classic-widgets' ),
		'default'     => 'classic',
		'fields'      => array(
			array(
				'value' => 'classic',
				'label' => esc_html__( 'Classic Editor', 'classic-editor-and-classic-widgets' ),
			),
			array(
				'value' => 'gutenberg',
				'label' => esc_html__( 'Block Editor', 'classic-editor-and-classic-widgets' ),
			),
		),
	)
);

Field::load(
	'checkbox',
	array(
		'name'        => 'allow_users',
		'label'       => esc_html__( 'Allow Switch Editors', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Allow users to switch editors', 'classic-editor-and-classic-widgets' ),
	)
);

Field::load(
	'checkbox',
	array(
		'name'        => 'edit_links',
		'label'       => esc_html__( 'Show Edit Links', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Display Classic/Block editor links (if Allowed Switch Editors)', 'classic-editor-and-classic-widgets' ),
	)
);

Field::load(
	'checkbox',
	array(
		'name'        => 'enable_frontend',
		'label'       => esc_html__( 'Enable Gutenberg Frontend', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Enqueue frontend Gutenberg/Block styles', 'classic-editor-and-classic-widgets' ),
	)
);

Field::load(
	'checkbox',
	array(
		'name'        => 'hide_menu_item',
		'label'       => esc_html__( 'Hide Plugin Menu Item', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Hide this plugin’s menu item under Settings menu', 'classic-editor-and-classic-widgets' ),
	)
);

Field::load(
	'checkbox',
	array(
		'name'        => 'acf_support',
		'label'       => esc_html__( 'Enable ACF Support', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Enable Custom Fields Meta Box (Default: ACF)', 'classic-editor-and-classic-widgets' ),
	)
);
