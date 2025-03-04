<?php
namespace Skt_Addons_Elementor\Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

defined( 'ABSPATH' ) || die();

class Cache_Manager {

	private static $widgets_cache;

	public static function init() {
		add_action( 'elementor/editor/after_save', [ __CLASS__, 'cache_widgets' ], 10, 2 );
		add_action( 'after_delete_post', [ __CLASS__, 'delete_cache' ] );
	}

	public static function delete_cache( $post_id ) {
		// Delete to regenerate cache file
		$assets_cache = new Assets_Cache( $post_id );
		$assets_cache->delete();
	}

	public static function cache_widgets( $post_id, $data ) {
		if ( ! self::is_published( $post_id ) ) {
			return;
		}

		self::$widgets_cache = new Widgets_Cache( $post_id, $data );
		self::$widgets_cache->save();

		// Delete to regenerate cache file
		$assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
		$assets_cache->delete();
	}

	public static function is_published( $post_id ) {
		return get_post_status( $post_id ) === 'publish';
	}

	public static function is_editing_mode() {
		return (
			skt_addons_elementor()->editor->is_edit_mode() ||
			skt_addons_elementor()->preview->is_preview_mode() ||
			is_preview()
		);
	}

	public static function is_built_with_elementor( $post_id ) {
		return skt_addons_elementor()->db->is_built_with_elementor( $post_id );
	}

	public static function should_enqueue( $post_id ) {
		return (
			skt_addons_elementor_is_on_demand_cache_enabled() &&
			self::is_built_with_elementor( $post_id ) &&
			self::is_published( $post_id ) &&
			! self::is_editing_mode()
		);
	}

	public static function should_enqueue_raw( $post_id ) {
		return (
			self::is_built_with_elementor( $post_id ) &&
			(
				! skt_addons_elementor_is_on_demand_cache_enabled() ||
				! self::is_published( $post_id ) ||
				self::is_editing_mode()
			)
		);
	}

	public static function enqueue_fa5_fonts( $post_id ) {
		$post_css = new Post_CSS( $post_id );
		$meta = $post_css->get_meta();
		if ( ! empty( $meta['icons'] ) ) {
			$icons_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
			foreach ( $meta['icons'] as $icon_font ) {
				if ( ! isset( $icons_types[ $icon_font ] ) ) {
					continue;
				}
				skt_addons_elementor()->frontend->enqueue_font( $icon_font );
			}
		}
	}

	public static function enqueue( $post_id ) {
		$assets_cache = new Assets_Cache( $post_id, self::$widgets_cache );
		$assets_cache->enqueue_libraries();
		$assets_cache->enqueue();
		self::enqueue_fa5_fonts( $post_id );

		wp_enqueue_script( 'skt-addons-elementor' );

		do_action( 'sktaddonselementor_enqueue_assets', $is_cache = true, $post_id );
	}

	public static function enqueue_raw() {
		$widgets_map = Widgets_Manager::get_widgets_map();
		$inactive_widgets = Widgets_Manager::get_inactive_widgets();

		foreach ( $widgets_map as $widget_key => $data ) {
			if ( ! isset( $data['vendor'] ) ) {
				continue;
			}

			if ( in_array( $widget_key, $inactive_widgets ) ) {
				continue;
			}

			$vendor = $data['vendor'];

			if ( isset( $vendor['css'] ) && is_array( $vendor['css'] ) ) {
				foreach ( $vendor['css'] as $vendor_css_handle ) {
					wp_enqueue_style( $vendor_css_handle );
				}
			}

			if ( isset( $vendor['js'] ) && is_array( $vendor['js'] ) ) {
				foreach ( $vendor['js'] as $vendor_js_handle ) {
					wp_enqueue_script( $vendor_js_handle );
				}
			}
		}

		wp_enqueue_style( 'skt-addons-elementor' );
		wp_enqueue_script( 'skt-addons-elementor' );

		do_action( 'sktaddonselementor_enqueue_assets', $is_cache = false, 0 );
	}
}

Cache_Manager::init();