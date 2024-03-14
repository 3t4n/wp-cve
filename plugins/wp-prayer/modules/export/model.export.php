<?php
/**
* Class: WPE_Model_Settings
* @author Flipper Code <hello@flippercode.com>
* @version 1.0.0
* @package Forms
*/
if ( ! class_exists( 'WPE_Model_Export' ) ) {
	
	/**
	* Setting model for Plugin Options.
	* @package Forms
	* @author Flipper Code <hello@flippercode.com>
	*/
	class WPE_Model_Export extends FlipperCode_WPE_Model_Base {
		
		/**
		* Intialize Backup object.
		*/
		function __construct() {

		}
		
		/**
		* Admin menu for Settings Operation
		* @return array Admin menu navigation(s).
		*/
		function navigation() {
			return array(
				'wpe_prayers_export' => __( 'Export', WPE_TEXT_DOMAIN ),
			);
		}

		function do_extra($pagehook) {

			//Check if form submitted then download the file.
			add_action('load-'.$pagehook, array( $this, 'export' ) );
		}

		/**
		* Add or Edit Operation.
		*/
		function export() {
            global $wpe_prayer;
			$response = array();

			if(empty($_POST)) {
				$wpe_prayer['response'] = array();
				return;
			} 

			//require_once(WPE_Model.'/export/download_export.php');
			
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( $_REQUEST['_wpnonce']  ); 
			} else {
				$wpe_prayer['response'] = array('error','Invalid form submission.');
			}

			$nonce = wp_create_nonce( 'wpgmp-nonce4' );
			if ( !isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wpgmp-nonce4' ) ) {
				die( 'Cheating...' );
			}
			
			
			//If form set then go for export files
			if( isset($_POST) && array_key_exists('lxt_export_prayers',$_POST)){
    
				//set variables
    			global $wpdb;
				$export_table 	=	sanitize_text_field($_POST['lxt_table']);
				$is_method 		= 	sanitize_text_field($_POST['format']);
				$is_method 		=	strtolower($is_method);
				$sdate 			=	false;
				$ldate 			=	false;
				$date_cond		=	'';

	
				if( isset($_POST['start_date']) &&  !empty($_POST['start_date']) ){
					$sdate 	=	date('Y-m-d', strtotime($_POST['start_date']));
				}//End ofcheck and set start date if avail

				
				if( isset($_POST['end_date']) &&  !empty($_POST['end_date']) ){
					$ldate 	=	date('Y-m-d', strtotime($_POST['end_date']));
				}//End of check and set end date if avail

				
				$export_query = "SELECT * FROM $export_table";
				$date_cond =false;

				//set date query
				if($sdate && $ldate){
					if( $sdate > $ldate ){
						$wpe_prayer1 = __('End date must be greater than start date',WPE_TEXT_DOMAIN);
						$wpe_prayer['response'] = array('error',$wpe_prayer1);
					}else if( $sdate == $ldate ){
						$date_cond =" WHERE 1 ";
						$date_cond.=" AND DATE(prayer_time) = '$sdate'";
					}else{
						$date_cond =" WHERE 1 ";
						$date_cond.=" AND DATE(prayer_time) >= '$sdate' AND DATE(prayer_time) <= '$ldate'";
					}	
				}else{
					$wpe_prayer1 = __('Both dates must be selected',WPE_TEXT_DOMAIN);
					$wpe_prayer['response'] = array('error',$wpe_prayer1);
				}//End of set date query condition

				if($date_cond){

					$export_query .= $date_cond;

					//set export records
					$export_table_records = array();
					$export_table_records = $wpdb->get_results( $export_query , ARRAY_A);

				    //print_r($export_table_records);
				    if(!is_wp_error( $export_table_records ) &&  $export_table_records){

				    	switch ($is_method) {
				    		case 'pdf':
				    		    $tcpdf_headline='From '.$sdate.'  To '.$ldate;
				    			require_once(WPE_Model.'export/prayers_export_pdf.php');
				    			break;
				    		case 'csv':
				    			require_once(WPE_Model.'export/prayers_export_csv.php');
				    			break;
				    		case 'xls':
				    			require_once(WPE_Model.'export/prayers_export_xls.php');
				    			break;
				    		default:
				    			$wpe_prayer['response'] = array('error','Invalid Export Method. Method not defined.');
				    			break;
				    	}
					} else {
						$wpe_prayer1 = __('No Record found for selected values',WPE_TEXT_DOMAIN);
						$wpe_prayer['response'] = array('error','No Record found for selected values');
					}

				}//End of check if data condition match
				
			}
		}
	}
}




