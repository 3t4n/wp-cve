var autoshipSelectNextOccurrenceDialogCallbacks = [];

window.addEventListener("message", autoshipReceiveSelectNextOccurrenceDialogMessage, false);
function autoshipReceiveSelectNextOccurrenceDialogMessage(event) {
    var origin = event.origin || event.originalEvent.origin; // For Chrome, the origin property is in the event.originalEvent object.

    // if (origin != '') {
    //
    // }

    if (event.data.type != 'qpilot-widget-select-next-occurrence') {
        return;
    }

    var match = /^select-next-occurrence-dialog-(\d+)$/.exec(event.data.callbackId);
    if (match == null) {
        return;
    }
    var id = match[1];
    if (typeof(autoshipSelectNextOccurrenceDialogCallbacks[id]) !== 'undefined') {
        autoshipSelectNextOccurrenceDialogCallbacks[id](event.data.nextOccurrence);
        autoshipSelectNextOccurrenceDialogCallbacks[id] = null;
        autoshipCloseSelectNextOccurrenceDialog();
    }
}

function autoshipOpenSelectNextOccurrenceDialog(callback, nextOccurrence) {
    autoshipCloseSelectNextOccurrenceDialog();
    var dialog = document.querySelector('#autoship-select-next-occurrence-dialog');
    // Close button
    var close = document.createElement('a');
    close.innerText = 'Cancel';
    close.addEventListener('click', autoshipCloseSelectNextOccurrenceDialog);
    dialog.appendChild(close);
    // Select frequency widget
    var iframe = document.createElement("iframe");
    iframe.frameBorder = 0;
    iframe.className = "autoship-widget-iframe";
    var baseUrl = AUTOSHIP_MERCHANTS_URL + '/widgets/select-next-occurrence';
    if (nextOccurrence != null) {
        baseUrl += '/' + encodeURIComponent(nextOccurrence.toString());
    }
    var length = autoshipSelectNextOccurrenceDialogCallbacks.push(callback);
    iframe.src = baseUrl + '?callbackId=' + encodeURIComponent('select-next-occurrence-dialog-' + (length - 1));
    dialog.appendChild(iframe);
    // Display dialog
    dialog.style.display = 'block';
    autoshipPositionSelectNextOccurrenceDialog();
}

function autoshipCloseSelectNextOccurrenceDialog() {
    var dialog = document.querySelector('#autoship-select-next-occurrence-dialog');
    while (dialog.childNodes.length > 0) {
        dialog.removeChild(dialog.childNodes[dialog.childNodes.length - 1]);
    }
    dialog.style.display = 'none';
}

function autoshipPositionSelectNextOccurrenceDialog() {
    var dialog = document.querySelector('#autoship-select-next-occurrence-dialog');
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
