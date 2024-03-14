<?php
/**
 * @package 
 * @author Jason Lau
 * @link http://jasonlau.biz
 * @copyright 2011-2014
 * @license GNU/GPL 3+
 * @uses WordPress
 
Plugin Name: Visitor Maps Extended Referer Field
Plugin URI: http://jasonlau.biz
Description: Extend <a href="http://www.642weather.com/weather/scripts-wordpress-visitor-maps.php" target="_blank">Visitor Maps and Who's Online</a> with extra features, such as IP and referer banning. Display the referring host name and search string.
Author: Jason Lau
Version: 1.2.6
Author URI: http://jasonlau.biz
*/

define("VMERF_VERSION", "1.2.6");
define("VMERF_SLUG", "visitor-maps-extended-referer");
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}


function visitor_maps_extended_ref(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_register_script('visitor_maps_extended_referer', plugins_url('/visitor-maps-extended-referer-field/visitor-maps-extended-referer.js?version='.rand(99,999), dirname(__FILE__)));
    wp_enqueue_script('visitor_maps_extended_referer');
    vmerf_init();
}

function vmerf_init(){
    if(function_exists('load_plugin_textdomain')){
      load_plugin_textdomain('visitor-maps', false, 'visitor-maps/languages/');
    }
    if(get_option("vmerf_version") != VMERF_VERSION){
        vmerf_install();
    }
    $vmerf_output = "<script type=\"text/javascript\">\n";
    $vmerf_htaccess = get_option("vmerf_htaccess");
    if($vmerf_htaccess){
        $vmerf_output .= "var vmerf_htaccess = true, vmerf_htaccess_warning = false, vmerf_message = ''";
    } else {
        if(get_option("vmerf_htaccess_warning")){
            $vmerf_output .= "var vmerf_htaccess = false, vmerf_htaccess_warning = true, vmerf_message = '<h3>Visitor Maps Extended Notice</h3> ";
            if(!file_exists(ABSPATH . ".htaccess")){
                $vmerf_output .= "<p><strong><em>" . ABSPATH . ".htaccess</em></strong> could not be found. IP and referer banning have been disabled.</p><p>To enable IP and referer banning features, be sure <strong><em>" . ABSPATH . ".htaccess</em></strong> exists, then reactivate the <em>Visitor Maps Extended Referer Field</em> plugin.</p><form action=\"#\" method=\"post\"><button title=\"Press to generate a new file - " . ABSPATH . ".htaccess\" type=\"submit\">Create A New .htaccess File Now</button><input type=\"hidden\" name=\"vmerf_mode\" value=\"new htaccess\" /></form><form action=\"#\" method=\"post\"><button title=\"Press to dismiss this warning. Banning features will not be enabled.\" type=\"submit\">Dismiss Message</button><input type=\"hidden\" name=\"vmerf_mode\" value=\"dismiss htaccess warning\" /></form>'";
            } else {
                $vmerf_output .= "" . ABSPATH . ".htaccess could not be copied. IP and referer banning have been disabled. To enable those features, be sure " . ABSPATH . ".htaccess exists, then reactivate the <em>Visitor Maps Extended Referer Field</em> plugin.'";
            }
            
        } else {
            $vmerf_output .= "var vmerf_htaccess = false, vmerf_htaccess_warning = false, vmerf_message = ''";
        }       
    }
    
	$vmerf_output .= ", vmerf_ip = '', vmerf_admin_ip = '" . $_SERVER["REMOTE_ADDR"] . "';\n";
    
    if($_POST['vmerf_mode']){
      switch ($_POST['vmerf_mode']){        
        case "ban referer":
        $vmerf_output .= vmerf_ban_referer();
        break;
        
        case "unban referer":
        $vmerf_output .= vmerf_unban_referer();
        break;
        
        case "purge referers":
        $vmerf_output .= vmerf_purge_referers();
        break;
        
        case "ban":
        $vmerf_output .= vmerf_ban_ip();
        break;
        
        case "unban":
        $vmerf_output .= vmerf_unban_ip();
        break;
        
        case "purge":
        $vmerf_output .= vmerf_purge_ips();
        break;
        
        case "new htaccess":
        $vmerf_output .= vmerf_new_htaccess();
        break;
        
        case "dismiss htaccess warning":
        update_option("vmerf_htaccess_warning", false);
        $vmerf_output .= "vmerf_htaccess = false;\nvmerf_htaccess_warning = false;\n";
        break;
        
        case "set auto update":        
        $vmerf_output .= vmerf_auto_update();
        break;
      }  
    }
    
    $banned_ips = get_option("vmerf_banned_ips");   
    $banned_referers = get_option("vmerf_banned_referers");
    $vmerf_auto_update = (!get_option("vmerf_auto_update")) ? 'false' : get_option("vmerf_auto_update");
    $vmerf_auto_update_time = (!get_option("vmerf_auto_update_time")) ? 5 : get_option("vmerf_auto_update_time");
    $vmerf_output .= "var vmerf_banned_ips = '" . @implode(', ', $banned_ips) . "';
        vmerf_banned_ips = vmerf_banned_ips.split(', ').sort();\n";
    $vmerf_output .= "var vmerf_banned_referers = '" . @implode(', ', $banned_referers) . "';
        vmerf_banned_referers = vmerf_banned_referers.split(', ').sort();\n";
    $vmerf_output .= "var vmerf_admin_host = '" . $_SERVER["HTTP_HOST"] . "', vmerf_auto_update = " . $vmerf_auto_update . ", vmerf_auto_update_time = " . $vmerf_auto_update_time . ", vmerf_table_selector_text = '" . __( 'FIRST', 'visitor-maps' ) . "', vmerf_powered_by_text = '" . __( 'Powered by Visitor Maps', 'visitor-maps' ) . "', vmerf_referer_text = '" . __( 'Referer', 'visitor-maps' ) . "';\n";
    $vmerf_output .= "</script>\n";
    echo $vmerf_output;
}

