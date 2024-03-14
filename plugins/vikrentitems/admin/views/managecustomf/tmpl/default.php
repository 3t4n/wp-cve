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

$row = $this->row;

$vri_app = VikRentItems::getVriApplication();

$choose = "";
if (count($row) && $row['type'] == "select") {
	$x = explode(";;__;;", $row['choose']);
	if (@count($x) > 0) {
		foreach ($x as $y) {
			if (!empty($y)) {
				$choose .= '<div class="vri-customf-sel-added"><input type="text" name="choose[]" value="'.$y.'" size="40"/></div>'."\n";
			}
		}
	}
}
?>
<script type="text/javascript">
function setCustomfChoose (val) {
	if (val == "text") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'flex';
	}
	if (val == "textarea") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'none';
	}
	if (val == "checkbox") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'none';
	}
	if (val == "date") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'none';
	}
	if (val == "select") {
		document.getElementById('customfchoose').style.display = 'block';
		document.getElementById('vrflag').style.display = 'none';
	}
	if (val == "country") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'none';
	}
	if (val == "separator") {
		document.getElementById('customfchoose').style.display = 'none';
		document.getElementById('vrflag').style.display = 'none';
	}
	return true;
}
function addElement() {
	var ni = document.getElementById('customfchooseadd');
	var numi = document.getElementById('theValue');
	var num = (document.getElementById('theValue').value -1)+ 2;
	numi.value = num;
	var newdiv = document.createElement('div');
	var divIdName = 'my'+num+'Div';
	newdiv.setAttribute('id',divIdName);
	newdiv.innerHTML = '<div class=\'vri-customf-sel-added\'><input type=\'text\' name=\'choose[]\' value=\'\' size=\'40\'/></div>';
	ni.appendChild(newdiv);
}
</script>
<input type="hidden" value="0" id="theValue" />

<form name="adminForm" id="adminForm" action="index.php" method="post">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCUSTOMFONE'); ?></div>
							<div class="vri-param-setting"><input type="text" name="name" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCUSTOMFTWO'); ?></div>
							<div class="vri-param-setting">
								<select id="stype" name="type" onchange="setCustomfChoose(this.value);">
									<!-- @wponly lite - only checkbox is supported for terms and conditions -->
									<option value="checkbox"<?php echo (count($row) && $row['type'] == "checkbox" ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRNEWCUSTOMFFIVE'); ?></option>
								</select>
								<div id="customfchoose" style="display: <?php echo (count($row) && $row['type'] == "select" ? "block" : "none"); ?>;">
									<?php
									if ((count($row) && $row['type'] != "select") || !count($row)) {
									?>
									<div class="vri-customf-sel-added"><input type="text" name="choose[]" value="" size="40"/></div>
									<?php
									} else {
										echo $choose;
									}
									?>
									<div id="customfchooseadd" style="display: block;"></div>
									<span><b><a href="javascript: void(0);" onclick="javascript: addElement();"><?php echo JText::translate('VRNEWCUSTOMFNINE'); ?></a></b></span>
								</div>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCUSTOMFSIX'); ?></div>
							<div class="vri-param-setting">
								<?php echo $vri_app->printYesNoButtons('required', JText::translate('VRYES'), JText::translate('VRNO'), (count($row) && intval($row['required']) == 1 ? 1 : 0), 1, 0); ?>
							</div>
						</div>
						<div class="vri-param-container" id="vrflag"<?php echo (count($row) && $row['type'] != "text" ? " style=\"display: none;\"" : ""); ?>>
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCUSTOMFFLAG'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRNEWCUSTOMFFLAG'), 'content' => JText::translate('VRNEWCUSTOMFFLAGHELP'))); ?></div>
							<div class="vri-param-setting">
								<select name="flag">
									<option value=""></option>
									<option value="isemail"<?php echo (count($row) && intval($row['isemail']) == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRNEWCUSTOMFSEVEN'); ?></option>
									<option value="isnominative"<?php echo (count($row) && intval($row['isnominative']) == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISNOMINATIVE'); ?></option>
									<option value="isphone"<?php echo (count($row) && intval($row['isphone']) == 1 ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISPHONENUMBER'); ?></option>
									<option value="isaddress"<?php echo (count($row) && stripos($row['flag'], 'address') !== false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISADDRESS'); ?></option>
									<option value="iscity"<?php echo (count($row) && stripos($row['flag'], 'city') !== false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISCITY'); ?></option>
									<option value="iszip"<?php echo (count($row) && stripos($row['flag'], 'zip') !== false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISZIP'); ?></option>
									<option value="iscompany"<?php echo (count($row) && stripos($row['flag'], 'company') !== false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISCOMPANY'); ?></option>
									<option value="isvat"<?php echo (count($row) && stripos($row['flag'], 'vat') !== false ? " selected=\"selected\"" : ""); ?>><?php echo JText::translate('VRIISVAT'); ?></option>
								</select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWCUSTOMFEIGHT'); ?></div>
							<div class="vri-param-setting">
								<input type="text" name="poplink" value="<?php echo count($row) ? $row['poplink'] : ''; ?>" size="40"/>
								<br/>
								<!-- @wponly we suggest to use a permalink -->
								<small>Eg. <i><?php echo get_site_url(); ?>/link-to-your-terms-page</i></small>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_vikrentitems" />
<?php
if (count($row)) {
	?>
	<input type="hidden" name="where" value="<?php echo $row['id']; ?>">
	<?php
}
?>
	<?php echo JHtml::fetch('form.token'); ?>
</form>
