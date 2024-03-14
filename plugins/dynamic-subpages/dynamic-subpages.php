<?php
/*
 * Plugin Name: Dynamic Subpages
 * Plugin URI: http://www.webfish.se/wp/plugins/dynamic-subpages
 * Version: 1.7.5
 * Description: Displays subpages for the current page
 * Author: Tobias Nyholm
 * Author URI: http://www.tnyholm.se
 * License: GPLv3
 * Copyright: Tobias Nyholm 2010
 */
/*
Copyright (C) 2010 Tobias Nyholm

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//load translations
load_plugin_textdomain('dynamic-subpages',false,"dynamic-subpages/languages");

class dynamicSubpageWidget extends WP_Widget {
  private $defaults = array(
                          'startlevel' => 1,
                          'useparenttitle' => 1,
                          'customtitle' => '',
  						  'depth'=> 2,
                          'exclude'=> '',  
                          'sort_column'=>'menu_order',
                          'sort_order'=>'asc',
  						  'link_title'=>0,
  						
                        );  
  
  function dynamicSubpageWidget() {
    $options = array(
                  'classname' => 'dynamicSubpageWidget',
                  'description' => __('Displays subpages for the current page.','dynamic-subpages')
                );
    $this->WP_Widget('dynamicSubpageWidget', 'Dynamic Subpage Widget', $options);             
    add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
  }
  
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
      
    global $post;
    $this_page_id=$post->ID;
    //if the current page is in the exclude list, abort
    $excludeArray=explode(",",$instance['exclude']);
    if(in_array($this_page_id,$excludeArray))
    	return;
    
    //get ids to the top.
    $hierarchy=get_post_ancestors($this_page_id);
    $hierarchy=array_reverse($hierarchy);
    $hierarchy[]=$this_page_id;
    //die(print_r($hierarchy,true));
    //the hierarchy is an array with ID where $hierarchy[0] is the root element with no parents

    //get the title
    $titleId=$hierarchy[$instance['startlevel']-1];
    
    //help variables to detimire css classes
    $ancestors=array();
    if(isset($post->ancestors)){//check if ancestors exists
    	$ancestors=$post->ancestors;
    }
    $parentID=$post->post_parent;
      
    $menu=array();
    for($i=$instance['startlevel'];$i<$instance['startlevel']+$instance['depth'];$i++){//for every level
    	if(isset($hierarchy[$i-1])){
    		if(in_array($hierarchy[$i-1],$excludeArray))//if the page is a subpage to a page in the exclude array, then abort
    			return;
    		//get subpages
    		$subpages=get_pages(array(
	  			'child_of'     => $hierarchy[$i-1],
	    		'parent'     => $hierarchy[$i-1],
	    		'sort_column'=>$instance['sort_column'],
    			'sort_order'=>$instance['sort_order']
    		));
    		foreach($subpages as $page){
    			if(!in_array($page->ID,$excludeArray)){//if not in the exclude list
    				$menu[$i][$page->ID]='<li class="page_item page-item-'.$page->ID.
    					//add some more css classes
    					(in_array($page->ID,$ancestors)?' current-page-ancestor current-menu-ancestor current_page_ancestor':'').
    					($page->ID==$parentID?' current-menu-parent current-page-parent current_page_parent':'').
    					($page->ID==$this_page_id?' current-menu-item current_page_item':'').
    					'">'."\n"
    				.' <a title="'.$page->post_title.'" href="'.get_permalink($page->ID).'">'.$page->post_title."</a>\n";
    			}
    		}
    	}
    }
  

    /*
     * Now is menu[i][j] (where startlevel<=i<startlevel+depth) an array with menu items
     */
    $output=$this->print_menu($menu,$instance['startlevel'],$instance['startlevel']+$instance['depth'],$hierarchy);
    	
    
	//if no output, dont print any html
    if (strlen($output) > 0) {
    	$titlePost=get_post($titleId);
    	if($instance['useparenttitle']==1){
    		//get the title
    		$title=$titlePost->post_title;
    	}
    	else{
    		$title=$instance['customtitle'];
    	}
    	echo $before_widget;
    	
    	if(strlen($title) > 0){
    		if($instance['link_title']==1){
    			//add a link
    			$title="<a href='".get_permalink($titleId)."' title='$title'>$title</a>";
    		}
    		echo $before_title.$title.$after_title;
    	}
    	echo "<ul id='dsp-widget' class='menu'>\n";
    	echo $output;
    	echo "</ul>\n";

    	echo $after_widget;
    }
  }
  
