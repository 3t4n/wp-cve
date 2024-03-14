<?php
if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

global $submenu;

if ( isset( $submenu[ QuadMenu_Panel::$panel_slug ] ) ) {
  $welcome_menu_items = $submenu[ QuadMenu_Panel::$panel_slug ];
}

if ( is_array( $welcome_menu_items ) ) {
	?>
	<div class="wrap about-wrap quadmenu-wp-admin-header ">
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ( $welcome_menu_items as $welcome_menu_item ) {

				if ( strpos( $welcome_menu_item[2], '.php' ) !== false ) {
					continue;
				}
				?>
			<a href="<?php echo admin_url( 'admin.php?page=' . $welcome_menu_item[2] ); ?>" class="nav-tab 
								<?php
								if ( isset( $_REQUEST['page'] ) and $_REQUEST['page'] == $welcome_menu_item[2] ) {
									echo 'nav-tab-active';
								}
								?>
			"><?php echo $welcome_menu_item[0]; ?></a>
			<?php
			}
			?>
		</h2>
	</div>
	<?php
}

