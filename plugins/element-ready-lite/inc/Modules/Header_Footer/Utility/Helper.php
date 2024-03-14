<?php 

/*
* get default header footer id
* @return bool
*/
function element_ready_get_default_template_id( $type= 'header' ){
    return false;
}

/*
* get default header footer id
* @return bool
*/
if( !function_exists( 'element_ready_header_footer_templates') ){
   
    function element_ready_header_footer_templates( $type= 'header' ){

        $list = [];
        $args = array(
            'post_type'           => 'element-ready-hf-tpl',
            'orderby'             => 'id',
            'order'               => 'DESC',
            'posts_per_page'      => -1,
            'ignore_sticky_posts' => 1,
            'meta_query'          => array(
                array(
                    'key'     => 'element_ready_template_type',
                    'compare' => 'LIKE',
                    'value'   => sanitize_text_field($type),
                ),
               
            ),
        );
     
        $data = get_posts($args);

        $list['--'] = esc_html__( 'None', 'element-ready-lite' );
        
        foreach($data as $item){
           $list[ $item->ID ] = esc_html( $item->post_title );
        }
        
        return $list;
     }

}


