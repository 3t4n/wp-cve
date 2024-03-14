var Fields = Fields || {};
Fields.checkbox = SktbuilderFieldView.extend(
/** @lends Fields.checkbox.prototype */{
    className: 'field-checkbox field-group',
    events: {
        'change input': 'changeInput'
    },
    /**
     * Event change input
     * @param {Object} evt
     */
    changeInput: function (evt) {
        var target = jQuery(evt.target);
        this.model.set(target.attr('name'), (target[0].checked == false ? 0 : 1));
    },
    /**
     * Get boolean value
     * @param {String} val
     * @returns {Boolean}
     */
    getBool: function (val){
        if(val == undefined) {
            return false;
        } 
        var num = +val;
        return !isNaN(num) ? !!num : !!String(val).toLowerCase().replace(!!0,'');
    },
    /**
     * Get checked
     * @returns {String}
     */
    checked: function () {        
        if (this.model.get(this.settings.name)) {
            return this.getBool(this.model.get(this.settings.name));
        } else {
            return this.getBool(this.settings.default);
        }
    },
    /**
     * Render filed checkbox
     * @returns {Object}
     */
    render: function () {
        var htmldata = {
            "label" : this.settings.label,
            "name" : this.settings.name,
            "checked" : (this.checked()  ? "checked" : "")
        };
        
        this.$el.html(_.template(this.storage.getSkinTemplate('field-checkbox-preview'))(htmldata));
        return this;
    }
});
