<?php 
class DirectoryPress_Terms {
	public $attrs;
	public $depth;
	public $columns;
	public $col;
	public $hide_empty;
	public $count;
	public $max_subterms;
	public $exact_terms = array();
	public $exact_terms_obj = array();
	public $col_md;
	public $tax;
	public $terms_icons_url;
	public $grid;
	public $grid_view;
	public $icons;
	public $menu;
	public $view_all_terms;
	public $directorytype;
	
	public function __construct($params) {
		$this->attrs = array_merge(array(
				'directorytype' => 0,
				'parent' => 0,
				'depth' => 2,
				'columns' => 2,
				'count' => true,
				'hide_empty' => false,
				'max_subterms' => 0,
				//'exact_terms' => array(),
				'grid' => 0,
				'grid_view' => 0,
				'icons' => 1,
				'subcats' =>  0,
				'cat_style' =>  1,
				'cat_icon_type' =>  1,
				'scroll' =>  0,
				'desktop_items' =>  3,
				'mobile_items' =>  1,
				'tab_items' =>  2,
				'autoplay' =>  true,
				'loop' =>  true,
				'owl_nav' =>  'false',
				'delay' =>  1000,
				'autoplay_speed' =>  1000,
				'gutter' =>  30,
				'slider_arrow_position' => 'absolute',
				'slider_arrow_icon_pre' => '',
				'slider_arrow_icon_next' => '',
				'allowed_packages' =>  array(),
				'exact_categories' =>  array(),
				'cat_font_size' =>  '',
				'cat_font_weight' =>  '',
				'cat_font_line_height' =>  '',
				'cat_font_transform' =>  '',
				'child_cat_font_size' =>  '',
				'child_cat_font_weight' =>  '',
				'child_cat_font_line_height' =>  '',
				'child_cat_font_transform' =>  '',
				'parent_cat_title_color' =>  '',
				'parent_cat_title_color_hover' =>  '',
				'parent_cat_title_bg' =>  '',
				'parent_cat_title_bg_hover' =>  '',
				'subcategory_title_color' =>  '',
				'subcategory_title_color_hover' =>  '',
				'cat_bg' =>  '',
				'cat_bg_hover' =>  '',
				'cat_border_color' =>  '',
				'cat_border_color_hover' =>  '',
				'location_style' => 1,
				'location_bg' => '',
				'location_bg_image' => '',
				'gradientbg1' => '',
				'gradientbg2' => '',
				'opacity1' => '',
				'opacity2' => '',
				'gradient_angle' => '',
				'location_width' => 30,
				'location_height' => '',
				'location_padding' => 15,
				'is_widget' => 0
		), $params);
		$this->params = $this->attrs;
		if(isset($this->attrs['tax'])){
			$this->tax = $this->attrs['tax'];
		}
		$this->directorytype = $this->attrs['directorytype'];
		if (is_numeric($this->attrs['parent'])) {
			$this->parent = $this->attrs['parent'];
		} else {
			$term_obj = get_term_by('slug', $this->attrs['parent'], $this->tax);
			if(!empty($term_obj)){
				$this->parent = $term_obj->term_id;
			}else{
				$this->parent = 0;
			}
		}
		$this->args['id'] = directorypress_create_random_value();
		$this->depth = $this->attrs['depth'];
		$this->columns = $this->attrs['columns'];
		$this->count = $this->attrs['count'];
		$this->hide_empty = $this->attrs['hide_empty'];
		$this->max_subterms = $this->attrs['max_subterms'];
		$this->grid = $this->attrs['grid'];
		$this->grid_view = $this->attrs['grid_view'];
		$this->icons = $this->attrs['icons'];
		//$this->menu = $this->attrs['menu'];
		
		$this->cat_style = $this->attrs['cat_style'];
		$this->cat_icon_type = $this->attrs['cat_icon_type'];
		$this->scroll = $this->attrs['scroll'];
		$this->desktop_items = $this->attrs['desktop_items'];
		$this->mobile_items = $this->attrs['mobile_items'];
		$this->tab_items = $this->attrs['tab_items'];
		$this->autoplay = $this->attrs['autoplay'];
		$this->loop = $this->attrs['loop'];
		$this->owl_nav = $this->attrs['owl_nav'];
		$this->delay = $this->attrs['delay'];
		$this->autoplay_speed = $this->attrs['autoplay_speed'];
		$this->gutter = $this->attrs['gutter'];
		$this->slider_arrow_position = $this->attrs['slider_arrow_position'];
		$this->slider_arrow_icon_pre = $this->attrs['slider_arrow_icon_pre'];
		$this->slider_arrow_icon_next = $this->attrs['slider_arrow_icon_next'];
		$this->allowed_packages = $this->attrs['allowed_packages'];
		$this->exact_categories = $this->attrs['exact_categories'];
		$this->cat_font_size = $this->attrs['cat_font_size'];
		$this->cat_font_weight = $this->attrs['cat_font_weight'];
		$this->cat_font_line_height = $this->attrs['cat_font_line_height'];
		$this->cat_font_transform = $this->attrs['cat_font_transform'];
		$this->child_cat_font_size = $this->attrs['child_cat_font_size'];
		$this->child_cat_font_weight = $this->attrs['child_cat_font_weight'];
		$this->child_cat_font_line_height = $this->attrs['child_cat_font_line_height'];
		$this->child_cat_font_transform = $this->attrs['child_cat_font_transform'];
		$this->parent_cat_title_color = $this->attrs['parent_cat_title_color'];
		$this->parent_cat_title_color_hover = $this->attrs['parent_cat_title_color_hover'];
		$this->parent_cat_title_bg = $this->attrs['parent_cat_title_bg'];
		$this->parent_cat_title_bg_hover = $this->attrs['parent_cat_title_bg_hover'];
		$this->subcategory_title_color = $this->attrs['subcategory_title_color'];
		$this->subcategory_title_color_hover = $this->attrs['subcategory_title_color_hover'];
		$this->cat_bg = $this->attrs['cat_bg'];
		$this->cat_bg_hover = $this->attrs['cat_bg_hover'];
		$this->cat_border_color = $this->attrs['cat_border_color'];
		$this->cat_border_color_hover = $this->attrs['cat_border_color_hover'];
				
		$this->location_style = $this->attrs['location_style'];		
		$this->location_bg = $this->attrs['location_bg'];
		$this->location_bg_image = $this->attrs['location_bg_image'];
		$this->gradientbg1 = $this->attrs['gradientbg1'];
		$this->gradientbg2 = $this->attrs['gradientbg2'];
		$this->opacity1 = $this->attrs['opacity1'];
		$this->opacity2 = $this->attrs['opacity2'];
		$this->gradient_angle = $this->attrs['gradient_angle'];
		$this->location_width = $this->attrs['location_width'];
		$this->location_height = $this->attrs['location_height'];
		$this->location_padding = $this->attrs['location_padding'];
		$this->is_widget = $this->attrs['is_widget'];
		
		if (is_array($this->attrs['exact_terms']) && !empty($this->attrs['exact_terms'])) {
			foreach ($this->attrs['exact_terms'] AS $term) {
				if (is_numeric($term)) {
					if ($term_obj = get_term_by('id', $term, $this->tax)) {
						$this->exact_terms[] = $term_obj->term_id;
						$this->exact_terms_obj[] = $term_obj;
					}
				} else {
					if ($term_obj = get_term_by('slug', $term, $this->tax)) {
						$this->exact_terms[] = $term_obj->term_id;
						$this->exact_terms_obj[] = $term_obj;
					}
				}
			}
		}
		
		if ($this->attrs['depth'] > 2) {
			$this->depth = 2;
		}
		if ($this->depth == 0 || !is_numeric($this->depth)) {
			$this->depth = 1;
		}
		if ($this->columns == 1) {
			$this->col = 12;
			$this->col_tab = 12;
			$this->col_mobile = 12;
		}elseif ($this->columns == 2) {
			$this->col = 6;
			$this->col_tab = 6;
			$this->col_mobile = 12;
		}elseif ($this->columns == 3) {
			$this->col = 4;
			$this->col_tab = 6;
			$this->col_mobile = 12;
		}elseif ($this->columns == 4) {
			$this->col = 3;
			$this->col_tab = 6;
			$this->col_mobile = 12;
		}elseif ($this->columns == 6) {
			$this->col = 2;
			$this->col_tab = 4;
			$this->col_mobile = 12;
		}elseif ($this->columns == 'inline' || !is_numeric($this->columns)) {
			$columns = 'inline';
			$this->col = 'inline';
			$this->col_tab = 'inline';
			$this->col_mobile = 'inline';
		} else{
			$this->col = 2;
			$this->col_tab = 4;
			$this->col_mobile = 12;
		}
		if($this->cat_style == 4){
			$columns = 'inline';
			$this->col = 'inline';
			$this->col_tab = 'inline';
			$this->col_mobile = 'inline';
		}
	}
	
