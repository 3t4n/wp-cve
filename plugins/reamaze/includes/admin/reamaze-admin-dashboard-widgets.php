<?php
/**
 * Reamaze Admin Dashboard Widgets.
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'Reamaze_Admin_Dashboard_Widgets' ) ) :

/**
 * Reamaze_Admin_Dashboard_Widgets
 */
class Reamaze_Admin_Dashboard_Widgets {

  public function __construct() {
    add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
  }

  public function init() {
    wp_add_dashboard_widget( 'reamaze_dashboard_overview_widget', __( 'Reamaze Overview', 'reamaze' ), array( $this, 'overview_widget' ) );
  }

  public function overview_widget() {
    $reamazeAccountId = get_option('reamaze_account_id');
    $reamazeApiKey = wp_get_current_user()->reamaze_api_key;
    $reamazeSettingsURL = admin_url('/admin.php?page=reamaze-settings');

    if ( ! empty( $reamazeAccountId ) && ! empty( $reamazeApiKey ) ) {
      try {
        $openConversationsResult = Reamaze\API\Conversation::all( array( "filter" => 'open' ) );
        $unassignedConversationsResult = Reamaze\API\Conversation::all( array( "filter" => 'unassigned' ) );
      } catch ( Reamaze\API\Exceptions\Api $e ) {
        if ( $e->getCode() == 403 ) {
          include( "views/errors/login-credentials-invalid.php" );
        } else {
          include( "views/errors/error.php" );
        }
        return;
      }

      $accountBaseUrl = sanitize_url( "https://" . $reamazeAccountId . ".reamaze.com" );
      ?>
      <?php if ( $openConversationsResult['total_count'] == 0 && $unassignedConversationsResult['total_count'] == 0 ) { ?>
        <p class="support-queue-empty"><i class="fa fa-thumbs-o-up"></i>Great job! Your support queue is empty.</p>
      <?php } else { ?>
        <ul class="clearfix">
          <li class="open_count">
            <i class="fa fa-fire"></i>
            <a href="<?php echo $accountBaseUrl ?>/admin?filter=open" target="_blank">
              <?php printf( __( '<strong>%s</strong> unresolved', 'reamaze' ), $openConversationsResult['total_count'] ); ?>
            </a>
          </li>
          <li class="unassigned_count">
            <i class="fa fa-bell-o"></i>
            <a href="<?php echo $accountBaseUrl ?>/admin?filter=unassigned" target="_blank">
              <?php printf( __( '<strong>%s</strong> unassigned', 'reamaze' ), $unassignedConversationsResult['total_count'] ); ?>
            </a>
          </li>
        </ul>
      <?php } ?>
      <p style="text-align: center;">
        <a href="<?php echo $accountBaseUrl ?>/admin?filter=all" target="_blank">
          View all conversations
        </a>
      </p>
      <?php
    } else {

      if ( ! $reamazeAccountId ) {
        include( "views/errors/setup-incomplete.php" );
        return;
      } elseif ( ! $reamazeApiKey ) {
        include( "views/errors/missing-api-key.php" );
        return;
      }
    }
  }
}

endif;

return new Reamaze_Admin_Dashboard_Widgets();
