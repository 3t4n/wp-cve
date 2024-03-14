.fl-node-<?php echo $id; ?> .njba-arrow-wrapper.njba-bottom-arrow {
<?php
 if($settings->dots == 1){ ?> bottom: -15px;
<?php
	}else{ ?> bottom: -60px;
<?php
	}
?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-<?php echo $settings->testimonial_layout; ?> a.bx-pager-link {
<?php if( $settings->dot_color ) { ?> background: <?php echo '#'.$settings->dot_color; ?><?php } ?>;
    opacity: 0.5;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-<?php echo $settings->testimonial_layout; ?> a.bx-pager-link.active {
<?php if( $settings->active_dot_color ) { ?> background: <?php echo '#'.$settings->active_dot_color; ?>;
<?php } ?> opacity: 1;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-<?php echo $settings->testimonial_layout; ?> .njba-arrow-wrapper i.fa {
    color: <?php echo '#'.$settings->arrow_color; ?>;
    font-size: <?php echo $settings->arrows_size.'px'; ?>;
}

<?php  if($settings->show_quote === 'yes'){ ?>
.fl-node-<?php echo $id; ?> .njba-testimonial.layout-9 .njba-testimonial-quote-icon,
.fl-node-<?php echo $id; ?> .njba-testimonial.layout-9 .njba-testimonial-quote-icon-two {
<?php if( $settings->quote_sign_color ) { ?> color: <?php echo '#'.$settings->quote_sign_color; ?>;
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-main .testimonial-body-right {
<?php if( isset($settings->content_box_padding['top']) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom']) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php }?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php }?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php }?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial-heading h1 {
<?php if( $settings->heading_color ) { ?> color: <?php echo '#'.$settings->heading_color; ?>;
<?php } ?> <?php if( $settings->heading_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->heading_font ); ?><?php } ?> <?php if( isset($settings->heading_font_size['desktop']) ) { ?> font-size: <?php echo $settings->heading_font_size['desktop'].'px'; ?>;
<?php } ?>
<?php if( isset($settings->heading_line_height['desktop']) ) { ?> line-height: <?php echo $settings->heading_line_height['desktop'].'px'; ?>;<?php } ?>
 <?php if( $settings->heading_alignment ) { ?> text-align: <?php echo $settings->heading_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial-heading h1::before {
<?php if( $settings->heading_color ) { ?> background-color: <?php echo '#'.$settings->heading_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial-image img {
<?php if( $settings->border_color ) { ?> border-color: <?php echo '#'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_width ) { ?> border-width: <?php echo $settings->border_width.'px'; ?>;
<?php } ?> <?php if( $settings->border_radius ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->image_border_style ) { ?> border-style: <?php echo $settings->image_border_style; ?>;
<?php } ?> max-height: <?php echo $settings->image_size.'px'; ?>;
    max-width: <?php echo $settings->image_size.'px'; ?>;
    margin: 0 auto;
}

.fl-node-<?php echo $id; ?> .njba-testimonial-content {
<?php if( $settings->content_alignment ) { ?> text-align: <?php echo $settings->content_alignment; ?>;
<?php } ?> <?php if( $settings->text_color ) { ?> color: <?php echo '#'.$settings->text_color; ?><?php } ?>;
<?php if( $settings->text_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->text_font ); ?><?php } ?> <?php if( isset($settings->text_font_size['desktop'] )) { ?> font-size: <?php echo $settings->text_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( isset($settings->text_line_height['desktop'] )) { ?> line-height: <?php echo $settings->text_line_height['desktop'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['top'] ) ) { ?> margin-top: <?php echo $settings->content_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->content_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['left'] ) ) { ?> margin-left: <?php echo $settings->content_margin['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_margin['right'] ) ) { ?> margin-right: <?php echo $settings->content_margin['right'].'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-testimonial-title {
<?php if( $settings->title_alignment ) { ?> text-align: <?php echo $settings->title_alignment; ?>;
<?php } ?> <?php if( $settings->title_color ) { ?> color: <?php echo '#'.$settings->title_color; ?>;
<?php } ?> <?php if( $settings->title_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->title_font ); ?><?php } ?>
 <?php if( isset($settings->title_font_size['desktop']) ) { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;<?php } ?>
 <?php if( isset($settings->title_line_height['desktop']) ) { ?> line-height: <?php echo $settings->title_line_height['desktop'].'px'; ?>;<?php } ?>

 <?php if( isset($settings->title_margin['top']) ) { ?> margin-top: <?php echo $settings->title_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->title_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->title_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->title_margin['left'] ) ) { ?> margin-left: <?php echo $settings->title_margin['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->title_margin['right'] ) ) { ?> margin-right: <?php echo $settings->title_margin['right'].'px'; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-testimonial-sub-title {
<?php if( $settings->subtitle_alignment ) { ?> text-align: <?php echo $settings->subtitle_alignment; ?>;
<?php } ?> <?php if( $settings->subtitle_color ) { ?> color: <?php echo '#'.$settings->subtitle_color; ?>;
<?php } ?> <?php if( $settings->subtitle_font['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->subtitle_font ); ?><?php } ?> <?php if( isset($settings->subtitle_font_size['desktop']) ) { ?> font-size: <?php echo $settings->subtitle_font_size['desktop'].'px'; ?>;
<?php } ?>
<?php if( isset($settings->subtitle_line_height['desktop']) ) { ?> line-height: <?php echo $settings->subtitle_line_height['desktop'].'px'; ?>;<?php } ?>
 <?php if( isset($settings->subtitle_margin['top'] ) ) { ?> margin-top: <?php echo $settings->subtitle_margin['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->subtitle_margin['bottom'] ) ) { ?> margin-bottom: <?php echo $settings->subtitle_margin['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->subtitle_margin['left'] ) ) { ?> margin-left: <?php echo $settings->subtitle_margin['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->subtitle_margin['right'] ) ) { ?> margin-right: <?php echo $settings->subtitle_margin['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-review {
<?php if( $settings->rate_alignment ) { ?> text-align: <?php echo $settings->rate_alignment; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-review i.fa.fa-star {
<?php if( $settings->active_rate_color ) { ?> color: <?php echo '#'.$settings->active_rate_color; ?>;
<?php } ?>

}

.fl-node-<?php echo $id; ?> .njba-review .rating i.fa {
<?php if( isset($settings->rate_font_size['desktop'] )) { ?> font-size: <?php echo $settings->rate_font_size['desktop'].'px'; ?>;
<?php } ?>
<?php if( isset($settings->rate_line_height['desktop']) ) { ?> line-height: <?php echo $settings->rate_line_height['desktop'].'px'; ?>;<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-review i.fa.fa-star-o {
<?php if( $settings->deactive_rate_color ) { ?> color: <?php echo '#'.$settings->deactive_rate_color; ?>;
<?php } ?>
}

<?php if( $settings->testimonial_layout == 1 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_1 {
<?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom']) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left']) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right']) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->testimonial_layout == 2 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_2 {
<?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->testimonial_layout == 3 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_3 .njba-testimonial-body-right {
<?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?><?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php if($settings->show_indicator === 'yes'){ ?>
.njba-testimonial.layout-3 .njba-testimonial-body-right::before {
    border-style: solid;
    border-width: 10px 15px 10px 0;
    content: "\a ";
    left: 0;
    position: absolute;
    top: 35px;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-3 .njba-testimonial-body-right::before {
<?php if( $settings->layout_4_content_bg ) { ?> border-color: transparent <?php echo ' #'.$settings->layout_4_content_bg; ?> transparent transparent;
<?php } ?>
}

<?php } ?>

.fl-node-<?php echo $id; ?> .njba-testimonial-main.layout-3 .njba-content-arrow-left {
<?php if( $settings->box_border_color ) { ?> border-right: 10px solid<?php echo ' #'.$settings->box_border_color; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->testimonial_layout == 4 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_4 {
<?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?><?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-4 .bx-viewport {
    height: auto !important;
}

<?php } ?>
<?php if( $settings->testimonial_layout == 5 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_5 {
<?php if( $settings->box_border_radius ) { ?> border-radius: <?php echo $settings->box_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?><?php if( $settings->box_border_style ) { ?> border-style: <?php echo $settings->box_border_style; ?>;
<?php } ?> <?php if( $settings->box_border_width ) { ?> border-width: <?php echo $settings->box_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?><?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->testimonial_layout == 6 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial.layout-6 .njba-testimonial-title {
<?php if( $settings->box_border_color ) { ?> border-color: <?php echo '#'.$settings->box_border_color; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-6 .njba-testimonial-body-left {
<?php if( $settings->border_color ) { ?> background-color: <?php echo '#'.$settings->border_color; ?>;
<?php } ?>
}

<?php } ?>
<?php if( $settings->testimonial_layout == 7 ) { ?>

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-7 .njba-testimonial-body-right {
<?php if( $settings->img_bg_color ) { ?> background-color: <?php echo '#'.$settings->img_bg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_7 {
<?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?><?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-7 .njba-testimonial-body img {
<?php if( $settings->border_color ) { ?> border-color: <?php echo '#'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_width ) { ?> border-width: <?php echo $settings->border_width.'px'; ?>;
<?php } ?> <?php if( $settings->border_radius ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->image_border_style ) { ?> border-style: <?php echo $settings->image_border_style; ?>;
<?php } ?>
}

<?php  if($settings->show_indicator === 'yes'){ ?>
.njba-testimonial.layout-7 .njba-testimonial-body-left::after {
    border-bottom: 60px solid transparent;
    border-left: 60px solid #d67456;
    border-right: 40px solid transparent;
    content: "";
    display: block;
    height: 0;
    margin-top: -25px;
    position: absolute;
    right: -100px;
    top: 50%;
    width: 0;
    z-index: 3;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-7 .njba-testimonial-body-left::after {
<?php if( $settings->layout_4_content_bg ) { ?> border-left-color: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php  if($settings->show_quote === 'yes'){ ?>
.njba-testimonial.layout-7 .njba-testimonial-body-left p:before {
    color: #fff;
    font-size: 80px;
    left: 80px;
    opacity: 0.2;
    position: absolute;
    top: 60px;
    content: "";
    font-family: FontAwesome;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-7 .njba-testimonial-body-left p:before {
<?php if( $settings->quote_sign_color ) { ?> color: <?php echo '#'.$settings->quote_sign_color; ?>;
<?php } ?>
}

<?php } ?>

<?php } ?>
<?php if( $settings->testimonial_layout == 8 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial.layout-8 .njba-testimonial-body-right {
<?php if( $settings->img_bg_color ) { ?> background-color: <?php echo '#'.$settings->img_bg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial-body.layout_8 {
<?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?><?php if( isset($settings->content_box_padding['top'] ) ) { ?> padding-top: <?php echo $settings->content_box_padding['top'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['bottom'] ) ) { ?> padding-bottom: <?php echo $settings->content_box_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['left'] ) ) { ?> padding-left: <?php echo $settings->content_box_padding['left'].'px'; ?>;
<?php } ?> <?php if( isset($settings->content_box_padding['right'] ) ) { ?> padding-right: <?php echo $settings->content_box_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-8 .njba-testimonial-body img {
<?php if( $settings->border_color ) { ?> border-color: <?php echo '#'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_width ) { ?> border-width: <?php echo $settings->border_width.'px'; ?>;
<?php } ?> <?php if( $settings->border_radius ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->image_border_style ) { ?> border-style: <?php echo $settings->image_border_style; ?>;
<?php } ?>
}

<?php  if($settings->show_indicator === 'yes'){ ?>
.njba-testimonial.layout-8 .njba-testimonial-body-left::after {
    border-bottom: 60px solid transparent;
    border-left: 50px solid transparent;
    border-right: 60px solid #d67456;
    content: "";
    display: block;
    height: 0;
    left: -110px;
    margin-top: -25px;
    position: absolute;
    top: 50%;
    width: 0;
    z-index: 3;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-8 .njba-testimonial-body-left::after {
<?php if( $settings->layout_4_content_bg ) { ?> border-right-color: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?>
}

<?php } ?>
<?php  if($settings->show_quote === 'yes'){ ?>
.njba-testimonial.layout-8 .njba-testimonial-body-left p:before {
    color: #fff;
    font-size: 80px;
    left: 80px;
    opacity: 0.2;
    position: absolute;
    top: 60px;
    content: "";
    font-family: FontAwesome;
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-8 .njba-testimonial-body-left p:before {
<?php if( $settings->quote_sign_color ) { ?> color: <?php echo '#'.$settings->quote_sign_color; ?>;
<?php } ?>
}

<?php } ?>
<?php } ?>
<?php if( $settings->testimonial_layout == 9 ) { ?>
.fl-node-<?php echo $id; ?> .njba-testimonial.layout-9 .njba-testimonial-body-quote-box {
<?php if( $settings->layout_4_content_bg ) { ?> background: <?php echo '#'.$settings->layout_4_content_bg; ?>;
<?php } ?> <?php if( $settings->quote_box_rotate ) { ?> transform: rotate(<?php echo $settings->quote_box_rotate; ?>deg);
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-testimonial.layout-9 .njba-testimonial-quote-box-content {
<?php if( $settings->quote_sign_bg_color ) { ?> background: <?php echo '#'.$settings->quote_sign_bg_color; ?>;
<?php } ?> <?php if( $settings->quote_boxcontent_rotate ) { ?> transform: rotate(<?php echo $settings->quote_boxcontent_rotate; ?>deg);
<?php } ?>
}

<?php } ?>
@media (max-width: 1169px) {
<?php  if($settings->show_quote === 'yes'){ ?>
    .njba-testimonial.layout-7 .njba-testimonial-body-left p::before {
        left: 50px;
    }

<?php } ?>
}

@media (max-width: 990px) {
<?php  if($settings->show_quote === 'yes'){ ?>
    .njba-testimonial.layout-7 .njba-testimonial-body-left p::before {
        font-size: 40px;
        left: 10px;
        top: 50px;
    }

    .njba-testimonial.layout-8 .njba-testimonial-body-left p::before {
        font-size: 40px;
        left: 10px;
        top: 50px;
    }

<?php } ?>
}

<?php if($global_settings->responsive_enabled) { // Global Setting If started ?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-testimonial-heading h1 {
    <?php if( isset($settings->heading_font_size['medium'] )) { ?> font-size: <?php echo $settings->heading_font_size['medium'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->heading_line_height['medium']) ) { ?> line-height: <?php echo $settings->heading_line_height['medium'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-content {
    <?php if( isset($settings->text_font_size['medium'] )) { ?> font-size: <?php echo $settings->text_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-title {
    <?php if( isset($settings->title_font_size['medium'] )) { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->title_line_height['medium']) ) { ?> line-height: <?php echo $settings->title_line_height['medium'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-sub-title {
    <?php if( isset($settings->subtitle_font_size['medium'] )) { ?> font-size: <?php echo $settings->subtitle_font_size['medium'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->subtitle_line_height['medium']) ) { ?> line-height: <?php echo $settings->subtitle_line_height['medium'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-review .rating i.fa {
    <?php if( isset($settings->rate_font_size['medium']) ) { ?> font-size: <?php echo $settings->rate_font_size['medium'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->rate_line_height['medium']) ) { ?> line-height: <?php echo $settings->rate_line_height['medium'].'px'; ?>;<?php } ?>
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-testimonial-heading h1 {
    <?php if( isset($settings->heading_font_size['small'] )) { ?> font-size: <?php echo $settings->heading_font_size['small'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->heading_line_height['small']) ) { ?> line-height: <?php echo $settings->heading_line_height['small'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-content {
    <?php if( isset($settings->text_font_size['small'] )) { ?> font-size: <?php echo $settings->text_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-title {
    <?php if( isset($settings->title_font_size['small'] )) { ?> font-size: <?php echo $settings->title_font_size['small'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->title_line_height['small']) ) { ?> line-height: <?php echo $settings->title_line_height['small'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-testimonial-sub-title {
    <?php if( isset($settings->subtitle_font_size['small'] )) { ?> font-size: <?php echo $settings->subtitle_font_size['small'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->subtitle_line_height['small']) ) { ?> line-height: <?php echo $settings->subtitle_line_height['small'].'px'; ?>;<?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-review .rating i.fa {
    <?php if( isset($settings->rate_font_size['small'] )) { ?> font-size: <?php echo $settings->rate_font_size['small'].'px'; ?>;
    <?php } ?>
    <?php if( isset($settings->rate_line_height['small']) ) { ?> line-height: <?php echo $settings->rate_line_height['small'].'px'; ?>;<?php } ?>
    }
}

<?php } //die();?>
