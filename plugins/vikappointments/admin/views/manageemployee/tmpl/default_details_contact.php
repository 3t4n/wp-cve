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

JHtml::fetch('vaphtml.assets.intltel', '[name="phone"]');

$employee = $this->employee;

$vik = VAPApplication::getInstance();

$user_mail_lookup = array();

?>

<!-- USER ID - Dropdown -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
);

foreach ($this->users as $u)
{
	$user_mail_lookup[$u->id] = $u->email;

	$options[] = JHtml::fetch('select.option', $u->id, $u->name . ($u->name != $u->username ? ' | ' . $u->username : ''));
}

$help = $vik->createPopover(array(
	'title'		=> JText::translate('VAPMANAGEEMPLOYEE19'),
	'content' 	=> JText::translate('VAPMANAGEEMPLOYEE19_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE19') . $help); ?>
	<select name="jid" id="vap-users-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $employee->jid); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- EMAIL - Text -->

<?php
$help = $vik->createPopover(array(
	'title'		=> JText::translate('VAPMANAGEEMPLOYEE8'),
	'content' 	=> JText::translate('VAPMANAGEEMPLOYEE8_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE8') . $help); ?>
	<input type="email" name="email" value="<?php echo $employee->email; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- PHONE - Text -->

<?php
$help = $vik->createPopover(array(
	'title'		=> JText::translate('VAPMANAGEEMPLOYEE10'),
	'content' 	=> JText::translate('VAPMANAGEEMPLOYEE10_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE10') . $help); ?>
	<input type="tel" name="phone" value="<?php echo $employee->phone; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- SHOW PHONE - Radio Button -->

<?php
$elem_yes = $vik->initRadioElement('', '', $employee->showphone == 1);
$elem_no  = $vik->initRadioElement('', '', $employee->showphone == 0);

$control = array();
$control['style'] = $employee->phone ? '' : 'display:none;';

echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE16'), 'phone-child', $control);
echo $vik->radioYesNo('showphone', $elem_yes, $elem_no, false);
echo $vik->closeControl();
?>

<script>

	jQuery(function($) {

		var userMailLookup = <?php echo json_encode($user_mail_lookup); ?>;

		$('#vap-users-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
		});

		$('#vap-users-sel').on('change', function() {
			var mail = $('input[name="email"]');

			if (mail.val().length) {
				// e-mail already filled in
				return true;
			}

			var id = $(this).val();

			if (!userMailLookup.hasOwnProperty(id)) {
				// selected user not found
				return true;
			}

			mail.val(userMailLookup[id]);
		});

		$('input[name="phone"]').on('change', function() {
			if ($(this).val().length) {
				$('.phone-child').show();
			} else {
				$('.phone-child').hide();
			}
		});

	});

</script>
