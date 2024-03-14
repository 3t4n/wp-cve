<?php

if ( ! class_exists( 'CF7_ADDONS_PARENT' ) ) {
	abstract class CF7_ADDONS_PARENT {

		protected $title       = '';
		protected $slug        = ''; // Must be unique
		protected $image       = '';
		protected $description = '';
		protected $help_url    = '';

		public function __construct() {
			 // Deletes the settings when the form is deleted
			add_action(
				'delete_post',
				function( $post_id, $post_obj ) {
					if ( 'wpcf7_contact_form' == $post_obj->post_type ) {
						$this->delete_settings( $post_id );
					}
				},
				10,
				2
			);
		} // End __construct

		public function is_free() {
			 return ( isset( $this->free ) && $this->free ) ? true : false;
		} // End is_free

		public function get_title() {
			return $this->title;
		} // End get_title

		public function get_slug() {
			return $this->slug;
		} // End get_slug

		public function get_description() {
			 return $this->description;
		} // End get_description

		public function get_help_url() {
			return $this->help_url;
		} // End get_help_url

		public function get_image_url() {
			$image = $this->image;
			if ( ! empty( $image ) ) {
				$image = plugins_url( '../' . $this->image, __FILE__ );
			}
			return $image;
		} // End get_image_url

		public function get_settings( $cf7_id = '' ) {
			if ( '' === $cf7_id ) {
				return get_option( $this->get_slug(), array() );
			}
			return get_option( $this->get_slug() . '-' . $cf7_id, array() );
		} // End get_settings

		protected function get_cf7( $cf7_id = 0 ) {
			if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
				return false;
			}

			if ( $cf7_id ) {
				return WPCF7_ContactForm::get_instance( $cf7_id );
			} else {
				return WPCF7_ContactForm::get_current();
			}
		} // End get_cf7

		protected function get_form_fields( $cf7_id, $as_list = false ) {
			if ( empty( $this->form_tags ) ) {
				if ( ( $cf7_obj = $this->get_cf7( $cf7_id ) ) === false ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
					return false;
				}

				$this->form_tags      = $cf7_obj->scan_form_tags();
				$this->form_tags_list = '<option value="">' . esc_attr( __( '- Select field -', 'cf7-datasource' ) ) . '</option>';
				foreach ( $this->form_tags as $form_tag ) {
					if ( ! empty( $form_tag->name ) ) {
						$this->form_tags_list .= '<option value="' . esc_attr( $form_tag->name ) . '">' . esc_html( $form_tag->name . '(' . $form_tag->type . ')' ) . '</option>';
					}
				}
			}
			return ( $as_list ) ? $this->form_tags_list : $this->form_tags;
		} // End get_form_fields

		protected function form_exists( $cf7_id ) {
			 return $this->get_cf7( $cf7_id );
		} // End form_exists

		public function set_settings( $settings, $cf7_id = '' ) {
			if ( '' === $cf7_id ) {
				update_option( $this->get_slug(), $settings );
			}
			update_option( $this->get_slug() . '-' . $cf7_id, $settings );
		} // End set_settings

		public function delete_settings( $cf7_id = '' ) {
			if ( '' === $cf7_id ) {
				delete_option( $this->get_slug() );
			}
			delete_option( $this->get_slug() . '-' . $cf7_id );
		} // End delete_settings

		abstract protected function settings_interface();

		protected function settings_interface_first_page() {
			global $wpdb;

			if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'cf7-datasource-addon-delete-nonce' ) ) {
				if (
					isset( $_REQUEST['cf7-id-delete'] ) &&
					is_numeric( $_REQUEST['cf7-id-delete'] )
				) {
					$this->delete_settings( intval( $_REQUEST['cf7-id-delete'] ) );
				}
			}

			$forms = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='wpcf7_contact_form' ORDER BY post_title ASC" );

			$options = '';
			$rows    = '';

			if ( count( $forms ) ) {
				foreach ( $forms as $form ) {
					$settings = $this->get_settings( $form->ID );
					if ( empty( $settings ) ) {
						$options .= '<option value="' . esc_attr( $form->ID ) . '">' . esc_html( $form->post_title ) . '</option>';
					} else {
						$rows .= '<tr>
                            <td>' . $form->ID . '</td>
                            <td>' . esc_html( $form->post_title ) . '</td>
                            <td>
                                <input type="button" class="button-primary" value="' . esc_attr__( 'Settings', 'cf7-datasource' ) . '" onclick="cf7_datasource_addon_settings_update(' . $form->ID . ');" />
                                <input type="button" class="button-secondary" value="' . esc_attr__( 'Delete', 'cf7-datasource' ) . '" onclick="cf7_datasource_addon_settings_delete(' . $form->ID . ');" />
                            </td>
                        </tr>';
					}
				}
			}

			if ( empty( $rows ) ) {
				$rows = '<tr><td colspan="3" style="text-align:center;">- ' . esc_html__( 'Empty list', 'cf7-datasource' ) . ' -</td></tr>';
			}

			print '
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th>' . esc_html__( 'Id', 'cf7-datasource' ) . '</th>
                        <th>' . esc_html__( 'Form title', 'cf7-datasource' ) . '</th>
                        <th>' . esc_html__( 'Actions', 'cf7-datasource' ) . '</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $rows // phpcs:ignore WordPress.Security.EscapeOutput
					. '
                </tbody>
            </table>
            <p>
                <select name="cf7-id">' . $options // phpcs:ignore WordPress.Security.EscapeOutput
				. '</select>
                <input type="button" class="button-primary" value="' . esc_attr__( 'Add Integration', 'cf7-datasource' ) . '" onclick="cf7_datasource_addon_settings_add();" />
            </p>
            <script>
                function cf7_datasource_addon_settings_add()
                {
                    var cf7_id = jQuery(\'[name="cf7-id"]\').val();
                    if(cf7_id == null) return;
                    document.location.href="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . urlencode( $this->get_slug() ) . '&cf7-id="+cf7_id;
                }
                function cf7_datasource_addon_settings_update(cf7_id)
                {
                    document.location.href="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . urlencode( $this->get_slug() ) . '&cf7-id="+cf7_id;
                }
                function cf7_datasource_addon_settings_delete(cf7_id)
                {
                    if(confirm("' . esc_js( __( 'Are you sure to delete the integration?', 'cf7-datasource' ) ) . '"))
                    {
                        document.location.href="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . urlencode( $this->get_slug() ) . '&_wpnonce=' . urlencode( wp_create_nonce( 'cf7-datasource-addon-delete-nonce' ) ) . '&cf7-id-delete="+cf7_id;
                    }
                }
            </script>
            ';
		} // End settings_interface_first_page

		protected function select_from_list( $list, $value ) {
			return preg_replace( '/<option\s+value="' . preg_quote( $value ) . '"/i', '<option value="' . esc_attr( $value ) . '" SELECTED', $list, 1 );
		} // End select_from_list

		protected function preprocess_values( $v ) {
			return is_array( $v ) ? implode( ',', $v ) : $v;
		} // End preprocess_value

	} // End class CF7_ADDONS_PARENT
}
