<?php
      /* 
      Plugin Name: Hi-Counter-Max
      Plugin URI: 
      Description: Displays a hit counter on your blog. Visit Settings -> Bliss Hit Counter to configure the plug-in.
      Version: 2.0
      Author: Baku
      Author URI: http://www.hitcountermax.com
      */
	  
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
      

      perform_install();
      
      register_deactivation_hook(__FILE__, 'perform_uninstall');
      require_once('class.resource.php');
      class HitCounter extends HookdResource {
          
          function __construct($arg) {
              if (get_option('wphcu_display_footer')) {
                  add_action('wp_footer', array(&$this,'display'));
              }
              register_activation_hook(__FILE__, array(&$this, '_hookd_activate'));
              register_deactivation_hook(__FILE__, array(&$this, '_hookd_deactivate'));
              parent::__construct($arg);
          }
          function HitCounter() {
              $args = func_get_args();
              call_user_func_array(array(&$this, '__construct'), $args);
          }
          
          function counter() {
              $hits = get_option('wphcu_data');
              if (is_404()) {
                  if (!get_option('wphcu_count_404')) {
                      return;
                  }
              }
              
              if (get_option('wphcu_count_only_unique')) {
                  if (!$_COOKIE['wphcu_seen']) {
                      setCookie("wphcu_seen", "1", time() + (3600 * 24));
                  } else {
                      return;
                  }
              }
              
              if (is_admin()) {
                  if (get_option('wphcu_count_admin')) {
                      update_option('wphcu_data', $hits+1);
                  }
              } else {
              		$exclude_list = split("\n",get_option('wphcu_exclude_ips'));

              		if(!in_array($_SERVER['REMOTE_ADDR'],$exclude_list)) {
	                  update_option('wphcu_data', $hits+1);
	                }
              }
          }
          
          function display() {
              $hits = get_option('wphcu_data');
              $style = get_option('wphcu_style');
              $align = get_option('wphcu_align');
              if ($align) {
                  $alignment_options = ' align="'.$align.'"';
              }
              $form_css = get_option("wphcu_css");
			  echo '<style type="text/css">'.$form_css.'</style>';
              echo '<div class="hit-counter-max"'.$alignment_options.'>';
              //if (get_option('wphcu_pad_zeros') && strlen($hits) < 7) {
                  for ($i = 0; $i < (4 - strlen($hits)); $i++) {
                      echo "<img src='".WP_PLUGIN_URL."/hit-counter-max/designs/$style/0.gif'>";
                  }
             // }                       
              echo preg_replace('/(\d)/', "<img src='".WP_PLUGIN_URL."/hit-counter-max/designs/$style/$1.gif'>", $hits);
			  if (get_option('wphcu_display_credit') == 1) { 
			  	echo '<br /><small>Hit Counter provided by <a href="https://wordpress.org/plugins/hit-counter-max/">hit counter max</a></small>';
			  } else {
			  	echo "";
			  }
              echo '</div>';
          }
      }
      function perform_install() {
          global $wpdb;
          if (!get_option('wphcu_data')) {
              $migration = $wpdb->get_row( "SELECT hitcounter, imagename, flag FROM wp_imagecounter" );
              if ($migration) {
                  update_option('wphcu_data', $migration->hitcounter);
                  update_option('wphcu_style', 'Basic/' . $migration->imagename);
                  update_option('wphcu_display_footer', $migration->flag);
                  update_option('wphcu_display_credit', 0);
                  update_option('wphcu_count_only_unique', 0);
                  update_option('wphcu_check_update', 1);
                  $wpdb->query( "DROP TABLE wp_imagecounter" );
              }

              add_option('wphcu_data', 1);
              add_option('wphcu_style', 'Basic/2');
              add_option('wphcu_display_footer', 1);
              add_option('wphcu_display_credit', 0);
              add_option('wphcu_count_only_unique', 0);
			  add_option('wphcu_align', 'center');
              add_option('wphcu_check_update', 1);
			  add_option('wphcu_css', ".credits_off {display:none;}");
          }
      }
      
      function perform_uninstall() {
          delete_option('wphcu_data');
          delete_option('wphcu_style');
          delete_option('wphcu_display_footer');
          delete_option('wphcu_display_credit');
          delete_option('wphcu_count_only_unique');
          delete_option('wphcu_algin');
          delete_option('wphcu_check_update');
		  delete_option('wphcu_css');
      }

      include("settings_wphcu.php");

      class wHitCounter extends WP_Widget {
          function wHitCounter() {
              parent::__construct(false, $name = 'Bliss Hit Counter',array("description"=>"Hit Counter"));
          }

          function form($instance) {
              echo 'Please go to <a href="options-general.php?page=hit-counter-max">Settings -> Bliss Hit Counter</a> to configure this 

sidebar widget';
          }

          function update($new_instance, $old_instance) {
              return $new_instance;
          }

          function widget($args, $instance) {
              $hits = get_option('wphcu_data');
              $style = get_option('wphcu_style');
              $align = get_option('wphcu_align');
              
              if ($align) {
                  $alignment_options = ' align="'.$align.'"';
              }              
              extract( $args );
              $title = apply_filters('widget_title', $instance['title']);
              echo $before_widget;
              if ( $title )
                  echo $before_title . $title . $after_title;
			  $form_css = get_option("wphcu_css");
              echo '<style type="text/css">'.$form_css.'</style>';
              echo '<div class="hit-counter-max"'.$alignment_options.'>';
              //if (get_option('wphcu_pad_zeros') && strlen($hits) < 7) {
                  for ($i = 0; $i < (4 - strlen($hits)); $i++) {
                      echo "<img src='".WP_PLUGIN_URL."/hit-counter-max/designs/$style/0.gif'>";
                  }
              //

              echo preg_replace('/(\d)/', "<img src='".WP_PLUGIN_URL."/hit-counter-max/designs/$style/$1.gif'>", $hits);
              if (get_option('wphcu_display_credit') == 1) { 
			  	echo '<br /><small>Powered by <a href="https://wordpress.org/plugins/hit-counter-max/">Hit Counter Max</a></small>';
			  } else {
			  	echo '';
			  }
			  echo '</div>';
              echo $after_widget;
          }
      }


      add_action('widgets_init', create_function('', 'return register_widget("wHitCounter");'));
      $HitCounter = new HitCounter('8b8203326e2a9c70947a');

      add_action('wp', array(&$HitCounter, 'counter'));
?>
