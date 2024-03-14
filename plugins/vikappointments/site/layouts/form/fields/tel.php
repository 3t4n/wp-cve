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

$name  = !empty($displayData['name']) ? $displayData['name']  : 'name';
$id    = !empty($displayData['id'])   ? $displayData['id']    : $name;
$value = isset($displayData['value']) ? $displayData['value'] : '';
$class = isset($displayData['class']) ? $displayData['class'] : '';

$config = array(
	// custom data to be passed when initializing
	// international tel input
	'data' => array(
		// display flags dropdown according to the
		// global configuration (Show Prefix Selection)
		'allowDropdown' => VAPFactory::getConfig()->getBool('showphprefix'),
	),
);

// render input using intltel
JHtml::fetch('vaphtml.assets.intltel', '#' . $id, $config);

?>

<input
	type="tel"
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	value="<?php echo $this->escape($value); ?>"
	size="40"
	class="<?php echo $this->escape($class); ?>"
	aria-labelledby="<?php echo $id; ?>-label"
/>

<input type="hidden" name="<?php echo $this->escape($id); ?>_dialcode" value="" />
<input type="hidden" name="<?php echo $this->escape($id); ?>_country" value="" />

<script>
	
	jQuery(function($) {
		// save "country code" and "dial code" every time the phone number changes
		$('#<?php echo $id; ?>').on('change countrychange', function() {
			var country = $(this).intlTelInput('getSelectedCountryData');

			if (!country) {
				return false;
			}

			if (country.iso2) {
				$('input[name="<?php echo $id; ?>_country"]').val(country.iso2.toUpperCase());
			}

			if (country.dialCode) {
				var dial = '+' + country.dialCode.toString().replace(/^\+/);

				if (country.areaCodes) {
					dial += ' ' + country.areaCodes[0];
				}

				$('input[name="<?php echo $id; ?>_dialcode"]').val(dial);
			}
		}).trigger('change');
	});

</script>
