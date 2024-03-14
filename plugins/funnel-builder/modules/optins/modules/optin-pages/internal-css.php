<?php

$css = [];
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$label_color = isset( $customizations['input_label_color'] ) ? "color:" . $customizations['input_label_color'] . ";" : '';

$css["body .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec label"] = $label_color;
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$text_color = isset( $customizations['input_text_color'] ) ? "color:" . $customizations['input_text_color'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$bg_color = isset( $customizations['input_bg_color'] ) ? "background-color:" . $customizations['input_bg_color'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$font_size = isset( $customizations['input_font_size'] ) ? "font-size:" . $customizations['input_font_size'] . "px;line-height:" . ( $customizations['input_font_size'] + 10 ) . "px;" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$label_font_size = isset( $customizations['label_font_size'] ) ? "font-size:" . $customizations['label_font_size'] . "px;line-height:" . ( $customizations['label_font_size'] + 10 ) . "px;" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$font_weight = isset( $customizations['input_font_weight'] ) ? "font-weight:" . $customizations['input_font_weight'] . ";" : "normal";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$font_family = isset( $customizations['input_font_family'] ) ? "font-family:" . $customizations['input_font_family'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_font_size = isset( $customizations['button_font_size'] ) ? "font-size:" . $customizations['button_font_size'] . "px;line-height:" . ( $customizations['button_font_size'] + 10 ) . "px;" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_font_family = isset( $customizations['button_font_family'] ) ? "font-family:" . $customizations['button_font_family'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_font_weight = isset( $customizations['button_font_weight'] ) ? "font-weight:" . $customizations['button_font_weight'] . ";" : "normal";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_text_color = isset( $customizations['button_text_color'] ) ? "color:" . $customizations['button_text_color'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_text_color_hover = isset( $customizations['button_text_color_hover'] ) ? "color:" . $customizations['button_text_color_hover'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_bg_color = isset( $customizations['button_bg_color'] ) ? "background-color:" . $customizations['button_bg_color'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_bg_color_hover = isset( $customizations['button_bg_color_hover'] ) ? "background-color:" . $customizations['button_bg_color_hover'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_border_color_hover = isset( $customizations['button_border_color_hover'] ) ? "border-color:" . $customizations['button_border_color_hover'] . ";" : "";
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_width   = ( isset( $customizations['button_width'] ) && 'full' === $customizations['button_width'] ) ? "width:100% !important;" : "width:auto !important;";
$btn_align   = isset( $customizations['button_align'] ) ? "text-align:" . $customizations['button_align'] . " !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_mrg_top = isset( $customizations['button_margin_top'] ) ? "margin-top:" . $customizations['button_margin_top'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_mrg_bot = isset( $customizations['button_margin_bottom'] ) ? "margin-bottom:" . $customizations['button_margin_bottom'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_mrg_rgt = isset( $customizations['button_margin_right'] ) ? "margin-right:" . $customizations['button_margin_right'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_mrg_lft = isset( $customizations['button_margin_left'] ) ? "margin-left:" . $customizations['button_margin_left'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$btn_pad_top = ( isset( $customizations['button_margin_top'] ) && 'custom' === $customizations['button_size'] ) ? "padding-top:" . $customizations['button_padding_top'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_pad_bot = ( isset( $customizations['button_margin_bottom'] ) && 'custom' === $customizations['button_size'] ) ? "padding-bottom:" . $customizations['button_padding_bottom'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_pad_rgt = ( isset( $customizations['button_margin_right'] ) && 'custom' === $customizations['button_size'] ) ? "padding-right:" . $customizations['button_padding_right'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$btn_pad_lft = ( isset( $customizations['button_margin_left'] ) && 'custom' === $customizations['button_size'] ) ? "padding-left:" . $customizations['button_padding_left'] . "px !important;" : ""; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$css[".bwfac_forms_outer.wfop-custom-form .bwfac_form_sec ::-webkit-input-placeholder"] = $text_color;
$css[".bwfac_forms_outer.wfop-custom-form .bwfac_form_sec ::-moz-placeholder"]          = $text_color;
$css[".bwfac_forms_outer.wfop-custom-form .bwfac_form_sec :-ms-input-placeholder"]      = $text_color;
$css[".bwfac_forms_outer.wfop-custom-form .bwfac_form_sec :-moz-placeholder"]           = $text_color;
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec input[type="text"], .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec input[type="email"], .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec input[type="number"], .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec input[type="tel"], .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec select, .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec textarea'] = 'border:' . $customizations['input_border_size'] . 'px ' . $customizations['input_border_type'] . ' ' . $customizations['input_border_color'] . ';' . $text_color . $bg_color;

$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec input, .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec textarea, .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec select'] = $font_size . $font_weight . $font_family;

$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec, .bwfac_forms_outer.wfop-custom-form .bwfac_form_sec label'] = $label_font_size . $font_weight . $font_family;
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec .wfop_submit_btn'] = 'cursor:pointer;min-height:auto;border: ' . $customizations['button_border_size'] . 'px ' . $customizations['button_border_type'] . ' ' . $customizations['button_border_color'] . ';' . $btn_font_size . $btn_font_family . $btn_font_weight . $btn_text_color . $btn_bg_color . $btn_width . $btn_mrg_top . $btn_mrg_bot . $btn_mrg_rgt . $btn_mrg_lft . $btn_pad_top . $btn_pad_bot . $btn_pad_rgt . $btn_pad_lft;

$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec .wfop_submit_btn:hover'] = $btn_text_color_hover . $btn_bg_color_hover . $btn_border_color_hover;

$css['.bwfac_forms_outer.wfop-custom-form .bwfac_form_sec .bwf-custom-button'] = $btn_align;

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$css       = apply_filters( 'wfop_internal_css', $css, $customizations );
$print_css = '';
foreach ( $css as $selector => $rules ) {
	$print_css .= $selector . '{' . $rules . '}';
}

if ( ! empty( $print_css ) ) {
	?>
	<style type="text/css" id="wfop_custom_css">
		<?php echo $print_css; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</style>
	<?php
}