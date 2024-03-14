<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Woocommerce;


class ADMINZ_Helper_Woocommerce_Taxonomy{
	static $taxonomy_hien_tai;
	static $list_taxonomy_option;

	function __construct() {		
		if(!class_exists( 'WooCommerce' ) ) return;
	}
	static function bo_loc_term_taxonomy($term,$taxonomy){
		if(in_array($taxonomy,['product_visibility','rating_filter'])){
			if(!in_array($term->slug,['rated-1','rated-2','rated-3','rated-4','rated-5'])){
				return false;
			} }
		return true;		
	}
	static function lay_taxonomy_co_the_loc(){
		$taxonomies = get_object_taxonomies( 'product', 'objects' );
		unset($taxonomies['product_shipping_class']);
		unset($taxonomies['product_type']);
		
    	$taxonomies['title'] = (object) NULL; 
    	$taxonomies['title']->name = "title"; 
    	$taxonomies['title']->labels = (object) NULL;
    	$taxonomies['title']->labels->singular_name = 'Type to search';

    	$taxonomies['price'] = (object) NULL; 
    	$taxonomies['price']->name = "price"; 
    	$taxonomies['price']->labels = (object) NULL;
    	$taxonomies['price']->labels->singular_name = apply_filters('admiz_woo_form_filter_by_price',__('Filter by price','administrator-z'));
    	
		return $taxonomies;
	}
	static function lay_toan_bo_taxonomy_hien_tai(){	
		return [];	
		/*$arr_queried = [];
		$get_queried_object = get_queried_object();
		if(!empty($get_queried_object) and isset($get_queried_object->taxonomy)){
			$arr_queried[self::thay_doi_term_slug_cho_link($get_queried_object->taxonomy)] = $get_queried_object->slug;
		}		
		$arr_request = [];
		if(!empty($_GET) and is_array($_GET)){
			foreach ($_GET as $key => $value) {
				if($value){
					$arr_request[$key] = $value;
				}
			}
		}		
		$return = []; 
		$return = $arr_queried;
		if(!empty($arr_request) and is_array($arr_request)){
			foreach ($arr_request as $key => $value) {
				if(array_key_exists($key, $arr_queried)){
					$temp = explode(",",$value);
					$temp[] = $arr_queried[$key];
					$temp = array_unique($temp);
					$value = implode(",",$temp);				
				}
				$return[$key] = $value;
			}
		}
		if(!isset($return['post_type'])) $return['post_type'] = 'product';		
		return $return;*/
	}
	static function lay_taxonomy_hien_tai($taxonomy){
		$current_taxonomy = [];
		$current_taxonomy_ancestors = [];

		if(isset($_GET[$taxonomy]) and $_GET[$taxonomy]){
			$product_get_arr = explode(",",sanitize_text_field($_GET[$taxonomy]));
			foreach ($product_get_arr as $key => $value) {		
				$termobj = get_term_by( 'slug', $value,$taxonomy );
				$current_taxonomy[] = $termobj->term_id;				
				if(isset(get_ancestors($termobj->term_id,$taxonomy)[0])){
					$current_taxonomy_ancestors[] = get_ancestors($termobj->term_id,$taxonomy)[0];
				}				
			}
		}else{
			if(isset(get_queried_object()->term_id)){
				$current_taxonomy = get_queried_object()->term_id;
			}
			if($current_taxonomy){
				$current_taxonomy_ancestors = get_ancestors( $current_taxonomy, $taxonomy );
			}
		}
		return [$current_taxonomy,$current_taxonomy_ancestors];
	}	
	static function co_phai_term_hien_tai($term,$taxonomy){
		if(isset($_GET[$taxonomy]) and in_array($term,(array)$_GET[$taxonomy])){
			return true;
		}
		$get_queried_object = get_queried_object();
		// var_dump($term);
		// echo "<pre>";print_r($get_queried_object->slug);echo "</pre>";
		if(isset($get_queried_object->slug) and $get_queried_object->slug == $term){
			return true;
		}

		return false;
		/*$taxonomy_hien_tai = self::lay_toan_bo_taxonomy_hien_tai();
		if(!isset($taxonomy_hien_tai[$taxonomy])) return false;
		if(!in_array($term,explode(",",$taxonomy_hien_tai[$taxonomy]))) return false; 
		return true;*/
	}
	static function lay_gia_tri_taxonomy_hien_tai($taxonomy){    		
	    if(isset(self::lay_toan_bo_taxonomy_hien_tai()[$taxonomy])){
	    	return self::lay_toan_bo_taxonomy_hien_tai()[$taxonomy];
	    }
	    return "";
	}
	static function thay_doi_gia_tri_term_slug($slug){
		if(in_array($slug, ['rated-1','rated-2','rated-3','rated-4','rated-5'])){
            return str_replace('rated-', '', $slug);
        }
        return $slug;
	}
	// hàm này thay đổi slug cho taxonomy theo giá trị term slug
	static function thay_taxonomy_slug_by_term_value($taxonomy,$current_term){
		if(intval($current_term)>0 ) {
            $taxonomy = "rating_filter";
        }
		return $taxonomy;
	}
	// hàm này thay đổi slug cho taxonomy để fix pa thành filter
	static function thay_doi_term_slug_cho_link($slug){
		if($slug == 'product_visibility') {
			$slug = 'rating_filter';
		}
		if(substr($slug, 0,3) == "pa_"){
            $slug = str_replace("pa_", "filter_", $slug);
        }
        return $slug;
	}	
	static function thay_doi_term_name($termname){
	    $transition_arr = [
	        'rated-1' => '1 star',
	        'rated-2' => '2 stars',
	        'rated-3' => '3 stars',
	        'rated-4' => '4 stars',
	        'rated-5' => '5 stars',
	    ];
	    foreach ($transition_arr as $key => $value) {
	        if($termname == $key){
	            $termname = $value;
	        }
	    }
	    return $termname;
	}
	static function thay_doi_taxonomy_label($taxobj){
		if($taxobj->name == 'product_visibility'){
            //return __('Visibility','administrator-z');
            return __('Product ratings','administrator-z');
        }
        if($taxobj->name == 'product_type'){
            return __('Product Type','administrator-z');
        }
        if($taxobj->name == 'product_tag'){
            return $taxobj->labels->name; 
        }
        if($taxobj->name == 'product_cat'){
            return __('Product categories','administrator-z');
        }
        if($taxobj->name == 'rating_filter'){
            return __('Product ratings','administrator-z');
        }
        return $taxobj->labels->singular_name;
	}
	static function chuyen_doi_term_sang_button_form($term,$taxonomy){
		if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
		ob_start();
		$taxonomy2 = self::thay_doi_term_slug_cho_link($taxonomy);
		$termslug = self::thay_doi_gia_tri_term_slug($term->slug);
		
        /* check is rated number */
        $taxonomy2 = self::thay_taxonomy_slug_by_term_value($taxonomy2,$termslug);

        $active = "";
        if(self::co_phai_term_hien_tai($termslug,$taxonomy2)){
            $active = "active";
        }                                                

        ?>
        <label data-value="<?php echo esc_attr($termslug);?>" data-tax="<?php echo esc_attr($taxonomy2);?>" class="<?php echo esc_attr($active);?>"> 
        	<?php echo self::thay_doi_term_name($term->name); ?>
        </label>
        <?php
        return ob_get_clean();
	}	
	static function chuyen_doi_term_option_select($term,$taxonomy, $i,$parent = ''){
    	if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
	    ob_start();    
	    $termname = $term->name;
	    $termname = self::thay_doi_term_name($termname);

	    $termslug = $term->slug;
	    $termslug = self::thay_doi_gia_tri_term_slug($termslug);

	    $selected = "";
	    $taxonomy = self::thay_doi_term_slug_cho_link($taxonomy);
	    $taxonomy = self::thay_taxonomy_slug_by_term_value($taxonomy,$termslug);
	    $class = [];

	    // nếu là children và enable mod hide child	    
	    if($i and isset(ADMINZ_Woocommerce::$options['enable_select2_multiple_hide_child']) and  ADMINZ_Woocommerce::$options['enable_select2_multiple_hide_child'] == 'on'){
	    	$class[]= 'hidden';
	    }
	    if($i){
	    	$class[]= 'is-children';
	    }
	    if(!empty($term->children)){
	    	$class[]= 'has-children';
	    }

	    if(self::co_phai_term_hien_tai($termslug,$taxonomy)){
	    	$selected = "selected";
	    }
	    // var_dump($termslug);
	    // var_dump($taxonomy);
	    // var_dump($selected);
	    
    	echo '<option     			
    			class="'.esc_attr(implode(' ',$class)).'"
    			'.esc_attr($selected).' 
    			value="'.esc_attr($termslug).'" 
    			data-parent="'.$parent.'"
    			data-value="'.esc_attr($termslug).'"
    			data-taxonomy="'.esc_attr($taxonomy).'">
    				'.esc_attr($i).esc_attr($termname).'
				</option>';
	    
	    
	    if(!empty($term->children) and is_array($term->children)){
	        $i .= "—";
	        foreach ($term->children as $key => $value) {
	            echo self::chuyen_doi_term_option_select($value,$taxonomy, $i,$termslug);
	        }        
	    }
	    return ob_get_clean();
	}		
	static function chuyen_doi_term_sang_link_widget($term,$taxonomy,$query_type=""){		
		if(!self::bo_loc_term_taxonomy($term,$taxonomy)){return ;}
		ob_start();
		$taxonomy2 = self::thay_doi_term_slug_cho_link($taxonomy); 
        $termslug = self::thay_doi_gia_tri_term_slug($term->slug);

        /* check is rated number */
        $taxonomy2 = self::thay_taxonomy_slug_by_term_value($taxonomy2,$termslug);

        $active = "";
        if(self::co_phai_term_hien_tai($termslug,$taxonomy2)){
            $active = "active";
        }        
        $links = self::lay_link_term_widget($termslug,$taxonomy2,$query_type);        
        ?>
        <div style="display:inline-block; position: relative;">
        	
            <a href="<?php echo esc_attr($links); ?>" class="<?php echo esc_attr($active);?>"> 
            	<?php 
            		if($active){
            			echo Adminz::get_icon_html('close',['width'=> '1em']);   
            		}
				?>
            	<?php echo self::thay_doi_term_name($term->name); ?>
            </a>
        </div>
        <?php

    	return ob_get_clean();
	}
	static function lay_link_list_metakey_widget($value,$metakey){
		$current_tax = self::lay_toan_bo_taxonomy_hien_tai();
		$current_url_args = array_unique (
			array_merge (
				$current_tax, 
				$_GET
			)
		);

		if(!array_key_exists($metakey,$current_url_args)){
			$current_url_args[$metakey] = $value;
		}else{
			$list = explode(",",$current_url_args[$metakey]);
			if(!in_array($value,$list)){
				$list[] = $value;				
			}else{
				if(!empty($list) and is_array($list)){
					foreach ($list as $key => $cvalue) {
						if($cvalue == $value){
							unset($list[$key]);
						}
					}
				}
			}
			$current_url_args[$metakey] = implode(',',$list);
		}
		$link = add_query_arg($current_url_args, wc_get_page_permalink( 'shop' ));				   
        $active = "";   
        if(isset($_GET[$metakey])){
        	$list = explode(",", sanitize_text_field($_GET[$metakey]));
        	if(in_array($value,$list)){
        		$active = "active current-cat";
        	}
        }
        ob_start();		  
        ?>
    	<li class="cat-item <?php echo esc_attr($active);?>">
    		<a href="<?php echo esc_attr($link); ?>">
    			<?php echo esc_attr($value); ?>
    		</a>
    	</li>
        <?php

    	return ob_get_clean();
	}
	static function chuyen_doi_metakey_sang_link_widget($value,$metakey,$query_type=""){
		$current_tax = self::lay_toan_bo_taxonomy_hien_tai();
		$current_url_args = array_unique (
			array_merge (
				$current_tax, 
				$_GET
			)
		);

		if(!array_key_exists($metakey,$current_url_args)){
			$current_url_args[$metakey] = $value;
		}else{
			$list = explode(",",$current_url_args[$metakey]);
			if(!in_array($value,$list)){
				$list[] = $value;				
			}else{
				if(!empty($list) and is_array($list)){
					foreach ($list as $key => $cvalue) {
						if($cvalue == $value){
							unset($list[$key]);
						}
					}
				}
			}
			$current_url_args[$metakey] = implode(',',$list);
		}		
		$link = add_query_arg($current_url_args, wc_get_page_permalink( 'shop' ));				   
        $active = "";   
        if(isset($_GET[$metakey])){
        	$list = explode(",", sanitize_text_field($_GET[$metakey]));
        	if(in_array($value,$list)){
        		$active = "active";
        	}
        }
        ob_start();		  
        ?>
        <div style="display:inline-block; position: relative;">        	
            <a href="<?php echo esc_attr($link); ?>" class="<?php echo esc_attr($active);?>"> 
            	<?php 
            		if($active){
            			echo Adminz::get_icon_html('close',['width'=> '1em']);   
            		}
				?>
            	<?php echo esc_attr($value); ?>
            </a>
        </div>
        <?php
    	return ob_get_clean();
	}
	static function lay_input_query_type($taxonomy,$currentvalue = "or"){		
		if(substr($taxonomy, 0,3) !== "pa_") return ;		
		ob_start();
		$name = self::chuyen_doi_taxonomy_sang_query_type($taxonomy);
		$value = $currentvalue;
		if(array_key_exists($name,$_GET)){
			$value = sanitize_text_field($_GET[$name]);
		}
		?>
		<input 
            type="hidden" 
            class="query_type"
            name="<?php echo esc_attr($name);?>" 
            placeholder="<?php echo esc_attr($name);?>"
            value="<?php echo esc_attr($value);?>"
        />
		<?php
		return ob_get_clean();
	}
	static function chuyen_doi_taxonomy_sang_query_type($taxonomy){
		if(substr($taxonomy, 0,3) !== "pa_") return ;
		$taxonomy = str_replace("pa_", "", $taxonomy);
		return "query_type_".$taxonomy;
	}
	static function sap_xep_lai_cha_con(Array &$cats, Array &$into, $parentId = 0) {
	    foreach ($cats as $i => $cat) {
	        if ($cat->parent == $parentId) {
	            $into[$cat->term_id] = $cat;
	            unset($cats[$i]);
	        }
	    }

	    foreach ($into as $topCat) {
	        $topCat->children = array();
	        self::sap_xep_lai_cha_con($cats, $topCat->children, $topCat->term_id);
	    }
	}
	static function lay_gia_lon_nho_tu_database() {
	    global $wpdb;
	 
	    $sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
	    $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id ";
	    $sql .= "   WHERE {$wpdb->posts}.post_type IN ('product')
	            AND {$wpdb->posts}.post_status = 'publish'
	            AND price_meta.meta_key IN ('_price')
	            AND price_meta.meta_value > '' ";
	    $prices = $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	    return [
	        'min' => floor( $prices->min_price ),
	        'max' => ceil( $prices->max_price )
	    ];
	}
	static function lay_link_price_widget($current_price){
		if(empty($current_price)) return; 


		$final_arr = self::lay_toan_bo_taxonomy_hien_tai();		

		if(isset($final_arr['min_price']) and $final_arr['min_price'] == $current_price[0]){
			unset($final_arr['min_price']);
		}else{
			$final_arr['min_price'] = $current_price[0];
		}

		if(isset($final_arr['max_price']) and $final_arr['max_price'] == $current_price[1]){
			unset($final_arr['max_price']);
		}else{
			$final_arr['max_price'] = $current_price[1];
		}

		if(
			$current_price[0] == $current_price[1] and 
			$current_price[0] == 0
		){
			unset($final_arr['min_price']);
			unset($final_arr['max_price']);
		}

		if(!$current_price[0]){
			unset($final_arr['min_price']);
		}
		if(!$current_price[1]){
			unset($final_arr['max_price']);
		}
		$link = add_query_arg($final_arr, wc_get_page_permalink( 'shop' ));		
		return $link;


	}
	static function lay_link_term_widget($current_term, $taxonomy,$query_type=""){
		$current_term = self::thay_doi_gia_tri_term_slug($current_term);
        $taxonomy = self::thay_taxonomy_slug_by_term_value($taxonomy,$current_term);

		$final_arr = self::lay_toan_bo_taxonomy_hien_tai();


		// kiem tra co phai term hien tai
		if(isset($final_arr[$taxonomy])){
			
			$value = explode(",",$final_arr[$taxonomy]);
			unset($final_arr[$taxonomy]);

			if(in_array($current_term,$value)){			

				$index = array_search($current_term,$value);								
				unset($value[$index]);

				if(empty($value)){
					unset($value);
				}
			}else{
				$value[] = $current_term;
				$value = array_unique($value);				
			}

			// nếu còn giá trị term			
			if(isset($value)){
				array_unshift($final_arr, [$taxonomy=>implode(",",$value)]);
				$final_arr = self::them_query_type($final_arr,$taxonomy,$query_type);
			}else{
				$final_arr = self::bo_query_type($final_arr,$taxonomy,$query_type);
			}
		}else{			
			if($current_term){
				$final_arr[$taxonomy] = $current_term;
				$final_arr = self::them_query_type($final_arr,$taxonomy,$query_type);
			}			
		}		

		return add_query_arg($final_arr, wc_get_page_permalink( 'shop' ));
	}	
	
	
	static function them_query_type($final_arr,$taxonomy,$query_type){
		if($query_type !=="" and substr($taxonomy,0,7) == "filter_"){
			$final_arr["query_type_".str_replace("filter_","",$taxonomy)]= $query_type;
		}
		return $final_arr;
	}
	static function bo_query_type($final_arr,$taxonomy,$query_type){
		if($query_type !=="" and substr($taxonomy,0,7) == "filter_"){
			unset($final_arr["query_type_".str_replace("filter_","",$taxonomy)]);
		}
		return $final_arr;
	}
	static function convert_thousand($value){
		/*$use_custom_thousand = [];
	    if(
	        isset(ADMINZ_Woocommerce::$options['filter_price_thousand_from']) and 
	        ADMINZ_Woocommerce::$options['filter_price_thousand_from'] and 
	        isset(ADMINZ_Woocommerce::$options['filter_price_thousand_to']) and 
	        ADMINZ_Woocommerce::$options['filter_price_thousand_to']
	    ){
	        $use_custom_thousand = [
	            array_reverse(explode("\n",ADMINZ_Woocommerce::$options['filter_price_thousand_from'])),
	            array_reverse(explode("\n",ADMINZ_Woocommerce::$options['filter_price_thousand_to'])),
	        ];
	    }
	    if(!empty($use_custom_thousand)){
	    	return str_replace($use_custom_thousand[0],$use_custom_thousand[1],$value);
		}else{
			return wc_price($value);
		}*/
	}
	static function get_price_range_2($global_filter_price,$step = false){
		return;
		/*$price_range = [];
	    $price_range_2 = [];    
	    if(
	        ($global_filter_price == "on" or $global_filter_price == "true")
	        and ADMINZ_Woocommerce::$options['filter_price_values']
	        and ADMINZ_Woocommerce::$options['filter_price_display']
	    ){
	        $filter_price_values = explode("\n",ADMINZ_Woocommerce::$options['filter_price_values']);
	        $filter_price_display = explode("\n",ADMINZ_Woocommerce::$options['filter_price_display']);

	        if(
	            !empty($filter_price_display) and is_array($filter_price_display) and 
	            !empty($filter_price_values) and is_array($filter_price_values) and 
	            (count($filter_price_values) == count($filter_price_display))
	        ){
	            $temp = [];
	            foreach ($filter_price_values as $key => $value) {
	                $explode = explode("-",str_replace(" ", "", $value));
	                $temp = [
	                    isset($explode[0])? trim($explode[0]) : "",
	                    isset($explode[1])? trim($explode[1]) : "",
	                    $filter_price_display[$key]
	                ];
	                $price_range_2[] = $temp;
	            }
	        }
	        
	    }else{
	        $minmax = ADMINZ_Helper_Woocommerce_Taxonomy::lay_gia_lon_nho_tu_database();

	        if($minmax['min'] == 0 and $minmax['max'] == 0 ) return;
	        $min = $minmax['min'];
	        $max = $minmax['max'];

	        if(!isset($step) or !$step){
	            $step = round($max/10);
	        }
	        
	        for ($i=$min; $i <=$max ; $i+=$step) { 
	            $price_range[] = $i;
	        }	       

	        array_shift($price_range);

	        
	        $price_range_2 = [];
	        
	        if(!empty($price_range) and is_array($price_range)){
	            if($price_range[0]>0){
	                array_unshift($price_range, 0);
	            }

	            foreach ($price_range as $key=> $value) {

	                if(!isset($price_range[$key+1])){
	                    $value2 = "";
	                    $text = "> ".ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($value);                                                       
	                }elseif(!$key){
	                    $value2= $price_range[$key+1];
	                    $text = "< ".ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($price_range[$key+1]);                                       
	                }else{
	                    $value2= $price_range[$key+1];
	                    $text = $text = ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($value). " - " .ADMINZ_Helper_Woocommerce_Taxonomy::convert_thousand($price_range[$key+1]);
	                }
	                $price_range_2[] = [
	                    $value,
	                    $value2,
	                    $text
	                ];

	            }
	        }
	    } 
	    return $price_range_2;*/
	}

}