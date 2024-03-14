<div class="bm-redirect-overlay">
    <h2>
		<?php
		_e( "In a while you'll be redirected to the payment page screen",
			"bm-woocommerce" ); ?>
    </h2>

    <span><?php
		_e( "Time to redirect:",
			"bm-woocommerce" ); ?>&nbsp;<span
                class="bm-redirect-counter"></span></span>


</div>

<script>

    jQuery(document).ready(function () {
        var counter = 5;
        var interval = setInterval(function () {

            if (counter >= 0) {
                jQuery('.bm-redirect-counter').text(counter + 's');
            }

            if (counter === 0) {
                clearInterval(interval);
                document.getElementById('paymentForm').submit();
            }

            counter--;

        }, 1000);
    });

</script>