<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
trait Integration {

    public function integration_tab_content() {
        $integration = get_option(ENTERADDONS_OPTION_KEY);
        // Mailchimp Token
        $mailchimp = '';
        if( !empty( $integration['integration']['mailchimp_token'] ) ) {
            $mailchimp = $integration['integration']['mailchimp_token'];
        }
        // Google Token
        $googleApiKey = '';
        if( !empty( $integration['integration']['google_api_key'] ) ) {
            $googleApiKey = $integration['integration']['google_api_key'];
        }

        ?>
            <div data-tab="integration">
                <div class="container">
                    <!-- Element Wrapper -->
                    <div class="elements-wrap">
                        <div class="integration-item-wrap">
                            <label><?php esc_html_e( 'Google API Key', 'enteraddons' ); ?></label>
                            <input type="text" class="theme-input-style" value="<?php echo esc_html($googleApiKey); ?>" name="enteraddons_integration[google_api_key]">
                        </div>
                        <div class="integration-item-wrap">
                            <label><?php esc_html_e( 'Mailchimp Token', 'enteraddons' ); ?></label>
                            <input type="text" class="theme-input-style" value="<?php echo esc_html($mailchimp); ?>" name="enteraddons_integration[mailchimp_token]">
                        </div>
                    </div>
                    <!-- End Element Wrapper -->
                </div>
            </div>
        <?php
    }

}
?>