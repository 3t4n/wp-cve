<?php

class WPForms_Views_LiteEditor {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ), 8 );
	}
	function register_sub_menu() {

		add_submenu_page( 'custom-settings', 'Edit View', 'Edit View', 'manage_options', 'wpf-views', array( &$this, 'views_editor' ) );
	}

	function views_editor() {
		// echo 'here'; die;
		$post_id = (int) $_GET['view_id'];
		if ( function_exists( 'wpforms' ) && wpforms()->is_pro() ) {

			$forms      = wpforms()->form->get();
			$view_forms = array(
				array(
					'id'    => '',
					'label' => 'Select',
				),
			);
			if ( ! empty( $forms ) ) {
				foreach ( $forms as $form ) {
					$view_forms[] = array(
						'id'    => $form->ID,
						'label' => $form->post_title,
					);
				}
			}
			// delete_post_meta($post->ID, 'view_settings');
			$wpf_view_saved_settings = get_post_meta( $post_id, 'view_settings', true );
			if ( empty( $wpf_view_saved_settings ) ) {
				$wpf_view_saved_settings = '{}';
				$form_id                 = '';
				if ( ! empty( $view_forms[1]['id'] ) ) {
					$form_id = $view_forms[1]['id'];
				}
			} else {
				$view_settings = json_decode( html_entity_decode( $wpf_view_saved_settings ) );
				$form_id       = $view_settings->formId;
			}
			$form_fields          = wpforms_views_get_form_fields( $form_id );
			$wpforms_views_config = apply_filters(
				'wpforms_views_config',
				array(
					'prefix' => 'wpforms',
					'addons' => array( '' ),
					'nonce'  => wp_create_nonce( 'wpf-views-builder' ),
				)
			);

			// Save and Resume Addon
			if ( defined( 'WPFORMS_SAVE_RESUME_VERSION' ) ) {
					$wpforms_views_config['addons'][] = 'wpforms-save-resume';

			}

			?>
				<script>
					var view_forms = '<?php echo addslashes( json_encode( $view_forms ) ); ?>';
					var _view_id = '<?php echo $post_id; ?>';
					var _view_title = '<?php echo addslashes( get_the_title( $post_id ) ); ?>';
					var _view_saved_settings = '<?php echo addslashes( $wpf_view_saved_settings ); ?>';
					var _view_form_fields =  '<?php echo addslashes( $form_fields ); ?>';
					var _view_config =  '<?php echo addslashes( json_encode( $wpforms_views_config ) ); ?>';
					var wpforms_views_active_addons = [];
				</script>
			<?php do_action( 'before_wpforms_views_builder' ); ?>
				   <div id="views-container"></div>
			<?php do_action( 'after_wpforms_views_builder' ); ?>
			<?php
		} else {
			echo 'Please install WPForms Pro to use this plugin';
		}

		?>
			<script>

			(function($){
				$(function(){
				$('#menu-dashboard').removeClass('wp-has-current-submenu','wp-menu-open menu-top');
					$('#menu-posts-wpforms-views').removeClass('wp-not-current-submenu');
					$('#menu-posts-wpforms-views').addClass('wp-has-current-submenu','wp-menu-open menu-top');
				})

			})(jQuery)
			</script>

		<?php
	}



}

new WPForms_Views_LiteEditor();
