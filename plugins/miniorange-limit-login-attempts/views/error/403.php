<?php

global $mollaUtility;
$mo_lla_config = new Mo_lla_MoWpnsHandler();
$userIp 		= $mollaUtility->get_client_ip();
$url			= $mollaUtility->get_current_url();
$current_browser= $mollaUtility->getCurrentBrowser();
$block_time     = date("Y-m-d h:i:sa");
wp_register_style( 'mo_lla_error_page_style', plugin_dir_url(dirname(dirname(__FILE__))) . 'includes/css/error-403.css');
wp_enqueue_style('mo_lla_error_page_style');
?>
<link rel="stylesheet">
<body class="main_container">
    <header class="app-header clearfix">
       <center> <div class="logo"></center>
        </div>
    </header>
    <section class="app-content access-denied clearfix">
        <center><div class="center_box width-max-940"><h1 style="font-size: xxx-large; margin: 0; ">Access Denied<span class="exc_mark">!</span></h1><h2 style="margin: 0; ">miniOrange Website Security</h2>
            <p class="medium-text code-snippet">If you are the site owner (or you manage this site), please whitelist your IP or if you think this block is an error please <a href="" class="color-green underline">contact your administrator</a> and make sure to include the block details (displayed in the box below). </p>
            <h2>Block Details</h2>
            <table class="property-table line-height-16">
            <tbody><tr>
            <td>Your IP:</td>
            <td><span><?php echo esc_html($userIp); ?></span></td>
            </tr>
            <tr><td>URL:</td>
            <td><span><?php echo esc_html($url); ?></span></td>
            </tr>
            <tr>
            <td>Your Browser: </td>
            <td><span><?php echo esc_html($current_browser); ?></span></td>
            </tr>
            <tr>
            <td>Block reason:</td>
            <td><span><?php echo esc_html($error_message); ?></span></td>
            </tr>
            <tr>
            <td>Time:</td>
            <td><span><?php echo esc_html($block_time); ?></span></td>
            </tr>

            </tbody></table>
        </div></center>
    </section>
</body>
<?php
exit();
?>
