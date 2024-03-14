<?php
/*
Plugin Name: WP User Stylesheet Switcher
Version: v2.2.0
Plugin URI: http://wordpress.org/plugins/wp-user-stylesheet-switcher/
Author: Stéphane Groleau
Author URI: http://web.globulesverts.org
Description: Adds a list of stylesheets in the frontend to allow visitors to choose a different stylesheet.
Text Domain: wp-user-stylesheet-switcher

LICENSE
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
//~ error_reporting(E_ALL);
//~ ini_set('error_reporting', E_ALL);
//~ ini_set('display_errors',1);

class WPUserStylesheetSwitcher {

	/*
	 * Counts the number of valid options
	 * 
	 * */
	function count_options($options)
	{
		$nb = 0;
		foreach ($options as $option)
			if (($option["name"] != "") && ($option["file"] != ""))
				$nb++;
		return $nb;
	}
	
	/*
	 * Adjust the path following the admin option
	 * 
	 * */
	function get_path($switcherId, $fileName)
	{
		$settings = $this->get_wp_user_stylesheet_settings();
		if (isset($settings['switchers'][$switcherId]['path'])) {
			if ($settings['switchers'][$switcherId]['path'] == "relative")
				$fileName = get_stylesheet_directory_uri().'/'.$fileName;
		} else
			$fileName = get_stylesheet_directory_uri().'/'.$fileName;
		return $fileName;
	}

	/*
	 * Get settings and check version. Update settings if necessary.
	 * 
	 * */
	function get_wp_user_stylesheet_settings()
	{
		$settings = get_option('wp_user_stylesheet_switcher_settings');
		
		if (!isset($settings['version']))
			$settings['version'] = 0;
		
		if (!isset($settings['switchers']) || (version_compare($settings['version'], "2.0.0") < 0)) {
			// For version prior to 2.0.0
			//echo "<h3><em>".__("--> Plugin updated", "wp-user-stylesheet-switcher")."</em></h3>";
			
			if (isset ($_SESSION['user_stylesheet_switcher']))
				unset ($_SESSION['user_stylesheet_switcher']);
			
			$newSettings['switchers']['s0']['title'] = $settings['title'];
			foreach ($settings['options'] as $key => $option) {
				if (!isset($option['icon'])) {
					$NewOption = array(
						'name' => $option['name'],
						'file' => $option['file'],
						'icon' => ''
					);
					$newSettings['switchers']['s0']['options'][$key] = $NewOption;
				} else
					$newSettings['switchers']['s0']['options'][$key] = $option;
			}
			
			if (!isset($settings['path']))
				$newSettings['switchers']['s0']['path'] = 'relative';
			else
				$newSettings['switchers']['s0']['path'] = $settings['path'];
				
			if (!isset($settings['remove']))
				$newSettings['switchers']['s0']['remove'] = "-1";
			else
				$newSettings['switchers']['s0']['remove'] = $settings['remove'];
			
			if (!isset($settings['reset']))
				$newSettings['switchers']['s0']['reset'] = "-1";
			else
				$newSettings['switchers']['s0']['reset'] = $settings['reset'];
			
			if (!isset($settings['button_icon_file']))
				$newSettings['switchers']['s0']['button_icon_file'] = "";
			else
				$newSettings['switchers']['s0']['button_icon_file'] = $settings['button_icon_file'];
			
			if (!isset($settings['rotation']))
				$newSettings['switchers']['s0']['rotation'] = 'none';
			else
				$newSettings['switchers']['s0']['rotation'] = $settings['rotation'];
				
			if (isset($settings['wp_user_stylesheet_switcher_default']))
				$newSettings['switchers']['s0']['default'] = $settings["wp_user_stylesheet_switcher_default"];
			else
				$newSettings['switchers']['s0']['default'] = 0;
				
			$newSettings['switcherLastKey'] = 0;
			
			$newSettings['version'] = WP_USER_STYLESHEET_SWITCHER_VERSION;
			$settings = $newSettings;
			update_option('wp_user_stylesheet_switcher_settings', $settings);
		}
		
		return $settings;
	}

	/*
	 * Adds the selected stylesheet file to the header and enqueue javascript files
	 * 
	 * */
	public function wp_user_stylesheet_switcher_addcss()
	{
		$settings = $this->get_wp_user_stylesheet_settings();
		
		wp_enqueue_script('wp_user_stylesheet_switcher_script_cookies', plugins_url().'/wp-user-stylesheet-switcher/js/js.cookie.js');
		
		$sessionData = array();
		$removeStyle = false;
		if (isset($_COOKIE["wp_user_stylesheet_switcher_js"])) {
			$sessionData = json_decode(stripslashes($_COOKIE["wp_user_stylesheet_switcher_js"]), true);		
		} else {
			wp_enqueue_script(
					'wp-user_stylesheet_switcher_use_cookie_when_ready', 
					plugins_url().'/wp-user-stylesheet-switcher/js/use_cookie_when_ready.js',
					array('jquery')
				);
		}
		foreach ($settings['switchers'] as $switcherId => $switcherData) {
			if (isset ($sessionData[$switcherId])) {
				$stylesheet_choice = intval($sessionData[$switcherId]);
			}
			else {
				$stylesheet_choice = intval($switcherData['default']);
			}
		
			if (isset($switcherData['rotation'])) {
				if ($switcherData['rotation'] != 'none') {
					$nb_choices = $this->count_options($switcherData['options']);	
					switch ($switcherData['rotation']) {
						case "weekday" : 
							$stylesheet_choice = $stylesheet_choice + date( 'w', current_time( 'timestamp' ) );
							if ($stylesheet_choice >= $nb_choices)
								$stylesheet_choice %= $nb_choices;
							break;
						case "week" :
							$diff_week = abs(date( 'W', current_time( 'timestamp' ) ) - date( 'W', $switcherData['save_date'] ));
							$stylesheet_choice = $stylesheet_choice + $diff_week;
							if ($stylesheet_choice >= $nb_choices)
								$stylesheet_choice %= $nb_choices;
							break;
						case "month" : 
							$diff_month = abs(date( 'm', current_time( 'timestamp' ) ) - date( 'm', $switcherData['save_date'] ));
							$stylesheet_choice = $stylesheet_choice + $diff_month;
							if ($stylesheet_choice >= $nb_choices)
								$stylesheet_choice %= $nb_choices;
							break;
						case "season" : 
							// What is today's date - number
							$current_day = date( 'z', current_time( 'timestamp' ) );

							//  Days of spring
							$spring = date("z", strtotime("March 21"));
							$summer = date("z", strtotime("June 21"));
							$autumn = date("z", strtotime("September 23"));
							$winter = date("z", strtotime("December 21"));

							if( $current_day >= $spring && $current_day <= $summer ) :
							   $season = 0; // "spring"
							elseif( $current_day >= $summer && $current_day < $autumn ) :
							   $season = 1; // "summer"
							elseif( $current_day >= $autumn && $current_day < $winter ) :
							   $season = 2; // "autumn"
							else :
							   $season = 3; // "winter"
							endif;
							
							$stylesheet_choice = $stylesheet_choice + $season;
							if ($stylesheet_choice >= $nb_choices)
								$stylesheet_choice %= $nb_choices;
							break;
						case "year" : 
							$diff_year = abs(date( 'Y', current_time( 'timestamp' ) ) - date( 'Y', $switcherData['save_date'] ));
							$stylesheet_choice = $stylesheet_choice + $diff_year;
							if ($stylesheet_choice >= $nb_choices)
								$stylesheet_choice %= $nb_choices;
							break;
						case "random" : 
							$stylesheet_choice = rand(0, $nb_choices-1);
							break;
					}
				}	
			}
		
			$fileCSS = $switcherData['options'][$stylesheet_choice]['file'];
			wp_register_style( 'wp_user_stylesheet_switcher_file'.$switcherId, $this->get_path($switcherId, $fileCSS) );
			wp_enqueue_style( 'wp_user_stylesheet_switcher_file'.$switcherId);
			if (isset($switcherData['remove']))
				if ($switcherData['remove'] == $stylesheet_choice)
					$removeStyle = true;
		}
		if ($removeStyle)
			wp_enqueue_script(
				'wp_user_stylesheet_switcher_disable_styles.js', 
				plugins_url().'/wp-user-stylesheet-switcher/js/disable_styles.js',
				array('jquery')
			);

	}

	/*
	 * Creates the list and returns it.
	 * 
	 * */
	public function create_wp_user_stylesheet_switcher($attributes)
	{
		global $wp_user_stylesheet_switcher_nbform;
		if (isset ($wp_user_stylesheet_switcher_nbform))
			$wp_user_stylesheet_switcher_nbform++;
		else
			$wp_user_stylesheet_switcher_nbform = 1;

		$settings = $this->get_wp_user_stylesheet_settings();

		if ($wp_user_stylesheet_switcher_nbform == 1) {
			
			wp_enqueue_script('wp_user_stylesheet_switcher_script', plugins_url().'/wp-user-stylesheet-switcher/js/wp_user_stylesheet_switcher.js', array('jquery'));
			
			$stylesheets = array();
			
			foreach ($settings['switchers'] as $switcherId => $switcherData) {
				$noOption = 0;
				$stylesheets[$switcherId]['default'] = $switcherData['default'];
				foreach ($switcherData['options'] as $option) {	
					$switcherFile = $this->get_path($switcherId, $option['file']);
					if ($noOption == $switcherData['remove'])
						$switcherFile = "Remove";
					else if ($noOption == $switcherData['reset'])
						$switcherFile = "Reset";
						
					if (($option['file'] != '') && (($option['name'] != ''))) {
						$stylesheets[$switcherId][$noOption]['file'] = $switcherFile;
						$noOption++;
					}
				}
			}
			
			wp_localize_script('wp_user_stylesheet_switcher_script', 'wp_user_stylesheets', $stylesheets);
		}
		
		$switcherId = 0;
		if (isset($settings['switchers'])) {
			$subArray = array_keys($settings['switchers']);
			$switcherId = reset($subArray);
		}
		if (isset($attributes['switcher_id']))
			$switcherId = $attributes['switcher_id'];

		$stylesheet_choice = $settings['switchers'][$switcherId]['default'];
		
		if (isset($settings['switchers'][$switcherId]['remove'])) {
			if ($stylesheet_choice == $settings['switchers'][$switcherId]['remove']) {
				wp_enqueue_script(
					'remove_styles.js', 
					plugins_url().'/wp-user-stylesheet-switcher/js/remove_styles.js',
					array('jquery')
				);
			}
		}
		if (isset($_COOKIE["wp_user_stylesheet_switcher_js"])) {
			$sessionData = json_decode(stripslashes($_COOKIE["wp_user_stylesheet_switcher_js"]), true);
			if (isset($sessionData[$switcherId]))
				$stylesheet_choice = $sessionData[$switcherId];
		} 
		
		// get optional attributes and assign default values if not present
		extract( shortcode_atts( array(
			'switcher_id' => $switcherId,
			'list_title' => $settings['switchers'][$switcherId]['title'],
			'show_list_title' => "true",
			'list_type' => 'dropdown',
		), $attributes ) );

		if (!isset($attributes['list_title']))
			$attributes['list_title'] = $settings['switchers'][$switcherId]['title'];
		
		if (!isset($attributes['show_list_title']))
			$attributes['show_list_title'] = "true";
		
		if ("icon" == $list_type) {		
			$output = '<span class="wp_user_stylesheet_switcher">';
			
			if (("true" == $attributes['show_list_title']) || ("on" == $attributes['show_list_title'])) $output .= $attributes['list_title'];
		
			$icon_names = "none";
			if (isset($attributes['icon_names']))
				$icon_names = $attributes['icon_names'];
		
			$noOption=0;
			foreach ($settings['switchers'][$switcherId]['options'] as $option) {	
				if (($option['file'] != '') && ($option['name'] != '') && ($option['icon'] != '')) {
					if ($icon_names != "none")
						$output .= '<div class="icon_names">';
					if ($icon_names == "before")
						$output .= '<a href="#" class="wp_user_stylesheet_switcher_iconnames_'.$switcherId.'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \''.$noOption.'\');">'.$option['name'].'</a>';
					else if ($icon_names == "over")
						$output .= '<a href="#" class="wp_user_stylesheet_switcher_iconnames_'.$switcherId.'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \''.$noOption.'\');">'.$option['name'].'</a><br>';
					$output .= '<button class="wp_user_stylesheet_switcher_button wp_user_stylesheet_switcher_icon_'.$switcherId.'_'.$noOption.' '.($stylesheet_choice==$noOption? "wp_user_stylesheet_switcher_active_option" :"").'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \''.$noOption.'\');"><img class="wp_user_stylesheet_switcher_icon" src="'.$this->get_path($switcherId, $option['icon']).'"  alt="'.$option['name'].'"></button>';
					if ($icon_names == "after")
						$output .= '<a href="#" class="wp_user_stylesheet_switcher_iconnames_'.$switcherId.'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \''.$noOption.'\');">'.$option['name'].'</a>';
					else if ($icon_names == "under")
						$output .= '<br><a href="#" class="wp_user_stylesheet_switcher_iconnames_'.$switcherId.'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \''.$noOption.'\');">'.$option['name'].'</a>';
					if ($icon_names != "none")
						$output .= '</div>';
					$noOption++;
				}
			}
			$output .= '</span>';

		} else if ("button" == $list_type) {
			$output = '<span class="wp_user_stylesheet_switcher">';
			
			if (("true" == $attributes['show_list_title']) || ("on" == $attributes['show_list_title'])) $output .= $attributes['list_title'];
		
			$newStyleSheet = $stylesheet_choice + 1;
			if ($newStyleSheet >= $this->count_options($settings['switchers'][$switcherId]['options']))
				$newStyleSheet = 0;
			
			$switcherOption = "";
			if ($newStyleSheet == $settings['switchers'][$switcherId]['remove'])
				$switcherOption = "Remove";
			else if ($newStyleSheet == $settings['switchers'][$switcherId]['reset'])
				$switcherOption = "Reset";
			
			$option = $settings['switchers'][$switcherId]['options'][$newStyleSheet];
					
			$output .= '<a href="#" class="wp_user_stylesheet_switcher_button_'.$switcherId.'" value="'.$newStyleSheet.'" title="'.$attributes['list_title'].'" onclick="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', \'-1\');">';
						
			if ($settings['switchers'][$switcherId]['button_icon_file'] != "")
				$output .= '<img class="wp_user_stylesheet_switcher_button_icon_'.$switcherId.'" src="'.$this->get_path($switcherId, $settings['switchers'][$switcherId]['button_icon_file']).'"  alt="'.$attributes['list_title'].'">';
			else
				$output .= $attributes['list_title'];
			
			$output .= '</a></span>';	
		}
		else
		{
			$output = '<div class="wp_user_stylesheet_switcher">';
			
			if (("true" == $attributes['show_list_title']) || ("on" == $attributes['show_list_title'])) $output .= $attributes['list_title'];
		
			$output .= '<select name="user_stylesheet_switcher_choice_dropdown_'.$switcherId.'" onchange="wp_user_stylesheet_switcher_changeCSS(\''.$switcherId.'\', this.value);">';
			
			$noOption=0;
			foreach ($settings['switchers'][$switcherId]['options'] as $option) {	
				if (($option['file'] != '') && (($option['name'] != ''))) {
					$output .= '<option '.($stylesheet_choice==$noOption?'selected="selected"':"").' value="'.$noOption.'">'.$option['name'].'</option>';
					$noOption++;
				}
			}
			$output .= '</select></div>';
		}
			
		return $output;
	}

	/*
	 * Shows the switcher on the webpage
	 * Function used directly in php
	 * 
	 * */
	public function show_wp_user_stylesheet_switcher($attributes = array('list_type'=>'dropdown'))
	{
		echo $this->create_wp_user_stylesheet_switcher($attributes);
	}
	
	/*
	 * Creates and display the admin option page in the setting menu
	 * Deals also with the submitted form to update the plugin options.
	 * 
	 * */
	public function show_wp_user_stylesheet_switcher_options()
	{
		$settings = $this->get_wp_user_stylesheet_settings();
		
		$activeSwitcherId = 's0';
		
		$addSwitcher = false;
		if (isset($_POST['user_stylesheet_switcher_list'])) {
			$subArray = array_keys($settings['switchers']);
			if (in_array($_POST['user_stylesheet_switcher_list'], $subArray)) {
				$activeSwitcherId = $_POST['user_stylesheet_switcher_list'];
				// Delete the switcher
				if (isset($_POST['delete_current_switcher'])) {
					if (count($subArray) > 1) {
						unset ($settings['switchers'][$activeSwitcherId]);
						update_option('wp_user_stylesheet_switcher_settings', $settings);
						$subArray = array_keys($settings['switchers']);
						$activeSwitcherId = reset($subArray);
					} else
						echo __("Can't delete this switcher, you need at least one switcher!", "wp-user-stylesheet-switcher");	
				} 
			}
			
			// Add a new switcher
			if ($_POST['user_stylesheet_switcher_list'] == "-1") {
				$addSwitcher = true;
				$settings['switcherLastKey']++;
				$activeSwitcherId = "s".$settings['switcherLastKey'];
			}
		} else
			if (isset($_POST['user_stylesheet_switcher_active_switcher_id']))
				// User chose a switcher in the dropdown list
				$activeSwitcherId = $_POST['user_stylesheet_switcher_active_switcher_id'];
			else
				if (isset ($settings['switchers'])) {
					$subArray = array_keys($settings['switchers']);
					$activeSwitcherId = reset($subArray);
				}
		
		if (isset($settings['switchers'][$activeSwitcherId]['options']))
			$nbStylesheets = count($settings['switchers'][$activeSwitcherId]['options']);
		else
			$nbStylesheets = 3;
		
		if ((isset($_POST['info_update'])) || (isset($_POST['add_stylesheet_option'])) || (isset($_POST['delete_last_stylesheet_option'])) || $addSwitcher)
		{
			if (!$addSwitcher) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'wp_user_stylesheet_switcher_update')){
					wp_die('Error! Nonce Security Check Failed! Go back to settings menu and save the settings again.');
				}
			}
			
			if (!isset($settings['switcherLastKey']))
				$settings['switcherLastKey'] = count($settings['switchers']);
			
			if (isset($_POST["wp_user_stylesheet_switcher_title"]))
				$settings['switchers'][$activeSwitcherId]['title'] = $_POST["wp_user_stylesheet_switcher_title"];
			else
				if ($addSwitcher)
					$settings['switchers'][$activeSwitcherId]['title'] =  __("New switcher", "wp-user-stylesheet-switcher")." ".(count($settings['switchers'])+1);
				else
					$settings['switchers'][$activeSwitcherId]['title'] =  __("Stylesheet switcher", "wp-user-stylesheet-switcher");
			
			if (isset($_POST["wp_user_stylesheet_switcher_path"]))
				$settings['switchers'][$activeSwitcherId]['path'] = $_POST["wp_user_stylesheet_switcher_path"];
			else
				$settings['switchers'][$activeSwitcherId]['path'] = "";
			
			if (isset($_POST["wp_user_stylesheet_switcher_default"]))
				$settings['switchers'][$activeSwitcherId]['default'] = $_POST["wp_user_stylesheet_switcher_default"];
			else
				$settings['switchers'][$activeSwitcherId]['default'] = 0;
			
			if ($addSwitcher)
				$nbStylesheets = 3;
			else
				$nbStylesheets = intval($_POST["wp_user_stylesheet_switcher_number"]);
			
			if (isset($_POST['delete_last_stylesheet_option']) && ($nbStylesheets > 1)) 
			{
				$nbStylesheets--;
				unset($settings['switchers'][$activeSwitcherId]['options'][$nbStylesheets]);
			}
			
			if ($addSwitcher) {
				for ($i=0; $i<$nbStylesheets; $i++) {
					$Option = array(
						'name' => '',
						'file' => '',
						'icon' => ''
					);
					$settings['switchers'][$activeSwitcherId]['options'][$i] = $Option;
				}
			}
			else
				for ($i=0; $i<$nbStylesheets; $i++) {
					$Option = array(
						'name' => $_POST["wp_user_stylesheet_switcher_name".$i],
						'file' => $_POST["wp_user_stylesheet_switcher_file".$i],
						'icon' => $_POST["wp_user_stylesheet_switcher_icon".$i]
					);
					$settings['switchers'][$activeSwitcherId]['options'][$i] = $Option;
				}
			
			if (isset($_POST['add_stylesheet_option']))
			{
				$Option = array(
						'name' => '',
						'file' => '',
						'icon' => ''
					);
				$settings['switchers'][$activeSwitcherId]['options'][$nbStylesheets] = $Option;
				$nbStylesheets++;
			}
			
			if (isset($_POST["wp_user_stylesheet_switcher_remove_stylesheets"]))
				$settings['switchers'][$activeSwitcherId]['remove'] = $_POST["wp_user_stylesheet_switcher_remove_stylesheets"];
			else
				$settings['switchers'][$activeSwitcherId]['remove'] = "-1";
			
			if (isset($_POST['wp_user_stylesheet_switcher_revert_to_default']))
				$settings['switchers'][$activeSwitcherId]['reset'] = $_POST['wp_user_stylesheet_switcher_revert_to_default'];
			else
				$settings['switchers'][$activeSwitcherId]['reset'] = "-1";
			
			if (isset($_POST["wp_user_stylesheet_switcher_button_icon_file"]))
				$settings['switchers'][$activeSwitcherId]['button_icon_file'] = $_POST["wp_user_stylesheet_switcher_button_icon_file"];
			else
				$settings['switchers'][$activeSwitcherId]['button_icon_file'] = "";
			
			if (isset($_POST["wp_user_stylesheet_switcher_auto_rotation"]))
				$settings['switchers'][$activeSwitcherId]['rotation'] = $_POST["wp_user_stylesheet_switcher_auto_rotation"];
			else
				$settings['switchers'][$activeSwitcherId]['rotation'] = 'none';
			
			$settings['switchers'][$activeSwitcherId]['save_date'] = current_time( 'timestamp' );
			
			update_option('wp_user_stylesheet_switcher_settings', $settings);
		}
		
		echo '<div class="wrap">'.screen_icon( ).'<h2>'.(__("WP User Stylesheet Switcher Options", "wp-user-stylesheet-switcher")).'</h2>';
		echo (__("version ", "wp-user-stylesheet-switcher")).WP_USER_STYLESHEET_SWITCHER_VERSION;
		
		echo "<div><br>".sprintf(__('If you use or if you like this plugin, please consider <a href="%s">making a donation</a>. This helps me keep motivation to update and develop plugins. Thanks!', "wp-user-stylesheet-switcher"), "http://web.globulesverts.org/wp-user-stylesheet-switcher/")."</div>";
		
		/***********************************************
		 * 
		 *  Dropdown with available stylesheet switchers
		 *  Retload the page with the selected switcher
		 *
		 */
		echo '<p><form method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'" id="wp_user_stylesheet_admin_switcher_list_form" name="wp_user_stylesheet_admin_switcher_list_form" style="display: inline">';
			
		echo (__("Choose switcher", "wp-user-stylesheet-switcher")).'<select name="user_stylesheet_switcher_list"  onchange="document.wp_user_stylesheet_admin_switcher_list_form.submit();">';
			
		foreach ($settings['switchers'] as $switcherKey => $switcherData) {	
			echo '<option '.($switcherKey==$activeSwitcherId?'selected="selected"':"").' value="'.$switcherKey.'">'.$switcherData['title'].'</option>';
		}
		echo '<option '.("-1"==$activeSwitcherId?'selected="selected"':"").' value="-1">'.(__("Add a new switcher", "wp-user-stylesheet-switcher")).'</option>';
		echo '</select>';
		echo ' <input type="submit" class="button-primary" name="delete_current_switcher" value="'.(__("Delete current switcher", "wp-user-stylesheet-switcher")).'" /><input type="hidden" name="current_switcher_id" value="'.$activeSwitcherId.'"><input type="hidden" name="wp_user_stylesheet_switcher_list_type" value="dropdownSwitcherList"></form></p><hr />';
		
		/***********************************************
		 * 
		 *  Options for the selected switcher
		 *
		 */
		 
		echo 'Switcher ID : '.$activeSwitcherId;
		?>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" >
		<?php wp_nonce_field('wp_user_stylesheet_switcher_update'); ?>
		<input type="hidden" name="info_update" id="info_update" value="true" />  
		<?php 
		echo '
		<table class="form-table">
		<tr valign="top">
		<th scope="row">'.(__("Default label for this switcher", "wp-user-stylesheet-switcher")).'</th>
		<td><input type="text" name="wp_user_stylesheet_switcher_title" value="'.$settings['switchers'][$activeSwitcherId]['title'].'" size="20" maxlength="40"/></td>
		</tr>
		<tr valign="top">
		<th scope="row">'.(__("Folder path for CSS and icons files", "wp-user-stylesheet-switcher")).'</th>
		<td><select name="wp_user_stylesheet_switcher_path">
			<option '.($settings['switchers'][$activeSwitcherId]['path']=='relative'?'selected="selected"':"").' value="relative">'.__('Relative to theme path').'</option>
			<option '.($settings['switchers'][$activeSwitcherId]['path']=='absolute'?'selected="selected"':"").' value="absolute">'.__('Absolute path (not recommended)').'</option></select>
			</td>
		</tr>
		</table>';
		
		echo '<table class="form-table">';
		$no = 0;
		foreach ($settings['switchers'][$activeSwitcherId]['options'] as $option) {
			$optionNumber = sprintf(__("Stylesheet option %d", "wp-user-stylesheet-switcher"), ($no+1));
			echo '<tr valign="top"><th scope="row">'.$optionNumber.'</th><td><label for="wp_user_stylesheet_switcher_name'.$no.'">'.(__("Option name ", "wp-user-stylesheet-switcher")).' </label><input type="text" name="wp_user_stylesheet_switcher_name'.$no.'" value="'.$option['name'].'" size="20" maxlength="40"/></td>
			<td><label for="wp_user_stylesheet_switcher_file'.$no.'">'.(__("CSS file name (including .CSS extension)", "wp-user-stylesheet-switcher")). ' </label><input type="text" name="wp_user_stylesheet_switcher_file'.$no.'" value="'.$option['file'].'" size="20" maxlength="250"/></td>
			<td><label for="wp_user_stylesheet_switcher_icon'.$no.'">'.(__("Optional icon file (.jpg, .gif or .png)", "wp-user-stylesheet-switcher")). ' </label><input type="text" name="wp_user_stylesheet_switcher_icon'.$no.'" value="'.$option['icon'].'" size="20" maxlength="250"/></td></tr>';
			$no++;
		}
				
		echo '</table>';
		echo'<div class="submit">
			<input type="submit" class="button-primary" name="add_stylesheet_option" value="'.(__("+ Add another stylesheet option", "wp-user-stylesheet-switcher")).'" />
			<input type="submit" class="button-primary" name="delete_last_stylesheet_option" value="'.(__("- Delete last stylesheet option", "wp-user-stylesheet-switcher")).'" />
		</div>';	
		echo '<input type="hidden" name="wp_user_stylesheet_switcher_number" value="'.$nbStylesheets.'">';
		
		echo '<table class="form-table"><tr valign="top">
		<th scope="row">'.(__("Default stylesheet", "wp-user-stylesheet-switcher")).'</th>
		<td><select name="wp_user_stylesheet_switcher_default">';
		
		$noOption=0;
		foreach ($settings['switchers'][$activeSwitcherId]['options'] as $option) {	
			if (($option['file'] != '') && (($option['name'] != '')))
				echo '<option '.($settings['switchers'][$activeSwitcherId]['default']==$noOption?'selected="selected"':"").' value="'.$noOption.'">'.$option['name'].'</option>';
			$noOption++;
		}
		echo '</select><em> '.(__("To update the content of this dropdown list, update options first", "wp-user-stylesheet-switcher")).'</em></td>
		</tr>';
		
		echo '<tr valign="top">
		<th scope="row">'.(__("Automatic stylesheet rotation ", "wp-user-stylesheet-switcher")).'</th>
		<td>'.__('Change stylesheet each ').'
			<select name="wp_user_stylesheet_switcher_auto_rotation">
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='none'?'selected="selected"':"").' value="none">'.__('None').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='weekday'?'selected="selected"':"").' value="weekday">'.__('Weekday').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='week'?'selected="selected"':"").' value="week">'.__('Week').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='month'?'selected="selected"':"").' value="month">'.__('Month').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='season'?'selected="selected"':"").' value="season">'.__('Season').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='year'?'selected="selected"':"").' value="year">'.__('Year').'</option>
				<option '.($settings['switchers'][$activeSwitcherId]['rotation']=='random'?'selected="selected"':"").' value="random">'.__('Random').'</option>
			</select> <em> '.(__("The present stylesheet (ie. for the current month) is set with Default stylesheet.", "wp-user-stylesheet-switcher")).'</em>
			</td>
		</tr>';
		
		echo '<table class="form-table"><tr valign="top">
		<th scope="row">'.(__("Option to remove stylesheets", "wp-user-stylesheet-switcher")).'</th>
		<td><select name="wp_user_stylesheet_switcher_remove_stylesheets">';
		echo '<option value="-1"></option>';
		$noOption=0;
		foreach ($settings['switchers'][$activeSwitcherId]['options'] as $option) {	
			if ($option['name'] != '')
				echo '<option '.($settings['switchers'][$activeSwitcherId]['remove']==$noOption?'selected="selected"':"").' value="'.$noOption.'">'.$option['name'].'</option>';
			$noOption++;
		}
		echo '</select><em> '.(__("Leave it blank if you don't want to offer the option to remove all stylesheet. Otherwise, choose the option associated with no stylesheet. To update the content of this dropdown list, update options first. ", "wp-user-stylesheet-switcher")).'</em></td>
		</tr>';
		
		echo '<tr valign="top">
		<th scope="row">'.(__("Option to revert to defaults", "wp-user-stylesheet-switcher")).'</th>
		<td><select name="wp_user_stylesheet_switcher_revert_to_default">';
		echo '<option value="-1"></option>';
		$noOption=0;
		foreach ($settings['switchers'][$activeSwitcherId]['options'] as $option) {	
			if ($option['name'] != '')
				echo '<option '.($settings['switchers'][$activeSwitcherId]['reset']==$noOption?'selected="selected"':"").' value="'.$noOption.'">'.$option['name'].'</option>';
			$noOption++;
		}
		echo '</select><em> '.(__("Leave it blank if you don't want to offer the option to revert to default settings. Otherwise, choose the option associated with it. To update the content of this dropdown list, update options first. ", "wp-user-stylesheet-switcher")).'</em></td>
		</tr>';
		
		echo '<tr><th scope="row"><label for="wp_user_stylesheet_switcher_icon">'.(__("Optional icon file for single switcher button (.jpg, .gif or .png)", "wp-user-stylesheet-switcher")).'</label></th><td><input type="text" name="wp_user_stylesheet_switcher_button_icon_file" value="'.$settings['switchers'][$activeSwitcherId]['button_icon_file'].'" size="20" maxlength="250"/></td></tr>
		</table>
		<input type="hidden" name="user_stylesheet_switcher_active_switcher_id" value="'.$activeSwitcherId.'">
		<div class="submit">
			<input type="submit" class="button-primary" name="info_update" value="'.(__("Save options", "wp-user-stylesheet-switcher")).'" />
		</div>						
		</form>';
	}

	/*
	 * Handle the options page display
	 * 
	 * */
	public function wp_user_stylesheet_switcher_options_page () 
	{
		add_options_page(__("WP User Stylesheet Switcher", "wp-user-stylesheet-switcher"), __("WP User Stylesheet Switcher", "wp-user-stylesheet-switcher"), 'manage_options', 'wp_user_stylesheet_switcher', array( $this, 'show_wp_user_stylesheet_switcher_options')); 
	}

	/*
	 * Initializes the options when the plugin is installed
	 * 
	 * */
	public function wp_user_stylesheet_switcher_plugin_install()
	{
		//General options
		$settings['version'] = WP_USER_STYLESHEET_SWITCHER_VERSION;
		$settings['switchers']['s0']['title'] = __("Stylesheet choice", "wp-user-stylesheet-switcher");
		$settings['switchers']['s0']['path'] = 'relative';
		$settings['switchers']['s0']['default'] = "";
		$settings['switchers']['s0']['remove'] = "-1";
		
		for ($i = 0; $i<5; $i++) {
			$Option = array(
				'name' => '',
				'file' => '',
				'icon' => ''
			);
			$settings['switchers']['s0']['options'][$i] = $Option;
		}
		$settings['switchers']['s0']['button_icon_file'] = "";
		$settings['switchers']['s0']['rotation'] = 'none';
		$settings['switcherLastKey'] = 0;
		add_option('wp_user_stylesheet_switcher_settings', $settings);
	}

	/*
	 * Adds the widget to the list of the available widgets
	 * 
	 * */
	public function wp_user_stylesheet_switcher_load_widgets()
	{
		register_widget('WP_User_Stylesheet_Switcher');
	}

	/*
	 * Adds the settings link
	 * 
	 * */
	public function wp_user_stylesheet_switcher_add_settings_link($links, $file) 
	{
		if ($file == plugin_basename(__FILE__)){
			$settings_link = '<a href="options-general.php?page=wp_user_stylesheet_switcher">'.(__("Settings", "wp-user-stylesheet-switcher")).'</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}

	/*
	 * Loads custom language file if present
	 * 
	 * */
	public function load_custom_language_files_wp_user_stylesheet_switcher($domain, $mofile)
	{
		// Note: the plugin directory check is needed to prevent endless function nesting
		// since the new load_textdomain() call will apply the same hooks again.
		if ('wp-user-stylesheet-switcher' === $domain && plugin_dir_path($mofile) === WP_PLUGIN_DIR.'/wp-user-stylesheet-switcher/languages/')
		{
			load_textdomain('wp-user-stylesheet-switcher', WP_LANG_DIR.'/wp-user-stylesheet-switcher/'.$domain.'-'.get_locale().'.mo');
		}
	}

	/*
	* this function loads my plugin translation files
	*/
	public function load_plugin_textdomain() {
		$domain = 'wp-user-stylesheet-switcher';
		// The "plugin_locale" filter is also used in load_plugin_textdomain()
		$locale = apply_filters('plugin_locale', get_locale(), $domain);

		load_textdomain($domain, WP_LANG_DIR.'/wp-user-stylesheet-switcher/'.$domain.'-'.$locale.'.mo');
		load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
	}

	/*
	 * Enqueu script for admin widget options
	 * 
	 * */
	public function wp_user_stylesheet_switcher_widget_script() 
	{
		wp_enqueue_script(
				'wp_user_stylesheet_switcher_toggle_showlink', 
				plugins_url().'/wp-user-stylesheet-switcher/js/wp_user_stylesheet_switcher_admin.js',
				array('jquery')
			);
	}

	public function __construct() {
	    
		if (!defined('WP_USER_STYLESHEET_SWITCHER_VERSION'))
			define('WP_USER_STYLESHEET_SWITCHER_VERSION', '2.2.0');
	
		add_action('init', array($this, 'load_plugin_textdomain'));
		$text = __('I will not be translated!', 'wp-user-stylesheet-switcher');

		//	add_filter( 'the_content', array( $this, 'the_content' ) );

		add_filter('plugin_action_links', array( $this, 'wp_user_stylesheet_switcher_add_settings_link'), 10, 2 );

		// Insert the options page to the admin menu
		add_action('admin_menu',array( $this, 'wp_user_stylesheet_switcher_options_page'));

		add_action('widgets_init',array( $this, 'wp_user_stylesheet_switcher_load_widgets'));

		add_action('init', array( $this, 'wp_user_stylesheet_switcher_plugin_install'));

		add_shortcode('wp_user_stylesheet_switcher', array( $this, 'create_wp_user_stylesheet_switcher'));

		// add_action('wp_head', array( $this, 'wp_user_stylesheet_switcher_addcss'));
		add_action('wp_enqueue_scripts', array( $this, 'wp_user_stylesheet_switcher_addcss'), 999);

		add_action('load_textdomain', array( $this, 'load_custom_language_files_wp_user_stylesheet_switcher') , 10, 2);

		register_activation_hook(__FILE__, array( $this, 'wp_user_stylesheet_switcher_plugin_install'));
		
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_user_stylesheet_switcher_widget_script') );
	}
}

$wpUserStylesheetSwitcher = new WPUserStylesheetSwitcher();

/*
* Definition of the new class created for the WP_User_Stylesheet_Switcher widget
* 
* */
class WP_User_Stylesheet_Switcher extends WP_Widget {
	function __construct() {
		parent::__construct('wp_user_stylesheet_switcher_widgets', 'WP User Stylesheet Switcher', array('description' => 'WP User Stylesheet Switcher') );
	}
	
	public function WP_User_Stylesheet_Switcher() { // For compatibility reason
        self::__construct();
    }
	
	function form($instance) {
		// outputs the options form on admin
		$settings = get_option('wp_user_stylesheet_switcher_settings');
		
		if (!isset($settings['switchers'])) {
			echo "<p>";
			echo __("Settings for Wp user style sheetswitcher out of date. Please review the options in the admin panel to configure the widget.", "wp-user-stylesheet-switcher");
			echo "</p>";
			return;
		}
		
		$icon_names = "none";
		if (isset($instance['icon_names']))
			$icon_names = $instance['icon_names'];
		
		$subArray = array_keys($settings['switchers']);
		$firstSwitcher = reset($subArray);

		$defaults = array('switcher_id' => $firstSwitcher, 'title' => 'Stylesheets','show_title' => 'true', 'list_title' => __("Stylesheet choice", "wp-user-stylesheet-switcher"),'show_list_title' => 'true','list_type' => 'dropdown');
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		  echo '<p><label for="'.$this->get_field_id('title').'">'.(__("Widget title", "wp-user-stylesheet-switcher")).' </label>
		  <input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" size="20" maxlength="40"/>
		  </p>';
		  ?>
		  <p>
		   <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show widget title', "wp-user-stylesheet-switcher");?></label>
		   <input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" <?php if ($instance['show_title']=="true") echo 'checked="checked"';
		  echo '/></p>';
		  
		  echo '<label for="'.$this->get_field_id('switcher_id').'">'._e('Select switcher', "wp-user-stylesheet-switcher").'</label><br />';
		echo '<select id="'.$this->get_field_id('switcher_id').'" name="'.$this->get_field_name('switcher_id').'">';
			$noSwitcher=0;
			foreach ($settings['switchers'] as $switcherKey => $switcherData) {	
				echo '<option '.($switcherKey==$instance['switcher_id']?'selected="selected"':"").' value="'.$switcherKey.'">'.$switcherData['title'].'</option>';
				$noSwitcher++;
			}
			echo '</select>';
		  
		  echo '<p><label for="'.$this->get_field_id('list_title').'">'.(__("List title", "wp-user-stylesheet-switcher")).' </label>
		  <input type="text" id="'.$this->get_field_id('list_title').'" name="'.$this->get_field_name('list_title').'" value="'.$instance['list_title'].'" size="20" maxlength="40"/>
		  </p>';
		  ?>
		  <p>
		   <label for="<?php echo $this->get_field_id('show_list_title'); ?>"><?php _e('Show list title', "wp-user-stylesheet-switcher");?></label>
		   <input type="checkbox" id="<?php echo $this->get_field_id('show_list_title'); ?>" name="<?php echo $this->get_field_name('show_list_title'); ?>" <?php if ($instance['show_list_title']=="true") echo 'checked="checked"' ?> />
		  </p>
		  <label for="<?php echo $this->get_field_id('list_type') ?>"> <?php echo (__("List type", "wp-user-stylesheet-switcher")); ?></label>
		  <select id="<?php echo $this->get_field_id('list_type'); ?>" name="<?php echo $this->get_field_name('list_type') ?>"onchange="wp_user_stylesheet_switcher_toggle_showlink(this.value,'<?php echo $this->get_field_id('icon_names'); ?>');">';
		  
			<option value="dropdown" <?php if ("dropdown"==$instance['list_type']) echo ' selected="selected"'; ?> ><?php _e("Dropdown list", "wp-user-stylesheet-switcher");?></option>
			<option value="icon" <?php if ("icon"==$instance['list_type']) echo ' selected="selected"'; ?> ><?php _e("Icon list", "wp-user-stylesheet-switcher");?></option>
			<option value="button" <?php if ("button"==$instance['list_type']) echo ' selected="selected"'; ?> ><?php _e("Switcher button", "wp-user-stylesheet-switcher");?></option>
		  </select>
		  </p>
		  <label for="<?php echo $this->get_field_id('icon_names') ?>"> <?php echo (__("Show icon names", "wp-user-stylesheet-switcher")); ?></label>
		  <select id="<?php echo $this->get_field_id('icon_names'); ?>" name="<?php echo $this->get_field_name('icon_names') ?>">';
		  
			<option value="none" <?php if ("none"==$icon_names) echo ' selected="selected"'; ?> ><?php _e("None", "wp-user-stylesheet-switcher");?></option>
			<option value="before" <?php if ("before"==$icon_names) echo ' selected="selected"'; ?> ><?php _e("Before the icon", "wp-user-stylesheet-switcher");?></option>
			<option value="after" <?php if ("after"==$icon_names) echo ' selected="selected"'; ?> ><?php _e("After the icon", "wp-user-stylesheet-switcher");?></option>
			<option value="over" <?php if ("over"==$icon_names) echo ' selected="selected"'; ?> ><?php _e("Over the icon", "wp-user-stylesheet-switcher");?></option>
			<option value="under" <?php if ("under"==$icon_names) echo ' selected="selected"'; ?> ><?php _e("Under the icon", "wp-user-stylesheet-switcher");?></option>
		  </select>
		  </p>
		   
		  <?php
	}
	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;

		$instance['switcher_id'] = $new_instance['switcher_id'];
		$instance['title'] = $new_instance['title'];
		$instance['show_title'] = $new_instance['show_title']=="on"?"true":"false";
		$instance['list_title'] = $new_instance['list_title'];
		$instance['show_list_title'] = $new_instance['show_list_title']=="on"?"true":"false";
		$instance['list_type'] = $new_instance['list_type'];
		$instance['icon_names'] = $new_instance['icon_names'];
		
		return $instance;
	}
	function widget($args, $instance) {
		// outputs the content of the widget for the user
		extract( $args );
		
		if (isset($instance['switcher_id']))
			$switcherId = $instance['switcher_id'];
		$title = $instance['title'];
		$list_type = $instance['list_type'];
		$icon_names = "none";
		if (isset($instance['icon_names']))
			$icon_names = $instance['icon_names'];
			
		echo $before_widget;
		echo $before_title;
		if ($instance['show_title']=="true") echo $title;
		echo $after_title;
		
		global $wpUserStylesheetSwitcher;
		$wpUserStylesheetSwitcher->show_wp_user_stylesheet_switcher($instance);
		echo $after_widget;
	}
}
