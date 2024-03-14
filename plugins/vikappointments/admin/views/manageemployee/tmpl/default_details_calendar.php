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

$employee = $this->employee;

$vik = VAPApplication::getInstance();

?>

<!-- TIMEZONE - Select -->

<?php
$zones = array(
	0 => array(JHtml::fetch('select.option', '', '')),
);
foreach (timezone_identifiers_list() as $zone)
{
	$parts = explode('/', $zone);

	$continent  = isset($parts[0]) ? $parts[0] : '';
	$city 		= (isset($parts[1]) ? $parts[1] : $continent) . (isset($parts[2]) ? '/' . $parts[2] : '');
	$city 		= ucwords(str_replace('_', ' ', $city));

	if (!isset($zones[$continent]))
	{
		$zones[$continent] = array();
	}

	$zones[$continent][] = JHtml::fetch('select.option', $zone, $city);
}

$params = array(
	'id'          => 'vap-timezone-sel',
	'group.items' => null,
	'list.select' => $employee->timezone,
);

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE30'));
echo JHtml::fetch('select.groupedList', $zones, 'timezone', $params);
echo $vik->closeControl();
?>

<!-- IMPORT URL - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE33')); ?>
	<button type="button" class="btn btn-primary" id="ical_url_btn"><?php echo JText::translate('VAPEDIT'); ?></button>
<?php echo $vik->closeControl(); ?>

<!-- SYNC KEY - Text -->

<?php
$help = $vik->createPopover(array(
	'title'		=> JText::translate('VAPMANAGEEMPLOYEE24'),
	'content'	=> JText::translate('VAPMANAGEEMPLOYEE24_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE24') . '*' . $help); ?>
	<input type="text" name="synckey" value="<?php echo $this->escape($employee->synckey); ?>" class="required" maxlength="32" size="40" id="synckey" />
<?php echo $vik->closeControl(); ?>

<!-- SYNC URL - Text -->

<?php
if ($employee->id > 0)
{
	$sync_url = 'index.php?option=com_vikappointments&task=appsync&employee=' . $employee->id . '&key=' . $employee->synckey;

	/**
	 * Route Sync URL for external usage.
	 *
	 * @since 1.7
	 */
	$sync_url = $vik->routeForExternalUse($sync_url);

	$help = $vik->createPopover(array(
		'title'		=> JText::translate('VAPMANAGEEMPLOYEE25'),
		'content'	=> JText::translate('VAPMANAGEEMPLOYEE25_DESC'),
	));

	echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE25') . $help); ?>
		<div>
			<input type="text" value="<?php echo $sync_url; ?>" size="48" id="syncurl" readonly />
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
	<?php
	echo $vik->closeControl();
}
else
{
	echo $vik->alert(JText::translate('VAPMANAGEEMPLOYEE24_DESC2'));
}
?>

<div id="sync-dialog-confirm" style="display: none;">

	<p><?php echo JText::translate('VAPSYNCUBSCRICS'); ?></p>

	<p>
		<select id="syncurl-subscr-driver">
			<option></option>
			<option value="apple">Apple iCal</option>
			<option value="google">Google Calendar</option>
		</select>
	</p>

	<div>
		<input type="checkbox" id="syncurl-exclude-import" />
		<label for="syncurl-exclude-import">
			<?php echo JText::translate('VAPSYNCUBSCRICS_EXCLUDE_IMPORT'); ?>
			<i class="fas fa-question-circle tip" title="<?php echo $this->escape(JText::translate('VAPSYNCUBSCRICS_EXCLUDE_IMPORT_HELP')); ?>"></i>
		</label>
	</div>
	
</div>

<?php
// render inspector to manage working days management
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'ical-inspector',
	array(
		'title'       => JText::translate('VAPMANAGEEMPLOYEE33'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => '<button type="button" class="btn btn-success" data-role="dismiss">' . JText::translate('VAPCLOSE') . '</button>',
		'width'       => 600,
	),
	$this->loadTemplate('details_calendar_modal')
);

JText::script('VAPCOPIED');
JText::script('VAPSUBSCRIBE');
JText::script('JCANCEL');
JText::script('VAP_SELECT_USE_DEFAULT_X');

// load default timezone
$tz = JFactory::getApplication()->get('offset', 'UTC');
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vap-timezone-sel').select2({
				placeholder: Joomla.JText._('VAP_SELECT_USE_DEFAULT_X').replace(/%s/, '<?php echo $tz; ?>'),
				allowClear: true,
				width: '90%',
			});

			$('#ical_url_btn').on('click', () => {
				vapOpenInspector('ical-inspector');
			});

			$('#ical-inspector button[data-role="dismiss"]').on('click', () => {
				vapCloseInspector('ical-inspector');
			});

			$('#syncurlsubscr').click(() => {
				dialog.show();
			});

			$('#syncurlcopy').click(() => {
				copyToClipboard($('#syncurl')[0]).then(() => {
					ToastMessage.enqueue(Joomla.JText._('VAPCOPIED'));
				});
			});

			$('#synckey').on('blur', function() {
				var url_input = $(this);

				if (url_input.length == 0) {
					return;
				}
				
				var url = url_input.val();
				url = url.substr(0, url.lastIndexOf('=') + 1) + $('#synckey').val();
				url_input.val(url);
			});

			var dialog = new VikConfirmDialog('#sync-dialog-confirm');

			// add confirm button
			dialog.addButton(Joomla.JText._('VAPSUBSCRIBE'), function(args, event) {
				// get selected driver
				var driver = $('#syncurl-subscr-driver').val();

				// get sync URL
				var url = $('#syncurl').val().trim();

				if ($('#syncurl-exclude-import').is(':checked')) {
					// add parameter in query string to exclude the imported events
					url += '&imported=0';
				}

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

			$('.vik-confirm-message .tip').tooltip({
				container: '.vik-confirm-message',
			});

		});
	})(jQuery);

</script>
