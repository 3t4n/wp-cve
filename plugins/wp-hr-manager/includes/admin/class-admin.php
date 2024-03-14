<?php
namespace WPHR\HR_MANAGER\Admin;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

class Admin_Page {

    use Hooker;

    function __construct() {
        $this->wphr_init_actions();
        $this->init_classes();
    }

    /**
     * Initialize action hooks
     *
     * @return void
     */
    public function wphr_init_actions() {
        $this->action( 'init', 'includes_files' );
        $this->action( 'admin_init', 'admin_redirects' );
        add_action( 'admin_footer', 'wphr_include_popup_markup' );

        // $this->action( 'admin_notices', 'promotional_offer' );
    }

    /**
     * Include required files
     *
     * @return void
     */
    public function includes_files() {
        // Setup/welcome
        if ( ! empty( $_GET['page'] ) ) {

            if ( 'wphr-setup' == sanitize_text_field($_GET['page']) ) {
                include_once dirname( __FILE__ ) . '/class-setup-wizard.php';
            }
        }
    }

    /**
     * Initialize required classes
     *
     * @return void
     */
    public function init_classes() {
        new Form_Handler();
        new Ajax();
    }

    /**
     * Handle redirects to setup/welcome page after install and updates.
     *
     * @return void
     */
    public function admin_redirects() {
        if ( ! get_transient( '_wphr_activation_redirect' ) ) {
            return;
        }

        delete_transient( '_wphr_activation_redirect' );

        if ( ( ! empty( $_GET['page'] ) && in_array( sanitize_text_field( $_GET['page'] ), array( 'wphr-setup', 'wphr-welcome' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // If it's the first time
        if ( get_option( 'wphr_setup_wizard_ran' ) != '1' ) {
            wp_safe_redirect( admin_url( 'index.php?page=wphr-setup' ) );
            exit;

            // Otherwise, the welcome page
        } else {
            wp_safe_redirect( admin_url( 'index.php?page=wphr-welcome' ) );
            exit;
        }
    }

    /**
     * Promotional offer notice
     *
     * @since 1.1.15
     *
     * @return void
     */
    public function promotional_offer() {
        // Show only to Admins
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // check if it has already been dismissed
        $hide_notice = get_option( 'wphr_promotional_offer_notice', 'no' );

        if ( 'hide' == $hide_notice ) {
            return;
        }

        $offer_msg  = __( '<h2><span class="dashicons dashicons-awards"></span> WPHR 4th Year Anniversary Offer</h2>', 'wphr' );
        $offer_msg .= sprintf( __( '<p>Get <strong class="highlight-text">44&#37; discount</strong> on all extensions also <a target="_blank" href="%1$s"><strong>WIN any product</strong></a> from our 4th year anniversary giveaway. Offer ending soon!</p>', 'wphr' ), 'https://wphrmanager.com/in/4years' );
        ?>
            <div class="notice is-dismissible" id="wphr-promotional-offer-notice">
                <table>
                    <tbody>
                        <tr>
                            <td class="image-container">
                                <img src="<?php echo WPHR_ASSETS . '/images/Barbara-Happy-600px-Trans.png';?>" alt="">
                            </td>
                            <td class="message-container">
                                <?php echo $offer_msg; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <span class="dashicons dashicons-megaphone"></span>
            </div><!-- #wphr-promotional-offer-notice -->

            <style>
                #wphr-promotional-offer-notice {
                    background-color: #089dd7;
                    border: 0px;
                    padding: 0;
                    opacity: 0;
                }

                .wrap > #wphr-promotional-offer-notice {
                    opacity: 1;
                }

                #wphr-promotional-offer-notice table {
                    border-collapse: collapse;
                    width: 100%;
                }

                #wphr-promotional-offer-notice table td {
                    padding: 0;
                }

                #wphr-promotional-offer-notice table td.image-container {
                    background-color: #fff;
                    vertical-align: middle;
                    width: 95px;
                }


                #wphr-promotional-offer-notice img {
                    max-width: 100%;
                    max-height: 100px;
                    vertical-align: middle;
                }

                #wphr-promotional-offer-notice table td.message-container {
                    padding: 0 10px;
                }

                #wphr-promotional-offer-notice h2{
                    color: rgba(250, 250, 250, 0.77);
                    margin-bottom: 10px;
                    font-weight: normal;
                    margin: 16px 0 14px;
                    -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                }


                #wphr-promotional-offer-notice h2 span {
                    position: relative;
                    top: 0;
                }

                #wphr-promotional-offer-notice p{
                    color: rgba(250, 250, 250, 0.77);
                    font-size: 14px;
                    margin-bottom: 10px;
                    -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                }

                #wphr-promotional-offer-notice p strong.highlight-text{
                    color: #fff;
                }

                #wphr-promotional-offer-notice p a {
                    color: #fafafa;
                }

                #wphr-promotional-offer-notice .notice-dismiss:before {
                    color: #fff;
                }

                #wphr-promotional-offer-notice span.dashicons-megaphone {
                    position: absolute;
                    bottom: 46px;
                    right: 119px;
                    color: rgba(253, 253, 253, 0.29);
                    font-size: 96px;
                    transform: rotate(-21deg);
                }

            </style>

            <script type='text/javascript'>
                jQuery('body').on('click', '#wphr-promotional-offer-notice .notice-dismiss', function(e) {
                    e.preventDefault();

                    wp.ajax.post('wphr-dismiss-promotional-offer-notice', {
                        dismissed: true
                    });
                });
            </script>
        <?php
    }
}

new Admin_Page();
