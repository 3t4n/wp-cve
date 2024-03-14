(function($) {
"use strict"

    var imageSelect = elementor.modules.controls.BaseData.extend({

    onReady: function () {

        var self = this;

        var parentSelector = this.ui.controlTitle.parent().parent();
        var btnSelector = parentSelector.find('.image-select-btn');


        // Onclick add and remove active class
        var item = parentSelector.find('.darkluplite-image-select-item')

        $(item).on( 'click', function() {
            var $this = $(this);

            item.removeClass( 'darkluplite_block-active' );
            $this.addClass( 'darkluplite_block-active' );

        } )

        // Default Active
        var $defaultActive = self.ui.radio.first().parent().parent();
        $defaultActive.addClass('darkluplite_block-active');

        // Seved item active
        self.ui.radio.each( function( index, val ) {

            if( val.checked == true ) {
                var inputTopParent = $(val).parent().parent();

                item.removeClass( 'darkluplite_block-active' );

                inputTopParent.addClass('darkluplite_block-active');
            }

        } )

        // Buton click  show hide
        btnSelector.on( 'click', function() {

            var pop = parentSelector.find('.select-image-wrapper');
            pop.slideToggle('slow');

        } )


    },

});

elementor.addControlView('image-select', imageSelect );


})(jQuery)