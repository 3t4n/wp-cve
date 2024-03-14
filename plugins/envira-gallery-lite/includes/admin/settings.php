<?php
/**
 * Envira Gallery Settings
 *
 * @package Envira Gallery Lite
 */

/**
 * Settings Class
 *
 * @since 1.8.7
 */
class Envira_Settings {

	/**
	 * Class Hooks
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 11 );
	}

	/**
	 * Adds admin menus
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function admin_menu() {
		global $submenu;

		$whitelabel = apply_filters( 'envira_whitelabel', false ) ? '' : __( 'Envira Gallery ', 'envira-gallery-lite' );

		// Register the submenus.
		add_submenu_page(
			'edit.php?post_type=envira',
			esc_html__( 'Settings', 'envira-gallery-lite' ),
			esc_html__( 'Settings', 'envira-gallery-lite' ),
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			'envira-gallery-settings',
			[ $this, 'page' ]
		);
	}

	/**
	 * Output tab navigation
	 *
	 * @since 2.2.0
	 *
	 * @param string $tab Tab to highlight as active.
	 */
	public static function tab_navigation( $tab = 'whats_new' ) {
		?>

		<ul class="envira-nav-tab-wrapper">
			<li>
			<a class="envira-nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'envira-gallery-settings' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				envira-nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							[
								'post_type' => 'envira',
								'page'      => 'envira-gallery-settings',
							],
							'edit.php'
						)
					)
				);
				?>
														">
				<?php esc_html_e( 'Settings', 'envira-gallery-lite' ); ?>
			</a>
			</li>

		</ul>

		<?php
	}

	/**
	 * Settings Page.
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function page() {

		self::tab_navigation( __METHOD__ );
		?>
		<div class="envira-settings-tab">
			<table class="form-table">
				<tbody>
					<tr id="envira-image-gallery-settings-title">
						<th scope="row" colspan="2">
							<h3><?php esc_html_e( 'License', 'envira-gallery-lite' ); ?></h3>
							<p><?php esc_html_e( 'Your license key provides access to updates and add-ons.', 'envira-gallery-lite' ); ?></p>
						</th>
					</tr>
					<tr id="envira-settings-key-box" class="title">
						<th scope="row">
							<label for="envira-settings-key"><?php esc_html_e( ' License Key', 'envira-gallery-lite' ); ?></label>
						</th>
						<td>
							<p><?php esc_html_e( "You're using Envira Gallery Lite - no license needed. Enjoy! 🙂", 'envira-gallery-lite' ); ?></p>

							<p>
							<?php
								printf(
									// Translators: %1$s - Opening anchor tag, do not translate. %2$s - Closing anchor tag, do not translate.
									esc_html__( 'To unlock more features consider %1$supgrading to PRO%2$s.', 'envira-gallery-lite' ),
									'<strong><a href="' . esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( 'https://enviragallery.com/pricing', 'settingspage', 'upgradingtopro' ) ) . '" target="_blank" rel="noopener noreferrer">',
									'</a></strong>'
								);
							?>
							</p>
							<p>
							<?php
								printf(
									// Translators: %1$s - Opening span tag, do not translate. %2$s - Closing span tag, do not translate.
									esc_html__( 'As a valued Envira Gallery Lite user you receive %1$s 50%% off%2$s, automatically applied at checkout', 'envira-gallery-lite' ),
									'<span class="envira-green"><strong>',
									'</strong></span>'
								);
							?>
							</p>
							<hr />
							<form id="envira-settings-verify-key" method="post">
								<p class="description"><?php esc_html_e( 'Already purchased? Simply enter your license key below to enable Envira Gallery PRO!', 'envira-gallery' ); ?><?php echo esc_html( apply_filters( 'envira_whitelabel_name', 'Envira' ) ); ?></p>
								<input placeholder="<?php esc_attr_e( 'Paste license key here', 'envira-gallery-lite' ); ?>" type="password" name="envira-license-key" id="envira-settings-key" value="" />
								<button type="button " class="button envira-button-dark envira-gallery-verify-submit primary" id="envira-gallery-settings-connect-btn">
					<?php esc_html_e( 'Verify Key', 'envira-gallery-lite' ); ?>
				</button>


							</form>
						</td>
					</tr>

				</tbody>
			</table>

			<!-- <hr /> -->
		</div>
		<?php
	}
}
