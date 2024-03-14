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

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
$vri_app = VikRentItems::getVriApplication();
JHtml::fetch('behavior.calendar');

$firstwday = (int)VikRentItems::getFirstWeekDay(true);
$days_labels = array(
	JText::translate('VRISUNDAY'),
	JText::translate('VRIMONDAY'),
	JText::translate('VRITUESDAY'),
	JText::translate('VRIWEDNESDAY'),
	JText::translate('VRITHURSDAY'),
	JText::translate('VRIFRIDAY'),
	JText::translate('VRISATURDAY')
);
$days_indexes = array();
for ($i = 0; $i < 7; $i++) {
	$days_indexes[$i] = (6-($firstwday-$i)+1)%7;
}

$wopening = count($row) && !empty($row['wopening']) ? json_decode($row['wopening'], true) : array();
$wopening = !is_array($wopening) ? array() : $wopening;

$difftime = false;
if (count($row) && !empty($row['opentime'])) {
	$difftime = true;
	$parts = explode("-", $row['opentime']);
	$openat = VikRentItems::getHoursMinutes($parts[0]);
	$closeat = VikRentItems::getHoursMinutes($parts[1]);
}
$hours = "<option value=\"\"> </option>\n";
$hours_ovw = "<option value=\"\"> </option>\n";
for ($i=0; $i <= 23; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$stat = ($difftime == true && (int)$openat[0] == $i ? " selected=\"selected\"" : "");
	$hours .= "<option value=\"".$i."\"".$stat.">".$in."</option>\n";
	$hours_ovw .= "<option value=\"".$i."\" data-val=\";".$i.";\">".$in."</option>\n";
}
$sugghours = "<option value=\"\"> </option>\n";
$defhour = count($row) && !empty($row['defaulttime']) ? ((int)$row['defaulttime'] / 3600) : '';
for ($i=0; $i <= 23; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$stat = (strlen($defhour) && $defhour == $i ? " selected=\"selected\"" : "");
	$sugghours.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
}
$minutes = "<option value=\"\"> </option>\n";
$minutes_ovw = "<option value=\"\"> </option>\n";
for ($i=0; $i <= 59; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$stat = ($difftime == true && (int)$openat[1] == $i ? " selected=\"selected\"" : "");
	$minutes .= "<option value=\"".$i."\"".$stat.">".$in."</option>\n";
	$minutes_ovw .= "<option value=\"".$i."\" data-val=\";".$i.";\">".$in."</option>\n";
}
$hoursto = "<option value=\"\"> </option>\n";
for ($i=0; $i <= 23; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$stat = ($difftime == true && (int)$closeat[0] == $i ? " selected=\"selected\"" : "");
	$hoursto.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
}
$minutesto = "<option value=\"\"> </option>\n";
for ($i=0; $i <= 59; $i++) {
	$in = $i < 10 ? "0".$i : $i;
	$stat = ($difftime == true && (int)$closeat[1] == $i ? " selected=\"selected\"" : "");
	$minutesto.="<option value=\"".$i."\"".$stat.">".$in."</option>\n";
}
$dbo = JFactory::getDbo();
$wiva = "<select name=\"praliq\">\n";
$wiva .= "<option value=\"\"> ------ </option>\n";
$q = "SELECT * FROM `#__vikrentitems_iva`;";
$dbo->setQuery($q);
$dbo->execute();
if ($dbo->getNumRows() > 0) {
	$ivas = $dbo->loadAssocList();
	foreach ($ivas as $iv) {
		$wiva .= "<option value=\"".$iv['id']."\"".(count($row) && $row['idiva'] == $iv['id'] ? " selected=\"selected\"" : "").">".(empty($iv['name']) ? $iv['aliq']."%" : $iv['name']."-".$iv['aliq']."%")."</option>\n";
	}
}
$wiva .= "</select>\n";
?>
<script type="text/javascript">
function vriAddClosingDate() {
	var closingdadd = document.getElementById('insertclosingdate').value;
	var closingdintv = document.getElementById('closingintv').value;
	if (closingdadd.length > 0) {
		document.getElementById('closingdays').value += closingdadd + closingdintv + ',';
		document.getElementById('insertclosingdate').value = '';
		document.getElementById('closingintv').value = '';
	}
}
function vriToggleWopening(mode, ind) {
	if (mode == 'on') {
		// plus button
		jQuery('#vri-wopen-on-'+ind).hide();
		jQuery('#vri-wopen-off-'+ind).fadeIn();
		jQuery('#wopening-'+ind).show();
	} else {
		// minus button
		jQuery('#vri-wopen-off-'+ind).hide();
		jQuery('#vri-wopen-on-'+ind).fadeIn();
		jQuery('#wopening-'+ind).hide().find('select').val('');
	}
}
</script>

