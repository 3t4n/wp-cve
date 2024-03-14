<?php
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {
	const FEATURES_DB_KEY = 'sktaddonselementor_inactive_features';

	/**
	 * Initialize
	 */
	public static function init() {
		include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/skt-features.php';

		include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/widgets-extended.php';

		if ( sktaddonselementorextra_is_skt_addons_elementor_particle_effects_enabled() ) {
			include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/skt-particle-effects.php';
		}

		if ( is_user_logged_in() && skt_addons_elementor_is_adminbar_menu_enabled() ) {
			include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/admin-bar.php';
		}

		if ( is_user_logged_in() && skt_addons_elementor_is_skt_addons_elementor_clone_enabled() ) {
			include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/clone-handler.php';
		}

		$inactive_features = self::get_inactive_features();

		foreach ( self::get_local_features_map() as $feature_key => $data ) {
			if ( ! in_array( $feature_key, $inactive_features ) ) {
				self::enable_feature( $feature_key );
			}
		}

		foreach ( self::get_pro_features_map() as $feature_key => $data ) {
			if ( in_array( $feature_key, $inactive_features ) ) {
				self::disable_pro_feature( $feature_key );
			}
		}
	}

	public static function load_display_condition() {
		include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/display-conditions.php';
	    include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/conditions/condition.php';
	}

	public static function get_features_map() {
		$features_map = [];

		$local_features_map = self::get_local_features_map();
		$features_map = array_merge( $features_map, $local_features_map );

		return apply_filters( 'sktaddonselementor_get_features_map', $features_map );
	}

	public static function get_inactive_features() {
		return get_option( self::FEATURES_DB_KEY, [] );
	}

	public static function save_inactive_features( $features = [] ) {
		update_option( self::FEATURES_DB_KEY, $features );
	}

	/**
	 * Get the pro features map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_features_map() {
		return [];
	}

	/**
	 * Get the free features map
	 *
	 * @return array
	 */
	public static function get_local_features_map() {
		return [
			'background-overlay' => [
				'title' => __( 'Background Overlay', 'skt-addons-elementor' ),
				'icon' => 'skti skti-layer',
				'is_pro' => false,
			],
			'grid-layer' => [
				'title' => __( 'Grid Layer', 'skt-addons-elementor' ),
				'icon' => 'skti skti-grid',
				'is_pro' => false,
			],
			'skt-particle-effects' => [
				'title' => __( 'SKT Particle Effects', 'skt-addons-elementor' ),
				'icon' => 'skti skti-spark',
				'is_pro' => false,
			],
			'image-masking' => [
				'title' => __( 'Image Masking', 'skt-addons-elementor' ),
				'icon' => 'skti skti-image-masking',
				'is_pro' => false,
			],
			'display-conditions' => [
				'title' => __( 'Display Condition', 'skt-addons-elementor' ),
				'icon' => 'skti skti-display-condition',
				'is_pro' => false,
			],
			'floating-effects' => [
				'title' => __( 'Floating Effects', 'skt-addons-elementor' ),
				'icon' => 'skti skti-weather-flood',
				'is_pro' => false,
			],
			'wrapper-link' => [
				'title' => __( 'Wrapper Link', 'skt-addons-elementor' ),
				'icon' => 'skti skti-section-link',
				'is_pro' => false,
			],
			'css-transform' => [
				'title' => __( 'CSS Transform', 'skt-addons-elementor' ),
				'icon' => 'skti skti-3d-rotate',
				'is_pro' => false,
			],
			'css-transform' => [
				'title' => __( 'CSS Transform', 'skt-addons-elementor' ),
				'icon' => 'skti skti-3d-rotate',
				'is_pro' => false,
			],
			'shape-divider' => [
				'title' => __( 'Shape Divider', 'skt-addons-elementor' ),
				'icon' => 'skti skti-map',
				'is_pro' => false,
			],
			'column-extended' => [
				'title' => __( 'Column Order & Extension', 'skt-addons-elementor' ),
				'icon' => 'skti skti-flip-card2',
				'is_pro' => false,
			],
			'advanced-tooltip' => [
				'title' => __( 'SKT Tooltip', 'skt-addons-elementor' ),
				'icon' => 'skti skti-comment-square',
				'is_pro' => false,
			],
			'text-stroke' => [
				'title' => __( 'Text Stroke', 'skt-addons-elementor' ),
				'icon' => 'skti skti-text-outline',
				'is_pro' => false,
			],
		];
	}

	protected static function enable_feature( $feature_key ) {
		$feature_file = SKT_ADDONS_ELEMENTOR_DIR_PATH . 'extensions/' . $feature_key . '.php';

		if ( is_readable( $feature_file ) ) {
			include_once( $feature_file );
		}
	}
}

Extensions_Manager::init();