<?php
namespace PandoExtra\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class ELPT_Elemenfolio extends Widget_Base {

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
		return 'elpug';
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
		return __( 'Elementor Portfolio', 'elpug' );
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
		return 'eicon-elementor-square';
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
				'label' => __( 'Portfolio Settings', 'elpug' ),
			]
		);

		$this->add_control(
		  'postsperpage',
		  [
		     'label'   => __( 'Number of projects to show', 'elpug' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 12,
		     'min'     => 1,
		     'max'     => 60,
		     'step'    => 1,
		  ]
		);


		$this->add_control(
			'showfilter',
			[
				'label' => __( 'Show category filter?', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __( 'Yes', 'elpug' ),
					'no' => __( 'No', 'elpug' ),
				]
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Display specific portfolio category', 'elpug' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'On', 'elpug' ),
				'label_off' => __( 'Off', 'elpug' ),
				'return_value' => 'yes',
			]
		);

		/*$portfolio_taxonomies = get_terms( array('taxonomy' => 'elemenfoliocategory', 'fields' => 'id=>name', 'hide_empty' => false, ) );

		$this->add_control(
			'taxonomy',
			[
				'label' => __( 'If yes, select wich portfolio category to show', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => $portfolio_taxonomies,
			]
		);*/

		$this->add_control(
			'margin',
			[
				'label' => __( 'Use item margin?', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'elpug' ),
					'no' => __( 'No', 'elpug' ),
				]
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Number of columns', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'2' => __( 'Two Columns', 'elpug' ),
					'3' => __( 'Three Columns', 'elpug' ),
					'4' => __( 'Four Columns', 'elpug' ),
				]
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Grid Style', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'box',
				'options' => [
					'masonry' => __( 'Masonry', 'elpug' ),
					'box' => __( 'Boxes', 'elpug' ),				]
			]
		);

		$this->add_control(
			'linkto',
			[
				'label' => __( 'Each project links to', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'project',
				'options' => [
					'image' => __( 'Featured Image into Lightbox', 'elpug' ),
					'project' => __( 'Project Details Page', 'elpug' ),				]
			]
		);

		$this->end_controls_section();

		/*$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'elpug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' => __( 'Text Transform', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'elpug' ),
					'uppercase' => __( 'UPPERCASE', 'elpug' ),
					'lowercase' => __( 'lowercase', 'elpug' ),
					'capitalize' => __( 'Capitalize', 'elpug' ),
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
				],
			]
		);*/

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
		?>
		
		<?php
			//Query Args 
			$args = array(
				'post_type' => 'elemenfolio',
				'posts_per_page' => $postsperpage,	
			);

			$portfolioposts = get_posts($args);
	
			if(count($portfolioposts)){ 
				global $post; ?>

				<div class="elpt-portfolio">

					<?php foreach($portfolioposts as $post){ ?>

						<?php the_title(); ?><br/>

					<?php } ?>
					
				</div>

				<?php wp_reset_postdata(); ?>

			<?php } 


		?>

		<?php echo do_shortcode('[elemenfolio postsperpage="'.$settings['postsperpage'].'" type="'.$settings['type'].'" taxonomy="'.$settings['taxonomy'].'" showfilter="'.$settings['showfilter'].'" style="'.$settings['style'].'" margin="'.$settings['margin'].'" columns="'.$settings['columns'].'" linkto="'.$settings['linkto'].'"]'); ?>
		
		<?php wp_reset_postdata(); ?>
		

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