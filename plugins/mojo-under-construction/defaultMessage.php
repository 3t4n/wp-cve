<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function displayDefaultComingSoonPage() {
	displayComingSoonPage( trim( get_bloginfo( 'title' ) ) . ' is coming soon', get_bloginfo( 'url' ), 'is coming soon' );
}

function displayComingSoonPage($title, $headerText, $bodyText) { ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title><?php echo esc_html($title); ?></title>

		<style type="text/css">
			
			.headerText {
				width: 550px;
				margin-top: 10%;
				margin-right: auto;
				margin-left: auto;
				font-size: 28px;
				font-weight: normal;
				display: block;
				text-align: center;
			}
			
			.bodyText {
				width: 550px;
				margin-top: 15px;
				margin-right: auto;
				margin-left: auto;
				font-size: 14px;
				font-weight: normal;
				display: block;
				text-align: center;
			}
			
			body {
				margin-left: 0px;
				margin-top: 0px;
				margin-right: 0px;
				margin-bottom: 0px;
				background-color: #222222;
				color: #FFF;
				font-family: Arial, Helvetica, sans-serif;
			}
		</style>
	</head>

	<body>
		<span class="headerText"><?php echo esc_html($headerText); ?></span>

		<br>

		<span class="bodyText"><?php echo esc_html($bodyText); ?></span>
		
	</body>
</html>

<?php } // Closing PHP Tag for the function