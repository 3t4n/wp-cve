<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$checkout      = WC()->checkout();
$instance      = wfacp_template();
$is_customizer = WFACP_Common::is_customizer();

/**
 * @var $instance WFACP_template_layout1
 */
$page_meta_title = WFACP_Common::get_option( 'wfacp_header_section_page_meta_title' );
$device_type     = $instance->device_type;

?>
<html>
<head>
    <meta charset="UTF-8">
    <base href="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $page_meta_title ? $page_meta_title : get_bloginfo( 'name' ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="wfacp_main_wrapper wfacpef_page <?php echo 'wfacp-' . $device_type; ?>">
