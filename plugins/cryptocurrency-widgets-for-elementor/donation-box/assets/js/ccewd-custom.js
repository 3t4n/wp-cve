class ccewd_custom_js extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                wraper: '.ccewd-container',
                ccewd_content: ' .ccewd-tabs-content',
                

            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $wraper: this.$element.find(selectors.wraper),
            $ccewd_content: this.$element.find(selectors.ccewd_content),
        };
    }

    bindEvents() {

        var wraper = this.elements.$wraper;
        var content = this.elements.$ccewd_content;
        var rand = this.elements.$wraper.attr('id');
        
        new ClipboardJS('.ccewd_btn');
                // Select all elements with class ccpw_btn
        var copyButtons = document.querySelectorAll('.ccewd_btn');
        if (copyButtons) {
            copyButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var inputToCopy = document.querySelector(button.getAttribute('data-clipboard-target'));
                if (inputToCopy) {
                inputToCopy.select();                
                button.innerHTML = 'Copied!';         
                 // Set a timeout to revert
                setTimeout(function () {
                    button.innerHTML = 'COPY';
                }, 800);     
                }
            });
            });
        }
        jQuery('#' + rand + ' li').click(function () {
            jQuery('#' + rand + " ul li").removeClass('current');
            jQuery('#' + rand + ' .ccewd-tabs-content').removeClass('current');
            var tab_id = jQuery(this).attr('data-tab');
          
            jQuery(this).addClass('current');
            jQuery('#'+rand+ ' #' + tab_id).addClass('current');
        })



    }
}



jQuery(window).on('elementor/frontend/init', () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(ccewd_custom_js, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/cryptocurrency-donation-box-widget.default', addHandler);

});