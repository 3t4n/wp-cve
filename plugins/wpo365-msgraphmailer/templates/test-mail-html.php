<?php

// Prevent public access to this script
defined('ABSPATH') or die();

?>

<html>

<head></head>

<body>
    <div style="
        width:100%;
        -webkit-text-size-adjust:none !important;
        margin:0;
        padding: 70px 0 70px 0;
    ">
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
            <tbody>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="520" id="template_container" style="
        box-shadow:0 0 0 1px #f3f3f3 !important;
        border-radius:3px !important;
        background-color: '#ffffff';
        border: 1px solid '#e9e9e9';
        border-radius:3px !important;
        padding: 20px;
    ">
                            <tbody>
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- Header -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="520" id="template_header" style="
        color: '#00000';
        border-top-left-radius:3px !important;
        border-top-right-radius:3px !important;
        border-bottom: 0;
        font-weight:bold;
        line-height:100%;
        text-align: center;
        vertical-align:middle;
        background-color:'#ffffff'">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h1 style="
        color: #000000;
        margin:0;
        padding: 28px 24px;
        display:block;
        font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
        font-size:24px;
        font-weight: 500;
        line-height: 1.2;
    ">[<?php echo esc_html(wp_specialchars_decode(get_option('blogname'), ENT_QUOTES)) ?>] Test email by WPO365 | LOGIN Graph Mailer</h1>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- End Header -->
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <!-- Body -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="520" id="template_body">
                                            <tbody>
                                                <tr>
                                                    <td valign="top" style="
        border-radius:3px !important;
        font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
    ">
                                                        <!-- Content -->
                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div style="
        color: #000000;
        font-size:14px;
        font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
        line-height:150%;
        text-align:left;
    ">
                                                                            <p>Hi there</p>
                                                                            <p>Thank you for choosing WPO365 | LOGIN and its extension to
                                                                                seamlessly connect your WordPress website with the powerful
                                                                                world of Microsoft Office 365 and Azure AD.</p>
                                                                            <p>This email is a test and is sent to you on your own request
                                                                                upon saving updated settings for the Graph Mailer feature.</p>
                                                                            <p>I hope everthing works as expected. If not, then please
                                                                                don't hesitate and get in touch, for example through the
                                                                                blue help beacon that you find when you go to your WordPress
                                                                                Admin Dashboard > WPO365.</p>
                                                                            <p>Marco van Wieren, Downloads by van Wieren</p>
                                                                            <p>WPO365 - Connecting WordPress and Microsoft Office 365 / Azure AD</p>
                                                                            <p>Zurich, Switzerland</p>
                                                                            <p>l https://www.linkedin.com/company/downloads-by-van-wieren</p>
                                                                            <p>w https://www.wpo365.com</p>
                                                                            <p>e support@wpo365.com</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!-- End Content -->
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- End Body -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>