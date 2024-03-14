<?php
/**
 * Navigation Menu API: Walker_Nav_Menu_Checklist class
 *
 * @package WordPress
 * @subpackage Administration
 * @since 4.4.0
 */

/**
 * Create HTML list of nav menu input items.
 *
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class DSAMM_Walker_Nav_Menu_Checklist extends Walker_Nav_Menu {
	/**
	 *
	 * @param array $fields
	 */
	public function __construct( $fields = false ) {
		if ( $fields ) {
			$this->db_fields = $fields;
		}
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 *
	 * @since 3.0.0
	 *
	 * @global int $_nav_menu_placeholder
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_nav_menu_placeholder;

		global  $gloable_all_author_array;
		global  $gloable_all_template_array;
		global  $gloable_all_category_array;
		global  $gloable_all_current_menu_id;

		$recently_edited = absint( get_user_option( 'nav_menu_recently_edited', get_current_user_id()) );
		if(!empty($recently_edited)){
			$menu_items = wp_get_nav_menu_items($recently_edited);
			if(isset($menu_items) && !empty($menu_items)) {
				for ( $amm=0; $amm < count($menu_items); $amm++ ) {
					
					$gloable_all_current_menu_id[] = $menu_items[$amm]->object_id;
					
				}
			}
		}


		// set all template globaly
		$get_templates_all = get_page_templates();
		foreach ( $get_templates_all as $template_name => $template_filename ) {
			$gloable_all_template_array[$template_name] = $template_filename;
		}

		$current_menu_post_id = $gloable_all_current_menu_id;
		$cate_array = array();
		$cate_id_array = array();
		$current_post_category = '';
		$allready_menu_class='';

		if(empty($item->front_or_home) && empty($item->term_id) && !empty($item->post_author)) $post_author_name = get_the_author_meta('display_name',$item->post_author);

		$_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1; //phpcs:ignore
		$possible_object_id = isset( $item->post_type ) && 'nav_menu_item' === $item->post_type ? $item->object_id : $_nav_menu_placeholder;
		$possible_db_id = ( ! empty( $item->ID ) ) && ( 0 < $possible_object_id ) ? (int) $item->ID : 0;
		$possible_db_id = $item->ID;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$chiled_dash = '';
		if(is_array($current_menu_post_id) && count($current_menu_post_id) >= 1 ){
			if (in_array($item->object_id, $current_menu_post_id, true)) { $allready_menu_class =  " menu_exists";}
		}

		if(!empty($indent)){
			$output .= $indent . '<li class="child_'.strlen($indent).$allready_menu_class.' ">';

			for	($i=0; $i < strlen($indent); $i++){
				$chiled_dash .='- ';
			}

		}else{
			$output .= $indent . '<li class="'.$allready_menu_class.'">';
		}
		$item_ID = esc_attr($item->object_id);
		if(!empty($item->front_or_home)) {$item_ID = '-';}
		if(empty($item->term_id)){
			$output .= '<span class="item_ID md_walker" >';
		}else{
			$output .= '<span class="item_ID md_walker taxonomy_id" >';
		}

		$output .= '<label class="menu-item-title">';
		$output .= '<input type="checkbox" class="menu-item-checkbox';

		if ( ! empty( $item->front_or_home ) )
		$output .= ' add-to-top';

		$output .= '" name="menu-item[' .esc_attr($possible_object_id). '][menu-item-object-id]" value="'. esc_attr( $item->object_id ) .'" /> ';

		$output .= '</label>';

		if ( ! empty( $item->label ) ) {
			$title = $item->label;
		} elseif ( isset( $item->post_type ) ) {
			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->post_title, esc_attr($item->ID) );
			if ( ! empty( $item->front_or_home ) && _x( 'Home', 'nav menu home label', 'advance-menu-manager' ) !== $title )
			$title = sprintf( _x( 'Home: %s', 'nav menu front page title', 'advance-menu-manager' ), $title );
		}

		// Menu item hidden fields
		$output .= '<input type="hidden" class="menu-item-db-id" name="menu-item[' . $possible_object_id . '][menu-item-db-id]" value="' .esc_attr($possible_db_id) . '" />';
		$output .= '<input type="hidden" class="menu-item-object" name="menu-item[' . $possible_object_id . '][menu-item-object]" value="'. esc_attr( $item->object ) .'" />';
		$output .= '<input type="hidden" class="menu-item-parent-id" name="menu-item[' . $possible_object_id . '][menu-item-parent-id]" value="'. esc_attr( $item->menu_item_parent ) .'" />';
		$output .= '<input type="hidden" class="menu-item-type" name="menu-item[' . $possible_object_id . '][menu-item-type]" value="'. esc_attr( $item->type ) .'" />';
		$output .= '<input type="hidden" class="menu-item-title" name="menu-item[' . $possible_object_id . '][menu-item-title]" value="'. esc_attr( $item->title ) .'" />';
		$output .= '<input type="hidden" class="menu-item-url" name="menu-item[' . $possible_object_id . '][menu-item-url]" value="'. esc_attr( $item->url ) .'" />';
		$output .= '<input type="hidden" class="menu-item-target" name="menu-item[' . $possible_object_id . '][menu-item-target]" value="'. esc_attr( $item->target ) .'" />';
		$output .= '<input type="hidden" class="menu-item-attr_title" name="menu-item[' . $possible_object_id . '][menu-item-attr_title]" value="'. esc_attr( $item->attr_title ) .'" />';
		$output .= '<input type="hidden" class="menu-item-classes" name="menu-item[' . $possible_object_id . '][menu-item-classes]" value="'. esc_attr( implode( ' ', $item->classes ) ) .'" />';
		$output .= '<input type="hidden" class="menu-item-xfn" name="menu-item[' . $possible_object_id . '][menu-item-xfn]" value="'. esc_attr( $item->xfn ) .'" />';
		if(empty($item->term_id)){$output .= $item_ID;	}
		$output .= '</span>';
		$output .= '<span class="title md_walker" title="'.esc_attr($item->title).'"> <strong>';
		$output .= (strlen($chiled_dash.esc_html($item->title)) > 35) ? substr($chiled_dash.esc_html($item->title), 0, 35).'...' : $chiled_dash.esc_html($item->title);
		$output .='</strong></span>';
		if(empty($item->term_id)){
			if( !empty($item->front_or_home) ){
				//fornt page menu item
				$output .= '<span class="item_slug md_walker">-</span>';
				$output .='<span class="author md_walker"> - </span>';
				$output .='<span class="template-list md_walker"> - </span>';
				$output .='<span class="publish_date md_walker"> - </span>';
			}else{
				// display page/post custom post type menu item.
				if(!empty($item->post_name)) {
					$output .= '<span class="item_slug md_walker" title="'.$item->post_name.'">';
					$output .= (strlen($item->post_name) > 20) ? substr($item->post_name, 0, 20).'...' : $item->post_name;
					$output .= '</span>';
				}else{
					$output .= '<span class="item_slug md_walker" title="">-</span>';
				}
				if(!empty($post_author_name)) { 
					$output .='<span class="author md_walker">'.$post_author_name.'</span>'; 
				}else{
					$output .='<span class="author md_walker">' . esc_html__('admin', 'advance-menu-manager') . '</span>'; 
				}

				if('page' === $item->post_type){

					$display_template_name = '';
					$tamplate_name = get_post_meta( $item->object_id,'_wp_page_template',true);

					if('default' === $tamplate_name || empty($tamplate_name)){
						$display_template_name .='default';
					}else if(!empty($gloable_all_template_array)){
						$template_name_key = array_search( $tamplate_name, $gloable_all_template_array, true );
						if (!empty($template_name_key)) {
							$tamplate_name = $template_name_key;
						}else{
							$tamplate_name = 'default';
						}
						$display_template_name = $tamplate_name;
					}
					$output .='<span class="template-list md_walker" title="'.$display_template_name.'">';
					$output .= (strlen($display_template_name) > 20) ? substr($display_template_name, 0, 20).'...' : $display_template_name;
					$output .='</span>';
				}else{
					$category_detail=get_the_category($item->object_id);
					$output .='<span class="category-list md_walker">';
					if(!empty($category_detail) && count($category_detail) > 0) {

						foreach($category_detail as $cd){
							$cate_array[]= $cd->cat_name;
							$cate_id_array[]= $cd->cat_ID;
						}
						$current_post_category = implode(',',$cate_array);
						$output .= $current_post_category;
					}else{
						$output .='-';
					}
					$output .='</span>';
				}
				$output .='<span class="publish_date md_walker">'.get_the_date('', $item->object_id).'</span>';
			}
		}else{
			// display taxonomy item
			$output .='<span class="taxonomy_slug md_walker">'.$item->slug.'</span>';
			if(!empty($item->description)) {
				$output .='<span class="taxomomy_content md_walker">'.$item->description.'</span>';
			}else{
				$output .='<span class="taxomomy_content md_walker">-</span>';
			}
		}
	}
} // Walker_Nav_Menu_Checklist