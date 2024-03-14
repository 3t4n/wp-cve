<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Lion_Badge_Meta_Boxes {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'badge_meta_boxes' ) );
	}

	/*
	 * Register meta boxes
	 *
	 */
	public function badge_meta_boxes() {
		add_meta_box( 'badge-preview', __( 'Preview', 'lionplugins' ), array( $this, 'preview_metabox' ), 'lion_badge' );
		add_meta_box( 'badge-options', __( 'Options', 'lionplugins' ), array( $this, 'options_metabox' ), 'lion_badge' );
		add_meta_box( 'badge-target', __( 'Target', 'lionplugins' ), array( $this, 'target_metabox' ), 'lion_badge' );
	}

	/**
	 * Preview meta box.
	 *
	 * @param object $badge
	 */
	public function preview_metabox( $badge ) {
		global $pagenow;

		$badge_shape = Lion_Badge_Style::get_badge_shape( $badge->ID );
		$shape_css = Lion_Badge_Style::get_badge_shape_css( $badge->ID );
		$text_css = Lion_Badge_Style::get_badge_text_css( $badge->ID );
		$badge_text = Lion_Badge_Style::get_badge_text( $badge->ID );

		$shape_inline_css = esc_attr( implode( ' ', $shape_css ) );
		$text_inline_css = esc_attr( implode( ' ', $text_css ) );

		include LION_BADGES_PATH . '/admin/views/badge-preview.php';
	}

	/**
	 * Options meta box.
	 *
	 * @param object $badge
	 */
	public function options_metabox( $badge ) {
		$tabs = new Lion_Badge_Option_Tabs( 'options' );
		$tabs->add_tab( 'shape', __( 'Shape', 'lionplugins' ) );
		$tabs->add_tab( 'shape-style', __( 'Shape style', 'lionplugins' ) );
		$tabs->add_tab( 'text', __( 'Text', 'lionplugins' ) );
		$tabs->add_tab( 'position', __( 'Position', 'lionplugins' ) );
		$tabs->generate();
	}

	/**
	 * Target meta box.
	 *
	 * @param object $badge
	 */
	public function target_metabox( $badge ) {
		$tabs = new Lion_Badge_Option_Tabs( 'target' );
		$tabs->add_tab( 'products', __( 'Products', 'lionplugins' ) );
		$tabs->add_tab( 'categories', __( 'Categories', 'lionplugins' ) );
		$tabs->generate();
	}
}

new Lion_Badge_Meta_Boxes();
