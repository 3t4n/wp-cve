<?php
/** 
 *	Ajax
 */

add_action('wp_head', 'YESNO_add_ajaxurl', 1 );
add_action('wp_ajax_YESNO_next_question', 'YESNO_next_question' );
add_action('wp_ajax_nopriv_YESNO_next_question', 'YESNO_next_question' );

/**
 *	ajax url
 */
function YESNO_add_ajaxurl() {
	?>
	    <script>
	        var ajaxurl = "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";
	    </script>
	<?php
}

/**
 *	Get next question
 */
function YESNO_next_question(){
	global $wpdb;

	$qid = absint( wp_unslash( $_POST['qid'] ) );
	$ret = array();
	if ( $qid > 0 ) {
		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
	    $table = $prefix.'question';
		$query = "SELECT * FROM {$table} "
				."WHERE `qid`=%d ";
		$ret = $wpdb->get_row( $wpdb->prepare( $query, $qid ), ARRAY_A );
		if ( ! empty( $ret ) ) {
			$ret['question'] = htmlspecialchars_decode( $ret['question'] );
			$ret['choices'] = unserialize( $ret['choices'] );
		}
	}
	header( 'Content-type: application/json' );
	echo json_encode( $ret );
	die();
}
