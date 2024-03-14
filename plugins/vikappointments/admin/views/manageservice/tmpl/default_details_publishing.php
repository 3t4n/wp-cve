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

?>

<!-- PUBLISHED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->published == 1);
$no  = $vik->initRadioElement('', '', $service->published == 0);

echo $vik->openControl(JText::translate('VAPMANAGESERVICE6'));
echo $vik->radioYesNo('published', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- START PUBLISHING - Date -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGESERVICE29'));
echo $vik->calendar(VAPDateHelper::sql2date($service->start_publishing), 'start_publishing', 'vap-startpub-date', null, array('showTime' => true));
echo $vik->closeControl();
?>

<!-- END PUBLISHING - Date -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGESERVICE30'));
echo $vik->calendar(VAPDateHelper::sql2date($service->end_publishing), 'end_publishing', 'vap-endpub-date', null, array('showTime' => true));
echo $vik->closeControl();
?>
