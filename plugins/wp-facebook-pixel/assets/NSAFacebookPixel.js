eval(elementSelector.pro);


jQuery(document).ready(function () {
    if (typeof fbqEvents === "undefined") return;

    fbqEvents.forEach(function (event, index, a) {
        eval(event.fbqTrigger)
    });
});

function sendFBQ(e, event) {
    if (event.preventDefault) { e.preventDefault(); }
    var data = JSON.parse(event.data);
    if (event.updateScript != null) { eval(event.updateScript); }
    console.log("remarketable > Sending " + event.event + " event to Facebook: " + JSON.stringify(data));
    fbq('track', event.event, data);
    if (event.preventDefault) {
        e.isDefaultPrevented = function () { return false; };
        jQuery(e.target).trigger(e);
    }
}