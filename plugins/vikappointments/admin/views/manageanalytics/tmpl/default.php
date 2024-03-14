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

JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

$last_widget_id = 0;

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">
	
	<div class="widgets-builder">
		
		<?php
		foreach ($this->dashboard as $position => $widgets)
		{
			?>
			<div class="widgets-position-row" data-position="<?php echo $this->escape($position); ?>">

				<h3><?php echo $position; ?></h3>
				
				<div class="widgets-position-container">

					<?php
					foreach ($widgets as $widget)
					{
						$last_widget_id = max(array($last_widget_id, $widget->getID()));

						?>
						<div class="widget-thumb" data-widget="<?php echo $this->escape($widget->getName()); ?>" data-id="<?php echo $widget->getID(); ?>">
							
							<h3>
								<span><?php echo $widget->getTitle(); ?></span>
								<a href="javascript:void(0)" onclick="openWidgetInspector(<?php echo $widget->getID(); ?>);" style="margin-left: 2px;">
									<i class="fas fa-cogs"></i>
								</a>		
							</h3>

							<div class="descr">
								<?php
								$desc = $widget->getDescription();

								if ($desc)
								{
									echo $vik->alert($desc, 'info');
								}
								?>
							</div>

							<i class="fas fa-ellipsis-v widget-sort-handle"></i>

							<input type="hidden" name="widget_id[]" value="<?php echo $widget->getID(); ?>" />
							<input type="hidden" name="widget_id_user[]" value="<?php echo $widget->getUserID(); ?>" />
							<input type="hidden" name="widget_name[]" value="<?php echo $this->escape($widget->getTitle($default = false)); ?>" />
							<input type="hidden" name="widget_class[]" value="<?php echo $this->escape($widget->getName()); ?>" />
							<input type="hidden" name="widget_position[]" value="<?php echo $this->escape($position); ?>" />
							<input type="hidden" name="widget_size[]" value="<?php echo $this->escape($widget->getSize()); ?>" />

						</div>
						<?php
					}
					?>

					<div class="widget-thumb add-new-widget">
						<i class="fas fa-plus"></i>
					</div>

				</div>

				<i class="fas fa-ellipsis-v position-sort-handle"></i>

			</div>
			<?php
		}
		?>

		<div class="widgets-position-row add-new-position">
			<i class="fas fa-plus"></i>
		</div>

	</div>
	
	<input type="hidden" name="location" value="<?php echo $this->escape($this->location); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// render inspector to manage positions
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'widgets-position-inspector',
	array(
		'title'       => JText::translate('VAP_ADD_POSITION'),
		'closeButton' => true,
		'keyboard'    => false,
		'footer'      => '<button type="button" class="btn btn-success" id="position-save-config" data-role="save">' . JText::translate('JAPPLY') . '</button>',
		'width'       => 400,
	),
	$this->loadTemplate('position_modal')
);

// render inspector to manage widgets

$footer = '<button type="button" class="btn btn-success" id="widget-save-config" data-role="save">' . JText::translate('JAPPLY') . '</button>'
	. '<button type="button" class="btn btn-danger" id="widget-delete-btn" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

