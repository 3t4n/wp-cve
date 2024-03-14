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
 * @var  array  $employees  A list of employees.
 */
extract($displayData);

foreach ($employees as $employee)
{
	?>
	<div class="vaptimeline-empblock" data-id="<?php echo $employee['id']; ?>">

		<h3><?php echo $employee['name']; ?></h3>
		
		<div class="vaptimelinewt">
			<?php echo $employee['timeline']; ?>
		</div>
		
	</div>
	<?php
}
