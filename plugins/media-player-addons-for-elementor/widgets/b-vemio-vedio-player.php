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
class b_vemio_vedio extends Widget_Base {

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
		return 'VemioVideoPlayer';
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
		return esc_html__( 'Vemio Video Player', 'baddon' );
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
		return 'bl_icon fab fa-vimeo-v';
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
            'section_b_v', [
                'label' =>esc_html__( 'Vedio Player Content Settings', 'baddon' ),
            ]
        );


		$this->add_control(
            'vd_posster_v',
            [
                'label' => esc_html__( 'Vedio Poster', 'baddon'),
                'type' => Controls_Manager::MEDIA,
           
               
            ]
        );

	
        $this->add_control(
            'vd_link_v',
            [
                'label' => esc_html__( 'Vedio link', 'baddon'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://Vimeo Link.com', 'baddon' ),
                'default' => 'https://vimeo.com/46926279?fbclid=IwAR3KuJWuuVHXbQwGiwASe97PNtJ1qdS_cMcOyHVxSSzecGnVY1uoFYWcO14'
            ]
        );
     
    $this->end_controls_section();


    $this->start_controls_section(
            'seion_t_v', [
                'label' =>esc_html__( 'Vedio Player Color Settings', 'baddon' ),
            ]
        );


      $this->add_control(
            'vimeo_col',
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

        //swicher
		 $this->start_controls_section(
            'section_abv', [
                'label' =>esc_html__( 'Vedio Player Control Settings', 'baddon' ),
            ]
        );

		
		   $this->add_control(
            'important_note_v',
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
        'se_v_v_ttabb', [
            'label' =>esc_html__( 'Vedio Player Other Settings', 'baddon' ),
        ]
    );

      $this->add_control(
            'vc_control',
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

		//Color Settings
		$vd_link_v = $settings['vd_link_v'];
	
		$vd_posster_v    = $settings['vd_posster_v'];



		//switch settings	
	

		$controls = [];
	

	?>
	<div class="vimeo_player" data-settings='<?php echo wp_json_encode($controls) ?>' data-poster="<?php echo esc_url($vd_posster_v['url']); ?>"  data-plyr-provider="vimeo" data-plyr-embed-id="<?php echo esc_url($vd_link_v); ?>"></div>
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