/**
 * Print the menu recrusivly
 * 
 */
  function print_menu(&$menu,$start,$stop,&$hierarchy){
  		if(!isset($menu[$start])){
  			return "";
  		}
  		$output="";
  		foreach($menu[$start] as $id=>$str){
  			$output.=$str;
  			if(in_array($id,$hierarchy) && $start+1<$stop && isset($menu[$start+1])){
  				$output.="<ul class='sub-menu'>\n".$this->print_menu($menu,$start+1,$stop,$hierarchy)."</ul>\n";
  			}
  			$output.="</li>";
  		}
  		return $output;
  }
  

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['startlevel'] = ($new_instance['startlevel']<1)?1:$new_instance['startlevel'];//must be > 0
    $instance['useparenttitle'] = $new_instance['useparenttitle'];
    $instance['customtitle'] = $new_instance['customtitle'];
    $instance['depth'] = $new_instance['depth'];
    $instance['exclude'] = $new_instance['exclude'];
	$instance['sort_column'] = $new_instance['sort_column'];
	$instance['sort_order']=$new_instance['sort_order'];
    $instance['link_title']=$new_instance['link_title'];
    
    return $instance;
  }
  
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, $this->defaults);
    $startlevel = $instance['startlevel'];
    $useparenttitle = $instance['useparenttitle'];
    $customtitle = $instance['customtitle'];
    $depth = $instance['depth'];
    $exclude=$instance['exclude'];
	$sortColomn=$instance['sort_column'];
	$sortMethod=$instance['sort_order'];
	$linkTitle=$instance['link_title'];
