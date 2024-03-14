<?php get_header(); ?>

<?php

$logged_in = is_user_logged_in();
$user_id = get_current_user_id();

$categories = wpc_get_course_category_list(true);
$content_class = $categories === false ? 'wpc-flex-content-fw' : '';

?>

<div class="wpc-main">
	<div class="wpc-flex-container wpc-wrapper">

		<?php include 'template-parts/course-filters.php'; ?>

		<div id="wpc-courses-classic" class="wpc-flex-container">
			<?php if($categories !== false){ ?>
				<div id="wpc-left-sidebar" class="wpc-flex-sidebar" style="position: relative; top: -1px;">
					<?php echo wp_kses($categories, 'post'); ?>
				</div>
			<?php } ?>
			<div id="wpc-content" class="wpc-flex-content <?php echo esc_attr($content_class); ?>">
				<div class="wpc-flex-container wpc-flex-cards-wrapper">
					<?php if(have_posts()){
						while(have_posts()){
							the_post(); 

								$course_id = get_the_ID();
								$difficulty = wp_get_post_terms($course_id, 'course-difficulty');
								$difficulty = !empty($difficulty) ? $difficulty[0]->name : false;

								$course_video = wpc_get_video( $course_id, 'course' );

								$first_lesson_id = (int) wpc_get_course_first_lesson_id($course_id);
								$first_lesson_link = get_the_permalink( $first_lesson_id );
								$teachers = wpc_get_connected_teachers($course_id);

								$show_progress_bar = get_option('wpc_show_completed_lessons');

								?>

								<div class="wpc-flex-3 wpc-material wpc-single-course-archive">

									<h3 class="wpc-material-heading wpc-h3"><?php the_title(); ?></h3>

									<?php if($course_video != false && $course_video !== '') { ?>
										<div class="wpc-vid-wrapper"><?php echo $course_video; ?></div>
									<?php } elseif(has_post_thumbnail()) { ?>
										<div class="wpc-img-wrapper"><?php echo the_post_thumbnail('medium'); ?></div>
									<?php } ?>

									<div class="wpc-material-tools">
										<?php do_action('wpc_before_course_details_button'); ?>
										<button type="button" class="wpc-btn wpc-btn-sm wpc-btn-solid wpc-btn-round wpc-load-course" data-id="<?php echo (int) $course_id; ?>" data-ajax="false"><?php _e('Details', 'wp-courses'); ?></button>
										<?php do_action('wpc_after_course_details_button'); ?>
									</div>

									<p class="wpc-material-text wpc-course-archive-excerpt"><?php echo get_the_excerpt(); ?></p>

									<?php $button = '<a class="wpc-btn" href="' . esc_url(add_query_arg( 'course_id', $course_id, $first_lesson_link)) . '"><i class="fa-solid fa-circle-play"></i> ' . __('Start Course', 'wp-courses') . '</a>'; ?>

									<?php $button = apply_filters('wpc_start_course_button', $button, $course_id); ?>

									<?php echo wp_kses($button, 'post'); ?>

									<?php if( !in_array(-1, $teachers) || $difficulty !== false || $logged_in) { ?>

									<div class="wpc-material-meta wpc-flex-container">

										<?php if($logged_in) { ?>
											<div class="wpc-material-meta-item wpc-flex-12"><?php echo wp_kses( wpc_get_progress_bar($course_id, $user_id, 0), 'post' ); ?></div>
										<?php } ?>

										<?php if($logged_in && $show_progress_bar == true) { ?>
											<div class="wpc-material-meta-item wpc-flex-12"><?php echo wp_kses( wpc_get_progress_bar($course_id, $user_id, 1, true, '#4f646d'), 'post' ); ?></div>
										<?php } ?>

										<?php if(!in_array(-1, $teachers)) { ?>
											<div class="wpc-material-meta-item wpc-flex-6 wpc-center">
												<i class="fa fa-users wpc-material-meta-icon"></i><br> <span class="wpc-meta-key"><?php echo count($teachers) > 1 ? __('Teachers', 'wp-courses') :  __('Teacher', 'wp-courses'); ?></span><br>
												<?php foreach($teachers as $teacher) { ?>
													<div class="wpc-meta-value">
														<div class="wpc-load-teacher wpc-ajax-link" data-id="<?php echo (int) $teacher; ?>"><?php echo get_the_title($teacher); ?></div>
													</div>
												<?php } ?>
											</div>
										<?php } ?>

										<?php if($difficulty !== false) { ?>
											<div class="wpc-material-meta-item wpc-flex-6 wpc-center">
												<i class="fa-solid fa-temperature-half wpc-material-meta-icon"></i><br> <span class="wpc-meta-key"><?php _e('Difficulty', 'wp-courses'); ?></span><br>
												<div class="wpc-meta-value"><?php echo esc_html($difficulty); ?></div>
											</div>
										<?php } ?>

									</div>

								<?php } ?>

								</div>

						<?php } 
					} else { ?>
						<div id="wpc-results-empty" class="wpc-flex-12"><i class="fa-regular fa-face-frown"></i> <?php _e('No results', 'wp-courses'); ?></div>
					<?php } ?>
				</div>
				<div id="wpc-toolbar-bottom" class="wpc-flex-toolbar wpc-course-archive-pagination">
					<?php echo paginate_links(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function($){

		new WPC_UI({
			loggedIn 			: <?php echo $logged_in === true ? 'true' : 'false'; ?>,
			userID 				: <?php echo $user_id === 0 ? 'false' : $user_id; ?>,
			onLoad 				: false,
			fixedToolbar 		: <?php echo get_option('wpc_fix_toolbar_top') == 'true' ? 'true' : 'false'; ?>,
			fixedToolbarOffset 	: <?php echo get_option('wpc_fixed_toolbar_offset') == 'true' ? 'true' : 'false'; ?>,
			adminBar 			: <?php echo is_admin_bar_showing() === true ? 'true' : 'false'; ?>,
			ajaxLinks			: false,
		});

		UI_Controller.resizeIframe();

	});

</script>

<?php get_footer(); ?>