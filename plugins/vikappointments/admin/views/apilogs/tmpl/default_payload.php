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

$log = $this->currentLog;

if (defined('JSON_PRETTY_PRINT'))
{
	$log['payload'] = json_encode(json_decode($log['payload']), JSON_PRETTY_PRINT);
}

$params = array(
	'readonly' => true,
	'syntax'   => 'json',
);

?>

<div style="padding: 10px;">

	<?php echo VAPApplication::getInstance()->getCodeMirror('payload_' . $log['id'], $log['payload'], $params); ?>

	<textarea class="keep-active-but-hidden" id="payload_copy_<?php echo $log['id']; ?>"><?php echo $log['payload']; ?></textarea>

</div>

<script>

	<?php
	/**
	 * In WordPress the codemirror seems to have rendering problems while
	 * initialized on a hidden panel. For this reason, we need to refresh
	 * its contents when the modal is displayed.
	 * @wponly
	 */
	if (VersionListener::isWordpress())
	{
		?>
		(function($) {
			'use strict';

			$(function() {
				$('#jmodal-payload-<?php echo $log['id']; ?>').on('show', () => {
					setTimeout(() => {
						Joomla.editors.instances['payload_<?php echo $log['id']; ?>'].element.codemirror.refresh();
					}, 256);
				});
			});
		})(jQuery);
		<?php
	}
	?>

</script>
