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

$service = $this->service;

$vik = VAPApplication::getInstance();

$interval = VAPFactory::getConfig()->getUint('minuteintervals');

?>

<!-- DURATION - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE4') . '*'); ?>
	<div class="input-append">
		<input type="number" name="duration" class="required" value="<?php echo $service->duration; ?>" size="10" min="1" max="99999999" id="vapdurationinput" />
	
		<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
	</div>
<?php echo $vik->closeControl(); ?> 

<!-- SLEEP - Number -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE19'),
	'content' => JText::translate('VAPMANAGESERVICE19_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE19') . $help); ?>
	<div class="input-append">
		<input type="number" name="sleep" value="<?php echo $service->sleep; ?>" size="10" min="-9999999" max="99999999" id="vapsleepinput" />
		
		<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
	</div>
<?php echo $vik->closeControl(); ?> 

<!-- TIME SLOTS LENGTH - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', 1, JText::sprintf('VAPSERVICETIMESLOTSLEN1', $service->duration + $service->sleep)),
	JHtml::fetch('select.option', 2, JText::sprintf('VAPSERVICETIMESLOTSLEN2', $interval)),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE20'),
	'content' => JText::translate('VAPMANAGESERVICE20_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE20') . $help); ?>
	<select name="interval" id="vap-interval-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $service->interval); ?>
	</select>
<?php echo $vik->closeControl(); ?> 

<!-- BOOKING MINUTES RESTRICTIONS - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', 1, 'VAPASGLOBAL'),
	JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE37'),
	'content' => JText::translate('VAPMANAGESERVICE37_DESC'),
));

$bmr = $service->minrestr == -1 ? 1 : 2;

echo $vik->openControl(JText::translate('VAPMANAGESERVICE37') . $help); ?>
	<div class="inline-fields">
		<select id="vap-minrestr-sel" class="flex-basis-60">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $bmr, true); ?>
		</select>

		<div class="input-append flex-basis-40" id="minrestr-input" style="<?php echo $bmr == 1 ? 'display:none;' : ''; ?>">
			<input type="number" name="minrestr" value="<?php echo $service->minrestr; ?>" min="<?php echo $bmr == 1 ? -1 : 0; ?>" max="999999" step="1" />
			
			<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
		</div>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- MIN DATE - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', 1, 'VAPASGLOBAL'),
	JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECONFIG122'),
	'content' => JText::translate('VAPMANAGECONFIG122_DESC'),
));

$md = $service->mindate == -1 ? 1 : 2;

echo $vik->openControl(JText::translate('VAPMANAGECONFIG122') . $help); ?>
	<div class="inline-fields">
		<select id="vap-mindate-sel" class="flex-basis-60">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $md, true); ?>
		</select>

		<div class="input-append flex-basis-40" id="mindate-input" style="<?php echo $md == 1 ? 'display:none;' : ''; ?>">
			<input type="number" name="mindate" value="<?php echo $service->mindate; ?>" min="<?php echo $md == 1 ? -1 : 0; ?>" max="999999" step="1" />
			
			<span class="btn"><?php echo JText::translate('VAPDAYSLABEL'); ?></span>
		</div>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- MAX DATE - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', 1, 'VAPASGLOBAL'),
	JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECONFIG123'),
	'content' => JText::translate('VAPMANAGECONFIG123_DESC'),
));

$md = $service->maxdate == -1 ? 1 : 2;

echo $vik->openControl(JText::translate('VAPMANAGECONFIG123') . $help); ?>
	<div class="inline-fields">
		<select id="vap-maxdate-sel" class="flex-basis-60">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $md, true); ?>
		</select>

		<div class="input-append flex-basis-40" id="maxdate-input" style="<?php echo $md == 1 ? 'display:none;' : ''; ?>">
			<input type="number" name="maxdate" value="<?php echo $service->maxdate; ?>" min="<?php echo $md == 1 ? -1 : 0; ?>" max="999999" step="1" />
			
			<span class="btn"><?php echo JText::translate('VAPDAYSLABEL'); ?></span>
		</div>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- EMPLOYEE CHOOSABLE - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->choose_emp == 1, 'onclick="chooseEmpValueChanged(1);"');
$no  = $vik->initRadioElement('', '', $service->choose_emp == 0, 'onclick="chooseEmpValueChanged(0);"');

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE18'),
	'content' => JText::translate('VAPMANAGESERVICE18_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE18') . $help);
echo $vik->radioYesNo('choose_emp', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- RANDOM EMPLOYEE - Checkbox -->

<?php
$control = array();
$control['style'] = $service->choose_emp ? '' : 'display:none;';

$yes = $vik->initRadioElement('', '', $service->random_emp == 1);
$no  = $vik->initRadioElement('', '', $service->random_emp == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE41'),
	'content' => JText::translate('VAPMANAGESERVICE41_HELP'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE41') . $help, 'choose-emp-child', $control);
echo $vik->radioYesNo('random_emp', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- HAS OWN CALENDAR - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->has_own_cal == 1);
$no  = $vik->initRadioElement('', '', $service->has_own_cal == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE33'),
	'content' => JText::translate('VAPHASOWNCALMESSAGE'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE33') . $help);
echo $vik->radioYesNo('has_own_cal', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- CHECKOUT SELECTION - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->checkout_selection == 1, 'onclick="timelineLayoutValueChanged(\'checkout_selection\');"');
$no  = $vik->initRadioElement('', '', $service->checkout_selection == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE35'),
	'content' => JText::translate('VAPMANAGESERVICE35_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE35') . $help);
echo $vik->radioYesNo('checkout_selection', $yes, $no, false);
echo $vik->closeControl();
?>

<?php
JText::script('VAPSERVICETIMESLOTSLEN1');
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vap-interval-sel, #vap-minrestr-sel, #vap-mindate-sel, #vap-maxdate-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: '90%',
			});

			$('#vapdurationinput, #vapsleepinput').on('change', () => {
				var duration = parseInt($('#vapdurationinput').val());
				var sleep 	 = parseInt($('#vapsleepinput').val());
				
				$('#vap-interval-sel').children().first().text(
					Joomla.JText._('VAPSERVICETIMESLOTSLEN1').replace(/%d/, (duration + sleep))
				);

				$('#vap-interval-sel').select2('val', $('#vap-interval-sel').val());
			});

			$('#vap-minrestr-sel').on('change', function() {
				if ($(this).val() == 1) {
					$('#minrestr-input').hide()
						.find('input').attr('min', -1).val(-1);
				} else {
					$('#minrestr-input').show()
						.find('input').attr('min', 0).val(0);
				}
			});

			$('#vap-mindate-sel').on('change', function() {
				if ($(this).val() == 1) {
					$('#mindate-input').hide()
						.find('input').attr('min', -1).val(-1);
				} else {
					$('#mindate-input').show()
						.find('input').attr('min', 0).val(0);
				}
			});

			$('#vap-maxdate-sel').on('change', function() {
				if ($(this).val() == 1) {
					$('#maxdate-input').hide()
						.find('input').attr('min', -1).val(-1);
				} else {
					$('#maxdate-input').show()
						.find('input').attr('min', 0).val(0);
				}
			});
		});
	})(jQuery);

	function chooseEmpValueChanged(is) {
		if (is) {
			jQuery('.choose-emp-child').show();
		} else {
			jQuery('.choose-emp-child').hide();
		}
	}

</script>
