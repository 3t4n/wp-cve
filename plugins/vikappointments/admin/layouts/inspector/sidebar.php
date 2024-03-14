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
 * Layout variables
 * -----------------
 * @param 	string 	 id           The ID of the inspector sidebar.
 * @param 	string	 title        An optional inspector title.
 * @param 	boolean  closeButton  True to display the close button (false by default).
 * @param 	boolean  keyboard     True to dismiss the inspector by pressing ESC (false by default).
 * @param 	string   placement    Where the inspector should be placed (left or right).
 *                                Left by default, if not specified.
 * @param 	mixed    url          An optional URL to render the body within a <iframe>.
 * @param 	mixed    body         An optional HTML string to be placed within the body. In case the URL
 *                                is specified, the body string will be ignored.
 * @param 	mixed    footer       An optional footer to be placed at bottom.
 */
extract($displayData);

JText::script('VAP_CONFIRM_MESSAGE_UNSAVE');
JText::script('VAPSYSTEMCONFIRMATIONMSG');

?>
	
<div class="record-inspector-overlay">

	<div
		class="record-inspector <?php echo $placement == 'left' ? 'left' : 'right'; ?>-side<?php echo $class ? ' ' . $class : ''; ?>"
		id="<?php echo $id; ?>"
		style="<?php echo $width ? 'min-width:' . $width . ';max-width: ' . $width . ';' : ''; ?>"
		data-esc="<?php echo $keyboard ? 1 : 0; ?>"
	>

		<div class="inspector-head<?php echo ($title || $closeButton ? '' : ' empty'); ?>">
			<?php
			if ($title)
			{
				?>
				<h3><?php echo $title; ?></h3>
				<?php
			}

			if ($closeButton)
			{
				?>
				<a class="dismiss"><i class="fas fa-times"></i></a>
				<?php
			}
			?>
		</div>

		<div class="inspector-body">
			<?php
			if ($url)
			{
				?>
				<iframe src="<?php echo $url; ?>"></iframe>
				<?php
			}
			else
			{
				echo $body;
			}
			?>
		</div>

		<?php
		if ($footer)
		{
			?>
			<div class="inspector-footer"><?php echo $footer; ?></div>
			<?php
		}
		?>

	</div>

</div>

<script>

	jQuery(function($) {
		const inspector = $('#<?php echo addslashes($id); ?>');
		
		let formObserver = null;

		// observe the form changes in case of button with "save" role
		if (inspector.find('.inspector-footer button[data-role="save"]').length) {
			formObserver = new VikFormObserver(inspector.find('.inspector-body'));

			// register current form state when the inspector fade in
			inspector.on('inspector.aftershow', function() {
				// freeze form at the current state
				formObserver.freeze();
			});

			// check for any changes when the inspector is closed
			inspector.on('inspector.close', function(event) {
				if (formObserver.isChanged()) {
					// something has changed, warn the user about the
					// possibility of losing any changes
					let discard = confirm(Joomla.JText._('VAP_CONFIRM_MESSAGE_UNSAVE'));

					if (!discard) {
						// the user denied the closure of the inspector, stop the
						// event propagation in order to prevent the action
						event.preventDefault();
						event.stopPropagation();
						return false;
					}
				}
			});	
		}

		// Trigger an event when a button in the footer is clicked.
		// The button must specify a data-role attribute in order to 
		// be invoked. The triggered event will be built as:
		// inspector.[ROLE]
		inspector.find('.inspector-footer button[data-role]').on('click', function() {
			let role = $(this).data('role');

			// check for SAVE role
			if (role == 'save' && formObserver) {
				// force observer to always return false when checking for
				// new changes, as they have been properly saved
				formObserver.unchanged();
			}
			// check for DELETE role
			else if (role == 'delete') {
				// ask a confirmation before to trigger the DELETE role
				let discard = confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'));

				if (!discard) {
					// the user denied the action, abort all
					event.preventDefault();
					event.stopPropagation();
					return false;
				}

				// force observer to always return false when checking for
				// new changes, as we are going to delete the whole record
				formObserver.unchanged();
			}

			// trigger role event
			$(this).trigger('inspector.' + role);
		});

		// Registers an event to allow the callers to manually commit the
		// changes of the inspector, since the initialization may be done
		// asynchronously.
		inspector.on('inspector.commit', () => {
			if (formObserver) {
				// freeze form again
				formObserver.freeze();
			}
		});

		<?php
		if ($keyboard)
		{
			?>
			$(window).on('keydown', (event) => {
				if (event.keyCode == 27) {
					vapCloseInspector('<?php echo $id; ?>');
				}
			});
			<?php
		}
		?>
	});

</script>
