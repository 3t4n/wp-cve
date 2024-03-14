<?php
$insert = false;

if ( is_email( $_POST['subscriber_email'] ) ) {
	global $wpdb;
	$wpdb->hide_errors();

	if ( isset( $_POST['subscriber_email'] ) ) {

		//add subscriber email to database
		$insert = $wpdb->insert(
			"{$wpdb->prefix}maintenance_page",
			array(
				'email'       => $_POST['subscriber_email'],
				'insert_date' => current_time( 'mysql', true ),
			),
			array(
				'%s',
				'%s',
			)
		);
	}
} else {
	$email        = isset( $_POST['subscriber_email'] ) ? $_POST['subscriber_email'] : false;
	$erroResponse = __( 'Enter a Valid Email Id', 'maintenance-page' ); ?>
		<script>
			var erroResponse = '<?php echo $erroResponse; ?>';
			jQuery(document).ready(function($) {
				$(".inputsubs").attr("placeholder", erroResponse );
				$(".email-field").addClass('alert');
				$(".inputsubs").css('color','RED');
			});
		</script>
	<?php
}
if ( ! $insert === false ) {
	$response = __( 'You successfully subscribed. Thanks!', 'maintenance-page' );
	?>
	<script>
		jQuery(document).ready(function($) {
			var response = '<?php echo $response; ?>';
			$(".sign-up").html(response);
			$('.sign-up').addClass('response').removeClass('sign-up'); 
		});
	</script>
<?php }
?>
