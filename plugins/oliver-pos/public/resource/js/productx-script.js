(function ($) {
    $(document).ready(function () {

        var loading = $("#loading");
        loading.show();
        setTimeout(function() {
            loading.hide();
        }, 2000);

        //Price measurmnet
        $('.amount_needed').attr('required', 'required');
        $('.amount_needed').prop('type', 'number');
        $('.amount_needed').val(1);
        $(".amount_needed").on("change paste keyup", function(){

            var test_input_value =  $(this).val();
            var oliver_inventry_value = $('#oliver_inventry_value').text();
            $('.oliver_out_of_stock').remove();
            if(parseInt(test_input_value) > parseInt(oliver_inventry_value)) {
                $(".price-table-row").append("<p class='oliver_out_of_stock' style='color : #ff0000;'>You cannot add more than product stock Quantity</p>");
            }
            else
            {
                var amount = $('.product_price .amount').text();
                amount = amount.replace(/[^\d\.]/g, '');
                $("#productx_price").text(amount);
                $('#productx_price').attr('product_price', amount);
            }
        });

        $(".woocommerce-grouped-product-list-item__label label a").attr('href','javascript:void(0)');
        $(".close_child_window").click(function(){
            if(window.opener)
            {
                window.close();
            }
            else
            {
                let jsonMsg = {
                    oliverpos: {
                        "event": "closePopUp"
                    },
                }
                oliverSendMessage(JSON.stringify(jsonMsg));
            }
        });
        $(".predefine_diss").click(function(){
            var discount_type = $(this).attr("discount_type");
            var discount_offer = $(this).attr("discount_offer");
            var offertypesymbol = $(this).attr("offertypesymbol");
            var dis_data = $('#productx_link_input').val();

            $('#productx_link_input').val(discount_offer);

            if(discount_type=='Number'){
                $('#productx_discount_sign').html(offertypesymbol);
            }
            if(discount_type=='Percentage'){
                $('#productx_discount_sign').html('%');
            }
        });

        if (typeof(Storage) == "undefined") {
            // Show warning message
            alert("Your browser doesn't support lhereggocal storage, Allow local storage to run Oliver POS");
        }

        // Send a message to the parent when extension ready
        oliverExtensionReady();

        var local_storage_productID = localStorage.getItem("oliver_pos_productx_id");
        if (local_storage_productID) {

            $("#loading").css("display", "block");
            oliverGetCartContent();
            oliverAddedToCart();
        }

    });
    // receive post messages
    window.addEventListener('message', function (e) {
        if (e.data) {
            console.log("message received on bridge", e.data);
            let receiveData = JSON.parse(e.data);
            receiveData.oliverpos.event ? oliverDistinctEvents(receiveData.oliverpos.event) : '';
        }
    }, false);

    // Send a message to the parent
    function oliverSendMessage(msg) {
        if(window.opener)
        {
            window.opener.postMessage(msg, '*');
        }
        else
        {
            window.parent.postMessage(msg, '*');
        }
    };

    // Send a message to the parent when extension ready
    function oliverExtensionReady() {
        let jsonMsg = {
            oliverpos: {
                "event": "extensionReady"
            },
        }
        oliverSendMessage(JSON.stringify(jsonMsg));
    }

    // Send a message to the parent when product add to cart
    function oliverAddedToCart() {
        let jsonMsg = {
            oliverpos: {
                "event": "oliverAddedToCart"
            },
        }
        oliverSendMessage(JSON.stringify(jsonMsg));
    }

    // Send a message to the parent with product data and status
    function oliverSendProductData( productData ) {
        console.log('send final productData');
        // console.log(productData);
        let jsonMsg = {
            oliverpos: {
                "event": "oliverSetProductxData"
            },
            data: productData
        }
        oliverSendMessage(JSON.stringify(jsonMsg));
    }

    // Send a message to the parent when extension finsished
    function oliverExtensionFinished() {
        let jsonMsg = {
            oliverpos: {
                "event": "extensionFinished"
            },
        }
        oliverSendMessage(JSON.stringify(jsonMsg));
    }

    // detect click on add to cart button (buttn name might be differ)
    $('form.cart').on('submit', function (e) {

        let productx_id = $("input[name='add-to-cart']").val() || $(".single_add_to_cart_button").val();
        localStorage.setItem('oliver_pos_productx_id', productx_id);
        if(window.opener)
        {
            window.opener.postMessage(productx_id, "*");
        }
        else
        {
            window.parent.postMessage(productx_id, '*');
        }
        $("#loading").show();

    });

    function oliverDistinctEvents(event) {
        if (event) {
            switch (event) {
                case 'oliverHideContent':
                    oliverHideContent();
                    break;
                case 'oliverGetProductxData':
                    //oliverGetCartContent();
                    oliverGetProductxSessionData();
                    break;
                default:
                    break;
            }
        }
    }

    function oliverGetProductxSessionData(id = null) {
        let getData = localStorage.getItem("oliver_pos_productx_cart_session_data");
        let data = new Object();
        let status = false;
        let productxId = localStorage.getItem("oliver_pos_productx_id");
        if ( getData ) {
            data = JSON.parse(getData);
            data['productx_id'] = productxId;
            status = true;

            // delete oliver pos temp data
            oliverDeleteItemFromCart(productxId);
        }
        let returnData = {
            "product"   : data,
            "productxId": productxId,
            "status"    : status
        };
        console.log('returnData', returnData);

        // Send a message to the parent with product data and status
        oliverSendProductData(returnData);

        // Send a message to the parent when extension finsished
        if (status) {
            oliverExtensionFinished();
            localStorage.removeItem("oliver_pos_productx_cart_session_data");
            localStorage.removeItem("oliver_pos_productx_id");
        }

        return returnData;
    }

    // hide site content
    function oliverHideContent(content = null) {
        if (content) {
            $(`#${content}`).hide();
        } else {
            oliverHideHeader();
            oliverHideFooter();
        }
    }

    // hide site header
    function oliverHideHeader() {
        $('header').hide();
    }

    // hide site footer
    function oliverHideFooter() {
        $('footer').hide();
    }

    // Get value of a specific cookie by cookie name
    function oliverGetCookieValue(a) {
        var b = document.cookie.match('(^|[^;]+)\\s*' + a + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
    }

    function oliverGetCartContent() {
        $.post(oliver_pos_productx.ajax_url, {
                action: 'oliver_pos_get_cart_content',
            }, function(response) {
                if(response=="empty"){
                    console.log('empty content');
                }
                else{
                    localStorage.setItem('oliver_pos_productx_cart_session_data', response);
                    oliverGetProductxSessionData();
                }
            }
        );

    }

    function oliverDeleteItemFromCart(product_id) {

        jQuery.ajax({
            url : oliver_pos_productx.ajax_url,
            type : 'post',
            data : {
                action : 'oliver_pos_remove_item_from_cart',
                productx_id : product_id

            },
            success : function( response ) {
                console.log('oliverDeleteItemFromCart');
            }
        });
    }
})(jQuery)