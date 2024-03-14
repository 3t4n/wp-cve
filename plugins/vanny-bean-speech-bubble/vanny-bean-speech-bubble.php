<?php
/*
Plugin Name: Vanny Bean Speech Bubble
Plugin URI: http://www.vannybean.com/pages/baby-speech-bubble.htm
Version: v0.1
Author: <a href="http://www.vannybean.com">VannyBean</a>
Description: Allows you to enter captions inside of speech bubbles on top of images.

Copyright 2010  James Charlesworth  (email : james DOT charlesworth [a t ] g m ail DOT com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributded in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



if (!class_exists("VannyBeanSpeechBubble")) {
	class VannyBeanSpeechBubble {
                var $adminOptionsName = "VannyBeanSpeechBubbleAdminOptions";




		function VannyBeanSpeechBubble() { //constructor

		}

                function init() {
                    $this->getAdminOptions();

                }



                function getAdminOptions() {
                    $VannyBeanSpeechBubbleAdminOptions = array('color' => 'black',
                        'font' => 'arial');

                    $VannyBeanSpeechBubbleOptions = get_option($this->adminOptionsName);
                    if (!empty($VannyBeanSpeechBubbleOptions)) {
                        foreach ($VannyBeanSpeechBubbleOptions as $key => $option)
                            $VannyBeanSpeechBubbleAdminOptions[$key] = $option;
                    }
                    update_option($this->adminOptionsName, $VannyBeanSpeechBubbleAdminOptions);



                    return $VannyBeanSpeechBubbleAdminOptions;


                }


                function printAdminPage() {

                $VannyBeanSpeechBubbleOptions = $this->getAdminOptions();

                    if (isset($_POST['update_VannyBeanSpeechBubbleSettings'])) {

                        if (isset($_POST['color'])) {
                            $VannyBeanSpeechBubbleOptions['color'] = $_POST['color'];
                        }

                        if (isset($_POST['font'])) {
                            $VannyBeanSpeechBubbleOptions['font'] = $_POST['font'];
                        }
                        update_option($this->adminOptionsName, $VannyBeanSpeechBubbleOptions);
                         ?>
                        <div class="updated"><p><strong><?php _e("Settings Updated.", "VannyBeanSpeechBubble");?></strong></p></div>
                         <?php
                    }

                    ?>
                        <div class=wrap>
                            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                                <h2>Vanny Bean Speech Bubble</h2>


                                <label for="font">Font:</label><br />
                                <input type="text" id="font" value="<?php echo  _e(apply_filters('format_to_edit',$VannyBeanSpeechBubbleOptions['font']), 'VannyBeanSpeechBubble') ?>" name="font" />
                                 <br /><br />
                                 <label for="color">Color:</label><br />
                                <input type="text" id="color" value="<?php echo  _e(apply_filters('format_to_edit',$VannyBeanSpeechBubbleOptions['color']), 'VannyBeanSpeechBubble') ?>"  name="color" />
                                <br />
                              <div class="submit">
                                     <input type="submit" name="update_VannyBeanSpeechBubbleSettings" value="<?php _e('Update Settings', 'VannyBeanSpeechBubble') ?>" />
                              </div>
                            </form>
                        </div>
                        <?


                }


                /*mce stuff*/
function myplugin_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;

   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {

     add_filter("mce_external_plugins", array(&$this, 'add_myplugin_tinymce_plugin'),1 );
     add_filter('mce_buttons', array(&$this, 'register_myplugin_button'),1);
   }
}

function register_myplugin_button($buttons) {

   array_push($buttons, "SpeechBubble");
   return $buttons;
}

// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_myplugin_tinymce_plugin($plugin_array) {

   $plugin_array['SpeechBubble'] =  get_bloginfo('wpurl') . '/wp-content/plugins/vanny-bean-speech-bubble/tinymce/speechbubble/editor_plugin_src.js';
   return $plugin_array;
}

// init process for button control




                function addHeaderCode() {
                    echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/vanny-bean-speech-bubble/css/vanny-bean-speech-bubble.css" />' . "\n";
        

                }

                function addFooterCode()
                {
              //      echo 'test';
                }




	}//End Class VannyBeanSpeechBubble

} //endif

//Initialize the admin panel
if (!function_exists("VannyBeanSpeechBubble_ap")) {
    function VannyBeanSpeechBubble_ap() {
        global $vb_speech_bubble_plugin;
        if (!isset($vb_speech_bubble_plugin)) {
         
            return;
        }
     
        if (function_exists('add_options_page')) {
            add_options_page('Vanny Bean Speech Bubble', 'Speech Bubble', 9, basename(__FILE__), array(&$vb_speech_bubble_plugin, 'printAdminPage'));
        }

    }
}



if (class_exists("VannyBeanSpeechBubble")) {
  
	$vb_speech_bubble_plugin = new VannyBeanSpeechBubble();
}


//Actions and Filters
if (isset($vb_speech_bubble_plugin)) {
	//Actions

//var_dump($vb_speech_bubble_plugin);

    	add_action('wp_head', array(&$vb_speech_bubble_plugin, 'addHeaderCode'),1);
        add_action('init',  array(&$vb_speech_bubble_plugin, 'myplugin_addbuttons'),1);
	//add_action('wp_footer', array(&$vb_speech_bubble_plugin, 'addFooterCode'),1);
        //add_action('admin_menu', 'VannyBeanSpeechBubble_ap');
        add_action('activate_vanny-bean-speech-bubble/vanny-bean-speech-bubble.php', array(&$vb_speech_bubble_plugin, 'init'));

        

        ////Filters
}


