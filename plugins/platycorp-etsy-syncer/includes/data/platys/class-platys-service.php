<?php

namespace platy\etsy;

class PlatysService {
    /**
     *
     * @var PlatysService
     */
    private static $instance;

    const TRANSIENT = "platys_etsy_transient";
    const CONSOMUER_KEY = 'ck_94d07f83ef5858f1837fbaf37aa753b1920adc90';
    const CONSUMER_SECRET = 'cs_f0587b030aed572089a6f8cb07ac301f6cb6e94c';
    const REMOTE_OPTION = "platy_etsy_platys_remote";
    const REMOTE_OPTION_KEY = "platy_etsy_platys_remote_key";
    const REMOTE_OPTION_SECRET = "platy_etsy_platys_remote_secret";
	const PRO_OPTION_KEY = "platy_syncer_etsy_pro_code";

    private $api;
    private $key;
    private $secret;
    private function __construct() {
        $remote = get_option( self::REMOTE_OPTION, "https://platycorp.com" );
        $this->api = "$remote/wp-json/lmfwc/v2/licenses";
        $this->key = get_option( self::REMOTE_OPTION_KEY, self::CONSOMUER_KEY );
        $this->secret = get_option( self::REMOTE_OPTION_SECRET, self::CONSUMER_SECRET );
    }

    public static function get_instance() {
        if(PlatysService::$instance == null) {
            PlatysService::$instance = new PlatysService();
        }
        return PlatysService::$instance;
    }

    private function flatten_platys($platys) {
        $flat = [];
        foreach($platys as $k => $v) {
            $flat[] = "$k=$v";
        }
        return \implode("&", $flat);
    }

    public function activate($lic, $platy) {
        $key = $this->key;
        $secret = $this->secret;
        $api = $this->api;
        $platy = $this->flatten_platys($platy);
        $ret = wp_remote_get( "$api/activate/$lic/?consumer_key=$key&consumer_secret=$secret&$platy",[

        ] );

        if(empty($ret) || empty($ret['response'])){
            $failure = true;
        }

        if(empty($ret['response']['code'])){
            $failure == true;
        }

        $success = false;
        if(empty($failure)){
            $success = $ret['response']['code'] == 200;
            if($success){
                update_option(self::PRO_OPTION_KEY , $lic);
                delete_transient( self::TRANSIENT );
            }
        }

        return $success;
    }

    private function get_from_transient($invalidate = true) {
        if(!$invalidate) {
            $platys_data = get_transient( self::TRANSIENT );
            if($platys_data !== false) {
                return $platys_data;
            }
        }

        $platys = [];
		$key = $this->key;
		$secret = $this->secret;
		$lic = get_option(self::PRO_OPTION_KEY);
		if(empty($lic)) {
            $this->fail();
        }
        $api = $this->api;

		$platys_response = wp_remote_get( "$api/validate/$lic?consumer_key=$key&consumer_secret=$secret&license_key=$lic" );
		$platys_body = wp_remote_retrieve_body( $platys_response );
		$platys_data = json_decode( $platys_body, true );

		if($platys_data && isset($platys_data['data'])){

			if(empty($platys_data['data']['platy'])){
                $this->fail();
			}
		}

        set_transient( self::TRANSIENT, $platys_data['data'], 60 );
        return $platys_data['data'];
    }

    public function fail() {
        delete_transient( self::TRANSIENT );
        throw new PlatysException();
    }

    public function get_platys($use_cache = false) {
        try {
            return $this->get_from_transient(!$use_cache)['platy'];
        }catch(PlatysException $e) {

        }
        return [];
    }

    public function get_level($use_cache = false) {
        try {
            return $this->get_from_transient(!$use_cache)['level'];
        }catch(PlatysException $e) {

        }
        return "";
    }

    public function is_platus($level) {
        return $level == "platus"  || $this->is_platy($level);
    }

    public function is_platy($level) {
        return $level == "platy";
    }

    private function is_subscription($data) {
        return isset($data['is_subscription']);
    }

    private function subscription_active($data) {
        return !empty($data['subscription_active']);
    }

    private function show_platy_notices($dates) {
        
    }
}