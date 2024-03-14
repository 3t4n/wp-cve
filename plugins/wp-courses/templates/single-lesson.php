<?php get_header(); ?>

<?php $user_id = get_current_user_id(); ?>
<?php $logged_in = is_user_logged_in(); ?>
<?php $lesson_id = get_the_ID(); ?>
<?php $course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id); ?>

<div class="wpc-main">
	<div class="wpc-wrapper">

		<?php include 'template-parts/lesson-toolbar.php'; ?>

		<?php while( have_posts() ){
			the_post(); ?>

				<div id="wpc-courses-ajax" class="wpc-flex-container">
					<div class="wpc-flex-container wpc-flex-full">
						<div id="wpc-content" class="wpc-flex-content">
							<div class="wpc-flex-12 wpc-flex-no-margin">
								<div class="wpc-material wpc-material-content" id="wpc-material-content">								
									<?php echo wp_kses(wpc_get_breadcrumb($lesson_id, $course_id), 'post'); ?>
									<h1 class="wpc-h1 wpc-content-title"><?php the_title(); ?></h1>
									<div><?php the_content(); ?></div>
									<?php echo wp_kses(wpc_get_comments_template($lesson_id), 'post'); ?>
								</div>
							</div>
						</div> <!-- flex container -->
						<div id="wpc-left-sidebar" class="wpc-flex-sidebar">
							<?php if ($course_id != 'none') { ?>
						    	<?php echo wp_kses(wpc_get_classic_lesson_navigation($course_id, get_current_user_id()), 'post'); ?>
					    	<?php } ?>
						</div>
					</div>
				</div>

		<?php } ?>
	</div>
</div>

<?php get_footer(); ?>

<script type="text/javascript">

jQuery(document).ready(function($){
	new WPC_UI({
		loggedIn 				: <?php echo $logged_in === true ? 'true' : 'false'; ?>,
		userID 					: <?php echo $user_id === 0 ? 'false' : $user_id; ?>,
		onLoad 					: false,
		fixedToolbar 			: <?php echo get_option('wpc_fix_toolbar_top') == 'true' ? 'true' : 'false'; ?>,
		fixedToolbarOffset 		: <?php echo get_option('wpc_fixed_toolbar_offset') == 'true' ? 'true' : 'false'; ?>,
		adminBar 				: <?php echo is_admin_bar_showing() === true ? 'true' : 'false'; ?>,
		ajaxLinks				: false,
	})
	UI_Controller.resizeIframe();
});

</script>