<?php
$btn_settings  = array(
	'button_text'          => $settings->button_text,
	'link'                 => $settings->link,
	'link_target'          => $settings->link_target,
	'buttton_icon_select'  => $settings->buttton_icon_select,
	'button_font_icon'     => $settings->button_font_icon,
	'button_icon_aligment' => $settings->button_icon_aligment,
);
$separator_img = '';
if ( ! empty( $settings->separator_image_select_src ) ) {
	$separator_img = $settings->separator_image_select_src;
}
$heading_settings = array(

	'main_title'                  => $settings->main_title,
	'sub_title'                   => $settings->sub_title,
	'main_title_tag'              => $settings->main_title_tag,
	'separator_select'            => $settings->separator_select,
	'separator_type'              => $settings->separator_type,
	'icon_position'               => $settings->icon_position,
	'separator_icon_text'         => $settings->separator_icon_text,
	'separator_image_select_src'  => $separator_img,
	'separator_text_select'       => $settings->separator_text_select,
	'heading_title_alignment'     => $settings->heading_title_alignment,
	'heading_sub_title_alignment' => $settings->heading_sub_title_alignment,
);

?>
<div class="njba-cta-module-content <?php echo $settings->cta_layout; ?>">
    <div class="njba-cta-text">
		<?php FLBuilder::render_module_html( 'njba-heading', (object) $heading_settings ); ?>
    </div>
	<?php FLBuilder::render_module_html( 'njba-button', (object) $btn_settings ); ?>
</div>
