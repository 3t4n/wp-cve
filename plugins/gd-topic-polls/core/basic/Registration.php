<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use Dev4Press\v43\WordPress\Content\PostType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Registration {
	use PostType;

	public function __construct() {
		add_action( 'gdpol_register_objects', array( $this, 'register' ) );
	}

	public function register() {
		$this->_register_poll();
	}

	private function _register_poll() {
		$reg = array(
			'labels'              => $this->generate_labels(
				gdpol_settings()->get( 'label_poll_singular', 'objects' ),
				gdpol_settings()->get( 'label_poll_plural', 'objects' ) ),
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'public'              => false,
			'rewrite'             => false,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'has_archive'         => false,
			'query_var'           => false,
			'supports'            => array(
				'title',
				'author',
				'revisions',
			),
			'show_ui'             => false,
			'can_export'          => true,
			'delete_with_user'    => false,
			'show_in_rest'        => false,
		);

		$data = apply_filters( 'gdpol_posttype_registration_poll', $reg );

		register_post_type( gdpol()->post_type_poll(), $data );
	}
}
