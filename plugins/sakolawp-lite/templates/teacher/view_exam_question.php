<?php
defined( 'ABSPATH' ) || exit;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$question_id = sanitize_text_field($_GET['question_id']);
$exam_code = sanitize_text_field($_GET['exam_code']);


$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;


?>

<div class="view-questions skwp-content-inner">
	<div class="skwp-page-title skwp-clearfix">
		<h5 class="pull-left">
			<?php esc_html_e('Question', 'sakolawp'); ?>
		</h5>

		<div class="back skwp-back hidden-sm-down pull-right">
			<a href="<?php echo add_query_arg( array('exam_code' => esc_html($exam_code)), home_url( 'exam_questions' ) );?>"><i class="sakolawp-icon sakolawp-icon-arrow"></i><?php esc_html_e('Back', 'sakolawp'); ?></a>
		</div>
	</div>

	<?php
	$n = 1;
	$ques = $wpdb->get_results( "SELECT question, optiona, optionb, optionc, optiond, correct_answer, marks FROM {$wpdb->prefix}sakolawp_questions WHERE question_id = '$question_id'", ARRAY_A );
	foreach ($ques as $row1): ?>

	<div class="skwp-question">
		<?php echo esc_html($row1['question']); ?>
	</div>

	<div class="skwp-clearfix the-options skwp-row">
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('A. ', 'sakolawp'); ?></strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optiona']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('B. ', 'sakolawp'); ?></strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optionb']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('C. ', 'sakolawp'); ?></strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optionc']); ?>
			</div>
		</div>
		<div class="skwp-column skwp-column-2">
			<strong><?php echo esc_html('D. ', 'sakolawp'); ?></strong>
			<div class="jawaban">
				<?php echo esc_html($row1['optiond']); ?>
			</div>
		</div>
	</div>
	
	<div class="correctanswer">
		<div class="skwp-row">
			<div class="skwp-column skwp-column-2">
				<label for=""><?php echo esc_html__( 'Correct Answer', 'sakolawp' ); ?></label>
				<select class="skwp-form-control" disabled name="correctanswer">
					<option value="A" <?php if($row1['optiona'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('A.'); ?></option>
					<option value="B" <?php if($row1['optionb'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('B.'); ?></option>
					<option value="C" <?php if($row1['optionc'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('C.'); ?></option>
					<option value="D" <?php if($row1['optiond'] == $row1['correct_answer']) { ?> selected <?php } ?>><?php echo esc_html('D.'); ?></option>
				</select>
			</div>
			<div class="skwp-column skwp-column-2">
				<label for=""> <?php echo esc_html__( 'Mark', 'sakolawp' ); ?></label>
				<input class="form-control" disabled="" type="text" value="<?php echo esc_attr($row1['marks']); ?>">
			</div>
		</div>
	</div>

	<?php endforeach;?>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();