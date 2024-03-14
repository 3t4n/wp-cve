(function ($) {
    const CTCDeal = {

        /**
         * Init
         */
        init: function () {
            this._bind();
        },

        /**
         * Binds events
         */
        _bind: function () {
            $( document ).on( 'click', '.ctc-deal-toggle-link', this.toggleDetails );
        },

        /**
         * Toggle Details
         */
        toggleDetails: function (event) {
            event.preventDefault();

            const self = $( this )
            const deal = self.parents( '.ctc-deal' )
            const details = deal.find( '.ctc-toggle-details' )

            details.slideToggle( 'fast' )
            
        }
    };

    /**
     * Initialization
     */
    $(function () {
        CTCDeal.init();
    });

})(jQuery);