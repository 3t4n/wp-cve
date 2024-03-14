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

<div class="vapempblock <?php echo $vik->getThemeClass('background'); ?>" id="vapempblock<?php echo $this->employee->id; ?>">

	<div class="vapempinfoblock">

		<?php
		if (strlen($this->employee->image) && file_exists(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $this->employee->image))
		{
			?>
			<div class="vapempimgdiv">
				<a href="javascript: void(0);" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $this->employee->image; ?>', this);">
					<?php
					// render image tag
					echo JHtml::fetch('vaphtml.media.display', $this->employee->image, [
						'loading' => 'lazy',
						'alt'     => $this->employee->nickname,
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
					<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=employeesearch&id_employee=' . $this->employee->id . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>">
						<?php echo $this->employee->nickname; ?>
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
						echo JHtml::fetch('vikappointments.rating', $this->employee->rating, $image = true);
						?>
					</div>
					<?php
				}
				?>

				<?php
				if ($reviews_enabled && !empty($rev_sub_title))
				{
					?>
					<div class="vap-empsubreview-div">
						<?php echo $rev_sub_title; ?>
					</div>
					<?php
				}
				?>

			</div>

			<div class="vapempdescdiv">
				<?php
				// display long employee description
				echo VikAppointments::renderHtmlDescription($this->employee->note, 'employeesearch');
				?>
			</div>
		</div>
		
	</div>
	
	<?php
	/**
	 * Check whether the price of the service should be displayed or not.
	 *
	 * @since 1.7
	 */
	$should_display_price = VikAppointments::shouldDisplayServicePrice($this->service);

	if ($this->employee->showphone || $this->employee->quick_contact || $should_display_price)
	{
		?>
		<div class="vapempcontactdiv">
			<span class="vapempcontactsp">
				<?php
				if ($should_display_price)
				{
					?>
					<span class="vap-price-info-box left-side">
						<i class="fas fa-money-bill"></i>

						<span class="vap-toolbar-ratedetails" id="vapratebox">
							<?php echo VAPFactory::getCurrency()->format($this->service->price * $this->service->min_per_res); ?>
						</span>
					</span>
					<?php
				}

				if ($this->employee->showphone)
				{
					?>
					<span class="vap-price-info-box">
						<i class="fas fa-phone"></i>
						
						<span class="vapempphonesp">
							<?php
							/**
							 * The phone number is now clickable to start a call on mobile devices.
							 *
							 * @since 1.6.2
							 */
							?>
							<a href="tel:<?php echo $this->employee->phone; ?>"><?php echo $this->employee->phone; ?></a>
						</span>
					</span>
					<?php
				}
				
				if ($this->employee->quick_contact)
				{
					?>
					<span class="vap-price-info-box">
						<i class="fas fa-envelope"></i>

						<span class="vapempquickcontsp">
							<a href="javascript:void(0)" onClick="vapGoToMail('.vapempblock');">
								<?php echo JText::translate('VAPEMPQUICKCONTACT'); ?>
							</a>
						</span>
					</span>
					<?php
				}
				?>
			</span>
		</div>
		<?php
	}
	?>
</div>
