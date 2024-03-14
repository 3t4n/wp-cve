<?php
namespace LaStudioKitThemeBuilder\Modules\Popup;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag as DynamicTagsTag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module as DynamicTagsModule;
use LaStudioKitExtensions\Elementor\Controls\Control_Query as QueryControlModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tag extends DynamicTagsTag {

	public function get_name() {
		return 'popup';
	}

	public function get_title() {
		return esc_html__( 'Popup', 'lastudio-kit' );
	}

	public function get_group() {
		return DynamicTagsModule::ACTION_GROUP;
	}

	public function get_categories() {
		return [ DynamicTagsModule::URL_CATEGORY ];
	}

	public static function on_import_replace_dynamic_content( $config, $map_old_new_post_ids ) {
		if ( isset( $config['settings']['popup'] ) ) {
			$config['settings']['popup'] = $map_old_new_post_ids[ $config['settings']['popup'] ];
		}

		return $config;
	}

	public function register_controls() {
		$this->add_control(
			'action',
			[
				'label' => esc_html__( 'Action', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'open',
				'options' => [
					'open' => esc_html__( 'Open Popup', 'lastudio-kit' ),
					'close' => esc_html__( 'Close Popup', 'lastudio-kit' ),
					'toggle' => esc_html__( 'Toggle Popup', 'lastudio-kit' ),
				],
			]
		);

		$this->add_control(
			'popup',
			[
				'label' => esc_html__( 'Popup', 'lastudio-kit' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
					'query' => [
						'posts_per_page' => 20,
						'post_status' => [ 'publish', 'private' ],
						'meta_query' => [
							[
								'key' => Document::TYPE_META_KEY,
								'value' => 'popup',
							],
						],
					],
				],
				'label_block' => true,
				'condition' => [
					'action' => [ 'open', 'toggle' ],
				],
			]
		);

		$this->add_control(
			'do_not_show_again',
			[
				'label' => esc_html__( 'Don\'t Show Again', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'action' => 'close',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_active_settings();

		if ( 'close' === $settings['action'] ) {
			$this->print_close_popup_link( $settings );

			return;
		}

		$this->print_open_popup_link( $settings );
	}

	// Keep Empty to avoid default advanced section
	protected function register_advanced_section() {}

	private function print_open_popup_link( array $settings ) {
		if ( ! $settings['popup'] ) {
			return;
		}

		$link_action_url = lastudio_kit()->elementor()->frontend->create_action_hash( 'popup:open', [
			'id' => $settings['popup'],
			'toggle' => 'toggle' === $settings['action'],
		] );

		Module::add_popup_to_location( $settings['popup'] );

		// PHPCS - `create_action_hash` is safe.
		echo $link_action_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	private function print_close_popup_link( array $settings ) {
		// PHPCS - `create_action_hash` is safe.
		echo lastudio_kit()->elementor()->frontend->create_action_hash( 'popup:close', [ 'do_not_show_again' => $settings['do_not_show_again'] ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
