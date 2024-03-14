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

?>
<div class="vri-tplfile-preview-wrap">
<?php
if (!empty($this->htmlpreview)) {
	echo $this->htmlpreview;
} else {
	?>
	<p class="err"><?php echo JText::translate('VRITMPLFILENOTREAD'); ?></p>
	<?php
}
?>
</div>
