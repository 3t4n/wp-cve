(function($) {

    FALPermission = {
    	
        /**
         * Init
         */
        init: function()
        {
            this._bind();
        },

        /**
         * Bind events
         * @private
         * @return {void}
         */
        _bind: function()
        {
            $( document ).on('click', '.fal-allow-tracking', FALPermission.allow );
            $( document ).on('click', '.fal-not-allow-tracking, .fal-notice .notice-dismiss', FALPermission.not_allow );
        },

        /**
         * Allow
         * 
         * @param  {object} e
         */
        allow: function( event ) {
            event.preventDefault();

            $.ajax({
                url: fal_notice.ajax_url,
                type: 'POST',
                data: {
                    action: 'fal_allow_tracking',
                    security: fal_notice.nonce,
                },
                success: function( response ) {
                    if( response.success ) {
                        $('.fal-notice').remove();
                    }
                }
            });
        },

        /**
         * Not allow
         * 
         * @param event
         */
        not_allow: function( event ) {
            event.preventDefault();

            $.ajax({
                url: fal_notice.ajax_url,
                type: 'POST',
                data: {
                    action: 'fal_dont_allow_tracking',
                    security: fal_notice.nonce,
                },
                success: function( response ) {
                    if( response.success ) {
                        $('.fal-notice').remove();
                    }
                }
            });
        }

    };

    /**
     * Initialization
     */
    $(function() {
        FALPermission.init();
    });

})(jQuery);

