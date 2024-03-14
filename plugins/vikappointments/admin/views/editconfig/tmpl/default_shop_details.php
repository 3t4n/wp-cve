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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigShopDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopDetails');

?>

<!-- SETTINGS -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPMANAGECRONJOBFIELDSET2'); ?></h3>
	</div>

	<div class="config-fieldset-body">
		
		<!-- DEFAULT STATUS - Select -->
		
		<?php
		$statusCodes = JHtml::fetch('vaphtml.status.find', array('code', 'name', 'approved'), array('appointments' => 1, 'reserved' => 1));

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG27'),
			'content' => JText::translate('VAPMANAGECONFIG27_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG27') . $help); ?>
			<select name="defstatus" class="small-medium">
				<?php echo JHtml::fetch('select.options', $statusCodes, 'code', 'name', $params['defstatus']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- SELF CONFIRMATION - Checkbox -->

		<?php
		$control = [];
		$control['style'] = JHtml::fetch('vaphtml.status.isapproved', 'appointments', $params['defstatus']) ? 'display:none;' : '';

		$yes = $vik->initRadioElement('', '', $params['selfconfirm'] == 1);
		$no  = $vik->initRadioElement('', '', $params['selfconfirm'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG127'),
			'content' => JText::translate('VAPMANAGECONFIG127_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG127') . $help, 'vap-defstatus-child', $control);
		echo $vik->radioYesNo('selfconfirm', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- SHOW CHECKOUT -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['showcheckout'] == 1);
		$no  = $vik->initRadioElement('', '', $params['showcheckout'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG118'),
			'content' => JText::translate('VAPMANAGECONFIG118_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG118') . $help);
		echo $vik->radioYesNo('showcheckout', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- LOGIN REQUIREMENTS - Select -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 0, 'VAPMANAGECONFIG99'),
			JHtml::fetch('select.option', 1, 'VAPMANAGECONFIG43'),
			JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG44'),
			JHtml::fetch('select.option', 3, 'VAPMANAGECONFIG89'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG42')); ?>
			<select name="loginreq" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['loginreq'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- PRINTABLE ORDERS - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['printorders'] == 1);
		$no  = $vik->initRadioElement('', '', $params['printorders'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG59'),
			'content' => JText::translate('VAPMANAGECONFIG59_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG59') . $help);
		echo $vik->radioYesNo('printorders', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- COUNTDOWN - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['showcountdown'] == 1);
		$no  = $vik->initRadioElement('', '', $params['showcountdown'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG120'),
			'content' => JText::translate('VAPMANAGECONFIG120_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG120') . $help);
		echo $vik->radioYesNo('showcountdown', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Shop > Settings fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>
	
</div>

<!-- CART -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE20'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- ENABLE CART FRAMEWORK - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['enablecart'] == 1, 'onClick="enableCartValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['enablecart'] == 0, 'onClick="enableCartValueChanged(0);"');
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG45'),
			'content' => JText::translate('VAPMANAGECONFIG45_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG45') . $help, 'shop-cart-setting');
		echo $vik->radioYesNo('enablecart', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- MAX ITEMS IN CART - Number -->
		
		<?php
		$control = array();
		$control['style'] = $params['enablecart'] == 0 ? 'display:none;' : '';

		if ($params['enablecart'] == 0)
		{
			// force to one in case the cart is disabled
			$params['maxcartsize'] = 1;
		}

		$options = array(
			JHtml::fetch('select.option', 1, 'VAPMANAGECONFIG47'),
			JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG46'), 'vapcartchildtr', $control); ?>
			<div class="inline-fields">
				<select class="small-medium" id="vap-maxcartsize-sel">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['maxcartsize'] > 0 ? 2 : 1, true); ?>
				</select>

				<input type="number" name="maxcartsize" value="<?php echo $params['maxcartsize']; ?>" size="20" min="<?php echo ($params['maxcartsize'] == "-1" ? '-1' : '1'); ?>" max="99999999" id="vapmaxcartsize" style="<?php echo ($params['maxcartsize'] == "-1" ? 'display: none;' : ''); ?>" /> 
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- CART ALLOW SYNC - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['cartallowsync'] == 1);
		$no  = $vik->initRadioElement('', '', $params['cartallowsync'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG98'),
			'content' => JText::translate('VAPMANAGECONFIG98_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG98') . $help, 'vapcartchildtr', $control);
		echo $vik->radioYesNo('cartallowsync', $yes, $no);	
		echo $vik->closeControl();
		?>
		
		<!-- SHOP CONTINUE LINK - Select -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', '', ''),
			JHtml::fetch('select.option', -2, JText::translate('VAPMANAGECONFIGSHOPOPT3')),
			JHtml::fetch('select.option', -1, JText::translate('VAPMANAGECONFIGSHOPOPT2')),
		);

		$options = array_merge($options, JHtml::fetch('vaphtml.admin.groups', 1));

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG53'),
			'content' => JText::translate('VAPMANAGECONFIG53_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG53') . $help, 'vapcartchildtr', $control); ?>
			<select name="shoplink" id="vap-shoplink-dropdown">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['shoplink']); ?>
			</select>
			
			<div id="vap-shoplink-text" style="<?php echo ($params['shoplink'] == -2 ? '' : 'display:none;'); ?>margin-top:5px;">
				<input type="text" name="shoplinkcustom" value="<?php echo $params['shoplinkcustom']; ?>" size="64" placeholder="/" />
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- CART ALREADY EXPANDED -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['confcartdisplay'] == 1);
		$no  = $vik->initRadioElement('', '', $params['confcartdisplay'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG84'),
			'content' => JText::translate('VAPMANAGECONFIG84_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG84') . $help);
		echo $vik->radioYesNo('confcartdisplay', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopDetails","key":"cart","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Shop > Cart fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['cart']))
		{
			echo $forms['cart'];

			// unset details form to avoid displaying it twice
			unset($forms['cart']);
		}
		?>

	</div>

</div>

<!-- DEPOSIT -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE19'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- USE DEPOSIT - Select -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 0, 'VAPCONFIGDEPOSITOPT0'),
			JHtml::fetch('select.option', 1, 'VAPCONFIGDEPOSITOPT1'),
			JHtml::fetch('select.option', 2, 'VAPCONFIGDEPOSITOPT2'),
		);

		$content = array();

		for ($i = 0; $i < count($options); $i++)
		{	
			$content[] = '<b>' . JText::translate('VAPCONFIGDEPOSITOPT' . $i) . '</b><br />' . JText::translate('VAPCONFIGDEPOSITOPT' . $i . '_DESC');
		}

		$help = $vik->createPopover(array(
			'title'	  => JText::translate('VAPMANAGECONFIG110'),
			'content' => implode('<br />', $content),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG110') . $help); ?>
			<select name="usedeposit" class="medium" id="vap-usedeposit-sel">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['usedeposit'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- DEPOSIT AFTER VALUE - Number -->
		
		<?php
		$control = array();
		$control['style'] = $params['usedeposit'] == 0 ? 'display:none;' : '';

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG50'),
			'content' => JText::translate('VAPMANAGECONFIG50_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG50') . $help, 'vap-deposit-child', $control); ?>
			<div class="input-prepend currency-field">
				<span class="btn"><?php echo $params['currencysymb']; ?></span>

				<input type="number" name="depositafter" value="<?php echo $params['depositafter']?>" min="0" max="999999999" size="10" step="any" />
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- DEPOSIT AMOUNT - Number -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 1, '%'),
			JHtml::fetch('select.option', 2, $params['currencysymb']),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG52'), 'vap-deposit-child', $control); ?>
			<div class="inline-fields">
				<input type="number" name="depositvalue" value="<?php echo $params['depositvalue']?>" min="1" max="999999999" size="10" step="any" />
				
				<select name="deposittype" class="short flex-auto">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['deposittype']); ?>
				</select>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopDetails","key":"deposit","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Shop > Deposit fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['deposit']))
		{
			echo $forms['deposit'];

			// unset details form to avoid displaying it twice
			unset($forms['deposit']);
		}
		?>

	</div>

