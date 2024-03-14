<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;

class ADMINZ_Helper_ACF{
	function __construct() {
		if(!function_exists('get_field')){
			return;
		}
	}
	static function get_field_label($acfkey){	
		if(!function_exists('get_field')){
			return $acfkey;
		}	
	    // get first post id by post_type
	    $query = [
	        'post_type'=>'product',
	        'posts_per_page'=> 1,
	        'meta_query' => array(
	          'relation' => 'AND',
	           array(
	             'key' => $acfkey,
	             'value' => "",
	             'compare' => '!=',
	           )
	        ),
	    ];
	    $posts = get_posts($query);
	    if(!empty($posts)){
	        $id = $posts[0]->ID;
	        $field = get_field_object($acfkey,$id);
	        if(isset($field['label'])){
	            return $field['label'];
	        }
	    }
	    return;
	}
}