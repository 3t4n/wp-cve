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

JHtml::fetch('formbehavior.chosen');
JHtml::fetch('bootstrap.popover');

$vik = VAPApplication::getInstance();

?>

<style>
	.export-params {
		display: none;
	}
	.export-params label {
		font-weight: bold;
	}

</style>

<div style="padding:10px">
	
	<div class="row-fluid">

		<div class="span12">
			<?php echo $vik->openEmptyFieldset(); ?>

				<!-- NAME - Text -->

				<?php echo $vik->openControl(JText::translate('VAPEXPORTRES1') . '*'); ?>
					<input type="text" name="filename" value="<?php echo $this->type; ?>" class="required" size="32" />
				<?php echo $vik->closeControl(); ?>

				<!-- EXPORT CLASS - Select -->

				<?php
				$elements = array();
				$elements[] = JHtml::fetch('select.option', '', '--');

				foreach ($this->exportList as $key => $exportable)
				{
					$elements[] = JHtml::fetch('select.option', $key, $exportable->getName());
				}
				
				echo $vik->openControl(JText::translate('VAPEXPORTRES2') . '*'); ?>
					<select name="export_class" class="required" id="export-class">
						<?php echo JHtml::fetch('select.options', $elements); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- RAW - Checkbox -->

				<?php
				$yes = $vik->initRadioElement('', '', false);
				$no  = $vik->initRadioElement('', '', true);

				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAP_EXPORT_RAW'),
					'content' => JText::translate('VAP_EXPORT_RAW_DESC'),
				));

				echo $vik->openControl(JText::translate('VAP_EXPORT_RAW') . $help);
				echo $vik->radioYesNo('raw', $yes, $no, false);
				echo $vik->closeControl();
				?>

			<?php echo $vik->closeEmptyFieldset(); ?>
		</div>

		<!-- export params -->

		<?php
		// Display different types of fieldset depending on the current platform.
		// Since Joomla doesn't return the fields in a fieldset, we should wrap
		// them by using our application methods. Wordpress instead returns a
		// full HTML including the fieldset opening and closure.
		if (VersionListener::isJoomla())
		{
			?>
			<div class="span12">
				<?php
				echo $vik->openEmptyFieldset('form-horizontal export-params');
				echo $vik->closeEmptyFieldset();
				?>
			</div>
			<?php
		}
		else
		{
			?><div class="export-params"></div><?php
		}
		?>
	
	</div>

</div>

<?php
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>

	jQuery(function($) {

		VikRenderer.chosen('#adminForm');

		$('#export-class').on('change', function() {

			var type = $(this).val();

			validator.unregisterFields('.export-params .required');

			$('.export-params').html('');

			if (!type.length) {
				return;
			}

			// disable export btn
			$('#export-btn').prop('disabled', true);

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=export.params'); ?>',
				{
					import_type: '<?php echo $this->type; ?>',
					export_class: type,
				},
				(html) => {
					$('.export-params').html(html);

					if (html) {
						$('.export-params').show();
					} else {
						$('.export-params').hide();
					}

					// register new required fields
					validator.registerFields('.export-params .required');

					// render new select
					VikRenderer.chosen('.export-params');

					// render popover
					$('.export-params .hasPopover').popover({trigger: 'hover', sanitize: false, trigger: 'hover', html: true});

					// enable export btn again
					$('#export-btn').prop('disabled', false);
				},
				(err) => {
					alert(Joomla.JText._('VAPCONNECTIONLOSTERROR'));

					// enable export btn again
					$('#export-btn').prop('disabled', false);

					$('.export-params').hide();
				}
			);

		});

		$('#export-btn').on('click', () => {
			if (!validator.validate()) {
				return false;
			}

			jQuery('#jmodal-download').modal('hide');

			Joomla.submitform('export.download', document.adminForm);
		});

	});

	// validate

	var validator = new VikFormValidator('#adminForm');

</script>
