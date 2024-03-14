<?php 
namespace Adminz\Helper;
use WP_Query;

class ADMINZ_Helper_Flatsome_Page_Navigation{
	function __construct() {
		
		// adminz_page: Lay all page 
		// adminz_page parent_{id}: lay all page co parent = id


		add_filter('wp_nav_menu_objects',[$this,'add_wp_nav_menu_objects'],10,2);
		// Term meta icon for page cat
	}
	function add_wp_nav_menu_objects($sorted_menu_items, $args){
		$arr_check = [];
		if(!empty($sorted_menu_items) and is_array($sorted_menu_items)){
			foreach ($sorted_menu_items as $key => $value) {
				if(in_array('adminz_page',$value->classes)){
					$arr_check[] = [
						'menu_item_parent' => $value->ID,
						'type'=> 'adminz_page',
						'item_parent'=> $this->get_parent_page_item($value->classes)
					];
				}
				if(in_array('adminz_page_replace',$value->classes)){
					$arr_check[] = [
						'menu_item_parent' => $value->ID,
						'type'=> 'adminz_page_replace',
						'item_parent'=> $this->get_parent_page_item($value->classes)
					];
				}
			}
		}		
		if(empty($arr_check)){ return $sorted_menu_items; }		
		foreach ($arr_check as $key => $value) {			
			extract($value);

			// use variables
			$type;
			$menu_item_parent;
			$item_parent;
			
			if($type=='adminz_page'){
				$menu_items = $this->get_navigation_items($value);
				foreach ((array)$menu_items as $key => $item) {
					$item->post_type = 'nav_menu_item';
					$item->ID = $item->ID;
		    		$item->type = 'post_type';
		    		$item->object = 'page';
		    		$item->object_id = $item->ID;
		    		$item->post_title = $item->post_title;
		    		$item->post_excerpt = ""; // for adminz filter icon
		    		$item->description = ""; // Fix for adminz navigation

		    		// Currently only support For Flatsome walker : menu-item-has-children
				    if($this->is_item_has_children($items_has_children, $item, $menu_items, $item_parent,$key)){
				    	$item->classes = ['menu-item-has-children'];
				    }

				    // parent
				    if(!$item->post_parent){
				    	$item->menu_item_parent = $menu_item_parent;
				    }else{
				    	if($this->check_page_parent($item_parent)){
				    		$item->menu_item_parent = $menu_item_parent;
				    	}else{
				    		$item->menu_item_parent = $item->post_parent;
				    	}					    	
				    }
			    }
			    $this->final_set_item_parent_css($item_parent,$items_has_children,$menu_items);
			    $menu_items = array_map( 'wp_setup_nav_menu_item', $menu_items );		

				// setup item obj
			    _wp_menu_item_classes_by_context($menu_items);

			    $insert_array_key = $this->get_insert_key($sorted_menu_items,$menu_item_parent);
				$sorted_menu_items = array_merge(
					array_slice($sorted_menu_items,0,$insert_array_key),
					$menu_items,
					array_slice($sorted_menu_items, $insert_array_key)
				);
			}
			if ($type=='adminz_page_replace'){
				$menu_items = $this->get_navigation_items($value);
				foreach ((array)$menu_items as $key => $item) {
					$item->post_type = 'nav_menu_item';
					$item->ID = $item->ID;
		    		$item->type = 'post_type';
		    		$item->object = 'page';
		    		$item->object_id = $item->ID;
		    		$item->post_title = $item->post_title;
		    		$item->post_excerpt = ""; // for adminz filter icon
		    		$item->description = ""; // Fix for adminz navigation

		    		// Currently only support For Flatsome walker : menu-item-has-children
				    if($this->is_item_has_children($items_has_children, $item, $menu_items, $item_parent,$key)){
				    	$item->classes = ['menu-item-has-children'];
				    }

				    // parent
				    if(!$item->post_parent){
				    	// nothing
				    }else{
				    	if($this->check_page_parent($item_parent)){
				    		// nothing
				    	}else{
				    		$item->menu_item_parent = $item->post_parent;
				    	}					    	
				    }
			    }
			    $this->final_set_item_parent_css($item_parent,$items_has_children,$menu_items);
			    $menu_items = array_map( 'wp_setup_nav_menu_item', $menu_items );		

				// setup item obj
			    _wp_menu_item_classes_by_context($menu_items);

			    $insert_array_key = $this->get_insert_key($sorted_menu_items,$menu_item_parent);
				$sorted_menu_items = array_merge(
					array_slice($sorted_menu_items,0,$insert_array_key),
					$menu_items,
					array_slice($sorted_menu_items, $insert_array_key)
				);
				$this->remove_navigation_items($sorted_menu_items,$menu_item_parent);
			}

						
		}
		// Menu Re-order
		if(!empty($sorted_menu_items) and is_array($sorted_menu_items)){
			foreach ($sorted_menu_items as $key => $value) {
				$value->menu_order = $key+1;
			}
		}	
		
		return $sorted_menu_items;
	}
	function is_item_has_children(&$items_has_children, $item, $menu_items, $item_parent,$key){
		if(!isset($items_has_children)) $items_has_children = [];
		// only no parent set
		if(!$item_parent){
			// if no children
			if(isset($menu_items[$key+1]) and $menu_items[$key+1]->post_parent == $item->term_id){
	    		//$item->classes = ['menu-item-has-children'];
	    		$items_has_children[] = $key;
	    		return true; 
	    	}
	    	// if no parent
	    	if (!$menu_items[$key]->post_parent){
	    		$items_has_children[] = $key;
	    		return true;
	    	}
		}
		return false;
	}
	function final_set_item_parent_css($item_parent,$items_has_children,$menu_items){
		if(!isset($items_has_children)) $items_has_children = [];
		// if all has children-> set no one
		if(!$item_parent and count($items_has_children) == count($menu_items)){
	    	foreach ($items_has_children as $key) {
	    		if(isset($menu_items[$key]->classes)){
	    			$menu_items[$key]->classes = [];
	    		}
	    	}
	    }
	}
	function get_parent_page_item($classes){
		$return = null ;
		foreach( (array) $classes as $key=>$value){
			if( is_int((strpos($value,'parent_')))){
				$index = strpos($value,'parent_');
				$return = substr($value, 7);				
			}
		}
		return $return;
	}
	function remove_navigation_items(&$menu_items,$item_id){
		foreach ((array)$menu_items as $key=> $value) {			
			if($value->ID == $item_id){
				unset($menu_items[$key]);
			}
		}
	}
	function get_insert_key($menu_items,$item_id){
		$return = 0;
		foreach ((array)$menu_items as $key=> $value) {
			if($value->ID == $item_id){
				$return  = $key;
			}
		}
		return $return;
	}
	function get_navigation_items($array){
		extract($array);
		$args = [
			'post_type'=>'page',
			'posts_per_page'=> -1,
			'orderby'=> 'menu_order',
			'order' => 'ASC',
		];
		if($this->check_page_parent($item_parent)){
			$args['post_parent'] = $item_parent;
		}
		$get_nav = new WP_Query($args);
		$return = $get_nav->posts;
		return $return;
	}
	function check_page_parent($parent){
		$return = false;
		if($parent or $parent == "0"){
			// 0 = default no parent
			$return = true;
		}
		return $return;
	}
}