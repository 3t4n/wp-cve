<?php if ($this->content && '' !== $this->content): ?>
	<div class="wpdt-image-card-carousel"
		data-show_arrow="<?php echo esc_attr($show_arrow); ?>"
		data-show_arrow_on_hover="<?php echo esc_attr($show_arrow_on_hover); ?>"
	>
		<div class="swiper-container"
		data-order_class="<?php echo '.' . esc_attr($module_class); ?>"
		data-effect="<?php echo esc_attr($effect); ?>"
		data-show_control_dot="<?php echo esc_attr($show_control_dot); ?>"
		data-slider_loop="<?php echo esc_attr($slider_loop); ?>"
		data-autoplay="<?php echo esc_attr($autoplay); ?>"
		data-autoplay_speed="<?php echo esc_attr($autoplay_speed); ?>"
		data-transition_duration="<?php echo esc_attr($slide_transition_duration); ?>"
		data-pause_on_hover="<?php echo esc_attr($pause_on_hover); ?>"
		data-slides_per_view_desktop="<?php echo esc_attr($slides_per_view_values['desktop']); ?>"
		data-slides_per_view_tablet="<?php echo esc_attr($slides_per_view_values['tablet']); ?>"
		data-slides_per_view_phone="<?php echo esc_attr($slides_per_view_values['phone']); ?>"
		data-enable_coverflow_slide_shadow="<?php echo esc_attr($enable_coverflow_slide_shadow); ?>"
		data-coverflow_rotate="<?php echo esc_attr($coverflow_rotate); ?>"
		data-coverflow_depth="<?php echo esc_attr($coverflow_depth); ?>"
		data-space_between_desktop="<?php echo esc_attr($space_between_desktop); ?>"
		data-space_between_tablet="<?php echo esc_attr($space_between_tablet); ?>"
		data-space_between_phone="<?php echo esc_attr($space_between_phone); ?>"
		data-show_arrow="<?php echo esc_attr($show_arrow); ?>"
		data-show_arrow_on_hover="<?php echo esc_attr($show_arrow_on_hover); ?>"
		data-initial_slide="<?php echo esc_attr($initial_slide); ?>"
		data-centered_slides="<?php echo esc_attr($centered_slides); ?>"
		>
		<div class="swiper-wrapper">
			<?php echo et_core_intentionally_unescaped($this->content, 'html') ?>
		</div> <!-- swiper-wrapper -->
	</div> <!-- swiper-container -->

	<?php if ('on' == $show_arrow): ?>
		<div class="swiper-buttton-container" data-vertical-position='<?php echo esc_attr($arrow_vertical_position); ?>' data-horizontal-position='<?php echo esc_attr($arrow_horizontal_position); ?>'>
			<?php
                echo et_core_intentionally_unescaped($this->container['carousel_nav']->previous_button_html(), 'html');
                echo et_core_intentionally_unescaped($this->container['carousel_nav']->next_button_html(), 'html');
            ?>
		</div>
	<?php endif?>
<?php if ('on' == $show_control_dot): ?>
		<div class="swiper-pagination" data-position="<?php echo esc_attr($pagination_position); ?>"></div>
	<?php endif?>
</div>
<?php else: ?>
	<div>
		<h1><?php echo esc_html__('No Image Carousel Cards Found!', 'ultimate-carousel-for-divi'); ?></h1>
		<p><?php echo esc_html__('Please add image carousel cards and setup the data', 'ultimate-carousel-for-divi'); ?></p>
	</div>
<?php endif?>
