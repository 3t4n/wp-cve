<?php
defined( 'ABSPATH' ) || exit;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

$question_code = sanitize_text_field($_GET['question_code']);

?>

<div class="view-questions skwp-content-inner skwp-clearfix">

	<div class="skwp-page-title skwp-clearfix">
		<h5 class="pull-left">
			<?php esc_html_e('Question', 'sakolawp'); ?>
		</h5>

		<div class="back skwp-back hidden-sm-down pull-right">
			<a href="<?php echo esc_url( home_url( 'questions_bank' ));?>"><i class="sakolawp-icon sakolawp-icon-arrow"></i><?php esc_html_e('Back', 'sakolawp'); ?></a>
		</div>
	</div>

	<?php
	$n = 1;
	$ques = $wpdb->get_results( "SELECT question,question_excerpt,optiona,optionb,optionc,optiond,correct_answer,class_id, subject_id FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = '$teacher_id' AND question_code = '$question_code'", ARRAY_A );
	foreach ($ques as $row1): ?>

	<div class="skwp-question">
		<?php echo esc_html($row1['question']); ?>
	</div>
	
	<div class="skwp-row the-options">
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('A.'); ?> </strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optiona']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('B.'); ?> </strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optionb']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('C.'); ?> </strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optionc']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('D.'); ?> </strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optiond']); ?>
			</div>
		</div>
	</div>
	
	<div class="correctanswer">
		<div class="skwp-row">
			<div class="skwp-column skwp-column-3">
				<label for=""><?php echo esc_html__( 'Correct Answer', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" disabled name="correctanswer">
					<option value="A" <?php if($row1['optiona'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('A.'); ?></option>
					<option value="B" <?php if($row1['optionb'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('B.'); ?></option>
					<option value="C" <?php if($row1['optionc'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('C.'); ?></option>
					<option value="D" <?php if($row1['optiond'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('D.'); ?></option>
				</select>
			</div>
			<div class="skwp-column skwp-column-3">
				<label for=""> <?php echo esc_html__( 'Class', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" disabled name="kelas">
					<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
					<?php $classes = $wpdb->get_results( "SELECT class_id,name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
					foreach($classes as $class):
					?>
					<option value="<?php echo esc_attr($class->class_id);?>" <?php if($class->class_id == $row1['class_id']){echo "selected";} ?>><?php echo esc_html( $class->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="skwp-column skwp-column-3">
				<label for=""><?php echo esc_html__( 'Subject', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" disabled name="mapel">
				<?php 
				$subjects = $wpdb->get_results( "SELECT subject_id,name FROM {$wpdb->prefix}sakolawp_subject WHERE teacher_id = $teacher_id", OBJECT );
				foreach($subjects as $subject):
					?>
					<option value="<?php echo esc_attr($subject->subject_id);?>" <?php if($subject->subject_id == $row1['subject_id']){echo "selected";} ?>><?php echo esc_html($subject->name);?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();