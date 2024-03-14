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
class Bplayer_Video_Playlist extends Widget_Base {

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
		return 'bplayer-vdo-playlist';
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
		return esc_html__( 'Advanced Video Playlist Player', 'baddon' );
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
		return 'bl_icon far fa-file-video';
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

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'bplayer-script', 'bplayer-playlist'];
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
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Advanced Video Playlist Settings', 'baddon' ),
				'tab' 	=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// End Controls_tabs

		$this->add_control(
			'track_options',
			[
				'label' 	=> esc_html__( 'Video Options', 'baddon' ),
				'type' 		=> \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'track_title',
			[
				'label' 		=> esc_html__( 'Video Title', 'baddon' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder'	=> esc_attr__('Input Song Title here','baddon'),
				'label_block'	=> true,
			]
		);
		$repeater->add_control(
			'track_source',
			[
				'label' 		=> esc_html__( 'Video Source', 'baddon' ),
				'type' 			=> Controls_Manager::MEDIA,
				'media_type' 	=> 'video',
				'description'	=> esc_html__('Upload Your Video here','baddon'),
				'label_block'	=> true,
			]
		);
		$repeater->add_control(
			'track_poster',
			[
				'label' 		=> esc_html__( 'Video Thumbnail', 'baddon' ),
				'type' 			=> Controls_Manager::MEDIA,
				'default' 		=> [
					'url' 		=> \Elementor\Utils::get_placeholder_image_src(),
				],
				'label_block'	=> true,
			]
		);
		$repeater->add_control(
			'track_artist_name',
			[
				'label' 		=> esc_html__( 'Singer Name', 'baddon' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder'	=> esc_attr__('Input singer name her','baddon'),
				'label_block'	=> true,
			]
		);
		$repeater->add_control(
			'track_album',
			[
				'label' 		=> esc_html__( 'Album Name', 'baddon' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'placeholder'	=> esc_attr__('Input Song\'s Album here','baddon'),
				'label_block'	=> true,
			]
		);
		$this->add_control(
			'media_source',
			[
				'label' 		=> esc_html__( 'Video Playlist', 'baddon' ),
				'type' 			=> Controls_Manager::REPEATER,
				'fields' 		=> $repeater->get_controls(),
				'title_field' 	=> '{{{ track_title }}}',
			]
		);

		// Player Mode Options
		
		$this->add_control(
			'player_options',
			[
				'label' 		=> esc_html__( 'Player Options', 'baddon' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'after',
			]
		);

		$this->add_control(
			'dark_mode',
			[
				'label' 		=> esc_html__( 'Mode', 'baddon' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> esc_attr__( 'Dark', 'baddon' ),
				'label_off' 	=> esc_attr__( 'Light', 'baddon' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'show_label'	=> true,
				'dynamic'		=> [
					'active'	=> true
				],
				'description'	=> esc_html__( 'Choose Player Mode', 'baddon' ),
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
		$bplayer_settings = $this->get_settings();

		$bplayer_opt	= [];

		//player Mode control
		if('yes' === $settings['dark_mode'] ){
			$bplayer_opt['dark_mode'] = true;
		}else{
			$bplayer_opt['dark_mode'] = false; 
		}
		
		$bplayer_opt['media_source'] = $settings['media_source'];
	
		?>

		<div id="app" data-settings='<?php echo wp_json_encode( $bplayer_opt ); ?>'></div>
		
		<?php
	}


}
