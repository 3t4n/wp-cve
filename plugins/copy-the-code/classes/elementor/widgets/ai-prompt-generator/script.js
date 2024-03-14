(function ($) {
    const CTCAIPromptGenerator = {

        /**
         * Init
         */
        init: function () {
            this._bind();
            this._generate();
        },

        /**
         * Binds events
         */
        _bind: function () {
            $( document ).on( 'click', '.ctc-ai-generator-button', this.doCopy );
            $( document ).on( 'input', '.ctc-ai-prompt-generator input', this.doChange );
        },

        /**
         * Do Change
         */
        doChange: function (event) {
            event.preventDefault();

            CTCAIPromptGenerator._generate();
        },

        /**
         * Do Copy to Clipboard
         */
        doCopy: function (event) {
            event.preventDefault();

            const self = $( this )
            const parent = self.parents( '.ctc-ai-prompt-generator' )
            const textarea = parent.find( '.ctc-ai-prompt-generator-textarea' )
            let text = textarea.val() || ''

            // Remove first and last new line.
            text = $.trim(text);

            // Copy to clipboard.
            CTC.copy(text);

            // Copied!
            parent.addClass('copied');
            setTimeout(function () {
                parent.removeClass('copied');
            }, 1000);
        },

        /**
         * Generate
         */
        _generate: function () {
            const blocks = $( '.ctc-ai-prompt-generator' )

            blocks.each( function() {
                const fields = $( this ).find( '.ctc-block-field' )
                const textarea = $( this ).find( '.ctc-ai-prompt-generator-textarea' )
                let markup = ''

                fields.each( function() {
                    const label = $( this ).find( '.ctc-block-field-label' ).text()
                    const value = $( this ).find( 'input' ).val() || ''

                    if ( ! value ) {
                        return
                    }

                    markup += label + ': ' + value + '\n'
                })

                textarea.val( markup )
            })
        }

    };

    /**
     * Initialization
     */
    $(function () {
        CTCAIPromptGenerator.init();
    });

})(jQuery);