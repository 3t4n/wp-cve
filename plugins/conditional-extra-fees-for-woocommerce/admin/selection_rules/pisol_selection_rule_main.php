<?php

class Pi_cefw_selection_rule_main{
    function __construct($title , $saved_conditions, $data){
        $this->title = $title;
        $this->saved_conditions = $saved_conditions;
        $this->slug = PI_CEFW_SELECTION_RULE_SLUG;
        $this->data = $data;
        $this->dynamicForm();
        
    }

    /*
    function selectionRules(){
        $values = array(''=>array('name'=>'Select Condition','group'=>''));
        $this->rules = apply_filters("pi_".$this->slug."_condition", $values);
        return $this->rules;
    }
    */
    function selectionRules(){
        $values = array(''=>array('name'=>'Select Condition','group'=>''));
        $this->rules = apply_filters("pi_".$this->slug."_condition", $values);
        foreach($this->rules as $rule){
            $group[$rule['group']][] = $rule;
        }
        return $group;
    }

    function dynamicForm(){
        include 'pisol_rule_form.php';
    }

    function group($key, $rules, $saved_values = ""){
        if($key == ""){
            $html = '<option>'.$rules[0]['name'].'</option>';
            return $html;
        }
        $group_names = array(
            'location_related'=>"Location Related",
            'product_related'=>'Product Related',
            'cart_related'=>'Cart Related',
            'user_related'=>'User Related',
            'delivery_method' => 'Shipping method',
            'order_date_time_plugin' => 'Deliver date time plugin dependent rules',
            'purchase_history' => 'Purchase History',
            'product_attributes'=>'Product attributes',
        );
        $group_name = isset($group_names[$key]) ? $group_names[$key] : $key;

        $html = '<optgroup label="'.$group_name.'">';
        foreach ($rules as $rule){
            $html .= '<option value="'.$rule['condition'].'" ';

        if($rule['condition'] == $saved_values){
            $html .= ' selected="selected" ';
        }

        if(isset($rule['pro']) && $rule['pro']){
            $html .= ' disabled ';
        }

        $html .= '>';
        $html .= $rule['name'];
        $html .= '</option>';
        }
        $html .= '</optgroup>';
        return $html;
    }

    /*
    function conditionDropdownScript(){
        $rules = $this->selectionRules();
        $html = '<script>';
        $html .= 'var pi_conditions= \'<select class="form-control pi_condition_rules" name="pi_selection[{count}][pi_'.$this->slug.'_condition]">';
        foreach ($rules as $key => $rule){
                $html .= '<option value="'.$key.'" ';
            if(isset($rule['ajax_call'])){
                $html .= ' data-ajax_call="'.$rule['ajax_call'].'" ';
             }
            
            if(isset($rule['ready_list'])){
                $html .= ' data-ready_list="'.$rule['ready_list'].'" ';
            }
            $html .= '>';
            $html .= $rule['name'];
            $html .= '</option>';
        }
        $html .= '</select>\';';
        $html .= '</script>';
        return $html;
    }
    */
    function conditionDropdownScript(){
        $groups = $this->selectionRules();
        $html = '<script>';
        $html .= 'var pi_conditions= \'<select class="form-control pi_condition_rules" name="pi_selection[{count}][pi_'.$this->slug.'_condition]">';
        foreach ($groups as $key => $group){
               $html .= $this->group($key, $group);
        }
        $html .= '</select>\';';
        $html .= '</script>';
        return $html;
    }
    /*
    function conditionDropdown($saved_values, $count){
        $rules = $this->selectionRules();
        $html = "";
        $html .= '<select class="form-control pi_condition_rules" name="pi_selection['.$count.'][pi_'.$this->slug.'_condition]">';
        foreach ($rules as $key => $rule){
                $html .= '<option value="'.$key.'" ';
            if(isset($rule['ajax_call'])){
                $html .= ' data-ajax_call="'.$rule['ajax_call'].'" ';
             }

            if($key == $saved_values){
                $html .= ' selected="selected" ';
            }
            $html .= '>';
            $html .= $rule['name'];
            $html .= '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    */
    function conditionDropdown($saved_values, $count){
        $groups = $this->selectionRules();
        $html = "";
        $html .= '<select class="form-control pi_condition_rules" name="pi_selection['.$count.'][pi_'.$this->slug.'_condition]">';
        foreach ($groups as $key => $group){
            $html .= $this->group($key, $group, $saved_values);
        }
        $html .= '</select>';
        return $html;
    }

