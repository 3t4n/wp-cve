<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/* BEGIN Section: Tools */
CSF::createSection(
	$prefix,
	array(
		'title'  => __( 'Import / Export', 'w2w' ),
		'id'     => 'tools',
		'icon'   => 'fas fa-tools',
		'fields' => array(
			array(
				'type'    => 'subheading',
				/* translators: %s = WP Toolkit  */
				'content' => sprintf( __( 'Nhập / Xuất cài đặt plugin %s', 'w2w' ), W2W_PLUGIN_NAME ),
			),
			array(
				'id'    => 'backup',
				'type'  => 'backup',
				'title' => '',
			),

		),
	)
);
/* END Section: Tools */