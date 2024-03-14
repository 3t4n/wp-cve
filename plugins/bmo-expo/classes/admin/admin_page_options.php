<?php
/*
The Admin Interface - options page
BMo Expo - a  Wordpress and NextGEN Gallery Plugin by B. Morschheuser
Copyright 2012-2013 by Benedikt Morschheuser (http://bmo-design.de/kontakt/)

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
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

http://wordpress.org/about/gpl/
#################################################################
*/

class bmoExpoAdmin_options_page {
		private $theExpo_AdminObjcet = ""; 

		function __construct($theExpo_AdminObjcet) {
	     	$this->theExpo_AdminObjcet=$theExpo_AdminObjcet;
	  	}

		public function BMo_Expo_registerPageComponents (){
			//register all page Components:

			//meta boxes - more infos http://www.wproots.com/ultimate-guide-to-meta-boxes-in-wordpress/
			add_meta_box('BMo_Exp_main_box', __('Default Gallery Options','bmo-expo'), array($this, 'BMo_Expo_main_MetaBox'), BMO_EXPO_PLUGINNAME, 'normal', 'core');
			
			//advanced
			//--
			
			//side
			//--
			
		}
		
		public function BMo_Expo_registerOptionSettings($withoutDesign = false){
			//register settings in main box
			register_setting( 'BMo_Expo_options', BMO_EXPO_OPTIONS, array($this,'BMo_Expo_options_validate') );//form

			//sections in main box
			add_settings_section('BMo_Expo_options_section_common_php', '', array($this,'BMo_Expo_options_section_common_php_html'), 'BMo_Expo_options_section_common_php_el');
			add_settings_section('BMo_Expo_options_section_common', '', array($this,'BMo_Expo_options_section_common_html'), 'BMo_Expo_options_section_common_el');
			add_settings_section('BMo_Expo_options_section_sG', '', array($this,'BMo_Expo_options_section_sG_html'), 'BMo_Expo_options_section_sG_el');
			add_settings_section('BMo_Expo_options_section_slG', '', array($this,'BMo_Expo_options_section_slG_html'), 'BMo_Expo_options_section_slG_el');

			//fields in sections in main box
			$options = get_option(BMO_EXPO_OPTIONS);
			if(empty($options)){
				return;
			}
			
			foreach($options as $key=>$option){
				$isSpecial = false;
				foreach($this->theExpo_AdminObjcet->BMo_Expo_get_theExpo_Objcet()->BMo_Expo_get_galleryTypes() as $key_gal => $val){
						if($key==$key_gal.'_design'){
							 $isSpecial = true;
						}	    
				}
				
				if($isSpecial){//special fields e.g. design
					if(!$withoutDesign){
						add_settings_field('BMo_Expo_options_field_'.$key, __($option['desc'], 'bmo-expo'), array($this,'BMo_Expo_options_field_html_design'), 'BMo_Expo_options_section_'.$option["type"].'_el', 'BMo_Expo_options_section_'.$option["type"], array("key"=>$key,"option"=>$option));
					}
				}else{
					//else - generated fields
					switch ($option['valtype']){
						case 'bool':
							add_settings_field('BMo_Expo_options_field_'.$key, __($option['desc'], 'bmo-expo'), array($this,'BMo_Expo_options_field_html_bool'), 'BMo_Expo_options_section_'.$option["type"].'_el', 'BMo_Expo_options_section_'.$option["type"], array("key"=>$key,"option"=>$option));
						break;
						case 'int':
							add_settings_field('BMo_Expo_options_field_'.$key, __($option['desc'], 'bmo-expo'), array($this,'BMo_Expo_options_field_html_int'), 'BMo_Expo_options_section_'.$option["type"].'_el', 'BMo_Expo_options_section_'.$option["type"], array("key"=>$key,"option"=>$option));
						break;
						case 'string':
							add_settings_field('BMo_Expo_options_field_'.$key, __($option['desc'], 'bmo-expo'), array($this,'BMo_Expo_options_field_html_string'), 'BMo_Expo_options_section_'.$option["type"].'_el', 'BMo_Expo_options_section_'.$option["type"], array("key"=>$key,"option"=>$option));
						break;
						case 'select':
							add_settings_field('BMo_Expo_options_field_'.$key, __($option['desc'], 'bmo-expo'), array($this,'BMo_Expo_options_field_html_select'), 'BMo_Expo_options_section_'.$option["type"].'_el', 'BMo_Expo_options_section_'.$option["type"], array("key"=>$key,"option"=>$option));
						break;
						default:
							echo "option type undefined";
					}
				}
			}

		}


