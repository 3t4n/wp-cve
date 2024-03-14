<?php 

	get_header();

	$course_id = get_the_ID();
	$logged_in = is_user_logged_in();
	$user_id = get_current_user_id();

?>
<div class="wpc-main">
	<div class="wpc-wrapper">
		<div class="wpc-flex-container">
			<div class="wpc-material wpc-material-content wpc-fade" style="border-right: 0;">
				<?php wpc_course($course_id, false, 'single-course'); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function($){

		UI_Controller.resizeIframe();

	});

</script>

<?php get_footer(); ?>