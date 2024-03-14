<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_zip
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

$itemid = $params->get('itemid', null);

?>

<div class="vapmainzipmod">
	
	<div class="vapzipfieldsetmod">

		<div class="vap-zipmod-item">

			<div class="vap-zipmod-item-label">
				<?php echo JText::translate('VAPSERVICE'); ?>
			</div>

			<div class="vap-zipmod-item-input">
				<select name="id_ser" id="vapserselzipmod<?php echo $module_id; ?>">
					<?php
					foreach ($services as $group)
					{
						if (!empty($group['id']))
						{
							?>
							<optgroup label="<?php echo htmlspecialchars($group['name']); ?>">
							<?php
						}

						foreach ($group['list'] as $s)
						{
							?>
							<option value="<?php echo (int) $s['id']; ?>"><?php echo $s['name']; ?></option>
							<?php
						}
						
						if (!empty($group['id']))
						{
							?>
							</optgroup>
							<?php
						}
					}
					?>
				</select>
			</div>

		</div>

		<div class="vap-zipmod-item" id="vapempzipblock<?php echo $module_id; ?>" style="<?php echo $employees ? '' : 'display: none;'; ?>">
			
			<div class="vap-zipmod-item-label">
				<?php echo JText::translate('VAPEMPLOYEE'); ?>
			</div>

			<div class="vap-zipmod-item-input">
				<select name="id_emp" id="vapempselzipmod<?php echo $module_id; ?>">
					<?php
					foreach ($employees as $e)
					{
						?>
						<option value="<?php echo (int) $e['id']; ?>"><?php echo $e['nickname']; ?></option>
						<?php
					}
					?>
				</select>
			</div>

		</div>

		<div class="vap-zipmod-item">

			<div class="vap-zipmod-item-label">
				<?php echo JText::translate('VAPZIP'); ?>
			</div>

			<div class="vap-zipmod-item-input">
				<input type="text" value="" id="vapziptextinput<?php echo $module_id; ?>" />
			</div>

		</div>
		
		<div class="vapzipresponsediv">
			
		</div>

		<div class="vap-zipmod-search">
			<button type="button" class="vap-btn blue vap-zipmod-submit" id="vapzipsearch<?php echo $module_id; ?>">
				<?php echo JText::translate('VAPFINDBUTTON'); ?>
			</button>
		</div>
			
	</div>
	
</div>

<?php
JText::script('VAPZIPVALID0');
JText::script('VAPZIPVALID1');
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('.vapzipfieldsetmod select').select2({
				allowClear: false,
				width: '100%'
			});

			$('#vapserselzipmod<?php echo $module_id; ?>').on('change', function() {
				$('#vapempselzipmod<?php echo $module_id; ?>').prop('disabled', true);
						
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.serviceemployees'); ?>',
					{
						id_ser: $(this).val(),
					},
					(resp) => {
						if (resp) {
							let options = [];

							resp.forEach((emp) => {
								options.push(
									$('<option></option>').val(emp.id).text(emp.nickname)
								);
							});

							$('#vapempselzipmod<?php echo $module_id; ?>').html(options).attr('disabled', false);
							$('#vapempselzipmod<?php echo $module_id; ?>').trigger('change.select2');

							$('#vapempzipblock<?php echo $module_id; ?>').show();
						} else {
							$('#vapempzipblock<?php echo $module_id; ?>').hide();
							$('#vapempselzipmod<?php echo $module_id; ?>').html('');
						}
					}
				);
			});

			$('#vapzipsearch<?php echo $module_id; ?>').on('click', function() {
				const data = {
					id_ser: $('#vapserselzipmod<?php echo $module_id; ?>').val(),
					id_emp: $('#vapempselzipmod<?php echo $module_id; ?>').val(),
					zip:    $('#vapziptextinput<?php echo $module_id; ?>').val(),
				};

				$('.vapzipresponsediv').html('')
					.removeClass('vapzipresponseok')
					.removeClass('vapzipresponseno');

				if (!data.zip) {
					return;
				}
						
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.validatezip'); ?>',
					data,
					(resp) => {
						if (resp) {
							$('.vapzipresponsediv').html(Joomla.JText._('VAPZIPVALID1'))
								.addClass('vapzipresponseok')
								.removeClass('vapzipresponseno');
						} else {
							$('.vapzipresponsediv').html(Joomla.JText._('VAPZIPVALID0'))
								.addClass('vapzipresponseno')
								.removeClass('vapzipresponseok');
						}
					}
				);
			});
		});

	})(jQuery);

</script>
