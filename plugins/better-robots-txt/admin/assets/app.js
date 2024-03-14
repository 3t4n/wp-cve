jQuery(document).ready(function () {
  jQuery("#fs_connect button[type=submit]").on("click", function (e) {
    console.log("open verify window");
    window.open(
      "https://better-robots.com/subscribe.php?plugin=better-robots",
      "better-robots",
      "resizable,height=400,width=700"
    );
  });
});
