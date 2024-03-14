<?php
/**
 * Read More Button Controllers
 *
 * @package AbsoluteAddons
 * @author Name <email>
 * @version
 * @since
 * @license
 */

namespace AbsoluteAddons;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

trait Absp_Read_More_Button {

	/**
	 * Render Accordion Icon Controls.
	 *
	 * @param Absp_Widget|Repeater $controller
	 * @param array $args
	 *
	 */
	protected function render_read_more_control( $controller, $args = [] ) {

		$args = wp_parse_args( $args, [
			'show'  => 'yes',
			'label' => __( 'Read More', 'absolute-addons' ),
			'url'   => [],
		] );

		$controller->add_control(
			'show_read_more',
			[
				'label'   => __( 'Read More', 'absolute-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => $args['show'],
			]
		);

		// read_more_text
		$controller->add_control(
			'read_more_label',
			[
				'label'     => __( 'ReadMore Label', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $args['label'],
				'condition' => [
					'show_read_more!' => '',
				],
			]
		);

		if ( false !== $args['url'] ) {
			$args = wp_parse_args( $args['url'], [
				'placeholder'   => __( 'https://your-link.com', 'absolute-addons' ),
				'show_external' => true,
				'url'           => '#',
				'is_external'   => true,
				'nofollow'      => true,
			] );

			$controller->add_control(
				'read_more_url',
				[
					'label'         => __( 'ReadMore Link', 'absolute-addons' ),
					'type'          => Controls_Manager::URL,
					'placeholder'   => $args['placeholder'],
					'condition'     => [
						'show_read_more!' => '',
					],
					'show_external' => $args['show_external'],
					'default'       => [
						'url'         => $args['url'],
						'is_external' => $args['is_external'],
						'nofollow'    => $args['nofollow'],
					],
				]
			);
		}
	}

	protected function validate_read_more_settings( $settings ) {
		return is_array( $settings ) && ( isset( $settings['read_more_label'], $settings['read_more_url'] ) );
	}

	protected function maybe_render_read_more( $settings ) {
		return $this->validate_read_more_settings( $settings ) && absp_string_to_bool( $settings['show_read_more'] ) && ! empty( $settings['read_more_label'] );
	}

	/**
	 * @param string $element Element Key, used for render attribute key.
	 * @param array $settings Read More Settings, this can be repeater item.
	 * @param array $attributes extra attribute list for the button.
	 */
	protected function prepare_read_more_attributes( $element, $settings, $attributes = [], $overwrite = false ) {

		if ( ! empty( $attributes ) && is_array( $attributes ) ) {
			$this->add_render_attribute( $element, $attributes, null, $overwrite );
		}

		$this->add_link_attributes( $element, $settings['read_more_url'] );
	}

	/**
	 * @param string $element Element Key, used for render attribute key.
	 * @param array $settings Read More Settings, this can be repeater item.
	 * @param array $attributes prepare attributes
	 */
	protected function render_read_more( $element = '', $settings = [], $attributes = [], $overwrite_attribute = false ) {

		if ( ! $this->maybe_render_read_more( $settings ) ) {
			return;
		}

		if ( ! $element ) {
			$element = 'read-more';
		}

		$this->prepare_read_more_attributes( $element, $settings, $attributes, $overwrite_attribute );

		?>
		<a <?php $this->print_attribute( $element ); ?>><?php absp_render_title( $settings['read_more_label'] ); ?></a>
		<?php
	}

	// render from custom data settings

	protected function prepare_data_from_settings( $settings, $custom = [] ) {

		if ( isset( $custom['show_read_more'] ) ) {
			$show = $custom['show_read_more'];
		} elseif ( isset( $settings['show_read_more'] ) ) {
			$show = $settings['show_read_more'];
		} else {
			$show = 'no';
		}

		$url = [];
		$label = '';

		if ( 'yes' === $show ) {

			if ( isset( $custom['read_more_label'] ) ) {
				$label = $custom['read_more_label'];
			} elseif ( isset( $settings['read_more_label'] ) ) {
				$label = $settings['read_more_label'];
			} else {
				$label = __( 'Read More', 'absolute-addons' );
			}

			if ( isset( $custom['read_more_url'] ) ) {
				$url = $custom['read_more_url'];
			} elseif ( isset( $settings['read_more_url'] ) ) {
				$url = $settings['read_more_url'];
			} else {
				$url = [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				];
			}
		}

		return $this->prepare_custom_data( $label, $url, $show );
	}

	protected function prepare_custom_data( $label, $url, $show = 'yes' ) {
		return [
			'show_read_more'  => 'no' !== $show ? 'yes' : 'no',
			'read_more_label' => $label,
			'read_more_url'   => $this->prepare_custom_url_data( $url ),
		];
	}

	protected function prepare_custom_url_data( $url ) {
		if ( is_array( $url ) && isset( $url['url'], $url['is_external'], $url['nofollow'] ) ) {
			return [
				'url'         => esc_url_raw( $url['url'] ),
				'is_external' => $url['is_external'],
				'nofollow'    => $url['nofollow'],
			];
		} else {
			return [
				'url'         => esc_url_raw( $url ),
				'is_external' => '',
				'nofollow'    => '',
			];
		}
	}
}

// End of file trait-absp-slider-controller.php.
