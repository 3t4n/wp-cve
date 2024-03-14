<?php
    /* 
	    This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License, version 2, as 
        published by the Free Software Foundation.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */  

add_action('admin_init', 'wphcu_init');
add_action('admin_menu', 'wphcu_menu');
                                      
function wphcu_init() {
	register_setting('bd-hit-counter-group', 'wphcu_exclude_ips',  'iplist');
    register_setting('bd-hit-counter-group', 'wphcu_display_footer', 'intval');
    register_setting('bd-hit-counter-group', 'wphcu_display_credit', 'intval');
    register_setting('bd-hit-counter-group', 'wphcu_style');
    register_setting('bd-hit-counter-group', 'wphcu_data', 'intval');
    register_setting('bd-hit-counter-group', 'wphcu_align');
    register_setting('bd-hit-counter-group', 'wphcu_count_only_unique', 'intval');}

function iplist($list) {
	$ips = split("\n",$list);
	
	foreach($ips as $ip) {
		if($ip=="") {
			continue;
		}
		
		if(preg_match("/(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b)/",$ip)) {
			$return_list[] = $ip;
		}
	}

	if(is_array($return_list)) {	
		return join("\n",$return_list);
	} else {
		return true;
	}
}

function file_array($path, $exclude = ".|..|.svn|.DS_Store", $recursive = true) {
    $path = rtrim($path, "/") . "/";
    $folder_handle = opendir($path) or die("Eof");
    $exclude_array = explode("|", $exclude);
    $result = array();
    while(false !== ($filename = readdir($folder_handle))) {
        if(!in_array(strtolower($filename), $exclude_array)) {
            if(is_dir($path . $filename . "/")) {
                if($recursive) $result[] = file_array($path . $filename . "/", $exclude, true);
            } else {
                if ($filename === '0.gif') {
                    if (!$done[$path]) {
                        $result[] = $path;
                        $done[$path] = 1;
                    }
                }
            }
        }
    }
    return $result;
}

function wphcu_menu() {
  add_options_page('Hit Counter Max Options', 'Hit Counter Max', 'administrator', 'hit-counter-max', 'wphcu_options');
}

function wphcu_options() {
    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"><br></div>
    <h2>Hit Counter Max Settings</h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'bd-hit-counter-group' ); ?>
        <?php
            $data = file_array(WP_CONTENT_DIR . '/plugins/hit-counter-max/designs/');
            foreach ($data as $parent_folder => $records) {
                foreach ($records as $style_folder => $style_records) {
                    foreach ($style_records as $style => $test) {
                        preg_match('/designs\/(.*?)\/(.*?)\//', $test, $match);
                        $groups[$match[1]][] = $match[2];
                    }
                }
            }
        ?>
        <h3>Main Settings</h3>
        <table width="100%">
        <tr>
        	<td>
        <table class="form-table">
            <tr valign="top" style="background-color:#eee;">
            <th scope="row">Set hit counter to</th>
            <td><input type="text" name="wphcu_data" value=""<?php echo get_option('wphcu_data') ?>" /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Exclude IP addresses</th>
            <td><textarea name="wphcu_exclude_ips" rows="3" cols="20"><?php echo get_option('wphcu_exclude_ips') ?></textarea></td>
            </tr>
            <tr valign="top" style="background-color:#eee;">
            <th scope="row">Display in Footer?</th>
            <td><input type="checkbox" name="wphcu_display_footer" value="1" <?php echo checked('1', get_option('wphcu_display_footer')) ?> /></td>
            </tr>
        </table>
        </td>
        <td>
        <table class="form-table">
        	 <tr valign="top" style="background-color:#eee;">
            <th scope="row">Count unique visitors</th>
            <td><input type="checkbox" name="wphcu_count_only_unique" value="1" <?php echo checked('1', get_option('wphcu_count_only_unique')) ?> /></td>
            </tr>
            <tr valign="top">
            <th scope="row">Counter Alignment</th>
            <td>
                <select name="wphcu_align">
                    <option value="">None</option>
                    <option <?php if (get_option('wphcu_align')==='left') { echo 'selected'; }?>>left</option>
                    <option <?php if (get_option('wphcu_align')==='center') { echo 'selected'; } ?>>center</option>
                    <option <?php if (get_option('wphcu_align')==='right') { echo 'selected'; } ?>>right</option>
                </select>
            </td>
            </tr>
            <tr valign="top">
            <th scope="row">Author credit <small>(link will be displayed under the hit counter)</small></th>
            <td><input type="checkbox" name="wphcu_display_credit" value="1" <?php echo checked('1', get_option('wphcu_display_credit')) ?> /></td>
            </tr>
            <tr valign="top" style="background-color:#eee;">
            <th scope="row">Check for updates automatically</th>
            <td><input type="checkbox" name="wphcu_check_update" value="1" <?php echo checked('1', get_option('wphcu_check_update')) ?> /></td>
            </tr>
            <tr valign="top" style="display:none;">
            <th scope="row">CSS Customize</th>
            <td><textarea name="wphcu_css" rows="7" cols="70"><?php echo get_option('wphcu_css') ?></textarea></td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
        <br/><br/>
        <h3>Counter designs</h3>
        <table cellpadding="8" width="100%" style="border:1px solid #ccc;"><tr valign="top">
        <?php
            foreach ($groups as $style_name => $style) {
?>
<td style="border:1px solid #ccc;"><h3 style="background-color:#eee;margin:0;padding:2px 5px"><?php echo $style_name; ?></h3>
<table class="form-table">
    <?php
                foreach ($style as $name) {
                    ?>
                	<tr>
                		<td>
                		<input type="radio" id="img1" name="wphcu_style" value="<?php echo $style_name . '/' . $name; ?>" <?php echo checked($style_name . '/' . $name, get_option('wphcu_style')) ?> />
                		<img src='<?php echo WP_PLUGIN_URL?>/hit-counter-max/designs/<?php echo $style_name . '/' . $name . '/'; ?>1.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/hit-counter-max/designs/<?php echo $style_name . '/' . $name . '/'; ?>2.gif'>
                		<img src='<?php echo WP_PLUGIN_URL?>/hit-counter-max/designs/<?php echo $style_name . '/' . $name . '/'; ?>3.gif'>
                		</td>
                	</tr>
                    <?php
                }
    ?>
</table>
<?php echo '</td>';
            }
        ?>
        </tr></table>
        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Update Changes') ?>" />
        </p>
    </form>
    </div>
    <?php
}
?>