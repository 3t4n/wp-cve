<?php

add_role(
	'teacher',
	esc_html__( 'Teacher', 'sakolawp' ),
	array(
		'read'			=> true,  // true allows this capability
		'edit_posts'	=> true,
		'delete_posts'	=> false, // Use false to explicitly deny
	)
);

add_role(
	'student',
	esc_html__( 'Student', 'sakolawp' ),
	array(
		'read'			=> true,  // true allows this capability
		'edit_posts'	=> false,
		'delete_posts'	=> false, // Use false to explicitly deny
	)
);

add_role(
	'parent',
	esc_html__( 'Parent', 'sakolawp' ),
	array(
		'read'			=> true,  // true allows this capability
		'edit_posts'	=> false,
		'delete_posts'	=> false, // Use false to explicitly deny
	)
);