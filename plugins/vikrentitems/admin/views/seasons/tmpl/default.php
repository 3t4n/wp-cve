<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * @wponly lite - placeholder view for Free version
 */
?>
<div class="vri-free-nonavail-wrap">
	<div class="vri-free-nonavail-inner">
		<div class="vri-free-nonavail-logo">
			<img src="<?php echo VRI_SITE_URI; ?>resources/vikwp_free_logo.png" />
		</div>
		<div class="vri-free-nonavail-expl">
			<h3><?php echo JText::translate('VRMENUTENSEVEN'); ?></h3>
			<p class="vri-free-nonavail-descr"><?php echo JText::translate('VRIFREESEASONSDESCR'); ?></p>
			<p class="vri-free-nonavail-footer-descr">
				<button type="button" class="btn vri-free-nonavail-gopro" onclick="document.location.href='admin.php?option=com_vikrentitems&amp;view=gotopro';">
					<?php VikRentItemsIcons::e('rocket'); ?> <span><?php echo JText::translate('VRIGOTOPROBTN'); ?></span>
				</button>
			</p>
		</div>
	</div>
</div>
