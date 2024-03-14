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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalZIP". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalZIP');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ZIP CODE ID - Select --> 

		<?php
		$elements = array(
			JHtml::fetch('select.option', '', ''),
		);

		foreach ($this->customFields as $cf)
		{
			// take only text, textarea and select
			if (in_array($cf['type'], array('text', 'textarea', 'select')))
			{
				$name = $cf['langname'];

				if ($cf['rule'] == 'zip')
				{
					$name .= ' ( !! )';
				}

				$elements[] = JHtml::fetch('select.option', $cf['id'], $name);
			}
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG32'),
			'content' => JText::translate('VAPMANAGECONFIG32_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG32') . $help); ?>
			<select name="zipcfid" id="vapzipfieldselect">
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $params['zipcfid']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- ZIP CODES LIST -->

		<?php
		$zip_codes = empty($params['zipcodes']) ? array() : json_decode($params['zipcodes'], true);

		$control = array();
		$control['style'] = $params['zipcfid'] <= 0 ? 'display:none;' : '';

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG34'), 'vapzipchild', $control); ?>
			<div id="vapzipcodescont">
				<?php 
				foreach ($zip_codes as $i => $zip)
				{
					?>
					<div class="vapzcrow inline-fields" style="margin-bottom: 5px; align-items: center;">
						
						<input type="text" name="zip_code_from[]" style="vertical-align: middle; margin-right: 4px;" value="<?php echo $zip['from']; ?>" size="10" placeholder="<?php echo $this->escape(JText::translate('VAPMANAGEWD3')); ?>" />
						<input type="text" name="zip_code_to[]" style="vertical-align: middle; margin-right: 4px;" value="<?php echo $zip['to']; ?>" size="10" placeholder="<?php echo $this->escape(JText::translate('VAPMANAGEWD4')); ?>" />
						<a href="javascript:void(0)" class="remove-zip-code no-underline">
							<i class="fas fa-minus-circle no big"></i>
						</a>

					</div>
					<?php 
				}
				?>
			</div>

			<div>
				<button type="button" class="btn" id="vapaddzipbutton">
					<?php echo JText::translate('VAPMANAGECONFIG35'); ?>
				</button>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- VALIDATE ZIP CODE - Test -->

		<?php
		$control = array();
		$control['style'] = $params['zipcfid'] <= 0 ? 'display:none;' : '';

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG36'), 'vapzipchild', $control); ?>
			<div class="input-append">
				<input type="text" id="vaptryziptext" size="10" />

				 <button type="button" id="vaptryzipbtn" class="btn">
				 	<?php echo JText::translate("VAPMANAGECONFIG37"); ?>
				 	
				 	<i id="vaploadimgspan" style="margin-left: 4px;"></i>
				 </button>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- UPLOAD ZIP LIST - File -->

		<?php
		$control = array();
		$control['style'] = $params['zipcfid'] <= 0 ? 'display:none;' : '';

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG39'),
			'content' => JText::translate('VAPMANAGECONFIG41'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG39') . $help, 'vapzipchild', $control); ?>
			<input type="file" id="zipfile" style="display:none;" />
				
			<button type="button" class="btn" id="zip-uploader">
				<?php echo JText::translate("VAPMANAGECONFIG40"); ?>
			</button>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalZIP","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > ZIP Codes > ZIP fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalZIP","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > ZIP tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<?php
JText::script('VAPMANAGECONFIG38');
JText::script('VAPMANAGEWD3');
JText::script('VAPMANAGEWD4');
?>

<script>

	jQuery(function($) {
		$('#vapzipfieldselect').select2({
			placeholder: Joomla.JText._('VAPMANAGECONFIG38'),
			allowClear: true,
			width: 250,
		});

		$('#vapzipfieldselect').on('change', function() {
			if (parseInt($(this).val()) > 0) {
				$('.vapzipchild').show();
			} else {
				$('.vapzipchild').hide();
			}
		});

		// create lambda to validate the ZIP code
		const validateZipCode = () => {
			let input = $('#vaptryziptext');
			// get zip code
			let zip = input.val();

			if (zip.length == 0) {
				return false;
			}

			let data = [];

			let zipFrom = [];
			let zipTo   = [];

			$('input[name="zip_code_from[]"]').each(function() {
				zipFrom.push($(this).val());
			});

			$('input[name="zip_code_to[]"]').each(function() {
				zipTo.push($(this).val());
			});

			for (let i = 0; i < zipFrom.length; i++) {
				let tmp = {
					from: zipFrom[i],
					to: zipTo[i],
				};

				if (tmp.from && !tmp.to) {
					tmp.to = tmp.from;
				} else if (tmp.to && !tmp.from) {
					tmp.from = tmp.to;
				}

				data.push(tmp);
			}
			
			// disable input for a while
			input.prop('readonly', true);

			let icon = $('#vaploadimgspan');
			icon.attr('class', 'fas fa-spinner fa-spin');
			
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=configuration.testzip'); ?>',
				{
					zipcode: zip,
					pool:    data,
					field:   $('#vapzipfieldselect').val(),
				},
				(result) => {
					input.prop('readonly', false);

					if (result) {
						icon.attr('class', 'fas fa-check-circle ok');
					} else {
						icon.attr('class', 'far fa-circle no');
					}
				},
				(error) => {
					input.prop('readonly', false);
					icon.attr('class', 'far fa-circle no');
				}
			);
		}

		$('#vaptryziptext').on('change', validateZipCode);
		$('#vaptryzipbtn').on('click', validateZipCode);

		// define internal callback to add a new zip code interval
		const addZipCode = (fromZip, toZip) => {
			let from = $('<input type="text" name="zip_code_from[]" style="vertical-align: middle; margin-right: 4px;" size="10" />')
				.attr('placeholder', Joomla.JText._('VAPMANAGEWD3'));

			if (fromZip) {
				from.val(fromZip);
			}

			let to = $('<input type="text" name="zip_code_to[]" style="vertical-align: middle; margin-right: 4px;" size="10" />')
				.attr('placeholder', Joomla.JText._('VAPMANAGEWD4'));

			if (toZip) {
				to.val(toZip);
			}

			let del = $(
				'<a href="javascript:void(0)" class="remove-zip-code no-underline">\n'+
					'<i class="fas fa-minus-circle no big"></i>\n'+
				'</a>'
			);

			let block = $('<div class="vapzcrow inline-fields" style="margin-bottom: 5px; align-items: center;"></div>')
				.append(from).append("\n")
				.append(to).append("\n")
				.append(del);

			$('#vapzipcodescont').append(block);
		};

		$('#vapaddzipbutton').on('click', () => {
			addZipCode();
		});

		$(document).on('click', '.remove-zip-code', function() {
			$(this).closest('.vapzcrow').remove();
		});

		$('#zip-uploader').on('click', () => {
			// unset selected files before showing the dialog
			$('input#zipfile').val(null).trigger('click');
		});

		$('input#zipfile').on('change', function() {
			// execute AJAX uploads after selecting the files
			let files = $(this)[0].files;

			if (files.length == 0) {
				return false;
			}

			var formData = new FormData();
			formData.append('file', files[0]);

			UIAjax.upload(
				// end-point URL
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=configuration.uploadzip'); ?>',
				// file post data
				formData,
				// success callback
				(resp) => {
					// iterate ZIP codes found and append them within the list
					resp.forEach((interval) => {
						addZipCode(interval.from, interval.to);
					});
				},
				(error) => {
					console.error(error);

					if (error.responseText) {
						alert(error.responseText);
					}
				}
			);
		});
	});

</script>
