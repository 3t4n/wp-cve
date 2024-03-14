<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFTP_Admin
 */
if ( ! class_exists( 'WFTP_Admin' ) ) {
	#[AllowDynamicProperties]

  class WFTP_Admin {

		private static $ins = null;

		public function __construct() {

			add_filter( 'woofunnels_global_settings', function ( $menu ) {
				array_push( $menu, array(
					'title'    => __( 'Thank You', 'funnel-builder' ),
					'slug'     => 'ty-settings',
					'priority' => 60,
				) );

				return $menu;
			} );

			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
			add_filter( 'et_builder_enabled_builder_post_type_options', [ $this, 'wffn_add_ty_type_to_divi' ], 999 );


			add_filter( 'bwf_general_settings_fields', array( $this, 'add_permalink_settings' ), 100 );
			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['ty_page_base'] = 'order-confirmed';

				return $fields;
			} );


		}


		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}



		/**
		 * @param $funnel
		 */
		public function get_tabs_html( $tp_id ) {
			$tabs = $this->get_tabs_links( $tp_id );

			?>
            <div class="bwf_menu_list_primary">
                <ul>
					<?php
					foreach ( $tabs as $tab ) {
						$is_active = $this->is_tab_active_class( $tab['section'] );
						$tab_link  = $this->get_tab_link( $tab );
						?>
                        <li class="<?php echo esc_attr( $is_active ); ?>">
                            <a href="<?php echo empty( $tab_link ) ? 'javascript:void(0);' : esc_url( $tab_link ); ?>">
								<?php
								echo esc_html( $tab['title'] );
								?>
                            </a>
                        </li>
						<?php
					}
					?>
                </ul>
            </div>
			<?php
		}
		public function add_back_button() {
			global $post;
			$ty_type = WFFN_Thank_You_WC_Pages::get_post_type_slug();
			$ty_id   = ( $ty_type === $post->post_type ) ? $post->ID : 0;
			if ( $ty_id > 0 ) {
				$funnel_id = get_post_meta( $ty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-thankyou/" . $ty_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $ty_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
                    <div id="wf_funnel-switch-mode">
                        <a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Thank You Page', 'funnel-builder' ); ?>
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
			$ty_type = WFFN_Thank_You_WC_Pages::get_post_type_slug();
			$ty_id   = ( $ty_type === $post->post_type ) ? $post->ID : 0;
			if ( $ty_id > 0 ) {
				$funnel_id = get_post_meta( $ty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-thankyou/" . $ty_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) ); ?>
                <script id="wf_funnel-back-button-template" type="text/html">
                    <div id="wf_funnel-switch-mode" style="margin-right: 15px;margin-left: -5px;">
                        <a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Thank You Page', 'funnel-builder' ); ?>
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
                                    text: "<?php esc_html_e( '← Back to Thank You Page', 'funnel-builder' ); ?>",
                                    label: "<?php esc_html_e( 'Back to Thank You Page', 'funnel-builder' ); ?>"
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

		/**
		 * @param $options
		 *
		 * @return mixed
		 */
		public function wffn_add_ty_type_to_divi( $options ) {
			$ty_type             = WFFN_Thank_You_WC_Pages::get_post_type_slug();
			$options[ $ty_type ] = 'on';

			return $options;
		}
		public function add_permalink_settings( $fields ) {

			$fields['ty_page_base'] = array(
				'label'     => __( 'Thank You Page', 'funnel-builder' ),
				'hint'      => '',
				'type'      => 'input',
				'inputType' => 'text',
			);

			return $fields;

		}


	}
}
