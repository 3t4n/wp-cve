<?php
/**
 * Model: Fields.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Models;

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Model: Fields.
 */
class Fields {
	private $type;
	private $name;
	private $value;
	private $default;
	private $label;
	private $class;
	private $id;
	private $holderClass;
	private $description;
	private $descriptionAdv;
	private $options;
	private $option;
	private $optionLabel;
	private $attr;
	private $multiple;
	private $alignment;
	private $placeholder;
	private $blank;

	function __construct() {
	}

	/**
	 * Initiate the predefined property for the field object
	 *
	 * @param $attr
	 */
	private function setArgument( $key, $attr ) {
		$this->type     = isset( $attr['type'] ) ? ( $attr['type'] ? $attr['type'] : 'text' ) : 'text';
		$this->multiple = isset( $attr['multiple'] ) ? ( $attr['multiple'] ? $attr['multiple'] : false ) : false;
		$this->name     = ! empty( $key ) ? $key : null;
		$this->default  = isset( $attr['default'] ) ? $attr['default'] : null;

		if ( 'menu_order' === $key ) {
			global $post;

			$this->value = $post->menu_order;
		} else {
			$this->value = isset( $attr['value'] ) ? ( $attr['value'] ? $attr['value'] : null ) : null;
		}

		if ( ! $this->value ) {
			$post_id = get_the_ID();

			if ( ! $this->meta_exist( $post_id, $this->name ) ) {
				$this->value = $this->default;
			} else {
				if ( $this->multiple && ! apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
					$this->value = get_post_meta( $post_id, $this->name );
				} else {
					$this->value = get_post_meta( $post_id, $this->name, true );
				}
			}
		}

		$this->label          = isset( $attr['label'] ) ? ( $attr['label'] ? $attr['label'] : null ) : null;
		$this->class          = isset( $attr['class'] ) ? ( $attr['class'] ? $attr['class'] : null ) : null;
		$this->holderClass    = isset( $attr['holderClass'] ) ? ( $attr['holderClass'] ? $attr['holderClass'] : null ) : null;
		$this->placeholder    = isset( $attr['placeholder'] ) ? ( $attr['placeholder'] ? $attr['placeholder'] : null ) : null;
		$this->description    = isset( $attr['description'] ) ? ( $attr['description'] ? $attr['description'] : null ) : null;
		$this->descriptionAdv = isset( $attr['description_adv'] ) ? ( $attr['description_adv'] ? $attr['description_adv'] : null ) : null;
		$this->options        = isset( $attr['options'] ) ? ( $attr['options'] ? $attr['options'] : [] ) : [];
		$this->option         = isset( $attr['option'] ) ? ( $attr['option'] ? $attr['option'] : null ) : null;
		$this->optionLabel    = isset( $attr['optionLabel'] ) ? ( $attr['optionLabel'] ? $attr['optionLabel'] : null ) : null;
		$this->attr           = isset( $attr['attr'] ) ? ( $attr['attr'] ? $attr['attr'] : null ) : null;
		$this->alignment      = isset( $attr['alignment'] ) ? ( $attr['alignment'] ? $attr['alignment'] : null ) : null;
		$this->blank          = ! empty( $attr['blank'] ) ? $attr['blank'] : null;
		$this->class          = $this->class ? $this->class . ' rt-form-control' : 'rt-form-control';
	}

