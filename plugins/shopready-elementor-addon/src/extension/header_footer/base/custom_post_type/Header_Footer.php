<?php

namespace Shop_Ready\extension\header_footer\base\custom_post_type;
use Shop_Ready\base\Custom_Post as shop_ready_cpt;

/** 
* plublic_query true for 
* elementor support 
* @since 1.0
*/
class Header_Footer extends shop_ready_cpt
{
  
    public $name         = 'Header Footers';
    public $menu         = 'Header Footer';
    public $posts        = array();
   
    public $public_quary = true;
    public $slug         = 'header-footer';
    public $search       = true;

	public function register() {
  
        $this->posts      = array();
        add_action( 'init' , array( $this, 'create_post_type' ),200 );
        add_action( 'admin_menu' , [ $this,'add_cpt_page'] , 10 );
        add_filter( 'save_post_woo-ready-hf-tpl' , array( $this, 'update_template' ), 10,3 );
    }

    public function update_template( $post_id,$post ,$update ){
      
        if( $update ):

            if(isset($_POST['page_template'])):

                $template = sanitize_text_field($_POST['page_template']);

                if(get_post_type($post_id) =='woo-ready-hf-tpl'):
                    update_post_meta( $post_id, '_wp_page_template', $template );
                endif;
                
            endif;

        else:

            update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );

        endif;  
      
    }

    public function add_cpt_page(){

        add_submenu_page( 'shop-ready-elements-dashboard', esc_html__( 'Header Footer', 'shopready-elementor-addon' ), esc_html__( 'Header Footer', 'shopready-elementor-addon' ),
        'manage_options', 'edit.php?post_type=woo-ready-hf-tpl');
       
    }
   
    public function create_post_type(){
  
       $args = array(
		'public' => true,
		'label'  => __( 'Header Footer', 'textdomain' ),
        'supports'            => array( 'title'),
        'menu_icon'=> 'dashicons-text-page',
        'show_in_menu'        => false,
        'hierarchical'        => false,
        'publicly_queryable'  => $this->public_quary,
        'exclude_from_search' => $this->search,
        'has_archive'         => false,  // Set to false hides Archive Pages
	   );
	   register_post_type( 'woo-ready-hf-tpl', $args );
       flush_rewrite_rules( true );
 
       $this->add_elementor_editor_support();
    }
    /* keep public_query true
    * @return void
    */ 
    public function add_elementor_editor_support() {
	 	add_post_type_support( 'woo-ready-hf-tpl', 'elementor' );
        $cpt_support = get_option( 'elementor_cpt_support',[] );
	    
        if( is_array($cpt_support) && ! in_array( 'woo-ready-hf-tpl', $cpt_support ) ) {
            $cpt_support[] = 'woo-ready-hf-tpl'; 
            update_option( 'elementor_cpt_support', $cpt_support ); 
        }else{
            $cpt_support[] = 'woo-ready-hf-tpl'; 
        }
	
    }

}