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

$vik    = VAPApplication::getInstance();
$config = VAPFactory::getConfig();

// build service details URL
$url = JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch&id_service=' . $this->service->id . ($this->itemid ? '&Itemid=' . $this->itemid : ''));

// fetch image action preferences
$link_href = $config->getUint('serlinkhref');

?>

<div class="vapserblock <?php echo $vik->getThemeClass('background'); ?>" id="vapserblock<?php echo $this->service->id; ?>">
							
	<div class="vapserwrapper">
		
		<div class="vapserimage" id="vapimage<?php echo $this->service->id; ?>">
			<?php
			if ($this->service->image)
			{
				// render image tag
				$image = JHtml::fetch('vaphtml.media.display', $this->service->image, [
					'loading' => 'lazy',
					'alt'     => $this->service->name,
					'small'   => false,
				]);

				if ($link_href == 2)
				{
					// by clicking the image, the system should open a popup containing the original image
					?>
					<a href="javascript:void(0)" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPMEDIA_URI . $this->service->image; ?>', this);">
						<?php echo $image; ?>
					</a>
					<?php
				}
				else
				{
					// by clicking the image, the users are redirected to the details of the service
					?>
					<a href="<?php echo $url; ?>">
						<?php echo $image; ?>
					</a>
					<?php
				}
			}
			?>
		</div>
		
		<div class="vapsername">
			<a href="<?php echo $url; ?>">
				<?php echo $this->service->name; ?>
			</a>
		</div>
		
		<?php
		if (VikAppointments::isServicesReviewsEnabled())
		{
			?>
			<div class="vapserbottomreview">
				<div class="reviewleft">
					<?php
					// Display the rating stars through this helper method.
					// Set the argument to false to use FontAwesome icons
					// in place of the images.
					echo JHtml::fetch('vikappointments.rating', $this->service->rating, $image = true);
					?>
				</div>

				<div class="reviewright">
					<?php
					// review subtitle
					if ($this->service->reviewsCount)
					{
						echo JText::sprintf('VAPREVIEWSSUBTITLE1', $this->service->reviewsCount);
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
		?>

	</div>
	
	<?php
	if ($link_href == 3 || empty($this->service->image))
	{
		// If the image is empty or the service description should be shown,
		// put a box containing the description of the service.
		?>
		<div class="vapserdescwrap <?php echo ($this->service->image ? '' : 'always'); ?>" id="vapdesc<?php echo $this->service->id; ?>">
			<div class="vapserdesc">
				<?php
				// get maximum number of supported characters
				$max_length = $config->getUint('serdesclength');

				// display short description
				echo VikAppointments::renderShortHtmlDescription($this->service->description, $max_length);
				?>
			</div>
		</div>
		<?php
	}
	?>
		
</div>
