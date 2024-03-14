.fl-node-<?php echo $id; ?> ul {
    margin: 0;
    padding: 0;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h3 {
<?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> 
<?php if( $settings->heading_font_size['desktop'] ) { ?> font-size: <?php echo $settings->heading_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->heading_line_height['desktop'] ) { ?> line-height: <?php echo $settings->heading_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->price_box_layout == 1 || $settings->price_box_layout == 2 || $settings->price_box_layout == 3 ): ?> <?php if($settings->heading_margintb['top']) {?> margin-top: <?php echo $settings->heading_margintb['top'].'px'; ?>;
<?php } ?> <?php if($settings->heading_margintb['bottom'])  {?> margin-bottom: <?php echo $settings->heading_margintb['bottom'].'px';  ?>;
<?php } ?> <?php endif; ?> <?php if ($settings->price_box_layout == 4 || $settings->price_box_layout == 5 ): ?> <?php if($settings->heading_paddingtb['top'])  {?> padding-top: <?php echo $settings->heading_paddingtb['top'].'px'; ?>;
<?php } ?> <?php if($settings->heading_paddingtb['bottom'])  {?> padding-bottom: <?php echo $settings->heading_paddingtb['bottom'].'px';  ?>;
<?php } ?> margin: 0;
<?php endif; ?>

}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h4 {
<?php if( $settings->price_box_layout == 1 || $settings->price_box_layout == 2 || $settings->price_box_layout == 3 ): ?> <?php if($settings->price_margintb['top'])  {?> margin-top: <?php echo $settings->price_margintb['top'].'px'; ?>;
<?php } ?> <?php if($settings->price_margintb['bottom'])  {?> margin-bottom: <?php echo $settings->price_margintb['bottom'].'px'; ?>;
<?php } ?> <?php endif; ?> <?php if( $settings->price_box_layout == 4 || $settings->price_box_layout == 5 ): ?> <?php if($settings->price_paddingtb['top'])  {?> padding-top: <?php echo $settings->price_paddingtb['top'].'px'; ?>;
<?php } ?> <?php if($settings->price_paddingtb['bottom'])  {?> padding-bottom: <?php echo $settings->price_paddingtb['bottom'].'px'; ?>;
<?php } ?> margin: 0;
<?php endif; ?> <?php if( $settings->price_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->price_font ); ?><?php } ?> 
<?php if( $settings->price_font_size['desktop'] ) { ?> font-size: <?php echo $settings->price_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->price_line_height['desktop'] ) { ?> line-height: <?php echo $settings->price_line_height['desktop'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading span {
<?php if( $settings->duration_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->duration_font ); ?><?php } ?>
<?php if( $settings->duration_font_size['desktop'] ) { ?> font-size: <?php echo $settings->duration_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->duration_line_height['desktop'] ) { ?> line-height: <?php echo $settings->duration_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->price_box_layout == 1 || $settings->price_box_layout == 2 || $settings->price_box_layout == 3 ): ?> <?php if($settings->duration_margintb['top'])  {?> margin-top: <?php echo $settings->duration_margintb['top'].'px'; ?>;
<?php } ?> <?php if($settings->duration_margintb['bottom'])  {?> margin-bottom: <?php echo $settings->duration_margintb['bottom'].'px'; ?>;
<?php } ?> <?php endif; ?> <?php if( $settings->price_box_layout == 4 || $settings->price_box_layout == 5 ): ?> <?php if($settings->duration_paddingtb['top'])  {?> padding-top: <?php echo $settings->duration_paddingtb['top'].'px'; ?>;
<?php } ?> <?php if($settings->duration_paddingtb['bottom'])  {?> padding-bottom: <?php echo $settings->duration_paddingtb['bottom'].'px';  ?>;
<?php } ?> margin: 0;
<?php endif; ?> display: inline-block;
}

.fl-node-<?php echo $id; ?> .njba-pricing-inner-body {
<?php if( $settings->properties_border_width ) { ?> border-top: <?php echo $settings->properties_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->properties_border_color ) { ?> border-color: <?php echo '#'.$settings->properties_border_color; ?>;
<?php } ?> <?php if( $settings->properties_border_style ) { ?> border-top-style: <?php echo $settings->properties_border_style; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-body ul li {
<?php if( $settings->properties_font_size['desktop'] ) { ?> font-size: <?php echo $settings->properties_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->properties_line_height['desktop'] ) { ?> line-height: <?php echo $settings->properties_line_height['desktop'].'px'; ?>; <?php } ?> <?php if( $settings->properties_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->properties_font ); ?><?php } ?> <?php if( $settings->price_box_layout == 1 || $settings->price_box_layout == 2 || $settings->price_box_layout == 3 ): ?> <?php if($settings->properties_margintb['top'])  {?> margin-top: <?php echo $settings->properties_margintb['top'].'px'; ?>;
<?php } ?> <?php if($settings->properties_margintb['bottom'])  {?> margin-bottom: <?php echo $settings->properties_margintb['bottom'].'px';  ?>;
<?php } ?> <?php endif; ?> <?php if( $settings->price_box_layout == 4 || $settings->price_box_layout == 5 ): ?> <?php if($settings->properties_paddingtb['top'])  {?> padding-top: <?php echo $settings->properties_paddingtb['top'].'px'; ?>;
<?php } ?> <?php if($settings->properties_paddingtb['bottom'])  {?> padding-bottom: <?php echo $settings->properties_paddingtb['bottom'].'px';  ?>;
<?php } ?> margin: 0;
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .njba-btn-main {
    <?php if($settings->space_bw_btn_pro['desktop']) { ?> margin-top: <?php echo $settings->space_bw_btn_pro['desktop'].'px'; ?>;
<?php } ?>
}

<?php
for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) :
    $box_content = $settings->price_box_content[$i];
	$layout = $settings->price_box_layout;
	$btn_id = $id.' .njba-pricing-table-main.layout-'.$layout.' .njba-pricing-column-'.$i;
        $btn_css_array = array(
            //Button Style
            'button_style'                  => 'normal',
            'button_background_color'       => $box_content->button_background_color,
            'button_background_hover_color' => $box_content->button_background_hover_color,
            'button_text_color'             => $box_content->button_text_color,
            'button_text_hover_color'       => $box_content->button_text_hover_color,
            'button_border_radius'          => $box_content->button_border_radius,
            'button_padding'                => $box_content->button_padding,
            'button_margin'                 => $box_content->button_margin,
            'transition'                    => '0.3',
            'width'                         => $box_content->width,
            'custom_width'                  => $box_content->custom_width,
            'custom_height'                 => $box_content->custom_height,
            'alignment'                     => $box_content->alignment,
            //Button Typography
            'button_font_family'            => $box_content->button_font_family,
            'button_font_size'              => $box_content->button_font_size,
        );
        FLBuilder::render_module_css('njba-button' , $btn_id, $btn_css_array);
    $spacer_id = $id.' .njba-pricing-table-main.layout-'.$layout;
    $spacer_array = array(
    	'desktop_space'		=> $settings->space_bw_btn_pro['desktop'],
    	'medium_device'		=> $settings->space_bw_btn_pro['medium'],
    	'small_device'		=> $settings->space_bw_btn_pro['small']
    );
        FLBuilder::render_module_css('njba-spacer' , $spacer_id, $spacer_array);
	?>
<?php
endfor;
//die();
?>
<?php if( $settings->price_box_layout == 1 ): ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-table {
    padding: 0 15px;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-inner {
    box-sizing: border-box;
    box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.1);
    padding-bottom: 0px;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-table:hover {
    transform: scale(1.03);
}

<?php
	for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) :
	$box_content = $settings->price_box_content[$i];
	if($box_content->set_as_featured_box === 'yes') :
?>
.fl-node-<?php echo $id; ?> .layout-1 .njba-label-holder {
    background-color: transparent;
    border-left: 65px solid transparent;
    border-top: 65px solid;
<?php if( $box_content->feature_background_color ) { ?> border-top-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> font-size: 12px;
    height: 0;
    position: absolute;
    right: 15px;
    top: 0;
    width: 0;
    z-index: 1;
}

<?php if($box_content->featured_item === 'feau_icon') :?>
.fl-node-<?php echo $id; ?> .layout-1 .njba-label-holder i {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> position: relative;
    right: 30px;
    top: -55px;
    height: auto;
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_image') :?>
.fl-node-<?php echo $id; ?> .layout-1 .njba-label-holder img {
    position: relative;
    right: 30px;
    top: -55px;
    height: auto;
    width: 30px;
    max-width: 30px;
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_text') :?>
.fl-node-<?php echo $id; ?> .layout-1 .njba-label-holder .njba-text span {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $settings->feature_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->feature_font ); ?><?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> display: block;
    height: 30px;
    left: -59px;
    line-height: 30px;
    position: absolute;
    text-align: center;
    top: -59px;
    transform: rotate(45deg);
    width: 80px;
}

<?php endif; ?>
<?php endif; ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h3 {
<?php if( $box_content->heading_color ) { ?> color: <?php echo '#'.$box_content->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h4 {
<?php if( $box_content->price_color ) { ?> color: <?php echo '#'.$box_content->price_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading span {
<?php if( $box_content->duration_color ) { ?> color: <?php echo '#'.$box_content->duration_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body ul li {
<?php if( $box_content->properties_color ) { ?> color: <?php echo '#'.$box_content->properties_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body {
<?php if( $box_content->foreground ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground )) ?>, <?php echo $box_content->foreground_opc/100; ?>);
<?php } ?> overflow: hidden;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-1 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading {
<?php if( $box_content->foreground_heading ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground_heading )) ?>, <?php echo $box_content->foreground_opc_heading/100; ?>);
<?php } ?> overflow: hidden;
}

<?php endfor; ?>
<?php endif; ?>
<?php if( $settings->price_box_layout == 2 ): ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-table {
    padding: 0;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-inner .njba-pricing-inner-heading {
    padding: 48px 30px 45px;
    overflow: hidden;
    position: relative;
    border-radius: 3px 3px 0 0;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-table.njba-active {
    margin-top: -23px;
    z-index: 1;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-inner {
    margin: 50px 0;
    box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.1);
    border-radius: 3px;
    position: relative;
    text-align: center;
    line-height: 27px;
    transition: all 0.3s;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-inner:hover {
    box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.3);
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-inner-body {
    padding: 35px 30px;
}

<?php for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) : ?>
<?php $box_content = $settings->price_box_content[$i];
if($box_content->set_as_featured_box === 'yes') :
?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder {
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> left: -70px;
    padding: 1px 40px;
    position: absolute;
    top: 18px;
    transform: rotate(-45deg);
    width: 205px;
    text-align: center;
    z-index: 1;
}

<?php if($box_content->featured_item === 'feau_icon') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder i {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> position: relative;
    height: auto;
<?php if( $box_content->feature_background_color ) { ?> background-color: #<?php echo $box_content->feature_background_color; ?>;
<?php } ?>

}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_image') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder img {
    position: relative;
    height: auto;
    width: 30px;
    max-width: 30px;
<?php if( $box_content->feature_background_color ) { ?> background-color: #<?php echo $box_content->feature_background_color; ?>;
<?php } ?>
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_text') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder .njba-text span {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font->family !== 'Default' ) { ?> font-family: "<?php echo $box_content->feature_font->family; ?>";
<?php } ?> <?php if( $box_content->feature_font->weight !== 'Default' ) { ?> font-weight: <?php echo $box_content->feature_font->weight; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> <?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> display: block;
    height: 30px;
    line-height: 30px;
    margin: 0;
}

<?php endif; ?>
<?php //print_r($box_content);
	//die();
endif; ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h3 {
<?php if( $box_content->heading_color ) { ?> color: <?php echo '#'.$box_content->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h4 {
<?php if( $box_content->price_color ) { ?> color: <?php echo '#'.$box_content->price_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading .duration {
<?php if( $box_content->duration_color ) { ?> color: <?php echo '#'.$box_content->duration_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body ul li {
<?php if( $box_content->properties_color ) { ?> color: <?php echo '#'.$box_content->properties_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body {
<?php if( $box_content->foreground ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground )) ?>, <?php echo $box_content->foreground_opc/100; ?>);
<?php } ?> overflow: hidden;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-2 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading {
<?php if( $box_content->foreground_heading ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground_heading )) ?>, <?php echo $box_content->foreground_opc_heading/100; ?>);
<?php } ?> overflow: hidden;
}

<?php endfor; ?>

<?php endif; ?>
<?php if( $settings->price_box_layout == 3 ): ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-inner .njba-pricing-inner-heading {
    position: relative;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-inner {
    padding: 75px 20px 35px;
    position: relative;
    overflow: hidden;
    margin: 20px 0;
    text-align: center;
    transition: all 0.3s;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-inner:hover {
    padding: 45px 20px 65px;
}

<?php for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) : ?>
<?php $box_content = $settings->price_box_content[$i];
if($box_content->set_as_featured_box === 'yes') :
?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder {
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> border-radius: 20px;
    padding: 0;
    position: absolute;
    right: 40px;
    text-align: right;
    text-transform: uppercase;
    top: 20px;
    z-index: 1;
}

<?php if($box_content->featured_item === 'feau_icon') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder i {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> position: relative;
    height: auto;
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?>

}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_image') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder img {
    position: relative;
    height: auto;
    border-radius: 20px;
    width: 30px;
    max-width: 30px;
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?>
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_text') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder .njba-text span {
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font->family !== 'Default' ) { ?> font-family: "<?php echo $box_content->feature_font->family; ?>";
<?php } ?> <?php if( $box_content->feature_font->weight !== 'Default' ) { ?> font-weight: <?php echo $box_content->feature_font->weight; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> <?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> display: block;
    border-radius: 20px;
    min-width: 110px;
    position: absolute;
    text-align: left;
    transform: translateX(-50%) translateY(0%) rotate(-90deg);
    padding: 5px 50px 5px 20px;
}

<?php endif; ?>
<?php //print_r($box_content);
	//die();
endif; ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h3 {
<?php if( $box_content->heading_color ) { ?> color: <?php echo '#'.$box_content->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h4 {
<?php if( $box_content->price_color ) { ?> color: <?php echo '#'.$box_content->price_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading .duration {
<?php if( $box_content->duration_color ) { ?> color: <?php echo '#'.$box_content->duration_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body ul li {
<?php if( $box_content->properties_color ) { ?> color: <?php echo '#'.$box_content->properties_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-inner.njba-pricing-column-<?php echo $i; ?> {
<?php if( $box_content->foreground ) { ?> background-color: <?php echo '#'.$box_content->foreground; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body {
<?php if( $box_content->foreground ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground )) ?>, <?php echo $box_content->foreground_opc/100; ?>);
<?php } ?> overflow: hidden;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-3 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading {
<?php if( $box_content->foreground_heading ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground_heading )) ?>, <?php echo $box_content->foreground_opc_heading/100; ?>);
<?php } ?> overflow: hidden;
}

<?php endfor; ?>

<?php endif; ?>
<?php if( $settings->price_box_layout == 4 ): ?>
.fl-node-<?php echo $id; ?> .layout-4 .njba-pricing-inner .njba-pricing-inner-heading {
    position: relative;
}

.fl-node-<?php echo $id; ?> .layout-4 .njba-pricing-inner {
    position: relative;
    overflow: hidden;
    margin: 20px 0;
    text-align: center;
    transition: all 0.3s;
}

.fl-node-<?php echo $id; ?> .layout-4 .njba-pricing-inner-heading h3 {
    border-bottom: 1px solid #bfbebe;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-inner:hover {
    box-shadow: 0 0 6px 0 #bfbfbf;
}

.fl-node-<?php echo $id; ?> .layout-4 .njba-pricing-inner-body {
    padding: 29px 15px;
}

<?php for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) : ?>
<?php $box_content = $settings->price_box_content[$i];
if($box_content->set_as_featured_box === 'yes') :
?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder {
    left: -15px;
    position: absolute;
    top: 10px;
    z-index: 1;
}

<?php if($box_content->featured_item === 'feau_icon') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder i {
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> border-radius: 30px;
<?php if( $box_content->feature_color ) { ?> color: #<?php echo $box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> position: relative;

<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> display: inline-block;
    border-radius: 30px;
    padding: 6px 30px 6px 40px;
    height: auto;

}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_image') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder img {
    position: relative;
    height: auto;
    border-radius: 30px;
    width: 30px;
    max-width: 30px;
    padding: 6px 30px 6px 40px;
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?>
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_text') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder .njba-text span {
<?php if( $box_content->feature_font->family !== 'Default' ) { ?> font-family: "<?php echo $box_content->feature_font->family; ?>";
<?php } ?> <?php if( $box_content->feature_font->weight !== 'Default' ) { ?> font-weight: <?php echo $box_content->feature_font->weight; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> border-radius: 30px;
<?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> position: relative;

<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> display: inline-block;
    padding: 6px 30px 6px 40px;
}

<?php endif; ?>
<?php //print_r($box_content);
	//die();
endif; ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h3 {
<?php if( $box_content->heading_color ) { ?> color: <?php echo '#'.$box_content->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h4 {
<?php if( $box_content->price_color ) { ?> color: <?php echo '#'.$box_content->price_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading .duration {
<?php if( $box_content->duration_color ) { ?> color: <?php echo '#'.$box_content->duration_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body ul li {
<?php if( $box_content->properties_color ) { ?> color: <?php echo '#'.$box_content->properties_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-inner.njba-pricing-column-<?php echo $i; ?> {
<?php if( $box_content->foreground ) { ?> background-color: <?php echo '#'.$box_content->foreground; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body {
<?php if( $box_content->foreground ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground )) ?>, <?php echo $box_content->foreground_opc/100; ?>);
<?php } ?> overflow: hidden;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-4 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading {
<?php if( $box_content->foreground_heading ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground_heading )) ?>, <?php echo $box_content->foreground_opc_heading/100; ?>);
<?php } ?> overflow: hidden;
}

<?php endfor; ?>

<?php endif; ?>
<?php if( $settings->price_box_layout == 5 ): ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main {
    display: flex;
}

.fl-node-<?php echo $id; ?> .layout-5 .njba-pricing-table {
    padding: 0;
}

.fl-node-<?php echo $id; ?> .layout-5 .njba-pricing-inner .njba-pricing-inner-heading {
    overflow: hidden;
    position: relative;
}

.fl-node-<?php echo $id; ?> .layout-5 .njba-pricing-inner {
    height: 100%;
    position: relative;
    text-align: center;
    transition: all 0.3s;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-active .njba-pricing-inner {
    box-shadow: 0 0 10px 0 #505050;
    position: relative;
    z-index: 2;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-inner:hover {
    box-shadow: 0 0 5px 3px #404040;
    z-index: 2;
}

.fl-node-<?php echo $id; ?> .layout-5 .njba-pricing-inner-body ul li {
    border-bottom: 1px solid #bfbebe;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-inner-body ul li:last-child {
    border-bottom: none;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-inner-body li:hover {
    background-color: #efefef;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-inner:hover {
    box-shadow: 0 0 6px 0 #bfbfbf;
}

.fl-node-<?php echo $id; ?> .layout-5 .njba-pricing-inner-body {
    padding: 20px 0;
}

<?php for($i=0,$iMax = count($settings->price_box_content); $i < $iMax; $i++) : ?>
<?php $box_content = $settings->price_box_content[$i];
if($box_content->set_as_featured_box === 'yes') :
?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder {
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    transform: translate(0%, -100%);
    z-index: 1;
}

<?php if($box_content->featured_item === 'feau_icon') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder i {
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> <?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> <?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> border-radius: 4px;
    box-shadow: 0 1px 6px #2f2f2f;
    display: inline-block;
    padding: 6px 20px;
    height: auto;
    width: auto;

}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_image') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder img {
    position: relative;
    height: auto;
    width: 30px;
    max-width: 30px;
<?php if( $box_content->feature_background_color ) { ?> background-color: <?php echo '#'.$box_content->feature_background_color; ?>;
<?php } ?> border-radius: 4px;
    box-shadow: 0 1px 6px #2f2f2f;
    display: inline-block;
}

<?php endif; ?>
<?php if($box_content->featured_item === 'feau_text') :?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-label-holder .njba-text span {
<?php if( $box_content->feature_font->family !== 'Default' ) { ?> font-family: "<?php echo $box_content->feature_font->family; ?>";
<?php } ?> <?php if( $box_content->feature_font->weight !== 'Default' ) { ?> font-weight: <?php echo $box_content->feature_font->weight; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> <?php if( $box_content->feature_color ) { ?> color: <?php echo '#'.$box_content->feature_color; ?>;
<?php } ?> <?php if( $box_content->feature_font_size ) { ?> font-size: <?php echo $box_content->feature_font_size.'px'; ?>;
<?php } ?> <?php if( $box_content->feature_background_color ) { ?> background-color: #<?php echo $box_content->feature_background_color; ?>;
<?php } ?> border-radius: 4px;
    box-shadow: 0 1px 6px #2f2f2f;
    display: inline-block;
}

<?php endif; ?>
<?php //print_r($box_content);
	//die();
endif; ?>
.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h3 {
<?php if( $box_content->heading_color ) { ?> color: <?php echo '#'.$box_content->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading h4 {
<?php if( $box_content->price_color ) { ?> color: <?php echo '#'.$box_content->price_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading .duration {
<?php if( $box_content->duration_color ) { ?> color: <?php echo '#'.$box_content->duration_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body ul li {
<?php if( $box_content->properties_color ) { ?> color: <?php echo '#'.$box_content->properties_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-inner.njba-pricing-column-<?php echo $i; ?> {
<?php if( $box_content->foreground ) { ?> background-color: <?php echo '#'.$box_content->foreground; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-body {
<?php if( $box_content->foreground ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground )) ?>, <?php echo $box_content->foreground_opc/100; ?>);
<?php } ?> overflow: hidden;
}

.fl-node-<?php echo $id; ?> .njba-pricing-table-main.layout-5 .njba-pricing-column-<?php echo $i; ?> .njba-pricing-inner-heading {
<?php if( $box_content->foreground_heading ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->foreground_heading )) ?>, <?php echo $box_content->foreground_opc_heading/100; ?>);
<?php } ?> overflow: hidden;
}

<?php endfor; ?>

<?php endif; ?>

@media ( max-width: 900px ) {
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main {
        display: inline-block;
    }
    .fl-node-<?php echo $id; ?> .njba-btn-main {
        <?php if($settings->space_bw_btn_pro['medium']) { ?> margin-top: <?php echo $settings->space_bw_btn_pro['medium'].'px'; ?>; <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h3 {
        <?php if( $settings->heading_font_size['medium'] ) { ?> font-size: <?php echo $settings->heading_font_size['medium'].'px'; ?>; <?php } ?> 
        <?php if( $settings->heading_line_height['medium'] ) { ?> line-height: <?php echo $settings->heading_line_height['medium'].'px'; ?>;
        <?php } ?>  
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h4 {
        <?php if( $settings->price_font_size['medium'] ) { ?> font-size: <?php echo $settings->price_font_size['medium'].'px'; ?>; <?php } ?>
        <?php if( $settings->price_line_height['medium'] ) { ?> line-height: <?php echo $settings->price_line_height['medium'].'px'; ?>;
        <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading span {
        <?php if( $settings->duration_font_size['medium'] ) { ?> font-size: <?php echo $settings->duration_font_size['medium'].'px'; ?>; <?php } ?>
        <?php if( $settings->duration_line_height['medium'] ) { ?> line-height: <?php echo $settings->duration_line_height['medium'].'px'; ?>;
        <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-body ul li {
        <?php if( $settings->properties_font_size['medium'] ) { ?> font-size: <?php echo $settings->properties_font_size['medium'].'px'; ?>; <?php } ?> <?php if( $settings->properties_line_height['medium'] ) { ?> line-height: <?php echo $settings->properties_line_height['medium'].'px'; ?>; <?php } ?>
    }
}

@media ( max-width: 767px ) {
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main {
        display: inline-block;
    }
    .fl-node-<?php echo $id; ?> .njba-btn-main {
        <?php if($settings->space_bw_btn_pro['small']) { ?> margin-top: <?php echo $settings->space_bw_btn_pro['small'].'px'; ?>; <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h3 {
        <?php if( $settings->heading_font_size['small'] ) { ?> font-size: <?php echo $settings->heading_font_size['small'].'px'; ?>; <?php } ?> 
        <?php if( $settings->heading_line_height['small'] ) { ?> line-height: <?php echo $settings->heading_line_height['small'].'px'; ?>;
        <?php } ?>  
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading h4 {
        <?php if( $settings->price_font_size['small'] ) { ?> font-size: <?php echo $settings->price_font_size['small'].'px'; ?>; <?php } ?>
        <?php if( $settings->price_line_height['small'] ) { ?> line-height: <?php echo $settings->price_line_height['small'].'px'; ?>;
        <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-heading span {
        <?php if( $settings->duration_font_size['small'] ) { ?> font-size: <?php echo $settings->duration_font_size['small'].'px'; ?>; <?php } ?>
        <?php if( $settings->duration_line_height['small'] ) { ?> line-height: <?php echo $settings->duration_line_height['small'].'px'; ?>;
        <?php } ?>
    }
    .fl-node-<?php echo $id; ?> .njba-pricing-table-main .njba-pricing-inner-body ul li {
        <?php if( $settings->properties_font_size['small'] ) { ?> font-size: <?php echo $settings->properties_font_size['small'].'px'; ?>; <?php } ?>
        <?php if( $settings->properties_line_height['small'] ) { ?> line-height: <?php echo $settings->properties_line_height['small'].'px'; ?>; <?php } ?>
    }
}