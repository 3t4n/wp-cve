<?php
if (empty($_GET['yrmFindPage'])) {
	require_once(dirname(__FILE__).'/list.php');
}
else if ($_GET['yrmFindPage'] == 'create') {
	YrmConfig::defaultOptions();
	$typeObj = ReadMore::createObjByType('far');
	if (!empty($_GET['farId'])) {
		$typeObj->setSavedId($_GET['farId']);
	}
	$typeObj->getDBData();
	require_once(dirname(__FILE__).'/create.php');
}