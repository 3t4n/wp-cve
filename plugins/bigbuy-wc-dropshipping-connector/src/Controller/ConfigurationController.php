<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\TagManager;
use WcMipConnector\Model\View\ConfigurationView;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\EmailConfiguratorService;
use WcMipConnector\Service\SystemService;
use WcMipConnector\View\Assets\Assets;
use WcMipConnector\View\ConfigurationViewManager;
use WcMipConnector\View\Modal\CronModal;
use WcMipConnector\View\Module\ConfigurationModuleView;
use WcMipConnector\View\Module\MinimumRequirements\Header;
use WcMipConnector\View\Module\MinimumRequirementsView;
use WcMipConnector\View\Module\PublicationContainer;
use WcMipConnector\View\Module\ShippingServiceView;
use WcMipConnector\View\Module\Title;
use WcMipConnector\View\Multichannel\NotLogged;
use WcMipConnector\View\Notice\Notice;
use WcMipConnector\View\Tab\Tab;

class ConfigurationController
{
    /** @var ConfigurationViewManager */
    protected $configurationViewManager;
    /**@var TagManager */
    protected $tagManager;
    /** @var Title */
    protected $titleView;
    /** @var Notice */
    protected $noticeView;
    /** @var NotLogged */
    protected $multichannelView;
    /** @var Tab  */
    protected $tabView;
    /** @var CronModal  */
    protected $cronModal;
    /** @var MinimumRequirementsView  */
    protected $minimumRequirementsView;
    /** @var PublicationContainer  */
    protected $publicationContent;
    /** @var Assets  */
    protected $assets;
    /** @var ConfigurationModuleView  */
    protected $configurationModuleView;
    /** @var DirectoryService  */
    protected $directoryService;
    /** @var ShippingServiceView  */
    protected $shippingService;
    /** @var Header  */
    protected $header;
    /** @var EmailConfiguratorService */
    private $emailConfigurationService;
    /** @var SystemService */
    private $systemService;

    public function __construct()
    {
        $this->directoryService = new DirectoryService();
        $this->configurationViewManager = new ConfigurationViewManager();
        $this->tagManager = new TagManager();
        $this->assets = new Assets();
        $this->systemService = new SystemService();
    }

    public function canHandleAdminRequest(): bool
    {
        if (
            !\array_key_exists('page', $_GET)
            || (\array_key_exists('page', $_GET) && sanitize_text_field($_GET['page']) !== MipWcConnector::MODULE_NAME)
        ) {
            return false;
        }

        return true;
    }

    public function initializeAdminViews(): void
    {
        if (!$this->canHandleAdminRequest()) {
            return;
        }

        $this->titleView = new Title();
        $this->noticeView = new Notice();
        $this->multichannelView = new NotLogged();
        $this->tabView = new Tab();
        $this->cronModal = new CronModal();
        $this->minimumRequirementsView = new MinimumRequirementsView();
        $this->publicationContent = new PublicationContainer();
        $this->configurationModuleView = new ConfigurationModuleView();
        $this->shippingService = new ShippingServiceView();
        $this->header = new Header();
        $this->emailConfigurationService = new EmailConfiguratorService();
    }

    public function loadTranslations():void
    {
        load_plugin_textdomain('WC-Mipconnector', false, $this->directoryService->getTranslationsDir());
    }

    public function loadMenu():void
    {
        add_menu_page(
            'BigBuy WC Dropshipping Connector',
            'BigBuy',
            'administrator',
            'bigbuy-wc-dropshipping-connector',
            [$this, 'renderPluginView'],
            'data:image/svg+xml;base64,'.base64_encode(file_get_contents(__DIR__.'/../../app/assets/image/bb_icon.svg')),
            40
        );

        add_action('admin_enqueue_scripts', [$this->assets, 'getHeaderAssets']);
    }

