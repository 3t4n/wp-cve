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

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewService","key":"assoc","type":"field"} -->

<?php
/**
 * Look for any additional fields to be pushed within
 * the "Assignments" fieldset.
 *
 * @since 1.6.6
 */
if (isset($forms['assoc']))
{
	?>
	<div class="row-fluid">
		<div class="span12">
			<?php
			echo $vik->openEmptyFieldset();
			echo $forms['assoc'];
			// unset assoc form to avoid displaying it twice
			unset($forms['assoc']);
			echo $vik->closeEmptyFieldset();
			?>
		</div>
	</div>
	<?php
}
?>

<div class="row-fluid">

	<div class="span6">
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGESERVICE13'),
			'content' => JText::translate('VAP_EDIT_SORT_DRAG_DROP'),
		));

		echo $vik->openFieldset(JText::translate('VAPMANAGESERVICE13') . $help);
		echo $this->loadTemplate('assoc_employees');
		echo $vik->closeFieldset();
		?>
	</div>

	<div class="span6">
		<?php
		echo $vik->openFieldset(JText::translate('VAPMANAGESERVICE11'));
		echo $this->loadTemplate('assoc_options');
		echo $vik->closeFieldset();
		?>
	</div>

</div>
