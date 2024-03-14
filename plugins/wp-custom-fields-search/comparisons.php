<?php 

class WPCustomFieldsSearch_Equals extends WPCustomFieldsSearch_Comparison {
    function get_name(){ return __("Exact Match","wp_custom_fields_search"); }
}
class WPCustomFieldsSearch_TextIn extends WPCustomFieldsSearch_Comparison {
    function get_name(){ return __("Contains Text","wp_custom_fields_search"); }

	function get_where($config,$value,$field_alias){
		return $field_alias." LIKE '%".wpcfs_escape_string($value)."%'";
	}
}
class WPCustomFieldsSearch_OrderedComparison extends WPCustomFieldsSearch_Comparison {
	function get_ordered_where($config,$value,$field_alias,$comparison){
		$value = wpcfs_escape_string($value);
		switch($config['numeric']){
		case 'Numeric':
			$field_alias="1*$field_alias";
			break;
		case 'Alphabetical': default:
			$value = "'$value'";
			break;
		}
		return "$field_alias$comparison$value";
    }
	function get_editor_options(){
		$options = parent::get_editor_options();
		$options['extra_config_form'] = plugin_dir_url(__FILE__).'ng/partials/comparisons/numeric.html';
		$options['numeric'] = "Alphabetical";
		return $options;
	}
}
class WPCustomFieldsSearch_GreaterThan extends WPCustomFieldsSearch_OrderedComparison {
    function get_name(){ return __("Greater Than","wp_custom_fields_search"); }
	function get_where($config,$value,$field_alias){
        $comparison = $config['inclusive'] ? ">=" : ">";
        return $this->get_ordered_where($config,$value,$field_alias,$comparison);
	}
}
class WPCustomFieldsSearch_LessThan extends WPCustomFieldsSearch_OrderedComparison {
    function get_name(){ return __("Less Than","wp_custom_fields_search"); }

	function get_where($config,$value,$field_alias){
        $comparison = $config['inclusive'] ? "<=" : "<";
        return $this->get_ordered_where($config,$value,$field_alias,$comparison);
	}
}
class WPCustomFieldsSearch_Range extends WPCustomFieldsSearch_OrderedComparison {
    function get_name(){ return __("In Range","wp_custom_fields_search"); }

	function get_where($config,$value,$field_alias){
		$range = explode(":",$value);
		if (count($range) !=2) {
			trigger_error( __("Range format should be '<min>:<max>' received '$value'"));
			if (count($range) == 1) {
				$range[] = null;
			} else {
				$range = array_slice($range,0,2);
			}
		}

		list($min, $max) = $range;
        $params = array();
        if($min){
            $comparison = $config['inclusive'] ? ">=" : ">";
            $params[] = $this->get_ordered_where($config,$min,$field_alias,$comparison);
        }
        if($max){
            $comparison = $config['inclusive'] ? "<=" : "<";
            $params[] = $this->get_ordered_where($config,$max,$field_alias,$comparison);
        }
        if(!$params) $params = array(1);

        return "( ".join(" AND ",$params)." )";
	}
}

class WPCustomFieldsSearch_SubCategoryOf extends WPCustomFieldsSearch_Comparison {
    function get_name(){ return __("In category or Sub-category","wp_custom_fields_search"); }

    function get_editor_options(){
        return array_merge(parent::get_editor_options(),array(
            "valid_for"=>array(
                "datatype"=>array("is_wp_term")
            )
        ));
    }
    function collect_ids($field,$category_list){
        $to_return = array();
        foreach($category_list as $category){
            $to_return[] = $category->$field;
            $to_return = array_unique(array_merge($to_return,$this->collect_ids($field,get_categories(array("child_of"=>$category->term_id)))));
        }
        return $to_return;
    }
    function get_where($config,$value,$field_alias){
        global $wpdb;
        $field = $config['datatype_field'];
        if($field == "term_id"){
            $dummy_category = new stdclass();
            $dummy_category->term_id = $value;
            $parent_categories = array($dummy_category);
        } else {
            $parent_categories = get_categories(array("name"=>$value));
        }
        $child_categories = $this->collect_ids($field,$parent_categories);
        
        return $field_alias." IN ('".join("','",$child_categories)."')";
    }
}
