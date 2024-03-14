var locationControlView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        var self = this,
            input_val = jQuery(this.ui.input[0]),
            apply = input_val.parent().parent().find('.flexmls_connect__location_button_apply');
            
            jQuery(apply).click(function(){
                self.saveValue();
            });
    },

    saveValue: function () {
        this.setValue(this.ui.input[0].value);
    },

    onBeforeDestroy: function () {
        this.saveValue();
    }
});

elementor.addControlView('location_control', locationControlView);