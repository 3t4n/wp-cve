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

JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$plugin = $this->plugin;

$vik = VAPApplication::getInstance();

?>

<?php
/**
 * Display plugin description outside of the form because it might use its own
 * form to post the payload to the example end-point.
 */
?>

<?php echo $vik->openCard(); ?>
	
	<div class="span12">
		<?php echo $vik->openFieldset($plugin->getTitle() . ' : ' . $plugin->getName() . '.php'); ?>

			<div><?php echo $plugin->getDescription(); ?></div>

		<?php echo $vik->closeFieldset(); ?>
	</div>

<?php echo $vik->closeCard(); ?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />

</form>


