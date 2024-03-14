<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;
class ADMINZ_Helper_Flatsome_Blog{
    function __construct() {
        // default template taxonomy
        add_action('pre_get_posts', function($query){
            if(!is_archive()) return;
            if(
                // nếu là shortcode blog_posts của flatsome
                isset($query->query_vars['post_type']) and 
                $query->query_vars['post_type'] == ['post', 'featured_item'] and 
                isset($query->query_vars['orderby']) and
                $query->query_vars['orderby'] == 'post__in'
                ){
                    $query->set('post_type', array_merge([get_post_type()], $query->get('post_type')));
            }            
        });
        
        // blog image size
        add_filter('post_thumbnail_size', function($size){
            if(is_admin() && is_main_query()){
                return $size;
            }
            return 'large';
        },10,1);

    }
}