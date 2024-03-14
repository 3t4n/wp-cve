<?php

namespace Categorify;

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class Helper
 */
class Helper
{
    public function preloader()
    {
        $html = '';
        /*$html .= '<div class="categorify_be_loader_wrap">';
        			$html .= '<div class="categorify_be_loader">';
        				$html .= '<span class="a_a"></span>';
        				$html .= '<span class="b_b"></span>';
        				$html .= '<span class="c_c"></span>';
        				$html .= '<span class="d_d"></span>';
        			$html .= '</div>';
        		$html .= '</div>';*/
        $html .= '<div class="categorify_be_loader_wrap">
						<div class="categorify_be_loader">
							<span class="loader_process">
								<span class="ball"></span>
								<span class="ball"></span>
								<span class="ball"></span>
							</span>
						</div>
					</div>';
        return $html;
    }
    
    public function getSidebarHeader()
    {
        $allCount = wp_count_posts( 'attachment' )->inherit;
        $uncategoryCount = $this->getUncategorizedAttachmentsCount();
        $preloader = $this->preloader();
        $html = '';
        $html .= '<div class="cc_categorify_header">';
        $html .= '<div class="header_top">';
        $html .= '<h3>' . esc_html__( 'Categories', CATEGORIFY_TEXT_DOMAIN ) . '</h3>';
        $html .= '<a href="#"><span>' . esc_html__( 'Add New', CATEGORIFY_TEXT_DOMAIN ) . '</span></a>';
        $html .= wp_kses_post( $preloader );
        $html .= '</div>';
        $html .= '<div class="header_bottom">';
        $html .= '<ul class="header_bottom_list">';
        $html .= '<li data-id="all" class="category_item"><div class="bottom_item cc_all_files">';
        $html .= '<a href="#">';
        $html .= '<span class="cc_text">' . esc_html__( 'All Files', CATEGORIFY_TEXT_DOMAIN ) . '</span>';
        $html .= '<span class="cc_count">' . esc_html( $allCount ) . '</span>';
        $html .= '</a>';
        $html .= '</div></li>';
        $html .= '<li data-id="-1" class="category_item"><div class="bottom_item cc_uncategorized">';
        $html .= '<a href="#">';
        $html .= '<span class="cc_text">' . esc_html__( 'Uncategorized', CATEGORIFY_TEXT_DOMAIN ) . '</span>';
        $html .= '<span class="cc_count">' . esc_html( $uncategoryCount ) . '</span>';
        $html .= '</a>';
        $html .= '</div></li>';
        $html .= '</ul>';
        $html .= '</div>';
        // SEARCH
        $html .= '</div>';
        return $html;
    }
    
    /* since 1.0 */
    public function getSidebarContent()
    {
        $tree = $this->categorifyTermTreeArray( CATEGORIFY_TAXONOMY, 0 );
        $categories = $this->categorifyConvertTreeToFlatArray( $tree );
        $html = '';
        $html .= '<div class="cc_categorify_content">';
        $html .= '<ul id="categorify_be_folder_list" class="cc_categorify_category_list">';
        $html .= $this->getAllCategories( $categories, 0 );
        $html .= '</ul>';
        $html .= '</div>';
        return $html;
    }
    
    /* since 1.0 */
    public static function getIcon( $icon = 'folder' )
    {
        return '<img class="categorify_be_svg" src="' . CATEGORIFY_ASSETS_URL . 'img/' . $icon . '.svg" alt="" />';
    }
    
    /* since 1.0 */
    public static function applyCancelButtons()
    {
        $html = '<div class="cc_btns">';
        $html .= '<span class="cc_apply">' . self::getIcon( 'check' ) . '<span class="cc_tooltip">' . esc_html__( 'Confirm', CATEGORIFY_TEXT_DOMAIN ) . '</span></span>';
        $html .= '<span class="cc_cancel">' . self::getIcon( 'close' ) . '<span class="cc_tooltip">' . esc_html__( 'Cancel', CATEGORIFY_TEXT_DOMAIN ) . '</span></span>';
        $html .= '</div>';
        return $html;
    }
    
    /* since 1.0 */
    public static function dragButton()
    {
        return '<span class="cc_drag"><span></span></span>';
    }
    
