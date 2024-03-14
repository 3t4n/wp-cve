<?php
defined( 'ABSPATH' ) || exit;

if(isset($_GET['action']) === 'delete') {
	if(current_user_can( 'read' )) {
		$question_code = sanitize_text_field($_GET['question_code']);
		$wpdb->delete(
			$wpdb->prefix . 'sakolawp_questions_bank',
			array(
				'question_code' => $question_code
			)
		);

		wp_redirect(home_url('questions_bank'));
		die;
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');
$teacher_id = get_current_user_id();

$my_bank = $wpdb->get_row( "SELECT owner_id FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = $teacher_id");

if(!empty($my_bank)) :

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

?>

<div class="questions-table skwp-content-inner">

	<div class="skwp-page-title skwp-clearfix">
		<h5 class="pull-left"><?php echo esc_html_e('My Questions', 'sakolawp'); ?>
			<span class="skwp-subtitle">
				<?php echo esc_html($teacher_name); ?>
			</span>
		</h5>
	
		<div class="pull-right">
			<a class="btn btn-primary btn-rounded btn-upper skwp-btn" href="<?php echo esc_url( home_url( 'add_new_question' ) ); ?>"><?php echo esc_html__('Add New', 'sakolawp'); ?></a>
		</div>
	</div>

	<div class="skwp-table table-responsive skwp-mt-20">
		<table id="tableini" class="table table-lightborder questions-bank-table">
			<thead>
				<tr>
					<th class="question"><?php echo esc_html__('Question', 'sakolawp'); ?></th>
					<th><?php echo esc_html__('Answer', 'sakolawp'); ?></th>
					<th><?php echo esc_html__('Subject', 'sakolawp'); ?></th>
					<th><?php echo esc_html__('Class', 'sakolawp'); ?></th>
					<th><?php echo esc_html__('Options', 'sakolawp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					$questions = $wpdb->get_results( "SELECT question_excerpt,correct_answer,subject_id,class_id,question_code FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = $teacher_id", ARRAY_A );
					foreach ($questions as $row):
				?>
					<?php  //if ($this->session->userdata('login_user_id') == $row['uploader_id']) { ?>
					<tr>
						<td>
							<?php echo esc_html($row['question_excerpt']); ?>
						</td>
						<td>
							<?php echo esc_html($row['correct_answer']); ?>
						</td>
						<td>
							<?php $subject_id = $row['subject_id'];
								$subject = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
								echo esc_html($subject->name);
							?>
						</td>
						<td>
							<?php
								$class_id = $row['class_id'];
								$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
								echo esc_html($class->name);
							?>
						</td>
						<td class="skwp-table-actions">
							<a class="btn btn-warning btn-rounded btn-sm skwp-btn" href="<?php echo add_query_arg( 'question_code', esc_html($row['question_code']), home_url( 'edit_bank_question' ) );?>">
								<?php echo esc_html__('Edit', 'sakolawp'); ?>
							</a>
							<a class="btn btn-primary btn-rounded btn-sm skwp-btn" href="<?php echo add_query_arg( 'question_code', esc_html($row['question_code']), home_url( 'view_bank_question' ) );?>">
								<?php echo esc_html__('View', 'sakolawp'); ?>
							</a>
							<a class="btn btn-danger btn-rounded btn-sm skwp-btn" onClick="return confirm('<?php echo esc_html__('Confirm Delete ?', 'sakolawp'); ?>')" href="<?php echo add_query_arg( array('action' => 'delete', 'question_code' => esc_html($row['question_code'])), home_url( 'questions_bank' ) );?>">
								<?php echo esc_html__('Delete', 'sakolawp'); ?>
							</a>
						</td>
					</tr>
					<?php //} ?>
					<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php 
else :
	echo esc_html_e('You are not create any question yet. Create a new ones!', 'sakolawp' ); ?>
	<div class="button-empty">
		<a class="btn btn-primary btn-rounded btn-upper skwp-btn" href="<?php echo esc_url( home_url( 'add_new_question' ) ); ?>"><?php echo esc_html__('Add New Question', 'sakolawp'); ?></a>
	</div>
<?php
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();