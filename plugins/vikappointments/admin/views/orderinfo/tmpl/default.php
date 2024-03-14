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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.fontawesome');

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewOrderinfo". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 * @since 1.7 Changed hook from onDisplayViewPurchaserinfo.
 */
$this->addons = $this->onDisplayView();

// get first appointment
$app = $this->order->appointments[0];

?>

<style>

	/* modal content pane */

	.contentpane.component {
		padding: 0 10px;
		height: 100%;
		/* do not scroll */
		overflow: hidden;
	}

</style>

<!-- container -->

<div class="order-container appointment">

	<!-- left box : order details, customer info, order items -->

	<div class="order-left-box">

		<!-- top box : order details, customer info -->

		<div class="order-left-top-box">

			<!-- left box : order details -->

			<div class="order-global-details">
				<?php echo $this->loadTemplate('details'); ?>
			</div>

			<!-- right box : customer indo -->

			<div class="order-customer-details">
				<?php
				echo $this->loadTemplate('customer');

				// display custom fields toggle only in case
				// the customer purchased at least an option
				if ($app->options && $this->order->hasFields)
				{
					?>
					<!-- Custom Fields Toggle Button -->
						
					<button type="button" class="btn" id="custom-fields-btn"><?php echo JText::translate('VAPSHOWCUSTFIELDS'); ?></button>
					<?php
				}
				?>
			</div>

		</div>

		<!-- bottom box: order options, custom fields -->

		<div class="order-left-bottom-box">

			<?php
			if ($app->options)
			{
				?>
				<!-- left box : order options -->

				<div class="order-options-list">
					<?php echo $this->loadTemplate('options'); ?>
				</div>
				<?php
			}
			
			if ($this->order->hasFields)
			{
				?>
				<!-- right box : custom fields -->

				<div class="order-custom-fields" style="<?php echo ($app->options ? 'display: none;' : ''); ?>">
					<?php echo $this->loadTemplate('fields'); ?>
				</div>
				<?php
			}
			?>

		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewOrderinfo","key":"main.bottom","type":"field"} -->

		<?php
		// plugins can use the "main.bottom" key to introduce custom
		// HTML at the bottom of the page
		if (isset($this->addons['main.bottom']))
		{
			echo $this->addons['main.bottom'];

			// unset details form to avoid displaying it twice
			unset($this->addons['main.bottom']);
		}
		?>

	</div>

	<!-- right box : payment details, invoices -->

	<div class="order-right-box">

		<!-- top box : payment details -->

		<div class="order-payment-details">
			<?php echo $this->loadTemplate('payment'); ?>
		</div>

	</div>

</div>

<?php
JText::script('VAPSHOWCUSTFIELDS');
JText::script('VAPHIDECUSTFIELDS');
?>

<script>

	jQuery(function($) {
		$('#custom-fields-btn').on('click', function() {
			var fields = $('.order-custom-fields');

			if (fields.is(':visible')) {
				fields.hide();

				$(this).text(Joomla.JText._('VAPSHOWCUSTFIELDS'));
			} else {
				fields.show();

				$(this).text(Joomla.JText._('VAPHIDECUSTFIELDS'));
			}
		});
	});

	/**
	 * Check whether the parent window provides the function
	 * to update the footer buttons (edit, delete) of the modal.
	 *
	 * @since 1.6.6
	 */
	if (window.parent.vapUpdateModalButtons) {
		window.parent.vapUpdateModalButtons(<?php echo $this->order->id; ?>);
	}

</script>
