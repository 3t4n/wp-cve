<?php
/**
 * Display settings tab.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/metabox
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for Display settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Display {
	/**
	 * Display section.
	 *
	 * @param string $prefix Section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'title'  => __( 'Display Options', 'woo-category-slider-grid' ),
				'icon'   => 'fa fa-th-large',
				'fields' => array(
					array(
						'id'         => 'wcsp_section_title',
						'type'       => 'switcher',
						'title'      => __( 'Section Title', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide category showcase section title.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
					),
					// array(
					// 'id'         => 'wcsp_section_title_color',
					// 'type'       => 'color',
					// 'title'      => __( 'Section Title Color', 'woo-category-slider-grid' ),
					// 'subtitle'   => __( 'Set color for category showcase section title.', 'woo-category-slider-grid' ),
					// 'default'    => '#444444',
					// 'dependency' => array(
					// 'wcsp_section_title',
					// '==',
					// 'true',
					// ),
					// ),
					array(
						'id'          => 'wcsp_section_title_margin',
						'type'        => 'spacing',
						'class'       => 'wcsp-section-title-margin',
						'title'       => __( 'Margin from Section Title', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set margin for category showcase section title.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
							esc_html__( 'em', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '30',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array(
							'wcsp_section_title',
							'==',
							'true',
						),
					),
					array(
						'id'          => 'wcsp_space_between_cat',
						'type'        => 'spacing',
						'class'       => 'wcsp-space-between-cat',
						'title'       => __( 'Space Between Categories', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set space between categories.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'all'         => true,
						'all_text'    => false,
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'all'  => '20',
							'unit' => 'px',
						),
					),
					array(
						'id'          => 'wcsp_cat_content_position',
						'class'       => 'wcsp_cat_content_position',
						'type'        => 'image_select',
						'title'       => __( 'Category Content Position', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Select a position for the category content.', 'woo-category-slider-grid' ),
						'desc'        => __( 'To unlock more amazing category Content Positions and Settings, <a href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115" target="_blank"><b>Upgrade To Pro!</b></a>', 'woo-category-slider-grid' ),
						'option_name' => true,
						'options'     => array(
							'thumb-above-cont-below' => array(
								'image'       => SP_WCS_URL . 'admin/img/below-c.svg',
								'option_name' => __( 'Below Content', 'woo-category-slider-grid' ),
							),
							'cont-above-thumb-below' => array(
								'image'       => SP_WCS_URL . 'admin/img/above-c.svg',
								'option_name' => __( 'Above Content', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'left-thumb-right-cont'  => array(
								'image'       => SP_WCS_URL . 'admin/img/right-c.svg',
								'option_name' => __( 'Right Content', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'left-cont-right-thumb'  => array(
								'image'       => SP_WCS_URL . 'admin/img/left-c.svg',
								'option_name' => __( 'Left Content', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),
							'cont-over-thumb'        => array(
								'image'       => SP_WCS_URL . 'admin/img/overlay-c.svg',
								'option_name' => __( 'Overlay Content', 'woo-category-slider-grid' ),
								'pro_only'    => true,
							),

						),
						'default'     => 'thumb-above-cont-below',
					),
					array(
						'id'       => 'wcsp_make_it_card_style',
						'type'     => 'checkbox',
						'title'    => __( 'Make it Card Style', 'woo-category-slider-grid' ),
						'subtitle' => __( 'By checking it, you can bring a material feel into your design through customization.', 'woo-category-slider-grid' ),
						'default'  => false,
					),
					array(
						'id'          => 'wcsp_cat_border',
						'type'        => 'border',
						'title'       => __( 'Border', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set category content border for the slider item.', 'woo-category-slider-grid' ),
						'hover_color' => true,
						'default'     => array(
							'top'         => '1',
							'left'        => '1',
							'right'       => '1',
							'bottom'      => '1',
							'color'       => '#e2e2e2',
							'hover_color' => '#e2e2e2',
						),
						'dependency'  => array(
							'wcsp_make_it_card_style',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'wcsp_cat_background',
						'type'       => 'color_group',
						'title'      => __( 'Background', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set color for the category content background.', 'woo-category-slider-grid' ),
						'options'    => array(
							'background'       => __( 'Background', 'woo-category-slider-grid' ),
							'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
						),
						'default'    => array(
							'background'       => '#f8f8f8',
							'hover_background' => '#f8f8f8',
						),
						'dependency' => array(
							'wcsp_make_it_card_style',
							'==',
							'true',
						),
					),
					array(
						'id'          => 'wcsp_cat_padding',
						'type'        => 'spacing',
						'class'       => 'wcsp-cat-padding',
						'title'       => __( 'Inner Padding', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set category content inner padding.', 'woo-category-slider-grid' ),
						'output_mode' => 'padding',
						'unit'        => true,
						'units'       => array( 'px' ),
						'default'     => array(
							'top'    => '16',
							'right'  => '16',
							'bottom' => '16',
							'left'   => '16',
							'unit'   => 'px',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Category Content', 'woo-category-slider-grid' ),
					),
					array(
						'id'         => 'wcsp_cat_icon',
						'class'      => 'pro_only_field',
						'type'       => 'switcher',
						'title'      => __( 'Category Icon', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide category icon.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => false,
					),
					array(
						'id'         => 'wcsp_cat_name',
						'type'       => 'switcher',
						'title'      => __( 'Category Name', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide category name.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
					),
					array(
						'id'          => 'wcsp_cat_name_margin',
						'type'        => 'spacing',
						'class'       => 'wcsp-cat-name-margin',
						'title'       => __( 'Category Name Margin', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set category name margin.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
							esc_html__( 'em', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '6',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array(
							'wcsp_cat_name',
							'==',
							'true',
						),
					),
					array(
						'id'         => 'wcsp_cat_product_count',
						'type'       => 'switcher',
						'title'      => __( 'Product Count', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide product count.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
						'dependency' => array( 'wcsp_cat_name', '==', 'true' ),
					),
					array(
						'id'         => 'wcsp_cat_product_count_position',
						'type'       => 'radio',
						'title'      => __( 'Product Count Position', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set product count position.', 'woo-category-slider-grid' ),
						'options'    => array(
							'beside_cat' => __( 'Beside category name', 'woo-category-slider-grid' ),
							'under_cat'  => __( 'Under category name', 'woo-category-slider-grid' ),
						),
						'default'    => 'beside_cat',
						'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true' ),
					),
					array(
						'id'         => 'wcsp_cat_product_count_before',
						'type'       => 'text',
						'title'      => __( 'Product Count Before', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set product count before text.', 'woo-category-slider-grid' ),
						'default'    => ' (',
						'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true' ),
					),
					array(
						'id'         => 'wcsp_cat_product_count_after',
						'type'       => 'text',
						'title'      => __( 'Product Count After', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set product count after text.', 'woo-category-slider-grid' ),
						'default'    => ')',
						'dependency' => array( 'wcsp_cat_name|wcsp_cat_product_count', '==|==', 'true|true' ),
					),
					array(
						'id'          => 'wcsp_product_count_margin',
						'type'        => 'spacing',
						'title'       => __( 'Product Count Margin', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set product count margin.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
							esc_html__( 'em', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '6',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array(
							'wcsp_cat_product_count_position|wcsp_cat_product_count|wcsp_cat_name',
							'==|==|==',
							'under_cat|true|true',
						),
					),
					array(
						'id'         => 'wcsp_cat_custom_text',
						'class'      => 'pro_only_field',
						'attributes' => array( 'disabled' => 'disabled' ),
						'type'       => 'switcher',
						'title'      => __( 'Custom Text', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide custom text.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => false,

					),
					array(
						'id'         => 'wcsp_cat_description',
						'type'       => 'switcher',
						'title'      => __( 'Description', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide description.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
					),
					array(
						'id'          => 'wcsp_description_margin',
						'type'        => 'spacing',
						'class'       => 'wcsp-description-margin',
						'title'       => __( 'Description Margin', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set description margin.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
							esc_html__( 'em', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '14',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array(
							'wcsp_cat_description',
							'==',
							'true',
						),
					),
					array(
						'type'    => 'subheading',
						'content' => __( 'Shop Now Button', 'woo-category-slider-grid' ),
					),
					array(
						'id'         => 'wcsp_cat_shop_now_button',
						'type'       => 'switcher',
						'title'      => __( 'Shop Now Button', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Show/Hide shop now button.', 'woo-category-slider-grid' ),
						'text_on'    => __( 'Show', 'woo-category-slider-grid' ),
						'text_off'   => __( 'Hide', 'woo-category-slider-grid' ),
						'text_width' => 80,
						'default'    => true,
					),
					array(
						'id'         => 'wcsp_cat_shop_now_button_text',
						'type'       => 'text',
						'title'      => __( 'Shop Now Button Label', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Type shop now button label.', 'woo-category-slider-grid' ),
						'default'    => 'Shop Now',
						'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),
					array(
						'id'         => 'wcsp_cat_shop_button_color',
						'type'       => 'color_group',
						'title'      => __( 'Shop Now Button Color', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set shop now button color.', 'woo-category-slider-grid' ),
						'options'    => array(
							'background'       => __( 'Background', 'woo-category-slider-grid' ),
							'hover_background' => __( 'Hover Background', 'woo-category-slider-grid' ),
						),
						'default'    => array(
							'background'       => '#cc2b5e',
							'hover_background' => '#af2435',
						),
						'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),
					array(
						'id'          => 'wcsp_cat_shop_button_border',
						'type'        => 'border',
						'title'       => __( 'Shop Now Button Border', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set border for the shop now button.', 'woo-category-slider-grid' ),
						'all'         => true,
						'hover_color' => true,
						'default'     => array(
							'all'         => '0',
							'color'       => '#cc2b5e',
							'hover_color' => '#af2435',
						),
						'dependency'  => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),
					array(
						'id'          => 'wcsp_cat_button_margin',
						'type'        => 'spacing',
						'class'       => 'wcsp-cat-button-margin',
						'title'       => __( 'Button Margin', 'woo-category-slider-grid' ),
						'subtitle'    => __( 'Set shop now button margin.', 'woo-category-slider-grid' ),
						'output_mode' => 'margin',
						'units'       => array(
							esc_html__( 'px', 'woo-category-slider-grid' ),
							esc_html__( 'em', 'woo-category-slider-grid' ),
						),
						'default'     => array(
							'top'    => '0',
							'right'  => '0',
							'bottom' => '5',
							'left'   => '0',
							'unit'   => 'px',
						),
						'dependency'  => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),
					array(
						'id'         => 'wcsp_cat_link_target',
						'type'       => 'select',
						'title'      => __( 'Link Target', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'Set link target.', 'woo-category-slider-grid' ),
						'options'    => array(
							'_self'     => __( '_self', 'woo-category-slider-grid' ),
							'_blank'    => __( '_blank', 'woo-category-slider-grid' ),
							'_parent'   => __( '_parent', 'woo-category-slider-grid' ),
							'_top'      => __( '_top', 'woo-category-slider-grid' ),
							'framename' => __( 'framename', 'woo-category-slider-grid' ),
						),
						'default'    => '_self',
						'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),

				), // End of fields array.
			)
		); // Display Options section end.
	}
}
