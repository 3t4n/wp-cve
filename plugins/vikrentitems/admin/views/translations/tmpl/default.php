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

$vri_tn = $this->vri_tn;

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));
$langs = $vri_tn->getLanguagesList();
$xml_tables = $vri_tn->getTranslationTables();
$active_table = '';
$active_table_key = '';
if (!(count($langs) > 1)) {
	//Error: only one language is published. Translations are useless
	?>
	<p class="err"><?php echo JText::translate('VRITRANSLATIONERRONELANG'); ?></p>
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_vikrentitems">
	</form>
	<?php
} elseif (!(count($xml_tables) > 0) || strlen($vri_tn->getError())) {
	//Error: XML file not readable or errors occurred
	?>
	<p class="err"><?php echo $vri_tn->getError(); ?></p>
	<form name="adminForm" id="adminForm" action="index.php" method="post">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_vikrentitems">
	</form>
	<?php
} else {
	$cur_langtab = VikRequest::getString('vri_lang', '', 'request');
	$table = VikRequest::getString('vri_table', '', 'request');
	if (!empty($table)) {
		$table = $vri_tn->replacePrefix($table);
	}
?>
<script type="text/Javascript">
var vri_tn_changes = false;
jQuery(document).ready(function() {
	jQuery('#adminForm input[type=text], #adminForm textarea').change(function() {
		vri_tn_changes = true;
	});
});
function vriCheckChanges() {
	if (!vri_tn_changes) {
		return true;
	}
	return confirm("<?php echo addslashes(JText::translate('VRITANSLATIONSCHANGESCONF')); ?>");
}
</script>
<form action="index.php?option=com_vikrentitems&amp;task=translations" method="post" onsubmit="return vriCheckChanges();">
	<div style="width: 100%; display: inline-block;" class="btn-toolbar vri-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-right">
			<button class="btn" type="submit"><?php echo JText::translate('VRIGETTRANSLATIONS'); ?></button>
		</div>
		<div class="btn-group pull-right">
			<select name="vri_table">
				<option value="">-----------</option>
			<?php
			foreach ($xml_tables as $key => $value) {
				$active_table = $vri_tn->replacePrefix($key) == $table ? $value : $active_table;
				$active_table_key = $vri_tn->replacePrefix($key) == $table ? $key : $active_table_key;
				?>
				<option value="<?php echo $key; ?>"<?php echo $vri_tn->replacePrefix($key) == $table ? ' selected="selected"' : ''; ?>><?php echo $value; ?></option>
				<?php
			}
			?>
			</select>
		</div>
	</div>
	<input type="hidden" name="vri_lang" class="vri_lang" value="<?php echo $vri_tn->default_lang; ?>">
	<input type="hidden" name="option" value="com_vikrentitems" />
	<input type="hidden" name="task" value="translations" />
</form>
<form name="adminForm" id="adminForm" action="index.php" method="post">
	<div class="vri-translation-langtabs">
<?php
foreach ($langs as $ltag => $lang) {
	$is_def = ($ltag == $vri_tn->default_lang);
	$lcountry = substr($ltag, 0, 2);
	/**
	 * @wponly 	we cannot take the country flag from the native modules
	 */
	$flag = '';
		?><div class="vri-translation-tab<?php echo $is_def ? ' vri-translation-tab-default' : ''; ?>" data-vrilang="<?php echo $ltag; ?>">
		<?php
		if (!empty($flag)) {
			?>
			<span class="vri-translation-flag"><?php echo $flag; ?></span>
			<?php
		}
		?>
			<span class="vri-translation-langname"><?php echo $lang['name']; ?></span>
		</div><?php
}
/**
 * @wponly  removed tab for .INI status
 */
?>
	</div>
	<div class="vri-translation-tabscontents">
<?php
$table_cols = !empty($active_table_key) ? $vri_tn->getTableColumns($active_table_key) : array();
$table_def_dbvals = !empty($active_table_key) ? $vri_tn->getTableDefaultDbValues($active_table_key, array_keys($table_cols)) : array();
if (!empty($active_table_key)) {
	echo '<input type="hidden" name="vri_table" value="'.$active_table_key.'"/>'."\n";
}
foreach ($langs as $ltag => $lang) {
	$is_def = ($ltag == $vri_tn->default_lang);
	?>
		<div class="vri-translation-langcontent" style="display: <?php echo $is_def ? 'block' : 'none'; ?>;" id="vri_langcontent_<?php echo $ltag; ?>">
	<?php
	if (empty($active_table_key)) {
		?>
			<p class="warn"><?php echo JText::translate('VRITRANSLATIONSELTABLEMESS'); ?></p>
		<?php
	} elseif (strlen($vri_tn->getError()) > 0) {
		?>
			<p class="err"><?php echo $vri_tn->getError(); ?></p>
		<?php
	} else {
		?>
			<fieldset class="adminform">
				<legend class="adminlegend"><?php echo $active_table; ?> - <?php echo $lang['name'].($is_def ? ' - '.JText::translate('VRITRANSLATIONDEFLANG') : ''); ?></legend>
				<div class="vri-translations-tab-container">
	<?php
	if ($is_def) {
		//Values of Default Language to be translated
		foreach ($table_def_dbvals as $reference_id => $values) {
			?>
					<div class="vri-translations-default-element">
						<div class="vri-translations-element-title" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
							<div class="vri-translate-element-cell"><?php echo $vri_tn->getRecordReferenceName($table_cols, $values); ?></div>
						</div>
						<div class="vri-translations-element-contents">
			<?php
			foreach ($values as $field => $def_value) {
				$title = $table_cols[$field]['jlang'];
				$type = $table_cols[$field]['type'];
				if ($type == 'html') {
					$def_value = strip_tags($def_value);
				}
				?>
							<div class="vri-translations-element-row" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
								<div class="vri-translations-element-lbl"><?php echo $title; ?></div>
								<div class="vri-translations-element-val"><?php echo $type != 'json' ? $def_value : ''; ?></div>
							</div>
				<?php
				if ($type == 'json') {
					$tn_keys = $table_cols[$field]['keys'];
					$keys = !empty($tn_keys) ? explode(',', $tn_keys) : array();
					$json_def_values = json_decode($def_value, true);
					if (count($json_def_values) > 0) {
						foreach ($json_def_values as $jkey => $jval) {
							if ((!in_array($jkey, $keys) && count($keys) > 0) || empty($jval)) {
								continue;
							}
							?>
							<div class="vri-translations-element-row vri-translations-element-row-nested" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
								<div class="vri-translations-element-lbl"><?php echo !is_numeric($jkey) ? ucwords($jkey) : '&nbsp;'; ?></div>
								<div class="vri-translations-element-val"><?php echo $jval; ?></div>
							</div>
							<?php
						}
					}
				}
				?>
				<?php
			}
			?>
						</div>
					</div>
			<?php
		}
	} else {
		//Translation Fields for this language
		$lang_record_tn = $vri_tn->getTranslatedTable($active_table_key, $ltag);
		foreach ($table_def_dbvals as $reference_id => $values) {
			?>
					<div class="vri-translations-language-element">
						<div class="vri-translations-element-title" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
							<div class="vri-translate-element-cell"><?php echo $vri_tn->getRecordReferenceName($table_cols, $values); ?></div>
						</div>
						<div class="vri-translations-element-contents">
			<?php
			foreach ($values as $field => $def_value) {
				$title = $table_cols[$field]['jlang'];
				$type = $table_cols[$field]['type'];
				if ($type == 'skip') {
					continue;
				}
				$tn_value = '';
				$tn_class = ' vri-missing-translation';
				if (array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content']) && strlen($lang_record_tn[$reference_id]['content'][$field])) {
					if (in_array($type, array('text', 'textarea', 'html'))) {
						$tn_class = ' vri-field-translated';
					} else {
						$tn_class = '';
					}
				}
				?>
							<div class="vri-translations-element-row<?php echo $tn_class; ?>" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
								<div class="vri-translations-element-lbl"><?php echo $title; ?></div>
								<div class="vri-translations-element-val">
						<?php
						if ($type == 'text') {
							if (array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
								$tn_value = $lang_record_tn[$reference_id]['content'][$field];
							}
							?>
									<input type="text" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>]" value="<?php echo htmlentities($tn_value); ?>" size="40" placeholder="<?php echo htmlentities($def_value); ?>"/>
							<?php
						} elseif ($type == 'textarea') {
							if (array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
								$tn_value = $lang_record_tn[$reference_id]['content'][$field];
							}
							?>
									<textarea name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>]" rows="5" cols="40"><?php echo $tn_value; ?></textarea>
							<?php
						} elseif ($type == 'html') {
							if (array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
								$tn_value = $lang_record_tn[$reference_id]['content'][$field];
							}
							if (interface_exists('Throwable')) {
								/**
								 * With PHP >= 7 supporting throwable exceptions for Fatal Errors
								 * we try to avoid issues with third party plugins that make use
								 * of the WP native function get_current_screen().
								 * 
								 * @wponly
								 */
								try {
									echo $editor->display( "tn[".$ltag."][".$reference_id."][".$field."]", $tn_value, '100%', 350, 70, 20, true, "tn_".$ltag."_".$reference_id."_".$field );
								} catch (Throwable $t) {
									echo $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . '<br/>';
								}
							} else {
								// we cannot catch Fatal Errors in PHP 5.x
								echo $editor->display( "tn[".$ltag."][".$reference_id."][".$field."]", $tn_value, '100%', 350, 70, 20, true, "tn_".$ltag."_".$reference_id."_".$field );
							}
						}
						?>
								</div>
							</div>
				<?php
				if ($type == 'json') {
					$tn_keys = $table_cols[$field]['keys'];
					$keys = !empty($tn_keys) ? explode(',', $tn_keys) : array();
					$json_def_values = json_decode($def_value, true);
					if (count($json_def_values) > 0) {
						$tn_json_value = array();
						if (array_key_exists($reference_id, $lang_record_tn) && array_key_exists($field, $lang_record_tn[$reference_id]['content'])) {
							$tn_json_value = json_decode($lang_record_tn[$reference_id]['content'][$field], true);
						}
						foreach ($json_def_values as $jkey => $jval) {
							if ((!in_array($jkey, $keys) && count($keys) > 0) || empty($jval)) {
								continue;
							}
							?>
							<div class="vri-translations-element-row vri-translations-element-row-nested" data-reference="<?php echo $ltag.'-'.$reference_id; ?>">
								<div class="vri-translations-element-lbl"><?php echo !is_numeric($jkey) ? ucwords($jkey) : '&nbsp;'; ?></div>
								<div class="vri-translations-element-val">
								<?php
								if (strlen($jval) > 40) {
								?>
									<textarea rows="5" cols="170" style="min-width: 60%;" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>][<?php echo $jkey; ?>]"><?php echo isset($tn_json_value[$jkey]) ? $tn_json_value[$jkey] : ''; ?></textarea>
								<?php
								} else {
								?>
									<input type="text" name="tn[<?php echo $ltag; ?>][<?php echo $reference_id; ?>][<?php echo $field; ?>][<?php echo $jkey; ?>]" value="<?php echo isset($tn_json_value[$jkey]) ? $tn_json_value[$jkey] : ''; ?>" size="40"/>
								<?php
								}
								?>
								</div>
							</div>
							<?php
						}
					}
				}
			}
			?>
						</div>
					</div>
			<?php
		}
	}
	?>
				</div>
			</fieldset>
		<?php
		//echo '<pre>'.print_r($table_def_dbvals, true).'</pre><br/>';
		//echo '<pre>'.print_r($table_cols, true).'</pre><br/>';
	}
	?>
		</div>
	<?php
}
	/**
	 * @wponly  removed contents for .INI status
	 */
	?>
	</div>
	<input type="hidden" name="vri_lang" class="vri_lang" value="<?php echo $vri_tn->default_lang; ?>">
	<input type="hidden" name="task" value="translations">
	<input type="hidden" name="option" value="com_vikrentitems" />
	<?php echo JHtml::_('form.token'); ?>
	<br/>
	<table align="center">
		<tr>
			<td align="center"><?php echo $vri_tn->getPagination(); ?></td>
		</tr>
		<tr>
			<td align="center">
				<select name="limit" onchange="document.adminForm.limitstart.value = '0'; document.adminForm.submit();">
					<option value="2"<?php echo $vri_tn->lim == 2 ? ' selected="selected"' : ''; ?>>2</option>
					<option value="5"<?php echo $vri_tn->lim == 5 ? ' selected="selected"' : ''; ?>>5</option>
					<option value="10"<?php echo $vri_tn->lim == 10 ? ' selected="selected"' : ''; ?>>10</option>
					<option value="20"<?php echo $vri_tn->lim == 20 ? ' selected="selected"' : ''; ?>>20</option>
				</select>
			</td>
		</tr>
	</table>
