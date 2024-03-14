<div class="skwp-user-area skwp-clearfix">
	<?php 
	global $wp;
	global $wpdb;
	$current_id = get_current_user_id();
	$user_info = get_user_meta($current_id);
	$first_name = $user_info["first_name"][0];
	$last_name = $user_info["last_name"][0];

	$user_name = $first_name .' '. $last_name;

	if(empty($first_name)) {
		$user_info = get_userdata($current_id);
		$user_name = $user_info->display_name;
	} ?>
	<div class="skwp-user-img">
		<?php 
		$user_img = wp_get_attachment_image_src( get_user_meta($current_id,'_user_img', array('80','80'), true, true ));
		if(!empty($user_img)) { ?>
		<img class="profile_img" src="<?php echo esc_url($user_img[0]); ?>" alt="<?php echo esc_attr($user_name); ?>">
		<?php }
		else {
			echo get_avatar( $current_id, 60 );
		} ?>
	</div>
	<div class="skwp-user-name-area">
		<h5 class="skwp-user"><?php echo esc_html( $user_name ); ?></h5>
		<a href="<?php echo esc_url(home_url('edit_profile')); ?>" class="skwp-edit-profile-link skwp-prof-side">
			<i class="sakolawp2-icon-edit"></i>
		</a>
		<a href="<?php echo esc_url(wp_logout_url( home_url() )); ?>" class="skwp-edit-profile-logout skwp-prof-side">
			<i class="sakolawp2-icon-logout"></i>
		</a>
	</div>
</div>
<div class="skwp-menu-wrap">
	<div class="skwp-menu-item <?php if($wp->request === "myaccount") echo esc_attr('active'); ?>">
        <a href="<?php echo esc_url( home_url( '/myaccount' ) ); ?>">
        	<i class="sakolawp-icon sakolawp-icon-web-page-home"></i>
            <?php echo esc_html__('My Account', 'sakolawp'); ?>
        </a>
    </div>
    <?php 
	$section_teached = $wpdb->get_row( "SELECT teacher_id FROM {$wpdb->prefix}sakolawp_section WHERE teacher_id = $current_id");
	$section_teached = $wpdb->num_rows;

	if($section_teached > 0) { ?>
	<div class="skwp-menu-item <?php if($wp->request === "manage_attendance" || $wp->request === "manage_attendance_view" || $wp->request === "attendance_report" ||$wp->request === "report_attendance_view") echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/manage_attendance' ) ); ?>">
			<i class="sakolawp-icon sakolawp-icon-biodata"></i>
			<?php echo esc_html__('Attendances', 'sakolawp'); ?>
		</a>
	</div>
	<?php } ?>
	<div class="skwp-menu-item <?php if($wp->request === "my_routines") echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/my_routines' ) ); ?>">
				<i class="sakolawp-icon sakolawp-icon-calendar"></i>
			<?php echo esc_html__('My Routines', 'sakolawp'); ?>
		</a>
	</div>
	<div class="skwp-menu-item <?php if($wp->request === "questions_bank" || $wp->request === "edit_bank_question" || $wp->request === "view_bank_question" || $wp->request === "add_new_question") echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/questions_bank' ) ); ?>">
				<i class="sakolawp-icon sakolawp-icon-input"></i>
			<?php echo esc_html__('Questions Bank', 'sakolawp'); ?>
		</a>
	</div>
	<div class="skwp-menu-item <?php if($wp->request === "online_exams" || $wp->request === "examroom" || $wp->request === "exam_questions" || $wp->request === "exam_results" || $wp->request === "view_exam_result" || $wp->request === "exam_edit" || $wp->request === "view_exam_question") echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/online_exams' ) ); ?>">
			<i class="sakolawp-icon sakolawp-icon-blogging"></i>
			<?php echo esc_html__('Online Exams', 'sakolawp'); ?>
		</a>
	</div>
	<div class="skwp-menu-item <?php if($wp->request === "marks") echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/marks' ) ); ?>">
			<i class="sakolawp-icon sakolawp-icon-checkmark"></i>
			<?php echo esc_html__('Marks', 'sakolawp'); ?>
		</a>
	</div>
	<div class="skwp-menu-item <?php if($wp->request === "news_post" || 'sakolawp-news' == get_post_type()) echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/news_post' ) ); ?>">
			<i class="sakolawp-icon sakolawp-icon-newspaper"></i>
			<?php echo esc_html__('News', 'sakolawp'); ?>
		</a>
	</div>
	<div class="skwp-menu-item <?php if($wp->request === "event_post" || 'sakolawp-event' == get_post_type()) echo esc_attr('active'); ?>">
		<a href="<?php echo esc_url( home_url( '/event_post' ) ); ?>">
				<i class="sakolawp-icon sakolawp-icon-printed"></i>
			<?php echo esc_html__('Event', 'sakolawp'); ?>
		</a>
	</div>
</div>