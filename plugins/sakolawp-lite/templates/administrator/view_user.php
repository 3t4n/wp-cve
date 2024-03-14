<?php
get_header(); 
do_action( 'sakolawp_before_main_content' );  

$current_user = sanitize_text_field($_GET['tgt_id']);

$user_meta = get_userdata($current_user);
$user_roles = $user_meta->roles;

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $current_user");

if(!empty($enroll)) {
	$class_id = $enroll->class_id;
	$section_id = $enroll->section_id;

	$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
	$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");

	if(!empty($class) && !empty($section)) {
		$classes_name = esc_html($class->name) . esc_html__('-', 'sakolawp') . esc_html($section->name);
	}
	else {
		$classes_name = esc_html__('Not Assign Yet.', 'sakolawp');
	}
}
?>
<div class="view-user-page skwp-content-inner skwp-clearfix">
	<div id="post-<?php the_ID(); ?>">

	<div class="view-user-profile">
		<p class="form-username">
			<label for="first-name"><?php esc_html_e('First Name', 'sakolawp'); ?></label>
			<input class="text-input" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user ); ?>" readonly />
		</p><!-- .form-username -->
		<p class="form-username">
			<label for="last-name"><?php esc_html_e('Last Name', 'sakolawp'); ?></label>
			<input class="text-input" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user ); ?>" readonly />
		</p><!-- .form-username -->
		<p class="form-email">
			<label for="email"><?php esc_html_e('E-mail *', 'sakolawp'); ?></label>
			<input class="text-input" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user ); ?>" readonly />
		</p><!-- .form-email -->
		<p class="form-url">
			<label for="url"><?php esc_html_e('Role', 'sakolawp'); ?></label>
			<input class="text-input" type="text" id="url" value="<?php echo esc_attr($user_roles[0]); ?>" readonly />
		</p><!-- .form-url -->
		<p class="form-textarea">
			<label for="description"><?php esc_html_e('Biographical Information', 'sakolawp') ?></label>
			<textarea id="description" rows="3" cols="50" readonly><?php the_author_meta( 'description', $current_user ); ?></textarea>
		</p><!-- .form-textarea -->
		<p class="form-url">
			<label for="url"><?php esc_html_e('Current Class', 'sakolawp'); ?></label>
			<input class="text-input" type="text" id="url" value="<?php echo esc_attr($classes_name); ?>" readonly />
		</p><!-- .form-url -->

		<div class="skwp-form-group">
			<label class="col-form-label" for=""> <?php esc_html_e('Profile Image', 'sakolawp'); ?></label>
			<?php 
			$user_img = wp_get_attachment_image_src( get_user_meta($current_user,'_user_img', array('80','80'), true, true ));
			if(!empty($user_img)) { ?>
			<img class="profile_img" src="<?php echo esc_url($user_img[0]); ?>" alt="<?php echo esc_attr($user_name); ?>">
			<?php }
			else {
				echo get_avatar( $current_user, 60 );
			} ?>
		</div>

	</div><!-- #adduser -->

	</div><!-- .hentry .post -->
</div>
<?php

do_action( 'sakolawp_after_main_content' );
get_footer();
?>