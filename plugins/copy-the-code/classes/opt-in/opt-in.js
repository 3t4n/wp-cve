(function($) {

    CopyTheCodeOptIn = {
    	
        /**
         * Init
         */
        init: function()
        {
            this._bind();
        },

        _bind: function()
        {
            $( document ).on('click', '.copy-the-code-opt-in-notice-welcome .notice-dismiss', CopyTheCodeOptIn.welcome );
        },

        /**
         * Welcome
         */
        welcome: function( event ) {
            event.preventDefault();

            $.ajax({
                url: copy_the_code_opt_in.ajax_url,
                type: 'POST',
                data: {
                    action: 'copy_the_code_opt_in',
                    opt_in: 'welcome',
                    security: copy_the_code_opt_in.security,
                },
                success: function( response ) {
                    if( response.success ) {
                        $('.copy-the-code-opt-in-notice-welcome').remove();
                    }
                }
            });
        }

    };

    /**
     * Initialization
     */
    $(function() {
        CopyTheCodeOptIn.init();
    });

})(jQuery);

