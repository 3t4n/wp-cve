<?php
/**
 * ElementSpecs.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementSpecs {
	const
		FIELD_INPUT_TEXT = 'inputtext',
		FIELD_INPUT_URL = 'inputurl',
		FIELD_INPUT_NUMBER = 'inputnumber',
		FIELD_TEXT_EDITOR = 'texteditor',
		FIELD_TEXT_AREA = 'textarea',
		FIELD_DROPDOWN = 'dropdown',
		FIELD_DROPDOWN_MENU = 'dropdownmenu',
		FIELD_SLIDER = 'slider',
		FIELD_BORDER_RADIUS = 'borderradius',
		FIELD_ICON = 'icon',
		FIELD_COLOR_PICKER = 'colorpicker',
		FIELD_IMAGE = 'imgupload',
		FIELD_RADIO = 'radio',
		FIELD_CHECKBOX = 'checkbox',
		FIELD_SPACING = 'spacing',
		FIELD_SWITCH = 'switcher',
		FIELD_BUTTON_GROUP = 'buttongroup',
		FIELD_FONT_FAMILY = 'fontfamily',
		FIELD_SHADOW = 'shadow',
		FIELD_DOUBLE_INPUT = 'inputduo',
		FIELD_TABS = 'tabs',
		FIELD_VERTICAL_TABS = 'verticaltabs',
        FIELD_ADVANCED_TABS = 'advancedtabs',
		FIELD_SEPARATOR = 'separator',
		FIELD_GO_PRO = 'gopro',

		ADVANCED_FULL_WIDTH = 'full_width_advanced',
		ADVANCED_FULL_HEIGHT = 'full_height_advanced',
		ADVANCED_PADDING = 'padding_advanced',
		ADVANCED_MARGIN = 'margin_advanced',
		ADVANCED_BORDER_RADIUS = 'border_radius_advanced',
		ADVANCED_CSS_ID = 'css_id_advanced',
		ADVANCED_CSS_CLASS = 'css_class_advanced',
		ADVANCED_BG_NORMAL = 'bg_color_n_advanced',
		ADVANCED_BORDER_TYPE_NORMAL = 'border_n_advanced',
		ADVANCED_BORDER_WIDTH_NORMAL = 'border_width_n_advanced',
		ADVANCED_BORDER_COLOR_NORMAL = 'border_color_n_advanced',
		ADVANCED_BG_HOVER = 'bg_color_h_advanced',
		ADVANCED_BORDER_TYPE_HOVER = 'border_h_advanced',
		ADVANCED_BORDER_WIDTH_HOVER = 'border_width_h_advanced',
		ADVANCED_BORDER_COLOR_HOVER = 'border_color_h_advanced',
		ADVANCED_SHADOW = 'shadow_advanced';

	protected function getTemplate( $slug ) {
		if ( file_exists( STAX_CORE_PATH . 'components/templates/' . $slug . '.tpl' ) ) {
			$content =  file_get_contents( STAX_CORE_PATH . 'components/templates/' . $slug . '.tpl' );
			$content = preg_replace('/\s+/', ' ',$content);
			return $content;
		}

		return '';
	}
}
