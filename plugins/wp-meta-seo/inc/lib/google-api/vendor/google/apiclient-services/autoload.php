<?php

// For older (pre-2.7.2) verions of google/apiclient
if (
    file_exists(__DIR__ . '/../apiclient/src/Google/Client.php')
    && !class_exists('WPMSGoogle_Client', false)
) {
    require_once(__DIR__ . '/../apiclient/src/Google/Client.php');
    if (
        defined('WPMSGoogle_Client::LIBVER')
        && version_compare(Google_Client::LIBVER, '2.7.2', '<=')
    ) {
        $servicesClassMap = [
            'WPMSGoogle\\Client' => 'WPMSGoogle_Client',
            'WPMSGoogle\\Service' => 'WPMSGoogle_Service',
            'WPMSGoogle\\Service\\Resource' => 'WPMSGoogle_Service_Resource',
            'WPMSGoogle\\Model' => 'WPMSGoogle_Model',
            'WPMSGoogle\\Collection' => 'WPMSGoogle_Collection',
        ];
        foreach ($servicesClassMap as $alias => $class) {
            class_alias($class, $alias);
        }
    }
}
spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'WPMSGoogle_Service_')) {
        // Autoload the new class, which will also create an alias for the
        // old class by changing underscores to namespaces:
        //     Google_Service_Speech_Resource_Operations
        //      => WPMSGoogle\Service\Speech\Resource\Operations
        $classExists = class_exists($newClass = str_replace('_', '\\', $class));
        if ($classExists) {
            return true;
        }
    }
}, true, true);
