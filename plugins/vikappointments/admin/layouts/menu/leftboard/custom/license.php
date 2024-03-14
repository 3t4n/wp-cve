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

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   boolean  $pro  True if there is an active PRO license.
 */

?>

<div class="license-box custom <?php echo $pro ? 'is-pro' : 'get-pro'; ?>">
	
	<?php
	if (!$pro)
	{
		?>
			<a href="admin.php?page=vikappointments&view=gotopro">
				<i class="fas fa-rocket"></i>
				<span><?php _e('Upgrade to PRO', 'vikappointments'); ?></span>
			</a>
		<?php
	}
	else
	{
		?>
		<a href="admin.php?page=vikappointments&view=gotopro">
			<i class="fas fa-trophy"></i>
			<span><?php _e('PRO Version', 'vikappointments'); ?></span>
		</a>
		<?php
	}
	?>

</div>