	/**
	 * Create field
	 *
	 * @param $key
	 * @param $attr
	 *
	 * @return null|string
	 */
	public function Field( $key, $attr = [] ) {
		$this->setArgument( $key, $attr );
		$holderId    = $this->name . '_holder';
		$holderClass = explode( ' ', $this->holderClass );

		$html  = null;
		$html .= '<div class="rt-field-wrapper ' . esc_attr( $this->holderClass ) . '" id="' . esc_attr( $holderId ) . '">';
		$html .= sprintf(
			'<div class="rt-label">%s%s</div>',
			$this->label ? sprintf( '<label for="">%s</label>', wp_kses( $this->label, Fns::allowedHtml() ) ) : '',
			( in_array( 'rt-pro-field', $holderClass, true ) && ! TLPFoodMenu()->has_pro() ) ? '<span class="rtfm-tooltip">Pro</span>' : ''
		);

		$html .= "<div class='rt-field'>";

		switch ( $this->type ) {
			case 'text':
				$html .= $this->text();
				break;

			case 'slug':
				$html .= $this->slug();
				break;

			case 'price':
				$html .= $this->price();
				break;

			case 'url':
				$html .= $this->url();
				break;

			case 'number':
				$html .= $this->number();
				break;

			case 'select':
				$html .= $this->select();
				break;

			case 'textarea':
				$html .= $this->textArea();
				break;

			case 'checkbox':
				$html .= $this->checkbox();
				break;

			case 'radio':
				$html .= $this->radioField();
				break;

			case 'radio-image':
				$html .= $this->radioImage();
				break;

			case 'colorpicker':
				$html .= $this->colorPicker();
				break;

			case 'custom_css':
				$html .= $this->customCss();
				break;

			case 'style':
				$html .= $this->smartStyle();
				break;

			case 'group':
				$html .= $this->contentGroup();
				break;

			case 'category-style':
				$html .= $this->categoryGroup();
				break;

			case 'image':
				$html .= $this->image();
				break;

			case 'image_size':
				$html .= $this->imageSize();
				break;

			case 'switch':
				$html .= $this->switchField();
				break;
		}

		if ( $this->description ) {
			$html .= "<p class='description'>{$this->description}</p>";
		}

		if ( $this->descriptionAdv ) {
			$html .= '<p class="description">' . Fns::htmlKses( $this->descriptionAdv, 'advanced' ) . '</p>';
		}

		$html .= '</div>'; // field.
		$html .= '</div>'; // field holder.

		return $html;
	}

