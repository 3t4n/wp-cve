function YRMConditionBuilder() {
}

YRMConditionBuilder.prototype.init = function() {
    this.conditionsBuilder();
    this.select2();
};

YRMConditionBuilder.prototype.select2 = function() {
    var select2 = jQuery('.js-yrm-select');

    if(!select2.length) {
        return false;
    }
    select2.each(function() {
        var type = jQuery(this).data('select-type');

        var options = {
            width: '100%'
        };

        if (type == 'ajax') {
            options = jQuery.extend(options, {
                minimumInputLength: 1,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    type: "POST",
                    data: function(params) {

                        var searchKey = jQuery(this).attr('data-value-param');
                        var postType = jQuery(this).attr('data-post-type');

                        return {
                            action: 'yrm_select2_search_data',
                            nonce_ajax: yrmBackendData.nonce,
                            postType: postType,
                            searchTerm: params.term,
                            searchKey: searchKey
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: jQuery.map(data.items, function(item) {

                                return {
                                    text: item.text,
                                    id: item.id
                                }

                            })
                        };
                    }
                }
            });
        }

        jQuery(this).select2(options);
    });
};

YRMConditionBuilder.prototype.conditionsBuilder = function() {
    this.conditionsBuilderEdit();
    this.conditionsBuilderAdd();
    this.conditionsBuilderDelte();
};

YRMConditionBuilder.prototype.conditionsBuilderAdd = function() {
    var params = jQuery('.yrm-condition-add');

    if(!params.length) {
        return false;
    }
    var that = this;
    params.bind('click', function() {
        var currentWrapper = jQuery(this).parents('.yrm-condion-wrapper').first();
        var selectedParams = currentWrapper.find('.js-conditions-param').val();

        that.addViaAjax(selectedParams, currentWrapper);
    });
};

YRMConditionBuilder.prototype.conditionsBuilderDelte = function() {
    var params = jQuery('.yrm-condition-delete');

    if(!params.length) {
        return false;
    }

    params.bind('click', function() {
        var currentWrapper = jQuery(this).parents('.yrm-condion-wrapper').first();

        currentWrapper.remove();
    });
};

YRMConditionBuilder.prototype.conditionsBuilderEdit = function() {
    var params = jQuery('.js-conditions-param');

    if(!params.length) {
        return false;
    }
    var that = this;
    params.bind('change', function() {
        var selectedParams = jQuery(this).val();
        var currentWrapper = jQuery(this).parents('.yrm-condion-wrapper').first();

        that.changeViaAjax(selectedParams, currentWrapper);
    });
};

YRMConditionBuilder.prototype.addViaAjax = function(selectedParams, currentWrapper) {
    var conditionId = parseInt(currentWrapper.data('condition-id'))+1;
    var conditionsClassName = currentWrapper.parent().data('child-class');

    var that = this;

    var data = {
        action: 'yrm_add_conditions_row',
        nonce: yrmBackendData.nonce,
        conditionId: conditionId,
        conditionsClassName: conditionsClassName,
        selectedParams: selectedParams
    };

    jQuery.post(ajaxurl, data, function(response) {
        currentWrapper.after(response);
        that.init();
    });
};

YRMConditionBuilder.prototype.changeViaAjax = function(selectedParams, currentWrapper) {
    var conditionId = currentWrapper.data('condition-id');
    var conditionsClassName = currentWrapper.parent().data('child-class');

    var that = this;

    var data = {
        action: 'yrm_edit_conditions_row',
        nonce: yrmBackendData.nonce,
        conditionId: conditionId,
        conditionsClassName: conditionsClassName,
        selectedParams: selectedParams
    };

    jQuery.post(ajaxurl, data, function(response) {
        currentWrapper.replaceWith(response);
        that.init();
    });
};

jQuery(document).ready(function() {
    var obj = new YRMConditionBuilder();
    obj.init();
});