    /* since 1.0 */
    public function getUncategorizedAttachmentsCount()
    {
        $args = array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit,private',
            'posts_per_page' => -1,
            'tax_query'      => array(
            'relation' => 'AND',
            0          => array(
            'taxonomy' => CATEGORIFY_TAXONOMY,
            'field'    => 'id',
            'terms'    => $this->getTermsValues( 'ids' ),
            'operator' => 'NOT IN',
        ),
        ),
        );
        $result = get_posts( $args );
        return count( $result );
    }
    
    /* since 1.0 */
    public function getTermsValues( $keys = 'ids' )
    {
        $mediaTerms = get_terms( CATEGORIFY_TAXONOMY, array(
            'hide_empty' => 0,
            'fields'     => 'id=>slug',
        ) );
        $mediaValues = array();
        foreach ( $mediaTerms as $key => $value ) {
            $mediaValues[] = ( $keys === 'ids' ? $key : $value );
        }
        return $mediaValues;
    }
    
    /* since 1.0 */
    public function categorifyTermTreeArray( $taxonomy, $parent )
    {
        $terms = get_terms( $taxonomy, array(
            'hide_empty' => false,
            'meta_key'   => 'folder_position',
            'orderby'    => 'meta_value',
            'parent'     => $parent,
        ) );
        $children = array();
        foreach ( $terms as $term ) {
            $term->children = $this->categorifyTermTreeArray( $taxonomy, $term->term_id );
            $children[] = $term;
        }
        return $children;
    }
    
    /* since 1.0 */
    public function categorifyTermTreeOption(
        $terms,
        $spaces = '',
        $child = '',
        $myKey = 2
    )
    {
        $result = '';
        if ( !is_null( $terms ) && count( $terms ) > 0 ) {
            foreach ( $terms as $item ) {
                $termID = $item->term_id;
                $termName = ucfirst( $item->name );
                $children = $item->children;
                
                if ( $child == 'child' ) {
                    $kkey = '';
                } else {
                    $kkey = $myKey . '.';
                }
                
                $termID = esc_html( $termID );
                $kkey = esc_html( $kkey );
                $spaces = esc_html( $spaces );
                $result .= '<option value="' . $termID . '" data-id="' . $termID . '">' . $kkey . $spaces . '&nbsp;' . $termName . '</option>';
                if ( is_array( $children ) && count( $children ) > 0 ) {
                    $result .= $this->categorifyTermTreeOption( $children, $spaces . "&rarr;", 'child' );
                }
                $myKey++;
            }
        }
        return $result;
    }
    
    /* since 1.0 */
    public function categorifyConvertTreeToFlatArray( $array )
    {
        $result = array();
        foreach ( $array as $key => $row ) {
            $item = new \stdClass();
            $item->term_id = $row->term_id;
            $item->name = $row->name;
            $item->parent = $row->parent;
            $item->count = $row->count;
            $result[] = $item;
            if ( count( $row->children ) > 0 ) {
                $result = array_merge( $result, $this->categorifyConvertTreeToFlatArray( $row->children ) );
            }
        }
        return $result;
    }
    
    /* since 1.0 */
    private function getAllCategories( $categories, $parent )
    {
        $orders = array();
        foreach ( $categories as $key => $row ) {
            $orders[$key] = $key;
        }
        array_multisort( $orders, SORT_ASC, $categories );
        $html = '';
        foreach ( $categories as $category ) {
            $categoryCount = ( $category->count ? $category->count : '' );
            $categoryTitle = $category->name;
            $categoryID = $category->term_id;
            $categoryParent = $category->parent;
            $applyCancelButtons = self::applyCancelButtons();
            $dragButton = self::dragButton();
            $depth = $this->categorifyFindDepth( $category, $categories );
            $extraHTML = '';
            $extraHTML .= '<ul class="categorify_be_placeholder"></ul>';
            $extraHTML .= '<input class="input_category_id" type="hidden" value="' . esc_html( $categoryID ) . '" />';
            $extraHTML .= '<input class="input_parent_id" type="hidden" value="' . esc_html( $categoryParent ) . '" />';
            $html .= '<li id="cc_category_item_' . esc_html( $categoryID ) . '" class="category_item category_item_depth_' . esc_html( $depth ) . ' parent_' . esc_html( $categoryID ) . '" data-parent-id="parent_' . esc_html( $categoryParent ) . '" data-id="' . esc_html( $categoryID ) . '">';
            $html .= '<div class="cat_item">';
            $html .= '<span class="cc_dropdown"></span>';
            $html .= '<a href="#">';
            $html .= '<span class="cc_icon_wrap">';
            $html .= '<span class="cc_icon cc_icon_folder">' . self::getIcon( 'folder' ) . '</span>';
            $html .= '<span class="cc_icon cc_icon_subfolder">' . self::getIcon( 'folder-plus' ) . '</span>';
            $html .= '<span class="cc_icon cc_icon_subfolderopen">' . self::getIcon( 'down-arrow' ) . '</span>';
            $html .= '</span>';
            $html .= '<span class="cc_title">' . esc_html( $categoryTitle ) . '</span>';
            $html .= '<span class="cc_count">' . esc_html( $categoryCount ) . '</span>';
            $html .= '</a>';
            $html .= '<div class="cc_changer"><div><input type="text" value="' . esc_html( $categoryTitle ) . '" /></div></div>';
            //$html .= wp_kses_post($dragButton);
            $html .= wp_kses_post( $applyCancelButtons );
            $html .= '</div>';
            $html .= $extraHTML;
            // extraHTML was already escaped
            $html .= '</li>';
        }
        return $html;
    }
    
    /* since 1.0 */
    private function categorifyFindDepth( $folder, $folders, $depth = 0 )
    {
        
        if ( $folder->parent != 0 ) {
            $depth = $depth + 1;
            $parent = $folder->parent;
            $find = array_filter( $folders, function ( $arr ) use( $parent ) {
                
                if ( $arr->term_id == $parent ) {
                    return $arr;
                } else {
                    return null;
                }
            
            } );
            
            if ( is_null( $find ) ) {
                return $depth;
            } else {
                foreach ( $find as $k2 => $v2 ) {
                    return $this->categorifyFindDepth( $v2, $folders, $depth );
                }
            }
        
        } else {
            return $depth;
        }
    
    }

}