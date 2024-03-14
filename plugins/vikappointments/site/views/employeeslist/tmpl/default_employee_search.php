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

$e = $this->employee;

$url = 'index.php?option=com_vikappointments&view=employeesearch&id_employee=' . $e->id;

if (!empty($this->filters['service']))
{
	$url .= '&id_service=' . $this->filters['service'];

	$id_service = $this->filters['service'];
}
else
{
	$id_service = 0;
}

if ($this->itemid)
{
	$url .= '&Itemid=' . $this->itemid;
}

// build employee details URL
$url = JRoute::rewrite($url);

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

// fetch image action preferences
$link_href = $config->getUint('emplinkhref');

?>

<div class="vapempblock-search <?php echo $vik->getThemeClass('background'); ?>" id="vapempblock<?php echo $e->id; ?>" data-employee="<?php echo $e->id; ?>" data-service="<?php echo $id_service; ?>" data-day="">

	<!-- DETAILS -->

	<div class="emp-search-box-left">

		<!-- PROFILE -->

		<div class="emp-profile-box">

			<?php
			if ($e->image && is_file(VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $e->image))
			{
				// render image tag
				$image = JHtml::fetch('vaphtml.media.display', $e->image, [
					'loading' => 'lazy',
					'alt'     => $e->nickname,
					'small'   => true,
				]);
				
				?>
				<!-- EMPLOYEE IMAGE -->

				<div class="emp-logo-image">
					<?php
					if ($link_href == 2)
					{
						// by clicking the image, the system should open a popup containing the original image
						?>
						<a href="javascript:void(0)" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $e->image; ?>', this);">
							<?php echo $image; ?>
						</a>
						<?php
					}
					else
					{
						// by clicking the image, the users are redirected to the details of the employee
						?>
						<a href="<?php echo $url; ?>">
							<?php echo $image; ?>
						</a>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>

			<!-- EMPLOYEE HEAD -->

			<div class="emp-title-box">

				<!-- EMPLOYEE NAME -->

				<div class="emp-name-box">
					<a href="<?php echo $url; ?>"><?php echo $e->nickname; ?></a>
				</div>

				<?php
				if ($e->group)
				{
					?>
					<!-- EMPLOYEE GROUP NAME -->

					<div class="emp-group-box">
						<?php echo $e->group->name; ?>
					</div>
					<?php
				}
				?>

			</div>

		</div>

		<?php
		if (VikAppointments::isEmployeesReviewsEnabled())
		{
			?>
			<!-- REVIEWS -->

			<div class="emp-reviews-box">

				<!-- RATING -->

				<div class="emp-stars-box">
					<?php
					if ($e->rating > 0)
					{
						// Display the rating stars through this helper method.
						// Set the argument to false to use FontAwesome icons
						// in place of the images.
						echo JHtml::fetch('vikappointments.rating', $e->rating, $image = true);
					}
					?>
				</div>

				<!-- REVIEWS SUBTITLE -->

				<div class="emp-rating-subtitle">
					<?php
					// review subtitle
					if ($e->reviewsCount)
					{
						echo JText::sprintf('VAPREVIEWSSUBTITLE1', $e->reviewsCount);
					}
					else
					{
						echo JText::translate('VAPNOREVIEWSSUBTITLE');
					}	
					?>
				</div>

			</div>
			<?php
		}

		if ($e->locations)
		{
			?>
			<!-- LOCATIONS -->

			<div class="emp-locations-box">
				
				<?php
				foreach ($e->locations as $loc)
				{
					?>
					<div class="emp-location-row">
						<div class="address"><?php echo $loc->text; ?></div>
						
						<?php
						if (isset($this->filters['latitude']) && strlen($loc->latitude) && strlen($loc->longitude))
						{ 
							$distance = VikAppointments::getGeodeticaDistance(
								$loc->latitude,
								$loc->longitude,
								$this->filters['latitude'],
								$this->filters['longitude']
							);

							?>
							<div class="distance">
								<?php
								echo JText::sprintf('VAPDISTANCEFROMYOU', VikAppointments::formatDistance($distance));
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
			<?php
		}
		
		if (!empty($e->rate))
		{
			?>
			<!-- QUICK CONTACT -->

			<div class="emp-rate-box">
				<strong><?php echo VAPFactory::getCurrency()->format($e->rate); ?></strong>
			</div>
			<?php
		}
		?>

		<!-- DETAILS BUTTON -->

		<div class="emp-viewdetails-box">
			<a href="<?php echo $url; ?>" class="vap-btn blue">
				<?php echo JText::translate('VAPVIEWDETAILS'); ?>
			</a>
		</div>

		<?php
		if ($e->quick_contact)
		{
			?>
			<!-- QUICK CONTACT -->

			<div class="emp-quickcontact-box">
				<a class="vap-btn blue" href="javascript: void(0);" onClick="vapGoToMail('#vapempblock<?php echo $e->id; ?>', <?php echo $e->id; ?>, '<?php echo addslashes(JText::sprintf('VAPEMPTALKINGTO', $e->nickname)); ?>');">
					<?php echo JText::translate('VAPEMPQUICKCONTACT'); ?>
				</a>
			</div>
			<?php
		}

		if ($e->showphone)
		{
			?>
			<!-- PHONE NUMBER -->

			<div class="emp-phone-box">
				<span>
					<?php
					/**
					 * The phone number is now clickable to start a call on mobile devices.
					 *
					 * @since 1.6.2
					 */
					?>
					<a href="tel:<?php echo $e->phone; ?>">
						<i class="fas fa-phone"></i>
						<?php echo $e->phone; ?>
					</a>
				</span>
			</div>
			<?php
		}
		?>

	</div>

	<!-- AVAILABILITY -->

	<div class="emp-search-box-right">

		<div class="emp-search-loading">
			<img src="<?php echo VAPASSETS_URI; ?>css/images/loading.gif" />
		</div>

	</div>

</div>

<script>

	jQuery(function($) {
		/**
		 * This function is declared by this view:
		 * views/employeeslist/view.html.php
		 * 
		 * @see 	addJS()
		 */
		loadEmployeeAvailTable(<?php echo $e->id; ?>);
	});

</script>
