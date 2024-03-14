<?php
namespace Shop_Ready\extension\blocks;

Class Reusable_Template{

    public function register(){
     
        // Add Column
        add_filter( 'manage_elementor_library_posts_columns', [ $this , 'set_custom_edit_wp_block_columns' ] ); 
        add_action( 'manage_elementor_library_posts_custom_column' , [$this , 'custom_wp_block_column' ], 10, 2 );  
        add_shortcode( 'shop-ready-reusable-template', [ $this ,'reusable_block_shortcode'] );
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
 
        $content = get_post_field( 'post_content', $block_id );
        return apply_filters( 'shop_ready_reusable_template_content', $content );
    }

    public function set_custom_edit_wp_block_columns($columns){

        if( !current_user_can( 'edit_users' ) ){
            return;
        }

        unset( $columns['date'] );

        $columns[ 'shop-ready-tpl' ] = esc_html__( 'ShortCode', 'shopready-elementor-addon' );
        $columns[ 'date' ]             = esc_html__( 'Date', 'shopready-elementor-addon' );
    
        return $columns;
    }

    public function custom_wp_block_column($column, $post_id){
        
        switch ( $column ) {
            case 'shop-ready-tpl' :
                echo sprintf("[shop-ready-reusable-template id='%s']", esc_html($post_id));
                break;
        }
    }

}