function vmerf_new_list($referer){
    $new_list = "# BEGIN Referers
<IfModule mod_rewrite.c>
# Cond start
RewriteCond %{HTTP_REFERER} " . str_replace(".", "\.", $referer) . " [NC]
# Cond end
RewriteRule .* - [F]
</IfModule>
# END Referers";
    return $new_list;
}

function vmerf_ban_referer(){
    $vmerf_referer = str_replace("http://", "", str_replace("www.", "", strtolower(trim($_POST['vmerf_referer']))));
    $banned_referers = get_option("vmerf_banned_referers");
    $vmerf_output = "";
    if(in_array($vmerf_referer, $banned_referers)){
        $vmerf_output .= "vmerf_message += '" . $vmerf_referer . " is already banned!';\n";  
    } else {
        array_push($banned_referers, $vmerf_referer);
        update_option("vmerf_banned_referers", $banned_referers);
        vmerf_rebuild_htaccess();
        $vmerf_output .= "vmerf_message += '" . $vmerf_referer . " successfully banned!';\n";
    }
    $vmerf_output .= "var vmerf_mode = 'ban referer';\n";
    return $vmerf_output;  
}

function vmerf_unban_referer(){
    $vmerf_referer = trim($_POST['vmerf_referer']);
    $banned_referers = get_option("vmerf_banned_referers");
    $vmerf_output = "";
    $new_list = array();
    foreach($banned_referers as $referer){
        if($referer != $vmerf_referer) array_push($new_list, $referer);
    }
    update_option("vmerf_banned_referers", $new_list);
    vmerf_rebuild_htaccess();
    $vmerf_output .= "vmerf_message += '" . $vmerf_referer . " successfully unbanned!';\n";
    $vmerf_output .= "var vmerf_mode = 'unban referer';\n";
    return $vmerf_output;  
}

