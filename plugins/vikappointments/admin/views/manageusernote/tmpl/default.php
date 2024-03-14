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
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$note = $this->note;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewUsernote". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm" style="<?php echo $this->isTmpl ? 'padding:10px;' : ''; ?>">

	<?php
	if ($this->isTmpl)
	{
		?>
		<div class="btn-toolbar" style="display:none;">
			<div class="btn-group pull-left">
				<button type="button" class="btn btn-success" name="tmplSaveButton" onclick="vapValidateFieldsAndDisableButton(this);">
					<i class="icon-apply"></i>&nbsp;<?php echo JText::translate('VAPSAVE'); ?>
				</button>
			</div>
		</div>
		<?php
	}
	?>

	<?php echo $vik->openCard(); ?>

		<!-- LEFT-SIDE -->
	
		<div class="span8 full-width">

			<!-- DETAILS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPCUSTFIELDSLEGEND1'));
					echo $this->loadTemplate('details');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewUsernote","key":"usernote","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Details" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['usernote']))
					{
						echo $forms['usernote'];

						// unset details form to avoid displaying it twice
						unset($forms['usernote']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- CONTENT -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECUSTMAIL7'));
					echo $vik->getEditor()->display('usernote_content', $note->content, '100%', 550, 70, 20);
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewUsernote","key":"content","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Content" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['content']))
					{
						echo $forms['content'];

						// unset details form to avoid displaying it twice
						unset($forms['content']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

		</div>

		<!-- RIGHT-SIDE -->

		<div class="span4 full-width">

			<!-- OPTIONS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('JGLOBAL_FIELDSET_BASIC'));
					echo $this->loadTemplate('options');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewUsernote","key":"options","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Options" fieldset (right-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['options']))
					{
						echo $forms['options'];

						// unset details form to avoid displaying it twice
						unset($forms['options']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- ATTACHMENTS -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPATTACHMENTS'));
					echo $this->loadTemplate('attachments');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewUsernote","key":"attachments","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Attachments" fieldset (right-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['attachments']))
					{
						echo $forms['attachments'];

						// unset details form to avoid displaying it twice
						unset($forms['attachments']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewUsernote","type":"fieldset"} -->

			<?php
			if ($forms)
			{
				/**
				 * Iterate remaining forms to be displayed within
				 * the sidebar.
				 *
				 * @since 1.7
				 */
				foreach ($forms as $formName => $formHtml)
				{
					$title = JText::translate($formName);
					?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							echo $vik->openFieldset($title);
							echo $formHtml;
							echo $vik->closeFieldset();
							?>
						</div>
					</div>
					<?php
				}
			}
			?>

		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<?php
	if ($this->isTmpl)
	{
		?>
		<input type="hidden" name="tmpl" value="component" />
		<?php
	}
	?>

	<input type="hidden" name="id_user" value="<?php echo $note->id_user; ?>" />
	<input type="hidden" name="id_parent" value="<?php echo $note->id_parent; ?>" />
	<input type="hidden" name="group" value="<?php echo $this->escape($note->group); ?>" />
	
	<input type="hidden" name="id" value="<?php echo $note->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// render inspector to see the details of the attachments
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'attachments-inspector',
	array(
		'title'       => JText::translate('VAPMANAGEMEDIATITLE1'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => '<button type="button" class="btn" data-role="dismiss">' . JText::translate('JTOOLBAR_CLOSE') . '</button>',
		'width'       => 400,
	),
	$this->loadTemplate('attachments_modal')
);
?>

<script>

	(function($) {
		'use strict';

		var validator;

		Joomla.submitbutton = function(task) {
			if (task.indexOf('save') === -1 || validator.validate()) {
				Joomla.submitform(task, document.adminForm);
			}
		}

		$(function() {
			// dismiss inspector when the close button gets clicked
			$('#attachments-inspector').on('inspector.dismiss', function() {
				$(this).inspector('close');
			});

			// create validator instance
			validator = new VikFormValidator('#adminForm');
		});
	})(jQuery);

	<?php if ($this->isTmpl) { ?>

		function vapValidateFieldsAndDisableButton(button) {
			if (jQuery(button).prop('disabled')) {
				// button already submitted
				return false;
			}

			// disable button
			jQuery(button).prop('disabled', true);

			// submit form
			if (Joomla.submitbutton('usernote.save') === false) {
				// invalid fields, enable button again
				jQuery(button).prop('disabled', false);
			}
		}

		// transfer submit button instance to parent for being clicked
		window.parent.modalUserNoteSaveButton = document.adminForm.tmplSaveButton;

		// transfer created record to parent
		window.parent.modalSavedUserNoteData = <?php echo $this->note->id ? json_encode($this->note) : 'null'; ?>;

	<?php } ?>

</script>
