<?php
for ( $i = 0; $i < $number_testimonials; $i ++ ) :
	$testimonials = $settings->testimonials[ $i ];
	?>
    <div class="njba-testimonial-body layout_<?php echo $settings->testimonial_layout; ?>">
        <div class="njba-testimonial-body-inner">
            <div class="njba-testimonial-body-quote-box">
                <div class="njba-testimonial-quote-box-content">
					<?php $module->njba_profile_image_render( $i ); ?>
					<?php $module->njba_profile_name( $i ); ?>
					<?php $module->njba_profile_designation( $i ); ?>
					<?php $module->njba_profile_ratings( $i ); ?>
                    <div class="njba-testimonial-content-warpper">
						<?php $module->njba_left_quotesign(); ?>
						<?php $module->njba_profile_content( $i ); ?>
						<?php $module->njba_right_quotesign(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endfor; ?>
