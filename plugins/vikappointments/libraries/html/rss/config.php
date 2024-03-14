<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.rss
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$config   = !empty($displayData['config'])   ? $displayData['config']   : null;
$channels = !empty($displayData['channels']) ? $displayData['channels'] : array();

$vik = VAPApplication::getInstance();

?>

<a name="rss"></a>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo __('Settings', 'vikappointments'); ?></h3>
	</div>

	<div class="config-fieldset-body">
		
		<!-- OPT IN - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', $config['optin'], 'onclick="rssOptinValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', !$config['optin'], 'onclick="rssOptinValueChanged(0);"');

		echo $vik->openControl(__('Enable RSS Service', 'vikappointments'));
		echo $vik->radioYesNo('rss_optin_status', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- DISPLAY DASHBOARD - Select -->

		<?php
		$control = array();
		$control['style'] = $config['optin'] ? '' : 'display:none;';

		$yes = $vik->initRadioElement('', '', $config['dashboard']);
		$no  = $vik->initRadioElement('', '', !$config['dashboard']);

		echo $vik->openControl(__('Display on Dashboard', 'vikappointments'), 'rss-child-setting', $control);
		echo $vik->radioYesNo('rss_display_dashboard', $yes, $no);
		echo $vik->closeControl();
		?>

	</div>

</div>

<?php
// allow channels management for PRO licenses
if (VikAppointmentsLicense::isPro())
{
	?>
	<div class="config-fieldset rss-child-setting" style="<?php echo $config['optin'] ? '' : 'display:none;'; ?>">

		<div class="config-fieldset-head">
			<h3><?php echo __('Channels', 'vikappointments'); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php
			// iterate supported channels
			foreach ($channels as $label => $url)
			{
				$checked = in_array($url, (array) $config['channels']);

				$yes = $vik->initRadioElement('', '', $checked, 'onclick="rssChannelValueChanged(1, \'' . $url . '\');"');
				$no  = $vik->initRadioElement('', '', !$checked, 'onclick="rssChannelValueChanged(0, \'' . $url . '\');"');

				echo $vik->openControl(ucwords($label), 'rss-child-setting', $control);

				echo $vik->radioYesNo('rss_channel_' . md5($url), $yes, $no);

				if ($checked)
				{
					?>
					<input type="hidden" name="rss_channel_url[]" value="<?php echo $url; ?>" />
					<?php
				}

				echo $vik->closeControl();
			}
			?>
		</div>

	</div>
	<?php
}
?>

<script>

	// toggle RSS settings according to the opt-in choice
	function rssOptinValueChanged(is) {
		if (is) {
			jQuery('.rss-child-setting').show();
		} else {
			jQuery('.rss-child-setting').hide();
		}
	}

	// toggle RSS channel according to the checkbox status
	function rssChannelValueChanged(is, url) {
		// get existing input URL
		var urlInput = jQuery('input[name="rss_channel_url[]"][value="' + url + '"]');

		if (is && urlInput.length == 0) {
			jQuery('#adminForm').append('<input type="hidden" name="rss_channel_url[]" value="' + url + '" />');
		} else {
			urlInput.remove();
		}
	}

</script>