function vmerf_purge_referers(){
    update_option("vmerf_banned_referers", array());
    vmerf_rebuild_htaccess();
    $vmerf_output .= "vmerf_message += 'All referers successfully unbanned!';\n";
    $vmerf_output .= "var vmerf_mode = 'purge referers';\n";
    return $vmerf_output;  
}

function vmerf_ban_ip(){    
    $banned_ips = get_option("vmerf_banned_ips");
    $vmerf_ip = trim($_POST['vmerf_ip']);
    $vmerf_output = "";
    if(in_array($vmerf_ip, $banned_ips)){
        $vmerf_output .= "vmerf_message += '" . $vmerf_ip . " is already banned!';\n";  
    } else {
        array_push($banned_ips, $vmerf_ip);
        update_option("vmerf_banned_ips", $banned_ips); 
        vmerf_rebuild_htaccess();             
        $vmerf_output .= "vmerf_message += '" . $vmerf_ip . " successfully banned!';\n";
    }
    $vmerf_output .= "vmerf_ip += '" . $vmerf_ip . "';\n";   
    return $vmerf_output;  
}

function vmerf_unban_ip(){
    $banned_ips = get_option("vmerf_banned_ips");
    $vmerf_ip = trim($_POST['vmerf_ip']);
    $vmerf_output = "";
    $vmerf_output .= "var vmerf_mode = 'unban';\n";
    $new_list = array();
    foreach($banned_ips as $ip){
        if(trim($ip) != $vmerf_ip){
            array_push($new_list, trim($ip));
        } 
    }
    update_option("vmerf_banned_ips", $new_list);
    vmerf_rebuild_htaccess();
    $vmerf_output .= "vmerf_message += '" . $vmerf_ip . " successfully unbanned!';\n";
    $vmerf_output .= "vmerf_ip += '" . $vmerf_ip . "';\n";
    return $vmerf_output;  
}

function vmerf_purge_ips(){
    update_option("vmerf_banned_ips", array());
    vmerf_rebuild_htaccess();
    $vmerf_output .= "vmerf_message += 'All IP addresses successfully unbanned!';\n";
    return $vmerf_output;  
}

function vmerf_read(){
    $fp = fopen(ABSPATH . ".htaccess", 'rt');
    $content = "";
    while(!feof($fp)){
        $content .= fgets($fp, 4096);
    }
    fclose($fp);
    return $content; 
}

function vmerf_write($content){
    $fp = fopen(ABSPATH . ".htaccess", "w+");
    fwrite($fp, trim($content));
    fclose($fp);
    chmod(ABSPATH . ".htaccess", 0755);
}

function vmerf_del_line_in_file($filename, $text_to_delete){
  $file_array = array();	
  $file = fopen($filename, 'rt');
  if($file){
    while(!feof($file)){
      $val = fgets($file);
      if(is_string($val))
        array_push($file_array, $val);
    }	
    fclose($file);
  }
  for($i = 0; $i < count($file_array); $i++){
    if(eregi($text_to_delete, $file_array[$i])){
      if(($file_array[$i] == $text_to_delete . "\n") || ($file_array[$i] == $text_to_delete)) $file_array[$i] = '';
    }
  }
  $content = trim(implode("", $file_array));
  if($text_to_delete == "all referers"){
    $all_referers = substr($content, stripos($content,"# BEGIN Referers"),stripos($content,"# END Referers"));
    $content = trim(str_replace($all_referers, "", $content));
    update_option("vmerf_referers", array());
  }
  
  $file_write = fopen($filename, 'wt');	
  if($file_write){
    fwrite($file_write, $content);
    fclose($file_write);
  }
}


