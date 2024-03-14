<?php
/**
 * Booking.com Product Helper
 *
 * The Booking.com Product Helper allows you to embed any Booking.com affiliate product anywhere on your website.
 *
 * @wordpress-plugin
 * Plugin Name: Booking.com Product Helper
 * Plugin URI: http://www.booking.com/general.html?tmpl=docs/product_helper
 * Description: The Booking.com Product Helper allows you to embed any Booking.com affiliate product on your website. With this plugin, you simply paste the embed code from the Affiliate Partner Centre and generate a shortcode, which can be used anywhere on your WordPress website.
 * Version: 1.0.4
 * Author: Booking.com
 * Author URI: https://www.booking.com/affiliate-program/v2/index.html
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bookingcom-product-helper
 * Domain Path: /languages
 */

/*
Booking.com Product Helper is free software:
you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Booking.com Product Helper is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booking.com Affiliate Widget Helper.
If not, see http://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKINGCOM_PRODUCT_HELPER_VERSION', '1.0.4' );

// Define constants and paths.
define( 'BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_SERVER', plugin_dir_path( __FILE__ ) );
define( 'BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_CLIENT', plugin_dir_url( __FILE__ ) );
define( 'BOOKINGCOM_PRODUCT_HELPER__PLUGIN_URL', admin_url( 'options-general.php?page=bookingcom-product-helper' ) );

// Includes other files.
require BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_SERVER . 'includes/class-bookingcom-product-helper-admin.php';

if ( is_admin() ) {
	add_action( 'admin_init', array( 'BookingComProductHelperAdmin', 'init' ) );

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	function bcph_load_textdomain() {
		load_plugin_textdomain(
			'bookingcom-product-helper',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}

	add_action( 'plugins_loaded', 'bcph_load_textdomain' );

	/**
	 * Define plugin menu in WP admin
	 */
	function bcph_add_plugin_in_admin_menu() {
		add_submenu_page(
			'options-general.php',
			'Booking.com Product Helper',
			'Booking.com Product Helper',
			'edit_posts',
			'bookingcom-product-helper',
			'bcph_load_plugin_settings'
		);
	}

	add_action( 'admin_menu', 'bcph_add_plugin_in_admin_menu' );

	/**
	 * Add a settings link to your WordPress plugin on the plugin listing page.
	 *
	 * @param array $links Array of menu links.
	 *
	 * @return mixed
	 */
	function bcph_add_action_links( $links ) {
		$settings_link  = '<a href="';
		$settings_link .= BOOKINGCOM_PRODUCT_HELPER__PLUGIN_URL . '">';
		$settings_link .= esc_html__( 'Settings', 'bookingcom-product-helper' );
		$settings_link .= '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bcph_add_action_links' );
}

/**
 * Output service messages (successful creation, update, errors, etc.)
 *
 * @param string[] $error_messages Array of errors.
 * @param string   $success_message Text of successful message.
 *
 * @return void
 */
function bcph_service_output( $error_messages, $success_message ) {
	$html_output = '';

	if ( count( $error_messages ) > 0 ) {
		$html_output .= '<div class="message error">';
		$html_output .= wpautop(
			implode( "\n", $error_messages )
		);
		$html_output .= '</div>';
	}

	if ( isset( $success_message ) && ! empty( $success_message ) ) {
		$html_output .= '<div class="message updated">';
		$html_output .= wpautop( $success_message );
		$html_output .= '</div>';
	}

	echo wp_kses_post( $html_output );
}

/**
 * Generate URL
 *
 * @param string $widget_id Widget ID.
 * @param string $type_action Form action.
 *
 * @return string
 */
