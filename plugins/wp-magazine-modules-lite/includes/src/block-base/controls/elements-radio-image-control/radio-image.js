/**
 * Handles the events in radio image control.
 */
"use strict";

var radioImageControlView = elementor.modules.controls.BaseData.extend({
    onReady: function () {
        this.saveValue();
    },

    saveValue: function () {
        var elementContainer = this;
        var Container = this.$el;
        var eachControl = Container.find( '#elementor-radio-image-control-field .cvmm-radio-image-control-wrap li' );
        jQuery( eachControl ).on( 'click', function($) {
            var _this = jQuery( this );
            _this.addClass( "isActive" ).siblings().removeClass( "isActive" );
            var newValue  = _this.data( "value" );
            elementContainer.setValue( newValue );
        })
    }
});

elementor.addControlView( 'RADIOIMAGE', radioImageControlView );