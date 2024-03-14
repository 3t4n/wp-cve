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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalSync". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalSync');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ADMIN SYNC URL - Response -->
		<?php
		$sync_url = 'index.php?option=com_vikappointments&task=appsync&key=' . $params['synckey'];
		
		/**
		 * Route Sync URL for external usage.
		 *
		 * @since 1.7
		 */
		$sync_url = $vik->routeForExternalUse($sync_url);

		$help = $vik->createPopover(array(
			'title' 	=> JText::translate('VAPMANAGECONFIG63'),
			'content' 	=> JText::translate('VAPICSURL'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG63') . $help); ?>
			<div>
				<input type="text" value="<?php echo $sync_url; ?>" size="64" id="syncurl" readonly />
			</div>

			<div style="margin-top: 5px;">
				<button type="button" class="btn" id="syncurlsubscr">
					<i class="fas fa-calendar-plus"></i>
					<?php echo JText::translate('VAPSUBSCRIBE'); ?>
				</button>

				<button type="button" class="btn" id="syncurlcopy">
					<i class="fas fa-copy"></i>
					<?php echo JText::translate('VAPCOPY'); ?>
				</button>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SYNC PASSWORD - Text -->
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG64')); ?>
			<input type="text" name="synckey" id="synckey" value="<?php echo $this->escape($params['synckey']); ?>" size="40" maxlength="32" />
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSync","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > Appointments Sync > Sync fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>
		
	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSync","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > Sync tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<div id="sync-dialog-confirm" style="display: none;">

	<p><?php echo JText::translate('VAPSYNCUBSCRICS'); ?></p>

	<div>
		<select id="syncurl-subscr-driver">
			<option></option>
			<option value="apple">Apple iCal</option>
			<option value="google">Google Calendar</option>
		</select>
	</div>
	
</div>

<?php
JText::script('VAPCOPIED');
JText::script('VAPSUBSCRIBE');
JText::script('JCANCEL');
JText::script('VAP_SELECT_USE_DEFAULT_X');
?>

<script>

	jQuery(function($) {

		$('#syncurlsubscr').click(() => {
			dialog.show();
		});

		$('#syncurlcopy').click(() => {
			copyToClipboard($('#syncurl')[0]).then(() => {
				ToastMessage.enqueue(Joomla.JText._('VAPCOPIED'));
			});
		});

		$('#synckey').on('change', function() {
			var input = $('#syncurl');

			if (input.length == 0) {
				return;
			}
			
			var url = input.val();
			url = url.substr(0, url.lastIndexOf('=') + 1) + $(this).val();
			input.val(url);
		});

		var dialog = new VikConfirmDialog('#sync-dialog-confirm');

		// add confirm button
		dialog.addButton(Joomla.JText._('VAPSUBSCRIBE'), function(args, event) {
			// get selected driver
			var driver = $('#syncurl-subscr-driver').val();

			// get sync URL
			var url = $('#syncurl').val().trim();

			switch (driver) {
				// Apple iCal
				case 'apple':
					// replace HTTP(s) with WEBCAL
					url = url.replace(/^https?:\/\//, 'webcal://');
					break;

				// Google Calendar
				case 'google':
					// replace HTTP(s) with WEBCAL
					url = url.replace(/^https?:\/\//, 'webcal://');
					// encode URL and prepend Google Calendar renderer
					url = 'https://www.google.com/calendar/render?cid=' + encodeURIComponent(url);
					break;

				// driver not selected/supported
				default:
					// prevent closure
					return false;
			}

			dialog.dispose();

			setTimeout(function() {
				// open subscription URL in a new browser page
				window.open(url, '_blank');
			}, 256);
		}, false);

		// add cancel button
		dialog.addButton(Joomla.JText._('JCANCEL'));

		// pre-build dialog
		dialog.build();

		$('#syncurl-subscr-driver').select2({
			minimumResultsForSearch: -1,
			placeholder: '--',
			allowClear: true,
			width: '100%',
		});

		$('.select2-drop').css('z-index', '99999');
	});

</script>