	/**
	 * Generate text field
	 *
	 * @return null|string
	 */
	private function text() {
		$h  = null;
		$h .= '<input
				type="text"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->name ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Generate text field
	 *
	 * @return null|string
	 */
	private function price() {
		$h  = null;
		$h .= '<input
				type="text"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->name ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Generate text field
	 *
	 * @return null|string
	 */
	private function slug() {
		$h  = null;
		$h .= '<input
				type="text"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->name ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Generate color picker
	 *
	 * @return null|string
	 */
	private function colorPicker() {
		$h  = null;
		$h .= '<input
				type="text"
				class="' . esc_attr( $this->class ) . ' fmp-color"
				id="' . esc_attr( $this->name ) . '"
				value="' . esc_attr( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Custom css field
	 *
	 * @return null|string
	 */
	private function customCss() {
		$h  = null;
		$h .= '<div class="rt-custom-css">';
		$h .= '<div class="custom_css_container">';
		$h .= "<div name='" . esc_attr( $this->name ) . "' id='ret-" . absint( wp_rand() ) . "' class='custom-css'>";
		$h .= '</div>';
		$h .= '</div>';

		$h .= '<textarea
					style="display: none;"
					class="custom_css_textarea"
					id="' . esc_attr( $this->name ) . '"
					name="' . esc_attr( $this->name ) . '"
					>' . wp_kses_post( $this->value ) . '</textarea>';
		$h .= '</div>';

		return $h;
	}

	/**
	 * Generate URL field
	 *
	 * @return null|string
	 */
	private function url() {
		$h  = null;
		$h .= '<input
				type="url"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->name ) . '"
				value="' . esc_url( $this->value ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Generate number field
	 *
	 * @return null|string
	 */
	private function number() {
		$h  = null;
		$h .= '<input
				type="number"
				class="' . esc_attr( $this->class ) . '"
				id="' . esc_attr( $this->name ) . '"
				value="' . ( ! empty( $this->value ) ? absint( $this->value ) : null ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				/>';

		return $h;
	}

	/**
	 * Generate Drop-down field
	 *
	 * @return null|string
	 */
	private function select() {
		$h = null;

		if ( $this->multiple ) {
			$this->attr  = " style='min-width:160px;'";
			$this->name  = $this->name . '[]';
			$this->attr  = $this->attr . " multiple='multiple'";
			$this->value = ( is_array( $this->value ) && ! empty( $this->value ) ? $this->value : [] );
		} else {
			$this->value = [ $this->value ];
		}

		$h .= '<select name="' . esc_attr( $this->name ) . '" id="' . esc_attr( $this->name ) . '" class="' . esc_attr( $this->class ) . '" ' . Fns::htmlKses( $this->attr, 'basic' ) . '>';

		if ( $this->blank ) {
			$h .= '<option value="">' . esc_html( $this->blank ) . '</option>';
		}

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$slt = ( in_array( $key, $this->value ) ? 'selected' : null );
				$h  .= '<option ' . esc_attr( $slt ) . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
			}
		}

		$h .= '</select>';

		return $h;
	}

	/**
	 * Generate textArea field
	 *
	 * @return null|string
	 */
	private function textArea() {
		$h  = null;
		$h .= '<textarea
				class="' . esc_attr( $this->class ) . ' rt-textarea"
				id="' . esc_attr( $this->name ) . '"
				name="' . esc_attr( $this->name ) . '"
				placeholder="' . esc_attr( $this->placeholder ) . '"
				' . Fns::htmlKses( $this->attr, 'basic' ) . '
				>' . wp_kses_post( $this->value ) . '</textarea>';

		return $h;
	}

	/**
	 * Generate check box
	 *
	 * @return null|string
	 */
	private function checkbox() {
		$h  = null;
		$id = $this->name;

		if ( $this->multiple ) {
			$this->name  = $this->name . '[]';
			$this->value = ( is_array( $this->value ) && ! empty( $this->value ) ? $this->value : [] );
		}

		if ( $this->multiple ) {
			$h .= '<div class="checkbox-group ' . esc_attr( $this->alignment ) . '" id="' . esc_attr( $id ) . '">';

			if ( is_array( $this->options ) && ! empty( $this->options ) ) {
				foreach ( $this->options as $key => $value ) {
					$checked = ( in_array( $key, $this->value ) ? 'checked' : null );
					$h      .= '<label for="' . esc_attr( $id ) . '-' . esc_attr( $key ) . '">
									<input type="checkbox" id="' . esc_attr( $id ) . '-' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' name="' . esc_attr( $this->name ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '
								</label>';
				}
			}

			$h .= '</div>';
		} else {
			$checked = ( $this->value ? 'checked' : null );
			$h      .= "<label class='rtfm-switch'><input type='checkbox' " . esc_attr( $checked ) . " id='" . esc_attr( $this->name ) . "' name='" . esc_attr( $this->name ) . "' value='" . esc_attr( $this->option ) . "' /><span class='rtfm-switch-slider round'></span></label>";
		}

		return $h;
	}

	/**
	 * Generate Radio field
	 *
	 * @return null|string
	 */
	private function radioField() {
		if ( '' === $this->value ) {
			$this->value = $this->default;
		}

		$h  = null;
		$h .= '<div class="radio-group ' . esc_attr( $this->alignment ) . '" id="' . esc_attr( $this->name ) . '">';

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$checked = ( $key == $this->value ? 'checked' : null );
				$h      .= '<label for="' . esc_attr( $this->name ) . '-' . esc_attr( $key ) . '">
								<input type="radio" id="' . esc_attr( $this->name ) . '-' . esc_attr( $key ) . '" ' . esc_attr( $checked ) . ' name="' . esc_attr( $this->name ) . '" value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '
							</label>';
			}
		}

		$h .= '</div>';

		return $h;
	}

