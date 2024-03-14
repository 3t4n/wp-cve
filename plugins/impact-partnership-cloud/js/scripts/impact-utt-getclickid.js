!function() {
  ire("generateClickId", function(e) {
    !function(e, i, t) {
      const n = new Date;
      n.setTime(n.getTime() + 24 * t * 60 * 60 * 1e3);
      const c = "expires=" + n.toUTCString();
      document.cookie = e + "=" + i + ";SameSite=None;" + c + ";path=/;secure";
    }("irclickid", e, 30)
  });
}();
