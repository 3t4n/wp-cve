<?php

/**
 * Description of Account
 *
 * @author Ali2Woo Team
 */
namespace AliNext_Lite;;

class Account {
    private static $_instance = null;
    
    public $account_type = '';
    public $custom_account = false;
    
    public $account_data = array('aliexpress'=>array('appkey'=>'', 'secretkey'=>'', 'trackingid'=>''), 
                                    'admitad'=>array('cashback_url'=>'', 'account_name' => ''),
                                    'epn'=>array('cashback_url'=>''));
    
    
    static public function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct() {
        $this->account_type = get_setting('account_type');
        $this->custom_account = get_setting('use_custom_account');
        $this->account_data = get_setting('account_data');
    }
    
    public function set_account_type($account_type) {
        $this->account_type = $account_type;
        set_setting('account_type', $this->account_type);
    }

    public function use_custom_account($use_custom_account = false) {
        $this->custom_account = $use_custom_account;
        set_setting('use_custom_account', $this->custom_account);
    }
    
    public function get_aliexpress_account() {
        return !empty($this->account_data['aliexpress'])?$this->account_data['aliexpress']:array('appkey'=>'', 'secretkey'=>'', 'trackingid'=>'');
    }
    
    public function get_admitad_account() {
        return !empty($this->account_data['admitad'])?$this->account_data['admitad']:array('cashback_url'=>'', 'account_name' => '');
    }

    public function get_epn_account() {
        return !empty($this->account_data['epn'])?$this->account_data['epn']:array('cashback_url'=>'');
    }
    

    public function save_aliexpress_account($appkey, $secretkey, $trackingid) {
        $this->account_data['aliexpress']['appkey']=$appkey;
        $this->account_data['aliexpress']['secretkey']=$secretkey;
        $this->account_data['aliexpress']['trackingid']=$trackingid;
        set_setting('account_data', $this->account_data);
    }
    
    public function save_admitad_account($cashback_url, $account_name) {
        $this->account_data['admitad']['cashback_url']=$cashback_url;
        $this->account_data['admitad']['account_name']=$account_name;
        
        set_setting('account_data', $this->account_data);
    }

    public function save_epn_account($cashback_url) {
        $this->account_data['epn']['cashback_url']=$cashback_url;
        
        set_setting('account_data', $this->account_data);
    }

    public function get_purchase_code(){
        if (a2wl_check_defined('A2WL_ITEM_PURCHASE_CODE')) {
            return A2WL_ITEM_PURCHASE_CODE;
        }else{
            return get_setting('item_purchase_code');
        }
    }
    
    public function build_params(){
        $item_purchase_code = $this->get_purchase_code();

        $result="token=".urlencode($item_purchase_code)."&version=".A2WL()->version;
        
        if(!empty( $this->account_data['admitad']['cashback_url'])){
            $result.="&cashback_url=". urlencode($this->account_data['admitad']['cashback_url']) . 
                     "&account_name=". urlencode($this->account_data['admitad']['account_name']);
        }
        
        return $result;
    }

    public function is_activated(){
        $item_purchase_code = a2wl_check_defined('A2WL_ITEM_PURCHASE_CODE')?A2WL_ITEM_PURCHASE_CODE:get_setting('item_purchase_code');
        return !empty($item_purchase_code);
    }
}
