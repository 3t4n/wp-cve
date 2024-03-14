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

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   boolean  $newupdate   True if there is a new available update.
 * @var   boolean  $vikupdater  True if VikUpdater plugin is active.
 * @var   boolean  $connect     True to auto-search for new updates.
 * @var   string   $url         The fallback remote URL.
 * @var   string   $title       The item title.
 * @var   string   $label       The item label.
 */

JText::script('VAPCHECKINGVERSION');
JText::script('ERROR');

?>

<div class="version-box custom<?php echo $newupdate ? ' upd-avail' : ''; ?>">
	
	<?php
	if ($vikupdater)
	{
		$url = 'index.php?option=com_vikappointments&task=updateprogram.checkversion';
		$url = VAPApplication::getInstance()->ajaxUrl($url);

		// VikUpdater plugin is enabled

		$document = JFactory::getDocument();
		$document->addScriptDeclaration(
<<<JS
function callVersionChecker() {
	setVersionContent(Joomla.JText._('VAPCHECKINGVERSION'));

	UIAjax.do(
		'{$url}',
		{},
		(obj) => {
			console.log(obj);

			if (obj["status"] == 1) {

				if (obj.response.status == 1) {

					if (obj.response.compare == 1) {
						jQuery("#vap-versioncheck-link").attr("onclick", "");
						jQuery("#vap-versioncheck-link").attr("href", "index.php?option=com_vikappointments&view=updateprogram");

						obj.response.shortTitle += '<i class="upd-avail fas fa-exclamation-triangle"></i>';

						jQuery(".version-box.custom").addClass("upd-avail");
					}

					setVersionContent(obj.response.shortTitle, obj.response.title);

				} else {
					console.log(obj.response.error);
					setVersionContent(Joomla.JText._('ERROR'));
				}

			} else {
				console.log("plugin disabled");
				setVersionContent(Joomla.JText._('ERROR'));
			}
		},
		(err) => {
			console.log(resp);
			setVersionContent(Joomla.JText._('ERROR'));
		}
	);
}

function setVersionContent(cont, title) {
	jQuery("#vap-version-content").html(cont);

	if (title === undefined) {
		var title = "";
	}

	jQuery("#vap-version-content").attr("title", title);
}
JS
		);

		if ($connect)
		{
			$document->addScriptDeclaration(
<<<JS
jQuery(document).ready(function() {
	callVersionChecker();
});
JS
			);
		}
		?>
		<a
			href="<?php echo ($newupdate ? 'index.php?option=com_vikappointments&view=updateprogram' : 'javascript:void(0)'); ?>"
			onclick="<?php echo ($newupdate ? '' : 'callVersionChecker();'); ?>"
			id="vap-versioncheck-link"
		>
			<i class="fas fa-rocket"></i>
			<span id="vap-version-content" title="<?php echo $title; ?>">
				<?php 
				echo $label;

				if ($newupdate)
				{
					?><i class="upd-avail fas fa-exclamation-triangle"></i><?php
				}
				?>
			</span>
		</a>
		<?php
	}
	else
	{
		echo JHtml::fetch(
			'bootstrap.renderModal',
			'jmodal-version-check',
			array(
				'title'       => $label,
				'closeButton' => true,
				'keyboard'    => true,
				'bodyHeight'  => 80,
				'url'         => $url,
				'footer'      => '<button type="button" class="btn btn-success" id="version-check-install">' . JText::translate('JTOOLBAR_INSTALL') . '</button>',
			)
		);

		?>
		<a id="vcheck" href="javascript:void(0)">
			<i class="fas fa-rocket"></i>
			<span><?php echo $label; ?></span>
		</a>

		<script>
			(function($) {
				'use strict';

				$(function() {
					$('#vcheck').on('click', () => {
						let id      = 'version-check';
						let url     = null;
						let jqmodal = true;

						<?php echo VAPApplication::getInstance()->bootOpenModalJS(); ?>
					});

					$('#version-check-install').on('click', () => {

						const form = $('<form action="index.php?option=com_installer&task=install.install" method="post"></form>');

						form.append('<input type="hidden" name="installtype" value="url" />');
						form.append('<input type="hidden" name="install_url" value="https://extensionsforjoomla.com/vikapi/?task=products.freedownload&sku=vup" />');
						form.append('<input type="hidden" name="return" value="<?php echo base64_encode(JUri::getInstance()); ?>" />');
						form.append('<?php echo JHtml::fetch('form.token'); ?>');

						$('body').append(form);

						form.submit();
					});
				});
			})(jQuery);
		</script>
		<?php
	}
	?>

</div>