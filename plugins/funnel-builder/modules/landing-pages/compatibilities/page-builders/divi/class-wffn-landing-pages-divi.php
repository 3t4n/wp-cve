<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Landing_Pages_Divi
 */
#[AllowDynamicProperties]

  class WFFN_Landing_Pages_Divi {

	private static $ins = null;
	protected $template_type = [];
	protected $design_template_data = [];
	protected $templates = [];
	private $edit_id = 0;
	private $url = '';

	/**
	 * WFFN_Landing_Pages_Elementor constructor.
	 */
	public function __construct() {
		$this->url = plugin_dir_url( __FILE__ );
		add_action( 'wffn_wflp_before_register_templates', array( $this, 'add_default_templates' ), 10 );
		add_filter( 'et_builder_enabled_builder_post_type_options', [ $this, 'wffn_add_lp_type_to_divi' ], 999 );
		add_filter( 'et_theme_builder_template_layouts', [ $this, 'disable_header_footer' ], 99 );
	}


	public function add_default_templates() {
		$template = [
			'slug'        => 'divi',
			'title'       => __( 'Divi', 'funnel-builder' ),
			'button_text' => __( 'Edit', 'funnel-builder' ),
			'edit_url'    => add_query_arg( [
				'p'         => WFFN_Core()->landing_pages->get_edit_id(),
				'et_fb'     => 1,
				'PageSpeed' => 'off',
			], site_url() ),
		];

		WFFN_Core()->landing_pages->admin->register_template_type( $template );
		$templates = WooFunnels_Dashboard::get_all_templates();
		$designs   = isset( $templates['landing'] ) ? $templates['landing'] : [];

		if ( isset( $designs['divi'] ) && is_array( $designs['divi'] ) ) {
			foreach ( $designs['divi'] as $d_key => $templates ) {

				if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
					$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
				}
				WFFN_Core()->landing_pages->admin->register_template( $d_key, $templates, 'divi' );

			}
		} else {

			$empty_template = [
				"type"               => "view",
				"import"             => "no",
				"show_import_popup"  => "no",
				"slug"               => "divi_1",
				"build_from_scratch" => true,

			];
			WFFN_Core()->landing_pages->admin->register_template( 'divi_1', $empty_template, 'divi' );
		}

		return [];
	}

	/**
	 * @return WFFN_Landing_Pages_Divi|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * @param $options
	 *
	 * @return mixed
	 */
	public function wffn_add_lp_type_to_divi( $options ) {
		$lp_type             = WFFN_Core()->landing_pages->get_post_type_slug();
		$options[ $lp_type ] = 'on';

		return $options;
	}

	public function disable_header_footer( $layouts ) {
		if ( ! isset( $_GET['et_fb'] ) || ! defined( 'ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE' ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $layouts;
		}

		global $post;
		if ( is_null( $post ) || $post->post_type !== WFFN_Core()->landing_pages->get_post_type_slug() ) {
			return $layouts;
		}
		$my_template = get_post_meta( $post->ID, '_wp_page_template', true );
		if ( ( 'wflp-boxed.php' === $my_template || 'wflp-canvas.php' === $my_template ) && isset( $layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ] ) ) {

			$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['id']       = 0;
			$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['enabled']  = false;
			$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['override'] = false;
			$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['id']       = 0;
			$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['enabled']  = false;
			$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['override'] = false;
		}

		return $layouts;
	}

}

WFFN_Landing_Pages_Divi::get_instance();
