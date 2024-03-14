<?php 
	if ( ! defined( 'ABSPATH' ) ) { exit; }
	
	global $general_options,$post_type_options;
	
	if(function_exists('vc_add_param')){
		require_once(THEPLUS_PLUGIN_PATH.'vc_elements/vc_param/vc_param.php');
		$check_elements=pt_plus_get_option('general','check_elements');
		if(isset($check_elements) && !empty($check_elements)){
			foreach($check_elements as $value) {
				foreach(glob(THEPLUS_PLUGIN_PATH.'vc_elements/map_shortcodes/'.$value.'.php') as $shortcode) {
					require_once($shortcode);
				}
			}
		}else{
			foreach(glob(THEPLUS_PLUGIN_PATH.'vc_elements/map_shortcodes/*.php') as $shortcode) {
				require_once($shortcode);
			}
		}
	}

function pt_plus_get_option($options_type,$field){
	$general_options=get_option( 'general_options' );
	$post_type_options=get_option( 'post_type_options' );
	$values='';
	if($options_type=='general'){
		if(isset($general_options[$field]) && !empty($general_options[$field])){
			$values=$general_options[$field];
		}
	}
	if($options_type=='post_type'){
		if(isset($post_type_options[$field]) && !empty($post_type_options[$field])){
			$values=$post_type_options[$field];
		}
	}
	return $values;
}
function pt_plus_getFontsData( $fontsString ) {   
 
    $googleFontsParam = new Vc_Google_Fonts();      
    $fieldSettings = array();
    $fontsData = strlen( $fontsString ) > 0 ? $googleFontsParam->_vc_google_fonts_parse_attributes( $fieldSettings, $fontsString ) : '';
    return $fontsData;
     
}
 
function pt_plus_googleFontsStyles( $fontsData ) {
     
    $fontFamily = explode( ':', $fontsData['values']['font_family'] );
    $styles[] = 'font-family:' . $fontFamily[0];
    $fontStyles = explode( ':', $fontsData['values']['font_style'] );
    $styles[] = 'font-weight:' . $fontStyles[1];
    $styles[] = 'font-style:' . $fontStyles[2];
     
    $inline_style = '';     
    foreach( $styles as $attribute ){           
        $inline_style .= $attribute.'; ';       
    }   
     
    return $inline_style;
     
}
 
