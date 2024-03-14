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

$note = $this->note;

$vik = VAPApplication::getInstance();

?>
				
<!-- TITLE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEREVIEW2')); ?>
	<input type="text" name="title" class="input-xxlarge input-large-text" value="<?php echo $this->escape($note->title); ?>" size="64" />
<?php echo $vik->closeControl(); ?>
