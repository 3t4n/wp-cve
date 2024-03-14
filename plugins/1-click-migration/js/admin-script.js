const validateEmail = function (email) {
    const regex = /^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
};

const buttonIsDisabled = function (button) {
    return jQuery(button).hasClass('one-click-disabled');
};

const disableButtons = function () {
    jQuery('.ocm-button.backup-button, .ocm-button.restore-button').addClass('one-click-disabled');

};

const enableButtons = function () {
    jQuery('.ocm-button').removeClass('one-click-disabled');
};

const setProgressBarToStart = function (text) {
    jQuery('.ocm-progress-text').html(text);
    jQuery('.progress-bar-color').css('width', '1%');
    jQuery('.progress-bar-inner').text('1%');
};

const clearMessageError = function () {
    jQuery('.ocm-info.error').hide();
};

const clearMessages = function () {
    jQuery('.ocm-info.error, .ocm-info.notice').hide();
};

const checkInArray = function (text, noticeList) {
  var status = false;

  jQuery(noticeList).each(function(index, listText){



    if(listText === text){
      status = true;
    }
  });


  return status;
}

const renderMessageNoticeType = function (text, noticeList) {

  var inArray = checkInArray(text, noticeList);

  if(inArray === false){
      jQuery('.ocm-info.notice').append(`<span class="ocm-progress-notice"></span><span>${text}</span><br>`).fadeIn(300);
      noticeList.push(text);
  }

};

const renderMessage = function (text) {

    if (text && text.match(/Error/)) {

      if(text.indexOf('SYSLOG: "[PHP ERR][FATAL]') === -1){

        jQuery('.ocm-info.error').html(`<span class="ocm-progress-notice"></span><span>${text}</span><br>`).fadeIn(300);
      }

    } else if (text && text.match(/Notice:/)) {
        renderMessageNoticeType(text, noticeList);
    } else {
        jQuery('.ocm-progress-text').html(text);
    }
};

const disableInputFields = function () {
    jQuery('#ocm_user_email').attr("disabled", true);
    jQuery('#ocm_user_password').attr("disabled", true);
};

const scrollToNextSection = function(target){

    if (target.length) {
        jQuery('html,body').animate({
            scrollTop: target.offset().top
        }, 1000);
        return false;
    }

}
var restoreAction = "not-yet-started";
var paypalAmount = siteData.defaultPrice;


const customAlert = function (message) {
    jQuery('<div></div>').html(message).dialog({
        title: 'Paypal Transaction',
        resizable: false,
        modal: true,
        buttons: {
            'Ok': function () {
                jQuery(this).dialog('close')
            }
        }
    })
}



/**
 * Determine the plugin price when the coupon code is applied
 *
 * @param {*} selectedCode The coupon code to get the price for
 *
 * @return int The plugin price
 */
const getPluginPrice = async function (selectedCode) {

    var finalPrice = siteData.defaultPrice;
    var APIRetries = 1;
    var APIsuccess = false;
    let apiResponse;

//     while (APIRetries-- > 0 && !APIsuccess) {
//         apiResponse = await retrievePricingFromAPI(selectedCode);
// 
//         if ('undefined' !== typeof apiResponse.price && apiResponse.price) {
//             finalPrice = apiResponse.price;
//             APIsuccess = true;
//         }
//     }
    finalPrice = 0; //for a limited time
    
    paypalAmount = finalPrice;


    return finalPrice;
}



/**
* Update the #ocm-paypal-div display status
* and make an AJAX call to mark the PayPal payment as complete
*/
const completePayPalPayment = function () {
    jQuery("#ocm-paypal-div").hide().addClass('payment-completed');
    jQuery(".reset-payment-container").hide();
    jQuery.post(ajaxurl, { 'action': 'ocm_make_payment' });
}

var timerInitiated = 0;
var timer = null;
var stopTimer = false;
var isRestarted = false;
var isRestarting = 0;
var noticeList = [];

