<div class="xpro-dynamic-slider-wrapper xpro-slider-orientation-<?php echo esc_attr($settings->slider_orientation); ?> xpro-dynamic-slider-dots-<?php echo esc_attr( $settings->dots_orientation ); ?>-<?php echo esc_attr( $settings->dots_layout ); ?> xpro-dynamic-slider-dots-position-<?php echo esc_attr( $settings->dots_position ); ?>">
	<div class="xpro-dynamic-slider xpro-dynamic-slider-animation-<?php echo esc_attr( $settings->slide_animation ); ?>">
		<?php
		$slides_count = count( $settings->slides_items );
		for ( $i = 0; $i < $slides_count; $i++ ) { ?>
        <div class="slick-slide">
            <?php
		     $slide_item = $settings->slides_items[$i];
            if ( (isset($slide_item->content_row) && $slide_item->content_row && $slide_item->content_row != 'no_template') || (isset($slide_item->content_bb_row) && $slide_item->content_bb_row && $slide_item->content_bb_row != 'no_template') ) {
                FLBuilder::render_query(
                    array(
                        'post_type' => $slide_item->template_type == 'rows' ? 'fl-builder-template' :  'xpro_bb_templates',
                        'p'         => $slide_item->template_type == 'rows' ? $slide_item->content_bb_row :  $slide_item->content_row,
                    )
                );
            }else{?>
                <div class="xpro-dynamic-slider-content-area-blank">
                    <p><?php echo __('Please select any template.'); ?></p>
                </div>
            <?php  } ?>
        </div>
        <?php } ?>
	</div>
    <?php if ( $settings->slider_nav ) : ?>
        <div class="xpro-dynamic-slider-navigation xpro-dynamic-slider-navigation-position-<?php echo esc_attr( $settings->nav_position ); ?> xpro-dynamic-slider-navigation-<?php echo esc_attr( $settings->nav_orientation ); ?>-<?php echo esc_attr( $settings->nav_layout ); ?>"></div>
    <?php endif; ?>
</div>