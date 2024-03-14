<?php
    class WPCustomFieldsSearch_PostField extends WPCustomFieldsSearch_DataType {
        function get_name(){ return __("Core Post Field","wp_custom_fields_search"); }
        function getFieldMap(){
            global $wpdb;
            return array(
                "post_title"    =>    __("Title","wp_custom_fields_search"),
                "post_author"    =>    __("Author","wp_custom_fields_search"),
                "post_date"    =>    __("Date","wp_custom_fields_search"),
                "post_content"    =>    __("Content","wp_custom_fields_search"),
                "post_excerpt"    =>    __("Excerpt","wp_custom_fields_search"),
                "post_type"    =>    __("Post Type","wp_custom_fields_search"),
                "all"        =>    __("All","wp_custom_fields_search"),
                "post_id"    =>    __("ID","wp_custom_fields_search"),
            );
        }
        function getAvailableFields(){
            return array_values($this->getFieldMap());
        }

        function add_joins($config,$join,$count){
            return $join;
        }
        function get_field_aliases($config,$count){
            if($config['datatype_field']=='all'){
                $aliases = array();
                foreach(array('post_title','post_author','post_content') as $field){
                    $aliases[] = $this->get_field_alias($config,$field,$count);
                }
                return $aliases;
            } else {
                return parent::get_field_aliases($config,$count);
            }
        }
        function get_field_alias($config,$field_name,$count=0){
            global $wpdb;
            return $wpdb->posts.".".$field_name;
        }

        function get_suggested_values($config){
            global $wpdb;
            switch($config['datatype_field']){
                case 'post_title': case 'post_date': case 'post_content': case 'post_excerpt': case 'all':
                    $map = $this->getFieldMap();
                    trigger_error(__("Cannot auto-populate select for ","wp_custom_fields_search").$map[$config['datatype_field']]);
                    return array();
                case 'post_author':
                    $q = $wpdb->get_results("SELECT GROUP_CONCAT(DISTINCT post_author) AS author FROM $wpdb->posts");
                    $authors = $wpdb->get_results("SELECT ID,display_name FROM $wpdb->users WHERE ID IN (".$q[0]->author.")");
                    $response = array();
                    foreach($authors as $row){
                        $response[] = array( "value"=>$row->ID, "label"=>$row->display_name);
                    }
                    return $response;
                case 'post_type':
                    return $this->_array_to_suggestions_list($wpdb->get_col($wpdb->prepare("SELECT DISTINCT post_type FROM $wpdb->posts WHERE post_status='publish'",array())));
            }
        }
        function get_editor_options(){
            $options = parent::get_editor_options();
            $options['defaults'] = array( "datatype_field"=>"all");
            return $options;
        }
    }

    class WPCustomFieldsSearch_CustomField extends WPCustomFieldsSearch_DataType {
        function get_name(){ return __("Custom Post Field","wp_custom_fields_search"); }
        function getFieldMap(){
            global $wpdb;
            $results = $wpdb->get_results("SELECT DISTINCT(meta_key) FROM $wpdb->postmeta ORDER BY meta_key");
            $fields = array();
            foreach($results as $result){
                $fields[$result->meta_key] = $result->meta_key;
            }
            return $fields;
        }
        function getAvailableFields(){
            return array_values($this->getFieldMap());
        }

        function get_table_name(){
            global $wpdb;
            return $wpdb->postmeta;
        }
        function get_field_alias($config,$field_name,$count){
            return $this->get_table_alias($config,$count).".meta_value";
        }
        function add_joins($config,$join,$count){
            $join = parent::add_joins($config,$join,$count);
            for($a = 0 ; $a<$count ; $a++){
                $alias = $this->get_table_alias($config,$a);
                $join = str_replace("AS $alias ON ","AS $alias ON $alias.meta_key='".wpcfs_escape_string($config['datatype_field'])."' AND ",$join);
            }
            return $join;
        }
        function get_suggested_values($config){
            global $wpdb;
            $values = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key=%s ORDER BY meta_value",$config['datatype_field']));
            return $this->_array_to_suggestions_list($values);
        }
    }

    class WPCustomFieldsSearch_TaxonomyTerm extends WPCustomFieldsSearch_DataType {
        var $multijoin = true;

        function getFieldMap(){
            return array("term_id"=>__("ID","wp_custom_fields_search"),"name"=>__("Name","wp_custom_fields_search"));
        }

        function add_joins($config,$join,$count){
            global $wpdb;
            for($index = 0 ; $index<$count ; $index++){

                $alias = $this->get_table_alias($config,$index);
                $alias2 = $alias."_2";
                $alias3 = $alias."_3";
                
                $join.=" LEFT JOIN $wpdb->term_relationships AS $alias2 ON $wpdb->posts.ID = $alias2.object_id ";
                $join.=" LEFT JOIN $wpdb->term_taxonomy AS $alias3 ON $alias3.term_taxonomy_id = $alias2.term_taxonomy_id ";
                $join.=" LEFT JOIN $wpdb->terms AS $alias ON $alias3.term_id = $alias.term_id ";
            }

            return $join;
        }

        function get_editor_options(){
            $options = parent::get_editor_options();
            if(!array_key_exists('labels',$options)) $options['labels'] = array();
            $options['labels'][] = "is_wp_term";
            $options['taxonomyName'] = $this->taxonomy;
            $options['defaults'] = array( "datatype_field"=>"name");
            $options['extra_config_form'] = plugin_dir_url(__FILE__).'ng/partials/datatypes/taxonomy.html';
            return $options;
        }

        function recurse_category($id,$field,$taxonomy,$trace=array()){
            $categories = get_terms(array('parent'=>$id,"taxonomy"=>$taxonomy, 'hide_empty'=>false));
            $values = array();
            foreach($categories as $category){
                $full_trace[] = array_merge($trace,array($category));
                $values[] = array("value"=>$category->$field,"label"=> $category->name);
                $values = array_merge($values,$this->recurse_category($category->term_id,$field,$taxonomy,$full_trace));
            }
            return $values;
        }

        function get_suggested_values($config){
            $root = array_key_exists('taxonomy_root', $config) ? $config['taxonomy_root'] : 0;
            return $this->recurse_category($root, $config['datatype_field'], $this->taxonomy);
        }
    }

    class WPCustomFieldsSearch_CustomTaxonomy extends WPCustomFieldsSearch_TaxonomyTerm {
        function get_name(){ return __("Custom Taxonomy","wp_custom_fields_search"); }

        function getFieldMap()
        {
            return array_reduce(
                get_taxonomies(),
                function ($map, $taxonomyName) {
                    $taxonomy = get_taxonomy($taxonomyName);
                    $map[$taxonomyName] = $taxonomy->labels->name;
                    return $map;
                },
                []    
            );
        }
        function recurse_category($id,$field,$taxonomy,$trace=array()){
            return parent::recurse_category($id,"term_id",$taxonomy,$trace);
        }
        function get_editor_options(){
            $options = parent::get_editor_options();
            unset($options['taxonomyName']);
            return $options;
        }

        function get_field_alias($config,$field_name,$count=0){
            $alias = $this->get_table_alias($config,$count);
            return "$alias.term_id";
        }

        function get_suggested_values($config){
            $root = array_key_exists('taxonomy_root', $config) ? $config['taxonomy_root'] : 0;
            return $this->recurse_category($root, 'term_id', $config['datatype_field']);
        }
    }
    class WPCustomFieldsSearch_Category extends WPCustomFieldsSearch_TaxonomyTerm {
        var $taxonomy = "category";
        function get_name(){ return __("Category Field","wp_custom_fields_search"); }
    }
    class WPCustomFieldsSearch_Tag extends WPCustomFieldsSearch_TaxonomyTerm {
        var $taxonomy = "post_tag";
        function get_name(){ return __("Tag","wp_custom_fields_search"); }
    }
