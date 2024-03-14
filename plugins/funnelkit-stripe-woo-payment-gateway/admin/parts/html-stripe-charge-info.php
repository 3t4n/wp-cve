<div class="stripe-info">
    <h3><?php esc_html_e( 'Transaction Data / Actions', 'funnelkit-stripe-woo-payment-gateway' ); ?></h3>
    <a href="#" class="do-stripe-transaction-interface"
       data-order="<?php echo esc_attr( $order->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get_transaction_data_admin' ) ); ?>"></a>
</div>
<script type="text/template" id="tmpl-wc-stripe-transaction-admin-view">
    <div class="wc-backbone-modal">
        <div class="wc-backbone-modal-content wc-stripe-box">
            <section class="wc-backbone-modal-main" role="main">
                <header class="wc-backbone-modal-header">
                    <h1>Transaction #{{ data.charge.id }}</h1>
                    <button
                        class="modal-close modal-close-link dashicons dashicons-no-alt">
                        <span class="screen-reader-text">Close Popup</span>
                    </button>
                </header>
                <article class="wc-stripe-box-container">
                    {{{data.html }}} <?php //phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation ?>
                </article>
                <footer>
                    <div class="inner">

                    </div>
                </footer>
            </section>
        </div>
    </div>
    <div class="wc-backbone-modal-backdrop modal-close"></div>
</script>
<script>
    (function ($) {


        function FkwcsOrder() {
            this.initialize();
        }

        FkwcsOrder.prototype.initialize = function () {

            /**
             * Setup all events
             */
            $(document.body).on('click', '.do-stripe-transaction-interface', this.getTransaction.bind(this));
            $(document.body).on('change', '[name="capture_amount"]', this.validate_capture_amount.bind(this));

            /**
             * Inside popup events
             */
            $(document.body).on('click', '.do-api-capture', this.performApiCapture.bind(this));
            $(document.body).on('click', '.do-api-cancel', this.performApiVoid.bind(this));


        };

        /**
         *
         */
        FkwcsOrder.prototype.performApiCapture = function (e) {
            e.preventDefault();
            var $modal = $('.wc-stripe-box');
            var $amount = $('[name="capture_amount"]');
            $modal.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.when($.ajax({
                method: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {

                    action: 'capture_charge',
                    order_id: woocommerce_admin_meta_boxes.post_id,
                    amount: $amount.val(),
                    _wpnonce: $amount.data('nonce'),
                },
            }).done(function (response) {
                if (!response.code) {
                    window.location.reload();
                } else {
                    $modal.unblock();
                    window.alert(response.message);
                }
            }.bind(this))).fail(function (jqXHR, textStatus, errorThrown) {
                $modal.unblock();
                window.alert(errorThrown);
            }.bind(this));
        };


        /**
         *
         */
        FkwcsOrder.prototype.performApiVoid = function (e) {
            e.preventDefault();
            let $modal = $('.wc-stripe-box');
            let $amount = $('[name="capture_amount"]');
            $modal.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.when($.ajax({
                method: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {

                    action: 'void_charge',
                    order_id: woocommerce_admin_meta_boxes.post_id,
                    amount: $amount.val(),
                    _wpnonce: $amount.data('nonce-void'),
                },
            }).done(function (response) {
                if (!response.code) {
                    window.location.reload();
                } else {
                    $modal.unblock();
                    window.alert(response.message);
                }
            }.bind(this))).fail(function (jqXHR, textStatus, errorThrown) {
                $modal.unblock();
                window.alert(errorThrown);
            }.bind(this));
        };


        FkwcsOrder.prototype.validate_capture_amount = function (e) {
            let value = $(e.currentTarget).val();
            let jqmodal = $('.wc-backbone-modal .wc-backbone-modal-content article');
            value = parseFloat(value);
            /**
             * Check if the value is numeric
             */
            if (!Number.isNaN(value)) {

                var data = $('.do-stripe-transaction-interface').data('charge');
                if (data && value < parseFloat(data.order_total)) {
                    if (typeof woocommerce_admin !== 'undefined') {
                        woocommerce_admin.capture_notice = $(e.currentTarget).data('error');
                        $(document.body).triggerHandler('wc_add_error_tip', [$(e.currentTarget), 'capture_notice']);
                        jqmodal.get(0).scrollTo(0, jqmodal.scrollHeight);

                    }
                } else {
                    $(document.body).triggerHandler('wc_remove_error_tip', [$(e.currentTarget), 'capture_notice']);
                    jqmodal.get(0).scrollTo(0, jqmodal.scrollHeight);

                }
            }
        };
        /**
         * Fetch the charge view data and render the modal.
         */
        FkwcsOrder.prototype.getTransaction = function (e) {
            e.preventDefault();
            var $icon = $(e.currentTarget);
            if (!$icon.data('charge')) {
                $icon.addClass('disabled');
                $.when($.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: ajaxurl,
                    data: {
                        action: 'get_transaction_data_admin',
                        order_id: $icon.data('order'),
                        _wpnonce: $icon.data('nonce')
                    }
                })).done(function (response) {
                    if (!response.code) {
                        $icon.data('charge', response.data);
                        $icon.removeClass('disabled');
                        $icon.WCBackboneModal({
                            template: 'wc-stripe-transaction-admin-view',
                            variable: response.data
                        });
                    } else {
                        window.alert(response.message);
                    }
                }.bind(this)).fail(function (jqXHR, textStatus, errorThrown) {
                    $icon.removeClass('disabled');
                    window.alert(errorThrown);
                }.bind(this))
            } else {
                $icon.WCBackboneModal({
                    template: 'wc-stripe-transaction-admin-view',
                    variable: $icon.data('charge')
                });
            }
        };

        /**
         * Initialize
         */
        new FkwcsOrder();

    }(jQuery));


</script>
<style>


    #order_data .order_data_column .stripe-info h3 {
        margin-bottom: 5px;
    }

    #order_data .order_data_column .stripe-info a {
        text-decoration: none;
        border: 2px solid transparent;
        border-radius: 4px;
        font-size: 18px;
    }

    #order_data .order_data_column .stripe-info a:before {
        font-family: WooCommerce;
        content: "\e010";
    }

    #order_data .order_data_column .stripe-info .do-transaction-view:hover {
        border: 2px solid #00a0d2;
    }

    .wc-stripe-box .data-container {
        display: flex;
        flex-wrap: wrap;
    }

    .wc-stripe-box h2 {
        margin: 0 0 1em 0;
    }

    .wc-stripe-box .modal-actions {
        margin-bottom: 0.5em;
    }

    .wc-stripe-box .data-container .column-6 {
        width: 50%;
        margin-bottom: 0.5em;
    }

    .wc-stripe-box .data-container .column-6 label {
        display: inline-block;
        font-weight: 600;
        vertical-align: bottom;
    }

    .wc-stripe-box .data-container .metadata {
        margin-bottom: 0.5em;
    }

    #order_data .order_data_column .stripe-info a.disabled:before {
        content: '';
        background: url(<?php echo esc_url(plugins_url('assets/images/wpspin.gif', WC_PLUGIN_FILE))?>) no-repeat center top;
        padding: 0 10px;
    }
</style>
