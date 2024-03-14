<?php
/*
Plugin Name: Universal Google Analytics (GA3 and GA4)
Plugin URI: https://www.brontobytes.com/blog/universal-google-analytics-free-wordpress-plugin/
Description: Adds your Google Analytics (GA3 or GA4) tracking code and ID to the footer of your theme.
Author: Brontobytes
Author URI: https://www.brontobytes.com/
Version: 1.4
License: GPLv2
Text Domain: universal-google-analytics
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

function universal_google_analytics_menu() {
	add_options_page(__('Universal Google Analytics Settings', 'universal-google-analytics'), __('Universal Google Analytics', 'universal-google-analytics'), 'administrator', 'universal-google-analytics-settings', 'universal_google_analytics_settings_page');
}
add_action('admin_menu', 'universal_google_analytics_menu');


add_filter( 'plugin_action_links', 'universal_google_analytics_settings_plugin_link', 10, 2 );

function universal_google_analytics_settings_plugin_link( $links, $file )
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/universal-google-analytics.php') )
    {
        /*
         * Insert the link at the beginning
         */
        //   $in = '<a href="options-general.php?page=cookie-bar-settings">' . __('Settings','mtt') . '</a>';
        //   array_unshift($links, $in);

        /*
         * Insert at the end
         */
        $links[] = '<a href="options-general.php?page=universal-google-analytics-settings">'.__('Settings','mtt').'</a>';
    }
    return $links;
}


