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

?>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=order' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" name="orderform" id="orderform" method="get">

	<div class="vaporderpagediv">

		<div class="vapordertitlediv"><?php echo JText::translate('VAPORDERTITLE1'); ?></div>
		
		<div class="vapordercomponentsdiv">

			<div class="vaporderinputdiv">
				<label class="vaporderlabel" for="vapordnum"><?php echo JText::translate('VAPORDERNUMBER'); ?>:</label>
				<input class="" type="text" id="vapordnum" name="ordnum" size="32" />
			</div>
			
			<div class="vaporderinputdiv">
				<label class="vaporderlabel" for="vapordkey"><?php echo JText::translate('VAPORDERKEY'); ?>:</label>
				<input class="" type="text" id="vapordkey" name="ordkey" size="32" />
			</div>
			
			<div class="vaporderinputdiv">
				<button type="submit" class="vap-btn blue"><?php echo JText::translate('VAPORDERSUBMITBUTTON'); ?></button>
			</div>

		</div>
		
	</div>
	
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="order" />
</form>
