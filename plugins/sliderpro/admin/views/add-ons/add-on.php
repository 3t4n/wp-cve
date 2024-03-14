<div class="plugin-card">
    <div class="plugin-card-top">
        <div class="add-on-info">
            <div class="name">
                <h3>
                    <a>
                        <?php echo esc_html( $add_on_name ); ?>
                        <img class="plugin-icon" src="<?php echo esc_url( $add_on_icon ); ?>"/>
                    </a>
                </h3>
            </div>
            <div class="desc">
                <p><?php echo $add_on_description; ?></p>
                <a class="more-details" data-slug="<?php echo esc_attr( $add_on_slug ); ?>" href="<?php echo esc_url( $more_details_url ); ?>" data-nonce="<?php echo wp_create_nonce( 'open-more-details-' . $add_on_slug ); ?>"><?php _e( 'More details', 'sliderpro' ); ?></a>
            </div>
        </div>
    </div>
    <div class="plugin-card-bottom">
        <a class="button button-primary action-button <?php echo esc_attr( $action_button_class ); ?>" data-slug="<?php echo esc_attr( $add_on_slug ); ?>" href="<?php echo esc_url( $action_url ); ?>" data-nonce=<?php echo $nonce; ?>><span></span><p><?php echo esc_html( $action_button_text ); ?></p></a>
       
       <?php
            if ( $add_on_status !== '' ) {
                $edit_license_key_url = admin_url( 'admin.php?page=sliderpro-add-ons&add_on=' . $add_on_slug . '&action=edit-license-key' );
                echo '<a class="button edit-license-key" data-slug="' . esc_attr( $add_on_slug ) . '" href="' . esc_url( $edit_license_key_url ) . '" data-nonce="' . wp_create_nonce( 'open-license-key-editor-' . $add_on_slug ) . '">' . __( 'Edit license key', 'sliderpro' ) . '</a>';
            }
        ?>
    </div>
</div>