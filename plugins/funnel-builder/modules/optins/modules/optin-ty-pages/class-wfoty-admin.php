<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOTY_Admin
 */
if ( ! class_exists( 'WFOTY_Admin' ) ) {
	#[AllowDynamicProperties]

  class WFOTY_Admin {

		private static $ins = null;

		public function __construct() {


			add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
			add_filter( 'et_builder_enabled_builder_post_type_options', [ $this, 'wffn_add_oty_type_to_divi' ], 999 );

			/**general settings**/
			add_filter( 'bwf_general_settings_fields', array( $this, 'add_permalink_settings' ) );
			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['optin_ty_page_base'] = 'op-confirmed';

				return $fields;
			} );

			add_filter( 'bwf_enable_ecommerce_integration_optin', '__return_true' );

		}

		/**
		 * @return WFOTY_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}




		/**
		 * Adding back to thank you optin page editor
		 */
		public function add_back_button() {
			global $post;
			$oty_type = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$oty_id   = ( $oty_type === $post->post_type ) ? $post->ID : 0;
			if ( $oty_id > 0 ) {
				$funnel_id = get_post_meta( $oty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-optin-confirmation/" . $oty_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) );

				if ( use_block_editor_for_post_type( $oty_type ) ) {
					add_action( 'admin_footer', array( $this, 'render_back_to_funnel_script_for_block_editor' ) );
				} else { ?>
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Confirmation Page', 'funnel-builder' ); ?>
						</a>
					</div>
					<script>
						window.addEventListener('load', function () {
							( function( window, wp ){
								var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
								if (link) {
									link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
								}

							} )( window, wp )
						});
					</script>
					<?php
				}
			} ?>
			<?php
		}

		public function render_back_to_funnel_script_for_block_editor() {
			global $post;
			$oty_type = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$oty_id   = ( $oty_type === $post->post_type ) ? $post->ID : 0;
			if ( $oty_id > 0 ) {
				$funnel_id = get_post_meta( $oty_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-optin-confirmation/" . $oty_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) ); ?>
				<script id="wf_funnel-back-button-template" type="text/html">
					<div id="wf_funnel-switch-mode">
						<a id="wf_funnel-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
							<?php esc_html_e( '&#8592; Back to Optin Confirmation Page', 'funnel-builder' ); ?>
						</a>
					</div>
				</script>

				<script>
					window.addEventListener('load', function () {
						( function( window, wp ){

							const { Toolbar, ToolbarButton } = wp.components;

							var link_button = wp.element.createElement(
								ToolbarButton,
								{
									variant :'secondary',
									href:"<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>",
									id:'wf_funnel-back-button',
									className:'button is-secondary',
									style:{
										display:'flex',
										height:'33px'
									},
									text :"<?php esc_html_e( '← Back to Optin Confirmation Page', 'funnel-builder' ); ?>",
									label :"<?php esc_html_e( 'Back to Optin Confirmation Page', 'funnel-builder' ); ?>"
								}
							);
							var linkWrapper = '<div id="wf_funnel-switch-mode"></div>';

							// check if gutenberg's editor root element is present.
							var editorEl = document.getElementById( 'editor' );
							if( !editorEl ){ // do nothing if there's no gutenberg root element on page.
								return;
							}

							var unsubscribe = wp.data.subscribe( function () {
								setTimeout( function () {
									if ( ! document.getElementById( 'wf_funnel-switch-mode' ) ) {
										var toolbalEl = editorEl.querySelector( '.edit-post-header__toolbar .edit-post-header-toolbar' );
										if( toolbalEl instanceof HTMLElement ){
											toolbalEl.insertAdjacentHTML( 'beforeend', linkWrapper );
											setTimeout(() => {
												wp.element.render( link_button, document.getElementById('wf_funnel-switch-mode') );
											}, 1 );
										}
									}
								}, 1 )
							} );

							var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
							if (link) {
								link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
							}

						} )( window, wp )
					});
				</script>
			<?php }
		}

		/**
		 * @param $options
		 *
		 * @return mixed
		 */
		public function wffn_add_oty_type_to_divi( $options ) {
			$oty_type             = WFOPP_Core()->optin_ty_pages->get_post_type_slug();
			$options[ $oty_type ] = 'on';

			return $options;
		}


		public function add_permalink_settings( $fields ) {

			$fields['optin_ty_page_base'] = array(
				'label'     => __( 'Optin Confirmation Page', 'funnel-builder' ),
				'hint'      => __( '', 'funnel-builder' ),
				'type'      => 'input',
				'inputType' => 'text',
			);

			return $fields;

		}


	}
}
