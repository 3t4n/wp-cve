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

if (!JFactory::getUser()->guest)
{
	?>
	<div class="vaporder-backbox">
		<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=allorders' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" class="vap-btn blue">
			<?php echo JText::translate('VAPALLORDERSBUTTON'); ?>
		</a>
	</div>
	<?php
}
