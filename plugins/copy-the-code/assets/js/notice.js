(function($) {

    CopyTheCodeNotice = {
    	
        /**
         * Init
         */
        init: function()
        {
            this._bind();
        },

        _bind: function()
        {
            $( document ).on('click', '.copy-the-code-allow-tracking', CopyTheCodeNotice.allow );
            $( document ).on('click', '.copy-the-code-not-allow-tracking, .copy-the-code-notice .notice-dismiss', CopyTheCodeNotice.not_allow );
        },

        /**
         * Copy to Clipboard
         */
        allow: function( event ) {
            event.preventDefault();

            $.ajax({
                url: copy_the_code_notice.ajax_url,
                type: 'POST',
                data: {
                    action: 'copy_the_code_allow_tracking',
                    security: copy_the_code_notice.nonce,
                },
                success: function( response ) {
                    if( response.success ) {
                        $('.copy-the-code-notice').remove();
                    }
                }
            });
        },

        /**
         * Copy to Clipboard
         */
        not_allow: function( event ) {
            event.preventDefault();

            $.ajax({
                url: copy_the_code_notice.ajax_url,
                type: 'POST',
                data: {
                    action: 'copy_the_code_dont_allow_tracking',
                    security: copy_the_code_notice.nonce,
                },
                success: function( response ) {
                    if( response.success ) {
                        $('.copy-the-code-notice').remove();
                    }
                }
            });
        }

    };

    /**
     * Initialization
     */
    $(function() {
        CopyTheCodeNotice.init();
    });

})(jQuery);

