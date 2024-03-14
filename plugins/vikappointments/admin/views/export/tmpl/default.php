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

$vik = VAPApplication::getInstance();

$head = $this->handler->getColumns();
$rows = $this->rows;

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<?php
if (!count($rows))
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'), 'warning', false, array('style' => 'margin-top: 10px;'));
}
else
{
	?>
	<div class="scrollable-hor">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
			<?php echo $vik->openTableHead(); ?>
				<tr>
					<?php
					foreach ($head as $k => $column)
					{
						?>
						<th class="<?php echo $vik->getAdminThClass('nowrap'); ?>" style="text-align: center;">
							<input type="checkbox" name="columns[]" value="<?php echo $k; ?>" id="export_col_<?php echo $k; ?>" checked="checked" />
							
							<label for="export_col_<?php echo $k; ?>">
								<?php echo $column->label; ?>
							</label>
						</th>
						<?php
					}
					?>
				</tr>
			<?php echo $vik->closeTableHead(); ?>
			
			<?php
			for ($i = 0, $n = count($rows); $i < $n; $i++)
			{
				$row = $rows[$i];
				?>
				<tr class="row<?php echo ($i % 2); ?>">
					<?php
					foreach ($head as $k => $label)
					{
						?>
						<td style="text-align: center;">
							<div>
								<?php
								$value = $row[$k];

								$value_no_html = strip_tags((string) $value);
								
								/**
								 * Show the first 64 characters when higher than 80.
								 *
								 * @since 1.7
								 */
								if (strlen($value_no_html) > 80)
								{
									echo trim(mb_substr($value_no_html, 0, 64, 'UTF-8')) . '...';

									if (strlen($value_no_html) > 800)
									{
										// avoid to display more than 800 characters
										$value = trim(mb_substr($value_no_html, 0, 800, 'UTF-8')) . '...';
									}

									// then display a tooltip with the remaining text
									?>
									<i class="fas fa-info-circle hasTooltip" title="<?php echo $this->escape($value); ?>"></i>
									<?php
								}
								else
								{
									echo $value;
								}
								?>
							</div>
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
		<small><?php echo JText::sprintf('VAPEXPORTTABLEFOOTER', $n, $this->handler->getTotalCount()); ?></small>
	</div>
	<?php
}
?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="import_type" value="<?php echo $this->type; ?>" />

	<?php
	foreach ($this->args as $k => $v)
	{
		?>
		<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $this->escape($v); ?>" />
		<input type="hidden" name="import_args[<?php echo $k; ?>]" value="<?php echo $this->escape($v); ?>" />
		<?php
	}

	foreach ($this->cid as $id)
	{
		?>
		<input type="hidden" name="cid[]" value="<?php echo $this->escape($id); ?>" />
		<?php
	}
	
	echo JHtml::fetch(
		'bootstrap.renderModal',
		'jmodal-download',
		array(
			'title'       => JText::translate('VAPMAINTITLEVIEWEXPORT'),
			'closeButton' => true,
			'keyboard'    => false, 
			'bodyHeight'  => 60,
			'footer' 	  => '<button type="button" class="btn btn-success" id="export-btn">' . JText::translate('JAPPLY') . '</button>',
		),
		$this->loadTemplate('params')
	);
	?>

</form>

<style>
	.export-column-disabled > * {
		opacity: 0.4;
	}
</style>

<script>

	jQuery(function($) {
		const columns = $('input[name="columns[]"]');

		// handle change event to toggle the status of the columns
		// to include within the export query
		columns.on('change', function() {
			const checked = $(this).is(':checked');
			const index   = columns.index(this);

			$('#adminForm table tr').each(function() {
				let td = $(this).find('td').eq(index);

				if (td.length && checked) {
					td.removeClass('export-column-disabled');
				} else {
					td.addClass('export-column-disabled');
				}
			});

			// cache column status
			cacheColumnStatus($(this).val(), checked);
		});

		const cacheKey = 'export.<?php echo $this->type; ?>.disabled';

		/**
		 * Helper function used to cache the status of the disabled
		 * columns within the browser session storage.
		 */
		const cacheColumnStatus = (column, status) => {
			if (typeof sessionStorage === 'undefined') {
				// the browser doesn't support session storage
				return false;
			}

			// get list of disabled columns
			let disabled = getDisabledColumns();

			// find index of columns within the list, if any
			let index = disabled.indexOf(column);

			if (status) {
				// enabled, so the column must be removed from the list
				if (index !== -1) {
					disabled.splice(index, 1);
				}
			} else {
				// disabled, so the column must be included within the list
				if (index === -1) {
					disabled.push(column);
				}
			}

			// save cache
			sessionStorage.setItem(cacheKey, disabled.join(','));
		};

		/**
		 * Helper function used to retrieve the disabled
		 * columns from the browser session storage.
		 */
		const getDisabledColumns = () => {
			if (typeof sessionStorage === 'undefined') {
				// the browser doesn't support session storage
				return [];
			}

			// get list of disabled columns
			let disabled = sessionStorage.getItem(cacheKey);

			if (!disabled) {
				// create new array from scratch
				disabled = [];
			} else {
				// create array from string
				disabled = disabled.split(/\s*,\s*/g);
			}

			return disabled;
		};

		// auto-disable cached columns during page loading
		getDisabledColumns().forEach((column) => {
			$('input[name="columns[]"][value="' + column + '"]')
				.prop('checked', false)
				.trigger('change');
		});
	});

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

	Joomla.submitbutton = function(task) {
		if (task == 'export') {
			vapOpenJModal('download', null, true);
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

</script>
