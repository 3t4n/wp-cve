<?php
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Xpathtranslator as Xpathtranslator;
use DOMDocument;
use DOMXpath;
use WC_Product;
use WC_Product_Variable;
use WC_Product_Attribute;
use WC_Product_Variation;
use WC_Product_Grouped;
use WC_Product_External;
use WC_Product_Simple;
use WP_Query;

class ADMINZ_Crawler extends Adminz {
    public $options_group = "adminz_import";
    public $title = 'Crawler Data';
    static $slug = 'adminz_import';
    static $options;    
    function __construct() {
        if(!is_admin()) return;
        
        add_action('admin_init', [$this, 'register_option_setting']);
        add_filter('adminz_setting_tab', [$this, 'register_tab']);    
        add_action( 'adminz_tabs_html',[$this,'tab_html']);

        add_action('wp_ajax_adminz_i_check_data', [$this, 'check_data']);
        add_action('wp_ajax_adminz_i_test_single', [$this, 'test_single']);
        add_action('wp_ajax_adminz_i_test_category', [$this, 'test_category']);
        add_action('wp_ajax_adminz_i_test_product', [$this, 'test_product']);
        add_action('wp_ajax_adminz_i_test_category_product', [$this, 'test_category_product']);
        add_action('wp_ajax_adminz_i_run_import_single', [$this, 'run_import_single']);
        add_action('wp_ajax_adminz_i_run_import_single_product', [$this, 'run_import_single_product']);
        add_action('wp_ajax_adminz_i_run_import_category', [$this, 'run_import_category']);

        $this::$options = get_option('adminz_import',
            [
                'adminz_import_mode_one_by_one'=>'',
                'adminz_import_from_post'=>'https://test.minhkhang.net/?p=9',
                'adminz_import_from_category'=>'https://test.minhkhang.net/?page_id=83',
                'adminz_import_from_product'=>'https://test.minhkhang.net/?p=223',
                'adminz_import_from_product_category'=>'https://test.minhkhang.net/?post_type=product',
                'adminz_import_post_title'=>'.article-inner .entry-header .entry-title',
                'adminz_import_post_thumbnail'=>'.article-inner .entry-header .entry-image',
                'adminz_import_post_content'=>'.article-inner .entry-content',
                'adminz_import_post_category'=>'.entry-category a',
                'adminz_import_post_date'=>'.posted-on time.entry-date',
                'adminz_import_category_post_item'=>'#post-list article',
                'adminz_import_category_post_item_link'=>'.more-link',
                /*'adminz_import_product_header_title'=>'.product-info',*/
                'adminz_import_product_title'=>'.product-info>.product-title',
                'adminz_import_product_prices'=>'.product-info .price-wrapper .woocommerce-Price-amount',
                'adminz_import_product_thumbnail'=>'.woocommerce-product-gallery__image',
                'adminz_import_product_thumbnail_tag'=>'img',
                'adminz_import_product_thumbnail_data_attr'=>'data-src',
                'adminz_import_product_short_description'=>'.product-info .product-short-description',
                'adminz_import_product_content'=>'.woocommerce-Tabs-panel--description',
                'adminz_import_product_single_add_to_cart_button'=>'.product-info .single_add_to_cart_button',
                'adminz_import_product_variations_json'=>'.product-info .variations_form',
                'adminz_import_product_variations_form_select'=>'.product-info .variations',
                'adminz_import_product_grouped_form'=>'.product-info .grouped_form',
                'adminz_import_category_product_item'=>'.products .product',
                'adminz_import_category_product_item_link'=>'.box-image a',
                'adminz_import_thumbnail_url_remove_string'=>"-280x280\n-400x400\n-800x800",
                'adminz_import_content_remove_attrs'=>'a',
                'adminz_import_content_rewrite_image_name'=>'',
                'adminz_import_content_use_exits_image'=>'on',
                'adminz_import_content_remove_tags'=>'iframe,script,video,audio',
                'adminz_import_content_remove_first'=>'0',
                'adminz_import_content_remove_end'=>'0',
                'adminz_import_content_replace_from'=>"January\nFebruary\nMarch\nApril\nMay\nJune\nJuly\nAugust\nSeptember\nOctober\nNovember\nDecember",
                'adminz_import_content_replace_to'=>"1\n2\n3\n4\n5\n6\n7\n8\n9\n10\n11\n12",
                'adminz_import_product_include_image_content_to_gallery'=>'on',
                'adminz_import_product_include_image_variations_to_gallery'=>'on',
                'adminz_import_content_product_decimal_seprator'=>'2',
                'adminz_import_content_thumbnail_data_attr'=>'src',
            ]
        );                
    }   
    function register_tab($tabs) {
        if(!$this->title) return;
        $this->title = $this->get_icon_html('download').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    } 
    function do_import_single($link = false){
        if(!$link){ $link = sanitize_url($_POST['link']); }
        $data = $this->get_single($link);        

        $post_args = array(
            'post_title'    => $data['post_title'],
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id()
        );

        $post_id = wp_insert_post( $post_args, $wp_error );
        if(!$post_id){
            wp_send_json_success('<div><code>Cannot import!</code><button class="button single_post_import_in_list" type="button">Run</button></div>');
            wp_die();
        }
        // query all image and save
        $post_thumbnails = $data['post_thumbnail'];

        $images_imported = [];        
        if(!empty($post_thumbnails) and is_array($post_thumbnails)){
            foreach ($post_thumbnails as $key => $url) {
                $res = $this->save_images($url,$post_args['post_title']."-".$key);                
                $images_imported[$url] = $res['attach_id'];
            }
        }
        if(!empty($images_imported) and is_array($images_imported)){  
            foreach ($images_imported as $url => $id) {
                if($id){
                    set_post_thumbnail( $post_id, $id );
                    break;
                }                
            }
        }
        
        $data['post_content'] = $this->replace_img_content($link,$images_imported,$data['post_content']);

        $content_replaced = array(
            'ID'           => $post_id,
            'post_content' => $data['post_content']
        );
        wp_update_post( $content_replaced );
        return $post_id;
    }
    function do_import_single_product($link = false) {
        if(!$link){ $link = sanitize_url($_POST['link']); }
        $data = $this->get_product($link);

        // first import all images and save to array temp
        $image_all = $data['images_all'];
        $images_imported = [];
        if(!empty($image_all) and is_array($image_all)){
            foreach ($image_all as $key => $url) {
                $res = $this->save_images($url,$data['post_title']."-".$key);
                $images_imported[$url] = $res['attach_id'];
                
                if($res['attach_id']){
                    if(in_array($url, $data['images_gallery'])){
                        $gallery[] = $res['attach_id'];
                    }                    
                    if($url == $data['image_thumbnail']){
                        $product_thumbnail_id = $res['attach_id'];
                    }
                }
            }
        }
        // check produduct type and set product type data
        switch ($data['product_type']) {
            case 'external':
                $product  = new WC_Product_External();
                
                if($data['product_type_data']){
                    $product->set_product_url($data['product_type_data']);
                }
                if($data['_price']){
                    $product->set_regular_price($data['_price']);
                }
                if($data['_sale_price']){
                    $product->set_sale_price($data['_sale_price']);
                }
                break;
            case 'variable':
                $product  = new WC_Product_Variable();
                $variations_list = $data['variations_list'];
                $variations_data = $data['product_type_data'];
                $default_attribute = $data['default_attribute'];

                // attribute
                $attr_array = [];
                if (!empty($variations_list) and is_array($variations_list)){
                    
                    foreach ($variations_list as $key => $value) {
                        $attribute = new WC_Product_Attribute();                
                        $attribute->set_name( $value['attr_name'] );
                        if(!empty($value['attr_options']) and is_array($value['attr_options'])){
                            $option_arr = [];
                            foreach ($value['attr_options'] as $key => $value) {
                                $option_arr[] = $value;
                            }
                            $attribute->set_options($option_arr ); 
                        }
                                           
                        $attribute->set_visible( 1 );
                        $attribute->set_variation( 1 );
                        $attr_array[] = $attribute;
                    }
                }
                $product->set_attributes($attr_array);
                $product->set_default_attributes($default_attribute);

                // variations
                $product_id = $product->save();
                if(!empty($variations_data) and is_array($variations_data)){
                    foreach ($variations_data as $key => $value) {                        
                        $set_attrs = (array)$value->attributes;
                        $temp_set_attrs = [];
                        if(is_array($set_attrs) and !empty($set_attrs)){
                            foreach ($set_attrs as $key => $attr) {        
                                $key = str_replace("attribute_", "", $key);
                                $temp_set_attrs[$key] = $attr;
                            } 
                        }                                              
                        $variation = new WC_Product_Variation();
                        $variation->set_regular_price($value->display_regular_price);
                        $variation->set_sale_price($value->display_price);
                        $variation->set_parent_id($product_id);
                        $variation->set_attributes($temp_set_attrs);                        
                        if(in_array($value->image->url,array_keys($images_imported))){
                            $variation->set_image_id($images_imported[$value->image->url]);
                        }                   
                        $variation->save();
                    }
                }               

                break;
            case 'grouped':
                $product  = new WC_Product_Grouped();
                $data_type = $data['product_type_data'];
                $children = [];
                if(!empty($data_type)){
                    foreach ($data_type as $key => $child) {
                        if(!$child['exits']){
                            $child_id = $this->do_import_single_product($child['url']);
                        }else{
                            $child_id = $child['exits_id'];
                        }
                        $children[] = $child_id;
                    }
                    $product->set_children($children);
                    $product->sync($product);
                }                
                break;
            default:
                $product  = new WC_Product_Simple();

                if($data['_price']){
                    $product->set_regular_price($data['_price']);
                }
                if($data['_sale_price']){
                    $product->set_sale_price($data['_sale_price']);
                }
                break;
        }

        $product->set_name($data['post_title']);
        $product->set_status('publish');
        if($data['short_description']){
            $product->set_short_description($data['short_description']);
        }
        $product_id = $product->save();

        // content thumbnails
        if(isset($product_thumbnail_id)){
            $product->set_image_id($product_thumbnail_id);
            // unset if like product thumbnail
            if(!empty($gallery) and is_array($gallery)){
                foreach ($gallery as $key => $value) {
                    if($value == $product_thumbnail_id){
                        unset($gallery[$key]);
                    }
                }
            }        
        } 

        if(isset($gallery)){
            $product->set_gallery_image_ids($gallery);
        }

        // fix content image url
        $data['post_content'] = $this->replace_img_content($link,$images_imported,$data['post_content']);
        $content_replaced = array(
            'ID'           => $product_id,
            'post_content' => $data['post_content']
        ); 
        wp_update_post( $content_replaced );
        
        $product_id = $product->save();
        if(!$product_id){
            wp_send_json_success('<div><code>Cannot import!</code><button class="button single_product_import_in_list" type="button">Run</button></div>');
            wp_die();
        }
        return $product_id;
    }
    function get_single($link){
        $html = $this->get_remote($link);
        $return = [];        
        
        //start check
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXpath($doc);        

        // title
        $title_dom = $this->get_option_value('adminz_import_post_title');        
        if($title_dom){
            $title = $xpath->query($this->get_xpath_query([$title_dom]));
            if (!is_null($title)) {
                foreach ($title as $element) {
                    $nodes = $element->childNodes;
                    foreach ($nodes as $node) {
                        $return['post_title'] .= $this->fix_content($node->nodeValue, $link);
                    }
                }
            } 
        }              
        
        $imgatt = $this->get_option_value('adminz_import_content_thumbnail_data_attr');

        // get entry image as first array
        $image_dom = $this->get_option_value('adminz_import_post_thumbnail');        
        if($image_dom){
            $imgs = $xpath->query($this->get_xpath_query([$image_dom,'img']));
            if (!is_null($imgs)) {
                foreach ($imgs as $element) {
                    if ($element->getAttribute($imgatt)) {
                        $return['post_thumbnail'][] = $this->fix_url($element->getAttribute($imgatt),$link);
                        break;
                    }
                }
            } 
        }
              

        // get all image in .entry-content
        $contentclass = $this->get_option_value('adminz_import_post_content');
        if($contentclass){
            $imgs = $xpath->query($this->get_xpath_query([$contentclass,'img']));
            if (!is_null($imgs)) {
                foreach ($imgs as $element) {
                    if ($element->getAttribute($imgatt)) {                    
                        $return['post_thumbnail'][] = $this->fix_url($element->getAttribute($imgatt),$link);                    
                    }
                }
            }
        }
        $return['post_thumbnail'] = array_values(array_unique($return['post_thumbnail']));
        //post content
        $return['post_content'] = "";
        if($contentclass){
            $content = $xpath->query($this->get_xpath_query([$contentclass]));
            $remove_end = $this->get_option_value('adminz_import_content_remove_end');
            $remove_first = $this->get_option_value('adminz_import_content_remove_first');
            if (!is_null($content)) {
                foreach ($content as $element) {
                    $nodes = $element->childNodes;
                    foreach ($nodes as $key => $node) {
                        if ($key <= (count($nodes) - $remove_end - 1) and $key >= ($remove_first)) {
                            $return['post_content'] .= $this->fix_content($doc->saveHTML($node) , $link);
                        }
                    }
                }
            }
        }   


        // post date
        $date_dom = $this->get_option_value('adminz_import_post_date');        
        if($date_dom){
            $title = $xpath->query($this->get_xpath_query([$date_dom]));
            if (!is_null($title)) {
                foreach ($title as $element) {
                    $nodes = $element->childNodes;
                    foreach ($nodes as $node) {
                        $return['post_date'] .= $this->fix_content($node->nodeValue, $link);
                    }
                }
            } 
        } 
        return $return;
    } 
    function get_product($link){
        $html = $this->get_remote($link);
        $return = [];
        
        //start check
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXpath($doc);


        //$header_dom = $this->get_option_value('adminz_import_product_header_title');
        // product type
        $type = $xpath->query($this->get_xpath_query([".type-product","div"]));
        $return['product_type'] = "simple";
        $return['images_gallery'] = [];
        $return['image_thumbnail'] = "";
        $return['images_gallery'] = [];
        $return['images_variations'] = [];
        $return['images_content'] = [];
        if (!is_null($type)) {
            foreach ($type as $element) {               
                if($element->parentNode->getAttribute('id')){
                    $class = $element->parentNode->getAttribute('class');
                    $class = explode(" ", $class);
                    if(in_array("product-type-grouped", $class)){
                        $return['product_type']=  'grouped';
                    }
                    if(in_array("product-type-external", $class)){
                        $return['product_type']=  'external';
                    }
                    if(in_array("product-type-variable", $class)){
                        $return['product_type']=  'variable';
                    }
                }                
            }
        }
        switch ($return['product_type']) {
            case 'external':
                $single_add_to_cart_button = $this->get_option_value('adminz_import_product_single_add_to_cart_button');
                if($single_add_to_cart_button){
                    $external_url = $xpath->query($this->get_xpath_query([$single_add_to_cart_button]));
                    if (!is_null($external_url)) {
                        foreach ($external_url as $external) {
                            $return['product_type_data'] = $external->parentNode->getAttribute('action');
                        }
                    }
                }                
                break;
            case 'variable':
                $variable_form_dom = $this->get_option_value('adminz_import_product_variations_json');
                if($variable_form_dom){
                    $variable_form = $xpath->query($this->get_xpath_query([$variable_form_dom]));
                    if (!is_null($variable_form)) {
                        foreach ($variable_form as $element) {
                            $data_variable = $element->getAttribute('data-product_variations');
                            $data_variable = json_decode( $data_variable );
                            $return['product_type_data'] = $data_variable;
                            if(!empty($data_variable) and is_array($data_variable)){
                                foreach ($data_variable as $key => $variable) {
                                    $return['images_variations'][] = $this->fix_url($variable->image->url,$link); 
                                }
                            }
                        }
                    }
                }                

                // list variations 
                $return['variations_list'] = [];
                $return['default_attribute'] = [];
                $variations_form_dom = $this->get_option_value('adminz_import_product_variations_form_select');
                if($variations_form_dom){
                    $variations = $xpath->query($this->get_xpath_query([$variations_form_dom,"tr",".label","label"]));
                    if (!is_null($variations)) {
                        foreach ($variations as $element) {
                            $attr_array =  [];
                            $attr_array['attr_name']= $element->textContent;
                            $trnode = $element->parentNode->parentNode;      
                            $attrsquery = $this->get_xpath_query([".value","select","option"]);
                            $attrsquery = ".".substr($attrsquery, 1);
                            $attrs = $xpath->query($attrsquery,$trnode);  
                            //$attrs = $xpath->query("//*[contains(@class, 'value')]//select//option",$trnode);
                            $attr_options = [];      
                            foreach ($attrs as $key => $value) {
                                if($value->getAttribute('selected') == 'selected'){
                                    $return['default_attribute'][$element->getAttribute("for")] = $value->getAttribute("value");
                                }
                                if($value->getAttribute('value')){
                                    $attr_options[]= $value->getAttribute('value');
                                }
                            }
                            $attr_array['attr_options'] = $attr_options;
                            $return['variations_list'][] = $attr_array;
                        }
                    }
                }                                
                break;
            case 'grouped':               

                $grouped_form_dom = $this->get_option_value('adminz_import_product_grouped_form');
                if($grouped_form_dom){
                    $grouped_form = $xpath->query($this->get_xpath_query([$grouped_form_dom,"tr","a"]));                    
                    if (!is_null($grouped_form)) {
                        foreach ($grouped_form as $element) {
                            if(!$element->getAttribute('aria-label')){
                                $temp= array(
                                    'title'=>$element->textContent,
                                    'url'=>$element->getAttribute('href'),
                                    'exits'=>false,
                                    'exits_url'=>false,
                                    'exits_id' =>false,
                                );
                                $exit_product_id = $this->search_product($element->textContent);
                                if($exit_product_id){
                                    $temp['exits'] = true;
                                    $temp['exits_id'] = $exit_product_id;
                                    $temp['exits_url'] = '<a target="_blank" href="'.get_permalink( $exit_product_id ).'">'.get_the_title($exit_product_id).'</a>';                            
                                }
                                $return['product_type_data'][] = $temp;
                            }                            
                        }
                    }
                }                
                break;
            default: 
                $return['product_type_data'] = [];
                break;
        }        
        
        $title_dom = $this->get_option_value('adminz_import_product_title');
        
        // Test for 'div'        


        if($title_dom){
            $title = $xpath->query($this->get_xpath_query([$title_dom]));
            if (!is_null($title)){
                foreach ($title as $element){
                    $nodes = $element->childNodes;
                    foreach ($nodes as $node){
                        if(!isset($return['post_title'])){
                            $return['post_title'] = "";
                        }
                        $return['post_title'].= trim($this->fix_content($node->nodeValue, $link));
                    }
                }
            }
        }
        
        // get price product
        
        $product_prices = $this->get_option_value('adminz_import_product_prices');
        $return['_sale_price'] = 0;
        $return['_price'] = 0;
        if($product_prices){
            $prices_dom = $xpath->query($this->get_xpath_query([$product_prices]));
            $price_arr = [];
            if (!is_null($prices_dom)) {
                foreach ($prices_dom as $element) {
                    preg_match_all('/[0-9]/', $element->textContent, $matches);
                    $price_arr[] = $this->fix_product_price(implode("", $matches[0]));
                }
            }
            if(!empty($price_arr))  {
                $return['_sale_price'] = min($price_arr);
                $return['_price'] = max($price_arr);
            }
            if(count($price_arr)==1){
                unset($return['_sale_price']);
            }
        }     
        
        $imgatt = $this->get_option_value('adminz_import_content_thumbnail_data_attr');

        // images on content
        $contentclass = $this->get_option_value('adminz_import_product_content');
        if($contentclass){
            $imgs = $xpath->query($this->get_xpath_query([$contentclass,'img']));
            if (!is_null($imgs)){
                foreach ($imgs as $element){                             
                    $return['images_content'][] = $this->fix_url($element->getAttribute($imgatt),$link);
                }
            }
        }

        // images on gallery
        $image_dom = $this->get_option_value('adminz_import_product_thumbnail');
        $image_gallery_tag = $this->get_option_value('adminz_import_product_thumbnail_tag');
        $image_gallery_data_attr = $this->get_option_value('adminz_import_product_thumbnail_data_attr');
        if($image_dom and $image_gallery_tag){
            $imgs = $xpath->query($this->get_xpath_query([$image_dom,$image_gallery_tag]));
            if (!is_null($imgs)){
                foreach ($imgs as $element){  
                    $return['images_gallery'][] = $this->fix_url($element->getAttribute($image_gallery_data_attr),$link);                    
                }
            }
        }
        // include variation to gallery
        $include_variations_to_gallery = $this->get_option_value('adminz_import_product_include_image_variations_to_gallery');
        if($include_variations_to_gallery =="on"){            
            $return['images_gallery'] = array_merge($return['images_gallery'],$return['images_variations']);
        }
        // include content to gallery
        $include_content_to_gallery = $this->get_option_value('adminz_import_product_include_image_content_to_gallery');
        if($include_content_to_gallery =="on"){            
            $return['images_gallery'] = array_merge($return['images_gallery'],$return['images_content']);
        }

        // set all image array and thumbnail
        $return['images_all'] = array_values(array_unique(array_merge($return['images_gallery'],$return['images_variations'],$return['images_content'])));

        if(is_array($return['images_all']) and !empty($return['images_all'])){
            $return['image_thumbnail'] = $return['images_all'][0];
        }

        // get product short_description        
        $excerpt_dom = $this->get_option_value('adminz_import_product_short_description');
        if($excerpt_dom){
            $excerpt = $xpath->query($this->get_xpath_query([$excerpt_dom]));            
            if (!is_null($excerpt)) {

                foreach ($excerpt as $element) {                
                    $nodes = $element->childNodes;
                    foreach ($nodes as $node) {
                        $return['short_description'] .= $this->fix_content($doc->saveHTML($node) , $link);
                    }
                }
            }
        }        

        // get product content 
        $contentclass = $this->get_option_value('adminz_import_product_content');
        if($contentclass){
            $content = $xpath->query($this->get_xpath_query([$contentclass]));            
            $remove_end = $this->get_option_value('adminz_import_content_remove_end');
            $remove_first = $this->get_option_value('adminz_import_content_remove_first');
            if (!is_null($content)){
                foreach ($content as $element){
                    $nodes = $element->childNodes;
                    foreach ($nodes as $key => $node){
                        if ($key <= (count($nodes) - (int)$remove_end - 1) and $key >= ($remove_first))
                        {
                            $return['post_content'] .= $this->fix_content($doc->saveHTML($node) , $link);
                        }
                    }
                }
            }
        }         
        return $return;
    }
    function get_category($link) {
        $html = $this->get_remote($link);
        $return = [];
        //start check
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXpath($doc);

        $post_item_dom = $this->get_option_value('adminz_import_category_post_item');
        $post_item_link = $this->get_option_value('adminz_import_category_post_item_link');

        if($post_item_dom){
            $titles = $xpath->query($this->get_xpath_query([$post_item_dom]));
            if (!is_null($titles)) {
                foreach ($titles as $key => $n) {
                    $return[$key]['post_title'] = $n->textContent;

                    foreach ($xpath->query($this->get_xpath_query([$post_item_link]), $n) as $child) {
                        $return[$key]['post_url'] = $this->fix_url($child->getAttribute('href'),$link);
                        break;
                    }
                }
            }
        }
        
        return $return;
    }
    function get_category_product($link) {
        $html = $this->get_remote($link);
        $return = [];
        //start check
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();
        $xpath = new DOMXpath($doc);
        
        $product_item_wrapper = $this->get_option_value('adminz_import_category_product_item');
        $product_item_link = $this->get_option_value('adminz_import_category_product_item_link');

        if($product_item_wrapper){
            $query = $this->get_xpath_query([$product_item_wrapper]);
            $titles = $xpath->query($query);
            if (!is_null($titles)) {
                foreach ($titles as $key => $n) {
                    $return[$key]['post_title'] = $n->textContent;

                    foreach ($xpath->query($this->get_xpath_query([$product_item_link]), $n) as $child) {
                        $return[$key]['post_url'] = $this->fix_url($child->getAttribute('href'),$link);
                        break;
                    }
                }
            }            
        }        

        return $return;
    }
    function run_import_single($link = false) {
        if(!$link){ $link = sanitize_url($_POST['link']); }
        $post_id = $this->do_import_single($link);
        wp_send_json_success("<a target='_blank' href='".get_permalink( $post_id )."'>Complete</a>".'<button class="button single_post_import_in_list" type="button">Run</button>');
        wp_die();
    }
    function run_import_single_product($link = false){
        if(!$link){ $link = sanitize_url($_POST['link']); }
        $post_id = $this->do_import_single_product($link);
        wp_send_json_success("<a target='_blank' href='".get_permalink( $post_id )."'>Complete</a>".'<button class="button single_product_import_in_list" type="button">Run</button>');
        wp_die();
    }
    function test_single() {
        $data = json_encode($this->get_single(sanitize_url($_POST['link'])));
        //endcheck
        $return = "";
        if (!empty($data) and is_array($data)){
            foreach ($data as $key => $value){
                $return .= '<div>' . $key . ": " . $value . '</div>';
            }
        }
        wp_send_json_success($data);
        wp_die();
    } 
    function test_product() {
        $data = json_encode($this->get_product(sanitize_url($_POST['link'])));
        //endcheck
        $return = "";
        if (!empty($data) and is_array($data)){
            foreach ($data as $key => $value){
                $return .= '<div>' . $key . ": " . $value . '</div>';
            }
        }
        wp_send_json_success($data);
        wp_die();
    }
    function test_category() {
        $data = json_encode($this->get_category(sanitize_url($_POST['link'])));
        wp_send_json_success($data);
        wp_die();
    }
    function test_category_product() {
        $data = json_encode($this->get_category_product(sanitize_url($_POST['link'])));
        wp_send_json_success($data);
        wp_die();
    }  
    function check_data($link = false){
        if(!$link) $link = sanitize_url($_POST['link']);
        $data = $this->get_remote($link);
        $data = strlen($data);
        wp_send_json_success($data);
        wp_die();
    }  
    function get_remote($link,$format=true){
        $request  = wp_remote_get( $link );               
        if ('OK' !== wp_remote_retrieve_response_message( $request )
                OR 200 !== wp_remote_retrieve_response_code( $request ) ) {
                return false;
        }else{
            $html = wp_remote_retrieve_body( $request ); 
            if($format){ 
                $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"); 
            }        
            return $html;
        }
    }
    function search_product($title){

        $return = false;
        $exit_products = get_posts([
            'post_type'  => 'product',
            'title' => $title,
            'post_status' =>'publish'
        ]);
        if(is_array($exit_products) and !empty($exit_products)){
            $return = $exit_products[0]->ID;
        }        
        return $return;
    }
    function replace_img_content($link,$images_imported,$content){
        if(empty($images_imported) or !is_array($images_imported)) return $content;
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $content_encode = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        $doc->loadHTML($content_encode);
        
        libxml_clear_errors();
        $xpath = new DOMXpath($doc);

        $imgs = $xpath->query("//img");

        $old_html = [];
        $new_html = [];
        if (!is_null($imgs)) {
            foreach ($imgs as $img) {
                
                $imgurl = $this->fix_url($img->getAttribute('src'),$link);
                
                if (array_key_exists($imgurl, $images_imported)){
                    $width = $img->getAttribute('width')? esc_attr($img->getAttribute('width')) : "";
                    $height = $img->getAttribute('height')? esc_attr($img->getAttribute('height')) : "";
                    $class = $img->getAttribute('class')? esc_attr($img->getAttribute('class')) : "";
                    $size = 'full';
                    if($width and $height){
                        $size = [$width, $height];
                    }
                    $class = null;
                    if($class){
                        $class = ['class'=>$class];
                    }
                    $old_html[] = $doc->saveHTML($img);
                    $new_html[] = wp_get_attachment_image($images_imported[$imgurl],$size,"",$class);
                }
            }
        }

        $content = str_replace($old_html, $new_html, $content);
        return $content;
    }
    function save_images($image_url, $posttitle) {
        $res = [];
        $file = $this->get_remote($image_url,false);
        if(!$file) {
            $res['attach_id'] = false;
            return $res;
        }
        $rewrite_name = $this->get_option_value('adminz_import_content_rewrite_image_name');
        $use_exits_image = $this->get_option_value('adminz_import_content_use_exits_image');

        $im_name = basename($image_url);
        if($rewrite_name == "on"){
            $postname = sanitize_title($posttitle);
            $im_name = "$postname.jpg";            
        }
        

        if($use_exits_image == "on"){            
            $searchname = sanitize_title(str_replace([".jpg",".jpeg",".png",".gif"], "", $im_name));
            $args = array(          
                'name' => $searchname,
                'post_type'=> 'attachment',
            );    
            
            $olds = new WP_Query( $args );
            if ( $olds->have_posts() ) :
                while ( $olds->have_posts() ) : $olds->the_post();
                    $res['attach_id'] = get_the_ID();
                endwhile;                
            endif;
            wp_reset_postdata();
            
        }

        if($res['attach_id']) return $res;

        $res = wp_upload_bits($im_name, '', $file);
        $dirs = wp_upload_dir();
        $filetype = wp_check_filetype($res['file']);
        $attachment = array(
            'guid' => $dirs['baseurl'] . '/' . _wp_relative_upload_path($res['file']) ,
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($res['file'])) ,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $res['file']);
        $attach_data = wp_generate_attachment_metadata($attach_id, $res['file']);
        $uploaded_data = wp_update_attachment_metadata($attach_id, $attach_data);
        if($uploaded_data){
            $res['attach_id'] = $attach_id;
        }else{
            wp_delete_post($attach_id);
        }
        
