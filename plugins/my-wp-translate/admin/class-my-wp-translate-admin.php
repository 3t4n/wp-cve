<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mythemeshop.com/
 * @since      1.0.0
 *
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/admin
 * @author     MyThemeShop <support@mythemeshop.com>
 */
class MY_WP_Translate_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Current tab options
	 *
	 * @var array
	 */
	private $plugin_options_tabs_arr = array();

	/**
	 * Context internal marker.
	 *
	 * @var array
	 */
	private $context_marker = '[[WPMT_CTXT]]';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();
		$screen_id = $screen->id;

		if ( 'toplevel_page_my-wp-translate' === $screen_id ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-wp-translate-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();
		$screen_id = $screen->id;

		if ( 'toplevel_page_my-wp-translate' === $screen_id ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-wp-translate-admin.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name,
				'mtswpt',
				array(
					'confirm_remove'  => esc_html__( 'Are you sure you want to delete plugin translations?', 'my-wp-translate' ),
					'confirm_import'  => esc_html__( 'Please make sure that you have correct code and that you have the backup of current strings. Proceed?', 'my-wp-translate' ),
					'no_import_data'  => esc_html__( 'No data to import. Please paste the code in import field.', 'my-wp-translate' ),
					'updating_export' => esc_html__( 'Updating export code...', 'my-wp-translate' ),
					'nonce'           => wp_create_nonce( 'my-wp-translate' ),
				)
			);
		}

	}

	/**
	 * Register the administration menu, attached to 'admin_menu'
	 *
	 * @since 1.0.0
	 */
	public function admin_menu_page() {

		add_menu_page(
			esc_html__( 'My WP Translate', 'my-wp-translate' ),
			esc_html__( 'My WP Translate', 'my-wp-translate' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' ),
			'dashicons-translation'
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_admin_page() {

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'mtswpt_add_new';
		?>
		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php $this->plugin_options_tabs(); ?>
			<div id="mtswpt-settings">
					<?php

					$mtswpt_translations = get_option( 'mtswpt_translations' );
					$mtswpt_translations_pl = isset( $mtswpt_translations['plugins'] ) ? $mtswpt_translations['plugins'] : array();

					if ( 'mtswpt_add_new' === $tab ) {

						$active_valid_plugins = wp_get_active_and_valid_plugins();

						?>
						<div id="mtswpt_plugin_select">
							<select id="mtswpt_plugin">
							<?php
							echo "\n\t" . '<option value="">' . esc_html__( 'Select Active Plugin', 'my-wp-translate' ) . '</option>';
							foreach ( $active_valid_plugins as $plugin_path ) {

								$plugin = get_plugin_data( $plugin_path );

								$text_domain = '';

								// If plugin have free and pro versions.
								$free_pro_versions = $this->get_shared_textdomains();

								// Find textdomain ( if no "Text Domain" in header or the plugin needs to have separate tabs - free/pro ).
								if ( empty( $plugin['TextDomain'] ) || in_array( $plugin['TextDomain'], $free_pro_versions ) ) {

									// Generated option inside "load_textdomain" action ( domain => lang folder path ).
									$mtswpt_domains_arr = get_option( 'mtswpt_domains', array() );
									$plugin_dir_path    = plugin_dir_path( $plugin_path );

									foreach ( $mtswpt_domains_arr as $domain => $lang_path ) {

										if ( false !== strpos( $lang_path, $plugin_dir_path ) ) {

											if ( in_array( $domain, $free_pro_versions ) ) {

												$domain = ( false !== strpos( $plugin_dir_path, $domain . '-pro' ) ) ? $domain . '_pprroo' : $domain . '_ffrree';
											}

											$text_domain = $domain;
											break;
										}
									}
								} else {
									// Take textdomain from plugin headers.
									$text_domain = $plugin['TextDomain'];
								}

								// Some plugins don't have/need translations - ignore them.
								if ( ! empty( $text_domain ) ) {

									// Disabled if already in tabs.
									$disabled = array_key_exists( 'mtswpt_plugin_' . $text_domain, $mtswpt_translations_pl ) ? 'disabled' : '';

									echo "\n\t" . '<option value="' . esc_attr( $text_domain ) . '" ' . $disabled . '>' . $plugin['Name'] . '</option>';
								}
							}
							?>
							</select>
						</div>
						<div id="add_plugin_button_wrap">
							<a href="#" class="button button-primary" id="add_plugin_button"><?php esc_html_e( 'Add', 'my-wp-translate' ); ?></a>
							<span id="mtswpt-select-loader" class="spinner"></span>
						</div>
					<?php
					} else {
					?>
						<form method="post" action="options.php">
							<?php
							settings_fields( $tab );

							$opt = get_option( $tab, array() );

							// Here comes the translation panel.
							$translate_enabled = false;
							if ( isset( $opt['translate'] ) ) {
								$translate_enabled = true;
							}

							$translate_path = '';
							if ( isset( $opt['path'] ) ) {
								$translate_path = $opt['path'];
							}
							$tr_options = get_option( 'mtswpt_translations' );
							$per_page = isset( $tr_options['strings_per_page'] ) ? $tr_options['strings_per_page'] : 20;

							echo '<div class="nhp-opts-field-wrapper">';
								echo '<p id="mtswpt_translate_wrap"><label for="nhp-opts-translate"><input type="checkbox" name="' . esc_attr( $tab ) . '[translate]" id="nhp-opts-translate" value="1" ' . checked( $translate_enabled, true, false ) . ' />' . esc_html__( 'Enable translation panel', 'my-wp-translate' ) . '</label></p>';

								echo '<div id="mtswpt_path_wrap" class="hidden">';
									echo '<p>' . esc_html__( 'We couldn\'t find language file, please enter the path to your theme/plugin language file relative to wp-content folder.', 'my-wp-translate' ) . '</p>';
									echo '<p><input type="text" id="mtswpt_path" name="' . esc_attr( $tab ) . '[path]" placeholder="/themes/my-theme/lang/my-theme.pot" value="' . esc_attr( $translate_path ) . '"/></p>';
									submit_button();
								echo '</div>';
							echo '</div>';

							echo '<div class="nhp-opts-field-wrapper" id="translate_search_wrapper">';
								echo '<p><input type="text" value="" id="translate_search" placeholder="' . esc_html__( 'Search Translations', 'my-wp-translate' ) . '" />';
							echo '</div>';

							echo '<div class="nhp-opts-field-wrapper" id="translate_strings_per_page_wrapper">';
								echo '<p><label>' . esc_html__( 'Strings Per Page:', 'my-wp-translate' ) . '<input type="number" value="' . esc_attr( $per_page ) . '" id="translate_strings_per_page" /></label>';
							echo '</div>';

							echo '<div class="nhp-opts-field-wrapper translate-strings"></div>';

							$mts_translations = array();
							$strings_opt_name = $tab . '_strings';
							$mts_translations_opt = get_option( $strings_opt_name );

							if ( $mts_translations_opt ) {
								$mts_translations = $mts_translations_opt;

							} elseif ( false !== strpos( $tab, 'mtswpt_theme_mts_' ) || false !== strpos( $tab, 'mtswpt_theme_mts-' ) ) {

								$curr_tab = str_replace( array( 'mts_', 'mts-' ), '', $tab );
								$alt_mts_translations_opt = get_option( $curr_tab . '_strings' );
								$strings_opt_name = $curr_tab . '_strings';

								if ( $alt_mts_translations_opt ) {
									$mts_translations = $alt_mts_translations_opt;
								}
							}
							echo '<div class="translate-additional-options">';
								echo '<div class="translate-import-export-options">';
									echo '<a href="#" class="show-import-export button button-secondary">' . esc_html__( 'Toggle Import/Export Options', 'my-wp-translate' ) . '</a>';

									echo '<div class="translate-import-export-wrap">';
										echo '<ul class="translate-import-export-tabs">';
											echo '<li><a href="#translate-import-wrap" class="import-export-tab import-tab active">' . esc_html__( 'Import Translated Strings', 'my-wp-translate' ) . '</a></li>';
											echo '<li><a href="#translate-export-wrap" class="import-export-tab export-tab">' . esc_html__( 'Export Translated Strings', 'my-wp-translate' ) . '</a></li>';
										echo '</ul>';

										$export_val = empty( $mts_translations ) ? '' : wp_json_encode( $mts_translations );
										echo '<div id="translate-import-wrap" class="translate-import-export-content active">';
											echo '<p>' . esc_html__( 'Paste your import/backup code and press "Import" button below.', 'my-wp-translate' ) . '</p>';
											echo '<textarea id="mtswpt-import" cols="40" rows="10" data-opt-name="' . esc_attr( $strings_opt_name ) . '"></textarea>';
											echo '<button id="import-button" class="button button-primary">' . esc_html__( 'Import', 'my-wp-translate' ) . '</button>';
										echo '</div>';

										echo '<div id="translate-export-wrap" class="translate-import-export-content">';
											echo '<p>' . esc_html__( 'Copy export/backup code and keep it safe.', 'my-wp-translate' ) . '</p>';
											echo '<textarea id="mtswpt-export" cols="40" rows="10" data-opt-name="' . esc_attr( $strings_opt_name ) . '" onclick="this.focus();this.select()">' . $export_val . '</textarea>';
										echo '</div>';

									echo '</div>';
								echo '</div>';

								echo '<div class="translate-download-wrap">';
									$url = '?page=' . $this->plugin_name . '&tab=' . $tab . '&download=1';
									echo '<a href="' . esc_url( $url ) . '" class="translate-download button button-primary">' . esc_html__( 'Create and Download .po file', 'my-wp-translate' ) . '</a>';
								echo '</div>';
							echo '</div>';
							?>
						</form>
					<?php
					}

					?>

			</div>
		</div>
	<?php
	}

	/**
	 * Register settings for each tab.
	 *
	 * @since    1.0.0
	 */
	public function settings_init() {

		$theme = wp_get_theme();
		$theme_textdomain = $theme->get( 'TextDomain' );
		$theme_tab_key = 'mtswpt_theme_' . $theme_textdomain;

		if ( is_child_theme() && ! $theme_textdomain ) {
			$parent = wp_get_theme( get_template() );
			$theme_textdomain = $parent->get( 'TextDomain' );
			$theme_tab_key = 'mtswpt_theme_' . $theme_textdomain;
		}

		if ( 'mythemeshop' === $theme_textdomain || ! $theme_textdomain ) {
			$theme_tab_key = 'mtswpt_theme_' . $theme->stylesheet;
		}

		$all_tr = get_option( 'mtswpt_translations' );

		if ( ! isset( $all_tr['themes'][ $theme_tab_key ] ) ) {
			$all_tr['themes'][ $theme_tab_key ] = array(
				'opt_name' => $theme_tab_key,
				'name' => $theme->get( 'Name' ),
			);
			update_option( 'mtswpt_translations', $all_tr );
		}
		$all_tabs[ $theme_tab_key ] = $theme->get( 'Name' );

		register_setting( $theme_tab_key, $theme_tab_key );

		if ( isset( $all_tr['plugins'] ) ) {
			$active_valid_plugins = wp_get_active_and_valid_plugins();
			foreach ( $all_tr['plugins'] as $plugin_tab_key => $plugin_data ) {

				// Show only tabs of active plugins.
				if ( is_plugin_active( plugin_basename( $plugin_data['plugin_path'] ) ) ) {

					register_setting( $plugin_tab_key, $plugin_tab_key );
					$all_tabs[ $plugin_tab_key ] = $plugin_data['name'];
				}
			}
		}

		$all_tabs = $all_tabs + array(
			'mtswpt_add_new' => esc_html__( 'Add Plugin', 'my-wp-translate' ),
		);

		$this->plugin_options_tabs_arr = $all_tabs;
	}

	/**
	 * Render options tabs.
	 *
	 * @since    1.0.0
	 */
	public function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'mtswpt_add_new';

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_options_tabs_arr as $tab_key => $tab_caption ) {
			$icon = 'mtswpt_add_new' === $tab_key ? '<span class="dashicons dashicons-plus"></span>' : '';
			$remove_icon = false !== strpos( $tab_key, 'mtswpt_plugin_' ) ? '<span class="mtswpt-remove-translation" data-tab="' . esc_attr( $tab_key ) . '"><span class="dashicons dashicons-no"></span></span>' : '';
			$active = $current_tab === $tab_key ? 'nav-tab-active' : '';

			$url = '?page=' . $this->plugin_name . '&tab=' . $tab_key;
			echo '<a class="nav-tab ' . esc_attr( $active ) . '" href="' . esc_url( $url ) . '">' . $icon . $tab_caption . $remove_icon . '</a>';
		}
		echo '</h2>';
	}

	/**
	 * Find lang file
	 *
	 * @since 1.0.0
	 * @param  string $current_tab Current tab.
	 * @return string
	 */
	public function find_lang_file( $current_tab ) {

		$file = '';

		$slug = str_replace( array( 'mtswpt_theme_', 'mtswpt_plugin_' ) , '', $current_tab );
		$slug = str_replace( array( '_ffrree', '_pprroo' ) , '', $slug );
		if ( empty( $slug ) ) {
			return $file;
		}

		// Check if custom path value is there.
		$mtswpt_path_opt = get_option( $current_tab );
		$mtswpt_path = isset( $mtswpt_path_opt['path'] ) ? $mtswpt_path_opt['path'] : '';

		if ( empty( $mtswpt_path ) ) {

			// Try to find language .po or .pot file.
			// Best chance to work.
			$collect_opt = get_option( 'mtswpt_domains', array() );
			if ( array_key_exists( $slug, $collect_opt ) ) {

				if ( file_exists( $collect_opt[ $slug ] . '.pot' ) ) {
					$file = $collect_opt[ $slug ] . '.pot';
				} elseif ( file_exists( $collect_opt[ $slug ] . '.po' ) ) {
					$file = $collect_opt[ $slug ] . '.po';
				} elseif ( file_exists( preg_replace( '#[^/]*$#', '', $collect_opt[ $slug ] ) . 'default.pot' ) ) {
					$file = preg_replace( '#[^/]*$#', '', $collect_opt[ $slug ] ) . 'default.pot';
				} elseif ( file_exists( preg_replace( '#[^/]*$#', '', $collect_opt[ $slug ] ) . 'default.po' ) ) {
					$file = preg_replace( '#[^/]*$#', '', $collect_opt[ $slug ] ) . 'default.po';
				}
			} else {

				if ( false !== strpos( $current_tab, 'mtswpt_theme_' ) ) {

					// Theme.
					$theme = str_replace( 'mtswpt_theme_', '', $current_tab );

					if ( file_exists( get_theme_root() . '/' . $theme . '/lang/default.po' ) ) {
						$file = get_theme_root() . '/' . $theme . '/lang/default.po';
					} elseif ( file_exists( get_theme_root() . '/mts_' . $theme . '/lang/default.po' ) ) {
						$file = get_theme_root() . '/mts_' . $theme . '/lang/default.po';
					}
				} else {

					// Plugin.
					$plugin_domain = str_replace( 'mtswpt_plugin_', '', $current_tab );

					$all_tr = get_option( 'mtswpt_translations' );

					if ( isset( $all_tr['plugins'][ 'mtswpt_plugin_' . $plugin_domain ] ) ) {

						$plugin_data = $all_tr['plugins'][ 'mtswpt_plugin_' . $plugin_domain ];

						$lang_dir = trailingslashit( $plugin_data['plugin_dir'] ) . untrailingslashit( $plugin_data['domain_path'] );

						if ( file_exists( $lang_dir . '/' . $plugin_data['domain'] . '.po' ) ) {
							$file = $lang_dir . '/' . $plugin_data['domain'] . '.po';
						} elseif ( file_exists( $lang_dir . '/' . $plugin_data['domain'] . '.pot' ) ) {
							$file = $lang_dir . '/' . $plugin_data['domain'] . '.pot';
						} elseif ( file_exists( $lang_dir . '/default.po' ) ) {
							$file = $lang_dir . '/default.po';
						} elseif ( file_exists( $lang_dir . '/default.pot' ) ) {
							$file = $lang_dir . '/default.pot';
						}
					}
				}
			}
		} else {

			// Use user path if possible.
			$extension = strtolower( pathinfo( $mtswpt_path, PATHINFO_EXTENSION ) );

			// Make sure there is .po/.pot at the end.
			if ( 'po' === $extension || 'pot' === $extension ) {
				$mtswpt_full_path = trailingslashit( WP_CONTENT_DIR ) . untrailingslashit( ltrim( $mtswpt_path, '/' ) );
				if ( file_exists( $mtswpt_full_path ) ) {
					$file = $mtswpt_full_path;
				}
			}
		}

		return $file;
	}

	/**
	 * Translation panel pagination.
	 *
	 * @param  integer $items_number   Total number of items.
	 * @param  integer $items_per_page Items per page.
	 * @param  integer $current        Current page.
	 */
	public function translation_pagination( $items_number, $items_per_page, $current = 1 ) {
		$max_page = ceil( $items_number / $items_per_page );
		echo '<div class="mts_translation_pagination">';
		echo '<span class="mts_translation_pagination_label">' . esc_html__( 'Page:', 'my-wp-translate' ) . '</span>';

		for ( $i = 1; $i <= $max_page; $i++ ) {
			echo '<a href="#"' . ( $i === $current ? ' class="current"' : '' ) . '>' . esc_html( $i ) . '</a> ';
		}

		echo '</div>';
	}

	/**
	 * Get .po file for download.
	 *
	 * @since    1.0.0
	 */
	public function get_po() {

		global $pagenow;

		$data = wp_unslash( $_GET );
		if ( 'admin.php' === $pagenow && current_user_can( 'export' ) && isset( $data['page'] ) && $data['page'] === $this->plugin_name && isset( $data['download'] ) && '1' === $data['download'] ) {

			header( 'Content-type: application/x-msdownload' );
			header( 'Content-Disposition: attachment; filename=' . $data['tab'] . '.po' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
			echo $this->get_po_contents( $data['tab'] );
			exit();
		}
	}

	/**
	 * Return contents of new po file.
	 *
	 * @param  string $tab Current tab.
	 * @return mixed
	 */
	public function get_po_contents( $tab ) {

		$file = $this->find_lang_file( $tab );
		$output = '';
		if ( empty( $file ) ) {
			return $output;
		}

		$poparser = new MY_WP_Translate_Po_Parser();

		$entries = $poparser->read( $file );

		// $name = str_replace( array( 'mts_', 'mts-' ), '', $tab );
		$strings_to_update = get_option( $tab . '_strings', array() );

		foreach ( $strings_to_update as $key => $value ) {
			$poparser->update_entry( $key, $value );
		}

		$output = $poparser->output( $file );

		return $output;
	}

	/**
	 * Remove MyThemeShop themes string translation.
	 *
	 * @since    1.0.0
	 */
	public function remove_theme_custom_translate() {
		remove_filter( 'gettext', 'custom_translate', 20, 3 );
	}

	/**
	 * Return translated string in plural or singular form.
	 *
	 * @param  string $translation Translated text.
	 * @param  string $single      Singular text.
	 * @param  string $plural      Plural text.
	 * @param  string $number      Number.
	 * @param  string $domain      Domain.
	 * @return string
	 * 
	 * @since    1.1
	 */
	public function custom_translate_n( $translation, $single, $plural, $number, $domain ) {
		return $this->custom_translate( $translation, ( $number == 1 ? $single : $plural ), $domain );
	}

	/**
	 * Return correct translated string based on context.
	 *
	 * @param  string $translation Translated text.
	 * @param  string $single      Singular text.
	 * @param  string $plural      Plural text.
	 * @param  string $number      Number.
	 * @param  string $domain      Domain.
	 * @return string
	 * 
	 * @since    1.1
	 */
	public function custom_translate_x( $translation, $text, $context, $domain ) {
		return $this->custom_translate( $translation, $text, $domain, $context );
	}

	/**
	 * Return translated string in plural or singular form based on context.
	 *
	 * @param  string $translation Translated text.
	 * @param  string $single      Singular text.
	 * @param  string $plural      Plural text.
	 * @param  string $number      Number.
	 * @param  string $domain      Domain.
	 * @return string
	 * 
	 * @since    1.1
	 */
	public function custom_translate_nx( $translation, $single, $plural, $number, $context, $domain ) {
		return $this->custom_translate( $translation, ( $number == 1 ? $single : $plural ), $domain, $context );
	}

	/**
	 * Return translated string.
	 *
	 * @param  string $translated_text Translated text.
	 * @param  string $text            Text.
	 * @param  string $domain          Domain.
	 * @return string
	 */
	public function custom_translate( $translated_text, $text, $domain, $context = '' ) {
		if ( $context ) {
			$text = $text . $this->context_marker . $context;
		}

		if ( 'mythemeshop' === $domain || 'nhp-opts' === $domain ) {

			$theme = wp_get_theme();

			$mts_theme_translate = get_option( 'mtswpt_theme_' . $theme->template, array() );
			if ( ! empty( $mts_theme_translate ) && isset( $mts_theme_translate['translate'] ) ) {

				$name = str_replace( array( 'mts_', 'mts-' ), '', $theme->template );

				$mts_theme_translations = get_option( 'mtswpt_theme_' . $name . '_strings', array() );

				if ( $mts_theme_translations && ! empty( $mts_theme_translations[ $text ] ) ) {
					$translated_text = $mts_theme_translations[ $text ];
				}
			}
		}

		$mtswpt_theme_translate = get_option( 'mtswpt_theme_' . $domain, array() );
		if ( ! empty( $mtswpt_theme_translate ) && isset( $mtswpt_theme_translate['translate'] ) ) {

			$name = str_replace( array( 'mts_', 'mts-' ), '', $domain );
			$mtswpt_theme_translations = get_option( 'mtswpt_theme_' . $name . '_strings', array() );

			if ( ! empty( $mtswpt_theme_translate ) && isset( $mtswpt_theme_translate['translate'] ) && ! empty( $mtswpt_theme_translations ) && ! empty( $mtswpt_theme_translations[ $text ] ) ) {
				$translated_text = $mtswpt_theme_translations[ $text ];
			}
		}

		$mtswpt_theme_translate_alt = get_option( 'mtswpt_theme_mts_' . $domain, array() );
		if ( ! empty( $mtswpt_theme_translate_alt ) && isset( $mtswpt_theme_translate_alt['translate'] ) ) {

			$name = str_replace( array( 'mts_', 'mts-' ), '', $domain );

			$mtswpt_theme_translations = get_option( 'mtswpt_theme_' . $name . '_strings', array() );

			if ( ! empty( $mtswpt_theme_translate_alt ) && isset( $mtswpt_theme_translate_alt['translate'] ) && ! empty( $mtswpt_theme_translations ) && ! empty( $mtswpt_theme_translations[ $text ] ) ) {
				$translated_text = $mtswpt_theme_translations[ $text ];
			}
		}

		// If plugin have free and pro versions.
		$free_pro_versions = $this->get_shared_textdomains();
		if ( in_array( $domain, $free_pro_versions ) ) {

			$mtswpt_domains_arr = get_option( 'mtswpt_domains', array() );

			if ( isset( $mtswpt_domains_arr[ $domain ] ) ) {
				$domain = ( false !== strpos( $mtswpt_domains_arr[ $domain ], $domain . '-pro' ) ) ? $domain . '_pprroo' : $domain . '_ffrree';
			}
		}

		$mtswpt_plugin_translate = get_option( 'mtswpt_plugin_' . $domain, array() );
		if ( ! empty( $mtswpt_plugin_translate ) && isset( $mtswpt_plugin_translate['translate'] ) ) {

			$mtswpt_plugin_translations = get_option( 'mtswpt_plugin_' . $domain . '_strings', array() );

			if ( ! empty( $mtswpt_plugin_translations ) && ! empty( $mtswpt_plugin_translations[ $text ] ) ) {
				$translated_text = $mtswpt_plugin_translations[ $text ];
			}
		}

		return $translated_text;
	}

	/**
	 * Disable Translation Panel inside MyThemeShop themes options panel.
	 *
	 * @param  array $args Arguments.
	 * @return array
	 */
	public function disable_theme_options_panel_translate( $args ) {

		if ( isset( $args['show_translate'] ) ) {
			$args['show_translate'] = false;
		}

		return $args;
	}

	/**
	 * Grab active theme and plugins domains and .mo path on 'load_textdomain' action.
	 *
	 * @param  string $domain Text domain.
	 * @param  string $mopath MO file path.
	 * @return string
	 */
	public function get_textdomains( $domain, $mopath ) {

		$collect_opt = get_option( 'mtswpt_domains', array() );

		$override_arr = $this->get_shared_textdomains();

		if ( ( in_array( $domain, $override_arr ) || ! array_key_exists( $domain, $collect_opt ) ) && strpos( $mopath, 'wp-content/languages' ) === false && 'default' !== $domain ) {
			$collect_opt[ $domain ] = preg_replace( '#[^/]*$#', '', $mopath ) . $domain;
			update_option( 'mtswpt_domains', $collect_opt );
		}

		return $domain;
	}

	/**
	 * Get domains of plugins that have free/pro versions.
	 *
	 * @since    1.0.0
	 */
	public function get_shared_textdomains() {

		/**
		 * Always get new path in case of plugins that have the same textdomain ( MyThemeShop free/pro plugins by default )
		 * Filter to pass plugin/theme textdomain/s for which lang path will always be refreshed.
		 *
		 * @since    1.0.0
		 */
		$textdomains = apply_filters(
			'mtswpt_free_pro_versions',
			array(
				'wp-review',
				'wp-subscribe',
				'my-wp-backup',
				'wpmm',
				'wp-tab-widget',
			)
		);

		return $textdomains;
	}

	/**
	 * Add new entries to handle plural translations: _n().
	 *
	 * @since    1.1
	 */
	function add_plural_entries( $entries ) {
		$new = array();
		foreach ( $entries as $key => $value ) {
			if ( isset( $value['msgid_plural'] ) && is_array( $value['msgid_plural'] ) ) {
				$value['msgid'] = $value['msgid_plural'];
				$new[$key . '|plural'] = $value;
			}
		}

		return array_merge( $entries, $new );
	}

	/**
	 * Transform entries to handle translations with context: _n() & _nx().
	 *
	 * @since    1.1
	 */
	function append_context_to_entries( $entries ) {
		foreach ( $entries as $key => $value ) {
			if ( isset( $value['msgctxt'] ) && is_array( $value['msgctxt'] ) ) {
				$new_msgid = implode( '', $value['msgid'] ) . $this->context_marker . implode( '', $value['msgctxt'] );
				$entries[ $key ] = $value;
				$entries[ $key ]['msgid'] = array( $new_msgid );
			}
		}

		return $entries;
	}

	// AJAX Helpers ------------------------------------------------

	/**
	 * Display translation panel.
	 *
	 * @since    1.0.0
	 */
	public function ajax_translation_panel() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$data = wp_unslash( $_POST );
		$current_tab = isset( $data['tab'] ) ? sanitize_text_field( $data['tab'] ) : '';

		if ( empty( $current_tab ) ) {
			echo 'no_tab';
			exit;
		}

		$file = $this->find_lang_file( $current_tab );

		if ( empty( $file ) ) {
			echo 'no_file';
			exit;
		}

		$mts_translations = array();
		$mts_translations_opt = get_option( $current_tab . '_strings' );
		if ( $mts_translations_opt ) {

			$mts_translations = $mts_translations_opt;

		} elseif ( false !== strpos( $current_tab, 'mtswpt_theme_mts_' ) || false !== strpos( $current_tab, 'mtswpt_theme_mts-' ) ) {

			$curr_tab = str_replace( array( 'mts_', 'mts-' ), '', $current_tab );
			$alt_mts_translations_opt = get_option( $curr_tab . '_strings' );

			if ( $alt_mts_translations_opt ) {

				$mts_translations = $alt_mts_translations_opt;
			}
		}

		$poparser = new MY_WP_Translate_Po_Parser();
		$entries = $poparser->read( $file );
		$entries = $this->add_plural_entries( $entries );
		$entries = $this->append_context_to_entries( $entries );

		$i = 0;

		$page = empty( $data['page'] ) ? 1 : (int) $data['page'] ;
		$search_query = empty( $data['search'] ) ? '' : $data['search'];
		$strings_per_page = empty( $data['per_page'] ) ? 20 : (int) $data['per_page'];

		$tr_options = get_option( 'mtswpt_translations' );
		$per_page = isset( $tr_options['strings_per_page'] ) ? $tr_options['strings_per_page'] : 20;
		if ( $per_page !== $strings_per_page ) {
			$tr_options['strings_per_page'] = $strings_per_page;
			update_option( 'mtswpt_translations', $tr_options );
		}

		$strings_tmp = array();
		if ( $search_query ) {
			foreach ( $entries as $string_id => $object ) {
				$message = '';
				$message_plural = '';
				foreach ( $object['msgid'] as $line ) {
					$message .= $line;
				}

				$value = empty( $mts_translations[ $message ] ) ? '' : $mts_translations[ $message ];
				if ( false !== stristr( $value, $search_query ) || false !== stristr( $message, $search_query ) ) {
					$strings_tmp[ $string_id ] = $object;
				}
			}
			$entries = $strings_tmp;
		}
		$number = count( $entries );
		$number_translated = 0;

		$this->translation_pagination( $number, $strings_per_page, $page );

		$form = '';
		foreach ( $entries as $string_id => $object ) {
			$i++;
			$message = '';

			foreach ( $object['msgid'] as $line ) {
				$message .= $line;
			}

			if ( ! empty( $mts_translations[ $message ] ) ) {
				$number_translated++;
			}

			if ( $i > ( $page - 1 ) * $strings_per_page && $i <= $page * $strings_per_page ) {

				$reference = '';
				if ( isset( $object['reference'] ) ) {

					$reference = implode( ' ', $object['reference'] );
					$reference = implode( ', ', explode( ' ', $reference ) );
				}

				$context = '';
				$context_marker = '';
				$context_label = '';
				if ( ! empty( $object['msgctxt'] ) ) {
					$context = implode( ' ', $object['msgctxt'] );
					$context_marker = $this->context_marker . $context;
					$context_label = sprintf( __('Context: %s', 'my-wp-translate'), $context );
				}

				$value = empty( $mts_translations[ $message ] ) ? '' : $mts_translations[ $message ];
				$form .= '<div class="translate-string-wrapper">';

				if ( strpos( $message, $this->context_marker ) ) {
					$message = explode( $this->context_marker, $message )[0];
				}

				$form .= '<label for="translate-string-' . $i . '">' . esc_html( $message ) . ' <span class="string-context">' . $context_label . '</span><span>(' . $reference . ')</span></label>';
				$form .= '<textarea id="translate-string-' . $i . '" data-id="' . _wp_specialchars( $message . $context_marker, ENT_QUOTES, false, true ) . '" class="mts_translate_textarea">';
					$form .= esc_textarea( $value );
				$form .= '</textarea>';
				$form .= '<span class="mts_translate_loading">'.__('Saved', 'my-wp-translate').'</span>';
				$form .= '</div>';
			}
		}

		echo $form;

		if ( 0 === intval( $number ) ) {
			$percent = 0;
		} else {
			$percent = $number_translated / $number * 100;
		}

		echo '<div class="translation_info">' . sprintf( __( 'Translated <span class="translated">%1$d</span> strings out of <span class="total">%2$d</span> <span class="percent">(%3$.2f%%)</span>', 'my-wp-translate' ), $number_translated, $number, $percent ) . '</div>';
		$this->translation_pagination( $number, $strings_per_page, $page );

		// Required for AJAX in WP.
		exit;
	}

	/**
	 * Save single translation instantly.
	 *
	 * @since    1.0.0
	 */
	public function ajax_save_translation() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$data = wp_unslash( $_POST );
		$id = $data['id'];
		$val = $data['val'];
		$current_tab = isset( $data['tab'] ) ? $data['tab'] : '';

		if ( empty( $id ) || ! is_string( $id ) || ! is_string( $val ) ) {
			echo 0;
			exit;
		}

		$mts_translations = array();
		$strings_opt_name = $current_tab . '_strings';
		$mts_translations_opt = get_option( $strings_opt_name );
		if ( $mts_translations_opt ) {

			$mts_translations = $mts_translations_opt;

		} elseif ( false !== strpos( $current_tab, 'mtswpt_theme_mts_' ) || false !== strpos( $current_tab, 'mtswpt_theme_mts-' ) ) {

			$curr_tab = str_replace( array( 'mts_', 'mts-' ), '', $current_tab );
			$alt_mts_translations_opt = get_option( $curr_tab . '_strings' );
			$strings_opt_name = $curr_tab . '_strings';

			if ( $alt_mts_translations_opt ) {
				$mts_translations = $alt_mts_translations_opt;
			}
		}

		if ( empty( $val ) && isset( $mts_translations[ $id ] ) ) {
			unset( $mts_translations[ $id ] );
		} else {
			$mts_translations[ $id ] = $val;
		}

		update_option( $strings_opt_name, $mts_translations );
		echo 1;
		exit;
	}

	/**
	 * Update tabs option when adding new.
	 *
	 * @since    1.0.0
	 */
	public function ajax_add_plugin() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$plugin_domain = sanitize_text_field( $_POST['plugin_domain'] );

		$active_valid_plugins = wp_get_active_and_valid_plugins();
		foreach ( $active_valid_plugins as $plugin_path ) {

			$plugin = get_plugin_data( $plugin_path );

			$text_domain = '';

			// If plugin have free and pro versions.
			$free_pro_versions = $this->get_shared_textdomains();

			// Find textdomain ( if no "Text Domain" in header or the plugin needs to have separate tabs - free/pro ).
			if ( empty( $plugin['TextDomain'] ) || in_array( $plugin['TextDomain'], $free_pro_versions ) ) {

				// Generated option inside "load_textdomain" action ( domain => lang folder path ).
				$mtswpt_domains_arr = get_option( 'mtswpt_domains', array() );
				$plugin_dir_path    = plugin_dir_path( $plugin_path );

				foreach ( $mtswpt_domains_arr as $domain => $lang_path ) {

					if ( false !== strpos( $lang_path, $plugin_dir_path ) ) {

						if ( in_array( $domain, $free_pro_versions ) ) {
							$domain = ( false !== strpos( $plugin_dir_path, $domain . '-pro' ) ) ? $domain . '_pprroo' : $domain . '_ffrree';
						}

						$text_domain = $domain;
						break;
					}
				}
			} else {
				// Take textdomain from plugin headers.
				$text_domain = $plugin['TextDomain'];
			}

			// Maybe not needed...
			if ( empty( $text_domain ) ) {
				$text_domain = sanitize_title( $plugin['Name'] );
			}

			if ( $text_domain === $plugin_domain ) {

				$all_tr = get_option( 'mtswpt_translations' );

				if ( ! isset( $all_tr['plugins'][ 'mtswpt_plugin_' . $plugin_domain ] ) ) {

					$all_tr['plugins'][ 'mtswpt_plugin_' . $plugin_domain ] = array(
						'opt_name' => 'mtswpt_plugin_' . $plugin_domain,
						'name' => $plugin['Name'],
						'domain' => str_replace( array( '_ffrree', '_pprroo' ) , '', $plugin_domain ),
						'plugin_path' => $plugin_path,
						'domain_path' => $plugin['DomainPath'],
						'plugin_dir' => plugin_dir_path( $plugin_path ),
					);

					update_option( 'mtswpt_translations', $all_tr );
				}

				echo '1';
				die();
			}
		}

		echo '0';
		die();
	}

	/**
	 * Remove plugin translation.
	 *
	 * @since    1.0.0
	 */
	public function ajax_remove_plugin() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$plugin_tab = sanitize_text_field( $_POST['plugin_tab'] );

		$all_tr = get_option( 'mtswpt_translations' );
		if ( isset( $all_tr['plugins'][ $plugin_tab ] ) ) {
			unset( $all_tr['plugins'][ $plugin_tab ] );
		}
		update_option( 'mtswpt_translations', $all_tr );

		$mtswpt_domains_arr = get_option( 'mtswpt_domains', array() );
		$domain = str_replace( 'mtswpt_plugin_' , '', $plugin_tab );
		$domain = str_replace( array( '_ffrree', '_pprroo' ) , '', $domain );
		if ( isset( $mtswpt_domains_arr[ $domain ] ) ) {
			unset( $mtswpt_domains_arr[ $domain ] );
		}
		update_option( 'mtswpt_domains', $mtswpt_domains_arr );

		if ( false !== get_option( $plugin_tab, false ) ) {
			delete_option( $plugin_tab );
		}

		if ( false !== get_option( $plugin_tab . '_strings', false ) ) {
			delete_option( $plugin_tab . '_strings' );
		}

		die();
	}

	/**
	 * Save enabled/disabled state.
	 *
	 * @since    1.0.0
	 */
	public function ajax_save_state() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$tab = sanitize_text_field( $_POST['tab'] );
		$tab_option = get_option( $tab, array() );

		if ( isset( $tab_option['translate'] ) ) {
			unset( $tab_option['translate'] );
		} else {
			$tab_option['translate'] = '1';
		}

		update_option( $tab, $tab_option );
		die();
	}

	/**
	 * Import strings.
	 *
	 * @since    1.0.0
	 */
	public function ajax_import_strings() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$data = wp_unslash( $_POST );
		$tab = $data['tab'];
		$strings_opt_name = $data['strings_opt_name'];
		$import_code_arr = json_decode( $data['import_code'], true );

		if ( is_array( $import_code_arr ) ) {
			update_option( $strings_opt_name, $import_code_arr );
			echo '1';
		} else {
			echo '<p class="mtswpt-import-error">' . esc_html__( 'Something is wrong with import data. Please paste valid code and try again.', 'my-wp-translate' ) . '</p>';
		}

		die();
	}

	/**
	 * Update Export textarea code.
	 *
	 * @since    1.0.0
	 */
	public function ajax_update_export_code() {

		check_ajax_referer( 'my-wp-translate', 'security' );

		$data = wp_unslash( $_POST );
		$tab = $data['tab'];
		$strings_opt_name = $data['strings_opt_name'];

		$mts_translations = get_option( $strings_opt_name, array() );
		$export_val = empty( $mts_translations ) ? '' : wp_json_encode( $mts_translations );

		echo $export_val;
		die();
	}
}
