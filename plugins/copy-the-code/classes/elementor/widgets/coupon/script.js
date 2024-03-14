(function ($) {
    const CTCCoupon = {

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
            $( document ).on( 'click', '.ctc-coupon-toggle-link', this.toggleDetails );
            $( document ).on( 'click', '.ctc-coupon-link', this.handleClick );
        },

        /**
         * Handle click
         */
        handleClick: function (event) {
            event.preventDefault()

            const self = $( this )
            const parent = self.parents( '.ctc-coupon' )
            if ( parent.hasClass( 'ctc-coupon-clicked' ) ) {
                return
            }

            const href = self.attr( 'href' )
            const target = self.attr( 'target' )
            const couponCode = parent.find( '.ctc-coupon-code' ).text().trim() || ''
            console.log( couponCode )

            parent.addClass( 'ctc-coupon-clicked' )

            CTC.copy( couponCode )

            window.open( href, target )
        },

        /**
         * Handle click
         */
        oldHandleClick: function (event) {
            event.preventDefault();

            const self = $( this )
            const href = self.data( 'href' )
            const target = self.data( 'target' )
            const btn = self.find( '.ctc-coupon-button' )

            // Clicked then open the link
            if ( ! self.hasClass( 'ctc-coupon-link-clicked' ) ) {
                window.open( href, target )

                self.addClass( 'ctc-coupon-link-clicked' )
                btn.text( 'Copy Code' )
            }

            if ( self.hasClass( 'ctc-coupon-link-clicked' ) ) {
                self.addClass( 'ctc-coupon-link-copied' )
                btn.text( 'Copied' )
            }
        },

        /**
         * Toggle Details
         */
        toggleDetails: function (event) {
            event.preventDefault();

            const self = $( this )
            const coupon = self.parents( '.ctc-coupon' )
            const details = coupon.find( '.ctc-toggle-details' )

            details.slideToggle( 'fast' )
        }
    };

    /**
     * Initialization
     */
    $(function () {
        CTCCoupon.init();
    });

})(jQuery);