<?php

namespace IfSo\PublicFace\Services\TriggersService;

class TriggerContext {
	private $trigger_id;
	private $data_rules;
	private $data_versions;
	private $default_content;
    private $HttpRequest;
    private $extra_opts;
    private $rendering_recurrence_version = null;
	
	public function __construct($trigger_id, $data_rules, $data_versions, $default_content, $HttpRequest, $extra_opts=[]) {
		$this->trigger_id = $trigger_id;
		$this->data_rules = $data_rules;
		$this->data_versions = $data_versions;
		$this->default_content = $default_content;
        $this->HttpRequest = $HttpRequest;
        $this->extra_opts = $extra_opts;
	}
	
	public function get_trigger_id() {
		return $this->trigger_id;
	}
	
	public function get_data_rules() {
		return $this->data_rules;
	}
	
	public function get_data_versions() {
		return $this->data_versions;
	}
	
	public function get_default_content() {
		return $this->default_content;
	}

	public function get_HTTP_request(){
	    return $this->HttpRequest;
    }

    public function get_extra_opts(){
        return $this->extra_opts;
    }

	public function remove_context_version($id){
        if (isset($this->data_rules[$id])) unset($this->data_rules[$id]);
        if (isset($this->data_versions[$id])) unset($this->data_versions[$id]);
    }

    public function clear_context($remaining){
        foreach($this->data_versions as $key => $version){
            if(!in_array($key,$remaining)){
                $this->remove_context_version($key);
            }
        }
    }

    public function set_new_default($content){
        $this->default_content = $content;
    }

    public function get_rendering_recurrence_version(){
        return $this->rendering_recurrence_version;
    }

    public function set_rendering_recurrence_version($val){
        $this->rendering_recurrence_version = $val;
    }
}