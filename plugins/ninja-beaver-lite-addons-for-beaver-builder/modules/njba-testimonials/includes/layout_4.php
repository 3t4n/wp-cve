<?php
for ( $i = 0; $i < $number_testimonials; $i ++ ) :
	$testimonials = $settings->testimonials[ $i ];
	?>
    <div class="njba-testimonial-body layout_<?php echo $settings->testimonial_layout; ?>">
        <div class="njba-testimonial-body-left">
			<?php $module->njba_profile_image_render( $i ); ?>
        </div>
        <div class="njba-testimonial-body-right">
			<?php $module->njba_profile_content( $i ); ?>
			<?php $module->njba_profile_name( $i ); ?>
			<?php $module->njba_profile_designation( $i ); ?>
			<?php $module->njba_profile_ratings( $i ); ?>
        </div>
    </div>
<?php endfor; ?>
