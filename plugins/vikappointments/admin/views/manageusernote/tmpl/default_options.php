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
				
<!-- ACCESS - Dropdown -->

<?php
$options = array(
	JHtml::fetch('select.option', 0, 'VAPPRIVATE'),
	JHtml::fetch('select.option', 1, 'VAPPUBLIC'),
);

echo $vik->openControl(JText::translate('VAPVISIBILITY')); ?>
	<select name="status" id="vap-access-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $note->status, true); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- NOTIFY CUSTOMER - Checkbox -->

<?php
$control = array();
$control['style'] = $note->status ? '' : 'display:none;';

// Specify a different ID because this page might be rendered also
// while editing a reservation inside an iframe.
// Since WordPress doesn't support iframes, the page will be retrieved via
// AJAX and appended into the document. For this reason, we need to use
// a unique ID to avoid conflicts with the "notifycust" checkbox used 
// by the reservation page.
$yes = $vik->initRadioElement('usernote_notifycust_1', '', false);
$no  = $vik->initRadioElement('usernote_notifycust_0', '', true);

echo $vik->openControl(JText::translate('VAPMANAGERESERVATION24'), 'status-child', $control);
echo $vik->radioYesNo('notifycust', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- TAGS - Dropdown -->

<?php
$tags = array();

foreach (JHtml::fetch('vaphtml.admin.tags', 'usernotes') as $tag)
{
	$tags[] = $tag->text;
}

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPTAGS'),
	'content' => JText::translate('VAPTAGHELP'),
));

echo $vik->openControl(JText::translate('VAPTAGS') . $help); ?>
	<input type="hidden" name="tags" id="vap-tags-sel" value="<?php echo $this->escape($note->tags); ?>" />
<?php echo $vik->closeControl(); ?>

<?php
JText::script('VAPTAGPLACEHOLDER');
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vap-access-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: '90%',
			});

			$('#vap-access-sel').on('change', function() {
				if ($(this).val() == 1) {
					$('.status-child').show();
				} else {
					$('.status-child').hide();
				}
			});

			$('#vap-tags-sel').select2({
				placeholder: Joomla.JText._('VAPTAGPLACEHOLDER'),
				allowClear: true,
				tags: <?php echo json_encode($tags); ?>,
				tokenSeparators: [','],
				width: '90%',
			});
		});
	})(jQuery);

</script>
