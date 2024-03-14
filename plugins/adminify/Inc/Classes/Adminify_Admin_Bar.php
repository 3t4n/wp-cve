<?php

namespace WPAdminify\Inc\Classes;

class Adminify_Admin_Bar extends \WP_Admin_Bar {

	public function render() {
		$root = $this->_bind();

		if ( empty( $root ) ) {
			return;
		}

		$class = 'nojq nojs';
		if ( wp_is_mobile() ) {
			$class .= ' mobile';
		}

		?>
		<div id="wpadminbar" class="<?php echo esc_attr( $class ); ?>">
			<?php if ( ! is_admin() && ! did_action( 'wp_body_open' ) ) { ?>
				<a class="screen-reader-shortcut" href="#wp-toolbar" tabindex="1"><?php esc_html_e( 'Skip to toolbar', 'adminify' ); ?></a>
			<?php } ?>
			<div class="quicklinks navbar" id="wp-toolbar" role="navigation" aria-label="<?php esc_attr_e( 'Toolbar', 'adminify' ); ?>">
				<?php
				foreach ( $root->children as $group ) {
					if ( $group->id !== 'top-secondary' ) {
						$this->_render_group( $group );
					}
				}

				?>

					<?php
					if ( is_admin() ) {
						do_action( 'adminify/before/secondary_menu' );}
					?>

				<?php

				foreach ( $root->children as $group ) {
					if ( $group->id === 'top-secondary' ) {
						$this->_render_group( $group );
					}
				}
				?>
			</div>
			<?php if ( is_user_logged_in() ) : ?>
			<a class="screen-reader-shortcut" href="<?php echo esc_url( wp_logout_url() ); ?>"><?php esc_html_e( 'Log Out', 'adminify' ); ?></a>
			<?php endif; ?>
		</div>

		<?php
	}

}
