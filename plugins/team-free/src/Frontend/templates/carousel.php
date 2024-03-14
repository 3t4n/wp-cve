<?php
/**
 * Carousel layout.
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates
 * @since 2.1.0
 */

use ShapedPlugin\WPTeam\Frontend\Helper;

$carousel_accessibility                           = isset( get_option( '_sptp_settings' )['carousel_accessibility'] ) ? get_option( '_sptp_settings' )['carousel_accessibility'] : '';
$carousel_accessibility_enabled                   = ( isset( $carousel_accessibility['accessibility'] ) && ( $carousel_accessibility['accessibility'] ) ) ? 'true' : 'false';
$carousel_accessibility_prev_slide_message        = isset( $carousel_accessibility['prev_slide_message'] ) ? $carousel_accessibility['prev_slide_message'] : '';
$carousel_accessibility_next_slide_message        = isset( $carousel_accessibility['next_slide_message'] ) ? $carousel_accessibility['next_slide_message'] : '';
$carousel_accessibility_first_slide_message       = isset( $carousel_accessibility['first_slide_message'] ) ? $carousel_accessibility['first_slide_message'] : '';
$carousel_accessibility_last_slide_message        = isset( $carousel_accessibility['last_slide_message'] ) ? $carousel_accessibility['last_slide_message'] : '';
$carousel_accessibility_pagination_bullet_message = isset( $carousel_accessibility['pagination_bullet_message'] ) ? $carousel_accessibility['pagination_bullet_message'] : '';
?>
<div id="<?php echo esc_html( 'sptp-' . $generator_id ); ?>"  class="sp-team sptp-carousel sptp-section <?php echo 'sptp-' . esc_html( $page_link_type ); ?> <?php echo esc_attr( $position ); ?>">
	<?php
	Helper::sptp_section_title( $main_section_title, $generator_id, $settings );
	if ( ! empty( $filter_members ) ) :
		Helper::sptp_preloader( $preloader );
		?>
		<div class="swiper-container sptp-main-carousel <?php echo esc_html( $navigation_position ); ?>" data-carousel='{
			"speed": <?php echo esc_html( $carousel_speed ); ?>,
			"items": <?php echo esc_html( $desktop ); ?>,
			"spaceBetween": <?php echo esc_html( $margin_between_member_left ); ?>,
			"autoplay": <?php echo esc_html( $carousel_autoplay ); ?>,
			"autoplay_speed": <?php echo esc_html( $autoplay_speed ); ?>,
			"loop": <?php echo esc_html( $loop ); ?>,
			"freeMode": false,
			"autoHeight": <?php echo esc_html( $auto_height ); ?>,
			"watchOverflow": true,
			"lazy": <?php echo esc_html( $lazy_load ); ?>,
			"breakpoints": {
				"desktop": <?php echo esc_html( $desktop ); ?>,
				"laptop": <?php echo esc_html( $laptop ); ?>,
				"tablet": <?php echo esc_html( $tablet ); ?>,
				"mobile": <?php echo esc_html( $mobile ); ?>,
				"desktop_pSlide": <?php echo esc_html( $member_per_slide['desktop'] ); ?>,
				"laptop_pSlide": <?php echo esc_html( $member_per_slide['laptop'] ); ?>,
				"tablet_pSlide": <?php echo esc_html( $member_per_slide['tablet'] ); ?>,
				"mobile_pSlide": <?php echo esc_html( $member_per_slide['mobile'] ); ?>
			},
			"stop_onhover": <?php echo esc_html( $stop_onhover ); ?>,
			"mouse_wheel": <?php echo esc_html( $slider_mouse_wheel ); ?>,
			"allowTouchMove": <?php echo esc_html( $touch_swipe ); ?>,
			"simulateTouch": <?php echo esc_html( $slider_draggable ); ?>,
			"freeMode": <?php echo esc_html( $free_mode ); ?>,
			"enabled": <?php echo esc_html( $carousel_accessibility_enabled ); ?>,
			"prevSlideMessage": "<?php echo esc_html( $carousel_accessibility_prev_slide_message ); ?>",
			"nextSlideMessage": "<?php echo esc_html( $carousel_accessibility_next_slide_message ); ?>",
			"firstSlideMessage": "<?php echo esc_html( $carousel_accessibility_first_slide_message ); ?>",
			"lastSlideMessage": "<?php echo esc_html( $carousel_accessibility_last_slide_message ); ?>",
			"paginationBulletMessage": "<?php echo esc_html( $carousel_accessibility_pagination_bullet_message ); ?>"
		}'>
		<div class="swiper-wrapper <?php echo esc_html( $position ); ?>">
			<?php foreach ( $filter_members as $member ) : ?>
				<div class="swiper-slide">
					<?php include Helper::sptp_locate_template( 'member.php' ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$show_pagination           = isset( $settings['carousel_pagination_group']['carousel_pagination'] ) ? $settings['carousel_pagination_group']['carousel_pagination'] : true;
		$pagination_hide_on_mobile = $show_pagination && isset( $settings['carousel_pagination_group']['pagination_hide_on_mobile'] ) && $settings['carousel_pagination_group']['pagination_hide_on_mobile'] ? ' sptp_pagination_hide_on_mobile' : '';
		if ( $show_pagination && ( count( $filter_members ) > $desktop ) ) :
			?>
			<div class="sptp-pagination swiper-pagination <?php echo esc_attr( $pagination_hide_on_mobile ); ?>"></div>
		<?php endif; ?>
		<?php
		$show_navigation           = isset( $settings['carousel_navigation_data']['carousel_navigation'] ) ? $settings['carousel_navigation_data']['carousel_navigation'] : true;
		$navigation_hide_on_mobile = $show_navigation && isset( $settings['carousel_navigation_data']['nav_hide_on_mobile'] ) && $settings['carousel_navigation_data']['nav_hide_on_mobile'] ? ' sptp_nav_hide_on_mobile' : '';
		if ( $show_navigation && ( count( $filter_members ) > $desktop ) ) :
			?>
			<div class="sptp-button-next swiper-button-next <?php echo esc_html( $navigation_position ); ?> <?php echo esc_attr( $navigation_hide_on_mobile ); ?>"><i class="fa fa-angle-right"></i></div>
			<div class="sptp-button-prev swiper-button-prev <?php echo esc_html( $navigation_position ); ?> <?php echo esc_attr( $navigation_hide_on_mobile ); ?>"><i class="fa fa-angle-left"></i></div>
		<?php endif; ?>
	</div>
<?php endif; ?>
</div>
