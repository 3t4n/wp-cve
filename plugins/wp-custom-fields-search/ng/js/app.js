angular.module('WPCFS')
.controller('WPCFSForm', ['$scope','i18n',function ($scope,i18n) {
    $scope.min_height = 0;
    $scope.heights = {};
    $scope.set_min_height = function (height,name){
        $scope.heights[name] = height;
        var min_height = 0;
        angular.forEach($scope.heights,function(v,k){
            if(v>min_height) min_height=v;
        });
        $scope.min_height = min_height + 100;
    };
    $scope.settings_visible = false;
    $scope.datatypes  = array2dict($scope.config.building_blocks.datatypes); 
    $scope.inputs  = array2dict($scope.config.building_blocks.inputs);
    $scope.comparisons  = array2dict($scope.config.building_blocks.comparisons); 

    var pull_config = function(){
        $scope.form_fields = $scope.config.form_config.inputs;
        if(!$scope.config.form_config.settings) $scope.config.form_config.settings = {};
        $scope.settings = $scope.config.form_config.settings;
    };
    pull_config();
    $scope.$watch('config.form_config.id',pull_config);

	$scope.sortableOptions = {
		"containment": "#field-list"
	};

    i18n.dict().then(function(__){
    	$scope.add_field = function(){
	    	var new_field = {};
	    	$scope.form_fields.push(new_field);
            $scope.edit_field(new_field);
    	};
    });

    $scope.edit_field = function(field){
        $scope.popped_up_field = field;
    }
    $scope.remove_field = function(field) {
        var index = $scope.form_fields.indexOf(field);
        if(index>-1) $scope.form_fields.splice($scope.form_fields.indexOf(field),1);
        return field;
    }
    $scope.close_edit_form = function(field){
        if(field && !field.label)
            $scope.remove_field(field);

        $scope.popped_up_field = null;
		if($scope.set_min_height)
	        $scope.set_min_height(0,"field");
    }

    $scope.show_settings_popup = function(){ $scope.settings_visible = true; }
    $scope.close_settings_popup = function(){ 
        $scope.settings_visible = false; 
		if($scope.set_min_height)
			$scope.set_min_height(0,"field");
    }
}]).controller('WPCFSField', ['$scope', 'replace_all', 'i18n', function($scope, replace_all, i18n) {
    $scope.field = $scope.popped_up_field;
	if(!$scope.field.multi_match) $scope.field.multi_match="All";

    $scope.show_config_form = function(form,field){
        $scope.config_popup = {"form": form, "field": field};
    };
    $scope.close_config_popup = function(){
        $scope.config_popup = null;
		if($scope.set_min_height)
			$scope.set_min_height(0,"sub_config");
    };
    i18n.dict().then(function(__){
        $scope.$watch("field.datatype",function(){
            var datatype_options = $scope.datatypes[$scope.field.datatype];
            $scope.fields = datatype_options ? datatype_options.options.all_fields : [];
        });

        $scope.get_valid_comparisons = function(){
            var comparisons = [];
            angular.forEach($scope.config.building_blocks.comparisons,
                function(comparison){
                    var valid = true;
                    if(!comparison['options']){
                        valid = false;
                    } else if(comparison['options']['valid_for']){
                        angular.forEach(comparison['options']['valid_for'],function(restrictions,type){
                            angular.forEach(restrictions,function(value){
                                switch(type){
                                    case 'datatype':
                                        var datatype = $scope.config.building_blocks.datatypes.find(function(element){ return element.id==$scope.field.datatype});
                                        if(datatype && datatype.options.labels){
                                            valid = valid && (datatype.options.labels.indexOf(value)>-1);
                                        }
                                        else valid=false;
                                        break;
                                    default:
                                        throw replace_all(__("Cannot restrict by type {type} in {comparison}"),
                                            { '{type}':type, '{comparison}':comparison.name});
                                }
                            });
                        });
                    }

                    if(valid)
                        comparisons.push(comparison);
                }
           );
            return comparisons;
        };
        [ "input" , "datatype", "comparison" ].forEach(function(type){
			var original = $scope.field[type], first=true;
            $scope.$watch("field."+type,function(new_option){
                try {
                    var config = $scope[type+"s"][new_option]['options'];
                } catch(err){ 
                    return false; 
                }

				var overwrite = true;
				if (first && (new_option==original)) {
					overwrite = false;
				}

				first = false;
                if(config.defaults)
                    angular.forEach(config.defaults,function(v,k){
						if (overwrite || !$scope.field[k])
							$scope.field[k] = angular.copy(v);
                    });
            });
        });
        $scope.$watch("field.datatype",function(){
            $scope.valid_comparisons = $scope.get_valid_comparisons();
        });
    });

}]).controller('WPCFSSettings', ['$scope', 'i18n', function($scope, i18n) {
	if (typeof $scope.settings.default_post_types == 'undefined') {
		angular.extend($scope.settings, {
			"default_post_types": true,
			"selected_post_types": [ "###ANY###"],
		});
	}
    $scope.expand = function(page){
        $scope.expanded = page;
    };
    $scope.is_expanded = function(page){
        return $scope.expanded == page;
    };
	i18n.dict().then(function(__){
		$scope.post_type_options = $scope.config.building_blocks.general.post_types.reduce(
			function(combined, post_type) {
				combined[post_type] = post_type;
				return combined;
			}, { "###ANY###" : __("Show All Post Types") }
		)
	})

    $scope.expanded = $scope.config.settings_pages[0];
}]).controller('ConfigPopup', ['$scope', function($scope) {
    $scope.include_file = $scope.config_popup.form;
    $scope.field = $scope.config_popup.field;
}]).controller('SelectController', ['$scope','i18n', function($scope,i18n) {
	$scope.remove_option = function(option){
		var index = $scope.field.options.indexOf(option);
		$scope.field.options.splice(index,1);
	};
	$scope.add_option = function(){
		$scope.field.options.push({});
	};
}]).controller('TaxonomyController', ['$scope','taxonomyLister', function($scope,taxonomyLister) {
	var extraConfigForm = $scope.datatypes[$scope.field.datatype].options;
	taxonomyLister(extraConfigForm.taxonomyName)
	.then(function(terms) {
		var flattened = [];

		var recurseTerms = function(terms, indent) {
			if (!indent) {
				indent="";
			}
			terms.map(function(term) {
				if (term.children.length==0) {
					return;
				}
				flattened.push({
					"term_id":term.term_id,
					"name":indent+term.name,
				})
				recurseTerms(term.children, indent+" - ");
			});
		}
		recurseTerms(terms);

		$scope.terms = flattened;
	});
}]).controller('PresetsController', [ '$scope', '$filter', '$http', 'i18n', 'serialize_form', function ($scope,$filter,$http,i18n, serialize_form) {
   $scope.form_config = [];
   angular.forEach($scope.config.form_config,function(preset){
        $scope.form_config.push(preset);
   });
   $scope.presets = $scope.form_config;
    $scope.preset = null;

    i18n.dict().then(function(__){
       $scope.get_preset_title = function(preset){
            return preset.settings.form_title || preset.name || __("Untitled Preset");
       };
       $scope.add_preset = function(){
            var preset = {
                "name": __("Untitled Preset"),
                "unsaved": true,
                "id": 1,
                "inputs": [],
                "modified": false,
                "state": "New",
            };
            while($filter('filter')($scope.presets,function(other){ return preset.id==other.id; }).length>0)
                preset.id+=1;
            $scope.presets.push(preset);
            $scope.edit_preset(preset);
       };

       $scope.edit_preset = function(preset){
            $scope.preset = preset;
            $scope.preset.safe = serialize_form(preset);
       };
        $scope.is_selected_preset = function(preset){
            return preset == $scope.preset;
        };
        $scope.is_preset_modified = function(preset){
            var serialized = serialize_form(preset);
            return (serialized!=$scope.preset.safe);
        };

        $scope.save_preset = function(preset){
            preset.state = "Saving";

            $http({
                "method":"POST",
                "url":ajaxurl,
                "data": "action="+$scope.config.save_callback+"&data="+serialize_form(preset)+"&nonce="+$scope.config.save_nonce,
                "headers": {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function(){
                preset.state="Saved";
                preset.modified=false;
            },function(){
                preset.state="Error";
            });
            $scope.preset.safe = serialize_form(preset);
            $scope.close_preset_popup();
        };
        $scope.serial = function(){ return serialize_form($scope.preset); };

        $scope.delete_preset = function(preset) {
            if(confirm(__("Are you sure you want to delete this preset '%s'?").replace('%s',preset.name))){
                $http({
                    "method":"POST",
                    "url":ajaxurl,
                    "data": "action="+$scope.config.delete_callback+"&id="+preset.id+"&nonce="+$scope.config.delete_nonce,
                    "headers": {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(){
                    $scope.presets.splice($scope.presets.indexOf(preset),1);
                },function(){
                    preset.state="Error";
                });
            }
        };

        $scope.close_preset_popup = function(){
            if($scope.is_preset_modified($scope.preset) && !confirm(__(
                "You have unsaved changes, are you sure you wish to close this preset"
            ))) return;

            $scope.preset = null;
        }

        //$scope.edit_preset($scope.presets[0]);

        $scope.export_settings_href = ajaxurl+"?action="+$scope.config.export_callback;
        $scope.warn_no_import = function(){
            alert(__("There is currently no import functionality, the settings export is for debug use only"));
        };
    });

}]).controller('PresetController', [ '$scope', function ($scope) {

    var update_child_config = function(){
        $scope.config = {
            "form_config": $scope.preset,
            "building_blocks": $scope.config.building_blocks,
            "settings_pages": $scope.config.settings_pages,
        };
    };
    $scope.$watch("preset",update_child_config);

    update_child_config();
}]).controller('PresetModifiedController', [ '$scope', function($scope){
}]);
