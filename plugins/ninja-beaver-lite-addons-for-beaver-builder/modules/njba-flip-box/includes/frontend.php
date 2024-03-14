<?php
$front_head = array(
	'main_title'     => $settings->title_front,
	'sub_title'      => $settings->desc_front,
	'main_title_tag' => $settings->front_title_typography_tag_selection

);
$back_head  = array(
	'main_title'     => $settings->title_back,
	'sub_title'      => $settings->desc_back,
	'main_title_tag' => $settings->back_title_typography_tag_selection
);
$img_src    = '';
if ( ! empty( $settings->title_icon->info_photo_src ) ) {
	$img_src = $settings->title_icon->info_photo_src;
}
$img_icon         = array(
	'image_type'                 => $settings->title_icon->image_type,
	'photo'                      => $settings->title_icon->info_photo,
	'photo_src'                  => $img_src,
	'icon'                       => $settings->title_icon->icon,
	'overall_alignment_img_icon' => 'center'
);

?>
<div class="njba-module-content njba-flip-box-wrap">
    <div class="njba-flip-box  <?php echo $settings->flip_type; ?>">
        <div class="njba-flip-box njba-flip-box-outter">
            <div class="njba-face njba-front">
                <div class="njba-flip-box-section njba-flip-box-section-vertical-middle">
					<?php
					FLBuilder::render_module_html( 'njba-icon-img', $img_icon );
					FLBuilder::render_module_html( 'njba-heading', $front_head );
					?>
                </div>
            </div>
            <div class="njba-face njba-back">
                <div class="njba-flip-box-section njba-flip-box-section-vertical-middle">
					<?php
					FLBuilder::render_module_html( 'njba-heading', $back_head );
					if ( $settings->show_button === 'yes' ) {
						FLBuilder::render_module_html( 'njba-button', $settings->button );
					} ?>
                </div>
            </div>
        </div>
    </div>
</div>
