<?php
/*
 * Plugin Name:   Product Feed for Pinterest Product Catalogs
 * Plugin URI:    https://wordpress.org/plugins/wc-product-feed-for-pinterest
 * Description:   Product RSS Feed for 'Pinterest Product Catalogs'. Automatically pin the products on your website by posting information such as image, price, stock status, product description in your pinterest account.
 * Version:       1.0.4
 * Author:        Mehmet Cenk Yenikoylu
 * Author URI:    https://github.com/mcyenikoylu
 * License:       GPLv2 or later
 * License URI:   http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( is_admin() ){
    require_once dirname( __FILE__ ) . '/index.php';

    function pinterest_product_catalogs_plugin_action_links( $links, $file ) {
        if ( $file == plugin_basename( dirname(__FILE__).'/pinterest-product-catalogs.php' ) ) {
            $links[] = '<a href="' . admin_url( 'admin.php?page=pinterest-product-catalogs-admin-options' ) . '">'.__( 'Settings' ).'</a>';
            }
            return $links;
        }
        add_filter('plugin_action_links', 'pinterest_product_catalogs_plugin_action_links', 10, 2);
}

function call_pinterest_product_catalogs(){    

    $pinterest_product_catalogs_options = get_option('pinterest_product_catalogs_options');

	if(is_array($pinterest_product_catalogs_options)===false){
		$pinterest_product_catalogs_options = pinterest_product_catalogs_set_defults();
	}
	
    $ppcf_show_post_terms = $ppcf_debug = $ppcf_show_all_post_terms = null;
     
    extract($pinterest_product_catalogs_options);
    $options['ppcf_show_content'] = $ppcf_show_content;
    $options['ppcf_show_thumbnail'] = $ppcf_show_thumbnail;
    $options['ppcf_show_post_terms'] = $ppcf_show_post_terms;
    $options['ppcf_allowed_tags'] = $ppcf_allowed_tags;
    $options['ppcf_pubdate_date_format'] = $ppcf_pubdate_date_format;
    
    if( isset($_GET["ppcf_post_type"]) ){
		$ppcf_post_type = sanitize_text_field($_GET["ppcf_post_type"]);	
	}
    if( isset($_GET["ppcf_posts_per_page"]) ){
		$ppcf_posts_per_page = intval($_GET["ppcf_posts_per_page"]);	
	}
    if( isset($_GET["ppcf_post_status"]) ){
		$ppcf_post_status = sanitize_text_field($_GET["ppcf_post_status"]);	
	}

	$args = array(
		'post_type' => $ppcf_post_type,
		'showposts' => $ppcf_posts_per_page, 
		'post_status'=>$ppcf_post_status,
		'ignore_sticky_posts' => true,
	);
	
    $namespaces = array(
        "content" => "http://purl.org/rss/1.0/modules/content/",
		"wfw" => "http://wellformedweb.org/CommentAPI/",
		"dc" => "http://purl.org/dc/elements/1.1/",
		"atom" => "http://www.w3.org/2005/Atom",
		"sy" => "http://purl.org/rss/1.0/modules/syndication/",
		"slash" => "http://purl.org/rss/1.0/modules/slash/",
        "media" => "http://search.yahoo.com/mrss/",
        "wp" => "http://wordpress.org/export/1.2/",
        "excerpt" => "http://wordpress.org/export/1.2/excerpt/",
        "g" => "http://base.google.com/ns/1.0",
    );
    $options['namespaces'] = $namespaces;
    
    $ppcf_feed_output = null;
    $ppcf_feed_output = ppcf_build_xml_string($args,$options);
    
    if($ppcf_feed_output){
        header('Content-Type: text/xml; charset=utf-8');
        print($ppcf_feed_output); 
    }else{
        header('Content-Type: text/xml; charset=utf-8');
        print('<?xml version="1.0" encoding="UTF-8"?><rss/>'); 
    }
 }

 function ppcf_build_xml_string($args,$options) {
	    
    extract($options);
   
    $the_query = new WP_Query( $args );
    
    $namespaces_str = '';
    foreach($namespaces as $name => $value){
        $namespaces_str .=  'xmlns:'.$name.'="'.$value.'" ';
    }    
    $ppcf_feed_current = '<?xml version="1.0" encoding="UTF-8"?>
    <rss version="2.0" '.$namespaces_str.' >';

    $ppcf_feed_current .= '
        <channel>
        <title>'.get_bloginfo("name").'</title>
        <description>'.get_bloginfo("description").'</description>
        <link>'.get_home_url().'</link>
        <lastBuildDate>'.  mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false) .'</lastBuildDate>';
        $debug_data = array(
            'args' => $args,
            'options' => $options,
        );

        if(isset($ppcf_debug)&& $ppcf_debug=='1') $ppcf_feed_current .=	'<debug>'.json_encode($debug_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE).'</debug>';

        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();		
                $post_id = get_the_ID();
                $the_post = get_post($post_id);
                $excerpt = $the_post->post_excerpt;
                $modified = $the_post->post_modified;
                $created = $the_post->post_date;
                $author_id = $the_post->post_author;
                $menu_order  = $the_post->menu_order;
                $post_parent  = $the_post->post_parent;
                $post_status = $the_post->post_status;
                $author = get_the_author_meta('display_name', $author_id );				
                $categories = get_the_category();
                switch ($ppcf_pubdate_date_format) {
                    case "rfc":
                        $date_format =  'D, d M Y H:i:s O';
                        $pub_date = get_the_date( $date_format, $post_id );
                        break;
                    case "blog_date":
                        $date_format =  get_option( 'date_format' );
                        $pub_date = get_the_date( $date_format, $post_id );
                        break;
                    case "blog_date_time":
                        $date_format =  get_option( 'date_format' ).' '.get_option('time_format');
                        $pub_date = get_the_date( $date_format, $post_id );
                        break;
                    default:
                        $date_format =  'D, d M Y H:i:s O';
                        $pub_date = get_the_date( $date_format, $post_id );
                }
                $collection = null;
                $taxonomies = null;
             
                $taxonomy_objects = null;
                
                $custom_fields = get_post_custom($post_id);
               
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $options['ppcf_thumbnail_size=full'],true);
             
                $thumb_url = $thumb['0'];
               
                    $ppcf_feed_current .='
                    <item>
                   
                            <id><![CDATA['. $post_id .']]></id>
                            <title><![CDATA['. get_the_title($post_id) .']]></title>
                            <description><![CDATA['. $excerpt .']]></description>
                            <link><![CDATA['. get_permalink($post_id) .']]></link>
                            <image_link><![CDATA[ '. esc_url($thumb_url) .']]></image_link>
                            
                            ';

                             foreach ( $custom_fields as $key => $value ) {

                                if ($key=='_stock_status'){
                                    if ($value[0] == 'instock')
                                        $ppcf_feed_current .= "<g:availability><![CDATA[ in stock ]]></g:availability>";//$value[0] = str_replace("instock","in stock",$value[0]);
                                    else if ($value[0] == 'outofstock')
                                        $ppcf_feed_current .= "<g:availability><![CDATA[ out of stock ]]></g:availability>";//$value[0] = str_replace("instock","in stock",$value[0]);
                                    else if ($value[0] == 'preorder')
                                        $ppcf_feed_current .= "<g:availability><![CDATA[ preorder ]]></g:availability>";//$value[0] = str_replace("instock","in stock",$value[0]);
                                }
                            }

                            $ppcf_feed_current .= "<g:condition><![CDATA[ New ]]></g:condition>";

                            $taxonomy_objects = get_object_taxonomies( $the_post );
                               
                            if($taxonomy_objects){
                                if ( ! is_wp_error( $taxonomy_objects ) ) {
                                   
                                    foreach($taxonomy_objects as $taxonomy_object){
                                      
                                        $terms = wp_get_post_terms( $post_id, $taxonomy_object, array("fields" => "all") );
                                        //print_r($terms);    
                                        if(!empty($terms)){
                                           
                                            if ( ! is_wp_error( $terms ) ) {

                                            if($taxonomy_object == 'product_cat'){
                                           
                                            $taxonomies.= "<g:product_type>";
                                            $ix = 1;
                                            foreach($terms as $term){
                                                if($ix == 1){
                                                    for ($i=0; $i <= 0; $i++) { 
                                                        $term_name = $term->name;
                                                        $taxonomies.= "<![CDATA[ ".$term_name." ]]>";
                                                        $ix++;
                                                    }
                                                }
                                            }
                                            $taxonomies.= "</g:product_type>";
                                            $ppcf_feed_current .= $taxonomies ;
                                            }
                                            }
                                        }
                                    }
                                }                    
                            }   

                            $ppcf_price = get_post_meta(get_the_ID(), '_price', true);
                            $ppcf_sale_price = get_post_meta(get_the_ID(), '_sale_price', true);
                            $ppcf_regular_price = get_post_meta(get_the_ID(), '_regular_price', true);
                            
                            if(empty($ppcf_sale_price)){
                                $ppcf_price = get_post_meta(get_the_ID(), '_price', true);
                                $ppcf_feed_current .= "<g:price><![CDATA[".$ppcf_price."]]></g:price>";
                            } else {
                                $ppcf_feed_current .= "<sale_price><![CDATA[".$ppcf_sale_price."]]></sale_price>";
                                $ppcf_feed_current .= "<g:price><![CDATA[".$ppcf_regular_price."]]></g:price>";
                            }
                            
                            
                    $ppcf_feed_current .='		
                    </item>';

            }
        } else {
            // no posts 
            $ppcf_feed_current .= 'no posts';
        }			
      
        wp_reset_postdata();
        
    $ppcf_feed_current .='</channel></rss><!-- end of xml string -->';
    return $ppcf_feed_current;
    
}


 add_filter( 'query_vars', 'pinterest_product_catalogs_query_vars' );
function pinterest_product_catalogs_query_vars( $query_vars ){
    $query_vars[] = 'call_pinterest_product_catalogs';
    return $query_vars;
}

 add_action( 'parse_request', 'pinterest_product_catalogs_parse_request' );
function pinterest_product_catalogs_parse_request( $wp )
{
    if (array_key_exists('call_pinterest_product_catalogs', $wp->query_vars ) ) {
		$call_pinterest_product_catalogs = $wp->query_vars['call_pinterest_product_catalogs'];
		if($call_pinterest_product_catalogs=='1') call_pinterest_product_catalogs();
		die();
    }
}

register_activation_hook(__FILE__, 'pinterest_product_catalogs_activation');
function pinterest_product_catalogs_activation() {
	pinterest_product_catalogs_set_defults();
}

register_deactivation_hook(__FILE__, 'pinterest_product_catalogs_deactivation');
function pinterest_product_catalogs_deactivation() {
	delete_option( 'pinterest_product_catalogs_options' );
}

 function pinterest_product_catalogs_set_defults(){
    $pinterest_product_catalogs_options	= array(
            'ppcf_post_type'=> 'product',
            'ppcf_post_status'=> 'publish',
            'ppcf_posts_per_page'=> 1000,
            'ppcf_show_meta'=> 0,
            'ppcf_show_thumbnail'=> 0,	
            'ppcf_show_content'=> 0,
            'ppcf_allowed_tags' => PINTEREST_PRODUCT_CATALOGS_PLUGIN_ALLOWED_TAGS,
            'ppcf_secret_key'=> '',
            'ppcf_xml_type'=> 0, 
            'ppcf_pubdate_date_format'=> 'rfc',
    );
    update_option('pinterest_product_catalogs_options',$pinterest_product_catalogs_options);
    return $pinterest_product_catalogs_options;
}