	public function getTerms($parent) {
			$terms = array_merge(
					wp_list_filter(
							get_categories(array(
									'taxonomy' => $this->tax,
									'pad_counts' => true,
									'hide_empty' => $this->hide_empty,
									'include' => $this->exact_terms,
							)),
							array('parent' => $parent)
					), array());
		
		return $terms;
	}
	
	public function getCount($term) {
		if ($this->exact_terms) {
			$q = new WP_Query(array(
					'nopaging' => true,
					'tax_query' => array(
							array(
									'taxonomy' => $this->tax,
									'field' => 'id',
									'terms' => $term->term_id,
									'include_children' => true,
							),
					),
					'fields' => 'ids',
			));
			$terms_count = $q->post_count;
		} else {
			$terms_count = $term->count;
		}

		return $terms_count;
	}
	
	public function getWrapperClasses() {
		$classes[] = "directorypress-content-wrap";
		$classes[] = $this->wrapper_classes;
		$classes[] = "directorypress-terms-columns-" . $this->col;
		if ($this->menu) {
			$classes[] = "directorypress-terms-menu";
		}
		if ($this->grid) {
			$classes[] = $this->grid_classes;
		}
		$classes[] = "directorypress-terms-depth-" . $this->depth;
		
		return implode(' ', $classes);
	}
	

	

