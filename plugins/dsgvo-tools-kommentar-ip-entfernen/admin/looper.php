<?php
add_action( 'fhw_dsgvo_kommentare_rotation', 'fhw_dsgvo_kommentare_rotation_func' );
function fhw_dsgvo_kommentare_rotation_func() {
	global $wpdb;
	if( 'on' != get_option( 'fhw_dsgvo_kommentar_time_removement' ) )
		return;
	$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->comments SET comment_author_IP = %s WHERE comment_date_gmt <= DATE_SUB(NOW(), INTERVAL %d DAY)", null, esc_attr( get_option( 'fhw_dsgvo_kommentar_removement_time', "180" ) ) ) );
	error_log( $wpdb->prepare( "UPDATE $wpdb->comments SET comment_author_IP = %s WHERE comment_date_gmt <= DATE_SUB(NOW(), INTERVAL %d DAY)", null, esc_attr( get_option( 'fhw_dsgvo_kommentar_removement_time', "180" ) ) ) );
}
?>