<?php
namespace BMianAddon\Widgets;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class d_player extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'dPlayer';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'dPlayer', 'baddon' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'bl_icon fas fa-compact-disc';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'baddon' ];
	}

    public function get_script_depends() {
        return [ 'dplayermin-js', 'main-js'];
    }
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

  //Vedio player Content Settings

    $this->start_controls_section(
        '_section_video',
        [
            'label' => __( 'Vedio Player Content Settings', 'baddon' ),
             
           
        ]
    );

    $this->add_control(
            'choose_v_source',
            [
                'label'         => __( 'Multiple Quality', 'baddon' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'yes', 'baddon' ),
                'label_off'     => __( 'no', 'baddon' ),
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );

       $this->add_control(
            'srrc_type',
            [
                'label' => esc_html__( 'Source From', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'uploaad',
                'options' => [
                    'uploaad' => esc_html__( 'Media Library', 'baddon' ),
                    'liink' => esc_html__( 'Video File Link', 'baddon' ),
                ],
                'condition' => [
                    'choose_v_source'    =>  '',
                ]
  
            ]
        );
       $this->add_control(
            'videoos_upload',
            [
                'label' => esc_html__( 'Upload Video', 'baddon' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'choose_v_source'    =>  '',
                    'srrc_type'     => 'uploaad',
                ]
            ]
        );
        $this->add_control(
            'videoos_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'condition' => [
                    'choose_v_source'    =>  '',
                    'srrc_type'     => 'liink',
                ]
            ]
        );
    //End single video
    //Start Multiple video
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'src_v_type',
            [
                'label' => esc_html__( 'Video Source', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'upload',
                'options' => [
                    'upload' => esc_html__( 'Upload Video', 'baddon' ),
                    'link' => esc_html__( 'Put Video Link', 'baddon' ),
                ],
            ]
        );
        $repeater->add_control(
            'video_v_upload',
            [
                'label' => esc_html__( 'Upload Video', 'baddon' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'src_v_type'     => 'upload',
                ]
            ]
        );
        $repeater->add_control(
            'video_v_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'condition' => [
                    'src_v_type'     => 'link',
                ]
            ]
        );
        $repeater->add_control(
            'video_d_size',
            [
                'label' => esc_html__( 'Video Size', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Select', 'baddon' ),
                    '240' => esc_html__( '240', 'baddon' ),
                    '360' => esc_html__( '360', 'baddon' ),
                    '480' => esc_html__( '480', 'baddon' ),
                    '576' => esc_html__( '576', 'baddon' ),
                    '720' => esc_html__( '720', 'baddon' ),
                    '1080' => esc_html__( '1080', 'baddon' ),
                    '1440' => esc_html__( '1440', 'baddon' ),
                    '2160' => esc_html__( '2160', 'baddon' ),
                    '2880' => esc_html__( '2880', 'baddon' ),
                    '4320'  => esc_html__( '4320', 'baddon' ),
                ],
            ]
        );
        $this->add_control(
            'video_d_list',
            [
                'label' => esc_html__( 'Video List', 'baddon' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                 'condition' => [
                    'choose_v_source'    =>  'yes',
                ]
           
            ]
        );
      $this->end_controls_section();
      $this->start_controls_section(
        '_sxubc_settings',
        [
            'label' => __( 'dPlayer Subtitle Settings', 'baddon' ),
           
        ]
    );
 
        $this->add_control(
            'dplayer_upload',
            [
                'label' => esc_html__( 'Upload Subtitle', 'baddon' ),
               
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'text/vtt',
            ]
        );

            $this->add_control(
            'sub_d_bg',
            [
                'type' => Controls_Manager::COLOR,
                'label' =>esc_html__('Player Subtitle Color', 'baddon'),
                'default'   =>  '#fff',
            ]
        );
  

    $this->end_controls_section();
        // player settings

     $this->start_controls_section(
                '_section_option',
                [
                    'label' => __( 'Vedio Player All Settings', 'baddon' ),
                     
                   
                ]
         );
       $this->add_control(
            'custom_logo_d',
            [
                'label' => esc_html__( 'Add player Logo', 'baddon' ),
                'type' =>Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'baddon' ),
                'label_off' => esc_html__( 'No', 'baddon' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'd_logo',
            [
                'label' => esc_html__( 'Player Logo For Video', 'baddon' ),
                'type' =>Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_logo_d'    =>  'true',
                ]
            ]
        );

           $this->add_control(
            'custom_banner_d',
            [
                'label' => esc_html__( 'Add Player Banner', 'baddon' ),
                'type' =>Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'baddon' ),
                'label_off' => esc_html__( 'No', 'baddon' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'banner',
            [
                'label' => esc_html__( 'Add Banner For Video', 'baddon' ),
                'type' =>Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_banner_d'    =>  'true',
                ]
            ]
        );

        $this->add_control(
            'auto_play',
            [
            'label' => __( 'Auto Play', 'baddon' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __( 'Choose a option whatever you want - Show / Hide
', 'baddon' ),
                'label_on' => __( 'yes', 'baddon' ),
                'label_off' => __( 'no', 'baddon' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'video_loop',
            [
            'label' => __( 'Video Loop', 'baddon' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'description' => __( 'Choose a option whatever you want - Show / Hide
', 'baddon' ),
                'label_on' => __( 'yes', 'baddon' ),
                'label_off' => __( 'no', 'baddon' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

          $this->add_control(
            'player_theme',
            [
            'label' => __( 'Player Theme Color', 'baddon' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default'   =>  '#e74c3c',
            'description' => __( 'Choose The player Color
', 'baddon' ),
                'label_on' => __( 'yes', 'baddon' ),
                'label_off' => __( 'no', 'baddon' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

  $this->add_control(
            'p_font',
            [
                'label'         => esc_html__( 'Player Font Size', 'baddon' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    =>['px'],
                'range'         => 
                [
                    'px' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
            ]
        );
        
     $this->end_controls_section();

	}
	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

			$settings = $this->get_settings_for_display();
            $options = [];
		    $options['choose_v_source'] = $settings['choose_v_source'];
		    $options['srrc_type'] = $settings['srrc_type'];
		    $options['videoos_upload'] = $settings['videoos_upload'];
		    $options['videoos_link'] = $settings['videoos_link'];
		    $options['video_d_list'] = $settings['video_d_list'];
		    $options['custom_logo_d'] = $settings['custom_logo_d'];
		    $options['d_logo'] = $settings['d_logo'];
		    $options['custom_banner_d'] = $settings['custom_banner_d'];
		    $options['banner'] = $settings['banner'];
		    $options['auto_play'] = $settings['auto_play'];
		    $options['video_loop'] = $settings['video_loop'];
		    $options['player_theme'] = $settings['player_theme'];
		    $options['p_font'] = $settings['p_font'];
		    $options['sub_d_bg'] = $settings['sub_d_bg'];
		    $options['dplayer_upload'] = $settings['dplayer_upload'];  
  
?>
   <div id="dplayer"  class ="dplayer" data-settings='<?php echo wp_json_encode($options) ?>'></div>
	<?php
	}
	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	// protected function _content_template() {}
	
}
