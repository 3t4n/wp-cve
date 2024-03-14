angular.module('WPCFS')
.factory('i18n',['$q','$http', function($q,$http){
    if(typeof __ != 'undefined') {
        var i18n = function(phrase){
            var d = $q.defer();
            d.resolve(__(phrase));
            return d.promise;
        };

        var d2 = $q.defer();
        d2.resolve(__);
        i18n.dict = function() { return d2.promise; };
        return i18n;
    }

    var translations;
    var get_translations = function(){
        if(!translations) translations = $http.get(ajaxurl+"?action=wpcfs_ng_load_translations");
        return translations;
    }

    var service = function(phrase){
        return service.i18n(phrase);
    }
    service.i18n = function(phrase){
        return get_translations().then(function(response){
            if(response.data[phrase])
                return response.data[phrase];
            else
                return phrase;
        });
    };
    service.dict = function(){
        return get_translations().then(function(response){
            return function(k){
                return response.data[k] || k;
            };
        });
    };
    return service;
}])
.directive('i18n',[ 'i18n', function(i18n){
   return {
        "link": function(scope,element,attrs,controller,transcludeFn){
            i18n(element.html()).then(function(translation){
                element.replaceWith(translation);
            });
        }
    }
}])
.factory('replace_all', function(){
    return function(string,replacements){
        angular.forEach(replacements,function(v,k){
            string = string.replace(k,v);
        });
        return string;
    };
})
.factory('serialize_input', function(){
    var serialize_input =  function(input){
        var serialized = {
            "label": input.label,
            "input": input.input,
            "datatype": input.datatype,
            "datatype_field": input.datatype_field,
            "comparison": input.comparison,
            "source": input.source,
            "options": input.options,
            "split_words": input.split_words,
            "numeric": input.numeric,
            "inclusive": input.inclusive,
            "taxonomy_root": input.taxonomy_root,
            "any_message": input.any_message,
            "multi_match": input.multi_match,
        };

        angular.forEach(serialize_input.extra_serializers,function(serializer){
            serialized = serializer(input, serialized);
        });

        return serialized;
    };

    serialize_input.extra_serializers = [];
    serialize_input.add_serializer = function(extra){
        serialize_input.extra_serializers.append(extra);
    }

    return serialize_input;
})
.factory('serialize_form',[ 'serialize_input', '$filter', function(serialize_input,$filter){
    var serialize_form =  function(form){
        var serialized = {
            "id": form.id,
            "settings": {

                "form_title": form.settings.form_title,
                "show_title": form.settings.show_title,
                "default_post_types": form.settings.default_post_types,
                "selected_post_types": form.settings.selected_post_types,

            },
            "action": form.action,
            "inputs": form.inputs.map(serialize_input),
        };

        angular.forEach(serialize_form.extra_serializers,function(serializer){
            serialized = serializer(form, serialized);
        });

        return $filter('json')(serialized);
    };

    serialize_form.extra_serializers = [];
    serialize_form.add_serializer = function(extra){
        serialize_form.extra_serializers.push(extra);
    };

    return serialize_form;
}])
.directive("wpcfsHeightSource", function(){
    return {
        "restrict": "A",
        "link": function(scope,elem,attrs) {
            var source_field = attrs.wpcfsHeightSource;
            scope.$watch(function(){
				if(scope.set_min_height)
					scope.set_min_height(elem.height(), source_field);
            });
        }
    }
})
.directive("focusMe", [ '$timeout', function($timeout){
    return {
        "restrict": "A",
        "link": function(scope,elem,attrs) {
            $timeout(function(){
                elem[0].focus();
            });
        }
    }
}])
.factory("taxonomyLister", [ '$http', '$q', function ($http) {
	var loaded = {};
	return function(taxonomy) {
		if (typeof loaded[taxonomy] == 'undefined') {
			var url = ajaxurl+"?action=wpcfs_ng_load_taxonomy&taxonomy="+taxonomy;
			loaded[taxonomy] = 
				$http.get(url)
				.then(function (response) {
					return response['data'];
				});
		}
		return loaded[taxonomy];

	};
}])
;
