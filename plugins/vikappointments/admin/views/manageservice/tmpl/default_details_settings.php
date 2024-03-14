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

$service = $this->service;

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

?>

<!-- MAIL ATTACHMENT - File -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECONFIG49'),
	'content' => JText::translate('VAPMANAGECONFIG49_DESC'),
));

// define media manager options
$options = array(
	'path'     => VAPMAIL_ATTACHMENTS,
	'multiple' => true,
	'filter'   => false,
	'preview'  => false,
	'icon'     => 'fas fa-upload',
);

echo $vik->openControl(JText::translate('VAPMANAGECONFIG49') . $help);
echo JHtml::fetch('vaphtml.mediamanager.field', 'attachments', $service->attachments, 'vap-mail-attach', $options);
echo $vik->closeControl();
?>

<!-- QUICK CONTACT - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->quick_contact == 1);
$no  = $vik->initRadioElement('', '', $service->quick_contact == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE17'),
	'content' => JText::translate('VAPMANAGESERVICE17_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE17') . $help);
echo $vik->radioYesNo('quick_contact', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- ENABLE ZIP - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->enablezip == 1);
$no  = $vik->initRadioElement('', '', $service->enablezip == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE24'),
	'content' => JText::translate('VAPMANAGESERVICE24_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE24') . $help);
echo $vik->radioYesNo('enablezip', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- USE RECURRENCE - Checkbox -->

<?php
/**
 * Display recurrence setting only if globally enabled.
 *
 * @since 1.6.3
 */
if ($config->getBool('enablerecur'))
{
	$yes = $vik->initRadioElement('', '', $service->use_recurrence == 1);
	$no  = $vik->initRadioElement('', '', $service->use_recurrence == 0);

	$help = $vik->createPopover(array(
		'title'   => JText::translate('VAPMANAGESERVICE27'),
		'content' => JText::translate('VAPMANAGESERVICE27_DESC'),
	));

	echo $vik->openControl(JText::translate('VAPMANAGESERVICE27') . $help);
	echo $vik->radioYesNo('use_recurrence', $yes, $no, false);
	echo $vik->closeControl();
}
?>

