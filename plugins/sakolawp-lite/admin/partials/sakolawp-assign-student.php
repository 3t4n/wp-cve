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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="student-area-admin skwp-content-inner">

	<nav class="skwp-tabs-menu">
        <ul class="nav nav-tabs">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-link nav-item" href="admin.php?page=sakolawp-student-area">
				<span><?php esc_html_e( 'Students Area', 'sakolawp' ); ?></span>
			</a>
			<a class="nav-item nav-link active" href="admin.php?page=sakolawp-assign-student">
				<span><?php esc_html_e( 'Assign Student', 'sakolawp' ); ?></span>
			</a>
		</ul>
	</nav>

	<div class="skwp-tab-content">
		
		<!-- start of class form -->
		<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
			<input type="hidden" name="action" value="add_student_enroll" />
			<h5 class="skwp-form-header">
				<?php esc_html_e( 'Add Student To Class', 'sakolawp' ); ?>
			</h5>

			<?php 
			$excludeIds = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll", ARRAY_A );
            $useridEx = array();
            foreach ($excludeIds as $ex) {
                $useridEx[] = $ex["student_id"];
            }
			$args = array(
				'exclude' => $useridEx,
				'role'    => 'student',
				'orderby' => 'user_nicename',
				'order'   => 'ASC'
			);
			$students = get_users( $args );
			?>

			<div class="skwp-form-group skwp-clearfix">
				<div class="skwp-row">
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group">
						<label for=""> <?php esc_html_e( 'Student Name', 'sakolawp' ); ?></label>
							<select class="skwp-form-control" name="student_id">
								<option value=""><?php esc_html_e( 'Select', 'sakolawp' ); ?></option>
								<?php 
								global $wpdb;
								foreach($students as $student): ?>

								<option value="<?php echo esc_attr($student->ID); ?>"><?php echo esc_html($student->display_name);?></option>

								<?php endforeach;?>
							</select>
						</div>
					</div>
						
					<div class="skwp-column skwp-column-4">
						<div class="skwp-form-group">
						<label for=""> <?php esc_html_e( 'Class', 'sakolawp' ); ?></label>
							<select class="skwp-form-control" name="class_id" id="class_holder">
								<option value=""><?php esc_html_e( 'Select', 'sakolawp' ); ?></option>
								<?php 
								$classes = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
								foreach($classes as $class):
								?>
								<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html( $class->name ); ?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class="skwp-column skwp-column-4">
						<label for=""> <?php esc_html_e( 'Section', 'sakolawp' ); ?></label>
						<div class="skwp-form-group">
							<select class="skwp-form-control" name="section_id" id="section_holder">
								<option value=""><?php esc_html_e( 'Select', 'sakolawp' ); ?></option>
								<?php 
								global $wpdb;
								$sections = $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section", OBJECT );
								foreach($sections as $section):
								?>
								<option value="<?php echo esc_attr($section->section_id); ?>"><?php echo esc_html( $section->name ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="skwp-column skwp-column-4">
						<button class="btn skwp-btn btn-rounded btn-primary skwp-mt-30" type="submit"> <?php esc_html_e( 'Add', 'sakolawp' ); ?></button>
					</div>
				</div>
			</div>
		</form>
		<!-- end of class form -->
				
	</div>
</div>