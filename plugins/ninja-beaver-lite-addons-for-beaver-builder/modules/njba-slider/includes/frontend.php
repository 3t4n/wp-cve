<?php
$number_photos = count( $settings->photos );
?>
<?php if ( $settings->dot == 2 ){ ?>
<div class="njba-slider-main thumbnail-pager">
	<?php }
	else
	{
	?>
    <div class="njba-slider-main">
		<?php
		} ?>
        <div class="njba-slider-body" itemscope itemtype="http://schema.org/ImageObject">
            <div class="bxslider">
				<?php

				for ( $i = 0; $i < $number_photos; $i ++ ) {
					$img_carousel = $settings->photos[ $i ];
					$attachment   = get_post( $img_carousel->photo );
					$cta_settings = array(
						'cta_layout'                  => $img_carousel->cta_layout,
						'main_title'                  => $img_carousel->main_title,
						'sub_title'                   => $img_carousel->sub_title,
						'main_title_tag'              => $img_carousel->main_title_tag,
						'separator_select'            => $img_carousel->separator_select,
						'separator_type'              => $img_carousel->separator_type,
						'icon_position'               => $img_carousel->icon_position,
						'separator_icon_text'         => $img_carousel->separator_icon_text,
						'separator_image_select_src'  => $img_carousel,
						'separator_text_select'       => $img_carousel->separator_text_select,
						'heading_title_alignment'     => $img_carousel->heading_title_alignment,
						'heading_sub_title_alignment' => $img_carousel->heading_sub_title_alignment,
						//Button text
						'button_text'                 => $img_carousel->button_text,
						//Button Link
						'link'                        => $img_carousel->link,
						'link_target'                 => $img_carousel->link_target,
						// Icon
						'buttton_icon_select'         => $img_carousel->buttton_icon_select,
						'button_font_icon'            => $img_carousel->button_font_icon,
						'button_icon_aligment'        => $img_carousel->button_icon_aligment,
					);

					?>
                    <div class="njba-slide-id-<?php echo $i ?>">
                        <div class="njba-slide-box-img">
							<?php if ( $img_carousel->photo !== '' ) {
								$slider_image = wp_get_attachment_image_src( $img_carousel->photo );
								if ( ! is_wp_error( $slider_image ) ) {
									$photo_src    = $slider_image[0];
									$photo_width  = $slider_image[1];
									$photo_height = $slider_image[2];
								} ?>
                                <img src="<?php echo $img_carousel->photo_src; ?>" width="<?php echo $photo_width; ?>" height="<?php echo $photo_height; ?>"
                                     class="njba-slider-image-responsive">
							<?php } ?>
                        </div>
						<?php if ( $img_carousel->select_option == 1 ) { ?>
                            <div class="njba-cta-box-main-<?php echo $img_carousel->cta_layout; ?>">
                                <div class="njba-cta-box-body">
                                    <div class="njba-cta-box-content ">
										<?php FLBuilder::render_module_html( 'njba-advance-cta', $cta_settings ); ?>
                                    </div>
                                </div>
                            </div>
						<?php } ?>
                    </div>

				<?php } ?>
            </div><!--bxslider-->
			<?php if ( $settings->dot == 2 ) { ?>
                <div class="bx-thumbnail-pager_section">
                    <i class="pager-toggle fa fa-chevron-down" aria-hidden="true"></i>

                    <div id="bx-pager-<?php echo $module->node; ?>" class="bx-thumbnail-pager">
                        <!---->
						<?php

						for ( $i = 0; $i < $number_photos; $i ++ ) {
							$img_carousel = $settings->photos[ $i ];
							$attachment   = get_post( $img_carousel->photo );

							?>
                            <a data-slide-index="<?php echo $i ?>" href="" class="bx-pager-link "><img src="<?php echo $img_carousel->photo_src; ?>"/></a>

						<?php } ?>
                    </div><!--bx-pager-->

                </div>
			<?php } ?>
        </div><!--njba-slider-body-->
    </div><!--njba-slider-main-->
