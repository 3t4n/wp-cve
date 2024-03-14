<?php
/**
 * Moove_GDPR_Review File Doc Comment
 *
 * @category Moove_GDPR_Review
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Moove_GDPR_Review Class Doc Comment
 *
 * @category Class
 * @package  Moove_GDPR_Review
 * @author   Moove Agency
 */
class Moove_GDPR_Review {
	/**
	 * Construct function
	 */
	public function __construct() {
    add_action( 'admin_notices', array( &$this, 'gdpr_add_review_notice' ) );
    add_action( 'admin_print_footer_scripts', array( &$this, 'gdpr_add_review_script' ) );
    add_action( 'wp_ajax_gdpr_cc_dismiss_review_notice', array( &$this, 'gdpr_cc_dismiss_review_notice' ) );
    add_filter( 'gdpr_check_review_banner_condition', array( &$this, 'gdpr_check_review_banner_condition_func' ), 10, 1 );
	}

  /**
   * Function which checks when to display the banner
   */
  public static function gdpr_check_review_banner_condition_func( $show_banner = false ){
    $current_screen         = get_current_screen();
    if ( 'moove-gdpr' !== $current_screen->parent_base || ! current_user_can( apply_filters( 'gdpr_options_page_cap', 'manage_options' ) ) ) :
      $show_banner = false;
    endif;

    if ( ! $show_banner && is_user_logged_in() ) :
      $user             = wp_get_current_user();
      $dismiss_stamp_p  = get_user_meta( $user->ID, 'gdpr_cc_dismiss_stamp_p', true );
      
      if ( ! intval( $dismiss_stamp_p ) ) :
        $dismiss_stamp    = get_user_meta( $user->ID, 'gdpr_cc_dismiss_stamp', true );

        if ( intval( $dismiss_stamp ) ) :
          $now_stamp    = strtotime('now');
          $show_banner  = intval( $dismiss_stamp ) <= $now_stamp;
        else :
          $dismiss_3m   = update_user_meta( $user->ID, 'gdpr_cc_dismiss_stamp', strtotime('+3 months') );
          $show_banner  = false;
        endif;
      else :
        $show_banner = false;
      endif;
    endif;
    return $show_banner;
  }

  /**
   * Dismiss notice on AJAX call
   */
  public static function gdpr_cc_dismiss_review_notice() {
    $nonce      = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : false;
    $type       = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
    $response   = array(
      'success' => false,
      'message' => 'Invalid request!',
    );
    if ( $nonce && wp_verify_nonce( $nonce, 'gdpr_cc_dismiss_nonce_field' ) && current_user_can( apply_filters( 'gdpr_options_page_cap', 'manage_options' ) ) ) :
      $user = wp_get_current_user();
      if ( $user && isset( $user->ID ) ) :
        $dismiss_3m = update_user_meta( $user->ID, 'gdpr_cc_dismiss_stamp' . $type, strtotime('+3 months') );
     
        $response = array(
          'success' => true,
          'message' => '',
        );
      endif;
    endif;
    echo json_encode( $response );
    die();
  }

  /**
   * Show the admin notice
   */
  public static function gdpr_add_review_notice() {
    $show_notice = apply_filters('gdpr_check_review_banner_condition', false);
    if ( $show_notice ) :
      ?>
      <div class="gdpr-cc-review-notice is-dismissible notice gdpr-cc-notice" data-adminajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
        <div class="gdpr-ccrn-label">
          <span class="gdpr-cc-icon" style="background-image: url('<?php echo moove_gdpr_get_plugin_directory_url() ?>/dist/images/gdpr-cookie-compliance-icon.png')"></span>

          <div class="gdpr-ccrn-content">
            <p><?php echo wp_kses_post( sprintf( __( 'Hi, thank you for using our plugin. We would really appreciate if you could take a moment to drop a quick review that will inspire us to keep going.', 'gdpr-cookie-compliance' ), '<strong>', '</strong>', '<br>' ) ); ?></p>
            <div class="gdpr-ccrn-button-wrap">
          
              <a href="https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/?rate=5#new-post" target="_blank" class="button button-gdpr-orange gdpr-ccrn-review">Review</a>
           
              <button class="button button-gdpr-alt gdpr-ccrn-dismiss">Remind me later</button>
                
            </div>
            <!-- .gdpr-ccrn-button-wrap -->
          </div>
          <!-- .gdpr-ccrn-content -->
         
        </div>
        <!-- .gdpr-ccrn-label -->
        <?php wp_nonce_field( 'gdpr_cc_dismiss_nonce_field', 'gdpr_cc_dismiss_nonce' ); ?>
      </div>
      <!-- .gdpr-cc-review-notice --> 
      <?php
    endif;
  }

