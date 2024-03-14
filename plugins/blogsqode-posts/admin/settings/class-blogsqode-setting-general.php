<?php
/**
 * Blogsqode General Settings
 *
 * @package Blogsqode\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Blogsqode_Settings_General', false ) ) {
	return new Blogsqode_Settings_General();
}
include_once dirname( __FILE__ ) . '/class-blogsqode-setting-page.php';

/**
 * Blogsqode_Admin_Settings_General.
 */
class Blogsqode_Settings_General extends Blogsqode_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = esc_html__( 'General', 'blogsqode' );

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
				'title' => esc_html__( 'Blogs Page', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => wp_kses_post( '* kindly use this <span>[blogsqode_blog_list]</span> shortcode inside any page, post, section.', 'blogsqode' ),
				'id'    => 'blog_page_settings',
				'desc_tip' => false,
			),

			array(
				'title'    => esc_html__( 'Enable Dark Mode', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable dark mode for changing color', 'blogsqode' ),
				'id'       => 'blogsqode_dark_mode',
				'default'  => 'Disable',
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Theme color will be change after enable it.', 'blogsqode' ),
			),

			array(
				'title'    => esc_html__( 'Layout', 'blogsqode' ),
				'desc'     => esc_html__( 'This option lets you set layout for blogs.', 'blogsqode' ),
				'id'       => 'blogsqode_blog_layout',
				'default'  => '1',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'1'        => array(esc_html__( 'Layout 1', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-1.png'),
					'2'        => array(esc_html__( 'Layout 2', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-2.png'),
					'3'        => array(esc_html__( 'Layout 3', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-3.png'),
					'4'        => array(esc_html__( 'Layout 4', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-4.png'),
					'5'        => array(esc_html__( 'Layout 5', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-5.png'),
					'6'        => array(esc_html__( 'Layout 6', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-6.png'),
				),
			),
			array(
				'title'    => esc_html__( '', 'blogsqode' ),
				'desc'     => esc_html__( 'This Preview is for layout.', 'blogsqode' ),
				'id'       => 'blogsqode_blog_layout_preview',
				'type'     => 'preview_design',
				'class'    => 'blogsqode-preview-design',
				'desc_tip' => true,
			),
			array(
				'title'    => esc_html__( 'Post Grid', 'blogsqode' ),
				'desc'     => esc_html__( 'Select Column for Blogs.', 'blogsqode' ),
				'id'       => 'blogsqode_blog_post_grid',
				'default'  => '3',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'2'	   => esc_html__( '2 Posts per Column', 'blogsqode' ),
					'3'    => esc_html__( '3 Posts per Column', 'blogsqode' ),
					'4'    => esc_html__( '4 Posts per Column', 'blogsqode' ),
					'5'    => esc_html__( '5 Posts per Column', 'blogsqode' ),

				),
			),
			array(
				'title'             => esc_html__( 'Post Per Page', 'blogsqode' ),
				'desc'              => esc_html__( 'This sets the number of blogs oer page.', 'blogsqode' ),
				'id'                => 'blogsqode_blogs_per_page',
				'css'               => 'width:50px;',
				'default'           => '9',
				'desc_tip'          => true,
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => 3,  
				),
			),

			array(
				'title'    => esc_html__( 'Pagination Option', 'blogsqode' ),
				'desc'     => esc_html__( 'Select a way of Paginate blog Page.', 'blogsqode' ),
				'id'       => 'blogsqode_blog_pagination_option',
				'default'  => 'pagination',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'pagination'	   => esc_html__( 'Pagination With Numeric', 'blogsqode' ),
					'load_more_button'    => esc_html__( 'Lode More Button with Ajax', 'blogsqode' ),

				),
			),

			array(
				'type' => 'sectionend',
				'id'   => 'blog_page_settings',
			),

			array(
				'title' => esc_html__( 'Enable/Disable options', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Enable/Disable in Blog List page.','blogsqode'),
				'id'    => 'enable_disable_options',
			),

			array(
				'title'    => esc_html__( 'Short Description', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog short description in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_short_desc_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
				

			),

			array(
				'title'    => esc_html__( 'Author Thumbnail', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog author thumbnail in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_auhtor_thumb_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Author Name', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog author name in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_author_name_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Blog Date', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog date in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_blog_date_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Comments Count', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog comment count in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_comment_count_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Read Time', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog read time in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_read_time_allow',
				'default'  => 'Disable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Category', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog category in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_category_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Read More Button', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog read more button in your blog list page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_read_more_btn_allow',
				'default'  => 'Unable',
				'type'     => 'switchbox',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'enable_disable_options',
			),


		);

return apply_filters( 'blogsqode_general_settings', $settings );
}

}

return new Blogsqode_Settings_General();