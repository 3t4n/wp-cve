<?php

class Zopim_Login extends Zopim_Base_View {
  protected function set_messages() {
    $this->_messages = array(
      'login-fail'            => __( 'Could not log in to Zendesk Chat. Please check your login details.', 'zopim-live-chat' ),
      'wp-login-error'        => __( 'Could not log in to Zendesk Chat. We were unable to contact Zendesk Chat servers. Please check with your server administrator to ensure that <a href="http://www.php.net/manual/en/book.curl.php">PHP Curl</a> is installed and permissions are set correctly',
      'zopim-live-chat' ),
      'setup-your-account'    => __( 'Set up your Zendesk Chat Account', 'zopim-live-chat' ),
      'congratulations'       => __( 'Congratulations on successfully installing the Zendesk Chat WordPress plugin!',
      'zopim-live-chat' ),
      'link-up-title'         => __( 'Link up with your Zendesk Chat account', 'zopim-live-chat' ),
      'username'              => __( 'Zendesk Chat Username (E-mail)', 'zopim-live-chat' ),
      'password'              => __( 'Zendesk Chat Password', 'zopim-live-chat' ),
      'widget-display-notice' => __( 'The Zendesk Chat widget will display on your blog after your account is linked up.',
      'zopim-live-chat' ),
      'link-up-button'        => __( 'Link Up', 'zopim-live-chat' ),
      'sign-up-link'          => __( 'Sign up now', 'zopim-live-chat' ),
      'subdomain'             => __( 'Subdomain (For Zendesk Chat accounts with a subdomain)' ),
    );
  }

  public function do_login() {
    $admin   = Zopim_Admin::get_instance();
    $notices = Zopim_Notices::get_instance();

    if (!( isset($_POST['_wpnonce'] ) ) || (! wp_verify_nonce($_POST['_wpnonce'], 'zopim_login'))) {
      update_option( Zopim_Options::ZOPIM_OPTION_SALT, 'wronglogin' );
      $notices->add_notice( 'login_form', 'Invalid CSRF token. Please try re-sending the request.', 'error' );
    } else {
      if ( $_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ] != '' && $_POST['zopimPassword'] != '' ) {

        $logindata   = array(
          'email'     => htmlspecialchars($_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ]),
          'password'  => htmlspecialchars($_POST['zopimPassword']),
          'subdomain' => htmlspecialchars($_POST[ Zopim_Options::ZENDESK_OPTION_SUBDOMAIN ]),
        );

        if ($logindata['subdomain'] != '') { // User has subdomain so we'll try it authenticating it with zendesk chat api
          $loginresult =  $admin->zendesk_authenticate($logindata);

          if ( $loginresult['response']['code'] >= 400 ) { //handle 4xx and 5xx errors
            $notices->add_notice( 'login_form', $this->get_message( 'login-fail' ), 'error' );
            update_option( Zopim_Options::ZOPIM_OPTION_SALT, 'wronglogin' );
            return;
          }
          update_option( Zopim_Options::ZOPIM_OPTION_USERNAME, $logindata['email'] );
          update_option( Zopim_Options::ZENDESK_OPTION_SUBDOMAIN, $logindata['subdomain'] );
          update_option( Zopim_Options::ZOPIM_OPTION_SALT, 'loginsuccess' );

          return;
        }
        $loginresult = json_decode( $admin->zopim_post_request( ZOPIM_LOGIN_URL, $logindata ) );

        if ( isset( $loginresult->error ) ) {
          $notices->add_notice( 'login_form', $this->get_message( 'login-fail' ), 'error' );
          update_option( Zopim_Options::ZOPIM_OPTION_SALT, 'wronglogin' );
        } else if ( isset( $loginresult->salt ) ) {
          update_option( Zopim_Options::ZOPIM_OPTION_USERNAME, $_POST[ Zopim_Options::ZOPIM_OPTION_USERNAME ] );
          update_option( Zopim_Options::ZOPIM_OPTION_SALT, $loginresult->salt );
          $account = $admin->zopim_get_account_details( get_option( Zopim_Options::ZOPIM_OPTION_SALT ) );

          if ( isset( $account ) ) {
            update_option( Zopim_Options::ZOPIM_OPTION_CODE, $account->account_key );

            if ( get_option( 'zopimGreetings' ) == '' ) {
              $jsongreetings = json_encode( $account->settings->greetings );

              update_option( 'zopimGreetings', $jsongreetings );
            }
          }
        } else {
          update_option( Zopim_Options::ZOPIM_OPTION_SALT, '' );
          $notices->add_notice( 'login_form', $this->get_message( 'wp-login-error' ), 'error' );
        }
      } else {
        update_option( Zopim_Options::ZOPIM_OPTION_SALT, "wronglogin" );
        $notices->add_notice( 'login_form', $this->get_message( 'login-fail' ), 'error' );
      }
    }
  }
  /**
  * Renders the login form
  *
  * @return void
  */
  public function display_login_form() {
    $notices = Zopim_Notices::get_instance();
    Zopim_Template::load_template( 'login-form', array( 'notices' => $notices, 'messages' => $this->_messages ) );
  }
}