function vmerf_create_random($number_of_digits = 1, $type = 3){
    // type: 1 - numeric, 2 - letters, 3 - mixed, 4 - all ascii chars.
    for ($x = 0; $x < $number_of_digits; $x++) {
        while (substr($num, strlen($num) - 1, strlen($num)) == $r) {
            switch ($type) {
                case "1":
                    $r = rand(0, 9);
                    break;

                case "2":
                    $n = rand(0, 999);
                    if ($n % 2) {
                        $r = chr(rand(0, 25) + 65);
                    } else {
                        $r = strtolower(chr(rand(0, 25) + 65));
                    }
                    break;

                case "3":
                    if (is_numeric(substr($num, strlen($num) - 1, strlen($num)))) {
                        $n = rand(0, 999);
                        if ($n % 2) {
                            $r = chr(rand(0, 25) + 65);
                        } else {
                            $r = strtolower(chr(rand(0, 25) + 65));
                        }
                    } else {
                        $r = rand(0, 9);
                    }
                    break;
                    
                    case "4":
                    if (is_numeric(substr($num, strlen($num) - 1, strlen($num)))) {
                        $n = rand(0, 999);
                        if ($n % 2) {
                            $r = chr(rand(33, 231));
                        } else {
                            $r = strtolower(chr(rand(33, 231)));
                        }
                    } else {
                        $r = rand(33, 231);
                    }                   
                    break;
            }
        }
        $num .= $r;
    }
    return $num;
}

function vmerf_install(){
    global $wp_version;
    if(!is_dir(plugin_dir_path('visitor-maps'))){
        wp_die("<strong>Notice:</strong> The <a href=\"http://wordpress.org/extend/plugins/visitor-maps/\" target=\"_blank\">Visitor Maps and Who's Online</a> plugin must be installed before installing <em>Visitor Maps Extended Referer Field</em>.");
    }
    
    $vmerf_htbackup = get_option("vmerf_htbackup", false);
    $vmerf_banned_ips = get_option("vmerf_banned_ips", array());
    $vmerf_banned_referers = get_option("vmerf_banned_referers", array());
    $vmerf_auto_update = get_option("vmerf_auto_update",false);
    $vmerf_auto_update_time = get_option('vmerf_auto_update_time', 5);
    
    if(!$vmerf_htbackup){
       $htbackup = ABSPATH . ".htaccess.backup." . vmerf_create_random(6);
       update_option("vmerf_htbackup", $htbackup);
       if(!vmerf_backup_htaccess($htbackup)){
        update_option("vmerf_htaccess_warning", true);
        update_option("vmerf_htaccess", false);
       } else {
        update_option("vmerf_htaccess_warning", false);
        update_option("vmerf_htaccess", true);
       }
    } else {
        update_option("vmerf_htaccess_warning", false);
        update_option("vmerf_htaccess", true);
    }
    
    /* Backwards Compatibility */
    if(!is_array($vmerf_banned_ips) && strlen($vmerf_banned_ips) > 0){
        $ips = explode(", ", $vmerf_banned_ips);
        update_option("vmerf_banned_ips", $ips);
    } else {
        update_option("vmerf_banned_ips", $vmerf_banned_ips);
    }
    
    if(!is_array($vmerf_banned_referers) && strlen($vmerf_banned_referers) > 0){
        $referers = explode(", ", $vmerf_banned_referers);
        update_option("vmerf_banned_referers", $referers);
    } else {
        update_option("vmerf_banned_referers", $vmerf_banned_referers);
    }
    /* /Backwards Compatibility */
    
    update_option("vmerf_wp_version", $wp_version);
    update_option("vmerf_version", VMERF_VERSION);
    update_option("vmerf_auto_update", $vmerf_auto_update);
    update_option("vmerf_auto_update_time", $vmerf_auto_update_time);            
}

function vmerf_backup_htaccess($htbackup){
    if(!copy(ABSPATH . ".htaccess", $htbackup)){
        return false;
    } else {
        return true;
    }
}

function vmerf_new_htaccess(){
    $vmerf_output = "";
    if(!file_exists(ABSPATH . ".htaccess")){
       if($new_htaccess = fopen(ABSPATH . ".htaccess", 'w')){
        fclose($new_htaccess);
        update_option("vmerf_htaccess_warning", false);
        $vmerf_output .= "vmerf_message = '<strong><em>" . ABSPATH . ".htaccess</em></strong> successfully created! <strong>Deactivate and reactivate the <em>Visitor Maps Extended Referer Field</em> plugin now.</strong>';\n";
       } else {
        $vmerf_output .= "vmerf_message = '<strong><em>" . ABSPATH . ".htaccess</em></strong> could not be created for some reason!';\n";
       }       
    }
    return $vmerf_output;
}

