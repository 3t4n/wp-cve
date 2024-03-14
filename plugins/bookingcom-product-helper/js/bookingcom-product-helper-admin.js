(function (w, $) {
    let $el_helpers_list = $(".js-bcom-widget-helpers-list");
    let $el_words_counter = $(".js-word-counter-limit");
    let $el_description_input = $(".js-short-description-input");

    const copyToClipboard = str => {
        const el = document.createElement('textarea');  // Create a <textarea> element

        el.value = str;                                 // Set its value to the string that you want copied
        el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
        el.style.position = 'absolute';
        el.style.left = '-9999px';                      // Move outside the screen to make it invisible
        document.body.appendChild(el);                  // Append the <textarea> element to the HTML document

        const selected =
            document.getSelection().rangeCount > 0        // Check if there is any content selected previously
                ? document.getSelection().getRangeAt(0)     // Store selection if found
                : false;                                    // Mark as false to know no selection existed before
        el.select();                                    // Select the <textarea> content
        document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
        document.body.removeChild(el);                  // Remove the <textarea> element

        if (selected) {                                 // If a selection existed before copying
            document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
            document.getSelection().addRange(selected);   // Restore the original selection
        }
    };

    if ($el_helpers_list.length) {
        $el_helpers_list
            .on("click", ".js-remove_widget_helper", function (e) {
                e.preventDefault();
                if (confirm($(e.target).data("bcom-prompt-delete-text")) && e.target instanceof HTMLAnchorElement) {
                    w.location.href = e.target.getAttribute("href");
                }
            })
            .on("click", ".js-copy-to-clipboard", function (e) {
                e.preventDefault();
                let clipboard_code = $(e.target).siblings(".js-copy-to-clipboard-code").text().trim();
                copyToClipboard(clipboard_code);

                alert($(e.target).data("bcom-alert-clipboard-text"));
            });
    }

    if ($el_words_counter.length && $el_description_input.length) {
        const SHORT_DESCRIPTION_LIMIT = 100;

        /**
         * Converting values into string for output.
         *
         * @param currentValue
         * @param maxValue
         * @returns {string}
         */
        function prepareOutput(currentValue, maxValue) {
            return [currentValue, maxValue].join("/")
        }

        /**
         * Check on changing short description input field.
         *
         * @param {Event} e Event handler
         */
        function descriptionChangeEvent(e) {
            const currentObj = $(e.target);
            const currentWordsAmount = currentObj.val().length;

            if (currentWordsAmount <= SHORT_DESCRIPTION_LIMIT) {
                $el_words_counter.html(
                    prepareOutput(currentWordsAmount, SHORT_DESCRIPTION_LIMIT)
                );
            } else {
                console.log(currentObj.val());
                console.log(currentObj.val().substr(0, SHORT_DESCRIPTION_LIMIT));
                currentObj.val(
                    currentObj.val().substr(0, SHORT_DESCRIPTION_LIMIT)
                );
            }
        }

        $el_words_counter.html(
            prepareOutput($el_description_input.val().length, SHORT_DESCRIPTION_LIMIT)
        );

        $el_description_input
            .on("paste", descriptionChangeEvent)
            .on("input", descriptionChangeEvent);
    }

})(window, jQuery);