function bcph_generate_form_url( $widget_id, $type_action ) {

	if ( 'add' === $type_action ) {
		return add_query_arg(
			array(
				'page'              => 'bookingcom-product-helper',
				'add_widget_helper' => $widget_id,
			),
			admin_url( 'options-general.php' )
		);
	}

	if ( 'edit' === $type_action ) {
		return add_query_arg(
			array(
				'page'                            => 'bookingcom-product-helper',
				'edit_widget_helper'              => $widget_id,
				'bookingcom_product_helper_nonce' => wp_create_nonce( 'edit_widget_helper' ),
			),
			admin_url( 'options-general.php' )
		);
	}

	if ( 'remove' === $type_action ) {
		return add_query_arg(
			array(
				'page'                            => 'bookingcom-product-helper',
				'remove_widget_helper'            => $widget_id,
				'bookingcom_product_helper_nonce' => wp_create_nonce( 'remove_widget_helper' ),
			),
			admin_url( 'options-general.php' )
		);
	}

	return null;
}

/**
 * Load settings of plugin
 */
function bcph_load_plugin_settings() {
	$success_message = '';
	$error_messages = array();
	$bcom_embeded   = array();

	if (
		isset( $_GET['add_widget_helper'] )
	) {
		return bcph_add_widget();
	}

	if (
		isset( $_GET['bookingcom_product_helper_nonce'], $_GET['edit_widget_helper'] ) &&
		wp_verify_nonce( sanitize_key( $_GET['bookingcom_product_helper_nonce'] ), 'edit_widget_helper' )
	) {
		return bcph_edit_widget();
	}

	if (
		isset( $_GET['bookingcom_product_helper_nonce'], $_GET['remove_widget_helper'] ) &&
		wp_verify_nonce( wp_unslash( sanitize_key( $_GET['bookingcom_product_helper_nonce'] ) ), 'remove_widget_helper' )
	) {
		delete_option( wp_unslash( sanitize_key( 'booking_product_helper_shortname-' . $_GET['remove_widget_helper'] ) ) );

		$bookingcom_product_helper_list =  get_option( 'bookingcom_product_helper_list' );

		if ( is_array( $bookingcom_product_helper_list ) &&
			in_array(
				wp_unslash( sanitize_key( $_GET['remove_widget_helper'] ) ),
				$bookingcom_product_helper_list,
				true
			)
		) {
			$bookingcom_product_helper_list = array_diff(
					$bookingcom_product_helper_list,
					array( wp_unslash( sanitize_key( $_GET['remove_widget_helper'] ) )
				)
			);

			if( count( $bookingcom_product_helper_list ) > 0 ) {
				update_option( 'bookingcom_product_helper_list', $bookingcom_product_helper_list );
			} else {
				delete_option( 'bookingcom_product_helper_list' );
			}

			

			$success_message = sprintf(
				/* translators: %s: Shortcode ID */
				esc_html__(
					'Booking.com Product Helper with id %s successfully deleted.',
					'bookingcom-product-helper'
				),
				'<strong>' . sanitize_text_field( wp_unslash( $_GET['remove_widget_helper'] ) ) . '</strong>'
			);
		}
	}

	$bookingcom_product_helper_list = get_option( 'bookingcom_product_helper_list' );
	if ( ! is_array( $bookingcom_product_helper_list ) ) {
		$bookingcom_product_helper_list = array();
	}

	?>
	<div class="wrap bookingcom-wrapper">
		<h2 class="bookingcom-header">
			<div class="header-block__text">
				<?php
				echo sprintf(
					/* translators: %s: Booking.com logo text */
					esc_html__(
						'%s Product Helper',
						'bookingcom-product-helper'
					),
					'<span class="bookingcom-logo">Booking<span class="bookingcom-logo__com">.com</span></span>'
				);
				?>
			</div>

			<?php if ( count( $bookingcom_product_helper_list ) > 0 ) : ?>
				<form method="get" action="" class="new-product-shortcode--btn">
					<input type="hidden" name="page" value="bookingcom-product-helper"/>
					<input type="hidden" name="add_widget_helper" value="1"/>

					<input class="bui-button bui-button--primary bui-button__text"
							type="submit"
							value="<?php
									echo esc_html__(
										'New product shortcode',
										'bookingcom-product-helper'
									);
									?>"/>
				</form>
			<?php else : ?>
				<a class="bui-button bui-button--primary new-product-shortcode--btn"
					href="<?php echo esc_url( bcph_generate_form_url( '1', 'add' ) ); ?>">
					<span class="bui-button__text">
						<?php
						echo esc_html__(
							'New product shortcode',
							'bookingcom-product-helper'
						);
						?>
					</span>
				</a>
			<?php endif; ?>
		</h2>

		<div class="bookingcom-layout">

			<div class="left-column">

				<?php bcph_service_output( $error_messages, $success_message ); ?>

				<?php if ( count( $bookingcom_product_helper_list ) > 0 ) : ?>

					<table class="widefat fixed bcom-widget-helpers-list js-bcom-widget-helpers-list">
						<thead>
						<tr>
							<th class="list-header">
								<?php
									echo esc_html( 'Shortname' );
								?>
							</th>
							<th class="list-header">
								<?php
								echo esc_html__(
									'Description',
									'bookingcom-product-helper'
								);
								?>
							</th>
							<th class="list-header">
								<?php
								echo esc_html__(
									'Actions',
									'bookingcom-product-helper'
								);
								?>
							</th>
						</tr>
						</thead>
						<tbody>

						<?php
						$single_product_helper_data = null;
						foreach ( $bookingcom_product_helper_list as $bdotcom_widget_helper_id ) {
							$single_product_helper_data = get_option( 'booking_product_helper_shortname-' . $bdotcom_widget_helper_id );
							?>
							<tr>
								<td class="el-shortcode-tag">
									<span class="el-shortcode__name">
										<?php
											echo esc_html( $bdotcom_widget_helper_id );
										?>
									</span>
									<code class="el-copy-to-clipboard-code js-copy-to-clipboard-code">
										[booking_product_helper shortname="<?php
										echo esc_html( $bdotcom_widget_helper_id );
										?>"]
									</code>
									<a class="el-copy-to-clipboard js-copy-to-clipboard"
										href=""
										data-bcom-alert-clipboard-text="<?php
											echo sprintf(
												/* translators: %s: Shortcode ID */
												esc_html__(
													'Shortcode %s successfully copied to your clipboard. Now, just paste it into your webpage or post.',
													'bookingcom-product-helper'
												),
												esc_textarea( '[booking_product_helper shortname="' . $bdotcom_widget_helper_id . '"]' )
											);
										?>">
										<?php
										echo esc_html( '(' ) . esc_html__(
											'Copy to clipboard',
											'bookingcom-product-helper'
										) . esc_html( ')' );
										?>
									</a>
								</td>
								<td class="el-shortcode-description">
									<?php
										echo esc_html( $single_product_helper_data['short_description'] );
									?>
								</td>
								<td class="el-shortcode-actions">
									<a href="<?php echo esc_url( bcph_generate_form_url( $bdotcom_widget_helper_id, 'edit' ) ); ?>">
										<?php
										echo esc_html__(
											'Edit',
											'bookingcom-product-helper'
										);
										?>
									</a> |
									<a class="js-remove_widget_helper"
										href="<?php echo esc_url( bcph_generate_form_url( $bdotcom_widget_helper_id, 'remove' ) ); ?>"
										data-bcom-prompt-delete-text="<?php echo esc_html__( 'Are you sure you want to delete this shortcode? Once deleted, you cannot recreate it.', 'bookingcom-product-helper' ); ?>"
									>
										<?php
										echo esc_html__(
											'Delete',
											'bookingcom-product-helper'
										);
										?>
									</a>
								</td>
							</tr>
						<?php } /* end of foreach */ ?>
						</tbody>
					</table>

				<?php else : ?>
					<div class="empty-product-list">
						<h2 class="empty-product-list__message">
							<img class="empty-product-list__logo"
								src="<?php
										echo esc_url(
											BOOKINGCOM_PRODUCT_HELPER__PLUGIN_DIR_CLIENT . '/images/empty_page.svg'
										);
										?>"
								alt="Empty list logo" />
							<span class="empty-product-list__text">
								<?php
								echo esc_html__(
									'Your product list is empty.',
									'bookingcom-product-helper'
								);
								?>
							</span>
						</h2>
					</div>
				<?php endif; ?>

			</div> <!-- .left-column (end) -->

			<?php BookingComProductHelperAdmin::view_template( 'info' ); ?>

		</div> <!-- .booking-layout (end) -->
	</div> <!-- .wrap (end) -->

	<?php
}

/**
 * Method for editing helper parameters
 */
function bcph_edit_widget() {
	$success_message           = '';
	$error_messages            = array();
	$bookingcom_product_helper = array();

	if (
		isset( $_GET['bookingcom_product_helper_nonce'], $_GET['edit_widget_helper'] ) &&
		wp_verify_nonce(
			sanitize_key( $_GET['bookingcom_product_helper_nonce'] ),
			'edit_widget_helper'
		)
	) {
		$bdotcom_widget_helper_id = 
			wp_unslash( sanitize_key( $_GET['edit_widget_helper'] )
		);
	}

	if (
		! empty( $_POST ) &&
		isset( $_POST['bookingcom_product_helper_nonce'] ) &&
		wp_verify_nonce(
			wp_unslash(
				sanitize_key(
					$_POST['bookingcom_product_helper_nonce']
				)
			),
			'bookingcom_product_helper_nonce'
		)
	) {

		foreach ( $_POST as $k => $v ) {			
            if ( $k == 'bdotcom_widget_code' ) {
                $bookingcom_product_helper[ $k ] = wp_filter_post_kses( wp_unslash( $v ) );
            } elseif ( $k == 'bdotcom_widget_description' ) {
                $bookingcom_product_helper[ $k ] = sanitize_textarea_field( $v );
            } elseif ( $k == 'bdotcom_widget_helper_id' ) {
                $bookingcom_product_helper[ $k ] = sanitize_key( $v );
            } else {
                $bookingcom_product_helper[ $k ] = sanitize_text_field( $v );
            }
		}

		if ( empty( $bookingcom_product_helper['bdotcom_widget_code'] ) ) {
			$error_messages[] = esc_html__(
				"Booking.com widget embedded code can't be empty",
				'bookingcom-product-helper'
			);
		}

		if ( count( $error_messages ) <= 0 ) {
			$booking_product_helper_options = array(
				'code_content'      => $bookingcom_product_helper['bdotcom_widget_code'],
				'short_description' => $bookingcom_product_helper['bdotcom_widget_description'],
			);

			update_option(
				'booking_product_helper_shortname-' . $bdotcom_widget_helper_id,
				$booking_product_helper_options
			);

			$success_message = sprintf(
				// translators: %s: Shortcode ID.
				esc_html__(
					'Your Booking.com product shortcode %s was successfully updated.',
					'bookingcom-product-helper'
				),
				'<strong>' . esc_html( $bdotcom_widget_helper_id ) . '</strong>'
			);
		}
	}

	$bookingcom_product_helper = get_option( 'booking_product_helper_shortname-' . $bdotcom_widget_helper_id );
	unset( $booking_product_helper_options );
	?>

	<div class="wrap bookingcom-wrapper">
		<h2 class="bookingcom-header">
			<a class="bui-button bui-button--secondary back--btn"
				href="?page=bookingcom-product-helper">
				<span class="bui-button__text">
					<?php
					echo esc_html( '&lsaquo; ' ) . esc_html__(
						'Back',
						'bookingcom-product-helper'
					);
					?>
				</span>
			</a>

			<span class="header-block__text">
				<?php
				echo sprintf(
					/* translators: %s: Booking.com logo text */
					esc_html__(
						'Edit %s product shortcode',
						'bookingcom-product-helper'
					),
					'<span class="bcom-logo">Booking<span class="bookingcom-logo__com">.com</span></span>'
				);
				?>
			</span>
		</h2>

		<div class="bookingcom-layout">

			<div class="left-column">

				<form class="bcom-widget-helper-edit-form" method="post" action="">

				<?php bcph_service_output( $error_messages, $success_message ); ?>

				<?php wp_nonce_field( 'bookingcom_product_helper_nonce', 'remove_widget_helper' ); ?>

				<div class="form-group">
					<label class="form_label" for="bdotcom_widget_helper_id">
						<?php
						echo esc_html__(
							'Product shortname',
							'bookingcom-product-helper'
						) . '<span style="color: #DC3232;">' . esc_html( ' *' ) . '</span>';
						?>
						<br>
						<span class="label-hint">
							<?php
							echo esc_html__(
								'Enter a unique name that you can use to identify your shortcode. Only use letters a-z and numbers 0-9, no symbols.',
								'bookingcom-product-helper'
							);
							?>
						</span>
					</label>
					<div class="form-control disabled">
						<?php
						if ( isset( $bdotcom_widget_helper_id ) ) {
							echo esc_html( $bdotcom_widget_helper_id );
						}
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="form_label" for="bdotcom_widget_code">
						<?php
						echo esc_html( '</> ' ) .
							esc_html__(
								'Product code',
								'bookingcom-product-helper'
							) . '<span style="color: #DC3232;">' . esc_html( ' *' ) . '</span>';
						?>
						<br>
						<span class="label-hint">
							<?php
							echo esc_html__(
								'Paste the full embed code you generated after creating the product on Booking.com Affiliate Partner Centre.',
								'bookingcom-product-helper'
							)
							?>
						</span>
					</label>
					<textarea
						class="form-control"
						dir="ltr"
						dirname="ltr"
						id="bdotcom_widget_code"
						name="bdotcom_widget_code"
						rows="10"
						cols="50"><?php if ( isset( $bookingcom_product_helper['code_content'] ) ) {
							echo esc_textarea( $bookingcom_product_helper['code_content'] );
						} ?></textarea>
				</div>

				<div class="form-group">
					<label class="form_label" for="bdotcom_widget_description">
						<?php
						echo esc_html__(
							'Short description (optional)',
							'bookingcom-product-helper'
						)
						?>
						<br>
						<span class="label-hint">
									<?php
									echo esc_html__(
										"If you want you can add a description of the product you're adding. This will only be visible to the admin and will not show when you add the shortcode to your WordPress page or post. 100 characters max.",
										'bookingcom-product-helper'
									)
									?>
								</span>
					</label>
					<textarea
						class="form-control js-short-description-input"
						dir="ltr"
						dirname="ltr"
						id="bdotcom_widget_description"
						name="bdotcom_widget_description"
						rows="3"
						cols="30"><?php
						if ( isset( $bookingcom_product_helper['short_description'] ) ) {
							echo esc_textarea( $bookingcom_product_helper['short_description'] );
						} ?></textarea>
					<p class="word-counter-limit js-word-counter-limit">0/100</p>
				</div>

				<p class="form-button-group">
					<input class="bui-button bui-button--primary bui-button__text"
							type="submit"
							value="<?php
									echo esc_html__(
										'Update product shortcode',
										'bookingcom-product-helper'
									);
									?>"/>
					<a class="bui-button bui-button--secondary back--btn"
						href="?page=bookingcom-product-helper">
								<span class="bui-button__text">
									<?php
									echo esc_html__(
										'Cancel',
										'bookingcom-product-helper'
									);
									?>
								</span>
					</a>
				</p>
				<?php wp_nonce_field( 'bookingcom_product_helper_nonce', 'bookingcom_product_helper_nonce' ); ?>
			</form>

			</div> <!-- .left-column (end) -->

			<?php BookingComProductHelperAdmin::view_template( 'info' ); ?>

		</div> <!-- .bookingcom-layout (end) -->
	</div> <!-- .wrap (end) -->

	<?php
}

/**
 * Method for adding helper
 */
function bcph_add_widget() {
	$success_message = '';
	$error_messages            = array();
	$bookingcom_product_helper = array();

	$bookingcom_product_helper_list = get_option( 'bookingcom_product_helper_list' );

	if ( ! is_array( $bookingcom_product_helper_list ) ) {
		$bookingcom_product_helper_list = array();
	}

	if (
		! empty( $_POST ) &&
		isset( $_POST['bookingcom_product_helper_nonce'] ) &&
		wp_verify_nonce(
			wp_unslash(
				sanitize_key(
					$_POST['bookingcom_product_helper_nonce']
				)
			),
			'bookingcom_product_helper_nonce'
		)
	) {

		foreach ( $_POST as $k => $v ) {
			if ( $k == 'bdotcom_widget_code' ) {
                $bookingcom_product_helper[ $k ] = wp_filter_post_kses( $v );
            } elseif ( $k == 'bdotcom_widget_description' ) {
                $bookingcom_product_helper[ $k ] = sanitize_textarea_field( $v );
            } elseif ( $k == 'bdotcom_widget_helper_id' ) {
                $bookingcom_product_helper[ $k ] = sanitize_key( $v );
            } else {
                $bookingcom_product_helper[ $k ] = sanitize_text_field( $v );
            }			
		}

		if (
			in_array(
				strtolower( $bookingcom_product_helper['bdotcom_widget_helper_id'] ),
				$bookingcom_product_helper_list,
				true
			)
		) {
			$error_messages[] = sprintf(
				// translators: %s: Shortcode ID.
				esc_html__(
					'An error occurred. The shortname %s already exists.',
					'bookingcom-product-helper'
				),
				'<strong>' . esc_html( strtolower( $bookingcom_product_helper['bdotcom_widget_helper_id'] ) ) . '</strong>'
			);
		}

		if ( empty( $bookingcom_product_helper['bdotcom_widget_helper_id'] ) ||
			empty( $bookingcom_product_helper['bdotcom_widget_code'] )
		) {
			$error_messages[] = esc_html__(
				'Something went wrong. Your Booking.com product shortcode has not been created.',
				'bookingcom-product-helper'
			);
		}

		if ( count( $error_messages ) <= 0 ) {
			$bdotcom_widget_helper_id         = $bookingcom_product_helper['bdotcom_widget_helper_id'];
			$bookingcom_product_helper_list[] = $bdotcom_widget_helper_id;
			$booking_product_helper_options   = array(
				'code_content'      => $bookingcom_product_helper['bdotcom_widget_code'],
				'short_description' => $bookingcom_product_helper['bdotcom_widget_description'],
			);

			update_option( 'bookingcom_product_helper_list', $bookingcom_product_helper_list );
			update_option(
				'booking_product_helper_shortname-' . $bdotcom_widget_helper_id,
				$booking_product_helper_options
			);

			$success_message = sprintf(
				// translators: %s: Shortcode ID which was added.
				esc_html__(
					'Your Booking.com product shortcode %s was successfully created.',
					'bookingcom-product-helper'
				),
				'<strong>' . esc_html( $bdotcom_widget_helper_id ) . '</strong>'
			);

			unset( $booking_product_helper_options );
			unset( $bookingcom_product_helper );
		}
	}

	?>
	<div class="wrap bookingcom-wrapper">
		<h2 class="bookingcom-header">
			<a class="bui-button bui-button--secondary back--btn"
				href="?page=bookingcom-product-helper">
				<span class="bui-button__text">
					<?php
					echo esc_html( '&lsaquo; ' ) . esc_html__(
						'Back',
						'bookingcom-product-helper'
					);
					?>
				</span>
			</a>
			<span class="header-block__text">
				<?php
				echo sprintf(
					/* translators: %s: Booking.com logo text */
					esc_html__(
						'Create %s product shortcode',
						'bookingcom-product-helper'
					),
					'<span class="bcom-logo">Booking<span class="bookingcom-logo__com">.com</span></span>'
				);
				?>
			</span>
		</h2>

		<div class="bookingcom-layout">

			<div class="left-column">
				<form class="bcom-widget-helper-add-form" method="post" action="">

					<?php bcph_service_output( $error_messages, $success_message ); ?>

					<?php wp_nonce_field( 'bookingcom_product_helper_nonce', 'bookingcom_product_helper_nonce' ); ?>

					<div class="form-group">
						<label class="form_label" for="bdotcom_widget_helper_id">
							<?php
							echo esc_html__(
								'Product shortname',
								'bookingcom-product-helper'
							) . '<span style="color: #DC3232;">' . esc_html( ' *' ) . '</span>';
							?>
							<br>
							<span class="label-hint">
								<?php
								echo esc_html__(
									'Enter a unique name that you can use to identify your shortcode. Only use letters A-Z and numbers 0-9, no symbols.',
									'bookingcom-product-helper'
								);
								?>
							</span>
						</label>
						<input
							class="form-control"
							type="text"
							name="bdotcom_widget_helper_id"
							id="bdotcom_widget_helper_id"
							size="40"
							value="<?php if ( isset( $bookingcom_product_helper['bdotcom_widget_helper_id'] ) ) {
								echo esc_attr( $bookingcom_product_helper['bdotcom_widget_helper_id'] );
							} ?>"
						/>
					</div>

					<div class="form-group">
						<label class="form_label" for="bdotcom_widget_code">
							<?php
							echo esc_html( '</> ' ) .
								esc_html__(
									'Product code',
									'bookingcom-product-helper'
								) . '<span style="color: #DC3232;">' . esc_html( ' *' ) . '</span>';
							?>
							<br>
							<span class="label-hint">
								<?php
								echo esc_html__(
									'Paste the full embed code you generated after creating the product on Booking.com Affiliate Partner Centre.',
									'bookingcom-product-helper'
								)
								?>
							</span>
						</label>
						<textarea
							class="form-control"
							dir="ltr"
							dirname="ltr"
							id="bdotcom_widget_code"
							name="bdotcom_widget_code"
							rows="10"
							cols="50"><?php
								if ( isset( $bookingcom_product_helper['bdotcom_widget_code'] ) ) {
									echo esc_textarea( $bookingcom_product_helper['bdotcom_widget_code'] );
								} ?></textarea>
					</div>

					<div class="form-group">
						<label class="form_label" for="bdotcom_widget_description">
							<?php
							echo esc_html__(
								'Short description (optional)',
								'bookingcom-product-helper'
							)
							?>
							<br>
							<span class="label-hint">
								<?php
								echo esc_html__(
									'If you want you can add a description of the product you\'re adding. This will only be visible to the admin and will not show when you add the shortcode to your WordPress page or post. 100 characters max.',
									'bookingcom-product-helper'
								)
								?>
							</span>
						</label>
						<textarea
							class="form-control js-short-description-input"
							dir="ltr"
							dirname="ltr"
							id="bdotcom_widget_description"
							name="bdotcom_widget_description"
							rows="3"
							cols="30"></textarea>
						<p class="word-counter-limit js-word-counter-limit">0/100</p>
					</div>

					<p class="form-button-group">
						<input class="bui-button bui-button--primary bui-button__text"
								type="submit"
								value="<?php
										echo esc_html__(
											'Create product shortcode',
											'bookingcom-product-helper'
										);
										?>"/>
						<a class="bui-button bui-button--secondary back--btn"
							href="?page=bookingcom-product-helper">
							<span class="bui-button__text">
								<?php
								echo esc_html__(
									'Cancel',
									'bookingcom-product-helper'
								);
								?>
							</span>
						</a>
					</p>
				</form>

			</div> <!-- .left-column (end) -->

			<?php BookingComProductHelperAdmin::view_template( 'info' ); ?>

		</div> <!-- .booking-layout (end) -->
	</div> <!-- .wrap (end) -->
	<?php
}

add_shortcode( 'booking_product_helper', 'bcph_get_bookingcom_widget_code' );

/**
 * Getting widget code from database.
 *
 * @param array $atts Attributes.
 *
 * @return mixed|string
 */
function bcph_get_bookingcom_widget_code( $atts ) {
	extract(
		shortcode_atts(
			array(
				'shortname' => false,
			),
			$atts
		)
	);

	if ( ! isset( $shortname ) || ! $shortname ) {
		return '';
	}

	$option_content = get_option( 'booking_product_helper_shortname-' . $shortname );

	if ( isset( $option_content ) && ! empty( $option_content ) ) {
		//return $option_content['code_content'];
		//Add script tags
		$booking_product_shortcode = wp_unslash( wp_kses_post( $option_content['code_content'] ) ); //for legacy shortcodes before ver. 1.0.2
		$booking_product_shortcode = str_replace( '</ins>', '</ins><script>', $booking_product_shortcode );
		$booking_product_shortcode = $booking_product_shortcode . '</script>';
		return $booking_product_shortcode;
	}
}
