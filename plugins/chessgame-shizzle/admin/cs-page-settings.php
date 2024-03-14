<?php
/*
 * Settings page.
 */

// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


function chessgame_shizzle_menu_settings() {
	add_submenu_page('edit.php?post_type=cs_chessgame', esc_html__('Settings', 'chessgame-shizzle'), esc_html__('Settings', 'chessgame-shizzle'), 'manage_options', 'cs_settings', 'chessgame_shizzle_page_settings');
}
add_action( 'admin_menu', 'chessgame_shizzle_menu_settings', 19 );



function chessgame_shizzle_page_settings() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	}

	$active_tab = 'cs_tab_themes';
	$saved = false;

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'chessgame_shizzle_options' ) {
		chessgame_shizzle_page_settings_update();
		$saved = true;
		$active_tab = chessgame_shizzle_settings_active_tab();
	}
	$chessgame_shizzle_messages = chessgame_shizzle_get_messages();
	$chessgame_shizzle_errors   = chessgame_shizzle_get_errors();
	$messageclass = '';
	if ( $chessgame_shizzle_errors ) {
		$messageclass = 'error';
	} ?>

	<div class="wrap chessgame_shizzle">

		<h1><?php esc_html_e('Settings', 'chessgame-shizzle'); ?></h1>

		<?php
		if ( $saved ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible">
					<p>' . esc_html__('Changes saved.', 'chessgame-shizzle') . '</p>
				</div>';
		} else if ( $chessgame_shizzle_messages ) {
			echo '
				<div id="message" class="updated fade notice is-dismissible ' . esc_attr( $messageclass ) . ' ">' .
					$chessgame_shizzle_messages .
				'</div>';
		}

		/* The rel attribute will be the form that becomes active */ ?>
		<h2 class="nav-tab-wrapper cs-nav-tab-wrapper" role="tablist"> <?php // Do not use nav but h2, since it is using (in)visible content, not real navigation. ?>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'cs_tab_themes')  { echo "nav-tab-active";} ?>" rel="cs_tab_themes"><?php /* translators: Settings page tab */ esc_html_e('Themes', 'chessgame-shizzle'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'cs_tab_antispam')  { echo "nav-tab-active";} ?>" rel="cs_tab_antispam"><?php /* translators: Settings page tab */ esc_html_e('Anti-spam', 'chessgame-shizzle'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'cs_tab_email')   { echo "nav-tab-active";} ?>" rel="cs_tab_email"><?php /* translators: Settings page tab */ esc_html_e('Notifications', 'chessgame-shizzle'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'cs_tab_misc')   { echo "nav-tab-active";} ?>" rel="cs_tab_misc"><?php /* translators: Settings page tab */ esc_html_e('Misc', 'chessgame-shizzle'); ?></a>
		</h2>

		<form name="cs_tab_options" role="tabpanel" class="cs_tab_options cs_tab_themes <?php if ($active_tab === 'cs_tab_themes') { echo "active";} ?>" method="post" action="">
			<?php chessgame_shizzle_page_settingstab_themes(); ?>
		</form>

		<form name="cs_tab_options" role="tabpanel" class="cs_tab_options cs_tab_antispam <?php if ($active_tab === 'cs_tab_antispam') { echo "active";} ?>" method="post" action="">
			<?php chessgame_shizzle_page_settingstab_antispam(); ?>
		</form>

		<form name="cs_tab_options" role="tabpanel" class="cs_tab_options cs_tab_email <?php if ($active_tab === 'cs_tab_email') { echo "active";} ?>" method="post" action="">
			<?php chessgame_shizzle_page_settingstab_email(); ?>
		</form>

		<form name="cs_tab_options" role="tabpanel" class="cs_tab_options cs_tab_misc <?php if ($active_tab === 'cs_tab_misc') { echo "active";} ?>" method="post" action="">
			<?php chessgame_shizzle_page_settingstab_misc(); ?>
		</form>

	</div> <!-- wrap -->
	<?php
}



