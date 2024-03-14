jQuery(document).ready(function () {
  !(function () {
    if (
      "undefined" != typeof lpacOrderDate &&
      null !== lpacOrderDate &&
      lpacOrderDate &&
      null !== lpacOrderTime
    ) {
      var e = "".concat(lpacOrderDate + " " + lpacOrderTime),
        r = new Date(e).getTime(),
        t = document.querySelector("#lpac-dps-countdown-timer");
      if (t)
        var a = setInterval(function () {
          var e = new Date().getTime(),
            l = r - e,
            n = Math.floor(l / 864e5),
            o = Math.floor((l % 864e5) / 36e5),
            c = Math.floor((l % 36e5) / 6e4),
            d = Math.floor((l % 6e4) / 1e3);
          (t.innerHTML = n + "d " + o + "h " + c + "m " + d + "s "),
            l < 0
              ? (clearInterval(a),
                (t.innerHTML = lpacExpiredText),
                (t.style.color = "#d55959"))
              : (t.style.color = "#63a563");
        }, 1e3);
    }
  })();
});
