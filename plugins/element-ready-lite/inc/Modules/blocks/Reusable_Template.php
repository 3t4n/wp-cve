<?php

namespace Element_Ready\Modules\blocks;

Class Reusable_Template{

    public function register(){
     
        // Add Column
        add_filter( 'manage_elementor_library_posts_columns', [ $this , 'set_custom_edit_wp_block_columns' ] ); 
        add_action( 'manage_elementor_library_posts_custom_column' , [$this , 'custom_wp_block_column' ], 10, 2 );  
        add_shortcode( 'element-ready-reusable-template', [ $this ,'reusable_block_shortcode'] );
    }

    public function reusable_block_shortcode($atts){

        extract( shortcode_atts( array(
            'id' => '',
        ), $atts ) );

        if(!is_numeric($id)){
            return;
        }

        $content = $this->get_reusable_block( $id );
        return $content;

    }

    public function get_reusable_block( $block_id = '' ){
 
        $content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($block_id,true);
        return apply_filters( 'element_ready_reusable_template_content', $content );
    }

    public function set_custom_edit_wp_block_columns($columns){

        if( !current_user_can( 'edit_users' ) ){
            return;
        }

        unset( $columns['date'] );
        $columns[ 'element-ready-tpl' ] = esc_html__( 'ShortCode', 'element-ready-lite' );
        $columns[ 'date' ] = esc_html__( 'Date', 'element-ready-lite' );
        return $columns;
    }

    public function custom_wp_block_column($column, $post_id){
        
        switch ( $column ) {
            case 'element-ready-tpl' :
                echo wp_kses_post( sprintf("[element-ready-reusable-template id='%s']", $post_id) );
                break;
        }
    }

}
