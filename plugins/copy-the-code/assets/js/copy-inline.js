(function ($) {
    const CTCInline = {

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
            $( document ).on( 'click', '.ctc-inline-copy', this.doCopy );
        },

        /**
         * Do Copy to Clipboard
         */
        doCopy: function (event) {
            event.preventDefault();

            const self = $( this )
            let text = self.find( '.ctc-inline-copy-textarea' ).val() || ''

            // Remove first and last new line.
            text = $.trim(text);

            // Copy to clipboard.
            CTC.copy(text);

            // Copied!
            self.addClass('copied');
            setTimeout(function () {
                self.removeClass('copied');
            }, 1000);
        }
    };

    /**
     * Initialization
     */
    $(function () {
        CTCInline.init();
    });

})(jQuery);