	/**
	 * Radio Image
	 *
	 * @return String
	 */
	private function radioImage() {
		$h  = null;
		$id = 'rtfm-' . $this->name;

		$h .= sprintf( "<div class='rtfm-radio-image %s' id='%s'>", esc_attr( $this->alignment ), esc_attr( $id ) );

		$selected_value = $this->value;

		if ( is_array( $this->options ) && ! empty( $this->options ) ) {
			foreach ( $this->options as $key => $value ) {
				$checked  = ( $key == $selected_value ? 'checked' : null );
				$title    = isset( $value['title'] ) && $value['title'] ? $value['title'] : '';
				$link     = isset( $value['layout_link'] ) && $value['layout_link'] ? $value['layout_link'] : '';
				$linkHtml = empty( $link ) ? $title : '<a href="' . $link . '" target="_blank"  title="Click here to view Demo">' . $title . '</a>';
				$layout   = isset( $value['layout'] ) ? $value['layout'] : '';
				$taghtml  = isset( $value['tag'] ) ? '<div class="rtfm-layout-tag"><span>' . $value['tag'] . '</span></div>' : '';

				$h .= sprintf(
					'<div class="rtfm-radio-layout %7$s"><label data-type="%7$s" class="radio-image %7$s" for="%2$s">
						<input type="radio" class="%4$s" id="%2$s" %3$s name="%4$s" value="%2$s">
						<div class="rtfm-radio-image-wrap">
							<img src="%5$s" title="%6$s" alt="%2$s">
							<div class="rtfm-checked"><span class="dashicons dashicons-yes"></span></div>
							%9$s
						</div>
					</label>
					<div class="rtfm-demo-name">%8$s</div>
					</div>',
					'',
					esc_attr( $key ),
					esc_attr( $checked ),
					esc_attr( $this->name ),
					! empty($value['img']) ? esc_url($value['img']) : '',
					esc_attr( $title ),
					esc_attr( $layout ),
					Fns::htmlKses( $linkHtml, 'basic' ),
					Fns::htmlKses( $taghtml, 'basic' )
				);
			}
		}

		$h .= '</div>';

		return $h;
	}

