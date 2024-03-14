<?php
namespace QuadLayers\QuadMenu\Backend\Walker;

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

use QuadLayers\QuadMenu\Backend\Settings;
use QuadLayers\QuadMenu\Plugin;

/**
 * Nav_Menu_Columns Class ex QuadMenu_Nav_Menu_Column
 */
class Nav_Menu_Columns extends Settings {

	public static $instance;

	public function __construct() {
		add_filter( 'quadmenu_edit_nav_menu_walker', array( $this, 'add_nav_menu_item_column' ), 10, 3 );
	}

	function add_nav_menu_item_column( $walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null ) {

		if ( ! empty( $menu_obj->menu_item_parent ) && ! empty( $menu_obj->quadmenu ) && $menu_obj->quadmenu === 'column' ) {
			return __CLASS__;
		}

		return $walker_class_name;
	}

	public function walk( $elements, $max_depth ) {

		$output = '';

		foreach ( $elements as $e ) {

			$output .= $this->column( $e );
		}

		return $output;

		wp_die();
	}

	public function column( $column_obj, $menu_id = 0 ) {

		ob_start();
		?>
		<div id="column_<?php echo esc_attr( $column_obj->ID ); ?>" class="quadmenu-column quadmenu-item-depth-2 <?php echo join( ' ', array_map( 'sanitize_html_class', $column_obj->columns ) ); ?>" data-columns="<?php echo join( ' ', array_map( 'sanitize_html_class', $column_obj->columns ) ); ?>" data-menu_item_id="<?php echo esc_attr( $column_obj->ID ); ?>">
			<div class="inner">
				<div class="action-top clearfix">
					<div class="actions">
						<!--<a class="option contract" title="<?php echo esc_attr( esc_html__( 'Contract', 'quadmenu' ) ); ?>"></a>
						<a class="option expand" title="<?php echo esc_attr( esc_html__( 'Expand', 'quadmenu' ) ); ?>"></a>-->
						<a class="option edit" title="<?php esc_html_e( 'Edit', 'quadmenu' ); ?>"></a>
						<a class="option remove" title="<?php esc_html_e( 'Remove', 'quadmenu' ); ?>"></a>
						<span class="spinner"></span>
					</div>
				</div>
				<div class="settings">
					<?php echo $this->form( $column_obj, 1, array( 'columns' ) ); ?>                       
				</div>
				<ul id="quadmenu-column-items-<?php echo esc_attr( $column_obj->ID ); ?>" class="items add-quadmenu-column-item sortable-area" data-sortable-items=".quadmenu-column-item" data-sortable-handle=".action-top" data-sortable-connect=".items" data-menu_item_parent_id="<?php echo esc_attr( $column_obj->ID ); ?>">     
					<?php
					$items = $this->get_children_nav_menu_items( $menu_id, $column_obj->ID );

					if ( is_array( $items ) && count( $items ) ) :
						foreach ( $items as $item ) :

							$menu_obj = Plugin::wp_setup_nav_menu_item( $item['id'] );

							$walker_class_name = apply_filters( 'quadmenu_edit_nav_menu_walker', 'Walker_Nav_Menu_Edit', null, $menu_obj, null );

							require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

							if ( class_exists( $walker_class_name ) ) {

								$args = array(
									'after'       => '',
									'before'      => '',
									'link_after'  => '',
									'link_before' => '',
									'walker'      => new $walker_class_name(),
								);

								echo walk_nav_menu_tree( array( $menu_obj ), 0, (object) $args );
							}

						endforeach;
					endif;
					?>
				</ul>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function columns( $menu_obj, $menu_id = 0 ) {

		$columns = $this->get_children_nav_menu_items( $menu_id, $menu_obj->ID );

		ob_start();

		$w4 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-4' ),
			),
		);

		$w12 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-12' ),
			),
		);

		$w6w6 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-6' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-6' ),
			),
		);

		$w4w4w4 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-4' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-4' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-4' ),
			),
		);

		$w3w3w3w3 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
		);

		$w2w2w2w2w2w2 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
		);

		$w4w8 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-4' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-8' ),
			),
		);

		$w3w6w3 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-6' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-3' ),
			),
		);

		$w2w10 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-10' ),
			),
		);

		$w2w8w2 = array(
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-8' ),
			),
			array(
				'quadmenu' => 'column',
				'columns'  => array( 'col-xs-12', 'col-sm-2' ),
			),
		);
		?>
		<div id="columns_<?php echo esc_attr( $menu_obj->ID ); ?>" class="quadmenu-columns sortable-area row" data-drop-area="drop-column" data-sortable-items=".quadmenu-column" data-sortable-handle=".action-top" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
			<ul role="tablist">
				<span class="spinner"></span>
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w4 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
					</a>
				</li>
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w12 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-auto">
						</div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w6w6 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w4w4w4 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w3w3w3w3 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w2w2w2w2w2w2 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
						<div class="col col-auto"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w4w8 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-4"></div>
						<div class="col col-8"></div>
					</a>
				</li>
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w3w6w3 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-3"></div>
						<div class="col col-6"></div>
						<div class="col col-3"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w2w10 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-2"></div>
						<div class="col col-10"></div>
					</a>
				</li>                               
				<li>
					<a href="#" class="submit-add-to-quadmenu-column" data-menu_item_type="custom" data-menu_item_quadmenu="<?php echo htmlspecialchars( json_encode( $w2w8w2 ), ENT_QUOTES, 'UTF-8' ); ?>" data-menu_item_url="#column" data-menu_item_title="<?php esc_html_e( 'Column', 'quadmenu' ); ?>" data-menu_item_parent=".quadmenu-columns" data-menu_item_parent_id="<?php echo esc_attr( $menu_obj->ID ); ?>">
						<div class="col col-2"></div>
						<div class="col col-8"></div>
						<div class="col col-2"></div>
					</a>
				</li>
			</ul>
			<?php
			if ( is_array( $columns ) && count( $columns ) ) :
				foreach ( $columns as $column ) :

					$column_obj = get_post( $column['id'] );

					$column_obj = Plugin::wp_setup_nav_menu_item( $column['id'] );

					if ( ! isset( $column_obj->quadmenu ) || $column_obj->quadmenu != 'column' ) {
						continue;
					}

					echo $this->column( $column_obj, $menu_id );

				endforeach;
			endif;
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

