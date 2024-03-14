<?php 

namespace Tussendoor\OpenRDW;

class Config
{
    public $config = array(
        'plugin' => array(
            'path' => __DIR__.'/../',    
            'view'       => __DIR__.'/../'.'views/',
            'pro_folder' => 'plugin-premium-openrdw-pro/plugin-premium-openrdw-pro.php'
        ),
        'open' => array(
            'api' => 'https://opendata.rdw.nl/resource/m9d7-ebf2.json',
            'sidecallexpress' => '/https:\/\/opendata.rdw.nl\/resource\/(\w+)/'
        ),
    );

    public function __construct($base_name, $folder, $plugin_data) {
        $this->config['plugin']['name'] = $plugin_data['Name'];
        $this->config['plugin']['version'] = $plugin_data['Version'];
        $this->config['plugin']['basename'] = $base_name;
        $this->config['plugin']['folder'] = $folder;
        $this->config['plugin']['asset_url'] = plugin_dir_url('').$folder.'/assets/';
    }

    public function get_config(){
        return new \Adbar\Dot ($this->config, true);
    }
}