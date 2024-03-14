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

$title       = isset($displayData['title'])       ? $displayData['title']       : '';
$id_service  = isset($displayData['id_service'])  ? $displayData['id_service']  : 0;
$id_employee = isset($displayData['id_employee']) ? $displayData['id_employee']	: 0;
$returnUri   = isset($displayData['return'])      ? $displayData['return']      : '';
$gdpr        = isset($displayData['gdpr'])        ? $displayData['gdpr']        : null;
$itemid      = isset($displayData['itemid'])      ? $displayData['itemid']      : null;

if ($id_service)
{
	// reviews for a service
	$col_name 	= 'id_service';
	$col_value 	= $id_service;
	$contact_task = 'servicesearch.quickcontact';
}
else
{
	// reviews for an employee
	$col_name 	= 'id_employee';
	$col_value 	= $id_employee;
	$contact_task = 'employeesearch.quickcontact';
}

if (is_null($gdpr))
{
	// gdpr setting not provided, get it from the global configuration
	$gdpr = VAPFactory::getConfig()->getBool('gdpr', false);
}

if (is_null($itemid))
{
	// item id not provided, get the current one (if set)
	$itemid = JFactory::getApplication()->input->getInt('Itemid');
}

/**
 * Get current user to auto-populate the contact fields.
 *
 * @since 1.7
 */
$user = JFactory::getUser();

$vik = VAPApplication::getInstance();

?>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&task=' . $contact_task . ($itemid ? '&Itemid=' . $itemid : '')); ?>" method="post" name="quickcontactform">
	
	<div class="vapqcdiv <?php echo $vik->getThemeClass('background'); ?>" style="display: none;">
		
		<h2 class="vapqcnominative"><?php echo $title; ?></h2>
		
		<div class="vapqcsendname">
			<label for="qc-send-name"><?php echo JText::translate('VAPEMPSENDERNAMELABEL'); ?></label>
			<input id="qc-send-name" type="text" name="sendername" value="<?php echo $this->escape($user->name); ?>" class="required" size="32" />
		</div>
		
		<div class="vapqcsendmail">
			<span class="vapqcmailsp">
				<label for="qc-send-mail"><?php echo JText::translate('VAPEMPSENDERMAILLABEL'); ?></label>
				<input id="qc-send-mail" type="email" name="sendermail" value="<?php echo $this->escape($user->email); ?>" class="required" size="32" />
			</span>
		</div>
		
		<div class="vapqcmailcont">
			<label for="qc-send-text" style="vertical-align: top;"><?php echo JText::translate('VAPEMPMAILCONTENTLABEL'); ?></label>
			<textarea name="mail_content" id="qc-send-text" class="required"></textarea>
		</div>

		<?php
		// check if global captcha is configured
		if ($use_captcha = $vik->isGlobalCaptcha())
		{
			// display reCaptcha plugin
			echo $vik->reCaptcha();
		}
		?>
		
		<span class="vapqcbuttonsp">
			<button type="submit" class="vap-btn blue" onClick="return vapValidateBeforeSendMail();">
				<?php echo JText::translate('VAPEMPSENDMAILOK'); ?>
			</button>

			<button type="button" class="vap-btn" onClick="vapCancelMail();">
				<?php echo JText::translate('VAPEMPSENDMAILCANCEL'); ?>
			</button> 
		</span>

		<?php
		/**
		 * Display a footer message for GDPR that
		 * inform the users that the specified data
		 * are not stored within the database of the website.
		 *
		 * @since 	1.6
		 */

		if ($gdpr)
		{
			?>
			<p class="gdpr-footer-disclaimer">
				<i class="fas fa-info-circle"><span><?php echo JText::translate('GDPR_DISCLAIMER'); ?></span></i>
			</p>
			<?php
		}
		?>
		
	</div>
	
	<?php echo JHtml::fetch('form.token'); ?>
	<input type="hidden" name="<?php echo $col_name; ?>" value="<?php echo $col_value; ?>" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="<?php echo $contact_task; ?>" /> 
	<input type="hidden" name="return" value="<?php echo base64_encode($returnUri); ?>" />
</form>

<script>

	var qcValidator;
	var cancelElement = null;

	jQuery(function($) {
		// instantiate validator on ready
		qcValidator = new VikFormValidator('form[name="quickcontactform"]', 'vaprequiredfield');

		<?php
		/**
		 * Add callback to validate whether the ReCAPTCHA quiz
		 * was completed or not.
		 *
		 * @since 1.7
		 */
		if ($use_captcha)
		{
			?>
			qcValidator.addCallback(() => {
				// get recaptcha elements
				let captcha = $(qcValidator.form).find('.g-recaptcha').first();
				let iframe  = captcha.find('iframe').first();

				// get widget ID
				let widget_id = captcha.data('recaptcha-widget-id');

				// check if recaptcha instance exists
				// and whether the recaptcha was completed
				if (typeof grecaptcha !== 'undefined'
					&& widget_id !== undefined
					&& !grecaptcha.getResponse(widget_id)) {
					// captcha not completed
					iframe.addClass(qcValidator.clazz);
					return false;
				}

				// captcha completed
				iframe.removeClass(qcValidator.clazz);
				return true;
			});
			<?php
		}
		?>
	});

	function vapValidateBeforeSendMail() {
		return qcValidator.validate();
	}
	
	function vapCancelMail() {
		jQuery('.vapqcdiv').fadeOut();

		if (cancelElement) {
			jQuery('html,body').animate({
				scrollTop: (jQuery(cancelElement).offset().top - 20),
			}, {
				duration: 'normal',
			});
		}
	}

	// used when clicking "QUICK CONTACT" from the list
	function vapGoToMail(elem, id, name) {
		cancelElement = elem;

		if (id) { 
			jQuery('input[name="<?php echo $col_name; ?>"]').val(id);
		}

		if (name) {
			jQuery('.vapqcnominative').text(name);
		}

		jQuery('.vapqcdiv').fadeIn();
		jQuery('html,body').animate({
			scrollTop: (jQuery('.vapqcdiv').offset().top - 20),
		}, {
			duration: 'normal',
		});
	}

</script>