		public function BMo_Expo_Admin_show_page() {

			//--- build page html and output html:

			?>
			<div class="wrap">
	          <div class="icon32" id="icon-options-general"></div>  
			  <h2>BMo Expo</h2>  
			  <form action="options.php" method="post" id="post" name="post">  
	          <div id="poststuff">
	           	<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<?php do_meta_boxes(BMO_EXPO_PLUGINNAME,'normal', NULL); ?>
					</div>
					<div id="postbox-container-1" class="postbox-container">
	          			<?php  do_meta_boxes(BMO_EXPO_PLUGINNAME, 'side', NULL); ?>
	                </div>
	                <div id="postbox-container-2" class="postbox-container" >
						 <?php do_meta_boxes(BMO_EXPO_PLUGINNAME,'advanced',NULL); ?>
	                </div>
				</div>	
	          </div>
              
			  </form>
			</div>
	        <div class="clear"></div>
			<?php
		}   


		 public function BMo_Expo_options_section_common_php_html() {
		   echo '<p>'.__('Here you can change the common PHP BMo Expo options:','bmo-expo').'</p>';
		 }
		 public function BMo_Expo_options_section_common_html() {
		   echo '<p>'.__('Here you can change the common BMo Expo options:','bmo-expo').'</p>';
		 }
		 public function BMo_Expo_options_section_sG_html() {
		   echo '<p>'.__('Scroll Gallery options:','bmo-expo').'</p>';
		 }
		 public function BMo_Expo_options_section_slG_html() {
		   echo '<p>'.__('Scroll Lightbox options:','bmo-expo').'</p>';
		 }
		//default fields
		 public function BMo_Expo_options_field_html_bool($args=array()) {
			$key = $args['key'];
			$option = $args['option'];
			echo '<input name="'.BMO_EXPO_OPTIONS.'['.$key.'][value]" type="checkbox" value="1" ';
			if($option["value"]) 
				echo "checked='checked'";
			else 
				echo "";
			echo "/> ";
			if($option['type']!='common_php')
				echo "<p class='small'>".$key." (boolean)</p>";
		 }
	 	 public function BMo_Expo_options_field_html_int($args=array()) {
			$key = $args['key'];
			$option = $args['option'];
			echo '<input  name="'.BMO_EXPO_OPTIONS.'['.$key.'][value]" size="4" type="text" value="'.$option['value'].'" />';
			if($option['type']!='common_php')
				echo "<p class='small'>".$key." (number)</p>";
	 	 }
	 	 public function BMo_Expo_options_field_html_string($args=array()) {
			$key = $args['key'];
			$option = $args['option'];
			echo '<input  name="'.BMO_EXPO_OPTIONS.'['.$key.'][value]" size="14" type="text" value="'.$option['value'].'" />';
			if($option['type']!='common_php')
				echo "<p class='small'>".$key." (string)</p>";
		 }
		 public function BMo_Expo_options_field_html_select($args=array()) {
			$key = $args['key'];
			$option = $args['option'];
			$_possibilities = "";
			echo '<select  name="'.BMO_EXPO_OPTIONS.'['.$key.'][value]" >';
			foreach($option['possibilities'] as $k => $val){
				if($val==$option["value"]){
					echo '<option value="'.$val.'" selected="selected">'.$val.'</option>';
				}else{
					echo '<option value="'.$val.'">'.$val.'</option>';
				}
				$_possibilities .= $val.", ";
			}
			if(strlen($_possibilities)>=2)
				$_possibilities = substr($_possibilities, 0, -2);
			echo '</select>';
			if($option['type']!='common_php')
				echo "<p class='small'>".$key." (string)[".$_possibilities."]</p>";
		 }

