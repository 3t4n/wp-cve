(function ($) {
    'use strict';

    $(function () {
        $("#cpf_pix").mask("999.999.999-99");
    });

    if (document.getElementById("pix-order-id")) {

        const key = "c8db72fbdb177a2a4a8a";
        const cluster = "us2";

        const pusher = new Pusher(key, {
            cluster: cluster,
            encrypted: true
        });

        pusher.logToConsole = 0;
        const orderId = document.getElementById("pix-order-id").value;
        const channel = pusher.subscribe(`pix.notification.${orderId}`);
        const wrapper = document.getElementById("wrapper");

        if (window.localStorage[`${orderId}`]) {
            wrapper.innerHTML = "<h2>Pagamento confirmado!</h2>";
        }

        const copyTxt = document.getElementById("pix_emv");
        const copyBtn = document.getElementById("get-qrcode");
        const countdown = document.getElementById("countdown");

        $("#get-qrcode").on("click", function (event) {
            event.preventDefault()

            copyTxt.select();
            navigator.clipboard.writeText(copyTxt.value);

            copyBtn.classList.add('disabled');
            copyBtn.textContent = 'Código copiado com sucesso!';
        })

        if (! window.localStorage[`${orderId}`]) {

            const expirationDate = document.getElementById('expiration_date').value;
            let countDownDate = new Date(expirationDate).getTime();

            const timer = setInterval(function () {

                let now = new Date().getTime();
                let distance = countDownDate - now;
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdown.innerHTML = formatDate(`${hours}:${minutes}:${seconds}`);

                if (distance < 0) {
                    clearInterval(timer);
                    document.getElementById("wrapper").innerHTML = "<h2>Atingido o tempo limite para pagamento.</h2>";
                }
            }, 1000);
        }

        const wp_order_id = document.getElementById('wp_order_id').value;

        channel.bind('pix.order-paid', function (data) {

            window.localStorage[`${orderId}`] = true;
            wrapper.innerHTML = "<h2>Pagamento confirmado!</h2>";

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: woocommerce_params.ajax_url,
                data: {
                    'action': 'update_order_status',
                    'order_id': wp_order_id,
                },
                success: function (response) {
                    console.log(response);
                }
            });

        });
    }

    function formatDate(currentMinutes) {

        let arr = currentMinutes.split(':');
        return arr.map((minute) => {
            if (minute < 10) {
                minute = "0" + minute;
            }
            return minute;
        }).join(':');
    }
}(jQuery));