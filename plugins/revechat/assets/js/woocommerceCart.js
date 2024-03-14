setInterval(function() {

    if (typeof $_REVECHAT_API == 'undefined')
    {
      return;
    }
  
    if (typeof $_REVECHAT_API.Form == 'undefined')
    {
      return;
    }


    var rcPlatform = localStorage.getItem("rcPlatform");
    if (rcPlatform != "woocommerce") {
        return;
    }
    resetCartForPreviousChat();
    
   
    var cartContents = fetch("/wp-json/revechat/v1/cart",
    {
        method: "GET",
        headers: {
          'X-WP-Nonce': revechatSettings.nonce
        }
    }
    ).then(response=>{
        if (response.ok) {
            return response.json()
        }
    }
    ).then(data=>{
        var newPayload = getCartPayload(data);
        var prevPayload = JSON.parse(localStorage.getItem("rcCartPayload"));
        var cartInformation = {};
        if (prevPayload == null) {
            if (newPayload.items.length > 0 || typeof newPayload.customer.customerId != "undefined") {
                cartInformation.cartResponse = newPayload;
                sendCartInfoToRevechat(cartInformation);
            }
        } else {
            if (JSON.stringify(newPayload.items) != JSON.stringify(prevPayload.items) || JSON.stringify(newPayload.customer) != JSON.stringify(prevPayload.customer)) {
                cartInformation.cartResponse = newPayload;
                sendCartInfoToRevechat(cartInformation);
            }
        }
    }
    ).catch((error)=>{}
    );
}, 10000);
function getCartPayload(data) {
    return data;
}
function updateCartPayload(payload) {
    localStorage.setItem("rcCartPayload", JSON.stringify(payload));
}
function sendCartInfoToRevechat(cartInformation) {
    if ($_REVECHAT_API.Form && $_REVECHAT_API.Form.Online && $_REVECHAT_API.isVisitorChatting()) {
        var cms_name = "woocommerce";
        $_REVECHAT_API.Form.Online.pushCartInformation(cms_name, JSON.stringify(cartInformation), $_REVECHAT_API.getShoppingCartEventType());
        updateCartPayload(cartInformation.cartResponse);
    }
}
function resetCartForPreviousChat() {
    if ($_REVECHAT_API.Form && $_REVECHAT_API.Form.Online) {
        $_REVECHAT_API.attach_chat_close_callback(function() {
            localStorage.removeItem("rcCartPayload");
        });
    }
}
