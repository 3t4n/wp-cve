<?php

/**
 * Dojo for WooCommerce Template
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/templates
 * @author     Dojo
 * @link       http://dojo.tech/
 */

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html lang="en">

<head>
	<title><?php echo esc_html($title); ?></title>
</head>

<body>
	<h1><?php echo esc_html($title); ?></h1>
	<p><?php echo esc_html($message); ?></p>
</body>

</html>