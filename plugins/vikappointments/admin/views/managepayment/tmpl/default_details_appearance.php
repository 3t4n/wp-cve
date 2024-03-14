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

$payment = $this->payment;

$vik = VAPApplication::getInstance();

?>

<!-- ICON - Fieldset -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 1, JText::translate('VAPPAYMENTICONOPT1')),
	JHtml::fetch('select.option', 2, JText::translate('VAPPAYMENTICONOPT2')),
);

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT15')); ?>
	<select name="icontype" id="vap-icontype-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $payment->icontype); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- FONT ICON - Select -->

<?php
$font_icons = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 'fab fa-paypal', 'PayPal'),
	JHtml::fetch('select.option', 'fab fa-cc-paypal', 'PayPal #2'),

	JHtml::fetch('select.option', 'fas fa-credit-card', 'Credit Card'),
	JHtml::fetch('select.option', 'far fa-credit-card', 'Credit Card #2'),
	JHtml::fetch('select.option', 'fab fa-cc-visa', 'Visa'),
	JHtml::fetch('select.option', 'fab fa-cc-mastercard', 'Mastercard'),
	JHtml::fetch('select.option', 'fab fa-cc-amex', 'American Express'),
	JHtml::fetch('select.option', 'fab fa-cc-discover', 'Discovery'),
	JHtml::fetch('select.option', 'fab fa-cc-jcb', 'JCB'),
	JHtml::fetch('select.option', 'fab fa-cc-diners-club', 'Diners Club'),
	JHtml::fetch('select.option', 'fab fa-stripe', 'Stripe'),
	JHtml::fetch('select.option', 'fab fa-cc-stripe', 'Stripe #2'),
	JHtml::fetch('select.option', 'fab fa-stripe-s', 'Stripe (S)'),

	JHtml::fetch('select.option', 'fas fa-euro-sign', 'Euro'),
	JHtml::fetch('select.option', 'fas fa-dollar-sign', 'Dollar'),
	JHtml::fetch('select.option', 'fas fa-pound-sign', 'Pound'),
	JHtml::fetch('select.option', 'fas fa-yen-sign', 'Yen'),
	JHtml::fetch('select.option', 'fas fa-won-sign', 'Won'),
	JHtml::fetch('select.option', 'fas fa-rupee-sign', 'Rupee'),
	JHtml::fetch('select.option', 'fas fa-ruble-sign', 'Ruble'),
	JHtml::fetch('select.option', 'fas fa-lira-sign', 'Lira'),
	JHtml::fetch('select.option', 'fas fa-shekel-sign', 'Shekel'),

	JHtml::fetch('select.option', 'fas fa-money-bill', 'Money'),
	JHtml::fetch('select.option', 'fas fa-money-bill-wave', 'Money #2'),
	JHtml::fetch('select.option', 'fas fa-money-check-alt', 'Money #3'),
);

$control = array();
$control['style']    = $payment->icontype == 1 ? '' : 'display: none;';
$control['idparent'] = 'vap-fonticon-wrapper';

echo $vik->openControl('', 'multi-field no-margin-last-3', $control); ?>
	<select name="font_icon" id="vap-fonticon-sel">
		<?php echo JHtml::fetch('select.options', $font_icons, 'value', 'text', $payment->icontype == 1 ? $payment->icon : null); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- IMAGE - Select -->

<?php
$control = array();
$control['style']    = $payment->icontype == 2 ? '' : 'display: none;';
$control['idparent'] = 'vap-iconupload-wrapper';

echo $vik->openControl('', '', $control);
echo $vik->getMediaField('upload_icon', $payment->icontype == 2 ? $payment->icon : '');
echo $vik->closeControl();
?>

<!-- POSITION - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 'vap-payment-position-top-left', JText::translate('VAPPAYMENTPOSOPT2')),
	JHtml::fetch('select.option', 'vap-payment-position-top-center', JText::translate('VAPPAYMENTPOSOPT3')),
	JHtml::fetch('select.option', 'vap-payment-position-top-right', JText::translate('VAPPAYMENTPOSOPT4')),
	JHtml::fetch('select.option', 'vap-payment-position-bottom-left', JText::translate('VAPPAYMENTPOSOPT5')),
	JHtml::fetch('select.option', 'vap-payment-position-bottom-center', JText::translate('VAPPAYMENTPOSOPT6')),
	JHtml::fetch('select.option', 'vap-payment-position-bottom-right', JText::translate('VAPPAYMENTPOSOPT7')),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGEPAYMENT12'),
	'content' => JText::translate('VAPMANAGEPAYMENT12_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT12')); ?>
	<select name="position" id="vap-position-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $payment->position); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<?php
JText::script('VAPPAYMENTPOSOPT1');
JText::script('VAPPAYMENTICONOPT0');
?>

<script>

	jQuery(function($) {
		$('#vap-position-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: Joomla.JText._('VAPPAYMENTPOSOPT1'),
			allowClear: true,
			width: '90%',
		});

		$('#vap-icontype-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: true,
			placeholder: Joomla.JText._('VAPPAYMENTICONOPT0'),
			width: '90%',
		});

		$('#vap-fonticon-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: '90%',
			formatResult: (opt) => {
				// Use a minimum width for the icons shown within the dropdown options
				// in order to have the texts properly aligned.
				// At the moment, the largest width of the icon seems to be 17px.
				return '<i class="' + opt.id + '" style="min-width:18px;"></i> ' + opt.text;
			},
			formatSelection: (opt) => {
				// Do not use a minimum width for the icon shown within the selection label.
				// Here we don't need to have a large space between the icon and the text.
				return '<i class="' + opt.id + '"></i> ' + opt.text;
			},
		});

		$('#vap-icontype-sel').on('change', function() {
			var val = $(this).val();

			if (val == 1) {
				$('#vap-fonticon-wrapper').show();
				$('#vap-iconupload-wrapper').hide();
			} else if (val == 2) {
				$('#vap-fonticon-wrapper').hide();
				$('#vap-iconupload-wrapper').show();
			} else {
				$('#vap-fonticon-wrapper').hide();
				$('#vap-iconupload-wrapper').hide();
			}
		});
	});

</script>
