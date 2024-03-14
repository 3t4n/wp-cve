<?php
/**
 * Easy Video Reviews - Frontend Modal
 * Frontend Modal
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>

<!-- recorder modal -->

<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php wp_head(); ?>
	</head>
	<body>
		<?php $this->render_template('frontend/recorder'); ?>
		<?php wp_footer(); ?>
	</body>
</html>

<!--  recorder modal -->