</div>

<!-- CANCELLATION -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE18'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- ENABLE CANCELLATION - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('canc1', '', $params['enablecanc'] == 1, 'onClick="cancValueChanged(1);"');
		$no  = $vik->initRadioElement('canc0', '', $params['enablecanc'] == 0, 'onClick="cancValueChanged(0);"');
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG30'));
		echo $vik->radioYesNo('enablecanc', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- MIN CANCELLATION TIME - Number -->
		
		<?php
		$control = array();
		$control['style'] = $params['enablecanc'] == 0 ? 'display:none;' : '';

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG31'),
			'content' => JText::translate('VAPMANAGECONFIG31_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG31') . $help, 'vapconfcanctr', $control); ?>
			<div class="input-append">
				<input type="number" name="canctime" value="<?php echo $params['canctime']; ?>" size="40" min="0" max="999" step="1">
				
				<span class="btn"><?php echo JText::translate('VAPDAYSLABEL'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- USER CREDIT - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['usercredit'] == 1);
		$no  = $vik->initRadioElement('', '', $params['usercredit'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG114'),
			'content' => JText::translate('VAPMANAGECONFIG114_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG114') . $help, 'vapconfcanctr', $control);
		echo $vik->radioYesNo('usercredit', $yes, $no);	
		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopDetails","key":"cancellation","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Shop > Cancellation fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['cancellation']))
		{
			echo $forms['cancellation'];

			// unset details form to avoid displaying it twice
			unset($forms['cancellation']);
		}
		?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopDetails","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Shop tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}

