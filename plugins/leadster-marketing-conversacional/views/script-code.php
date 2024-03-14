<?php if (!defined("ABSPATH")) die("go away!"); ?>

<!--BEGIN LEADSTER SCRIPT-->
<script>
  (function (w, d, s, c) {
    try {
      var h = d.head || d.getElementsByTagName("head")[0];
      var e = d.createElement("script");

      e.setAttribute("src", s);
      e.setAttribute("charset", "UTF-8");
      e.defer = true;

      w.neuroleadId = c;
      h.appendChild(e);
    } catch (e) {}
  })(window,document,"https://cdn.leadster.com.br/neurolead/neurolead.min.js", "<?php echo esc_attr($leadster_script_code) ?>");
</script>

<!--END LEADSTER SCRIPT-->
