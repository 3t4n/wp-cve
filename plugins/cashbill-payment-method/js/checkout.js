function selectCashBillChannel(channel, id) {
    var elements = document.querySelectorAll(".cashbill__payments__channel.active");
    var input = document.getElementById("cashbill__channel");
    elements.forEach(element => {
        element.classList.remove("active");
    });
    channel.classList.add("active");
    input.value = id;
}

function doNext(el) {
    if (el.value.length < el.getAttribute('maxlength')) return;

    var f = el.form;
    var els = f.elements;
    var x, nextEl;
    for (var i = 0, len = els.length; i < len; i++) {
        x = els[i];
        if (el == x && (nextEl = els[i + 1])) {
            if (nextEl.focus) nextEl.focus();
        }
    }
}