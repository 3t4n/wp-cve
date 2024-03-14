<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\Form;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportCsvSelectorViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportXmlSelectorViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
/**
 * Class ImportFileFormProcessAction, import file view form process.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\Process\Form
 */
class ImportFileFormProcessAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\Initable
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ImportFileForm
     */
    private $form;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm $form, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao)
    {
        $this->request = $request;
        $this->form = $form;
        $this->plugin_helper = $helper;
        $this->data_provider_factory = $data_provider_factory;
        $this->import_dao = $import_dao;
    }
    public function isActive() : bool
    {
        $settings = $this->request->get_param('post.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm::get_id());
        return $settings->isArray() && !$settings->isEmpty();
    }
    public function init()
    {
        $this->save_form_data();
    }
    private function save_form_data()
    {
        $file_form_id = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm::get_id();
        $uid = $this->request->get_param('post.' . $file_form_id . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::UID)->getAsString();
        $format = $this->request->get_param('post.' . $file_form_id . '.' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::ORIGINAL_FILE_FORMAT)->getAsString();
        $this->form->handle_request($this->request->get_param('post.' . $file_form_id)->get());
        if ($this->form->is_valid() && \current_user_can('manage_options')) {
            $data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportFileDataProvider::class, ['postfix' => $uid]);
            $data_provider->update($this->form);
            $view = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML == $format ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportXmlSelectorViewAction::class : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportCsvSelectorViewAction::class;
            $mode = $this->request->get_param('get.mode')->getAsString();
            if (!empty($mode)) {
                $args = ['uid' => $uid, 'mode' => $mode];
                if ($this->import_dao->is_uid_exists($uid)) {
                    $import = $this->import_dao->find_by_uid($uid);
                    $url = $data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportFileFormFields::FILE_URL);
                    if ($url != $import->get_url()) {
                        $import->set_url($url);
                        $this->import_dao->update($import);
                        $args['changed'] = 'yes';
                    }
                }
            } else {
                $args = ['uid' => $uid];
            }
            $this->redirect($this->plugin_helper->generate_url_by_view($view, $args));
        }
    }
    private function redirect(string $url)
    {
        \wp_redirect($url);
        exit;
    }
}
