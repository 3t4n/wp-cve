<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$cron_data = $this->cron_data;
$run_res = $this->run_res;
$cron_output = $this->cron_output;
$cron_obj = $this->cron_obj;

?>
<div class="vri-shell-wrap">
	<p class="vri-shell-top-bar"><?php echo $cron_data['cron_name']; ?> - <span><?php echo JText::translate('VRICRONEXECRESULT'); ?>:</span> <?php var_dump($run_res); ?></p>
	<div class="vri-shell-body" style="min-height: 400px;">
		<?php echo $cron_output; ?>
	<?php
	if (strlen($cron_obj->log)) {
	?>
		<p>---------- LOG ----------</p>
		<div class="vri-cronexec-log">
			<pre><?php echo $cron_obj->log; ?></pre>
		</div>
	<?php
	}
	?>
	</div>
</div>
<style type="text/css">
	body {
		padding: 0 !important;
	}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	setTimeout(function() {
		checkShellHeight();
	}, 500);
});
function checkShellHeight() {
	var page_height = jQuery(window).height();
	var shell_height = jQuery(".vri-shell-wrap").height();
	if (shell_height < page_height) {
		var diff_height = page_height - shell_height - 10;
		jQuery(".vri-shell-body").css('height', "+="+diff_height+'px');
	}
}
</script>
