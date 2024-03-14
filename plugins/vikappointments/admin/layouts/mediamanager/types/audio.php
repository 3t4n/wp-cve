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
 * @see AppointmentsHelper::getFileProperties();
 */
extract($displayData);

?>

<!-- add icon for file preview -->

<i class="fas fa-file-audio hasTooltip" data-src="<?php echo $uri; ?>" title="<?php echo $this->escape($name); ?>"></i>

<!-- display audio player only under inspector -->

<audio class="inspector-only" style="margin-top: 20px;" preload controls onclick="return false;">
	<source src="<?php echo $uri; ?>"></source>	
</audio>
