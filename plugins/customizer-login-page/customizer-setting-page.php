<?php
/**
 * Dashboard customizer login page.
 *
 * @package customizer login page.
 */

?>
<style>
	.awp-customizer {
		width: auto;
		height: auto;
		padding: 15px;
		margin: 15px 15px 0px 0px;
		-webkit-box-shadow: 1px 9px 83px 9px rgba(16, 15, 15, 0.2);
		-moz-box-shadow: 1px 9px 83px 9px rgba(16, 15, 15, 0.2);
		box-shadow: 1px 9px 83px 9px rgba(16, 15, 15, 0.2);
		background-color : #fff;
	}
	.awp-heading-text{
		display: block;
		font-weight: 700;
		text-align: center;
		padding: 20px;
		background: linear-gradient(110deg, #4caf50c2 49%, #008cbab8 51%);
		color: #fff;
	}
	.awp-paragraph {
		font-size: 15px;
		margin: 1em 15px;
		font-weight: 500;
	}
	.awp-button {
		background-color: #4CAF50; /* Green */
		border: none;
		color: white;
		padding: 16px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 20px;
		margin: 8px 20px;
		-webkit-transition-duration: 0.4s; /* Safari */
		transition-duration: 0.4s;
		cursor: pointer;
		font-weight: 500;
	}
	.awp-button1 {
		background-color: white; 
		color: black; 
		border: 2px solid #4CAF50;
	}
	.awp-button1:hover {
		background-color: #4CAF50;
		color: white;
	}
	.awp-button2 {
		background-color: white; 
		color: black; 
		border: 2px solid #008CBA;
	}
	.awp-button2:hover {
		background-color: #008CBA;
		color: white;
	}
	.awp-button3 {
		background: linear-gradient(110deg, #81f785c2 47%, #00a6deb8 74%); 
		color: black; 
		border: 2px solid #008CBA;
	}
	.awp-button3:hover {
		background-color: #008CBA;
		color: white;
	}
	.awp-button-advance {
		background-color: #FF5733; /* Example color */
		color: white;
		border: 2px solid #FFC300;
	}

	.awp-button-advance:hover {
		background-color: #FFC300;
		color: black;
	}

	.awp-warning {
		font-size: 16px;
		margin: 1em 15px;
		font-weight: 500;
		color: red; /* Example warning color */
		text-align: center;
	}
	/* seprator line  */
	.awp-separator{
		display:flex;
		align-items: center;
	}

	.awp-separator .awp-line{
		height: 3px;
		flex: 1;
		background-color: #000;
	}

	.awp-separator h2{
		padding: 0 2rem;
	}
</style>
<div class="awp-customizer" style="text-align:center;" >
	<div id="awp-login-logo"></div>
	<h1 class="awp-heading-text"><?php esc_html_e( 'Customizer Login Page', 'customizer-login-page' ); ?></h1>
	<p class="awp-paragraph"><?php esc_html_e( 'Customizer Login page plugin allows you to easily customize your login page straight from your WordPress Customizer', 'customizer-login-page' ); ?></p>
	<div style="text-align:center;">
		<!-- seprator line -->
		<div class="awp-separator" style="margin-top:30px;"><div class="awp-line"></div><h2>Switch to Latest Build</h2><div class="awp-line"></div></div>
		<!-- end seprator line -->
			<p class="awp-paragraph" style="text-align:center;font-size:20px;"><?php esc_html_e( 'Switch to Lastest Build of this plugin which has many more customizations and settings supported with latest WordPress funcionalities', 'customizer-login-page' ); ?></p>
			<p class="awp-warning">
				<?php esc_html_e( 'Warning: All old plugin customizations will be lost.', 'customizer-login-page' ); ?>
			</p>
			<!-- New Button with ID -->
			<a href="#" id="advanceBuildButton" class="awp-button awp-button-advance">
				<?php esc_html_e( 'Click to Use Latest Advance Build of Customizer Login Page', 'customizer-login-page' ); ?>&nbsp;<span class="dashicons dashicons-update-alt"></span>
			</a>
			</a>
			<!-- Warning Text -->
		<!-- seprator line -->
		<div class="awp-separator" style="margin-bottom:30px;"><div class="awp-line"></div><h2>Switch to Latest Build</h2><div class="awp-line"></div></div>
		<!-- end seprator line -->
	</div>

	<p class="awp-paragraph"><?php esc_html_e( 'In Customizer, navigate to Login Customizer', 'customizer-login-page' ); ?>.</p>
	<a href="<?php echo esc_url( get_admin_url() ); ?>customize.php?url=<?php echo esc_url( wp_login_url() ); ?>" id="submit" class="awp-button awp-button1"><?php esc_html_e( 'Start Customizing', 'customizer-login-page' ); ?>&nbsp;<span class="dashicons dashicons-admin-customizer"></span></a>
	<p class="awp-paragraph"><?php esc_html_e( 'Thanks for using Customizer Login plugin. Don not forget to leave a review, It will be Helpful for Us.', 'customizer-login-page' ); ?></p>
	<a href="https://wordpress.org/support/plugin/customizer-login-page/reviews/?filter=5" target="_blank" id="submit" class="awp-button awp-button2"><?php esc_html_e( 'Love Us', 'customizer-login-page' ); ?>&nbsp;<span class="dashicons dashicons-heart"></span></a>
	<p class="awp-paragraph"><?php esc_html_e( 'Must Try Our Brand New Recommended Themes And Plugins', 'customizer-login-page' ); ?></p>
	<a href="https://awplife.com/" target="_blank" id="submit" class="awp-button awp-button3"><?php esc_html_e( 'A WP Life', 'customizer-login-page' ); ?>&nbsp;<span class="dashicons dashicons-admin-home"></span></a>
</div>
<script>
jQuery(document).ready(function($) {
	$('#advanceBuildButton').click(function(e) {
		e.preventDefault();
		var confirmAction = confirm("You confirm that you have read the warning and want to use the advance version.");
		if (confirmAction) {
			$.ajax({
				url: '<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'handle_advance_build.php' ); ?>',
				type: 'GET',
				data: { 'confirm_advance_build': 'true' },
				success: function(response) {
					console.log(response);
					// Redirect to the WordPress Dashboard or handle the response as needed
					window.location.href = "admin.php?page=loginpc-settings";
				}
			});
		}
	});
});
</script>
