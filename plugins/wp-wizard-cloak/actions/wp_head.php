<?php

function pmlc_wp_head() {
	// output keyword style
	$options = PMLC_Plugin::getInstance()->getOption();
	echo "<style>a.pmlc-linked-keyword{";
	foreach(array('color', 'font-size', 'font-weight', 'font-style', 'text-decoration') as $prop) {
		$opt = 'keywords_' . str_replace('-', '_', $prop);
		'' != $options[$opt] and print($prop . ':' . $options[$opt] . ';');
	}
	echo "}</style>\n";
}