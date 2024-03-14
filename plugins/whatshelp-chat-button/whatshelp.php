<?php
/**
 * Plugin Name: Chat Button by GetButton.io (ex. WhatsHelp)
 * Description: The Chat button by GetButton takes website visitor directly to the messaging app such as Facebook Messenger or WhatsApp and allows them to initiate a conversation with you. After that, both you and your customer can follow up the conversation anytime and anywhere!
 * Version: 1.9
 * Author: GetButton
 * Author URI: https://getbutton.io
 */

function whatshelp_setup()
{
    //add_submenu_page('options-general.php', __('Whatshelp Chat Button', 'whatshelp'), __('Whatshelp Chat Button', 'whatshelp'), 'manage_options', 'options-whatshelp', 'whatshelp_settings');
    add_menu_page(__('GetButton', 'getbutton'), __('GetButton', 'getbutton'), 'manage_options', basename(__FILE__), 'whatshelp_settings', plugin_dir_url(__FILE__) . 'img/wh-icon.ico');
    register_setting('whatshelp', 'whatshelp-code');
}

// Display settings page
function whatshelp_settings()
{
    $safeLogoURL = htmlspecialchars(get_whatshelp_logo_url());
    $safeSiteURL = htmlspecialchars(get_whatshelp_url());

    echo <<<EOTEXT
     <a href="{$safeSiteURL}" target="_blank">
     <img src="{$safeLogoURL}" style="max-width: 250px;"></a>
EOTEXT;

    if (get_option('whatshelp-code')) {
        echo <<<EOTEXT
	<p style="font-size: 14px;">
		Check your <a href="/" target="_blank">website</a> to see if the Chat Button is present. 
		<br>
		You can always get a new code at <a href="{$safeSiteURL}" target="_blank">getbutton.io</a> and paste it in the form below.
	</p>
EOTEXT;
    } else {
        echo <<<EOTEXT
	<h3>Step 1: Get button code</h3>
	<p style="font-size: 14px;">
		To install Chat Button, please go to  <strong><a href="{$safeSiteURL}" target="_blank">getbutton.io</a></strong> and get the button code.
	</p>
	<h3>Step 2: Paste the code</h3>
	<p style="font-size: 14px;">Copy and paste button code into the form below:</p>
EOTEXT;
    }

    echo '<form action="options.php" method="POST">';
    settings_fields('whatshelp');
    do_settings_sections('whatshelp');
    echo '<textarea cols="80" rows="14" name="whatshelp-code">' . esc_attr(get_option('whatshelp-code')) . '</textarea>';
    submit_button();
    echo '</form>';
}

function get_whatshelp_url()
{
    return 'https://getbutton.io/?utm_campaign=wordpress_plugin&utm_medium=widget&utm_source=wordpress';
}

function get_whatshelp_logo_url()
{
    return plugin_dir_url(__FILE__) . 'img/getbutton_logo.png';
}


function get_clean_code($code)
{
    // 1. remove all comments
    $code_clean = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $code);

    // 2. find positions of all variables
    preg_match_all('/(?:var|let|const)[\s]+[a-zA-Z0-9_]+[\s]*=/', $code_clean, $allVarsPos, PREG_OFFSET_CAPTURE);
    $allVarsPos = $allVarsPos[0];
    if (!count($allVarsPos) || !count($allVarsPos[0])) {
        throw new \Exception();
    }

    // 3. find start position of "options" variable and next variable after options
    $varOptionPos = null;
    $nextVarPos = null;
    for ($i=0; $i<count($allVarsPos); $i++) {
        $pos = $allVarsPos[$i];
        if (strpos($pos[0], 'options') !== false) {
            $varOptionPos = $pos[1];
            if (isset($allVarsPos[$i + 1])) {
                $nextVarPos = $allVarsPos[$i + 1][1];
            }
            break;
        };
    }
    if ($varOptionPos === null) {
        throw new \Exception();
    }

    // 3. find end position of "options" variable
    // 4.1 find all positions of "}"
    preg_match_all('/}/', $code_clean, $posCurls, PREG_OFFSET_CAPTURE, $varOptionPos);
    $posCurls = $posCurls[0];
    if (!count($posCurls)) {
        throw new \Exception();
    }
    // 4.2 find position of last "}" before any next variable
    $positionCurl = null;
    // if there no var after options
    if ($nextVarPos === null) {
        $positionCurl = end($posCurls)[1];
    } else {
        for ($i=0; $i<count($posCurls); $i++) {
            $pos = $posCurls[$i][1];
            if ($pos < $nextVarPos) {
                $positionCurl = $pos;
            }
        }
    }
    if (!$positionCurl) {
        throw new \Exception();
    }

    // 5. Remove footer (after end of "options" var)
    $code_clean = substr($code_clean, 0, $positionCurl + 1);

    // 6. Remove header (before "options" var)
    $code_clean = substr($code_clean, $varOptionPos);

    // Clear XSS from code
    $result = strip_tags($code_clean);
    return $result;
}

function get_prefix()
{
    $prefix = <<<EOTEXT
\n\n<!-- GetButton.io widget -->
<script type="text/javascript">
(function () {\n
EOTEXT;
    return $prefix;
}

function get_suffix()
{
    $suffix = <<<EOTEXT
;
    var proto = 'https:', host = "getbutton.io", url = proto + '//static.' + host;
    var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
    s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
    var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
})();
</script>
<!-- /GetButton.io widget -->\n\n
EOTEXT;

    return $suffix;
}

function add_whatshelp_code()
{
    $dbval = trim(get_option('whatshelp-code'));

    // if it short code, render it new way
    preg_match('/<script.+http.+\?[a-zA-Z0-9=&]*id=([a-zA-Z0-9]+)/', $dbval, $allVarsPos);
    if (count($allVarsPos) && isset($allVarsPos[1])) {
        $id = $allVarsPos[1];
        echo "\n\n<!-- GetButton.io widget -->\n<script defer src=\"https://static.getbutton.io/widget/bundle.js?id=${id}\"></script>\n<!-- /GetButton.io widget -->\n\n";
        return;
    }

    // else if it long old code, render another
    $err_output = 'console.warn("Getbutton: parsing code failed!"); return;';
    echo get_prefix();
    try {
        echo get_clean_code($dbval);
    } catch (\Throwable $err) {
        echo $err_output;
    } catch (\Exception $err) {
        echo $err_output;
    }
    echo get_suffix();
}

// Add settings page and register settings with WordPress
add_action('admin_menu', 'whatshelp_setup');
// Add the code to footer
add_action('wp_footer', 'add_whatshelp_code');
