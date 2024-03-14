<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

/**
 * class control all things related to plugin settings
 * @author grojanteam
 *
 */
class GJMAA_Controller_Profiles extends GJMAA_Controller
{

    protected $profileId;
    
    protected $content;

    protected $parent = 'gjmaa_dashboard';

    protected $buttons = [
        'Add' => '&action=add'
    ];

    public function getName()
    {
        $page = $this->getParam('page');
        $action = $this->getParam('action');

        if ($page == $this->getSlug()) {
            switch ($action) {
                default:
                    return 'Profiles';
                case 'edit':
                    return 'Edit profile';
                case 'add':
                    return 'Add profile';
            }
        }

        return 'Profiles';
    }

    public function getMenuName()
    {
        return __('Profiles', GJMAA_TEXT_DOMAIN);
    }

    /**
     * display table for settings
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function renderView()
    {
        $html = '<div class="wrap">' . $this->getTitle() . $this->getContent() . '</div>';

        echo $html;
    }

    public function index()
    {
        $table = GJMAA::getTable('profiles');
        $this->setContent($table->show());
    }

    public function add()
    {
        $this->edit(0);
    }

    public function edit($id = null)
    {
        $this->buttons = [];

        if (is_null($id)) {
            $id = $this->getParam('profile_id');
        }

        $model = GJMAA::getModel('profiles');
        $model->load($id);

        $profileData = $model->getData();
        $profileData['profile_category_hidden'] = $model->getData('profile_category');

        $form = GJMAA::getForm('profiles');
        $form->prepareForm();
        $form->setValues($profileData);
        $form->generate();
        $this->setContent($form->toHtml());
    }

    public function save()
    {
        $params = $this->getParams();
        if (empty($params)) {
            $this->redirect($this->getIndexUrl());
            return;
        }

        try {
            $params['profile_sellingmode_format'] = $this->parseMultiSelectData($params, 'profile_sellingmode_format');
            $params['profile_sync_woocommerce_fields'] = $this->parseMultiSelectData($params, 'profile_sync_woocommerce_fields');
            $params['profile_publication_status'] = $this->parseMultiSelectData($params, 'profile_publication_status');

            /** @var GJMAA_Model_Profiles $model */
            $model = GJMAA::getModel('profiles');
            $model->setData($params);
            $model->save();

            $this->addSessionSuccess(__('Profile saved successfully.', GJMAA_TEXT_DOMAIN));
        } catch (Exception $e) {
            $this->addSessionError($e->getMessage());
        }

        $this->redirect($this->getIndexUrl());
    }
    
    public function initModel(){
        $this->profileId = $this->getParam('profile_id');
        if (! $this->profileId) {
            return false;
        }
        
        return GJMAA::getModel('profiles')->load($this->profileId);
    }

    public function getSlug()
    {
        return 'gjmaa_profiles';
    }

    public function delete()
    {
        $profile = $this->initModel();
        if(!$profile){
            $this->redirect($this->getIndexUrl());
            return;
        }
        
        try {   
            $profile->delete();
            $this->clearByProfileId($this->profileId);

            $this->addSessionSuccess(__('Profile removed successfully.', GJMAA_TEXT_DOMAIN));
        } catch (Exception $e) {
            $this->addSessionError($e->getMessage());
        }

        $this->redirect($this->getIndexUrl());
    }
    
    public function clear(){
        $profile = $this->initModel();
        if(!$profile){
            $this->redirect($this->getIndexUrl());
            return;
        }
        
        try {
            $this->clearByProfileId($this->profileId);
            $profile->setData('profile_errors',0);
            $profile->setData('profile_error_message',NULL);
            $profile->setData('profile_imported_auctions', 0);
            $profile->setData('profile_import_step', 1);
            $profile->setData('profile_all_auctions', 0);
            $profile->save();
            $this->addSessionSuccess(__('Profile cleared successfully.', GJMAA_TEXT_DOMAIN));
        } catch (Exception $e){
            $this->addSessionError($e->getMessage());
        }
        
        $this->redirect($this->getIndexUrl());
    }
        
    public function clearByProfileId($profileId){
        $auctions = GJMAA::getModel('auctions');
        $auctions->deleteByProfileId($profileId);
    }

    private function parseMultiSelectData(array $params, $field): ?string
    {
        $value = $params[$field] ?? [];
        if(empty($value)) {
            $value = null;
        }

        if(is_array($value)) {
            $value = implode(',', $value);
        }

        return $value;
    }

    public function addScreenOptions(): bool
    {
        return true;
    }

    public function addOptions()
    {
        global $myListTable;

        $option = 'per_page';
        $args = [
            'label' => __('Profiles', GJMAA_TEXT_DOMAIN),
            'default' => 20,
            'option' => 'profiles_per_page'
        ];

        add_screen_option($option, $args);

        $myListTable = GJMAA::getTable('profiles');
    }
}