<?php


namespace rnpdfimporter\Pages;


use rnpdfimporter\core\Integration\IntegrationURL;
use rnpdfimporter\core\PageBase;
use rnpdfimporter\pr\Utilities\Activator;

class Settings extends PageBase
{

    public function Render()
    {

        $this->Loader->AddScript('shared','js/dist/SharedCore_bundle.js',array('wp-element'));
        $this->Loader->AddScript('Settings','js/dist/Settings_bundle.js',array('@shared'));

        $this->Loader->AddStyle('Settings','js/dist/Settings_bundle.css',array('@shared'));

        global $wpdb;
        $lisense=Activator::GetLicense($this->Loader);
        $this->Loader->LocalizeScript('rnSettings','Settings','settings',array(
            "LicenseKey"=>$lisense->LicenseKey,
            "Prefix"=>$this->Loader->Prefix,
            'ajaxurl'=>IntegrationURL::AjaxURL(),
            "ItemId"=>$this->Loader->GetProductItemId(),
            'BaseUrl'=>get_home_url(),
            'LicenseURL'=>$lisense->URL,
            'RootURL'=>$this->Loader->GetRootURL()
        ));

        echo '<div id="App"></div>';

    }
}