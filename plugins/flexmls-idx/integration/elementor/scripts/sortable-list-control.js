var sortableListControlView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        var self = this,
            old_value = this.ui.input[0].value;
            
        self.timer = setInterval(function(){
            if(self.ui.input[0].value !== old_value){
                self.saveValue();
                old_value = self.ui.input[0].value;
            }
        }, 300);
    },

    saveValue: function () {
        this.setValue(this.ui.input[0].value);
    },

    onBeforeDestroy: function () {
        this.saveValue();
        clearInterval(this.timer);
    }
});

elementor.addControlView('sortable_list_control', sortableListControlView);
