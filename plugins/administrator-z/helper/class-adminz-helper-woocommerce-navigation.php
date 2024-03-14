<?php 
namespace Adminz\Helper;
use WP_Query;

class ADMINZ_Helper_Woocommerce_Navigation{
	function __construct() {
		add_filter('wp_nav_menu_objects',[$this,'add_wp_nav_menu_objects'],10,2);

		// Term meta icon for product cat
		$this->add_product_category_icon_support();
	}
	function add_wp_nav_menu_objects($sorted_menu_items, $args){
		$arr_check = [];
		if(!empty($sorted_menu_items) and is_array($sorted_menu_items)){
			foreach ($sorted_menu_items as $key => $value) {
				if(in_array('adminz_product_category_replace',$value->classes)){
					$arr_check[] = [
						'menu_item_parent' => $value->ID,						
						'type' => 'adminz_product_category_replace',
						'term_parent'=> $this->get_parent_category($value->classes)
					];
				}else if(in_array('adminz_product_category',$value->classes)){					
					$arr_check[] = [
						'menu_item_parent' => $value->ID,						
						'type' => 'adminz_product_category',
						'term_parent'=> $this->get_parent_category($value->classes)
					];
				}else if(in_array('adminz_product',$value->classes)){
					$arr_check[] = [
						'menu_item_parent' => $value->ID,						
						'type' => 'adminz_product',
						'term_parent'=> $this->get_parent_category($value->classes)
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
			$term_parent;
			
			switch ($type) {									
				case 'adminz_product_category_replace':
					$menu_items = $this->get_navigation_items($value);
					foreach ((array)$menu_items as $key => $item) {
						$item->post_type = 'nav_menu_item';
						$item->ID = $item->term_id;
			    		$item->type = 'taxonomy';
			    		$item->object = 'product_cat';
			    		$item->object_id = $item->term_id;
			    		$item->post_title = $item->name;
		    			$item->post_excerpt = $this->get_term_icon($item);
		    			$item->description = ""; // Fix for adminz navigation

			    		// Currently only support For Flatsome walker : menu-item-has-children
					    if($this->is_item_has_children($items_has_children, $item, $menu_items, $term_parent,$key)){
					    	$item->classes = ['menu-item-has-children'];
					    }
					    // parent
					    if(!$item->parent){
					    	// nothing
					    }else{
					    	if($this->check_term_parent($term_parent)){
					    		// nothing
					    	}else{
					    		$item->menu_item_parent = $item->parent;
					    	}					    	
					    }

				    }
				    $this->final_set_item_parent_css($term_parent,$items_has_children,$menu_items);
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
					break;
				case 'adminz_product_category':
					
					$menu_items = $this->get_navigation_items($value);
					foreach ((array)$menu_items as $key => $item) {
						$item->post_type = 'nav_menu_item';
						$item->ID = $item->term_id;
			    		$item->type = 'taxonomy';
			    		$item->object = 'product_cat';
			    		$item->object_id = $item->term_id;
			    		$item->post_title = $item->name;
			    		$item->post_excerpt = $this->get_term_icon($item);
			    		$item->description = ""; // Fix for adminz navigation

			    		// Currently only support For Flatsome walker : menu-item-has-children
					    if($this->is_item_has_children($items_has_children, $item, $menu_items, $term_parent,$key)){
					    	$item->classes = ['menu-item-has-children'];
					    }

					    // parent
					    if(!$item->parent){
					    	$item->menu_item_parent = $menu_item_parent;
					    }else{
					    	if($this->check_term_parent($term_parent)){
					    		$item->menu_item_parent = $menu_item_parent;
					    	}else{
					    		$item->menu_item_parent = $item->parent;
					    	}					    	
					    }
				    }
				    $this->final_set_item_parent_css($term_parent,$items_has_children,$menu_items);
				    $menu_items = array_map( 'wp_setup_nav_menu_item', $menu_items );		

					// setup item obj
				    _wp_menu_item_classes_by_context($menu_items);

				    $insert_array_key = $this->get_insert_key($sorted_menu_items,$menu_item_parent);
					$sorted_menu_items = array_merge(
						array_slice($sorted_menu_items,0,$insert_array_key),
						$menu_items,
						array_slice($sorted_menu_items, $insert_array_key)
					);
					
					break;
				case 'adminz_product':

					$menu_items = $this->get_navigation_items($value);
					foreach ((array)$menu_items as $key => $item) {
						$item->post_type = 'nav_menu_item';
						$item->ID = $item->ID;
			    		$item->type = 'post_type';
			    		$item->object = 'product';
			    		$item->object_id = $item->ID;
			    		$item->post_title = $item->post_title;
			    		$item->post_excerpt = ""; // for adminz filter icon
			    		$item->description = ""; // Fix for adminz navigation

			    		// parent
						$item->menu_item_parent = $menu_item_parent;
					}
					$menu_items = array_map( 'wp_setup_nav_menu_item', $menu_items );		

					// setup item obj
				    _wp_menu_item_classes_by_context($menu_items);

				    $insert_array_key = $this->get_insert_key($sorted_menu_items,$menu_item_parent);
					$sorted_menu_items = array_merge(
						array_slice($sorted_menu_items,0,$insert_array_key),
						$menu_items,
						array_slice($sorted_menu_items, $insert_array_key)
					);
					break;
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
	function is_item_has_children(&$items_has_children, $item, $menu_items, $term_parent,$key){
		if(!isset($items_has_children)) $items_has_children = [];
		// only no parent set
		if(!$term_parent){
			// if no children
			if(isset($menu_items[$key+1]) and $menu_items[$key+1]->parent == $item->term_id){
	    		//$item->classes = ['menu-item-has-children'];
	    		$items_has_children[] = $key;
	    		return true; 
	    	}
	    	// if no parent
	    	if (!$menu_items[$key]->parent){
	    		$items_has_children[] = $key;
	    		return true;
	    	}
		}
		return false;
	}
	function final_set_item_parent_css($term_parent,$items_has_children,$menu_items){
		if(!isset($items_has_children)) $items_has_children = [];
		// if all has children-> set no one
		if(!$term_parent and count($items_has_children) == count($menu_items)){
	    	foreach ($items_has_children as $key) {
	    		if(isset($menu_items[$key]->classes)){
	    			$menu_items[$key]->classes = [];
	    		}
	    	}
	    }
	}
	function get_term_icon($item){
		return get_term_meta($item->term_id,'adminz_product_cat_icon',true);		
	}
	function get_parent_category($classes){
		$return = null ;
		foreach( (array) $classes as $key=>$value){
			if( is_int((strpos($value,'parent_')))){
				$index = strpos($value,'parent_');
				$return = substr($value, 7);				
			}
		}
		return $return;
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
	function remove_navigation_items(&$menu_items,$item_id){
		foreach ((array)$menu_items as $key=> $value) {			
			if($value->ID == $item_id){
				unset($menu_items[$key]);
			}
		}
	}
	function get_navigation_items($array){
		extract($array);
		switch ($type) {
			case 'adminz_product':
				$args = [
					'post_type'=>'product',
					'posts_per_page'=> -1
				];
				if($this->check_term_parent($term_parent)){
					$args['tax_query'] = [
	                    'relation'=> 'AND',
	                    [
	                        'taxonomy'=>'product_cat',
	                        'field'=>'term_id',
	                        'terms'=> [$term_parent],
	                        'include_children'=>true,
	                        'operator'=> 'IN'
	                    ]
	                ];
				}
				$get_nav = new WP_Query($args);
				$return = $get_nav->posts;
				break;
			
			default:	
				$args = [
					'taxonomy'=> 'product_cat',
		            'hide_empty'=> false,
				];				
				if($this->check_term_parent($term_parent)){
					$args['parent'] = $term_parent;
				}				
				$return = get_terms($args );
				break;
		}
		return $return;
	}
	function add_product_category_icon_support(){
		add_action('product_cat_add_form_fields',[$this,'add_product_cat_icon']);
		add_action('product_cat_edit_form_fields',[$this,'edit_product_cat_icon']);			
		add_action('edit_term',[$this,'update_product_cat_icon']);
		add_action('create_term',[$this,'update_product_cat_icon']);	
	}
	function update_product_cat_icon($term_id){		
	    if(isset($_POST['adminz_product_cat_icon'])){
	        update_term_meta($term_id, 'adminz_product_cat_icon',sanitize_text_field($_POST['adminz_product_cat_icon']));
	    }
	}
	function add_product_cat_icon($taxonomy){
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<div>Navigation Icon</div>
				<p>
					<a target="_blank" href="<?php echo admin_url().'tools.php?page=adminz&tab=adminz_icons'; ?>">Link to get Icon</a> or Fill Image url here
				</p>
			</th>
			<td>
				<div class="form-field">
					<input type="text" name="adminz_product_cat_icon">
				</div>	
			</td>
		</tr>		         
		<?php 		
	}
	function edit_product_cat_icon($taxonomy){
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><div>Navigation Icon</div>
				<p>
					<a target="_blank" href="<?php echo admin_url().'tools.php?page=adminz&tab=adminz_icons'; ?>">Link to get Icon</a> or Fill Image url here
				</p>
			</th>
			<td>
				<?php 
					$icon = get_term_meta($taxonomy->term_id,'adminz_product_cat_icon',true);
			 	?>
				<input type="text" name="adminz_product_cat_icon" value="<?php echo esc_attr($icon); ?>">
			</td>
		</tr>		         
		<?php 		
	}
	function check_term_parent($parent){
		$return = false;
		if($parent or $parent == "0"){
			// 0 = default no parent
			$return = true;
		}
		return $return;
	}
}