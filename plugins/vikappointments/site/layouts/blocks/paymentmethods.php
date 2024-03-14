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

$payments   = isset($displayData['payments'])   ? $displayData['payments']   : array();
$showdesc   = isset($displayData['showdesc'])   ? $displayData['showdesc']   : true;
$id_payment = isset($displayData['id_payment']) ? $displayData['id_payment'] : null;

$payCount = count($payments);

?>

<h3 class="vap-confirmapp-h3"><?php echo JText::translate('VAPMETHODOFPAYMENT'); ?></h3>

<div class="vap-payments-list">
	<?php
	foreach ($payments as $i => $p)
	{	
		$cost_str = '';

		if ($p['charge'] != 0)
		{
			$cost_str = (float) $p['charge'];

			if ($cost_str > 0)
			{
				$cost_str = '+' . $cost_str;
			}

			$cost_str = VAPFactory::getCurrency()->format($cost_str);
		}

		if ($id_payment)
		{
			// select the specified payment gateway only
			$selected = $p['id'] == $id_payment;
		}
		else
		{
			// auto-select the first one available
			$selected = $i == 0;
		}
		?>

		<div class="vap-payment-wrapper vap-payment-block">

			<div class="vap-payment-title">

				<?php
				if ($payCount > 1)
				{
					?>
					<input
						type="radio"
						name="id_payment"
						value="<?php echo $p['id']; ?>"
						id="vappayradio<?php echo $p['id']; ?>"
						<?php echo $selected ? 'checked="checked"' : '' ?>
					/>
					<?php
				}
				else
				{
					?>
					<input type="hidden" name="id_payment" value="<?php echo $p['id']; ?>" />
					<?php
				}
				?>

				<label for="vappayradio<?php echo $p['id']; ?>" class="vap-payment-title-label">

					<?php 
					if ($p['icontype'] == 1)
					{
						?>
						<i class="<?php echo $p['icon']; ?>"></i>&nbsp;
						<?php
					}
					else if ($p['icontype'] == 2)
					{
						?>
						<img src="<?php echo JUri::root() . $p['icon']; ?>" alt="<?php echo $this->escape($p['name']); ?>" />&nbsp;
						<?php
					}
					?>

					<span><?php echo $p['name'] . (strlen($cost_str) ? ' (' . $cost_str . ')' : ''); ?></span>

				</label>

			</div>

			<?php
			if (strlen($p['prenote']) && $showdesc)
			{
				?>
				<div class="vap-payment-description" id="vap-payment-description<?php echo $p['id']; ?>" style="<?php echo (!$selected ? 'display: none;' : ''); ?>">
					<?php
					/**
					 * Render HTML description to interpret attached plugins.
					 * 
					 * @since 1.6.3
					 */
					echo VikAppointments::renderHtmlDescription($p['prenote'], 'paymentconfirm');
					?>
				</div>
				<?php
			}
			?>

		</div>
		<?php
	}
	?>
</div>

<script>

	jQuery(function($) {
		$('.vap-payment-wrapper input[name="id_payment"]').on('change', function() {
			$('.vap-payment-title-label').removeClass('vaprequired');

			// get input parent
			var block = $(this).closest('.vap-payment-block');
			// get description block
			var desc = $(block).find('.vap-payment-description');
			// check if a description was visible
			var was = $('.vap-payment-description:visible').length > 0;

			if (desc.length == 0) {
				// hide previous description with animation
				// only if the selected payment doesn't
				// have a description to display
				$('.vap-payment-description').slideUp();
			} else {
				// otherwise hide as quick as possible
				$('.vap-payment-description').hide();
			}

			if (was) {
				// in case a description was already visible,
				// show new description without animation
				desc.show();
			} else {
				// animate in case there was no active payment
				desc.slideDown();
			}
		});
	});

</script>