function pt_plus_enqueueGoogleFonts( $fontsData ) {
     
    $settings = get_option( 'wpb_js_google_fonts_subsets' );
    if ( is_array( $settings ) && ! empty( $settings ) ) {
        $subsets = '&subset=' . implode( ',', $settings );
    } else {
        $subsets = '';
    }
	
    if ( isset( $fontsData['values']['font_family'] ) ) {
        wp_enqueue_style( 
            'vc_google_fonts_' . vc_build_safe_css_class( $fontsData['values']['font_family'] ), 
            '//fonts.googleapis.com/css?family=' . $fontsData['values']['font_family'] . $subsets
        );
    }
}
if(!function_exists('pt_plus_gradient_color')){
	function pt_plus_gradient_color($overlay_color1,$overlay_color2,$overlay_gradient) {
$gradient_style='';
if($overlay_gradient=='horizontal'){
	$gradient_style ='background: -moz-linear-gradient(left, '.esc_attr($overlay_color1).' 0%, '.esc_attr($overlay_color2).' 100%);background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.esc_attr($overlay_color1).'), color-stop(100%,'.esc_attr($overlay_color2).'));background: -webkit-linear-gradient(left, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -o-linear-gradient(left, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -ms-linear-gradient(left, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: linear-gradient(to right, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);';
}elseif($overlay_gradient=='vertical'){
 $gradient_style ='background: -moz-linear-gradient(top, '.esc_attr($overlay_color1).' 0%, '.esc_attr($overlay_color2).' 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.esc_attr($overlay_color1).'), color-stop(100%,'.esc_attr($overlay_color2).'));background: -webkit-linear-gradient(top, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -o-linear-gradient(top, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -ms-linear-gradient(top, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: linear-gradient(to bottom, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);';
}elseif($overlay_gradient=='diagonal'){
$gradient_style ='background: -moz-linear-gradient(45deg, '.esc_attr($overlay_color1).' 0%, '.esc_attr($overlay_color2).' 100%);background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,'.esc_attr($overlay_color1).'), color-stop(100%,'.esc_attr($overlay_color2).'));background: -webkit-linear-gradient(45deg, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -o-linear-gradient(45deg, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -ms-linear-gradient(45deg, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: linear-gradient(45deg, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);';
}elseif($overlay_gradient=='radial'){
 $gradient_style ='background: -moz-radial-gradient(center, ellipse cover, '.esc_attr($overlay_color1).' 0%, '.esc_attr($overlay_color2).' 100%);background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,'.esc_attr($overlay_color1).'), color-stop(100%,'.esc_attr($overlay_color2).'));background: -webkit-radial-gradient(center, ellipse cover, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -o-radial-gradient(center, ellipse cover, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: -ms-radial-gradient(center, ellipse cover, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);background: radial-gradient(ellipse at center, '.esc_attr($overlay_color1).' 0%,'.esc_attr($overlay_color2).' 100%);';
}
	   return $gradient_style; 
	}
}
/*----------------------Header breadcurmbss------------------------------*/
	function pt_plus_breadcrumbs() {

    /* === OPTIONS === */
    $text['home']     = __('Home', 'pt_theplus'); 
    $text['category'] = __('Archive by "%s"', 'pt_theplus'); 
    $text['search']   = __('Search Results for "%s" Query', 'pt_theplus');
    $text['tag']      = __('Posts Tagged "%s"', 'pt_theplus');
    $text['author']   = __('Articles Posted by %s', 'pt_theplus');
    $text['404']      = __('Error 404', 'pt_theplus');

    $showCurrent = 1; 
    $showOnHome  = 1; 
    $delimiter   = ' <span class="del"></span> '; 
    $before      = '<span class="current">';
    $after       = '</span>';
    /* === END OF OPTIONS === */

    global $post;
    $homeLink = home_url() . '/';
    $linkBefore = '<span>';
    $linkAfter = '</span>';
    $link = $linkBefore . '<a href="%1$s">%2$s</a>' . $linkAfter;

    if (is_home() || is_front_page()) {

        if ($showOnHome == 1) $crumbs_output = '<nav id="crumbs"><a href="' . esc_url($homeLink) . '">' . esc_html($text['home']) . '</a></nav>';

    } else {

        $crumbs_output ='<nav id="crumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

        if ( is_category() ) {
            $thisCat = get_category(get_query_var('cat'), false);
            if ($thisCat->parent != 0) {
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a', $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                $crumbs_output .= $cats;
            }
            $crumbs_output .= $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            $crumbs_output .= $before . sprintf($text['search'], get_search_query()) . $after;


        }
        elseif (is_singular('topic') ){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }
        /* in forum, add link to support forum page template */
        elseif (is_singular('forum')){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }
        elseif (is_tax('topic-tag')){
            $post_type = get_post_type_object(get_post_type());
            printf($link, $homeLink . '/forums/', $post_type->labels->singular_name);
        }
        elseif ( is_day() ) {
            $crumbs_output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            $crumbs_output .= sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            $crumbs_output .= $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            $crumbs_output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            $crumbs_output .= $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            $crumbs_output .= $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                 $crumbs_output .= $linkBefore . '<a href="'.$homeLink . '/' . $slug["slug"] . '/">'.$post_type->labels->singular_name.'</a>' . $linkAfter;
                if ($showCurrent == 1) $crumbs_output .= $delimiter . $before . esc_html(get_the_title()) . $after;
            } else {
                $cat = get_the_category();
				if(isset($cat[0])) {
					$cat =  $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);
					if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $linkBefore . '<a', $cats);
					$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
					$crumbs_output .= $cats;
					if ($showCurrent == 1) $crumbs_output .= $before . esc_html(get_the_title()) . $after;
				}
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            $crumbs_output .= $before . $post_type->labels->singular_name . $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
			if($cat) {
				$cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, $delimiter);
				$cats = str_replace('<a', $linkBefore . '<a', $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				$crumbs_output .= $cats;
				printf($link, get_permalink($parent), $parent->post_title);
				if ($showCurrent == 1) $crumbs_output .= $delimiter . $before . esc_html(get_the_title()) . $after;
			}
        } elseif ( is_page() && !$post->post_parent ) {
            if ($showCurrent == 1) $crumbs_output .= $before . get_the_title() . $after;

        } elseif ( is_page() && $post->post_parent ) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            for ($i = 0; $i < count($breadcrumbs); $i++) {
                $crumbs_output .= $breadcrumbs[$i];
                if ($i != count($breadcrumbs)-1) $crumbs_output .= $delimiter;
            }
            if ($showCurrent == 1) $crumbs_output .= $delimiter . $before . esc_html(get_the_title()) . $after;

        } elseif ( is_tag() ) {
            $crumbs_output .= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            $crumbs_output .= $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            $crumbs_output .= $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $crumbs_output .= ' (';
            $crumbs_output .= __('Page', 'pt_theplus') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $crumbs_output .= ')';
        }

        $crumbs_output .= '</nav>';

    }
return $crumbs_output;
}
/*----------------------Header breadcurmbss------------------------------*/