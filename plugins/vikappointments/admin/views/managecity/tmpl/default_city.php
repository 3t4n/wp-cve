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

$city = $this->city;

$vik = VAPApplication::getInstance();

?>
			
<!-- CITY NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECITY1') . '*'); ?>
	<input type="text" name="city_name" class="input-xxlarge input-large-text required" value="<?php echo $city->city_name; ?>" size="40" id="vapcity" />
<?php echo $vik->closeControl(); ?>

<!-- CITY 2 CODE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECITY2')); ?>
	<input type="text" name="city_2_code" value="<?php echo $city->city_2_code; ?>" size="20" maxlength="2" />
<?php echo $vik->closeControl(); ?>

<!-- CITY 3 CODE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECITY3')); ?>
	<input type="text" name="city_3_code" value="<?php echo $city->city_3_code; ?>" size="20" maxlength="3" />
<?php echo $vik->closeControl(); ?>

<!-- LATITUDE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECITY4')); ?>
	<input class="city-latlng" type="number" name="latitude" value="<?php echo $city->latitude; ?>" size="40" id="vap-city-latitude" />
<?php echo $vik->closeControl(); ?>

<!-- LONGITUDE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECITY5')); ?>
	<input class="city-latlng" type="number" name="longitude" value="<?php echo $city->longitude; ?>" size="40" id="vap-city-longitude" />
<?php echo $vik->closeControl(); ?>

<!-- PUBLISHED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $city->published == 1);
$no  = $vik->initRadioElement('', '', $city->published == 0);

echo $vik->openControl(JText::translate('VAPMANAGECITY6'));
echo $vik->radioYesNo('published', $yes, $no, false);
echo $vik->closeControl();
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('.city-latlng').on('change', () => {
				changeCityLatLng($('#vap-city-latitude').val(), $('#vap-city-longitude').val());
			});

			$('#vapcity').on('change', function() {
				evaluateCoordinatesFromCity($(this).val());
			});
		});
	})(jQuery);

</script>
