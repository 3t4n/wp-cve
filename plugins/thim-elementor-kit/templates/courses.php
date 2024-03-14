<?php
use Elementor\Icons_Manager;

if ( ! isset( $request ) || ! isset( $courses ) ) {
	return;
}

$settings         = $request['argsWidget'];
$class            = 'thim-ekits-course';
$class_inner      = 'thim-ekits-course__inner';
$class_item       = 'thim-ekits-course__item';
$hiden_nav_mobile = '';

if ( isset( $settings['course_skin'] ) && $settings['course_skin'] == 'slider' ) {
	$class      .= ' thim-ekits-sliders swiper-container';
	$class_inner = 'swiper-wrapper';
	$class_item .= ' swiper-slide';

	if ( $settings['slider_show_pagination'] != 'none' ) :
		$hiden_nav_mobile = ' hidden-nav-mobile';
		?>

		<div
			class="thim-slider-pagination
			<?php

			echo esc_attr( 'thim-' . $settings['slider_show_pagination'] );
			?>
			"></div>
	<?php endif; ?>

	<?php if ( $settings['slider_show_arrow'] ) : ?>
		<div class="thim-slider-nav thim-slider-nav-prev<?php echo esc_attr( $hiden_nav_mobile ); ?>">
			<?php Icons_Manager::render_icon( $settings['slider_arrows_left'], array( 'aria-hidden' => 'true' ) ); ?>
		</div>
		<div class="thim-slider-nav thim-slider-nav-next<?php echo esc_attr( $hiden_nav_mobile ); ?>">
			<?php Icons_Manager::render_icon( $settings['slider_arrows_right'], array( 'aria-hidden' => 'true' ) ); ?>
		</div>
		<?php
	endif;
}
?>
<div class="<?php echo esc_attr( $class ); ?>">
	<div class="<?php echo esc_attr( $class_inner ); ?>">
		<?php
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/global/course-base.php';
		require_once THIM_EKIT_PLUGIN_PATH . '/inc/elementor/widgets/global/list-course.php';
		$widget_course = new Elementor\Thim_Ekit_Widget_List_Course();
		global $post;

		foreach ( $courses as $course_obj ) {
			$post = get_post( $course_obj->ID );
			setup_postdata( $post );
			$widget_course->render_course( $settings, $class_item );
		}
		wp_reset_postdata();
		?>
	</div>
</div>
