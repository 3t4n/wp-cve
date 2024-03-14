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

$noteLayout = new JLayoutFile('blocks.card');

?>

<div class="vap-cards-container cards-user-notes" id="cards-user-notes">

	<!-- ADD PLACEHOLDER -->

	<div class="vap-card-fieldset up-to-2 add add-user-note">
		<div class="vap-card compress">
			<i class="fas fa-plus"></i>
		</div>
	</div>

	<?php
	foreach ($this->usernotes as $note)
	{
		?>
		<div class="vap-card-fieldset up-to-2" id="user-note-fieldset-<?php echo $note->id; ?>">

			<?php
			$displayData = array();

			// reduce card size
			$displayData['class'] = 'compress' . ($note->status ? ' published' : '');

			// fetch primary text
			$displayData['primary'] = $note->title;
		
			// fetch secondary text
			$displayData['secondary'] = JHtml::fetch('date', VAPDateHelper::isNull($note->modifiedon) ? $note->createdon : $note->modifiedon, JText::translate('DATE_FORMAT_LC2'));

			// fetch badge icon
			$displayData['badge'] = '<i class="fas fa-' . ($note->status ? 'eye'  : 'low-vision') . '"></i>';

			// fetch edit button
			$displayData['edit'] = (int) $note->id;

			// render layout
			echo $noteLayout->render($displayData);
			?>

		</div>
		<?php
	}
	?>

</div>

<div style="display:none;" id="user-note-struct">
			
	<?php
	// create structure for records
	$displayData = array();
	$displayData['class']     = 'compress';
	$displayData['primary']   = '';
	$displayData['secondary'] = '';
	$displayData['badge']     = '<i class="fas fa-eye"></i>';
	$displayData['edit']      = true;

	echo $noteLayout->render($displayData);
	?>

</div>

<script>

	(function($) {
		const refreshCard = (elem, data) => {
			data.status = parseInt(data.status);

			if (data.status) {
				$(elem).addClass('published');
			} else {
				$(elem).removeClass('published');
			}

			// update primary text
			$(elem).vapcard('primary', data.title);

			// update secondary text
			$(elem).vapcard('secondary', data.modifiedon_pretty);

			// update badge
			$(elem).vapcard('badge', '<i class="fas fa-' + (data.status ? 'eye' : 'low-vision') + '"></i>');
		}

		$(function() {
			$('.add-user-note').on('click', () => {
				// add user note URL
				let url = 'index.php?option=com_vikappointments&tmpl=component&task=usernote.add&id_parent=<?php echo $this->reservation->id; ?>&group=appointments';

				// open modal
				vapOpenJModal('usernote', url, true);
			});

			$(document).on('click', '.vap-card .card-edit[data-id]', function() {
				// add user note URL
				let url = 'index.php?option=com_vikappointments&tmpl=component&task=usernote.edit&cid[]=' + $(this).attr('data-id');

				// open modal
				vapOpenJModal('usernote', url, true);
			});

			$('button[data-role="usernote.save"]').on('click', function() {
				// trigger click of save button contained in manageusernote view
				window.modalUserNoteSaveButton.click();
			});

			$('#jmodal-usernote').on('hidden', function() {
				// restore default submit function, which might have been
				// replaced by the callback used in manage user note view
				Joomla.submitbutton = ManageReservationSubmitButtonCallback;
				
				// check if the user note was saved
				if (window.modalSavedUserNoteData) {
					let data = window.modalSavedUserNoteData;

					// get assigned element
					let elem = $('#user-note-fieldset-' + data.id);

					if (elem.length == 0) {
						// note created, add card
						var html = $('#user-note-struct').clone().html();

						// create fieldset, append card and insert at the end of the list
						elem = $('<div class="vap-card-fieldset up-to-2" id="user-note-fieldset-' + data.id + '"></div>')
							.append(html)
							.insertAfter($('.vap-card-fieldset.add-user-note').last());

						// update edit button
						elem.vapcard('edit', parseInt(data.id));
					}

					// refresh card details
					refreshCard(elem.find('.vap-card'), data);

					window.modalSavedUserNoteData = null;
				}
			});
		});
	})(jQuery);

</script>