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

<!-- SERVICES - Select -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGECOUPON17'));

// load services and group them
$options = JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = false, $group = true);

// create dropdown attributes
$params = array(
	'id'          => 'vap-services-sel',
	'group.items' => null,
	'list.select' => $this->coupon->services,
	'list.attr'   => array('multiple' => true),
);

// render select
echo JHtml::fetch('select.groupedList', $options, 'services[]', $params);

echo $vik->closeControl();
?>

<!-- EMPLOYEES - Select -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON18'),
	'content' => JText::translate('VAPMANAGECOUPON18_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON18') . $help);

// load employees and group them
$options = JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = false, $group = true);

// create dropdown attributes
$params = array(
	'id'          => 'vap-employees-sel',
	'group.items' => null,
	'list.select' => $this->coupon->employees,
	'list.attr'   => array('multiple' => true),
);

// render select
echo JHtml::fetch('select.groupedList', $options, 'employees[]', $params);

echo $vik->closeControl();
?>

<?php
JText::script('VAPMANAGECOUPON19');
JText::script('VAPMANAGECOUPON20');
?>

<script>

	jQuery(function($) {

		$('#vap-services-sel').select2({
			placeholder: Joomla.JText._('VAPMANAGECOUPON19'),
			allowClear: true,
			width: '90%',
		});

		$('#vap-employees-sel').select2({
			placeholder: Joomla.JText._('VAPMANAGECOUPON20'),
			allowClear: true,
			width: '90%',
		});

	});

</script>