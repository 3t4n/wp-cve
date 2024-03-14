<?php
namespace IfSo\PublicFace\Services\AjaxTriggersService;

require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers-service.class.php';
require_once(IFSO_PLUGIN_BASE_DIR . 'public/helpers/ifso-request/If-So-Http-Get-Request.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'public/services/analytics-service/analytics-service.class.php');

use IfSo\PublicFace\Services\TriggersService;
use IfSo\PublicFace\Helpers\IfSoHttpGetRequest as IfsoRequest;
use IfSo\Services\PluginSettingsService\PluginSettingsService;
use IfSo\Extensions\IFSOExtendedShortcodes\ExtendedShortcodes\ExtendedShortcodes;

class AjaxTriggersService{
    private static $instance;
    private $loader_animation_type;
    private $loader_classnames_old = ['', 'ifso-logo-loader', 'lds-dual-ring','ifso-default-content-loader'];
    private $loader_classnames = ['none'=>'','ifso-logo'=>'ifso-logo-loader','loader1'=>'lds-dual-ring','default-content'=>'ifso-default-content-loader'];
    private $loader_prettynames = ['none'=>'No Loader','ifso-logo'=>'If-So Logo','loader1'=>'Circle','default-content'=>'Default Content'];
    private $ajax_secret_option_name = 'ifso_ajax_secret';
    private $ajax_secret_update_interval = 7;
    private $attrs_for_ajax = ['ga4','the_content'];
    private $request = null;

    private function __construct(){
        $anim_type = PluginSettingsService::get_instance()->ajaxLoaderAnimationType->get();
        if(is_numeric($anim_type))     //compatibility with the old system
            $anim_type = array_search($this->loader_classnames_old[$anim_type],$this->loader_classnames);
        $this->loader_animation_type = $anim_type;
    }

    public static function get_instance(){
        if(NULL === self::$instance)
            self::$instance = new AjaxTriggersService();

        return self::$instance;
    }

    public function get_request(){
        return $this->request;
    }

    public function set_request($request){
        $this->request = $request;
    }

    public function is_inside_ajax_triggers_request(){
        return (!empty($this->request) && $this->request->getRequestType()==='AJAX');
    }

    public function get_current_request(){
        if($this->is_inside_ajax_triggers_request())
            return $this->get_request();
        else
            return \IfSo\PublicFace\Helpers\IfSoHttpGetRequest\IfSoHttpGetRequest::create();
    }

    public function create_ifso_ajax_tag($atts){
        $attString = '';
        $loader_classes = $this->loader_classnames;
        $loader_type = $this->loader_animation_type;
        foreach($atts as $attName=>$attVal){
            if($attName!== 'id' && $attName!=='ajax'){
                $attString .= " {$attName}='{$attVal}'";
            }
            if($attName === 'loader'){
                if(in_array($attVal,array_keys($loader_classes)))
                    $loader_type = $attVal;
            }
        }
        $content = ($loader_type==="default-content") ? TriggersService\TriggerContextLoader::load_default_content($atts['id']) : '';
        $html = "<IfSoTrigger tid='{$atts['id']}' class='{$loader_classes[$loader_type]}' {$attString} style='display:inline-block;'>{$content}</IfSoTrigger>";
        return $html;
    }

    public function handle_standalone_condition($content,$default,$rule){
        $default_raw = $default;
        $loader_anim_type = (empty($rule['loader'])) ? $this->loader_animation_type : $rule['loader'];
        $content = preg_replace('~[\r\n]+~', '', $content);
        $rule_json = json_encode($rule);
        $hash = $this->hash_standalone_condition($content,$default,$rule_json);
        $rule = htmlspecialchars($rule_json,ENT_QUOTES);
        $content = htmlspecialchars($content,ENT_QUOTES );
        $default = htmlspecialchars($default,ENT_QUOTES );
        $loader_class = $this->loader_classnames[$loader_anim_type];
        $condition_element_content = ($loader_anim_type==="default-content") ? $default_raw : '';

        $html = "<IfSoCondition content='{$content}' default='{$default}' rule='{$rule}' hash='{$hash}' class='{$loader_class}' style='display:inline-block;'>$condition_element_content</IfSoCondition>";
        return $html;
    }

    public function handle_dki($atts){
        $loader_anim_type = (empty($atts['loader'])) ? $this->loader_animation_type : $atts['loader'];
        $loader_class = $this->loader_classnames[$loader_anim_type];
        $condition_element_content = ($loader_anim_type==="default-content" && isset($atts['default'])) ? $atts['default'] : '';
        unset($atts['ajax']);
        $atts_json = esc_attr(json_encode($atts));

        $html = "<IfSoDKI class='{$loader_class}' dkiAtts='{$atts_json}'>$condition_element_content</IfSoDKI>";
        return $html;
    }

    public function handle($atts){
        if(!empty($atts['id'])){
            return $this->create_ifso_ajax_tag($atts);
        }
        return '';
    }

    public function handle_ajax(){
        if(wp_doing_ajax() && !empty($_REQUEST['triggers']) && isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'],'ifso-nonce')){
            $triggers = $_REQUEST['triggers'];
            $page_url = $_REQUEST['page_url'];
            $pageload_referrer = !empty($_REQUEST['pageload_referrer']) ? $_REQUEST['pageload_referrer'] : '';
            $http_request = IfsoRequest\IfSoHttpGetRequest::create($page_url,$pageload_referrer);
            $this->request = $http_request;
            if(isset($_REQUEST['is_dki']) && $_REQUEST['is_dki']==='true'){$this->handle_ajax_dki($triggers,$http_request);return;}
            $triggers_service = TriggersService\TriggersService::get_instance();
            \IfSo\PublicFace\Services\AnalyticsService\AnalyticsService::get_instance()->useAjax=false;
            $is_standalone_condition_handle = (!empty($_REQUEST['is_standalone_condition']) && $_REQUEST['is_standalone_condition']);
            $triggers = ($is_standalone_condition_handle) ? json_decode(stripslashes($triggers)) : $triggers;
            if($triggers && is_array($triggers)){
                if($is_standalone_condition_handle){
                    $res = $this->handle_ajax_standalone_condition($triggers,$triggers_service,$http_request);
                }
                else{
                    $res = new \stdClass();
                    foreach($triggers as $tr){
                        $tr_json = json_encode($tr);
                        if(is_numeric($tr))
                            $res->$tr = $triggers_service->handle(['id'=>$tr],$http_request);
                        elseif(is_array($tr) && is_numeric($tr['id'])){
                            $allowed_attrs = $this->get_atts_for_ajax();
                            $tr = array_filter($tr,function ($key) use ($allowed_attrs){return $key==='id' || in_array($key,$allowed_attrs);},ARRAY_FILTER_USE_KEY);
                            $res->$tr_json = $triggers_service->handle($tr,$http_request);
                        }
                    }
                }

                if(!empty($res)){
                    echo json_encode($res);
                }
            }
        }
        wp_die();
    }

    private function handle_ajax_dki($triggers_str,$request){
        $triggers = json_decode(stripslashes($triggers_str),true);
        $ret = [];
        foreach ($triggers as $attrs_json){
            $attrs = json_decode($attrs_json,true);
            $ret[] = ExtendedShortcodes::get_instance()->render_dki_shortcode($attrs,$request);
        }
        if(!empty($ret)){
            echo json_encode($ret);
        }
        wp_die();
    }

    public function handle_ajax_standalone_condition($triggers,$triggers_service,$http_request){
        $ret = [];
        foreach($triggers as $trigger){
            $content = preg_replace('~[\r\n]+~', '', $trigger->content);
            $default = $trigger->default;
            $rule = json_decode($trigger->rule,true);
            $hash = $trigger->hash;
            $ret[] = (!empty($rule) && $this->verify_condition_integrity($content,$default,$trigger->rule,$hash)) ? $triggers_service->handle_from_data([$rule],[$content],$default,$http_request) : '';
        }
        return $ret;
    }

    private function get_ajax_secret($check_if_old=false){
        $secret = get_option($this->ajax_secret_option_name);
        if($secret!==false){
            //if(!$check_if_old || ($check_if_old && substr(date('w'),-1,1)===substr($secret,-1,1))){     //Invalidate old secret if its not from this week. Maybe use CRON instead?
        return $secret;
            //}
        }
        if($this->update_ajax_secret()) {
            return $this->get_ajax_secret();
        }
    }

    public function update_ajax_secret(){
        $value = wp_generate_password(64,true,true) . date('w');
        return update_option($this->ajax_secret_option_name,$value);
    }

    private function hash_standalone_condition($content,$default,$rule){
        $hash = null;
        $secret = $this->get_ajax_secret(true);
        if($secret!==null){
            $hash = hash('sha256',$content.$default.$rule.$secret);
        }
        return $hash;
    }

    private function verify_condition_integrity($content,$default,$rule,$hash){
        if(!empty($hash)){
            return hash_equals($this->hash_standalone_condition($content,$default,$rule),$hash);
        }
        return false;
    }

    public function get_ajax_loader_list($which='classnames'){
        if($which==='classnames')
            return $this->loader_classnames;
        elseif($which==='prettynames')
            return $this->loader_prettynames;
    }

    public function get_atts_for_ajax(){
        return $this->attrs_for_ajax;
    }
}