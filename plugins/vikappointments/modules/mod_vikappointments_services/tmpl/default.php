<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_services
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

$itemid = $params->get('itemid');

$listing = VikAppointments::getServicesListingDetails();

$currency = VAPFactory::getCurrency();

$pagination = $params->get('pagination', 0);
$navigation = $params->get('navigation', 1);
$autoplay   = $params->get('autoplay', 1);

?>

<div class="vamodservices-container wrap">
	<div id="vamodservices-<?php echo $module_id; ?>" class="owl-carousel owl-theme vamodservices-inner">

		<?php foreach ($services as $service): ?>
			<div class="vamodservices-item item">

				<div class="vamodservices-boxdiv">	

					<?php if (!empty($service['image']) && $params->get('showimg')): ?>
						<div class="vamodservices-image-box">
							<img src="<?php echo VAPMEDIA_URI . $service['image']; ?>" class="vamodservices-img" style="width: 100%;" alt="<?php echo htmlspecialchars($service['name']); ?> "/>
						</div>
					<?php endif; ?>

					<div class="vainf">

						<div class="vamodservices-divblock">

							<div class="vamodservices-name"><?php echo $service['name']; ?></div>

							<?php if ($params->get('showdesc')): ?>
								<div class="vamodservices-desc">
									<?php
									/**
									 * Displays the short description if specified, otherwise takes the first N
									 * characters from the long one.
									 *
									 * @since 1.4
									 */
									echo VikAppointments::renderShortHtmlDescription($service['description'], $listing['desclength']);
									?>
								</div>
							<?php endif; ?>

						</div>

					</div>

					<div class="vamodservices-detailsbox">

						<div class="vamodservices-details">

							<?php if ($params->get('showduration')): ?>
								<div class="vamodservices-duration">
									<span class="vamodservices-durlabel"><?php echo JText::translate('VASERVICESDURATIONTEXT'); ?></span>
									<span class="vamodservices-dur"> <?php echo VikAppointments::formatMinutesToTime($service['duration']); ?></span>
								</div>
							<?php endif; ?>

							<?php if ($params->get('showprice') && $service['price'] > 0): ?>
								<div class="vamodservices-cost">
									<div class="vamodservices-price">
										<?php
										if ($params->get('showpricelabel'))
										{
											?>
											<span class="vamodservices-pricelabel"><?php echo JText::translate('VASERVICESPRICELABELTEXT'); ?> </span>
											<?php
										}

										echo $currency->format($service['price']);
										?>
									</div>
								</div>
							<?php endif; ?>

						</div>

						<?php if ($params->get('showbutton')): ?>
							<span class="vamodservices-view">
								<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch&id_service=' . $service['id'] . ($itemid ? '&Itemid=' . $itemid : '')); ?>" class="vap-btn blue">
									<?php echo JText::translate('VASERVICESCONTINUE'); ?>
								</a>
							</span>
						<?php endif; ?>

					</div>

				</div>

			</div>
		<?php endforeach; ?>

	</div>
</div>

<?php
/**
 * The buttons named "next" and "prev" are now translatable.
 * 
 * @since 1.3.2
 */
JText::script('JPREV');
JText::script('JNEXT');
?>

<script>
	(function($) {
		'use strict';

		$(function() {
			$("#vamodservices-<?php echo $module_id; ?>").owlCarousel({
				items:           <?php echo $params->get('numb_roomrow', 4); ?>,
				autoplay:        <?php echo $autoplay == 1 || $autoplay === 'true' ? 'true' : 'false'; ?>,
				autoplayTimeout: <?php echo (int) $params->get('autoplaytime', 5000); ?>,
				dots:            <?php echo $pagination == 1 || $pagination === 'true' ? 'true' : 'false'; ?>,
				nav:             <?php echo $navigation == 1 || $navigation === 'true' ? 'true' : 'false'; ?>,
				navText:         [Joomla.JText._('JPREV'), Joomla.JText._('JNEXT')],
				lazyLoad:        true,
				loop:            true,
			});
		});
	})(jQuery);
</script>
