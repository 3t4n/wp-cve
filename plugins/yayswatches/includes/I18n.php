<?php

namespace Yay_Swatches;

defined( 'ABSPATH' ) || exit;
/**
 * I18n Logic
 */
class I18n {

	public static function loadPluginTextdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		unload_textdomain( 'yay-swatches' );
		load_textdomain( 'yay-swatches', YAY_SWATCHES_PLUGIN_DIR . '/languages/' . $locale . '.mo' );
		load_plugin_textdomain( 'yay-swatches', false, YAY_SWATCHES_PLUGIN_DIR . '/languages/' );
	}

	public static function getTranslation() {
		return array(
			'save_change_text'                 => __( 'Save Changes', 'yay-swatches' ),
			'name_text'                        => __( 'Name', 'yay-swatches' ),
			'swatch_color_text'                => __( 'Swatch color', 'yay-swatches' ),
			'dual_color_text'                  => __( 'Dual color', 'yay-swatches' ),
			'swatch_image_text'                => __( 'Swatch Image', 'yay-swatches' ),
			'title_variant_option_settings'    => __( 'Variant Option Settings', 'yay-swatches' ),
			'subtitle_variant_option_settings' => __( 'Choose the style you want to display for variant options like color swatch, image swatch, button, dropdown.', 'yay-swatches' ),
			'choose_styles_label'              => __( 'Choose types:', 'yay-swatches' ),
			'custom_color_image_option'        => __( 'Color or Custom Image swatch', 'yay-swatches' ),
			'automated_variant_image_option'   => __( 'Automated Variant Image Swatches', 'yay-swatches' ),
			'button_option'                    => __( 'Button', 'yay-swatches' ),
			'radio_option'                     => __( 'Radio', 'yay-swatches' ),
			'dropdown_list_option'             => __( 'Dropdown List', 'yay-swatches' ),
			'no_data_show_text'                => __( 'No data to show. Please add at least one product attribute.', 'yay-swatches' ),
			'variant_image_description'        => __( 'This automatically assigns the existing variant images to image swatches.', 'yay-swatches' ),
			'upload_image_text'                => __( 'Upload Image', 'yay-swatches' ),
			'wc_archive_show_text'             => __( 'Display on Shop / Categories page', 'yay-swatches' ),
			'no_data_found_text'               => __( 'No product found', 'yay-swatches' ),
			'title_swatches_customize'         => __( 'Swatch Customizer', 'yay-swatches' ),
			'subtitle_swatches_customize'      => __( 'Customize the swatch color/image on your product page.', 'yay-swatches' ),
			'circle_text'                      => __( 'Circle', 'yay-swatches' ),
			'square_text'                      => __( 'Square', 'yay-swatches' ),
			'thumbnail_text'                   => __( 'Thumbnail', 'yay-swatches' ),
			'small_text'                       => __( 'Small', 'yay-swatches' ),
			'medium_text'                      => __( 'Medium', 'yay-swatches' ),
			'large_text'                       => __( 'Large', 'yay-swatches' ),
			'custom_text'                      => __( 'Custom', 'yay-swatches' ),
			'fit_text'                         => __( 'Fit', 'yay-swatches' ),
			'top_text'                         => __( 'Top', 'yay-swatches' ),
			'bottom_text'                      => __( 'Bottom', 'yay-swatches' ),
			'center_text'                      => __( 'Center', 'yay-swatches' ),
			'normal_text'                      => __( 'Normal', 'yay-swatches' ),
			'active_text'                      => __( 'Active', 'yay-swatches' ),
			'preview_text'                     => __( 'Preview', 'yay-swatches' ),
			'swatch_preview_text'              => __( 'Swatch Preview', 'yay-swatches' ),
			'button_preview_text'              => __( 'Button Preview', 'yay-swatches' ),
			'disable_text'                     => __( 'Disable', 'yay-swatches' ),
			'enable_text'                      => __( 'Enable', 'yay-swatches' ),
			'image_position_text'              => __( 'Image position', 'yay-swatches' ),
			'swatch_style_text'                => __( 'Swatch style', 'yay-swatches' ),
			'swatch_size_text'                 => __( 'Swatch size', 'yay-swatches' ),
			'picture_size_text'                => __( 'Picture size', 'yay-swatches' ),
			'border_color_text'                => __( 'Border color', 'yay-swatches' ),
			'variant_name_tooltip_text'        => __( 'Variant name tooltip', 'yay-swatches' ),
			'title_button_customize'           => __( 'Button Customizer', 'yay-swatches' ),
			'subtitle_button_customize'        => __( 'Customize the swatch button on your product page.', 'yay-swatches' ),
			'button_size_text'                 => __( 'Button size', 'yay-swatches' ),
			'background_color_text'            => __( 'Background color', 'yay-swatches' ),
			'button_color_text'                => __( 'Button color', 'yay-swatches' ),
			'text_color_text'                  => __( 'Text color', 'yay-swatches' ),
			'title_soldout_customize'          => __( 'Sold Out Customizer', 'yay-swatches' ),
			'subtitle_soldout_customize'       => __( 'Customize the appearance of out-of-stock variants.', 'yay-swatches' ),
			'show_out_of_stock_variant_text'   => __( 'Show out-of-stock variants', 'yay-swatches' ),
			'hide_out_of_stock_variant_text'   => __( 'Hide out-of-stock variants', 'yay-swatches' ),
			'style_text'                       => __( 'Style', 'yay-swatches' ),
			'cross_text'                       => __( 'Cross', 'yay-swatches' ),
			'gray_out_text'                    => __( 'Gray Out', 'yay-swatches' ),
			'opacity_text'                     => __( 'Opacity', 'yay-swatches' ),
			'no_effect_text'                   => __( 'No Effect', 'yay-swatches' ),
			'automatic_text'                   => __( 'Automatic', 'yay-swatches' ),
			'interactive_text'                 => __( 'Interactive', 'yay-swatches' ),
			'tooltip_automatic_text'           => __( 'Automatically hide out-of-stock variant swatches when loading page. Use this when you have only one variant title per product (i.e. Color)', 'yay-swatches' ),
			'tooltip_interactive_text'         => __( 'Hide out-of-stock variants once a swatch is selected. Use this when you have more than one variant title per product.', 'yay-swatches' ),
			'title_wc_archive_customize'       => __( 'Shop / Categories Customizer', 'yay-swatches' ),
			'subtitle_wc_archive_customize'    => __( 'Customize swatches on collection page.', 'yay-swatches' ),
			'show_variant_label_text'          => __( 'Show variant label', 'yay-swatches' ),
			'limit_number_text'                => __( 'Limit the number of swatches/buttons', 'yay-swatches' ),
			'number_swatch_text'               => __( 'Number of swatches', 'yay-swatches' ),
			'number_button_text'               => __( 'Number of buttons', 'yay-swatches' ),
			'plus_action_text'                 => __( 'Action of the plus button', 'yay-swatches' ),
			'nothing_text'                     => __( 'Do nothing', 'yay-swatches' ),
			'link_product_text'                => __( 'Link to product page', 'yay-swatches' ),
			'show_rest_swatch_text'            => __( 'Show the rest of the swatches', 'yay-swatches' ),
			'go_pro_tip_txt'                   => __( 'Please active YaySwatches Pro license to use this feature.', 'yay-swatches' ),
			'alert_pro_text'                   => __( 'This feature is available in YaySwatches Pro version', 'yay-swatches' ),
			'alert_unlock_button_text'         => __( 'Unlock this feature', 'yay-swatches' ),
		);
	}
}
