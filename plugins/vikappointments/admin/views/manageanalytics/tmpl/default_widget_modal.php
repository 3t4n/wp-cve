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

<div class="inspector-form" id="inspector-widget-form">

	<div class="inspector-fieldset">

		<!-- WIDGET NAME - Text -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAP_WIDGET_NAME'),
			'content' => JText::translate('VAP_WIDGET_NAME_DESC'),
		));

		echo $vik->openControl(JText::translate('VAP_WIDGET_NAME') . $help); ?>
			<input type="text" name="widget_name" value="" placeholder="" class="field" />
		<?php echo $vik->closeControl(); ?>

		<!-- WIDGET CLASS - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', '', ''),
		);

		foreach ($this->supported as $widget)
		{
			$options[] = JHtml::fetch('select.option', $widget->getName(), $widget->getTitle());
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAP_WIDGET_CLASS'),
			'content' => JText::translate('VAP_WIDGET_CLASS_DESC'),
		));

		echo $vik->openControl(JText::translate('VAP_WIDGET_CLASS') . '*' . $help); ?>
			<select name="widget_class" class="field required">
				<?php echo JHtml::fetch('select.options', $options); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- WIDGET POSITION - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', '', ''),
		);

		foreach ($this->positions as $position)
		{
			$options[] = JHtml::fetch('select.option', $position, $position);
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAP_WIDGET_POSITION'),
			'content' => JText::translate('VAP_WIDGET_POSITION_DESC'),
		));

		echo $vik->openControl(JText::translate('VAP_WIDGET_POSITION') . '*' . $help); ?>
			<select name="widget_position" class="field required">
				<?php echo JHtml::fetch('select.options', $options); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- WIDGET SIZE - Select -->

		<?php
		$options = array(
			JHtml::fetch('select.option', '', ''),
			JHtml::fetch('select.option', 'extra-small', JText::translate('VAP_WIDGET_SIZE_OPT_EXTRA_SMALL')),
			JHtml::fetch('select.option', 'small', JText::translate('VAP_WIDGET_SIZE_OPT_SMALL')),
			JHtml::fetch('select.option', 'normal', JText::translate('VAP_WIDGET_SIZE_OPT_NORMAL')),
			JHtml::fetch('select.option', 'large', JText::translate('VAP_WIDGET_SIZE_OPT_LARGE')),
			JHtml::fetch('select.option', 'extra-large', JText::translate('VAP_WIDGET_SIZE_OPT_EXTRA_LARGE')),
		);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAP_WIDGET_SIZE'),
			'content' => JText::translate('VAP_WIDGET_SIZE_DESC'),
		));

		echo $vik->openControl(JText::translate('VAP_WIDGET_SIZE') . $help); ?>
			<select name="widget_size" class="field">
				<?php echo JHtml::fetch('select.options', $options); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

	</div>

	<?php
	foreach ($this->supported as $widget)
	{
		?>
		<div 
			class="inspector-fieldset widget-desc"
			data-name="<?php echo $widget->getName(); ?>"
			data-title="<?php echo $this->escape($widget->getTitle()); ?>"
			style="display:none;"
		>
			<?php
			// show widget description, if any
			$desc = $widget->getDescription();

			if ($desc)
			{
				echo $vik->alert($desc, 'info');
			}
			?>
		</div>
		<?php
	}
	?>

	<input type="hidden" name="widget_id" value="0" />

</div>

<?php
JText::script('VAP_WIDGET_SELECT_CLASS');
JText::script('VAP_WIDGET_SELECT_POSITION');
JText::script('VAP_WIDGET_SIZE_OPT_DEFAULT');
?>

<script>

	var widgetValidator = new VikFormValidator('#inspector-widget-form');

	(function($) {
		'use strict';

		$(function() {
			$('#inspector-widget-form select[name="widget_class"]').select2({
				placeholder: Joomla.JText._('VAP_WIDGET_SELECT_CLASS'),
				allowClear: false,
			});

			$('#inspector-widget-form select[name="widget_position"]').select2({
				placeholder: Joomla.JText._('VAP_WIDGET_SELECT_POSITION'),
				allowClear: false,
			});

			$('#inspector-widget-form select[name="widget_size"]').select2({
				minimumResultsForSearch: -1,
				placeholder: Joomla.JText._('VAP_WIDGET_SIZE_OPT_DEFAULT'),
				allowClear: true,
			});

			$('#inspector-widget-form select[name="widget_class"]').on('change', function() {
				// hide all descriptions
				$('#inspector-widget-form .widget-desc').hide();

				// get selected widget
				var widget = $('#inspector-widget-form .widget-desc[data-name="' + $(this).val() + '"]');

				// get name input
				var nameInput = $('#inspector-widget-form input[name="widget_name"]');

				// set up placeholder
				nameInput.attr('placeholder', widget.data('title'));

				if (nameInput.val() == widget.data('title')) {
					// specified title is equals to the default one, unset it
					nameInput.val('');
				}

				// show description of selected widget
				widget.show();
			});
		});
	})(jQuery);

	function setupWidgetData(data) {
		// fill ID
		jQuery('#inspector-widget-form input[name="widget_id"]').val(data.id ? data.id : 0);

		// fill name
		jQuery('#inspector-widget-form input[name="widget_name"]').val(data.name);

		// fill widget class
		data.widget = data.widget || data.class;

		jQuery('#inspector-widget-form select[name="widget_class"]').select2('val', data.widget ? data.widget : '').trigger('change');

		// fill widget position
		jQuery('#inspector-widget-form select[name="widget_position"]').select2('val', data.position ? data.position : '');

		// fill widget size
		jQuery('#inspector-widget-form select[name="widget_size"]').select2('val', data.size ? data.size : '');
	}

	function getWidgetData() {
		var data = {};

		// extract widget data
		jQuery('#inspector-widget-form')
			.find('input,select')
				.filter('[name^="widget_"]')
					.each(function() {
						var name  = jQuery(this).attr('name').replace(/^widget_/, '');
						var value = jQuery(this).val();

						data[name] = value;
					});

		// replicate CLASS in WIDGET property
		data.widget = data.class;

		return data;
	}

	function getDefaultWidget(widget) {
		// get widget
		var widget = jQuery('#inspector-widget-form .widget-desc[data-name="' + widget + '"]');

		var data = {
			name: widget.data('name'),
			title: widget.data('title'),
			description: widget.html(),
		};

		return data;
	}

	function addPositionOption(position) {
		jQuery('#inspector-widget-form select[name="widget_position"]').append('<option value="' + position + '">' + position + '</option>');
	}

</script>
