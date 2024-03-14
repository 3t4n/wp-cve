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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.select2');

$vik = VAPApplication::getInstance();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<!-- SEARCH FILTERS -->

	<?php echo $this->loadTemplate('filters'); ?>
	
	<?php echo $vik->openCard(); ?>

		<!-- CALENDAR -->

		<?php echo $this->loadTemplate('calendar'); ?>

		<!-- TIMELINE -->

		<?php echo $this->loadTemplate('timeline'); ?>

		<!-- RESERVATIONS -->

		<?php echo $this->loadTemplate('reservations'); ?>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="day" value="" id="vapdayselected" />
	<input type="hidden" name="hour" value="" />
	<input type="hidden" name="min" value="" /> 
	
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="calendar" />
	<input type="hidden" name="from" value="calendar" />
</form>

<?php
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-respinfo',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATIONTITLE1'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'      => '<button type="button" class="btn btn-danger" data-role="reservation.delete" data-id="" style="float:left;">' . JText::translate('VAPDELETE') . '</button>'
					   . '<button type="button" class="btn btn-success" data-role="reservation.edit" data-id="">' . JText::translate('VAPEDIT') . '</button>',
	)
);

JText::script('VAPRESERVATIONREMOVEMESSAGE');
?>

<script>

	jQuery(function($) {
		var editButton   = $('button[data-role="reservation.edit"]');
		var deleteButton = $('button[data-role="reservation.delete"]');

		editButton.on('click', function() {
			// get selected ID
			var id = $(this).attr('data-id');

			if (!id.length) {
				return false;
			}

			// go to management page
			document.location.href = 'index.php?option=com_vikappointments&task=reservation.edit&from=calendar&cid[]=' + id;
		});

		deleteButton.on('click', function() {
			// get selected ID
			var id = $(this).attr('data-id');

			if (!id.length) {
				return false;
			}

			// ask for confirmation before delete
			var r = confirm(Joomla.JText._('VAPRESERVATIONREMOVEMESSAGE'));

			if (r) {
				// delete reservation
				document.location.href = '<?php echo $vik->addUrlCSRF('index.php?option=com_vikappointments&task=reservation.delete&from=calendar'); ?>&cid[]=' + id;
			}
		});
	});

	function displayDetailsView(ids) {
		// build iframe URL
		let url = 'index.php?option=com_vikappointments&view=orderinfo&tmpl=component';

		ids.forEach((id) => {
			url += '&cid[]=' + id;
		});

		// update modal buttons
		vapUpdateModalButtons(ids);

		// fade modal
		vapOpenJModal('respinfo', url, true);
	}

	function vapUpdateModalButtons(ids) {
		if (ids && !Array.isArray(ids)) {
			ids = [ids];
		}

		var editButton   = jQuery('button[data-role="reservation.edit"]');
		var deleteButton = jQuery('button[data-role="reservation.delete"]');

		if (ids && ids.length == 1) {
			deleteButton.attr('data-id', ids[0]).prop('disabled', false);
			editButton.attr('data-id', ids[0]).prop('disabled', false);
		} else {
			deleteButton.attr('data-id', '').prop('disabled', true);
			editButton.attr('data-id', '').prop('disabled', true);
		}
	}

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

</script>