/*
 * Update Settings.
 *
 * @since 1.1.1
 */
function chessgame_shizzle_page_settings_update() {

	if ( ! current_user_can('manage_options') ) {
		die( esc_html__('You need a higher level of permission.', 'chessgame-shizzle') );
	}

	$active_tab = 'cs_tab_themes';

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'chessgame_shizzle_options' ) {
		if ( isset( $_POST['cs_tab'] ) ) {
			$active_tab = sanitize_text_field( $_POST['cs_tab'] );
			chessgame_shizzle_settings_active_tab( $active_tab );

			switch ( $active_tab ) {
				case 'cs_tab_themes':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['chessgame_shizzle_page_settingstab_themes']) ) {
						$verified = wp_verify_nonce( $_POST['chessgame_shizzle_page_settingstab_themes'], 'chessgame_shizzle_page_settingstab_themes' );
					}
					if ( $verified == false ) {
						// Nonce is invalid.
						chessgame_shizzle_add_message( '<p>' . esc_html__('Nonce check failed. Please try again.', 'chessgame-shizzle') . '</p>', true, false);
						break;
					}

					if (isset($_POST['cs_boardtheme']) ) {
						$boardthemes = chessgame_shizzle_get_boardthemes();
						$boardtheme = sanitize_text_field( $_POST['cs_boardtheme'] );
						foreach ( $boardthemes as $theme ) {
							if ( $theme === $boardtheme ) {
								update_option( 'chessgame_shizzle-boardtheme', $theme );
							}
						}
					}

					if (isset($_POST['cs_piecetheme']) ) {
						$piecethemes = chessgame_shizzle_get_piecethemes();
						$piecetheme = sanitize_text_field( $_POST['cs_piecetheme'] );
						if ( isset( $piecethemes["$piecetheme"] ) && isset( $piecethemes["$piecetheme"]['name'] ) ) {
							update_option( 'chessgame_shizzle-piecetheme', $piecethemes["$piecetheme"]['name'] );
						}
					}
					break;

				case 'cs_tab_antispam':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['chessgame_shizzle_page_settingstab_antispam']) ) {
						$verified = wp_verify_nonce( $_POST['chessgame_shizzle_page_settingstab_antispam'], 'chessgame_shizzle_page_settingstab_antispam' );
					}
					if ( $verified == false ) {
						// Nonce is invalid.
						chessgame_shizzle_add_message( '<p>' . esc_html__('Nonce check failed. Please try again.', 'chessgame-shizzle') . '</p>', true, false);
						break;
					}

					if (isset($_POST['chessgame_shizzle_honeypot']) && $_POST['chessgame_shizzle_honeypot'] === 'on') {
						update_option('chessgame_shizzle-honeypot', 'true');
					} else {
						update_option('chessgame_shizzle-honeypot', 'false');
					}

					if ( get_option('chessgame_shizzle-honeypot_value', false) === false ) {
						$random = rand( 1, 99 );
						update_option( 'chessgame_shizzle-honeypot_value', $random );
					}

					if (isset($_POST['chessgame_shizzle_nonce']) && $_POST['chessgame_shizzle_nonce'] === 'on') {
						update_option('chessgame_shizzle-nonce', 'true');
					} else {
						update_option('chessgame_shizzle-nonce', 'false');
					}

					if (isset($_POST['chessgame_shizzle_timeout']) && $_POST['chessgame_shizzle_timeout'] === 'on') {
						update_option('chessgame_shizzle-timeout', 'true');
					} else {
						update_option('chessgame_shizzle-timeout', 'false');
					}
					break;

				case 'cs_tab_email':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['chessgame_shizzle_page_settingstab_email']) ) {
						$verified = wp_verify_nonce( $_POST['chessgame_shizzle_page_settingstab_email'], 'chessgame_shizzle_page_settingstab_email' );
					}
					if ( $verified == false ) {
						// Nonce is invalid.
						chessgame_shizzle_add_message( '<p>' . esc_html__('Nonce check failed. Please try again.', 'chessgame-shizzle') . '</p>', true, false);
						break;
					}

					if ( isset($_POST['cs_unsubscribe']) && $_POST['cs_unsubscribe'] > 0 ) {
						$user_id = (int) $_POST['cs_unsubscribe'];
						$user_ids = array();

						$user_ids_old = get_option('chessgame_shizzle-notifybymail' );
						if ( strlen($user_ids_old) > 0 ) {
							$user_ids_old = explode( ',', $user_ids_old );
							foreach ( $user_ids_old as $user_id_old ) {
								if ( $user_id_old == $user_id ) {
									continue;
								}
								if ( is_numeric($user_id_old) ) {
									$user_ids[] = (int) $user_id_old;
								}
							}
						}

						$user_ids = implode( ',', $user_ids );
						update_option('chessgame_shizzle-notifybymail', $user_ids);
					}

					if ( isset($_POST['cs_subscribe']) && $_POST['cs_subscribe'] > 0 ) {
						$user_id = (int) $_POST['cs_subscribe'];
						$user_ids = array();

						$user_ids_old = get_option('chessgame_shizzle-notifybymail' );
						if ( strlen($user_ids_old) > 0 ) {
							$user_ids_old = explode( ',', $user_ids_old );
							foreach ( $user_ids_old as $user_id_old ) {
								if ( $user_id_old == $user_id ) {
									continue; // will be added again below the loop
								}
								if ( is_numeric($user_id_old) ) {
									$user_ids[] = (int) $user_id_old;
								}
							}
						}
						$user_ids[] = $user_id; // Really add it.

						$user_ids = implode( ',', $user_ids );
						update_option('chessgame_shizzle-notifybymail', $user_ids);
					}

					if ( isset($_POST['cs_admin_mail_from']) && strlen($_POST['cs_admin_mail_from']) > 0 ) {
						$admin_mail_from = sanitize_text_field( $_POST['cs_admin_mail_from'] );
						if ( filter_var( $admin_mail_from, FILTER_VALIDATE_EMAIL ) ) {
							// Valid Email address.
							update_option('chessgame_shizzle-mail-from', $admin_mail_from);
						}
					} else {
						delete_option( 'chessgame_shizzle-mail-from' );
					}
					break;

				case 'cs_tab_misc':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['chessgame_shizzle_page_settingstab_misc']) ) {
						$verified = wp_verify_nonce( $_POST['chessgame_shizzle_page_settingstab_misc'], 'chessgame_shizzle_page_settingstab_misc' );
					}
					if ( $verified == false ) {
						// Nonce is invalid.
						chessgame_shizzle_add_message( '<p>' . esc_html__('Nonce check failed. Please try again.', 'chessgame-shizzle') . '</p>', true, false);
						break;
					}

					if (isset($_POST['chessgame_shizzle_rss']) && $_POST['chessgame_shizzle_rss'] === 'on') {
						update_option('chessgame_shizzle-rss', 'true');
					} else {
						update_option('chessgame_shizzle-rss', 'false');
					}

					if (isset($_POST['chessgame_shizzle_simple_list_search']) && $_POST['chessgame_shizzle_simple_list_search'] === 'on') {
						update_option('chessgame_shizzle-simple-list-search', 'true');
					} else {
						update_option('chessgame_shizzle-simple-list-search', 'false');
					}
					break;

				default:
					/* Just load the first tab */
					$active_tab = 'cs_tab_themes';
					break;
			}
		}
	}
}



/*
 * Set and Get active tab for settings page.
 *
 * @param  string $active_tab text string with active tab (optional).
 * @return string text string with active tab.
 *
 * @since 3.0.0
 */
function chessgame_shizzle_settings_active_tab( $active_tab = false ) {

	static $active_tab_static;

	if ( $active_tab ) {
		$active_tab_static = sanitize_text_field( $active_tab );
	}

	return $active_tab_static;

}
