<style>
	.vg-frontend-admin-guests {
		text-align: center;
		margin: 0 auto 50px;
		max-width: 304px;
	}

	.vg-frontend-admin-guests form {
		text-align: left
	}

	.vg-frontend-admin-guests label {
		display: block
	}

	.vg-frontend-admin-guests form input[type=text],
	.vg-frontend-admin-guests form input[type=password],
	.vg-frontend-admin-guests form input[type=submit] {
		width: 100%
	}

	.vg-frontend-admin-guests form input[type=submit] {
		padding: 5px;
		border: 0
	}
	.vg-frontend-admin-guests .vgfa-alert-failed {
		color: black;
		background-color: #ffc4c4;
		padding: 4px;
	}
</style>
<?php 
do_action( 'login_enqueue_scripts' );
?>
<div class="vg-frontend-admin-guests">
	<?php 

if ( !empty($_GET['vgfa_login_failed']) ) {
    ?>
		<p class="vgfa-alert-failed"><?php 
    _e( 'Login failed. Please enter the right user and password', VG_Admin_To_Frontend::$textname );
    ?></p>
	<?php 
}

?>
	<?php 
echo  $login_message ;
?>
	<?php 
echo  str_replace( '</form>', '<input type="hidden" name="wpfa_login_form" value="yes"/></form>', $login_form ) ;
?>
</div>

