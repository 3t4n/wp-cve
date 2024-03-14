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

// build layout used to render the parameters form
$formLayout = new JLayoutFile('form.fields');

foreach ($this->customizerNode as $k => $fields)
{
	$fieldset_lang_key = 'VAP_CUSTOMIZER_FIELDSET_' . strtoupper($k);

	// attempt to translate parameter label
	$fieldset_label = JText::translate($fieldset_lang_key);

	if ($fieldset_label === $fieldset_lang_key)
	{
		// prettify default name
		$fieldset_label = ucfirst($k);
	}

	$params = [];

	foreach ($fields as $name => $field)
	{
		$lang_key = 'VAP_CUSTOMIZER_PARAM_' . strtoupper($name);

		// attempt to translate parameter label
		$label = JText::translate($lang_key);

		if ($label === $lang_key)
		{
			// prettify default name
			$label = ucwords(str_replace('_', ' ', $name));
		}

		$params[$name] = [
			'type'    => $field['type'],
			'name'    => 'customizer[' . $field['key'] . ']',
			'value'   => $field['val'],
			'label'   => $label,
			'preview' => true,
		];
	}

	?>
	<div class="config-fieldset full-width">

		<div class="config-fieldset-head">
			<h3><?php echo $fieldset_label; ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php
			// render parameters form
			echo $formLayout->render(['fields' => $params]);
			?>

			<!-- RESTORE - Button -->

			<?php echo $vik->openControl(''); ?>
				<button type="button" class="btn restore-customizer-settings"><?php echo JText::translate('VAPRESTORE'); ?></button>
			<?php echo $vik->closeControl(); ?>
		</div>

	</div>
	<?php
}
