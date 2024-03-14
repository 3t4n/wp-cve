<?php
namespace IfSo\Addons\Base;

require_once (__DIR__ . '/extension-initializer-base.class.php');

class ConditionsExtensionInitializer extends ExtensionInitializer{
    protected $extension_instance;

    public function __construct($ext,$update_settings){
        parent::__construct($ext, $update_settings);
        $this->extension_instance = $ext;

        $this->add_actions();
        $this->add_filters();
    }

    private function add_actions(){
        add_action('ifso_custom_conditions_ui_selector',[$this,'print_selector_ui']);
        add_action('ifso_custom_conditions_ui_data_inputs',[$this,'print_data_inputs_ui'],10,2);
        add_action('ifso_extra_extended_shortcodes',[$this,'add_shortcodes']);
    }

    private function add_filters(){
        add_filter('ifso_data_rules_model_filter',[$this,'filter_data_rules_model']);
        add_filter('ifso_data_rules_ui_model_filter',[$this,'filter_data_rules_ui_model']);
        add_filter('ifso_triggers_list_filter',[$this,'extend_triggers_list']);
        add_filter('ifso_custom_conditions_new_rule_data_extension',[$this,'filter_new_rule_data'],10,2);
        add_filter('ifso_custom_conditions_expand_data_reset_by_selector',[$this,'expand_ui_data_attributes']);
    }

    public function filter_data_rules_model($conditions){
        return array_merge($conditions,$this->extension_instance->data_rules_model_extension());
    }

    public function filter_data_rules_ui_model($dr_ui){
        foreach($this->extension_instance->data_rules_ui_model_extension() as $rulename=>$fields){
            $dr_ui->$rulename = $fields;
        }
        return $dr_ui;
    }

    public function extend_triggers_list($triggers){
        return array_merge($triggers,$this->extension_instance->export_triggers());
    }

    public function filter_new_rule_data($data,$group_item){
        return array_merge($data,$this->extension_instance->new_rule_data_extension($group_item));
    }

    public function expand_ui_data_attributes($data){
        return array_merge($data,$this->extension_instance->get_new_trigger_type_fields());
    }

    public function add_shortcodes(){
        if(method_exists($this->extension_instance,'add_extra_shortcodes'))
            $this->extension_instance->add_extra_shortcodes();
    }

    public function print_selector_ui($rule){
        $this->extension_instance->print_selector_ui($rule);
    }

     public function print_data_inputs_ui($rule,$current_version_index){
         $this->extension_instance->print_data_inputs_ui($rule,$current_version_index);
     }

}