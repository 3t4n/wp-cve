function securepay_bank_select( $, path_icon, path_checkout, path_time, version ) {
    $( document )
        .ready(
            function() {
                function formatBank( bank ) {
                    if ( !bank.id ) {
                        return bank.text;
                    }
                    if ( '' === bank.element.value ) {
                        return '';
                    }
                    var img = path_icon + bank.element.value.toLowerCase() + '.png';
                    return $( '<div class="securepay-bnklogo"><div><img src="' + img + '?v=' + version + '"></div><div>' + bank.text + '</div></div>' );
                };
                $( "#buyer_bank_code" )
                    .select2( {
                        templateResult: formatBank,
                        width: "100%"
                    } );
                $( "#buyer_bank_code" )
                    .on(
                        "select2:select",
                        function( e ) {
                            var bank_id = e.params.data.id;
                            $.post(
                                path_checkout, {
                                    securepaybuyerbank: bank_id,
                                    time: path_time
                                }
                            );
                        }
                    );
            }
        );
};