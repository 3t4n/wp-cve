function addCurrentClass(el) {
    var canContinue = false;

    const current_payment_block = el.closest('#payment');
    const elements = current_payment_block.querySelectorAll(".bm-payment-channel-item > label > input[type='radio']");

    const bank_group_wrap = current_payment_block.querySelector(".bm-group-expandable-wrapper");
    const bank_group_radio = current_payment_block.querySelector("#bm-gateway-bank-group");
    if (elements) {
        elements.forEach((element) => {
            if (element.checked) {
                let closestItem = element.closest(".bm-payment-channel-item");

                if (closestItem && closestItem.classList) {
                    closestItem.classList.toggle("selected");
                    canContinue = closestItem.classList.contains("selected");
                }


                if (!canContinue) {
                    element.checked = false
                }

                // hide list of "PRZELEW INTERNETOWY"
                if (!isChild(element, current_payment_block.querySelector("div.bm-group-expandable-wrapper"))) {

                    if (bank_group_wrap && bank_group_wrap.classList) {
                        bank_group_wrap.classList.remove('active');
                    }


                    if (bank_group_radio && bank_group_radio.checked) {
                        bank_group_radio.checked = !bank_group_radio.checked;

                    }
                }
            }
            current_payment_block.querySelectorAll(".bm-payment-channel-item > label > input[type='radio']").forEach((element) => {
                if (element.checked === false) {
                    element.closest(".bm-payment-channel-item").classList.remove("selected");
                }
            })
        });
    }


    if (canContinue) {
        BmActivateNewOrderButton()
    } else {
        BmDeactivateNewOrderButton()
    }

}

var bm_global_update_checkout_in_progress = 1;
var bm_global_timer = null;



jQuery(document).ready(function () {


    jQuery('body').on('update_checkout', function() {
        bm_global_update_checkout_in_progress = 1;
    });


    if (typeof window.blueMedia !== 'undefined') {
        if (typeof blue_media_ga4_tasks !== 'undefined' && typeof blueMedia.ga4TrackingId !== 'undefined') {
            window.dataLayer = window.dataLayer || [];


            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', blueMedia.ga4TrackingId);
            let events = JSON.parse(blue_media_ga4_tasks)[0].events;
            //console.log(events);

            events.forEach((event) => {
                gtag('event', event.name,
                    {
                        'items': event.params.items
                    }
                )
            });
        }
    }
})

function blueMediaRadioShow() {
    jQuery('.payment_box.payment_method_bluemedia .payment_box.payment_method_bacs').css('display', 'block');

}

function blueMediaRadioHide() {
    jQuery('.payment_box.payment_method_bluemedia .payment_box.payment_method_bacs').css('display', 'none');
};

function blueMediaRadioTest() {
    if (jQuery('#payment_method_bluemedia').is(':checked')) {
        blueMediaRadioShow();
    }
};

document.addEventListener('click', function (e) {
    e = e || window.event;
    var target = e.target || e.srcElement;

    const bank_group_wrap = document.querySelector(".bm-group-expandable-wrapper");
    // click on PRZELEW INTERNETOWY
    if (target.hasAttribute('id') && target.getAttribute('id') == 'bm-gateway-bank-group') {
        if (target.checked) {

            BmSelectGroupedLi()

            document.querySelectorAll(".bm-group-expandable-wrapper").forEach((element) => {
                if (element && element.classList) {
                    element.classList.add('active');
                }


            });

            document.querySelectorAll(".bm-payment-channel-item > label > input[type='radio']").forEach((element) => {
                if (element.checked) {

                    let closestItem = element.closest(".bm-payment-channel-item");

                    if (closestItem && closestItem.classList) {
                        closestItem.classList.remove("selected");
                    }

                    element.checked = !element.checked;
                    BmDeactivateNewOrderButton()
                }
            })
        }
    } else {
        //console.log(target.getAttribute('class'));
        if (target.hasAttribute('class') && target.getAttribute('class') !== 'bm-payment-channel-group-in-group') {
            //console.log(target.getAttribute('class'));
            BmDeselectGroupedLi()
        }
    }
});

function isChild(obj, parentObj) {
    while (obj != undefined && obj != null && obj.tagName.toUpperCase() != 'BODY') {
        if (obj == parentObj) {
            return true;
        }
        obj = obj.parentNode;
    }
    return false;
}

function BmDeactivateNewOrderButton() {
    jQuery("#place_order").prop("disabled", true); // Deaktywuj przycisk
}

function BmActivateNewOrderButton() {
    jQuery("#place_order").prop("disabled", false); // Deaktywuj przycisk
}

function BmSelectGroupedLi() {
    jQuery('.bm-payment-channel-group-item').addClass('bm-selected-group')
}

function BmDeselectGroupedLi() {
    jQuery('.bm-payment-channel-group-item').removeClass('bm-selected-group')
}
