<?php
/*
Plugin Name: Wufoo Shortcode Plugin
Description: Enables shortcode to embed Wufoo forms. Usage: <code>[wufoo username="chriscoyier" formhash="x7w3w3" autoresize="true" height="458" header="show" ssl="true"]</code>. This code is available to copy and paste directly from the Wufoo Code Manager.
Version: 1.55
License: GPL
Author: Wufoo
Author URI: http://wufoo.com
Text Domain: wufoo-shortcode
*/

function createWufooEmbedJS($atts, $content = null) {
	extract($atts);

	$username = ctype_alnum($username) ? $username : false;
	$formhash = ctype_alnum($formhash) ? $formhash : false;
	$height = isset($height) && is_numeric($height) ? intval($height) : 500;
	$header = isset($header) && ctype_alnum($header) ? $header : 'show';
	$ssl = !isset($ssl) || $ssl;
	$entsource = isset($entsource) && ctype_alnum($entsource) ? $entsource : 'wordpress';
	$defaultv = isset($defaultv) ? htmlentities($defaultv, ENT_QUOTES) : '';
	$autoresize = isset($autoresize) ? filter_var($autoresize, FILTER_VALIDATE_BOOLEAN) : true;

	if (!$username || !$formhash) {

		return "
		<div style='border: 20px solid red; border-radius: 40px; padding: 40px; margin: 50px 0 70px;'>
			<h3>Uh oh!</h3>
			<p style='margin: 0;'>Something is wrong with your Wufoo shortcode. If you copy and paste it from the <a href='https://wufoo.com/docs/code-manager/'>Wufoo Code Manager</a>, you should be golden.</p>
		</div>";

	} else {

		$JSEmbed =  "<div id='wufoo-$formhash'>\n";
		$JSEmbed .= "Fill out my <a href='https://$username.wufoo.com/forms/$formhash'>online form</a>.\n";
		$JSEmbed .=  "</div>\n";

		$JSEmbed .= "<script type='text/javascript'>var $formhash;(function(d, t) {\n";
		$JSEmbed .= "var s = d.createElement(t), options = {\n";
		$JSEmbed .= "'userName'      : '$username',    \n";
		$JSEmbed .= "'formHash'      : '$formhash',    \n";
		$JSEmbed .= "'autoResize'    :  " . var_export($autoresize, true) . ",   \n";
		$JSEmbed .= "'height'        : '$height',      \n";
		$JSEmbed .= "'async'         :  true,          \n";
		$JSEmbed .= "'header'        : '$header',      \n";
		$JSEmbed .= "'host'          : 'wufoo.com',    \n";
		$JSEmbed .= "'entSource'     : '$entsource',   \n";
		$JSEmbed .= "'defaultValues' : '$defaultv'     \n";

		// Only output SSL value if passes as param
		// Gratis and Ad Hoc plans don't show that param (don't offer SSL)
		if ($ssl) {
	  		$JSEmbed .= ",'ssl'          :  $ssl           ";
		}
		$JSEmbed .= "};\n";

		$JSEmbed .= "s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'secure.wufoo.com/scripts/embed/form.js';\n";
		$JSEmbed .= "s.onload = s.onreadystatechange = function() {\n";
		$JSEmbed .= "var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;\n";
		$JSEmbed .= "try { $formhash = new WufooForm();$formhash.initialize(options);$formhash.display(); } catch (e) {}}\n";
		$JSEmbed .= "var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);\n";
		$JSEmbed .= "})(document, 'script');</script>";

		/**
		* iframe embed, loaded inside <noscript> tags
		*/
		$iframe_embed = '<iframe ';
		$iframe_embed .= 'height="'. (int) $height .'" ';
		$iframe_embed .= 'allowTransparency="true" sandbox="allow-top-navigation allow-scripts allow-popups allow-forms allow-same-origin allow-popups-to-escape-sandbox" frameborder="0" scrolling="no" style="width:100%;border:none;"';
		$iframe_embed .= 'src="https://'. $username .'.wufoo.com/embed/'. $formhash . '?';
		if (isset($defaultv) && $defaultv != ''){
			$iframe_embed .= "$defaultv&entsource=wordpress\">";
		}
		else{
			$iframe_embed .= "entsource=wordpress\">";
		}
		$iframe_embed .= '<a href="https://'. $username .'.wufoo.com/forms/'. $formhash .'?';
		if (isset($defaultv) && $defaultv != ''){
			$iframe_embed .= "$defaultv&entsource=wordpress\" ";
		}
		else{
			$iframe_embed .= "entsource=wordpress\" ";
		}
		$iframe_embed .= 'rel="nofollow">Fill out my Wufoo form!</a></iframe>';

		/**
		* Return embed in JS and iframe
		*/
		return "$JSEmbed <noscript> $iframe_embed </noscript>";

	}
}

add_shortcode('wufoo', 'createWufooEmbedJS');

?>
