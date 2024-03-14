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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="manage-section skwp-content-inner">


	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><?php esc_html_e('Sections', 'sakolawp'); ?></a>
			<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><?php esc_html_e('Add Section', 'sakolawp'); ?></a>
		</div>
	</nav>
	<div class="skwp-tab-content tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

			<!-- start of class table -->
			<div class="table-responsive">
				<table id="dataTable1" width="100%" class="table table-striped table-lightfont">
					<thead>
						<tr>
							<th>
								<?php esc_html_e('Section', 'sakolawp'); ?>
							</th>
							<th>
								<?php esc_html_e('Class', 'sakolawp'); ?>
							</th>
							<th>
								<?php esc_html_e('Teacher', 'sakolawp'); ?>
							</th>
							<th class="text-center">
								<?php esc_html_e('Action', 'sakolawp'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						global $wpdb;
						$sections = $wpdb->get_results("SELECT name, class_id, teacher_id, section_id FROM {$wpdb->prefix}sakolawp_section", OBJECT);
						foreach ($sections as $section) :
						?>
							<tr>
								<td>
									<?php echo esc_html($section->name); ?>
								</td>
								<td>
									<?php
									global $wpdb;
									$classes = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $section->class_id");
									echo esc_html($classes->name); ?>
								</td>
								<td>
									<?php
									$user_info = get_userdata($section->teacher_id);
									echo esc_html($user_info->display_name); ?>
								</td>
								<td>
									<a class="btn skwp-btn btn-sm btn-primary" href="<?php echo add_query_arg(array('edit' => intval($section->section_id)), admin_url('admin.php?page=sakolawp-manage-section')); ?>">
										<?php esc_html_e('Edit', 'sakolawp'); ?>
									</a>
									<a class="btn skwp-btn btn-sm btn-danger" href="<?php echo add_query_arg(array('delete' => intval($section->section_id)), admin_url('admin.php?page=sakolawp-manage-section')); ?>">
										<?php esc_html_e('Delete', 'sakolawp'); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<!-- end of class table -->

		</div>
		<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

			<?php
			$args = array(
				'role'    => 'teacher',
				'orderby' => 'user_nicename',
				'order'   => 'ASC'
			);
			$teachers = get_users($args);
			?>

			<!-- start of class form -->
			<form id="myForm" name="myform" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
				<input type="hidden" name="action" value="save_section_setting" />
				<h5 class="skwp-form-header">
					<?php esc_html_e('Add New Section', 'sakolawp'); ?>
				</h5>
				<div class="skwp-form-group skwp-clearfix">
					<div class="skwp-row">
						<div class="skwp-column skwp-column-3">
							<label for=""><?php esc_html_e('Section Name', 'sakolawp'); ?></label>
							<div class="input-group">
								<input class="skwp-form-control" placeholder="Section Name" name="name" required="" type="text">
							</div>
						</div>

						<div class="skwp-column skwp-column-3">
							<label for=""><?php esc_html_e('Class', 'sakolawp'); ?></label>
							<div class="input-group">
								<select class="skwp-form-control" name="class_id">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									global $wpdb;
									$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
									foreach ($classes as $class) :
									?>
										<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html($class->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="skwp-column skwp-column-3">
							<label for=""><?php esc_html_e('Teacher', 'sakolawp'); ?></label>
							<div class="input-group">
								<select class="skwp-form-control" name="teacher_id">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php

									foreach ($teachers as $teacher) :
									?>
										<option value="<?php echo esc_attr($teacher->ID); ?>"><?php echo esc_html($teacher->display_name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="skwp-form-button">
					<button class="btn skwp-btn btn-rounded btn-primary" type="submit"><?php esc_html_e('Add', 'sakolawp'); ?></button>
				</div>
			</form>
			<!-- end of class form -->

		</div>
	</div>
</div>