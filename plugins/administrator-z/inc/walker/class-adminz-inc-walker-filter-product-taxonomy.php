<?php 
namespace Adminz\Inc\Walker;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy;
use Adminz\Admin\Adminz as Adminz;
use Walker; 

class ADMINZ_Inc_Walker_Filter_Product_Taxonomy extends Walker {
	public $taxonomy;
	public $query_type;
	function __construct($taxonomy,$query_type) {		
		$this->taxonomy = $taxonomy;
		$this->query_type= $query_type;
	}
	public $db_fields = array(
		'parent' => 'parent',
		'id'     => 'term_id',
		'slug'   => 'slug',
	);
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' !== $args['style'] ) {
			return;
		}

		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent<ul class='children'>\n";
	}
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' !== $args['style'] ) {
			return;
		}

		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}
	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {		
		if(!ADMINZ_Helper_Woocommerce_Taxonomy::bo_loc_term_taxonomy($cat,$this->taxonomy)){return ;}

		$cat_id = intval( $cat->term_id );

		$output .= '<li class="cat-item cat-item-' . $cat_id;


		$termslug = $cat->slug;
		$termslug = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_gia_tri_term_slug($termslug);
		$taxonomy = $this->taxonomy;
		$query_type = $this->query_type;

		$taxonomy = ADMINZ_Helper_Woocommerce_Taxonomy::thay_taxonomy_slug_by_term_value($taxonomy,$termslug);

		$catlink = ADMINZ_Helper_Woocommerce_Taxonomy::lay_link_term_widget($termslug, $taxonomy,$query_type);
		
		if(ADMINZ_Helper_Woocommerce_Taxonomy::co_phai_term_hien_tai($termslug,$taxonomy)){
			$output .= ' current-cat';
		}

		if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
			$output .= ' cat-parent';
		}

		if (
			($args['current_category_ancestors'] &&
			$args['current_category'] && 
			in_array( $cat_id, $args['current_category_ancestors'], true )) or 
			(is_array($args['current_category_ancestors']) and  in_array($cat_id,$args['current_category_ancestors']))
		) {			
			$output .= ' current-cat-parent';
		}
		
		$catname = $cat->name;
		$catname = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_name($cat->name);
		$output .= '">';
		$output .= '<a href="'.$catlink.'">';
		$output .= apply_filters( 'list_product_cats', $catname, $cat ).'</a>';

		/*if ( $args['show_count'] ) {
			$output .= ' <span class="count">(' . $cat->count . ')</span>';
		}*/
	}
	public function end_el( &$output, $cat, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element || ( 0 === $element->count && ! empty( $args[0]['hide_empty'] ) ) ) {
			return;
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