const displayProgressBar = function () {

    clearMessageError();

    // Delay the interval so that if there's an existing log file,
    // the progress bar doesn't jump back to 100% right away
    var timeoutId, intervalId;
    var restoreTimes = 0;

    timeoutId = setTimeout(function () {
        intervalId = setInterval(function () {
            const apiURL = siteData.progressUrl;
            jQuery.get(apiURL, function (response) {



                renderMessage(response.text);
                if (response.customNotice) {
                    renderMessageNoticeType(response.customNotice, noticeList);
                }


                // Set inner bar width
                jQuery('#ocmProgressBar .progress-bar-color').css('width', response.value);
                // Set progress indicator text
                jQuery('.progress-bar-inner').text(response.value);

                if (response.uploadFileData) {
                    var progress = response.uploadFileData.progress,
                        complete = response.uploadFileData.complete;

                    jQuery('#ocmProgressBarUploadFile').css({ display: 'block' });
                    jQuery('#ocmProgressBarUploadFile .progress-bar-inner')
                        .css({ width: progress + '%' })
                        .text(progress + '%');

                    if (complete) {
                        jQuery('#ocmProgressBarUploadFile').css('display', 'none');
                    }
                } else {
                    jQuery('#ocmProgressBarUploadFile').css('display', 'none');
                }
                var responseText = response.text;

                if (response.isStopped) {

                    clearInterval(intervalId);
                    clearTimeout(timeoutId);
                    enableButtons();
                } else if ('1%' === response.value) {
                    jQuery('.ocm-info').fadeOut(100);
                    jQuery('#ocmProgressBarUploadFile').css('display', 'none');
                } 
                else if ('77%' === response.value) {
                    var target = jQuery('#ocm-paypal-div');

                    const ocmStartAction = jQuery('#ocmStartAction').val();
                    restoreTimes++;
                    
                    completePayPalPayment();

                    if (restoreTimes == 1 && siteData.defaultPrice == 0) {

                        completePayPalPayment();


                    } else {

                        if ((restoreAction === 'started-restore-action' || ocmStartAction === 'restore')) {
                            if (jQuery('#ocm-paypal-div').hasClass('payment-completed')) {
                                var message = " Thank you. Please wait while we finish up the restore process";
                                jQuery('.ocm-progress-text').html(message);
                            } else {


                                if (jQuery('#ocm-paypal-div').css('display') == 'none') {
                                  jQuery("#ocm-paypal-div").show();
                                  jQuery(".reset-payment-container").show();
                                  scrollToNextSection(target);
                                }


                            }

                        }

                    }


                } 
                else if ('100%' === response.value) {

                    jQuery('.ocm-info').fadeOut(100);
                    jQuery('.ocm-timer').hide();
                    jQuery('.restart-button').hide();
                    jQuery('.ocm-restart-message').hide();
                    jQuery('.oocm-timer-text').hide();



                    if (responseText.includes("Notice: File themes was skipped due to timeout")) {

                      jQuery('.ocm-themes-skipped').show();

                    }
                    if (responseText.includes("Notice: File plugins was skipped due to timeout")) {

                      jQuery('.ocm-plugins-skipped').show();

                    }
                    if (responseText.includes("Notice: File db was skipped due to timeout")) {

                      jQuery('.ocm-db-skipped').show();

                    }
                    if (responseText.includes("Notice: File uploads was skipped due to timeout")) {

                      jQuery('.ocm-uploads-skipped').show();

                    }

                    if (responseText.includes("Notice: File themes.zip.crypt was not found on the remote server. Please try to back it up again.")) {


                      jQuery('.ocm-themes-not-found').show();

                    }
                    if (responseText.includes("Notice: File plugins.zip.crypt was not found on the remote server. Please try to back it up again.")) {


                      jQuery('.ocm-plugins-not-found').show();

                    }
                    if (responseText.includes("Notice: File uploads.zip.crypt was not found on the remote server. Please try to back it up again.")) {


                      jQuery('.ocm-uploads-not-found').show();

                    }
                    if (responseText.includes("Notice: File db.zip.crypt was not found on the remote server. Please try to back it up again.")) {


                      jQuery('.ocm-db-not-found').show();

                    }

                    enableButtons();
                    if (responseText.includes("Backup Completed") || responseText.includes("Restore Completed")) {

                      clearInterval(intervalId);
                      clearTimeout(timeoutId);
                    }
                }


                if(responseText.includes("Process is Restarting")){

                  if(isRestarting === 0){
                    isRestarted = true;
                    isRestarting = 1;
                    displayDecreasingTimer();
                  }

                }


            })
        }, 1000);
    }, 2000);
};

