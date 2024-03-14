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

?>

<?php echo $vik->openControl(''); ?>

	<button type="button" class="btn" onclick="allowAllRules(1);"><?php echo JText::translate('VAPMEDIASELECTALL'); ?></button>
	<button type="button" class="btn" onclick="allowAllRules(0);"><?php echo JText::translate('VAPMEDIASELECTNONE'); ?></button>

<?php echo $vik->closeControl(); ?>

<?php
foreach ($this->plugins as $plugin)
{
	?>
	<!-- PLUGIN - Dropdown -->
	<?php
	$is_allowed = (int) ($plugin->alwaysAllowed() || !in_array($plugin->getName(), $this->user->denied));

	$elements = array(
		JHtml::fetch('select.option', 1, 'VAPALLOWED'),
		JHtml::fetch('select.option', 0, 'VAPDENIED'),
	);
	
	echo $vik->openControl($plugin->getTitle()); ?>
		<div class="inline-fields" style="align-items: center;">
			<select name="plugin[<?php echo $plugin->getName(); ?>]" class="vap-plugin-rules flex-basis-70" <?php echo $plugin->alwaysAllowed() ? 'disabled="disabled"' : ''; ?>>
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $is_allowed, true); ?>
			</select>

			<i class="fas fa-<?php echo ($is_allowed ? 'check-circle ok' : 'ban no'); ?> big"></i>
		</div>
	<?php echo $vik->closeControl(); ?>

	<?php
}
?>

<script>

	jQuery(function($) {
		$('.vap-plugin-rules').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: '90%',
		});
	});

	function allowAllRules(is) {
		jQuery('select.vap-plugin-rules:not(:disabled)').val(is).trigger('change');
	}

</script>