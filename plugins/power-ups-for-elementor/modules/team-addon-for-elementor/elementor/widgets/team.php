<?php
namespace ElpugTeam\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class ELPUG_Team extends Widget_Base {

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
		return 'team_member';
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
		return __( 'Team Member', 'elpug' );
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
		return 'eicon-person';
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
		return [ 'elpug-elements' ];
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
		return [ 'elpug' ];
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

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Team Member Settings', 'elpug' ),
			]
		);

		$this->add_control(
		  'team_name',
		  [
		     'label'   => __( 'Name', 'elpug' ),
		     'type'    => Controls_Manager::TEXT,
		     'default'     => __( 'Type the Name Here', 'elpug' ),
     		 //'placeholder' => __( 'Type your title text here', 'elpug' ),
		  ]
		);

		$this->add_control(
		  'team_position',
		  [
		     'label'   => __( 'Position (optional, leave blank to hide)', 'elpug' ),
		     'type'    => Controls_Manager::TEXT,
		     //'default'     => __( '', 'elpug' ),
     		 'placeholder' => __( 'Type the position here (eg: CEO)', 'elpug' ),
		  ]
		);

		$this->add_control(
		  'team_description',
		  [
		     'label'   => __( 'Description (optional, leave blank to hide)', 'elpug' ),
		     'type'    => Controls_Manager::TEXTAREA,
		     //'default'     => __( 'Type the Name Here', 'elpug' ),
     		 //'placeholder' => __( 'Type your title text here', 'elpug' ),
		  ]
		);

		$this->add_control(
		  'team_image',
		  [
		     'label'   => __( 'Image', 'elpug' ),
		     'type' => Controls_Manager::MEDIA,
		     //'default'     => __( 'Type the Name Here', 'elpug' ),
     		 //'placeholder' => __( 'Type your title text here', 'elpug' ),
		  ]
		);

		$this->add_control(
			'team_social',
			[
				'label' => __( 'Links / Social Media', 'elpug' ),
				'type' => Controls_Manager::REPEATER,				
				'fields' => [
					[
						'name' => 'team_list_icon',
						'label' => __( 'Icon', 'elpug' ),
						'type' => Controls_Manager::ICON,
						'include' => [
				            'fa fa-facebook',
				            'fa fa-flickr',
				            'fa fa-google-plus',
				            'fa fa-instagram',
				            'fa fa-linkedin',
				            'fa fa-pinterest',
				            'fa fa-reddit',
				            'fa fa-twitch',
				            'fa fa-twitter',
				            'fa fa-vimeo',
				            'fa fa-youtube',
				            'fa fa-link',
				        ],
					],
					[
						'name' => 'team_list_url',
						'label' => __( 'URL', 'elpug' ),
						'type' => Controls_Manager::URL,
						'show_external' => true, // Show the 'open in new tab' button.
					],
				],
				//'title_field' => '{{{ team_list_url }}}',
			]
		);

		$this->add_control(
		  'team_style',
		  [
		     'label'       => __( 'Element Style', 'elpug' ),
		     'type' => Controls_Manager::SELECT,
		     'default' => 'elpug-team-style1',
		     'options' => [
		     	'elpug-team-style1'  => __( 'Classic', 'elpug' ),
		     	'elpug-team-style3' => __( 'Rounded', 'elpug' ),
		     	'elpug-team-style2' => __( 'Show Content on Hover', 'elpug' ),
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
		$settings = $this->get_settings();

		$image = $this->get_settings( 'team_image' );

		$sociallist = $this->get_settings( 'team_social' );
		?>

		<div class="elpug-team-item-wrapper">

			<figure class="elpug-team-item <?php echo esc_attr($settings['team_style']); ?>">
				<div class="elpug-team-image" style="background-image: url(<?php echo esc_url($image['url']); ?>);">
					<img src="<?php echo esc_url($image['url']); ?>">
				</div>
				
				<figcaption>
					<div class="elpug-team-caption">
						<h4 class="elpug-team-item-heading"><?php echo esc_html($settings['team_name']); ?></h4>
						<div class="elpug-team-item-position"><?php echo esc_html($settings['team_position']); ?></div>
						<div class="elpug-team-item-description"><?php echo esc_html($settings['team_description']); ?></div>
						<?php if ( $sociallist ) { ?>
						<div class="elpug-team-item-links">
							<ul class="elpug-team-item-links">
								<?php foreach ( $sociallist as $item ) { ?>
									<?php $itemlink = $item['team_list_url']; ?>
									<li><a href="<?php echo esc_url($itemlink['url']); ?>"><i class="<?php echo esc_attr($item['team_list_icon']); ?>"></i></a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</div>
				</figcaption>			
			</figure>
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
	/*protected function _content_template() {
		$sliderheight = $settings['slider_height'];
		?>
		
		<div class="pando-slideshow">
			<?php echo do_shortcode('[pando-slider heightstyle="'.$sliderheight.'"]'); ?>
		</div>


		<?php
	}*/
}