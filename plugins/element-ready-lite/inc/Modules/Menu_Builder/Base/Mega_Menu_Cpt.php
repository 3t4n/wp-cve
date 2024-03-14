<?php

namespace Element_Ready\Modules\Menu_Builder\Base;
use Element_Ready\Api\Callbacks\Custom_Post;

class Mega_Menu_Cpt extends Custom_Post
{

    public $name         = 'MegaMenu';
    public $menu         = 'Element Ready Mega Menu';
    public $textdomain   = '';
    public $posts        = array();
    public $public_quary = true;
    public $slug         = 'er-mg-menu';
    public $search       = false;

	public function register() {
      
        $this->posts      = array();
        add_action( 'init', array( $this, 'create_post_type' ) );
        add_filter('save_post_er-mg-menu', array( $this, 'update_template' ), 10,3 );
      
    }
    
    public function _post_update_template(){
      
        if(isset($_POST['post'])):

            $post = sanitize_text_field($_POST['post']); 
            if( get_post_type($post) =='er-mg-menu' ):
                update_post_meta( $post , '_wp_page_template', 'elementor_canvas' );
            endif;
            
        endif;
  
    }

    public function update_template( $post_id,$post ,$update ){
      
        if($update):
            if(isset($_POST['page_template'])):
                $template = sanitize_text_field($_POST['page_template']);
                if(get_post_type($post_id) =='er-mg-menu'):
                    update_post_meta( $post_id, '_wp_page_template', $template );
                endif;
            endif;
        else:
          update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
        endif;  
    }
    
    public function create_post_type(){

        if( !current_user_can('manage_options') ){
          return;
        }
     
        $this->init( 'er-mg-menu', $this->name, $this->menu, array( 'menu_icon' => 'dashicons-media-interactive',
            'supports'            => array( 'title'),
            'rewrite'             => array( 'slug' => $this->slug ),
            'exclude_from_search' => $this->search,
            'has_archive'         => false,                            // Set to false hides Archive Pages
            'publicly_queryable'  => $this->public_quary,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_ui'             => false,
            'hierarchical'        => false,
        ) 

       );

       $this->register_custom_post();
       $this->enable_nav_type();
    }

    function enable_nav_type(){
        add_post_type_support( 'er-mg-menu', 'elementor' );
    }
}

