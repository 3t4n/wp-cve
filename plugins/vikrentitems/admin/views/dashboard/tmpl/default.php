<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$pidplace = $this->pidplace;
$arrayfirst = $this->arrayfirst;
$allplaces = $this->allplaces;
$nextrentals = $this->nextrentals;
$pickup_today = $this->pickup_today;
$dropoff_today = $this->dropoff_today;
$items_locked = $this->items_locked;
$totnextrentconf = $this->totnextrentconf;
$totnextrentpend = $this->totnextrentpend;

$nowdf = VikRentItems::getDateFormat(true);
$nowtf = VikRentItems::getTimeFormat(true);
if ($nowdf == "%d/%m/%Y") {
	$df = 'd/m/Y';
} elseif ($nowdf == "%m/%d/%Y") {
	$df = 'm/d/Y';
} else {
	$df = 'Y/m/d';
}
$selplace = "";
if (is_array($allplaces)) {
	$selplace = "<form action=\"index.php?option=com_vikrentitems\" method=\"post\" name=\"vridashform\" style=\"display: inline; margin: 0;\"> <label style=\"display: inline-block; mrgin-right: 5px;\">".JText::translate('VRIDASHPICKUPLOC')."</label> <select name=\"idplace\" onchange=\"javascript: document.vridashform.submit();\">\n<option value=\"0\">".JText::translate('VRIDASHALLPLACES')."</option>\n";
	foreach ($allplaces as $place) {
		$selplace .= "<option value=\"".$place['id']."\"".($place['id'] == $pidplace ? " selected=\"selected\"" : "").">".$place['name']."</option>\n";
	}
	$selplace .= "</select></form>\n";
}

/**
 * @wponly - check if some shortcodes have been defined before showing the Dashboard
 */
$model 		= JModel::getInstance('vikrentitems', 'shortcodes');
$shortcodes = $model->all('post_id');
//

