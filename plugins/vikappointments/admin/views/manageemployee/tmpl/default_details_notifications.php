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

?>

<!-- NOTIFY BOOKINGS - Radio Button -->

<?php
$elem_yes = $vik->initRadioElement('', '', $employee->notify == 1);
$elem_no  = $vik->initRadioElement('', '', $employee->notify == 0);

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE9'));
echo $vik->radioYesNo('notify', $elem_yes, $elem_no, false);
echo $vik->closeControl();
?>

<!-- QUICK CONTACT - Radio Button -->

<?php
$elem_yes = $vik->initRadioElement('', '', $employee->quick_contact == 1);
$elem_no  = $vik->initRadioElement('', '', $employee->quick_contact == 0);

$help = $vik->createPopover(array(
	'title' 	=> JText::translate('VAPMANAGEEMPLOYEE17'),
	'content'	=> JText::translate('VAPMANAGEEMPLOYEE17_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE17') . $help);
echo $vik->radioYesNo('quick_contact', $elem_yes, $elem_no, false);
echo $vik->closeControl();
?>
