<?php
declare( strict_types=1 );

namespace Pontet_Labs\Hide_Admin_Notices;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Options {
  private const BUG_REPORT_EMAIL_RECIPIENT = 'support@pontetlabs.com';

  private const BUG_REPORT_EMAIL_SUBJECT = 'Plugin Bug Report';

  private const OPTIONS_PAGE_SLUG = 'hide-admin-notices';

  static array $admin_notices = [];

  /**
   * @var Context $options
   */
  protected Context $context;

  public function __construct( Context $context ) {
    $this->context = $context;
  }

  public function init() {
    add_action( 'admin_menu', array( $this, 'add_options_page' ) );
    add_action( 'plugins_loaded', array( $this, 'form_handler' ) );
    add_action( 'admin_notices', array( $this, 'admin_notices' ) );
  }

  public function get_options_page_url() {
    return menu_page_url( self::OPTIONS_PAGE_SLUG, false );
  }

  public function add_options_page() {
    add_options_page( __( 'Hide Admin Notices', 'hide-admin-notices' ), __( 'Hide Admin Notices', 'hide-admin-notices' ), 'edit_posts', self::OPTIONS_PAGE_SLUG, array(
      $this,
      'render_settings_page'
    ) );
  }

  public function render_settings_page() {
    $bug_reports = $this->context->get( 'bug_reports', array() );
    $options     = sprintf( "<option value='' disabled selected>%s</option>", esc_html__( 'Choose a plugin...', 'hide-admin-notices' ) );

    foreach ( get_option( 'active_plugins' ) as $plugin ) {
      // $plugin will be for example: query-monitor/query-monitor.php.
      $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
      // Get just plugin ID.
      if ( strpos( $plugin, '/' ) !== false ) {
        // Get the folder name if the plugin is in a folder.
        $plugin_id = substr( $plugin, 0, strpos( $plugin, '/' ) );
      } else {
        $plugin_id = $plugin;
      }
      // Ignore this plugin!
      if ( $plugin_id == 'hide-admin-notices' ) {
        continue;
      }
      $plugin_name    = trim( $plugin_data['Name'] );
      $plugin_version = trim( $plugin_data['Version'] );
      $option         = [
        'id'      => $plugin_id,
        'name'    => $plugin_name,
        'version' => $plugin_version,
      ];
      // Disable the option for this plugin if a request has already been sent for it.
      $disabled_attribute = '';
      $name_suffix        = '';
      if ( isset( $bug_reports[ $plugin_id ] )
           && $plugin_version == $bug_reports[ $plugin_id ] ) {
        $disabled_attribute = ' disabled';
        $name_suffix        = sprintf( " - %1s ✓", esc_html__( 'report sent', 'hide-admin-notices' ) );
      }
      $options .= sprintf( "<option value='%1s'%2s>%3s v%4s%5s</option>", json_encode( $option ), $disabled_attribute, $plugin_name, $plugin_version, $name_suffix );
    }
    ?>
    <div class="wrap" style="margin:0">
      <div class="privacy-settings-header">
        <div class="privacy-settings-title-section">
          <h1><?php esc_html_e( 'Hide Admin Notices', 'hide-admin-notices' ); ?></h1>
        </div>
      </div>
      <hr class="wp-header-end">
      <div class="privacy-settings-body hide-if-no-js">
        <h2><?php esc_html_e( 'Report a Compatibility Issue with Hide Admin Notices', 'hide-admin-notices' ); ?></h2>
        <p>
          <strong><?php esc_html_e( "Use this form to report a plugin that is not compatible with Hide Admin Notices.", 'hide-admin-notices' ); ?></strong>
        </p>
        <p><?php esc_html_e( "Unfortunately, we have found that there are many plugins that do not adhere to the WordPress standard way to display admin notices, and with so many plugins available, we can't possibly test them all.", 'hide-admin-notices' ); ?></p>
        <p><?php esc_html_e( "So, if you let us know of an issue with a particular plugin, we will include support for it in the next version of Hide Admin Notices. Simply complete the form below, and we'll even let you know when the updated version is available. 😉", 'hide-admin-notices' ); ?></p>
        <p><strong><?php esc_html_e( "Note: we can only support free plugins." ) ?></strong></p>
        <hr>
        <form method="post" action="">
          <?php wp_nonce_field( 'hide_admin_notices_nonce', 'hide_admin_notices_nonce_field' ); ?>
          <input type="hidden" name="hide-admin-notices-action" value="bug-report"/>
          <table class="form-table" role="presentation">
            <tbody>
            <tr>
              <th scope="row"><label
                  for="hide-admin-notices-options[plugin]"><?php esc_html_e( 'Which plugin?', 'hide-admin-notices' ); ?></label>
              </th>
              <td>
                <select name="hide_admin_notices_options[plugin]"
                        id="hide-admin-notices-options[plugin]"
                        required><?php echo $options; ?></select>
                <p class="description">
                  <?php esc_html_e( 'These are your installed and active plugins.', 'hide-admin-notices' ); ?>
                </p>
              </td>
            </tr>
            <tr>
              <th scope="row"><label
                  for="hide-admin-notices-options[comments]"><?php esc_html_e( 'Please describe the issue:', 'hide-admin-notices' ); ?></label>
              </th>
              <td>
                            <textarea rows="5" cols="50" name="hide_admin_notices_options[comments]"
                                      id="hide-admin-notices-options[comments]" required></textarea>
                <p class="description">
                  <?php esc_html_e( 'Please describe the problem(s) that occur(s) and when, citing any relevant pages.', 'hide-admin-notices' ); ?>
                  <br>
                  <?php esc_html_e( 'The more information you can give, the more it will help us. 👍', 'hide-admin-notices' ); ?>
                </p>
              </td>
            </tr>
            <tr>
              <th scope="row"><label
                  for="hide-admin-notices-options[notify]"><?php esc_html_e( 'Would you like to be notified when resolved?', 'hide-admin-notices' ); ?></label>
              </th>
              <td>
                <input type="checkbox" name="hide_admin_notices_options[notify]"
                       id="hide-admin-notices-options[notify]">
                <p
                  class="description"><?php esc_html_e( 'We will drop you an email when the updated version is available.', 'hide-admin-notices' ); ?></p>
              </td>
            </tr>
            </tbody>
          </table>
          <?php esc_html_e( 'The data that you send will be completely anonymous. However, if you check the notification checkbox, you agree to also send the name and email address of the current user account — this data will be used solely for notification purposes.', 'hide-admin-notices' ); ?>
          <p
            class="submit"><?php submit_button( __( 'Send' ), 'primary', 'bug_report[send]', false ); ?></p>
        </form>
      </div>
    </div>
    <?php
  }

