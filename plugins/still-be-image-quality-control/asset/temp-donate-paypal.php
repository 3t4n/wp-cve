<?php


/**
 * 寄付ボタン
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>
<div>
  <div id="smart-button-container" style="display: inline-block; margin: 0 0 48px; padding: 24px; border: 1px dashed #ccc;">
    <div>
      <div style="display: flex; align-items: baseline;">
        <div><label for="description"><?php _e( 'Comment', 'still-be-image-quality-control' ); ?> </label><input type="text" name="descriptionInput" id="description" maxlength="127" value=""></div>
        <p id="descriptionError" style="visibility: hidden; color:red; margin-left: 1em;"><?php _e( 'Please enter a comment', 'still-be-image-quality-control' ); ?></p>
      </div>
      <div style="display: flex; align-items: baseline;">
        <div><label for="amount"><?php _e( 'Donation Amount', 'still-be-image-quality-control' ); ?> </label><input name="amountInput" type="number" id="amount" value="" ><span><?php _e( ' USD', 'still-be-image-quality-control' ); ?></span></div>
        <p id="priceLabelError" style="visibility: hidden; color:red; margin-left: 1em;"><?php _e( 'Please enter a price', 'still-be-image-quality-control' ); ?></p>
      </div>
    </div>
    <div style="display: none;">
      <div id="invoiceidDiv" style="text-align: center; display: none;"><label for="invoiceid"> </label><input name="invoiceid" maxlength="127" type="text" id="invoiceid" value="" ></div>
      <p id="invoiceidError" style="visibility: hidden; color:red; text-align: center;">Please enter an Invoice ID</p>
    </div>
    <div style="display: flex; margin-top: 0.625rem;" id="paypal-button-container"></div>
  </div>
  <script src="https://www.paypal.com/sdk/js?client-id=AZWcdyjnflU_XG3SS1CV7t75u0zS-x2nkdyMWWzrt2eouSLkC-BgPnnJBARO4RXwGoGfOVRxqs_0rcNd&<?php _e( 'currency=USD', 'still-be-image-quality-control' ); ?>" data-sdk-integration-source="button-factory"></script>
  <script>
  function initPayPalButton() {
    var description = document.querySelector('#smart-button-container #description');
    var amount = document.querySelector('#smart-button-container #amount');
    var descriptionError = document.querySelector('#smart-button-container #descriptionError');
    var priceError = document.querySelector('#smart-button-container #priceLabelError');
    var invoiceid = document.querySelector('#smart-button-container #invoiceid');
    var invoiceidError = document.querySelector('#smart-button-container #invoiceidError');
    var invoiceidDiv = document.querySelector('#smart-button-container #invoiceidDiv');

    var elArr = [description, amount];

    if (invoiceidDiv.firstChild.innerHTML.length > 1) {
      invoiceidDiv.style.display = "block";
    }

    var purchase_units = [];
    purchase_units[0] = {};
    purchase_units[0].amount = {};

    function validate(event) {
      return event.value.length > 0;
    }

    paypal.Buttons({
      style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay',
        layout: 'vertical',
        
      },

      onInit: function (data, actions) {
        actions.disable();

        if(invoiceidDiv.style.display === "block") {
          elArr.push(invoiceid);
        }

        elArr.forEach(function (item) {
          item.addEventListener('keyup', function (event) {
            var result = elArr.every(validate);
            if (result) {
              actions.enable();
            } else {
              actions.disable();
            }
          });
        });
      },

      onClick: function () {
        if (description.value.length < 1) {
          descriptionError.style.visibility = "visible";
        } else {
          descriptionError.style.visibility = "hidden";
        }

        if (amount.value.length < 1) {
          priceError.style.visibility = "visible";
        } else {
          priceError.style.visibility = "hidden";
        }

        if (invoiceid.value.length < 1 && invoiceidDiv.style.display === "block") {
          invoiceidError.style.visibility = "visible";
        } else {
          invoiceidError.style.visibility = "hidden";
        }

        purchase_units[0].description = description.value;
        purchase_units[0].amount.value = amount.value;

        if(invoiceid.value !== '') {
          purchase_units[0].invoice_id = invoiceid.value;
        }
      },

      createOrder: function (data, actions) {
        return actions.order.create({
          purchase_units: purchase_units,
        });
      },

      onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
          alert('Transaction completed by ' + details.payer.name.given_name + '!');
        });
      },

      onError: function (err) {
        console.log(err);
      }
    }).render('#paypal-button-container');
  }
  initPayPalButton();
  </script>
</div>