		//special fields
		 public function BMo_Expo_options_field_html_design($args=array()) {
			$key = $args['key'];
			$option = $args['option'];
			$act_cssfile = $option['value'];
			$path_val = "";
			foreach($this->theExpo_AdminObjcet->BMo_Expo_get_theExpo_Objcet()->BMo_Expo_get_galleryTypes() as $key_gal => $val){
				if($key_gal==$option['type']){
					 $path_val = '/'.$val;
				}	    
			}
			$csslist = $this->BMo_Expo_get_cssfiles(BMO_EXPO_BASEPATH.'/css/themes',$path_val,false);//plugin themes
			if (is_dir(BMO_EXPO_CUSTOM_THEME_BASEPATH)) {
				$csslist = array_merge($csslist, $this->BMo_Expo_get_cssfiles(BMO_EXPO_CUSTOM_THEME_BASEPATH,$path_val,true)); //themes in wp-content/bmo-expo-themes
			}
			
			echo '<select name="'.BMO_EXPO_OPTIONS.'['.$key.'][value]" onchange="this.form.submit();">';
					foreach ($csslist as $key =>$a_cssfile) {
						$css_name = $a_cssfile['Name'];
						if ($key == $act_cssfile) {
							$file_show = $key;
							$selected = " selected='selected'";
							$act_css_description = $a_cssfile['Description'];
							$act_css_author = $a_cssfile['Author'];
							$act_css_version = $a_cssfile['Version'];
							$act_css_name = esc_attr($css_name);
						}
						else $selected = '';
						$css_name = esc_attr($css_name);
						echo "\n\t<option value=\"$key\" $selected>$css_name</option>";
					}

			echo "</select>

			<p><strong>".__("Active design","bmo-expo").":</strong> $act_css_name<br/>
			".__("Author","bmo-expo").": $act_css_author<br/>
			".__("Version","bmo-expo").": $act_css_version<br/>
			".__("Description","bmo-expo").": $act_css_description</p>";
		}

		//...

		//validate
		 public function BMo_Expo_options_validate($inputs) {
			$options = get_option(BMO_EXPO_OPTIONS);
			$validatedInputs = $options;
			if(!is_array($inputs))
				return $validatedInputs;

			foreach($options as $key => $option){
				switch($option['valtype']){
					case 'bool':
						if(isset($inputs[$key]['value'])){
							$validatedInputs[$key]['value'] = (bool) 1;
						}else{
							$validatedInputs[$key]['value'] =  (bool) 0;
						}
					break;
					case 'int':
						if(isset($inputs[$key]['value'])&&is_numeric($inputs[$key]['value'])){
							$validatedInputs[$key]['value'] =  (int) $inputs[$key]['value'];
						}else{
							$validatedInputs[$key]['value'] =  (int) $option['default'];
						}
					break;
					case 'string':
						if(isset($inputs[$key]['value'])&&!empty($inputs[$key]['value'])){
							$validatedInputs[$key]['value'] =  (string) sanitize_text_field($inputs[$key]['value']);//validierung mit WP Hausmitteln
						}else{
							$validatedInputs[$key]['value'] =  (string) $option['default'];
						}
					break;
					case 'select':
						if(isset($inputs[$key]['value'])&&!empty($inputs[$key]['value'])&&in_array($inputs[$key]['value'],$option['possibilities'])){
							$validatedInputs[$key]['value'] =  (string) sanitize_text_field($inputs[$key]['value']);//validierung mit WP Hausmitteln
						}else{
							$validatedInputs[$key]['value'] =  (string) $option['default'];
						}
					break;
				}

			}


			return $validatedInputs;
		 }
		 