	public function renderTermCount($term) {
		
		if ($this->count) {
			//if ($this->attrs['cat_style'] == 5){
				//$term_count = $this->getCount($term).' '. esc_html__('ads', 'DIRECTORYPRESS');
			//}elseif($this->attrs['cat_style'] == 6){
				$term_count = $this->getCount($term);
			//}else{
				//$term_count = ' ('.$this->getCount($term).')';
			//}
		}else{
			$term_count = '';
		}
		return $term_count;
	}
	
	public function display() {
		global $directorypress_directory_flag, $DIRECTORYPRESS_ADIMN_SETTINGS;
		if ($this->directorytype) {
			$directorypress_directory_flag = $this->directorytype;
		}
		
		$terms = $this->getTerms($this->parent);
		
		if (!$terms && $this->exact_terms && (get_terms($this->tax, array('hide_empty' => false, 'parent' => $this->parent)))) {
			$terms = $this->exact_terms_obj;
		}

		if ($terms) {
			directorypress_display_template('partials/terms/taxonomy/template.php', array('instance' => $this, 'terms' => $terms));
		}
		
		$directorypress_directory_flag = 0;
	}
	
	
	function _display($parent, $depth_level) {
		$html = '';
			$terms = $this->getTerms($parent);
			if ($terms && $this->tax == DIRECTORYPRESS_CATEGORIES_TAX) {
				$depth_level++;
				$counter = 0;
				$html .= '<div class="subterms">';
					$html .= '<ul>';
					foreach ($terms AS $term) {
							if ($this->count){
								$term_count = ' ('.$this->getCount($term).')';
							}else{
								$term_count = '';
							}
							$counter++;
							
							if ($this->max_subterms != 0 && $counter > $this->max_subterms) {
								$html .= '<li>';
									$html .='<a class="view-all-btn" data-popup-open="' . $parent . '" href="#">' . __('View all', 'DIRECTORYPRESS') .'</a>';
								$html .= '</li>';
								break;
							} else{
								if ( count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
									/* directorypress customized*/
									$html .= '<li>';
										$html .='<a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">'. $term->name .' <span>'. $term_count . '</span></a>';	
									$html .='</li>';
								}else{
									$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">'. $term->name .' <span>'. $term_count . '</span></a></li>';  
								}
							}
							
						
					}
					$html .= '</ul>';
						
					$html .= '</div>';
			}
		return $html;
	}
}