<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VI_WPRODUCTBUILDER_F_Admin_Admin {
	function __construct() {
		add_filter(
			'plugin_action_links_woocommerce-product-builder/woocommerce-product-builder.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'load-options-permalink.php', array( $this, 'woo_product_builder_load_permalinks' ), 11 );

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_product_builder_metaboxes' ) );

		add_action( 'save_post', array( $this, 'save_post_metadata' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );


		/*Get list product or categories in edit page*/
		add_action( 'wp_ajax_woopb_get_data', array( $this, 'get_data' ) );

	}

	/**
	 * Get Product via ajax
	 */
	public function get_data() {
        check_ajax_referer('woocommerce-product-builder_save', '_woopb_field_nonce');
		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}
		$type    = isset($_POST['type']) ? sanitize_text_field($_POST['type']) :'';
		$keyword    = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) :'';
		$results = array();
		switch ( $type ) {
			case 1:
				$args      = array(
					'post_status'    => 'publish',
					'post_type'      => 'product',
					'posts_per_page' => 50,
					's'              => $keyword,
					'tax_query'      => array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => array( 'simple', 'variable' ),
							'operator' => 'IN'
						),
					)
				);
				$the_query = new WP_Query( $args );
				// The Loop
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$data          = array();
						$data['id']    = get_the_ID();
						$data['title'] = get_the_title();
						if ( has_post_thumbnail() ) {
							$data['thumb_url'] = get_the_post_thumbnail_url();
						} else {
							$data['thumb_url'] = '';
						}
						$results[] = $data;
					}
				}
				// Reset Post Data
				wp_reset_postdata();
				break;
			default:
				$args  = array(
					'taxonomy'   => 'product_cat',
					'orderby'    => 'name',
					'hide_empty' => true,
					'number'     => 50,
					'search'     => $keyword
				);
				$cates = get_terms( $args );
				if ( count( $cates ) ) {
					foreach ( $cates as $cat ) {
						$data              = array();
						$data['id']        = $cat->term_id;
						$data['title']     = $cat->name;
						$data['thumb_url'] = '';
						$results[]         = $data;
					}
				}
		}
		wp_send_json( $results );
		die;
	}

	/**
	 * Register post type
	 */
	public function init() {
		load_plugin_textdomain( 'woo-product-builder' );
		$this->load_plugin_textdomain();
		register_post_type(
			'woo_product_builder', array(
				'labels' => array(
					'name'               => 'Product Builders',
					'singular_name'      => 'Product Builder',
					'add_new'            => 'Add New',
					'add_new_item'       => 'Add New Product Builder',
					'edit'               => 'Edit',
					'edit_item'          => 'Edit Product Builder',
					'new_item'           => 'New MProduct Builder',
					'view'               => 'View',
					'view_item'          => 'View Product Builder',
					'search_items'       => 'Search Product Builders',
					'not_found'          => 'No Product Builders found',
					'not_found_in_trash' => 'No Product Builders found in Trash'
				),

				'public'               => true,
				'menu_position'        => 2,
				'supports'             => array( 'title', 'thumbnail', 'revisions' ),
				'taxonomies'           => array( '' ),
				'menu_icon'            => 'dashicons-feedback',
				'has_archive'          => true,
				'register_meta_box_cb' => array( $this, 'add_product_builder_metaboxes' ),
				'rewrite'              => array( 'slug' => get_option( 'wpb2205_cpt_base' ), "with_front" => false )
			)
		);
		flush_rewrite_rules();
	}

	public function woo_product_builder_load_permalinks() {
		if ( isset( $_POST['wpb2205_cpt_base'] ) ) {
			update_option( 'wpb2205_cpt_base', sanitize_title_with_dashes( $_POST['wpb2205_cpt_base'] ) );
		}

		// Add a settings field to the permalink page
		add_settings_field(
			'wpb2205_cpt_base', __( 'Product builders' ),
			array( $this, 'woo_product_builder_field_callback' ), 'permalink', 'optional'
		);
	}

	public function woo_product_builder_field_callback() {
		$value = get_option( 'wpb2205_cpt_base' );
		echo '<input type="text" value="' . esc_attr( $value ) . '" name="wpb2205_cpt_base" id="wpb2205_cpt_base" class="regular-text" placeholder="product-builder" />';

	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="edit.php?post_type=woo_product_builder&page=woocommerce-product-builder-setting" title="' . __( 'Settings', 'woo-product-builder' ) . '">' . __( 'Settings', 'woo-product-builder' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-product-builder' );
		// Admin Locale
		if ( is_admin() ) {
			load_textdomain( 'woo-product-builder', VI_WPRODUCTBUILDER_F_LANGUAGES . "woo-product-builder-$locale.mo" );
		}

		// Global + Frontend Locale
		load_textdomain( 'woo-product-builder', VI_WPRODUCTBUILDER_F_LANGUAGES . "woo-product-builder-$locale.mo" );
		load_plugin_textdomain( 'woo-product-builder', false, VI_WPRODUCTBUILDER_F_LANGUAGES );
	}

	/**
	 * Enqueue scripts admin page
	 */
	public function admin_enqueue_script() {
		$screen = get_current_screen();
		$page   = isset( $_REQUEST['page'] ) ? sanitize_text_field($_REQUEST['page']) : '';
		if ( ( get_post_type() == 'woo_product_builder' && $screen->id == 'woo_product_builder' ) || $page == 'woocommerce-product-builder-setting' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			//			print_r($scripts);
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					wp_dequeue_script( $script->handle );
				}
			}
			wp_enqueue_style( 'woocommerce-product-builder-form', VI_WPRODUCTBUILDER_F_CSS . 'form.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-table', VI_WPRODUCTBUILDER_F_CSS . 'table.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-dropdown', VI_WPRODUCTBUILDER_F_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-checkbox', VI_WPRODUCTBUILDER_F_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-menu', VI_WPRODUCTBUILDER_F_CSS . 'menu.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-segment', VI_WPRODUCTBUILDER_F_CSS . 'segment.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-button', VI_WPRODUCTBUILDER_F_CSS . 'button.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-transition', VI_WPRODUCTBUILDER_F_CSS . 'transition.min.css' );
			wp_enqueue_style( 'woocommerce-product-builder-tab', VI_WPRODUCTBUILDER_F_CSS . 'tab.css' );
			wp_enqueue_style( 'woocommerce-product-builder-input', VI_WPRODUCTBUILDER_F_CSS . 'input.min.css' );
			wp_enqueue_style( 'woo-product-builder', VI_WPRODUCTBUILDER_F_CSS . 'woo-product-builder-admin-product.css' );

			wp_enqueue_script( 'woocommerce-product-builder-transition', VI_WPRODUCTBUILDER_F_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woocommerce-product-builder-checkbox', VI_WPRODUCTBUILDER_F_JS . 'checkbox.js', array( 'jquery' ) );
			wp_enqueue_script( 'woocommerce-product-builder-dropdown', VI_WPRODUCTBUILDER_F_JS . 'dropdown.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woocommerce-product-builder-address', VI_WPRODUCTBUILDER_F_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woocommerce-product-builder-tab', VI_WPRODUCTBUILDER_F_JS . 'tab.js', array( 'jquery' ) );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);
			if ( $page == 'woocommerce-product-builder-setting' ) {
				wp_enqueue_script( 'woocommerce-product-builder-admin-product', VI_WPRODUCTBUILDER_F_JS . 'woo-product-builder-admin.js', array( 'jquery' ) );

			} else {
				wp_enqueue_script( 'woocommerce-product-builder-admin-product', VI_WPRODUCTBUILDER_F_JS . 'woo-product-builder-admin-product.js', array( 'jquery' ) );
			}
			$arg_scripts = array(
				'tab_title'                => esc_html__( 'Please fill your step title', 'woo-product-builder' ),
				'tab_title_change'         => esc_html__( 'Please fill your tab title that you want to change.', 'woo-product-builder' ),
				'tab_notice_remove'        => esc_html__( 'Do you want to remove this tab?', 'woo-product-builder' ),
				'compatible_notice_remove' => esc_html__( 'Do you want to remove all compatible?', 'woo-product-builder' ),
				'message_notice_3'         => esc_html__( 'You only add maximum 3 steps. Please upgrade Premium version.', 'woo-product-builder' ),
				'ajax_url'                 => esc_url( admin_url( 'admin-ajax.php' ) ),
			);
			wp_localize_script( 'woocommerce-product-builder-admin-product', '_woopb_params', $arg_scripts );
		}

		wp_enqueue_style( 'woocommerce-product-builder-admin-update', VI_WPRODUCTBUILDER_F_CSS . 'woo-product-builder-admin-update.css' );

	}


	/**
	 * Register metaboxes
	 */
	public function add_product_builder_metaboxes() {
		add_meta_box(
			'vi_wpb_select_product', __( 'Products Configuration', 'woo-product-builder' ), array(
			$this,
			'select_products_html'
		), 'woo_product_builder', 'normal', 'default'
		);
		add_meta_box(
			'vi_wpb_side_bar', __( 'Garenal', 'woo-product-builder' ), array(
			$this,
			'general_setting_html'
		), 'woo_product_builder', 'normal', 'default'
		);
		add_meta_box(
			'vi_wpb_product_per_page', __( 'Products', 'woo-product-builder' ), array(
			$this,
			'products_per_page_html'
		), 'woo_product_builder', 'normal', 'default'
		);
	}

	/**
	 * Set fields post meta
	 */
	public static function set_field( $field, $multi = false ) {
		if ( $field ) {
			if ( $multi ) {
				return 'woopb-param[' . $field . '][]';
			} else {
				return 'woopb-param[' . $field . ']';
			}

		} else {
			return '';
		}
	}

	/**
	 * Get fields post meta
	 */
	public static function get_field( $field, $default = '' ) {
		global $post;
		$params = get_post_meta( $post->ID, 'woopb-param', true );
		if ( isset( $params[ $field ] ) && $field ) {
			return $params[ $field ];
		} else {
			return $default;
		}
	}

	/**
	 * Register select product metaboxes
	 */
	public function select_products_html() {

		wp_nonce_field( 'woocommerce-product-builder_save', '_woopb_field_nonce' );
		?>
        <!--		Form search-->
        <div class="vi-ui form woopb-search-form">

            <div class="inline fields">
                <div class="three wide field">
                    <label for="<?php echo self::set_field( 'select_product' ) ?>"><?php esc_html_e( 'Select products', 'woo-product-builder' ) ?></label>
                </div>
                <div class="three wide field">
                    <select class="vi-ui  dropdown woopb-type">
                        <option value="0"><?php esc_html_e( 'Categories', 'woo-product-builder' ) ?></option>
                        <option value="1"><?php esc_html_e( 'Products', 'woo-product-builder' ) ?></option>
                    </select>
                </div>
                <div class="one wide field">
                </div>
                <div class="eight wide field">
                    <div class="vi-ui action input">
                        <input class="wpb-search-field" type="text" placeholder="<?php esc_attr_e( 'Fill your product title or category title', 'woo-product-builder' ) ?>"/>
                        <span class="vi-ui button blue woopb-search-button"><?php esc_html_e( 'Search', 'woo-product-builder' ) ?></span>
                    </div>
                </div>
            </div>
            <script type="text/html" id="tmpl-woopb-item-template">
                <div class="woopb-item woopb-item-{{{data.item_class}}}" data-id="{{{data.id}}}">
                    <div class="woopb-item-top">{{{data.thumb}}}</div>
                    <div class="woopb-item-bottom">{{{data.name}}}</div>
                </div>
            </script>
            <div class="woopb-product-select">
                <div class="woopb-items">
					<?php
					$args  = array(
						'taxonomy'   => 'product_cat',
						'orderby'    => 'name',
						'hide_empty' => true,
						'number'     => 20
					);
					$cates = get_terms( $args );
					if ( count( $cates ) ) {
						foreach ( $cates as $cat ) { ?>
                            <div class="woopb-item woopb-item-category" data-id="<?php echo esc_attr( $cat->term_id ) ?>">
                                <div class="woopb-item-top"></div>
                                <div class="woopb-item-bottom"><?php echo esc_html( $cat->name ) ?></div>
                            </div>
						<?php }
					}
					?>
                </div>
            </div>
        </div>
		<?php
		$list_contents = self::get_field( 'list_content', array() );
		$tab_titles    = self::get_field( 'tab_title', array() );
		?>
        <div class="vi-ui form woopb-items-added">

            <div class="inline fields">
                <div class="five wide field woopb-tabs">
                    <div class="vi-ui vertical tabular menu">
						<?php if ( count( $tab_titles ) ) {
							foreach ( $tab_titles as $k => $tab_title ) {
								?>
                                <a class="item <?php echo $k ? '' : 'active' ?>" data-tab="<?php echo esc_attr( $k ) ?>">
                                    <span class="woopb-remove"></span>
                                    <span class="woopb-edit"></span>
                                    <span class="woopb-tab-title"><?php echo esc_html( $tab_title ) ?></span>
                                    <input type="hidden" name="woopb-param[tab_title][]" value="<?php echo esc_attr( $tab_title ) ?>">
                                </a>
							<?php }
						} else { ?>
                            <a class="active item" data-tab="first">
                                <span class="woopb-tab-title"><?php esc_html_e( 'First step', 'woo-product-builder' ) ?></span>
                                <span class="woopb-edit"></span>
                                <span class="woopb-remove"></span>
                                <input type="hidden" name="woopb-param[tab_title][]" value="first">
                            </a>
						<?php } ?>
                    </div>
                </div>
                <div class="eleven wide field woopb-tabs-content">
					<?php if ( count( $list_contents ) ) {
						foreach ( $list_contents as $k => $list_content ) { ?>
                            <div class="vi-ui tab <?php echo $k ? '' : 'active' ?>" data-tab="<?php echo esc_attr( $k ) ?>">
								<?php

								if ( is_array( $list_content ) && count( $list_content ) ) {
									foreach ( $list_content as $item ) {

										$item_data     = array();
										$check_product = 0;
										if ( strpos( trim( $item ), 'cate_' ) === false ) {

											$item_data['title'] = get_post_field( 'post_title', $item );
											$item_data['id']    = get_post_field( 'ID', $item );

											$check_product = 1;
										} else {
											$term_id            = str_replace( 'cate_', '', trim( $item ) );
											$term_data          = get_term_by( 'id', $term_id, 'product_cat' );
											$item_data['title'] = $term_data->name;
											$item_data['id']    = $term_data->term_id;

										}

										?>
                                        <div class="woopb-item woopb-item-<?php echo $check_product ? 'product' : 'category' ?> <?php echo has_post_thumbnail( $item_data['id'] ) && $check_product ? 'woopb-img' : '' ?>"
                                             data-id="<?php echo esc_attr( $item_data['id'] ) ?>">
                                            <div class="woopb-item-top">
												<?php if ( $check_product ) {
													echo get_the_post_thumbnail( $item_data['id'] );
												} ?>
                                            </div>
                                            <div class="woopb-item-bottom"><?php echo esc_attr( $item_data['title'] ) ?></div>
                                            <input type="hidden" name="woopb-param[list_content][<?php echo esc_attr( $k ) ?>][]"
                                                   value="<?php echo $check_product ? esc_attr( $item_data['id'] ) : 'cate_' . esc_attr( $item_data['id'] ) ?>">
                                        </div>
									<?php }

								}
								?>

                            </div>
						<?php }
					} else { ?>
                        <div class="vi-ui active tab" data-tab="first"></div>

					<?php } ?>
                </div>
            </div>
        </div>

        <p class="woopb-controls">
            <span class="vi-ui button green woopb-add-tab"><?php esc_html_e( 'Add New Step', 'woo-product-builder' ) ?></span>
        </p>
        <p>
			<?php esc_html_e( 'You only add maximun 3 steps. Please upgrade to add more.', 'woo-product-builder' ) ?>
            <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>

        </p>
		<?php
	}

	/**
	 * Register products per page metaboxes
	 */
	public function products_per_page_html() { ?>
        <table class="form-table vi-ui form">
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::set_field( 'product_per_page' ) ?>"><?php esc_html_e( 'Product per page', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <input type="number" id="<?php echo self::set_field( 'product_per_page' ) ?>" name="<?php echo self::set_field( 'product_per_page' ) ?>"
                           value="<?php echo self::get_field( 'product_per_page', 10 ) ?>" min="1"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::set_field( 'product_compatible' ) ?>"><?php esc_html_e( 'Depend', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Please save first to load all steps.', 'woo-product-builder' ) ?></p>

                </td>
            </tr>
        </table>
	<?php }

	/**
	 * General setting metaboxes
	 */
	public function general_setting_html() { ?>
        <table class="form-table vi-ui form">
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::set_field( 'text_prefix' ); ?>"><?php esc_html_e( 'Text prefix each step', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <input type="text" name="<?php echo self::set_field( 'text_prefix' ); ?>" id="<?php echo self::set_field( 'text_prefix' ); ?>"
                           value="<?php echo self::get_field( 'text_prefix', 'Step {step_number}' ); ?>">
                    <p class="description"><?php esc_html_e( '{step_number} - Number of current step', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::set_field( 'enable_quantity' ); ?>"><?php esc_html_e( 'Quantity field', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox checked">
                        <input type="checkbox" name="<?php echo self::set_field( 'enable_quantity' ); ?>"
                               id="<?php echo self::set_field( 'enable_quantity' ); ?>" <?php checked( self::get_field( 'enable_quantity' ), 1 ); ?> value="1">
                        <label for="<?php echo self::set_field( 'enable_quantity' ); ?>"><?php esc_html_e( 'Enable', 'woo-product-builder' ) ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'Default quantity is 1. Please enable if you want add more.', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::set_field( 'enable_preview' ); ?>"><?php esc_html_e( 'Preview button', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox checked">
                        <input type="checkbox" name="<?php echo self::set_field( 'enable_preview' ); ?>"
                               id="<?php echo self::set_field( 'enable_preview' ); ?>" <?php checked( self::get_field( 'enable_preview' ), 1 ); ?> value="1">
                        <label for="<?php echo self::set_field( 'enable_preview' ); ?>"><?php esc_html_e( 'Enable', 'woo-product-builder' ) ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'Display preview button when you have not reach to the final step', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Description', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Sort default', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Children categories', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Add many products in step', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>

                    <p class="description"><?php esc_html_e( 'Select multiple products in a step', 'woo-product-builder' ) ?></p>
                </td>
            </tr>


            <tr valign="top">
                <th scope="row">
                    <label ><?php esc_html_e( 'Add to cart button always show ', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Remove all button ', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label ><?php esc_html_e( 'Search product form', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Display search products form by ajax', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Product is required each step', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Out of stock products', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Allow to display out of stock products on product builder page', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Hide zero price product', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Allow to hide the products which have zero prices.', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php esc_html_e( 'Remove product title link', 'woo-product-builder' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui button yellow" href="https://1.envato.market/M3Wjq" target="_blank"><?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Allow to disable the link to single product pages from the title of products.', 'woo-product-builder' ) ?></p>
                </td>
            </tr>
        </table>
	<?php }


	/**
	 * Save metaboxes
	 */
	public function save_post_metadata( $post_id ) {
		// verify nonce
		if ( ! isset( $_POST['_woopb_field_nonce'] ) || ! isset( $_POST['woopb-param'] ) ) {
			return false;
		}

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return esc_html__( 'Cannot edit page', 'woo-product-builder' );
		}
		$data = wc_clean( $_POST['woopb-param'] );
		array_walk_recursive( $data, 'sanitize_text_field' );
		if ( is_array( $data['list_content'] ) ) {
			$data['list_content'] = array_values( $data['list_content'] );
		}

		if ( count( $data['list_content'] ) < count( $data['tab_title'] ) ) {
			$count = count( $data['list_content'] );
			if ( $count > 3 ) {
				$data['list_content'] = array_slice( $data['list_content'], 0, 3 );
				$count                = 3;
			}
			$data['tab_title'] = array_slice( $data['tab_title'], 0, $count );

		} elseif ( count( $data['list_content'] ) > count( $data['tab_title'] ) ) {
			$count = count( $data['tab_title'] );
			if ( $count > 3 ) {
				$data['tab_title'] = array_slice( $data['tab_title'], 0, 3 );
				$count             = 3;
			}
			$data['list_content'] = array_slice( $data['list_content'], 0, $count );

		} else {
			$count = count( $data['tab_title'] );
			if ( $count > 3 ) {
				$data['tab_title']    = array_slice( $data['tab_title'], 0, 3 );
				$data['list_content'] = array_slice( $data['list_content'], 0, 3 );
			}
		}
		update_post_meta( $post_id, 'woopb-param', $data );

	}


}