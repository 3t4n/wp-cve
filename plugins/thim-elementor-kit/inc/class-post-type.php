<?php
/**
 * Register Post Type
 *
 * @author Nhamdv <daonham95@gmail.com>
 */

namespace Thim_EL_Kit;

use Thim_EL_Kit\Shortcode;
use Thim_EL_Kit\Modules\Cache;

class Custom_Post_Type {
	use SingletonTrait;

	const CPT = 'thim_elementor_kit';

	const TYPE = 'thim_elementor_type';

	const ADMIN_MENU_SLUG = 'edit.php?post_type=' . self::CPT;

	public $tabs = array();

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'views_edit-' . self::CPT, array( $this, 'admin_tabs' ) );
		add_action( 'current_screen', array( $this, 'add_admin_header' ) );
		add_action( 'manage_' . self::CPT . '_posts_columns', array( $this, 'admin_columns_headers' ) );
		add_action( 'manage_' . self::CPT . '_posts_custom_column', array( $this, 'admin_columns_content' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'register_tabs' ) );
		add_action( 'parse_query', array( $this, 'admin_post_filter' ) );
		add_filter( 'single_template', array( $this, 'load_canvas_template' ) );
		add_action( 'admin_menu', array( $this, 'change_admin_menu' ), 900 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 800 );
		add_action( 'wp_trash_post', array( $this, 'remove_post_from_cache' ) );
		add_action( 'untrashed_post', array( $this, 'revert_post_to_cache' ) );
		add_action( 'save_post_' . self::CPT, array( $this, 'regenerate_cache_save_post' ) );
	}

	public function register_tabs() {
		$this->tabs = apply_filters( 'thim_ekit/post_type/register_tabs', array() );
	}

	/** Add html header ( Add new Button ) use for Popup */
	public function add_admin_header() {
		$current_screen = get_current_screen();

		if ( ! $current_screen || ! strstr( $current_screen->id, self::CPT ) ) {
			return;
		}

		add_action(
			'in_admin_header',
			function () {
				echo '<div id="thim-elementor-header"></div>';
			}
		);
	}

	/** Add tabs in Admin */
	public function admin_tabs( $views ) {
		$all_templates = array(
			'templates' => array(
				'name' => esc_html__( 'All Templates', 'thim-elementor-kit' ),
				'url'  => admin_url( 'edit.php?post_type=' . self::CPT ),
			),
		);

		$tabs = $all_templates + $this->tabs;
		?>

		<div id="thim-elementor-tabs-wrapper" class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $tab_id => $tab ) {
				$active_tab = ( ( isset( $_GET[self::TYPE] ) && $tab_id === sanitize_text_field( wp_unslash( $_GET[self::TYPE] ) ) ) || ( ! isset( $_GET[self::TYPE] ) && 'templates' === $tab_id ) ) ? $tab_id : '';

				$active = ( $active_tab === $tab_id ) ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab['url'] ) . '" class="nav-tab' . esc_attr( $active ) . '">';
				echo esc_html( $tab['name'] );
				echo '</a>';
			}
			?>
		</div>

		<?php
		return $views;
	}

	/** Add Column header: Type to table */
	public function admin_columns_headers( array $columns ): array {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['thim-ekit-type']       = esc_html__( 'Type', 'thim-elementor-kit' );
		$columns['thim-ekit-conditions'] = esc_html__( 'Conditions', 'thim-elementor-kit' );
		$columns['thim-ekit-shortcode']  = esc_html__( 'Shortcode', 'thim-elementor-kit' );
		$columns['date']                 = $date_column;

		return apply_filters( 'thim_ekit/post_type/admin_columns_headers', $columns );
	}

	/** Add Tab content in table column Type */
	public function admin_columns_content( string $column_name, int $post_id ) {
		$type = get_post_meta( $post_id, 'thim_elementor_type', true );

		switch ( $column_name ) {
			case 'thim-ekit-type':
				$item_post_type = '';
				if ( $type == 'loop_item' ) {
					$post_type     = get_post_meta( $post_id, 'thim_loop_item_post_type', true );
					$get_post_type = get_post_type_object( $post_type );
					if ( $get_post_type ) {
						$item_post_type = $post_type ? ' - ' . $get_post_type->label : '';
					}
				}

				echo ! empty( $this->tabs[$type] ) ? sprintf( '<a href="%1s">%2s</a>', esc_url( $this->tabs[$type]['url'] ), esc_html( $this->tabs[$type]['name'] . $item_post_type )  ) : esc_html__( 'Unsupported', 'thim-elementor-kit' );

				break;

			case 'thim-ekit-conditions':
				if ( ! empty( $type ) && $type !== 'loop_item' ) {
					$args = array(
						'post_type' => self::CPT,
						self::TYPE  => $type,
					);

					$url = add_query_arg( $args, admin_url( 'edit.php' ) );

					echo '<a href="' . esc_url( $url ) . '#edit_condition_' . absint( $post_id ) . '" class="button button-primary button-small">' . esc_html__( 'EDIT CONDITIONS' ) . '</a>';
				}
				break;

			case 'thim-ekit-shortcode':
				$shortcode = sprintf( '[%s id="%d"]', Shortcode::SHORTCODE_NAME, $post_id );
				?>
				<span class="thim-ekit-shortcode-column">
					<input type="text" onfocus="this.select();" readonly="readonly"
						   value="<?php echo esc_attr( $shortcode ); ?>">
				</span>
				<?php
				break;
		}

	}

	public function admin_post_filter( $query ) {
		if ( ! $this->is_current_screen() || ! empty( $query->query_vars['meta_key'] ) ) {
			return;
		}

		$current_tab = ! empty( $_REQUEST[self::TYPE] ) ? sanitize_text_field( wp_unslash( $_REQUEST[self::TYPE] ) ) : '';

		if ( isset( $query->query_vars[self::TYPE] ) && '-1' === $query->query_vars[self::TYPE] ) {
			unset( $query->query_vars[self::TYPE] );
		}

		if ( empty( $current_tab ) ) {
			return;
		}

		$query->query_vars['meta_key']     = 'thim_elementor_type';
		$query->query_vars['meta_value']   = $current_tab;
		$query->query_vars['meta_compare'] = '=';
	}

	public function is_current_screen() {
		global $pagenow, $typenow;

		return 'edit.php' === $pagenow && self::CPT === $typenow;
	}

	/**
	 * Change link Add new in menu.
	 *
	 * @return void
	 */
	public function change_admin_menu() {
		global $submenu;

		if ( ! isset( $submenu[self::ADMIN_MENU_SLUG] ) ) {
			return;
		}
		$template_submenu = &$submenu[self::ADMIN_MENU_SLUG];

		// If current use can 'Add New' - move the menu to end, and add the '#add_new' anchor.
		if ( isset( $template_submenu[10][2] ) ) {
			$template_submenu[700] = $template_submenu[10];
			unset( $template_submenu[10] );
			$template_submenu[700][2] = admin_url( self::ADMIN_MENU_SLUG . '#add_new' );
		}
	}

	/**
	 *  Single template function which will choose our template
	 *
	 * @param [type] $single_template
	 *
	 * @return void
	 */
	public function load_canvas_template( $single_template ) {
		global $post;

		if ( $post->post_type === self::CPT ) {
			$elementor_modules  = \Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' );
			$elementor_template = $elementor_modules->get_template_path( $elementor_modules::TEMPLATE_CANVAS );
			$elementor_template = apply_filters( 'thim_ekit/post_type/single_template/override', $elementor_template, $post );

			if ( file_exists( $elementor_template ) ) {
				return $elementor_template;
			}
		}

		return $single_template;
	}

	public function register_post_type() {
		$labels = array(
			'name'               => esc_html__( 'Thim Elementor Templates', 'thim-elementor-kit' ),
			'singular_name'      => esc_html__( 'Thim Elementor', 'thim-elementor-kit' ),
			'add_new'            => esc_html__( 'Add New', 'thim-elementor-kit' ),
			'add_new_item'       => esc_html__( 'Add New Template', 'thim-elementor-kit' ),
			'edit_item'          => esc_html__( 'Edit Template', 'thim-elementor-kit' ),
			'new_item'           => esc_html__( 'New Template', 'thim-elementor-kit' ),
			'all_items'          => esc_html__( 'All Templates', 'thim-elementor-kit' ),
			'view_item'          => esc_html__( 'View Template', 'thim-elementor-kit' ),
			'search_items'       => esc_html__( 'Search Template', 'thim-elementor-kit' ),
			'not_found'          => esc_html__( 'No Templates found', 'thim-elementor-kit' ),
			'not_found_in_trash' => esc_html__( 'No Templates found in Trash', 'thim-elementor-kit' ),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__( 'Thim Elementor', 'thim-elementor-kit' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-admin-page',
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'elementor' ),
		);

		$args = apply_filters( 'thim-ekit/register_post_type_args', $args );

		register_post_type( self::CPT, $args );
	}

	public function add_admin_menu() {
		add_submenu_page(
			Dashboard::MENU_SLUG,
			esc_html__( 'All Templates', 'thim-elementor-kit' ),
			esc_html__( 'All Templates', 'thim-elementor-kit' ),
			'manage_options',
			self::ADMIN_MENU_SLUG
		);

		add_submenu_page(
			Dashboard::MENU_SLUG,
			esc_html__( 'Add New Template', 'thim-elementor-kit' ),
			esc_html__( 'Add New Template', 'thim-elementor-kit' ),
			'manage_options',
			self::ADMIN_MENU_SLUG . '#add_new'
		);
	}

	public function revert_post_to_cache( $post_id ) {
		$post_id    = absint( $post_id );
		$type       = get_post_meta( $post_id, 'thim_elementor_type', true );
		$conditions = get_post_meta( $post_id, 'thim_ekits_conditions', true );

		$cache = Cache::instance()->add( $type, $conditions, $post_id )->save();
	}

	public function remove_post_from_cache( $post_id ) {
		$post_id = absint( $post_id );

		$cache = Cache::instance()->remove( $post_id )->save();
	}

	public function regenerate_cache_save_post() {
		Cache::instance()->regenerate();
	}
}

Custom_Post_Type::instance();
