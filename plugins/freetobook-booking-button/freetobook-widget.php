<?php

/**
 * Plugin Name: 	freetobook widget (legacy)
 * Plugin URI: 		https://en.freetobook.com/developers/wordpress.php
 * Description: 	Freetobook Booking Widget for wordpress (legacy)
 * Version:  		1.0.7
 * Author: 			freetobook.com
 * Author URI: 		https://en.freetobook.com
 * License: 		GPL v2
 */


if (!class_exists("FreetobookWidget")) 
{
    class FreetobookWidget extends WP_Widget
	{
	    const NUMBER_OF_BUTTON_STYLES = 7;
        const NUMBER_OF_BUTTONS = 6;

        const WIDGET_STYLE_CUSTOM = 'custom';
        const WIDGET_STYLE_BUTTON = 'button';
        const WIDGET_STYLE_CALENDAR = 'calendar';

		private $widget_key;
		private $widget_style;
		private $widget_button_url;
		private $widget_button_id;

		
        function __construct() 
		{ 
			$this->widget_key=get_option('ftb_widget_key');
			$this->widget_style=get_option('ftb_widget_style');
			$this->widget_button_url=get_option('ftb_widget_button_url');
			$this->widget_button_id=get_option('ftb_widget_button_id');
			if (empty($this->widget_button_id)) $this->widget_button_id='11';
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'FreetobookWidget', 
								'description' => 'Add freetobook booking button to your wordpress site' );
	
			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'freetobook' );   
			parent::__construct('freetobook','Freetobook Widget',$widget_ops,$control_ops); 
			
        }

		function add_settings_menu()
		{
			add_options_page( 'Freetobook Widget Settings', 'freetobook settings', 
								'Administrator', 'freetobook-widget-admin.php'); 

			add_submenu_page('options-general.php', 'Freetobook Widget Options', 
								'Freetobook Widget', 'activate_plugins', __FILE__, array(&$this, 'admin_page'));			
		}


		 function add_settings_link($links, $file) 
		 {
			static $this_plugin;
			if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
		 
			if ($file == $this_plugin)
			{
				$settings_link = '<a href="options-general.php?page=freetobook-booking-button/freetobook-widget.php">' . 
				                   __("Settings", "freetobook").'</a>';
			 array_unshift($links, $settings_link);
			}
			return $links;
		 }
		 
		 
		 function admin_page()
		 {
			 //must check that the user has the required capability 
			if (!current_user_can('manage_options'))
			{
			  wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			$updated = $this->update_widget_settings($updateErrors);

			$this->widget_key=get_option('ftb_widget_key');
			$this->widget_style=get_option('ftb_widget_style');
			$this->widget_button_url=get_option('ftb_widget_button_url');
			$this->widget_button_id=get_option('ftb_widget_button_id');
			 
			 
			 $calendarSelected=($this->widget_style==self::WIDGET_STYLE_CALENDAR)?' checked="checked" ':'';
			 $buttonSelected=($this->widget_style==self::WIDGET_STYLE_BUTTON)?' checked="checked" ':'';
			 $buttonPaneStyle=($this->widget_style==self::WIDGET_STYLE_BUTTON)?'table-row':'none';

			 $customSelected=($this->widget_style==self::WIDGET_STYLE_CUSTOM)?' checked="checked" ':'';
			 $urlPaneStyle=($this->widget_style==self::WIDGET_STYLE_CUSTOM)?'table-row':'none';
			 			 
			$html='<div class="wrap">';
			
			$html.='<div id="icon-options-general" class="icon32"><br /></div><h2>Freetobook Widget Settings</h2>';
			if ($updated) {
				$html.='<h3>Changes saved</h3>';
			}

             if (!empty($updateErrors)) {
                 $html.='<h3>Error: ' . implode(', ', $updateErrors) . '</h3>';
             }


			$html.='
			<br />
			<br />
			<form method="post" action="options-general.php?page=freetobook-booking-button/freetobook-widget.php">';
	
			if ( function_exists('wp_nonce_field') )
				$html.=wp_nonce_field('freetobook_update','ftb_nonce',true,false);

			$html.='
			<table>
				<tr> 
					<td style="width:100px">Widget Key</td> 
					<td><input type="text" size="110" name="ftb-widget-key" value="' . esc_attr($this->widget_key) . '" ></td>
				</tr>

				<tr> 
					<td>Widget Style</td>  
					<td>Calendar <input onclick="checkVis(this)" type="radio" name="ftb-widget-style" value="calendar" ' . $calendarSelected . '>
				 		Button Only<input onclick="checkVis(this)" type="radio" name="ftb-widget-style" value="button" ' . $buttonSelected . '>
						Custom Image <input onclick="checkVis(this)" type="radio" name="ftb-widget-style" value="custom" ' . $customSelected . '>
						
										
					</td>
				</tr>

				<tr valign="top" id="button-chooser"  style="display:'.$buttonPaneStyle.'">
				<td colspan="2">
				<table>
				<tr>
				';
				$numberOfStyles = self::NUMBER_OF_BUTTON_STYLES;
				$numberOfButtons = self::NUMBER_OF_BUTTONS;
				for ($i = 1; $i <= $numberOfStyles; $i++) {
					for ($j = 1; $j <= $numberOfButtons; $j++) {
                        $checked=($this->widget_button_id==($i . $j))?' checked="checked" ':'';
                        $html.='<td style="text-align:center;padding:7px;">
                                    <img src="'.plugins_url('stock_buttons/style' . $i . '/btn'.$j.'.gif', __FILE__).'" alt=""><br>
                                    <input type="radio" name="ftb-widget-button-id" value="'.$i.$j.'" '.$checked.' >
                                </td>';
					}
					$html.='</tr><tr>';					
				}
				$html.='
				</tr>
				</table>
				</td>
				</tr>





				<tr valign="top" id="button-upload"  style="display:'.$urlPaneStyle.'">
				<td scope="row">Image</td>
				<td><label for="upload_image">
				<input id="fake_post_id" value="0" type="hidden">
				<input id="upload_image" type="text" size="90" name="ftb-widget-button-url" value="'.esc_attr($this->widget_button_url).'" />
				<input id="upload_image_button" type="button" value="Upload Image" />
				<br />Enter a URL or upload an image for the search button.
				</label></td>
				</tr>				
				
			</table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="' . 
					__('Save Changes', 'FreetobookWidget') . '" /></p>
			</form>



			</div>
			';
			$html.='';
			echo  $html; 
		 }

	 function update_widget_settings(&$updateErrors = array())
	 {
         $update = false;
         $updateErrors = array();

		 if (isset($_POST['ftb-widget-key'])) {
             check_admin_referer('freetobook_update', 'ftb_nonce');

		     $widgetKey = sanitize_text_field($_POST['ftb-widget-key']);
		     if ($this->widget_key_is_valid($widgetKey)) {
                 update_option('ftb_widget_key', $widgetKey);
                 $update = true;
             } else {
		         $updateErrors[] = "Widget Key is invalid";
             }
		 }

		 if (isset($_POST['ftb-widget-style'])) {
             check_admin_referer('freetobook_update', 'ftb_nonce');

		     $widgetStyle = sanitize_text_field($_POST['ftb-widget-style']);
             switch ($widgetStyle) {
                 case self::WIDGET_STYLE_CALENDAR:
                     update_option('ftb_widget_style', self::WIDGET_STYLE_CALENDAR);
                     $update = true;
                     break;

                 case self::WIDGET_STYLE_BUTTON:
                     if (isset($_POST['ftb-widget-button-id'])
                         && ($widgetButtonId = sanitize_text_field($_POST['ftb-widget-button-id']))
                         && $this->widget_button_id_is_valid($widgetButtonId)
                     ) {
                         update_option('ftb_widget_style', self::WIDGET_STYLE_BUTTON);
                         update_option('ftb_widget_button_id', $widgetButtonId);
                         $update = true;
                     } else {
                         $updateErrors[] = "Chosen Widget Button is invalid";
                     }
                     break;

                 case self::WIDGET_STYLE_CUSTOM:
                     if (isset($_POST['ftb-widget-button-url'])
                         && ($widgetButtonUrl = sanitize_text_field($_POST['ftb-widget-button-url']))
                         && $this->widget_button_url_is_valid($widgetButtonUrl)
                     ) {
                         update_option('ftb_widget_style', self::WIDGET_STYLE_CUSTOM);
                         update_option('ftb_widget_button_url', $widgetButtonUrl);
                         $update = true;
                     } else {
                         $updateErrors[] = "Widget image URL is invalid";
                     }
                     break;

                 default:
                    $updateErrors[] = "Widget Style is invalid";
             }
		 }

		 return $update;
	 }

    function widget_key_is_valid($widgetKey)
    {
        return preg_match('/^w_id=[0-9]+&w_tkn=[a-z0-9]+$/i', $widgetKey) === 1;
    }

    function widget_button_id_is_valid($widgetButtonId)
    {
        return preg_match('/^[1-' . self::NUMBER_OF_BUTTON_STYLES . '][1-' . self::NUMBER_OF_BUTTONS . ']$/', $widgetButtonId) === 1;
    }

    function widget_button_url_is_valid($widgetButtonURL)
    {
        return wp_http_validate_url($widgetButtonURL) !== false;
    }
		 
		 
    function add_widget_stylesheet()
    {
        wp_register_style('myStyleSheets2', 'https://www.freetobook.com/affiliates/dynamicWidget/styles/widget-css.php?' . $this->widget_key);
        wp_enqueue_style('myStyleSheets2');
    }

    function add_admin_widget_stylesheet()
    {
        wp_enqueue_style('thickbox');
    }
		
		function add_widget_scripts() 
		{
			if (!is_admin()) {
				// instruction to only load if it is not the admin area
				// register your script location, dependencies and version
				 wp_register_script(
				     'freetobook-js',
                     'https://www.freetobook.com/affiliates/dynamicWidget/js/wordpress-widget-js.php?' . $this->widget_key,
                     array(),
                     '1.0'
                 );

			   // enqueue the script
			   wp_enqueue_script('freetobook-js');
			}
			else
			{
			
			   wp_register_script('freetobook-js', plugins_url('ftb_admin.js', __FILE__), array(), '1.0');
			   wp_enqueue_script('freetobook-js');			
			   wp_enqueue_script('media-upload');
			   wp_enqueue_script('thickbox');
			}
		}

		 
		 
		 
		 function load_widgets()
		 {
			 	register_widget( 'FreetobookWidget' );
		 }
		 
		 function get_widget_html()
		 {
			 $resultPage = 'https://portal.freetobook.com/reservations?'. $this->widget_key;
			 
			 if (empty($this->widget_key)) {
				return '';
			 }
			 
			switch ($this->widget_style)
			{
                case self::WIDGET_STYLE_CUSTOM:
			 	$html='<div id="f2b-widget" style="height:auto;">
						<a href="'. esc_url($resultPage) .'"><img src="' . esc_url($this->widget_button_url) .  '"></a>
						
						</div> ';
				break;
				
                case self::WIDGET_STYLE_BUTTON:
				$st=substr($this->widget_button_id,0,1);
				$bt=substr($this->widget_button_id,1,1);
			 	$html='<div id="f2b-widget" style="height:auto;">
						<a href="'. esc_url($resultPage) .'"><img src="' . plugins_url('/stock_buttons/style' . $st .'/btn' . $bt . '.gif', __FILE__) . '"></a>
						
						</div> ';
				break;
			default:
				$resultPage='https://portal.freetobook.com/widget-redir?'. $this->widget_key;
				$html='
			<div id="f2b-widget">
			 	<div>
					<form action="' . esc_url($resultPage) . '" id="f2b_search_form" name="f2b_search_form" method="POST">
					 <div id="cin">
					 	<strong>Check In date:</strong>
					 	<div class="cin-box">
							<div id="f2b-calendar" style="margin-top:2px">
								<img style="cursor: pointer;" alt="calendar icon"
									 src="https://www.freetobook.com/affiliates/dynamicWidget/images/calendar.gif"
								  	id="cp_opener_f2b_search_cal" width="16" border="0" height="15">
							</div>
							 <input value="dates" name="search_stage" type="hidden">
							 <input value="" name="referrer" type="hidden" id="f2b-widget-referrer">
							 <input value="2011-04-20" id="checkIn" name="checkIn" type="hidden">
							 <input size="11" readonly="readonly" id="checkInDisplay" type="text" style="margin-top:1px">			
						 </div>
					</div>
					<div id="duration">
						<div class="label"><strong>Nights:</strong></div>
						<div class="duration-box">
						<input maxlength="2" size="2" id="stayLength" name="stayLength" class="stayLength" type="text"  style="margin-top:1px">
						</div>
					</div>
					<div class="searchButtonContainer">
						<input value="" class="searchButton" type="submit">
				 	</div>
					</form>
				</div>
			</div>';
			 
			}
			 
			 return $html; 
		 }
		 
		 
		 function widget($args,$instance) 
		 {
			extract($args);
			echo $before_widget;
			echo $this->get_widget_html();
			echo $after_widget; 
		 }

		function form($instance) 
		{
		// outputs the options form on admin
		}
				 
		function update($new_instance, $old_instance) 
		{
			// processes widget options to be saved
		}

		 
    }
 
} 


if (class_exists("FreetobookWidget")) 
{
    $ftb_widget = new FreetobookWidget();
}


if (isset($ftb_widget)) 
{
	add_filter('plugin_action_links', array(&$ftb_widget, 'add_settings_link'), 10, 2 );
	add_action('admin_menu',array(&$ftb_widget,'add_settings_menu'));
	add_action('widgets_init',array(&$ftb_widget,'load_widgets'));
    add_action('wp_print_styles', array(&$ftb_widget,'add_widget_stylesheet'));
    add_action('admin_print_styles', array(&$ftb_widget,'add_admin_widget_stylesheet'));
	add_action('init', array(&$ftb_widget,'add_widget_scripts'));
}