function vmerf_auto_update(){
    $vmerf_auto_update = trim($_POST['vmerf_auto_update']);
    $vmerf_auto_update_time = (trim($_POST['vmerf_auto_update_time']) == '') ? 5 : trim($_POST['vmerf_auto_update_time']);
    settype($vmerf_auto_update_time, "integer"); 
    $vmerf_auto_update_time = ($vmerf_auto_update_time < 1) ? 1 : $vmerf_auto_update_time; 
    update_option("vmerf_auto_update", $vmerf_auto_update);
    update_option("vmerf_auto_update_time", $vmerf_auto_update_time);
    $vmerf_output = "vmerf_message += 'Auto-Update settings successfully updated.';\n";
    return $vmerf_output;  
}

function vmerf_deactivate(){
    $vmerf_settings = get_option("vmerf_settings");
    if(!is_array($vmerf_settings)) 
       $vmerf_settings = array('preserve_data' => true);
    $preserve_data = intval($vmerf_settings['preserve_data']);
    if(!$preserve_data):
     $vmerf_htbackup = get_option("vmerf_htbackup");
       copy(ABSPATH . ".htaccess", $vmerf_htbackup); 
       update_option("vmerf_banned_ips", array());
       update_option("vmerf_banned_referers", array());
       vmerf_rebuild_htaccess();  
       delete_option("vmerf_wp_version");
       delete_option("vmerf_banned_ips");
       delete_option("vmerf_banned_referers");
       delete_option("vmerf_htbackup");
       delete_option("vmerf_auto_update");
       delete_option("vmerf_auto_update_time");
       delete_option("vmerf_settings");
    endif;    
}

function vmerf_help($contextual_help, $screen_id, $screen) {
    if($screen->id != 'dashboard_page_whos-been-online')
    return;
    $vmerf_contextual_help_tabs = array(array('Visitor Maps Extended Settings', vmerf_settings_form())); 
    $x = 1;
    foreach($vmerf_contextual_help_tabs as $tab):
        $screen->add_help_tab(array(
          'id'	=> 'vmerf_help_tab_' . $x,
          'title'	=> __($tab[0]),
          'content'	=> __($tab[1])
        ));
        $x++;
    endforeach;  
}

function vmerf_settings_form(){
    $output = '<h2>' . __( 'Visitor Maps Extended Settings', VMERF_SLUG) . '</h2>' . "\n";
    //wp_die($_REQUEST['vmerf_update_settings']);
    $updated = false;
    if(isset($_REQUEST['vmerf_update_settings'])):   
       $vmerf_settings = array('preserve_data' => $_REQUEST['vmerf_preserve_data']);
       update_option("vmerf_settings", $vmerf_settings);
       $output .= '<div class="updated below-h2" style="padding: 10px 4px; margin:10px 0px;">Settings successfully updated!</div>' . "\n";
    $updated = true;
    endif;
    $vmerf_settings = get_option("vmerf_settings");
    if(empty($vmerf_settings) || !is_array($vmerf_settings)) 
       $vmerf_settings = array('preserve_data' => 1);
    $preserve_data = intval($vmerf_settings['preserve_data']);
    switch($preserve_data){
        case 0:
        $preserve_yes = '';
        $preserve_no = ' selected="selected"';
        break;
        
        default:
        $preserve_yes = ' selected="selected"';
        $preserve_no = '';
    }
    $output .= '<form id="vmerf_update_settings_form" action="#" method="post" data-updated="' . $updated . '"><table class="vmerf-settings-table widefat vmerf-rounded-corners">
<tr class="alternate">
	<td>
        <strong>' . __('Preserve Saved Data On Uninstall', VMERF_SLUG) . '</strong><br />
        <select size="1" name="vmerf_preserve_data">
	<option value="1"' . $preserve_yes . '>' . __('Yes', VMERF_SLUG) . '</option>
	<option value="0"' . $preserve_no . '>' . __('No', VMERF_SLUG) . '</option>
</select><br /><span>' . __('Select <em>No</em> to remove all traces of this plugin from the database when uninstalled. All saved data will be lost forever.', VMERF_SLUG) . '</span></td>
</tr>
<tr>
	<td><input data-form="vmerf_update_settings_form" data-subpage="vmerf_update_settings" data-wait="' . __('Wait...', VMERF_SLUG) . '" class="button-primary vmerf-update-settings-submit" type="submit" style="margin: 10px 5px;" value="' . __('Update Settings', VMERF_SLUG) . '"><input name="vmerf_update_settings" type="hidden" value="true">
 </td>
</tr>
</table>
        </form>';
return $output;   
}

