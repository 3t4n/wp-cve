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

// check if the status color is bright or dark
if (JHtml::fetch('vaphtml.color.light', $status->color))
{
	// we have a light background, so we need to use a darker foreground
	$fg_color = '333';
}
else
{
	// we have a dark background, so we need to use a lighter foreground
	$fg_color = 'fff';
}

?>

<span class="badge" style="background-color: #<?php echo $status->color; ?>; color: #<?php echo $fg_color; ?>;">
	<?php echo $status->name; ?>
</span>
