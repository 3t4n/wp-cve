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

$canRegister = isset($displayData['register']) ? $displayData['register'] : false;
$returnUrl   = isset($displayData['return'])   ? $displayData['return']   : '';
$remember    = isset($displayData['remember']) ? $displayData['remember'] : false;
$useCaptcha  = isset($displayData['captcha'])  ? $displayData['captcha']  : null;
$gdpr        = isset($displayData['gdpr'])     ? $displayData['gdpr']     : null;
$footerLinks = isset($displayData['footer'])   ? $displayData['footer']   : true;
$active      = isset($displayData['active'])   ? $displayData['active']   : 'login';
$formId      = isset($displayData['form'])     ? ltrim($displayData['form'], '#') : null;

$vik = VAPApplication::getInstance();

if (is_null($useCaptcha))
{
	// check if 'recaptcha' is configured
	$useCaptcha = $vik->isCaptcha();
}

$app   = JFactory::getApplication();
$input = $app->input;

if (($tab = $input->get('tab')))
{
	// overwrite pre-selected tab with the one set in request
	$active = $tab;
}

if ((!$canRegister && $active == 'registration') || !in_array($active, array('login', 'registration')))
{
	// restore active tab to "login" as the registration is disabled
	$active = 'login';
}

if (is_null($gdpr))
{
	$config = VAPFactory::getConfig();

	// gdpr setting not provided, get it from the global configuration
	$gdpr 	= $config->getBool('gdpr', false);
	$policy = $config->get('policylink');
}

if ($footerLinks)
{
	// load com_users site language to display footer messages
	JFactory::getLanguage()->load('com_users', JPATH_SITE, JFactory::getLanguage()->getTag(), true);
}

