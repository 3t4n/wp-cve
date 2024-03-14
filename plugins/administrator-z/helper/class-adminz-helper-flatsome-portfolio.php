<?php 
namespace Adminz\Helper;
use Adminz\Helper\ADMINZ_Helper_Taxonomy_Thumbnail;
use Adminz\Helper\ADMINZ_Helper_Language;
use Adminz\Admin\Adminz;
use Adminz\Admin\ADMINZ_Flatsome;

class ADMINZ_Helper_Flatsome_Portfolio{
    static $customname = "Portfolio";
    static $customtag = "featured_tag";
    static $customcat = "featured_category";
    static $taxname = "";
    static $post_type = 'featured_item';
    static $metakey_thumbnail = '';
    static $metakey_portfolio_tax = 'portfolio_tax';

    function __construct() {
        self::$metakey_thumbnail = ADMINZ_Helper_Taxonomy_Thumbnail::$metakey;

        $name_opt = ADMINZ_Helper_Language::get_pll_string('adminz_flatsome[adminz_flatsome_portfolio_name]');
        if($name_opt){
            self::$customname = $name_opt;
            add_filter( self::$post_type.'posttype_args', [$this,'change_featured'],10 ,1);
        }               

        $tag_opt = ADMINZ_Helper_Language::get_pll_string('adminz_flatsome[adminz_flatsome_portfolio_tag]');
        if($tag_opt){
            self::$customtag = $tag_opt;
            add_filter( self::$post_type.'posttype_tag_args', [$this,'change_featured_tag'],10 ,1);
        }       

        $cat_opt = ADMINZ_Helper_Language::get_pll_string('adminz_flatsome[adminz_flatsome_portfolio_category]');
        if($cat_opt){
            self::$customcat = $cat_opt;
            add_filter( self::$post_type.'posttype_category_args', [$this,'change_featured_category'],10 ,1);
        }

        if(class_exists( 'WooCommerce' )){

            // sync product   
            $taxname = ADMINZ_Helper_Language::get_pll_string('adminz_flatsome[adminz_flatsome_portfolio_product_tax]');
            //$taxname = (string) ADMINZ_Helper_Language::get_pll_string('adminz_flatsome[adminz_flatsome_portfolio_product_tax]');

            if(is_string($taxname) and $taxname){
                self::$taxname = $taxname;
                add_action( 'save_post_'.self::$post_type, [$this,'update_term_by_featured_item'], 10, 1 );
                add_action( 'trashed_post', [$this,'delete_term_by_featured_item'], 10, 1 );

                add_action( 'edited_'.self::$taxname, [$this, 'update_featured_item_by_term'],10,2);
                add_action( 'pre_delete_term', [$this, 'delete_featured_item_by_term'],10,2);
            } 
            
            $portfolio_tax = isset(ADMINZ_Flatsome::$options['adminz_flatsome_portfolio_product_tax'])? ADMINZ_Flatsome::$options['adminz_flatsome_portfolio_product_tax'] : "";
            
            if($portfolio_tax){
                add_action($portfolio_tax.'_edit_form_fields',[$this,'add_tax_viewer']); 
            }
            // add products after portfolio content 
            if(
                isset(ADMINZ_Flatsome::$options['adminz_add_products_after_portfolio']) 
                and ADMINZ_Flatsome::$options['adminz_add_products_after_portfolio'] == "on"){                        
                add_filter('the_content', function($content){
                    global $post;
                    if($post->post_type !== "featured_item"){
                        return $content;
                       }
                    ob_start();
                    if(isset(ADMINZ_Flatsome::$options['adminz_add_products_after_portfolio_title'])){
                        $title = ADMINZ_Flatsome::$options['adminz_add_products_after_portfolio_title'];
                        echo do_shortcode('[adminz_flatsome_portfolio_product_list title="'.$title.'"]');
                    }                    
                    $add = ob_get_clean();
                    return $content.$add;
                });    
            }  
        }

    }
    function change_featured($args){
        $labels = array(
            'name'               => self::$customname,
            'singular_name'      => self::$customname,
            'add_new'            => __( 'Add New', 'flatsome-admin' ),
            'add_new_item'       => __( 'Add New', 'flatsome-admin' ),
            'edit_item'          => 'Edit '.self::$customname,
            'new_item'           => 'Add new '. self::$customname,
            'view_item'          => 'View '. self::$customname,
            'search_items'       => 'Search '. self::$customname,
            'not_found'          => __( 'No items found', 'flatsome-admin' ),
            'not_found_in_trash' => __( 'No items found in trash', 'flatsome-admin' ),
        );
        $args['labels'] = $labels;
        $rewrite = array(
            'slug' => sanitize_title(self::$customname)
        );
        $args['rewrite'] = $rewrite;
        return $args;
    }
    function change_featured_tag($args){
        $labels = array(
            'name'                       => self::$customtag,
            'singular_name'              => self::$customtag,
            'menu_name'                  => self::$customtag,
            'edit_item'                  => __( 'Edit Tag', 'flatsome-admin' ),
            'update_item'                => __( 'Update Tag', 'flatsome-admin' ),
            'add_new_item'               => __( 'Add New Tag', 'flatsome-admin' ),
            'new_item_name'              => __( 'New Tag Name', 'flatsome-admin' ),
            'parent_item'                => __( 'Parent Tag', 'flatsome-admin' ),
            'parent_item_colon'          => __( 'Parent Tag:', 'flatsome-admin' ),
            'all_items'                  => __( 'All Tags', 'flatsome-admin' ),
            'search_items'               => __( 'Search Tags', 'flatsome-admin' ),
            'popular_items'              => __( 'Popular Tags', 'flatsome-admin' ),
            'separate_items_with_commas' => __( 'Separate tags with commas', 'flatsome-admin' ),
            'add_or_remove_items'        => __( 'Add or remove tags', 'flatsome-admin' ),
            'choose_from_most_used'      => __( 'Choose from the most used tags', 'flatsome-admin' ),
            'not_found'                  => __( 'No tags found.', 'flatsome-admin' ),
        );
        $args['labels'] = $labels;
        $rewrite = array(
            'slug' => sanitize_title(self::$customtag)
        );
        $args['rewrite'] = $rewrite;
        return $args;
    }
    function change_featured_category($args){
        $labels = array(
            'name'                       => self::$customcat,
            'singular_name'              => self::$customcat,
            'menu_name'                  => self::$customcat,
            'edit_item'                  => __( 'Edit Category', 'flatsome-admin' ),
            'update_item'                => __( 'Update Category', 'flatsome-admin' ),
            'add_new_item'               => __( 'Add New Category', 'flatsome-admin' ),
            'new_item_name'              => __( 'New Category Name', 'flatsome-admin' ),
            'parent_item'                => __( 'Parent Category', 'flatsome-admin' ),
            'parent_item_colon'          => __( 'Parent Category:', 'flatsome-admin' ),
            'all_items'                  => __( 'All Categories', 'flatsome-admin' ),
            'search_items'               => __( 'Search Categories', 'flatsome-admin' ),
            'popular_items'              => __( 'Popular Categories', 'flatsome-admin' ),
            'separate_items_with_commas' => __( 'Separate categories with commas', 'flatsome-admin' ),
            'add_or_remove_items'        => __( 'Add or remove categories', 'flatsome-admin' ),
            'choose_from_most_used'      => __( 'Choose from the most used categories', 'flatsome-admin' ),
            'not_found'                  => __( 'No categories found.', 'flatsome-admin' ),
        );
        $args['labels'] = $labels;
        $rewrite = array(
            'slug' => sanitize_title(self::$customcat)
        );
        $args['rewrite'] = $rewrite;
        return $args;
    }
    function update_term_by_featured_item($post_id){
        remove_action( 'edited_'.self::$taxname, [$this, 'update_featured_item_by_term'],10,2);
        remove_action( 'pre_delete_term', [$this, 'delete_featured_item_by_term'],10,2);
        if(!empty(get_post_meta( $post_id, 'check_if_run_once' ))) return;
        $post = get_post($post_id);
        // search by old slug
        $term = self::get_terms($post_id);


        if($post->post_status == 'publish'){
            if($term){
                $termid = $term->term_id;
                // update
                $term_return = wp_update_term(
                    $termid,   // the term 
                    self::$taxname, // the taxonomy
                    array(
                        'name' => $post->post_title,
                        'description' => $post->post_excerpt,
                        'slug'        => sanitize_title($post->post_title),
                    )
                );
                // auto renew slug
                remove_action( 'save_post_'.self::$post_type, [$this,'update_term_by_featured_item'], 10, 3 );
                wp_update_post( array(
                    'ID' => $post_id,
                    'post_name' => sanitize_title($post->post_title)
                ));
                add_action( 'save_post_'.self::$post_type, [$this,'update_term_by_featured_item'], 10, 1 );
            }else{
                // create
                $term_return = wp_insert_term(
                    $post->post_title,   // the term 
                    self::$taxname, // the taxonomy
                    array(
                        'description' => $post->post_excerpt,
                        'slug'        => sanitize_title($post->post_name),
                    )
                );
                if(is_wp_error($term_return)){
                    $termid = $term_return->error_data['term_exists'];
                }else{
                    $termid = $term_return['term_taxonomy_id'];
                }
            }
            // update meta keys
            update_term_meta($termid,self::$metakey_thumbnail,get_post_thumbnail_id());
            update_term_meta($termid, self::$metakey_portfolio_tax, get_the_ID());           

        }else{
            if($term){
                // delete
                remove_action( 'pre_delete_term', [$this, 'delete_featured_item_by_term'],10,2);
                wp_delete_term( $termid, self::$taxname );
                add_action( 'pre_delete_term', [$this, 'delete_featured_item_by_term'],10,2);
            }
        }
        
    }
    function delete_term_by_featured_item($post_id){
        if(!empty(get_post_meta( $post_id, 'check_if_run_once' ))) return;

        // search by old slug
        $post = get_post($postid);
        if($post->post_type !== self::$post_type) return;

        $term = self::get_terms($post_id);
        $term_id = $termid = $term->term_id;
        wp_delete_term( $termid, self::$taxname );
    }
    function update_featured_item_by_term($termid, $taxonomy){
        $termobj = get_term($termid);

        // update term slug
        remove_action( 'edited_'.self::$taxname, [$this, 'update_featured_item_by_term'],10,2);
        wp_update_term(
            $termobj->term_id,
            self::$taxname,
            array(
                'slug' => sanitize_title($termobj->name),                
            )
        );
        add_action( 'edited_'.self::$taxname, [$this, 'update_featured_item_by_term'],10,2);

        // update featured: name, thumbnail
        $featured_id = get_term_meta($termobj->term_id,self::$metakey_portfolio_tax,true);
        remove_action( 'save_post_'.self::$post_type, [$this,'update_term_by_featured_item'], 10, 1 );
        wp_update_post(
            [
                'ID'=> $featured_id,
                'post_title'=> $termobj->name,
                'post_name'=> sanitize_title($termobj->name),
                'post_excerpt'=> $termobj->description
            ]
        );
        $term_thumbnail_id = get_term_meta ($termobj->term_id, self::$metakey_thumbnail,true);
        set_post_thumbnail($featured_id,$term_thumbnail_id);
        add_action( 'save_post_'.self::$post_type, [$this,'update_term_by_featured_item'], 10, 1 );
    }   
    function delete_featured_item_by_term( $termid, $taxonomy){
        if($taxonomy !== self::$taxname) return ;

        $featured_id =  get_term_meta($termid,self::$metakey_portfolio_tax,true);
        wp_trash_post($featured_id);
    }
    function get_terms($post_id){
        $args = [
            'taxonomy'=> self::$taxname,
            'hide_empty'=> false, 
            'posts_per_page'=> 1,
            'meta_query'=> [
                [
                    'key'=> self::$metakey_portfolio_tax,
                    'value'=> $post_id,
                    'compare' => '='
                ]
            ]
        ];
        $return = get_terms($args);
        if(isset($return[0])) return $return[0];
        return false;
        
    }
    static function get_meta_key_label($metakey){
        if(!function_exists('get_field_object')) return $metakey;

        $args = array(
            'numberposts' => 1, 
            'post_type'   => self::$post_type,
            'meta_query' => array(
               array(
                 'key' => $metakey,
                 'value' => '',
                 'type' => 'CHAR',                                                    
                 'compare' => '!='
               ),
            ), 

        );

        $example_portfolio = get_posts( $args );        
        if(empty($example_portfolio)) return $metakey;

        $exid = $example_portfolio[0]->ID;
       

        if($exid) {
            $label = get_field_object($metakey,$exid);
            return get_field_object($metakey,$exid)['label'];
        }else{
            return $metakey;
        }
    }
    static function adminz_get_all_meta_key_value($post_type = 'post', $metakey = false, $exclude_empty = false, $exclude_hidden = false){
        global $wpdb;
        $query = "
            SELECT $wpdb->posts.ID, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
            FROM $wpdb->posts 
            LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
            WHERE $wpdb->posts.post_type = '%s'
        ";

        if(!empty($metakey) and is_array($metakey)){
            $query.=" AND (";
            $i = 0;
            foreach ($metakey as $value) {
                if($i == 0){
                    $query.= " $wpdb->postmeta.meta_key = '$value'";
                }else{
                    $query.= " or $wpdb->postmeta.meta_key = '$value'";
                }  
                $i++;
            }
            $query.=" ) ";
        }

        if($exclude_empty) $query .= " AND $wpdb->postmeta.meta_key != ''";
        if($exclude_hidden) $query .= " AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' ";

        $query .= " order by $wpdb->posts.ID";

        $sql = $wpdb->prepare($query, $post_type);
        
        $meta_keys = $wpdb->get_results($sql);

        return $meta_keys;
    }
    static function get_featured_custom_tax($excluded){
        $taxonomies = get_object_taxonomies( self::$post_type, 'objects' );
        $tax_arr = [];
        if(!empty($taxonomies) and is_array($taxonomies)){
            foreach ($taxonomies as $key => $value) {
                if(!in_array($key,$excluded)){
                    $tax_arr[$key] = $value->label;
                }
            }
        }
        return $tax_arr;
    }
    static function change_sub_title($post_id, $before_title_text, $before_title, $get_arr_tax, $get_arr_meta_key){
        if(!empty($before_title) and is_array($before_title)){
            $before_title_text = [];
            $text = "";
            $list_tax = [];
            $list_meta = [];


            foreach ($before_title as $value) {
                if(array_key_exists($value,$get_arr_tax)){
                    $list_tax[] = $value;
                }
                if(array_key_exists($value,$get_arr_meta_key)){
                    $list_meta[] = $value;
                }
                if($value == 'product_count'){
                    $tax_name = get_the_title($post_id);
                    $term = get_term_by('slug',sanitize_title($tax_name), self::$taxname );
                    if($term){
                        $count = $term->count;
                        if($count){
                            $product_count_text = $count. " ".__( 'Products', 'administrator-z' );
                        }                   
                    }                                       
                }
            }
            // for terms
            if(!empty($list_tax) and is_array($list_tax)){
                $temp = [];
                foreach ($list_tax as $key => $value) {
                    $terms = get_the_terms($post_id,$value);
                    if(!empty($terms) and is_array($terms)){
                        foreach ($terms as $key => $term) {
                            $temp[] = $term->name;
                        }
                    }
                }
                
                $temp = implode(', ',$temp);
                if($temp){
                    $before_title_text[]= $temp;
                }
            }
            // for meta keys                
            if(!empty($list_meta) and is_array($list_meta)){
                $temp = [];
                foreach ($list_meta as $value) {
                    $temp[] = get_post_meta($post_id,$value,true);
                }
                $temp = implode(', ',$temp);
                $before_title_text[]= $temp;
            }
            if(isset($product_count_text)){
                $before_title_text[] = $product_count_text;
            }
            $before_title_text = implode('</br>',$before_title_text);
        }
        return $before_title_text;
    }
    function add_tax_viewer($taxonomy){        
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><?php echo self::$customname;?> Connected</th>
            <td>
                <?php 
                    $featured_item_id = get_term_meta($taxonomy->term_id,self::$metakey_portfolio_tax,true); 
                ?>
                <a href="<?php echo get_edit_post_link($featured_item_id); ?>">
                    <?php echo get_the_title($featured_item_id); ?>                                  
                </a>
            </td>
        </tr> 
        <?php
    }
    static function get_list_meta_key_builder($post_type){
        $sqlresult = self::adminz_get_all_meta_key_value($post_type);
        $list_metakey = [];
        $list_all = [];
        if(!empty($sqlresult) and is_array($sqlresult)){
            foreach ($sqlresult as $result) {
                if(!in_array($result->meta_key,$list_metakey)){
                    $list_metakey[$result->meta_key] = $result->meta_key;
                    $list_all[$result->meta_key] = [];
                }
            }
            foreach ($sqlresult as $result) {
                if(!in_array($result->meta_value,$list_all[$result->meta_key]) and $result->meta_value){
                    $list_all[$result->meta_key][$result->meta_value] = $result->meta_value;
                }
            }        
        }
        return [
            'list_metakey'=> $list_metakey,
            'list_all'=> $list_all
        ];
    }
}