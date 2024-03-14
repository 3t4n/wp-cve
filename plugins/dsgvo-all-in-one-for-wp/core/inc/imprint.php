<?php

$imprint_template = "";
$country = get_option("dsgvoaiocountry", "");
if ($country == "" or $country == "Deutschland" or $country == "deutschland" or $country == "de" or $country == "DE" or $country == "De" or $country == "Germany" or $country == "germany") {
	$imprint_template .= "<p><b>".__("Information according to § 18 para. 2 Medienstaatsvertrag ", "dsgvo-all-in-one-for-wp")."</b></p>";
} else if ($country == "&Ouml;sterreich" or $country == "&ouml;sterreich" or $country == "at" or $country == "AT" or $country == "At" or $country == "Austria" or $country == "austria") {
	$imprint_template .= "<p><b>".__("Duty to inform according to § 5 para. 1 E-Commerce Act and § 25 Media Act", "dsgvo-all-in-one-for-wp")."</b></p>";
} else {
	$imprint_template .= "<p><b>".__("Information according to § 5 TMG", "dsgvo-all-in-one-for-wp")."</b></p>";	
}
$imprint_template .= "<p>";

if (get_option("dsgvoaiocompanyname", "") != "") {
	$imprint_template .= "[dsgvocompany]<br />";
}

$imprint_template .= "[dsgvoperson]";
$imprint_template .= "[dsgvostreet]";
$imprint_template .= "[dsgvozip] [dsgvocityowner]";
$imprint_template .= "[dsgvocountryowner]";

$imprint_template .= "</p>";
$imprint_template .= "<p><b>".__("Represented by", "dsgvo-all-in-one-for-wp").":</b> [dsgvoperson]</p>";
$imprint_template .= "<p>";
$imprint_template .= "<b>".__("Contact", "dsgvo-all-in-one-for-wp").":</b><br/>";

if (get_option('dsgvoaiophone', '') != "") {
	$imprint_template .= __("Phone", "dsgvo-all-in-one-for-wp").": [dsgvophone]<br/>";
}

if (get_option('dsgvoaiofax', '') != "") {
	$imprint_template .= __("Fax", "dsgvo-all-in-one-for-wp").": [dsgvofax]<br/>";
}

if (get_option('dsgvoaiomail', '') != "") {
	$imprint_template .= __("E-Mail", "dsgvo-all-in-one-for-wp").": [dsgvoemail]<br/>";
}

$imprint_template .= "</p>";


if (get_option("dsdvo_legalform_needregister", "no") == "yes") {
	$imprint_template .= "<p>";
	$imprint_template .= "<b>".__("Register Entry", "dsgvo-all-in-one-for-wp").":</b><br/>";
	$imprint_template .= __("Register", "dsgvo-all-in-one-for-wp").": [dsgvoregister]<br/>";
	$imprint_template .= __("Register Location", "dsgvo-all-in-one-for-wp").": [dsgvocity]<br/>";
	$imprint_template .= __("Registration Number", "dsgvo-all-in-one-for-wp").": [dsgvoregisternr]<br/>";
	$imprint_template .= "</p>";
}

if (get_option("dsdvo_legalform_ustid", "") != "") {
	$imprint_template .= "<p><b>".__("Vat-ID", "dsgvo-all-in-one-for-wp").":</b> [dsgvoustid]</p>";
}

if (get_option("dsdvo_legalform_wid", "") != "") {
$imprint_template .= "
<p><b>".__("Economic ID", "dsgvo-all-in-one-for-wp").":</b> [dsgvowid]</p>";
}

if (get_option("dsdvo_legalform_needconsens", "no") == "yes") {
	$imprint_template .= "<p><b>".__("Regulatory Authority", "dsgvo-all-in-one-for-wp").":</b> [dsgvosupervisoryauthority]</p>";
}


$legalforminforule = get_option("dsdvo_legalform_inforule", "0");


if ($legalforminforule != "1" && $legalforminforule != "0") {
	$imprint_template .= "<p>";
	$imprint_template .= __("Job Title", "dsgvo-all-in-one-for-wp").": [dsgvoinforule]<br />";
	$imprint_template .= __("Responsible chamber", "dsgvo-all-in-one-for-wp").": [dsgvochamber]<br />";
	$imprint_template .= __("Granted by the state", "dsgvo-all-in-one-for-wp").": [dsgvocountry]<br />";
	$imprint_template .= "</p>";
}

if (get_option("dsdvo_legalform_journalist", "no") == "yes") {
	$imprint_template .= "<p>";
	$imprint_template .= "<b>".__("Responsible for the content according to § 55 Abs. 2 RStV", "dsgvo-all-in-one-for-wp").":</b><br />";
	$imprint_template .= "[dsgvoperson_journalist]<br/>";
	$imprint_template .= "[dsgvostreet_journalist]<br/>";
	$imprint_template .= "[dsgvozip_journalist] [dsgvocity_journalist]<br/>";
	$imprint_template .= "[dsgvocountry_journalist]<br/>";
	$imprint_template .= "</p>";
}

