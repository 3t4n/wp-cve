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

$vik = VAPApplication::getInstance();

$options = array();
$options[] = JHtml::fetch('select.option', '', '');

foreach (array_reverse($this->logs) as $file => $name)
{
	$options[] = JHtml::fetch('select.option', basename($file), $name);
}

?>

<div style="display: flex;">

	<select id="vap-log-sel">
		<?php echo JHtml::fetch('select.options', $options); ?>
	</select>

	<button type="button" class="btn" id="load-log-btn" disabled style="margin-left: 10px;">
		<?php echo JText::translate('VAPLOAD'); ?>
	</button>

</div>

<pre id="vap-log-content" style="margin-top: 15px; max-height: 500px; overflow-y: scroll; display: none;"></pre>

<?php
JText::script('VAPFILTERSELECTFILE');
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>

	jQuery(function($) {
		$('#vap-log-sel').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTFILE'),
			allowClear: false,
			width: '100%',
		});

		$('#vap-log-sel').on('change', function() {
			if ($(this).val()) {
				$('#load-log-btn').prop('disabled', false);
			} else {
				$('#load-log-btn').prop('disabled', true);
			}
		});

		$('#load-log-btn').on('click', function() {
			const file = $('#vap-log-sel').val();

			if (!file || $(this).prop('disabled')) {
				return false;
			}

			$('#vap-log-content').hide();
			$(this).prop('disabled', true);

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=webhook.loadlogajax'); ?>',
				{
					file: file,
				},
				(resp) => {
					$('#vap-log-content').html(resp).show();
					$(this).prop('disabled', false);
				},
				(err) => {
					alert(err.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'))
					$(this).prop('disabled', false);
				}
			);
		});
	});

</script>
