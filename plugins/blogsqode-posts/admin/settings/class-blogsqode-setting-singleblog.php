<?php
/**
 * Blogsqode General Settings
 *
 * @package Blogsqode\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Blogsqode_Settings_SingleBlog', false ) ) {
	return new Blogsqode_Settings_SingleBlog();
}
include_once dirname( __FILE__ ) . '/class-blogsqode-setting-page.php';

/**
 * Blogsqode_Admin_Settings_General.
 */
class Blogsqode_Settings_SingleBlog extends Blogsqode_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'singleblog';
		$this->label = esc_html__( 'Single Blog', 'blogsqode' );

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
				'title' => esc_html__( 'Single Blog Page', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set Pagination designs.', 'blogsqode' ),
				'id'    => 'singleblog_page_settings',
			),

			array(
				'title'    => esc_html__( 'Select Single Post Design', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable plugin single blog layout', 'blogsqode' ),
				'id'       => 'blogsqode_single_post_design_mode',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'unable'   => esc_html__( 'Plugin Design', 'blogsqode' ),
				'disable'  => esc_html__( 'Theme Design', 'blogsqode' ),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Theme color will be change after enable it.', 'blogsqode' ),
			),
			
			array(
				'title'    => esc_html__( 'Enable Dark Mode', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable dark mode for changing color', 'blogsqode' ),
				'id'       => 'blogsqode_single_dark_mode',
				'default'  => esc_html__('Disable', 'blogsqode'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Theme color will be change after enable it.', 'blogsqode' ),
			),

			array(
				'title'    => esc_html__( 'Sidebar Layout', 'blogsqode' ),
				'desc'     => esc_html__( 'This option lets you set layout for Single Blog Page.', 'blogsqode' ),
				'id'       => 'blogsqode_singleblog_layout',
				'default'  => esc_html__('with_sidebar', 'blogsqode'),
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'with_sidebar'    => esc_html__( 'With Sidebar', 'blogsqode' ),
					'without_sidebar'    => esc_html__( 'Without Sidebar', 'blogsqode' ),
				),
			),

			array(
				'title'    => esc_html__( 'Page Layout', 'blogsqode' ),
				'desc'     => esc_html__( 'This option lets you set layout for blogs.', 'blogsqode' ),
				'id'       => 'blogsqode_singlepage_layout',
				'default'  => '1',
				'type'     => 'select',
				'class'    => 'blogsqode-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'1'        => array(esc_html__( 'Layout 1', 'blogsqode' ), BLOGSQODE_PLUGIN_FILE.'images/blog-layout-1.png'),
				),
			),

			array(
				'type' => 'sectionend',
				'id'   => 'singleblog_page_settings',
			),

			array(
				'title' => esc_html__( 'Enable/Disable options', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Enable/Disable in Single Blog page.', 'blogsqode'),
				'id'    => 'enable_disable_options_for_singleblog',
			),

			array(
				'title'    => esc_html__( 'Author Thumbnail', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog author thumbnail in your single blog page.', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog author thumbnail in your single blog page.', 'blogsqode' ),
				'id'       => 'blogsqode_single_auhtor_thumb_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Author Name', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog author name in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_author_name_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Blog Date', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog date in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_blog_date_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),

			array(
				'title'    => esc_html__( 'Comments Count', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog comment count in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_comment_count_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),
			array(
				'title'    => esc_html__( 'Category', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog category in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_category_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),
			array(
				'title'    => esc_html__( 'Tags', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your blog tags in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_tags_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),
			array(
				'title'    => esc_html__( 'Pagination of Post', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your pagination in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_pagination_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),
			array(
				'title'    => esc_html__( 'Post Comment', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display your post comment in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_postcomment_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),
			array(
				'title'    => esc_html__( 'Social Share Buttons', 'blogsqode' ),
				'desc'     => esc_html__( 'Enable If you want to display social share button in your single blog page.', 'blogsqode' ),
				'desc_tip' => true,
				'id'       => 'blogsqode_single_sharebutton_allow',
				'default'  => esc_html__('Unable', 'blogsqode'),
				'type'     => 'switchbox',
			),


			array(
				'type' => 'sectionend',
				'id'   => 'enable_disable_options_for_singleblog',
			),

			array(
				'title' => esc_html__( 'Social Icon Images', 'blogsqode' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Change Social icon in Single Blog page.', 'blogsqode'),
				'id'    => 'icon_upload_for_singleblog',
			),
			array(
				'id' => 'facebook_social_icon',
				'type' => 'file',
				'title' => esc_html__('Facebook', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Facebook Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'twitter_social_icon',
				'type' => 'file',
				'title' => esc_html__('Twitter', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Twitter Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'instagram_social_icon',
				'type' => 'file',
				'title' => esc_html__('Instagram', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Instagram Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),	
			array(
				'id' => 'linkedin_social_icon',
				'type' => 'file',
				'title' => esc_html__('Linkedin', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Linkedin Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'pinterest_social_icon',
				'type' => 'file',
				'title' => esc_html__('Pinterest', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Pinterest Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'whatsapp_social_icon',
				'type' => 'file',
				'title' => esc_html__('Whatsapp', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Whatsapp Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'snapchat_social_icon',
				'type' => 'file',
				'title' => esc_html__('Snapchat', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Snapchat Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'id' => 'wechat_social_icon',
				'type' => 'file',
				'title' => esc_html__('Wechat', 'blogsqode'),
				'desc' => esc_html__('','blogsqode'),
				'field_desc' => esc_html__('Select <strong> Wechat Icon </strong>.', 'blogsqode'),
				'class' => 'blogsqode-loader',
				'default' => '',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'icon_upload_for_singleblog',
			),
		);

return apply_filters( 'blogsqode_general_settings', $settings );
}
}

return new Blogsqode_Settings_SingleBlog();