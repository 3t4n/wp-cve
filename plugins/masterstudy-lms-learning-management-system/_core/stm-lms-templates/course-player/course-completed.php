<?php
/**
 * @var array $user
 * @var array $settings
 * @var int $post_id
 * @var boolean $lesson_completed
 * @var boolean $dark_mode
 */

$total_progress = STM_LMS_Lesson::get_total_progress( $user['id'] ?? null, $post_id );
$course_passed  = false;

if ( ! empty( $total_progress['course']['progress_percent'] ) ) {
	$course_passed = $total_progress['course']['progress_percent'] >= ( $settings['certificate_threshold'] ?? 70 );
}

wp_enqueue_style( 'masterstudy-course-player-course-completed' );
wp_enqueue_script( 'masterstudy-course-player-course-completed' );
wp_localize_script(
	'masterstudy-course-player-course-completed',
	'course_completed',
	array(
		'course_id' => $post_id,
		'completed' => $lesson_completed,
		'nonce'     => wp_create_nonce( 'stm_lms_total_progress' ),
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
	)
);
wp_enqueue_script( 'jspdf' );
wp_enqueue_script( 'masterstudy-course-player-certificate' );
wp_localize_script(
	'masterstudy-course-player-certificate',
	'course_certificate',
	array(
		'nonce'    => wp_create_nonce( 'stm_get_certificate' ),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	)
);
?>
<div id="masterstudy-course-player-course-completed" class="masterstudy-course-player-course-completed" style="display: none;">
	<div class="masterstudy-course-player-course-completed__info">
		<span class="masterstudy-course-player-course-completed__info-close stmlms-close"></span>
		<div class="masterstudy-course-player-course-completed__info-loading">
			<?php echo esc_html__( 'Loading your statistics', 'masterstudy-lms-learning-management-system' ); ?>
		</div>
		<div class="masterstudy-course-player-course-completed__info-success">
			<div class="masterstudy-course-player-course-completed__opportunities">
				<?php
				if ( ! STM_LMS_Options::get_option( 'finish_popup_image_disable', false ) ) :
					$failed_image  = STM_LMS_URL . 'assets/icons/lessons/course-completed-negative.png';
					$success_image = STM_LMS_URL . 'assets/icons/lessons/course-completed-positive.svg';

					if ( ! empty( $settings['finish_popup_image_failed'] ) ) {
						$custom_failed_image_url = wp_get_attachment_image_url( $settings['finish_popup_image_failed'] );
						if ( ! empty( $custom_failed_image_url ) ) {
							$failed_image = $custom_failed_image_url;
						}
					}

					if ( ! empty( $settings['finish_popup_image_success'] ) ) {
						$custom_success_image_url = wp_get_attachment_image_url( $settings['finish_popup_image_success'] );
						if ( ! empty( $custom_success_image_url ) ) {
							$success_image = $custom_success_image_url;
						}
					}
					?>
					<div class="masterstudy-course-player-course-completed__opportunities-icon">
						<?php if ( $course_passed ) : ?>
							<img src="<?php echo esc_url( $success_image ); ?>" width="80" height="80" alt="<?php echo esc_html__( 'You have successfully completed the course', 'masterstudy-lms-learning-management-system' ); ?>">
						<?php else : ?>
							<img src="<?php echo esc_url( $failed_image ); ?>" width="80" height="80" alt="<?php echo esc_html__( 'You have NOT completed the course', 'masterstudy-lms-learning-management-system' ); ?>">
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="masterstudy-course-player-course-completed__opportunities-statistic">
					<span class="masterstudy-course-player-course-completed__opportunities-label"><?php echo esc_html__( 'Your score', 'masterstudy-lms-learning-management-system' ); ?></span>
					<span class="masterstudy-course-player-course-completed__opportunities-percent"></span>
				</div>
			</div>

			<?php if ( $course_passed ) : ?>
			<div class="masterstudy-course-player-course-completed__info-message"><?php echo esc_html__( 'You have successfully completed the course', 'masterstudy-lms-learning-management-system' ); ?></div>
			<?php else : ?>
			<div class="masterstudy-course-player-course-completed__info-message"><?php echo esc_html__( 'You have NOT completed the course', 'masterstudy-lms-learning-management-system' ); ?></div>
			<?php endif; ?>
			<h2 class="masterstudy-course-player-course-completed__info-title"></h2>
			<div class="masterstudy-course-player-course-completed__curiculum-statistic">
				<?php
				$curriculums = ms_plugin_curriculum_list();
				foreach ( $curriculums as $curriculum ) :
					?>
				<div class="masterstudy-course-player-course-completed__curiculum-statistic-item masterstudy-course-player-course-completed__curiculum-statistic-item_type-<?php echo esc_attr( $curriculum['type'] ); ?>">
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/icons/lessons/' . $curriculum['icon'] . '.svg' ); ?>" width="<?php echo esc_attr( $curriculum['icon_width'] ); ?>" height="<?php echo esc_attr( $curriculum['icon_height'] ); ?>">
					<span><?php echo esc_html( $curriculum['label'] ); ?> <strong><span class="masterstudy-course-player-course-completed__curiculum-statistic-item_completed"></span>/<span class="masterstudy-course-player-course-completed__curiculum-statistic-item_total"></span></strong></span>
				</div>
				<?php endforeach; ?>
			</div>
			<div class="masterstudy-course-player-course-completed__buttons">
				<?php
				if ( class_exists( 'STM_LMS_Certificate_Builder' ) && $lesson_completed && $course_passed && masterstudy_lms_course_has_certificate( $post_id ) ) :
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title'         => __( 'Certificate', 'masterstudy-lms-learning-management-system' ),
							'type'          => '',
							'link'          => '#',
							'style'         => 'primary',
							'size'          => 'md',
							'id'            => $post_id,
							'icon_position' => '',
							'icon_name'     => '',
						)
					);
				endif;
					STM_LMS_Templates::show_lms_template(
						'components/button',
						array(
							'title'         => __( 'View course', 'masterstudy-lms-learning-management-system' ),
							'type'          => '',
							'link'          => '#',
							'style'         => 'tertiary',
							'size'          => 'md',
							'data'          => array(),
							'icon_position' => '',
							'icon_name'     => '',
						)
					);
					?>
			</div>
		</div>
	</div>
</div>
