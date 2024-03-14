<?php

/**
 * Settings Page
 * Handles to settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

    global $esb_eu_settings;
    
    $extension  = isset( $esb_eu_settings['extension'] ) ? esb_eu_escape_attr( $esb_eu_settings['extension'] ) : '';
    
?>
<div class="wrap">
    
    <h2><?php _e( 'Extension Settings', 'esbeu' ); ?></h2>
    
    <form method="post" action="options.php">
        
        <?php settings_fields( 'esb-eu-settings-group' ); ?>

        <table class="form-table esb-eu-form-table">

            <tr valign="top">
                <td scope="row">
                    <label for="esb_eu_settings_extension"><?php _e( 'Extension', 'esbeu' ); ?></label>
                </td>
                <td>
                    <input type="text" id="esb_eu_settings_extension" name="esb_eu_settings[extension]" class="medium-text" value="<?php echo $extension ?>" />
                    <br/>
                    <p class="description"><?php _e( 'Ex. <code>.html</code> <code>.php</code> <code>.jsp</code> etc...', 'esbeu' ) ?></p>
                </td>
            </tr>

        </table>
        
        <?php submit_button(); ?>

    </form>
        
</div>