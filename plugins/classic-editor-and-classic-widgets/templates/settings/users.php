<?php

use \GRIM_CEW\Vendor\Field;

global $wp_roles;

foreach ( $wp_roles->get_names() as $key => $role_name ) {
	Field::load(
		'checkbox',
		array(
			'name'        => 'user_role_enable_gutenberg',
			'sub_name'    => $key,
			'label'       => esc_html__( 'Enable Gutenberg for ', 'classic-editor-and-classic-widgets' ) . "<strong>$role_name</strong>",
			/* translators: %s: CPT Name */
			'description' => esc_html( sprintf( __( 'Default editor is Gutenberg for "%s"', 'classic-editor-and-classic-widgets' ), $key ) ),
		)
	);
}
