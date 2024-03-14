<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_ThankYou_WC_Pages_Divi
 */
#[AllowDynamicProperties]

  class WFFN_ThankYou_WC_Pages_Divi {

	private static $ins = null;
	protected $template_type = [];
	protected $design_template_data = [];
	protected $templates = [];
	private $edit_id = 0;
	private $url = '';

	/**
	 * WFFN_ThankYou_WC_Pages_Divi constructor.
	 */
	public function __construct() {
		$this->url = plugin_dir_url( __FILE__ );
		add_action( 'divi_extensions_init', [ $this, 'init_extension' ] );
	}



	public function add_default_templates() {

		$template = [
			'slug'        => 'divi',
			'title'       => __( 'Divi', 'funnel-builder' ),
			'button_text' => __( 'Edit', 'funnel-builder' ),
			'edit_url'    => add_query_arg( [
				'p'         => $this->edit_id,
				'et_fb'     => 1,
				'PageSpeed' => 'off',
			], site_url() ),
		];

		WFFN_Core()->thank_you_pages->register_template_type( $template );
		$templates = WooFunnels_Dashboard::get_all_templates();
		$designs   = isset( $templates['wc_thankyou'] ) ? $templates['wc_thankyou'] : [];

		if ( isset( $designs['divi'] ) && is_array( $designs['divi'] ) ) {
			foreach ( $designs['divi'] as $d_key => $templates ) {

				if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
					$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
				}
				WFFN_Core()->thank_you_pages->register_template( $d_key, $templates, 'divi' );

			}
		} else {

			$empty_template = [
				"type"               => "view",
				"import"             => "no",
				"show_import_popup"  => "no",
				"slug"               => "divi_1",
				"build_from_scratch" => true,

			];
			WFFN_Core()->thank_you_pages->register_template( 'divi_1', $empty_template, 'divi' );
		}

		return [];
	}

	/**
	 * @return WFFN_ThankYou_WC_Pages_Divi|null
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function init_extension() {

		if ( wp_doing_ajax() ) {
			$post_type = WFFN_Core()->thank_you_pages->get_post_type_slug();
			if ( isset( $_REQUEST['action'] ) && "et_fb_get_saved_templates" === $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && $post_type !== $_REQUEST['et_post_type'] ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( isset( $_REQUEST['action'] ) && "et_fb_update_builder_assets" === $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && $post_type !== $_REQUEST['et_post_type'] ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			$post_id = 0;
			if ( isset( $_REQUEST['action'] ) && "heartbeat" === $_REQUEST['action'] && isset( $_REQUEST['data'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_REQUEST['data']['et'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$post_id = $_REQUEST['data']['et']['post_id']; //phpcs:ignore
				}
			}

			if ( isset( $_REQUEST['post_id'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['et_post_id'] ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['et_post_id'] );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( $post_id > 0 ) {
				$post = get_post( $post_id );
				if ( is_null( $post ) || $post->post_type !== $post_type ) {
					return;
				}
			}
		}

		include __DIR__ . '/class-wfty-divi-extension.php';

	}
}

WFFN_ThankYou_WC_Pages_Divi::get_instance();
