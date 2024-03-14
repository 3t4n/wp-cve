!(function () {
    !(function () {
        const e = (function (e, n) {
            n || (n = window.location.href), (e = e.replace(/[\[\]]/g, "\\$&"));
            var c = new RegExp("[?&]" + e + "(=([^&#]*)|&|#|$)").exec(n);
            return c ? (c[2] ? decodeURIComponent(c[2].replace(/\+/g, " ")) : "") : null;
        })("irclickid");
        e &&
            (function (e, n, c) {
                const i = new Date();
                i.setTime(i.getTime() + 24 * c * 60 * 60 * 1e3);
                const o = "expires=" + i.toUTCString();
                document.cookie = e + "=" + n + ";SameSite=None;" + o + ";path=/;secure";
            })("irclickid", e, 30);
    })();
})();