	private function contentGroup() {
		$h            = null;
		$bgColor      = ! empty( $this->value['bg_color'] ) ? $this->value['bg_color'] : null;
		$borderRadius = ! empty( $this->value['border_radius'] ) ? $this->value['border_radius'] : null;

		$h .= '<div class="rt-multiple-field-container">';

		// Background.
		$h .= '<div class="rt-inner-field color rt-col-3">';
		$h .= '<div class="rt-inner-field-container">';
		$h .= '<span class="label">Background Color</span>';
		$h .= "<input type='text' value='" . esc_attr( $bgColor ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[bg_color]'>";
		$h .= '</div>';
		$h .= '</div>';

		// Border Radius.
		$h .= '<div class="rt-inner-field color rt-col-3">';
		$h .= '<div class="rt-inner-field-container">';
		$h .= '<span class="label">Border Radius</span>';
		$h .= "<input type='text' value='" . esc_attr( $borderRadius ) . "' name='" . esc_attr( $this->name ) . "[border_radius]'>";
		$h .= '</div>';
		$h .= '</div>';

		$h .= '</div>';

		return $h;
	}

	private function categoryGroup() {
		$h            = null;
		$first_color  = ! empty( $this->value['first_color'] ) ? $this->value['first_color'] : null;
		$second_color = ! empty( $this->value['second_color'] ) ? $this->value['second_color'] : null;
		$text_color   = ! empty( $this->value['text_color'] ) ? $this->value['text_color'] : null;
		$sSize        = ! empty( $this->value['size'] ) ? $this->value['size'] : null;
		$sWeight      = ! empty( $this->value['weight'] ) ? $this->value['weight'] : null;
		$sWeight      = ! empty( $this->value['weight'] ) ? $this->value['weight'] : null;
		$sAlign       = ! empty( $this->value['align'] ) ? $this->value['align'] : null;

		$h .= '<div class="rt-multiple-field-container" id="category_title_color">';

		// Background First Color.
		$h .= '<div class="rt-inner-field color rt-col-3">';
		$h .= '<div class="rt-inner-field-container">';
		$h .= '<span class="label">Gradient Background (1st Color)</span>';
		$h .= "<input type='text' value='" . esc_attr( $first_color ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[first_color]'>";
		$h .= '</div>';
		$h .= '</div>';

		// Background Second Color.
		$h .= '<div class="rt-inner-field color rt-col-3">';
		$h .= '<div class="rt-inner-field-container">';
		$h .= '<span class="label">Gradient Background (2nd Color)</span>';
		$h .= "<input type='text' value='" . esc_attr( $second_color ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[second_color]'>";
		$h .= '</div>';
		$h .= '</div>';

		// Text Color.
		$h .= '<div class="rt-inner-field color rt-col-3">';
		$h .= '<div class="rt-inner-field-container">';
		$h .= '<span class="label">Text Color</span>';
		$h .= "<input type='text' value='" . esc_attr( $text_color ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[text_color]'>";
		$h .= '</div>';
		$h .= '</div>';

		$h .= '</div>';
		$h .= '<div class="rt-multiple-field-container" id="category_title_typo">';

		// Font size.
		$h     .= "<div class='rt-inner-field size rt-col-4'>";
		$h     .= "<div class='rt-inner-field-container'>";
		$h     .= "<span class='label'>Font size</span>";
		$h     .= "<select name='" . esc_attr( $this->name ) . "[size]' class='fmp-select2'>";
		$fSizes = $this->scFontSize();
		$h     .= "<option value=''>Default</option>";

		foreach ( $fSizes as $size => $label ) {
			$sSlt = ( $size == $sSize ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $size ) . '" ' . esc_attr( $sSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Weight.
		$h      .= "<div class='rt-inner-field weight rt-col-4'>";
		$h      .= "<div class='rt-inner-field-container'>";
		$h      .= "<span class='label'>Weight</span>";
		$h      .= "<select name='" . esc_attr( $this->name ) . "[weight]' class='fmp-select2'>";
		$h      .= "<option value=''>Default</option>";
		$weights = $this->scTextWeight();

		foreach ( $weights as $weight => $label ) {
			$wSlt = ( $weight == $sWeight ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $weight ) . '" ' . esc_attr( $wSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Alignment.
		$h     .= "<div class='rt-inner-field alignment rt-col-4'>";
		$h     .= "<div class='rt-inner-field-container'>";
		$h     .= "<span class='label'>Alignment</span>";
		$h     .= "<select name='" . esc_attr( $this->name ) . "[align]' class='fmp-select2'>";
		$h     .= "<option value=''>Default</option>";
		$aligns = $this->scAlignment();

		foreach ( $aligns as $align => $label ) {
			$aSlt = ( $align == $sAlign ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $align ) . '" ' . esc_attr( $aSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		$h .= '</div>';

		return $h;
	}

	private function smartStyle() {
		$h           = null;
		$sColor      = ! empty( $this->value['color'] ) ? $this->value['color'] : null;
		$sHoverColor = ! empty( $this->value['hover_color'] ) ? $this->value['hover_color'] : null;
		$sSize       = ! empty( $this->value['size'] ) ? $this->value['size'] : null;
		$sWeight     = ! empty( $this->value['weight'] ) ? $this->value['weight'] : null;
		$sAlign      = ! empty( $this->value['align'] ) ? $this->value['align'] : null;

		$h .= "<div class='rt-multiple-field-container'>";
		// color.
		$h .= "<div class='rt-inner-field color rt-col-3'>";
		$h .= "<div class='rt-inner-field-container'>";
		$h .= "<span class='label'>Color</span>";
		$h .= "<input type='text' value='" . esc_attr( $sColor ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[color]'>";
		$h .= '</div>';
		$h .= '</div>';

		// Hover Color.
		$h .= "<div class='rt-inner-field hover-color rt-col-3'>";
		$h .= "<div class='rt-inner-field-container'>";
		$h .= "<span class='label'>Hover Color</span>";
		$h .= "<input type='text' value='" . esc_attr( $sHoverColor ) . "' class='fmp-color' name='" . esc_attr( $this->name ) . "[hover_color]'>";
		$h .= '</div>';
		$h .= '</div>';
		$h .= '</div>';
		$h .= "<div class='rt-multiple-field-container'>";

		// Font size.
		$h     .= "<div class='rt-inner-field size rt-col-4'>";
		$h     .= "<div class='rt-inner-field-container'>";
		$h     .= "<span class='label'>Font size</span>";
		$h     .= "<select name='" . esc_attr( $this->name ) . "[size]' class='fmp-select2'>";
		$fSizes = $this->scFontSize();
		$h     .= "<option value=''>Default</option>";

		foreach ( $fSizes as $size => $label ) {
			$sSlt = ( $size == $sSize ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $size ) . '" ' . esc_attr( $sSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Weight.
		$h      .= "<div class='rt-inner-field weight rt-col-4'>";
		$h      .= "<div class='rt-inner-field-container'>";
		$h      .= "<span class='label'>Weight</span>";
		$h      .= "<select name='" . esc_attr( $this->name ) . "[weight]' class='fmp-select2'>";
		$h      .= "<option value=''>Default</option>";
		$weights = $this->scTextWeight();

		foreach ( $weights as $weight => $label ) {
			$wSlt = ( $weight == $sWeight ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $weight ) . '" ' . esc_attr( $wSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		// Alignment.
		$h     .= "<div class='rt-inner-field alignment rt-col-4'>";
		$h     .= "<div class='rt-inner-field-container'>";
		$h     .= "<span class='label'>Alignment</span>";
		$h     .= "<select name='" . esc_attr( $this->name ) . "[align]' class='fmp-select2'>";
		$h     .= "<option value=''>Default</option>";
		$aligns = $this->scAlignment();

		foreach ( $aligns as $align => $label ) {
			$aSlt = ( $align == $sAlign ? 'selected' : null );
			$h   .= '<option value="' . esc_attr( $align ) . '" ' . esc_attr( $aSlt ) . '>' . esc_html( $label ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';
		$h .= '</div>';

		return $h;
	}

	private function image() {
		$h = null;

		$h  .= "<div class='rt-image-holder'>";
		$h  .= '<input type="hidden" name="' . esc_attr( $this->name ) . '" value="' . absint( $this->value ) . '" id="' . esc_attr( $this->name ) . '" class="hidden-image-id" />';
		$img = null;
		$c   = 'hidden';

		if ( $id = absint( $this->value ) ) {
			$aImg = wp_get_attachment_image_src( $id, 'thumbnail' );
			$img  = '<img src="' . esc_url( $aImg[0] ) . '" >';
			$c    = null;
		} else {
			$aImg = Fns::placeholder_img_src();
			$img  = '<img src="' . esc_url( $aImg ) . '" >';
		}

		$h .= "<div class='rt-image-preview'>
					" . Fns::htmlKses( $img, 'image' ) . "
					<span class='dashicons dashicons-plus-alt rtAddImage'></span>
					<span class='dashicons dashicons-trash rtRemoveImage " . esc_attr( $c ) . "'></span>
				</div>";
		$h .= '</div>';

		return $h;
	}

	private function imageSize() {
		$width  = ( ! empty( $this->value['width'] ) ? $this->value['width'] : null );
		$height = ( ! empty( $this->value['height'] ) ? $this->value['height'] : null );
		$cropV  = ( ! empty( $this->value['crop'] ) ? $this->value['crop'] : 'soft' );

		$h  = null;
		$h .= "<div class='rt-image-size-holder'>";
		$h .= "<div class='rt-image-size-width rt-image-size'>";
		$h .= '<label>Width</label>';
		$h .= "<input type='number' name='" . esc_attr( $this->name ) . "[width]' value='" . absint( $width ) . "' />";
		$h .= '</div>';
		$h .= "<div class='rt-image-size-height rt-image-size'>";
		$h .= '<label>Height</label>';
		$h .= "<input type='number' name='" . esc_attr( $this->name ) . "[height]' value='" . absint( $height ) . "' />";
		$h .= '</div>';
		$h .= "<div class='rt-image-size-crop rt-image-size'>";
		$h .= '<label>Crop</label>';
		$h .= "<select name='" . esc_attr( $this->name ) . "[crop]' class='fmp-select2'>";

		$cropList = Options::imageCropType();

		foreach ( $cropList as $crop => $cropLabel ) {
			$cSl = ( $crop == $cropV ? 'selected' : null );
			$h  .= '<option value="' . esc_attr( $crop ) . '" ' . esc_attr( $cSl ) . '>' . esc_html( $cropLabel ) . '</option>';
		}

		$h .= '</select>';
		$h .= '</div>';
		$h .= '</div>';

		return $h;
	}

	private function switchField() {
		$h       = null;
		$checked = $this->value ? 'checked' : null;

		$h .= '<label class="rtfm-switch"><input type="checkbox" ' . esc_attr( $checked ) . ' id="' . esc_attr( $this->name ) . '" name="' . esc_attr( $this->name ) . '" value="1" /><span class="rtfm-switch-slider round"></span></label>';

		return $h;
	}

	private function scFontSize() {
		$num = [];

		for ( $i = 10; $i <= 60; $i ++ ) {
			$num[ $i ] = $i . 'px';
		}

		return $num;
	}

	private function scAlignment() {
		return [
			'left'    => esc_html__( 'Left', 'tlp-food-menu' ),
			'right'   => esc_html__( 'Right', 'tlp-food-menu' ),
			'center'  => esc_html__( 'Center', 'tlp-food-menu' ),
			'justify' => esc_html__( 'Justify', 'tlp-food-menu' ),
		];
	}

	private function scTextWeight() {
		return [
			'normal'  => esc_html__( 'Normal', 'tlp-food-menu' ),
			'bold'    => esc_html__( 'Bold', 'tlp-food-menu' ),
			'bolder'  => esc_html__( 'Bolder', 'tlp-food-menu' ),
			'lighter' => esc_html__( 'Lighter', 'tlp-food-menu' ),
			'inherit' => esc_html__( 'Inherit', 'tlp-food-menu' ),
			'initial' => esc_html__( 'Initial', 'tlp-food-menu' ),
			'unset'   => esc_html__( 'Unset', 'tlp-food-menu' ),
			100       => esc_html__( '100', 'tlp-food-menu' ),
			200       => esc_html__( '200', 'tlp-food-menu' ),
			300       => esc_html__( '300', 'tlp-food-menu' ),
			400       => esc_html__( '400', 'tlp-food-menu' ),
			500       => esc_html__( '500', 'tlp-food-menu' ),
			600       => esc_html__( '600', 'tlp-food-menu' ),
			700       => esc_html__( '700', 'tlp-food-menu' ),
			800       => esc_html__( '800', 'tlp-food-menu' ),
			900       => esc_html__( '900', 'tlp-food-menu' ),
		];
	}

	private function meta_exist( $post_id = null, $meta_key = null, $type = 'post' ) {
		if ( ! $post_id ) {
			return false;
		}

		return metadata_exists( $type, $post_id, $meta_key );
	}
}
