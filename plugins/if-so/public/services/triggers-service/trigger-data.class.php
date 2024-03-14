<?php

namespace IfSo\PublicFace\Services\TriggersService;

class TriggerData {
	protected $trigger_id;
	protected $rule;
	protected $version_index;
	protected $data_rules;
	protected $content;
	protected $general_data;
    protected $http_request;
    protected $rendering_recurrence_version;
	
	private function __construct($trigger_id,
								 $rule, 
								 $version_index, 
								 &$data_rules,
								 $content,
                                 $http_request,
                                $rendering_recurrence_version) {
		$this->trigger_id = $trigger_id;
		$this->rule = $rule;
		$this->version_index = $version_index;
		$this->data_rules = &$data_rules;
		$this->content = $content;
		$this->general_data = array();
        $this->http_request = $http_request;
        $this->rendering_recurrence_version = $rendering_recurrence_version;
	}

	public static function create($trigger_id,
								  $rule, 
								  $version_index,
								  &$data_rules,
								  $content,
                                  $http_request,
                                  $rendering_recurrence_version=null) {
		return new TriggerData($trigger_id,
							   $rule, 
							   $version_index, 
							   $data_rules,
							    $content,
                                $http_request,
                                $rendering_recurrence_version);
	}

    public static function createFromContext($context,$version='DEFAULT'){
        $data_rules = $context->get_data_rules();
        $content = $version!=="DEFAULT" && isset($context->get_data_versions()[$version]) ? $context->get_data_versions()[$version] : $context->get_default_content();
        $rule = $version!=='DEFAULT' && isset($data_rules[$version]) ? $data_rules[$version] : [];
        return self::create($context->get_trigger_id(),$rule,$version,$data_rules,$content,$context->get_HTTP_request(),$context->get_rendering_recurrence_version());
    }

	public function get_trigger_id() {
		return $this->trigger_id;
	}

	public function get_rule() {
		return $this->rule;
	}

	public function get_version_index() {
		return $this->version_index;
	}

	public function &get_data_rules() {
		return $this->data_rules;
	}

	public function get_content() {
		return $this->content;
	}

	public function get_general_data($key) {
		if ( !array_key_exists($key, $this->general_data) )
			return false;

		return $this->general_data[$key];
	}

	public function get_http_request(){
	    return $this->http_request;
    }

	public function set_general_data($key, $value) {
		$this->general_data[$key] = $value;
	}

    public function get_rendering_recurrence_version(){
        return $this->rendering_recurrence_version;
    }
}