<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

/**
 * Get the existing menus in array format
 * @return array
 */
function better_get_menu_array() {
    $menus = wp_get_nav_menus();
    $menu_array = [];
    foreach ( $menus as $menu ) {
        $menu_array[$menu->slug] = $menu->name;
    }
    return $menu_array;
}

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Header extends Widget_Base {

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
		return 'better-header';
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
		return esc_html__( 'BETTER Header', 'elementor-hello-world' );
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
		return 'eicon-posts-ticker';
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
		return [ 'better-category' ];
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
		return [ 'swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','scrollit','counterup','jquery.twentytwenty','better-el-addons','elementor-hello-world' ];
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

		// start of the Content tab section
		$this->start_controls_section(
			'clients_content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
        );
        
        $this->add_control(
			'better_logo',
			[
				'label' => __( 'White Logo', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
        );
        
        $this->add_control(
			'better_logo_dark',
			[
				'label' => __( 'Dark Logo', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);

		$this->add_control(
            'menu', [
                'label' => __( 'Menu', 'saasland-core' ),
                'type' => Controls_Manager::SELECT,
                'options' => better_get_menu_array()
            ]
        );

		$this->end_controls_section();
		// end of the Content tab section

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'better_header_nav_color',
			[
				'label' => esc_html__( 'Header Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-navbar.style-1.nav-scroll' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'better_header_links_color',
			[
				'label' => esc_html__( 'Header links Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .navbar .navbar-nav .nav-link' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'better_sticky_header_links_color',
			[
				'label' => esc_html__( 'Scroll Header links Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .nav-scroll .navbar-nav .nav-link' => 'color: {{VALUE}}',
				],
			]
        );

		$this->end_controls_section();

	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    // get our input from the widget settings.
	    $settings = $this->get_settings_for_display();
	    ?>
	    <nav class="better-navbar navbar change navbar-expand-lg style-1">
	        <div class="container">
	            <!-- Logo -->
	            <a class="logo" href="#">
	                <img class="white" src="<?php echo esc_url( $settings['better_logo']['url'] ); ?>" alt="logo">
	                <img class="dark d-none" src="<?php echo esc_url( $settings['better_logo_dark']['url'] ); ?>" alt="logo">
	            </a>

	            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
	                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	                <span class="icon-bar"><i class="fas fa-bars"></i></span>
	            </button>

	            <!-- navbar links -->
	            <div class="collapse navbar-collapse" id="navbarSupportedContent">
	                <?php
	                $menu = !empty( $settings['menu'] ) ? $settings['menu'] : 'main-menu';
	                wp_nav_menu( array(
	                    'menu'            => $menu,
	                    'theme_location'  => 'menu-1',
	                    'menu_class'      => 'navbar-nav ml-auto',
	                    'container'       => false,
	                ) );
	                ?>
	            </div>
	        </div>
	    </nav>
	    <?php
	}

}