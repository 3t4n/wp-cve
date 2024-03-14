<?php
$iframe_settings_init = new iframe_settings('iframe_settings_config');

class iframe_settings_config { 
	// MAIN CONFIGURATION SETTINGS 
	var $group = "iframeSettingsDisplay"; // defines setting groups
	var $page_name = "iframe_settings_display"; // defines which pages settings will appear on. Either bespoke or media/discussion/reading etc
	 
	//  DISPLAY SETTINGS
	var $title = "IFrame Markup Generator";  
	var $intro_text = 'This page allows you to generate the required HTML for using the <a href="http://wordpress.org/extend/plugins/iframe-widget" target="_blank">IFrame Widget</a> Markup.'; 
	var $nav_title = "IFrame Markup Generator"; // how page is listed on left-hand Settings panel
 
	//  SECTIONS
	var $sections = array(
      'markup_options' => array(
          'title' => 'IFrame parameters',
          'description' => "Enter or select appropriate parameters for your IFrame, then press the 'Generate Markup' button to get the Markup.",
          'fields' => array(
		    'width' => array (
              'label' => "Width",
              'description' => "In pixels or percentage of available space (example: 50px or 50 or 50%)",
              'length' => "3",
              'default_value' => "300"
            ),
			'height' => array (
              'label' => "Height",
              'description' => "In pixels or percentage of available space (example: 50px or 50 or 50%)",
              'length' => "3",
              'default_value' => "300"
            ),
            'style' => array (
              'label' => "CSS Style",
              'description' => "Please enter valid CSS here to style your IFrame.",
              'default_value' => "border:1px solid blue;align:left;"
            ),
			'url' => array (
              'label' => "Page URL",
              'description' => "Please enter the URL of the webpage you want to display in the IFrame.",
              'default_value' => "http://www.google.com"
            ),
            'border' => array(
              'label' => "Display Frame Border?",
              'dropdown' => "frame_border",
              'default_value' => "no"		
            ),
			'scrolling' => array(
              'label' => "Display Scroll bars?",
              'dropdown' => "frame_scrolling",
              'default_value' => "auto"
            )		
          )
		)
    );
 
	// DROPDOWN OPTIONS
	var $dropdown_options = array (
		'frame_border' => array (
			'1' => "Yes",
			'0' => "No",
		),
		'frame_scrolling' => array (
			'yes' => "Yes",
			'no' => "No",
			'auto' => "Auto",
		)
	);
 
//  end class
};
 
class iframe_settings {
 
	function iframe_settings($settings_class) {
		global $iframe_settings;
		$iframe_settings = get_class_vars($settings_class);
	 
		if (function_exists('add_action')) :
		  add_action('admin_init', array( &$this, 'plugin_admin_init'));
		  add_action('admin_menu', array( &$this, 'plugin_admin_add_page'));
		endif;
	}
	 
	function plugin_admin_add_page() {
		global $iframe_settings;
		add_options_page($iframe_settings['title'], $iframe_settings['nav_title'], 'manage_options', $iframe_settings['page_name'], array( &$this,'plugin_options_page'));
	}
 
	function plugin_options_page() {
		global $iframe_settings;
		echo '</pre><div>';
		printf('<h2>%s</h2>%s<form action="options.php" method="post">', $iframe_settings['title'], $iframe_settings['intro_text']);
		settings_fields($iframe_settings['group']);
		do_settings_sections($iframe_settings['page_name']);
		printf('<p class="submit"><input type="submit" name="Submit" value="%s" /></p></form>',__('Generate Markup'));
			
		//Print the Markup		
		$options = get_option($iframe_settings['group'].'_'.'url');		
		$url = $options['text_string'];
		$options = get_option($iframe_settings['group'].'_'.'border');		
		$border = $options['text_string'];
		$options = get_option($iframe_settings['group'].'_'.'height');		
		$height = $options['text_string'];
		$options = get_option($iframe_settings['group'].'_'.'width');		
		$width = $options['text_string'];
		$options = get_option($iframe_settings['group'].'_'.'scrolling');		
		$scrolling = $options['text_string'];
		$options = get_option($iframe_settings['group'].'_'.'style');		
		$style = $options['text_string'];
		
		if($url){
			echo "<h2>Copy-Paste the following Markup to your page where you wish to display the IFrame:</h2>";
			echo '<textarea style="width:600px;height:60px;font-family:courier;font-size:16px;" name="markup" id="markup">';
			printf('[dciframe]%s,%s,%s,%s,%s,%s[/dciframe]', $url, $width, $height, $border, $scrolling, $style);
			echo "</textarea>";
		}
		else {
			echo "<p><font color=red>Markup cannot be generated without a URL.</font></p>";
		}
		echo '</div><pre>';
	}
 
	function plugin_admin_init(){
		global $iframe_settings;
		foreach ($iframe_settings["sections"] AS $section_key=>$section_value) :
			add_settings_section($section_key, $section_value['title'], array( &$this, 'plugin_section_text'), $iframe_settings['page_name'], $section_value);
			foreach ($section_value['fields'] AS $field_key=>$field_value) :
				$function = (!empty($field_value['dropdown'])) ? array( &$this, 'plugin_setting_dropdown' ) : array( &$this, 'plugin_setting_string' );
				$function = (!empty($field_value['function'])) ? $field_value['function'] : $function;
				$callback = (!empty($field_value['callback'])) ? $field_value['callback'] : NULL;
				add_settings_field($iframe_settings['group'].'_'.$field_key, $field_value['label'], $function, $iframe_settings['page_name'], $section_key, array_merge($field_value,array('name' => $iframe_settings['group'].'_'.$field_key)));
				register_setting($iframe_settings['group'], $iframe_settings['group'].'_'.$field_key, $callback);
			endforeach;
		endforeach;
	}
 
	function plugin_section_text($value = NULL) {
	  global $iframe_settings;
	  printf("%s", $iframe_settings['sections'][$value['id']]['description']);
	}
 
	function plugin_setting_string($value = NULL) {
		$options = get_option($value['name']);
		$default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
		printf('<input id="%s" type="text" name="%1$s[text_string]" value="%2$s" size="40" /> %3$s%4$s',
		$value['name'],
		(!empty ($options['text_string'])) ? $options['text_string'] : $default_value,
		(!empty ($value['suffix'])) ? $value['suffix'] : NULL,
		(!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : NULL);
	}
 
	function plugin_setting_dropdown($value = NULL) {
		global $iframe_settings;
		$options = get_option($value['name']);
		$default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
		$current_value = ($options['text_string']) ? $options['text_string'] : $default_value;
		$chooseFrom = "";
		$choices = $iframe_settings['dropdown_options'][$value['dropdown']];
		foreach($choices AS $key=>$option) :
			$chooseFrom .= sprintf('<option value="%s" %s>%s</option>',
			$key,($current_value == $key ) ? ' selected="selected"' : NULL,$option);
		endforeach;
		printf('<select id="%s" name="%1$s[text_string]">%2$s</select>%3$s',$value['name'], $chooseFrom,	(!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : NULL);
	} 
//end class
}
?>