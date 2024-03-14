<?php
/**
 * Typography settings tab.
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
 * This class is responsible for Typography settings tab.
 *
 * @since 1.0.0
 */
class SP_WCS_Typography {
	/**
	 * Typography section.
	 *
	 * @param string $prefix Typography section prefix.
	 * @return void
	 */
	public static function section( $prefix ) {

		SP_WCS::createSection(
			$prefix,
			array(
				'title'           => __( 'Typography', 'woo-category-slider-grid' ),
				'icon'            => 'fa fa-font',
				'enqueue_webfont' => true,
				'fields'          => array(
					array(
						'type'    => 'notice',
						'style'   => 'normal',
						'content' => __( 'To unlock the following typography(900+ Google Fonts) options, <a href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115" target="_blank"><b>Upgrade to Pro</b></a> only except the Slider Section Title, Category Name, Product Count, Description, and Shop Now Button color fields!', 'woo-category-slider-grid' ),
					),
					array(
						'id'         => 'wpsp_section_title_font_load',
						'type'       => 'switcherf',
						'title'      => __( 'Load Slider Section Title Font', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'On/Off google font for the slider section title.', 'woo-category-slider-grid' ),
						'default'    => false,
						'dependency' => array( 'wcsp_section_title', '==', 'true', true ),
					),
					array(
						'id'           => 'wpsp_section_title_typography',
						'type'         => 'typography',
						'title'        => __( 'Slider Section Title Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set slider section title font properties.', 'woo-category-slider-grid' ),
						'default'      => array(
							'color'          => '#444444',
							'font-family'    => 'Open Sans',
							'font-weight'    => '600',
							'font-size'      => '20',
							'line-height'    => '20',
							'letter-spacing' => '0',
							'text-align'     => 'left',
							'text-transform' => 'none',
							'type'           => 'google',
							'unit'           => 'px',
						),
						'preview'      => 'always',
						'preview_text' => 'Slider Section Title',
						'dependency'   => array( 'wcsp_section_title', '==', 'true' ),
					),
					array(
						'id'         => 'wcsp_cat_name_font_load',
						'type'       => 'switcherf',
						'title'      => __( 'Load Category Name Font', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'On/Off google font for the category name.', 'woo-category-slider-grid' ),
						'default'    => false,
						'dependency' => array( 'wcsp_cat_name', '==', 'true' ),
					),
					array(
						'id'           => 'wcsp_cat_name_typography',
						'type'         => 'typography',
						'title'        => __( 'Category Name Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set category name font properties.', 'woo-category-slider-grid' ),
						'hover-color'  => true,
						'default'      => array(
							'color'          => '#444444',
							'hover-color'    => '#444444',
							'font-family'    => 'Lato',
							'font-style'     => '700',
							'font-size'      => '16',
							'line-height'    => '22',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Kids Fashion',
						'dependency'   => array( 'wcsp_cat_name', '==', 'true' ),
					),
					array(
						'id'         => 'wcsp_product_count_font_load',
						'type'       => 'switcherf',
						'title'      => __( 'Load Product Count Font', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'On/Off google font for the product count.', 'woo-category-slider-grid' ),
						'default'    => false,
						'dependency' => array( 'wcsp_cat_product_count', '==', 'true' ),
					),
					array(
						'id'           => 'wcsp_product_count_typography',
						'type'         => 'typography',
						'title'        => __( 'Product Count Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set product count font properties.', 'woo-category-slider-grid' ),
						'default'      => array(
							'color'          => '#777777',
							'font-family'    => 'Open Sans',
							'font-style'     => '400',
							'font-size'      => '14',
							'line-height'    => '20',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => '23 Products',
						'dependency'   => array( 'wcsp_cat_product_count', '==', 'true' ),
					),
					array(
						'id'       => 'wcsp_child_cat_font_load',
						'type'     => 'switcherf',
						'title'    => __( 'Load Child Category Font', 'woo-category-slider-grid' ),
						'subtitle' => __( 'On/Off google font for the child category.', 'woo-category-slider-grid' ),
						'default'  => false,
					),
					array(
						'id'           => 'wcsp_child_cat_typography',
						'type'         => 'typography',
						'class'        => 'wcsp_child_cat_typography',
						'title'        => __( 'Child Category Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set child category font properties.', 'woo-category-slider-grid' ),
						'hover-color'  => true,
						'default'      => array(
							'color'          => '#636363',
							'hover-color'    => '#cc2b5e',
							'font-family'    => 'Open Sans',
							'font-style'     => '400',
							'font-size'      => '14',
							'line-height'    => '18',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Child Category',
					),
					array(
						'id'       => 'wcsp_custom_text_font_load',
						'type'     => 'switcherf',
						'title'    => __( 'Load Custom Text Font', 'woo-category-slider-grid' ),
						'subtitle' => __( 'On/Off google font for the custom text.', 'woo-category-slider-grid' ),
						'default'  => false,
					),
					array(
						'id'           => 'wcsp_custom_text_typography',
						'type'         => 'typography',
						'class'        => 'wcsp_custom_text_typography',
						'title'        => __( 'Custom Text Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set custom text font properties.', 'woo-category-slider-grid' ),
						'default'      => array(
							'color'          => '#535353',
							'font-family'    => 'Lato',
							'font-style'     => '400',
							'font-size'      => '14',
							'line-height'    => '18',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'uppercase',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Black Friday Offer 50% Off',
					),
					array(
						'id'         => 'wcsp_description_font_load',
						'type'       => 'switcherf',
						'title'      => __( 'Load Description Font', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'On/Off google font for the description.', 'woo-category-slider-grid' ),
						'default'    => false,
						'dependency' => array( 'wcsp_cat_description', '==', 'true' ),

					),
					array(
						'id'           => 'wcsp_description_typography',
						'type'         => 'typography',
						'title'        => __( 'Description Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set description font properties.', 'woo-category-slider-grid' ),
						'default'      => array(
							'color'          => '#444444',
							'font-family'    => 'Open Sans',
							'font-style'     => '300',
							'font-size'      => '14',
							'line-height'    => '18',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer semper congue ultricies. Suspendisse a congue magna. Fusce at lacinia risus.',
						'dependency'   => array( 'wcsp_cat_description', '==', 'true' ),

					),
					array(
						'id'         => 'wcsp_shop_now_font_load',
						'type'       => 'switcherf',
						'title'      => __( 'Load Shop Now Button Font', 'woo-category-slider-grid' ),
						'subtitle'   => __( 'On/Off google font for the shop now button.', 'woo-category-slider-grid' ),
						'default'    => false,
						'dependency' => array( 'wcsp_cat_shop_now_button', '==', 'true' ),

					),
					array(
						'id'           => 'wcsp_shop_now_typography',
						'type'         => 'typography',
						'title'        => __( 'Shop Now Button Font', 'woo-category-slider-grid' ),
						'subtitle'     => __( 'Set shop now button font properties.', 'woo-category-slider-grid' ),
						'hover-color'  => true,
						'default'      => array(
							'color'          => '#ffffff',
							'hover-color'    => '#ffffff',
							'font-family'    => 'Lato',
							'font-style'     => '700',
							'font-size'      => '15',
							'line-height'    => '20',
							'letter-spacing' => '0',
							'text-align'     => 'center',
							'text-transform' => 'none',
							'type'           => 'google',
						),
						'preview'      => 'always',
						'preview_text' => 'Shop Now',
						'dependency'   => array( 'wcsp_cat_shop_now_button', '==', 'true' ),
					),

				), // End of fields array.
			)
		); // Typography settings section end.
	}
}
