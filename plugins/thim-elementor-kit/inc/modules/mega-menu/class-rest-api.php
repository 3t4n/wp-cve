<?php
namespace Thim_EL_Kit\Modules\MegaMenu;

use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Utilities\Rest_Response;

class Rest_API {
	use SingletonTrait;

	const NAMESPACE = 'thim-ekits-megamenu';

	const META_KEY = 'thim_ekits_menu_item';

	const META_KEY_OPTIONS = 'thim_ekits_menu_item_options';

	const ENABLE_MEGA_MENU = 'thim_enable_mega_menu';

	const DEFAULT_OPTIONS = array(
		'enableMegaMenu' => false,
		'enableIcon'     => false,
		'iconType'       => 'icon',
		'icon'           => '',
		'iconUpload'     => array(
			'id'   => null,
			'name' => null,
			'url'  => null,
		),
		'iconColor'      => '',
		'iconSize'       => '',
		'iconWidth'      => '',
		'iconHeight'     => '',
		'enableBadge'    => false,
		'badgeText'      => '',
		'badgeColor'     => '',
		'badgeBgColor'   => '',
		'badgeSize'      => '',
		'widthMenu'      => '',
		'menuType'       => 'screen',
		'menuPosition'   => 'right',
	);

	public function __construct() {
		add_action( 'thim_ekit/rest_api/register_endpoints', array( $this, 'register_endpoints' ), 10, 1 );
	}

	public function register_endpoints( $namespace ) {
		register_rest_route(
			self::NAMESPACE,
			'/get',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/create-megamenu',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_megamenu' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/save',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/get-settings',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/save-settings',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_settings' ),
				'permission_callback' => array( $this, 'permission_callback' ),
			)
		);
	}

	public function permission_callback( \WP_REST_Request $request ) {
		return current_user_can( 'edit_posts' );
	}

	public function get( \WP_REST_Request $request ) {
		$menu_item_id = isset( $request['menu_item_id'] ) ? absint( $request['menu_item_id'] ) : '';

		$response = new Rest_Response();

		$options = self::DEFAULT_OPTIONS;

		try {
			if ( empty( $menu_item_id ) ) {
				throw new \Exception( esc_html__( 'Invalid params', 'thim-elementor-kit' ) );
			}

			$option_values = get_post_meta( $menu_item_id, self::META_KEY_OPTIONS, true );

			if ( ! empty( $option_values ) ) {
				$options = wp_parse_args( json_decode( $option_values, true ), $options );
			}

			$response->status = 'success';
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		$response->data = apply_filters( 'thim_ekit/modules/mega_menu/rest_api/get/options', $options );

		return rest_ensure_response( $response );
	}

	public function save( \WP_REST_Request $request ) {
		$menu_item_id = isset( $request['menu_item_id'] ) ? absint( $request['menu_item_id'] ) : '';
		$options      = isset( $request['options'] ) ? (array) $request['options'] : '';

		$response = new Rest_Response();

		try {
			if ( empty( $menu_item_id ) ) {
				throw new \Exception( esc_html__( 'Invalid params', 'thim-elementor-kit' ) );
			}

			$options = apply_filters( 'thim_ekit/modules/mega_menu/rest_api/save/options', $options );

			$meta_id = update_post_meta( $menu_item_id, self::META_KEY_OPTIONS, wp_json_encode( $options ) );

			if ( ! $meta_id ) {
				throw new \Exception( esc_html__( 'No change options.', 'thim-elementor-kit' ) );
			}

			$response->status  = 'success';
			$response->message = esc_html__( 'Save options successfully.', 'thim-elementor-kit' );
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}

	public function create_megamenu( \WP_REST_Request $request ) {
		$menu_item_id = isset( $request['menu_item_id'] ) ? absint( $request['menu_item_id'] ) : '';

		$response = new Rest_Response();

		try {
			if ( empty( $menu_item_id ) ) {
				throw new \Exception( esc_html__( 'Invalid params', 'thim-elementor-kit' ) );
			}

			if ( ! is_nav_menu_item( $menu_item_id ) ) {
				throw new \Exception( esc_html__( 'This item is not menu item.', 'thim-elementor-kit' ) );
			}

			$mega_menu_id = get_post_meta( $menu_item_id, self::META_KEY, true );

			if ( empty( $mega_menu_id ) ) {
				$args = apply_filters(
					'thim_ekit/modules/mega_menu/rest_api/create_megamenu/args',
					array(
						'post_type'   => Custom_Post_Type::CPT,
						'post_title'  => 'mega-item-' . $menu_item_id,
						'post_status' => 'publish',
						'meta_input'  => array(
							'_elementor_edit_mode' => 'builder',
						),
					)
				);

				$mega_menu_id = wp_insert_post( $args );

				if ( is_wp_error( $mega_menu_id ) ) {
					throw new \Exception( esc_html__( 'Cannot insert mega menu', 'thim-elementor-kit' ) );
				}

				update_post_meta( $menu_item_id, self::META_KEY, $mega_menu_id );
			}

			$url = add_query_arg(
				array(
					'post'    => $mega_menu_id,
					'action'  => 'elementor',
					'nocache' => time(),
				),
				admin_url( 'post.php' )
			);

			$response->status    = 'success';
			$response->data->id  = $mega_menu_id;
			$response->data->url = $url;
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}

	public function get_settings( \WP_REST_Request $request ) {
		$menu_id = isset( $request['menuID'] ) ? absint( $request['menuID'] ) : '';

		$response = new Rest_Response();

		try {
			if ( empty( $menu_id ) ) {
				throw new \Exception( esc_html__( 'Invalid params', 'thim-elementor-kit' ) );
			}

			$enable = get_term_meta( $menu_id, self::ENABLE_MEGA_MENU, true );

			$response->status       = 'success';
			$response->data->enable = absint( $enable );
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}

	public function save_settings( \WP_REST_Request $request ) {
		$menu_id          = isset( $request['menuID'] ) ? absint( $request['menuID'] ) : '';
		$enable_maga_menu = isset( $request['enableMegaMenu'] ) ? absint( $request['enableMegaMenu'] ) : 0;

		$response = new Rest_Response();

		try {
			if ( empty( $menu_id ) ) {
				throw new \Exception( esc_html__( 'Invalid params', 'thim-elementor-kit' ) );
			}

			$update = update_term_meta( $menu_id, self::ENABLE_MEGA_MENU, $enable_maga_menu );

			if ( is_wp_error( $update ) ) {
				throw new \Exception( esc_html__( 'Cannot save settings', 'thim-elementor-kit' ) );
			}

			$response->status  = 'success';
			$response->message = esc_html__( 'Save setting successfully.', 'thim-elementor-kit' );
		} catch ( \Throwable $th ) {
			$response->message = $th->getMessage();
		}

		return rest_ensure_response( $response );
	}
}

Rest_API::instance();
