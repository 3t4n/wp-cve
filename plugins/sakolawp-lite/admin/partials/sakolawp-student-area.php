<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/admin/partials
 */


global $wpdb;

if (isset($_POST['submit'])) {
	$class_id = sanitize_text_field($_POST['class_id']);
	$section_id = sanitize_text_field($_POST['section_id']);
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="student-area skwp-content-inner">

	<nav class="skwp-tabs-menu">
		<ul class="nav nav-tabs">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>

			<a class="nav-item nav-link active" href="admin.php?page=sakolawp-student-area">
				<span><?php echo esc_html__('Students Area', 'sakolawp'); ?></span>
			</a>


			<a class="nav-item nav-link" href="admin.php?page=sakolawp-assign-student">
				<span><?php echo esc_html__('Assign Student', 'sakolawp'); ?></span>
			</a>

		</ul>
	</nav>
	<div class="skwp-tab-content tab-content" id="nav-tabContent">
		<?php if (isset($class_id) != '' && isset($section_id) != '') : ?>
			<form name="sakolawp_admin_student_area" action="" method="POST">
				<div class="skwp-row skwp-clearfix">
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group">
							<label class="gi" for=""><?php echo esc_html__('Class :', 'sakolawp'); ?></label>
							<select name="class_id" class="skwp-form-control" id="class_holder">
								<option value=""><?php echo esc_html__('Select', 'sakolawp'); ?></option>
								<?php
								$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
								foreach ($classes as $class) :
								?>
									<option value="<?php echo esc_attr( $class->class_id ); ?>"><?php echo esc_html($class->name); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group"> <label class="gi" for=""><?php echo esc_html__('Section :', 'sakolawp'); ?></label>
							<select class="skwp-form-control" name="section_id" id="section_holder">
								<option value=""><?php echo esc_html__('Select', 'sakolawp'); ?></option>
							</select>
						</div>
					</div>
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php echo esc_html__('View', 'sakolawp'); ?></span></button></div>
					</div>
				</div>
			</form>
		<?php
		else : ?>
			<form name="sakolawp_admin_student_area" action="" method="POST">
				<div class="skwp-row skwp-clearfix">
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group">
							<label class="gi" for=""><?php echo esc_html__('Class :', 'sakolawp'); ?></label>
							<select name="class_id" class="skwp-form-control" id="class_holder">
								<option value=""><?php echo esc_html__('Select', 'sakolawp'); ?></option>
								<?php
								$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
								foreach ($classes as $class) :
								?>
									<option value="<?php echo esc_attr( $class->class_id ); ?>"><?php echo esc_html($class->name); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group"> <label class="gi" for=""><?php echo esc_html__('Section :', 'sakolawp'); ?></label>
							<select class="skwp-form-control" name="section_id" id="section_holder">
								<option value=""><?php echo esc_html__('Select', 'sakolawp'); ?></option>
							</select>
						</div>
					</div>
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php echo esc_html__('View', 'sakolawp'); ?></span></button></div>
					</div>
				</div>
			</form>
		<?php endif; ?>

		<?php if (isset($class_id) != '' && isset($section_id) != '') : ?>
			<div class="tab-content skwp-clearfix skwp-mt-40">

				<?php
				$query = $wpdb->get_results("SELECT section_id FROM {$wpdb->prefix}sakolawp_section", OBJECT);
				if ($wpdb->num_rows > 0) : ?>
					<div class="skwp-row daftar-murid">
						<?php
						$class_id = $class_id;
						$section_id = $section_id;
						$students = $wpdb->get_results("SELECT student_id, roll FROM {$wpdb->prefix}sakolawp_enroll WHERE section_id = $section_id", ARRAY_A);
						foreach ($students as $row2) : ?>
							<div class="skwp-column skwp-column-4 m-b murid-list">
								<div class="pipeline-item">
									<div class="pi-foot">
										<a class="extra-info" href="#">
											<span>
												<?php
												$school_name = get_option('school_name');
												echo esc_html($school_name); ?>
											</span>
										</a>
									</div>
									<div class="pi-body bglogo">
										<div class="avatar">
											<?php
											$current_id = $row2['student_id'];
											$user_info = get_user_meta($current_id);
											$first_name = $user_info["first_name"][0];
											$last_name = $user_info["last_name"][0];

											$user_name = $first_name . ' ' . $last_name;

											if (empty($first_name)) {
												$user_info = get_userdata($current_id);
												$user_name = $user_info->display_name;
											}
											$user_img = wp_get_attachment_image_src(get_user_meta($current_id, '_user_img', array('80', '80'), true, true));
											if (!empty($user_img)) { ?>
												<img class="profile_img" src="<?php echo esc_url($user_img[0]); ?>" alt="<?php echo esc_attr($user_name); ?>">
											<?php } else {
												echo get_avatar($current_id, 80);
											}

											$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
											$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
											$tgt_id = intval($current_id); ?>
										</div>
										<div class="pi-info">
											<div class="h6 pi-name">
												<span class="name">
													<?php echo esc_html($user_name); ?><br>
												</span>
												<small><?php echo esc_html__('ID:', 'sakolawp'); ?> <?php echo esc_html($row2['roll']); ?></small>
											</div>
										</div>
										<div class="pi-link">
											<a href="<?php echo esc_url(add_query_arg(array('tgt_id' => $tgt_id), home_url( 'view-user' ) )); ?>">
												<i class="sakolawp-icon icon-user"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php
						endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>
	</div>
</div>