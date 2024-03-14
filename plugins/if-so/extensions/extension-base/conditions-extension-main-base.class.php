<?php
namespace IfSo\Addons\Base;

require_once (__DIR__ . '/extension-main-base.class.php');
if(version_compare(IFSO_WP_VERSION,'1.5.1','>='))       //This file and functionality doesn't exist in previous versions
    require_once(IFSO_PLUGIN_BASE_DIR. 'public/models/data-rules/ifso-data-rules-ui-model.class.php');


abstract class ConditionsExtensionMain extends ExtensionMain{

    public function new_rule_data_extension($group_item){
        $newModel = $this->data_rules_model_extension();
        $ret = [];
        foreach($newModel as $dataPoint => $dataVal){
            if(is_array($dataVal)){
                foreach($dataVal as $subDataVal){
                    $ret[$subDataVal] = $group_item[$subDataVal];
                }
            }
        }
        return $ret;
    }

    public function get_new_trigger_type_fields(){
        $rules = $this->data_rules_model_extension();
        $ret = [];
        foreach($rules as $rule){
            if(is_array($rule)){
                foreach($rule as $datafield){
                    $ret[] = $datafield;
                }
            }
        }

        return $ret;
    }

    abstract public function export_triggers();
    abstract public function data_rules_model_extension();
    abstract public function data_rules_ui_model_extension();

    abstract public function print_selector_ui($rule);
    abstract public function print_data_inputs_ui($rule,$current_version_index);
}