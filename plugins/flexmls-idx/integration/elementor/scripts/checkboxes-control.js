var checkboxesControlView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        var self = this,
            $ = jQuery,
            input_val = $(this.ui.input[0]),
            wrapper = input_val.parent();

            var cb = wrapper.find('.type_cb');

            cb.click(function(){
                var arr = [];
                cb.each(function(){
                    var self_cb = $(this);
                    //console.log(self_cb.attr('fmc-field'), self_cb.is(':checked'));
                    if(self_cb.is(':checked')){
                        arr.push(self_cb.attr('fmc-field'));
                    }
                });
                input_val.val(arr.join(','));
                self.saveValue();
            })
    },

    saveValue: function () {
        this.setValue(this.ui.input[0].value);
    },

    onBeforeDestroy: function () {
        this.saveValue();
    }
});

elementor.addControlView('checkboxes_control', checkboxesControlView)