  /**
   * Notice CSS and JS added to admin footer if the banner should be visible
   */
  public static function gdpr_add_review_script() {
    $show_notice = apply_filters('gdpr_check_review_banner_condition', false);
    if ( $show_notice ) :
      ?>
      <style>
        .gdpr-cc-review-notice {
          background-color: #fff;
          padding: 20px;
          border-left-color: #f79322;
          padding-top: 10px;
          padding-bottom: 10px;
        }

        .gdpr-cc-review-notice .gdpr-ccrn-button-wrap {
          display: flex;
          margin: 0 -5px;
        }

        .gdpr-cc-review-notice .button-gdpr-alt {
          border-radius: 0;
          text-shadow: none;
          box-shadow: none;
          outline: none;
          padding: 3px 10px;
          font-size: 12px;
          font-weight: 400;
          color: #fff;
          transition: all .3s ease;
          height: auto;
          line-height: 22px;
          border: 1px solid #d28b21;
          background-color: #262c33;
          border-color: #737373;
          opacity: .5;
          margin: 10px 5px;
        }

        .gdpr-cc-review-notice .button-gdpr-alt:hover {
          opacity: 1;
        }

        .gdpr-cc-review-notice .button-gdpr-orange {
          border-radius: 0;
          text-shadow: none;
          box-shadow: none;
          outline: none;
          padding: 3px 10px;
          font-size: 12px;
          font-weight: 400;
          color: #fff;
          transition: all .3s ease;
          height: auto;
          line-height: 22px;
          border: 1px solid #d28b21;
          background-color: #f79322;
          margin: 10px 5px;
        }

        .gdpr-cc-review-notice .button-gdpr-orange:hover {
          background-color: #1d2327;
          color: #f0f0f1;
        }

        .gdpr-cc-review-notice .gdpr-ccrn-content {
          flex: 0 0 calc( 100% - 100px);
          max-width: calc( 100% - 100px);
        }

        .gdpr-cc-review-notice .gdpr-ccrn-content p {
          font-size: 14px;
          margin: 0;
        }

        .gdpr-cc-review-notice .gdpr-cc-icon {
          flex: 0 0 80px;
          max-width: 80px;
          height: 80px;
          background-size: contain;
          background-position: center;
          background-repeat: no-repeat;
          margin: 0;
        }

        .gdpr-cc-review-notice .gdpr-ccrn-label {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
      </style>

      <script>
        (function($) {
          $(document).ready(function() {
            
            $(document).on('click','.gdpr-cc-review-notice .gdpr-ccrn-review', function(e){
              $(this).closest('.gdpr-cc-notice').slideUp();
              var ajax_url =$(this).closest('.gdpr-cc-notice').attr('data-adminajax');
              try {
                if ( ajax_url ) {
                  jQuery.post(
                    ajax_url,
                    {
                      action: 'gdpr_cc_dismiss_review_notice',
                      type: '_p',
                      nonce: $('#gdpr_cc_dismiss_nonce').val(),
                    },
                    function( msg ) {
                      console.warn(msg);
                    }
                  );
                }
              } catch(err) {
                console.error(err);
              }
            });
            $(document).on('click','.gdpr-cc-review-notice .gdpr-ccrn-dismiss', function(e){
              e.preventDefault();
              $(this).closest('.gdpr-cc-notice').slideUp();
              var ajax_url =$(this).closest('.gdpr-cc-notice').attr('data-adminajax');
              try {
                if ( ajax_url ) {
                  jQuery.post(
                    ajax_url,
                    {
                      action: 'gdpr_cc_dismiss_review_notice',
                      type: '',
                      nonce: $('#gdpr_cc_dismiss_nonce').val(),
                    },
                    function( msg ) {
                      console.warn(msg);
                    }
                  );
                }
              } catch(err) {
                console.error(err);
              }
            });
          });
        })(jQuery);
      </script>
      <?php
    endif;
  }
}
$gdpr_review = new Moove_GDPR_Review();