<?php
/**                
 * Cron
 v2.0.0a
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//*****************************
//v1.1.6.1 Refactored
//*****************************

class VTMAM_Cron {

	public function __construct() {

    add_filter( 'cron_schedules', array( &$this, 'vtmam_add_schedules'   ) ); 
     
    // https://wordpress.stackexchange.com/questions/179694/wp-schedule-event-every-day-at-specific-time
    
    //AS the CLASS is instantiated in the INIT hook, you can't use that hook or an earlier one here.
    // wp_loaded follows INIT but is still early enough to be useful!
		
    //v2.0.0.2  added the IF - once this is scheduled, no reason to do this again.
    if ( ! wp_next_scheduled( 'vtmam_twice_daily_scheduled_events' ) ) {
      add_action( 'wp_loaded', array( &$this, 'vtmam_schedule_events' ) );
    }
    
 
  //  add_action( 'init', array( &$this, 'vtmam_schedule_events' ) ); //does not work
  //  add_action( 'admin_init', array( &$this, 'vtmam_schedule_events' ) );  //WORKS, but only on the admin side
  //  add_action( 'wp', array( &$this, 'vtmam_schedule_events' ) ); //does not work
 
	}


	public function vtmam_add_schedules( $schedules = array() ) {
 //error_log( print_r(  'BEGIN vtmam_add_schedules' , true ) ); 	
  	// Adds to the existing schedules.
/* v2.0.0.2
		$schedules['vtmam_thrice_daily'] = array(
			'interval' => 28800,
			'display'  => __( 'Every Eight Hours', 'vtmam' )
		);
 */   
    //v1.1.6.1 added
		$schedules['vtmam_twice_daily'] = array(
			'interval' => 43200,
			'display'  => __( 'Every 12 Hours', 'vtmam' )
		);    
		return $schedules;
	}


	public function vtmam_schedule_events() {
 //error_log( print_r(  'BEGIN vtmam_schedule_events' , true ) ); 
	//	$this->vtmam_thrice_daily(); v2.0.0.2
		$this->vtmam_twice_daily();
 
	}


	private function vtmam_twice_daily() {
 //error_log( print_r(  'BEGIN vtmam_twice_daily' , true ) );
		//v1.1.6.1 added
    if ( ! wp_next_scheduled( 'vtmam_twice_daily_scheduled_events' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'vtmam_twice_daily', 'vtmam_twice_daily_scheduled_events' );
 //error_log( print_r(  'Scheduled vtmam_twice_daily' , true ) );      
		}
	}  
   

}
$vtmam_cron = new VTMAM_Cron;

//cron job run out of license-options.php
// add_action( 'vtmam_thrice_daily_scheduled_events', 'vtmam_maybe_recheck_license_activation' );
