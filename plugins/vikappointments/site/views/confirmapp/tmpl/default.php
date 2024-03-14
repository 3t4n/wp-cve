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

JHtml::fetch('vaphtml.assets.fancybox');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

/**
 * Get login requirements:
 * [0] - Never
 * [1] - Optional
 * [2] - Required on confirmation page
 * [3] - Required on calendars page
 */
$login_req = $config->getUint('loginreq');

// If the login is mandatory/optional and the customer is not logged in, we need to show
// a form to allow the customers to login or at least to create a new account.
// Login req = 0 means [NEVER]
if ($login_req > 0 && !VikAppointments::isUserLogged())
{
	// display login/registration form
	echo $this->loadTemplate('login');
	
	// We should stop the flow only if the login is mandatory.
	// Login req = 1 means [OPTIONAL]
	if ($login_req > 1)
	{
		return;
	}
}

// check whether the total cost (without discount) is higher than 0
// because we might want to keep displaying the coupon form even if
// the appointment has been entirely discounted with a different
// coupon code
if ($this->anyCoupon == 1 && $this->cart->getTotalCost() > 0)
{
	// load coupon form, only if there is at least a valid coupon code
	echo $this->loadTemplate('coupon');
}
?>

<div class="vapseparatordiv"></div>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&task=confirmapp.saveorder'); ?>" id="vappayform" name="vappayform" method="POST">
	
	<!-- CART -->

	<?php
	// load summary cart
	echo $this->loadTemplate('cart');
	?>

	<!-- CONTINUE SHOPPING -->
	
	<?php
	// display a continue button only if the shop feature is enabled
	if($config->getBool('enablecart'))
	{
		/**
		 * Returns the link type that will be used by clicking the "Continue Shopping" button:
		 * - [0]  link disabled
		 * - [-1] serviceslist (without group filtering) 
		 * - [-2] custom link
		 * - [1+] serviceslist (with group filter equals to the specified ID)
		 */
		$shop_group = $config->getInt('shoplink');

		if ($shop_group != 0)
		{ 
			$url = '';

			if ($shop_group != -2)
			{
				$url = JRoute::rewrite('index.php?option=com_vikappointments&view=serviceslist' . ($shop_group != -1 ? '&service_group=' . $shop_group : '') . '&Itemid=' . $this->itemid);
			}
			else
			{
				$url = $config->get('shoplinkcustom');
			}
			?>

			<div class="vapcontinueshopdiv">
				<a href="<?php echo $url; ?>" class="vap-btn"><?php echo JText::translate('VAPCONTINUESHOPPINGLINK'); ?></a>
			</div>
			<?php 
		}
	}
	?>

	<!-- CUSTOM FIELDS -->

	<?php
	if (count($this->customFields))
	{
		// display custom fields form to collect
		// the billing details of the customers
		echo $this->loadTemplate('fields');

		// display form to collect attendees details, if needed
		echo $this->loadTemplate('attendees');
	}
	?>

	<!-- PAYMENTS -->

	<?php
	if (count($this->payments))
	{
		?>
		<div class="vapcompleteorderdiv" id="vappaymentsdiv" style="display: none;">

			<?php
			// display the list of the payments that the customers can choose
			echo $this->loadTemplate('payments');
			?>

		</div>
		<?php
	}
	?>

	<!-- CONTINUE/CONFIRM BUTTON -->
	
	<button type="button" class="vap-btn big blue" id="vapcontinuebutton" onClick="vapContinueButton(this);">
		<?php echo JText::translate($this->skipPayments ? 'VAPCONFIRMRESBUTTON' : 'VAPCONTINUEBUTTON'); ?>
	</button>

	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="confirmapp.saveorder" />

	<?php
	// use token to prevent brute force attacks
	echo JHtml::fetch('form.token');
	?>
</form>

<?php
JText::script('VAPCONFIRMRESBUTTON');
JText::script('VAPCONFAPPREQUIREDERROR');
JText::script('VAPCONFAPPZIPERROR');
?>

<script>

	var vapCustomFieldsValidator;

	var CONFIRMATION_STEP = <?php echo $this->skipPayments ? 1 : 0; ?>;

	function vapContinueButton(button) {
		// validate custom fields
		if (!vapCustomFieldsValidator.validate()) {
			// display error message
			if (ZIP_VALIDATED) {
				jQuery('#vapordererrordiv').html(Joomla.JText._('VAPCONFAPPREQUIREDERROR')).show();
			} else {
				// invalid or empty ZIP code
				jQuery('#vapordererrordiv').html(Joomla.JText._('VAPCONFAPPZIPERROR')).show();
			}

			// get first invalid input
			var input = jQuery('.vapcustomfields .vapinvalid').filter('input,textarea,select').first();

			if (input.length == 0) {
				// the label is displayed before the input, get it
				var input = jQuery('.vapcustomfields .vapinvalid').first();
			}

			// animate to element found
			if (input.length) {
				jQuery('html,body').stop(true, true).animate({
					scrollTop: (jQuery(input).offset().top - 100),
				}, {
					duration:'medium'
				}).promise().done(function() {
					// try to focus the input
					jQuery(input).focus();
				});
			}

			// do not go ahead in case of error
			return;
		}

		// hide error message
		jQuery('#vapordererrordiv').html('').hide();

		if (CONFIRMATION_STEP == 0) {
			// display payment gateways
			jQuery('#vappaymentsdiv').show();

			// change button text
			jQuery(button).text(Joomla.JText._('VAPCONFIRMRESBUTTON'));

			// increase step and do not go ahead
			CONFIRMATION_STEP++;
			return;
		}

		// do not validate payment gateways selection
		// because the first payment available, if any,
		// is now pre-selected by default

		<?php
		/**
		 * Disable book now button before submitting the
		 * form in order to prevent several clicks.
		 *
		 * @since 1.7
		 */
		?>
		jQuery(button).prop('disabled', true);

		jQuery('#vappayform').submit();
	}

	(function($) {
		'use strict';

		$(function() {
			// create validator once the document is ready, because certain themes
			// might load the resources after the body
			vapCustomFieldsValidator = new VikFormValidator('#vappayform', 'vapinvalid');
		});
	})(jQuery);

</script>
