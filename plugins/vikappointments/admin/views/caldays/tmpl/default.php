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
 * It seems that Joomla stopped loading JS core for
 * the views loaded with tmpl component. We need to force
 * it to let the pagination accessing Joomla object.
 *
 * @since Joomla 3.8.7
 */
JHtml::fetch('behavior.core');

JHtml::fetch('vaphtml.assets.colorpicker');

$vik = VAPApplication::getInstance();

?>

<form action="index.php" method="post" name="adminForm"  id="adminForm">

	<?php
	// load filter bar
	echo $this->loadTemplate('filterbar');
	?>

	<?php echo $vik->openCard('employee_workdays'); ?>

		<div class="span8" style="margin-left: 0px;">
			<?php
			if ($this->filters['layout'] == 'day')
			{
				// load day contents
				echo $this->loadTemplate('day');
			}
			else
			{
				// load calendar content
				echo $this->loadTemplate('calendar');
			}
			?>
		</div>

		<div class="span4" class="cal-sidebar">
			<?php
			// load sidebar content
			echo $this->loadTemplate('sidebar');
			?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="mode" value="<?php echo $this->filters['layout']; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="caldays" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// load modal content
echo $this->loadTemplate('modal');

/**
 * Render inspector to create new appointments.
 * 
 * @since 1.7.4 Changed from modal because on J4 there is a conflict with select2
 */
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'newapp-inspector',
	array(
		'title'       => JText::translate('VAPMAINTITLENEWRESERVATION'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => '<button type="button" class="btn btn-success" data-role="reservation.create">' . JText::translate('VAPNEW') . '</button>',
		'width'       => 400,
	),
	$this->loadTemplate('create_modal')
);
?>

<script>

	Joomla.submitbutton = function(task) {
		if (task == 'backToCal') {
			// unset form data to retrieve the values stored in the user state
			document.adminForm.date.remove();

			document.adminForm.mode.value = '';
			task = 'caldays';
		} else if (task == 'reportsemp') {
			// Include employee ID for being used by reports view.
			// Include from=calendar in order to return to this view when exiting from reports view.
			jQuery('#adminForm').append(
				'<input type="hidden" name="cid[]" value="<?php echo $this->filters['employee']; ?>" />\n' +
				'<input type="hidden" name="from" value="caldays" />\n'
			);

			document.adminForm.task.value = '';
			document.adminForm.view.value = 'reportsemp';
			task = '';
		}

		Joomla.submitform(task, document.adminForm);
	}

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

</script>
