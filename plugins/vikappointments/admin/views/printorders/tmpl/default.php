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

<div class="vap-printer-layout">

	<div class="vap-print-orders-container">
		<?php
		foreach ($this->rows as $r)
		{	
			// save order as class property for being used in sub-layout
			$this->orderDetails = $r;

			// display order details
			echo $this->loadTemplate('order');	
		}
		?>
	</div>

</div>

<script>
	
	jQuery(function($) {
		window.print();
	});
	
</script>
