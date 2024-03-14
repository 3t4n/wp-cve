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

echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-purchinfo',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATIONTITLE1'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '', // it will be filled dinamically
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

		$('div.time-starts').on('click', function() {
			var ids = $(this).data('id');

			if (isNaN(ids)) {
				ids = ids.split(',');
			} else {
				ids = [ids];
			}

			url = 'index.php?option=com_vikappointments&view=orderinfo&tmpl=component';

			for (var i = 0; i < ids.length; i++) {
				url += '&cid[]=' + ids[i];
			}

			vapUpdateModalButtons(ids);

			vapOpenJModal('purchinfo', url, true);
		});

		editButton.on('click', function() {
			// get selected ID
			var id = $(this).attr('data-id');

			if (!id.length) {
				return false;
			}

			// go to management page
			document.location.href = 'index.php?option=com_vikappointments&task=reservation.edit&from=caldays&cid[]=' + id;
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
				document.location.href = '<?php echo $vik->addUrlCSRF('index.php?option=com_vikappointments&task=reservation.delete&from=caldays'); ?>&cid[]=' + id;
			}
		});

	});

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

</script>
