<?php
/**
 * Genesis view for metabox content.
 *
 * @package genesis-simple-sidebars
 */

$sidebars = Genesis_Simple_Sidebars()->core->get_sidebars();
global $wp_registered_sidebars;
wp_nonce_field( 'genesis-simple-sidebars-save-entry', 'genesis-simple-sidebars-save-entry-nonce' );

if ( is_registered_sidebar( 'header-right' ) ) : ?>
<p>
	<label class="howto" for="genesis_simple_sidebars[_ss_header]"><span><?php echo esc_attr( $wp_registered_sidebars['header-right']['name'] ); ?><span></label>
	<select name="genesis_simple_sidebars[_ss_header]" id="genesis_simple_sidebars[_ss_header]">
		<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
		<?php
		foreach ( (array) $sidebars as $sidebar_id => $info ) {
			printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, genesis_get_custom_field( '_ss_header' ), false ), esc_html( $info['name'] ) );
		}
		?>
	</select>
</p>
	<?php
endif;

if ( is_registered_sidebar( 'sidebar' ) ) :
	?>
<p>
	<label class="howto" for="genesis_simple_sidebars[_ss_sidebar]"><span><?php echo esc_attr( $wp_registered_sidebars['sidebar']['name'] ); ?><span></label>
	<select name="genesis_simple_sidebars[_ss_sidebar]" id="genesis_simple_sidebars[_ss_sidebar]">
		<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
		<?php
		foreach ( (array) $sidebars as $sidebar_id => $info ) {
			printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, genesis_get_custom_field( '_ss_sidebar' ), false ), esc_html( $info['name'] ) );
		}
		?>
	</select>
</p>
	<?php
endif;

if ( is_registered_sidebar( 'sidebar-alt' ) ) :
	?>
<p>
	<label class="howto" for="genesis_simple_sidebars[_ss_sidebar_alt]"><span><?php echo esc_attr( $wp_registered_sidebars['sidebar-alt']['name'] ); ?><span></label>
	<select name="genesis_simple_sidebars[_ss_sidebar_alt]" id="genesis_simple_sidebars[_ss_sidebar_alt]">
		<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
		<?php
		foreach ( (array) $sidebars as $sidebar_id => $info ) {
			printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, genesis_get_custom_field( '_ss_sidebar_alt' ), false ), esc_html( $info['name'] ) );
		}
		?>
	</select>
</p>
	<?php
endif;
