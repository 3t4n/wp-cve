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
class b_html5_audio extends Widget_Base {

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
		return 'Html5AudioPlayer';
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
		return esc_html__( 'Html5 Audio Player', 'baddon' );
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
		return 'bl_icon fas fa-headphones-alt';
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



		//Vedio player Control Settings
		 $this->start_controls_section(
            'section_taab', [
                'label' =>esc_html__( 'Audio Player Content Settings', 'baddon' ),
            ]
        );

           $this->add_control(
            'src_type',
            [
                'label' => esc_html__( 'Audio Source', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'upload',
                'options' => [
                    'upload' => esc_html__( 'Upload Audio', 'baddon' ),
                    'link' => esc_html__( 'Audio Link', 'baddon' ),
                ],
            ]
        );

        $this->add_control(
            'audio_upload',
            array(
                'label' => esc_html__( 'Upload Audio', 'baddon' ),
                'type'  => Controls_Manager::MEDIA,
                'media_type' => 'audio',
                'condition' => array(
                    'src_type' => 'upload',
                ),
            )
        );

        $this->add_control(
            'audio_link',
            [
                'label' => esc_html__( 'Audio Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://example.com/music-name.mp3', 'baddon' ),
             
                'condition' => [
                    'src_type'    =>  'link',
                ]
            ]
        );

        $this->end_controls_section();


     //Vedio player Color Settings
		 $this->start_controls_section(
            'section_taabo', [
                'label' =>esc_html__( 'Audio Player Color Settings', 'baddon' ),
            ]
        );
		 

		     $this->add_control(
            'important_color_a',
            [
                'label' => __( 'This Section Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/audio-color.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
           
            ]
        );

        $this->end_controls_section();
        
        //Vedio player Control Settings
        $this->start_controls_section(
            'audio_tabb', [
                'label' =>esc_html__( 'Audio Player Control Settings', 'baddon' ),
            ]
        );

      $this->add_control(
            'important_note_a',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/audio-control.png', dirname(__FILE__)).'" />
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
		   if($settings['src_type'] == 'upload'){
            $audio_link = $settings['audio_upload']['url'];
        } else {
            $audio_link = $settings['audio_link'];
        }

		$controls = [];
		if($audio_link):
            $arr = explode('.', $audio_link);
            $file_ext = end($arr);
        endif;
        ?>
		<audio class="audio_player"  data-settings='<?php echo wp_json_encode($controls) ?>' controls>
		  <source src="<?php echo esc_url($audio_link); ?>" type="audio/<?php echo esc_attr($file_ext); ?>" />
		</audio>
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
