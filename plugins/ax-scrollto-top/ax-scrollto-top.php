<?php
/*
Plugin Name: Ax ScrollTo Top
Plugin URI: http://www.annupex.com/Plugins/ax-scrollto-top/
Description: Add a Scroll to top button in the website footer. 
Author: H Ranjan
Version: 1.0.0
Author URI: http://www.annupex.com
License: GPL2
*/

/*  Copyright 2012  H Ranjan  (email : himanshu0ranjan@gmail.com)

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
function ax_scrollto_top_js() {

	    wp_enqueue_script( 'axScrollUp', plugins_url().'/ax-scrollto-top/js/ax-scrollto-top.js',array('jquery'), '1.0.0', true );

}    
 
add_action('wp_enqueue_scripts', 'ax_scrollto_top_js');

function ax_scrollto_top_css() {
    
    $ax_icon_select      = get_option('ax_icon_select');
    if($ax_icon_select == 'none'){	

	    wp_enqueue_style( 'axScrollToTop', plugins_url().'/ax-scrollto-top/ax-scrollto-top-css.php',array(), '1.0.0', 'all' );
    } else {
	    wp_enqueue_style( 'axScrollToTop', plugins_url().'/ax-scrollto-top/ax-scrollto-top.css',array(), '1.0.0', 'all' );
	}
}    
 
add_action('wp_enqueue_scripts', 'ax_scrollto_top_css');


function ax_scrollto_top () {
    $ax_icon_select      = get_option('ax_icon_select');

    if($ax_icon_select == 'none'){	

	    $ax_width            = get_option('ax_width');
    	    $ax_height           = get_option('ax_height');
   	    $ax_background_color = get_option('ax_background_color');
            $ax_text_color       = get_option('ax_text_color');

	    if($ax_width && $ax_height && $ax_background_color && $ax_text_color ){	

     	     echo '<a href="#" id="axScrollTo" class="axScrollToTop" title="valu" >Scroll To Top</a>';

	    } else {

	       	     echo '<a href="#" id="axScrollTo" class="axScrollToTop">Scroll To Top</a>';
		
            }	

	    
    }else{ 	
	echo '<a href="#" id="axScrollTo" class="axScrollToTop" ><img src="' .plugins_url( ''.'ax-scrollto-top/images/'.$ax_icon_select.'.png' , dirname(__FILE__) ). '"></a>';
    }
}
add_action('wp_footer', 'ax_scrollto_top', 9999);

add_action( 'admin_menu', 'ax_scrollto_top_menu' );
add_action('admin_init', 'ax_scrollto_top_init');

function ax_scrollto_top_init(){
	register_setting( 'ax-scrollto-top-group', 'ax_width' );
	register_setting( 'ax-scrollto-top-group', 'ax_height' );
	register_setting( 'ax-scrollto-top-group', 'ax_background_color' );
	register_setting( 'ax-scrollto-top-group', 'ax_text_color' );
	register_setting( 'ax-scrollto-top-group', 'ax_icon_select' );
	register_setting( 'ax-scrollto-top-group', 'ax_font_size' );
	register_setting( 'ax-scrollto-top-group', 'ax_padding' );

}

function ax_scrollto_top_menu() {
	add_options_page( 'Ax ScrollTo Top Options', 'Ax ScrollTo Top', 'administrator', 'ax-scrollto-top', 'ax_scrollto_top_options' );
}?>
<?php
function ax_scrollto_top_options() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Ax ScrollTo Top Settings</h2>


		<form action="options.php" method="post">
		<?php settings_fields('ax-scrollto-top-group'); ?>
		<?php do_settings_sections('ax-scrollto-top-group'); ?>
		<h3>Select an icon</h3>
		<table class="form-table">

			<tr valign="top">
        			<td><input type="radio" id="ax_icon_select_1" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '1') echo 'checked="checked"'; if(get_option('ax_icon_select') != true) { echo('checked="checked"'); }?> value="1" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'1.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_2" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '2') echo 'checked="checked"'; ?> value="2" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'2.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_3" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '3') echo 'checked="checked"'; ?> value="3" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'3.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_4" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '4') echo 'checked="checked"'; ?> value="4" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'4.png' , dirname(__FILE__) ). '" >' ?>
    				</td>


        			<td><input type="radio" id="ax_icon_select_5" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '5') echo 'checked="checked"'; ?> value="5" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'5.png' , dirname(__FILE__) ). '" >' ?>
    				</td>

        		</tr>
        
       			<tr valign="top">
				
        			<td><input type="radio" id="ax_icon_select_6" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '6') echo 'checked="checked"'; ?> value="6" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'6.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_7" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '7') echo 'checked="checked"'; ?> value="7" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'7.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_8" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '8') echo 'checked="checked"'; ?> value="8" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'8.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_9" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '9') echo 'checked="checked"'; ?> value="9" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'9.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_10" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '10') echo 'checked="checked"'; ?> value="10" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'10.png' , dirname(__FILE__) ). '" >' ?>
    				</td>

        		</tr>
        
       			<tr valign="top">

        			<td><input type="radio" id="ax_icon_select_11" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '11') echo 'checked="checked"'; ?> value="11" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'11.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_12" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '12') echo 'checked="checked"'; ?> value="12" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'12.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_13" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '13') echo 'checked="checked"'; ?> value="13" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'13.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_14" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '14') echo 'checked="checked"'; ?> value="14" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'14.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_15" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '15') echo 'checked="checked"'; ?> value="15" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'15.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        		</tr>
        
       			<tr valign="top">


        			<td><input type="radio" id="ax_icon_select_16" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '16') echo 'checked="checked"'; ?> value="16" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'16.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_17" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '17') echo 'checked="checked"'; ?> value="17" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'17.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_18" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '18') echo 'checked="checked"'; ?> value="18" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'18.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        			<td><input type="radio" id="ax_icon_select_19" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '19') echo 'checked="checked"'; ?> value="19" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'19.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
				
        			<td><input type="radio" id="ax_icon_select_20" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '20') echo 'checked="checked"'; ?> value="20" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'20.png' , dirname(__FILE__) ). '" >' ?>
    				</td>
        		</tr>
        
       			<tr valign="top">

        			<td><input type="radio" id="ax_icon_select_21" name="ax_icon_select" <?php if(get_option('ax_icon_select') == '21') echo 'checked="checked"'; ?> value="21" /><?php echo '<img src="' .plugins_url( ''.'ax-scrollto-top/images/'.'21.png' , dirname(__FILE__) ). '" >' ?>
    				</td>

        			<td><input type="radio" id="ax_icon_select_22" name="ax_icon_select" <?php if(get_option('ax_icon_select') == 'none') echo 'checked="checked"'; ?> value="none" /> None
    				</td>

				
        		</tr>
</table>
			<h3>Fill below details only when you have selected the option 'None' in above list.</h3>
		<table class="form-table">
        	
		        <tr valign="top">
        		<th scope="row">Width</th>
        			<td><input type="text" name="ax_width" value="<?php echo get_option('ax_width'); ?>" /> px</td>
        		</tr>
			<tr valign="top">
        		
			<th scope="row">Height</th>
        			<td><input type="text" name="ax_height" value="<?php echo get_option('ax_height'); ?>" /> px</td>
        		</tr>
        
       			<tr valign="top">
        		<th scope="row">Background Color</th>
        			<td><input type="text" name="ax_background_color" value="<?php echo get_option('ax_background_color'); ?>" /></td>
        		</tr>
			<tr valign="top">
        		<th scope="row">Text Color</th>
        			<td><input type="text" name="ax_text_color" value="<?php echo get_option('ax_text_color'); ?>" /></td>
        		</tr>
			<tr valign="top">
        		<th scope="row">Font Size</th>
        			<td><input type="text" name="ax_font_size" value="<?php echo get_option('ax_font_size'); ?>" /></td>
        		</tr>
			<tr valign="top">
        		<th scope="row">Padding</th>
        			<td><input type="text" name="ax_padding" value="<?php echo get_option('ax_padding'); ?>" /></td>
        		</tr>



    		</table>	
		<p class="submit">
			<input name="Submit" class="button-primary" type="submit" value="<?php _e('Save Changes'); ?>" />
		</p>	
		</form>
	
      </div>
<?php }?>