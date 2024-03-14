<?php
/**
 * Class for the FDP submenu items.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

class FDP_Submenu_Item {

	private $parent_slug;
	private $slug;
	private $title;
	private $capability;
	private $callback;
	private $priority;

	public function __construct( $fdp_parent_item_slug, $fdp_subitem_slug, $fdp_submenu_title, $capability, $callback, $priority = 10 ) {
		$this->parent_slug = $fdp_parent_item_slug;
		$this->slug        = 'eos_dp_' . ltrim( $fdp_subitem_slug, 'eos_dp_' );
		$this->title       = $fdp_submenu_title;
		if ( ! $capability ) {
			$capability = current_user_can( 'fdp_plugins_viewer' ) ? 'read' : 'activate_plugins';
		}
		$this->capability = apply_filters( 'eos_dp_settings_capability', $capability );
		$this->callback   = isset( $_GET['page'] ) && $fdp_subitem_slug === sanitize_text_field( $_GET['page'] ) ? $callback : '__return_false';
		add_filter(
			'fdp_pages',
			function( $arr ) {
				$arr[] = $this->slug;
				return array_filter( array_unique( $arr ) );
			},
			999999
		);
		$this->admin_menu();
		if ( ! in_array( $fdp_parent_item_slug, array( 'settings', 'tools', 'database', 'testing', 'help' ) ) ) {
			 add_filter( 'fdp_main_nav_menu_items', array( $this, 'menu_items_filter' ) );
			 add_action( 'fdp_submenu_item_' . sanitize_key( $this->parent_slug ), array( $this, 'subitem_output' ), absint( $priority ) );
		} else {
			add_action( 'fdp_' . sanitize_key( $this->parent_slug ) . '_submenu_end', array( $this, 'prenav_item' ) );
			add_filter( 'fdp_' . sanitize_key( $this->parent_slug ) . '_pages', array( $this, 'add_slug' ) );
		}
	}

	public function admin_menu() {
		add_submenu_page( 'fdp_hidden_menu', esc_html( $this->title ), esc_html( $this->title ), $this->capability, $this->slug, $this->callback, $this->priority );
	}

	public function menu_items_filter( $arr ) {
		$arr[ sanitize_key( $this->parent_slug ) ]['active_if'] = array_merge( $arr[ sanitize_key( $this->parent_slug ) ]['active_if'], array( sanitize_key( $this->slug ) ) );
		$arr[ sanitize_key( $this->parent_slug ) ]['subitems']  = array_merge( $arr[ sanitize_key( $this->parent_slug ) ]['subitems'], array( sanitize_key( $this->slug ) ) );
		return $arr;
	}

	public function prenav_item() {
		?>
  <li class="hover"><a href="<?php echo esc_url( admin_url( 'admin.php?page=' . esc_attr( $this->slug ) ) ); ?>"><?php echo esc_html( $this->title ); ?></a></li>
		<?php
	}

	public function add_slug( $arr ) {
		$arr[] = esc_attr( $this->slug );
		return $arr;
	}

	public function subitem_output() {
		?>
		<li data-section="<?php echo esc_attr( str_replace( '_', '-', $this->slug ) ); ?>" class="hover">
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'page'     => esc_attr( $this->slug ),
						'fdp_page' => 'true',
					),
					admin_url( 'admin.php' )
				)
			);
			?>
						"><?php echo esc_html( $this->title ); ?></a>
		</li>
		<?php
	}

}
