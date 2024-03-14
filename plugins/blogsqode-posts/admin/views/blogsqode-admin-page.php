<?php
/**
 * Admin View: Settings
 *
 * @package Blogsqode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// $tab_exists  = isset( $tabs[ $current_tab ] ) || has_action( 'blogsqode_sections_' . $current_tab ) || has_action( 'blogsqode_settings_' . $current_tab ) || has_action( 'blogsqode_settings_tabs_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';
?>
<div class="wrap blogsqode">
	<?php do_action( 'blogsqode_before_settings_' . esc_attr($current_tab) ); ?>
	<form method="<?php echo esc_attr( apply_filters( 'blogsqode_settings_form_method_tab_' . esc_attr($current_tab), 'post' ), 'blogsqode' ); ?>" id="mainblogsqodeform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper blogsqode-nav-tab-wrapper">
			<div class="blogsqode_logo_admin">
				<img src="<?php echo esc_url(BLOGSQODE_PLUGIN_FILE); ?>/images/b_logo.png" alt="blogsqode logo">
			</div>

			<?php
			foreach ( $tabs as $slug => $label ) {
				echo '<a href="' . esc_url( admin_url( 'admin.php?page=main-blogsqode&tab=' . esc_attr( $slug ) ), 'blogsqode' ) . '" class="blogsqode-nav-tab ' . ( esc_attr($current_tab) === $slug ? 'blogsqode-nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
			}

			do_action( 'blogsqode_settings_tabs' );

			?>
		</nav>
		<div class="blogsqode-settings-content">
			<h1 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h1>
			
				<?php
					do_action( 'blogsqode_sections_' . esc_attr($current_tab) );

					self::show_messages();
					do_action( 'blogsqode_settings_' . esc_attr($current_tab) );
					do_action( 'blogsqode_settings_tabs_' . esc_attr($current_tab) ); // @deprecated 3.4.0 hook.
				?>
				<p class="submit">
					<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
						<button name="save" class="button-primary blogsqode-save-button" type="submit" value="<?php echo esc_attr( 'Save changes', 'blogsqode' ); ?>"><?php echo esc_html__( 'Save changes', 'blogsqode' ); ?></button>
					<?php endif; ?>
					<?php wp_nonce_field( 'blogsqode-settings' ); ?>
				</p>
		</div>
	</form>
	<?php do_action( 'blogsqode_after_settings_' . esc_attr($current_tab) ); ?>
</div>