        return $res;
    }
    function fix_date($string){
        return $string;
    }
    function fix_product_price($price){
        // fix for decima
        return $price/pow(10,(int)$this->get_option_value('adminz_import_content_product_decimal_seprator'));
    }
    function fix_url($url, $link) {        
        preg_match('/(http(|s)):\/\/(.*?)\//si', $link, $output);
        $domain = $output[0];

        // fist not http/https
        if ($url[0] !=="/" and $url[0] != "h" and $url[0] != "t" and $url[0] != "t" and $url[0] != "p"){
            $url = "/". $url;
        }

        // first : //
        if ($url[0] == "/" and $url[1] == "/"){
            $url = "https:". $url;
        }
        // first : /
        if ($url[0] == "/"){
            $url = $domain . substr($url,1);
        }  
        
        $remove_string = $this->get_option_value('adminz_import_thumbnail_url_remove_string');
        if ($remove_string){
            $url = str_replace(
                explode("|", str_replace(["\n", "\r"], ["|", ""], $remove_string)) , 
                '',
                $url
            );
        }

        return $url;
    }
    function fix_content($content, $link = false) {
        preg_match('/(http(|s)):\/\/(.*?)\//si', $link, $output);
        $domain = $output[0];

        // first decode
        $content = htmlentities($content, null, 'utf-8');
        $content = str_replace("&nbsp;", " ", $content);
        $content = html_entity_decode($content);


        // fix missing domain in url/ href
        $preg_arr_from = [
            "/(src|href)=(\'|\")\/\//",
            "/(src|href)=(\'|\")\//",
            "/(src|href)=(\'|\")(?!http|tel|mailto)/",
        ];
        $preg_arr_to = [
            "$1=$2http://",
            "$1=$2".$domain,
            "$1=$2".$domain."$3",
        ];

        if ($this->get_option_value('adminz_import_content_remove_attrs')){
            $remove_attributes = $this->get_option_value('adminz_import_content_remove_attrs');
            $remove_attributes = explode(',',$remove_attributes);
            
            if(!empty($remove_attributes) and is_array($remove_attributes)){
                foreach ($remove_attributes as $attr) {
                    $preg_arr_from[] = '#<'.$attr.'.*?>(.*?)</'.$attr.'>#i';
                    $preg_arr_to[] = '\1';
                }
            }
        }

        if ($this->get_option_value('adminz_import_content_remove_tags')){
            $remove_tags = $this->get_option_value('adminz_import_content_remove_tags');
            $remove_tags = explode(',',$remove_tags);
            
            if(!empty($remove_tags) and is_array($remove_tags)){
                foreach ($remove_tags as $tag) {
                    $preg_arr_from[] = '#<'.$tag.'(.*?)>(.*?)</'.$tag.'>#is';
                    $preg_arr_to[] = '';
                }
            }
        }
        
        
        $imgatt = $this->get_option_value('adminz_import_content_thumbnail_data_attr');
        if($imgatt !=="src"){
            $preg_arr_from[] = '/src/';
            $preg_arr_to[] = 'old';

            $imgatt = str_replace("src","old",$imgatt);

            $preg_arr_from[] = '/'.$imgatt.'/';
            $preg_arr_to[] = 'src'; 
        }
               


        $content = preg_replace(
            $preg_arr_from, 
            $preg_arr_to,
            $content
        );

        

        // replce strings
        $replace_from = $this->get_option_value('adminz_import_content_replace_from');
        $replace_to = $this->get_option_value('adminz_import_content_replace_to');
        if ($replace_from){
            $content = str_replace(
                explode("|", str_replace(["\n", "\r"], ["|", ""], $replace_from)) , 
                explode("|", str_replace(["\n", "\r"], ["|", ""], $replace_to)) , 
                $content
            );
        }
         

        return $content;
    }
    function get_xpath_query($selectors = false){
        $selectors = is_array($selectors) ? implode( " ",$selectors) : $selectors;        
        return new Xpathtranslator($selectors);
    }
    function tool_script() {
        ?>
        <script type="text/javascript">
            (function($){
                $(document).ready(function(){
                    $('.test_single').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            get_fist_results(link,$(this).closest("td").find(".data_check"));
                            test_single(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }                           
                        return false;
                    })
                    $('.test_category').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            get_fist_results(link,$(this).closest("td").find(".data_check"));
                            test_category(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }
                        return false;
                    })
                    $('.test_product').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            get_fist_results(link,$(this).closest("td").find(".data_check"));
                            test_product(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }                           
                        return false;
                    })
                    $('.test_category_product').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            get_fist_results(link,$(this).closest("td").find(".data_check"));
                            test_category_product(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }
                        return false;
                    })
                    $('.run_import_single').click(function(){                        
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            var link_arr = [];
                            link_arr.push([link,$(this).closest("td").find(".data_test")]);
                            run_import_single(link_arr);
                        }else{
                            alert("Input link");
                        }                           
                        return false;
                    })                    
                    $('.run_import_category').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            run_import_category(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }
                        return false;
                    })
                    $('.run_import_category_product').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            run_import_category_product(link,$(this).closest("td").find(".data_test"));
                        }else{
                            alert("Input link");
                        }
                        return false;
                    })
                    $('.run_import_single_product').click(function(){
                        var link = $(this).closest("td").find("input").val();
                        if(link){
                            $(".data").html("");
                            var link_arr = [];
                            link_arr.push([link,$(this).closest("td").find(".data_test")]);
                            run_import_single_product(link_arr);
                        }else{
                            alert("Input link");
                        }                           
                        return false;
                    }) 

                    $(document).on("click",'.single_post_import_in_list',function(){
                        var link = $(this).closest("tr").data("link");
                        if(link){                            
                            var link_arr = [];
                            link_arr.push([link,$(this).closest("td")]);                            
                            run_import_single(link_arr);
                        }else{
                            alert("Input link");
                        }  
                        return false;
                    })  
                    $(document).on("click",'.single_product_import_in_list',function(){
                        var link = $(this).closest("tr").data("link");                        
                        if(link){                            
                            var link_arr = [];
                            link_arr.push([link,$(this).closest("td")]);                            
                            run_import_single_product(link_arr);
                        }else{
                            alert("Input link");
                        }  
                        return false;
                    })
                    function get_fist_results(link,output){                        
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_check_data",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){                                 
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {                                       
                                
                                if(response.success) {                                          
                                    var data_test = JSON.parse(response.data);
                                    var html_test = "";

                                    if(!data_test){
                                        html_test = '<div class="notice notice-alt notice-warning upload-error-message"><p aria-label="Checking...">No HTML string found! Please check url or CSS classes check</p></div>';
                                    }else{
                                        html_test += "<div style='padding: 10px; background-color: white;'>";
                                        html_test += "<code>HTMl string results:</code>";
                                        html_test += data_test;
                                        html_test +="</div>";
                                    }
                                    
                                    output.html(html_test);
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }                  
                    function test_single(link,output){                        
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_single",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){                                 
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {                                       
                                
                                if(response.success) {                                          
                                    var data_test = JSON.parse(response.data);    
                                    //console.log(data_test);                                    
                                    var html_test = "";

                                    if(!data_test.post_title){
                                        html_test = '<div class="notice notice-alt notice-warning upload-error-message"><p aria-label="Checking...">Title not found! Please check url or CSS classes check</p></div>';
                                    }else{
                                        html_test += "<div style='padding: 10px; background-color: white;'>"; 
                                        
                                        html_test +="<code>Thumbnail</code>";
                                        html_test +="<div>";
                                        if(data_test.post_thumbnail){
                                            for (var i = 0; i < data_test.post_thumbnail.length; i++) {
                                                if(i==0){
                                                    html_test +="<div><img src='"+data_test.post_thumbnail[i]+"'/>"; html_test +="</div>";
                                                }else{
                                                    html_test +='<img style="margin-right: 10px; height: 70px; border: 5px solid silver;" src="'+data_test.post_thumbnail[i]+'"/>';
                                                }
                                            }
                                            for (var i = 0; i < data_test.post_thumbnail.length; i++) {
                                                html_test +="<p><small>"+data_test.post_thumbnail[i]+"</small></p>";
                                            }
                                        }else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        html_test +="</div>";

                                        html_test +="<code>Title:</code>";
                                        if(data_test.post_title){
                                            html_test +="<h1>"+data_test.post_title+"</h1>";
                                        } else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }

                                        html_test += "<code>Content:</code>";
                                        if(data_test.post_content){
                                            html_test +="<div>"+data_test.post_content+"</div>";
                                        }else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        html_test +="</div>";
                                    }

                                    
                                    output.html(html_test);
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function test_category(link,output){
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_category",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {
                                
                                if(response.success) {
                                    var data_test = JSON.parse(response.data);                                   
                                    console.log(data_test);
                                    var html_test = "";
                                    if(!data_test.length){
                                        html_test = '<div class="notice notice-alt notice-warning upload-error-message"><p aria-label="Checking...">Title not found! Please check url or CSS classes check</p></div>';
                                    }else{
                                        html_test += "<div style='padding: 10px; background-color: white;'>";
                                        html_test +='<table>';
                                        for (var i = 0; i < data_test.length; i++) {                                            
                                            html_test +='<tr data-link="'+data_test[i]['post_url']+'"> <td class="status" style="width: 30%;"><button class="button single_post_import_in_list" type="button">Run</button></td><td><p>'+data_test[i]['post_title']+'</p><a target="_blank" href="'+data_test[i]['post_url']+'">Link</a></td></tr>';
                                        }                                       
                                        html_test +='</table>';
                                        html_test +="</div>";
                                    }
                                    
                                    output.html(html_test);
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function test_product(link,output){
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_product",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){                                 
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {
                                if(response.success) {
                                    var data_test = JSON.parse(response.data);
                                    console.log(data_test);
                                    var html_test = "";

                                    if(!data_test.post_title){
                                        html_test = '<div class="notice notice-alt notice-warning upload-error-message"><p aria-label="Checking...">Title not found! Please check url or CSS classes check</p></div>';
                                    }else{
                                        html_test += "<div style='padding: 10px; background-color: white;'>"; 
                                        
                                        // thumbnail
                                        html_test +="<code>Thumbnail</code>";
                                        html_test +="<div>";
                                        
                                        if(data_test.image_thumbnail){
                                            html_test +="<div><img style='width: 500px;' src='"+data_test.image_thumbnail+"'/></div>";                                            
                                        }else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        html_test +='<p><small>'+data_test.image_thumbnail+'</small></p>';
                                        html_test +="</div>";

                                        // gallery 
                                        html_test +="<code>Gallery</code>";
                                        html_test +="<div>";
                                        for (var i = 0; i < data_test.images_gallery.length; i++) {
                                            if(i!==0){
                                                html_test +='<img style="margin-right: 10px; height: 100px; border: 5px solid silver;" src="'+data_test.images_gallery[i]+'"/>';
                                            }
                                        }
                                        for (var i = 0; i < data_test.images_gallery.length; i++) {
                                            if(i!==0){
                                                html_test +='<p><small>'+data_test.images_gallery[i]+'</small></p>';
                                            }
                                        }
                                        html_test +="</div>";


                                        // product type
                                        html_test +="<div>";
                                        html_test +="<code>Product type:</code> ";
                                        html_test +=data_test.product_type;                                    
                                        html_test +="</div>";  

                                        // title
                                        html_test +="<code>Title:</code>";
                                        if(data_test.post_title){
                                            html_test +="<h1>"+data_test.post_title+"</h1>";
                                        } else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        html_test +="</br>";    

                                        // price 
                                        html_test +="<code>Price:</code>";
                                        if(data_test.product_type == 'simple'){                                        
                                            if(data_test._price){
                                                html_test +=" <span>Regular: "+data_test._price+"</span>";
                                            } else{
                                                html_test +="<b style='color: white; background-color: red;'>Sale price not found</b>";
                                            }                                        
                                            if(data_test._sale_price){
                                                html_test +=" <span>Sale: "+data_test._sale_price+"</span>";
                                                
                                            }
                                        }
                                        if(data_test.product_type == 'external'){
                                            html_test +=data_test.product_type_data;
                                        }
                                        if(data_test.product_type == 'variable'){
                                            if(data_test.product_type_data.length){
                                                if(data_test.default_attribute){
                                                    html_test+= '</br><code>Default Attribute</code>';
                                                    html_test+= JSON.stringify(data_test.default_attribute);                                              
                                                }
                                                
                                                html_test+= '<table><tr>';
                                                for (var i = 0; i < data_test.product_type_data.length; i++) {
                                                    var attr_image = '<img width="50px" src="'+data_test.product_type_data[i].image.url+'"/>';
                                                    var attr_name = Object.values(data_test.product_type_data[i].attributes);
                                                    var attr_price = data_test.product_type_data[i].display_price;
                                                    var attr_price_regular_sale = data_test.product_type_data[i].display_regular_price;
                                                    html_test+='<td><p>'+attr_image+'</p> <p>'+attr_name+'</p> <p>'+attr_price+'</p><p><del>'+attr_price_regular_sale+'</del></p></td>';
                                                }
                                                html_test +='</tr></table>';
                                            }
                                        }
                                        if(data_test.product_type == 'grouped'){
                                            if(data_test.product_type_data.length){
                                                html_test+= '<table>';
                                                for (var i = 0; i < data_test.product_type_data.length; i++) {
                                                    var exit_product_status = (data_test.product_type_data[i].exits)? "Already exists on the system" : "--"
                                                    var exit_product_url = (data_test.product_type_data[i].exits_url)? data_test.product_type_data[i].exits_url : "will be import at same at";
                                                    html_test += '<tr><td><a target="_blank" href="'+data_test.product_type_data[i].url+'">'+data_test.product_type_data[i].title+'</a></td><td>'+exit_product_status+'</td><td>'+exit_product_url+'</td></tr>';
                                                }
                                                html_test +='</table>';
                                            }
                                        }
                                        html_test +="</br>";

                                        // short description          
                                        html_test +="<code>Short description:</code>";                          
                                        if(data_test.short_description){                                        
                                            html_test +="<div>"+data_test.short_description+"</div>";
                                        }else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        // content
                                        html_test += "<code>Description:</code>";
                                        if(data_test.post_content){
                                            html_test +="<div>"+data_test.post_content+"</div>";
                                        }else{
                                            html_test +="<b style='color: white; background-color: red;'>Not found</b></br>";
                                        }
                                        html_test +="</div>";
                                    }

                                    
                                    output.html(html_test);
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function test_category_product(link,output){
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_category_product",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {
                                
                                if(response.success) {
                                    var data_test = JSON.parse(response.data);
                                    //console.log(data_test);
                                    var html_test = "";
                                    if(!data_test.length){
                                        html_test = '<div class="notice notice-alt notice-warning upload-error-message"><p aria-label="Checking...">Title not found! Please check url or CSS classes check</p></div>';
                                    }else{
                                        html_test += "<div style='padding: 10px; background-color: white;'>";
                                        html_test +='<table>';
                                        for (var i = 0; i < data_test.length; i++) {                                            
                                            html_test +='<tr data-link="'+data_test[i]['post_url']+'"> <td class="status" style="width: 30%;"><button class="button single_product_import_in_list" type="button">Run</button></td><td><p>'+data_test[i]['post_title']+'</p><a target="_blank" href="'+data_test[i]['post_url']+'">Link</a></td></tr>';
                                        }                                       
                                        html_test +='</table>';
                                        html_test +="</div>";
                                    }

                                    output.html(html_test);
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function run_import_single(link_arr,first=0,infinity=true){
                        var link = link_arr[first][0];
                        var output = link_arr[first][1];
                        
                        var start = new Date().getTime();
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_run_import_single",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){
                                var html_run = '<div class="notice notice-alt updating-message"><p aria-label="Importing...">Importing...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {                                
                                if(response.success) {
                                    var end = new Date().getTime();
                                    var html_run = "";
                                    html_run += "<div class='notice notice-alt notice-success updated-message'>";
                                    html_run += '<div aria-label="done">';
                                    html_run += response.data;
                                    html_run +="<code>"+ (end - start)/1000+" seconds</code>";
                                    html_run += '</div>';
                                    html_run +="</div>";
                                    output.html(html_run);

                                    if(infinity){
                                        first ++;
                                        if(first<link_arr.length){
                                            run_import_single(link_arr,first);
                                        } 
                                    }                                    
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })                        
                    }
                    function run_import_category(link,output){
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_category",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){
                                var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {
                                
                                if(response.success) {
                                    var data_test = JSON.parse(response.data);
                                    var html_test = "";
                                    html_test += "<div style='padding: 10px; background-color: white;'>";
                                    html_test +='<table>';
                                    for (var i = 0; i < data_test.length; i++) {                                            
                                        html_test +='<tr data-link="'+data_test[i]['post_url']+'"> <td class="status" style="width: 30%;"><button class="button single_post_import_in_list" type="button">Run</button></td><td><p>'+data_test[i]['post_title']+'</p><a target="_blank" href="'+data_test[i]['post_url']+'">Link</a></td></tr>';
                                    }                                       
                                    html_test +='</table>';
                                    html_test +="</div>";                                       
                                    output.html(html_test);

                                    // foreach all item listed to run single import
                                    var tr = output.find('table').find('tr');
                                    var link_arr = [];
                                    tr.each(function(){
                                        var link = ($(this).attr('data-link'));
                                        var output = $(this).find("td.status");
                                        link_arr.push([link,output]);                                        
                                    });


                                    var mod_one_by_one = <?php echo $this->get_option_value('adminz_import_mode_one_by_one') == 'on' ? 'true' : 'false'; ?>;
                                    if(mod_one_by_one){
                                        run_import_single(link_arr,0);                                        
                                    }else{
                                        for (var i = 0; i < link_arr.length; i++) {
                                            run_import_single(link_arr,i,false);
                                        }
                                    }
                                    
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function run_import_single_product(link_arr,first=0,infinity=true){
                        var link = link_arr[first][0];
                        var output = link_arr[first][1];

                        var start = new Date().getTime();
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_run_import_single_product",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){                                
                                var html_run = '<div class="notice notice-alt updating-message"><p aria-label="Importing...">Importing...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {                                
                                if(response.success) {
                                    var end = new Date().getTime();
                                    var html_run = "";
                                    html_run += "<div class='notice notice-alt notice-success updated-message'>";
                                    html_run += '<div aria-label="done">';
                                    html_run += response.data;
                                    html_run +="<code>"+ (end - start)/1000+" seconds</code>";
                                    html_run += '</div>';
                                    html_run +="</div>";                                    
                                    output.html(html_run);

                                    if(infinity){
                                        first ++;
                                        if(first<link_arr.length){
                                            run_import_single_product(link_arr,first);
                                        }
                                    }                                    
                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                    function run_import_category_product(link,output){
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "adminz_i_test_category_product",
                                link : link
                            },
                            context: this,
                            beforeSend: function(){
                               var html_run = '<div class="notice notice-alt notice-warning updating-message"><p aria-label="Checking...">Checking...</p></div>';
                                output.html(html_run);
                            },
                            success: function(response) {
                                
                                if(response.success) {
                                    var data_test = JSON.parse(response.data);
                                    var html_test = "";
                                    html_test += "<div style='padding: 10px; background-color: white;'>";
                                    html_test +='<table>';
                                    for (var i = 0; i < data_test.length; i++) {                                            
                                        html_test +='<tr data-link="'+data_test[i]['post_url']+'"> <td class="status" style="width: 30%;"><button class="button single_product_import_in_list" type="button">Run</button></td><td><p>'+data_test[i]['post_title']+'</p><a target="_blank" href="'+data_test[i]['post_url']+'">Link</a></td></tr>';
                                    }                                       
                                    html_test +='</table>';
                                    html_test +="</div>";
                                    output.html(html_test);

                                    // foreach all item listed to run single import
                                    var tr = output.find('table').find('tr');
                                    var link_arr = [];
                                    tr.each(function(){
                                        var link = ($(this).attr('data-link'));
                                        var output = $(this).find("td.status");
                                        link_arr.push([link,output]);                                        
                                    });

                                    var mod_one_by_one = <?php echo $this->get_option_value('adminz_import_mode_one_by_one') == 'on' ? 'true' : 'false'; ?>;
                                    if(mod_one_by_one){
                                        run_import_single_product(link_arr,0);                                        
                                    }else{
                                        for (var i = 0; i < link_arr.length; i++) {
                                            run_import_single_product(link_arr,i,false);
                                        }
                                    }

                                }
                                else {
                                    alert('There is an error');
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        })
                    }
                })
            })(jQuery)
        </script>
        <?php
    }
    function tab_html() {
        if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
        global $adminz;
        ?>
        <div class="import_data">
            <form method="post" action="options.php">
                <?php
        settings_fields($this->options_group);
        do_settings_sections($this->options_group);
            ?>
            <table class="form-table table_imports">
                <tr valign="top">
                    <th scope="row">
                        <h3>Import data</h3>
                    </th>
                </tr>
                <tr valign="top">
                    <th scope="row">From post</th>
                    <td>
                        <label>
                            <input type="url" name="adminz_import[adminz_import_from_post]" placeholder="https://test.minhkhang.net/?p=9" value="<?php echo $this->get_option_value('adminz_import_from_post'); ?>"> 
                        </label>
                        <button class="button test_single">Test</button>
                        <button class="button button-primary run_import_single">Run</button>
                        
                        <br>
                        <p class="data data_check"></p>
                        <p class="data data_test"></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">From category</th>
                    <td>
                        <label>
                            <input type="url" name="adminz_import[adminz_import_from_category]" placeholder="https://test.minhkhang.net/?page_id=83" value="<?php echo esc_attr($this->get_option_value('adminz_import_from_category')); ?>"> 
                        </label>
                        <button class="button test_category">Test</button>
                        <button class="button button-primary run_import_category">Run</button>
                        <code>Check single import before run category import is recommended.</code>
                        <br>     
                        <p class="data data_check"></p>                    
                        <p class="data data_test"></p>       
                    </td>
                </tr>
                <?php if(class_exists( 'WooCommerce' ) ){ ?>
                <tr valign="top">
                    <th scope="row">From Product</th>
                    <td>
                        <label>
                            <input type="url" name="adminz_import[adminz_import_from_product]" placeholder="https://test.minhkhang.net/?p=223" value="<?php echo esc_attr($this->get_option_value('adminz_import_from_product')); ?>"> 
                        </label>
                        <button class="button test_product">Test</button>
                        <button class="button button-primary run_import_single_product">Run</button>                        
                        <br>
                        <p class="data data_check"></p>      
                        <p class="data data_test"></p> 
                    </td>
                </tr>  
                <tr valign="top">
                    <th scope="row">From Product category</th>
                    <td>
                        <label>
                            <input type="url" name="adminz_import[adminz_import_from_product_category]" placeholder="https://test.minhkhang.net/?post_type=product" value="<?php echo esc_attr($this->get_option_value('adminz_import_from_product_category')); ?>"> 
                        </label>
                        <button class="button test_category_product">Test</button>
                        <button class="button button-primary run_import_category_product">Run</button>
                        <code>Check single import before run category import is recommended.</code>
                        <br>
                        <p class="data data_check"></p>
                        <p class="data data_test"></p>       
                    </td>
                </tr>   
                <?php } ?>         
            </table>
        </div>
        <?php $this->tool_script(); ?>
            <style type="text/css">
                .adminz_html_domcheck input[type="text"],
                .adminz_html_domcheck select,
                .adminz_html_domcheck textarea{
                    width: 450px;
                    max-width: 100%;
                }
            </style>
            <table class="form-table adminz_html_domcheck">
                <tr valign="top">
                    <th>
                        <h3>HTML Dom checker</h3>
                    </th>
                    <td>
                        
                    </td>
                </tr>
                <tr valign="top">                   
                    <th>
                        Post single
                    </th>
                    <td>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_post_title]" placeholder='.article-inner .entry-header .entry-title' value="<?php echo esc_attr($this->get_option_value('adminz_import_post_title')); ?>" />
                            <code>Title wrapper</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_post_thumbnail]" placeholder='.article-inner .entry-header .entry-image' value="<?php echo esc_attr($this->get_option_value('adminz_import_post_thumbnail')); ?>" />
                            <code>Thumbnails wrapper</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_post_content]" placeholder='.article-inner .entry-content' value="<?php echo esc_attr($this->get_option_value('adminz_import_post_content')); ?>" />
                            <code>Content wrapper</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_post_category]" placeholder='.entry-category a' value="<?php echo esc_attr($this->get_option_value('adminz_import_post_category')); ?>" />
                            <code>Category <mark>Incomplete</mark></code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_post_date]" placeholder='.posted-on time.entry-date' value="<?php echo esc_attr($this->get_option_value('adminz_import_post_date')); ?>" />
                            <code>Posted On <mark>Incomplete</mark></code>
                        </p>
                    </td>
                </tr>
                <tr valign="top">                   
                    <th>
                        Category/ blog
                    </th>
                    <td>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_category_post_item]" placeholder='#post-list article' value="<?php echo esc_attr($this->get_option_value('adminz_import_category_post_item')); ?>" />
                            <code>Post item wrapper</code>
                        </p>     
                        <p>
                            &rdsh;<input type="text" name="adminz_import[adminz_import_category_post_item_link]" placeholder='.more-link' value="<?php echo esc_attr($this->get_option_value('adminz_import_category_post_item_link')); ?>" />
                            <code>Post item link</code>
                        </p>            
                    </td>
                </tr>
                <?php if(class_exists( 'WooCommerce' ) ){ ?>
                <tr valign="top">                   
                    <th>
                        Product single
                    </th>
                    <td>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_title]" placeholder='.product-info>.product-title' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_title')); ?>" />
                            <code>Title wrapper</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_prices]" placeholder='.product-info .price-wrapper .woocommerce-Price-amount' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_prices')); ?>" />
                            <code>Prices</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_single_add_to_cart_button]" placeholder='.product-info .single_add_to_cart_button' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_single_add_to_cart_button')); ?>" />
                            <code>Single add to cart button</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_variations_json]" placeholder='.product-info .variations_form' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_variations_json')); ?>" />
                            <code>Variations json data</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_variations_form_select]" placeholder='.product-info .variations' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_variations_form_select')); ?>" />
                            <code>Variations form select</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_grouped_form]" placeholder='.product-info .grouped_form' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_grouped_form')); ?>" />
                            <code>Grouped form</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_short_description]" placeholder='.product-info .product-short-description' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_short_description')); ?>" />
                            <code>Excerpt wrapper</code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_thumbnail]" placeholder='.woocommerce-product-gallery__image' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_thumbnail')); ?>" />
                            <code>Gallery wrapper</code>
                        </p>
                        <p>
                            &rdsh;<input type="text" name="adminz_import[adminz_import_product_thumbnail_tag]" placeholder='img' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_thumbnail_tag')); ?>" />
                            <code>Gallery item tag</code>
                        </p>
                        <p>
                            &rdsh;<input type="text" name="adminz_import[adminz_import_product_thumbnail_data_attr]" placeholder='data-src' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_thumbnail_data_attr')); ?>" />
                            <code>Gallery item data attribute</code>
                        </p>                        
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_content]" placeholder='.woocommerce-Tabs-panel--description' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_content')); ?>" />
                            <code>Content wrapper </code>| Warning: Select <b>Inside</b> a description tab content for reason: default hide tab css
                        </p> 
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_category]" placeholder='.entry-category a' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_category')); ?>" />
                            <code>Category <mark>Incomplete</mark></code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_date]" placeholder='.posted-on time.entry-date' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_date')); ?>" />
                            <code>Posted On <mark>Incomplete</mark></code>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_product_attr]" placeholder='.posted-on time.entry-date' value="<?php echo esc_attr($this->get_option_value('adminz_import_product_attr')); ?>" />
                            <code>Product attributes <mark>Incomplete</mark></code>
                        </p>

                    </td>
                </tr>
                <tr valign="top">                   
                    <th>
                        Product list
                    </th>
                    <td>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_category_product_item]" placeholder='.products .product' value="<?php echo esc_attr($this->get_option_value('adminz_import_category_product_item')); ?>" />                            
                            <code>Item wrapper</code>
                        </p>   
                        <p>
                            &rdsh;<input type="text" name="adminz_import[adminz_import_category_product_item_link]" placeholder='.box-image a' value="<?php echo esc_attr($this->get_option_value('adminz_import_category_product_item_link')); ?>" />
                            <code>Item wrapper link</code>
                        </p>          
                    </td>
                </tr>
                <?php } ?>
                <tr valign="top">
                    <th><h3>Import Mode</h3></th>
                    <td></td>
                </tr>
                <tr valign="top">                   
                    <th>
                        One by one
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" <?php echo $this->get_option_value('adminz_import_mode_one_by_one') == 'on' ? 'checked' : ''; ?>  name="adminz_import[adminz_import_mode_one_by_one]"/>
                            <code>Enable to run on by one importing. </code><em>Save and reload is required!</em>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <th><h3>Content fix</h3></th>
                    <td></td>
                </tr> 
                <tr valign="top">                   
                    <th>
                        Attachment
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" disabled checked  name=""/>
                            <code>Save images to library</code>
                        </label>
                        <br>                        
                        <label>
                            <input type="checkbox" <?php echo $this->get_option_value('adminz_import_content_use_exits_image') == 'on' ? 'checked' : ''; ?>  name="adminz_import[adminz_import_content_use_exits_image]"/>
                            <code>Use exits images in library to post content</code> <em>Search image name before upload to library</em>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" <?php echo $this->get_option_value('adminz_import_content_rewrite_image_name') == 'on' ? 'checked' : ''; ?>  name="adminz_import[adminz_import_content_rewrite_image_name]"/>
                            <code>Rewrite image name </code> <em> <b>Name structure</b>: [post-title]-[image-index]</em>
                        </label>
                        <br>
                    </td>
                </tr> 
                <tr valign="top">                   
                    <th>
                        Small thumbnail url fix
                    </th>
                    <td>
                        <p>                         
                            <textarea rows="3" cols="40%" class="input-text wide-input " placeholder="-280x280&#10;-400x400" type="text" name="adminz_import[adminz_import_thumbnail_url_remove_string]" ><?php echo esc_attr($this->get_option_value('adminz_import_thumbnail_url_remove_string')); ?></textarea><br>
                        </p>                        
                        <span>https://domain.com/image_folder/image_name<code>-280x280</code>.jpg</span><br>
                        <span>https://domain.com/image_folder/image_name<code>-400x400</code>.jpg</span><br>
                    </td>
                </tr>              
                <tr valign="top">                   
                    <th>
                        Content Fix
                    </th>
                    <td>
                        <p>
                        <label>
                            <input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_import_content_remove_attrs'));?>" placeholder = "a" name="adminz_import[adminz_import_content_remove_attrs]"/>
                            <code>Remove Attributes for Tags</code> | Tags are separated by commas
                        </label>
                        </p>
                        <p>
                        <label>
                            <input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_import_content_remove_tags'));?>" placeholder = "iframe,script,video,audio" name="adminz_import[adminz_import_content_remove_tags]"/>
                            <code>Remove HTML Tags</code> | Tags are separated by commas
                        </label>
                        </p>
                        <p>                         
                            <input type="number" name="adminz_import[adminz_import_content_remove_first]" placeholder = '0' value="<?php echo esc_attr($this->get_option_value('adminz_import_content_remove_first')); ?>" />
                            <code>Removes the number of elements from the <strong>First</strong></code> <em>Useful when removing post meta: date, author, viewcount </em>
                        </p>
                        <p>                         
                            <input type="number" name="adminz_import[adminz_import_content_remove_end]" placeholder = '0' value="<?php echo esc_attr($this->get_option_value('adminz_import_content_remove_end')); ?>" />
                            <code>Removes the number of elements from the <strong>END</strong></code> <em>Useful when removing signatures or socials share buttons</em>
                        </p>
                        <p>
                            <input type="text" name="adminz_import[adminz_import_content_thumbnail_data_attr]" placeholder='src' value="<?php echo esc_attr($this->get_option_value('adminz_import_content_thumbnail_data_attr')); ?>" />
                            <code>Image data attribute</code>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th>
                        Search and replace content
                    </th>
                    <td>
                        
                        <p>                         
                            <textarea rows="3" cols="40%" class="input-text wide-input " type="text" name="adminz_import[adminz_import_content_replace_from]" placeholder="January&#10;February&#10;March&#10;April&#10;May&#10;June&#10;July&#10;August&#10;September&#10;October&#10;November&#10;December" ><?php echo esc_attr($this->get_option_value('adminz_import_content_replace_from')); ?></textarea><br>
                        </p>
                        <p>             
                            <textarea rows="3" cols="40%" class="input-text wide-input " type="text" name="adminz_import[adminz_import_content_replace_to]" placeholder="1&#10;2&#10;3&#10;4&#10;5&#10;6&#10;7&#10;8&#10;9&#10;10&#10;11&#10;12"><?php echo esc_attr($this->get_option_value('adminz_import_content_replace_to')); ?></textarea><br>
                        </p>
                        <code>Each character is one line</code>
                    </td>
                </tr>      
                <tr valign="top">
                    <th>Posted date format </th>
                    <td>
                        <select>
                            <option value="F j, Y">August 25, 2021</option>
                            <option value="Y-m-d">2021-08-25</option>
                            <option value="m/d/Y">08/25/2021</option>
                            <option value="d/m/Y">25/08/2021</option>
                        </select>
                        <mark>Incomplete</mark>
                    </td>
                </tr> 
                <?php if(class_exists( 'WooCommerce' ) ){ ?>
                <tr valign="top">                   
                    <th>
                        <h3>Product</h3>
                    </th>
                    <td>
                        
                    </td>
                </tr>

                <tr valign="top">                   
                    <th>
                        Gallery
                    </th>
                    <td>
                        <p>
                            <label>
                                <input type="checkbox" name="adminz_import[adminz_import_product_include_image_content_to_gallery]" <?php echo $this->get_option_value('adminz_import_product_include_image_content_to_gallery') == 'on' ? 'checked' : ''; ?> />
                                <code>Include entry content images to gallery</code>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input type="checkbox" name="adminz_import[adminz_import_product_include_image_variations_to_gallery]" <?php echo $this->get_option_value('adminz_import_product_include_image_variations_to_gallery') == 'on' ? 'checked' : ''; ?> />
                                <code>Include variations images to gallery</code>
                            </label>
                        </p>
                    </td>
                </tr>                
                <tr valign="top">                   
                    <th>
                        Price
                    </th>
                    <td>
                        <p>
                            <input type="number" name="adminz_import[adminz_import_content_product_decimal_seprator]" placeholder='2' value="<?php echo esc_attr($this->get_option_value('adminz_import_content_product_decimal_seprator')); ?>" />
                            <code>Product price remove decimal separator from <b>END</b></code>
                        </p>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php submit_button(); ?>
        </form>     
        <?php
    }
    function register_option_setting() {
        register_setting($this->options_group, 'adminz_import');
    }
}

