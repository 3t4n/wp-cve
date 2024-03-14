<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$question_code = substr(md5(rand(100000000, 200000000)), 0, 10);
$datetime = strtotime(date('d-m-Y', time()));

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_add_question_csrf'], 'sakola-add-question-csrf')) {
		if(current_user_can( 'read' )) {
			
			$question = sanitize_textarea_field($_POST['question']);
			$question_excerpt = sanitize_textarea_field($_POST['question_excerpt']);

			$optiona = sanitize_textarea_field($_POST['optiona']);
			$optionb = sanitize_textarea_field($_POST['optionb']);
			$optionc = sanitize_textarea_field($_POST['optionc']);
			$optiond = sanitize_textarea_field($_POST['optiond']);

			$correct_answer1 = sanitize_text_field($_POST['correctanswer']);

			if($correct_answer1 == 'A'){
				$correct_answer = $optiona;
			}
			elseif($correct_answer1 == 'B'){
				$correct_answer = $optionb;
			}
			elseif($correct_answer1 == 'C'){
				$correct_answer = $optionc;
			}
			elseif($correct_answer1 == 'D'){
				$correct_answer = $optiond;
			}

			$class_id = sanitize_text_field($_POST['kelas']);
			$subject_id = sanitize_text_field($_POST['mapel']);
			$owner_id = sanitize_text_field($_POST['owner_id']);
			$post_id= sanitize_text_field($_POST['post_id']);

			$wpdb->insert(
				$wpdb->prefix . 'sakolawp_questions_bank',
				array( 
					'question' => $question,
					'question_excerpt' => $question_excerpt,
					'optiona' => $optiona,
					'optionb' => $optionb,
					'optionc' => $optionc,
					'optiond' => $optiond,
					'correct_answer' => $correct_answer,
					'class_id' => $class_id,
					'subject_id' => $subject_id,
					'owner_id' => $owner_id,
					'added' => $datetime,
					'question_code' => $post_id,
				)
			);
		}
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

?>

<div class="add-new-questions questions-table skwp-content-inner skwp-clearfix">

	<div class="skwp-page-title skwp-clearfix">
		<h5 class="pull-left">
			<?php esc_html_e('Add A Question', 'sakolawp'); ?>
		</h5>
		<div class="back skwp-back hidden-sm-down pull-right">
			<a href="<?php echo esc_url(home_url('questions_bank')); ?>"><i class="sakolawp-icon sakolawp-icon-arrow"></i><?php esc_html_e('Back', 'sakolawp'); ?></a>
		</div>
	</div>
	
	<form id="myForm" name="save_create_homework" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="sakola_add_question_csrf" value="<?php echo wp_create_nonce('sakola-add-question-csrf'); ?>" />
		<input type="hidden" class="skwp-form-control" name="owner_id" value="<?php echo esc_attr($teacher_id); ?>" />
		<input type="hidden" class="skwp-form-control" name="post_id" value="<?php echo esc_attr($question_code); ?>" />

		<div class="skwp-form-group">
			<label> <?php esc_html_e('Question', 'sakolawp'); ?></label>
			<?php $content = '';
			$editor_id = 'mycustomeditor';
			$settings = array( 'media_buttons' => false, 'textarea_name' => 'question', 'class' => 'textarea_question' );
			wp_editor( $content, $editor_id, $settings ); ?>
		</div>

		<div class="skwp-form-group">
			<label> <?php esc_html_e('Excerpt', 'sakolawp'); ?></label>
			<textarea class="skwp-form-control" rows="4" name="question_excerpt"></textarea>
		</div>

		<div class="skwp-row">
			<div class="skwp-form-group skwp-column skwp-column-2">
				<label for=""><?php esc_html_e('Option A', 'sakolawp'); ?></label>
				<textarea id="editorsoal2" class="summernotesoal2" rows="3" name="optiona"></textarea>
			</div>
			<div class="skwp-form-group skwp-column skwp-column-2">
				<label for=""><?php esc_html_e('Option B', 'sakolawp'); ?></label>
				<textarea id="editorsoal3" class="summernotesoal2" rows="3" name="optionb"></textarea>
			</div>
		</div>
		<div class="skwp-row">
			<div class="skwp-form-group skwp-column skwp-column-2">
				<label for=""> <?php esc_html_e('Option C', 'sakolawp'); ?></label>
				<textarea id="editorsoal4" class="summernotesoal2" rows="3" name="optionc"></textarea>
			</div>
			<div class="skwp-form-group skwp-column skwp-column-2">
				<label for=""> <?php esc_html_e('Option D', 'sakolawp'); ?></label>
				<textarea id="editorsoal5" class="summernotesoal2" rows="3" name="optiond"></textarea>
			</div>
		</div>

		<div class="skwp-row">
			<div class="skwp-form-group skwp-column skwp-column-3">
				<label for=""><?php esc_html_e('Correct Answer', 'sakolawp'); ?></label>
				<select class="skwp-form-control" required="" name="correctanswer">
					<option value="A"><?php echo esc_html('A', 'sakolawp'); ?></option>
					<option value="B"><?php echo esc_html('B', 'sakolawp'); ?></option>
					<option value="C"><?php echo esc_html('C', 'sakolawp'); ?></option>
					<option value="D"><?php echo esc_html('D', 'sakolawp'); ?></option>
				</select>
			</div>
			<div class="skwp-form-group skwp-column skwp-column-3">
				<label for=""> <?php echo esc_html__( 'Class', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" name="kelas">
					<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
					<?php 
					global $wpdb;
					$classes = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
					foreach($classes as $class):
					?>
					<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html($class->name); ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="skwp-form-group skwp-column skwp-column-3">
				<label for=""><?php echo esc_html__( 'Subject', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" required="" name="mapel">
					<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
					<?php 
					$subjects = $wpdb->get_results( "SELECT subject_id, name FROM {$wpdb->prefix}sakolawp_subject WHERE teacher_id = $teacher_id", OBJECT );
					foreach($subjects as $subject):
						?>
						<option value="<?php echo esc_attr($subject->subject_id);?>"><?php echo esc_html($subject->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="skwp-form-button">
			<button class="btn btn-rounded btn-success skwp-btn skwp-mt-20" name="submit" value="submit" type="submit"> 
				<?php echo esc_html__( 'Create', 'sakolawp' ); ?>
			</button>
		</div>
	</form>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();