		 /**********************************************************/
		 // ### Code from wordpress plugin 
		 // read in the css files
		public  function BMo_Expo_get_cssfiles($path,$path_val,$isCustomTheme=false) {//$isCustomTheme ist wichtig für bau der urls

			/*global $cssfiles; //falls nur einmal eingelesen werden sollte global setzen, sonst wird es immer wieder neu gelesen, wichtig bei veschiedenen ordneren

			if (isset ($cssfiles)) {
				return $cssfiles;
			}*/

			$cssfiles = array ();

			$plugin_root = $path.$path_val;
			$plugins_dir = @ dir($plugin_root);
			
			if ($plugins_dir) {
				while (($file = $plugins_dir->read()) !== false) {
					if (preg_match('|^\.+$|', $file))
						continue;
					if (is_dir($plugin_root.'/'.$file)) {
						$plugins_subdir = @ dir($plugin_root.'/'.$file);
						if ($plugins_subdir) {
							while (($subfile = $plugins_subdir->read()) !== false) {
								if (preg_match('|^\.+$|', $subfile))
									continue;
								if (preg_match('|\.css$|', $subfile))
									$plugin_files[] = "$file/$subfile";
							}
						}
					} else {
						if (preg_match('|\.css$|', $file))
							$plugin_files[] = $file;
					}
				}
			}
			if ( !$plugins_dir || !$plugin_files )
				return $cssfiles;

			foreach ( $plugin_files as $plugin_file ) {
				if ( !is_readable("$plugin_root/$plugin_file"))
					continue;

				$plugin_data = $this->BMo_Expo_get_cssfiles_data("$plugin_root/$plugin_file");

				if ( empty ($plugin_data['Name']) )
					continue;

				//$cssfiles[plugin_basename($plugin_file)] = $plugin_data;
				if($isCustomTheme){
					$cssfiles[BMO_EXPO_CUSTOM_THEME_URL.$path_val.'/'.$plugin_file] = $plugin_data;
				}else{
					$cssfiles[BMO_EXPO_URL.'/css/themes'.$path_val.'/'.$plugin_file] = $plugin_data;
				}
			}

			uasort($cssfiles, create_function('$a, $b', 'return strnatcasecmp($a["Name"], $b["Name"]);'));

			return $cssfiles;
		 }
		 // parse the Header information
		 public function BMo_Expo_get_cssfiles_data($plugin_file) {
			$plugin_data = implode('', file($plugin_file));
			preg_match("|CSS Name:(.*)|i", $plugin_data, $plugin_name);
			preg_match("|Description:(.*)|i", $plugin_data, $description);
			preg_match("|Author:(.*)|i", $plugin_data, $author_name);
			if (preg_match("|Version:(.*)|i", $plugin_data, $version))
				$version = trim($version[1]);
			else
				$version = '';

			$description = wptexturize(trim($description[1]));
			$name = trim($plugin_name[1]);
			$author = trim($author_name[1]);

			return array ('Name' => $name, 'Description' => $description, 'Author' => $author, 'Version' => $version);
		 }

		 public function BMo_Expo_main_MetaBox(){
			 settings_fields('BMo_Expo_options');
			 echo "
			    <ul class='nav nav-tabs' id='bmoTab' data-tabs='tabs'>
			    	<li class='active'><a id='tab_common' href='#common' data-toggle='tab'>".__("General","bmo-expo")."</a></li>
			    	<li class=''><a id='tab_common_js' href='#common_js' data-toggle='tab'>".__("Gallery basics","bmo-expo")."</a></li>
			    	<li class=''><a id='tab_ScrollGallery' href='#ScrollGallery' data-toggle='tab'>Scroll Gallery</a></li>
			    	<li class=''><a id='tab_ScrollLightboxGallery' href='#ScrollLightboxGallery' data-toggle='tab'>Scroll Lightbox Gallery</a></li>
			    </ul>
				<div class='tab-content'>
					<div class='tab-pane active' id='common'>";
					do_settings_sections('BMo_Expo_options_section_common_php_el');
			  echo "</div>
					<div class='tab-pane' id='common_js'>";
					do_settings_sections('BMo_Expo_options_section_common_el');
			  echo "</div>
					<div class='tab-pane' id='ScrollGallery'>";
					do_settings_sections('BMo_Expo_options_section_sG_el');
			  echo "</div>
					<div class='tab-pane' id='ScrollLightboxGallery'>";
					do_settings_sections('BMo_Expo_options_section_slG_el');
			  echo "</div>
				</div>
				<script>
					(function($){
						$(function(){
							$('#BMo_Exp_main_box a[data-toggle=\"tab\"]').on('shown', function (e) {
						    	//save the latest tab; use cookies if you like 'em better:
						    	localStorage.setItem('lastTab', $(e.target).attr('id'));
						  	 });

						     //go to the latest tab, if it exists:
						     var lastTab = localStorage.getItem('lastTab');
						     if (lastTab) {
						      	$('#'+lastTab).tab('show');
						     }
							 
							 $('#BMo_Exp_main_box table th a').popover({ placement: 'right'});
				    	});
					})(jQuery); 
				</script>";
			 echo '<p><input name="Submit" type="submit" class="button-primary" value="';
			 esc_attr_e('Save Changes');
			 echo '" style="margin-left:230px"/></p>';
	     }

}

?>