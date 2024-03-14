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

/**
 * Layout variables
 * -----------------
 * @param 	object 	 status  Holds the details of the status code.
 */
extract($displayData);

?>

<b style="color: #<?php echo $status->color; ?>; text-transform: uppercase;">
	<?php echo $status->name; ?>
</b>
