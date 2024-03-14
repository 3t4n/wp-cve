<?php 

class DirectoryPress_Category_Terms extends DirectoryPress_Terms {
	public $tax = DIRECTORYPRESS_CATEGORIES_TAX;
	public $wrapper_classes = 'directorypress-categories-table';
	public $row_classes = 'directorypress-categories-wrapper';
	public $column_classes = 'directorypress-category-item';
	public $root_classes = 'directorypress-parent-category';
	public $subterms_classes = 'directorypress-subcategories';
	public $item_classes = 'directorypress-category-item';
	public $term_count_classes = 'directorypress-category-count';
	public $grid_classes = 'directorypress-categories-grid';
	
	public function __construct($params) {
		parent::__construct($params);
		$this->params = $params;
		$this->view_all_terms = __("View all subcategories ->", "DIRECTORYPRESS");
		
	}
	
	
	public function termIcon($term_id) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;

		$term = get_term_by('id', $term_id, DIRECTORYPRESS_CATEGORIES_TAX);	
		
		$cat_style = $this->cat_style;
		$cat_icon_type = $this->cat_icon_type;
		if($cat_color_set = get_listing_category_color($term_id)){
			if($cat_style == 6){
				$cat_color = 'style="background-color:'.$cat_color_set.';"';
			}else{
				$cat_color = 'style="color:'.$cat_color_set.';"';	
			}
		}else{
			if($cat_style == 6){
				$cat_color = 'style="background-color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'].';"';
			}elseif($cat_style == 7){
				$cat_color = 'style="color:'.$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'].';"';
			}else{
				$cat_color = '';
			}
		}
		if(isset($cat_icon_type )){
			$cat_icon_type_set = $cat_icon_type;
		}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type'])){
			$cat_icon_type_set = $DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type'];
		}else{
			$cat_icon_type_set = 1;
		}
		
		
		if($cat_icon_type_set == 1){
			$icon_image = '<span class="cat-icon font-icon '.get_listing_category_font_icon($term_id).'" '.$cat_color.'></span>';			
		}elseif($cat_icon_type_set == 2) {
			$cat_icon_img = (!empty(get_listing_category_icon_url($term_id)))? ('<img class="directorypress-field-icon" src="' . get_listing_category_icon_url($term_id) . '" alt="'.$term->name.'" />'): '';
			$cat_icon_bg = (!empty(get_listing_category_background_image_url($term_id)))? ('style="background-image:url('.esc_url(get_listing_category_background_image_url($term_id)).');"'): '';
		
			if ($cat_style == 1 || $cat_style == 2){
				$icon_image = '<span class="cat-icon" '.$cat_icon_bg.'>'.$cat_icon_img.'</span>';
			}else{
				$icon_image = '<span class="cat-icon">'.$cat_icon_img.'</span>';
			}
			
		}
		
		return $icon_image;
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

		if ($terms && $this->tax == DIRECTORYPRESS_CATEGORIES_TAX) {
			directorypress_display_template('partials/terms/categories/categories.php', array('instance' => $this, 'terms' => $terms));
	
		}
		
		$directorypress_directory_flag = 0;
	}
	
	
	function _display($parent, $depth_level) {
		$html = '';
			$terms = $this->getTerms($parent);
			if ($terms && $this->tax == DIRECTORYPRESS_CATEGORIES_TAX) {
				$depth_level++;
				$counter = 0;
				$html .= '<div class="subcategories">';
					$html .= '<ul>';
						foreach ($terms AS $term) {
							if ($this->count){
								$term_count = ' ('.$this->getCount($term).')';
							}else{
								$term_count = '';
							}
								
							if ($this->icons && $icon_url = get_listing_category_icon_url($term->term_id)) {
								$icon_image = '<img class="directorypress-field-icon" src="' . $icon_url . '" />';
							} else {
								$icon_image = '';
							}
							$counter++;
							if($this->cat_style == 6){
									if ($this->max_subterms != 0 && $counter > $this->max_subterms) {
										$html .= '<li class="view-all-btn-wrap">';
										$html .='<a class="view-all-btn" data-popup-open="' . $parent . '" href="#">' . __('View all', 'DIRECTORYPRESS') .'</a>';
										$html .= '</li>';
										break;
									} else{
										  if ( count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
										/* directorypress customized*/
										$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">' . $icon_image . $term->name .' <span>'. $term_count . '</span></a>';
											
										$html .='</li>';
										  }else{
											$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">' . $icon_image . $term->name .' <span>'. $term_count . '</span></a></li>';  
										  }
									}
							}else{
								if ($this->max_subterms != 0 && $counter > $this->max_subterms) {
									$html .= '<li>';
										$html .='<a class="view-all-btn" data-popup-open="' . $parent . '" href="#">' . __('View all', 'DIRECTORYPRESS') .'</a>';
									$html .= '</li>';
									break;
								} else{
									if ( count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 ) {
										/* directorypress customized*/
										$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">' . $icon_image . $term->name .' <span>'. $term_count . '</span></a></li>';
									}else{
										$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">' . $icon_image . $term->name .' <span>'. $term_count . '</span></a></li>';  
									}
								}
							}
							
						}
					$html .= '</ul>';
						
					$html .= '</div>';
			}
		return $html;
	}
}