if (get_option("dsdvo_clause", "no") == "yes") {
	$imprint_template .= "<p>";
	$imprint_template .= "<b>".__("Disclaimer", "dsgvo-all-in-one-for-wp")."</b><br />";
	$imprint_template .= "</p>";	
	$imprint_template .= "<p><b>".__("Responsibility for links", "dsgvo-all-in-one-for-wp").":</b><br/>";
	$imprint_template .= __("Our offer contains links to external websites of third parties, on whose contents we have no influence. Therefore we cannot assume any liability for these external contents. The respective provider or operator of the sites is always responsible for the contents of the linked sites. The linked pages were checked for possible legal violations at the time of linking. Illegal contents were not identified at the time of linking. However, a permanent control of the contents of the linked pages is not reasonable without concrete evidence of a violation of the law. If we become aware of any infringements, we will remove such links immediately.", "dsgvo-all-in-one-for-wp")."</p>";
	$imprint_template .= "<p>";	
	$imprint_template .= "<b>".__("Liability for content", "dsgvo-all-in-one-for-wp").":</b><br/>";
if ($country == "" or $country == "Deutschland" or $country == "deutschland" or $country == "de" or $country == "DE" or $country == "De" or $country == "Germany" or $country == "germany") {
	$imprint_template .= __("The contents of our pages were created with the greatest care. However, we cannot assume any liability for the correctness, completeness and topicality of the contents. As a service provider, we are responsible for our own content on these pages in accordance with § 7 para. 1 TMG (German Telemedia Act) and general laws. According to § 8 to 10 TMG we are not obliged to monitor transmitted or stored information from third parties or to investigate circumstances that indicate illegal activity. Obligations to remove or block the use of information according to general laws remain unaffected. However, liability in this respect is only possible from the time of knowledge of a concrete infringement. If we become aware of any such violations, we will remove the content in question immediately.", "dsgvo-all-in-one-for-wp")."</p>";
} else if ($country == "&Ouml;sterreich" or $country == "&ouml;sterreich" or $country == "at" or $country == "AT" or $country == "At" or $country == "Austria" or $country == "austria") {	
	$imprint_template .= __("The contents of our pages were created with the greatest care. However, we cannot assume any liability for the correctness, completeness and topicality of the contents. As a service provider, we are responsible for our own content on these pages in accordance with § 25 para. 1 Austrian Media Act and general laws. According to § 25 Austrian Media Act we are not obliged to monitor transmitted or stored information from third parties or to investigate circumstances that indicate illegal activity. Obligations to remove or block the use of information according to general laws remain unaffected. However, liability in this respect is only possible from the time of knowledge of a concrete infringement. If we become aware of any such violations, we will remove the content in question immediately.", "dsgvo-all-in-one-for-wp")."</p>";
} else {
	$imprint_template .= __("The contents of our pages were created with the greatest care. However, we cannot assume any liability for the correctness, completeness and topicality of the contents. As a service provider, we are responsible for our own content on these pages in accordance with § 7 para. 1 TMG (German Telemedia Act) and general laws. According to § 8 to 10 TMG we are not obliged to monitor transmitted or stored information from third parties or to investigate circumstances that indicate illegal activity. Obligations to remove or block the use of information according to general laws remain unaffected. However, liability in this respect is only possible from the time of knowledge of a concrete infringement. If we become aware of any such violations, we will remove the content in question immediately.", "dsgvo-all-in-one-for-wp")."</p>";	
}
}


if (get_option("dsdvo_copyright", "no") == "yes") {
	$imprint_template .= "<p>";	
	$imprint_template .= "<b>".__("Copyright", "dsgvo-all-in-one-for-wp")."</b><br />";
	$imprint_template .= __("The contents and works on these pages created by the site operators are subject to German copyright law. The duplication, editing, distribution and any kind of utilization outside the limits of copyright law require the written consent of the respective author or creator. Downloads and copies of these pages are only permitted for private, non-commercial use. Insofar as the content on this site was not created by the operator, the copyrights of third parties are observed. In particular, third-party content is identified as such. Should you nevertheless become aware of a copyright infringement, please inform us accordingly. If we become aware of any infringements, we will remove such contents immediately.", "dsgvo-all-in-one-for-wp")."</p>";

}

if (get_option("dsdvo_owntextsimprint", "no") == "yes") {
	$imprint_template .= "<br />";	
	if (!isset($language)) $language = wf_get_language();	
	$kses_allowed_html = dsdvo_wp_frontend::dsdvo_kses_allowed();
	if ($language == "de") {
		$imprint_template .= wpautop(html_entity_decode(stripcslashes(wp_kses(get_option("dsdvo_customimprinttext"), $kses_allowed_html))));
	}
	
	if ($language == "en") {
		$imprint_template .= wpautop(html_entity_decode(stripcslashes(wp_kses(get_option("dsdvo_customimprinttext_en"), $kses_allowed_html))));
	}	
	
	if ($language == "it") {
		$imprint_template .= wpautop(html_entity_decode(stripcslashes(wp_kses(get_option("dsdvo_customimprinttext_it"), $kses_allowed_html))));
	}		
}