function vmerf_rebuild_htaccess($backup=true){
    if($backup){
        $htbackup = get_option("vmerf_htbackup");
        vmerf_backup_htaccess($htbackup);
    }
    $htcontent = vmerf_read();
    if(@eregi("Visitor Maps Extended", $htcontent)){
        $part1 = explode("# BEGIN Visitor Maps Extended", $htcontent);
        $part2 = explode("# END Visitor Maps Extended", $htcontent);        
        $htpart1 = trim($part1[0]);
        $htpart2 = trim($part2[1]);        
    } else {
      $htpart1 = trim($htcontent);
      $htpart2 = "";
    }
    $banned_referers = get_option("vmerf_banned_referers");
    $banned_ips = get_option("vmerf_banned_ips");
    $new_htcontent = "\n# BEGIN Visitor Maps Extended";
    if($banned_referers[0] != ""){
        $new_htcontent .= "\n# BEGIN Referers
<IfModule mod_rewrite.c>
# Uncomment 'Options +FollowSymlinks' if your server returns a '500 Internal Server' error.
# This means your server is not configured with FollowSymLinks in the '' section of the 'httpd.conf'.
# Contact your system administrator for advice with this issue.
# Options +FollowSymlinks
# Cond start ";
    
    foreach($banned_referers as $referer){
        $referer = str_replace("http://", "", str_replace("www.", "", strtolower(trim($referer))));
        if($referer != ""){           
            if(count($banned_referers) < 2){
                $new_htcontent .= "\nRewriteCond %{HTTP_REFERER} " . str_replace(".", "\.", $referer) . " [NC]";
            } else {
                $new_htcontent .= "\nRewriteCond %{HTTP_REFERER} " . str_replace(".", "\.", $referer) . " [NC,OR]";
            }
        }
    }
    if(count($banned_referers) != 1){
        $new_htcontent = trim($new_htcontent, "[NC,OR]");
    }
    $new_htcontent .= "\n# Cond end
RewriteRule .* - [F]
</IfModule>
# END Referers";
    }
    if($banned_ips[0] != ""){
      $new_htcontent .= "\n# BEGIN banned ips
order allow,deny";
    foreach($banned_ips as $ip){
        if(trim($ip) != ""){
            $new_htcontent .= "\ndeny from " . $ip;  
        }          
    }
    $new_htcontent .= "\nallow from all
# END banned ips";  
    }    
    $new_htcontent .= "\n# END Visitor Maps Extended\n";
    if(count($banned_referers) == 0 && count($banned_ips) == 0){
        $new_htcontent = "";
    }
    vmerf_write($htpart1 . $new_htcontent . $htpart2);
}

if(is_dir(plugin_dir_path('visitor-maps')) && ($_REQUEST['page'] == "whos-been-online")){
    add_action('admin_enqueue_scripts', 'visitor_maps_extended_ref'); 
    add_filter('contextual_help', 'vmerf_help', 10, 3);
}

register_activation_hook(__FILE__, 'vmerf_install');
register_deactivation_hook(__FILE__, 'vmerf_deactivate');

?>