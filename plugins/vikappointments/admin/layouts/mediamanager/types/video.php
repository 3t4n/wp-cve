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

<i class="fas fa-file-video hasTooltip" data-src="<?php echo $uri; ?>" title="<?php echo $this->escape($name); ?>"></i>

<!-- display video player only under inspector -->

<video class="inspector-only" style="margin-top: 20px;" controls onclick="return false;">
	<source src="<?php echo $uri; ?>"></source>	
</video>
