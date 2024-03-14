<?php
function cpln_exclusions_desc() {
    echo '<p>This plugin comes equipped with <strong>two</strong> ways of excluding links:</p><ol><li>You can give a link, button, or navigation item a <em>special class</em> to prevent a pop up warning for that particular item. That class is <code>no-notice</code>.</li><li>You can universally exclude a domain from the pop up notice across your entire site by listing it below. You can use any number of domains, <strong>separated by commas</strong>.</li>';
} // end cpln_exclusions_desc

function cpln_exclusion_list_output($args) {
	$options = get_option('cpln_exclusions');
	
	//check to see if there are exclusions. if yes, pass list of exclusions	
	$exclusions = null;
	if($options){
		$exclusions = $options['cpln_exclusion_list'];
	}
	
	$html = '<textarea name="cpln_exclusions[cpln_exclusion_list]" rows="6" cols="60" placeholder="domain.com, test.com">';
	$html .= $exclusions;
	$html .= '</textarea>';
    echo $html;
} // end cpln_exclusion_list_output