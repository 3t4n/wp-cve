<?php
	$header = isset($header) ? $header : '';
	$body = isset($body) ? $body : '';
	$footer = isset($footer) ? $footer : '';
	$loader = isset($loader) ? $loader : '';
	$loginModal = isset($loginModal) ? $loginModal : '';
?>

<div class="iwp-admin-container" style="opacity: 0; visibility: hidden;">
	<?php echo($loginModal); ?>
	<?php echo($header); ?>
	<div class="iwp-admin-body-container">
		<?php echo($body); ?>
	</div>
	<?php echo($footer); ?>
	<?php echo($loader); ?>
</div>
