<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$vik = VAPApplication::getInstance();

$editor = $vik->getEditor();

$positions = array(
	'{custom_position_top}',
	'{custom_position_middle}',
	'{custom_position_bottom}',
	'{custom_position_footer}',
);

$json = array();

foreach ($this->mailTemplates as $tmpl)
{
	$json[$tmpl->id] = $tmpl;
}

?>

<style>

	.custmail-top-control {
		display: flex;
		justify-content: space-between;
		margin-bottom: 15px;
	}
	.custmail-top-control > div:first-child {
		width: 70%;
	}
	.custmail-top-control > div:not(:last-child) {
		margin-right: 10px;
	}
	input[name="custmail_name"] {
		width: calc(100% - 14px) !important;
	}

</style>

<div style="padding: 10px;">

	<div class="span12">
		<?php echo $vik->openEmptyFieldset(); ?>

			<div class="custmail-top-control">
				<div>
					<input type="text" name="custmail_name" placeholder="<?php echo $this->escape(JText::translate('VAPCUSTMAILNAME')); ?>" />
				</div>

				<div>
					<?php
					$elements = array();
					foreach ($positions as $p)
					{
						$elements[] = JHtml::fetch('select.option', $p, $p);
					}
					?>
					<select name="custmail_position">
						<?php echo JHtml::fetch('select.options', $elements); ?>
					</select>
				</div>

				<div>
					<?php
					$elements = array();
					$elements[] = JHtml::fetch('select.option', '', '');
					foreach ($this->mailTemplates as $t)
					{
						$elements[] = JHtml::fetch('select.option', $t->id, $t->name);
					}
					?>
					<select name="custmail_id">
						<?php echo JHtml::fetch('select.options', $elements); ?>
					</select>
				</div>
			</div>

			<div class="control-group">
				<?php echo $editor->display('custmail_content', '', '100%', 550, 70, 20); ?>
			</div>

		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>

</div>

<?php
JText::script('VAPFILTERCREATENEW');
?>

<script>

	jQuery(function($) {
		const MAIL_TMPL_LOOKUP = <?php echo json_encode($json); ?>;

		var saveFlag = false;

		$('select[name="custmail_position"]').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200,
		});

		$('select[name="custmail_id"]').select2({
			placeholder: Joomla.JText._('VAPFILTERCREATENEW'),
			allowClear: true,
			width: 200,
		}).on('change', function() {
			var id = parseInt($(this).val());

			if (isNaN(id) || !MAIL_TMPL_LOOKUP.hasOwnProperty(id)) {
				// do not clear as it could be needed to 
				// create a new template starting from
				// an existing one
				return;
			}

			// get e-mail template
			var tmpl = MAIL_TMPL_LOOKUP[id];

			// update fields
			$('input[name="custmail_name"]').val(tmpl.name);
			$('select[name="custmail_position"]').select2('val', tmpl.position);
			Joomla.editors.instances.custmail_content.setValue(tmpl.content);
		});

		$('#custmail-save').on('click', () => {
			// keep fields filled-in
			saveFlag = true;

			// dismiss modal
			bootDismissModal('custmail');
		});

		$('#custmail-cancel').on('click', () => {
			// reset all fields
			saveFlag = false;

			// dismiss modal
			bootDismissModal('custmail');
		});

		$('#jmodal-custmail').on('show', function() {
			// unset tabindex from modal so that it is possible to focus
			// the input fields within the popups opened by the editor
			$(this).attr('tabindex', '');
		});

		$('#jmodal-custmail').on('hide', function() {
			if (!saveFlag) {
				// clear all fields
				$('input[name="custmail_name"]').val('');
				$('select[name="custmail_id"]').select2('val', '');
				Joomla.editors.instances.custmail_content.setValue('');
			}

			// reset tabindex to -1 from modal so that the inputs contained
			// within the modal won't be focused when hidden
			$(this).attr('tabindex', -1);
		});

		const bootDismissModal = (id) => {
			<?php echo $vik->bootDismissModalJS(); ?>
		};
	});

</script>