echo JHtml::fetch(
	'vaphtml.inspector.render',
	'widget-config-inspector',
	array(
		'title'       => JText::translate('VAP_ADD_WIDGET'),
		'closeButton' => true,
		'keyboard'    => false,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('widget_modal')
);

JText::script('VAP_ADD_POSITION');
JText::script('VAP_ADD_WIDGET');
JText::script('VAP_EDIT_WIDGET');
?>

<script>

	var LAST_WIDGET_ID  = <?php echo $last_widget_id; ?>;
	var SELECTED_WIDGET = null;

	(function($) {
		'use strict';

		const makePositionSortable = (selector) => {
			$(selector).sortable({
				cursor: 'move',
				handle: '.widget-sort-handle',
				items:  '.widget-thumb',
				cancel: '.add-new-widget',
				connectWith: '.widgets-position-row .widgets-position-container',
				revert: false,
				receive: (event, ui) => {
					// get new position
					var position = $(ui.item).closest('.widgets-position-row').data('position');

					// update widget position (input)
					$(ui.item).find('input[name^="widget_position["]').val(position);
				},
			});

			$('.widgets-position-row').disableSelection();
		}

		$(function() {
			$('.widgets-builder').sortable({
				axis:   'y',
				cursor: 'move',
				handle: '.position-sort-handle',
				items:  '.widgets-position-row:not(.add-new-position)',
				revert: false,
			});

			makePositionSortable('.widgets-position-row .widgets-position-container');

			$('.widget-thumb').disableSelection();

			// new position placeholder clicked
			$('.add-new-position').on('click', function() {
				// clear position form before showing it
				clearPositionForm();

				// open position inspector
				vapOpenInspector('widgets-position-inspector');
			});

			// create new position
			$('#widgets-position-inspector').on('inspector.save', function() {
				// validate position first
				if (!positionValidator.validate()) {
					return false;
				}

				// get position data
				var data = getPositionData();

				// create position block
				var block = '<div class="widgets-position-row" data-position="' + data.position + '">\n' + 
					'<h3>' + data.position + '</h3>\n' +
					'<div class="widgets-position-container">\n' +
						'<div class="widget-thumb add-new-widget">\n' +
							'<i class="fas fa-plus"></i>\n' +
						'</div>\n'+
						'<i class="fas fa-ellipsis-v position-sort-handle"></i>\n' +
					'</div>\n' +
				'</div>';

				// insert new position before ADD placeholder
				$(block).insertBefore('.add-new-position');

				// make it sortable
				makePositionSortable('.widgets-position-row[data-position="' + data.position + '"] .widgets-position-container');

				// add position to widget modal dropdown
				addPositionOption(data.position);

				// dismiss inspector
				$(this).inspector('dismiss');
			});

			// new widget placeholder clicked
			$('.widgets-builder').on('click', '.add-new-widget', function() {
				// get widget position
				var position = $(this).closest('.widgets-position-row').data('position');

				// pre-fill only the position when creating a new widget
				setupWidgetData({position: position});

				// open widget inspector
				openWidgetInspector();
			});

			// fill the form before showing the inspector
			$('#widget-config-inspector').on('inspector.show', function() {
				// make sure we are editing a widget
				if (SELECTED_WIDGET) {
					var data = {};

					// extract widget data
					$('.widget-thumb[data-id="' + SELECTED_WIDGET + '"]')
						.find('input[name^="widget_"]')
							.each(function() {
								var name  = $(this).attr('name').match(/^widget_([a-z0-9_]+)\[\]$/i);
								var value = $(this).val();

								if (name && name.length) {
									data[name[1]] = value;
								}
							});

					// pre-fill inspector with widget data
					setupWidgetData(data);
				}
			});

			// save widget
			$('#widget-config-inspector').on('inspector.save', function() {
				// validate widget first
				if (!widgetValidator.validate()) {
					return false;
				}

				// get widget data
				var data = getWidgetData();

				if (!SELECTED_WIDGET) {
					// insert widget block first and update widget temporary ID
					SELECTED_WIDGET = insertWidgetBlock(data);
				}

				// then fill inputs with data
				updateWidgetBlock(data);

				// dismiss inspector
				$(this).inspector('dismiss');
			});

			// delete widget
			$('#widget-config-inspector').on('inspector.delete', function() {
				// get widget block
				var widget = $('.widget-thumb[data-id="' + SELECTED_WIDGET + '"]');

				// get widget ID
				var id = parseInt(widget.find('input[name^="widget_id["]').val());

				if (id > 0) {
					// register widget ID to delete
					$('#adminForm').append('<input type="hidden" name="widgets_delete[]" value="' + id + '" />');
				}

				// remove block
				widget.remove();

				// dismiss inspector
				$(this).inspector('dismiss');
			});

		});
	})(jQuery);

	function openWidgetInspector(widget) {
		SELECTED_WIDGET = widget;

		var title;

		if (typeof widget === 'undefined') {
			title = Joomla.JText._('VAP_ADD_WIDGET');
			jQuery('#widget-delete-btn').hide();
		} else {
			title = Joomla.JText._('VAP_EDIT_WIDGET');
			jQuery('#widget-delete-btn').show();
		}

		// open inspector
		vapOpenInspector('widget-config-inspector', {title: title});
	}

	function updateWidgetBlock(data) {
		// get widget block
		var block = jQuery('.widget-thumb[data-id="' + SELECTED_WIDGET + '"]');

		// extract widget data
		jQuery('.widget-thumb[data-id="' + SELECTED_WIDGET + '"]')
			.find('input[name^="widget_"]')
				.each(function() {
					var name  = jQuery(this).attr('name').match(/^widget_([a-z0-9_]+)\[\]$/i);

					if (data.hasOwnProperty(name)) {
						jQuery(this).val(data[name]);
					}
				});

		// get default widget data
		var widget = getDefaultWidget(data.widget);

		// set widget name (if empty, the default title will be used)
		block.find('h3 span').text(data.name || widget.title);
		block.find('input[name^="widget_name["]').val(data.name);

		// update widget class
		block.find('input[name^="widget_class["]').val(data.widget);

		// update position
		block.find('input[name^="widget_position["]').val(data.position);

		// update size
		block.find('input[name^="widget_size["]').val(data.size);

		// refresh widget description
		block.find('div.descr').html(widget.description);

		// get current position in which the block is placed
		var prev_pos = block.closest('.widgets-position-row').data('position');

		// check if the position has changed
		if (data.position != prev_pos) {
			// move block in the right position
			block.prependTo('.widgets-position-row[data-position="' + data.position + '"] .widgets-position-container');
		}
	}

	function insertWidgetBlock(data) {
		LAST_WIDGET_ID++;

		// create widget HTML
		var html = '<div class="widget-thumb" data-widget="' + data.widget + '" data-id="' + LAST_WIDGET_ID + '">\n'+
			'<h3>\n'+
				'<span></span>\n'+
				'<a href="javascript:void(0);" onclick="openWidgetInspector(' + LAST_WIDGET_ID + ');" style="margin-left: 2px;">\n'+
					'<i class="fas fa-cogs"></i>\n'+
				'</a>\n'+
			'</h3>\n'+
			'<div class="descr">\n</div>\n'+
			'<i class="fas fa-ellipsis-v widget-sort-handle"></i>\n'+
			'<input type="hidden" name="widget_id[]" value="0" />\n'+
			'<input type="hidden" name="widget_id_user[]" value="<?php echo JFactory::getUser()->id; ?>" />\n'+
			'<input type="hidden" name="widget_name[]" value="" />\n'+
			'<input type="hidden" name="widget_class[]" value="" />\n'+
			'<input type="hidden" name="widget_position[]" value="" />\n'+
			'<input type="hidden" name="widget_size[]" value="" />\n'+
		'</div>\n';

		// insert HTML before the ADD placeholder
		jQuery(html).insertBefore('.widgets-position-row[data-position="' + data.position + '"] .add-new-widget');

		jQuery('.widget-thumb').disableSelection();

		return LAST_WIDGET_ID;
	}
	
</script>
