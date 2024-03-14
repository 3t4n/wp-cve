<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFLP_Admin
 */
if ( ! class_exists( 'WFLP_Admin' ) ) {
	#[AllowDynamicProperties]
	class WFLP_Admin {

		private static $ins = null;
		public $edit_id = 0;

		public function __construct() {
			$this->process_url();

			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );

			add_filter( 'bwf_enable_ecommerce_integration_landing', '__return_true' );


		}

		/**
		 * @return WFLP_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_edit_id() {
			return $this->edit_id;
		}

		private function process_url() {

			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['post'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && isset( $_REQUEST['editor_post_id'] ) && $_REQUEST['editor_post_id'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['editor_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		public function register_template_type( $data ) {

			if ( isset( $data['slug'] ) && ! empty( $data['slug'] ) && isset( $data['title'] ) && ! empty( $data['title'] ) ) {
				$slug  = sanitize_title( $data['slug'] );
				$title = esc_html( trim( $data['title'] ) );
				if ( ! isset( $this->template_type[ $slug ] ) ) {
					$this->template_type[ $slug ]        = trim( $title );
					$this->design_template_data[ $slug ] = [
						'edit_url'    => $data['edit_url'],
						'button_text' => $data['button_text'],
						'title'       => $data['title'],
						'description' => isset( $data['description'] ) ? $data['description'] : '',
					];
				}
			}
		}

		public function register_template( $slug, $data, $type = 'pre_built' ) {
			if ( '' !== $slug && ! empty( $data ) ) {
				$this->templates[ $type ][ $slug ] = $data;
			}
		}


		/**
		 * Adding back to landing page editor
		 */
		public function add_back_button() {
			global $post;
			$lp_type = WFFN_Core()->landing_pages->get_post_type_slug();
			$lp_id   = ( $lp_type === $post->post_type ) ? $post->ID : 0;
			if ( $lp_id > 0 ) {
				$funnel_id = get_post_meta( $lp_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-landing/" . $lp_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $lp_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
                    <div id="wf_funnel-switch-mode">
                        <a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Sales Page', 'funnel-builder' ); ?>
                        </a>
                    </div>
                    <script>
                        window.addEventListener('load', function () {
                            (function (window, wp) {
                                var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
                                if (link) {
                                    link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
                                }

                            })(window, wp)
                        });
                    </script>
					<?php
				}
			} ?>
			<?php
		}

		public function render_back_to_funnel_script_for_block_editor() {
			global $post;
			$lp_type = WFFN_Core()->landing_pages->get_post_type_slug();
			$lp_id   = ( $lp_type === $post->post_type ) ? $post->ID : 0;
			if ( $lp_id > 0 ) {
				$funnel_id = get_post_meta( $lp_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-landing/" . $lp_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) ); ?>
                <script id="wf_funnel-back-button-template" type="text/html">
                    <div id="wf_funnel-switch-mode" style="margin-right: 15px;margin-left: -5px;">
                        <a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Sales Page', 'funnel-builder' ); ?>
                        </a>
                    </div>
                </script>

                <script>
                    window.addEventListener('load', function () {
                        (function (window, wp) {

                            const {Toolbar, ToolbarButton} = wp.components;

                            var link_button = wp.element.createElement(
                                ToolbarButton,
                                {
                                    variant: 'secondary',
                                    href: "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>",
                                    id: 'wf_funnel-back-button',
                                    className: 'button is-secondary',
                                    style: {
                                        display: 'flex',
                                        height: '33px'
                                    },
                                    text: "<?php esc_html_e( '← Back to Sales Page', 'funnel-builder' ); ?>",
                                    label: "<?php esc_html_e( 'Back to Sales Page', 'funnel-builder' ); ?>"
                                }
                            );
                            var linkWrapper = '<div id="wf_funnel-switch-mode"></div>';

                            // check if gutenberg's editor root element is present.
                            var editorEl = document.getElementById('editor');
                            if (!editorEl) { // do nothing if there's no gutenberg root element on page.
                                return;
                            }

                            var unsubscribe = wp.data.subscribe(function () {
                                setTimeout(function () {
                                    if (!document.getElementById('wf_funnel-switch-mode')) {
                                        var toolbalEl = editorEl.querySelector('.edit-post-header__toolbar .edit-post-header-toolbar');
                                        if (toolbalEl instanceof HTMLElement) {
                                            toolbalEl.insertAdjacentHTML('beforeend', linkWrapper);
                                            setTimeout(() => {
                                                wp.element.render(link_button, document.getElementById('wf_funnel-switch-mode'));
                                            }, 1);
                                        }
                                    }
                                }, 1)
                            });

                            var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
                            if (link) {
                                link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
                            }

                        })(window, wp)

                    });
                </script>
			<?php }
		}


		public function localize_data() {
			$data                          = [];
			$design                        = [];
			$data['nonce_save_design']     = wp_create_nonce( 'wffn_lp_save_design' );
			$data['nonce_remove_design']   = wp_create_nonce( 'wffn_lp_remove_design' );
			$data['nonce_import_design']   = wp_create_nonce( 'wffn_lp_import_design' );
			$data['nonce_custom_settings'] = wp_create_nonce( 'wffn_lp_custom_settings_update' );
			$data['nonce_update_edit_url'] = wp_create_nonce( 'wffn_lp_update_edit_url' );
			$data['nonce_toggle_state']    = wp_create_nonce( 'wffn_lp_toggle_state' );
			$data['wflp_edit_nonce']       = wp_create_nonce( 'wflp_edit_landing' );
			$data['design_template_data']  = $this->design_template_data;
			$data['custom_options']        = WFFN_Core()->landing_pages->get_custom_option();
			$data['texts']                 = array(
				'settings_success'       => __( 'Changes saved', 'funnel-builder' ),
				'copy_success'           => __( 'Link copied!', 'funnel-builder' ),
				'shortcode_copy_success' => __( 'Shortcode Copied!', 'funnel-builder' ),
			);

			$data['update_popups']         = array(

				'label_texts' => array(
					'title' => array(
						'label'       => __( 'Name', 'funnel-builder' ),
						'placeholder' => __( 'Enter Name', 'funnel-builder' ),
					),
					'slug'  => array(
						'label'       => sprintf( __( '%s URL Slug', 'funnel-builder' ), WFFN_Core()->landing_pages->get_module_title() ),
						'placeholder' => __( 'Enter Slug', 'funnel-builder' ),
					),
				),

			);
			$data['custom_setting_fields'] = array(
				'legends_texts' => array(
					'custom_css' => __( 'Custom CSS', 'funnel-builder' ),
					'custom_js'  => __( 'External Scripts', 'funnel-builder' ),
				),
				'fields'        => array(
					'custom_css' => array(
						'label'       => __( 'Custom CSS Tweaks', 'funnel-builder' ),
						'placeholder' => __( 'Paste your CSS code here', 'funnel-builder' ),
					),
					'custom_js'  => array(
						'label'       => __( 'Custom JS Tweaks', 'funnel-builder' ),
						'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
					),
				),
			);
			if ( 0 !== $this->edit_id ) {
				$post = get_post( $this->edit_id );

				$data['id']                   = $this->get_edit_id();
				$data['title']                = $post->post_title;
				$data['lp_title']             = WFFN_Core()->landing_pages->get_module_title();
				$data['status']               = $post->post_status;
				$data['content']              = $post->post_content;
				$data['view_url']             = get_the_permalink( $this->edit_id );
				$data['design_template_data'] = $this->design_template_data;
				$design                       = WFFN_Core()->landing_pages->get_page_design( $this->edit_id );

				$data['update_popups']['values'] = array(
					'title' => $post->post_title,
					'slug'  => $post->post_name,
				);
			}

			$design = array_merge( [
				'designs'         => $this->templates,
				'design_types'    => $this->template_type,
				'template_active' => "yes"
			], $design, $data );

			return $design;
		}


	}
}