    /**
     * @throws \Exception
     */
    public function renderPluginView(): void
    {
        $configurationView = $this->configurationViewManager->create();

        $this->saveButtonAction();
        $this->titleView->getTitle();

        if (!ConfigurationOptionManager::getAccessToken() || !ConfigurationOptionManager::getSecretKey()) {
            $this->multichannelView->showNotLoggedView();
            return;
        }

        $this->showWarningMessages($configurationView);

        $pageTab = \array_key_exists('tab', $_GET) ? sanitize_text_field($_GET['tab']) : '';
        ?>
            <div class="wrap">
                <?php
                    $this->tabView->showTabs($pageTab);
                    $this->header->showHeader($configurationView);
                ?>
                <div class="tab-content">
                    <div class="u-pdr">
                        <section class="content-section-mip">
                            <?php
                                $this->renderAdminViews($pageTab, $configurationView);
                            ?>
                        </section>
                    </div>
                </div>
            </div>
        <?php

        $this->assets->getFooterAssets();
    }

    public function saveButtonAction(): void
    {
        if (isset($_POST['save']) || isset($_REQUEST['id'])) {
            if (isset($_POST['tagOption'])) {
                ConfigurationOptionManager::setTagName();
                $tagOption = (bool)$_POST['tagOption'];
                ConfigurationOptionManager::setActiveTag($tagOption);
                
                if (!$_POST['tagOption']) {
                    $this->tagManager->deleteByName(ConfigurationOptionManager::getTagName());
                }
            }

            if (isset($_POST['sendEmail'])) {
                $sendEmailStatus = (bool)$_POST['sendEmail'];
                $updateWooCommerceEmailSettings = $this->emailConfigurationService->updateWooCommerceEmailSettings($sendEmailStatus);

                if (!$updateWooCommerceEmailSettings) {
                    $this->noticeView->showErrorNotice('Error saving update settings email');

                    return;
                }

                ConfigurationOptionManager::setSendEmail($sendEmailStatus);
            }

            if (isset($_POST['productOption'])) {
                $productOption = (bool)$_POST['productOption'];
                ConfigurationOptionManager::setProductOption($productOption);
            }

            if (isset($_POST['bigbuyCarrierOption'])) {
                $bigbuyCarrierOption = (bool)$_POST['bigbuyCarrierOption'];
                ConfigurationOptionManager::setCarrierOption($bigbuyCarrierOption);
            }

            if (isset($_POST['apiKey']) && !empty($_POST['apiKey'])) {
                if (!$this->systemService->isValidApiKey(sanitize_text_field($_POST['apiKey']))) {
                    $this->noticeView->showErrorNotice(esc_html__('The API Key is not correct. Please check your configuration to introduce the correct API key.', 'WC-Mipconnector'));

                    return;
                }

                ConfigurationOptionManager::setApiKey(sanitize_text_field($_POST['apiKey']));
            }

            $this->noticeView->showSuccessVersion();
        }
    }

    /**
     * @param ConfigurationView $configurationView
     */
    private function showWarningMessages(ConfigurationView $configurationView): void
    {
        if (\array_key_exists('WcVersion', $configurationView->warningMessages)) {
            $this->noticeView->showWarningVersion($configurationView->warningMessages['WcVersion']);
        }

        if (\array_key_exists('storage_full', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['storage_full']);
        }

        if (\array_key_exists('storage_almost_full', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['storage_almost_full']);
        }

        if (\array_key_exists('low_performance', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['low_performance']);
        }

        if (\array_key_exists('folder_ownership_mismatch', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['folder_ownership_mismatch']);
        }

        if (\array_key_exists('mod_rewrite_not_active', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['mod_rewrite_not_active']);
        }

        if (\array_key_exists('permalink_not_active', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['permalink_not_active']);
        }

        if (\array_key_exists('disabled_required_functions', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['disabled_required_functions']);
        }

        if (\array_key_exists('php_support', $configurationView->warningMessages)) {
            $this->noticeView->showWarningNotice($configurationView->warningMessages['php_support']);
        }
    }

    /**
     * @param string $pageTab
     * @param ConfigurationView $configurationView
     */
    private function renderAdminViews(string $pageTab, ConfigurationView $configurationView): void
    {
        switch ($pageTab) {
            case Tab::PUBLICATION_PAGE_VIEW:
                $this->publicationContent->showPublicationView($configurationView->defaultIsoCode);
                break;
            case Tab::CONFIGURATION_PAGE_VIEW:
                $this->configurationModuleView->showConfigurationModuleView();
                break;
            case Tab::SHIPPING_SERVICE_PAGE_VIEW:
                $this->shippingService->showView();
                break;
            default:
                $this->cronModal->showCronModal();
                $this->minimumRequirementsView->showMinimumRequirementsView($configurationView);
        }
    }
}