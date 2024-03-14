<?php
/**
 * Displays the content on the plugin settings page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gglstmp_Settings_Tabs' ) ) {
	class Gglstmp_Settings_Tabs extends Bws_Settings_Tabs {
		/**
		 * Htaccess options
		 *
		 * @var bool
		 */
		public $htaccess_options = false;
		/**
		 * Htaccess active flag
		 *
		 * @var bool
		 */
		public $htaccess_active = false;
		/**
		 * Robots option
		 *
		 * @var array
		 */
		public $robots;
		/**
		 * Htaccess pth array
		 *
		 * @var array
		 */
		public $htaccess = '';
		/**
		 * Google Client
		 *
		 * @var object
		 */
		public $client = '';
		/**
		 * Blog prefix for multisite
		 *
		 * @var string
		 */
		public $blog_prefix = '';
		/**
		 * Info for sitemap (create/delete/add)
		 *
		 * @var string
		 */
		public $manage_info = '';
		/**
		 * All post type array
		 *
		 * @var array
		 */
		public $all_post_types;
		/**
		 * All taxonomies array
		 *
		 * @var array
		 */
		public $all_taxonomies;

		/**
		 * Constructor.
		 *
		 * @access public
		 *
		 * @see Bws_Settings_Tabs::__construct() for more information on default arguments.
		 *
		 * @param string $plugin_basename Plugin slug.
		 */
		public function __construct( $plugin_basename ) {
			global $gglstmp_options, $gglstmp_plugin_info;

			$tabs = array(
				'settings' => array( 'label' => esc_html__( 'Settings', 'google-sitemap-plugin' ) ),
				'display'  => array( 'label' => esc_html__( 'Structure', 'google-sitemap-plugin' ) ),
				'misc'     => array( 'label' => esc_html__( 'Misc', 'google-sitemap-plugin' ) ),
				/*pls */
				'license'  => array( 'label' => esc_html__( 'License Key', 'google-sitemap-plugin' ) ),
				/* pls*/
			);

			parent::__construct(
				array(
					'plugin_basename' => $plugin_basename,
					'plugins_info'    => $gglstmp_plugin_info,
					'prefix'          => 'gglstmp',
					'default_options' => gglstmp_get_options_default(),
					'options'         => $gglstmp_options,
					'tabs'            => $tabs,
					/*pls */
					'wp_slug'         => 'google-sitemap-plugin',
					'link_key'        => '28d4cf0b4ab6f56e703f46f60d34d039',
					'link_pn'         => '83',
					/* pls*/
					'doc_link'        => 'https://bestwebsoft.com/documentation/sitemap/sitemap-user-guide/',
					'doc_video_link'  => 'https://www.youtube.com/watch?v=hzz0_Yj4gaQ',
				)
			);

			add_action( get_parent_class( $this ) . '_additional_misc_options', array( $this, 'additional_misc_options' ) );

			$this->robots = get_option( 'gglstmp_robots' );

			/* Check htaccess plugin */
			if ( $this->is_multisite && ! is_subdomain_install() ) {
				$all_plugins            = get_plugins();
				$this->htaccess         = gglstmp_plugin_status(
					array(
						'htaccess/htaccess.php',
						'htaccess-pro/htaccess-pro.php',
					),
					$all_plugins,
					false
				);
				$this->htaccess_options = false;
				if ( 'actived' === $this->htaccess['status'] ) {
					global $htccss_options;
					register_htccss_settings();
					$this->htaccess_options = &$htccss_options;
					$this->htaccess_active  = true;
					if ( function_exists( 'htccss_check_xml_access' ) ) {
						$htaccess_check = htccss_check_xml_access();
						if ( absint( $this->htaccess_options['allow_xml'] ) !== $htaccess_check ) {
							$this->htaccess_options['allow_xml'] = $htaccess_check;
							update_site_option( 'htccss_options', $this->htaccess_options );
						}
					}
				}
			}

			if ( function_exists( 'curl_init' ) ) {
				$this->client      = gglstmp_client();
				$this->blog_prefix = '_' . get_current_blog_id();
				if ( ! isset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] ) && isset( $this->options['authorization_code'] ) ) {
					$_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] = $this->options['authorization_code'];
				}
				if ( isset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] ) ) {
					$this->client->setAccessToken( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] );
				}
			}

			$this->all_post_types = get_post_types( array( 'public' => true ), 'objects' );
			unset( $this->all_post_types['attachment'] );

			$this->all_taxonomies = array(
				'category' => esc_html__( 'Post category', 'google-sitemap-plugin' ),
				'post_tag' => esc_html__( 'Post tag', 'google-sitemap-plugin' ),
			);

			add_filter(
				get_parent_class( $this ) . '_additional_restore_options',
				array(
					$this,
					'additional_restore_options',
				)
			);
			add_filter( get_parent_class( $this ) . '_display_custom_messages', array( $this, 'display_custom_messages' ) );
			add_filter( get_parent_class( $this ) . '_information_postbox_top', array( $this, 'information_postbox_top' ) );
		}

		/**
		 * Save plugin options to the database
		 *
		 * @access public
		 *
		 * @return array    The action results
		 */
		public function save_options() {
			global $wpdb;

			$message       = '';
			$notice        = '';
			$error         = '';
			$sitemapcreate = false;
			$reschedule    = false;

			if ( isset( $_POST['gglstmp_save_info'] )
				&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gglstmp_save_info'] ) ), 'gglstmp_save_info_action' ) ) {
				if ( isset( $_POST['gglstmp_logout'] ) ) {
					unset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ], $this->options['authorization_code'] );
					update_option( 'gglstmp_options', $this->options );
				} elseif ( isset( $_POST['gglstmp_authorize'] ) && ! empty( $_POST['gglstmp_authorization_code'] ) ) {
					try {
						$this->client->authenticate( sanitize_text_field( wp_unslash( $_POST['gglstmp_authorization_code'] ) ) );
						$this->options['authorization_code'] = $this->client->getAccessToken();

						$_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ] = $this->options['authorization_code'];
						update_option( 'gglstmp_options', $this->options );
					} catch ( Exception $e ) {
						/* Skip */
					}
				} elseif ( isset( $_POST['gglstmp_menu_add'] ) || isset( $_POST['gglstmp_menu_delete'] ) || isset( $_POST['gglstmp_menu_info'] ) ) {
					if ( $this->client->getAccessToken() && false === $this->client->isAccessTokenExpired() ) {
						$webmasters        = new Google_Service_Webmasters( $this->client );
						$site_verification = new Google_Service_SiteVerification( $this->client );
						if ( isset( $_POST['gglstmp_menu_info'] ) ) {
							$this->manage_info .= gglstmp_get_site_info( $webmasters, $site_verification );
						} elseif ( isset( $_POST['gglstmp_menu_add'] ) ) {
							$this->manage_info .= gglstmp_add_site( $webmasters, $site_verification );
						} else {
							$this->manage_info .= gglstmp_delete_site( $webmasters, $site_verification );
						}
					}
				} else {
					if ( $this->htaccess_active && $this->htaccess_options && function_exists( 'htccss_generate_htaccess' ) ) {
						$gglstmp_allow_xml = ( ! empty( $_POST['gglstmp_allow_xml'] ) ) ? 1 : 0;
						if ( $gglstmp_allow_xml !== $this->htaccess_options['allow_xml'] ) {
							$this->htaccess_options['allow_xml'] = $gglstmp_allow_xml;
							update_site_option( 'htccss_options', $this->htaccess_options );
							htccss_generate_htaccess();
						}
					}

					$filter_param = apply_filters( 'gglstmp_save_frequency_options', $this->options, $sitemapcreate );
					if ( ! empty( $filter_param['frequency_options'] ) && ! empty( $filter_param['sitemapcreate'] ) ) {
						$this->options = array_merge( $this->options, $filter_param['frequency_options'] );
						$sitemapcreate = $filter_param['sitemapcreate'];
					}

					$post_types = array();
					$taxonomies = array();
					if ( isset( $_POST['gglstmp_save_taxonomy_info'] )
						&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gglstmp_save_taxonomy_info'] ) ), 'gglstmp_save_taxonomy_action' )
						&& ! empty( $_POST['gglstmp_post_types'] ) ) {
						foreach ( (array) $_POST['gglstmp_post_types'] as $type ) {
							if ( array_key_exists( sanitize_text_field( wp_unslash( $type ) ), $this->all_post_types ) ) {
								$post_types[] = sanitize_text_field( wp_unslash( $type ) );
							}
						}
					}
					if ( isset( $_POST['gglstmp_save_taxonomy_info'] )
						&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gglstmp_save_taxonomy_info'] ) ), 'gglstmp_save_taxonomy_action' )
						&& ! empty( $_POST['gglstmp_taxonomies'] ) ) {
						foreach ( (array) $_POST['gglstmp_taxonomies'] as $tax ) {
							if ( array_key_exists( sanitize_text_field( wp_unslash( $tax ) ), $this->all_taxonomies ) ) {
								$taxonomies[] = sanitize_text_field( wp_unslash( $tax ) );
							}
						}
					}

					if ( $this->options['post_type'] !== $post_types || $this->options['taxonomy'] !== $taxonomies ) {
						$sitemapcreate = true;
					}

					$this->options['post_type'] = $post_types;
					$this->options['taxonomy']  = $taxonomies;

					if ( isset( $_POST['gglstmp_limit'] ) ) {
						if ( absint( $_POST['gglstmp_limit'] ) !== $this->options['limit'] ) {
							$sitemapcreate = true;
						}

						$this->options['limit'] = ( absint( $_POST['gglstmp_limit'] ) >= 1000 && absint( $_POST['gglstmp_limit'] ) <= 50000 ) ? absint( $_POST['gglstmp_limit'] ) : 50000;
					}

					if ( ( empty( $this->options['alternate_language'] ) && isset( $_POST['gglstmp_alternate_language'] ) ) || ( ! empty( $this->options['alternate_language'] ) && ! isset( $_POST['gglstmp_alternate_language'] ) ) ) {
						$sitemapcreate = true;
					}

					$split_sitemap = isset( $_POST['gglstmp_split_sitemap'] ) ? 1 : 0;

					if ( $this->options['split_sitemap'] != $split_sitemap ) {
						$sitemapcreate = true;
						$reschedule    = true;
					}

					$this->options['split_sitemap'] = $split_sitemap;

					$split_items                    = array();

					if ( ! empty( $_POST['gglstmp_split_sitemap_item'] ) ) {
						foreach ( (array) $_POST['gglstmp_split_sitemap_item'] as $item ) {
							if ( array_key_exists( sanitize_text_field( wp_unslash( $item ) ), $this->all_post_types ) || array_key_exists( sanitize_text_field( wp_unslash( $item ) ), $this->all_taxonomies ) ) {
								$split_items[] = sanitize_text_field( wp_unslash( $item ) );
							}
						}
					}
					if ( $this->options['split_sitemap_items'] !== $split_items ) {
						$sitemapcreate = true;
						$reschedule = true;
					}
					$this->options['split_sitemap_items'] = $split_items;

					$this->options['remove_automatic_canonical'] = isset( $_POST['gglstmp_automatic_canonical'] ) ? 1 : 0;
					$this->options['remove_all_canonical'] = isset( $_POST['gglstmp_all_canonical'] ) ? 1 : 0;

					$media_sitemap = isset( $_POST['gglstmp_media_sitemap'] ) ? 1 : 0;
					if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-pro/wp-seo-pro.php' ) ) {
						if ( isset( $_POST['disable_yoast_xml_sitemap'] ) ) {
							WPSEO_Options::set( 'enable_xml_sitemap', false );
						} else {
							WPSEO_Options::set( 'enable_xml_sitemap', true );
						}
					}
					if ( absint( $this->options['media_sitemap'] ) !== $media_sitemap ) {
						$sitemapcreate = true;
					}
					$this->options['media_sitemap']  = $media_sitemap;
					$this->options['images_quality'] = isset( $_POST['gglstmp_images_quality'] ) && in_array( sanitize_text_field( wp_unslash( $_POST['gglstmp_images_quality'] ) ), array( 'thumbnail', 'medium', 'large', 'full' ) ) ? sanitize_text_field( wp_unslash( $_POST['gglstmp_images_quality'] ) ) : 'full';

					$this->robots                        = isset( $_POST['gglstmp_checkbox'] ) ? 1 : 0;
					$this->options['alternate_language'] = isset( $_POST['gglstmp_alternate_language'] ) ? 1 : 0;

					if ( isset( $_POST['gglstmp_client_id'] ) && ! empty( $_POST['gglstmp_client_id'] ) ) {
						$this->options['client_id'] = trim( sanitize_text_field( wp_unslash( $_POST['gglstmp_client_id'] ) ) );
					}
					if ( isset( $_POST['gglstmp_client_secret'] ) && ! empty( $_POST['gglstmp_client_secret'] ) ) {
						$this->options['client_secret'] = trim( sanitize_text_field( wp_unslash( $_POST['gglstmp_client_secret'] ) ) );
					}

					if ( isset( $_POST['gglstmp_remove_access'] ) ) {
						unset( $this->options['client_id'] );
						unset( $this->options['client_secret'] );
					}

					update_option( 'gglstmp_robots', $this->robots );
					update_option( 'gglstmp_options', $this->options );

					if ( $sitemapcreate ) {
						gglstmp_schedule_sitemap( false, false, true );
					}

					$message = esc_html__( 'Settings saved.', 'google-sitemap-plugin' );
				}
			}

			return compact( 'message', 'notice', 'error' );
		}

		/**
		 * Display tab settings
		 */
		public function tab_settings() { ?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Sitemap Settings', 'google-sitemap-plugin' ); ?></h3>
			<?php
			$this->help_phrase();
			global $wp_version;
			if ( ! $this->all_plugins ) {
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$this->all_plugins = get_plugins();
			}
			?>
			<hr>
			<table class="form-table gglstmp_settings_form">
				<?php if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-pro/wp-seo-pro.php' ) ) { ?>
					<tr>
						<th><?php esc_html_e( 'Yoast Sitemap', 'google-sitemap-plugin' ); ?></th>
						<td>
							<input type='checkbox' name="disable_yoast_xml_sitemap" value="1" <?php checked( WPSEO_Options::get( 'enable_xml_sitemap' ), false ); ?>/>
							<span class="bws_info"><?php esc_html_e( 'Enable to activate the sitemap (Yoast sitemap will be deactivated).', 'google-sitemap-plugin' ); ?></span>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<th>Robots.txt</th>
					<td>
						<input type='checkbox' id="gglstmp_checkbox" name="gglstmp_checkbox" value="1" <?php checked( $this->robots, 1 ); ?> />
						<span class="bws_info">
							<?php
							printf(
								esc_html_x( 'Enable to add a sitemap file path to the %s file.', '%robots.txt file link%', 'google-sitemap-plugin' ),
								'<a href="' . esc_url( home_url( '/robots.txt' ) ) . '" target="_blank">robots.txt</a>'
							);
							?>
						</span>
					</td>
				</tr>
			</table>
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options"
								class="notice-dismiss bws_hide_premium_options"
								title="<?php esc_html_e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<table class="form-table bws_pro_version">
							<tr class="gglstmp_file_name">
								<th><?php esc_html_e( 'Sitemap Filename', 'google-sitemap-plugin' ); ?></th>
								<td>
									<input class="regular-text" type="text" maxlength="150" autocomplete="off" disabled="disabled" />.xml				
									<div class="bws_info"><?php esc_html_e( 'The default name is sitemap.xml. For multisite the default name is sitemap_blog_id.xml', 'google-sitemap-plugin' ); ?></div>
								</td>
							</tr>
						</table>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th><?php esc_html_e( 'Disable automatic canonical tag', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type='checkbox' <?php echo esc_html( $this->change_permission_attr ); ?> name="gglstmp_automatic_canonical" value="1" <?php checked( $this->options['remove_automatic_canonical'], 1 ); ?> />
						<span class="bws_info"><?php esc_html_e( 'Enable to remove automatic canonical tag with standart WP url', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Disable all canonical tag', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type='checkbox' <?php echo esc_html( $this->change_permission_attr ); ?> name="gglstmp_all_canonical" value="1" <?php checked( $this->options['remove_all_canonical'], 1 ); ?> />
						<span class="bws_info"><?php esc_html_e( 'Enable to remove all canonical tag from plugin', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Media Sitemap', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type='checkbox' name="gglstmp_media_sitemap" value="1" <?php checked( $this->options['media_sitemap'], 1 ); ?> />
						<span class="bws_info"><?php esc_html_e( 'Enable to create separate sitemap files for images and videos.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Images quality', 'google-sitemap-plugin' ); ?></th>
					<td>
						<select name="gglstmp_images_quality">
							<option value="thumbnail" <?php selected( $this->options['images_quality'], 'thumbnail' ); ?>><?php esc_html_e( 'Thumbnail', 'google-sitemap-plugin' ); ?></option>
							<option value="medium" <?php selected( $this->options['images_quality'], 'medium' ); ?>><?php esc_html_e( 'Medium', 'google-sitemap-plugin' ); ?></option>
							<option value="large" <?php selected( $this->options['images_quality'], 'large' ); ?>><?php esc_html_e( 'Large', 'google-sitemap-plugin' ); ?></option>
							<option value="full" <?php selected( $this->options['images_quality'], 'full' ); ?>><?php esc_html_e( 'Full', 'google-sitemap-plugin' ); ?></option>
						</select>
						<div class="bws_info"><?php esc_html_e( 'Choosing the quality of images in the sitemap. If there is no image of the required size, a full-size image will be used.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<?php
				if ( $this->is_multisite && ! is_subdomain_install() ) {
					$attr_checked           = '';
					$attr_disabled          = '';
					$htaccess_plugin_notice = __( 'This option will be applied to all websites in the network.', 'google-sitemap-plugin' );
					if ( 'deactivated' === $this->htaccess['status'] ) {
						$attr_disabled          = 'disabled="disabled"';
						$htaccess_plugin_notice = '<a href="' . network_admin_url( '/plugins.php' ) . '">' . __( 'Activate', 'google-sitemap-plugin' ) . '</a>';
					} elseif ( 'not_installed' === $this->htaccess['status'] ) {
						$attr_disabled          = 'disabled="disabled"';
						$htaccess_plugin_notice = '<a href="https://bestwebsoft.com/products/wordpress/plugins/htaccess/?k=bc745b0c9d4b19ba95ae2c861418e0df&pn=106&v=' . $this->plugins_info['Version'] . '&wp_v=' . $wp_version . '">' . __( 'Install Now', 'google-sitemap-plugin' ) . '</a>';
					}
					if ( ! empty( $this->htaccess_options['allow_xml'] ) && empty( $attr_disabled ) ) {
						$attr_checked = 'checked="checked"';
					}
					?>
					<tr id="gglstmp_allow_xml_block">
						<th><?php printf( esc_html__( '%s Plugin', 'google-sitemap-plugin' ), 'Htaccess' ); ?></th>
						<td>
							<input <?php printf( '%s %s', esc_html( $attr_checked ), esc_html( $attr_disabled ) ); ?> type="checkbox" name="gglstmp_allow_xml" value="1" />
							<span class="bws_info"><?php printf( esc_html__( 'Enable to allow XML files access using %s plugin.', 'google-sitemap-plugin' ), 'Htaccess' ); ?><?php echo esc_html( $htaccess_plugin_notice ); ?></span>
							<?php echo wp_kses_post( bws_add_help_box( esc_html__( 'The following string will be added to your .htaccess file', 'google-sitemap-plugin' ) . ': <code>RewriteRule ([^/]+\.xml)$ $1 [L]</code>' ) ); ?>
						</td>
					</tr>
					<?php
				}
				do_action( 'gglstmp_display_settings_frequency', $this->options );
				?>
			</table>		  
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options"
								class="notice-dismiss bws_hide_premium_options"
								title="<?php esc_html_e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<table class="form-table bws_pro_version">
							<?php gglstmp_frequency_block(); ?>
						</table>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th><?php esc_html_e( 'Split Sitemap', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type='checkbox' id="gglstmp_split_sitemap" name="gglstmp_split_sitemap" value="1" <?php checked( $this->options['split_sitemap'], 1 ); ?> />
						<span class="bws_info"><?php esc_html_e( 'Create a main sitemap with separate child sitemaps for post, pages, categories', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr class="gglstmp_split_sitemap_items <?php echo 1 !== $this->options['split_sitemap'] ? 'hidden' : ''; ?>">
					<th><?php esc_html_e( 'Split Sitemap Items', 'google-sitemap-plugin' ); ?></th>
					<td>
						<?php
						foreach ( $this->all_post_types as $post_type ) {
							?>
							<label><input type='checkbox' name="gglstmp_split_sitemap_item[]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php checked( is_array( $this->options['split_sitemap_items'] ) && in_array( $post_type->name, $this->options['split_sitemap_items'], true ), 1 ); ?> <?php disabled( ! in_array( $post_type->name, $this->options['post_type'], true ), true ); ?> /><?php echo esc_html( $post_type->label ); ?></label><br />
							<?php
						}
						foreach ( $this->all_taxonomies as $tax_key => $taxonomy ) {
							?>
							<label><input type='checkbox' name="gglstmp_split_sitemap_item[]" value="<?php echo esc_attr( $tax_key ); ?>" <?php checked( is_array( $this->options['split_sitemap_items'] ) && in_array( $tax_key, $this->options['split_sitemap_items'], true ), 1 ); ?> <?php disabled( ! in_array( $tax_key, $this->options['taxonomy'], true ), true ); ?> /><?php echo esc_html( $taxonomy ); ?></label><br />
						<?php } ?>	
						<span class="bws_info"><?php esc_html_e( 'Enable the items on the Structure tab to make them available.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'URLs Limit', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input type="number" name="gglstmp_limit" min="1000" max="50000"
							   value="<?php echo absint( $this->options['limit'] ); ?>"/>
						<div class="bws_info">
							<?php esc_html_e( "A sitemap file can't contain more than 50,000 URLs and must be no larger than 50 MB uncompressed.", 'google-sitemap-plugin' ); ?>
							&nbsp;<a
									href="https://support.google.com/webmasters/answer/183668?ref_topic=4581190#general-guidelines"
									target="_blank"><?php esc_html_e( 'Learn More', 'google-sitemap-plugin' ); ?></a><br/>
							<?php esc_html_e( 'Decrease the limit if your sitemap exceeds file size limit.', 'google-sitemap-plugin' ); ?>
							<?php esc_html_e( 'When the limit is reached, the sitemap will be splitted into multiple files.', 'google-sitemap-plugin' ); ?>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Google Search Console', 'google-sitemap-plugin' ); ?></th>
					<td>
						<?php
						if ( ! $this->client ) {
							esc_html_e( 'This hosting does not support сURL, so you cannot add a sitemap file automatically.', 'google-sitemap-plugin' );
							?>
						<?php } else { ?>
							<?php if ( ! isset( $_POST['gglstmp_logout'] ) && $this->client->getAccessToken() && false === $this->client->isAccessTokenExpired() ) { ?>
						<input class="button-secondary bws_no_bind_notice" name="gglstmp_logout" type="submit" value="<?php esc_html_e( 'Logout', 'google-sitemap-plugin' ); ?>"/>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Manage Website with Google Search Console', 'google-sitemap-plugin' ); ?></th>
					<td>
						<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_add' value="<?php esc_html_e( 'Add', 'google-sitemap-plugin' ); ?>"/>
						<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_delete' value="<?php esc_html_e( 'Delete', 'google-sitemap-plugin' ); ?>"/>
						<input class="button-secondary bws_no_bind_notice" type='submit' name='gglstmp_menu_info' value="<?php esc_html_e( 'Get Info', 'google-sitemap-plugin' ); ?>"/>
						<div class="bws_info">
								<?php esc_html_e( 'Add, delete or get info about this website using your Google Search Console account.', 'google-sitemap-plugin' ); ?>
						</div>
								<?php
								echo wp_kses_post( $this->manage_info );
							} else {
								if ( ! isset( $this->options['client_id'] ) && ! isset( $this->options['client_secret'] ) ) {
									?>
									<table class="form-table">
										<tr>
											<th>
												<?php esc_html_e( 'Client ID', 'adsense-pro' ); ?> <br />
											</th>
											<td>
												<label>
													<input id="adsns_client_id" class="bws_no_bind_notice regular-text" name="gglstmp_client_id" type="text" autocomplete="off" maxlength="150" />
												</label>
											</td>
										</tr>
										<tr>
											<th>
												<?php esc_html_e( 'Client Secret', 'adsense-pro' ); ?> <br />
											</th>
											<td>
												<label>
													<input id="adsns_client_secret" class="bws_no_bind_notice regular-text" name="gglstmp_client_secret" type="text" autocomplete="off" maxlength="150" />
												</label>
											</td>
										</tr>
									</table>
									<?php
								}
								if ( isset( $this->options['client_id'] ) && isset( $this->options['client_secret'] ) ) {
									$this->client = gglstmp_client();
									$gglstmp_state = mt_rand();
									$this->client->setState( $gglstmp_state );
									$_SESSION[ 'gglstmp_state' . $this->blog_prefix ] = $this->client;
									$gglstmp_auth_url                                 = $this->client->createAuthUrl();
									?>
									<a id="gglstmp_authorization_button" class="button-secondary button"
									href="<?php echo esc_url( $gglstmp_auth_url ); ?>" target="_blank"
									onclick="window.open(this.href,'','top='+(screen.height/2-560/2)+',left='+(screen.width/2-240/2)+',width=640,height=560,resizable=0,scrollbars=0,menubar=0,toolbar=0,status=1,location=0').focus(); return false;"><?php esc_html_e( 'Open the Google Search Console', 'google-sitemap-plugin' ); ?></a>
									<input class="button-secondary bws_no_bind_notice" name="gglstmp_remove_access" type="submit" value="<?php esc_html_e( 'Remove Console Access', 'google-sitemap-plugin' ); ?>"/>
									<?php if ( isset( $_POST['gglstmp_authorization_code'] ) && isset( $_POST['gglstmp_authorize'] ) ) { ?>
										<div id="gglstmp_authorize_error"><?php esc_html_e( 'Invalid authorization code. Please try again.', 'google-sitemap-plugin' ); ?></div>
										<?php
									}
								}
							}
						}
						?>
						<div class="bws_info">
							<?php esc_html_e( 'You can also add your sitemap to Google Search Console manually.', 'google-sitemap-plugin' ); ?>
							&nbsp;<a target="_blank" href="https://bestwebsoft.com/documentation/sitemap/sitemap-user-guide/#h.2phv39y4trv1"><?php esc_html_e( 'Read the instruction', 'google-sitemap-plugin' ); ?></a>
						</div>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Alternate Language Pages', 'google-sitemap-plugin' ); ?></th>
					<td>
						<?php
						$disabled = '';
						$link     = '';
						if ( array_key_exists( 'multilanguage/multilanguage.php', $this->all_plugins ) || array_key_exists( 'multilanguage-pro/multilanguage-pro.php', $this->all_plugins ) ) {
							if ( ! is_plugin_active( 'multilanguage/multilanguage.php' ) && ! is_plugin_active( 'multilanguage-pro/multilanguage-pro.php' ) ) {
								$disabled = ' disabled="disabled"';
								$link     = '<a href="' . admin_url( 'plugins.php' ) . '">' . esc_html__( 'Activate', 'google-sitemap-plugin' ) . '</a>';
							}
						} else {
							$disabled = ' disabled="disabled"';
							$link     = '<a href="https://bestwebsoft.com/products/wordpress/plugins/multilanguage/?k=84702fda886c65861801c52644d1ee11&amp;pn=83&amp;v=' . $this->plugins_info['Version'] . '&amp;wp_v=' . $wp_version . '" target="_blank">' . esc_html__( 'Install Now', 'google-sitemap-plugin' ) . '</a>';
						}
						?>
						<input type="checkbox" name="gglstmp_alternate_language" value="1" 
						<?php
						echo esc_attr( $disabled );
						checked( $this->options['alternate_language'] )
						?>
						/>
						<span class="bws_info"> <?php esc_html_e( 'Enable to add alternate language pages using Multilanguage plugin.', 'google-sitemap-plugin' ); ?> <?php echo wp_kses_post( $link ); ?></span>
						<?php wp_nonce_field( 'gglstmp_save_info_action', 'gglstmp_save_info' ); ?>
					</td>
				</tr>
			</table>
			<?php
		}

		/**
		 * Display tab display
		 */
		public function tab_display() {
			?>
			<h3 class="bws_tab_label"><?php esc_html_e( 'Sitemap Structure', 'google-sitemap-plugin' ); ?></h3>
			<?php $this->help_phrase(); ?>
			<hr>
			<table class="form-table gglstmp_settings_form">
				<tr>
					<th><?php esc_html_e( 'Post Types', 'google-sitemap-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php foreach ( $this->all_post_types as $post_type => $post_type_object ) { ?>
								<label>
									<input type="checkbox" 
									<?php
									if ( in_array( $post_type, $this->options['post_type'], true ) ) {
										echo 'checked="checked"';}
									?>
									name="gglstmp_post_types[]" value="<?php echo esc_attr( $post_type ); ?>"/>
									<span style="text-transform: capitalize; padding-left: 5px;"><?php echo esc_attr( $post_type_object->labels->name ); ?></span>
								</label>
								<br/>
							<?php } ?>
						</fieldset>
						<span class="bws_info"><?php esc_html_e( 'Enable to add post type links to the sitemap.', 'google-sitemap-plugin' ); ?></span>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Taxonomies', 'google-sitemap-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php foreach ( $this->all_taxonomies as $key => $value ) { ?>
								<label><input
									type="checkbox" 
									<?php
									if ( in_array( $key, $this->options['taxonomy'], true ) ) {
										echo 'checked="checked"';
									}
									?>
									name="gglstmp_taxonomies[]" value="<?php echo esc_attr( $key ); ?>"/><span
									style="padding-left: 5px;"><?php echo esc_attr( $value ); ?></span></label><br/>
							<?php } ?>
						</fieldset>
						<span class="bws_info"><?php esc_html_e( 'Enable to taxonomy links to the sitemap.', 'google-sitemap-plugin' ); ?></span>
						<?php wp_nonce_field( 'gglstmp_save_taxonomy_action', 'gglstmp_save_taxonomy_info' ); ?>
					</td>
				</tr>
			</table>
			<!-- pls -->
			<?php if ( ! $this->hide_pro_tabs ) { ?>
				<div class="bws_pro_version_bloc">
					<div class="bws_pro_version_table_bloc">
						<button type="submit" name="bws_hide_premium_options"
								class="notice-dismiss bws_hide_premium_options"
								title="<?php esc_html_e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
						<div class="bws_table_bg"></div>
						<?php gglstmp_extra_block(); ?>
					</div>
					<?php $this->bws_pro_block_links(); ?>
				</div>
			<?php } ?>
			<!-- end pls -->
			<?php
		}

		/**
		 * Display custom options on the 'misc' tab
		 *
		 * @access public
		 */
		public function additional_misc_options() {
			do_action( 'gglstmp_settings_page_misc_action', $this->options );
		}

		/**
		 * Custom functions for "Restore plugin options to defaults"
		 *
		 * @access public
		 *
		 * @param array $default_options Default potions for restore.
		 */
		public function additional_restore_options( $default_options ) {
			$default_name = 'sitemap';

			if ( $this->is_multisite ) {
				$blog_id = get_current_blog_id();
				$mask    = $default_name . '_' . $blog_id . '.xml';
			} else {
				$mask = $default_name . '.xml';
			}
			/* remove all sitemap files */
			array_map( 'unlink', glob( ABSPATH . $mask ) );

			/* clear robots.txt */
			$this->robots = 0;
			update_option( 'gglstmp_robots', $this->robots );

			$this->client->revokeToken();
			unset( $_SESSION[ 'gglstmp_authorization_code' . $this->blog_prefix ], $this->options['authorization_code'] );

			return $default_options;
		}

		/**
		 * Custom content for metabox
		 *
		 * @access public
		 */
		public function information_postbox_top() {
			$default_name = 'sitemap';

			if ( $this->is_multisite ) {
				$blog_id  = get_current_blog_id();
				$xml_file = $default_name . '_' . $blog_id . '.xml';
				$xml_url = network_home_url( '/' . $xml_file );
			} else {
				$xml_file = $default_name . '.xml';
				$xml_url = home_url( '/' . $xml_file );
			}

			if ( isset( $xml_file ) && file_exists( ABSPATH . $xml_file ) ) {

				printf(
					'<div class="misc-pub-section"><strong>%s</strong></div>',
					sprintf(
						esc_html__( 'Sitemap File: %s', 'google-sitemap-plugin' ),
						'<a href="' . esc_url( $xml_url ) . '" target="_blank">' . esc_html( $xml_file ) . '</a>'
					)
				);

				if ( ! $this->is_multisite ) {
					$status = gglstmp_check_sitemap( home_url( '/' . $default_name . '.xml' ) );
				} else {
					$status = gglstmp_check_sitemap( network_home_url( '/' . $default_name . '_' . $blog_id . '.xml' ) );
				}

				if ( ! is_wp_error( $status ) && 200 !== $status['code'] ) {
					if ( $this->is_multisite ) {
						$home_url = network_home_url();
						$site_url = network_site_url();
					} else {
						$home_url = home_url();
						$site_url = site_url();
					}
					$home_dir = str_replace( $home_url, '', $site_url );
					if ( '' !== $home_dir ) {
						$home_dir .= '/';
					}
					$replace = $home_dir . '$1';
					?>
					<div class="error below-h2">
						<p>
							<strong><?php esc_html_e( 'Error', 'google-sitemap-plugin' ); ?>:</strong> 
							<?php
							printf(
								esc_html__( "Can't access XML files. Try to add the following rule %1\$s to your %2\$s file which is located in the root of your website to resolve this error. Find the following line %3\$s and paste the code just after it.", 'google-sitemap-plugin' ),
								'<code>RewriteRule ([^/]+\.xml)$ ' . esc_html( $replace ) . ' [L]</code>',
								'<strong>.htaccess</strong>',
								'<strong>"RewriteBase /"</strong>'
							);
							?>
						</p>
					</div>
					<?php
				}
			} else {
				gglstmp_schedule_sitemap();
			}

			if ( class_exists( 'Google_Client' ) && version_compare( Google_Client::LIBVER, '1.1.3', '!=' ) ) {
				/* Google Client library of some other product is used! */
				?>
				<div class="updated bws-notice inline">
					<p><strong><?php esc_html_e( 'Note', 'google-sitemap-plugin' ); ?>
							:&nbsp;</strong><?php esc_html_e( 'Another plugin is providing Google Client functionality and may interrupt proper plugin work.', 'google-sitemap-plugin' ); ?>
					</p>
				</div>
				<?php
			}
		}

		/**
		 * Display custom message
		 *
		 * @access public
		 *
		 * @return bool false
		 */
		public function display_custom_messages() {
			$tooltip_text = sprintf(
				_x( 'The “Robots.txt” option is enabled. You should disable the “Search Engine Visibility" option on the %s.', '%reading settings page link%', 'google-sitemap-plugin' ),
				sprintf(
					'<a href="%s" target="_blank">%s</a>',
					admin_url( '/options-reading.php#blog_public' ),
					_x( 'Reading Settings page', '...on the reading settings page.', 'google-sitemap-plugin' )
				)
			);
			if ( file_exists( ABSPATH . 'robots.txt' ) ) {
				$tooltip_text .= '<br />' . esc_html__( 'Also, please add the following code to the beginning of your ".htaccess" file:', 'google-sitemap-plugin' ) . '<br />' .
					'<pre><code>' .
					'&lt;IfModule mod_rewrite.c&gt;<br />' .
					'RewriteEngine On<br />' .
					'RewriteBase /<br />' .
					'RewriteRule robots\.txt$ index.php?gglstmp_robots=1<br />' .
					'&lt;/IfModule&gt;' .
					'</code></pre>';
			}
			if ( 1 === absint( $this->robots ) && '1' !== get_option( 'blog_public' ) ) {
				printf( '<div class="updated bws-notice inline"><p><strong>%s</strong></p></div>', wp_kses_post( $tooltip_text ) );
			}
			return false;
		}
	}
}
