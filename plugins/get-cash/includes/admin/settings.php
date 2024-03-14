<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

$get_cash_options = get_option( 'get_cash_option_name' ); // Array of All Options
settings_errors();

$new = " <sup style='color:#0c0;'>NEW</sup>";
$improved = " <sup style='color:#0c0;'>IMPROVED</sup>";
$comingSoon = " <sup style='color:#00c;'>COMING SOON</sup>";

?>

<div class="container">

	<div>
		<h1>Get Cash Shortcodes</h1>
		<br>
		<p>Receive funds, tips, donations on WordPress via Cash App, Venmo, PayPal, Zelle with a button or QR Code anywhere on your website</p>
		<br>
	</div>

	<div class="row">
		<div class="col-12 col-md-7">

			<form method="post" action="options.php">
				<?php
					settings_fields( 'get_cash_option_group' );
					do_settings_sections( 'get-cash-admin' );
					echo "<label style='color:#00c; margin: 10px 0'>Update Styles / CSS: <span style='margin-left: 35px'>COMING SOON</span></label>";
					submit_button();
				?>
			</form>

			<h2>Example Shortcodes<?php echo $new; ?></h2>
			<div><img src="<?php echo GET_CASH_PLUGIN_DIR_URL . 'images/get-cash-shortcodes.jpg'; ?>" alt="Get Cash Shortcodes"></div>
		</div>

		<div class="col-12 col-md-5">
			<h2>About [get-cash] and [get-cash-form]<?php echo $new; ?></h2>

			<h5>Default and available options for <strong>[get-cash]</strong></h5>
			<p><code>[get-cash title='Send Cash' amount='' qr='yes' zelle='' paypal='' paypalqr='yes' venmo='' venmoqr='yes' venmonote='Thank you' cashapp='' cashappqr='yes']</code></p>

			<h5>Default and available options for <strong>[get-cash-form]</strong></h5>
			<p><code>[get-cash-form title='Send Cash' subtitle='Send Cash' amount='' currency='USD' receiver='' receiver_method='' payment_options='Cash App, Venmo, Paypal, Zelle' button='Generate Payment Link' class='']</code></p>

			<h5>On [get-cash-form] submission, the following events will occur:</h5>
			<ul class="list-inline">
				<li>An email is sent to the receiver email in settings</li>
				<li>A Get Cash transaction receipt post is created in your admin dashboard for reference</li>
				<li>A QR/button for the relevant payment method is generated with all information</li>
			</ul>
			<p>Try it out on <a role="button" class="button btn btn-primary" href="https://gurastores.com/get-cash" target="_blank">our shop</a></p>

			<h2>Example shortcodes</h2>
			<h5>Place shortcodes in any of these formats anywhere on your site</h5>
			<p>Options you can add in your shortcode are:</p>
			<ul class="list-inline">
				<li><code>[<strong>cashapp</strong>]</code> | <code>[<strong>venmo</strong>]</code> | <code>[<strong>paypal</strong>]</code></li>
				<li><code>[cashapp <strong>qr="yes"</strong>]</code> | <code>[venmo <strong>qr="no"</strong>]</code></li>
				<li><code>[paypal <strong>amount="123"</strong>]</code></li>
				<li><code>[venmo qr="no" amount="15" <strong>note="Custom note goes here"</strong>]</code> <br><em><a style="text-decoration:none" href="https://theafricanboss.com/get-cash/" target="_blank"><sup style="color:blue"><strong>note</strong> only for Venmo in the PRO version</sup></a></em></li>
				<li><code>[get-cash]</code> <br><em><a style="text-decoration:none" href="https://theafricanboss.com/get-cash/" target="_blank"><sup style="color:blue"><strong>note</strong> only in the PRO version</sup></a></em></li>
				<li><code>[get-cash-form]</code> <br><em><a style="text-decoration:none" href="https://theafricanboss.com/get-cash/" target="_blank"><sup style="color:blue"><strong>note</strong> only in the PRO version</sup></a></em></li>
			</ul>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Simple Cash App Shortcode</h5>
					<h6 class="card-subtitle mb-2 text-muted">[cashapp]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Simple Venmo Shortcode</h5>
					<h6 class="card-subtitle mb-2 text-muted">[venmo]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Simple PayPal Shortcode</h5>
					<h6 class="card-subtitle mb-2 text-muted">[paypal]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Simple Zelle Shortcode</h5>
					<h6 class="card-subtitle mb-2 text-muted">[zelle]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Shortcode with default amount</h5>
					<h6 class="card-subtitle mb-2 text-muted">[cashapp amount="20"]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Choose between QR code or Cash App logo</h5>
					<h6 class="card-subtitle mb-2 text-muted">[cashapp qr="yes"]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Choose between QR code or Cash App logo with default amount</h5>
					<h6 class="card-subtitle mb-2 text-muted">[cashapp amount="30" qr="yes"]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Venmo logo with transaction note prepopulated</h5>
					<h6 class="card-subtitle mb-2 text-muted">[venmo amount="11" qr="no" note="Thank you for your work"]</h6>
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Zelle with amount</h5>
					<h6 class="card-subtitle mb-2 text-muted">[zelle amount="20"]</h6>
				</div>
			</div>

		</div>

	</div>
</div>