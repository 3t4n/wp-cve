var autoshipSelectFrequencyDialogCallbacks = [];

window.addEventListener("message", autoshipReceiveSelectFrequencyDialogMessage, false);
function autoshipReceiveSelectFrequencyDialogMessage(event) {
    var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.

    // if (origin != '') {
    //
    // }

    if (event.data.type != 'qpilot-widget-select-frequency') {
        return;
    }

    var match = /^select-frequency-dialog-(\d+)$/.exec(event.data.callbackId);
    if (match == null) {
        return;
    }
    var id = match[1];
    if (typeof(autoshipSelectFrequencyDialogCallbacks[id]) !== 'undefined') {
        autoshipSelectFrequencyDialogCallbacks[id](event.data.frequencyType, event.data.frequency, event.data.displayName);
        autoshipSelectFrequencyDialogCallbacks[id] = null;
        autoshipCloseSelectFrequencyDialog();
    }
}

function autoshipOpenSelectFrequencyDialog(callback, defaultFrequencyType, defaultFrequency) {
    autoshipCloseSelectFrequencyDialog();
    var dialog = document.querySelector('#autoship-select-frequency-dialog');
    // Close button
    var close = document.createElement('a');
    close.innerText = 'Cancel';
    close.addEventListener('click', autoshipCloseSelectFrequencyDialog);
    dialog.appendChild(close);
    // Select frequency widget
    var iframe = document.createElement("iframe");
    iframe.frameBorder = 0;
    iframe.className = "autoship-widget-iframe";
    var baseUrl = AUTOSHIP_MERCHANTS_URL + '/widgets/select-frequency';
    if (defaultFrequencyType != null && defaultFrequency != null) {
        baseUrl += '/' + defaultFrequencyType + '/' + defaultFrequency;
    }
    var length = autoshipSelectFrequencyDialogCallbacks.push(callback);
    iframe.src = baseUrl + '?callbackId=' + encodeURIComponent('select-frequency-dialog-' + (length - 1));
    dialog.appendChild(iframe);
    // Display dialog
    dialog.style.display = 'block';
    autoshipPositionSelectFrequencyDialog();
}

function autoshipCloseSelectFrequencyDialog() {
    var dialog = document.querySelector('#autoship-select-frequency-dialog');
    while (dialog.childNodes.length > 0) {
        dialog.removeChild(dialog.childNodes[dialog.childNodes.length - 1]);
    }
    dialog.style.display = 'none';
}

function autoshipPositionSelectFrequencyDialog() {
    var dialog = document.querySelector('#autoship-select-frequency-dialog');
    var dialogWidth = dialog.offsetWidth;
    var dialogHeight = dialog.offsetHeight;

    var windowWidth = window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth;
    var windowHeight = window.innerHeight
        || document.documentElement.clientHeight
        || document.body.clientHeight;

    dialog.style.left = ((windowWidth - dialogWidth)/2).toString() + 'px';
    dialog.style.top = ((windowHeight - dialogHeight)/2).toString() + 'px';
}
