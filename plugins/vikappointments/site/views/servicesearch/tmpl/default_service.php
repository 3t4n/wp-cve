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

$reviews_enabled = $this->displayData['reviewsEnabled'];
$rev_sub_title   = $this->displayData['review_sub'];

$vik = VAPApplication::getInstance();

?>

<div class="vapempblock <?php echo $vik->getThemeClass('background'); ?>" id="vapempblock<?php echo $this->service->id; ?>">

	<div class="vapempinfoblock">

		<?php
		if (strlen($this->service->image) && file_exists(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $this->service->image))
		{
			?>
			<div class="vapempimgdiv">
				<a href="javascript:void(0)" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $this->service->image; ?>', this);">
					<?php
					// render image tag
					echo JHtml::fetch('vaphtml.media.display', $this->service->image, [
						'loading' => 'lazy',
						'alt'     => $this->service->name,
						'small'   => true,
					]);
					?>
				</a>
			</div>
			<?php
		}
		?>

		<div class="vap-empmain-block">

			<div class="vap-empheader-div">

				<div class="vapempnamediv">
					<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch&id_service=' . $this->service->id . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>">
						<?php echo $this->service->name; ?>
					</a>
				</div>

				<?php
				if ($reviews_enabled)
				{
					?>
					<div class="vapempratingdiv">
						<?php
						// Display the rating stars through this helper method.
						// Set the argument to false to use FontAwesome icons
						// in place of the images.
						echo JHtml::fetch('vikappointments.rating', $this->service->rating, $image = true);
						?>
					</div>
					<?php

					if (!empty($rev_sub_title))
					{
						?>
						<div class="vap-empsubreview-div">
							<?php echo $rev_sub_title; ?>
						</div>
						<?php
					}
				}
				?>

			</div>

			<div class="vapempdescdiv">
				<?php
				// display long service description
				echo VikAppointments::renderHtmlDescription($this->service->description, 'servicesearch');
				?>
			</div>

		</div>
		
	</div>
	
	<div class="vapempcontactdiv">
		<span class="vapempcontactsp">

			<?php
			if ($this->service->quick_contact)
			{
				?>
				<span class="vap-price-info-box left-side service-ask">
					<i class="fas fa-envelope"></i>

					<span class="vapempquickcontsp">
						<a href="javascript:void(0)" onClick="vapGoToMail('.vapempinfoblock');">
							<?php echo JText::translate('VAPSERQUICKCONTACT'); ?>
						</a>
					</span>
				</span>
				<?php
			}
			
			/**
			 * Check whether the price of the service should be displayed or not.
			 *
			 * @since 1.7
			 */
			if (VikAppointments::shouldDisplayServicePrice($this->service))
			{
				$service_cost = VAPSpecialRates::getRate($this->service->id, $this->idEmployee, $this->date, $this->service->min_per_res);

				if ($this->service->priceperpeople)
				{
					$service_cost *= $this->service->min_per_res;
				}
				?>
				<span class="vap-price-info-box service-price">
					<i class="fas fa-money-bill"></i>

					<span class="vapempserpricesp">
						<?php echo VAPFactory::getCurrency()->format($service_cost); ?>
					</span>
				</span>
				<?php
			}
			?>
			
			<span class="vap-price-info-box service-duration">
				<i class="fas fa-clock"></i>

				<span class="vapempsertimesp">
					<?php echo VikAppointments::formatMinutesToTime($this->service->duration); ?> 
				</span>
			</span>

		</span>
	</div>

</div>

<script>

	function vapUpdateServiceRate(rate) {
		/**
		 * @todo 	Should the rate be updated
		 * 			also in case the new cost has been 
		 * 			nullified (free)?
		 */

		if (rate > 0) {
			// update only if the rate is higher than 0
			jQuery('.vapempserpricesp').html(Currency.getInstance().format(rate));
		}
	}

</script>