if ($canRegister)
{
	$dispatcher = VAPFactory::getEventDispatcher();

	$forms = array();

	foreach (array('top', 'name', 'username', 'email', 'password', 'captcha', 'policy', 'bottom') as $location)
	{
		/**
		 * Trigger event to let the plugins add custom HTML contents within the user registration form.
		 *
		 * @param 	string  $location  The HTML will be always placed after the specified location.
		 *
		 * @return 	string  The HTML to display.
		 *
		 * @since 	1.7
		 */
		$html = array_filter($dispatcher->trigger('onDisplayUserRegistrationForm', array($location)));

		// display all returned blocks, separated by a new line
		$forms[$location] = implode("\n", $html);
	}

	// evaluate the task that will be used to register the account
	if ($input->get('view') != 'emplogin')
	{
		$controller = 'userprofile';
	}
	else
	{
		$controller = 'emplogin';
	}

	/**
	 * In case of failed registration, the user details are stored
	 * within the user state. We can fetch them to auto-populate 
	 * the registration form.
	 *
	 * @since 1.7
	 */
	$data = $app->getUserState('vap.cms.user.register', array());
	// immediately unset the specified user data to avoid displaying
	// them again in case the user decides to leave the page
	$app->setUserState('vap.cms.user.register', null);
	?>

	<!-- REGISTRATION -->

	<script>

		var vapUserRegistrationValidator;

		jQuery(function($) {
			// in case of a specified form, register only the fields contained within the registration wrapper
			const formSelector = '<?php echo $formId ? '#' . $formId . ' .vapregform' : '#vapregform'; ?>';

			// create validator once the document is ready, because certain themes
			// might load the resources after the body
			vapUserRegistrationValidator = new VikFormValidator(formSelector, 'vapinvalid');

			// register callback to make sure both the password fields are equals
			vapUserRegistrationValidator.addCallback((form) => {
				const pwd1 = $('#register-password');
				const pwd2 = $('#register-confpassword');

				if (!pwd1.val() || (pwd1.val() !== pwd2.val())) {
					// the specified password are not matching
					form.setInvalid($(pwd1).add(pwd2));
					return false;
				}

				// the specified password are equals
				form.unsetInvalid($(pwd1).add(pwd2));
				return true;
			});

			<?php
			if ($gdpr)
			{
				// in case of GDPR enabled, validate the disclaimer checkbox
				?>
				vapUserRegistrationValidator.addCallback((form) => {
					const field = $('#gdpr-register');

					if (!field.is(':checked')) {
						// not checked
						form.setInvalid(field);
						return false;
					}

					// checked
					form.unsetInvalid(field);
					return true;
				});
				<?php
			}

			if ($useCaptcha)
			{
				// make sure the captcha has been validated
				?>
				vapUserRegistrationValidator.addCallback((form) => {
					// get recaptcha elements
					var captcha = $(form.form).find('.g-recaptcha').first();
					var iframe  = captcha.find('iframe').first();

					// get widget ID
					var widget_id = captcha.data('recaptcha-widget-id');

					// check if recaptcha instance exists
					// and whether the recaptcha was completed
					if (typeof grecaptcha !== 'undefined'
						&& widget_id !== undefined
						&& !grecaptcha.getResponse(widget_id)) {
						// captcha not completed
						iframe.addClass(form.clazz);
						return false;
					}

					// captcha completed
					iframe.removeClass(form.clazz);
					return true;
				});
				<?php
			}
			?>

			/**
			 * Overwrite getLabel method to properly access the label by using
			 * our custom layout.
			 *
			 * @param 	mixed  input  The input element.
			 *
			 * @param 	mixed  The label of the input.
			 */
			vapUserRegistrationValidator.getLabel = function(input) {
				// get all labels at the same level of the input (see checkbox)
				const siblings = $(input).siblings('label');

				if (siblings.length) {
					// label found, use it
					return siblings.first();
				}

				// otherwise fallback to label placed on a different block
				return $(input).closest('.vaploginfield').find('.vaploginsplabel label');
			}

			$(formSelector).find('button[name="registerbutton"]').on('click', function(event) {
				if (!vapUserRegistrationValidator.validate()) {
					event.preventDefault();
					event.stopPropagation();
					return false;
				}

				// find parent form
				const formElement = $(this).closest('form');

				<?php
				if ($formId)
				{
					?>
					// check if we have an option field within our form
					let optionField = $(formElement).find('input[name="option"]');

					if (!optionField.length) {
						// nope, create it and append it at the end of the form
						optionField = $('<input type="hidden" name="option" value="" />');
						$(formElement).append(optionField);
					}

					// register the correct option value
					optionField.val('com_vikappointments');

					// check if we have a task field within our form
					let taskField = $(formElement).find('input[name="task"]');

					if (!taskField.length) {
						// nope, create it and append it at the end of the form
						taskField = $('<input type="hidden" name="task" value="" />');
						$(formElement).append(taskField);
					}

					// register the correct task value
					taskField.val('<?php echo $controller; ?>.register');

					// check if we have a return field within our form
					let returnField = $(formElement).find('input[name="return"]');

					if (!returnField.length) {
						// nope, create it and append it at the end of the form
						returnField = $('<input type="hidden" name="return" value="" />');
						$(formElement).append(returnField);
					}

					// register the correct return value
					returnField.val('<?php echo base64_encode($returnUrl); ?>');
					<?php
				}
				?>

				return true;
			});
		});

		function vapLoginValueChanged() {
			if (jQuery('input[name=loginradio]:checked').val() == 1) {
				jQuery('.vapregisterblock').css('display', 'none');
				jQuery('.vaploginblock').fadeIn();
			} else {
				jQuery('.vaploginblock').css('display', 'none');
				jQuery('.vapregisterblock').fadeIn();
			}
		}

	</script>
	
	<div class="vaploginradiobox" id="vaploginradiobox">
		<span class="vaploginradiosp">
			<label for="logradio1"><?php echo JText::translate('VAPLOGINRADIOCHOOSE1'); ?></label>
			<input type="radio" id="logradio1" name="loginradio" value="1" onChange="vapLoginValueChanged();" <?php echo $active == 'login' ? 'checked="checked"' : ''; ?> />
		</span>

		<span class="vaploginradiosp">
			<label for="logradio2"><?php echo JText::translate('VAPLOGINRADIOCHOOSE2'); ?></label>
			<input type="radio" id="logradio2" name="loginradio" value="2" onChange="vapLoginValueChanged();" <?php echo $active != 'login' ? 'checked="checked"' : ''; ?> />
		</span>
	</div>
	
	<div class="vapregisterblock" style="<?php echo $active != 'login' ? '' : 'display: none;'; ?>">
		<?php
		if ($formId)
		{
			// the registration is already contained within a parent form
			?>
			<div class="vapregform">
			<?php
		}
		else
		{
			// wrap the registration fields within a form
			?>
			<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments'); ?>" method="post" name="vapregform" id="vapregform">
			<?php
		}
		?>

			<h3><?php echo JText::translate('VAPREGISTRATIONTITLE'); ?></h3>
			
			<div class="vaploginfieldsdiv">

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"top"} -->

				<?php
				// display custom HTML at the beginning of the form
				echo $forms['top'];
				?>

				<!-- FIRST NAME -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vapfname">
						<label for="register-fname" id="register-fname-label"><?php echo JText::translate('VAPREGNAME'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-fname" type="text" name="fname" value="<?php echo $this->escape(!empty($data['firstname']) ? $data['firstname'] : ''); ?>" size="30" class="vapinput required" aria-labelledby="register-fname-label" />
					</span>
				</div>

				<!-- LAST NAME -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vaplname">
						<label for="register-lname" id="register-lname-label"><?php echo JText::translate('VAPREGLNAME'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-lname" type="text" name="lname" value="<?php echo $this->escape(!empty($data['lastname']) ? $data['lastname'] : ''); ?>" size="30" class="vapinput required" aria-labelledby="register-lname-label" />
					</span>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"name"} -->

				<?php
				// display custom HTML after the first name and last name fields
				echo $forms['name'];
				?>

				<!-- USERNAME -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vapusername">
						<label for="register-username" id="register-username-label"><?php echo JText::translate('VAPREGUNAME'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-username" type="text" name="reg_username" value="<?php echo $this->escape(!empty($data['username']) ? $data['username'] : ''); ?>" size="30" class="vapinput required" aria-labelledby="register-username-label" />
					</span>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"username"} -->

				<?php
				// display custom HTML after the username field
				echo $forms['username'];
				?>

				<!-- E-MAIL -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vapemail">
						<label for="register-email" id="register-email-label"><?php echo JText::translate('VAPREGEMAIL'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-email" type="email" name="email" value="<?php echo $this->escape(!empty($data['email']) ? $data['email'] : ''); ?>" size="30" class="vapinput required" aria-labelledby="register-email-label" />
					</span>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"email"} -->

				<?php
				// display custom HTML after the username field
				echo $forms['email'];
				?>

				<!-- PASSWORD -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vappassword">
						<label for="register-password" id="register-password-label"><?php echo JText::translate('VAPREGPWD'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-password" type="password" name="reg_password" value="" size="30" class="vapinput required" aria-labelledby="register-password-label" />
					</span>
				</div>

				<!-- CONFIRM PASSWORD -->

				<div class="vaploginfield">
					<span class="vaploginsplabel" id="vapconfpassword">
						<label for="register-confpassword" id="register-confpassword-label"><?php echo JText::translate('VAPREGCONFIRMPWD'); ?><sup>*</sup></label>
					</span>
					<span class="vaploginspinput">
						<input id="register-confpassword" type="password" name="confpassword" value="" size="30" class="vapinput required" aria-labelledby="register-confpassword-label" />
					</span>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"password"} -->

				<?php
				// display custom HTML after the password fields
				echo $forms['password'];
				?>

				<?php
				if ($gdpr)
				{
					// load fancybox to support login GDPR popup
					JHtml::fetch('vaphtml.assets.fancybox');
					?>
					<div class="vaploginfield field-gdpr">
						<!-- <span class="vaploginsplabel" class="">&nbsp;</span> -->
						<span class="vaploginspinput">
							<input type="checkbox" class="required" id="gdpr-register" value="1" />
							<label for="gdpr-register" style="display: inline;">
								<?php
								if ($policy)
								{
									// label with link to read the privacy policy
									echo JText::sprintf(
										'GDPR_POLICY_AUTH_LINK',
										'javascript: void(0);',
										'vapOpenPopup(\'' . $policy . '\');'
									);
								}
								else
								{
									// label without link
									echo JText::translate('GDPR_POLICY_AUTH_NO_LINK');
								}
								?>
							</label>
						</span>
					</div>
					<?php
				}
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"policy"} -->

				<?php
				// display custom HTML after the privacy field
				echo $forms['policy'];
				?>
				
				<?php
				if ($useCaptcha)
				{
					?>
					<div class="vaploginfield field-captcha">
						<?php echo $vik->reCaptcha(); ?>
					</div>
					<?php
				}
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"captcha"} -->

				<?php
				// display custom HTML after the reCAPTCHA field
				echo $forms['captcha'];
				?>

				<div class="vaploginfield field-button">
					<span class="vaploginsplabel" class="">&nbsp;</span>
					<span class="vaploginspinput">
						<button type="submit" class="vap-btn blue" name="registerbutton">
							<?php echo JText::translate('VAPREGSIGNUPBTN'); ?>
						</button>
					</span>
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayUserRegistrationForm","type":"sitepage","key":"bottom"} -->

				<?php
				// display custom HTML at the end of the form
				echo $forms['bottom'];
				?>

			</div>
			
			<?php echo JHtml::fetch('form.token'); ?>
		
		<?php
		if ($formId)
		{
			?></div><?php
		}
		else
		{
			?>
				<input type="hidden" name="option" value="com_vikappointments" />
				<input type="hidden" name="task" value="<?php echo $controller; ?>.register" />
				<input type="hidden" name="return" value="<?php echo base64_encode($returnUrl); ?>" />
			</form>
			<?php
		}
		?>

	</div>

	<?php
}
?>

<!-- LOGIN -->

<div class="vaploginblock" style="<?php echo $active == 'login' ? '' : 'display: none;'; ?>">
	<?php
	/**
	 * The login form is displayed from the layout below:
	 * /components/com_vikappointments/layouts/blocks/login/[PLATFORM_NAME].php
	 * which depends on the current platform ("joomla" or "wordpress").
	 * 
	 * If you need to change something from this layout, just create
	 * an override of this layout by following the instructions below:
	 * - open the back-end of your Joomla
	 * - visit the Extensions > Templates > Templates page
	 * - edit the active template
	 * - access the "Create Overrides" tab
	 * - select Layouts > com_vikappointments > blocks
	 * - start editing the login/[platform].php file on your template to create your own layout
	 *
	 * @since 1.6.3
	 */
	echo $this->sublayout(VersionListener::getPlatform(), $displayData);
	?>
</div>
