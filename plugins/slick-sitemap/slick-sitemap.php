<?php
/*
Plugin Name: Slick Sitemap
Plugin URI: http://pengbos.com/blog/slick-html-sitemap
Description: <a href="http://pengbos.com/blog/slick-html-sitemap" target="_blank">Slick Sitemap</a> Adds an HTML (Not XML) sitemap of your blog by entering the shortcode [slick-sitemap]. A plugin from <a href="http://pengbos.com/" target="_blank">Plugins and Themes: Pengbos.com</a>.
Version: 2.0.0
Author: Pengbo Tang
Author URI: http://pengbos.com

Contributors:
	Pengbo Tang, host of the pengbos.com http://pengbos.com - Plugin author
        Matt Everson of Astuteo, LLC http://astuteo.com/slickmap - CSS Creator

Copyright 2011=2015 Pengbo Tang, host of the Pengbos.com (http://pengbos.com)

License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
*/


define('SLICKMAP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

add_action('admin_menu', 'wpsm_add_menu');
add_action('admin_init', 'wpsm_reg_function' );

function wpsm_reg_function() {
	register_setting( 'wpsm-settings-group', 'wpsm_menu' );
	register_setting( 'wpsm-settings-group', 'wpsm_column' );
        register_setting( 'wpsm-settings-group', 'wpsm_utility_menu' );
}

function wpsm_add_menu() {
    $page = add_options_page('Slick Sitemap', 'Slick Sitemap', 'administrator', 'wpsm_menu', 'wpsm_menu_function');
}

wp_register_style('slickmap.css', SLICKMAP_PLUGIN_URL . '/slickmap.css');
wp_enqueue_style('slickmap.css');


function slick_sitemap_shortcode_handler($args)
{    
	if( is_feed() )
		return '';

		$atts = shortcode_atts(
								array(
									'sitemap_menu' => '',
									'utility_menu' => '',
									'column'=>'',
								), $args);
		
		$sitemap_menu_name = $atts['sitemap_menu'];
        $column = $atts['column'];
        $utility_menu_name = $atts["utility_menu"];	
		
		$menu = get_option('wpsm_menu');
		if(!empty($sitemap_menu_name)){
			$menu_object = wp_get_nav_menu_object( $sitemap_menu_name );	
			$menu = $menu_object->term_id;
		}
		$utility_menu = get_option("wpsm_utility_menu");
		if(!empty($utility_menu_name)){
			$utility_menu_object = wp_get_nav_menu_object( $utility_menu_name ); 
			$utility_menu = $utility_menu_object->term_id;
		}
		
        if(empty($column)){
			$column = get_option('wpsm_column');
		}

        if(isset($menu)||isset($utility_menu)){
            $defaults = array( 'menu' => '', 'container' => 'div', 'container_class' => '', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
            'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth' => 0, 'walker' => '', 'theme_location' => '' );

            $args = wp_parse_args( $args, $defaults );
            $args = apply_filters( 'wp_nav_menu_args', $args );
            $args = (object) $args;
        }
        
        
        $menu_list="";
        if(isset($utility_menu)){
             $utility_menu_items = wp_get_nav_menu_items($utility_menu);
             $menu_list .= '<ul id="utilityNav">';
             $menu_list .= walk_nav_menu_tree($utility_menu_items, $args->depth,$args );
             $menu_list .= '</ul>';
        }
        if(isset($menu)){
            if(!isset($column)){
                $column=8;
            }

         $menu_items = wp_get_nav_menu_items($menu);

         $menu_list .= '<ul id="primaryNav" class="col'.$column.'">';
         $menu_list .= '<li id="home"><a href="'.get_option("home").'">'.get_option("blogname").'</a></li>';
         $menu_list .= walk_nav_menu_tree($menu_items, $args->depth,$args );

         $menu_list .= '</ul>';
        }else{
            $html='no settings for sitemap';
            $menu_list= '<ul id="primaryNav" class="col8">'. $html .'</ul>';
        }
    // $menu_list now ready to output

        return $menu_list;
}
/*
	Add the sitemap when shortcode is encountered
*/
add_shortcode('slick-sitemap', 'slick_sitemap_shortcode_handler');

function wpsm_menu_function() {
?>
<div class="wrap">
<h2>Slick Sitemap</h2>
 
<form method="post" action='options.php'>
    <?php settings_fields( 'wpsm-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Default Sitemap Menu</th>
        <td>
        <select name="wpsm_menu" id="wpsm_menu"> 
			 <option value="">Select a Menu</option> 
 			<?php 
 				$menu = get_option('wpsm_menu');
  				$allmenus=  wp_get_nav_menus(); 
  				foreach ($allmenus as $m) {
  					$option = '<option value="'.$m->term_id.'"';
  					if ($menu == $m->term_id) $option .= ' selected="selected">';
  					else { $option .= '>'; }
					$option .= $m->name;
					$option .= '</option>';
					echo $option;
  				}
 			?>
		</select>

        </tr>
    	
        <tr valign="top">
        <th scope="row">Default Columns</th>
        <td>
        <label>
        <?php $columns = get_option('wpsm_column'); ?>
        <select name="wpsm_column" id="wpsm_column">
        	<option value="1" <?php if($columns == '1') echo 'selected="selected"'; ?>>1</option>
        	<option value="2" <?php if($columns == '2') echo 'selected="selected"'; ?> >2</option>
        	<option value="3" <?php if($columns == '3') echo 'selected="selected"'; ?> >3</option>
        	<option value="4" <?php if($columns == '4') echo 'selected="selected"'; ?> >4</option>
        	<option value="5" <?php if($columns == '5') echo 'selected="selected"'; ?> >5</option>
        	<option value="6" <?php if($columns == '6') echo 'selected="selected"'; ?> >6</option>
        	<option value="7" <?php if($columns == '7') echo 'selected="selected"'; ?> >7</option>
        	<option value="8" <?php if($columns == '8') echo 'selected="selected"'; ?> >8</option>
        	<option value="9" <?php if($columns == '9') echo 'selected="selected"'; ?> >9</option>
                <option value="10" <?php if($columns == '10') echo 'selected="selected"'; ?> >10</option>
        </select>
        </label>
        </tr>
        
      <tr valign="top">
        <th scope="row">Default Utility Menu</th>
        <td>
        <select name="wpsm_utility_menu" id="wpsm_utility_menu"> 
			 <option value="">Select a Menu for Utility</option> 
 			<?php 
 				$menu = get_option('wpsm_utility_menu');
  				$allmenus=  wp_get_nav_menus(); 
  				foreach ($allmenus as $m) {
  					$option = '<option value="'.$m->term_id.'"';
  					if ($menu == $m->term_id) $option .= ' selected="selected">';
  					else { $option .= '>'; }
					$option .= $m->name;
					$option .= '</option>';
					echo $option;
  				}
 			?>
		</select>

        </tr>
    </table>
 
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
 
</form>
</div>
<?php } ?>