<?php

/**
 * Main file for editorplus styles generwator
 *
 * @package EditorPlus
 */
require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/blocks.php';

/**
 * Main class responsible for generating editorplus stylings.
 */
class EditorPlus_Styles_Generator {

	/**
	 * Used selectors.
	 *
	 * @var array
	 */
	private $selectors = array();

	/**
	 * Will recursively generate styles from a list of blocks.
	 *
	 * @param array $blocks - List of blocks.
	 *
	 * @return string - CSS Styling.
	 */
	public function generate_styles_from_blocks($blocks) {

		if (!is_array($blocks)) {
			return '';
		}

		$collected_styles = '';

		// Converting reusable blocks to regular blocks.
		$blocks = editorplus_convert_reusable_blocks($blocks);

		if (is_array($blocks)) {
			// Looping each block recursively.
			foreach ($blocks as $block) {

				// Looping innerblocks recursively, if found.
				if (isset($block['innerBlocks'])) {
					$collected_styles .= $this->generate_styles_from_blocks($block['innerBlocks']);
				}

				if (!isset($block['blockName']) || is_null($block['blockName']) || !editorplus_has_block_styles($block)) {
					continue;
				}

				$block_unique_id       = editorplus_generate_unique_uid_for_block($block);
				$block_unique_selector = 'eplus-styles-uid-' . $block_unique_id;
				$selector              = editorplus_get_block_selector($block_unique_selector, $block['blockName']);

				// Skipping if the selector is already used.
				if (in_array($selector, $this->selectors, true) || 0 === strlen($selector)) {
					continue;
				}

				$block_styles = $this->get_block_styles($block, $selector);

				$collected_styles .= $block_styles;

				$this->selectors[] = $selector; // Adding this selector in used selectors.

			}
		}

		return editor_plus_minify_css($collected_styles);
	}

	/**
	 * Will provide styling of the given block based on it's attributes.
	 *
	 * @param array  $block - A single block.
	 * @param string $selector - A unique block selector.
	 *
	 * @return string - CSS Styling.
	 */
	public function get_block_styles($block, $selector) {

		include_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/utils.php';

		$generated_styling        = '';
		$generated_tablet_styling = '';
		$generated_mobile_styling = '';
		$generated_hover_styling  = '';
		$custom_styling           = '';

		if (!isset($block['attrs']['epGeneratedClass'])) {
			return $generated_styling;
		}

		// Creating a collection of editorplus attributes, along with the converter function and extension name which can be
		// used to convert them into CSS styles.
		$editorplus_config = array(
			'styling'           => array(
				'epCustomTypography'         => 'editorplus_typography_convert',
				'epCustomBackground'         => 'editorplus_background_convert',
				'epCustomShadow'             => 'editorplus_box_shadow_convert',
				'epCustomBorder'             => 'editorplus_borders_convert',
				'epCustomBorderRadius'       => 'editorplus_border_radius_convert',
				'epCustomMargin'             => 'editorplus_margin_convert',
				'epCustomPadding'            => 'editorplus_padding_convert',
				'epCustomSizing'             => 'editorplus_sizing_convert',
				'epCustomOtherSettings'      => 'editorplus_extras_convert',
				'epCustomShapeDividerBefore' => 'editorplus_before_shape_divider_convert',
				'epCustomShapeDividerAfter'  => 'editorplus_after_shape_divider_convert',
				'epCustomTextShadow'         => 'editorplus_text_shadow_convert',
				'epCustomTransform'          => 'editorplus_transform_convert',
				'epCustomObjectSettings'     => 'editor_plus_image_settings'
			),
			'custom_block_code' => array(
				'editorPlusCustomCSS' => 'editorplus_customcss_convert',
			),

		);

		// Looping through each editorplus extension, along with it's extended attributes and handler.
		foreach ($editorplus_config as $editorplus_extension => $editorplus_attributes) {

			// Skipping if the extension is disabled.
			if (false === editorplus_is_extension_enabled($editorplus_extension)) {
				continue;
			}

			// Looping and converting each editorplus attribute.
			foreach ($editorplus_attributes as $editorplus_attribute => $converter) {

				// Skipping, if no converter exists yet.
				if (!function_exists($converter)) {
					continue;
				}

				// For Custom CSS.
				if ('editorPlusCustomCSS' === $editorplus_attribute && array_key_exists($editorplus_attribute, $block['attrs'])) {
					$custom_styling .= $converter($block['attrs'][$editorplus_attribute], $selector);
					continue;
				}

				// For Desktop Styling.
				if (array_key_exists($editorplus_attribute, $block['attrs'])) {
					$generated_styling .= $converter($block['attrs'][$editorplus_attribute]);
				}

				// Generating CSS Styles for this attribute, along with responsive and hover.
				$valid_suffix = array('Tablet', 'Mobile', 'Hover');

				foreach ($valid_suffix as $suffix) {

					$scoped_attribute_key = $editorplus_attribute . $suffix;

					if (isset($block['attrs'][$scoped_attribute_key])) {

						$scoped_attribute_val = $block['attrs'][$scoped_attribute_key];
						$additional_styles    = $converter($scoped_attribute_val);

						// Generating styles for Tablet, Mobile and Hover additionally, if needed.
						if ('Tablet' === $suffix) {
							$generated_tablet_styling .= $additional_styles;
						}

						if ('Mobile' === $suffix) {
							$generated_mobile_styling .= $additional_styles;
						}

						if ('Hover' === $suffix) {
							$generated_hover_styling .= $additional_styles;
						}
					}
				}
			}
		}

		$generated_styles_output = editorplus_generate_css_output(
			$selector,
			$generated_styling,
			$generated_tablet_styling,
			$generated_mobile_styling,
			$generated_hover_styling
		);

		// Generating output for custom css.
		if ('' !== $custom_styling) {
			$generated_styles_output .= $custom_styling;
		}

		// For Blocks Extender extension.
		if (editorplus_is_extension_enabled('blocks_extender')) {

			switch ($block['blockName']) {
				case 'core/list':
					$generated_styles_output .= editorplus_convert_extended_list_block($block, $selector);
					break;

				case 'core/columns':
					$generated_styles_output .= editorplus_convert_extended_columns_block($block, $selector);
					break;

				case 'core/button':
					$generated_styles_output .= editorplus_convert_extended_button_block($block, $selector);
					break;
			}
		}

		// For animation support.
		if (editorplus_is_extension_enabled('animation_builder') && isset($block['attrs']['epCustomAnimation'])) {
			$generated_styles_output .= editorplus_animation_convert($block, $selector);
		}

		// For shape divider.
		if (editorplus_is_extension_enabled('styling') && isset($block['attrs']['epCustomShapeDividerBefore'])) {
			$generated_styles_output .= editorplus_shape_divider_convert($block, $selector);
		}

		return $generated_styles_output;
	}
}