    function logicDropdownScript(){
        
        echo '<script>';
        foreach($this->rules as $key => $rule){
            do_action('pi_'.$this->slug.'_logic_'.$key);
        }
        echo '</script>';
    }

    function logicDropdown($condition, $saved_logic, $count){
        $html = apply_filters('pi_'.$this->slug.'_saved_logic_'.$condition,"",$saved_logic, $count);
        return $html;
    }

    function savedConditions($conditions){
        $html = '<script>';
        $html .= 'var pi_'.$this->slug.'_metabox='.count($conditions).';';
        $html .= '</script>';
        return $html;
    }

    function savedRows(){
        $html = '<div class="my-2 pisol-no-cond-msg alert alert-danger text-center" style="display:none">You have not added any condition, you need to add condition to make the Fees work. <br>Add condition by clicking "Add Condition" button</div>';
        if(count($this->saved_conditions) > 0 && is_array($this->saved_conditions)){
            $count = 0;
            foreach($this->saved_conditions as $condition){
                $html .= $this->savedRow($condition, $count);
                $count++;
            }
        }
        return $html;
    }

    function savedRow($condition, $count){
        $html = '<div class="row py-3 border-bottom align-items-center" data-count="' . $count . '">';
        $html .= '<div class="col-12 col-md-4">';
        $html .= $this->conditionDropdown($condition['pi_condition'], $count);
        $html .= '</div>';
        $html .= '<div class="col-12 col-md-3 pi_logic_container">';
        $html .= $this->logicDropdown($condition['pi_condition'],$condition['pi_logic'], $count);
        $html .= '</div>';
        $html .= '<div class="col-12 col-md-4 pi_condition_value_container">';
        $html .= $this->conditionValue($condition['pi_condition'],$condition['pi_value'], $count);
        $html .= '</div>';
        $html .= '<div class="col-12 col-md-1 text-right ">';
        $html .= '<a href="javascript:void(0);" class="pi-delete-rule"><span class="dashicons dashicons-trash"></span></a>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    function conditionValue($condition, $values, $count){
        $html = apply_filters('pi_cefw_saved_values_'.$condition, "", $values, $count);
        return $html;
    }

    static function createSelect($array, $count, $condition ="",  $multiple = "",  $values = array(), $dynamic = ""){

        if($multiple === 'multiple'){
            $multiple = ' multiple="multiple" ';
        }else{
            $multiple = '';
        }

        $html = '<select class="form-control pi_condition_value pi_values_'.$dynamic.'" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_CEFW_SELECTION_RULE_SLUG.'_condition_value][]" '.$multiple.' placeholder="Select">';
        foreach ($array as $key => $value){
                $selected = "";
                if(is_array($values) && in_array($key, $values)){
                    $selected = ' selected="selected" ';
                }
                $html .= '<option value="'.$key.'" '.$selected.'>';
            $html .= $value;
            $html .= '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function createNumberField($count, $condition ="",  $values = array(), $step = 'any'){

        if(is_array($values) && $values > 0){
            $value = ' value="'.$values[0].'" ';
        }else{
            $value = "";
        }
        $html = '<input type="number" step="'.$step.'" class="form-control" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_CEFW_SELECTION_RULE_SLUG.'_condition_value][]" '.$value.' >';
        return $html;
    }

    static function createTextField($count, $condition ="",  $values = array()){

        if(is_array($values) && $values > 0){
            $value = ' value="'.$values[0].'" ';
        }else{
            $value = "";
        }
        $html = '<input required type="text" class="form-control" data-condition="'.$condition.'" name="pi_selection['.$count.'][pi_'.PI_CEFW_SELECTION_RULE_SLUG.'_condition_value][]" '.$value.' >';
        return $html;
    }

}


add_action( 'admin_enqueue_scripts', 'pisol_cefw_dynamic_rule_main_script');

function pisol_cefw_dynamic_rule_main_script(){
    if(isset($_GET['page']) && 'pisol-cefw' == $_GET['page']){
        wp_enqueue_script( 'pisol_cefw_dynamic_rule_main_script', plugin_dir_url( __FILE__ ) . 'js/dynamic_form.js',array( 'jquery' ), '1.7.16' );
        wp_enqueue_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '1.0.4' );
        wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');
    }
}