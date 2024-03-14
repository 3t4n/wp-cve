(function ($) {
    class WFACP_Smart_Button_JS {
        constructor() {
            this.smart_button_id = '#wfacp_smart_buttons';
            this.loading_gif = 'wfacp-dynamic-checkout-loading';
            this.button_displayed = false;
            this.available_buttons = {};
            this.wcEvents();

        }

        wcEvents() {

            if (document.readyState !== 'loading') {
                this.DOMLoaded();
            } else {
                document.addEventListener('DOMContentLoaded', () => {
                    this.DOMLoaded();
                });
            }
        }

        isMobilePhone() {
            return ('yes' === wfacp_frontend.is_mobile_phone);
        }

        DOMLoaded() {
            $(document.body).on('updated_checkout', this.handleCheckout.bind(this));
            $(document.body).on('fkwcs_smart_buttons_showed', this.handleWfgsGateway.bind(this));
            this.checkButtons();
        }

        updateWrapperClass() {
            let btn_counts = Object.keys(this.available_buttons).length;
            if (btn_counts > 0) {
                $('.wfacp_smart_button_outer_buttons').attr('count', btn_counts);
            }
        }


        handleCheckout() {
            this.updateWrapperClass();
            this.show_hide_smart_button();

        }

        /**
         * Handle Funnel Kit Gateway Smart buttons
         */
        handleWfgsGateway() {
            let wfgs_stripe = $('#wfacp_smart_button_fkwcs_gpay_apay');

            if (wfgs_stripe.length === 0) {
                return;
            }
            wfgs_stripe.show();
            this.showButton('#wfacp_smart_button_fkwcs_gpay_apay');
            this.showButtons();
            let fkwcs_buttons = $('.fkwcs_stripe_smart_button_wrapper');
            if ($('.wfacp_smart_button_container').length === 1 && fkwcs_buttons.length === 1 && false === fkwcs_buttons.is(":visible")) {
                this.hideButtons();
            }
        }

        show_hide_smart_button() {
            let hideTimeout = 200;

            if ('yes' === wfacp_frontend.is_desktop && 'yes' === wfacp_frontend.stripe_smart_show_on_desktop) {
                hideTimeout = wfacp_frontend.smart_button_hide_timeout
            } else if ('no' === wfacp_frontend.is_desktop) {
                hideTimeout = wfacp_frontend.smart_button_hide_timeout_m
            }
            if (true == this.button_displayed) {
                hideTimeout = this.isMobilePhone() ? 500 : 100;
            }

            setTimeout(() => {
                if (true === this.button_displayed) {
                    /**
                     * Handle for Funnelkit Stripe
                     */
                    let fkwcs_buttons = $('.fkwcs_stripe_smart_button_wrapper');
                    if ($('.wfacp_smart_button_container').length === 1 && fkwcs_buttons.length === 1 && false === fkwcs_buttons.is(":visible")) {
                        this.hideButtons();
                    } else {
                        this.showButtons();
                    }

                } else {
                    this.hideButtons();
                }
            }, hideTimeout);

        }


        getAvailableButtons() {
            return {
                "#wfacp_smart_button_stripe_gpay_apay": "#wfacp_smart_button_stripe_gpay_apay",
                "#wfacp_smart_button_wc_payment_gpay_apay #wcpay-payment-request-button": "#wfacp_smart_button_wc_payment_gpay_apay",
                "#wfacp_smart_button_woo_braintree_braintree_paypal .wc_braintree_banner_gateway_braintree_paypal": "#wfacp_smart_button_woo_braintree_braintree_paypal",
                "#wfacp_smart_button_woo_braintree_braintree_googlepay .wc_braintree_banner_gateway_braintree_googlepay": "#wfacp_smart_button_woo_braintree_braintree_googlepay",
                "#wfacp_smart_button_woo_braintree_braintree_applepay .wc_braintree_banner_gateway_braintree_applepay": "#wfacp_smart_button_woo_braintree_braintree_applepay",
                "#wfacp_smart_button_paymentplugins_wc_stripe .banner_payment_method_stripe_googlepay": "#wfacp_smart_button_paymentplugins_wc_stripe",
                "#wfacp_smart_button_paymentplugins_wc_stripe .banner_payment_method_stripe_applepay": "#wfacp_smart_button_paymentplugins_wc_stripe",
                "#wfacp_smart_button_paymentplugins_wc_stripe .banner_payment_method_stripe_payment_request": "#wfacp_smart_button_paymentplugins_wc_stripe",
                "#wfacp_smart_button_paypal_express #checkout_paypal_message .angelleye_smart_button_checkout_top": '#wfacp_smart_button_paypal_express',
                "#wfacp_smart_button_angelleye_ppcp #angelleye_ppcp_checkout_top": '#wfacp_smart_button_angelleye_ppcp',
                '#wfacp_smart_button_wc_braintree .sv-wc-apple-pay-button': '#wfacp_smart_button_wc_braintree'
            };
        }

        noConflictButton() {
            return ['#wfacp_smart_button_amazon_pay #pay_with_amazon', '#wfacp_smart_button_paymentplugins_wc_ppcp', '#wfacp_smart_button_wffn_fake_cc', '#wfacp_smart_button_wffn_fake_paypal'];
        }

        checkButtons() {

            let noConflictButtons = this.noConflictButton();
            for (let c = 0; c < noConflictButtons.length; c++) {
                this.findButtonElements(noConflictButtons[c]);
            }


            let buttons = this.getAvailableButtons();
            for (let i in buttons) {
                let parent = buttons[i];
                this.domInsert(parent, i);
            }
        }

        findButtonElements(id) {
            let container = document.querySelector(id);
            if (null != container) {
                this.showButton(id);
                this.showButtonOnMobile();
            }
        }


        domInsert(parent, selector) {
            let container = document.querySelector(selector);
            if (null == container) {
                return;
            }
            container.addEventListener('DOMNodeInserted', () => {
                this.showButton(parent);
                this.showButtonOnMobile();
            });
        }

        showButtonOnMobile() {
            if (this.isMobilePhone()) {
                this.showButtons();
            }
        }

        showButton(button_id) {
            let p = document.querySelector(button_id);
            if (null != p) {
                this.available_buttons[button_id] = 'yes';
                p.style.display = 'block';
                this.updateWrapperClass();
                this.button_displayed = true;
            }
        }

        showButtons() {


            let smart_buttons = document.querySelector(this.smart_button_id);

            if (null == smart_buttons) {
                return;
            }
            smart_buttons.style.display = 'block';
            smart_buttons.classList.remove(this.loading_gif);
            this.button_displayed = true;


            this.handleAmazonButton(smart_buttons);

        }

        handleAmazonButton() {
            let amazon_id = document.querySelector('#pay_with_amazon .amazonpay-button-inner-image');
            if (null == amazon_id) {
                return;
            }
            document.querySelector('#pay_with_amazon').classList.add("wfacp-amazon-active-image");

        }

        hideButtons() {
            let smart_buttons = document.querySelector(this.smart_button_id);
            if (null == smart_buttons) {
                return;
            }
            smart_buttons.style.display = 'none';
            this.button_displayed = false;
        }
    }

    new WFACP_Smart_Button_JS();
})(jQuery);