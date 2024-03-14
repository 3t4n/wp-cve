<?php

defined( 'ABSPATH' ) || exit;

class Kinsta_Cache_Compatible extends RapidLoad_ThirdParty{

    public $kinsta_cache;

    function __construct(){

        $this->plugin = 'kinsta-mu-plugins.php';
        $this->catgeory = 'cache';
        $this->name = 'kinsta-cache';

        parent::__construct();

        add_action('kinsta_cache_init', function ($kinsta){
            $this->kinsta_cache = $kinsta->kinsta_cache_purge;
        },10,1);
    }

    public function is_mu_plugin(){
        return class_exists('\Kinsta\Cache_Purge');
    }

    public function init_hooks()
    {
        add_action( 'uucss/cached', [$this, 'handle'], 10, 2 );
        add_action( 'uucss/cache_cleared', [$this, 'handle'], 10, 2 );
    }

    public function handle($args)
    {
        if(class_exists('\Kinsta\Cache_Purge')){

            $url = null;

            if ( isset( $args['url'] ) ) {
                $url = $this->transform_url( $args['url'] );
            }

            if($url && isset($this->kinsta_cache)){

                $this->kinsta_cache->purge_complete_caches();
            }

        }
    }
}