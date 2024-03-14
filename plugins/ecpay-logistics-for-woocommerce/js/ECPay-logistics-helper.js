// 後台訂單頁-建立物流訂單
function ecpayCreateLogisticsOrder() {
    var ecPayshipping = document.getElementById('ECPayForm');
    map = window.open('','Map',config='height=500px,width=900px');
    if (map) {
        ecPayshipping.submit();
    }
}

// 後台訂單頁-變更門市
function ecpayChangeStore() {
    var changeStore = document.getElementById('ecpayChangeStoreForm');
    map = window.open('','ecpay',config='height=790px,width=1020px');
    if (map) {
        changeStore.submit();
    }
}

// 前台結帳明細頁-變更門市
function ecpayChangeOrderStore($orderId) {
    var changeStore = document.getElementById($orderId);
    map = window.open('','ecpay',config='height=790px,width=1020px');
    if (map) {
        changeStore.submit();
    }
}

// 後台訂單頁-列印繳款單
function ecpayPaymentForm() {
    document.getElementById('ECPayForm').submit();
}

// 後台訂單頁-隱藏'物流訂單建立'按鈕
(function() {
    document.getElementById('__paymentButton').style.display = 'none';
})();