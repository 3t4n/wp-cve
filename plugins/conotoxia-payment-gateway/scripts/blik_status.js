const waitingTime = 120000;
let processingStart;

jQuery(document).ready($ => {
    processingStart = Date.now();
    checkStatus($);
    clearEmptyPTag($);
});

function checkStatus($) {
    $.ajax({
        method: 'post',
        url: args.ajaxUrl,
        data: {
            action: 'cx_check_blik_status',
            orderId: args.orderId,
            orderKey: args.orderKey
        },
        dataType: 'json',
        success: (result) => {
            switch (result.status) {
                case 'SUCCESS':
                    changeStatus('success');
                    break;
                case 'WAITING':
                    if (Date.now() - processingStart < waitingTime) {
                        setTimeout(() => checkStatus($), 2000);
                    } else {
                        changeStatus('time-exceeded');
                    }
                    break;
                case 'ERROR':
                    changeStatus('error');
                    break;
                default:
                    changeStatus('problem');
            }
        },
        error: () => changeStatus('problem')
    });
}

function changeStatus(status) {
    document.querySelectorAll('.js-cx-blik-status-waiting-element')
        .forEach(element => hideElement(element));
    document.querySelectorAll(`.js-cx-blik-status-${status}-element`)
        .forEach(element => showElement(element));
}

function showElement(element) {
    element.style.display = null;
}

function hideElement(element) {
    element.style.display = 'none';
}

function clearEmptyPTag($){
    $("#cx-blik-status-container p:empty").remove();
}