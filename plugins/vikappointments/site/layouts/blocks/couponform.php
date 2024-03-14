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

$controller = isset($displayData['controller']) ? $displayData['controller'] : 'confirmapp';
$itemid     = isset($displayData['itemid'])     ? $displayData['itemid']     : null;
$form       = isset($displayData['form'])       ? $displayData['form']       : null;

// build action URL
$url = JRoute::rewrite('index.php?option=com_vikappointments&task=' . $controller . '.redeemcoupon' . ($itemid ? '&Itemid=' . $itemid : ''));

// open form only if not specified
if (!$form)
{
	?>
	<form action="<?php echo $url; ?>" name="couponform" method="post">
	<?php
}
?>

<div class="vapcouponcodediv">

	<h3 class="vapheading3"><?php echo JText::translate('VAPENTERYOURCOUPON'); ?></h3>

	<input class="vapcouponcodetext" type="text" name="couponkey" />

	<button type="submit" class="vap-btn blue" onclick="return onBeforeSubmitCouponCode();"><?php echo JText::translate('VAPAPPLYCOUPON'); ?></button>

</div>
	
<?php
// close form only if not specified
if (!$form)
{
	// use token to prevent brute force attacks
	echo JHtml::fetch('form.token');
	?>
		<input type="hidden" name="option" value="com_vikappointments" />
		<input type="hidden" name="task" value="<?php echo $controller; ?>.redeemcoupon" />
	</form>
	<?php
}
?>

<script>

	function onBeforeSubmitCouponCode() {
		<?php if ($form) { ?>
			// manually update task
			document.<?php echo $form; ?>.task.value = '<?php echo $controller; ?>.redeemcoupon';
		<?php } ?>

		return true;
	}

</script>
