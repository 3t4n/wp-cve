<?php

/**
 * Maintenance mode template that's shown to logged out users.
 *
 * @package   dd-simplest-maintenance-mode
 * @copyright Copyright (c) 2021, Ankush Anand
 * @license   GPL
 */
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo esc_html(get_bloginfo('name')); ?> is Under Maintenance</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="">



	<link rel="stylesheet" href="<?php echo plugins_url('assets/css/maintenance.css', dirname(__FILE__)); ?>">
	<!-- Calculated Styles -->
	<style type="text/css">



	</style>

	<!-- Google Analytics Code Goes Here-->
</head>

<body style="display: flex; padding:3em">

	<div id="article">
		<span>
			<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="60px" height="60px" viewBox="0 0 128 128" xml:space="preserve">
				<rect x="0" y="0" width="100%" height="100%" fill="#f2f2f2" />
				<g>
					<path fill="#333333" fill-opacity="1" d="M116.7 66.28a52.86 52.86 0 0 1-1 8.18l9.8 7.57-1.78 4.67-1.77 4.67-12.36-.82a52.87 52.87 0 0 1-4.7 6.7L110 108.5l-3.75 3.3-3.75 3.32L92 108.67a52.6 52.6 0 0 1-7.45 3.9l-.66 12.3-4.87 1.2-4.86 1.2-6.38-10.66q-1.9.2-3.88.2-2.15 0-4.25-.14l-6.3 10.64-4.84-1.2-4.85-1.2-.7-12.43a52.6 52.6 0 0 1-6.7-3.5l-10.6 6.64-3.75-3.3-3.76-3.3 5.05-11.4a52.88 52.88 0 0 1-4.73-6.73l-12.34.9-1.8-4.66-1.8-4.67 9.7-7.67a52.8 52.8 0 0 1-1-8.05l-11.4-5 .56-4.95.54-4.97 12.26-2.3a52.37 52.37 0 0 1 2.94-7.83L8.4 32l2.8-4.14 2.8-4.14 12 3.68a53.06 53.06 0 0 1 6-5.33L29.57 9.8l4.4-2.37 4.43-2.35 8.95 8.86a52.4 52.4 0 0 1 8-1.98L59 0h10l3.66 11.96a52.4 52.4 0 0 1 7.8 1.9L89.26 5l4.42 2.3 4.43 2.34-2.3 12.27a52.98 52.98 0 0 1 6.2 5.5l11.9-3.7 2.9 4.1 2.84 4.1-7.8 9.8a52.34 52.34 0 0 1 2.86 7.5l12.3 2.17.6 4.96.57 4.95zM41 64a23 23 0 1 0 23-23 23 23 0 0 0-23 23z" />
					<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="27.69 64 64" dur="1400ms" repeatCount="indefinite"></animateTransform>
				</g>
			</svg></span>
		<h1 class="fadeIn">Our site is getting a little tune up and some love.</h1>
		<div>
			<p class="fadeIn">We apologize for the inconvenience, but we're performing some maintenance. You can still contact us
				at
				<?php $admin_email = get_option('admin_email');
				echo '<a href="mailto:' . $admin_email . '">' . $admin_email . '</a>'; ?>. We'll be back up soon!</p>
			<p class="fadeIn">&mdash; Team <?php echo esc_html(get_bloginfo('name')); ?></p>
		</div>
	</div>


</body>

</html>