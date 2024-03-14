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

$thumb = $this->thumb;

$vik = VAPApplication::getInstance();

?>

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA18')); ?>
	<span class="control-text-value">
		<span class="badge badge-info"><?php echo $thumb['size']; ?></span>
		&nbsp;
		<span class="badge badge-success"><?php echo $thumb['width'] . 'x' . $thumb['height'] . ' pixel'; ?></span>
	</span>
<?php echo $vik->closeControl(); ?>

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA14')); ?>
	<span class="control-text-value badge badge-important"><?php echo $thumb['creation']; ?></span>
<?php echo $vik->closeControl(); ?>

<div class="control">
	<img src="<?php echo VAPMEDIA_SMALL_URI . $thumb['name'] . '?' . time(); ?>" />
</div>