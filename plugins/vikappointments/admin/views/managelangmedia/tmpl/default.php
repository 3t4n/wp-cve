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

JHtml::fetch('vaphtml.scripts.selectflags', '#vap-lang-sel');

$vik = VAPApplication::getInstance();

$deflang = VikAppointments::getDefaultLanguage();

$editor = $vik->getEditor();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- TRANSLATION -->

		<div class="span6">
			<?php echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIA3')); ?>
			
				<!-- LANGUAGE - Dropdown -->

				<?php
				$elements = JHtml::fetch('contentlanguage.existing');
				
				echo $vik->openControl(JText::translate('VAPLANGUAGE')); ?>
					<select name="tag" id="vap-lang-sel">
						<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', isset($this->language->tag) ? $this->language->tag : null); ?>
					</select>
				<?php echo $vik->closeControl(); ?>
				
				<!-- ALT - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA20')); ?>
					<input type="text" name="alt" value="<?php echo $this->escape((isset($this->language->alt) ? $this->language->alt : '')); ?>" size="48" />
				<?php echo $vik->closeControl(); ?>

				<!-- TITLE - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA21')); ?>
					<input type="text" name="title" value="<?php echo $this->escape((isset($this->language->title) ? $this->language->title : '')); ?>" size="48" />
				<?php echo $vik->closeControl(); ?>

				<!-- CAPTION - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA22')); ?>
					<textarea name="caption" class="full-width" style="height:120px;resize:vertical;"><?php echo htmlentities((isset($this->language->caption) ? $this->language->caption : '')); ?></textarea>
				<?php echo $vik->closeControl(); ?>
			
				<input type="hidden" name="id" value="<?php echo isset($this->language->id) ? $this->language->id : 0; ?>" />
				
			<?php echo $vik->closeFieldset(); ?>
		</div>

		<!-- ORIGINAL -->

		<div class="span6">
			<?php echo $vik->openFieldset(JText::translate('VAPORIGINAL')); ?>
			
				<!-- LANGUAGE - HTML -->

				<?php
				echo $vik->openControl(JText::translate('VAPLANGUAGE'));
				echo JHtml::fetch('vaphtml.site.flag', $deflang);
				echo $vik->closeControl();
				?>
				
				<!-- ALT - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA20')); ?>
					<input type="text" value="<?php echo $this->escape($this->default->alt); ?>" size="48" readonly tabindex="-1" />
				<?php echo $vik->closeControl(); ?>

				<!-- TITLE - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA21')); ?>
					<input type="text" value="<?php echo $this->escape($this->default->title); ?>" size="48" readonly tabindex="-1" />
				<?php echo $vik->closeControl(); ?>

				<!-- CAPTION - Textarea -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA22')); ?>
					<textarea class="full-width" style="height:120px;resize:vertical;" readonly tabindex="-1"><?php echo $this->default->caption; ?></textarea>
				<?php echo $vik->closeControl(); ?>
				
			<?php echo $vik->closeFieldset(); ?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="image" value="<?php echo $this->escape($this->default->image); ?>" />	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
JText::script('VAP_SAVE_TRX_DEF_LANG');
?>

<script>

	Joomla.submitbutton = function(task) {
		var selected_lang = jQuery('#vap-lang-sel').val();

		if (task.indexOf('save') !== -1 && selected_lang == '<?php echo $deflang; ?>') {
			// saving translation with default language, ask for confirmation
			var r = confirm(Joomla.JText._('VAP_SAVE_TRX_DEF_LANG').replace(/%s/, selected_lang));

			if (!r) {
				return false;
			}
		}

		Joomla.submitform(task, document.adminForm);
	}

</script>
