<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.wpdash
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  JRegistry         $config      The configuration registry
 * @var  JDashboardWidget  $widget      The widget instance.
 * @var  JUser             $user        The current user instance.
 * @var  array             $monthtotal  An array holding the total earning of the month (total, tax and net).
 * @var  mixed             $chart       The analytics widget used to generate a revenue chart.
 * @var  integer           $pending     The total count of pending appointments.
 * @var  integer           $needpay     The total count of appointments that require a payment.
 */
extract($displayData);

JHtml::fetch('vaphtml.assets.currency');

$currency = VAPFactory::getCurrency();

?>

<style>
	#vik_appointments_overview .inside {
		padding: 0;
		margin: 0;
	}

	.vap-overview-widget .overview-row {
		padding: 9px 12px;
		border-top: 1px solid #ececec;
	}
	.vap-overview-widget .overview-row:first-child {
		border-top: 0;
	}
	.vap-overview-widget .overview-row.overview-row-container {
		padding: 0;
		display: flex;
		justify-content: space-between;
	}
	.vap-overview-widget .overview-row.overview-row-container .row-child {
		flex: 1;
		border-left: 1px solid #ececec;
		padding: 9px 12px;
	}
	.vap-overview-widget .overview-row.overview-row-container .row-child:first-child {
		border-left: 0;
	}

	.vap-overview-widget .month-total-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.vap-overview-widget .month-total-row .total-earning {
		flex: 1;
	}
	.vap-overview-widget .month-total-row .total-earning:before {
		content: "\f185";
	}
	.vap-overview-widget .overview-row .row-main:before {
		font-family: dashicons;
		font-size: 2em;
		line-height: 1.2em;
		color: #464646;
		float: left;
		margin-right: 12px;
	}
	.vap-overview-widget .overview-row .row-main > * {
		display: block;
		width: 100%;
	}
	.vap-overview-widget .overview-row .row-main small {
		color: #999;
	}
	.vap-overview-widget .month-total-row .rog .dashicons {
		font-size: 16px;
	}
	.vap-overview-widget .month-total-row .rog.up {
		color: #090;
	}
	.vap-overview-widget .month-total-row .rog.up .dashicons {
		transform: rotate(45deg);
	}
	.vap-overview-widget .month-total-row .rog.down {
		color: #900;
	}
	.vap-overview-widget .month-total-row .rog.down .dashicons {
		transform: rotate(135deg);
	}
	.vap-overview-widget .month-total-row .rog.nodata {
		color: #999;
	}
	.vap-overview-widget .appointments-stat .pending-count:before {
		content: "\f14f";
		color: #FF7000;
	}
	.vap-overview-widget .appointments-stat .needpay-count:before {
		content: "\f533";
		color: #339CCC;
	}
</style>

<div class="vap-overview-widget">

	<?php
	if ($user->authorise('core.access.analytics.finance', 'com_vikappointments'))
	{
		?>
		<div class="month-total-row overview-row">
		
			<div class="row-main total-earning">
				<strong><?php echo $currency->format($monthtotal['total']); ?></strong>
				<small><?php _e('sales this month', 'vikappointments'); ?></small>
			</div>

			<?php
			if (!empty($rog['rog']))
			{
				?>
				<div class="rog <?php echo $rog['rog'] > 0 ? 'up' : 'down'; ?>">
					<span class="dashicons dashicons-arrow-up-alt"></span>

					<?php
					// strip decimals in case the rog is higher than 10 or lower than -10
					if (abs($rog['rogPercent']) > 10)
					{
						$rog['rogPercent'] = round($rog['rogPercent']);
					}

					echo ($rog['rogPercent'] > 0 ? '+' : '') . $rog['rogPercent'] . '%';
					?>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="rog nodata">
					<span class="dashicons dashicons-minus"></span>
				</div>
				<?php
			}
			?>

		</div>
		<?php
	}

	if ($user->authorise('core.access.reservations', 'com_vikappointments'))
	{
		?>
		<div class="appointments-stat overview-row overview-row-container">
			
			<div class="pending-orders row-child">

				<div class="row-main pending-count">
					<strong><?php echo JText::plural('VAP_N_RESERVATIONS', $pending); ?></strong>
					<small><?php _e('awaiting confirmation', 'vikappointments'); ?></small>
				</div>

			</div>

			<div class="needpay-orders row-child">
				
				<div class="row-main needpay-count">
					<strong><?php echo JText::plural('VAP_N_RESERVATIONS', $needpay); ?></strong>
					<small><?php _e('requiring payment', 'vikappointments'); ?></small>
				</div>

			</div>

		</div>
		<?php
	}
	?>

	<div class="weekly-chart overview-row">
		<?php
		// display widget
		echo $chart->display([
			'data' => $chart->getData(),
		]);
		?>
	</div>
	
</div>
