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

?>

<div class="control-group">
	<a
		id="usernotes-link"
		class="btn btn-block"
		href="index.php?option=com_vikappointments&amp;view=usernotes&amp;id_parent=<?php echo $this->reservation->id; ?>&amp;group=appointments"
	>
		<?php echo JText::translate('VAP_DISPLAY_ALL_NOTES'); ?>
	</a>
</div>

<?php
JText::script('VAPFORMCHANGEDCONFIRMTEXT');
?>

<script>

	(function($) {
		let observer;

		$(function() {
			observer = new VikFormObserver('#adminForm');
			// exclude fields linked to intltel, because they auto-fill the
			// dial code and the country code when the page loads
			observer.exclude('input[name$="_dialcode"]');
			observer.exclude('input[name$="_country"]');

			// freeze form with a short delay to allow any components to
			// fill the form
			setTimeout(() => {
				observer.freeze();
			}, 256);

			$('#usernotes-link').on('click', (event) => {
				if (!observer.isChanged()) {
					// nothing has changed, go ahead
					return true;
				}

				// ask for a confirmation
				let r = confirm(Joomla.JText._('VAPFORMCHANGEDCONFIRMTEXT'));

				if (!r) {
					// do not leave the page
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			});
		});
	})(jQuery);

</script>