<?php
$user_info = get_userdata(get_current_user_id());

$output = "";

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 

	global $woocommerce;
	$customer = new WC_Customer();

}
 
$adress = get_user_meta( $user_info->ID, 'billing_address_1', true );
$billig_first_name = get_user_meta( $user_info->ID, 'first_name', true );
$billig_last_name = get_user_meta( $user_info->ID, 'last_name', true );
$city = get_user_meta( $user_info->ID, 'billing_city', true );
$zip_code = get_user_meta( $user_info->ID, 'billing_postcode', true );
$billing_email = get_user_meta( $user_info->ID, 'billing_email', true );



//Delete user // uncomment for live
if (isset($_POST['delete_user_data'])) {
	
	//if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'once_remove_user' ) ) {
		
		//$output .= "Ihre Sitzung ist abgelaufen bitte aktualisieren Sie die Seite.";
		
	//} else {
		
		require_once(ABSPATH.'wp-admin/includes/user.php' );

		$current_user = wp_get_current_user();

		//double check "privilegs"
		if ( is_user_logged_in() ) {
			wp_delete_user( $current_user->ID );
			$output .= __("Your user account including all data was successfully and irrevocably deleted. You can create a new account at any time.", "dsgvo-all-in-one-for-wp"); 

			//header(get_home_url());
		}
		
	//}	
} else {
if (!isset($language)) $language = wf_get_language();

if ($language == "de") {	
	if (get_option('dsgvo_deleteaccounttext'))	{
		$dsgvo_deleteaccounttext = html_entity_decode(get_option('dsgvo_deleteaccounttext'));
	} else {
		$dsgvo_deleteaccounttext = "<b>Achtung:</b> Mit dem Klick auf den Button \"Benutzerkonto löschen\" werden alle Ihre Daten aus unserer Datenbank gelöscht.<br />Ihre Daten können nicht wiederhergestellt werden nach der Löschung.";
	}	
} else if ($language == "en") {
	if (get_option('dsgvo_deleteaccounttext_en'))	{
		$dsgvo_deleteaccounttext = html_entity_decode(get_option('dsgvo_deleteaccounttext_en'));
	} else {
		$dsgvo_deleteaccounttext = "<b> Attention: </ b> By clicking on the button \"Delete user account\" all your data will be deleted from our database. <br /> Your data can not be recovered after deletion.";
	}	
} else if ($language == "it") {
	if (get_option('dsgvo_deleteaccounttext_it'))	{
		$dsgvo_deleteaccounttext = html_entity_decode(get_option('dsgvo_deleteaccounttext_it'));
	} else {
		$dsgvo_deleteaccounttext = "<b>Attenzione:</b> Cliccando sul pulsante \"Cancella account utente\" tutti i vostri dati saranno cancellati dal nostro database.<br />I vostri dati non possono essere ripristinati dopo la cancellazione.";
	}		
} else {
	
}
	
$output .='
	<h2>'.__("Delete User Account", "dsgvo-all-in-one-for-wp").'</h2>
	<div class="dsgvoaio_notice_info">
		<span class="dashicons dashicons-info"></span>'.$dsgvo_deleteaccounttext.'
	</div>
	<p style="margin-bottom: 0px;"><b>'.__("Extract of your Data", "dsgvo-all-in-one-for-wp").'</b></p>
	<form action="" method="POST" class="dsgvoaio_removeform">
	<table>
		<tr style="display: none;">
			<td>'.__("User ID", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.get_current_user_id().'" readonly/></td>
		</tr>
		<tr>
			<td>'.__("Username", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$user_info->nickname.'" readonly/></td>
		</tr>			
		';
		
		
		if($user_info->first_name) { 
		$output .= '
		<tr>
			<td>'.__("Fistname", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$user_info->first_name.'" readonly/></td>
		</tr>';
		 } 
		if($user_info->last_name) { 
		$output .= '
		<tr>
			<td>'.__("Lastname", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$user_info->last_name.'" readonly/></td>
		</tr>
		';
		}
		
		$output .= '
		<tr>
			<td>'.__("E-mail Adress", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$user_info->user_email.'" readonly/></td>
		</tr>		
		';
		
		if($adress) {
		$output .= '
		<tr>
			<td class="dsgvoaio_td_full" colspan="2"><label><b>'.__("Billing Adress", "dsgvo-all-in-one-for-wp").'</b></label></td>
		</tr>
		';
		}
		if($billig_first_name) {
		$output .= '
		<tr>
			<td>'.__("Fistname", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$billig_first_name.'" readonly/></td>
		</tr>
		';
		}	
		if($billig_last_name) {
		$output .= '
		<tr>
			<td>'.__("Lastname", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$billig_last_name.'" readonly/></td>
		</tr>
		';
		}		
		if($adress) {
		$output .= '
		<tr>
			<td>'.__("Street", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$adress.'" readonly/></td>
		</tr>
		';
		}
		if($city) {
		$output .= '
		<tr>
			<td>'.__("City", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$city.'" readonly/></td>
		</tr>
		';
		}
		if($zip_code) {
		$output .= '
		<tr>
			<td>'.__("Zip", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$zip_code.'" readonly/></td>
		</tr>
		';
		}
		if (get_user_meta( $user_info->ID, 'billing_country', true )) {
		$output .= '
		<tr>
			<td>'.__("Country", "dsgvo-all-in-one-for-wp").':
			<td><input type="text" value="'.get_user_meta( $user_info->ID, 'billing_country', true ).'" readonly/>
		</tr>
		';		
		}
		if($billing_email) {
		$output .= '
		<tr>
			<td>'.__("E-mail Adress", "dsgvo-all-in-one-for-wp").':</td>
			<td><input type="text" value="'.$billing_email.'" readonly/></td>
		</tr>
		';
		}					
		
					$user_meta = get_user_meta ( $user_info->ID);
					
					if (isset($user_meta['community-events-location'][0])) {

						$useripdata = explode(":", $user_meta['community-events-location'][0]);

						$userip = str_replace('"',"", $useripdata);

						$userip = str_replace(';}',"", $userip);					

						

						if ($userip[6]) {
							
						$userip = preg_replace('/([0-9]+\\.[0-9]+\\.[0-9]+)\\.[0-9]+/', '\\1.xxx', $userip[6]);	

						$output .= "<tr>";

						$output .= "<td>".__("Saved IP Adress", "dsgvo-all-in-one-for-wp")."</td>";

						$output .= '<td><input type="text" value="'.$userip.'" readonly/></td>';

						$output .= "</tr>";
						}
					
					}
		
		
		
		$output .= "<br />";
		//$field = wp_nonce_field( 'once_remove_user' );
		$output .= wp_nonce_field( 'once_remove_user', '_wpnonce', true, false );
		$output .= "<tr><td class='dsgvoaio_td_full' colspan='2'><button type='submit' name='delete_user_data' class='delete_user_data'"; 
					
		if(current_user_can('administrator')) { $output .= "disabled"; }
					
		$output .= "><span class='dashicons dashicons-trash'></span>".__("Delete User Account", "dsgvo-all-in-one-for-wp")."</button>";
		$output .= "</td></tr>";
		
		if(current_user_can('administrator')) {

			$output .= "<tr style='color: red;'><td class='dsgvoaio_td_full' colspan='2'>".__("Since you are an administrator, you cannot delete your account here and the button is disabled", "dsgvo-all-in-one-for-wp").".</td></tr>";
		}
		
		$output .= '<tr style="color: red;"><td class="dsgvoaio_td_full"  colspan="2"><u>'.__("By clicking on the button \"Delete user account\" your data will be irrevocably deleted", "dsgvo-all-in-one-for-wp").'!</u></td></tr></table></form>';
	
	
}
return $output;
?>