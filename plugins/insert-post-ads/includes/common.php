<?php 
/* Begin Version Upgrade */
add_action('init', 'insert_ads_upgrade_version', 0);
function insert_ads_upgrade_version() {
	$databaseVersion = get_option('insert_ads_version');
	if($databaseVersion != WP_INSADS_VERSION) {
		do_action('insert_ads_upgrade_database');
		update_option('insert_ads_version', WP_INSADS_VERSION);
	}
}
/* End Version Upgrade */

/* Begin Misc Functions */
function insert_ads_add_ordinal_number_suffix($num) {
	if (!in_array(($num % 100),array(11,12,13))){
		switch ($num % 10) {
			case 1:  return $num.'st';
			case 2:  return $num.'nd';
			case 3:  return $num.'rd';
		}
	}
	return $num.'th';
}

function insert_ads_get_domain_name_from_url($url){
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
        return $regs['domain'];
    }
    return false;
}
/* End Misc Functions */
?>