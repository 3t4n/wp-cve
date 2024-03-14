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

JHtml::fetch('formbehavior.chosen');

$vik = VAPApplication::getInstance();

?>

<div class="user-note-box note-draft">
	<div class="textarea-box">
		<textarea placeholder="<?php echo $this->escape(JText::translate('VAPNOTESDRAFTPLACEHOLDER')); ?>" id="area-draft"></textarea>
	</div>

	<div class="tip-box">
		<?php
		echo $vik->createPopover(array(
			// show popover on the left side, otherwise the popover would exceed the screen bounds
			'placement' => 'left',
			'title'     => JText::translate('VAPNOTESDRAFTTITLE'),
			'content'   => JText::translate('VAPNOTESDRAFTHELP'),
		));
		?>
	</div>

	<div class="tags-box">
		<?php
		foreach (JHtml::fetch('vaphtml.admin.tags', 'usernotes') as $tag)
		{
			?>
			<a href="javascript:void(0)" class="toggle-note-tag">
				<?php echo JHtml::fetch('vikappointments.tag', $tag, 'icon', array('data-tag' => $tag->name)); ?>
			</a>
			<?php
		}
		?>
	</div>

	<div class="status-box">
		<?php
		$options = array(
			JHtml::fetch('select.option', 0, 'VAPPRIVATE'),
			JHtml::fetch('select.option', 1, 'VAPPUBLIC'),
		);
		?>
		<select id="note-status-select">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', 0, true); ?>
		</select>
	</div>
</div>

<?php
JText::script('JLIB_APPLICATION_SAVE_SUCCESS');
JText::script('VAP_AJAX_GENERIC_ERROR');
?>

<script>

	(function($) {
		'use strict';

		const draft = {
			id: 0,
			cache: null,
			tags: [],
		};

		const textarea = $('#area-draft');

		// commit changes
		const saveDrafts = () => {
			var content = textarea.val();
			var state   = $('#note-status-select').val(); 

			if (!content.length || content == draft.cache) {
				// nothing to save
				return;
			}

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=usernote.savedraftajax'); ?>',
				{
					id:      draft.id,
					id_user: <?php echo $this->customer->id; ?>,
					status:  state,
					draft:   content,
					tags:    draft.tags,
				},
				(resp) => {
					// keep current ID to update future changes
					draft.id = resp.id;
					// update current text
					draft.cache = content;

					ToastMessage.enqueue({
						status: 1,
						text: Joomla.JText._('JLIB_APPLICATION_SAVE_SUCCESS'),
						delay: 1000,
					});
				},
				(error) => {
					// display error message
					ToastMessage.enqueue({
						status: 0,
						text:   error.responseText || Joomla.JText._('VAP_AJAX_GENERIC_ERROR'),
					});
				}
			);
		}

		$(function() {
			VikRenderer.chosen('.note-draft');

			// auto-save after 3 seconds
			textarea.on('keyup', VikTimer.debounce('draftautosave', saveDrafts, 3000));

			$('#note-status-select').on('change', function() {
				if (!VikTimer.isRunning('draftautosave') && draft.id) {
					// unset draft cache to force update
					draft.cache = '';
					// update notes
					saveDrafts();
				}
			});

			$('.toggle-note-tag').on('click', function() {
				const icon = $(this).find('[data-tag]');
				let tag = $(icon).data('tag');

				var index = draft.tags.indexOf(tag);

				if (index === -1) {
					// include tag
					draft.tags.push(tag);

					$(this).css('opacity', 1);
				} else {
					// remove tag
					draft.tags.splice(index, 1);

					$(this).css('opacity', 0.4);
				}

				if (!VikTimer.isRunning('draftautosave') && draft.id) {
					// unset draft cache to force update
					draft.cache = '';
					// update notes
					saveDrafts();
				}
			});
		});
	})(jQuery);

</script>
