<?php
namespace WPRemoteMediaExt\RemoteMediaExt;

use WPRemoteMediaExt\RemoteMediaExt\Accounts as RemoteService;

use WPRemoteMediaExt\WPCore;

use WPRemoteMediaExt\WPForms\FieldSet;

class FRemoteMediaExt extends WPCore\WPfeature implements Interfaces\RMLFeature
{
    public static $instance;

    protected $version = '1.6.3';
    protected $accountPostType;
    protected $remoteServices = array();

    protected $serviceSetting;
    protected $fPointerAccounts;
    protected $fPointerMediaManager;

    protected $isMediaLibInit = false;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct('feature-remote-medias', 'feature-remote-medias');
    }

    public function init()
    {
        $this->serviceSetting = new Library\MediaArraySettings('remoteServiceSettings');
        
        $this->accountPostType = new AccountPostType();

        $this->hook($this->accountPostType);

        //Hook Vimeo Support
        $service = new RemoteService\Vimeo\Service();
        $service->setBasePath($this->getBasePath());
        $service->setAccountPostType($this->accountPostType);
        $this->hook($service);
        $this->addRemoteService($service);

        //Hook Youtube Support
        $service = new RemoteService\Youtube\Service();
        $service->setBasePath($this->getBasePath());
        $service->setAccountPostType($this->accountPostType);
        $this->hook($service);
        $this->addRemoteService($service);

        //Hook Dailymotion Support
        $service = new RemoteService\Dailymotion\Service();
        $service->setBasePath($this->getBasePath());
        $service->setAccountPostType($this->accountPostType);
        $this->hook($service);
        $this->addRemoteService($service);

        //Hook Flickr Support
        $service = new RemoteService\Flickr\Service();
        $service->setBasePath($this->getBasePath());
        $service->setAccountPostType($this->accountPostType);
        $this->hook($service);
        $this->addRemoteService($service);

        //Hook Instagram Support
        $service = new RemoteService\Instagram\Service();
        $service->setBasePath($this->getBasePath());
        $service->setAccountPostType($this->accountPostType);
        $this->hook($service);
        $this->addRemoteService($service);

        if (is_admin()) {
            $this->initAdmin();
        }
    }

    public function initAdmin()
    {
        //Hook ajax service for accounts validation on remote library page
        $ajaxQueryValidation = new Ajax\AjaxQueryValidation();
        $this->hook($ajaxQueryValidation);

        //Dismissing notice on any page
        $ajaxDismissNotices = new Ajax\AjaxDismissNotice();
        $this->hook($ajaxDismissNotices);
        
        $this->initPointers();

        $this->initMetaboxes();

        $this->initAjax();
        //Localize scripts
        add_action('admin_enqueue_scripts', array($this, 'adminScripts'), 10);

        $this->initRemoteLibraries();

        // $this->hook(new Library\MediaBanner(new WPCore\View($this->getViewsPath().'admin/media-banner.php')));
        $msgView = new WPCore\View($this->getViewsPath().'admin/media-activation-banner.php');
        $msgView->setData(array('version' => $this->version));
        $this->hook(new Library\MediaBannerDismissable($msgView, 'ocsrmlactivationnotice'.$this->version));

        $msgView = new WPCore\View($this->getViewsPath().'admin/media-activation-gdrive.php');
        $this->hook(new Library\MediaBannerDismissable($msgView, 'ocsrmlactivationgdrive'.$this->version));

        $msgView = new WPCore\View($this->getViewsPath().'admin/media-activation-dropbox.php');
        $this->hook(new Library\MediaBannerDismissable($msgView, 'ocsrmlactivationdropbox'.$this->version));

        $msgView = new WPCore\View($this->getViewsPath().'admin/media-activation-amazons3.php');
        $this->hook(new Library\MediaBannerDismissable($msgView, 'ocsrmlactivationawss3'.$this->version));

        $this->addScript(new WPCore\WPscriptAdmin(array(), 'ocsrml-adminmanager', $this->getJsUrl().'admin.min.js', $this->getJsUrl().'admin.js', array('common'), $this->version));
    }

    public function registerScripts()
    {
        $remotescript = new WPCore\WPscript('media-remote-ext', $this->getJsUrl().'media-remote-ext.min.js', $this->getJsUrl().'media-remote-ext.js', array('media-editor','media-views'), $this->version);
        $remotescript->register();

        $remotestyle = new WPCore\WPstyle('media-remote-admin-css', $this->getCssUrl().'media-remote-admin.min.css', $this->getCssUrl().'media-remote-admin.css', array(), $this->version);
        $remotestyle->register();
    }
    
    public function initRemoteLibraries()
    {
        //Skip if Media Library already initialized
        if ($this->isMediaLibInit === true) {
            return;
        }
        
        $this->isMediaLibInit = true;

         //MediaArraySettings
         $this->serviceSetting->register();

        $remoteAccounts = new Library\MediaSettings('remoteMediaAccounts');
        $remoteAccounts->register();
        
        $mediaTemplate = new Library\MediaTemplate(
            new WPCore\View(
                $this->getViewsPath().'admin/media-remote-attachment.php'
            )
        );
        $mediaTemplate->register();
    }

    //must run after enqueueScripts on theme side
    //earlier on admin side
    public function initAjax($forceInit = false)
    {
        $isAdmin = is_admin();
        $forceInit = $forceInit || !$isAdmin;

        //Hook ajax service for accounts attachments fetching
        $ajaxqueryAttachments   = new Ajax\AjaxQueryAttachments($isAdmin);
        //Hook ajax service for accounts send to editor action
        $ajaxSendRemoteToEditor = new Ajax\AjaxSendRemoteToEditor($isAdmin);
        //Hook ajax service for accounts send to editor action
        $ajaxCreateAttachment   = new Ajax\AjaxCreateAttachment($isAdmin);

        if ($forceInit) {
            $ajaxqueryAttachments->init();
            $ajaxSendRemoteToEditor->init();
            $ajaxCreateAttachment->init();
        }

        $ajaxqueryAttachments->register();
        $ajaxSendRemoteToEditor->register();
        $ajaxCreateAttachment->register();
    }

    //must run in admin_enqeue_scripts or wp_enqueue_scripts
    public function enqueueScripts()
    {
        $this->registerScripts();

        $remotescript = new WPCore\WPscript('media-remote-ext');
        $remotescript->enqueue();

        $remotestyle = new WPCore\WPstyle('media-remote-admin-css');
        $remotestyle->enqueue();
    }

    public function adminScripts()
    {
        global $pagenow;

        //Load remote media libraries modal scripts on these admin pagess
        if (in_array(
            $pagenow,
            array(
                'upload.php',
                'post.php',
                'post-new.php',
            )
        )) {
            $this->enqueueScripts();
        }
    }

    //Alias for backward compatibility
    public function initMediaLibrary()
    {
        return $this->themeScripts();
    }
    //to hook in wp_enqueue_scripts
    public function themeScripts($forceInit = false)
    {
        $this->initRemoteLibraries();
        
        $this->enqueueScripts();
        $this->initAjax($forceInit);
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function initPointers()
    {
        //New Menu Feature Pointer
        $this->fPointerAccounts = new WPCore\admin\WPfeaturePointer(
            'rml_accounts_v100',
            '<h3>'.__('New Menu Added', 'remote-medias-lite').'</h3>'.
            '<p>'.sprintf(__('Add %sremote medias accounts%s here and access any medias directly from your media manager!', 'remote-medias-lite'), '<a href="'.$this->accountPostType->getAdminUrl().'">', '</a>').'</p>',
            '#menu-media',
            array(
                'edge' => 'left',
                'align' => 'center'
            )
        );

        //New Media Manager Extension Applied Feature Pointer
        $this->fPointerMediaManager = new WPCore\admin\WPfeaturePointer(
            'rml_media_v100',
            '<h3>'.__('Media Manager Extended', 'remote-medias-lite').'</h3>'.
            '<p>'.sprintf(__('You can now access medias of %sremote accounts%s directly from the media manager. Check it out!', 'remote-medias-lite'), '<a href="'.$this->accountPostType->getAdminUrl().'">', '</a>').'</p>',
            '.insert-media',
            array(
                'edge' => 'left',
                'align' => 'center'
            ),
            array('post', 'page')
        );
        $fpl = new WPCore\admin\WPfeaturePointerLoader($this->getJsUrl(), 'pointersRML');
        $fpl->addPointer($this->fPointerMediaManager);
        $fpl->addPointer($this->fPointerAccounts);
        $this->hook($fpl);
    }

    public function initMetaboxes()
    {
        $this->addScript(new WPCore\WPscriptAdmin(array('post.php' => array('post_type' => $this->accountPostType->getSlug()), 'post-new.php' => array('post_type' => $this->accountPostType->getSlug())), 'rmedias-query-test', $this->getJsUrl().'media-remote-query-test.min.js', $this->getJsUrl().'media-remote-query-test.js', array('jquery'), $this->version));
        $this->addScript(new WPCore\WPscriptAdmin(array('post.php' => array('post_type' => $this->accountPostType->getSlug()), 'post-new.php' => array('post_type' => $this->accountPostType->getSlug())), 'media-remote-account', $this->getJsUrl().'rml-account.min.js', $this->getJsUrl().'rml-account.js', array('jquery'), $this->version));
        
        //Main metabox for Account Service selection
        $metabox = new Accounts\MetaBoxService(
            new WPCore\View(
                $this->getViewsPath().'admin/metaboxes/account-settings.php',
                array('fRemoteMediaExt' => $this) //view data
            ),
            'rml_service_selection',
            __('Service Selection', 'remote-medias-lite'),
            $this->accountPostType->getSlug(),
            'normal',
            'high'
        );
        $this->hook(new Accounts\MetaBoxServiceLoader($metabox));

        $metabox = new Accounts\MetaBoxService(
            new WPCore\View(
                $this->getViewsPath().'admin/metaboxes/basic-settings.php'
            ),
            'rml_account_settings',
            __('Account Settings', 'remote-medias-lite'),
            $this->accountPostType->getSlug(),
            'normal',
            'default'
        );
        $this->hook(new Accounts\MetaBoxServiceLoader($metabox));

        //Main metabox for Account Status and Action buttons
        $metabox = new Accounts\MetaBoxService(
            new WPCore\View($this->getViewsPath().'admin/metaboxes/status-actions.php'),
            'remote_media_actions',
            __('Status & Actions', 'remote-medias-lite'),
            $this->accountPostType->getSlug(),
            'side', //'normal', 'advanced', or 'side'
            'high' //'high', 'core', 'default' or 'low'
        );
        $this->hook(new Accounts\MetaBoxServiceLoader($metabox));
    }

    public function getBasicFieldSet(Accounts\AbstractRemoteAccount $account)
    {
        $fieldSet = new FieldSet();

        $services = array();
        foreach ($this->getRemoteServices() as $service) {
            $services[$service->getSlug()] = $service->getName();
        }

        $field = array(
            'label' => __("Remote Service", 'remote-medias-lite'),
            'type' => 'Select',
            'id' => 'remote_media_type',
            'name' => 'account_meta[remote_account_type]',
            'class' => 'all',
            'options' => $services,
            'value' => $account->get('type'),
            'desc' => __("Choose the type of service you want to connect.", 'remote-medias-lite'),
        );
        $fieldSet->addField($field);

        return $fieldSet;
    }

    public function uninstall()
    {
        $uid = get_current_user_id();

        if (is_null($this->fPointerAccounts) ||
            is_null($this->fPointerMediaManager)
        ) {
            $this->initPointers();
        }

        $this->fPointerAccounts->clearDismissed($uid);
        $this->fPointerMediaManager->clearDismissed($uid);

        $this->clearActivatedDismissed();
    }

    public function addRemoteService(Accounts\AbstractRemoteService $service)
    {
        $this->remoteServices[$service->getSlug()] = $service;
        $this->serviceSetting->addSetting($service->getSlug(), $service->getSettings());
        Accounts\RemoteServiceFactory::addClass($service->getSlug(), get_class($service));
    }

    public function getServicesSettings()
    {
        return $this->serviceSetting;
    }

    public function getRemoteServices()
    {
        return $this->remoteServices;
    }

    public function getAccountPostType()
    {
        return $this->accountPostType;
    }

    public function clearActivatedDismissed()
    {
        $user = WPCore\WPuser::getCurrent();
        if (is_null($user)) {
            return;
        }

        $dismissedNotices = $user->get('dismissed_rml_notices', '');
        if (empty($dismissedNotices)) {
            return;
        }

        $dismissedNotices = explode(',', $dismissedNotices);
        foreach ($dismissedNotices as $i => $notice) {
            if ($notice == 'ocsrmlactivationnotice'.$this->version) {
                unset($dismissedNotices[$i]);
            }
        }
        $dismissedNotices = implode(',', $dismissedNotices);
        $user->set('dismissed_rml_notices', $dismissedNotices);
        $user->save();
    }
}