JText::script('VAPMANAGECONFIGSHOPOPT1');
?>

<script>

	(function($) {
		'use strict';

		window['cancValueChanged'] = (is) => {
			if (is) {
				$('.vapconfcanctr').show();
			} else {
				$('.vapconfcanctr').hide();
			}
		}
		
		window['enableCartValueChanged'] = (is) => {
			if (is) {
				$('.vapcartchildtr').show();
			} else {
				$('.vapcartchildtr').hide();
			}

			$('#vapmaxcartsize').val(is ? 1 : -1);
		}

		const statusCodes = <?php echo json_encode($statusCodes); ?>;

		$(function() {
			$('select[name="defstatus"]').on('change', function() {
				const val = $(this).val();

				let code = statusCodes.filter((data) => {
					return data.code === val;
				});

				if (code.length && code[0].approved == 0) {
					$('.vap-defstatus-child').show();
				} else {
					$('.vap-defstatus-child').hide();
				}
			});

			$('#vap-shoplink-dropdown').select2({
				placeholder: Joomla.JText._('VAPMANAGECONFIGSHOPOPT1'),
				allowClear: true,
				width: 250,
			});

			$('#vap-shoplink-dropdown').on('change', function() {
				if ($(this).val() == -2) {
					$('#vap-shoplink-text').show();
				} else {
					$('#vap-shoplink-text').hide();
				}
			});

			$('#vap-maxcartsize-sel').on('change', function() {
				if ($(this).val() == 1) {
					$('#vapmaxcartsize').attr('min', -1).val(-1);
					$('#vapmaxcartsize').hide();
				} else {
					$('#vapmaxcartsize').attr('min', 1).val(1);
					$('#vapmaxcartsize').show();
				}
			});

			$('#vap-usedeposit-sel').on('change', function() {
				var val = parseInt($(this).val());

				if (val == 0) {
					$('.vap-deposit-child').hide();
				} else {
					$('.vap-deposit-child').show();
				}
			});
		});
	})(jQuery);

</script>
