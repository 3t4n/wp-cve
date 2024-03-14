<?php

// For older (pre-2.7.2) verions of google/apiclient
if (
    file_exists(__DIR__ . '/../apiclient/src/Google/Client.php')
    && !class_exists('Mailster_Gmail_Google_Client', false)
) {
    require_once(__DIR__ . '/../apiclient/src/Google/Client.php');
    if (
        defined('Mailster_Gmail_Google_Client::LIBVER')
        && version_compare(Mailster_Gmail_Google_Client::LIBVER, '2.7.2', '<=')
    ) {
        $servicesClassMap = [
            'Mailster\Gmail\Google\\Client' => 'Mailster_Gmail_Google_Client',
            'Mailster\Gmail\Google\\Service' => 'Mailster_Gmail_Google_Service',
            'Mailster\Gmail\Google\\Service\\Resource' => 'Mailster_Gmail_Google_Service_Resource',
            'Mailster\Gmail\Google\\Model' => 'Mailster_Gmail_Google_Model',
            'Mailster\Gmail\Google\\Collection' => 'Mailster_Gmail_Google_Collection',
        ];
        foreach ($servicesClassMap as $alias => $class) {
            class_alias($class, $alias);
        }
    }
}
spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'Google_Service_')) {
        // Autoload the new class, which will also create an alias for the
        // old class by changing underscores to namespaces:
        //     Google_Service_Speech_Resource_Operations
        //      => Mailster\Gmail\Google\Service\Speech\Resource\Operations
        $classExists = class_exists($newClass = str_replace('_', '\\', $class));
        if ($classExists) {
            return true;
        }
    }
}, true, true);