</form>
<script type="text/Javascript">
jQuery(document).ready(function() {
	jQuery('.vri-translation-tab').click(function() {
		var langtag = jQuery(this).attr('data-vrilang');
		if (jQuery('#vri_langcontent_'+langtag).length) {
			jQuery('.vri_lang').val(langtag);
			jQuery('.vri-translation-tab').removeClass('vri-translation-tab-default');
			jQuery(this).addClass('vri-translation-tab-default');
			jQuery('.vri-translation-langcontent').hide();
			jQuery('#vri_langcontent_'+langtag).fadeIn();
		} else {
			jQuery('.vri-translation-tab').removeClass('vri-translation-tab-default');
			jQuery(this).addClass('vri-translation-tab-default');
			jQuery('.vri-translation-langcontent').hide();
			jQuery('#vri_langcontent_ini').fadeIn();
		}
	});
<?php
if (!empty($cur_langtab)) {
	?>
	jQuery('.vri-translation-tab').each(function() {
		var langtag = jQuery(this).attr('data-vrilang');
		if (langtag != '<?php echo $cur_langtab; ?>') {
			return true;
		}
		if (jQuery('#vri_langcontent_'+langtag).length) {
			jQuery('.vri_lang').val(langtag);
			jQuery('.vri-translation-tab').removeClass('vri-translation-tab-default');
			jQuery(this).addClass('vri-translation-tab-default');
			jQuery('.vri-translation-langcontent').hide();
			jQuery('#vri_langcontent_'+langtag).fadeIn();
		}
	});
	<?php
}
?>
});
</script>
<?php
}
