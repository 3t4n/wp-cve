jQuery(function ($) {
  var squad_submit = false;

  //init widget on page load
  wcSquadFormHandler();

  jQuery("#squad-payment-button").click(function () {
    window.location.reload();
    // return wcSquadFormHandler();
  });

  function wcSquadFormHandler() {
    $("#wc-squad-form").hide();

    if (squad_submit) {
      squad_submit = false;
      return true;
    }

    var $form = $("form#payment-form, form#order_review"),
      public_key = wc_squad_params.public_key,
      meta_name = wc_squad_params.meta_name,
      payment_options = wc_squad_params.payment_options,
      currency = wc_squad_params.currency,
      txnref = wc_squad_params.txnref,
      order_id = wc_squad_params.order_id,
      email = wc_squad_params.email,
      meta_products = wc_squad_params.meta_products;

    if (wc_squad_params.bank_channel) {
      bank = "true";
    }

    if (wc_squad_params.card_channel) {
      card = "true";
    }

    if (wc_squad_params.subaccount_code) {
      subaccount_code = wc_squad_params.subaccount_code;
    }

    if (wc_squad_params.charges_account) {
      charges_account = wc_squad_params.charges_account;
    }

    if (wc_squad_params.transaction_charges) {
      transaction_charges = Number(wc_squad_params.transaction_charges);
    }

    var amount = Number(wc_squad_params.amount);

    var successCallback = function (response) {
      let res;
      try {
        res = JSON.parse(response);
      } catch (error) {
        res = response;
      }
      const responseTransactionRef = res.transaction_ref;

      $form.append(
        `<input type="hidden" 
				class="squad_txnref" 
				name="squad_txnref" 
				value="${responseTransactionRef}"/>`
      );

      $("#squad_form a").hide();
      squad_submit = true;

      $form.submit();

      $("body").block({
        message: null,
        overlayCSS: {
          background: "#fff",
          opacity: 0.6,
        },
        css: {
          cursor: "wait",
        },
      });
    };
    const channels =
      payment_options.length === 0 ? ["card", "transfer", "ussd", "bank"] : payment_options;

    const squadInstance = new squad({
      onClose: () => {
        $("#wc-squad-form").show();
        $(this.el).unblock();
      },
      onLoad: () => console.log("Widget loaded successfully"),
      onSuccess: (response) => {
        successCallback(response);
      },
      key: public_key,
      email: email,
      amount: amount,
      currency_code: currency,
      transaction_ref: txnref,
      paymentChannel: channels,
      metadata: "",
    });
    squadInstance.setup();
    squadInstance.open();

    return false;
  }
});
