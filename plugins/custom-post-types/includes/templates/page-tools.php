<?php

defined( 'ABSPATH' ) || exit;

$pages = array(
	'export'  => __( 'Export', 'custom-post-types' ),
	'import'  => __( 'Import', 'custom-post-types' ),
//	'migrate' => __( 'Migrate', 'custom-post-types' ),
);

$requested_page = ! empty( $_GET['action'] ) && array_key_exists( $_GET['action'], $pages ) ? $_GET['action'] : 'main';

$page_url = function ( $slug ) use ( $pages ) {
	if ( ! array_key_exists( $slug, $pages ) && 'main' !== $slug ) {
		return false;
	}
	$page_url = admin_url( '/edit.php?post_type=' . CPT_UI_PREFIX . '&page=tools' );
	if ( 'main' !== $slug ) {
		$page_url .= '&action=' . $slug;
	}

	return $page_url;
};

$is_current = function ( $current ) use ( $requested_page ) {
	return $current == $requested_page; //phpcs:ignore Universal.Operators.StrictComparisons
};
?>
<nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
	<?php
	printf(
		'<a href="%1$s" class="nav-tab %2$s" title="%3$s" aria-label="%3$s">%3$s</a>',
		$page_url( 'main' ),
		$is_current( 'main' ) ? 'nav-tab-active' : '',
		__( 'Infos', 'custom-post-types' )
	);
	foreach ( $pages as $slug => $label ) {
		printf(
			'<a href="%1$s" class="nav-tab %2$s" title="%3$s" aria-label="%3$s">%3$s</a>',
			$page_url( $slug ),
			$is_current( $slug ) ? 'nav-tab-active' : '',
			$label
		);
	}
	printf(
		'<a href="%1$s" class="nav-tab" target="_blank" title="%2$s" aria-label="%2$s">%2$s <span class="dashicons dashicons-external" style="text-decoration: none;"></span></a>',
		CPT_PLUGIN_DOC_URL,
		__( 'Documentation', 'custom-post-types' )
	);
	?>
</nav>
<div class="cpt-tools-page-content page-<?php echo $requested_page; ?>">
	<?php
	switch ( $requested_page ) {
		case 'import':
			echo '<p>' . __( 'This tool allows you to <u>import</u> all plugin settings (post types, taxonomies, field groups and templates).', 'custom-post-types' ) . '</p>';
			if ( ! has_action( 'cpt_import_page' ) ) {
				echo cpt_utils()->get_pro_banner();
			} else {
				do_action( 'cpt_import_page' );
			}
			break;
		case 'export':
			echo '<p>' . __( 'This tool allows you to <u>export</u> all plugin settings (post types, taxonomies, field groups and templates).', 'custom-post-types' ) . '</p>';
			if ( ! has_action( 'cpt_export_page' ) ) {
				echo cpt_utils()->get_pro_banner();
			} else {
				do_action( 'cpt_export_page' );
			}
			break;
//		case 'migrate':
//			echo '<p>' . __( 'This tool allows you to <u>migrate</u> from other plugins (post types, taxonomies, field groups and templates).', 'custom-post-types' ) . '</p>';
//			require_once CPT_PATH . '/includes/templates/page-migrate.php';
//			break;
		default:
			?>
			<p>
				<?php _e( 'The purpose of the plugin is to <u>extend the features of the CMS</u> by adding custom content types without writing code or knowledge of development languages.', 'custom-post-types' ); ?>
			</p>
			<p>
				<?php _e( 'This plugin is <strong>FREE</strong> and the developer guarantees frequent updates (for security and compatibility), if this plugin is useful <u>please support the development</u>.', 'custom-post-types' ); ?>
			</p>
			<?php do_action( 'custom-post-types-pro_license_form' ); ?>
			<div class="cpt-container">
				<div class="cpt-row">
					<div class="cpt-col-3">
						<h2><?php _e( 'Support the project', 'custom-post-types' ); ?></h2>
						<?php
						if ( ! cpt_utils()->is_pro_version_active() ) {
							printf(
								'<p><a href="%1$s" class="button button-primary button-hero" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
								CPT_PLUGIN_URL,
								__( 'Get PRO version', 'custom-post-types' )
							);
						}
						printf(
							'<p><a href="%1$s" class="button button-primary" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
							CPT_PLUGIN_DONATE_URL,
							__( 'Make a Donation', 'custom-post-types' )
						);
						printf(
							'<p><a href="%1$s" class="button button-primary" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
							CPT_PLUGIN_REVIEW_URL,
							__( 'Write a Review', 'custom-post-types' )
						);
						?>
					</div>
					<div class="cpt-col-3">
						<h2><?php _e( 'Other infos', 'custom-post-types' ); ?></h2>
						<?php
						printf(
							'<p><a href="%1$s" class="button button-secondary" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
							CPT_PLUGIN_WPORG_URL,
							__( 'WordPress.org Plugin Page', 'custom-post-types' )
						);
						printf(
							'<p><a href="%1$s" class="button button-secondary" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
							CPT_PLUGIN_SUPPORT_URL,
							__( 'Official Support Page', 'custom-post-types' )
						);
						printf(
							'<p><a href="%1$s" class="button button-secondary" target="_blank" title="%2$s" aria-label="%2$s">%2$s</a></p>',
							CPT_PLUGIN_DOC_URL,
							__( 'Plugin Documentation', 'custom-post-types' )
						);
						?>
					</div>
					<div class="cpt-col-3">
						<h2><?php _e( 'Tools', 'custom-post-types' ); ?></h2>
						<?php

						foreach ( $pages as $slug => $label ) {
							printf(
								'<p><a href="%1$s" class="button button-secondary" title="%2$s" aria-label="%2$s">%2$s</a></p>',
								$page_url( $slug ),
								$label
							);
						}
						?>
					</div>
				</div>
			</div>
			<?php
			break;
	}
	?>
</div>
