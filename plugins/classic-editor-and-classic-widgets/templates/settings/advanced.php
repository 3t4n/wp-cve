<?php

use \GRIM_CEW\Vendor\Field;
use \GRIM_CEW\Fields\Autocomplete;

Field::load(
	'checkbox',
	array(
		'name'        => 'remember_editor',
		'label'       => esc_html__( 'Remember Last Editor', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Edit posts with latest used Editor', 'classic-editor-and-classic-widgets' ),
	)
);

Autocomplete::render(
	array(
		'name'        => 'whitelist',
		'label'       => esc_html__( 'Exclude Posts', 'classic-editor-and-classic-widgets' ),
		'description' => esc_html__( 'Pages/Posts/CPT that always use Gutenberg', 'classic-editor-and-classic-widgets' ),
	)
);

Field::load(
	'delimiter',
	array(
		'title' => esc_html__( 'Custom Post Types', 'classic-editor-and-classic-widgets' ),
	)
);

foreach ( cew_get_cpt() as $cpt ) {
	Field::load(
		'checkbox',
		array(
			'name'        => 'cpt',
			'sub_name'    => $cpt->name,
			'label'       => esc_html( $cpt->labels->name ),
			/* translators: %s: CPT Name */
			'description' => esc_html( sprintf( __( 'Enable Gutenberg Editor for "%s"', 'classic-editor-and-classic-widgets' ), $cpt->name ) ),
		)
	);
}
