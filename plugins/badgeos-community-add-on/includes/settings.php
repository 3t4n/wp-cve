<?php
/**
 * Register add-on settings.
 *
 * @since 1.0.0
 */
function badgeos_community_settings( $settings = array() ) {
    
    if( class_exists('BadgeOS_Interactive_Progress_Map') ) {
        ?>
            <tr>
                <td colspan="2">
                    <hr/>
                    <h2><?php _e( 'Interactive Map Settings', 'badgeos-community' ); ?></h2>
                    <p class="description"><?php _e( 'Select if interactive map tab should show under achievement/ranks tabs on buddypress profile.', 'badgeos-community' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Interactive Map Tab:', 'badgeos-community' ); ?></th>
                <td>
                    <p>
                        <label>
                            <input type="checkbox" name="badgeos_settings[badgeos_bp_inp_tab]" value="yes" <?php isset( $settings['badgeos_bp_inp_tab'] ) ? checked( $settings['badgeos_bp_inp_tab'], 'yes' ) : ''; ?> />
                            <?php _e( 'Yes', 'badgeos-community' ); ?>
                        </label>
                    </p>
                </td>
            </tr>
            
        <?php
    }
} /* settings() */
add_action( 'badgeos_settings', 'badgeos_community_settings' );