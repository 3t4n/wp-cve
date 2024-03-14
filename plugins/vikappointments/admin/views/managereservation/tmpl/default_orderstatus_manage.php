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

$reservation = $this->reservation;

$vik = VAPApplication::getInstance();

?>

<!-- STATUS - Dropdown -->

<?php
$statuses = JHtml::fetch('vaphtml.admin.statuscodes', 'appointments');

echo $vik->openControl(JText::translate('VAPMANAGERESERVATION19')); ?>
	<select class="vap-status-sel">
		<?php echo JHtml::fetch('select.options', $statuses, 'value', 'text', $reservation->status); ?>
	<select>
<?php echo $vik->closeControl(); ?>

<!-- COMMENT - Textarea -->

<?php
// disable only if we are editing
$disabled = $reservation->status ? ' disabled' : '';

echo $vik->openControl(JText::translate('VAPMANAGEREVIEW9')); ?>
	<textarea name="comment" style="height: 100px;"<?php echo $disabled; ?>></textarea>
<?php echo $vik->closeControl(); ?>

<script>

	jQuery(function($) {
		$('.vap-status-sel').on('change', function() {
			var disabled = $(this).val() == '<?php echo $reservation->status; ?>';
			$('textarea[name="comment"]').attr('disabled', disabled);
		});
	});

</script>
