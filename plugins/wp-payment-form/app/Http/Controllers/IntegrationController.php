<?php

namespace WPPayForm\App\Http\Controllers;

use WPPayForm\App\Models\Meta;
use WPPayForm\App\Modules\AddOnModules\AddOnModule;
use WPPayForm\App\Services\Integrations\GlobalIntegrationManager;

class IntegrationController extends Controller
{
    public function index()
    {
        return (new AddOnModule())->updateAddOnsStatus($this->request->all());
    }

    public function enable()
    {
        return GlobalIntegrationManager::migrate();
    }

    public function getIntegrations($formId)
    {
        return (new GlobalIntegrationManager())->getAllFormIntegrations($formId);
    }

    public function settings($formId)
    {
        return (new GlobalIntegrationManager())->getIntegrationSettings($formId, $this->request->all());
    }

    public function saveSettings($formId)
    {
        return (new GlobalIntegrationManager())->saveIntegrationSettings($formId, $this->request->all());
    }

    public function deleteSettings($formId)
    {
        return (new GlobalIntegrationManager())->deleteIntegrationFeed($formId, $this->request->all());
    }

    public function status($formId)
    {
        return (new GlobalIntegrationManager())->updateNotificationStatus($formId, $this->request->all());
    }

    public function lists($formId)
    {
        return (new GlobalIntegrationManager())->getIntegrationList($formId, $this->request->all());
    }

    public function getGlobalSettings()
    {
        return (new GlobalIntegrationManager())->getGlobalSettingsData($this->request->all());
    }

    public function setGlobalSettings()
    {
        return (new GlobalIntegrationManager())->saveGlobalSettingsData($this->request->all());
    }

    public function authenticateCredentials()
    {
        return (new GlobalIntegrationManager())->authenticateCredentials($this->request->all());
    }

    public function chained()
    {
        return (new GlobalIntegrationManager())->chainedData($this->request->all());
    }
}
