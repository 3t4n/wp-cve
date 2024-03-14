<?php  Namespace AntiHacker_feedback{
    
    if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
    
    if( is_multisite())
       return;
       
    if ( __NAMESPACE__ == 'BoatDealerPlugin_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "boat_dealer_plugin" );
        define(__NAMESPACE__ .'\VERSION', BOATDEALERPLUGINVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://BoatDealerPlugin.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Boat Dealer Plugin" );
        define(__NAMESPACE__ .'\LANGUAGE', "LANGUAGE" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
     //   define(__NAMESPACE__ .'\OPTIN', "boat_dealer_plugin_optin" );
        define(__NAMESPACE__ .'\URL', BOATDEALERPLUGINURL);
     //   define(__NAMESPACE__ .'\DINSTALL', "bill_installed_LANGUAGEplugin" );
    }
     elseif ( __NAMESPACE__ == 'AntiHacker_feedback')
    {
        define(__NAMESPACE__ .'\PRODCLASS', "anti_hacker" );
        define(__NAMESPACE__ .'\VERSION', ANTIHACKERVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://AntiHackerPlugin.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Anti Hacker Plugin" );
        define(__NAMESPACE__ .'\LANGUAGE', "antihacker" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
    //    define(__NAMESPACE__ .'\OPTIN', "anti_hacker_optin" );
        define(__NAMESPACE__ .'\URL', ANTIHACKERURL);
    //    define(__NAMESPACE__ .'\DINSTALL', "bill_installed_antihacker" );
    }
     elseif ( __NAMESPACE__ == 'ReportAttacks_feedback')
    {
          
        define(__NAMESPACE__ .'\PRODCLASS', "report_attacks" );
        define(__NAMESPACE__ .'\VERSION', REPORTATTACKSVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://ReportAttacks.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Report Attacks Plugin" );
        define(__NAMESPACE__ .'\LANGUAGE', "reportattacks" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
     //   define(__NAMESPACE__ .'\OPTIN', "report_attacks_optin" );
        define(__NAMESPACE__ .'\URL', REPORTATTACKSURL);
     //   define(__NAMESPACE__ .'\DINSTALL', "bill_installed_reportattacks" );
    }
     elseif ( __NAMESPACE__ == 'StopBadBots_feedback')
    {
          
        define(__NAMESPACE__ .'\PRODCLASS', "stop_bad_bots" );
        define(__NAMESPACE__ .'\VERSION', STOPBADBOTSVERSION );
        define(__NAMESPACE__ .'\PLUGINHOME', "http://StopBadBots.com" );
        define(__NAMESPACE__ .'\PRODUCTNAME', "Stop Bad Bots Plugin" );
        define(__NAMESPACE__ .'\LANGUAGE', "stopbadbots" );
        define(__NAMESPACE__ .'\PAGE', "settings" );
     //   define(__NAMESPACE__ .'\OPTIN', "stop_bad_bots_optin" );
        define(__NAMESPACE__ .'\URL', STOPBADBOTSURL);
     //   define(__NAMESPACE__ .'\DINSTALL', "bill_installed_stopbadbots" );
    }
    
    // somente um, por enquanto
    define(__NAMESPACE__ .'\OPTIN', "bill-vote" );
    define(__NAMESPACE__ .'\DINSTALL', "bill_installed" );

 
 class Bill_Vote {
    
     protected static $namespace = __NAMESPACE__;
     protected static $bill_plugin_url = URL;
     protected static $bill_class = PRODCLASS;
     protected static $bill_prod_version = VERSION;
 
	function __construct() {
		add_action( 'load-plugins.php', array( __CLASS__, 'init' ) );
		add_action( 'wp_ajax_vote',  array( __CLASS__, 'vote' ) );
	}
	public static function init() {
        if(isset($_GET['page'] ))
        {
           $page = sanitize_text_field($_GET['page'] );
           if ($page != PAGE)
              return;
        }
        else
         return;
         
         
		$vote = get_option( OPTIN );
        // echo $vote; 
        
        // $vote = '';
        
  		$timeinstall =  get_option( DINSTALL, '');
        
        
        if($timeinstall == '')
        {
            $w = update_option(DINSTALL, time() );
            if (!$w)
              add_option(DINSTALL, time() );
         
            $timeinstall = time();    
                         
        }  
      
		$timeout = time() > ( $timeinstall + 60*60*24*3 );
        
        
        
        
        
		if ( in_array( $vote, array( 'yes', 'no' ) ) || !$timeout ) return;
		add_action( 'in_admin_footer', array( __CLASS__, 'message' ) );
		add_action( 'admin_head',      array( __CLASS__, 'register' ) );
		add_action( 'admin_footer',    array( __CLASS__, 'enqueue' ) );
	}
	public static function register() {
       
	    wp_enqueue_style( PRODCLASS , URL.'includes/feedback/feedback-plugin.css');
     	wp_register_script( PRODCLASS, URL.'includes/feedback/feedback.js' , array( 'jquery' ), VERSION , true );
	}
	public static function enqueue() {
		wp_enqueue_style( PRODCLASS );
		wp_enqueue_script( PRODCLASS );
	}
    
	public static function vote() {
		$vote = sanitize_text_field( $_GET['vote'] );
        
        // http://boatplugin.com/wp-admin/admin-ajax.php?action=vote&vote=no
		if ( !is_user_logged_in() || !in_array( $vote, array( 'yes', 'no', 'later' ) ) ) die( 'error' );
		$r = update_option( OPTIN, $vote );
        if(!$r)
  	    	 add_option( OPTIN, $vote );
     
    	if ( $vote === 'later' ) update_option( DINSTALL, time() );
		     wp_die( 'OK: ' . $vote );
	}
	public static function message() {
?>
		<div class="<?php echo esc_attr(PRODCLASS);?>-wrap-vote" style="display:none">
			<div class="bill-vote-wrap">
				<div class="bill-vote-gravatar"><a href="http://profiles.wordpress.org/sminozzi" target="_blank"><img src="https://en.gravatar.com/userimage/94727241/31b8438335a13018a1f52661de469b60.jpg?size=100" alt="<?php esc_attr_e('Bill Minozzi', 'antihacker' ); ?>" width="70" height="70"></a></div>
				<div class="bill-vote-message">
					<p>
                    <?php 
                       esc_attr_e( 'Hello, my name is Bill Minozzi, and I am developer of', 'antihacker' );
                       echo ' ' . esc_attr(PRODUCTNAME);
                       echo '. ';
                      esc_attr_e('If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.<br><b>Thank you!</b>', 'antihacker' ); ?></p>
					<p>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=yes" class="bill-vote-action button button-medium button-primary" data-action="<?php echo esc_attr(PLUGINHOME);?>/share/"><?php esc_attr_e('Rate or Share', 'antihacker' ); ?></a>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=no" class="bill-vote-action button button-medium"><?php esc_attr_e('No, dismiss', 'antihacker' ); ?></a>
<span><?php esc_attr_e('or', 'antihacker' ); ?></span>
						<a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=vote&amp;vote=later" class="bill-vote-action button button-medium"><?php esc_attr_e('Remind me later', 'antihacker' ); ?></a>
				        <input type="hidden" id="billclassvote" name="billclassvote" value="<?php echo esc_attr(PRODCLASS);?>" />
				        <input type="hidden" id="billclassvote" name="billclassvote" value="<?php echo esc_attr(PRODCLASS);?>" />

                    </p>
				</div>
				<div class="bill-vote-clear"></div>
		</div>
		<?php
	}
 }
 new Bill_Vote;
}
