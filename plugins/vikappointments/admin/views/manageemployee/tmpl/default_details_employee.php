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

$employee = $this->employee;

$vik = VAPApplication::getInstance();

?>
		
<!-- FIRST NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE2') . '*'); ?>
	<input class="required" type="text" name="firstname" value="<?php echo $employee->firstname; ?>" size="40" />
<?php echo $vik->closeControl(); ?> 

<!-- LAST NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE3') . '*'); ?>
	<input class="required" type="text" name="lastname" value="<?php echo $employee->lastname; ?>" size="40" />
<?php echo $vik->closeControl(); ?> 

<!-- NICKNAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE4') . '*'); ?>
	<input class="required" type="text" name="nickname" value="<?php echo $employee->nickname; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- ALIAS - Text -->

<?php echo $vik->openControl(JText::translate('JFIELD_ALIAS_LABEL'), 'field-alias'); ?>
	<input type="text" name="alias" value="<?php echo $employee->alias; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- IMAGE - Media -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE7'));
echo JHtml::fetch('vaphtml.mediamanager.field', 'image', $employee->image);
echo $vik->closeControl();
?>

<script>

	jQuery(function($) {

		$('input[name="firstname"],input[name="lastname"]').on('blur', () => {
			var nickname = $('input[name="nickname"]');

			if (nickname.val().length) {
				// nominative already filled in
				return true;
			}

			var fn = $('input[name="firstname"]').val();
			var ln = $('input[name="lastname"]').val();

			if (fn.length == 0 || ln.length == 0) {
				// one of these fields is missing
				return true;
			}

			nickname.val(fn + ' ' + ln);
		});

	});

</script>
