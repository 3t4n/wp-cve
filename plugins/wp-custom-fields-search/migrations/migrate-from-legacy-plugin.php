<?php
function wpcfs_upgrade_3_x_to_1_0(){
    $old_settings = get_option("db_customsearch_widget");
    if(!$old_settings) return;

    $new_settings = array(
        "widget"=>array(),
        "preset"=>array()
    );
    foreach($old_settings as $k=>$old_config){
        if($k=="version") continue;

        $new_config = array(
            "name"=>$old_config['name'],
            "inputs"=>array(),
        ); 
        $mappings = array(
            "joiner"=>array(
                "PostDataJoiner"=>"WPCustomFieldsSearch_PostField",
                "PostTypeJoiner"=>"WPCustomFieldsSearch_PostField",
                "CategoryJoiner"=>"WPCustomFieldsSearch_Category",
            ),
            "comparison"=>array(
                "WordsLikeComparison"=>"WPCustomFieldsSearch_TextIn",
                "LikeComparison"=>"WPCustomFieldsSearch_TextIn",
                "EqualComparison" =>"WPCustomFieldsSearch_Equals",
            ),
            "input"=>array(
                "DropDownField"=>"WPCustomFieldsSearch_SelectInput",
                "TextField"=>"WPCustomFieldsSearch_TextBoxInput",
                "HiddenField"=>"WPCustomFieldsSearch_HiddenInput",
            ),
        );
            
        foreach($old_config as $field_index=>$old_input){
            if(is_numeric($field_index)){
                $new_input = array(
                    "label"=>$old_input['label'],
                    "datatype"=>$mappings["joiner"][$old_input['joiner']],
                    "datatype_field"=>$old_input['name'],
                    "comparison"=>$mappings['comparison'][$old_input['comparison']],
                    "input"=>$mappings['input'][$old_input['input']],
                );
                switch($old_input['joiner']){
                    case 'CategoryJoiner':
                        $new_input['datatype_field']='term_id';
                        break;
                    case "PostTypeJoiner":
                        $new_input['datatype_field']='post_type';
                        break;
                        
                }
                switch($old_input['comparison']){
                    case "WordsLikeComparison":
                        $new_input['split_words'] = "True";
                        $new_input['multi_match'] = "All";
                        break;
                }
                switch($old_input['input']){
                    case 'DropDownField':
                        if($old_input['dropdownoptions']){
                            $new_input['source'] = 'Manual';
                            $options = array();
                            $optionString = $old_input['dropdownoptions'];
                            $options=array();
                            $optionPairs = explode(',',$optionString);
                            $prefix="";
                            foreach($optionPairs as $option){
                                if(strrchr($option,"\\")=="\\"){
                                    $prefix .= substr($option,0,-1).",";
                                    continue;
                                }
                                $option = $prefix.$option;
                                list($k,$v) = explode(':',$option);
                                if(!$v) $v=$k;
                                $options[]=array("label"=>$v,"value"=>$k);
                                $prefix = "";
                            }
                            $new_input['options'] = $options;
                        } else {
                            $new_input['source'] = 'Auto';
                        }
                        break;
                    case "HiddenField":
                        $new_input['constant_value'] = $old_input['constant-value'];
                        break;
                }

                $new_config["inputs"][] = $new_input;
            }
        }

        if(strpos($k,"preset-")===0){
            $type="preset";
            $new_config['id'] = substr($k,7);
            if($new_config['id']=="default") $new_config['id']=0;
        } elseif(is_numeric($k)){
            $type="widget";
        } else {
            trigger_error("Unrecognised item in legacy settings");
        }
        
        $new_settings[$type][$k] = $new_config;
    }
    update_option("wp_custom_fields_search",array("presets"=>array_values($new_settings['preset'])));
    foreach($new_settings['widget'] as $k=>$v){
        $new_settings['widget'][$k] = array('data'=>json_encode($v));
    }
    update_option("widget_wp_custom_fields_search",$new_settings["widget"]);

    $sidebars = get_option("sidebars_widgets");
    foreach($sidebars as $menu_name=>$widgets){
        $new_widgets = array();
        foreach($widgets as $k=>$v){
            $new_widgets[] = $v;
            $alt = str_replace("db_customsearch_widget","wp_custom_fields_search",$v);
            if($alt!=$v){
                $new_widgets[] = $alt;
            }
        }
        $sidebars[$menu_name] = $new_widgets;
    }
    update_option('sidebars_widgets',$sidebars);
};
