<?php

namespace Element_Ready\Modules\Header_Footer\Settings;

class Page {

    public function register() {

	    if ( !file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
         return;
        }

        add_action( 'admin_enqueue_scripts', [$this,'add_admin_scripts'] );
        add_action( 'admin_menu', [$this,'add_cpt_page'] );
        add_action( 'admin_post_element_ready_hf_options', [$this,'components_options']); 
   
    }

    public function add_admin_scripts($handle){
     
        if($handle == 'elementsready_page_element-ready-header-footer-template' && element_ready_get_modules_option('header_footer_builder')){
           wp_enqueue_script( 'jquery-ui-tabs' );
           wp_enqueue_style( 'element-ready-grid', ELEMENT_READY_ROOT_CSS .'grid.css' );
           wp_enqueue_style( 'element-ready-admin', ELEMENT_READY_ROOT_CSS .'admin.css' );
           wp_enqueue_script( 'element-ready-admin', ELEMENT_READY_ROOT_JS .ELEMENT_READY_SCRIPT_VAR.'js' ,array('jquery','jquery-ui-tabs'), ELEMENT_READY_VERSION, true );
        }
      
   }
    public function add_cpt_page(){
       
        add_submenu_page( 'element_ready_elements_dashboard_page', 'Template', 'Header Footer',
        'manage_options', 'edit.php?post_type=element-ready-hf-tpl');
        add_submenu_page( 'element_ready_elements_dashboard_page', 'Template_Settings', 'Template Settings', 'manage_options', 'element-ready-header-footer-template', [$this,'template_settings'] );
    }

    public function template_settings(){
        require_once( __DIR__ .'/..' .'/Templates/settings.php' );
    }

    function components_options(){
       
        // Verify if the nonce is valid
        if ( !isset($_POST['_element_ready_hf_components']) || !wp_verify_nonce($_POST['_element_ready_hf_components'], 'element-ready-hf-components')) {
            wp_redirect( esc_url_raw($_SERVER["HTTP_REFERER"]) );
        }
        
        if( !isset($_POST['element-ready-hf-options']) ){
            wp_redirect( esc_url_raw($_SERVER["HTTP_REFERER"]) ); 
        }
       
        // Save
        $validate_options = map_deep($_POST['element-ready-hf-options'],'sanitize_text_field');
        update_option('element_ready_hf_options',$validate_options);
        
        if ( wp_doing_ajax() )
        {
          wp_die();
        }else{
            wp_redirect( esc_url_raw($_SERVER["HTTP_REFERER"]) );
        }
        
    }

    public function validate_options($options = []){
        
        if(!is_array($options)){
            return $options;
        }

        $return_options = [];
        
        foreach( $options as $key => $value ){
          $return_options[$key] = sanitize_text_field($value); 
        }

        return $return_options;
    }
    public function components(){

        $return_arr = [
          
            'header_template' => [
               
                'lavel' => esc_html__('Header Template','element-ready-lite'),
                'default' => null,
                'type' => 'select',
                'is_pro' => 0,
                'options'=> $this->get_headers()
            ],

            'footer_template' => [
               
                'lavel' => esc_html__('Footer Template','element-ready-lite'),
                'default' => null,
                'type' => 'select',
                'is_pro' => 0,
                'options'=> $this->get_footers()
            ],
         

        ];

        if( class_exists( 'WeDocs' ) ){

            $return_arr['wedocs_header_template'] = [
               
                'lavel' => esc_html__('Wedocs Header Template','element-ready-lite'),
                'default' => null,
                'type' => 'select',
                'is_pro' => 0,
                'options'=> $this->get_headers()
            ]; 

            $return_arr['wedocs_footer_template'] = [
               
                'lavel' => esc_html__('Wedocs Footer Template','element-ready-lite'),
                'default' => null,
                'type' => 'select',
                'is_pro' => 0,
                'options'=> $this->get_footers()
            ];

        }

        $return_arr = apply_filters( 'element_ready_hf_global_option' , $return_arr );
        $return_arr = $this->get_transform_options( $return_arr , 'element_ready_hf_options' );
        
        return $return_arr;
    }

    public function get_transform_options($options = [], $key = false){

        if( !is_array($options) || $key == false ){
            return $options;
        }

        $db_option = get_option( $key );
        $return_options = $options;
       
        foreach ( $options as $key => $value ) {

          if($options[$key]['type'] =='switch'){
            if( isset($db_option[$key]) ){
                $return_options[$key]['default'] = 1; 
              }else{
                $return_options[$key]['default'] = 0;    
              } 
          } 

          if($options[$key]['type'] =='select'){

            if( isset($db_option[$key]) ){
                $return_options[$key]['default'] = $db_option[$key]; 
              }else{
                $return_options[$key]['default'] = '';    
              } 

          }
       
        }
      
        return $return_options; 
    }
    public function get_headers(){
       
        $_header = element_ready_header_footer_templates();
        $_header[ -1 ] = esc_html__('Empty','element-ready-lite');
        return is_array($_header)?$_header:[];
    }

    public function get_footers(){
       
        $_footer = element_ready_header_footer_templates('footer');
        $_footer[ -1 ] = esc_html__('Empty','element-ready-lite');
        return is_array( $_footer ) ? $_footer : [];
    }

}