<?php
/**
 * Blogsqode General Settings
 *
 * @package Blogsqode\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Blogsqode_Settings_Pagination', false ) ) {
	return new Blogsqode_Settings_Pagination();
}
include_once dirname( __FILE__ ) . '/class-blogsqode-setting-page.php';

/**
 * Blogsqode_Admin_Settings_General.
 */
class Blogsqode_Settings_Pagination extends Blogsqode_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'pagination';
		$this->label = esc_html__( 'Pagination', 'blogsqode' );

		parent::__construct();
	}

	/**
	 * Get settings or the default section.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		$settings =
		array(



			array(
				'title' => esc_html__( 'Pagination Page', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set Pagination designs.', 'blogsqode' ),
				'id'    => 'pagination_page_settings',
			),

			array(
				'title'    => esc_html__( 'Pagination Design', 'blogsqode' ),
				'desc'     => esc_html__( 'This option lets you set layout for Pagination.', 'blogsqode' ),
				'id'       => 'blogsqode_pagination_layout',
				'default'  => '1',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'1'    => array(esc_html__( 'Layout 1', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-1.png'),
					'2'    => array(esc_html__( 'Layout 2', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-2.png'),
					'3'    => array(esc_html__( 'Layout 3', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-3.png'),
					'4'    => array(esc_html__( 'Layout 4', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-4.png'),
					'5'    => array(esc_html__( 'Layout 5', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-5.png'),
					'6'    => array(esc_html__( 'Layout 6', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/pagination-6.png'),
				),
			),

			array(
				'title'    => esc_html__( 'Pagination Design Preview', 'blogsqode' ),
				'desc'     => esc_html__( 'This option is selected for layout of Pagination.', 'blogsqode' ),
				'id'       => 'blogsqode_pagination_layout_preview',
				'type'     => 'preview_design',
				'class'    => 'blogsqode-preview-design',
				'desc_tip' => true,
			),

			array(
				'type' => 'sectionend',
				'id'   => 'pagination_page_settings',
			),

			array(
				'title' => esc_html__( 'Read More Button', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Read More button layout in Blog List page.', 'blogsqode'),
				'id'    => 'enable_disable_paginate_options',
			),

			array(
				'title'    => esc_html__( 'Read More Button Design', 'blogsqode' ),
				'desc'     => esc_html__( 'This option lets you set layout for Read More Button.', 'blogsqode' ),
				'id'       => 'blogsqode_read_more_button_layout',
				'default'  => '1',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'1'    => array(esc_html__( 'Layout 1', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-1.png'),
					'2'    => array(esc_html__( 'Layout 2', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-2.png'),
					'3'    => array(esc_html__( 'Layout 3', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-3.png'),
					'4'    => array(esc_html__( 'Layout 4', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-4.png'),
					'5'    => array(esc_html__( 'Layout 5', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-5.png'),
					'6'    => array(esc_html__( 'Layout 6', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/read-more-6.png'),
				),
			),
			array(
				'title'    => esc_html__( 'Read More Button Design Preview', 'blogsqode' ),
				'desc'     => esc_html__( 'This option is selected for layout of Read More Button.', 'blogsqode' ),
				'id'       => 'blogsqode_read_more_button_layout_preview',
				'type'     => 'preview_design',
				'class'    => 'blogsqode-preview-design',
				'desc_tip' => true,
			),
			array(
				'title'    => esc_html__( 'Read More Fill', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to fill read more button in your blog list page.', 'blogsqode' ),
				'desc_tip'     => esc_html__( 'Enable If you want to fill read more button in your blog list page.', 'blogsqode' ),
				'id'       => 'blogsqode_read_more_fill_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'enable_disable_paginate_options',
			),


		);

return apply_filters( 'blogsqode_general_settings', $settings );
}
}

return new Blogsqode_Settings_Pagination();