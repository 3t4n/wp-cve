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

$closure = $this->order;

$config = VAPFactory::getConfig();

?>

<div class="puchinfo-closure" style="padding:10px;">

	<h3><?php echo JText::translate('VAPSTATUSCLOSURE') . ' #' . $closure->id; ?></h3>

	<p>
		<?php
		/**
		 * e.g. "The employee John Smith is closed on YYYY-MM-DD from HH:mm to HH:mm
		 */
		echo JText::sprintf(
			'VAPCLOSUREINFOMESSAGE',
			$closure->nickname,
			JHtml::fetch('date', $closure->checkin_ts, JText::translate('DATE_FORMAT_LC1')),
			JHtml::fetch('date', $closure->checkin_ts, $config->get('timeformat')),
			JHtml::fetch('date', VikAppointments::getCheckout($closure->checkin_ts, $closure->duration), $config->get('timeformat'))
		);
		?>
	</p>

</div>
