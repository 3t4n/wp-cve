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
class b_art_addon extends Widget_Base {

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
		return 'artvideoplayer';
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
		return esc_html__( 'Art Player', 'baddon' );
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
		return 'bl_icon far fa-play-circle';
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
        return [ 'artplayer-js', 'main-js'];
    }
	protected function register_controls() {

       //Vedio player Content Settings

         //single video

        $this->start_controls_section(
            '_ssection_images',
            [
                'label' => __( 'Art Player Content Settings', 'baddon' ),
               
            ]
        );

        $this->add_control(
        'choo_source',
        [
            'label'         => __( 'Multiple Quality', 'baddon' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => __( 'yes', 'b-addon' ),
            'label_off'     => __( 'no', 'b-addon' ),
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
                    'choo_source'    =>  '',
                ]
            ]
        );

       $this->add_control(
            'vxideoos_upload',
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
                    'srrc_type'    =>  'uploaad',
                    'choo_source'    =>  '',
                ]
            ]
        );

        $this->add_control(
            'vxideoos_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'show_external' => false,
               'default' => 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                'condition' => [
                    'srrc_type'    =>  'liink',
                     'choo_source'    =>  '',
                ]
            ]
        );

   

    //Multiple video
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'sxrc_type',
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
            'vxideoos_upload',
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
                    'sxrc_type'    =>  'upload',
                ]
            ]
        );

        $repeater->add_control(
            'vxideoos_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'default' => 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                'condition' => [
                    'sxrc_type'    =>  'link',
                ]
            ]
        );

        $repeater->add_control(
            'vxideo_size',
            [
                'label' => esc_html__( 'Video Size', 'baddon' ),
                'type' => Controls_Manager::TEXT,
             
            ]
        );

        $this->add_control(
            'vxideo_list',
            [
                'label' => esc_html__( 'Video List', 'baddon' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                  'condition' => [
                    'choo_source'    =>  'yes',
                ]
           
            ]
        );
       $this->add_control(
            'custom_poster_art',
            [
                'label' => esc_html__( 'Add Video Poster', 'baddon' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'baddon' ),
                'label_off' => esc_html__( 'No', 'baddon' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bar_img',
            [
                'label' => esc_html__( 'Video Poster', 'baddon' ),
                'type' =>Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_poster_art'    =>  'true',
                ]
            ]
        );
        $this->add_control(
            'custom_poster_artt',
            [
                'label' => esc_html__( 'Add Album Poster', 'baddon' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'baddon' ),
                'label_off' => esc_html__( 'No', 'baddon' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'video_album_poster',
            [
                'label' => esc_html__( 'Album Poster', 'baddon' ),
                'type' =>Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_poster_artt'    =>  'true',
                ]
            ]
        );

    $this->end_controls_section();


       $this->start_controls_section(
        '_sxubc_settings',
        [
            'label' => __( 'Art Player Subtitle Settings', 'baddon' ),
           
        ]
    );
 
        $this->add_control(
            'sxrc_typed',
            [
                'label' => esc_html__( 'Subtitle Source', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'uploadds',
                'options' => [
                    'uploadds' => esc_html__( 'Upload Subtitle', 'baddon' ),
                    'linkks' => esc_html__( 'Put Subtitle Link', 'baddon' ),
                ],
            ]
        );
        $this->add_control(
            'sxubtitle_upload',
            [
                'label' => esc_html__( 'Upload Subtitle', 'baddon' ),
               
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'vtt',
                'condition' => [
                    'sxrc_typed'    =>  'uploadds',
                ]
            ]
        );
        $this->add_control(
            'sxubtitle_link',
            [
                'label' => esc_html__( 'Subtitle Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),

                'condition' => [
                    'sxrc_typed'    =>  'linkks',
                ]
            ]
        );

            $this->add_control(
            'sub_bg',
            [
                'type' => Controls_Manager::COLOR,
                'label' =>esc_html__('Player Subtitle Color', 'baddon'),
                'default'   =>  '#00b3ff',
            ]
        );
  

    $this->end_controls_section();

     $this->start_controls_section(
            'artt_color',
            [
                'label' => __( 'Art Player Color Settings', 'baddon' ),
         
            ]
        );

     $this->add_control(
        'vd_color',
        [
            'label' => __( 'Player Color', 'baddon' ),
            'type' => Controls_Manager::COLOR,
            'default' => 'red',
        ]
    );

    $this->end_controls_section();


       $this->start_controls_section(
            'artt_plyr',
            [
                'label' => __( 'Art Player Button Settings', 'baddon' ),
         
            ]
        );
        $this->add_control(
            'art_one',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/art_control.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
               
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

          //switch settings
	        $sub_bg = $this->get_settings('sub_bg');
	        $vxideo_list = $this->get_settings('vxideo_list');
	        $vxideoos_link = $this->get_settings('vxideoos_link');
	        $sxubtitle_link = $this->get_settings('sxubtitle_link');
	        $sxubtitle_upload = $this->get_settings('sxubtitle_upload');
	        $vxideoos_upload = $this->get_settings('vxideoos_upload');
	        $bar_img = $this->get_settings('bar_img');
	        $video_album_poster = $this->get_settings('video_album_poster');
	        $vd_pip = $this->get_settings('vd_pip');
	        $vd_muted = $this->get_settings('vd_muted');
	        $vd_settings = $this->get_settings('vd_settings');
	        $vd_color = $this->get_settings('vd_color');
	        $vd_camera = $this->get_settings('vd_camera');
	        $vd_full_screen = $this->get_settings('vd_full_screen');
	        $vd_full_web = $this->get_settings('vd_full_web');
            $vd_auto_p = $this->get_settings('vd_auto_p');
            $multiple_quality = $this->get_settings('choo_source');
            $custom_poster_art = $this->get_settings('custom_poster_art');
            $custom_poster_artt = $this->get_settings('custom_poster_artt');
            $srrc_type = $this->get_settings('srrc_type');
            $sxrc_typed = $this->get_settings('sxrc_typed');
        


	      //vedio content settings
          $settings = [];
          $settings['vxideoos_link'] = $vxideoos_link;
          $settings['sxubtitle_link'] = $sxubtitle_link;
          $settings['sxubtitle_upload'] = $sxubtitle_upload;
          $settings['sub_bg'] = $sub_bg;
          $settings['vxideoos_upload'] = $vxideoos_upload;
          $settings['srrc_type'] = $srrc_type;
          $settings['sxrc_typed'] = $sxrc_typed;

          if($custom_poster_art === 'true'){
            $settings['bar_img'] = $bar_img; 
          }else {
            $settings['bar_img'] = ['url' => '']; 
          }

          if($custom_poster_artt === 'true'){
           $settings['video_album_poster'] = $video_album_poster;  
          }else {
            $settings['video_album_poster'] = ['url' => '']; 
          }

          $settings['vd_color'] = $vd_color; 
          $settings['vxideo_list'] = $vxideo_list;
          //vedio swith settings
		  $controls = [];
          $controls['multiple_quality'] = $multiple_quality == 'yes' ? 'yes' : 'no'; 


	?>
    <div class="artplayer-app" id='aaa<?php echo uniqid();?>' data-controls ='<?php echo wp_json_encode($controls);?>'data-settings='<?php echo wp_json_encode($settings) ?>'>
     </div>
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

	
}