function universal_google_analytics_settings_page() { ?>
    <style type="text/css" >
        .wrap {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
        }

        .postbox .inside h2, .wrap [class$="icon32"] + h2, .wrap h1, .wrap > h2:first-child {
            padding: 0px;
        }

        @media screen and (max-width: 768px) {
            input[type="text"] {
                max-width: 200px !important;
            ; }

            xmp {
                display: block;
                white-space: pre-wrap;
            }
        }

    </style>

<div class="wrap google_analytics">
<h2><?php echo __('Universal Google Analytics Settings', 'universal-google-analytics'); ?></h2>
<p><?php echo __('Our plugin will automatically set up the required tracking snippet and code to the footer of your WordPress installation, as required by Google Analytics.', 'universal-google-analytics'); ?></p>
<p><?php echo __('It currently supports both Universal Analytics (GA3) and Google Analytics 4.', 'universal-google-analytics'); ?></p>
<form method="post" action="options.php">
    <?php settings_fields( 'universal-google-analytics-settings' ); ?>
    <?php do_settings_sections( 'universal-google-analytics-settings' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php echo __('Google Analytics version', 'universal-google-analytics'); ?></th>
            <td>
                <?php $version = "ga3";
                $option_version = get_option('universal_google_analytics_version');
                if (isset($option_version) && $option_version === "ga4") {
                    $version = "ga4";
                }
                ?>
                <input type="radio" onclick="handleClick_version(this);" name="universal_google_analytics_version" <?php if ($version=="ga3") echo "checked";?> value="ga3"><?php echo __('Universal Analytics (GA3)', 'universal-google-analytics'); ?>
                <br/>
                <input type="radio" onclick="handleClick_version(this);" name="universal_google_analytics_version" <?php if ($version=="ga4") echo "checked";?> value="ga4"><?php echo __('Google Analytics 4', 'universal-google-analytics'); ?>

                <br><br>

        </tr>
        <tr class="ga3" valign="top">
        <th scope="row"><?php echo __('UA Tracking ID', 'universal-google-analytics'); ?></th>
        <td><input type="text" name="universal_google_analytics_tracking_id" value="<?php echo esc_attr( get_option('universal_google_analytics_tracking_id') ); ?>" /> <small><?php echo __('E.g.: UA-12345678-1<br />You can find your Tracking ID next to your Property, in the dropdown, on your Google Analytics homepage or in the Admin settings page.', 'universal-google-analytics'); ?></small></td>
        </tr>





        <tr class="ga4" valign="top">
            <th scope="row"><?php echo __('Google tag embed type', 'universal-google-analytics'); ?></th>
            <td>
                <?php $gtag_type = "gtag_tracking_code";
                $option_gtag_type = get_option('universal_google_tag_type');
                if (isset($option_gtag_type) && $option_gtag_type === "gtag_customizable") {
                    $gtag_type = "gtag_customizable";
                }
                ?>
                <input type="radio" onclick="handleClick_type(this);" name="universal_google_tag_type" <?php if ($gtag_type=="gtag_customizable") echo "checked";?> value="gtag_customizable"><?php echo __('Google tag (customizable)', 'universal-google-analytics'); ?>
                <br/>
                <input type="radio" onclick="handleClick_type(this);" name="universal_google_tag_type" <?php if ($gtag_type=="gtag_tracking_code") echo "checked";?> value="gtag_tracking_code"><?php echo __('Tracking ID', 'universal-google-analytics'); ?>

                <br><br>

        </tr>

        <tr class="ga4 tracking_code_gtag" valign="top">
            <th scope="row"><?php echo __('Tracking ID', 'universal-google-analytics'); ?></th>
            <td><input type="text" name="universal_google_analytics_gtag_id" value="<?php echo esc_attr( get_option('universal_google_analytics_gtag_id') ); ?>" /> <small><?php echo __('E.g.: G-Z5P7HR1MTE<br />You can find your tracking ID (measurement ID/property number) next to your Property on your Google Analytics management page.', 'universal-google-analytics'); ?></small></td>
        </tr>

        <tr class="ga4 tag_type" valign="top">
            <th scope="row"> <?php echo __('Google tag', 'universal-google-analytics'); ?> </th>
            <td><textarea style="min-width: 350px" name="universal_google_analytics_tracking_id_ga4" rows="10"  ><?php echo esc_attr( get_option('universal_google_analytics_tracking_id_ga4') ); ?></textarea> <small> <br/>
                    <?php echo __('You can find the Google Analytics 4 tracking code (Google tag) in your <strong>Google Analytics Dashboard > select your account/property > Admin > Data Streams > Web > View Tag Instructions > Install Manually</strong>. Copy the whole Google tag code here. You can also modify it, if necessary.', 'universal-google-analytics'); ?>
                    <br/><br/>
                    <?php echo __('Here is a sample Google tag:', 'universal-google-analytics'); ?><br/><br/>
                    <code>

                        <?php echo esc_html('<!-- Google tag (gtag.js) -->'); ?>
                        <br/>
                        <?php
                        echo esc_html('<script async src="https://www.googletagmanager.com/gtag/js?id=G-GTAGID"></script>'); ?>
                        <br/>
                        <?php
                        echo  esc_html('<script>'); ?>
                        <br/>
                        <?php
                        echo esc_html('    window.dataLayer = window.dataLayer || [];'); ?>
                        <br/>
                        <?php
                        echo esc_html('    function gtag(){dataLayer.push(arguments);}'); ?>
                        <br/>
                        <?php
                        echo esc_html('    gtag(\'js\', new Date());'); ?>
                        <br/>
                        <?php

                        echo esc_html('    gtag(\'config\', \'G-GTAGID\');'); ?>
                        <br/>
                        <?php
                        echo esc_html('</script>'); ?> </code> </small></td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
<p><?php echo __('We are very happy to be able to provide this and other <a target="_blank" href="https://www.brontobytes.com/blog/c/wordpress-plugins/">free WordPress plugins</a>.', 'universal-google-analytics'); ?></p>
<p><?php echo __('Plugin developed by <a target="_blank" href="https://www.brontobytes.com/">Brontobytes</a>.', 'universal-google-analytics'); ?></p>
    <a target="_blank" href="https://www.brontobytes.com/"><img width="100" style="vertical-align:middle" src="<?php echo plugins_url( 'images/brontobytes.svg', __FILE__ ) ?>" alt="Web hosting provider"></a>
</div>
    <style type="text/css" >
        .wrap.google_analytics form th {display: inline-block; width: 200px;}
        .wrap.google_analytics form td {display: inline-block; }

        .wrap.google_analytics form tr td small{ max-width: 600px; display: block; }

    </style>
    <script type="text/javascript" >
        function show_ga4_rows (){
            var ga4_rows = document.getElementsByClassName('ga4');

            for (var i = 0; i < ga4_rows.length; i ++) {
                ga4_rows[i].style.display = 'block';
            }
        }

        function show_ga4_customizable_form (){
            var ga4_rows_tag_type = document.getElementsByClassName('tag_type');

            for (var i = 0; i < ga4_rows_tag_type.length; i ++) {
                ga4_rows_tag_type[i].style.display = 'block';
            }
        }

        function hide_ga4_rows() {
            var ga4_rows = document.getElementsByClassName('ga4');

            for (var i = 0; i < ga4_rows.length; i ++) {
                ga4_rows[i].style.display = 'none';
            }
        }
        function hide_ga4_customizable_form (){
            var ga4_rows_tag_type = document.getElementsByClassName('tag_type');

            for (var i = 0; i < ga4_rows_tag_type.length; i ++) {
                ga4_rows_tag_type[i].style.display = 'none';
            }
        }

        function hide_ga3_rows (){
            var ga3_rows_tag_type = document.getElementsByClassName('ga3');

            for (var i = 0; i < ga3_rows_tag_type.length; i ++) {
                ga3_rows_tag_type[i].style.display = 'none';
            }
        }

        function show_ga3_rows (){
            var ga3_rows_tag_type = document.getElementsByClassName('ga3');

            for (var i = 0; i < ga3_rows_tag_type.length; i ++) {
                ga3_rows_tag_type[i].style.display = 'block';
            }
        }

        function show_customizable_gtag () {
            var tag_type = document.getElementsByClassName('tag_type');

            for (var i = 0; i < tag_type.length; i ++) {
                tag_type[i].style.display = 'block';
            }
        }

        function hide_customizable_gtag () {
            var tag_type = document.getElementsByClassName('tag_type');

            for (var i = 0; i < tag_type.length; i ++) {
                tag_type[i].style.display = 'none';
            }
        }


        function show_tracking_code_gtag () {
            var tracking_code_gtag = document.getElementsByClassName('tracking_code_gtag');

            for (var i = 0; i < tracking_code_gtag.length; i ++) {
                tracking_code_gtag[i].style.display = 'block';
            }
        }

        function hide_tracking_code_gtag () {
            var tracking_code_gtag = document.getElementsByClassName('tracking_code_gtag');

            for (var i = 0; i < tracking_code_gtag.length; i ++) {
                tracking_code_gtag[i].style.display = 'none';
            }
        }

        function handleClick_version(version) {

            if( version.value == "ga3")
            {
                show_ga3_rows();
                hide_ga4_rows();
            }
            else if( version.value == "ga4")
            {
                show_ga4_rows();
                hide_ga3_rows();
                <?php if( $gtag_type == "gtag_customizable") {
                ?>
                hide_tracking_code_gtag();
                show_customizable_gtag();
                <?php
                }
                else if ( $gtag_type == "gtag_tracking_code") {
                    ?>
                show_tracking_code_gtag();
                hide_customizable_gtag();
                <?php
                }
                ?>


            }
        }


        function handleClick_type(type_) {

            if( type_.value == "gtag_customizable")
            {
                show_customizable_gtag();
                hide_tracking_code_gtag();
            }
            else if( type_.value == "gtag_tracking_code")
            {
                show_tracking_code_gtag();
                hide_customizable_gtag();
            }
        }

        <?php if ($version == "ga3") {
            ?>
            show_ga3_rows();
            hide_ga4_rows();
        <?php
        }
        else if ($version == "ga4") {
            ?>
            show_ga4_rows();
            hide_ga3_rows();

            <?php if( $gtag_type == "gtag_customizable") {
            ?>
            hide_tracking_code_gtag();
            show_customizable_gtag();
            <?php
            }
            else if ( $gtag_type == "gtag_tracking_code") {
            ?>
            show_tracking_code_gtag();
            hide_customizable_gtag();
            <?php
            }
            ?>
    <?php
}
?>

    </script>
<?php }

function universal_google_analytics_deactivation() {
    delete_option( 'universal_google_analytics_tracking_id' );
    delete_option( 'universal_google_analytics_version' );
    delete_option( 'universal_google_analytics_tracking_id_ga4' );
    delete_option( 'universal_google_analytics_gtag_id' );
    delete_option( 'universal_google_tag_type' );
}
register_deactivation_hook( __FILE__, 'universal_google_analytics_deactivation' );

function universal_google_analytics_settings() {
    register_setting( 'universal-google-analytics-settings', 'universal_google_analytics_tracking_id' );
    register_setting( 'universal-google-analytics-settings', 'universal_google_analytics_version' );
    register_setting( 'universal-google-analytics-settings', 'universal_google_analytics_tracking_id_ga4' );
    register_setting( 'universal-google-analytics-settings', 'universal_google_analytics_gtag_id' );
    register_setting( 'universal-google-analytics-settings', 'universal_google_tag_type' );
}
add_action( 'admin_init', 'universal_google_analytics_settings' );


if(!function_exists('universal_google_analytics')) {
    function universal_google_analytics() {
        $version = "ga3";
        $option_version = get_option('universal_google_analytics_version');
        if (isset($option_version) && $option_version === "ga4") {
            $version = "ga4";
        }
        if ( $version == "ga3") {

        ?>
            <!-- Universal Google Analytics -->
            <script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

              ga('create', '<?php echo esc_attr( get_option('universal_google_analytics_tracking_id') ); ?>', 'auto');
              ga('send', 'pageview');
            </script>
            <!-- End Universal Google Analytics -->
            <?php
        }
    }
    add_action( 'wp_footer', 'universal_google_analytics', 10 );
}

if(!function_exists('gtag_tracking_code_head')) {
    function gtag_tracking_code_head() {
        $version = "ga3";
        $option_version = get_option('universal_google_analytics_version');
        if (isset($option_version) && $option_version === "ga4") {
            $version = "ga4";
        }

        $gtag_type = "gtag_tracking_code";
        $option_gtag_type = get_option('universal_google_tag_type');
        if (isset($option_gtag_type) && $option_gtag_type === "gtag_customizable") {
            $gtag_type = "gtag_customizable";
        }


        if ( $version == "ga4") {
            if ( $gtag_type == "gtag_tracking_code") {
            ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( get_option('universal_google_analytics_gtag_id') ); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo esc_attr( get_option('universal_google_analytics_gtag_id') ); ?>');
            </script>

            <?php
            }
            else if ( $gtag_type == "gtag_customizable") {
                $allowed = array(
                    'script' => array( // on allow a tags
                        'async' => array(),  // and those anchors can only have href attribute
                        'src' => array() // and those anchors can only have href attribute
                    )
                );
                echo  wp_kses( get_option('universal_google_analytics_tracking_id_ga4'), $allowed ) ;
            }
        }
    }
    add_action( 'wp_footer', 'gtag_tracking_code_head', 100 );
}