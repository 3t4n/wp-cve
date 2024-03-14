<?php
namespace QuadLayers\QuadMenu\Backend;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Plugin;
use QuadLayers\QuadMenu\Backend\Settings;
use QuadLayers\QuadMenu\Configuration;

/**
 * Ajax Class ex QuadMenu_Ajax
 */
class Ajax extends Settings {

	public static $instance;

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 10 );

		// Ajax
		// -----------------------------------------------------------------
		// add_action('wp_ajax_quadmenu_change_nav_menu_theme', array($this, 'ajax_change_nav_menu_theme'));

		add_action( 'wp_ajax_quadmenu_add_nav_menu_item', array( $this, 'ajax_add_nav_menu_item' ) );

		add_action( 'wp_ajax_quadmenu_delete_nav_menu_item', array( $this, 'ajax_delete_nav_menu_item' ) );

		add_action( 'wp_ajax_quadmenu_update_nav_menu_item', array( $this, 'ajax_update_nav_menu_item_order' ) );

		add_action( 'wp_ajax_quadmenu_add_nav_menu_item_settings', array( $this, 'ajax_add_nav_menu_item_settings' ) );

		add_action( 'wp_ajax_quadmenu_save_nav_menu_item_settings', array( $this, 'ajax_save_nav_menu_item_settings' ) );

		// WP
		// ---------------------------------------------------------------------

		add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu_item' ), 10, 3 );

		// Nav Menu
		// -----------------------------------------------------------------

		add_meta_box( 'quadmenu_nav_menu_items', esc_html__( 'QuadMenu Items', 'quadmenu' ), array( $this, 'nav_items' ), 'nav-menus', 'side', 'high' );

		add_meta_box( 'quadmenu_nav_menu_archives', esc_html__( 'QuadMenu Archives', 'quadmenu' ), array( $this, 'nav_archives' ), 'nav-menus', 'side', 'high' );
	}

	public function enqueue() {

		global $pagenow;

		if ( $pagenow != 'nav-menus.php' ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'quadmenu-admin' );

		wp_enqueue_script( 'quadmenu-admin' );

		wp_localize_script(
			'quadmenu-admin',
			'quadmenu',
			array(
				'nonce'          => wp_create_nonce( 'quadmenu' ),
				'add_name'       => QUADMENU_PLUGIN_NAME,
				'add_background' => esc_html( 'Add Background', 'quadmenu' ),
				'add_to_column'  => esc_html( 'Add to Column', 'quadmenu' ),
			)
		);
	}

	public function nav_items() {
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		$items = Configuration::custom_nav_menu_items();
		?>
	<div id="posttype-quadmenu-custom" class="posttypediv">
		<div id="tabs-panel-quadmenu-custom" class="tabs-panel tabs-panel-active">
		<ul id ="quadmenu-custom-checklist" class="categorychecklist form-no-clear">
			<?php
			if ( is_object( $items ) ) :

				foreach ( $items as $id => $item ) :

					if ( ! isset( $item->depth ) || $item->depth > 0 ) {
						continue;
					}

					$classes = array();

					$classes[] = 'quadmenu-item';

					$classes[] = 'quadmenu-item-' . $id;

					if ( isset( $item->depth ) ) {
						$classes[] = 'quadmenu-item-depth-' . $item->depth;
					}

					$title = $id;

					if ( isset( $item->title ) ) {
						$title = $item->title;
					}

					$_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval( $_nav_menu_placeholder ) - 1 : -1;
					?>

				<li class="<?php echo join( ' ', array_map( 'sanitize_html_class', $classes ) ); ?>">
				<label class="menu-item-title">
					<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-label]" value="0">
					<?php echo esc_html( $title ); ?>
					<?php if ( ! empty( $item->desc ) ) : ?>
					<small><?php echo $item->desc; ?></small>
					<?php endif; ?>
				</label>
				<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-type]" value="custom">
				<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-title]" value="<?php echo esc_html( $title ); ?>">
				<input type="hidden" class="menu-item-quadmenu" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-quadmenu]" value="<?php echo esc_attr( $id ); ?>">
				</li>

				<?php endforeach; ?>

			<?php endif; ?>
		</ul>
		</div>
		<p class="button-controls">
		<span class="add-to-menu">
			<input type="submit" class="button-secondary submit-add-to-quadmenu right" value="<?php esc_html_e( 'Add to Menu' ); ?>" name="submit-posttype-quadmenu-custom" id="submit-posttype-quadmenu-custom">
			<span class="spinner"></span>
		</span>
		</p>
	</div>
		<?php
	}

	public function nav_archives() {

		$post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'object' );

		if ( $post_types ) :
			$items      = array();
			$loop_index = 999999;

			foreach ( $post_types as $post_type ) {
				$item = new \stdClass();
				$loop_index++;

				$item->object_id        = $loop_index;
				$item->db_id            = 0;
				$item->object           = $post_type->name;
				$item->menu_item_parent = 0;
				$item->type             = 'post_type_archive';
				$item->title            = sprintf( esc_html__( 'All %s', 'quadmenu' ), str_replace( 'All ', '', $post_type->labels->name ) );
				$item->url              = get_post_type_archive_link( $post_type->query_var );
				$item->target           = '';
				$item->attr_title       = '';
				$item->classes          = array();
				$item->xfn              = '';

				$items[] = $item;
			}

			$walker = new \Walker_Nav_Menu_Checklist( array() );
			?>

		<div id="posttype-archive" class="posttypediv">
		<div id="tabs-panel-posttype-archive" class="tabs-panel tabs-panel-active">
			<ul id="posttype-archive-checklist" class="categorychecklist form-no-clear">
			<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $items ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>
		</div>
		</div>

		<p class="button-controls">
		<span class="add-to-menu">
			<input type="submit"<?php disabled( 1, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_html_e( 'Add to Menu' ); ?>" name="add-posttype-archive-menu-item" id="submit-posttype-archive" />
			<span class="spinner"></span>
		</span>
		</p>

		<?php
		endif;
	}

	/*
	public function ajax_change_nav_menu_theme() {

		if (!check_ajax_referer('quadmenu', 'nonce', false)) {
		Plugin::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
		}

		$menu_id = absint($_REQUEST['menu_id']);

		$key = sanitize_text_field($_REQUEST['current_theme']);

		if ($menu_id > 0 && is_nav_menu($menu_id)) {

		$saved_themes = get_term_meta($menu_id, QUADMENU_DB_THEME);

		Plugin::send_json_error(json_encode($saved_themes));

		if (add_term_meta($menu_id, QUADMENU_DB_THEME, array_merge((array) $saved_themes, (array) $_REQUEST['quadmenu_themes']), true)) {
		Plugin::send_json_success();
		} else {
		Plugin::send_json_error(sprintf(esc_html__('Failed to add theme', 'quadmenu')));
		}
		}

		wp_die();
		}
	*/

	public function ajax_add_nav_menu_item() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		if ( empty( $_REQUEST['menu-item'] ) ) {
			Plugin::send_json_error( esc_html__( 'Undefined menu-item var.', 'quadmenu' ) );
			wp_die();
		}

		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		// For performance reasons, we omit some object properties from the checklist.
		// The following is a hacky way to restore them when adding non-custom items.

		$menu_items_data = array();

		foreach ( (array) $_REQUEST['menu-item'] as $menu_item_data ) {
			$menu_items_data[] = $menu_item_data;
		}

		$menu_id = absint( $_REQUEST['menu_id'] );

		$item_ids = $this->ajax_create_nav_menu_items( $menu_id, $menu_items_data );

		if ( is_wp_error( $item_ids ) ) {
		wp_die();
		}

		$menu_items = array();

		foreach ( (array) $item_ids as $menu_item_id ) {

			$menu_obj = Plugin::wp_setup_nav_menu_item( $menu_item_id );

			$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items

			$menu_items[] = $menu_obj;
		}

		$walker_class_name = apply_filters( 'quadmenu_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', $menu_id, $menu_obj, $menu_items );

		if ( ! class_exists( $walker_class_name ) ) {

			Plugin::send_json_error( sprintf( esc_html__( 'Class %s don\'t exists.', 'quadmenu' ), $walker_class_name ) );

			wp_die();
		}

		if ( ! empty( $menu_items ) ) {
			$args = array(
				'after'       => '',
				'before'      => '',
				'link_after'  => '',
				'link_before' => '',
				'walker'      => new $walker_class_name(),
				'doing_ajax'  => true,
			);

			$html = walk_nav_menu_tree( $menu_items, 0, (object) $args );

			if ( $html ) {
				Plugin::send_json_success( $html );
			} else {
				Plugin::send_json_error( sprintf( esc_html__( 'Failed to add %s', 'quadmenu' ), $items[ $menu_obj->quadmenu ]['label'] ) );
			}
		}

		wp_die();
	}

	public function ajax_create_nav_menu_items( $menu_id = 0, $menu_data = array() ) {

		$items_saved = array();

		if ( 0 == $menu_id || is_nav_menu( $menu_id ) ) {

			wp_cache_delete( "wp_get_nav_menu_items_{$menu_id}", 'quadmenu' );

			// Loop through all the menu items' POST values.
			foreach ( (array) $menu_data as $_possible_db_id => $_item_object_data ) {

				// If this possible menu item doesn't actually have a menu database ID yet.
				if ( empty( $_item_object_data['menu-item-db-id'] ) || ( 0 > $_possible_db_id ) || $_possible_db_id != $_item_object_data['menu-item-db-id'] ) {
					$_actual_db_id = 0;
				} else {
					$_actual_db_id = (int) $_item_object_data['menu-item-db-id'];
				}

				$args = array(
					'menu-item-status'          => ( isset( $_item_object_data['menu-item-status'] ) ? $_item_object_data['menu-item-status'] : '' ),
					'menu-item-db-id'           => ( isset( $_item_object_data['menu-item-db-id'] ) ? $_item_object_data['menu-item-db-id'] : '' ),
					'menu-item-object-id'       => ( isset( $_item_object_data['menu-item-object-id'] ) ? $_item_object_data['menu-item-object-id'] : '' ),
					'menu-item-object'          => ( isset( $_item_object_data['menu-item-object'] ) ? $_item_object_data['menu-item-object'] : '' ),
					'menu-item-type'            => ( isset( $_item_object_data['menu-item-type'] ) ? $_item_object_data['menu-item-type'] : '' ),
					'menu-item-title'           => ( isset( $_item_object_data['menu-item-title'] ) ? $_item_object_data['menu-item-title'] : '' ),
					'menu-item-url'             => ( isset( $_item_object_data['menu-item-url'] ) ? $_item_object_data['menu-item-url'] : '' ),
					'menu-item-parent-id'       => ( isset( $_item_object_data['menu-item-parent-id'] ) ? $_item_object_data['menu-item-parent-id'] : '' ),
					'menu-item-columns'         => ( isset( $_item_object_data['menu-item-columns'] ) ? $_item_object_data['menu-item-columns'] : $_item_object_data['menu-item-type'] ),
					'menu-item-quadmenu'        => ( isset( $_item_object_data['menu-item-quadmenu'] ) ? $_item_object_data['menu-item-quadmenu'] : $_item_object_data['menu-item-type'] ),
					'menu-item-quadmenu-widget' => ( isset( $_item_object_data['menu-item-quadmenu-widget'] ) ? $_item_object_data['menu-item-quadmenu-widget'] : '' ),
				);

				if ( $this->ajax_check_nav_menu_item_parent( $args ) ) {
					$items_saved[] = wp_update_nav_menu_item( $menu_id, $_actual_db_id, $args );
				}
			}
		}

		return $items_saved;
	}

	public function ajax_check_nav_menu_item_parent( $args ) {

		$items = Configuration::custom_nav_menu_items();

		if ( ! isset( $items->{$args['menu-item-quadmenu']} ) ) {
			Plugin::send_json_error( sprintf( esc_html__( 'Failed to add %s. Is not registered in QuadMenu Items.', 'quadmenu' ), $items->{$args['menu-item-quadmenu']} ) );
		}

		if ( isset( $items->{$args['menu-item-quadmenu']}->parent ) ) {

			if ( $parent_obj = Plugin::wp_setup_nav_menu_item( $args['menu-item-parent-id'] ) ) {

				if ( $items->{$args['menu-item-quadmenu']}->parent === 'main' ) {
					Plugin::send_json_error( sprintf( esc_html__( 'Failed to add %s. Only can be placed on the main menu.', 'quadmenu' ), $items->{$args['menu-item-quadmenu']}->label ) );
				}

				if ( ! in_array( sanitize_key( $parent_obj->quadmenu ), (array) $items->{$args['menu-item-quadmenu']}->parent ) ) {
					Plugin::send_json_error( sprintf( esc_html__( 'Failed to add %1$s as child of %2$s', 'quadmenu' ), $items->{$args['menu-item-quadmenu']}->label, $items->{$parent_obj->quadmenu}->label ) );
				}
			} elseif ( $items->{$args['menu-item-quadmenu']}->parent !== 'main' ) {
				Plugin::send_json_error( sprintf( esc_html__( 'Failed to add %1$s. Has to be child of %2$s', 'quadmenu' ), join( ' ', (array) $items->{$args['menu-item-quadmenu']}->label ), join( ' ', (array) $items->{$args['menu-item-quadmenu']}->parent ) ) );
			}
		}

		return true;
	}

	public function ajax_delete_nav_menu_item() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		$menu_id = absint( $_REQUEST['menu_id'] );

		$menu_item_id = absint( $_REQUEST['menu_item_id'] );

		$deleted = $this->delete_children_nav_menu_items( $menu_id, $menu_item_id );

		Plugin::send_json_success( sprintf( esc_html__( 'Removed items %s.', 'quadmenu' ), join( ' ', (array) $deleted ) ) );

		wp_die();
	}

	public function delete_children_nav_menu_items( $menu_id = 0, $menu_item_id = 0 ) {

		$deleted = array();

		if ( $menu_item_id > 0 && is_nav_menu_item( $menu_item_id ) ) {

			wp_cache_delete( "wp_get_nav_menu_items_{$menu_id}", 'quadmenu' );

			$delete_menu_items_id = $this->get_children_nav_menu_items( $menu_id, $menu_item_id );

			$delete_menu_items_id[] = array( 'id' => $menu_item_id );

			if ( is_array( $delete_menu_items_id ) && count( $delete_menu_items_id ) ) {
				foreach ( $delete_menu_items_id as $item ) {

					$id = absint( $item['id'] );

					do_action( 'quadmenu_delete_nav_menu_item', $id, $menu_id );

					if ( wp_delete_post( $id, true ) ) {

						$deleted[] = $id;
					}
				}
			}
		}

		return $deleted;
	}

	public function ajax_update_nav_menu_item_order() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		$menu_id = absint( $_REQUEST['menu_id'] );

		if ( isset( $_REQUEST['menu-item'] ) && is_array( $_REQUEST['menu-item'] ) ) {

			$updated = array();

			foreach ( $_REQUEST['menu-item'] as $menu_item_id => $menu_item_data ) {

				$menu_item_parent_id = absint( $menu_item_data['menu-item-parent-id'] );

				$menu_item_position = absint( $menu_item_data['menu-item-position'] );

				update_post_meta( $menu_item_id, '_menu_item_menu_item_parent', $menu_item_parent_id );

				if ( $id = wp_update_post(
					array(
						'ID'         => $menu_item_id,
						'menu_order' => $menu_item_position,
					)
				) ) {
					$updated[] = $menu_item_id;
				}
			}

			wp_cache_delete( "wp_get_nav_menu_items_{$menu_id}", 'quadmenu' );

			Plugin::send_json_success( sprintf( esc_html__( 'Updated items %s.', 'quadmenu' ), join( ' ', (array) $updated ) ) );
		} else {

			Plugin::send_json_error( esc_html__( 'Undefined menu-item var.', 'quadmenu' ) );
		}

		wp_die();
	}

	public function ajax_add_nav_menu_item_settings() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		$menu_id = absint( $_REQUEST['menu_id'] );

		$menu_item_id = absint( $_REQUEST['menu_item_id'] );

		$menu_item_depth = absint( $_REQUEST['menu_item_depth'] );

		$menu_obj = Plugin::wp_setup_nav_menu_item( $menu_item_id );

		ob_start();
		?>

	<div id="quadmenu-settings" class="quadmenu-item-depth-<?php echo esc_attr( $menu_item_depth ); ?> quadmenu-item-<?php echo esc_attr( $menu_obj->quadmenu ); ?>">
		<div class="quadmenu-settings-header">    
		<div class="quadmenu_saving"><?php esc_html_e( 'Saving', 'quadmenu' ); ?></div>                
		<button type="button" class="quadmenu_close button-secondary" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h3 id="quadmenu-title-<?php echo esc_attr( $menu_obj->ID ); ?>" class="quadmenu-title"><i class="<?php echo esc_attr( $menu_obj->icon ); ?>"></i><span><?php esc_html_e( $menu_obj->title ); ?></span></h3>
		</div>
		<div class="quadmenu-settings-body clearfix">
	<?php do_action( 'quadmenu_modal_panels', $menu_item_depth, $menu_obj, $menu_id ); ?>
		</div>
		<div class="clearfix"></div>
		<div class="quadmenu-settings-modal-footer">
		</div>
		<div class="clearfix"></div>
	</div>
	<?php
		if ( $settings = ob_get_clean() ) {
			Plugin::send_json_success( $settings );
		} else {
			Plugin::send_json_error( sprintf( esc_html__( 'Can\'t load settings of %s.', 'quadmenu' ), $menu_obj->ID ) );
		}

		wp_die();
	}

	public function ajax_save_nav_menu_item_settings() {

		if ( ! check_ajax_referer( 'quadmenu', 'nonce', false ) ) {
			Plugin::send_json_error( esc_html__( 'Please reload page.', 'quadmenu' ) );
		}

		if ( $menu_item_id = absint( $_REQUEST['menu_item_id'] ) ) {
			wp_cache_delete( "wp_setup_nav_menu_item_{$menu_item_id}", 'quadmenu' );
		}

		$menu_id = absint( $_REQUEST['menu_id'] );

		if ( $menu_item_id > 0 ) {

			if ( isset( $_REQUEST['menu-item-quadmenu-settings'] ) && is_array( $_REQUEST['menu-item-quadmenu-settings'] ) ) {

				$saved_settings = array_filter( (array) get_post_meta( $menu_item_id, QUADMENU_DB_ITEM, true ) );

				if ( is_array( $saved_settings ) && $updated_settings = array_merge( $saved_settings, $_REQUEST['menu-item-quadmenu-settings'] ) ) {
					update_post_meta( $menu_item_id, QUADMENU_DB_ITEM, $updated_settings );
				}
			}

			if ( isset( $_REQUEST['menu-item-url'] ) ) {
				update_post_meta( $menu_item_id, '_menu_item_url', esc_url_raw( $_REQUEST['menu-item-url'] ) );
			}
			if ( isset( $_REQUEST['menu-item-parent-id'] ) ) {
				update_post_meta( $menu_item_id, '_menu_item_menu_item_parent', strval( (int) $_REQUEST['menu-item-parent-id'] ) );
			}
			if ( isset( $_REQUEST['menu-item-target'] ) ) {
				update_post_meta( $menu_item_id, '_menu_item_target', sanitize_key( $_REQUEST['menu-item-target'] ) );
			}
			if ( isset( $_REQUEST['menu-item-classes'] ) ) {
				$_REQUEST['menu-item-classes'] = array_map( 'sanitize_html_class', explode( ' ', $_REQUEST['menu-item-classes'] ) );
				update_post_meta( $menu_item_id, '_menu_item_classes', $_REQUEST['menu-item-classes'] );
			}
			if ( isset( $_REQUEST['menu-item-xfn'] ) ) {
				$_REQUEST['menu-item-xfn'] = join( ' ', array_map( 'sanitize_html_class', explode( ' ', $_REQUEST['menu-item-xfn'] ) ) );
				update_post_meta( $menu_item_id, '_menu_item_xfn', $_REQUEST['menu-item-xfn'] );
			}

			if ( isset( $_REQUEST['menu-item-title'] ) || isset( $_REQUEST['menu-item-attr-title'] ) || isset( $_REQUEST['menu-item-description'] ) ) {

				$post = array(
					'ID' => $menu_item_id,
				);

				if ( isset( $_REQUEST['menu-item-title'] ) ) {
					$post['post_title'] = apply_filters( 'the_title', $_REQUEST['menu-item-title'], $menu_item_id );
				}
				if ( isset( $_REQUEST['menu-item-attr-title'] ) ) {
					$post['post_excerpt'] = wp_kses_post( $_REQUEST['menu-item-attr-title'] );
				}
				if ( isset( $_REQUEST['menu-item-description'] ) ) {
					$post['post_content'] = wp_kses_post( $_REQUEST['menu-item-description'] );
				}

				// 'post_type' => 'nav_menu_item',
				// 'post_parent' => $original_parent,
				// 'menu_order' => $args['menu-item-position'],

				if ( ! wp_update_post( $post ) ) {

					Plugin::send_json_error( sprintf( esc_html__( 'Can\'t save settings of %s.', 'quadmenu' ), $menu_item_id ) );

					wp_die();
				}
			}

			Plugin::send_json_success( sprintf( esc_html__( 'Saved settings of %s.', 'quadmenu' ), $menu_item_id ) );

			wp_die();
		}

		Plugin::send_json_error( sprintf( esc_html__( 'Can\'t save settings of %s.', 'quadmenu' ), $menu_item_id ) );

		wp_die();
	}

	public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {

		if ( ! empty( $args['menu-item-quadmenu'] ) ) {

			$saved_settings = array_filter( (array) get_post_meta( $menu_item_db_id, QUADMENU_DB_ITEM, true ) );

			$saved_settings['quadmenu'] = $args['menu-item-quadmenu'];

			if ( ! empty( $args['menu-item-columns'] ) ) {
				$saved_settings['columns'] = $args['menu-item-columns'];
			}

			update_post_meta( $menu_item_db_id, QUADMENU_DB_ITEM, $saved_settings );
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
