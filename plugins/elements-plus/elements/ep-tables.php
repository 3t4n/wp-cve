<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_Tables extends Widget_Base {

	public function get_name() {
		return 'ep_tables';
	}

	public function get_title() {
		return __( 'Tables Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-table';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			[
				'label' => __( 'General Options', 'elements-plus' ),
			]
		);

		$this->add_control(
			'responsive_tables',
			[
				'label'     => __( 'Make table responsive', 'elements-plus' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'elements-plus' ),
				'label_off' => __( 'No', 'elements-plus' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header',
			[
				'label' => __( 'Table Header', 'elements-plus' ),
			]
		);

		$this->add_control(
			'table_header',
			[
				'label'       => __( 'Table Header', 'elements-plus' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => __( 'Table Header', 'elements-plus' ),
				'fields'      => [
					[
						'name'        => 'text',
						'label'       => __( 'Header Text', 'elements-plus' ),
						'type'        => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Header Text', 'elements-plus' ),
						'default'     => __( 'Header Text', 'elements-plus' ),
					],
					[
						'name'    => 'colspan',
						'label'   => __( 'Column Span', 'elements-plus' ),
						'type'    => \Elementor\Controls_Manager::NUMBER,
						'min'     => 1,
						'step'    => 1,
						'default' => 1,
					],
					[
						'name'    => 'rowspan',
						'label'   => __( 'Row Span', 'elements-plus' ),
						'type'    => \Elementor\Controls_Manager::NUMBER,
						'min'     => 1,
						'step'    => 1,
						'default' => 1,
					],
					[
						'name'      => 'align',
						'label'     => __( 'Alignment', 'elements-plus' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => [
							'left'    => [
								'title' => __( 'Left', 'elements-plus' ),
								'icon'  => 'eicon-text-align-left',
							],
							'center'  => [
								'title' => __( 'Center', 'elements-plus' ),
								'icon'  => 'eicon-text-align-center',
							],
							'right'   => [
								'title' => __( 'Right', 'elements-plus' ),
								'icon'  => 'eicon-text-align-right',
							],
							'justify' => [
								'title' => __( 'Justified', 'elements-plus' ),
								'icon'  => 'eicon-text-align-justify',
							],
						],
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} table.ep-table .ep-table-head {{CURRENT_ITEM}}' => 'text-align: {{VALUE}};',
						],
					],
				],
				'default'     => [
					[
						'text' => __( 'Header Text', 'elements-plus' ),
					],
					[
						'text' => __( 'Header Text', 'elements-plus' ),
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_body',
			[
				'label' => __( 'Table Body', 'elements-plus' ),
			]
		);

		$this->add_control(
			'table_body',
			[
				'label'   => __( 'Table Body', 'elements-plus' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => [
					[
						'name'        => 'text',
						'label'       => __( 'Body Text', 'elements-plus' ),
						'type'        => \Elementor\Controls_Manager::WYSIWYG,
						'placeholder' => __( 'Type your description here', 'elements-plus' ),
						'default'     => __( 'Table Data', 'elements-plus' ),
					],
					[
						'name'         => 'new_row',
						'label'        => __( 'New Row', 'elements-plus' ),
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_on'     => __( 'On', 'your-plugin' ),
						'label_off'    => __( 'Off', 'your-plugin' ),
						'return_value' => 'yes',
						'default'      => '',
					],
					[
						'name'    => 'colspan',
						'label'   => __( 'Column Span', 'elements-plus' ),
						'type'    => \Elementor\Controls_Manager::NUMBER,
						'min'     => 1,
						'step'    => 1,
						'default' => 1,
					],
					[
						'name'    => 'rowspan',
						'label'   => __( 'Row Span', 'elements-plus' ),
						'type'    => \Elementor\Controls_Manager::NUMBER,
						'min'     => 1,
						'step'    => 1,
						'default' => 1,
					],
				],
				'default' => [
					[
						'text' => __( 'Body Text', 'elements-plus' ),
					],
					[
						'text' => __( 'Body Text', 'elements-plus' ),
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Table Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cell_padding',
			[
				'label'      => __( 'Inner Cell Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} table.ep-table td,{{WRAPPER}} table.ep-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'table_border',
				'label'    => __( 'Border', 'elements-plus' ),
				'selector' => '{{WRAPPER}} table.ep-table td,{{WRAPPER}} table.ep-table tr,{{WRAPPER}} table.ep-table th',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'table_header_style',
			[
				'label' => __( 'Table Header Style', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => __( 'Text Alignment', 'elements-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'elements-plus' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'elements-plus' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'elements-plus' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elements-plus' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-head th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label'     => __( 'Text Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-head' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} table.ep-table .ep-table-head',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'header_bg_color',
			[
				'label'     => __( 'Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-head' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'table_body_style',
			[
				'label' => __( 'Table Body Style', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'body_text_align',
			[
				'label'     => __( 'Alignment', 'elements-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'elements-plus' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'elements-plus' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'elements-plus' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elements-plus' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-body' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'body_text_color',
			[
				'label'     => __( 'Text Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-body' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'body_text_typography',
				'selector' => '{{WRAPPER}} table.ep-table .ep-table-body',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'body_bg_color',
			[
				'label'     => __( 'Primary Background Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-body' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'body_striped_bg_color',
			[
				'label'     => __( 'Secondary Background Color (alternating rows)', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} table.ep-table .ep-table-body tr:nth-of-type(2n)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings();
		$table_header = $settings['table_header'];
		$table_body   = $settings['table_body'];
		$responsive   = 'yes' === $settings['responsive_tables'] ? 'ep-table-responsive' : '';

		?>
		<table class="ep-table <?php echo esc_attr( $responsive ); ?>">
			<thead class="ep-table-head">
				<tr>
					<?php
					foreach ( $table_header as $arr_index => $data ) {

						echo '<th class="elementor-repeater-item-' . esc_attr( $data['_id'] ) . '" colspan="' . intval( $data['colspan'] ) . '" rowspan="' . intval( $data['rowspan'] ) . '">' . esc_html( $data['text'] ) . '</th>';
					}
					?>
				</tr>
			</thead>
			<tbody class="ep-table-body">
				<tr>
					<?php
					foreach ( $table_body as $arr_index => $data ) {
						if ( 'yes' === $data['new_row'] ) {
							echo '</tr><tr>';
						}

						echo '<td colspan="' . intval( $data['colspan'] ) . '" rowspan="' . intval( $data['rowspan'] ) . '">' . wp_kses_post( $data['text'] ) . '</td>';
					}
					?>
				</tr>
			</tbody>
		</table>
		<?php
	}

}

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_Tables() );
	}
);

