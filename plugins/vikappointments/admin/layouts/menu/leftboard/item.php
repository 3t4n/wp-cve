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
 * @var   boolean  $selected   True whether the item is active.
 * @var   string   $href       The item HREF.
 * @var   string   $icon       The custom icon, if specified.
 * @var   string   $title      The item title.
 */

?>

<div class="item<?php echo $selected ? ' selected' : ''; ?>">
	
	<a href="<?php echo $href; ?>">
		<?php
		if (strlen($icon))
		{
			?><i class="fas fa-<?php echo $icon; ?>"></i><?php
		}

		?><span><?php echo $title; ?></span>
	</a>

</div>