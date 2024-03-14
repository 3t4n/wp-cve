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

JHtml::fetch('formbehavior.chosen', 'form#adminForm');

$sel   = $this->shortcode;
$views = $this->views;

$vik = VAPApplication::getInstance();

?>

<form action="admin.php" method="post" name="adminForm" id="adminForm">

	<div id="poststuff">

		<?php echo $vik->openFieldset(JText::translate('JSHORTCODE')); ?>

			<!-- NAME -->

			<?php echo $vik->openControl(JText::translate('JNAME') . '*', '', array('id' => 'vik-name')); ?>
				<input type="text" id="vik-name" name="name" class="required" value="<?php echo $this->escape($sel['name']); ?>" size="40" />
			<?php echo $vik->closeControl(); ?>

			<!-- TYPE -->

			<?php echo $vik->openControl(JText::translate('JTYPE') . '*', '', array('id' => 'vik-type')); ?>
				<select name="type" id="vik-type" class="required">
					<option data-desc="" value=""><?php echo JText::translate('JGLOBAL_SELECT_AN_OPTION'); ?></option>

					<?php
					foreach ($this->views as $k => $v)
					{
						?>
						<option data-desc="<?php echo $this->escape(JText::translate($v['desc'])); ?>" value="<?php echo $this->escape($k); ?>" <?php echo $k == $sel['type'] ? 'selected="selected"' : ''; ?>>
							<?php echo JText::translate($v['name']); ?>
						</option>
						<?php
					}
					?>
				</select>
			<?php echo $vik->closeControl(); ?>

			<!-- PARENT -->

			<?php echo $vik->openControl(JText::translate('VAP_SHORTCODE_PARENT_FIELD'), '', array('id' => 'vik-parent')); ?>
				<select name="parent_id" id="vik-parent">
					<option value="">--</option>

					<?php
					foreach ($this->shortcodesList as $item)
					{
						if ($item->id === $sel['id'])
						{
							// exclude self
							continue;
						}
						
						?>
						<option value="<?php echo $this->escape($item->id); ?>" <?php echo $item->id == $sel['parent_id'] ? 'selected="selected"' : ''; ?>>
							<?php echo $item->name; ?>
						</option>
						<?php
					}
					?>
				</select>
			<?php echo $vik->closeControl(); ?>

			<!-- LANGUAGE -->

			<?php echo $vik->openControl(JText::translate('JLANGUAGE'), '', array('id' => 'vik-lang')); ?>
				<select name="lang" id="vik-lang">
					<option value="*"><?php echo JText::translate('JALL'); ?></option>

					<?php
					foreach (JLanguage::getKnownLanguages() as $tag => $lang)
					{
						?>
						<option value="<?php echo $this->escape($tag); ?>" <?php echo $tag == $sel['lang'] ? 'selected="selected"' : ''; ?>>
							<?php echo $lang['nativeName']; ?>
						</option>
						<?php
					}
					?>
				</select>
			<?php echo $vik->closeControl(); ?>

			<!-- DESCRIPTION -->

			<div class="control">
				<div id="vik-type-desc"></div>
			</div>

		<?php echo $vik->closeFieldset(); ?>

		<!-- PARAMETERS -->

		<div class="shortcode-params">
			<?php
			/**
			 * Immediately render the form fields of the selected shortcode.
			 *
			 * @since 1.2
			 */
			if ($this->form)
			{
				echo $this->form->renderForm(json_decode($sel['json']));
			}
			?>
		</div>

	</div>

	<input type="hidden" name="id" value="<?php echo (int) $sel['id']; ?>" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo $this->escape($this->returnLink); ?>" />

</form>

<script>

	let formValidator = null;

	(function($) {
		'use strict';

		formValidator = new JFormValidator('#adminForm');

		const typeSelect = $('select[name="type"]');

		typeSelect.on('change', function() {
			formValidator.unregisterFields('.shortcode-params .required');

			if (!$('#vik-name').val()) {
				// use the page title as shortcode name
				$('#vik-name').val($(this).find('option:selected').text().trim());
			}

			UIAjax.do(
				'admin-ajax.php?option=com_vikappointments&task=shortcode.params',
				{
					id:   <?php echo (int) $sel['id']; ?>,
					type: $(this).val()
				}, (html) => {
					// destroy current chosen just before updating the params form
					$('.shortcode-params select').chosen('destroy');
					$('.shortcode-params').html(html);

					formValidator.registerFields('.shortcode-params .required');

					$('.shortcode-params select').chosen();

					$('#vik-type-desc').html($(this).find('option:selected').attr('data-desc'));
				}
			);
		});

		Joomla.submitbutton = function(task) {
			if (task.indexOf('shortcode.save') == -1 || formValidator.validate()) {
				Joomla.submitform(task, document.adminForm);
			}
		}
	})(jQuery);

</script>