?>

  <p>
    <label for="<?php echo $this->get_field_id('useparenttitle'); ?>"><?php _e('Use parent title:','dynamic-subpages')?></label><br />
    <input type="radio" id="<?php echo $this->get_field_id('useparenttitle'); ?>" name="<?php echo $this->get_field_name('useparenttitle'); ?>" value="1" <?php if ($instance['useparenttitle'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','dynamic-subpages');?>
    <input type="radio" id="<?php echo $this->get_field_id('useparenttitle'); ?>" name="<?php echo $this->get_field_name('useparenttitle'); ?>" value="0" <?php if ($instance['useparenttitle'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','dynamic-subpages');?>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('customtitle'); ?>"><?php _e("Custom Title:",'dynamic-subpages');?></label><br />
      <input class="widefat" id="<?php echo $this->get_field_id('customtitle'); ?>" name="<?php echo $this->get_field_name('customtitle'); ?>" type="text" value="<?php echo esc_attr($customtitle); ?>" />
    
  </p>
    <p>
    <label for="<?php echo $this->get_field_id('link_title'); ?>"><?php _e("Use title as link to start level:",'dynamic-subpages');?></label><br />
      <input id="<?php echo $this->get_field_id('link_title'); ?>_yes" name="<?php echo $this->get_field_name('link_title');?>" <?php if(esc_attr($linkTitle)==1) echo "checked='checked'"; ?> type="radio" value="1" /><?php _e('Yes','dynamic-subpages');?>
      <input id="<?php echo $this->get_field_id('link_title'); ?>_no" name="<?php echo $this->get_field_name('link_title');?>" <?php if(esc_attr($linkTitle)!=1) echo "checked='checked'"; ?> type="radio" value="0" /><?php _e('No','dynamic-subpages');?>

  </p>
  
  <p>
    <label for="<?php echo $this->get_field_id('startlevel'); ?>"><?php _e("Start level:",'dynamic-subpages');?> </label><br />
      <input class="widefat" id="<?php echo $this->get_field_id('startlevel'); ?>" name="<?php echo $this->get_field_name('startlevel'); ?>" type="text" value="<?php echo esc_attr($startlevel); ?>" />
   
  </p>
 
    <p>
    <label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e("Depth (from startlevel):",'dynamic-subpages');?></label><br />
      <input class="widefat" id="<?php echo $this->get_field_id('depth'); ?>" name="<?php echo $this->get_field_name('depth'); ?>" type="text" value="<?php echo esc_attr($depth); ?>" />
   
  </p>
 

  <p>
    <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php echo __("Exclude pages:",'dynamic-subpages')." <i>(".__("Coma separated ID list",'dynamic-subpages').")";?></i>: </label>
      <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo esc_attr($exclude); ?>" />
   
  </p>
  
  <p>
    <label for="<?php echo $this->get_field_id('sort_column'); ?>"><?php _e("Sort column:",'dynamic-subpages');?></label>
      <select class="widefat" id="<?php echo $this->get_field_id('sort_column'); ?>" name="<?php echo $this->get_field_name('sort_column'); ?>">
      	<?php $op=array(
      		__("Menu order",'dynamic-subpages')=>"menu_order",
      		__("Post Title",'dynamic-subpages')=>"post_title",
      		__("Post Date",'dynamic-subpages')=>"post_date",
      		__("Post modified",'dynamic-subpages')=>"post_modified",
      		"ID"=>"ID",
      		__("Post Author",'dynamic-subpages')=>"post_author",
      		__("Post Name",'dynamic-subpages')=>"post_name",
      	);
      		foreach($op as $name=>$value){
      			echo "<option value='$value' ".(esc_attr($sortColomn)==$value?"selected":"").">$name</option>";
      		}
      	?>
      </select> 
    
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('sort_order'); ?>"><?php _e('Sort order:','dynamic-subpages')?></label><br />
    <input type="radio" id="<?php echo $this->get_field_id('sort_order'); ?>" name="<?php echo $this->get_field_name('sort_order'); ?>" value="asc" <?php if ($instance['sort_order'] == "asc") { echo "checked='checked'"; } ?>><?php _e('ASC','dynamic-subpages');?>
    <input type="radio" id="<?php echo $this->get_field_id('sort_order'); ?>" name="<?php echo $this->get_field_name('sort_order'); ?>" value="desc" <?php if ($instance['sort_order'] == "desc") { echo "checked='checked'"; } ?>><?php _e('DESC','dynamic-subpages');?>
  </p>


<?php
  }

  function plugin_action_links( $links, $file ) {
    static $this_plugin;
    
    if( empty($this_plugin) )
      $this_plugin = plugin_basename(__FILE__);

    if ( $file == $this_plugin )
      $links[] = '<a href="' . admin_url( 'widgets.php' ) . '">Widgets</a>';

    return $links;
  }
  
  
}

add_action('widgets_init', create_function('', 'return register_widget("dynamicSubpageWidget");'));

### Function: Enqueue JavaScripts/CSS
add_action('wp_enqueue_scripts', 'dynamic_subpages_scripts');
function dynamic_subpages_scripts() {
	//import css
	if(@file_exists(TEMPLATEPATH.'/dynamic-subpages.css')) {
		wp_enqueue_style('dynamic-subpages', get_stylesheet_directory_uri().'/dynamic-subpages.css', false, '0.50', 'all');
	} else {
		wp_enqueue_style('dynamic-subpages', plugins_url('dynamic-subpages/dynamic-subpages.css'), false, '0.50', 'all');
	}
}
?>