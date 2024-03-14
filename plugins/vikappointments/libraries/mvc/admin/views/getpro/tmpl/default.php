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

<div class="viwppro-cnt vikwppro-download">
	<div class="vikwppro-header">
		<div class="vikwppro-header-inner">
			<div class="vikwppro-download-result">
				<h2><i class="fas fa-sync-alt"></i> <?php _e('Please wait', 'vikappointments'); ?></h2>
				<h3 class="vikwppro-cur-action"><?php _e('Downloading the package...', 'vikappointments'); ?></h3>
			</div>
			<div class="vikwppro-download-progress">
				<progress value="0" max="100" id="vikwpprogress"></progress> 
			</div>
		</div>
	</div>

	<?php
	if (!empty($this->changelog))
	{
		?>
		<div class="vikwppro-changelog-wrap">
			<div class="vikwppro-plg-changelog">
				<?php echo $this->changelog; ?>
			</div>
		</div>
		<?php
	}
	?>
</div>

<?php
JText::script('VAPUPDCOMPLOKCLICK');
JText::script('VAPUPDCOMPLNOKCLICK');
?>

<script>

	(function($) {
		'use strict';

		let isRunning  = false;
		let isComplete = false;

		const vikwpStartDownload = () => {
			if (isRunning) {
				return;
			}

			isRunning = true;
			dispatchProgress();

			UIAjax.do(
				'admin-ajax.php?action=vikappointments&task=license.downloadpro',
				{
					key: "<?php echo $this->licenseKey; ?>",
				}, (resp) => {
					// stop with success
					vikwpStopMonitoring(true);
				}, (err) => {
					// stop with error
					vikwpStopMonitoring(err.responseText);
				}
			);
		}

		const vikwpStopMonitoring = (result) => {
			isComplete = true;
			isRunning  = false;

			$('#vikwpprogress').attr('value', 100);

			if (result === true) {
				$('.vikwppro-download-result').html(
					'<h1 class="vikwp-download-success"><i class="fas fa-check"></i></h1>\n' +
					'<p>\n' + 
						'<button type="button" class="button button-primary" onclick="document.location.href=\'admin.php?page=vikappointments\';">' + 
							Joomla.JText._('VAPUPDCOMPLOKCLICK') +
						'</button>\n'+
					'</p>\n'
				);
			} else {
				$('.vikwppro-download-result').html(
					'<h1 class="vikwp-download-error"><i class="fas fa-times"></i></h1>\n' +
					'<h3 class="download-error-message">' + result + '</h3>\n' +
					'<p>\n' +
						'<button type="button" class="button" onclick="document.location.href=\'admin.php?page=vikappointments&view=gotopro\';">\n' +
							Joomla.JText._('VAPUPDCOMPLNOKCLICK') + 
						'</button>\n' + 
					'</p>\n'
				);
				
				$('#vikwpprogress').hide();
			}
		}

		const dispatchProgress = () => {
			setTimeout(() => {
				if (isComplete) {
					$('#vikwpprogress').attr('value', 100);
					return;
				}

				var curprogress = parseInt($('#vikwpprogress').attr('value'));
				var nextstep = Math.floor(Math.random() * 5) + 6;
				
				if ((curprogress + nextstep) > 100) {
					curprogress = 100;
				} else {
					curprogress += nextstep;
				}

				$('#vikwpprogress').attr('value', curprogress);

				if (curprogress < 100) {
					dispatchProgress();
				}
			}, (Math.floor(Math.random() * 501) + 750));
		}

		$(function() {
			vikwpStartDownload();
		});
	})(jQuery);

</script>
