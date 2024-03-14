(function($) {
"use strict";

let imageSelect = elementor.modules.controls.BaseData.extend({

    onReady: function () {

        let self = this;

        let parentSelector = this.ui.controlTitle.parent().parent();
        let btnSelector = parentSelector.find('.image-select-btn');

        // Onclick add and remove active class
        let item = parentSelector.find('.enteraddons-image-select-item')

        $(item).on( 'click', function() {
            let $this = $(this);

            item.removeClass( 'enteraddons_block-active' );
            $this.addClass( 'enteraddons_block-active' );

        } )

        // Default Active
        let $defaultActive = self.ui.radio.first().parent().parent();
        $defaultActive.addClass('enteraddons_block-active');

        // Seved item active
        self.ui.radio.each( function( index, val ) {

            if( val.checked == true ) {
                let inputTopParent = $(val).parent().parent();

                item.removeClass( 'enteraddons_block-active' );

                inputTopParent.addClass('enteraddons_block-active');
            }

        } )

        // Buton click  show hide
        btnSelector.on( 'click', function() {

            let pop = parentSelector.find('.select-image-wrapper');
            pop.slideToggle('slow');

        } )


    },

});

elementor.addControlView('image-select', imageSelect );


})(jQuery)