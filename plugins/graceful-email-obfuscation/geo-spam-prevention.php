<?php
/*
Plugin Name: Graceful Email Obfuscation
Description: An email obfuscator to prevent spam, using Roel Van Gils' method, GEO. In brief: there is always a clickable, copyable link at the end for the user; transparently if JavaScript is enabled, and links are (so far) impossible to harvest.
Version: 0.2.2
Author: Nicholas Wilson, Jason Hendriks

Copyright: Nicholas Wilson, 2015
Licence: GPL v.2

*/

add_shortcode('email','geo_emailshortcode');
add_action('init','geo_hijackemailload');
add_action( 'wp_enqueue_scripts', 'geo_spam_prevention_enqueue_scripts' );

/**
 * Enqueues the Javascript for the public pages
 */
function geo_spam_prevention_enqueue_scripts() {
	wp_enqueue_script('geo-spam-prevention',plugin_dir_url(__FILE__).'geo-spam-prevention.js', array('jquery'), 0.1);
}

function geo_hijackemailload() {
    if (isset($_GET['geo-address'])) {
        add_action('wp_head', 'geo_buffer_start');
        add_action('wp_footer', 'geo_buffer_end');
    }
}

function geo_buffer_start() { ob_start('geo_content_replace'); }

function geo_buffer_end() { ob_end_flush(); }

function geo_content_replace($content) {
    /*
     * Stage 1: Generate the text to return
     */
    $fail = false;
    if (isset($_GET['geo-result'])) {
        //check against target:
        $target = isset($_GET['geo-target']) ?
            (int)$_GET['geo-target'] - 5 : 0;
        if ((int)$_GET['geo-result'] != $target)
            $fail = true;
        else {
            $email = rawurldecode(str_rot13($_GET['geo-address']));
            $email = str_replace(array('A','N'),array('.','@'),$email);
            $insert = '<p>Sorry for making you jump through those hoops! The email you were looking for is <a href="mailto:'.$email.'">'.$email.'</a>.</p>';
        }
    } else {

        //Now here we generate the form to ask the question
        $insert = '';
        if ($fail) $insert = '<p style="color:red;">Sorry! Your answer seemed to be wrong. If there is a mistake in the site, you are probably getting frustrated now. If you have already tried again, do contact me so I can fix the problem.</p>';
        $one = rand(1,10);
        $two = rand(1,10);
        $insert .= "<form action=\"\" method=\"get\">
        <fieldset>
            <legend>Please enter the sum of $one and $two.</legend>
            <input type=\"text\" size=\"2\" maxlength=\"2\" name=\"geo-result\" id=\"geo-result\" />
            <input type=\"hidden\" name=\"geo-target\" value=\"".($one + $two + 5)."\" />
            <input type=\"hidden\" name=\"geo-address\" value=\"{$_GET['geo-address']}\" />
            <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Enter\" />
        </fieldset>
        </form>
        <h4>Why am I being asked to fill this in?</h4>
        <p>If email addresses are left ‘out in the open’, spammers find them quickly. You have probably seen various spam avoiding facilities on other sites. Because you have JavaScript disabled in your browser, we are running this simple check. Thank you for your patience!</p>";
    }
    $insert = "<div>\n$insert\n</div>";


    /*
     * Stage 2: Work out where to put the text.
     *     Scan for </head>, grab everything after it; <body> is closed automatically
     */
    $split = explode('</head>',$content);
    $content = $split[count($split) - 1];

    $return = '';
    if (count($split) > 1) $return .= $split[0];
    $return .= '</head>';

    $doc = new DOMDocument();
    //Very grunky, but only way to get PHP DOM to read in UTF-8
    $html_meta = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8"></head>';
    $content = "$html_meta$content</html>";
    if (@$doc->loadHTML($content)) {
        $xpath = new DOMXPath($doc);
        $el = $xpath->query('//div[@id=\'content\'] | //div[@role=\'main\']');
        if ($el->length == 0) $el = $xpath->query('//div[@id=\'main\']');

        if ($el->length != 0) {
            $el = $el->item(0);
            //We are all set to go: use DOM methods to put our text in place

            //Remove children
            while($el->hasChildNodes()) {
                $el->removeChild($el->childNodes->item(0));
            }

            $geo_rep = new DOMDocument();
            $geo_rep->loadHTML("$html_meta<body>$insert</body></html>");
            //Have to get rid of <html> and <body> levels inserted
            $imported = $doc->importNode($geo_rep->documentElement->childNodes->item(1)->childNodes->item(0), TRUE);
            $el->appendChild($imported);

            $insert = $doc->saveXML($doc->documentElement, LIBXML_NOEMPTYTAG);
            $insert = explode('</body>', $insert);
            $insert = $insert[0];
            $insert = explode('</head>', $insert);
            $insert = $insert[1];
        } else {
            $return .= '<body>';
        }
    } else {
        $return .= '<body>';
    }

    $return .= $insert;


    return "<!--GEO-->\n$return\n<!--GEO-->\n";
}

function geo_emailshortcode($atts, $content) {
    //We want an href attribute, and non-empty content
    if (!isset($atts['href'])) {
        //Test roughly for email in text
        if (strpos($content,'@') > 1) {
            $atts['href'] = $content;
            $content = 'email';
        } else {
            return "[email]{$content}[/email]";
        }
    }
    //Now code up the email:
    $email = str_replace(array('.','@'),array('A','N'),strtolower($atts['href']));
    $email = rawurlencode(str_rot13($email));
    return '<a href="'.get_bloginfo('url')."/?geo-address=$email\" class=\"geo-address".
        (isset($atts['class'])?' '.$atts['class']:'').
        '"'.(isset($atts['style'])? ' style="'.$atts['style'].'"':'').'>'.$content.'</a>';
}

?>
