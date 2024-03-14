<?PHP
/***********************************
*
* series of checks.
*
************************************/
if (!defined('ABSPATH')) exit;
function astound_check_allow() {
	astound_require('includes/astound-class-loader.php');
 	// check the white list
	$allow=array(
		'astound_chkwlist'
	);
   foreach($allow as $module) {
		$ok=$options[$module];
		if ($ok=='Y') {
			$res=astound_load_module($module);
			if ($res!==false) {
				// have a hit - it is time to redirect back to the current screen
				// check for very long length
				if (strlen($res)>256) {
					$res=substr($res,0,252).'...';
				}
				return $res;
			}
		}
	}
	
	return false;
}
	

function astound_comment_check() {
	$options=astound_get_options();
	$check=array(
		'astound_chkcache',	
		'astound_chkredherring',
		'astound_chkaccept',
		'astound_chkagent',
		'astound_chkbbcode',
		'astound_chkdisp',
		'astound_chkdomains',
		'astound_chkexploits',
		'astound_chkinvalidip',
		'astound_chklong',
		'astound_chkperiods',
		'astound_chkreferer',
		'astound_chkshort',
		'astound_chkspamwords',
		'astound_chksubdomains',
		'astound_chkbadtld',		
		'astound_chktld',		
		'astound_chktor',
		'astound_chkvpn',
		'astound_chkphish',
		'astound_chkmyip',
		'astound_chktoxic',
		'astound_chkisphosts',
		'astound_chkbadneighborhoods',
		'astound_chksession',
		'astound_chkdnsbl'
		//'astound_chksfs'
	);
	astound_require('includes/astound-class-loader.php');
	$full="";
    foreach($check as $module) {
		$ok=$options[$module];
		if ($ok=='Y') {
			$res=astound_load_module($module);
			if ($res!==false) {
				// have a hit - it is time to redirect back to the current screen
				if ($options['astound_displayall']!='Y') {
					return $res;
				}
				$full.="~~~~   $res $module rejection";
			} 
		}
	}
	if (empty($full)) {
		return false;
	}
	return $full;
}

function astound_register_check() {
	return astound_comment_check(); // for now they are the same
}	


?>