var displayDecreasingTimer = function(){
  jQuery('.ocm-timer').show();
  // jQuery('.restart-button').addClass('one-click-disabled');
  jQuery('.restart-button').hide();
  jQuery('.ocm-restart-message').hide();

  var timeOut = siteData.timeout;


  if(timerInitiated === 0 || isRestarted === true){

    timer = setInterval(function(){
      if(isRestarted){
        clearInterval(timer);
      }
      timeOut = timeOut - 1;
      timeOutText = timeOut;

      jQuery('.ocm-decreasing-timer').text(timeOutText);

      if(timeOut == 0 ){
        stopTimer = true;
        isRestarting = 0;
         jQuery('.restart-button').show();
         jQuery('.restart-button').removeClass('one-click-disabled');

         jQuery('.ocm-restart-message').show();
         clearInterval(timer);
      }

    }, 1000);
    timerInitiated = 1;
    isRestarted = false;


  };

};

var stopTimer = function(timer, stopTimer){
  if(stopTimer === true){
    clearInterval(timer);
  }

};

var hideRestartBtn = function(){
  jQuery('.restart-button').click(function(){
    $(this).hide();
  });
};

/**
 * Retrieve the price to display to the user
 *
 * @param {string} discountCode The discount code
 * @param {string} email User email address
 */
const retrievePricingFromAPI = function (discountCode) {
    const userId = jQuery.MD5(jQuery('#ocm_user_email').val());
    let apiEndpoint = siteData.priceAPIEndpoint + '?id=' + userId + '&domain=' + siteData.domain;
    if (typeof discountCode !== 'undefined') {
        apiEndpoint += '&discountcode=' + discountCode.toLowerCase();
    }

    return new Promise(function (resolve, reject) {
        jQuery.ajax({
            url: apiEndpoint,
            error: function (error) {
                reject(error)
            },
            success: function (result) {
                if (typeof discountCode === 'undefined') {
                    siteData.defaultPrice = result.price;
                }
                resolve(result)
            }

        });
    });
}

