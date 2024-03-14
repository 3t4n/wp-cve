<?php
namespace BMianAddon\Widgets;

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
class b_youtube_vedio extends Widget_Base {

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
		return 'YoutubeVideoPlayer';
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
		return esc_html__( 'Youtube Video Player', 'baddon' );
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
		return 'bl_icon fab fa-youtube';
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
		return [ 'plyr-js', 'main-js'];
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
            'section_b', [
                'label' =>esc_html__( 'Vedio Player Content Settings', 'baddon' ),
            ]
        );

		  $this->add_control(
            'vd_posster',
            [
                'label' => esc_html__( 'Vedio Poster', 'baddon'),
                'type' => Controls_Manager::MEDIA,
               
            ]
        );

        $this->add_control(
            'vd_link',
            [
                'label' => esc_html__( 'Vedio link', 'baddon'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://youtubelink.com', 'baddon' ),
                'default' => 'https://www.youtube.com/watch?v=9xwazD5SyVg'
            ]
        );
     
        $this->end_controls_section();

	//Vedio player Color Settings
		$this->start_controls_section(
            'seion_t', [
                'label' =>esc_html__( 'Vedio player Color Settings', 'baddon' ),
            ]
        );

       $this->add_control(
            'important_note_P',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/youtube_color.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
               
            ]
        );
  
    
    $this->end_controls_section();

    //Vedio player Control Settings
        $this->start_controls_section(
            'section_tabb', [
                'label' =>esc_html__( 'Vedio Player Control Settings', 'baddon' ),
            ]
        );

       $this->add_control(
            'important_note_y',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/youtube_control.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
               
            ]
        );
      

 $this->end_controls_section();
//Others settings

    	$this->start_controls_section(
        'se_ttabb', [
            'label' =>esc_html__( 'Vedio player Other Settings', 'baddon' ),
        ]
    );

    	$this->add_control(
            'important_note_OO',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/control_size.png', dirname(__FILE__)).'" />
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

        $vd_link = $settings['vd_link'];
		$vd_posster    = $settings['vd_posster'];
		
		//switch settings
		$controls = [];
	

	?>
     <div class="youtube_player" data-settings='<?php echo wp_json_encode($controls) ?>' data-poster="<?php echo esc_url($vd_posster['url']); ?>" data-plyr-provider="youtube" data-plyr-embed-id="<?php echo esc_url($vd_link); ?>">
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
	// protected function _content_template() {}
	
}
