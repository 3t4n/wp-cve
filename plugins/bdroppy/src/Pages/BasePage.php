<?php


namespace BDroppy\Pages;



use BDroppy\Init\Core;
use BDroppy\Pages\EndPoints\AdminEndPoints;

abstract class BasePage
{
    protected $core;
    protected $remote;
    protected $config;
    protected $system;
    protected $wc;
    public $contentPath = 'Template/Dashboard/index.php';
    protected $headerData ;
    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->remote = $core->getRemote();
        $this->wc = $core->getWc();
        $this->config = $core->getConfig();
        $this->system = $core->getSystem();

        $this->handle();
        $this->loadEndPoints();
        $this->core->getLoader()
            ->addAction( 'admin_menu', $this, 'setMenu' )
            ->addAction( 'admin_enqueue_scripts', $this, 'LoadScripts' )
            ->addAction( 'admin_enqueue_scripts', $this, 'LoadStyles' );

//        $this->loadHeaderData();

    }

    private function loadHeaderData()
    {
        // get user and checking token
        $response = $this->remote->main->getMe();

        if($response['response']['code'] == 200) {
            $txtStatus = '<span style="color: #36a537;">' . $response['response']['code'] .' - '. $response['response']['message'] .'</span>';
        }else if($response['response']['code'] == 401){
           // $this->config->api->set('api-token',null);
        }
    }


    public function loadEndPoints() {}

    public function handle(){}

    public function setMenu()
    {
        add_menu_page(
            'BDroppy',
            'BDroppy',
            'manage_options',
            'bdroppy-setting',
            [$this,'loadTheme'],
            plugins_url('/../../assets/image/icon.png', __FILE__),2
        );
    }

    abstract public function getScript() : array ;

    public function LoadScripts($hook)
    {
        $options = [
            'api_nonce'   => wp_create_nonce( 'wp_rest' ),
            'api_url'	  => rest_url( 'bdroppy' . '/v1/' ),
            'plugin_url'	  => admin_url(). 'admin.php?page=bdroppy-setting'
        ];
        $this->core->getLoader()->addScript('header');
        $this->core->getLoader()->addScriptObject('header',$options);
        foreach ($this->getScript() as $script)
        {
            $this->core->getLoader()->addScript($script['name']);
            $options = array_merge($options,isset($script['options'])?$script['options']:[]);
            $this->core->getLoader()->addScriptObject($script['name'],$options);
        }
    }

    abstract public function getStyle() : array;

    public function LoadStyles($hook)
    {

        foreach ($this->getStyle() as $style) {
            if ($hook == 'toplevel_page_bdroppy-setting') {
                $this->core->getLoader()->addStyle($style['name']);
            }
        }

    }

    abstract public function loadTheme();





}