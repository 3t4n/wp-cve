<?php
/*
Class Name: VI_WOO_BOPO_BUNDLE_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_BOPO_BUNDLE_Admin {
	protected $settings;
	protected $language;
	protected $languages;
	protected $default_language;
	protected $languages_data;
	protected $languages_count;

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		$this->settings = VI_WOO_BOPO_BUNDLE_DATA::get_instance();
		$this->languages        = array();
		$this->languages_count  = 0;
		$this->languages_data   = array();
		$this->default_language = '';
		add_filter(
			'plugin_action_links_bopo-woo-product-bundle-builder/bopo-woo-product-bundle-builder.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ), 99 );

		add_action( 'wp_ajax_bopobb_first_bopobb_link', array( $this, 'bopobb_first_bopobb_link' ) );

		add_action( 'wp_print_scripts', array( $this, 'custom_script' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );

	}

	function init() {
		bopobb_register_product_type();
		load_plugin_textdomain( 'woo-bopo-bundle' );
		$this->load_plugin_textDomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/bopo-woo-product-bundle-builder/',
					'docs'      => 'https://docs.villatheme.com/?item=bopo',
					'review'    => 'https://wordpress.org/plugins/bopo-woo-product-bundle-builder/#reviews/?rate=5#rate-response',
					'pro_url'   => 'https://1.envato.market/4eLB0L',
					'css'       => VI_WOO_BOPO_BUNDLE_CSS,
					'image'     => VI_WOO_BOPO_BUNDLE_IMAGES,
					'slug'      => 'bopo-woo-product-bundle-builder',
					'menu_slug' => 'bopo-woo-product-bundle-builder',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbwx2ZKrkb5qi_M8sq3jin3vcJ7QTj6TcHzfWVNmo_G8Jz5ExtXOOboaoi06RN_6Jx-a/exec',
					'version'   => VI_WOO_BOPO_BUNDLE_VERSION
				)
			);
		}
	}

	/**
	 * load Language translate
	 */
	function load_plugin_textDomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-bopo-bundle' );
		// Admin Locale
		if ( is_admin() ) {
			load_textdomain( 'woo-bopo-bundle', VI_WOO_BOPO_BUNDLE_LANGUAGES . "woo-bopo-bundle-$locale.mo" );
		}

		// Global + Frontend Locale
		load_textdomain( 'woo-bopo-bundle', VI_WOO_BOPO_BUNDLE_LANGUAGES . "woo-bopo-bundle-$locale.mo" );
		load_plugin_textdomain( 'woo-bopo-bundle', false, VI_WOO_BOPO_BUNDLE_LANGUAGES );
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		If ( version_compare( $wp_version, "2.9", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	function add_menu() {
		add_menu_page(
			esc_html__( 'Bopo - Woo product bundle builder', 'woo-bopo-bundle' ),
			esc_html__( 'Bopo Bundle', 'woo-bopo-bundle' ),
			'manage_options',
			'woo-bopo-bundle',
			array(
				$this,
				'settings_page'
			), VI_WOO_BOPO_BUNDLE_IMAGES . 'icon_bopo.svg', 2
		);
		add_submenu_page(
			'woo-bopo-bundle',
			esc_html__( 'Bopo - Woo Product Bundle Builder', 'woo-bopo-bundle' ),
			esc_html__( 'Settings', 'woo-bopo-bundle' ),
			'manage_options',
			'woo-bopo-bundle'
		);
	}

	public function custom_script() {
		$script = 'var bopobb_ajax_url = "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '"'; ?>
        <script type="text/javascript" data-cfasync="false">
			<?php echo $script; ?>
        </script>
	<?php }

	public function bopobb_first_bopobb_link() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'bopobb_settings_page_save' ) ) {
			$bopo_settings['bopobb-first-product'] = 1;
			update_option( 'woo_bopo_bundle_params', $bopo_settings );
		}
		die;
	}

	function bopobb_wp_get_page_url_by_template_slug( $template_slug ) {
		$url      = null;
		$template = 'page-' . $template_slug . '.php';

		$pages = get_posts( array(
			'post_type'  => 'page',
			'meta_query' => array(
				array(
					'key'     => '_wp_page_template',
					'value'   => $template,
					'compare' => '=',
				)
			)
		) );

		if ( isset( $pages[0] ) ) {
			$url = get_permalink( $pages[0]->ID );
		}

		return $url;
	}

	/**
	 * Save data.
	 */
	public function save_settings() {
		global $bopobb_settings;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['bopobb_nonce_field'] ) || ! wp_verify_nonce( $_POST['bopobb_nonce_field'], 'bopobb_settings_page_save' ) ) {
			return;
		}

		/*wpml*/
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			global $sitepress;
			$default_lang           = $sitepress->get_default_language();
			$this->default_language = $default_lang;
			$languages              = icl_get_languages( 'skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str' );
			$this->languages_data   = $languages;
			if ( count( $languages ) ) {
				foreach ( $languages as $key => $language ) {
					if ( $key != $default_lang ) {
						$this->languages[] = $key;
					}
				}
			}
		} elseif ( class_exists( 'Polylang' ) ) {
		    /*Polylang*/
			$languages    = pll_languages_list();
			$default_lang = pll_default_language( 'slug' );
			foreach ( $languages as $language ) {
				if ( $language == $default_lang ) {
					continue;
				}
				$this->languages[] = $language;
			}
		}
		$this->languages_count = count( $this->languages );

		$args = array(
			//General
			'bopobb_view_quantity'    => 0,
			'bopobb_view_stock'       => 0,
			'bopobb_view_ratting'     => 0,
			'bopobb_view_description' => 0,
			'bopobb_link_individual'  => 0,
			'bopobb_single_template'  => 1,

			//swap button
			'bopobb_swap_text'        => 'Change',
			'bopobb_swap_pos'         => 1,
			'bopobb_swap_background'  => '',
			'bopobb_swap_color'       => '',

			//popup
			'bopobb_popup_title'      => 'Select product to bundle',
			'bopobb_popup_background' => '',
			'bopobb_popup_color'      => '',
			'bopobb_popup_fontsize'   => '',
			'bopobb_popup_page_items' => 32,
		);
		if ( $this->languages_count ) {
			foreach ( $this->languages as $key => $value ) {
//				$args[ 'bopobb_swap_text_' . $value ]   = isset( $_POST[ 'bopobb_swap_text_' . $value ] ) ? sanitize_text_field( $_POST[ 'bopobb_swap_text_' . $value ] ) : 'Change';
				$args[ 'bopobb_popup_title_' . $value ] = isset( $_POST[ 'bopobb_popup_title_' . $value ] ) ? sanitize_text_field( $_POST[ 'bopobb_popup_title_' . $value ] ) : 'Select product to bundle';
			}
		}
		foreach ( $args as $key => $arg ) {
			$args[ $key ] = isset( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : '';
		}
		$args = wp_parse_args( $args, get_option( 'woo_bopo_bundle_params', $bopobb_settings ) );
		update_option( 'woo_bopo_bundle_params', $args );
		$bopobb_settings = $args;
		$this->settings  = VI_WOO_BOPO_BUNDLE_DATA::get_instance( true );
		add_action( 'admin_notices', function () {
			?>
            <div class="updated">
                <p><?php esc_html_e( 'Your settings have been saved!', 'woo-bopo-bundle' ) ?></p>
            </div>
			<?php
		} );
	}

	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Bopo - Woo Product Bundle Builder - Build Your Own Box', 'woo-bopo-bundle' ); ?></h2>
            <div class="vi-ui raised">
                <form class="vi-ui form bopobb-general-settings" method="post" action="">
					<?php
					wp_nonce_field( 'bopobb_settings_page_save', 'bopobb_nonce_field' );
					?>
                    <div class="vi-ui segment">
                        <table class="vi-ui bottom attached form-table">
                            <tbody>
                            <?php if ( $this->settings->get_params( 'bopobb-first-product' ) == 0 ) { ?>
                                <h5 class="vi-ui label"><?php esc_html_e( 'Create your first bopo product: ', 'woo-bopo-bundle' ); ?>
                                    <a class="bopobb-create-product"
                                       href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product&product_type=bopobb' ) ) ?>"
                                       target="_blank">
			                            <?php echo esc_url( admin_url( 'post-new.php?post_type=product' ) ) ?>
                                    </a>
                                </h5>
                            <?php } ?>
                            <tr valign="top">
                                <th scope="row">
                                    <h4 class="vi-ui blue header"><?php esc_html_e( 'Bundle', 'woo-bopo-bundle' ); ?></h4>
                                </th>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_item_view">
										<?php esc_html_e( 'Item view', 'woo-bopo-bundle' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" tabindex="0"
                                               id="bopobb_view_quantity" <?php checked( $this->settings->get_params( 'bopobb_view_quantity' ), 1 ) ?>
                                               name="bopobb_view_quantity" value="1">
                                        <label><?php esc_html_e( 'Show quantity', 'woo-bopo-bundle' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_view_stock">
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="bopobb_view_stock" <?php checked( $this->settings->get_params( 'bopobb_view_stock' ), 1 ) ?>
                                               name="bopobb_view_stock" value="1">
                                        <label><?php esc_html_e( 'Show stock', 'woo-bopo-bundle' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_view_ratting">
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="bopobb_view_ratting" <?php checked( $this->settings->get_params( 'bopobb_view_ratting' ), 1 ) ?>
                                               name="bopobb_view_ratting" value="1">
                                        <label><?php esc_html_e( 'Show rating', 'woo-bopo-bundle' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_view_description">
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="bopobb_view_description" <?php checked( $this->settings->get_params( 'bopobb_view_description' ), 1 ) ?>
                                               name="bopobb_view_description" value="1">
                                        <label><?php esc_html_e( 'Show short description(on hover)', 'woo-bopo-bundle' ); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_link_individual">
										<?php esc_html_e( 'Link to individual product', 'woo-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" id="bopobb_link_individual" tabindex="1"
                                            name="bopobb_link_individual">
                                        <option <?php selected( $this->settings->get_params( 'bopobb_link_individual' ), 0 ) ?>
                                                value="0"><?php esc_html_e( 'No link', 'woo-bopo-bundle' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'bopobb_link_individual' ), 1 ) ?>
                                                value="1"><?php esc_html_e( 'New tab', 'woo-bopo-bundle' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'bopobb_link_individual' ), 2 ) ?>
                                                value="2"><?php esc_html_e( 'A bundle product changing popup', 'woo-bopo-bundle' ) ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Action after clicking on product title of bundle', 'woo-bopo-bundle' ); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_single_template">
										<?php esc_html_e( 'Template', 'woo-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" id="bopobb_single_template" tabindex="1"
                                            name="bopobb_single_template">
                                        <option <?php selected( $this->settings->get_params( 'bopobb_single_template' ), 1 ) ?>
                                                value="1"><?php esc_html_e( 'Vertical bundle template', 'woo-bopo-bundle' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'bopobb_single_template' ), 2 ) ?>
                                                value="2"><?php esc_html_e( 'Horizontal bundle template', 'woo-bopo-bundle' ) ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e( '', 'woo-bopo-bundle' ); ?></p>
                                </td>
                            </tr>
<!--                            <tr valign="top">-->
<!--                                <th scope="row">-->
<!--                                    <h4 class="vi-ui blue header">--><?php //esc_html_e( 'Change product button', 'woo-bopo-bundle' ); ?><!--</h4>-->
<!--                                </th>-->
<!--                            </tr>-->
<!--                            <tr valign="top">-->
<!--                                <th scope="row">-->
<!--                                    <label for="bopobb_swap_text">-->
<!--										--><?php //esc_html_e( 'Text', 'woo-bopo-bundle' ) ?><!--</label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input type="text" id="bopobb_swap_text" tabindex="2" name="bopobb_swap_text"-->
<!--                                           value="--><?php //echo esc_attr( $this->settings->get_params( 'bopobb_swap_text' ) ); ?><!--"-->
<!--                                           placeholder="--><?php //esc_html_e( 'Change', 'woo-bopo-bundle' ) ?><!--">-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr valign="top">-->
<!--                                <th scope="row">-->
<!--                                    <label for="bopobb_swap_color">-->
<!--										--><?php //esc_html_e( 'Color', 'woo-bopo-bundle' ) ?><!--</label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input name="bopobb_swap_color" id="bopobb_swap_color" tabindex="3" type="text"-->
<!--                                           class="color-picker"-->
<!--                                           value="--><?php //if ( $this->settings->get_params( 'bopobb_swap_color' ) ) {
//										       echo esc_attr( $this->settings->get_params( 'bopobb_swap_color' ) );
//									       } ?><!--"-->
<!--                                           style="background: --><?php //if ( $this->settings->get_params( 'bopobb_swap_color' ) ) {
//										       echo esc_attr( $this->settings->get_params( 'bopobb_swap_color' ) );
//									       } ?>
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr valign="top">-->
<!--                                <th scope="row">-->
<!--                                    <label for="bopobb_swap_background">-->
										<?php //esc_html_e( 'Background color', 'woo-bopo-bundle' ) ?><!--</label>-->
<!--                                </th>-->
<!--                                <td>-->
<!--                                    <input name="bopobb_swap_background" id="bopobb_swap_background" tabindex="4"-->
<!--                                           type="text" class="color-picker"-->
<!--                                           value="--><?php //if ( $this->settings->get_params( 'bopobb_swap_background' ) ) {
//										       echo esc_attr( $this->settings->get_params( 'bopobb_swap_background' ) );
//									       } ?><!--"-->
<!--                                           style="background: --><?php //if ( $this->settings->get_params( 'bopobb_swap_background' ) ) {
//										       echo esc_attr( $this->settings->get_params( 'bopobb_swap_background' ) );
//									       } ?>
<!--                                </td>-->
<!--                            </tr>-->
                            <tr valign="top">
                                <th scope="row">
                                    <h4 class="vi-ui blue header"><?php esc_html_e( 'Popup change product', 'woo-bopo-bundle' ); ?></h4>
                                </th>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_popup_title">
										<?php esc_html_e( 'Title', 'woo-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" id="bopobb_popup_title" tabindex="2" name="bopobb_popup_title"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'bopobb_popup_title' ) ); ?>"
                                           placeholder="<?php esc_html_e( 'Select product to bundle', 'woo-bopo-bundle' ) ?>">
	                                <?php
	                                if ( $this->languages_count ) {
		                                foreach ( $this->languages as $key => $value ) {
			                                ?>
                                            <p>
                                                <label for="<?php echo 'bopobb_popup_title_' . $value; ?>"><?php
					                                if ( isset( $this->languages_data[ $value ]['country_flag_url'] ) && $this->languages_data[ $value ]['country_flag_url'] ) {
						                                ?>
                                                        <img src="<?php echo esc_url( $this->languages_data[ $value ]['country_flag_url'] ); ?>">
						                                <?php
					                                }
					                                echo esc_html( $value );
					                                if ( isset( $this->languages_data[ $value ]['translated_name'] ) ) {
						                                echo '(' . $this->languages_data[ $value ]['translated_name'] . ')';
					                                }
					                                ?>:</label>
                                            </p>
                                            <input type="text"
                                                   id="<?php echo esc_attr( 'bopobb_popup_title_' . $value ) ?>"
                                                   tabindex="7"
                                                   name="<?php echo esc_attr( 'bopobb_popup_title_' . $value ) ?>"
                                                   value="<?php echo esc_attr( $this->settings->get_params( 'bopobb_popup_title_' . $value ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Please select your product', 'woocommerce-bopo-bundle' ) ?>">
			                                <?php
		                                }
	                                }
	                                ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_popup_color">
										<?php esc_html_e( 'Color', 'woo-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <input name="bopobb_popup_color" id="bopobb_popup_color" tabindex="5" type="text"
                                           class="color-picker"
                                           value="<?php if ( $this->settings->get_params( 'bopobb_popup_color' ) ) {
										       echo esc_attr( $this->settings->get_params( 'bopobb_popup_color' ) );
									       } ?>"
                                           style="background: <?php if ( $this->settings->get_params( 'bopobb_popup_color' ) ) {
										       echo esc_attr( $this->settings->get_params( 'bopobb_popup_color' ) );
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_popup_background">
										<?php esc_html_e( 'Background color', 'woo-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <input name="bopobb_popup_background" id="bopobb_popup_background" tabindex="4"
                                           type="text" class="color-picker"
                                           value="<?php if ( $this->settings->get_params( 'bopobb_popup_background' ) ) {
										       echo esc_attr( $this->settings->get_params( 'bopobb_popup_background' ) );
									       } ?>"
                                           style="background: <?php if ( $this->settings->get_params( 'bopobb_popup_background' ) ) {
										       echo esc_attr( $this->settings->get_params( 'bopobb_popup_background' ) );
									       } ?>;"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="bopobb_popup_page_items">

			                            <?php esc_html_e( 'Number product per page', 'woocommerce-bopo-bundle' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="bopobb_popup_page_items" id="bopobb_popup_page_items"
                                           tabindex="13" type="text" class="bopobb-popup-column"
                                           step="1" min="1" max="100"
                                           value="<?php if ( $this->settings->get_params( 'bopobb_popup_page_items' ) ) {
			                                   echo esc_attr( $this->settings->get_params( 'bopobb_popup_page_items' ) );
		                                   } ?>"/>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <p>
                        <input type="submit" name="bopobb_save_data"
                               value="<?php esc_html_e( 'Save', 'woo-bopo-bundle' ); ?>" class="vi-ui primary button">
                    </p>
                </form>
            </div>
        </div>
		<?php
		do_action( 'villatheme_support_bopo-woo-product-bundle-builder' );
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=woo-bopo-bundle" title="' . __( 'Bopo - Woo Product Bundle Builder', 'woo-bopo-bundle' ) . '">' . __( 'Settings', 'woo-bopo-bundle' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 *  Include style and script
	 */
	public function admin_enqueue_script() {
		$page = isset( $_REQUEST['page'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['page'] ) ) : '';
		if ( $page == 'woo-bopo-bundle' ) {
			// style
			wp_enqueue_style( 'woo-bopo-bundle-semantic-button-css', VI_WOO_BOPO_BUNDLE_CSS . 'button.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-table-css', VI_WOO_BOPO_BUNDLE_CSS . 'table.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-form-css', VI_WOO_BOPO_BUNDLE_CSS . 'form.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-dropdown-css', VI_WOO_BOPO_BUNDLE_CSS . 'dropdown.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-transition-css', VI_WOO_BOPO_BUNDLE_CSS . 'transition.min.css', VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-checkbox-css', VI_WOO_BOPO_BUNDLE_CSS . 'checkbox.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-menu-css', VI_WOO_BOPO_BUNDLE_CSS . 'menu.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-header-css', VI_WOO_BOPO_BUNDLE_CSS . 'header.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-segment-css', VI_WOO_BOPO_BUNDLE_CSS . 'segment.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_style( 'woo-bopo-bundle-semantic-icon-css', VI_WOO_BOPO_BUNDLE_CSS . 'icon.min.css', array(), VI_WOO_BOPO_BUNDLE_VERSION );

			//script
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);
			if ( WP_DEBUG ) {
				wp_enqueue_script( 'woo-bopo-bundle-setting', VI_WOO_BOPO_BUNDLE_JS . 'bopo-settings.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_VERSION );
			} else {
				wp_enqueue_script( 'woo-bopo-bundle-setting', VI_WOO_BOPO_BUNDLE_JS . 'bopo-settings.min.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_VERSION );
			}
			wp_enqueue_script( 'woo-bopo-bundle-semantic-dropdown-js', VI_WOO_BOPO_BUNDLE_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_VERSION );
			wp_enqueue_script( 'woo-bopo-bundle-semantic-transition-js', VI_WOO_BOPO_BUNDLE_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_VERSION );
		}
	}
}