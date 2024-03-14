jQuery(function ($) {

    //generate the qr code.
    if ($('.return-code-qr b').show()) {
        $('#qr-code').qrcode({width: 128,height: 128,text: $('.return-code-qr b').text()});
    }
});