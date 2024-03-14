<?php

namespace Element_Ready\Modules\Header_Footer\Base\Custom_Post_Type;

use Element_Ready\Api\Callbacks\Custom_Post;

class Header_Footer extends Custom_Post
{

    public $name         = 'Header Footers';
    public $menu         = 'Header Footer';
    public $textdomain   = '';
    public $posts        = array();
    /*
    * plublic_query true for 
    * elementor support 
    */
    public $public_quary = true;
    public $slug         = 'header-footer';
    public $search       = true;

	public function register() {

        $this->textdomain = 'element-ready-lite';
        $this->posts      = array();
       
        add_action( 'init', array( $this, 'create_post_type' ) );
       
        add_filter( 'save_post_element-ready-hf-tpl', array( $this, 'update_template' ), 10,3 );
        add_action( 'save_post_element-ready-hf-tpl',array( $this, 'save' ) );
        add_action( 'save_post_page',array( $this, 'page_save' ) );
        add_action( 'save_post_docs',array( $this, 'page_save' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
   }
    public function add_metabox() {
     
        add_meta_box(
            'element_ready_template_type_settings',
            esc_html__( 'Settings','element-ready-lite' ),
            array( $this, 'render_meta_box_content' ),
            'element-ready-hf-tpl',
            'advanced',
            'default'
        );

        add_meta_box(
            'element_ready_template_type_page_settings',
            esc_html__( 'Settings','element-ready-lite' ),
            array( $this, 'render_page_meta_box_content' ),
            ['page','docs'],
            'advanced',
            'default'
        );
 
    }

    public function render_meta_box_content( $post ) {
 
         wp_nonce_field( 'element_ready_header_footer', 'element_ready_header_footer_nonce' );
         $template_type = get_post_meta( $post->ID, 'element_ready_template_type', true );
 
        ?>
            <label for="element_ready_template_type">
                <?php echo esc_html__( 'Template Type', 'element-ready-lite' ); ?>
            </label>
            <select id="element_ready_template_type" name="element_ready_template_type" >
                <option value=""> <?php echo esc_html__('Select Template Type','element-ready-lite'); ?> </option>
                <option <?php if ( $template_type == 'header' ) echo esc_attr('selected="selected"'); ?> value="header"> <?php echo esc_html__('Header','element-ready-lite'); ?> </option>
                <option <?php if ( $template_type == 'footer' ) echo esc_attr('selected="selected"'); ?>  value="footer"> <?php echo esc_html__( 'Footer', 'element-ready-lite' ) ?> </option>
            </select>
        <?php
    } 
    
    public function render_page_meta_box_content( $post ) {
 
        wp_nonce_field( 'element_ready_header_footer', 'element_ready_header_footer_nonce' );
        $template_header = get_post_meta( $post->ID, 'element_ready_builder_header_layout_style', true );
        $template_footer = get_post_meta( $post->ID, 'element_ready_builder_footer_layout_style', true );
       
        ?>
        <label for="element_ready_builder_header_layout_style">
            <?php echo esc_html__( 'Header Template', 'element-ready-lite' ); ?>
        </label>
        <select id="element_ready_builder_header_layout_style" name="element_ready_builder_header_layout_style" >
            <?php foreach($this->get_headers() as $h_key => $header): ?>
              <option <?php if ( $template_header == $h_key ) echo 'selected="selected"'; ?> value="<?php echo esc_attr( $h_key ); ?>"> <?php echo esc_html($header); ?> </option>
            <?php endforeach; ?>
        </select> 
        <label for="element_ready_builder_footer_layout_style">
            <?php echo esc_html__( 'Footer Template', 'element-ready-lite' ); ?>
        </label>
        <select id="element_ready_builder_footer_layout_style" name="element_ready_builder_footer_layout_style" >
           <?php foreach($this->get_footers() as $_key => $footer): ?>
              <option <?php if ( $template_footer == $_key ) echo 'selected="selected"'; ?> value="<?php echo esc_attr( $_key ); ?>"> <?php echo esc_html($footer); ?> </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function save( $post_id ) {
      
        if ( ! isset( $_POST['element_ready_header_footer_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['element_ready_header_footer_nonce'];
 
        if ( ! wp_verify_nonce( $nonce, 'element_ready_header_footer' ) ) {
            return $post_id;
        }
 
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
  
        $template_type = sanitize_text_field( $_POST['element_ready_template_type'] );
        update_post_meta( $post_id, 'element_ready_template_type', $template_type );
    }

    public function page_save( $post_id ) {
        
        if ( ! isset( $_POST['element_ready_header_footer_nonce'] ) ) {
            return $post_id;
        }
        $nonce = $_POST['element_ready_header_footer_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'element_ready_header_footer' ) ) {
            return $post_id;
        }
 
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        $header_type = sanitize_text_field( $_POST['element_ready_builder_header_layout_style'] );
        $footer_type = sanitize_text_field( $_POST['element_ready_builder_footer_layout_style'] );
        update_post_meta( $post_id, 'element_ready_builder_header_layout_style', $header_type );
        update_post_meta( $post_id, 'element_ready_builder_footer_layout_style', $footer_type );
    }
 
    public function get_headers(){
       
        $_header = element_ready_header_footer_templates();
        return is_array($_header)?$_header:[];
    }

    public function get_footers(){
       
        $_footer = element_ready_header_footer_templates('footer');
        return is_array($_footer)?$_footer:[];
    }

    public function create_post_type(){
      
        $this->init( 'element-ready-hf-tpl', $this->name, $this->menu, array( 'menu_icon' => 'dashicons-text-page',
            'supports'            => array( 'title'),
            'rewrite'             => array( 'slug' => $this->slug ),
            'exclude_from_search' => $this->search,
            'has_archive'         => false,                            // Set to false hides Archive Pages
            'publicly_queryable'  => $this->public_quary,
            'hierarchical'        => false,
            'show_in_menu'=> false
        ) 

       );

       $this->register_custom_post();
       $this->add_elementor_editor_support();
    }
     public function add_elementor_editor_support() {
    	add_post_type_support( 'element-ready-hf-tpl', 'elementor' );
    }

    public function update_template( $post_id,$post ,$update ){
      
        if($update):
            if(isset($_POST['page_template'])):
                $template = sanitize_text_field($_POST['page_template']);
                if(get_post_type($post_id) =='element-ready-hf-tpl'):
                    update_post_meta( $post_id, '_wp_page_template', $template );
                endif;
            endif;
        else:
            update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
        endif;  

    }

    public function get_template_display_option(){

        $post_types = get_post_types();

		$post_types_unset = array(
			'attachment'          => 'attachment',
			'revision'            => 'revision',
			'nav_menu_item'       => 'nav_menu_item',
			'custom_css'          => 'custom_css',
			'customize_changeset' => 'customize_changeset',
			'oembed_cache'        => 'oembed_cache',
			'user_request'        => 'user_request',
			'wp_block'            => 'wp_block',
			'elementor_library'   => 'elementor_library',
			'btf_builder'         => 'btf_builder',
			'elementor-hf'        => 'elementor-hf',
			'elementor_font'      => 'elementor_font',
			'elementor_icons'     => 'elementor_icons',
			'wpforms'             => 'wpforms',
			'wpforms_log'         => 'wpforms_log',
			'acf-field-group'     => 'acf-field-group',
			'acf-field'           => 'acf-field',
			'booked_appointments' => 'booked_appointments',
			'wpcf7_contact_form'  => 'wpcf7_contact_form',
			'scheduled-action'    => 'scheduled-action',
			'shop_order'          => 'shop_order',
			'shop_order_refund'   => 'shop_order_refund',
			'shop_coupon'         => 'shop_coupon',
        );
        
        $diff = array_diff( $post_types, $post_types_unset );
        
		$default = array(
			'all'       => esc_html__( 'All', 'element-ready-lite'),
			'blog'      => esc_html__( 'Blog Page' , 'element-ready-lite'),
			'archive'   => esc_html__( 'Archive Page' , 'element-ready-lite'),
			'post'      => esc_html__( 'Post Page' , 'element-ready-lite'),
			'page'      => esc_html__( 'Page Page' , 'element-ready-lite'),
			'author'    => esc_html__( 'Author Page' , 'element-ready-lite'),
			'tags'      => esc_html__( 'Tags Page' , 'element-ready-lite'),
			'category'  => esc_html__( 'Category Page' , 'element-ready-lite'),
			'search'    => esc_html__( 'Search Page' , 'element-ready-lite'),
			'not_found' => esc_html__( '404 Page' , 'element-ready-lite'),
        );
        
		$options = array_merge( $default, $diff );

        return $options;
    }
   
}