<?php

/**
 * Plugin Settings.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
if ( ! in_array( $active_tab, array_keys( $this->tabs ) ) ) {
	$active_tab = 'general';
}

$active_section = isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';

$sections = array();
foreach ( $this->sections as $section ) {
	$tab = $section['tab'];
	
	if ( ! isset( $sections[ $tab ] ) ) {
		$sections[ $tab ] = array();
	}

	$slug = $section['slug'];
	if ( ! isset( $sections[ $tab ][ $slug ] ) ) {
		$sections[ $tab ][ $slug ] = $section;
	}	
}
?>

<div id="acadp-settings" class="acadp acadp-settings wrap">
	<div class="acadp-flex acadp-flex-col acadp-gap-4">
		<h1>
			<?php esc_html_e( 'Plugin Settings', 'advanced-classifieds-and-directory-pro' ); ?>
		</h1>
		
		<?php settings_errors(); ?>
		
		<h2 class="nav-tab-wrapper wp-clearfix">
			<?php
			foreach ( $this->tabs as $slug => $title ) {
				$classes = array( 'nav-tab' );
				if ( $active_tab == $slug ) $classes[] = 'nav-tab-active';			
				
				$section = '';
				foreach ( $sections[ $slug ] as $key => $value ) {
					$section = $key;

					if ( $active_tab == $slug && empty( $active_section ) ) {
						$active_section = $section;
					}

					break;
				}

				$url = add_query_arg( 
					array( 
						'tab'     => $slug, 
						'section' => $section 
					), 
					admin_url( 'admin.php?page=acadp_settings' ) 
				);
				
				printf( 
					'<a href="%s" class="%s">%s</a>',
					esc_url( $url ),
					implode( ' ', $classes ),
					esc_html( $title ) 
				);
			}
			?>
		</h2>

		<?php	
		$section_links = array();

		foreach ( $sections[ $active_tab ] as $section ) {
			$url = add_query_arg( 
				array( 
					'tab'     => $active_tab, 
					'section' => $section['slug']
				), 
				admin_url( 'admin.php?page=acadp_settings' ) 
			);

			$section_links[] = sprintf( 
				'<a href="%s" class="%s">%s</a>',
				esc_url( $url ),
				( $section['slug'] == $active_section ? 'current' : '' ),			
				esc_html( $section['title'] )
			);
		}

		if ( count( $section_links ) > 1 ) : ?>
			<ul class="acadp-m-0 subsubsub">
				<li><?php echo implode( ' | </li><li>', $section_links ); ?></li>
			</ul>
		<?php endif; ?>

		<form method="post" action="options.php"> 
			<?php
			$page_hook = $active_section;

			settings_fields( $page_hook );
			do_settings_sections( $page_hook );
					
			submit_button( '', 'button-primary button-hero' );
			?>
		</form>
	</div>
</div>