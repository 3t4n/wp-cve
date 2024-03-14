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

?>
			
<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPEXPORTRES1')); ?>
	<input type="text" name="filename" value="" size="32" />
<?php echo $vik->closeControl(); ?>

<!-- EXPORT CLASS - Select -->

<?php
$elements = array();
$elements[] = JHtml::fetch('select.option', '', '');

foreach ($this->drivers as $k => $v)
{
	$elements[] = JHtml::fetch('select.option', $k, $v);
}

echo $vik->openControl(JText::translate('VAPEXPORTRES2') . '*'); ?>
	<select name="driver" class="required" id="vap-driver-sel">
		<?php echo JHtml::fetch('select.options', $elements); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- DATE FROM - Calendar -->

<?php
echo $vik->openControl(JText::translate('VAPEXPORTRES3'));
echo $vik->calendar($this->data->fromdate, 'fromdate', 'vap-date-from');
echo $vik->closeControl();
?>

<!-- DATE TO - Calendar -->

<?php
echo $vik->openControl(JText::translate('VAPEXPORTRES4'));
echo $vik->calendar($this->data->todate, 'todate', 'vap-date-from');
echo $vik->closeControl();
?>

<!-- EMPLOYEE - Select -->

<?php
if ($this->data->type == 'appointment')
{
	echo $vik->openControl(JText::translate('VAPEXPORTRES5'));
	
	// load employees and group them
	$options = JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = '', $group = true);

	// create dropdown attributes
	$params = array(
		'id'          => 'vap-employees-sel',
		'group.items' => null,
		'list.select' => $this->data->id_employee,
	);

	// render select
	echo JHtml::fetch('select.groupedList', $options, 'id_employee', $params);

	echo $vik->closeControl();
}
?>

<?php
JText::script('VAPFILTERSELECTFILE');
JText::script('VAPFILTERSELECTEMPLOYEE');
?>

<script>

	jQuery(function($) {

		$('#vap-driver-sel').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTFILE'),
			allowClear: false,
			width: '90%',
		});

		$('#vap-employees-sel').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTEMPLOYEE'),
			allowClear: true,
			width: '90%',
		});

	});

</script>
