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

<div class="vaporderboxcontent parent-order">

	<!-- BOX TITLE -->
				
	<div class="vap-order-first">

		<h3 class="vaporderheader vap-head-first"><?php echo JText::translate('VAPMANAGERESERVATIONTITLE4'); ?></h3>

	</div>

	<!-- CONTENT -->

	<div class="vapordercontentinfo">

		<?php
		// iterate all notes that belong to the parent order
		foreach ($this->order->getUserNotes($this->order->id) as $note)
		{
			// keep track of the current note
			$this->itemNote = $note;

			// display the user notes block through an apposite template
			// to take advantage of reusability
			echo $this->loadTemplate('usernote_block');
		}
		?>

	</div>

</div>