jQuery(document).ready(function () {

    const ocmStartAction = jQuery('#ocmStartAction').val();
    const buttons = jQuery('.ocm-button');


    if ('backup' === ocmStartAction) {
        displayProgressBar();
        jQuery('.restart-button').attr('data-action', 'backup');
    } else if ('restore' === ocmStartAction) {
      var apiPrice =  getPluginPrice();
      var initialPrice = Promise.resolve(apiPrice);
      initialPrice.then((result) => {
        var price =  parseFloat(result).toFixed(2);
        var amountText =  ' $' + price + ' USD'
        jQuery('#ocm-api-price').text(amountText);
        jQuery('#ocm-coupon-amount').text(amountText);
      }, function (err) {
        jQuery('#ocm-api-price').text(paypalAmount);
        jQuery('#ocm-coupon-amount').text(paypalAmount);
      });
        displayProgressBar();
        jQuery('.restart-button').attr('data-action', 'restore');
    }

    buttons.click(function (e) {
        const clickButton = jQuery(e.target);

        if (clickButton.hasClass('cancel-actions-button')) {
            return true;
        }

        e.preventDefault();
        if (buttonIsDisabled(e.target)) {
            return;
        }

        clearMessages();
        disableButtons();

        const user_name = jQuery('#ocm_user_email').val();
        const password = jQuery('#ocm_user_password').val();
        var selectedFolders = jQuery('#selective-backup').val();
        var selected = '';
        if(selectedFolders){
          selected = selectedFolders.join(',');

        }
        const backup_url = e.target.href + '&username=' + user_name + '&password=' + password + '&selected=' + selected;

        jQuery('form .error.notice').remove();

        if (password.length < 4) {
            jQuery('.form-table').append('<div class="error notice"><p>Please choose a longer password.</p></div>');
            enableButtons();
            return false;
        }

        if (!validateEmail(user_name)) {
            jQuery('.form-table').append('<div class="error notice"><p>Please enter a valid email.</p></div>');
            enableButtons();
            return false;
        }

        if (clickButton.hasClass('restore-button')) {
            const bucketExistsUrl = siteData.bucketExistsUrl,
                bucket_check_url = bucketExistsUrl.match(/\?/)
                    ? bucketExistsUrl + '&username=' + user_name + '&password=' + password
                    : bucketExistsUrl + '?username=' + user_name + '&password=' + password
                ;

            jQuery.get(bucket_check_url, function (data) {
                if ('false' === data) {
                    jQuery('.form-table').append("<div class=\"error notice\"><p>We could not find a backup for this email and password combination. Please try again or if your backup was created more than 24 hours ago, you need to re-create it as it's been deleted.</p></div>");
                    enableButtons();
                } else {
                    // Set progress bar to 1%
                    setProgressBarToStart('Restore started');

                    var apiPrice =  getPluginPrice();
                    var initialPrice = Promise.resolve(apiPrice);
                    initialPrice.then((result) => {
                      var price =  parseFloat(result).toFixed(2);
                      var amountText =  ' $' + price + ' USD'
                      jQuery('#ocm-api-price').text(amountText);
                      jQuery('#ocm-coupon-amount').text(amountText);
                    }, function (err) {
                      jQuery('#ocm-api-price').text(paypalAmount);
                      jQuery('#ocm-coupon-amount').text(paypalAmount);
                    });


                    restoreAction = 'started-restore-action';
                    displayProgressBar();
                    displayDecreasingTimer();

                    jQuery.get(backup_url, function () { });


                }
            })
        } else {
            // Set progress bar to 1%
            setProgressBarToStart('Backup started');
            displayProgressBar();
            displayDecreasingTimer();
            if ('backup' === ocmStartAction) {

                jQuery('.restart-button').attr('data-action', 'backup');
            }

            jQuery.get(backup_url, function () { });
        }

    });


    // Catering for the PayPal coupon input
    jQuery("#ocm-coupon-button").click(function () {
        jQuery('#ocm-discount-response').html('Applying discount...');
        var couponInput = jQuery('#ocm-coupon-code').val();
        var discountAmount = getPluginPrice(couponInput);
        var finalPrice = Promise.resolve(discountAmount);
        finalPrice.then((result) => {
            jQuery('#ocm-discount-response').html('Discount applied').fadeTo("slow", 0);
            paypalAmount = parseFloat(result).toFixed(2);

            var amountText = "";
            if (parseFloat(result) === 0) {//When it is free at 100 % discount
                completePayPalPayment();
            } else {
                amountText = ' $ ' + parseFloat(result).toFixed(2) + ' USD';
                jQuery("#ocm-coupon-amount").text(amountText);
            }
        }, function (err) {
            jQuery('#ocm-discount-response').html('');
        });

    })

    //Added the PayPal integration..
    jQuery("#ocm-paypal-button").ready(function ($) {


        paypal.Buttons({
            //Style the paypal button..
            style: {
                color: 'gold',
                shape: 'pill',
                label: 'pay',
                size: 'large',
                height: 40
            },

             // Set up the transaction
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: paypalAmount
                        },
                    }],

                    application_context: {
                        shipping_preference: "NO_SHIPPING"
                    }
                });
            },

            // Finalize the transaction
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    completePayPalPayment();
                });
            },
            onError: function (err) {
                if (err) {
                    customAlert("Failed to process the paypal payment...");
                }
            }

        }).render('#ocm-paypal-button');
    });


    //Disable both restore and backup buttons while payment is happening.
    if (jQuery('.progress-bar-inner').html() == "77%") {
        if (jQuery('#ocm-paypal-div').css('display') == 'none') {

            jQuery("#ocm-paypal-div").show();
            jQuery(".reset-payment-container").show();
            var target = jQuery('#ocm-paypal-div');
            scrollToNextSection(target);


        }

        disableButtons();
        disableInputFields();
        displayProgressBar();

    }


    var renderMultiselect = function(){

      document.multiselect('#selective-backup');
      jQuery('.multiselect-input-div').append('<span class="multiselect-placeholder">Advanced Options<span class="placeholder-small-text"> (click to expand) </span></sapn>');
      jQuery('#selective-backup_input').attr('readonly');
      jQuery('.multiselect-dropdown-arrow, .multiselect-placeholder').click(function(){
      jQuery('#selective-backup_itemList').toggle();
      var display = jQuery('#selective-backup_itemList').css('display');

      if(display === 'block'){
        jQuery('.multiselect-dropdown-arrow').addClass("multiselect-rotate-arrow-down");
      }
      if(display === 'none'){
        jQuery('.multiselect-dropdown-arrow').removeClass("multiselect-rotate-arrow-down");
      }


      });

    };

  var reloadPaypalButtons = function(){
    jQuery(".reset-payment-actions-button").click(function(){
      window.location.href = window.location.href;
    });

  };

  var restartFailedProcess = function(){
    jQuery(".restart-button").click(function(){

      jQuery(this).addClass('one-click-disabled');
      jQuery('.ocm-restart-message').hide();
      jQuery('.ocm-timer').hide();
      var ajaxurl = siteData.ajaxurl;
      var process = jQuery('.restart-button').attr('data-action');

      jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          data : {
            action: 'ocm_restart_failed_process',
            process: process
          },

          success: function (result) {


          }

      });
    });
  };

  var markRestoreStarted = function(){
    jQuery(".restore-button").click(function(){

        jQuery('.restart-button').attr('data-action', 'restore');
        jQuery('.restart-button').text("Resume Restore");
        var target = jQuery('.progress-row');
        scrollToNextSection(target);
    });
  };
  var markBackupStarted = function(){
    jQuery(".backup-button").click(function(){
      
        jQuery('.restart-button').attr('data-action', 'backup');
        jQuery('.restart-button').text("Resume Backup");
        var target = jQuery('.progress-row');
        scrollToNextSection(target);
    });
  };

  var initializeAccordion = function(){
    jQuery('#ocm-instruction-box-accordion').accordion(
      {
        active: false,
        collapsible: true
      }
    );


  }

  var toggleCouponCodeRow = function(){
    jQuery('#ocm-payment-table').on('click', '.ocm-hide-mc-row', function(){
      var thisElem = jQuery(this);

        jQuery('#ocm_migration_code_row').show();
        thisElem.removeClass('ocm-hide-mc-row');
        thisElem.addClass('ocm-show-mc-row');

    });

    jQuery('#ocm-payment-table').on('click', '.ocm-show-mc-row', function(){

      var thisElem = jQuery(this);

        jQuery('#ocm_migration_code_row').hide();
        thisElem.removeClass('ocm-show-mc-row');
        thisElem.addClass('ocm-hide-mc-row');


    });

  }


    renderMultiselect();
    reloadPaypalButtons();
    restartFailedProcess();
    markRestoreStarted();
    markBackupStarted();
    initializeAccordion();
    toggleCouponCodeRow();
    hideRestartBtn();

    // displayDecreasingTimer();

});
