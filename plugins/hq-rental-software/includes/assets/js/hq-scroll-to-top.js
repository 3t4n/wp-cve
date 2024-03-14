/***
 * Scroll on Top for Iframe
 * @type {string}
 */
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
var firstTime = true;
eventer(messageEvent, function (e) {
    if (e.data === 'hq-scroll-to-top' || e.message === "hq-scroll-to-top") {
        if (firstTime) {
            firstTime = false;
        } else {
            window.scrollTo(0, findPos(document.getElementById("hq-rental-iframe")));
        }
    }
});

//Finds y value of given object
function findPos(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
        do {
            curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);
        return [curtop];
    }
}