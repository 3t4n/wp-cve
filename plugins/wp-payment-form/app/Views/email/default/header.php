<?php
/**
 * Email Header
 */

if (!defined('ABSPATH')) {
    exit;
}

$email_heading = apply_filters('wppayform/email_template_email_heading', false, $submission, $notification);
$headerImage = apply_filters('wppayform/email_template_header_image', false, $submission, $notification);

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo get_bloginfo('name', 'display'); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="
0">
<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tr>
            <td align="center" valign="top">
                <div id="template_header_image">
                    <?php
                    if ($headerImage) {
                        echo '<p style="margin-top:0;"><img src="' . esc_url($headerImage) . '" alt="' . get_bloginfo('name', 'display') . '" /></p>';
                    }
                    ?>
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
                    <?php if ($email_heading) { ?>
                        <tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header">
                                    <tr>
                                        <td id="header_wrapper"><h1><?php echo wp_kses_post($email_heading); ?></h1></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                <tr>
                                    <td valign="top" id="body_content">
                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top">
                                                    <div id="body_content_inner">
