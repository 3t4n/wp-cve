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

class VikRentItemsHelper {

	public static function printHeader($highlight="") {
		$cookie = JFactory::getApplication()->input->cookie;
		$tmpl = VikRequest::getVar('tmpl');
		if ($tmpl == 'component') {
			return;
		}
		/**
		 * @wponly Hide menu for Pro-update views
		 */
		$view = VikRequest::getVar('view');
		$skipmenu = array('getpro');
		if (in_array($view, $skipmenu)) {
			return;
		}
		//
		$backlogo = VikRentItems::getBackendLogo();
		$vri_auth_items = JFactory::getUser()->authorise('core.vri.items', 'com_vikrentitems');
		$vri_auth_prices = JFactory::getUser()->authorise('core.vri.prices', 'com_vikrentitems');
		$vri_auth_orders = JFactory::getUser()->authorise('core.vri.orders', 'com_vikrentitems');
		$vri_auth_gsettings = JFactory::getUser()->authorise('core.vri.gsettings', 'com_vikrentitems');
		$vri_auth_management = JFactory::getUser()->authorise('core.vri.management', 'com_vikrentitems');
		?>
		<div class="vri-menu-container">
			<div class="vri-menu-left"><img src="<?php echo VRI_ADMIN_URI . (!empty($backlogo) ? 'resources/'.$backlogo : 'vikrentitems.png'); ?>" alt="VikRentItems Logo" /></div>
			<div class="vri-menu-right">
				<ul class="vri-menu-ul"><?php
					if ($vri_auth_prices || $vri_auth_gsettings) {
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('truck-moving'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRMENUONE'); ?></a></span>
						<ul class="vri-submenu-ul">
						<?php
						if ($vri_auth_prices) {
							?>
							<li><span class="<?php echo ($highlight=="2" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=iva"><?php echo JText::translate('VRMENUNINE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="1" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=prices"><?php echo JText::translate('VRMENUFIVE'); ?></a></span></li>
							<?php
						}
						if ($vri_auth_gsettings) {
							?>
							<li><span class="<?php echo ($highlight=="3" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=places"><?php echo JText::translate('VRMENUTENTHREE'); ?></a></span></li>
							<?php
						}
						if ($vri_auth_prices) {
							?>
							<li><span class="<?php echo ($highlight=="timeslots" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=timeslots"><?php echo JText::translate('VRMENUTIMESLOTS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="restrictions" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=restrictions"><?php echo JText::translate('VRMENURESTRICTIONS'); ?></a></span></li>
							<?php
						}
						?>
						</ul>
					</li><?php
					}
					if ($vri_auth_items) {
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('layer-group'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRMENUTWO'); ?></a></span>
						<ul class="vri-submenu-ul">
							<li><span class="<?php echo ($highlight=="4" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=categories"><?php echo JText::translate('VRMENUSIX'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="6" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=optionals"><?php echo JText::translate('VRMENUTENFIVE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="5" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=carat"><?php echo JText::translate('VRMENUTENFOUR'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="7" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=items"><?php echo JText::translate('VRMENUTEN'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="relations" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=relations"><?php echo JText::translate('VRMENURELATIONS'); ?></a></span></li>
						</ul>
					</li><?php
					}
					if ($vri_auth_prices) {
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('calculator'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRIMENUFARES'); ?></a></span>
						<ul class="vri-submenu-ul">
							<li><span class="<?php echo ($highlight=="fares" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=tariffs"><?php echo JText::translate('VRIMENUPRICESTABLE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="13" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=seasons"><?php echo JText::translate('VRMENUTENSEVEN'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="12" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=locfees"><?php echo JText::translate('VRMENUTENSIX'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="discounts" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=discounts"><?php echo JText::translate('VRIMENUDISCOUNTS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="ratesoverv" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=ratesoverv"><?php echo JText::translate('VRIMENURATESOVERVIEW'); ?></a></span></li>
						</ul>
					</li><?php
					}
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('calendar-check'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRMENUTHREE'); ?></a></span>
						<ul class="vri-submenu-ul"><?php
						if ($vri_auth_orders) {
						?>
							<li><span class="<?php echo ($highlight=="8" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=orders"><?php echo JText::translate('VRMENUSEVEN'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="19" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=calendar"><?php echo JText::translate('VRIMENUQUICKRES'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="15" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=overv"><?php echo JText::translate('VRMENUTENNINE'); ?></a></span></li><?php
						}
						?><li><span class="<?php echo ($highlight=="18" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems"><?php echo JText::translate('VRIMENUDASHBOARD'); ?></a></span></li>
						</ul>
					</li><?php
					if ($vri_auth_management) {
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('chart-line'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRIMENUMANAGEMENT'); ?></a></span>
						<ul class="vri-submenu-ul">
							<li><span class="<?php echo ($highlight=="customers" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=customers"><?php echo JText::translate('VRIMENUCUSTOMERS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="17" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=coupons"><?php echo JText::translate('VRIMENUCOUPONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="10" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=stats"><?php echo JText::translate('VRMENUEIGHT'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="crons" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=crons"><?php echo JText::translate('VRIMENUCRONS'); ?></a></span></li>
						</ul>
					</li><?php
					}
					if ($vri_auth_gsettings) {
					?><li class="vri-menu-parent-li">
						<span><?php VikRentItemsIcons::e('cogs'); ?> <a href="javascript: void(0);"><?php echo JText::translate('VRMENUFOUR'); ?></a></span>
						<ul class="vri-submenu-ul">
							<li><span class="<?php echo ($highlight=="11" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=config"><?php echo JText::translate('VRMENUTWELVE'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="20" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=translations"><?php echo JText::translate('VRMENUTRANSLATIONS'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="14" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=payments"><?php echo JText::translate('VRMENUTENEIGHT'); ?></a></span></li>
							<li><span class="<?php echo ($highlight=="16" ? "vmenulinkactive" : "vmenulink"); ?>"><a href="index.php?option=com_vikrentitems&amp;task=customf"><?php echo JText::translate('VRMENUTENTEN'); ?></a></span></li>
						</ul>
					</li><?php
					}
					?>
				</ul>
				<div class="vri-menu-updates">
			<?php
			/**
			 * @wponly PRO Version
			 */
			VikRentItemsLoader::import('update.license');
			if (!VikRentItemsLicense::isPro()) {
				?>
					<button type="button" class="vri-gotopro" onclick="document.location.href='admin.php?option=com_vikrentitems&view=gotopro';">
						<?php VikRentItemsIcons::e('rocket'); ?>
						<span><?php echo JText::translate('VRIGOTOPROBTN'); ?></span>
					</button>
				<?php
			} else {
				?>
					<button type="button" class="vri-alreadypro" onclick="document.location.href='admin.php?option=com_vikrentitems&view=gotopro';">
						<?php VikRentItemsIcons::e('trophy'); ?>
						<span><?php echo JText::translate('VRIISPROBTN'); ?></span>
					</button>
				<?php
			}
			?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		var vri_menu_type = <?php echo (int)$cookie->get('vriMenuType', '0', 'string') ?>;
		var vri_menu_on = ((vri_menu_type % 2) == 0);
		//
		function vriDetectMenuChange(e) {
			e = e || window.event;
			if ((e.which == 77 || e.keyCode == 77) && e.altKey) {
				//ALT+M
				vri_menu_type++;
				vri_menu_on = ((vri_menu_type % 2) == 0);
				console.log(vri_menu_type, vri_menu_on);
				//Set Cookie for next page refresh
				var nd = new Date();
				nd.setTime(nd.getTime() + (365*24*60*60*1000));
				document.cookie = "vriMenuType="+vri_menu_type+"; expires=" + nd.toUTCString() + "; path=/; SameSite=Lax";
			}
		}
		document.onkeydown = vriDetectMenuChange;
		//
		jQuery(document).ready(function(){
			jQuery('.vri-menu-parent-li').click(function() {
				if (jQuery(this).find('ul.vri-submenu-ul').is(':visible')) {
					vri_menu_on = false;
					return;
				}
				jQuery('ul.vri-submenu-ul').hide();
				jQuery(this).find('ul.vri-submenu-ul').show();
				vri_menu_on = true;
			});
			jQuery('.vri-menu-parent-li').hover(
				function() {
					if (vri_menu_on === true) {
						jQuery(this).addClass('vri-menu-parent-li-opened');
						jQuery(this).find('ul.vri-submenu-ul').show();
					}
				},function() {
					if (vri_menu_on === true) {
						jQuery(this).removeClass('vri-menu-parent-li-opened');
						jQuery(this).find('ul.vri-submenu-ul').hide();
					}
				}
			);
			var targetY = jQuery('.vri-menu-right').offset().top + jQuery('.vri-menu-right').outerHeight() + 150;
			jQuery(document).click(function(event) { 
				if (!jQuery(event.target).closest('.vri-menu-right').length && parseInt(event.which) == 1 && event.pageY < targetY) {
					jQuery('ul.vri-submenu-ul').hide();
					vri_menu_on = true;
				}
			});

			if (jQuery('.vmenulinkactive').length) {
				jQuery('.vmenulinkactive').parent('li').parent('ul').parent('li').addClass('vri-menu-parent-li-active');
				if ((vri_menu_type % 2) != 0) {
					jQuery('.vmenulinkactive').parent('li').parent('ul').show();
				}
			}
		});
		</script>
		<?php
	}
	
	public static function printFooter() {
		echo '<br clear="all" />' . '<div id="hmfooter">' . JText::sprintf('VRIVERSION', VIKRENTITEMS_SOFTWARE_VERSION) . ' <a href="https://extensionsforjoomla.com/">e4j - Extensionsforjoomla.com</a></div>';
	}
	
	/**
	 * Returns a BS-compatible dropdown menu that submits
	 * the form whenever a value is selected.
	 * 
	 * @param 	array		$arr_values
	 * @param 	string 		$current_key
	 * @param 	string 		$empty_value
	 * @param 	string 		$default
	 * @param 	string 		$input_name
	 *
	 * @return 	string
	 */
	public static function getDropDown($arr_values, $current_key, $empty_value, $default, $input_name) {
		$dropdown = '';
		$x = rand(1, 999);
		if (defined('JVERSION') && version_compare(JVERSION, '2.6.0') < 0) {
			//Joomla 2.5
			$dropdown .= '<select name="'.$input_name.'" onchange="document.adminForm.submit();">'."\n";
			$dropdown .= '<option value="">'.$default.'</option>'."\n";
			$list = "\n";
			foreach ($arr_values as $k => $v) {
				$dropdown .= '<option value="'.$k.'"'.($k == $current_key ? ' selected="selected"' : '').'>'.$v.'</option>'."\n";
			}
			$dropdown .= '</select>'."\n";
		} else {
			//Joomla 3.x
			$dropdown .= '<script type="text/javascript">'."\n";
			$dropdown .= 'function dropDownChange'.$x.'(setval) {'."\n";
			$dropdown .= '	document.getElementById("dropdownval'.$x.'").value = setval;'."\n";
			$dropdown .= '	document.adminForm.submit();'."\n";
			$dropdown .= '}'."\n";
			$dropdown .= '</script>'."\n";
			$dropdown .= '<input type="hidden" name="'.$input_name.'" value="'.$current_key.'" id="dropdownval'.$x.'"/>'."\n";
			$list = "\n";
			foreach ($arr_values as $k => $v) {
				if($k == $current_key) {
					$default = $v;
				}
				$list .= '<li><a href="javascript: void(0);" onclick="dropDownChange'.$x.'(\''.$k.'\');">'.$v.'</a></li>'."\n";
			}
			$list .= '<li class="divider"></li>'."\n".'<li><a href="javascript: void(0);" onclick="dropDownChange'.$x.'(\'\');">'.$empty_value.'</a></li>'."\n";
			$dropdown .= '<div class="btn-group">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">'.$default.' <span class="caret"></span></button>
		<ul class="dropdown-menu" role="menu">'.
			$list.
		'</ul>
	</div>';
		}

		return $dropdown;
	}

	//VikUpdater plugin methods - Start
	public static function pUpdateProgram($version)
	{
		?>
		<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
			<div class="span12">
				<fieldset class="form-horizontal">
					<legend><?php $version->shortTitle ?></legend>
					<div class="control"><strong><?php echo $version->title; ?></strong></div>

					<div class="control" style="margin-top: 10px;">
						<button type="button" class="btn btn-primary" onclick="downloadSoftware(this);">
							<?php echo JText::translate($version->compare == 1 ? 'VRDOWNLOADUPDATEBTN1' : 'VRDOWNLOADUPDATEBTN0'); ?>
						</button>
					</div>

					<div class="control vik-box-error" id="update-error" style="display: none;margin-top: 10px;"></div>

					<?php if ( isset($version->changelog) && count($version->changelog) ) { ?>

						<div class="control vik-update-changelog" style="margin-top: 10px;">

							<?php echo self::digChangelog($version->changelog); ?>

						</div>

					<?php } ?>
				</fieldset>
			</div>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="option" value="com_vikrentitems"/>
		</form>

		<div id="vikupdater-loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999999 !important; background-color: rgba(0,0,0,0.5);">
			<div id="vikupdater-loading-content" style="position: fixed; left: 33.3%; top: 30%; width: 33.3%; height: auto; z-index: 101; padding: 10px; border-radius: 5px; background-color: #fff; box-shadow: 5px 5px 5px 0 #000; overflow: auto; text-align: center;">
				<span id="vikupdater-loading-message" style="display: block; text-align: center;"></span>
				<span id="vikupdater-loading-dots" style="display: block; font-weight: bold; font-size: 25px; text-align: center; color: green;">.</span>
			</div>
		</div>
		
		<script type="text/javascript">
		var isRunning = false;
		var loadingInterval;

		function vikLoadingAnimation() {
			var dotslength = jQuery('#vikupdater-loading-dots').text().length + 1;
			if (dotslength > 10) {
				dotslength = 1;
			}
			var dotscont = '';
			for (var i = 1; i <= dotslength; i++) {
				dotscont += '.';
			}
			jQuery('#vikupdater-loading-dots').text(dotscont);
		}

		function openLoadingOverlay(message) {
			jQuery('#vikupdater-loading-message').html(message);
			jQuery('#vikupdater-loading').fadeIn();
			loadingInterval = setInterval(vikLoadingAnimation, 1000);
		}

		function closeLoadingOverlay() {
			jQuery('#vikupdater-loading').fadeOut();
			clearInterval(loadingInterval);
		}

		function downloadSoftware(btn) {

			if ( isRunning ) {
				return;
			}

			switchRunStatus(btn);
			setError(null);

			var jqxhr = jQuery.ajax({
				url: "index.php?option=com_vikrentitems&task=updateprogramlaunch&tmpl=component",
				type: "POST",
				data: {}
			}).done(function(resp) {

				try {
					var obj = JSON.parse(resp);
				} catch (e) {
					console.log(resp);
					return;
				}
				
				if ( obj === null ) {

					// connection failed. Something gone wrong while decoding JSON
					alert('<?php echo addslashes('Connection Error'); ?>');

				} else if ( obj.status ) {

					document.location.href = 'index.php?option=com_vikrentitems';
					return;

				} else {

					console.log("### ERROR ###");
					console.log(obj);

					if ( obj.hasOwnProperty('error') ) {
						setError(obj.error);
					} else {
						setError('Your website does not own a valid support license!<br />Please visit <a href="https://extensionsforjoomla.com" target="_blank">extensionsforjoomla.com</a> to purchase a license or to receive assistance.');
					}

				}

				switchRunStatus(btn);

			}).fail(function(resp) {
				console.log('### FAILURE ###');
				console.log(resp);
				alert('<?php echo addslashes('Connection Error'); ?>');

				switchRunStatus(btn);
			}); 
		}

		function switchRunStatus(btn) {
			isRunning = !isRunning;

			jQuery(btn).prop('disabled', isRunning);

			if ( isRunning ) {
				// start loading
				openLoadingOverlay('The process may take a few minutes to complete.<br />Please wait without leaving the page or closing the browser.');
			} else {
				// stop loading
				closeLoadingOverlay();
			}
		}

		function setError(err) {

			if ( err !== null && err !== undefined && err.length ) {
				jQuery('#update-error').show();
			} else {
				jQuery('#update-error').hide();
			}

			jQuery('#update-error').html(err);

		}

	</script>
		<?php
	}

	/**
	 * Scan changelog structure.
	 *
	 * @param 	array 	$arr 	The list containing changelog elements.
	 * @param 	mixed 	$html 	The html built. 
	 * 							Specify false to echo the structure immediately.
	 *
	 * @return 	string|void 	The HTML structure or nothing.
	 */
	private static function digChangelog(array $arr, $html = '') {

		foreach ( $arr as $elem ):

			if ( isset($elem->tag) ):

				// build attributes

				$attributes = "";
				if ( isset($elem->attributes) ) {

					foreach ( $elem->attributes as $k => $v ) {
						$attributes .= " $k=\"$v\"";
					}

				}

				// build tag opening

				$str = "<{$elem->tag}$attributes>";

				if ( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

				// display contents

				if ( isset($elem->content) ) {

					if ( $html ) {
						$html .= $elem->content;
					} else {
						echo $elem->content;
					}

				}

				// recursive iteration for elem children

				if ( isset($elem->children) ) {
					self::digChangelog($elem->children, $html);
				}

				// build tag closure

				$str = "</{$elem->tag}>";

				if ( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

			endif;

		endforeach;

		return $html;
	}
	//VikUpdater plugin methods - End

}