?>
<div class="vri-dashboard-fullcontainer">
	<div class="vri-dashboard-today-bookings">
		<?php
		//Todays Pick Up
		?>
		<div class="vri-dashboard-today-pickup-wrapper">
			<h4><?php VikRentItemsIcons::e('sign-in'); ?> <?php echo JText::translate('VRIDASHTODAYPICKUP'); ?></h4>
			<div class="vri-dashboard-today-pickup table-responsive">
				<table class="table">
					<tr class="vri-dashboard-today-pickup-firstrow">
						<td align="center"><?php echo JText::translate('VRIDASHUPRESONE'); ?></td>
						<td align="center"><?php echo JText::translate('VRIDASHUPRESTWO'); ?></td>
						<td align="center"><?php echo JText::translate('VRPVIEWORDERSTWO'); ?></td>
						<td align="center"><?php echo JText::translate('VRIDASHUPRESTHREE'); ?></td>
						<td align="center"><?php echo JText::translate('VRIDASHUPRESFOUR'); ?></td>
						<td><?php echo JText::translate('VRIDASHUPRESFIVE'); ?></td>
					</tr>
				<?php
				foreach ($pickup_today as $next) {
					$nominative = strlen($next['nominative']) > 1 ? $next['nominative'] : VikRentItems::getFirstCustDataField($next['custdata']);
					$country_flag = '';
					if (is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$next['country'].'.png')) {
						$country_flag = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$next['country'].'.png'.'" title="'.$next['country'].'" class="vri-country-flag vri-country-flag-left"/>';
					}
					$num_items = $next['totitems'];
					if (!empty($next['item_names'])) {
						$oinames = explode(',', $next['item_names']);
						if (count($oinames) === 1 && !empty($oinames[0])) {
							if ($next['totitems'] > 1) {
								$num_items = $oinames[0].' x'.$next['totitems'];
							} else {
								$num_items = $oinames[0];
							}
						} elseif (count($oinames) > 1) {
							$num_items = '<span class="hasTooltip" title="'.htmlentities(implode(', ', $oinames)).'">'.$next['totitems'].'</span>';
						}
					}
					$status_lbl = '<span class="label label-success vri-status-label">'.strtoupper(JText::translate('VRIONFIRMED')).'</span>';
					if ($next['status'] == 'standby') {
						$status_lbl = '<span class="label label-warning vri-status-label">'.strtoupper(JText::translate('VRSTANDBY')).'</span>';
					} elseif ($next['status'] == 'cancelled') {
						$status_lbl = '<span class="label label-error vri-status-label">'.strtoupper(JText::translate('VRCANCELLED')).'</span>';
					}
					?>
					<tr class="vri-dashboard-today-pickup-rows">
						<td align="center"><a href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $next['id']; ?>"><?php echo $next['id']; ?></a></td>
						<td align="center"><?php echo $num_items; ?></td>
						<td align="center"><?php echo $country_flag.$nominative; ?></td>
						<td align="center"><?php echo (!empty($next['idplace']) && empty($pidplace) ? VikRentItems::getPlaceName($next['idplace'])." " : "").date('H:i', $next['ritiro']); ?></td>
						<td align="center"><?php echo (!empty($next['idreturnplace']) ? VikRentItems::getPlaceName($next['idreturnplace'])." " : "").date($df.' H:i', $next['consegna']); ?></td>
						<td align="center"><?php echo $status_lbl; ?></td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
		</div>
		<?php
		//Todays Drop Off
		?>
		<div class="vri-dashboard-today-dropoff-wrapper">
			<h4><?php VikRentItemsIcons::e('sign-out'); ?> <?php echo JText::translate('VRIDASHTODAYDROPOFF'); ?></h4>
			<div class="vri-dashboard-today-dropoff table-responsive">
				<table class="table">
					<tr class="vri-dashboard-today-dropoff-firstrow">
						<td class="left"><?php echo JText::translate('VRIDASHUPRESONE'); ?></td>
						<td class="left"><?php echo JText::translate('VRIDASHUPRESTWO'); ?></td>
						<td class="left"><?php echo JText::translate('VRPVIEWORDERSTWO'); ?></td>
						<td class="center"><?php echo JText::translate('VRIDASHUPRESTHREE'); ?></td>
						<td class="center"><?php echo JText::translate('VRIDASHUPRESFOUR'); ?></td>
						<td class="center"><?php echo JText::translate('VRIDASHUPRESFIVE'); ?></td>
					</tr>
				<?php
				foreach ($dropoff_today as $next) {
					$nominative = strlen($next['nominative']) > 1 ? $next['nominative'] : VikRentItems::getFirstCustDataField($next['custdata']);
					$country_flag = '';
					if (is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$next['country'].'.png')) {
						$country_flag = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$next['country'].'.png'.'" title="'.$next['country'].'" class="vri-country-flag vri-country-flag-left"/>';
					}
					$num_items = $next['totitems'];
					if (!empty($next['item_names'])) {
						$oinames = explode(',', $next['item_names']);
						if (count($oinames) === 1 && !empty($oinames[0])) {
							if ($next['totitems'] > 1) {
								$num_items = $oinames[0].' x'.$next['totitems'];
							} else {
								$num_items = $oinames[0];
							}
						} elseif (count($oinames) > 1) {
							$num_items = '<span class="hasTooltip" title="'.htmlentities(implode(', ', $oinames)).'">'.$next['totitems'].'</span>';
						}
					}
					$status_lbl = '<span class="label label-success vri-status-label">'.strtoupper(JText::translate('VRIONFIRMED')).'</span>';
					if ($next['status'] == 'standby') {
						$status_lbl = '<span class="label label-warning vri-status-label">'.strtoupper(JText::translate('VRSTANDBY')).'</span>';
					} elseif ($next['status'] == 'cancelled') {
						$status_lbl = '<span class="label label-error vri-status-label">'.strtoupper(JText::translate('VRCANCELLED')).'</span>';
					}
					?>
					<tr class="vri-dashboard-today-pickup-rows">
						<td class="left"><a href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $next['id']; ?>"><?php echo $next['id']; ?></a></td>
						<td class="left"><?php echo $num_items; ?></td>
						<td class="left"><?php echo $country_flag.$nominative; ?></td>
						<td class="center"><?php echo (!empty($next['idplace']) && empty($pidplace) ? VikRentItems::getPlaceName($next['idplace'])." " : "").date($df.' H:i', $next['ritiro']); ?></td>
						<td class="center"><?php echo (!empty($next['idreturnplace']) ? VikRentItems::getPlaceName($next['idreturnplace'])." " : "").date('H:i', $next['consegna']); ?></td>
						<td class="center"><?php echo $status_lbl; ?></td>
					</tr>
					<?php
				}
				?>
				</table>
			</div>
		</div>
	</div>

	<div class="vri-dashboard-next-bookings-block">
		<div class="vri-dashboard-next-bookings table-responsive">
			<h4><?php VikRentItemsIcons::e('calendar'); ?> <?php echo JText::translate('VRIDASHUPCRES'); ?></h4>
			<div class="vri-dashboard-pickloc-filterbar"><?php echo $selplace; ?></div>
	<?php
	if (is_array($nextrentals)) {
		?>
			<table class="table">
				<tr class="vri-dashboard-today-dropoff-firstrow">
					<td class="left"><?php echo JText::translate('VRIDASHUPRESONE'); ?></td>
					<td class="left"><?php echo JText::translate('VRIDASHUPRESTWO'); ?></td>
					<td class="left"><?php echo JText::translate('VRPVIEWORDERSTWO'); ?></td>
					<td class="center"><?php echo JText::translate('VRIDASHUPRESTHREE'); ?></td>
					<td class="center"><?php echo JText::translate('VRIDASHUPRESFOUR'); ?></td>
					<td class="center"><?php echo JText::translate('VRIDASHUPRESFIVE'); ?></td>
				</tr>
		<?php
		foreach ($nextrentals as $next) {
			$nominative = strlen($next['nominative']) > 1 ? $next['nominative'] : VikRentItems::getFirstCustDataField($next['custdata']);
			$country_flag = '';
			if (is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$next['country'].'.png')) {
				$country_flag = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$next['country'].'.png'.'" title="'.$next['country'].'" class="vri-country-flag vri-country-flag-left"/>';
			}
			$num_items = $next['totitems'];
			if (!empty($next['item_names'])) {
				$oinames = explode(',', $next['item_names']);
				if (count($oinames) === 1 && !empty($oinames[0])) {
					if ($next['totitems'] > 1) {
						$num_items = $oinames[0].' x'.$next['totitems'];
					} else {
						$num_items = $oinames[0];
					}
				} elseif (count($oinames) > 1) {
					$num_items = '<span class="hasTooltip" title="'.htmlentities(implode(', ', $oinames)).'">'.$next['totitems'].'</span>';
				}
			}
			$status_lbl = '<span class="label label-success vri-status-label">'.strtoupper(JText::translate('VRIONFIRMED')).'</span>';
			if ($next['status'] == 'standby') {
				$status_lbl = '<span class="label label-warning vri-status-label">'.strtoupper(JText::translate('VRSTANDBY')).'</span>';
			} elseif ($next['status'] == 'cancelled') {
				$status_lbl = '<span class="label label-error vri-status-label">'.strtoupper(JText::translate('VRCANCELLED')).'</span>';
			}
			?>
				<tr class="vri-dashboard-today-dropoff-rows">
					<td class="left"><a class="vri-orderid" href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $next['id']; ?>"><?php echo $next['id']; ?></a></td>
					<td class="left"><?php echo $num_items; ?></td>
					<td class="left"><?php echo $country_flag.$nominative; ?></td>
					<td class="center"><?php echo (!empty($next['idplace']) && empty($pidplace) ? VikRentItems::getPlaceName($next['idplace'])." " : "").date($df.' H:i', $next['ritiro']); ?></td>
					<td class="center"><?php echo (!empty($next['idreturnplace']) ? VikRentItems::getPlaceName($next['idreturnplace'])." " : "").date($df.' H:i', $next['consegna']); ?></td>
					<td class="center"><?php echo $status_lbl; ?></td>
				</tr>
			<?php
		}
		?>
			</table>
		<?php
	}
	?>
		</div>
	</div>

	<?php
	//Items Locked
	if (count($items_locked)) {
		?>
	<div class="vri-dashboard-items-locked-block">
		<div class="vri-dashboard-items-locked table-responsive">
			<h4 id="vri-dashboard-items-locked-toggle"><?php echo JText::translate('VRIDASHITEMSLOCKED'); ?><span>(<?php echo count($items_locked); ?>)</span></h4>
			<table class="table" style="display: none;">
				<tr class="vri-dashboard-items-locked-firstrow">
					<td align="center"><?php echo JText::translate('VRIDASHUPRESTWO'); ?></td>
					<td align="center"><?php echo JText::translate('VRPVIEWORDERSTWO'); ?></td>
					<td align="center"><?php echo JText::translate('VRIDASHLOCKUNTIL'); ?></td>
					<td align="center"><?php echo JText::translate('VRIDASHUPRESONE'); ?></td>
					<td align="center">&nbsp;</td>
				</tr>
			<?php
			foreach ($items_locked as $lock) {
				$country_flag = '';
				if (is_file(VRI_ADMIN_PATH.DS.'resources'.DS.'countries'.DS.$lock['country'].'.png')) {
					$country_flag = '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$lock['country'].'.png'.'" title="'.$lock['country'].'" class="vri-country-flag vri-country-flag-left"/>';
				}
				?>
				<tr class="vri-dashboard-items-locked-rows">
					<td align="center"><?php echo $lock['item_name']; ?></td>
					<td align="center"><?php echo $country_flag.$lock['nominative']; ?></td>
					<td align="center"><?php echo date($df.' H:i', $lock['until']); ?></td>
					<td align="center"><a href="index.php?option=com_vikrentitems&amp;task=editorder&amp;cid[]=<?php echo $lock['idorder']; ?>" target="_blank"><?php echo $lock['idorder']; ?></a></td>
					<td align="center"><button type="button" class="btn btn-danger" onclick="if (confirm('<?php echo addslashes(JText::translate('VRIDELCONFIRM')); ?>')) location.href='index.php?option=com_vikrentitems&amp;task=unlockrecords&amp;cid[]=<?php echo $lock['id']; ?>';"><?php echo JText::translate('VRIDASHUNLOCK'); ?></button></td>
				</tr>
				<?php
			}
			?>
			</table>
		</div>
	</div>

	<script type="text/JavaScript">
	if (jQuery.isFunction(jQuery.fn.tooltip)) {
		jQuery(".hasTooltip").tooltip();
	} else {
		jQuery.fn.tooltip = function() {};
	}
	jQuery(document).ready(function() {
		jQuery("#vri-dashboard-items-locked-toggle").click(function() {
			jQuery(this).next("table").fadeToggle();
		});
	});
	</script>
		<?php
	}
	?>

	<div class="vridashdivright">
		<h4><?php VikRentItemsIcons::e('tasks'); ?> <?php echo JText::translate('VRIDASHSTATS'); ?></h4>
		<div class="vri-dash-reports">
		<?php
		if ($arrayfirst['totprices'] < 1) {
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOPRICES'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&task=prices" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		}
		if ($arrayfirst['totlocations'] < 1 && $arrayfirst['totitems'] < 1) {
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOLOCATIONS'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&task=places" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		} else {
			?>
			<p class="vridashparag">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOLOCATIONS'); ?></span>
				<span class="vri-dash-report-val"><?php echo $arrayfirst['totlocations']; ?></span>
			</p>
			<?php
		}
		if ($arrayfirst['totcategories'] < 1) {
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOCATEGORIES'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&task=categories" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		} else {
			?>
			<p class="vridashparag">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOCATEGORIES'); ?></span>
				<span class="vri-dash-report-val"><?php echo $arrayfirst['totcategories']; ?></span>
			</p>
			<?php
		}
		if ($arrayfirst['totitems'] < 1) {
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOITEMS'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&task=items" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		} else {
			?>
			<p class="vridashparag">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNOITEMS'); ?></span>
				<span class="vri-dash-report-val"><?php echo $arrayfirst['totitems']; ?></span>
			</p>
			<?php
		}
		if ($arrayfirst['totdailyfares'] < 1) {
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHNODAILYFARES'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&task=tariffs" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		}
		if (count($shortcodes) < 1) {
			/**
			 * @wponly  we use this IF statement by enclosing the rest in an ELSE statement
			 */
			?>
			<p class="vridashparagred">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIFIRSTSETSHORTCODES'); ?></span>
				<span class="vri-dash-report-val">0</span>
				<a href="index.php?option=com_vikrentitems&view=shortcodes" class="button button-secondary"><?php echo JText::translate('VRICONFIGURETASK'); ?></a>
			</p>
			<?php
		} else {
		?>
			<p class="vridashparag">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHTOTRESCONF'); ?></span>
				<span class="vri-dash-report-val"><?php echo $totnextrentconf; ?></span>
			</p>
			<p class="vridashparag">
				<span class="vri-dash-report-lbl"><?php echo JText::translate('VRIDASHTOTRESPEND'); ?></span>
				<span class="vri-dash-report-val"><?php echo $totnextrentpend; ?></span>
			</p>
		<?php
		}
		?>
		</div>
	</div>

</div>
