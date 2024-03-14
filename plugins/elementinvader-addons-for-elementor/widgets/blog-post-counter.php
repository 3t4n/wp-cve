<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliBlog_Post_Counter extends Elementinvader_Base {

    // Default widget settings
    public $defaults = array();
    public $view_folder = 'blog_post_counter';
    public $items_num = 0;

    public function __construct($data = array(), $args = null) {
        wp_enqueue_style('eli-main', plugins_url('/assets/css/main.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-blog-post-counter';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Blog post Counter', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-counter';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {
      
        /* TAB_STYLE */

		$this->start_controls_section(
			'config',
			[
				'label' => __( 'Query', 'elementinvader-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'prefix_counter',
			[
				'label'         => __('Text Prefix', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
			]
		);

		$this->add_control(
			'suffix_counter',
			[
				'label'         => __('Text After Counter', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
			]
		);

        $this->add_control(
			'is_mobile_view_enable',
			[
				'label' => __( 'Horizontal mobile view', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'True', 'elementinvader-addons-for-elementor' ),
				'label_off' => __( 'False', 'elementinvader-addons-for-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'config_limit',
			[
				'label' => __( 'Limit Results', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 50,
				'step' => 1,
				'default' => 6,
			]
		);

        $this->add_control(
			'results_on',
			[
                'label'         => __('Show results based on', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
                'options'       => [
					'post_type'  => __('Post Type', 'elementinvader-addons-for-elementor'),
					'custom_id'    => __('ID', 'elementinvader-addons-for-elementor'),
					'on_title' => __('Titles', 'elementinvader-addons-for-elementor'),
					'on_query' => __('Query', 'elementinvader-addons-for-elementor'),
				],
				'default'       => 'post_type',
			]
		);

		$this->add_control(
			'config_limit_post_type',
			[
				'label'         => __('Post Type', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::SELECT,
				'options'       => $this->ma_el_get_post_types(),
				'default'       => 'post',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'post_type',
                        ]
                    ],
                ],
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label'         => __('Based on title search posts', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXT,
				'default'       => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'on_title',
                        ]
                    ],
                ],
			]
		);

		$this->add_control(
			'custom_query',
			[
				'label'         => __('Based on query', 'elementinvader-addons-for-elementor'),
				'type'          => Controls_Manager::TEXTAREA,
				'default'       => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'results_on',
                            'operator' => '==',
                            'value' => 'on_query',
                        ]
                    ],
                ],
			]
		);

        if(true){
            $repeater = new Repeater();
            $repeater->start_controls_tabs( 'custom_posts' );
            $repeater->add_control(
                'post_id',
                [
                    'label' => esc_html__('Post ID', 'wpdirectorykit'),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            $repeater->end_controls_tabs();
                            
            $this->add_control(
                'custom_posts_id',
                [
                    'type' => Controls_Manager::REPEATER,
                    'label' => __('Define custom posts id', 'elementinvader-addons-for-elementor'),
                    'fields' => $repeater->get_controls(),
                    'default' => [
                    ],
                    'title_field' => '{{{ post_id }}}',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'results_on',
                                'operator' => '==',
                                'value' => 'custom_id',
                            ]
                        ],
                    ],
                ]
            );
        }
      
        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__( 'Manager Posts <a href="%1$s" target="_blank"> open </a>', 'elementinvader-addons-for-elementor' ), admin_url('edit.php')),
                'content_classes' => 'eli_elementor_hint',
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();

        /* TAB_STYLE */

        $items = [
            [
                'key'=>'counter_el',
                'label'=> esc_html__('Counter', 'wpdirectorykit'),
                'selector_hide'=>'',
                'selector'=>'{{WRAPPER}} .eli_blog_post_counter',
                'selector_hover'=>'',
                'selector_focus'=>'',
                'options'=>'full',
            ]
        ];

        foreach ($items as $item) {
            $this->start_controls_section(
                $item['key'].'_section',
                [
                    'label' => $item['label'],
                    'tab' => 'tab_layout'
                ]
            );

            if(!empty($item['selector_hide'])) {
                $this->add_responsive_control(
                    $item['key'].'_hide',
                    [
                        'label' => esc_html__( 'Hide Element', 'wdk-svg-map' ),
                        'type' => Controls_Manager::SWITCHER,
                        'none' => esc_html__( 'Hide', 'wdk-svg-map' ),
                        'block' => esc_html__( 'Show', 'wdk-svg-map' ),
                        'return_value' =>  'none',
                        'default' => ($item['key'] == 'field_button_reset' ) ? 'none':'',
                        'selectors' => [
                            $item['selector_hide'] => 'display: {{VALUE}};',
                        ],
                    ]
                );
            }

            $selectors = array();

            if(!empty($item['selector']))
                $selectors['normal'] = $item['selector'];

            if(!empty($item['selector_hover']))
                $selectors['hover'] = $item['selector_hover'];

            if(!empty($item['selector_focus']))
                $selectors['focus'] = $item['selector_hover'];
                
            $this->generate_renders_tabs($selectors, $item['key'].'_dynamic', $item['options']);

            $this->end_controls_section();
            /* END special for some elements */
        }

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();

        $id_int = substr($this->get_id_int(), 0, 3);
        $settings = $this->get_settings();

        $args = array();

        global $paged;
        $allposts = array( 
            'post_type'           =>  'post',
            'post_type'      =>  $settings['config_limit_post_type'],
            'posts_per_page'      =>  $settings['config_limit'],
            'post_status'		  => 'publish',	
            'ignore_sticky_posts' => true,
            'paged'			      => $paged
        );

        if(!empty($settings['results_on'])) {
            switch ($settings['results_on']) {
                case 'post_type':
                    $allposts = array( 
                        'post_type'           =>  'post',
                        'post_type'      =>  $settings['config_limit_post_type'],
                        'posts_per_page'      =>  $settings['config_limit'],
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                        'paged'			      => $paged
                    );
                    break;
                case 'custom_id':
                    $post__in = array();
                    foreach ($settings['custom_posts_id'] as $key => $value) {
                        if(!empty($value['post_id']))
                            $post__in[] = intval($value['post_id']);
                    }

                    $allposts = array( 
                        'post__in'  => $post__in,
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                    );
                    break;
                case 'on_title':
                    $allposts = array( 
                        'post_type'           =>  'post',
                        's' => $settings['custom_title'],
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                        'paged'			      => $paged
                    );
                    break;
                case 'on_query':
                    $allposts = $settings['custom_query'];
                    break;
            }


        }

        if(is_string($allposts)){
            $allposts .= '&paged='.$paged;
            $allposts .= '&posts_per_page='.$settings['config_limit'];
            if(isset($_GET['s']))
                $allposts .= '&s='.sanitize_text_field($_GET['s']);
            if(isset($_GET['search']))
                $allposts .= '&s='.sanitize_text_field($_GET['search']);

        }elseif((is_array($allposts))) {
            if(isset($_GET['s'])) {
                $allposts ['s'] = sanitize_text_field($_GET['s']);
            }
            if(isset($_GET['search'])) {
                $allposts ['s'] = sanitize_text_field($_GET['search']);
            }
        }

        if(isset($_GET['cat'])) {
            if(is_string($allposts)){
                $allposts .= '&category_name='.sanitize_text_field($_GET['cat']);
            }elseif((is_array($allposts))) {
                $allposts['category_name'] = sanitize_text_field($_GET['cat']);
            }
        }

        if(isset($_GET['tag'])) {
            if(is_string($allposts)){
                $allposts .= '&tag='.sanitize_text_field($_GET['tag']);
            }elseif((is_array($allposts))) {

                $allposts['tag'] = sanitize_text_field($_GET['tag']);
            }
        }

        
        $wp_query = new \WP_Query($allposts); 

        $count = 0;
        if($wp_query){
            $count = $wp_query->found_posts;
        }

        $object = ['count'=>$count, 'settings'=>$settings,'id_int'=>$id_int];
                
        $object['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $object['is_edit_mode'] = true;
      
        echo $this->view('widget_layout', $object); 
    }

	public static function ma_el_get_post_types()
	{
		$post_type_args = array(
			'public'            => true,
			'show_in_nav_menus' => true
		);

		$post_types = get_post_types($post_type_args, 'objects');
		$post_lists = array();
		foreach ($post_types as $post_type) {
			$post_lists[$post_type->name] = $post_type->labels->singular_name;
		}
		return $post_lists;
	}
}
