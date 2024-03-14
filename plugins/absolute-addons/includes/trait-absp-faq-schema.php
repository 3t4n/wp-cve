<?php
/**
 * FAQ Controllers
 *
 * @package AbsoluteAddons
 * @author Name <email>
 * @version
 * @since
 * @license
 */

namespace AbsoluteAddons;

use Elementor\Controls_Manager;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

trait Absp_FAQ_Schema {

	/**
	 * Render Slider Controls.
	 */
	protected function render_faq_schema_control() {
		$this->add_control(
			'faq_schema',
			[
				'label'     => __( 'FAQ Schema', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);
	}

	/**
	 * @param array|WP_Post[] $items
	 * @param string $title_key
	 * @param string $content_key
	 * @param array $settings
	 */
	protected function render_faq_schema( $items, $title_key = '', $content_key = '', $settings = [] ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
			$json = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => [],
			];
			foreach ( $items as $item ) {
				$question = '';
				$answer   = '';
				if ( true === is_a( $item, 'WP_Post' ) ) {
					$question = $item->post_title;
					$answer   = $item->post_content;
				} else {
					if ( $title_key && $content_key ) {
						if ( is_object( $item ) ) {
							$question = $item->{$title_key};
							$answer   = $item->{$content_key};
						} else {
							$question = $item[ $title_key ];
							$answer   = $item[ $content_key ];
						}
					}
				}

				$json['mainEntity'][] = [
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $question ),
					'acceptedAnswer' => [
						'@type' => 'Answer',
						'text'  => $this->parse_text_editor( $answer ),
					],
				];
			}
			?>
			<script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
			<?php
		}
	}
}

// End of file trait-absp-slider-controller.php.
