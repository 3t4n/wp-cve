<div class="wrap about-wrap full-width-layout qlwrap">

	<h1><?php echo esc_html( QUADMENU_PLUGIN_NAME ); ?></h1>

	<p class="about-text"><?php printf( esc_html__( 'Hello we\'re QuadLayers! We will do our absolute best to support you and fix all the issues.', 'quadmenu' ), QUADMENU_PLUGIN_NAME ); ?></p>

	<p class="about-text">
	<?php printf( '<a href="%s" target="_blank">%s</a>', QUADMENU_PURCHASE_URL, esc_html__( 'Purchase', 'quadmenu' ) ); ?></a> | 
	<?php printf( '<a href="%s" target="_blank">%s</a>', QUADMENU_DEMO_URL, esc_html__( 'Demo', 'quadmenu' ) ); ?></a> | 
	<?php printf( '<a href="%s" target="_blank">%s</a>', QUADMENU_DOCUMENTATION_URL, esc_html__( 'Documentation', 'quadmenu' ) ); ?></a>
	</p>

	<?php
	printf(
		'<a href="%s" target="_blank"><div style="
				background: #006bff url(%s) no-repeat;
				background-position: top center;
				background-size: 130px 130px;
				color: #fff;
				font-size: 14px;
				text-align: center;
				font-weight: 600;
				margin: 5px 0 0;
				padding-top: 120px;
				height: 40px;
				display: inline-block;
				width: 140px;
				" class="wp-badge">%s</div></a>',
		'https://quadlayers.com/?utm_source=quadmenu_admin',
		plugins_url( '/assets/backend/img/quadlayers.jpg', QUADMENU_PLUGIN_FILE ),
		esc_html__( 'QuadLayers', 'quadmenu' )
	);
	?>

</div>
<?php
use QuadLayers\QuadMenu\Panel;
if ( isset( $submenu[ Panel::$panel_slug ] ) ) {
	if ( is_array( $submenu[ Panel::$panel_slug ] ) ) {
		?>
	<div class="wrap about-wrap full-width-layout qlwrap">
		<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $submenu[ Panel::$panel_slug ] as $tab ) {
			if ( strpos( $tab[2], '.php' ) !== false ) {
				continue;
			}
			?>
			<a href="<?php echo admin_url( 'admin.php?page=' . esc_attr( $tab[2] ) ); ?>" class="nav-tab<?php echo ( isset( $_GET['page'] ) && $_GET['page'] == $tab[2] ) ? ' nav-tab-active' : ''; ?>"><?php echo wp_kses_post( $tab[0] ); ?></a>
		<?php
		}
		?>
		</h2>
	</div>
		<?php
	}
}