<form name="adminForm" id="adminForm" action="index.php" method="post">
	<div class="vri-admin-container">
		<div class="vri-config-maintab-left">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDDETAILS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VREDITPLACEONE'); ?></div>
							<div class="vri-param-setting"><input type="text" name="placename" value="<?php echo count($row) ? htmlspecialchars($row['name']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRILOCADDRESS'); ?></div>
							<div class="vri-param-setting"><input type="text" name="address" value="<?php echo count($row) ? htmlspecialchars($row['address']) : ''; ?>" size="40"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACELAT'); ?></div>
							<div class="vri-param-setting"><input type="text" name="lat" value="<?php echo count($row) ? $row['lat'] : ''; ?>" size="30"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACELNG'); ?></div>
							<div class="vri-param-setting"><input type="text" name="lng" value="<?php echo count($row) ? $row['lng'] : ''; ?>" size="30"/></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACEOVERRIDETAX'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPLACEOVERRIDETAX'), 'content' => JText::translate('VRIPLACEOVERRIDETAXTXT'))); ?></div>
							<div class="vri-param-setting"><?php echo $wiva; ?></div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACEDESCR'); ?></div>
							<div class="vri-param-setting">
								<?php
								if (interface_exists('Throwable')) {
									/**
									 * With PHP >= 7 supporting throwable exceptions for Fatal Errors
									 * we try to avoid issues with third party plugins that make use
									 * of the WP native function get_current_screen().
									 * 
									 * @wponly
									 */
									try {
										echo $editor->display("descr", (count($row) ? $row['descr'] : ''), 400, 200, 70, 20);
									} catch (Throwable $t) {
										echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
									}
								} else {
									// we cannot catch Fatal Errors in PHP 5.x
									echo $editor->display("descr", (count($row) ? $row['descr'] : ''), 400, 200, 70, 20);
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="vri-config-maintab-right">
			<fieldset class="adminform">
				<div class="vri-params-wrap">
					<legend class="adminlegend"><?php echo JText::translate('VRIADMINLEGENDSETTINGS'); ?></legend>
					<div class="vri-params-container">
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACEOPENTIME'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPLACEOPENTIME'), 'content' => JText::translate('VRIPLACEOPENTIMETXT'))); ?></div>
							<div class="vri-param-setting">
								<table style="width: auto !important;">
									<tr>
										<td style="vertical-align: middle;"><?php echo JText::translate('VRIPLACEOPENTIMEFROM'); ?>:</td>
										<td style="vertical-align: middle;"><select style="margin: 0;" name="opentimefh"><?php echo $hours; ?></select></td>
										<td style="vertical-align: middle;">:</td>
										<td style="vertical-align: middle;"><select style="margin: 0;" name="opentimefm"><?php echo $minutes; ?></select></td>
									</tr>
									<tr>
										<td style="vertical-align: middle;"><?php echo JText::translate('VRIPLACEOPENTIMETO'); ?>:</td>
										<td style="vertical-align: middle;"><select style="margin: 0;" name="opentimeth"><?php echo $hoursto; ?></select></td>
										<td style="vertical-align: middle;">:</td>
										<td style="vertical-align: middle;"><select style="margin: 0;" name="opentimetm"><?php echo $minutesto; ?></select></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACESUGGOPENTIME'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPLACESUGGOPENTIME'), 'content' => JText::translate('VRIPLACESUGGOPENTIMETXT'))); ?></div>
							<div class="vri-param-setting">
								<select name="suggopentimeh"><?php echo $sugghours; ?></select>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRIPLACEOVROPENTIME'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRIPLACEOVROPENTIME'), 'content' => JText::translate('VRIPLACEOVROPENTIMEHELP'))); ?></div>
							<div class="vri-param-setting">
								<div class="vri-param-loc-wopening-wrap">
								<?php
								for ($i = 0; $i < 7; $i++) {
									$d_ind = ($i + $firstwday) < 7 ? ($i + $firstwday) : ($i + $firstwday - 7);
									$fhopt = isset($wopening[$d_ind]) ? str_replace('data-val=";'.$wopening[$d_ind]['fh'].';"', 'selected="selected"', $hours_ovw) : $hours_ovw;
									$fmopt = isset($wopening[$d_ind]) ? str_replace('data-val=";'.$wopening[$d_ind]['fm'].';"', 'selected="selected"', $minutes_ovw) : $minutes_ovw;
									$thopt = isset($wopening[$d_ind]) ? str_replace('data-val=";'.$wopening[$d_ind]['th'].';"', 'selected="selected"', $hours_ovw) : $hours_ovw;
									$tmopt = isset($wopening[$d_ind]) ? str_replace('data-val=";'.$wopening[$d_ind]['tm'].';"', 'selected="selected"', $minutes_ovw) : $minutes_ovw;
									?>
									<div class="vri-param-loc-wopening-wday">
										<div class="vri-param-loc-wopening-wday-head">
											<div class="vri-param-loc-wopening-wday-head-inner">
												<span><?php echo $days_labels[$d_ind]; ?></span>
												<a style="display: <?php echo isset($wopening[$d_ind]) ? 'none' : 'inline-block'; ?>;" class="vri-param-loc-toggle-on" href="javascript: void(0);" id="vri-wopen-on-<?php echo $d_ind; ?>" onclick="vriToggleWopening('on', '<?php echo $d_ind; ?>');"><?php VikRentItemsIcons::e('plus-circle'); ?></a>
												<a style="display: <?php echo isset($wopening[$d_ind]) ? 'inline-block' : 'none'; ?>;" class="vri-param-loc-toggle-off" href="javascript: void(0);" id="vri-wopen-off-<?php echo $d_ind; ?>" onclick="vriToggleWopening('off', '<?php echo $d_ind; ?>');"><?php VikRentItemsIcons::e('minus-circle'); ?></a>
											</div>
										</div>
										<div class="vri-param-loc-wopening-wday-override" style="display: <?php echo isset($wopening[$d_ind]) ? 'block' : 'none'; ?>;" id="wopening-<?php echo $d_ind; ?>">
											<div class="vri-param-marginbottom">
												<span class="vrirestrdrangesp"><?php echo JText::translate('VRIPLACEOPENTIMEFROM'); ?></span>
												<span class="vri-param-loc-wopening-override-sels">
													<select style="margin: 0;" name="wopeningfh[<?php echo $d_ind; ?>]"><?php echo $fhopt; ?></select>
													<span class="vri-param-loc-wopening-timesep">:</span>
													<select style="margin: 0;" name="wopeningfm[<?php echo $d_ind; ?>]"><?php echo $fmopt; ?></select>
												</span>
											</div>
											<div class="vri-param-marginbottom">
												<span class="vrirestrdrangesp"><?php echo JText::translate('VRIPLACEOPENTIMETO'); ?></span>
												<span class="vri-param-loc-wopening-override-sels">
													<select style="margin: 0;" name="wopeningth[<?php echo $d_ind; ?>]"><?php echo $thopt; ?></select>
													<span class="vri-param-loc-wopening-timesep">:</span>
													<select style="margin: 0;" name="wopeningtm[<?php echo $d_ind; ?>]"><?php echo $tmopt; ?></select>
												</span>
											</div>
										</div>
									</div>
									<?php
								}
								?>
								</div>
							</div>
						</div>
						<div class="vri-param-container">
							<div class="vri-param-label"><?php echo JText::translate('VRNEWPLACECLOSINGDAYS'); ?> <?php echo $vri_app->createPopover(array('title' => JText::translate('VRNEWPLACECLOSINGDAYS'), 'content' => JText::translate('VRNEWPLACECLOSINGDAYSHELP'))); ?></div>
							<div class="vri-param-setting">
								<?php echo JHtml::fetch('calendar', '', 'insertclosingdate', 'insertclosingdate', '%Y-%m-%d', array('class'=>'', 'size'=>'10',  'maxlength'=>'19', 'todayBtn' => 'true')); ?>
								<span class="vri-loc-closeintv">
									<select id="closingintv">
										<option value=""><?php echo JText::translate('VRNEWPLACECLOSINGDAYSINGLE'); ?></option>
										<option value=":w"><?php echo JText::translate('VRNEWPLACECLOSINGDAYWEEK'); ?></option>
									</select>
								</span> 
								<span class="btn vri-config-btn" onclick="javascript: vriAddClosingDate();"><?php echo JText::translate('VRNEWPLACECLOSINGDAYSADD'); ?></span>
								<textarea name="closingdays" id="closingdays" rows="5" cols="44"><?php echo count($row) ? $row['closingdays'] : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<input type="hidden" name="task" value="">
<?php
if (count($row)) {
?>
	<input type="hidden" name="whereup" value="<?php echo $row['id']; ?>">
<?php
}
?>
	<input type="hidden" name="option" value="com_vikrentitems" />
	<?php echo JHtml::fetch('form.token'); ?>
</form>
