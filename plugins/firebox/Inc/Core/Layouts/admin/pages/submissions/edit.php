<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$submission = $this->data->get('submission');
$form_submissions_url = admin_url('admin.php?page=firebox-submissions&form_id=' . $submission->form_id);
?>
<div class="fb-edit-submission-page">
	<div class="submission-page-header mb-3">
		<h1 class="text-default text-[32px] dark:text-white flex gap-1 items-center fp-admin-page-title"><?php echo 'Submission #' . esc_html($submission->id); ?></h1>
		<a href="<?php echo esc_url($form_submissions_url); ?>" class="fb-go-back"><?php esc_html_e(firebox()->_('FB_BACK_TO_SUBMISSIONS')); ?></a>
	</div>
	<div class="submission-fields">
		<h3><?php esc_html_e(firebox()->_('FB_USER_SUBMITTED_DATA')); ?></h3>
		<table>
			<tbody>
				<?php
				foreach ($submission->form->fields as $key => $field):
					$required = $field->getOptionValue('required') ? '*' : '';
				?>
					<tr>
						<td class="fb-submission-field-label"><?php echo esc_html__($field->getLabel()) . $required; ?></td>
						<td>
							<?php
							$field_id = $field->getOptionValue('id');
							$submission_meta_item = array_filter($submission->meta, function($meta_item) use ($field_id) {
								return $field_id === $meta_item->meta_key;
							});
							$submission_meta_item = reset($submission_meta_item);
							$field->setValue(isset($submission_meta_item->meta_value) ? $submission_meta_item->meta_value : '');
							$field->addInputCSSClass('fpf-control-input-item xxlarge');
							$field->getInput();
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="submission-fields">
		<h3><?php esc_html_e(firebox()->_('FB_SUBMISSION_INFO')); ?></h3>
		<table>
			<tr>
				<td><?php esc_html_e(fpframework()->_('FPF_STATUS')); ?></td>
				<td>
					<?php
					$status_payload = [
						'name' => 'submission_state',
						'name_prefix' => 'firebox_submission',
						'render_group' => false,
						'choices' => [
							'published' => fpframework()->_('FPF_PUBLISHED'),
							'unpublished' => fpframework()->_('FPF_UNPUBLISHED')
						],
						'value' => $submission->state === '1' ? 'published' : 'unpublished'
					];
					$status = new \FPFramework\Base\Fields\Toggle($status_payload);
					$status->render();
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e(fpframework()->_('FPF_ID')); ?></td>
				<td><?php esc_html_e($submission->id); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e(firebox()->_('FB_FORM')); ?></td>
				<td><?php esc_html_e($submission->form->name); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e(fpframework()->_('FPF_VISITOR_ID')); ?></td>
				<td><?php esc_html_e($submission->visitor_id); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e(fpframework()->_('FPF_USER')); ?></td>
				<td>
					<?php
					if ($submission->user_id !== '0')
					{
						$user = get_user_by('id', $submission->user_id);
	
						echo '<a href="' . get_edit_user_link($submission->user_id) . '">' . $user->display_name . '</a>';
					}
					else
					{
						echo '-';
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e(firebox()->_('FB_CREATED_DATE')); ?></td>
				<td><?php esc_html_e(get_date_from_gmt($submission->created_at)); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e(firebox()->_('FB_MODIFIED_DATE')); ?></td>
				<td><?php echo !empty($submission->modified_at) ? esc_html(get_date_from_gmt($submission->modified_at)) : '-'; ?></td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="submission_id" value="<?php esc_attr_e($submission->id); ?>" />
	<input type="hidden" name="form_id" value="<?php esc_attr_e($submission->form_id); ?>" />
	<input type="hidden" name="task" value="edit_submission" />
</div>