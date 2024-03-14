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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');

$rows    = $this->handler->getRecords();
$columns = $this->handler->getColumns();

$vik = VAPApplication::getInstance();

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<?php
	if ($rows)
	{
		// create error box to inform the user that the configuration is not complete
		$attrs = array();
		$attrs['id']    = 'custom-error';
		$attrs['style'] = 'display:none;';
		echo $vik->alert(JText::translate('VAPMANAGECUSTOMERERR3'), 'error', false, $attrs);
		?>

		<!-- ASSIGNMENTS TABLE -->

		<?php echo $vik->openCard(); ?>
		
			<div class="span12" style="margin-left: 0;">
				<?php echo $vik->openEmptyFieldset(); ?>

					<div class="scrollable-hor">
						<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
							<?php echo $vik->openTableHead(); ?>
								<tr>
									<?php for ($i = 0, $n = count($rows[0]); $i < $n; $i++) { ?>
										<th class="<?php echo $vik->getAdminThClass(); ?>" width="<?php echo floor(100 / $n); ?>%" style="text-align: center;">
											
											<select name="column[<?php echo $rows[0][$i]; ?>]" class="vap-column-sel" data-col-index="<?php echo $i; ?>">
												<option></option>
												<?php
												foreach ($columns as $col)
												{
													$req = $col->required ? ' *' : '';

													?>
													<option value="<?php echo $col->name; ?>" data-required="<?php echo $col->required; ?>">
														<?php echo $col->label . $req; ?>
													</option>
													<?php
												}
												?>
											</select>

											<br /><br />

											<span class="col-import-title"><?php echo ucwords($rows[0][$i]); ?></span>

										</th>
									<?php } ?>
								</tr>
							<?php echo $vik->closeTableHead(); ?>

							<?php
							for ($i = 1, $n = count($rows); $i < $n; $i++)
							{
								?>
								<tr class="row<?php echo ($i % 2); ?>">

									<?php
									for ($j = 0; $j < count($rows[$i]); $j++)
									{
										?>
										<td style="text-align: center;" data-col-index="<?php echo $j; ?>">
											<?php echo $rows[$i][$j]; ?>
										</td>
										<?php
									}
									?>
									
								</tr>
								<?php
							}
							?>

						</table>
					</div>

					<div style="text-align: center;">
						<small><?php echo JText::sprintf('VAPIMPORTTABLEFOOTER', count($rows) - 1); ?></small>
					</div>

				<?php echo $vik->closeEmptyFieldset(); ?>
			</div>
		
		<?php
		echo $vik->closeCard();
	}
	else
	{
		echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	}
	?>

	<?php echo JHtml::fetch('form.token'); ?>

	<?php
	foreach ($this->args as $k => $v)
	{
		?>
		<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $this->escape($v); ?>" />
		<input type="hidden" name="import_args[<?php echo $k; ?>]" value="<?php echo $this->escape($v); ?>" />
		<?php
	}
	?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="import_type" value="<?php echo $this->type; ?>" />
	
	<?php echo JHtml::fetch('form.token'); ?>
</form>

<?php
JText::script('VAPPAYMENTPOSOPT1');
?>

<script>

	var COLUMNS_MAP = <?php echo json_encode($columns); ?>;

	jQuery(function($) {

		$('.vap-column-sel').select2({
			placeholder: Joomla.JText._('VAPPAYMENTPOSOPT1').toLowerCase(),
			allowClear: true,
			width: '100%',
		});

		$('.vap-column-sel').on('change', function() {
			var index = $(this).data('col-index');
			var value = $(this).val();

			var column = null;

			if (COLUMNS_MAP.hasOwnProperty(value)) {
				column = COLUMNS_MAP[value];
			}

			$('td[data-col-index="' + index + '"]').each(function() {
				if ($(this).data('value') === undefined)
				{
					$(this).data('value', $(this).html().trim());
				}

				var value = $(this).data('value');

				var style = '';
				var title = '';

				if (column != null)
				{
					if (value.length == 0)
					{
						value = column.default;
						title = 'Default';
						style += 'color:#2b3ce3;';
					}

					if (column.options.hasOwnProperty(value))
					{
						value = column.options[value];
						style += 'font-style:italic;';
					}

					if (style.length || title.length)
					{
						value = '<span style="' + style + '" title="' + title + '">' + value + '</span>';
					}
				}

				$(this).html(value);

				if (title && value)
				{
					$(this).find('span').tooltip();
				}

			});

			var old = $(this).data('old-value');
			var val = $(this).val();

			// disable this option on all the other select

			if (val && val.length)
			{
				$('.vap-column-sel')
					.not(this)
					.find('option[value="' + val + '"]')
					.attr('disabled', true);
			}

			// enable old option on all the other select

			$('.vap-column-sel')
				.not(this)
				.find('option[value="' + old + '"]')
				.attr('disabled', false);

			$(this).data('old-value', val);
		});

		// try to auto-populate the assignments
		$('.col-import-title').each(function() {
			// find select before the column title
			let select = $(this).prevAll('select');

			// extract column title
			let title = $(this).html().trim().toLowerCase();

			// search for a compatible option
			$(select).find('option:not(disabled)').each(function() {
				// extract option name
				let optionText = $(this).html().replace(/\*/g, '').trim().toLowerCase();

				if (optionText == title) {
					// matching option, auto-select it
					$(select).select2('val', $(this).val()).trigger('change');
				}
			});
		});

	});

	// validate

	var validator = new VikFormValidator('#adminForm');

	Joomla.submitbutton = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate(customValidation)) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

	function customValidation() {
		var ok = true;

		jQuery('select.vap-column-sel').first().find('option[data-required="1"]').each(function() {
			var val = jQuery(this).val();

			if (!hasRequiredValue(val)) {
				ok = false;
				return false;
			}
		});

		if (!ok) {
			jQuery('#custom-error').show();
		} else {
			jQuery('#custom-error').hide();
		}

		return ok;
	}

	function hasRequiredValue(val) {
		var has = false;

		jQuery('.vap-column-sel').each(function() {
			if (jQuery(this).val() == val) {
				has = true;
				return false;
			}
		});

		return has;
	}

</script>
