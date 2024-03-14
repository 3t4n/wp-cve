<?php
/**
 * The main class for the plugin
 * 
 */
namespace KUDOS\WCHPAS;
if ( ! defined( 'ABSPATH' ) )

{
  exit;
}
class HomenypotPlugin {
  /**
   * Whether instance is instantiated or not
   *
   * @var bool
   */
  private $instantiated = FALSE;
 /**
   * Track if registration action is successful
   *
   * @var bool
   */

  private $allow_registration = FALSE;
  /**
   * Plugin constructor.
   */
  public function __construct ()
  {
    // Initialise actions & filters

    add_action( 'woocommerce_register_form', [ $this, 'kudos_woocommerce_register_form' ] );

    add_action( 'wp_loaded', [ $this, 'kudos_check_honeypot_trap_sprung' ], 1 );

    add_filter( 'woocommerce_registration_errors', [ $this, 'kudos_check_honeypot_trap_sprung_errors' ], 1, 3 );
 
   //Login Actions or process error

  add_action( 'woocommerce_login_form', [ $this, 'kudos_woocommerce_login_form' ] );  
  add_filter( 'woocommerce_process_login_errors', [ $this, 'kudos_woocommerce_login_errors' ] );  

   // Mark instantiated

    $this->instantiated = TRUE;

  }
  /**
   * Put in a honeypot trap in the customer registration form to fool automated registration bots
   *
   * @hooked woocommerce_register_form_end
   */
  public function kudos_woocommerce_register_form ()
  {

    ?>

<div class="form-row" style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;" aria-hidden="true">

  <label for="sp_website_input">

    <?php _e( 'WC Website Input', 'woocommerce' ); ?>

  </label>

  <input type="text" id="wc_website_input" name="wc_website_input" value="" tabindex="-1" autocomplete="off" class="input-field" />

</div>

<?php
  }
  /**
   * @hooked woocommerce_login_form_end
   */
  public function kudos_woocommerce_login_form ()
  {
      ?>
    <div class="form-row" style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;" aria-hidden="true"> 

      <label for="sp_login_website_input">

        <?php _e( 'WC Login Website', 'woocommerce' ); ?>

      </label>

      <input type="text" id="wc_login_website_input" name="wc_login_website_input" value="" tabindex="-1" autocomplete="off" class="input-field" />
    </div>
<?php
  }  

  /**
   * Check if honeypot input has value. Allow if it exists and has an empty value.
   *
   * @hooked woocommerce_process_login_errors
   * @priority 1
   */
   public function kudos_woocommerce_login_errors( $errors   ) {

    if ( !empty( $_POST['wc_login_website_input'] ) ){      

      $errors = new \WP_Error( 'registration-error-invalid-email', __( 'Oops! Our form flagged this login attempt as non-human.', 'woocommerce' ) );
    }
    return $errors; 
  }
  /**
   * Check if honeypot trap has value. Allow if it exists and has an empty value.
   *
   * @hooked wp_loaded
   * @priority 1
   */

  public function kudos_check_honeypot_trap_sprung ()

  {
    if ( ! empty( $_POST ) && ! empty( $_POST['register'] ) && array_key_exists( 'wc_website_input', $_POST ) )

    {
      if ( $_POST['wc_website_input'] === '' )

      {
        $this->allow_registration = TRUE;

      }
     else

      {
        $this->allow_registration = FALSE;
      }

    }
    // WooCommerce will do all its own other stuff following this...
  }
  /**
   * Return an error if the honeypot trap was sprung
   *
   * @hooked woocommerce_registration_errors
   * @param \WP_Error $errors
   * @param string $usernames
   * @param string $email
   * @returns \WP_Error
   */

  public function kudos_check_honeypot_trap_sprung_errors ( $errors, $username, $email )

  {
    if ( ! $this->allow_registration )

    {

      $errors = new \WP_Error( 'registration-error-invalid-honeypot', __( 'Oops!  Our form flagged this registration attempt as a non-human submission.', 'woocommerce' ) );      
    }
    return $errors;

  }

}

