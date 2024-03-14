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

$status = $this->status;

$vik = VAPApplication::getInstance();

?>

<!-- APPROVED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->approved == 1);
$no  = $vik->initRadioElement('', '', $status->approved == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPSTATUSCODEROLE_APPROVED'),
	'content' => JText::translate('VAPSTATUSCODEROLE_APPROVED_HELP'),
));

echo $vik->openControl(JText::translate('VAPSTATUSCODEROLE_APPROVED') . $help);
echo $vik->radioYesNo('approved', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- RESERVED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->reserved == 1);
$no  = $vik->initRadioElement('', '', $status->reserved == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPSTATUSCODEROLE_RESERVED'),
	'content' => JText::translate('VAPSTATUSCODEROLE_RESERVED_HELP'),
));

echo $vik->openControl(JText::translate('VAPSTATUSCODEROLE_RESERVED') . $help);
echo $vik->radioYesNo('reserved', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- EXPIRED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->expired == 1);
$no  = $vik->initRadioElement('', '', $status->expired == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPSTATUSCODEROLE_EXPIRED'),
	'content' => JText::translate('VAPSTATUSCODEROLE_EXPIRED_HELP'),
));

echo $vik->openControl(JText::translate('VAPSTATUSCODEROLE_EXPIRED') . $help);
echo $vik->radioYesNo('expired', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- CANCELLED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->cancelled == 1);
$no  = $vik->initRadioElement('', '', $status->cancelled == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPSTATUSCODEROLE_CANCELLED'),
	'content' => JText::translate('VAPSTATUSCODEROLE_CANCELLED_HELP'),
));

echo $vik->openControl(JText::translate('VAPSTATUSCODEROLE_CANCELLED') . $help);
echo $vik->radioYesNo('cancelled', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- PAID - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->paid == 1);
$no  = $vik->initRadioElement('', '', $status->paid == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPSTATUSCODEROLE_PAID'),
	'content' => JText::translate('VAPSTATUSCODEROLE_PAID_HELP'),
));

echo $vik->openControl(JText::translate('VAPSTATUSCODEROLE_PAID') . $help);
echo $vik->radioYesNo('paid', $yes, $no, false);
echo $vik->closeControl();
?>
