<?php
/**
 * WpBean Elementor Team Members Widget
 *
 * @link       https://wpbean.com
 * @since      1.0.0
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class WPB_Elementor_Widget_Our_Team_Members
 *
 */

class WPB_Elementor_Widget_Our_Team_Members extends Widget_Base {

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Our Team Members', 'our-team-members' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wpb-our-team-members';
	}

	/**
	 * Widget Category
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'wpb-elementor-widgets' ];
	}


	/**
	 * Widget Scripts
	 */
	
	public function get_script_depends() {
        return [
            'bars',
            'wpb_otm_main'
        ];
    }

	/**
	 * Register Elementor Controls
	 */
	protected function _register_controls() {
		$this->otm_generel_settings_section();
	}

	/**
	 * Content > Title section.
	 */
	private function otm_generel_settings_section() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Team Members Options', 'our-team-members' ),
			]
		);

		$this->add_control(
			'skin',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Select a skin', 'our-team-members' ),
				'default' => 'default',
				'options' => [
					'default' 	=> __( 'Skin Default', 'our-team-members' ),
					'one' 		=> __( 'Skin One', 'our-team-members' ),
					'two' 		=> __( 'Skin Two', 'our-team-members' ),
					'three' 	=> __( 'Skin Three', 'our-team-members' ),
				],
			]
		);

		$this->add_control(
			'column',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Number of columns', 'our-team-members' ),
				'default' => 4,
				'options' => [
					1 	=> __( 'One Column', 'our-team-members' ),
					2 	=> __( 'Two Columns', 'our-team-members' ),
					3 	=> __( 'Three Columns', 'our-team-members' ),
					4 	=> __( 'Four Columns', 'our-team-members' ),
					6 	=> __( 'Six Columns', 'our-team-members' ),
				],
			]
		);

		$this->add_control(
            'number_of_member',
            [
                'label' 	=> __('Number of members', 'our-team-members'),
                'type' 		=> Controls_Manager::NUMBER,
                'default' 	=> 4,
            ]
        );

        $this->add_control(
			'order',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Order', 'our-team-members' ),
				'default' => 'DESC',
				'options' => [
					'DESC' 		=> esc_html__('Descending', 'our-team-members'),
					'ASC' 		=> esc_html__('Ascending', 'our-team-members')
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Order By', 'our-team-members' ),
				'default' => 'date',
				'options' => [
					'date' 				=> esc_html__('Date', 'our-team-members'),
					'menu_order'		=> esc_html__('Menu Order', 'our-team-members'),
					'title' 			=> esc_html__('Title', 'our-team-members'),
					'id' 				=> esc_html__('ID', 'our-team-members'),
					'last_modified' 	=> esc_html__('Last modified', 'our-team-members'),
					'rand' 				=> esc_html__('Random', 'our-team-members')
				],
			]
		);

		$this->add_control(
            'member_categories',
            [
                'label' 	=> __('Comma separated member categories id', 'our-team-members'),
                'type' 		=> Controls_Manager::TEXT,
                'default' 	=> '',
            ]
        );


        $this->add_control(
            'excerpt_length',
            [
                'label' 	=> __('Description length', 'our-team-members'),
                'type' 		=> Controls_Manager::NUMBER,
                'default' 	=> 20,
            ]
        );

        $this->add_control(
            'x_class',
            [
                'label' 	=> __('Extra CSS Class', 'our-team-members'),
                'type' 		=> Controls_Manager::TEXT,
                'default' 	=> '',
            ]
        );

		$this->end_controls_section(); // end section-title
	}


	/**
	 * Render function to output the team members.
	 */
	protected function render() {
		$settings = $this->get_settings();

		echo do_shortcode( '[wpb-our-team-members skin="' . $settings['skin'] . '" column="' . $settings['column'] . '" number_of_member="' . $settings['number_of_member'] . '" excerpt_length="' . $settings['excerpt_length'] . '" order="' . $settings['order'] . '" orderby="' . $settings['orderby'] . '" member_categories="' . $settings['member_categories'] . '" x_class="' . $settings['x_class'] . '" ]' );
	}
}

