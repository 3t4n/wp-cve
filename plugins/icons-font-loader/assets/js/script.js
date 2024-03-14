(function ($) {
  $(document).ready(function () {
    if(typeof bifl === 'undefined') return;
    $(".bifl_dynamic").on("click", function (e) {
      e.preventDefault();
      console.log( parseInt($(this).data("id")))
      $.post(
        bifl?.ajax_url,
        {
          action: "bifl_ajax_call",
          id: parseInt($(this).data("id")),
          do: $(this).attr("action"),
          nonce: bifl?.nonce
        },
        function (data) {
          const msg = JSON.parse(data);
          console.log(msg);
          if (msg.success == true) {
            location.reload();
          }
        }
      );
    });
  });
})(jQuery);
