<?php require __DIR__ . '/MnetBundleLoadError.php'  ?>
<div id="loading-message">Loading...</div>
<script>
  var mnetAssetLoadStarted = Date.now();
  var loadingContainer = document.getElementById('loading-message');


  var counterFlag = 0;
  var mnetLoaderText = 'Loading';

  function loadingFunction() {
    var loaderEllipses = ['&nbsp;&nbsp;&nbsp;', '.&nbsp;&nbsp;', '..&nbsp;', '...'];
    loadingContainer.innerHTML = mnetLoaderText + loaderEllipses[counterFlag];
    counterFlag = (counterFlag + 1) % 4;
  }

  window.loadingTimer = setInterval(loadingFunction, 1000);
  window.loading2 = setTimeout(function() {
    counterFlag = 0;
    mnetLoaderText = 'Taking longer than usual';
  }, 20000)

  function reload() {
    window.location.reload();
  }

  // report error and show error page
  function handleLoadingErrors(message) {
    clearInterval(window.loadingTimer);
    mnetReportLoadFailed(message);
    loadingContainer.innerHTML = "";
    mnetShowErrorPage();
  }

  function mnetReportLoadFailed(message) {
    if (message === undefined) {
      message = null;
    }
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "mnet_admin_ui_error",
        message: message || "Asset loading failed",
        timeElapsed: Date.now() - mnetAssetLoadStarted
      },
      dataType: "JSON",
    })
  }

  function mnetShowErrorPage() {
    document.getElementById('infographic').src = window.MNET_PLUGIN.url + 'images/error_boundary.svg';
    // document.getElementById('loading-message').style = "height: calc(65vh - 32px - 16px)"
    document.getElementById('loading-message').style = "height: 0px"
    jQuery('.error-fallback').removeClass('hide');
  }
</script>