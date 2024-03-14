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

<i class="fas fa-file-code hasTooltip" data-src="<?php echo $uri; ?>" title="<?php echo $this->escape($name); ?>"></i>