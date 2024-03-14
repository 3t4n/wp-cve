<div class="wrap sliderpro-admin">
	<h2><?php _e( 'Add-ons', 'sliderpro' ); ?></h2>
    <?php
        if ( is_array( $add_ons_data ) ) {
            echo '<div class="inline-info add-ons-info">';
            echo '<p>' . __( 'To install an add-on, just click on the <em>Install</em> button and provide a license key. Information about licensing are provided in the <em>More details</em> link.', 'sliderpro' ) . '</p>';
            echo '<p>' . __( 'Upon providing a license key, the add-on will be downloaded and installed automatically, but if plugin installation is restricted on your site, you can install the add-on manually.', 'sliderpro' ) . '</p>';
            echo '</div>';
            echo '<div class="add-ons-list sp-clearfix">';

            foreach ( $add_ons_data as $add_on ) {
                $add_on_name = sanitize_text_field( $add_on['name'] );
                $add_on_description = wp_kses_post( $add_on['description'] );
                $add_on_icon = sanitize_text_field( $add_on['icon'] );
                $add_on_slug = sanitize_text_field( $add_on['slug'] );
                $add_on_status_raw = $this->get_add_on_data( $add_on_slug, 'status' );
                $add_on_status = is_null( $add_on_status_raw ) ? '' : sanitize_text_field( $add_on_status_raw );
                $action_button_param = 'not-available';
                $action_button_class = 'disabled';
                $action_button_text = __( 'Not available', 'sliderpro' );
                $nonce = '';

                switch( $add_on_status ) {
                    case '':
                        $action_button_param = 'install';
                        $action_button_class = 'install-add-on';
                        $action_button_text = __( 'Install', 'sliderpro' );
                        $nonce = json_encode( array(
                            'verify' => wp_create_nonce( 'verify-add-on-license-key-' . $add_on_slug ),
                            'install' => wp_create_nonce( 'install-add-on-' . $add_on_slug ),
                            'activate' => wp_create_nonce( 'activate-add-on-' . $add_on_slug )
                        ));
                        break;
                    case 'installed':
                        $action_button_param = 'activate';
                        $action_button_class = 'activate-add-on';
                        $action_button_text = __( 'Activate', 'sliderpro' );
                        $nonce = json_encode( array(
                            'activate' => wp_create_nonce( 'activate-add-on-' . $add_on_slug )
                        ));
                        break;
                    case 'activated':
                        $action_button_param = 'deactivate';
                        $action_button_class = 'deactivate-add-on';
                        $action_button_text = __( 'Deactivate', 'sliderpro' );
                        $nonce = json_encode( array(
                            'deactivate' => wp_create_nonce( 'deactivate-add-on-' . $add_on_slug )
                        ));
                        break;
                }

	            $action_url = admin_url( 'admin.php?page=sliderpro-add-ons&add_on=' . $add_on_slug . '&action=' . $action_button_param );
	            $more_details_url = admin_url( 'admin.php?page=sliderpro-add-ons&add_on=' . $add_on_slug . '&action=more-details' );

                include( 'add-on.php' );
            }

            echo '</div>';
        } else if ( ! empty( $error_message ) ) {
            echo '<div class="error"><p>' . $error_message . '</p></div>';
        }
    ?>
</div>