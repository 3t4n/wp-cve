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

<div class="row-fluid">

	<!-- USER NOTES PANEL -->

	<div class="span8">
		<?php
		echo $vik->openEmptyFieldset();
		echo $this->loadTemplate('usernotes_main');
		echo $vik->closeEmptyFieldset();
		?>
	</div>

	<!-- USER NOTES SIDEBAR -->

	<div class="span4 full-width">
		<?php
		echo $vik->openEmptyFieldset();
		echo $this->loadTemplate('usernotes_sidebar');
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"notes","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the "Notes" tab (right-side).
		 *
		 * NOTE: retrieved from "onDisplayViewReservation" hook.
		 *
		 * @since 1.6.6
		 */
		if (isset($this->forms['notes']))
		{
			echo $this->forms['notes'];

			// unset details form to avoid displaying it twice
			unset($this->forms['notes']);
		}

		echo $vik->closeEmptyFieldset();
		?>
	</div>

</div>
