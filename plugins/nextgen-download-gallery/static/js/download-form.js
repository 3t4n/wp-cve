/**
* NextGEN Download Gallery form script
* https://wordpress.org/plugins/nextgen-download-gallery/
*/

(function ($) {
  let isDownloadAll = false;

  /* make sure that at least one image is selected before submitting form for download */
  $(document.body).on("submit", "form.ngg-download-frm", function (event) {
    if (!isDownloadAll && $("input[name='pid[]']:checked", this).length === 0) {
      event.preventDefault();
      window.alert(ngg_dlgallery.alertNoImages);
    }
    isDownloadAll = false;
  });

  /* reveal "select all" button and active it; if all are checked, action is to uncheck all */
  $(document.body).on("click", "input.ngg-download-selectall", function () {
    const pid = $(this.form).find("input[name='pid[]']");
    pid.prop({
      checked: pid.not(":checked").length > 0
    });
  });

  /* reveal "download all images" button and active it */
  if (ngg_dlgallery.canDownloadAll) {
    $(document.body).on("click", "input.ngg-download-everything", function () {
      if (this.form.elements.nggDownloadAll) {
        // old template using getDownloadAllUrl()
        document.location = this.form.elements.nggDownloadAll.value;
      } else {
        // flag that submit is a Download All
        isDownloadAll = true;
      }
    });
  }

  /* handle NextGEN Gallery 2.0 AJAX pagination, which pulls the template afresh -- need to show buttons again */
  $(document).on("refreshed", showHiddenButtons);

  /**
  * show buttons that are hidden unless JavaScript is operational
  */
  function showHiddenButtons() {
    if (ngg_dlgallery.canSelectAll) {
      $("input.ngg-download-selectall").show();
    }
    if (ngg_dlgallery.canDownloadAll) {
      $("input.ngg-download-everything").show();
    }
  }
  showHiddenButtons();
})(jQuery);
