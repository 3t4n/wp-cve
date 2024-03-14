<?php

/***** ADD OPTIONS *****/
function fca_eoi_gdpr_settings_heading() {
	echo '<p>If you are collecting data on people in the EU, the GDPR requires consent for any marketing activities, and will store that consent. 
			Enabling this setting below will let your subscribers give explicit consent to receive marketing emails.<br>
			Note that turning this feature on is only part of making your business GDPR compliant. We recommend consulting with a lawyer.</p>';
}
function fca_eoi_add_gdpr_options( $array ) {
	$gdpr_text = 'Enabling this will:<br><br>
	1. Add a checkbox, which is unchecked by default, to all existing & new forms. To increase conversions, the checkbox will show after your subscriber submits his email.<br>
	2. Log consent in the <a href="' . admin_url( 'edit.php?post_type=easy-opt-ins&page=fca_eoi_subscribers_page' ) . '">Subscribers</a> table.<br><br>
	Note: Your subscribers will only be sent onwards to your email provider, if they check the consent checkbox.';

	$array[] = array( 'gdpr_checkbox', 'EU GDPR Checkbox', 'fca_eoi_checkbox_callback', 'fca_eoi_gdpr_settings_section', $gdpr_text, 'easy-opt-ins');
	
	$array[] = array( 'consent_headline', 'Checkbox Headline', 'fca_eoi_wysi_callback', 'fca_eoi_gdpr_settings_section', '' );
	
	$array[] = array( 'consent_msg', 'GDPR Consent Statement', 'fca_eoi_wysi_callback', 'fca_eoi_gdpr_settings_section', 'I have read and agree to the email marketing terms & conditions' );

	$array[] = array( 'gdpr_locale', "Only show checkbox if subscriber's browser registers to the EU", 'fca_eoi_checkbox_callback', 'fca_eoi_gdpr_settings_section', "Will only show the consent checkbox if your subscriber's browser's location setting is set to the EU.<br>
				Note, this can't 100% guarantee that all EU residents will be caught, so use with caution.", 'easy-opt-ins');

	return $array;
	
}
add_filter( 'fca_eoi_setting_filter', 'fca_eoi_add_gdpr_options' );

function fca_eoi_is_gdpr_country( $accept_language = '' ) {
	$accept_language = empty( $accept_language ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : $accept_language;
	$gdpr_countries = array(
		"AT",
		"BE",
		"BG",
		"CY",
		"CZ",
		"DE",
		"DK",
		"EE",
		"EL",
		"ES",
		"FI",
		"FR",
		"HR",
		"HU",
		"IE",
		"IT",
		"LT",
		"LU",
		"LV",
		"MT",
		"NL",
		"PL",
		"PT",
		"RO",
		"SE",
		"SI",
		"SK",
		"UK",
		"GL",
		"GF",
		"PF",
		"TF",
		"GP",
		"MQ",
		"YT",
		"NC",
		"RE",
		"BL",
		"MF",
		"PM",
		"WF",
		"AW",
		"AN",
		"BV",
		"AI",
		"BM",
		"IO",
		"VG",
		"KY",
		"FK",
		"FO",
		"GI",
		"MS",
		"PN",
		"SH",
		"GS",
		"TC",
	);
		
	$code = '';

	//in some cases like "fr" or "hu" the language and the country codes are the same
	if ( strlen( $accept_language ) === 2 ){
		$code = strtoupper( $accept_language ); 
	} else if ( strlen( $accept_language ) === 5 ) {          
		$code = strtoupper( substr( $accept_language, 3, 5 ) ); 
	} 
	if ( in_array( $code, $gdpr_countries ) ) {
		return true;
	}
	
	if ( strlen( $accept_language ) > 5 ) {
		
		for ( $i=0; $i+2 < strlen( $accept_language ); $i++ ){
			$code = strtoupper( substr( $accept_language, $i, $i+2 ) );
			if ( in_array( $code, $gdpr_countries ) ) {
				return true;
			}
		}
	}
	return false;
}


function fca_eoi_show_gdpr_checkbox(){
	$settings = get_option( 'fca_eoi_settings' );
	if ( !empty( $settings['gdpr_checkbox'] ) ) {
		if ( empty( $settings['gdpr_locale'] ) ) {
			return true;
		}
		return fca_eoi_is_gdpr_country();
	}
	
	return false;
}

class EoiSubscribers {
	
	private static $instance;
	private $table_name;
		
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}	
	
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . "fca_eoi_subscribers";
		
		$subscribers_table_ver = get_option( 'fca_eoi_subscribers_table_ver' );
		if ( version_compare( $subscribers_table_ver, '1.0.0', '<' ) ) {
			//CREATE THE PEOPLE TABLE
			$sql = "CREATE TABLE $this->table_name (
				`id` INT NOT NULL AUTO_INCREMENT,
				`email` LONGTEXT,
				`name` LONGTEXT,
				`time` DATETIME,
				`timezone` LONGTEXT,
				`campaign_id` INT,
				`status` LONGTEXT,
				`consent_granted` LONGTEXT,
				`consent_msg` LONGTEXT,
				PRIMARY KEY  (id)
			);";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			update_option( 'fca_eoi_subscribers_table_ver', '1.0.0' );
		}
			
		add_action( 'fca_eoi_after_submission', array( $this, 'add_subscriber' ), 10, 2 ); 
		add_action( 'admin_menu', array( $this, 'register_subscribers_page') );
		add_action( 'plugins_loaded', array( $this, 'export_subscribers' ) );
		add_filter(	'wp_privacy_personal_data_exporters', array( $this, 'register_data_exporter' ) );
		add_filter(	'wp_privacy_personal_data_erasers', array( $this, 'register_data_eraser' ) );
	}
	public function register_data_exporter( $exporters ) {
		$exporters['easy-opt-ins'] = array(
			'exporter_friendly_name' => __( 'Optin Cat' ),
			'callback' => __CLASS__ .'::data_exporter',
		);
		return $exporters;
	}
	public function register_data_eraser( $erasers ) {
		$erasers['easy-opt-ins'] = array(
			'eraser_friendly_name' => __( 'Optin Cat' ),
			'callback' => __CLASS__ .'::data_eraser',
		);
		return $erasers;
	}
	
	public static function data_exporter( $email, $page = 1 ) {
		$EoiSubscribers = EoiSubscribers::get_instance();
		global $wpdb;
		$table_name = $EoiSubscribers->table_name;
		$number = 500; // Limit us to avoid timing out
		$page = (int) $page;
		$offset = ( $page - 1 ) * $number;
		$subscribers = $wpdb->get_results( "SELECT * FROM $table_name WHERE `email` = '$email' LIMIT $number OFFSET $offset" );
		$export_items = array();
		$fields = array(
			'email',
			'name',
			'time',
			'timezone',
			'camapgin_id',
			'status',
			'consent_granted',
			'consent_msg',		
		);
		
		
		if ( count( $subscribers ) )  {
			forEach ($subscribers as $subscriber ) {
				$item = array(
					'group_id' => 'subscriber',
					'group_label' => 'Subscriber',
					'item_id' => 'subscriber-' . $subscriber->id,
					'data' => array(),
				);
				
				forEach ( $fields as $field ) {
					if ( isSet( $subscriber->$field ) && $subscriber->$field !== '' ) {
						$item['data'][] = array(
							'name' => $field,
							'value' => $subscriber->$field
						);
						
					}
				}
						
				$export_items[] = $item;
			}
		}
		return array(
			'data' => $export_items,
			'done' => count( $export_items ) < $number,
		 );			 
	}
	
	public static function data_eraser( $email, $page = 1 ) {
		$EoiSubscribers = EoiSubscribers::get_instance();
		global $wpdb;
		$table_name = $EoiSubscribers->table_name;
		$number = 500; // Limit us to avoid timing out
		$page = (int) $page;
		$offset = ( $page - 1 ) * $number;
		$subscribers = $wpdb->get_results( "SELECT * FROM $table_name WHERE `email` = '$email' LIMIT $number OFFSET $offset" );
		$rows_deleted = 0;
				
		if ( count( $subscribers ) )  {
			forEach ( $subscribers as $subscriber ) {
				$rows_deleted += $wpdb->delete( $table_name, array( 'id' => $subscriber->id ), array( '%d' ) );
				
			}			
		}
		
		return array(
			'done' => $rows_deleted < $number,
			'items_removed' => $rows_deleted,
			'items_retained' => false,
			'messages' => array(), 
		 );			 
	}

	public function register_subscribers_page() {
		add_submenu_page(
			'edit.php?post_type=easy-opt-ins',
			__('Subscribers', 'easy-opt-ins'),
			__('Subscribers', 'easy-opt-ins'),
			'manage_options',
			'fca_eoi_subscribers_page',
			__CLASS__ .'::fca_eoi_subscribers_page'
		);
		
	}
	
	public function add_subscriber( $fca_eoi, $status ) {
		global $wpdb;
		$options = get_option( 'fca_eoi_settings' );
		$campaign_id = isSet( $fca_eoi['post_id'] ) ?  intVal( $fca_eoi['post_id'] ) : false;
		$provider = isSet( $fca_eoi['provider'] ) ? $fca_eoi['provider'] : false;
		$email = isSet( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : false;
		$name = isSet( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : false;
		$timezone = isSet( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : false;
		$time = current_time( 'mysql', 1 );
		$consent_granted = isSet( $_POST['consent_granted'] ) ? sanitize_text_field( $_POST['consent_granted'] ) : 'unknown';
		$consent_msg = $consent_granted === 'true' && isSet( $options['consent_msg'] ) ? $options['consent_msg'] : '';
		
		//COMMENT OPT IN
		if ( is_array( $status ) ) {
			$comment_settings = get_option( 'fca_eoi_comment_optin_settings' );
			$campaign_id = get_option( 'fca_eoi_comment_optin_post' );
			$email = $status['email'];	
			if ( isSet( $status['name'] ) ) {
				$name = $status['name'];
			}
			
			$consent_granted = isSet( $_POST['fca_eoi_comment_optin'] ) ? 'true' : 'unknown';
			$consent_msg = $consent_granted === 'true' && isSet( $comment_settings['checkbox_text'] ) ? $comment_settings['checkbox_text'] : '';
			$status = $status['status'];		
		}

		if ( $status === true ) {
			$status_msg = "added to $provider";
		} else if ( $status ) {
			$status_msg = "failed to add to $provider [$status]";
		} else {
			$status_msg = "added locally";
		}
		
		$wpdb->insert( $this->table_name, array(
			'campaign_id'   =>$campaign_id,
			'email'      => $email,
			'name'      => $name,
			'status' => $status_msg,
			'timezone' => $timezone,
			'time' => $time,
			'consent_granted' => $consent_granted,
			'consent_msg' => $consent_msg			
		), array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );
				
	}	

	public static function fca_eoi_subscribers_page() {
		$EoiSubscribers = EoiSubscribers::get_instance();
		global $wpdb;
		$table_name = $EoiSubscribers->table_name;
		$where = '';
		$search_text = '';
		$post_limit = 50;
		$page = empty( $_GET['paged'] ) ? 0 : intVal( $_GET['paged'] );
		$offset = $page * $post_limit;
		
		if (  isSet( $_POST['_wpnonce'] ) ) {
			$verified = wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ) );
			$search_text = sanitize_text_field( $_POST['search_text'] );
			
			if ( $verified && $search_text ) {
				if ( $search_text ) {
					$where = "WHERE ( `email` LIKE '%$search_text%' OR `name` LIKE '%$search_text%' )";
					
				}
			}
		}
		
		$subscribers = $wpdb->get_results( "SELECT * FROM $table_name $where ORDER BY `time` DESC LIMIT $post_limit OFFSET $offset" );
				
		ob_start(); ?>
		<div style='padding-right: 32px;'>
			<h1>Subscribers</h1>
			<p>List of people that opted in since you've updated to Optin Cat 2.2 (released May 2018)</p>
			<form method='post' id='fca_eoi_subscribers' >
				<?php wp_nonce_field(); ?>
				<input size='35' type='text' name='search_text' value='<?php echo $search_text ?>' ></input>
				<button class='button button-secondary' name='search' type='submit'>Search subscribers</button>
				<a href='<?php echo add_query_arg( array( 'fca_eoi_export' => true, '_wpnonce' => wp_create_nonce() ) ) ?>' class='button button-secondary' style='float:right;' download >Download CSV</a>
			</form>
			<br>
				<table class='wp-list-table widefat fixed striped'>
					<tr>			
						<th style='display:none;'>ID</th>
						<th>Email</th>
						<th>Name</th>
						<th>Time</th>
						<th>Timezone</th>
						<th>Optin Form</th>
						<th>Status</th>
						<th>Consent granted</th>
						<th>Consent message</th>
					</tr>
					<?php forEach ( $subscribers as $p ) { 
						$form_title = get_the_title( $p->campaign_id );
						
						$form_link = admin_url( "post.php?post=$p->campaign_id&action=edit" );
						
						if ( empty( $form_title ) ) {
							$form_title = '(no title)';
						}
						
						if ( $p->campaign_id == get_option( 'fca_eoi_comment_optin_post' ) ) {
							$form_link = admin_url( "edit.php?post_type=easy-opt-ins&page=fca_eoi_comment_optin_page" );
							$form_title = 'Comment Optin';
						}
						
						echo "<tr>
								<td style='display:none;'>$p->id</td>
								<td>$p->email</td>
								<td>$p->name</td>
								<td>$p->time</td>
								<td>$p->timezone</td>
								<td><a href='$form_link'>$form_title</a></td>
								<td>$p->status</td>
								<td>$p->consent_granted</td>
								<td>$p->consent_msg</td>
						</tr>";				
					} ?>
				</table>
				<br>
			<?php
			if ( $page ) {
				$prev_page_link = add_query_arg( 'paged', $page - 1 );
				echo "<a href='$prev_page_link'>Previous</a>";
			}
			if ( count( $subscribers ) >= $post_limit ) {
				$next_page_link = add_query_arg( 'paged', $page + 1 );
				echo "<a style='float:right;' href='$next_page_link'>Next</a>";
			}
			?>
		</div>
		
		<?php		
		echo ob_get_clean();
	}
	
	public function export_subscribers() {
		if ( is_user_logged_in() && current_user_can('manage_options') && isset( $_GET['fca_eoi_export'] ) && isset( $_GET['_wpnonce'] ) ) {
			if( !wp_verify_nonce( sanitize_text_field( $_GET['_wpnonce'] ) ) ) {
				echo 'Authentication failure.  Please log in and try again.';
				die();
			}
			$EoiSubscribers = EoiSubscribers::get_instance();
			global $wpdb;
			$table_name = $EoiSubscribers->table_name;
				
			$subscribers = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );
		
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=subscribers.csv');
			if ( empty( $subscribers ) ) {
				echo 'No results found.';
				die();	
			}
			$headings = array( 'id', 'email', 'name', 'time', 'ip', 'campaign_id', 'consent_granted', 'consent_message' );
			//$customers = array_map( 'array_values', $customers );
			$out = fopen('php://output', 'w');
			fputcsv( $out, $headings );
			foreach ( $subscribers as $fields ) {
				fputcsv( $out, $fields );
			}
			fclose( $out );
			die();			 
		}
	}


}
new EoiSubscribers();