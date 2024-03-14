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

<div class="vikpayparamdiv">
	<?php echo $vik->alert(JText::translate('VAPMANAGEPAYMENT9')); ?>
</div>

<div id="vikparamerr" style="display: none;">
	<?php echo $vik->alert(JText::translate('VAP_AJAX_GENERIC_ERROR'), 'error'); ?>
</div>
