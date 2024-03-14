<?php

namespace WPVR\Builder\DIVI\Modules;

use ET_Builder_Module;

class WPVR_Tour extends ET_Builder_Module {

    public $slug       = 'wpvr_divi';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => '',
        'author'     => '',
        'author_uri' => '',
    );
    /**
     * Module properties initialization
     */
    public function init() {
        $this->name = esc_html__( 'WPVR', 'wpvr' );
        $this->icon_path        =  plugin_dir_path( __FILE__ ) . 'VR.svg';

        $this->settings_modal_toggles  = array(
            'general'  => array(
                'toggles' => array(
                    'main_content'     	=> __( 'WPVR', 'wpvr' ),
                ),
            ),
        );
        $this->main_css_element = '%%order_class%%';

    }

    function get_advanced_fields_config() {

        $advanced_fields = array();


        return $advanced_fields;
    }
    /**
     * Module's specific fields
     *
     *
     * The following modules are automatically added regardless being defined or not:
     *   Tabs     | Toggles          | Fields
     *   --------- ------------------ -------------
     *   Content  | Admin Label      | Admin Label
     *   Advanced | CSS ID & Classes | CSS ID
     *   Advanced | CSS ID & Classes | CSS Class
     *   Advanced | Custom CSS       | Before
     *   Advanced | Custom CSS       | Main Element
     *   Advanced | Custom CSS       | After
     *   Advanced | Visibility       | Disable On
     * @return array
     */

    /**
     * Get all active tour
     * @return array
     */
    public function get_all_tour()
    {
        $the_posts = get_posts(
            array(
            'post_type' => 'wpvr_item',
            'posts_per_page'    => -1,
            'orderby'        => 'DESC',

            )
        );

        $wpvr_post = array(
            '0' => 'none'
        );

        foreach($the_posts as $post){
            if($post->post_title){
                $wpvr_post[$post->ID] = $post->post_title.' : '.$post->ID;
            }else{
                $wpvr_post[$post->ID] = 'No title'  .' : '.$post->ID;
            }
        }
        return $wpvr_post;
    }

    public function get_fields() {
        return array(
            'vr_id'             => array(
                'label'            => esc_html__( 'ID', 'wpvr' ),
                'description'      => esc_html__( 'WPVR Tour ID', 'wpvr' ),
                'type'             => 'select',
                'options'          => $this->get_all_tour(),
                'priority'         => 80,
                'default'          => '0',
                'default_on_front' => '0',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
            ),
            'vr_width'       => array(
                'label'            => __( 'Width', 'wpvr' ),
                'description'      => __( 'WPVR Width', 'wpvr' ),
                'type'             => 'text',
                'default'          => '600',
                'default_on_front' => '600',
                'toggle_slug'      => 'main_content',
                'show_if'          => array(
                    'vr_fullwidth' => 'no',
                ),
            ),
            'vr_width_unit'             => array(
                'label'            => esc_html__( 'Width Unit', 'wpvr' ),
                'description'      => esc_html__( 'Width Unit', 'wpvr' ),
                'type'             => 'select',
                'options'          => array(
					'px'           => __( 'px' ,'wpvr'),
					'%'            => __( '%' ,'wpvr'),
					'vw'           => __( 'vw','wpvr' ),
				),
                'default'          => 'px',
                'default_on_front' => 'px',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
                'show_if'          => array(
                    'vr_fullwidth' => 'no',
                ),
            ),
            'vr_fullwidth'             => array(
                'label'            => esc_html__( 'Fullwidth', 'wpvr' ),
                'description'      => esc_html__( 'Fullwidth', 'wpvr' ),
                'type'             => 'select',
                'options'          => array(
					'yes'           => __( 'Yes' ,'wpvr'),
					'no'            => __( 'No' ,'wpvr'),
				),
                'default'          => 'no',
                'default_on_front' => 'no',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
            ),
            
            'vr_height'       => array(
                'label'            => __( 'Height', 'wpvr' ),
                'description'      => __( 'WPVR Height', 'wpvr' ),
                'type'             => 'text',
                'default'          => '400',
                'default_on_front' => '400',
                'toggle_slug'      => 'main_content',
            ),
            'vr_height_unit'             => array(
                'label'            => esc_html__( 'Height Unit', 'wpvr' ),
                'description'      => esc_html__( 'Height Unit', 'wpvr' ),
                'type'             => 'select',
                'options'          => array(
					'px'           => __( 'px' ,'wpvr'),
					'vh'           => __( 'vh','wpvr' ),
				),
                'default'          => 'px',
                'default_on_front' => 'px',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
            ),

            'vr_radius'       => array(
                'label'            => __( 'Radius', 'wpvr' ),
                'description'      => __( 'WPVR Radius', 'wpvr' ),
                'type'             => 'text',
                'default'          => '0',
                'default_on_front' => '0',
                'toggle_slug'      => 'main_content',
            ),
            'vr_radius_unit'             => array(
                'label'            => esc_html__( 'Radius Unit', 'wpvr' ),
                'description'      => esc_html__( 'Radius Unit', 'wpvr' ),
                'type'             => 'select',
                'options'          => array(
					'px'           => __( 'px' ,'wpvr'),
				),
                'default'          => 'px',
                'default_on_front' => 'px',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
            ),
            
            'vr_mobile_height'       => array(
                'label'            => __( 'Mobile Height', 'wpvr' ),
                'description'      => __( 'WPVR Mobile Height', 'wpvr' ),
                'type'             => 'text',
                'default'          => '300',
                'default_on_front' => '300',
                'toggle_slug'      => 'main_content',
            ),
            'vr_mobile_height_unit'             => array(
                'label'            => esc_html__( 'Mobile Height Unit', 'wpvr' ),
                'description'      => esc_html__( 'Mobile Height Unit', 'wpvr' ),
                'type'             => 'select',
                'options'          => array(
					'px'           => __( 'px' ,'wpvr'),
				),
                'default'          => 'px',
                'default_on_front' => 'px',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
            ),
        );
    }


    /**
     * Computed checkout form
     * @param $props
     * @return string
     */


    public static  function wpvr_render($props) {
        $id = 0;
        $width = "600px";
        $height = "400px";
        $radius = "0px";
        $id = $props['vr_id'];
        $width = $props['vr_width'].$props['vr_width_unit'];
        $height = $props['vr_height'].$props['vr_height_unit'];
        $radius = $props['vr_radius'].$props['vr_radius_unit'];
        $vr_mobile_height = $props['vr_mobile_height'].$props['vr_mobile_height_unit'];
        if (empty($width)) {
            $width = "600px";
        }
        if($props['vr_fullwidth'] == 'yes'){
            $width = 'fullwidth';
        }
        if (empty($height)) {
            $height = "400px";
        }
        if (empty($radius)) {
            $radius = "0px";
        }
        if (empty($vr_mobile_height)) {
            $vr_mobile_height = "300px";
        }
        if ($id) {
             ob_start();
             echo do_shortcode( '[wpvr id="'.$id.'" width="'.$width.'" height="'.$height.'" radius="'.$radius.'" mobile_height="'.$vr_mobile_height.'"]' );

            return ob_get_clean();
        }

    }

    /**
     * Render Optin form
     * @param array $attrs
     * @param null $content
     * @param string $render_slug
     * @return bool|string|null
     */

    public function render( $attrs, $content = null, $render_slug )
    {
        $output = self::wpvr_render($this->props);
        return $output;
    }
}

new WPVR_Tour();
