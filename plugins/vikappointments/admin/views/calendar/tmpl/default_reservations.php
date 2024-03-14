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

<div class="vapreservationslistdiv">
	
</div>

<script>

	function getReservationsOnDay(date) {
		// prepare request data
		let data = {
			date:   date,
			id_emp: <?php echo (int) $this->filters['id_emp']; ?>,
			id_ser: <?php echo (int) $this->filters['id_ser']; ?>,
		};
		
		jQuery('.vapreservationslistdiv').html('');
				
		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=calendar.appointmentsajax'); ?>',
			data,
			(resp) => {
				jQuery('.vapreservationslistdiv').html(resp);
			},
			(err) => {
				alert(err.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));
			}
		);
	}

</script>
