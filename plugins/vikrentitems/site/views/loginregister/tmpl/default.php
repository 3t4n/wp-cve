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

$priceid = $this->priceid;
$place = $this->place;
$returnplace = $this->returnplace;
$elemid = $this->elemid;
$days = $this->days;
$pickup = $this->pickup;
$release = $this->release;
$copts = $this->copts;

$action = 'index.php?option=com_user&amp;task=login';
$vri_app = VikRentItems::getVriApplication();
$pitemid = VikRequest::getString('Itemid', '', 'request');

if (!empty($elemid) && !empty($pickup) && !empty($release)) {
	$chosenopts = "";
	if (is_array($copts) && @count($copts) > 0) {
		foreach ($copts as $idopt => $quanopt) {
			$chosenopts .= "&optid".$idopt."=".$quanopt;
		}
	}
	$goto = "index.php?option=com_vikrentitems&task=oconfirm&priceid=".$priceid."&place=".$place."&returnplace=".$returnplace."&elemid=".$elemid."&days=".$days."&pickup=".$pickup."&release=".$release.(!empty($chosenopts) ? $chosenopts : "").(!empty($pitemid) ? "&Itemid=".$pitemid : "");
	/**
	 * @wponly 	no need to use VikRentItems::getLoginReturnUrl() or we would get a double protocol.
	 */
	$goto = JRoute::rewrite($goto, false);
} else {
	//User Reservations page
	$goto = "index.php?option=com_vikrentitems&view=userorders";
	$goto = JRoute::rewrite($goto, false);
}

$return_url = base64_encode($goto);

?>

<script language="JavaScript" type="text/javascript">
function checkVriReg() {
	var vrvar = document.vrireg;
	if (!vrvar.name.value.match(/\S/)) {
		document.getElementById('vrifname').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrifname').style.color='';
	}
	if (!vrvar.lname.value.match(/\S/)) {
		document.getElementById('vriflname').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vriflname').style.color='';
	}
	if (!vrvar.email.value.match(/\S/)) {
		document.getElementById('vrifemail').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrifemail').style.color='';
	}
	if (!vrvar.username.value.match(/\S/)) {
		document.getElementById('vrifusername').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrifusername').style.color='';
	}
	if (!vrvar.password.value.match(/\S/)) {
		document.getElementById('vrifpassword').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrifpassword').style.color='';
	}
	if (!vrvar.confpassword.value.match(/\S/)) {
		document.getElementById('vrifconfpassword').style.color='#ff0000';
		return false;
	} else {
		document.getElementById('vrifconfpassword').style.color='';
	}
	return true;
}
</script>

<div class="loginregistercont">
		
	<div class="registerblock">
		<form action="<?php echo JRoute::rewrite('index.php?option=com_vikrentitems'); ?>" method="post" name="vrireg" onsubmit="return checkVriReg();">
			<h3><?php echo JText::translate('VRREGSIGNUP'); ?></h3>
			<table valign="top">
				<tr><td align="right"><span id="vrifname"><?php echo JText::translate('VRREGNAME'); ?></span></td><td><input type="text" name="name" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><span id="vriflname"><?php echo JText::translate('VRREGLNAME'); ?></span></td><td><input type="text" name="lname" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><span id="vrifemail"><?php echo JText::translate('VRREGEMAIL'); ?></span></td><td><input type="text" name="email" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><span id="vrifusername"><?php echo JText::translate('VRREGUNAME'); ?></span></td><td><input type="text" name="username" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><span id="vrifpassword"><?php echo JText::translate('VRREGPWD'); ?></span></td><td><input type="password" name="password" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><span id="vrifconfpassword"><?php echo JText::translate('VRREGCONFIRMPWD'); ?></span></td><td><input type="password" name="confpassword" value="" size="20" class="vriinput"/></td></tr>
			<?php
			if ($vri_app->isCaptcha()) {
				?>
				<tr><td></td><td><?php echo $vri_app->reCaptcha(); ?></td></tr>
				<?php
			}
			?>
				<tr><td align="right">&nbsp;</td><td><input type="submit" value="<?php echo JText::translate('VRREGSIGNUPBTN'); ?>" class="btn booknow" name="submit" /></td></tr>
			</table>
			<input type="hidden" name="priceid" value="<?php echo $priceid; ?>" />
			<input type="hidden" name="place" value="<?php echo $place; ?>" />
			<input type="hidden" name="returnplace" value="<?php echo $returnplace; ?>" />
			<input type="hidden" name="elemid" value="<?php echo $elemid; ?>" />
			<input type="hidden" name="days" value="<?php echo $days; ?>" />
			<input type="hidden" name="pickup" value="<?php echo $pickup; ?>" />
			<input type="hidden" name="release" value="<?php echo $release; ?>" />
			<?php
			if (is_array($copts) && @count($copts) > 0) {
				foreach ($copts as $idopt => $quanopt) {
					?>
			<input type="hidden" name="optid<?php echo $idopt; ?>" value="<?php echo $quanopt; ?>" />
					<?php
				}
			}
			?>
			<input type="hidden" name="Itemid" value="<?php echo $pitemid; ?>" />
			<input type="hidden" name="option" value="com_vikrentitems" />
			<input type="hidden" name="task" value="register" />
		</form>
	</div>

<?php

/**
 * @wponly 	use WP login form (change login and password names: "log" and "pwd")
 */
$url = wp_login_url(base64_decode($return_url));
$url .= (strpos($url, '?') !== false ? '&' : '?') . 'action=login';

?>

	<div class="loginblock">
		<form action="<?php echo $url; ?>" method="post">
			<h3><?php echo JText::translate('VRREGSIGNIN'); ?></h3>
			<table valign="top">
				<tr><td align="right"><?php echo JText::translate('VRREGUNAME'); ?></td><td><input type="text" name="log" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right"><?php echo JText::translate('VRREGPWD'); ?></td><td><input type="password" name="pwd" value="" size="20" class="vriinput"/></td></tr>
				<tr><td align="right">&nbsp;</td><td><input type="submit" value="<?php echo JText::translate('VRREGSIGNINBTN'); ?>" class="booknow btn" name="Login" /></td></tr>
			</table>
			<input type="hidden" name="remember" id="remember" value="yes" />
			<?php echo JHtml::fetch('form.token'); ?>
		</form>
	</div>
		
</div>
