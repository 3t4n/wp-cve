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
}

if ($this->itemid)
{
	$url .= '&Itemid=' . $this->itemid;
}

// build employee URL
$url = JRoute::rewrite($url);

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

// fetch image action preferences
$link_href = $config->getUint('emplinkhref');

?>

<div class="vapempblock <?php echo $vik->getThemeClass('background'); ?>" id="vapempblock<?php echo $e->id; ?>">

	<div class="vapempinfoblock">

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
			<!-- display image -->

			<div class="vapempimgdiv">
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

		<div class="vap-empinfo">

			<!-- block with name and reviews -->

			<div class="vap-empheader-div">

				<div class="vapempnamediv">
					<a href="<?php echo $url; ?>">
						<?php echo $e->nickname; ?>
					</a>
				</div>

				<?php
				if ($e->group)
				{
					?>
					<!-- employee group name -->

					<div class="vap-empgroup-namediv">
						<?php echo $e->group->name; ?>
					</div>
					<?php
				}

				if (VikAppointments::isEmployeesReviewsEnabled())
				{
					?>
					<div class="vapempratingdiv">
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

					<div class="vap-empsubreview-div">
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
					<?php
				}
				?>

			</div>

		</div>
	   
		<div class="vapempdescdiv">

			<!-- employee description -->

			<?php
			// get maximum number of supported characters
			$max_length = $config->getUint('empdesclength');

			// display short description
			echo VikAppointments::renderShortHtmlDescription($e->note, $max_length);
			?>
		</div>
		
		<?php
		if ($e->locations)
		{
			?>
			<!-- employee locations list -->

			<div class="vap-emp-avloc-block">
				
				<?php
				foreach ($e->locations as $loc)
				{
					?>

					<div class="vap-emp-avlocation-item">
						<span class="address"><i class="fas fa-map-marker-alt"></i> <?php echo $loc->text; ?></span>
						
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
							<span class="distance">
								<?php
								echo JText::sprintf('VAPDISTANCEFROMYOU', VikAppointments::formatDistance($distance));
								?>
							</span>
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
		?>
		
	</div>
	
	<?php
	if ($e->showphone || $e->quick_contact || !empty($e->rate))
	{
		?>
		<!-- contact information and rate -->
		
		<div class="vapempcontactdiv">

			<span class="vapempcontactsp">

				<?php
				if (!empty($e->rate))
				{
					?>
					<span class="vap-price-info-box left-side">
						<i class="fas fa-money-bill"></i>

						<span class="vapempratesp">
							<?php echo VAPFactory::getCurrency()->format($e->rate); ?>
						</span>
					</span>
					<?php
				}

				if ($e->showphone)
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
							<a href="tel:<?php echo $e->phone; ?>"><?php echo $e->phone; ?></a>
						</span>
					</span>
					<?php
				}

				if ($e->quick_contact)
				{
					?>
					<span class="vap-price-info-box">
						<i class="fas fa-envelope"></i>

						<span class="vapempquickcontsp">
							<a href="javascript: void(0);" onClick="vapGoToMail('#vapempblock<?php echo $e->id; ?>', <?php echo $e->id; ?>, '<?php echo addslashes(JText::sprintf('VAPEMPTALKINGTO', $e->nickname)); ?>');">
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
