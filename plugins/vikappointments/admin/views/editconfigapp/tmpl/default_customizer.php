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

// create object to pass to the hook, so that external plugins
// can extend the appearance of any additional tab
$setup = new stdClass;
$setup->icons = array();

$tabs = array();

foreach ($this->customizerTree as $nodeName => $nodeLevels)
{
	$tab_lang_key = 'VAP_CUSTOMIZER_TAB_' . strtoupper($nodeName);

	// attempt to translate tab label
	$tab_label = JText::translate($tab_lang_key);

	if ($tab_label === $tab_lang_key)
	{
		// prettify default name
		$tab_label = ucfirst($nodeName);
	}

	$this->customizerNode     = $nodeLevels;
	$tabs[$tab_label]         = $this->loadTemplate('customizer_form');

	switch ($nodeName)
	{
		case 'calendar':
			$setup->icons[$tab_label] = 'fas fa-calendar-alt';
			break;

		case 'timeline':
			$setup->icons[$tab_label] = 'fas fa-clock';
			break;

		case 'button':
			$setup->icons[$tab_label] = 'fas fa-square';
			break;
	}
}

// add tab to write custom CSS code
$tabs['VAP_CUSTOMIZER_TAB_ADDITIONALCSS']         = $this->loadTemplate('customizer_addcss');
$setup->icons['VAP_CUSTOMIZER_TAB_ADDITIONALCSS'] = 'fas fa-code';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigappCustomizer". The event method receives the
 * view instance as argument.
 *
 * @since 1.7.2
 */
$forms = $this->onDisplayView('Customizer', $setup);

// create display data
$data = array();
$data['id']     = 4;
$data['active'] = $this->selectedTab == $data['id'];
$data['tabs']   = array_merge($tabs, $forms);
$data['setup']  = $setup;
$data['hook']   = 'Customizer';
$data['suffix'] = 'app';

$data['before'] = $this->loadTemplate('customizer_toolbar');
$data['after']  = $this->loadTemplate('customizer_preview');

// render configuration pane with apposite layout
echo JLayoutHelper::render('configuration.tabview', $data);

JText::script('VAP_CUSTOMIZER_RESTORE_FACTORY_SETTINGS');
?>

<script>
	(function($) {
		'use strict';

		// get default customizer settings
		const defaultSettings = <?php echo json_encode($this->customizerModel->getItem()); ?>;

		$(function() {
			$('.restore-customizer-settings').on('click', function() {
				// ask for a confirmation before to proceed
				const r = confirm(Joomla.JText._('VAP_CUSTOMIZER_RESTORE_FACTORY_SETTINGS'));

				if (!r) {
					return false;
				}

				// find all fields related to the clicked button
				const fields = $(this).closest('.config-fieldset-body').find('[name^="customizer["]');

				fields.each(function() {
					// extract CSS var from name
					const match = $(this).attr('name').match(/^customizer\[(.*?)\]$/);
					const key   = match[1];

					if (!defaultSettings.hasOwnProperty(key)) {
						// var not found...
						return false;
					}

					let value = defaultSettings[key];

					if (key.match(/-(?:color|background|border)$/)) {
						value = value.replace(/^#/, '');
					}

					// restore original value
					$(this).val(value).trigger('change');
				});
			});
		});
	})(jQuery);
</script>
