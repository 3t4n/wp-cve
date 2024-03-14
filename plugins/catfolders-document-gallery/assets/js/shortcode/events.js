document.addEventListener("DOMContentLoaded", function () {
  (function ($) {
    const init = () => {
      funcs.copy_clipboard_shortcode();
      funcs.selectAll_table_input_shortcode();
    };

    const funcs = {
      copy_clipboard_shortcode() {
        $("#catf-dg-shortcode").on("click", function () {
          $(this).focus();
          $(this).select();
          document.execCommand("copy");
          $(".catf-dg-shortcode-copy-status").show();
        });
      },

      selectAll_table_input_shortcode() {
        $(".catf-dg-shortcode-table").on("click", function () {
          $(this).focus();
          $(this).select();
        });
      },
    };

    init();
  })(jQuery);
});
