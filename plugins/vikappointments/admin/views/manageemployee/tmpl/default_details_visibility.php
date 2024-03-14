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

$employee = $this->employee;

$vik = VAPApplication::getInstance();

$date_format = VAPFactory::getConfig()->get('dateformat');

?>

<!-- GROUP - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 0, JText::translate('VAPFILTERCREATENEW')),
);

$options = array_merge($options, JHtml::fetch('vaphtml.admin.groups', 2));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE26')); ?>
	<select name="id_group" id="vap-group-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $employee->id_group ? $employee->id_group : ''); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- CREATE NEW GROUP - Text -->

<?php echo $vik->openControl('', 'create-group-control', array('style' => 'display:none;')); ?>
	<input type="text" name="group_name" placeholder="<?php echo $this->escape(JText::translate('VAPMANAGEGROUP2')); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- LISTABLE - Radio Button -->

<?php
$yes = $vik->initRadioElement('', '', $employee->listable == 1, 'onClick="jQuery(\'.vapactiverow\').show();"');
$no  = $vik->initRadioElement('', '', $employee->listable == 0, 'onClick="jQuery(\'.vapactiverow\').hide();"');

$help = $vik->createPopover(array(
	'title' 	=> JText::translate('VAPMANAGEEMPLOYEE18'),
	'content'	=> JText::translate('VAPMANAGEEMPLOYEE18_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE18') . $help);
echo $vik->radioYesNo('listable', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- ACTIVE TO - Custom -->

<?php
if ($this->hasSubscr)
{
	$active_to_type = 'date';

	if ($employee->active_to == 0)
	{
		$active_to_type = 'pending';
	}
	else if ($employee->active_to == -1)
	{
		$active_to_type = 'lifetime';
	}

	$options = array(
		JHtml::fetch('select.option', 'date', 'VAPINVDATE'),
		JHtml::fetch('select.option', 'pending', 'VAPSTATUSPENDING'),
		JHtml::fetch('select.option', 'lifetime', 'VAPSUBSCRTYPE5'),
	);

	$control = array();
	$control['style'] = $employee->listable == 0 ? 'display: none;' : '';
	
	$help = $vik->createPopover(array(
		'title'		=> JText::translate('VAPMANAGEEMPLOYEE27'),
		'content'	=> JText::translate('VAPMANAGEEMPLOYEE27_DESC'),
	));

	echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE27') . $help, 'vapactiverow', $control); ?>
		<select name="active_to_type" id="vap-activeto-sel">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $active_to_type, true); ?>
		</select>

		<div class="vapactivetosp" style="margin-top: 5px;<?php echo ($employee->active_to <= 0 ? 'display: none;' : ''); ?>">
			<?php
			$active_date = $employee->active_to <= 0 ? '' : VAPDateHelper::sql2date($employee->active_to_date);
			
			/**
			 * Allow time selection to avoid resetting the expiration to the midnight.
			 *
			 * @since 1.7
			 */
			echo $vik->calendar($active_date, 'active_to', 'vapactivetodate', null, array('showTime' => true));
			?>
		</div>
	<?php
	echo $vik->closeControl();
}
else
{
	/**
	 * In case there are no subscriptions available, we need to 
	 * have the employee lifetime published.
	 *
	 * @since 1.6.2
	 */
	?>
	<input type="hidden" name="active_to_type" value="lifetime" />
	<?php
}
?>

<script>

	jQuery(function($) {
		
		$('#vap-group-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
		});

		$('#vap-activeto-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: '90%',
		});

		$('#vap-group-sel').on('change', function() {
			var group = $('input[name="group_name"]');

			if (parseInt($(this).val()) === 0) {
				$('.create-group-control').show();
				group.focus();
				validator.registerFields(group);
			} else {
				$('.create-group-control').hide();
				validator.unregisterFields(group);
				group.val('');
			}
		});
		
		$('#vap-activeto-sel').on('change', function() {
			var val = $(this).val();

			if (val == 'date') {
				$('.vapactivetosp').show();
				$('#vapactivetodate').val('');
				$('#vapactivetodate').attr('data-alt-value', '');
			} else {
				$('.vapactivetosp').hide();
				
				if (val == 'pending') {
					$('#vapactivetodate').val(0);
					$('#vapactivetodate').attr('data-alt-value', 0);
				} else {
					$('#vapactivetodate').val(-1);
					$('#vapactivetodate').attr('data-alt-value', -1);
				}
			}
		});

	});

</script>
