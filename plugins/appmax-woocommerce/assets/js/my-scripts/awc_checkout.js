(function( $ ) {
    'use strict';

    $( function() {

        let formSubmit = false;

        $( 'form.checkout' ).on( 'click', '#place_order', function() {
            return openModal();
        });

        $( 'form.checkout' ).on( 'checkout_place_order', {
            preserveCheckoutSubmitValue: true
        }, isCheckoutInvalid );

        /**
         * Check if checkout is valid.
         *
         * @param {Object} evt
         * @return {Bool}
         */
        function isCheckoutInvalid( evt ) {

            if ( formSubmit ) {
                if ( 'undefined' !== typeof evt && 'undefined' !== typeof evt.data ) {
                    if ( 'undefined' !== typeof evt.data.preserveCheckoutSubmitValue && ! evt.data.preserveCheckoutSubmitValue ) {
                        formSubmit = false;
                    }
                }
                return true;
            }

            if ( ! $( '#payment_method_appmax-credit-card' ).is( ':checked' ) ) {
                return true;
            }

            let requiredInputs = $( '.woocommerce-billing-fields .validate-required' );

            if ( requiredInputs.length ) {
                let requiredError = false;

                requiredInputs.each( function() {
                    if ( '' === $( this ).find( 'input.input-text, select' ).not( $( '#account_password, #account_username' ) ).val() ) {
                        requiredError = true;
                    }
                });

                if ( requiredError ) {
                    return true;
                }
            }

            return false;
        }

        function openModal() {

            if ( isCheckoutInvalid() ) {
                return true;
            }

            let form = $( 'form.checkout, form#order_review' );

            $( '#myModal' ).css( 'display', 'block' );

            $(' #card_number' ).mask( '9999 9999 9999 9999' );
            $(' #card_cpf' ).mask( '999.999.999-99' );

            $( '.close' ).on( 'click', function() {
                closeModal();
            });

            $( 'button[name="awc_confirm_payment"]' ).on( 'click', function() {

                form.addClass( 'processing' );

                if (! validateFormCreditCard()) {
                    return false;
                }

                $( '#myModal' ).css( 'display', 'none' );

                form.removeClass( 'processing' );

                formSubmit = true;

                form.submit();
            });

            return false;
        }

        function validateFormCreditCard() {

            let card_number = $('input[name="card_number"]');
            let card_name = $('input[name="card_name"]');
            let card_cpf = $('input[name="card_cpf"]');
            let card_month = $('select[name="card_month"]');
            let card_year = $('select[name="card_year"]');
            let card_security_code = $('input[name="card_security_code"]');
            let installments = $('select[name="installments"]')

            $( '.message-error' ).remove();
            $( '.awc-input-validate' ).css( 'border', '0px' );

            let errorHtml = '';
            errorHtml += '<div class="message-error" style="color: #F30"></div>';

            if (! card_number.val() ) {
                card_number.css( 'border', '1px solid #F30' ).after(errorHtml);
                $( '.message-error' ).text( 'O campo Número do Cartão é obrigatório.' );
                return;
            }

            if (! card_name.val() ) {
                card_name.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'O campo Nome do Titular é obrigatório.' );
                return;
            }

            if (! card_cpf.val() ) {
                card_cpf.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'O campo CPF do Titular é obrigatório.' );
                return;
            }

            if (! validateCPF( card_cpf.val() )) {
                card_cpf.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'CPF do Titular inválido!' );
                return;
            }

            if (! card_month.val() ) {
                card_month.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'Selecione o mês de expiração do cartão.' );
                return;
            }

            if (! card_security_code.val() ) {
                card_security_code.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'O campo Cód. de Segurança é obrigatório.' );
                return;
            }

            if (! isNumber( card_security_code.val() ) ) {
                card_security_code.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'Somente números!' );
                return;
            }

            if (! card_year.val() ) {
                card_year.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'Selecione o ano de expiração do cartão.' );
                return;
            }

            if (! installments.val() ) {
                installments.css( 'border', '1px solid #F30' ).after( errorHtml );
                $( '.message-error' ).text( 'Selecione a parcela.' );
                return;
            }

            return true;
        }

        function validateCPF(strCpf) {

            let value = strCpf.replace(/([~!@#$%^&*()_+=`{}\[\]\-|\\:;'<>,.\/? ])+/g, "");

            if (value.length !== 11) {
                return false;
            }

            let sum = 0, firstCN, secondCN, checkResult, i;

            firstCN = parseInt(value.substring(9, 10), 10);
            secondCN = parseInt(value.substring(10, 11), 10);

            checkResult = function(sum, cn) {
                let result = (sum * 10) % 11;
                if ((result === 10) || (result === 11)) {
                    result = 0;
                }
                return (result === cn);
            };

            if (value === "" ||
                value === "00000000000" ||
                value === "11111111111" ||
                value === "22222222222" ||
                value === "33333333333" ||
                value === "44444444444" ||
                value === "55555555555" ||
                value === "66666666666" ||
                value === "77777777777" ||
                value === "88888888888" ||
                value === "99999999999"
            ) {
                return false;
            }

            for ( i = 1; i <= 9; i++ ) {
                sum = sum + parseInt(value.substring(i - 1, i), 10) * (11 - i);
            }

            if ( checkResult(sum, firstCN) ) {
                sum = 0;
                for ( i = 1; i <= 10; i++ ) {
                    sum = sum + parseInt(value.substring(i - 1, i), 10) * (12 - i);
                }
                return checkResult(sum, secondCN);
            }

            return false;
        }

        function isNumber(string) {
            return string.match(/^[0-9]+$/);
        }

        function closeModal() {
            $( '.message-error' ).remove();
            $( '#myModal' ).css( 'display', 'none' );
        }
    });

}( jQuery ));