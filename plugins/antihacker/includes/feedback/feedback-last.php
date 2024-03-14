<?php  Namespace AntiHacker_last_feedback{
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
    if( is_multisite())
       return;
    if ( __NAMESPACE__ == 'BoatDealerPlugin_last_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "boat_dealer_plugin" );
        define(__NAMESPACE__ .'\VERSION', BOATDEALERPLUGINVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://BoatDealerPlugin.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Boat Dealer Plugin" );
        define(__NAMESPACE__ .'\"antihacker"', "boatdealer" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
        define(__NAMESPACE__ .'\OPTIN', "boat_dealer_plugin_optin" );
        define(__NAMESPACE__ .'\LAST', "boat_dealer_last_feedback" );
        define(__NAMESPACE__ .'\URL', BOATDEALERPLUGINURL);
    }
     elseif ( __NAMESPACE__ == 'AntiHacker_last_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "anti_hacker" );
        define(__NAMESPACE__ .'\VERSION', ANTIHACKERVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://AntiHackerPlugin.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Anti Hacker Plugin" );
        define(__NAMESPACE__ .'\"antihacker"', "antihacker" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
        define(__NAMESPACE__ .'\OPTIN', "anti_hacker_optin" );
        define(__NAMESPACE__ .'\LAST', "anti_hacker_last_feedback" );
        define(__NAMESPACE__ .'\URL', ANTIHACKERURL);
    }
     elseif ( __NAMESPACE__ == 'ReportAttacks_last_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "report_attacks" );
        define(__NAMESPACE__ .'\VERSION', REPORTATTACKSVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://ReportAttacks.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Report Attacks Plugin" );
        define(__NAMESPACE__ .'\"antihacker"', "reportattacks" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
        define(__NAMESPACE__ .'\OPTIN', "report_attacks_optin" );
        define(__NAMESPACE__ .'\LAST', "report_attacks_last_feedback" );
        define(__NAMESPACE__ .'\URL', REPORTATTACKSURL);
    }
     elseif ( __NAMESPACE__ == 'StopBadBots_last_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "stop_bad_bots" );
        define(__NAMESPACE__ .'\VERSION', STOPBADBOTSVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://StopBadBots.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Stop Bad Bots Plugin" );
        define(__NAMESPACE__ .'\"antihacker"', "stopbadbots" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
        define(__NAMESPACE__ .'\OPTIN', "stop_bad_bots_optin" );
        define(__NAMESPACE__ .'\LAST', "stop_bad_bots_last_feedback" );
        define(__NAMESPACE__ .'\URL', STOPBADBOTSURL);
    }
   
   
$last_feedback =  (int) sanitize_text_field(get_site_option(LAST, '0'));

    
if($last_feedback == 0){
  $delta = 0;
  $last_feedback = time();
}
else{
   $delta = (1 * 24 * 3600);
}

// debug
// $delta = 0;


    if ( $last_feedback + $delta <= time() ) {
		// return;
		define( __NAMESPACE__ . '\AHSHOW', true );
	}
	else
	    define( __NAMESPACE__ . '\AHSHOW', false );


 class Bill_Config {
     protected static $namespace = __NAMESPACE__;
     protected static $bill_plugin_url = URL;
     protected static $bill_class = PRODCLASS;
     protected static $bill_prod_veersion = VERSION;
	function __construct() {
	  	add_action( 'load-plugins.php', array( __CLASS__, 'init' ) );
	   	add_action( 'wp_ajax_bill_feedback',  array( __CLASS__, 'feedback' ) );
    }
	public static function init() {
		add_action( 'in_admin_footer', array( __CLASS__, 'message' ) );
		add_action( 'admin_head',      array( __CLASS__, 'register' ) );
		add_action( 'admin_footer',    array( __CLASS__, 'enqueue' ) );
	}
	public static function register() {
	    wp_enqueue_style( PRODCLASS , URL.'includes/feedback/feedback-plugin.css');
        if(AHSHOW)
          wp_register_script( PRODCLASS, URL.'includes/feedback/feedback-last.js' , array( 'jquery' ), VERSION , true );
	}
	public static function enqueue() {
		wp_enqueue_style( PRODCLASS );
		wp_enqueue_script( PRODCLASS );
	}
   	public static function message() {
    if( ! update_option(LAST, time() ))
        add_option(LAST, time() );


        ?>  
        <div class="<?php echo esc_attr( PRODCLASS ); ?>-wrap-deactivate" style="display:none">
           <div class="bill-vote-gravatar"><a href="https://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="Bill Minozzi" width="70" height="70"></a></div>
             <div class="bill-vote-message">

            <?php
            echo '<h2 style="color:blue;">';
            echo __( "We're sorry to hear that you're leaving.", 'antihacker' );
            echo '</h2>'; 
            _e( 'Hello,', 'antihacker' );
            echo '<br />'; 
            echo '<br />'; 
            _e( 'We have other essential, <strong>free </strong> plugins to enhance your website, including options for security, utilities, backup, and more.
            ', 'antihacker' );
            // echo '<br />'; 
            _e( "We'd like to thank you for trying our products.", 'antihacker' );
              ?>
              <br /><br />             
              <strong><?php _e( 'Best regards!', 'antihacker' ); ?></strong>
              <br /><br /> 
              Bill Minozzi<br /> 
              Plugin Developer<br />
              Since 2013
              <br /> <br /> 
                     <a href="<?php echo admin_url('admin.php?page=antihacker_new_more_plugins'); ?>" class="button button-primary <?php echo esc_attr( PRODCLASS ); ?>-close-submit"><?php _e( 'Discover New Plugins', 'antihacker' ); ?></a>
                     <a href="https://BillMinozzi.com/dove/" class="button button-primary <?php echo PRODCLASS; ?>-close-dialog"><?php _e( 'Support Page', 'antihacker' ); ?></a>
                     <a href="#" class="button <?php echo esc_attr( PRODCLASS ); ?>-close-dialog"><?php _e( 'Cancel', 'antihacker' ); ?></a>
                     <a href="#" class="button <?php echo esc_attr( PRODCLASS ); ?>-deactivate"><?php _e( 'Just Deactivate', 'antihacker' ); ?></a>
              <br /><br />
            </div>
      </div> 
		<?php
	}
 }
 new Bill_Config;
 /*
 if( ! update_option('bill_last_feedback', '1' ))
     add_option('bill_last_feedback', '1' );
 */
$stringtime = strval(time());
 if ( ! update_option( LAST, $stringtime ) ) {
		add_option( LAST, $stringtime );
	}
} // End Namespace ...
?>