  public function form_handler() {
    $action = isset( $_POST['hide-admin-notices-action'] ) ? $_POST['hide-admin-notices-action'] : '';
    if ( $action ) {
      if ( ! wp_verify_nonce( $_POST['hide_admin_notices_nonce_field'], 'hide_admin_notices_nonce' ) ) {
        throw new \Exception( __( 'Unable to verify your request.', 'hide-admin-notices' ) );
      }
      if ( $action === 'bug-report' ) {
        if ( ! $this->send_bug_report( $_POST['hide_admin_notices_options'] ) ) {
          $this->add_admin_notice( __( 'Unable to send request.', 'hide-admin-notices' ), 'error' );
        } else {
          $this->add_admin_notice( __( 'Report sent successfully.', 'hide-admin-notices' ) );

          if ( ! $this->update_bug_reports( $_POST['hide_admin_notices_options'] ) ) {
            $this->add_admin_notice( __( 'An error occurred.', 'hide-admin-notices' ), 'error' );
          }
        }
      }
    }
  }

  public function admin_notices() {
    // WP v6.4 introduced the wp_admin_notice function.
    $use_wp_admin_notice = function_exists( 'wp_admin_notice' );
    foreach ( self::$admin_notices as $notice ) {
      if ( $use_wp_admin_notice ) {
        wp_admin_notice(
          $notice['message'],
          array(
            'id'                 => 'message',
            'additional_classes' => array( $notice['type'] ),
          )
        );
      } else {
        echo sprintf( '<div id="message" class="notice %1s"><p>%2s</p></div>', $notice['type'], $notice['message'] );
      }
    }
  }

  private function send_bug_report( $form_data ) {
    $plugin   = json_decode( $form_data['plugin'] );
    $comments = $form_data['comments'] ?? '';
    $notify   = $form_data['notify'] ?? '';
    if ( ! is_object( $plugin ) ) {
      return false;
    }
    $user_name  = '';
    $user_email = '';
    if ( $notify ) {
      $user       = wp_get_current_user();
      $user_name  = $user->user_nicename;
      $user_email = $user->user_email;
    }
    $loader = new FilesystemLoader( HIDE_ADMIN_NOTICES_DIR . 'templates' );
    $twig   = new Environment( $loader );
    $body   = $twig->render( 'bug-report-email.html.twig', array(
      'plugin_id'      => $plugin->id,
      'plugin_name'    => $plugin->name,
      'plugin_version' => $plugin->version,
      'comments'       => $comments,
      'notify'         => $notify,
      'user_name'      => $user_name,
      'user_email'     => $user_email,
    ) );

    return wp_mail( self::BUG_REPORT_EMAIL_RECIPIENT, self::BUG_REPORT_EMAIL_SUBJECT, $body, 'Content-type: text/html' );
  }

  private function update_bug_reports( $options ) {
    $bug_reports = $this->context->get( 'bug_reports', array() );
    $plugin      = json_decode( $options['plugin'] );
    if ( ! is_object( $plugin ) ) {
      return false;
    }
    $bug_reports = array_merge( $bug_reports, array(
      $plugin->id => $plugin->version,
    ) );
    $this->context->set( 'bug_reports', $bug_reports );

    return true;
  }

  private function add_admin_notice( $message, $type = 'updated' ) {
    self::$admin_notices[] = array(
      'message' => $message,
      'type'    => $type,
    );
  }
}