class WPHindi_Deactivation_Feedback {
    constructor (WPDeactivationButton, popUpFormID) {
        this.deactivationButton = WPDeactivationButton;
        this.popUpFormID = `#${popUpFormID}`;
        this.popUpForm   = document.querySelector(this.popUpFormID) ;
        this.pluginDeactivationURL = this.getDeactivationURL();
        this.prepareForm();
        this.assignPopUp();
        this.bindAjaxToForm();
    }
    /**
     * Gets WordPress plugin deactivation url
     * @return {string}
     */
    getDeactivationURL() {
        return this.deactivationButton.getAttribute('href');
    }

    /**
     * Assigns PopUp to deactivation button
     * @returns {void}
     */
    assignPopUp() {
        this.deactivationButton.addEventListener('click', this.displayPopUp.bind(this));
    }

    displayPopUp(event) {
        event.preventDefault();
        jQuery(this.popUpFormID).modal();
    }

    bindAjaxToForm() {
        const $this = this;
        jQuery(this.popUpFormID).submit((event) => {
            event.preventDefault();
            $this.sendFeedback(event.target);
        });
    }

    sendFeedback(HTMLFormElement) {
        jQuery.ajax({
            url: this.getFeedbackURL(),
            method: 'post',
            data: jQuery(HTMLFormElement).serialize(),
            success: () => {
                WPHindiNotices('Feedback submitted. Deactivating WPHindi', 'notice-success');
                this.deactivateWPHindi();
            },
            error: (xhr) => {
                /**
                 * Output errors
                 */
                if (typeof xhr.responseJSON.errors !== "undefined") {
                    const errors = xhr.responseJSON.errors;
                    errors.map((errorMsg) => {
                        WPHindiNotices(errorMsg, 'notice-error');
                    })
                } else {
                    WPHindiNotices('Something went wrong.');
                }
            }
        });
    }
    
    /**
     * Returns URL for feedback submission
     * @returns {string}
     */
    getFeedbackURL(){
        return jQuery(this.popUpFormID).attr('action');
    }

    /**
     * Sets url for deactivation form 
     * @param {string} skipElementID 
     * @returns {void}
     */
    setSkipURL(skipElementID){
        const skipButton = document.querySelector(`#${skipElementID}`);
        skipButton.setAttribute('href', this.getDeactivationURL());
    }

    /**
     * Deactivates WPHindi
     * @returns void
     */
    deactivateWPHindi(){
        const deactivationURL = this.getDeactivationURL();
        window.location.href = deactivationURL;
    }

    /**
     * Returns site URL
     * @returns {string}
     */
    getSiteURL(){
        return window.location.href.split('wp-admin')[0];
    }

    /**
     * Appends required data to form input
     */
    prepareForm(){
        // Set site URL
        this.popUpForm.siteURL.value = this.getSiteURL();
    }
}
/**
 * Enable Deactivation form;
 */
const deactivationButton = jQuery('[data-slug="wphindi"] a:contains(Deactivate)').get(0);
const WPHindiDeactivation = new WPHindi_Deactivation_Feedback(deactivationButton, 'wphindi-deactivate-form');
WPHindiDeactivation.setSkipURL('wphindi-skip-deactivate');
/**
 * Make notices for feedback form
 * @param {string} errorMsg 
 * @param {string} errorClass 
 */
function WPHindiNotices(errorMsg, errorClass) {
    const notice = document.createElement('div');
    /*
    * Set Classes
    */
    notice.classList.add('notice');
    notice.classList.add('is-dismissible');
    notice.classList.add(errorClass);

    /*
    * Add text to notice
    */
    const msg = document.createElement('p');
    msg.innerText = errorMsg;

    // Append text to notice
    notice.append(msg);

    /*
    * Make notice dismissible
    */
    const dismissButton = document.createElement('button');
    // Disable button actions
    dismissButton.setAttribute('type', 'button');
    // Add class
    dismissButton.classList.add('notice-dismiss');

    /*
    * Enable dismiss button
    */
    dismissButton.addEventListener('click', (event) => {
        event.target.parentElement.remove();
    });

    // Append dissmis button to notice
    notice.append(dismissButton)

    // Append notice to display area
    const noticeArea = document.querySelector('#wphindi-errors-area');
    noticeArea.append(notice);

}