<?php
/*
Plugin Name: Collapsible Archive Widget
Plugin URI: http://www.romantika.name/v2/wordpress-plugin-collapsible-archive-widget/
Description: Display Collapsible Archive in yous sidebar to save space.
Version: 2.3.1
Author: Ady Romantika
Author URI: http://www.romantika.name/v2/
*/

/*
	Copyright 2007-2009  ADY ROMANTIKA  (ady AT romantika DOT name)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function ara_collapsiblearchive($before,$after)
{
	global $wpdb, $ara_collapsible_icons;
	$options = (array) get_option('widget_ara_collapsiblearchive');
	$title = $options['title'];
	$count = $options['count'] ? 1 : 0;
	$mcount = $options['mcount'] ? 1 : 0;
	$abbr = $options['abbr'] ? 1 : 0;
	$scriptaculous = $options['scriptaculous'] ? 1 : 0;
	$effectexpand = $options['effectexpand'] ? $options['effectexpand'] : 1;
	$effectcollapse = $options['effectcollapse'] ? $options['effectcollapse'] : 1;
	$defaultexpand = $options['defaultexpand'] ? 1 : 0;
	$expandcurryear = $options['expandcurryear'] ? 1 : 0;
	$htmlarrows = $options['htmlarrows'] ? 1 : 0;

	# years
	$years	= ara_collapsiblearchive_get_archivesbyyear();

	# Header
	$string_to_echo  =  ($before.__($title).$after."\n");
	?>
	<script type="text/javascript">
	//<!--
	<?php
	if($scriptaculous > 0) # Only do this if scriptaculous is selected
	{
		switch($effectexpand)
		{
			case 1: $effxp = 'Appear'; break;
			case 2: $effxp = 'BlindDown'; break;
			case 3: $effxp = 'SlideDown'; break;
			case 4: $effxp = 'Grow'; break;
		}

		switch($effectcollapse)
		{
			case 1: $effcl = 'Fade'; break;
			case 2: $effcl = 'Puff'; break;
			case 3: $effcl = 'BlindUp'; break;
			case 4: $effcl = 'SwitchOff'; break;
			case 5: $effcl = 'SlideUp'; break;
			case 6: $effcl = 'DropOut'; break;
			case 7: $effcl = 'Squish'; break;
			case 8: $effcl = 'Fold'; break;
			case 9: $effcl = 'Shrink'; break;
		}

		?>
		<?php
			# Be kind to those with older WordPress versions
			if(!function_exists('wp_enqueue_script')):
		?>
			<script src="<?php echo get_settings('home'); ?>/wp-includes/js/scriptaculous/prototype.js" type="text/javascript"></script>
			<script src="<?php echo get_settings('home'); ?>/wp-includes/js/scriptaculous/effects.js" type="text/javascript"></script>
		<?php
			endif;
		?>

		collapsiblearchive_toggle = function(listelement,visible,listsign)
		{
			(visible == false ?
				new Effect.<?php echo $effxp ?>(document.getElementById(listelement)) :
				new Effect.<?php echo $effcl ?>(document.getElementById(listelement))
				);
			var sign = document.getElementById(listsign);
			collapsiblearchive_togglesign(sign, visible);
			visible = (visible == false ? true : false);
			return visible;
		}
		<?php
	}
	else
	{
	?>

		collapsiblearchive_toggle = function(listelement, listsign)
		{
			var listobject = document.getElementById(listelement);
			var sign = document.getElementById(listsign);
			if(listobject.style.display == 'block')
			{
				listobject.style.display = 'none';
				collapsiblearchive_togglesign(sign, true);
			}
			else
			{
				listobject.style.display = 'block';
				collapsiblearchive_togglesign(sign, false);
			}
		}
	<?php
	}
	?>
		collapsiblearchive_togglesign = function(element,visibility)
		{
			(visibility == false ? element.innerHTML = '<?php print $ara_collapsible_icons['minus'][$htmlarrows] ?>' : element.innerHTML = '<?php print $ara_collapsible_icons['plus'][$htmlarrows] ?>');
		}

	// -->
	</script>
	<?php

	list($parentOpen, $parentClose, $lineStart, $lineEnd, $childOpen, $childClose, $preappend, $parentPreOpen, $parentPreClose) = ara_collapsiblearchive_getlisttype();

	$string_to_echo .= $parentPreOpen;
	for ($x=0;$x<count($years);$x++ )
	{
		if (strlen($parentOpen) > 0 ) $string_to_echo .= $parentOpen;
		$icon = ($defaultexpand || ($expandcurryear && date("Y") == $years[$x]->year)) ? $ara_collapsible_icons['minus'][$htmlarrows] : $ara_collapsible_icons['plus'][$htmlarrows];
		$year_link = get_year_link($years[$x]->year);
		if($scriptaculous > 0)
		{
			?><script type="text/javascript">var visible_<?php echo $years[$x]->year ?> = <?php echo (($defaultexpand || ($expandcurryear && date("Y") == $years[$x]->year)) ? 'true' : 'false') ?>;</script><?php
			$string_to_echo .= <<<EOB
				<a style="cursor:pointer;" onclick="visible_{$years[$x]->year} = collapsiblearchive_toggle('ara_ca_mo{$years[$x]->year}',visible_{$years[$x]->year},'ara_ca_mosign{$years[$x]->year}')">
EOB;
		}
		else
		{
			$string_to_echo .= <<<EOB
				<a style="cursor:pointer;" onclick="collapsiblearchive_toggle('ara_ca_mo{$years[$x]->year}','ara_ca_mosign{$years[$x]->year}')">
EOB;
		}
		$string_to_echo .= "<span id=\"ara_ca_mosign{$years[$x]->year}\">$icon</span></a><a href=\"$year_link\">{$years[$x]->year}</a>";
		if($count > 0) $string_to_echo .= '&nbsp;('.$years[$x]->posts.')';
		$string_to_echo .= $childOpen.' id="ara_ca_mo'.$years[$x]->year.'" style="display:'.(($defaultexpand || ($expandcurryear && date("Y") == $years[$x]->year)) ? 'block' : 'none').'">';
		$string_to_echo .= ara_collapsiblearchive_get_archivesbymonth($years[$x]->year,$count,$lineStart.$preappend,$lineEnd,$abbr);
		$string_to_echo .= $childClose;
		if (strlen($parentClose) > 0) $string_to_echo .= $parentClose;
	}
	$string_to_echo .= $parentPreClose;

	return $string_to_echo;
}

function ara_collapsiblearchive_get_archivesbymonth($year, $count, $before, $after, $abbr)
{
	global $wpdb, $wp_locale, $ara_collapsible_icons;
	$options = (array) get_option('widget_ara_collapsiblearchive');
	$scriptaculous = $options['scriptaculous'] ? 1 : 0;
	$defaultexpand = $options['defaultexpand'] ? 1 : 0;
	$expandcurrmonth = $options['expandcurrmonth'] ? 1 : 0;
	$show_individual_posts = $options['showposts'] ? 1 : 0;
	$count = $options['count'] ? 1 : 0;
	$mcount = $options['mcount'] ? 1 : 0;
	$htmlarrows = $options['htmlarrows'] ? 1 : 0;
	$highlightcurrmonth = $options['highlightcurrmonth'] ? 1 : 0;
	$monthhideyear = $options['monthhideyear'] ? 1 : 0;

	$monthresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts"
		. " FROM $wpdb->posts"
		. " WHERE $wpdb->posts.post_status = 'publish'"
		. " AND $wpdb->posts.post_type = 'post'"
		. " AND YEAR(post_date) = $year"
		. " GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC");

	$result_string = '';

	foreach ($monthresults as $month)
	{
		$url = get_month_link($year, $month->month);
		if($show_individual_posts > 0)
		{
			$icon = ($defaultexpand || ($expandcurrmonth && date("Y") == $year && date("n") == $month->month)) ? $ara_collapsible_icons['minus'][$htmlarrows] : $ara_collapsible_icons['plus'][$htmlarrows];
			if($scriptaculous > 0)
			{
				$add_before = '<script type="text/javascript">var visible_'.$year.$month->month.' = '.(($defaultexpand || ($expandcurrmonth && date("Y") == $year && date("n") == $month->month)) ? 'true' : 'false').'</script>';
				$add_before .= <<<EOB
					<a style="cursor:pointer;" onclick="visible_$year{$month->month} = collapsiblearchive_toggle('ara_ca_po$year{$month->month}',visible_$year{$month->month},'ara_ca_posign$year{$month->month}')">
EOB;
			}
			else
			{
				$add_before = <<<EOB
					<a style="cursor:pointer;" onclick="collapsiblearchive_toggle('ara_ca_po$year{$month->month}','ara_ca_posign$year{$month->month}')">
EOB;
			}
			$add_before .= "<span id=\"ara_ca_posign$year{$month->month}\">$icon</span></a>";
		}
		else $add_before = '';
		$monthname = ($abbr > 0 ? $wp_locale->get_month_abbrev($wp_locale->get_month($month->month)) : $wp_locale->get_month($month->month));
		if($monthhideyear > 0)
			$text = ($highlightcurrmonth > 0 && date("nY") == $month->month.$year ? '<strong>' : '') . sprintf(__('%1$s'), $monthname) . ($highlightcurrmonth > 0 && date("nY") == $month->month.$year ? '</strong>' : '');
		else
			$text = ($highlightcurrmonth > 0 && date("nY") == $month->month.$year ? '<strong>'.$month->month : '') . sprintf(__('%1$s %2$d'), $monthname, $year) . ($highlightcurrmonth > 0 && date("nY") == $month->month.$year ? '</strong>' : '');
		if ($mcount > 0) $aftertext = '&nbsp;('.$month->posts.')' . $after;
		else $aftertext = $after;
		$result_string .= get_archives_link($url, $text, 'custom', $before.$add_before, $aftertext);

		if($show_individual_posts)
		{
			$result_string .= ara_collapsiblearchive_get_postsbymonth($year, $month->month, '', '');
			$result_string .= $after;
		}
	}

	return $result_string;
}

function ara_collapsiblearchive_get_postsbymonth($year, $month, $before, $after)
{
	global $wpdb;
	$options = (array) get_option('widget_ara_collapsiblearchive');
	$defaultexpand = $options['defaultexpand'] ? 1 : 0;
	$expandcurrmonth = $options['expandcurrmonth'] ? 1 : 0;

    if (empty($year) || empty($month)) {
        return null;
    }

   	list($parentOpen, $parentClose, $lineStart, $lineEnd, $childOpen, $childClose, $preappend, $parentPreOpen, $parentPreClose) = ara_collapsiblearchive_getlisttype();

	$postresults = $wpdb->get_results("SELECT ID, post_title, post_name"
        . " FROM $wpdb->posts"
        . " WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'post'"
        . " AND YEAR(post_date) = $year AND MONTH(post_date) = $month"
        . " ORDER BY post_date DESC");

	$result_string = '';

	$sectionstart = true;

	foreach ($postresults as $post)
	{
		if($sectionstart)
		{
			$result_string .= $lineStart.$childOpen.' id="ara_ca_po'.$year.$month.'" style="display:'.(($defaultexpand || ($expandcurrmonth && date("Yn") == $year.$month)) ? 'block' : 'none').'">';
			$sectionstart = false;
		}
		$url  = get_permalink($post->ID);
		$result_string .= $lineStart.'<a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a>'.$lineEnd;
	}

	$result_string .= $childClose;

	return $result_string;
}

function ara_collapsiblearchive_get_archivesbyyear() {
	global $wpdb;

	$yearresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, count(ID) as posts"
		. " FROM $wpdb->posts"
		. " WHERE $wpdb->posts.post_status = 'publish'"
		. " AND $wpdb->posts.post_type = 'post'"
		. " GROUP BY YEAR(post_date) ORDER BY post_date DESC");

	return $yearresults;
}

function widget_ara_collapsiblearchive_control() {
	$options = $newoptions = get_option('widget_ara_collapsiblearchive');
	if ( $_POST['collapsiblearchive-submit'] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['collapsiblearchive-title']));
		$newoptions['count'] = isset($_POST['collapsiblearchive-count']);
		$newoptions['mcount'] = isset($_POST['collapsiblearchive-mcount']);
		$newoptions['abbr'] = isset($_POST['collapsiblearchive-monthabbr']);
		$newoptions['scriptaculous'] = isset($_POST['collapsiblearchive-scriptaculous']);
		$newoptions['effectexpand'] = $_POST['collapsiblearchive-effectexpand'];
		$newoptions['effectcollapse'] = $_POST['collapsiblearchive-effectcollapse'];
		$newoptions['showposts'] = $_POST['collapsiblearchive-showposts'];
		$newoptions['defaultexpand'] = $_POST['collapsiblearchive-defaultexpand'];
		$newoptions['expandcurryear'] = $_POST['collapsiblearchive-expandcurryear'];
		$newoptions['expandcurrmonth'] = $_POST['collapsiblearchive-expandcurrmonth'];
		$newoptions['linktoplugin'] = $_POST['collapsiblearchive-linktoplugin'];
		$newoptions['htmlarrows'] = $_POST['collapsiblearchive-htmlarrows'];
		$newoptions['highlightcurrmonth'] = $_POST['collapsiblearchive-highlightcurrmonth'];
		$newoptions['monthhideyear'] = $_POST['collapsiblearchive-monthhideyear'];
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_ara_collapsiblearchive', $options);
	}
	$count = $options['count'] ? 'checked="checked"' : '';
	$mcount = $options['mcount'] ? 'checked="checked"' : '';
	$abbr = $options['abbr'] ? 'checked="checked"' : '';
	$showposts = $options['showposts'] ? 'checked="checked"' : '';
	$defaultexpand = $options['defaultexpand'] ? 'checked="checked"' : '';
	$expandcurryear = $options['expandcurryear'] ? 'checked="checked"' : '';
	$expandcurrmonth = $options['expandcurrmonth'] ? 'checked="checked"' : '';
	$scriptaculous = $options['scriptaculous'] ? 'checked="checked"' : '';
	$linktoplugin = $options['linktoplugin'] ? 'checked="checked"' : '';
	$htmlarrows = $options['htmlarrows'] ? 'checked="checked"' : '';
	$highlightcurrmonth = $options['highlightcurrmonth'] ? 'checked="checked"' : '';
	$monthhideyear = $options['monthhideyear'] ? 'checked="checked"' : '';
?>
			<div>
			<p>
				<label for="collapsiblearchive-title"><?php _e('Widget title:', 'widgets'); ?>
					<input class="widefat" type="text" id="collapsiblearchive-title" name="collapsiblearchive-title" value="<?php echo ($options['title'] ? wp_specialchars($options['title'], true) : 'Archives'); ?>" />
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-count">
					<input class="checkbox" type="checkbox" <?php echo $count; ?> id="collapsiblearchive-count" name="collapsiblearchive-count" />
					<?php _e('Show post counts for year'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-mcount">
					<input class="checkbox" type="checkbox" <?php echo $mcount; ?> id="collapsiblearchive-mcount" name="collapsiblearchive-mcount" />
					<?php _e('Show post counts for month'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-monthabbr">
					<input class="checkbox" type="checkbox" <?php echo $abbr; ?> id="collapsiblearchive-monthabbr" name="collapsiblearchive-monthabbr" />
					<?php _e('Abbreviate month names'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-monthhideyear">
					<input class="checkbox" type="checkbox" <?php echo $monthhideyear; ?> id="collapsiblearchive-monthhideyear" name="collapsiblearchive-monthhideyear" />
					<?php _e('Hide year from month names'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-scriptaculous">
					<input class="checkbox" type="checkbox" <?php echo $scriptaculous; ?> id="collapsiblearchive-scriptaculous" name="collapsiblearchive-scriptaculous" onChange="var slc1 = document.getElementById('collapsiblearchive-effectexpand'); var slc2 = document.getElementById('collapsiblearchive-effectcollapse'); if(this.checked) { slc1.disabled = false; slc2.disabled = false; } else { slc1.disabled = true; slc2.disabled = true; }" />
					<?php _e('Use script.aculo.us effects'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-effectexpand">
					<?php _e('Expand Effect:', 'widgets'); ?>
						<select class="widefat" id="collapsiblearchive-effectexpand" name="collapsiblearchive-effectexpand" <?php if($scriptaculous == '') echo 'disabled'; ?>>
							<option value="1"<?php if ($options['effectexpand'] == '1') echo ' selected' ?>>Appear</option>
							<option value="2"<?php if ($options['effectexpand'] == '2') echo ' selected' ?>>BlindDown</option>
							<option value="3"<?php if ($options['effectexpand'] == '3') echo ' selected' ?>>SlideDown</option>
							<option value="4"<?php if ($options['effectexpand'] == '4') echo ' selected' ?>>Grow</option>
						</select>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-effectcollapse">
					<?php _e('Collapse Effect:', 'widgets'); ?>
						<select class="widefat" id="collapsiblearchive-effectcollapse" name="collapsiblearchive-effectcollapse" <?php if($scriptaculous == '') echo 'disabled'; ?>>
							<option value="1"<?php if ($options['effectcollapse'] == '1') echo ' selected' ?>>Fade</option>
							<option value="2"<?php if ($options['effectcollapse'] == '2') echo ' selected' ?>>Puff</option>
							<option value="3"<?php if ($options['effectcollapse'] == '3') echo ' selected' ?>>BlindUp</option>
							<option value="4"<?php if ($options['effectcollapse'] == '4') echo ' selected' ?>>SwitchOff</option>
							<option value="5"<?php if ($options['effectcollapse'] == '5') echo ' selected' ?>>SlideUp</option>
							<option value="6"<?php if ($options['effectcollapse'] == '6') echo ' selected' ?>>DropOut</option>
							<option value="7"<?php if ($options['effectcollapse'] == '7') echo ' selected' ?>>Squish</option>
							<option value="8"<?php if ($options['effectcollapse'] == '8') echo ' selected' ?>>Fold</option>
							<option value="9"<?php if ($options['effectcollapse'] == '9') echo ' selected' ?>>Shrink</option>
						</select>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-defaultexpand">
					<input class="checkbox" type="checkbox" <?php echo $defaultexpand; ?> id="collapsiblearchive-defaultexpand" name="collapsiblearchive-defaultexpand" />
					<?php _e('Expand the list by default'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-expandcurryear">
					<input class="checkbox" type="checkbox" <?php echo $expandcurryear; ?> id="collapsiblearchive-expandcurryear" name="collapsiblearchive-expandcurryear" />
					<?php _e('Expand current year by default'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-expandcurrmonth">
					<input class="checkbox" type="checkbox" <?php echo $expandcurrmonth; ?> id="collapsiblearchive-expandcurrmonth" name="collapsiblearchive-expandcurrmonth" />
					<?php _e('Expand current month by default'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-showposts">
					<input class="checkbox" type="checkbox" <?php echo $showposts; ?> id="collapsiblearchive-showposts" name="collapsiblearchive-showposts" />
					<?php _e('Show individual posts (<a target="_blank" href="http://www.romantika.name/v2/wordpress-plugin-collapsible-archive-widget/#showpostswarning">Warning</a>)'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-htmlarrows">
					<input class="checkbox" type="checkbox" <?php echo $htmlarrows; ?> id="collapsiblearchive-htmlarrows" name="collapsiblearchive-htmlarrows" />
					<?php _e('Use HTML arrows instead of images'); ?> (&#9658; &#9660;)
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-linktoplugin">
					<input class="checkbox" type="checkbox" <?php echo $linktoplugin; ?> id="collapsiblearchive-linktoplugin" name="collapsiblearchive-linktoplugin" />
					<?php _e('Show a link to plugin page. Thank you for your support!'); ?>
				</label>
			</p>
			<p>
				<label for="collapsiblearchive-highlightcurrmonth">
					<input class="checkbox" type="checkbox" <?php echo $highlightcurrmonth; ?> id="collapsiblearchive-highlightcurrmonth" name="collapsiblearchive-highlightcurrmonth" />
					<?php _e('Show current month in bold'); ?>
				</label>
			</p>
			<input type="hidden" name="collapsiblearchive-submit" id="collapsiblearchive-submit" value="1" />
			</div>
<?php
}

function ara_collapsiblearchive_getlisttype()
{
	$parentOpen	=	"\n\t<li>";
	$parentClose=	"\n\t</li>";
	$lineStart	=	"\n\t\t\t<li>";
	$lineEnd	=	"</li>";
	$childOpen	=   "\n\t\t<ul";
	$childClose	=	"\n\t\t</ul>";
	$preappend	=	'';
	$parentPreOpen	=	"\n<ul>";
	$parentPreClose	=	"\n</ul>";
	return array($parentOpen, $parentClose, $lineStart, $lineEnd, $childOpen, $childClose, $preappend, $parentPreOpen, $parentPreClose);
}

function ara_collapsiblearchive_microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function widget_ara_collapsiblearchive_init() {

	global $ara_collapsible_icons;

	$ara_collapsible_icons['plus'][0] = '<img src="'.WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)).'/plus.png" alt="" />&nbsp;';
	$ara_collapsible_icons['minus'][0] = '<img src="'.WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)).'/minus.png" alt="" />&nbsp;';

	$ara_collapsible_icons['plus'][1] = '&#9658;&nbsp;';
	$ara_collapsible_icons['minus'][1] = '&#9660;&nbsp;';

	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// This prints the widget
	function widget_ara_collapsiblearchive($args) {
		$options = (array) get_option('widget_ara_collapsiblearchive');
		$linktoplugin = $options['linktoplugin'] ? 1 : 0;
		extract($args);
		$start = ara_collapsiblearchive_microtime_float();
		echo $before_widget;
		echo "\n\t".'<!-- Collapsible Archive Widget starts here -->'."\n";
		echo ara_collapsiblearchive($before_title, $after_title);
		if($linktoplugin)
		{
			print "\n\t".'<div style="text-align:center; padding-top: 5px; "><em><a title="Powered by Collapsible Archive Widget" href="http://www.romantika.name/v2/wordpress-plugin-collapsible-archive-widget/">Collapsible Archive</a></em></div>';
		}
		echo $after_widget;
		echo "\n\t".'<!-- Collapsible Archive Widget ends here -->';
		$end = ara_collapsiblearchive_microtime_float();
		echo "\n\t".'<!-- Time taken for the Collapsible Archive Widget plugin to complete loading is '.round($end - $start,3).' seconds -->'."\n";
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Collapsible Archive', 'widgets'), 'widget_ara_collapsiblearchive');
	register_widget_control(array('Collapsible Archive', 'widgets'), 'widget_ara_collapsiblearchive_control', 300, 300);
}

function widget_ara_collapsiblearchive_enqueue()
{
	$options = (array) get_option('widget_ara_collapsiblearchive');
	$scriptaculous = $options['scriptaculous'] ? 1 : 0;
	if(function_exists('wp_enqueue_script') && $scriptaculous > 0) wp_enqueue_script('scriptaculous-effects');
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_ara_collapsiblearchive_enqueue');
add_action('widgets_init', 'widget_ara_collapsiblearchive_init');

?>