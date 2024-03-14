<?php


namespace rnpdfimporter\ajax;


use rnpdfimporter\core\Integration\FileManager;
use rnpdfimporter\pr\Utilities\Activator;

class Settings extends AjaxBase
{

    function GetDefaultNonce()
    {
        return 'settings';
    }

    protected function RegisterHooks()
    {
        $this->RegisterPrivate('activate_license','ActivateLicense');
        $this->RegisterPrivate('deactivate_license','DeactivateLicense');
        $this->RegisterPrivate('delete_metrics','DeleteMetrics');

    }


    public function ActivateLicense(){

        $licenseKey=$this->GetRequired('LicenseKey');
        $expirationDate=$this->GetRequired('ExpirationDate');
        $url=$this->GetRequired('URL');
        (new Activator())->SaveLicense($this->Loader,$licenseKey,$expirationDate,$url);
        $this->SendSuccessMessage('');
    }

    public function DeactivateLicense(){
        Activator::DeleteLicense($this->Loader);

        $this->SendSuccessMessage('');
    }

    public function DeleteMetrics()
    {
        $fileManager=new FileManager($this->Loader);
        $metricsPath=$fileManager->GetFontMetricsPath();

        $files = glob($metricsPath . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Delete the empty directory
        rmdir($metricsPath);

        $this->SendSuccessMessage('Folder deleted');
    }
}