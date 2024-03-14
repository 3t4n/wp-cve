<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_items
 * @author      Alessio Gaggii - e4j srl - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2020 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$currencysymb = $params->get('currency');
$pagination = $params->get('pagination');
$navigation = $params->get('navigation');
$autoplayparam = $params->get('autoplay');
$autoplaytime = $params->get('autoplaytime');
$showdetails = $params->get('showdetailsbtn');
$itemdesc = $params->get('showitemdesc');
$getdesc = $params->get('mod_desc');
$getshowcarats = $params->get('show_carats');
$loop = $params->get('get_loop');

$get_module_class = $params->get('moduleclass_sfx');

if ($navigation == 1) {
	$show_navigation = 'true';
} else {
	$show_navigation = 'false';
}

if ($pagination == 1) {
	$show_pagination = 'true';
} else {
	$show_pagination = 'false';
}

if ($loop == 1) {
	$loop_status = 'true';
} else {
	$loop_status = 'false';
}

if ($autoplayparam == 1) {
	$show_autoplay = 'true';
} else {
	$show_autoplay = 'false';
}

$numb_xrow = $params->get('numb_itemrow');

$document = JFactory::getDocument();
$document->addStyleSheet($baseurl . 'modules/mod_vikrentitems_items/src/owl.carousel.min.css');
$document->addStyleSheet($baseurl . 'modules/mod_vikrentitems_items/mod_vikrentitems_items.css');

JHtml::fetch('script', $baseurl . 'modules/mod_vikrentitems_items/src/owl.carousel.min.js');

?>
<div class="vrimoditemscontainer wrap">
	<?php if (!empty($getdesc)) { ?>
		<div class="vrimoditem-desc"><?php echo $getdesc; ?></div>
	<?php } ?>
	<div>
		<div id="vri-moditems-<?php echo $randid; ?>" class="owl-carousel owl-theme vrimoditems">
			<?php
				foreach ($items as $c) {
					$carats = VikRentItems::getItemCaratOriz($c['id']);
				?>
			<div class="vrimoditems-item">
				<div class="vrimoditemsboxdiv">
					<?php
					if (!empty($c['img'])) {
						$imgpath = is_file(VRI_ADMIN_PATH . DS . 'resources' . DS . 'vthumb_' . $c['img']) ? VRI_ADMIN_URI . 'resources/vthumb_'.$c['img'] : VRI_ADMIN_URI . 'resources/' . $c['img'];
						?>
						<img src="<?php echo $imgpath; ?>" alt="<?php echo $c['name']; ?>" class="vrimoditemsimg"/>
						<?php
					}
					?>
					<div class="vriinf">
						<div class="vrimoditems-divblock">
					        <span class="vrimoditemsname"><?php echo $c['name']; ?></span>
						</div>
						<?php
						if ($showcatname) {
						?>
							<span class="vrimoditemscat"><?php echo $c['catname']; ?></span>
						<?php
						}
						if ($itemdesc) {
						?>	
							<span class="vrimoditemsdesc"><?php echo $c['shortdesc']; ?></span>		
						<?php
						}
						if ($carats && $getshowcarats == 1) {
							?>
							<div class="vrimoditems-carats">
								<?php echo $carats; ?>
							</div>
							<?php
						}
						if ($c['cost'] > 0) {
							$cost_text = ModVikRentItemsItemsHelper::getItemParam($c['params'], 'startfromtext');
							?>
							<div class="vrimoditemsitemcost">
								<span class="vri_currency"><?php echo $currencysymb; ?></span> 
								<span class="vri_price"><?php echo ModVikRentItemsItemsHelper::numberFormat($c['cost']); ?></span>
							<?php
							if (!empty($cost_text)) {
								?>
								<span class="vrimoditemsitemcost-txt"><?php echo JText::translate($cost_text); ?></span> 
								<?php
							}
							?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
					if ($showdetails == 1) {
						?>
					<span class="vrimoditemsview">
						<a href="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems&view=itemdetails&elemid='.$c['id'].'&Itemid='.$params->get('itemid')); ?>"><?php echo JText::translate('VRIMODITEMSCONTINUE'); ?></a>
					</span>
						<?php
					}
					?>
				</div>	
			</div>
		<?php
		} ?>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() { 
	jQuery("#vri-moditems-<?php echo $randid; ?>").owlCarousel({
		items : <?php echo $numb_xrow; ?>,
		autoplay : <?php echo $show_autoplay; ?>,
		nav : <?php echo $show_navigation; ?>,
		navText : ['<?php echo JText::translate('VRIMODITEMSPREV'); ?>', '<?php echo JText::translate('VRIMODITEMSNEXT'); ?>'],
		dots : <?php echo $show_pagination; ?>,
		loop : <?php echo $loop_status; ?>,
		lazyLoad : true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			<?php if ($numb_xrow == 1) { ?>
				600: {
					items:1,
					nav: true
				},
			<?php } else { ?>
				600: {
					items:2,
					nav: true
				},
			<?php } ?>
			<?php if ($numb_xrow == 1) { ?>
				820: {
					items: 1,
					nav: true
				},
			<?php } else if ($numb_xrow == 2) { ?>
				820: {
					items: 2,
					nav: true
				},
			<?php } else { ?>
				820: {
					items: 3,
					nav: true
				},
			<?php } ?>
			1024: {
				items: <?php echo $numb_xrow; ?>,
				nav: true
			}
		}		
	});

	<?php if ($show_navigation == "false") { ?>
		jQuery("#vri-moditems-<?php echo $randid; ?> .owl-nav").addClass('owl-disabled');
	<?php } ?>
});
</script>
