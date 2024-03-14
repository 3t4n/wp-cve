<?php
/*
Plugin Name:  Fonts Add 
Plugin URI:
Description: Add google fonts to your wordpress website in few minutes.
Version: 1.0
License:GplV2
Author: smuzthemes
*/


add_action( 'admin_menu', 'wsgf_menu');

function wsgf_menu(){
	add_menu_page( 'Google Fonts', 'Google Fonts','administrator', 'wsgf_option', 'wsgf_settings_page', '', 18);
	add_action( 'admin_init', 'wsgf_register_settings' );

}

function wsgf_sanitize_options($value){
  $value = stripslashes($value);
  $value = filter_var($value,FILTER_SANITIZE_STRING);

  return $value;
}


function wsgf_register_settings(){

	register_setting('wsgf-settings-group' , 'wsgf_enable_plugin','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h1','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h2','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h3','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h4','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h5','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_h6','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_p','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_ol','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_ul','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_sp','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_link','wsgf_sanitize_options');

	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_abbr','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_address','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_blockquote','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_caption','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_time','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_gfont_figure','wsgf_sanitize_options');


	register_setting('wsgf-settings-group' , 'wsgf_select_color_h1','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_h2','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_h3','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_h4','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_h5','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_h6','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_p','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_ol','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_ul','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_sp','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , 'wsgf_select_color_link','wsgf_sanitize_options');
	register_setting('wsgf-settings-group' , '');
}


function wsgf_admin_style(){

 wp_register_style( 'custom_tab_admin_css', plugin_dir_url( __FILE__ )  . 'css/style.css', false );
 wp_register_script( 'custom_tab_admin_js', plugin_dir_url(__FILE__) . 'js/admin.js', array('wp-color-picker'), true );

 wp_enqueue_style( 'wp-color-picker' );
 wp_enqueue_style('custom_tab_admin_css');
 wp_enqueue_script('custom_tab_admin_js');
}
add_action( 'admin_enqueue_scripts', 'wsgf_admin_style' );

require("public/wsgf-load-style.php");


function wsgf_settings_page(){?>

<div id="tabs-container">
     <form method="post" action="options.php" >
           <?php settings_fields('wsgf-settings-group');?>
    <ul class="tabs-menu">
        <li class="current"><a href="#tab-1">Google Font</a></li>
        <li><a href="#tab-2">Special Elements</a></li>
        <li><a href="#tab-3">Color</a></li>
    </ul>
    <div class="tab">
        <div id="tab-1" class="tab-content">

        <table class="form-table">

        <tr valign='top'>
        <th scope='row'><?php _e('Enable Plugin :');?></th>
        <td>
            <div class="onoffswitch">
                     <input type="checkbox" name="wsgf_enable_plugin" class="onoffswitch-checkbox"  id="myonoffswitch" value='1'<?php checked(1, get_option('wsgf_enable_plugin')); ?> />
                     <label class="onoffswitch-label" for="myonoffswitch">
                     <span class="onoffswitch-inner"></span>
                     <span class="onoffswitch-switch"></span>
                     </label>
                    </div>
        </td>
         </tr>


        <tr valign="top">
        <th scope="row"><?php _e('H1 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h1">
          <input id='wsgf_fonth1' type="text"  name="wsgf_select_gfont_h1"  class="wsgf_fh1" value="<?php echo get_option('wsgf_select_gfont_h1'); ?>"/>
          </label>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('H2 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h2">
          <input id='wsgf_fonth2' type="text"  name="wsgf_select_gfont_h2"  class="wsgf_fh2" value="<?php echo get_option('wsgf_select_gfont_h2'); ?>"/>
          </label>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('H3 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h3">
          <input id='wsgf_fonth3' type="text"  name="wsgf_select_gfont_h3"  class="wsgf_fh3" value="<?php echo get_option('wsgf_select_gfont_h3'); ?>"/>
          </label>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('H4 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h4">
          <input id='wsgf_fonth4' type="text"  name="wsgf_select_gfont_h4"  class="wsgf_fh4" value="<?php echo get_option('wsgf_select_gfont_h4'); ?>"/>
          </label>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('H5 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h5">
          <input id='wsgf_fonth5' type="text"  name="wsgf_select_gfont_h5"  class="wsgf_fh5" value="<?php echo get_option('wsgf_select_gfont_h5'); ?>"/>
          </label>
        </td>
      </tr>


       <tr valign="top">
        <th scope="row"><?php _e('H6 Font'); ?></th>
        <td><label for="wsgf_select_gfont_h6">
          <input id='wsgf_fonth6' type="text"  name="wsgf_select_gfont_h6"  class="wsgf_fh6" value="<?php echo get_option('wsgf_select_gfont_h6'); ?>"/>
          </label>
        </td>
      </tr>


       <tr valign="top">
        <th scope="row"><?php _e('Paragraph Font'); ?></th>
        <td><label for="wsgf_select_gfont_p">
          <input id='wsgf_fontp' type="text"  name="wsgf_select_gfont_p"  class="wsgf_fp" value="<?php echo get_option('wsgf_select_gfont_p'); ?>"/>
          </label>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><?php _e('Ordered List Font'); ?></th>
        <td><label for="wsgf_select_gfont_ol">
          <input id='wsgf_fontol' type="text"   class="wsgf_fol" /> 
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('Un Ordered List Font'); ?></th>
        <td><label for="wsgf_select_gfont_ul">
          <input id='wsgf_fontul' type="text"    class="wsgf_ful" />
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('Span Font'); ?></th>
        <td><label for="wsgf_select_gfont_sp">
          <input id='wsgf_fontsp' type="text"   class="wsgf_fusp" />
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('Link Font'); ?></th>
        <td><label for="wsgf_select_gfont_link">
          <input id='wsgf_fontlink' type="text"    class="wsgf_flink" />
          </label>

         <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>

         </table>

              <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />

</div>


<div id="tab-2" class="tab-content">
        
      <table class="form-table">
      
        <tr valign="top">
        <th scope="row"><?php _e('Abbreviation (abbr)'); ?></th>
        <td><label for="wsgf_select_gfont_abbr">
          <input id='wsgf_fontabbr' type="text"    class="wsgf_fabbr" />
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Address Font'); ?></th>
        <td><label for="wsgf_select_gfont_address">
          <input id='wsgf_fontaddress' type="text"   class="wsgf_faddress" />
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Block Qoutes (blockquote) Font'); ?></th>
        <td><label for="wsgf_select_gfont_blockquote">
          <input id='wsgf_fontblockquote' type="text"    class="wsgf_fblockquote" />
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Caption (caption) Font'); ?></th>
        <td><label for="wsgf_select_gfont_caption">
          <input id='wsgf_fontcaption' type="text"   class="wsgf_fcaption" />
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e('Time (time) Font'); ?></th>
        <td><label for="wsgf_select_gfont_time">
          <input id='wsgf_fonttime' type="text"    class="wsgf_ftime" />
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>


        <tr valign="top">
        <th scope="row"><?php _e('Picture Caption (figure) Font'); ?></th>
        <td><label for="wsgf_select_gfont_figure">
          <input id='wsgf_fontfigure' type="text"   class="wsgf_ffigure" />
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
        </tr>

        </table>
               <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />

        
        </div>

        <div id="tab-3" class="tab-content">
        
        <table class="form-table">

        <tr valign="top">
        <th scope="row"><?php _e('H1 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h1">
          <input id='wsgf_fonth1c' type="text"  name="wsgf_select_color_h1"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h1'); ?>"/>
          </label>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('H2 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h2">
          <input id='wsgf_fonth2c' type="text"  name="wsgf_select_color_h2"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h2'); ?>"/>
          </label>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('H3 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h3">
          <input id='wsgf_fonth3c' type="text"  name="wsgf_select_color_h3"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h3'); ?>"/>
          </label>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('H4 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h4">
          <input id='wsgf_fonth4c' type="text"  name="wsgf_select_color_h4"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h4'); ?>"/>
          </label>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('H5 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h5">
          <input id='wsgf_fonth5c' type="text"  name="wsgf_select_color_h5"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h5'); ?>"/>
          </label>
        </td>
      </tr>


       <tr valign="top">
        <th scope="row"><?php _e('H6 Font Color'); ?></th>
        <td><label for="wsgf_select_color_h6">
          <input id='wsgf_fonth6c' type="text"  name="wsgf_select_color_h6"  class="color_picker" value="<?php echo get_option('wsgf_select_color_h6'); ?>"/>
          </label>
        </td>
      </tr>


       <tr valign="top">
        <th scope="row"><?php _e('Paragraph Font Color'); ?></th>
        <td><label for="wsgf_select_color_p">
          <input id='wsgf_fontpc' type="color" disabled name="wsgf_select_color_p"   value="<?php echo get_option('wsgf_select_color_p'); ?>"/>
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row"><?php _e('Ordered List Font Color'); ?></th>
        <td><label for="wsgf_select_color_ol">
          <input id='wsgf_fontolc' type="color" disabled name="wsgf_select_color_ol"   value="<?php echo get_option('wsgf_select_color_ol'); ?>"/>
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('Un Ordered List Font Color'); ?></th>
        <td><label for="wsgf_select_color_ul">
          <input id='wsgf_fontulc' type="color" disabled name="wsgf_select_color_ul"   value="<?php echo get_option('wsgf_select_color_ul'); ?>"/>
          </label>
          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>


      <tr valign="top">
        <th scope="row"><?php _e('Span Font'); ?></th>
        <td><label for="wsgf_select_color_sp">
          <input id='wsgf_fontspc' type="color" disabled name="wsgf_select_color_sp"   value="<?php echo get_option('wsgf_select_color_sp'); ?>"/>
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>



      <tr valign="top">
        <th scope="row"><?php _e('Link Font'); ?></th>
        <td><label for="wsgf_select_color_link">
          <input id='wsgf_fontlink' type="color" disabled name="wsgf_select_color_link"   value="<?php echo get_option('wsgf_select_color_link'); ?>"/>
          </label>

          <p class='description'><?php _e(' <b> This is Premium Feature <a href="http://web-settler.com/wordpress-google-fonts/" target=_blank>Buy Premium Version</a> </b> ') ;?></p>
        </td>
      </tr>

         </table>
               <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />

         </form>
        </div>
        
    </div>
</div>
<?php 
}
?>