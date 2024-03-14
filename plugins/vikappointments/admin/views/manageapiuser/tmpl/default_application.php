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

$user = $this->user;

$vik = VAPApplication::getInstance();

?>
				
<!-- APPLICATION - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEAPIUSER2')); ?>
	<input type="text" name="application" class="input-xxlarge input-large-text" value="<?php echo $this->escape($user->application); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- USERNAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEAPIUSER3') . '*'); ?>
	<input type="text" name="username" class="required" value="<?php echo $this->escape($user->username); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- USERNAME REGEX - Label -->

<?php
$control = array();
$control['idparent'] = 'user-regex';
$control['style']    = 'display: none;';

echo $vik->openControl('', 'vap-user-regex', $control); ?>
	<span style="color: #900; font-size: 95%;"><?php echo JText::translate('VAPAPIUSERUSERNAMEREGEX'); ?></span>
<?php echo $vik->closeControl(); ?>

<!-- PASSWORD - Password -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECUSTOMER13') . '*'); ?>
	<div class="input-append">
		<input type="password" name="password" class="required" value="<?php echo $this->escape($user->password); ?>" size="40" />

		<button type="button" class="btn" id="pwd-reveal-btn"><i class="fas fa-eye"></i></button>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- PASSWORD REGEX - Label -->

<?php
$control = array();
$control['idparent'] = 'pwd-regex';
$control['style']    = 'display: none;';

echo $vik->openControl('', 'vap-pwd-regex', $control); ?>
	<span style="color: #900; font-size: 95%;"><?php echo JText::translate('VAPAPIUSERPASSWORDREGEX'); ?></span>
<?php echo $vik->closeControl(); ?>

<!-- GENERATE PASSWORD - Button -->

<?php echo $vik->openControl(''); ?>
	<button type="button" class="btn" id="pwd-gen-btn"><?php echo JText::translate('VAPMANAGECUSTOMER17'); ?></button>
<?php echo $vik->closeControl(); ?>

<!-- ACTIVE - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $user->active == 1);
$no  = $vik->initRadioElement('', '', $user->active == 0);

echo $vik->openControl(JText::translate('VAPACTIVE'));
echo $vik->radioYesNo('active', $yes, $no, false);
echo $vik->closeControl();
?>

<?php
JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	jQuery(function($) {
		$('#pwd-reveal-btn').on('click', function() {
			var icon = $(this).find('i');

			if (icon.hasClass('fa-eye')) {
				$('input[name="password"]').attr('type', 'text');
				icon.removeClass('fa-eye').addClass('fa-eye-slash');
			} else {
				$('input[name="password"]').attr('type', 'password');
				icon.removeClass('fa-eye-slash').addClass('fa-eye');
			}
		});

		String.prototype.shuffle = function() {
			var a = this.split('');

			for (var i = a.length - 1, j = 0, tmp = 0; i >= 0; i--) {
				j = Math.floor(Math.random() * (i + 1));
				
				tmp = a[i];
				a[i] = a[j];
				a[j] = tmp;
			}

			return a.join('');
		}

		const buildPassword = (min_length, max_length, min_digits, min_uppercase, chars_str) => {
			var pwd = '';

			var len = Math.min(24, ( min_length + max_length ) / 2); 

			var i;

			for (i = 0; i < min_digits; i++) {
				pwd += '' + Math.floor(Math.random() * 10);
			}

			for (i = 0; i < min_uppercase; i++) {
				pwd += String.fromCharCode(65 + Math.floor(Math.random() * 26));
			}

			for (i = pwd.length; i < len; i++) {
				pwd += chars_str.charAt(Math.floor(Math.random() * chars_str.length));
			}

			return pwd.shuffle();
		};

		$('#pwd-gen-btn').on('click', () => {
			var password = buildPassword(8, 128, 1, 1, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

			$('input[name="password"]').val(password);

			if ($('input[name="password"]').attr('type') == 'password') {
				$('#pwd-reveal-btn').trigger('click');
			}

			$('#pwd-regex').hide();
			validator.validate();
		});

		// validate username
		validator.addCallback(() => {
			var userInput = $('input[name="username"]');
			var user      = userInput.val();

			if (typeof user !== 'string') {
				user = '';
			}

			if (user.match(/^[0-9A-Za-z._]{3,128}$/)) {
				validator.unsetInvalid(userInput);
				$('#user-regex').hide();
				return true;
			}

			validator.setInvalid(userInput);
			$('#user-regex').show();
			return false;
		});

		// validate password
		validator.addCallback(() => {
			var pwdInput = $('input[name="password"]');
			var pwd      = pwdInput.val();

			if (typeof pwd !== 'string') {
				pwd = '';
			}

			if (pwd.match(/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!?@#$%_.\-{\[()\]}]{8,128}$/)) {
				validator.unsetInvalid(pwdInput);
				$('#pwd-regex').hide();
				return true;
			}

			validator.setInvalid(pwdInput);
			$('#pwd-regex').show();
			return false;
		});
	});

</script>
