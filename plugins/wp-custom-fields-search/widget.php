<?php
/*
 * Copyright 2015 Web Hammer UK Ltd.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at 
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License.
 */

require_once(dirname(__FILE__).'/functions.php');

class WPCustomFieldsSearchWidget extends WP_Widget {
	function __construct(){
		parent::__construct('wp_custom_fields_search',
			__("WPCFS Custom Search Form","wp_custom_fields_search" ,"wp_custom_fields_search"),
			array(
				"description"=>__("Customisable search form (from WP Custom Fields Search)","wp_custom_fields_search","wp_custom_fields_search")
			)
		);
	}


    function get_query_if_submitted($instance){
        if($_GET['wpcfs']==$instance['widget_id']){
            return $_GET;
        }
    }   
	function widget($args,$instance){
		require_once("search_form.php");
		$data =json_decode($instance['data'],true);
        WPCFSSearchForm::show_form($data,$args['widget_id'],$args);
	}

	function update($new_instance,$old_instance){
		return array(
			"data"=>wpcfs_strip_hash_keys($new_instance['data']),
		);
	}

	function form($instance){

		$defaults = array();

		$instance=array_merge($defaults,$instance);

        $settings_pages = apply_filters("wpcfs_settings_pages",array());

		$form_id = $this->get_field_id('edit-form');
        $default = "{inputs:[],settings:{}}";
        $form_config = (array_key_exists('data',$instance) && $instance['data']) ?$instance['data']:$default;
        if(!json_decode($form_config)){
            $form_config2 = str_replace('""','"',$form_config);
            if(json_decode($form_config2)) $form_config = $form_config2;
            else $form_config = $default;
        }
        include(dirname(__FILE__).'/templates/unsupported-message.php');
		// TODO: Could this be implemented with is_active_sidebar???
		if($this->number=="__i__"){
			echo "
				<div id='$form_id' class='wp_custom_fields_search-form'>
				</div>
				<script>
                    var configure_forms = function(){
                        jQuery('.wp_custom_fields_search-form:not(.wp_custom_fields_search_editor)').each(function(el){
                            var $=jQuery;
                            var template_id = '$form_id',
                                template_name='".$this->get_field_name('data')."',
                                id_parts = template_id.split('__i__'),
                                actual_id = $(this).attr('id');

                            var index=actual_id.substr(id_parts[0].length,actual_id.length-id_parts[1].length-id_parts[0].length);
                            var actual_name = template_name.replace('__i__',index);
                        
                            if(index=='__i__') return;

                            $(this).wp_custom_fields_search_editor({
                                'form_config': $form_config,
                                'building_blocks': ".json_encode(WPCustomFieldsSearchPlugin::get_javascript_editor_config()).",
                                'settings_pages': ".json_encode($settings_pages).",
                                'field_name':'".$this->get_field_name('data')."',
                                'root': '".plugin_dir_url(__FILE__)."'
                            });
                            
                        });
                    };
                    var __translations = {};
                    var __ = function(phrase){
                        return __translations[phrase]||phrase;
                    };
                    jQuery.get(ajaxurl+'?action=wpcfs_ng_load_translations').then(function(data){
                       __translations = data;
                        configure_forms(); 
                        jQuery('body').mouseup(function(){
                            configure_forms();
                            setTimeout(configure_forms,1000);
                            setTimeout(configure_forms,5000);
                            setTimeout(configure_forms,10000);
                        });
                    });
				</script>
			";
		} else {
			echo "
				<div id='$form_id' class='wp_custom_fields_search-form'>
				</div>
				<script>
					jQuery('#$form_id').wp_custom_fields_search_editor({
						'form_config':$form_config,
						'building_blocks': ".json_encode(WPCustomFieldsSearchPlugin::get_javascript_editor_config()).",
                        'settings_pages': ".json_encode($settings_pages).",
						'field_name':'".$this->get_field_name('data')."',
                        'root': '".plugin_dir_url(__FILE__)."'
					});
				</script>
			";
		}
	}
}
