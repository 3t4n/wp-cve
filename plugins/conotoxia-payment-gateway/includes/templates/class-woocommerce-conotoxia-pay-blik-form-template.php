<?php

class WC_Gateway_Conotoxia_Pay_Blik_Form_Template
{
    /**
     * @return string
     */
    public static function get(): string
    {
        $enter_the_code = esc_html(__('Enter the code:', CONOTOXIA_PAY));
        $by_paying_you_accept_the = esc_html(__('By paying you accept the', CONOTOXIA_PAY));
        $single_payment_transaction_terms_and_conditions_url = esc_url(
            __(
                'https://conotoxia.com/files/regulamin/Single_payment_transaction_terms_and_conditions.pdf',
                CONOTOXIA_PAY
            )
        );
        $single_payment_transaction_terms_and_conditions = esc_html(
            __('Single Payment Transaction Terms and Conditions.', CONOTOXIA_PAY)
        );

        return <<<HTML
            <style>
                #cx-blik-code-label {
                   font-size: 16px;
                }
                #js-cx-blik-code-input {
                   height: 2em;
                   width: 7em;
                   margin: 0;
                   padding: 0;
                   font-size: 16px;
                   letter-spacing:1px;
                   text-align: center;
                   border: 1px solid #D3DCE3;
                   border-radius: 4px;
                   caret-color: transparent;
                   outline: none;
                }
                #js-cx-blik-code-input:focus {
                   border-color: #0B49DB;
                }
                #cx-blik-code-terms-and-conditions {
                   margin-top: 1rem;
                   font-size: 16px;
                }
            </style>
            <div id="cx-blik-code-label">$enter_the_code</div>
            <input type='text'
                   id='js-cx-blik-code-input'
                   name='cx-blik-code'
                   pattern='[0-9]*'
                   inputmode='numeric'
                   value='___ ___'>
            <div id='cx-blik-code-terms-and-conditions'>
                $by_paying_you_accept_the
                <a href='$single_payment_transaction_terms_and_conditions_url'
                   target='_blank'
                   rel='noopener noreferrer'>
                    $single_payment_transaction_terms_and_conditions
                </a>
            </div>
            <input id='cx-user-screen-resolution' name='cx-user-screen-resolution' type='hidden'>
            <input id='cx-user-agent' name='cx-user-agent' type='hidden'>
            <script>
                function handleBlikCodeInput(event) {
                    const blikCodeInput = document.getElementById('js-cx-blik-code-input');
                    const oldValue = blikCodeInput.getAttribute('oldvalue');
                    const newValue = event.target.value;
                    const blikCode = event.inputType === 'deleteContentBackward' && oldValue.slice(-1) === '_'
                                     ? resolveBlikCode(oldValue).slice(0, -1)
                                     : resolveBlikCode(newValue);
                    blikCodeInput.value = formatBlikCode(blikCode);
                    blikCodeInput.setAttribute('oldvalue', blikCodeInput.value);
                }
                function resolveBlikCode(value) {
                    let valueArray = value.split('');
                    valueArray = valueArray.filter(element => /^\d$/.test(element));
                    if (valueArray.length > 6) {
                        valueArray = valueArray.slice(0, 6);
                    }
                    return valueArray.join('');
                }
                function formatBlikCode(blikCode) {
                    if (blikCode.length > 3) {
                        blikCode = blikCode.slice(0, 3) + ' ' + blikCode.slice(3);
                    }
                    for (let index = blikCode.length; index < 7; index++) {
                        blikCode += index === 3 ? ' ' : '_';
                    }
                    return blikCode;
                }
                blikCodeInput = document.getElementById('js-cx-blik-code-input');
                blikCodeInput.addEventListener(
                    'focus',
                    () => blikCodeInput.setAttribute('oldvalue', blikCodeInput.value)
                );
                blikCodeInput.addEventListener('input', event => handleBlikCodeInput(event));
                document.getElementById('cx-user-screen-resolution').value = screen.width + 'x' + screen.height;
                document.getElementById('cx-user-agent').value = navigator.userAgent;
            </script>
HTML;
    }
}
