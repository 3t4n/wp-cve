<?php

class DirectoryPress_Location_Terms extends DirectoryPress_Terms {
	public $tax = DIRECTORYPRESS_LOCATIONS_TAX;
	public $wrapper_classes = 'directorypress-locations-table';
	public $row_classes = 'directorypress-locations-row';
	public $column_classes = 'directorypress-locations-column';
	public $root_classes = 'directorypress-parent-location';
	public $subterms_classes = 'directorypress-sublocations';
	public $item_classes = 'directorypress-location-item';
	public $term_count_classes = 'directorypress-location-item-numbers';
	public $grid_classes = 'directorypress-locations-grid';
	public $has_child = false;
	
	public function __construct($params) {
		parent::__construct($params);
		$this->params = $params;
		$this->view_all_terms = __("View all sublocations ->", "DIRECTORYPRESS");
		
	}
	
	public function display() {
		global $directorypress_directory_flag;
		if ($this->directorytype) {
			$directorypress_directory_flag = $this->directorytype;
		}
		
		$terms = $this->getTerms($this->parent);
		
		if (!$terms && $this->exact_terms && (get_terms($this->tax, array('hide_empty' => false, 'parent' => $this->parent)))) {
			$terms = $this->exact_terms_obj;
		}

		if ($terms && $this->tax == DIRECTORYPRESS_LOCATIONS_TAX) {
			
			
			if($this->location_style == 4  || $this->location_style == 8 || $this->location_style == 9){
				$gutter = '';
				$row = 'row';
			}else{
				$row = '';
				$gutter = 'padding:'.$this->location_padding.'px;';
			}
			
			directorypress_display_template('partials/terms/locations/locations.php', array('instance' => $this, 'terms' => $terms));
		}
		
		$directorypress_directory_flag = 0;
	}
	
	function _display($parent, $depth_level) {
		$html = '';
		//if ($this->depth == 0 || !is_numeric($this->depth) || $this->depth > $depth_level) {
			$terms = $this->getTerms($parent);
			if ($terms && $this->tax == DIRECTORYPRESS_LOCATIONS_TAX) {
			
				//if ($this->depth == 0 || !is_numeric($this->depth) || $this->depth > $depth_level) {
					$depth_level++;
					$counter = 0;
					
					$html .= '<div class="sublocations">';
					$html .= '<ul>';
					foreach ($terms AS $term) {
						
							if ($this->count){
								$term_count = ' ('.$this->getCount($term).')';
							}else{
								$term_count = '';
							}
							
							$counter++;
							if ($this->max_subterms != 0 && $counter > $this->max_subterms) {
								$html .= '<li><a href="' . get_term_link(intval($parent), DIRECTORYPRESS_LOCATIONS_TAX) . '">' . __('View All', 'DIRECTORYPRESS') . '</a></li>';
								break;
							} else
								$html .= '<li><a href="' . get_term_link($term) . '" title="' . $term->name .$term_count . '">'. $term->name .'<span>'. $term_count .'</span></a></li>';
						
					}
					$html .= '</ul>';
					$html .= '</div>';
				//}
			
			
			}
		//}
		return $html;
	}
}