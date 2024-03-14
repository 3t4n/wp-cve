<?php
	header("Service-Worker-Allowed: /");
	header("Content-Type: application/javascript");
	$config_id = $_GET['elem'];
	if(isset($config_id) && $config_id!="" && (preg_match("/(\d+)/i", $config_id) || preg_match("/(\d+)_(\d+)/i", $config_id))){
?>
importScripts("https://cdn.pushalert.co/sw-<?php echo $config_id?>.js");
<?php
    }
?>