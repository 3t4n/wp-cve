<?php
require_once __DIR__ .'/../libraries/XmlExportUser.php';

function pmue_pmxe_init_addons() {
    XmlExportengine::$user_export = new XmlExportUser();
}