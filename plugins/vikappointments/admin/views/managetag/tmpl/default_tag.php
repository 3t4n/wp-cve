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

$vik = VAPApplication::getInstance();

?>

<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEGROUP2') . '*'); ?>
	<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $this->escape($this->tag->name); ?>" size="64" />
<?php echo $vik->closeControl(); ?>
