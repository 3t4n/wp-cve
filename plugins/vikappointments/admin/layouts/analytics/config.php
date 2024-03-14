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

/**
 * Layout variables
 * -----------------
 * @var  array  $dashboard  An array 
 */
extract($displayData);

$vik = VAPApplication::getInstance();

$layout = new JLayoutFile('form.fields');

?>

<div class="inspector-form" id="inspector-config-form">

	<?php
	// iterate all positions
	foreach ($dashboard as $widgets)
	{
		// iterate position widgets
		foreach ($widgets as $widget)
		{
			?>
			<div
				class="inspector-fieldset"
				data-id="<?php echo $widget->getID(); ?>"
				data-widget="<?php echo $widget->getName(); ?>"
				style="display: none;">

				<h3><?php echo $widget->getTitle(); ?></h3>

				<?php
				// get widget description
				$desc = $widget->getDescription();

				if ($desc)
				{
					// display description before configuration form
					echo $vik->alert($desc, 'info');
				}

				// prepare layout data
				$data = array(
					'fields' => $widget->getForm(),
					'params' => $widget->getParams(),
					'prefix' => $widget->getName() . '_' . $widget->getID() . '_',
				);

				// display widget configuration
				echo $layout->render($data);
				?>

			</div>
			<?php
		}
	}
	?>

</div>

<script>

	(function($) {
		'use strict';

		$(function() {
			VikRenderer.chosen('.inspector-form', '100%');
		});
	})(jQuery);

	function setupWidgetConfig(id) {
		jQuery('.inspector-fieldset').hide();
		jQuery('.inspector-fieldset[data-id="' + id + '"]').show();
	}

	function updateWidgetConfig(id, key, value) {
		// extract widget name
		var widget = jQuery('.inspector-fieldset[data-id="' + id + '"]').data('widget');
		// find target input
		const input = jQuery('*[name="' + widget + '_' + id + '_' + key + '"]');

		if (input.length == 0) {
			return false;
		}
		
		if (input.is(':checkbox')) {
			input.prop('checked', value ? true : false);
		} else {
			input.val(value);

			// check whether the input owns an alternative value to update (such as the calendar)
			if (input.attr('data-alt-value') !== undefined) {
				// update it too
				input.attr('data-alt-value', value);
			}
		}

		return true;
	}

	function getWidgetConfig(id) {
		var config = {};

		var widget = jQuery('.inspector-fieldset[data-id="' + id + '"]').data('widget');

		jQuery('.inspector-fieldset[data-id="' + id + '"]')
			.find('input,select')
				.filter('[name^="' + widget + '_"]')
					.each(function() {
						var name = jQuery(this).attr('name').replace(new RegExp('^' + widget + '_' + id + '_'), '');

						if (jQuery(this).is(':checkbox')) {
							config[name] = jQuery(this).is(':checked') ? 1 : 0;
						} else {
							config[name] = jQuery(this).val();
						}
					});

		return config;
	}

</script>
