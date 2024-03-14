(function ($) {
  "use strict";

  $(window).load(function () {
    //$(document).ready(function () {
    //setup

    $(".theme[data-slug]")
      //.filter(":not(:has(div.theme-id-container > h2 > span))") //is active theme
      .filter(":not(:has(div.update-message))") //only when not needing an update
      .each(function () {
        let element = $(window.force_reinstall.button);

        //https://stackoverflow.com/a/39620299/5956589
        let url = new URL(element.attr("href"));
        url.searchParams.set("force-reinstall", $(this).data("slug"));

        element.attr("href", url.href);

        $(this).find(".theme-actions").prepend(element);
      });

    let obs = new MutationObserver(function () {
      $(".theme-wrap")
        .filter(":not(:has(a.force-reinstall))") //not already added
        .filter(
          ":not(:has(div.theme-info div.notice a[href*='action=upgrade-theme']))" //target notice with upgrade theme notice
        )
        .each(function () {
          let element = $(window.force_reinstall.button);

          //get id
          //https://stackoverflow.com/a/39620299/5956589
          let params = new URLSearchParams(new URL(window.location).search);

          element.attr(
            "href",
            element.attr("href") +
              params.get("theme") +
              (params.toString().length != 0 ? "&" + params.toString() : "")
          );

          $(this).find(".theme-actions > div").prepend(element);
        });
    });
    obs.observe(document.body, {
      childList: true,
      subtree: true,
      attributes: false,
      characterData: false,
    });
  });
})(jQuery);
