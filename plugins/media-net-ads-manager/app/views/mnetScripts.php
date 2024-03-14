<script>
  // Script errors

  // reports error if adblock not detected
  function handleScriptError(event) {
    var bait = addBaitElement();
    setTimeout(function() {
      if (!detectAdblocker(bait)) {
        var errorData = {
          message: 'Bundle script failed to load',
          source: event.target.src
        };
        handleLoadingErrors(JSON.stringify(errorData));
      }
    }, 1000);
  }

  // scripts to load
  <?php
  $scripts = array('mnetBundle.js');
  if (mnet_normalize_chunks('vendors.js') !== '/vendors.js') { // prod env
    array_push($scripts, 'vendors.js', 'runtime.js');
  }
  $scripts = array_map(function ($script) {
    return "'" . \plugin_dir_url(__DIR__) . mnet_normalize_chunks("/../../dist/js/" . $script) . "'";
  }, $scripts);
  $scripts = implode(', ', $scripts);
  echo ("var scripts = [" . $scripts . "];\n");
  ?>

  var scriptsLoaded = 0; // count of scripts loaded

  scripts.forEach(function(script) {
    var scriptElement = document.createElement('script');
    scriptElement.src = script;
    scriptElement.addEventListener('error', handleScriptError);
    scriptElement.addEventListener('load', function() {
      scriptElement.removeEventListener('error', handleScriptError);
      scriptsLoaded++;
      if (scriptsLoaded === scripts.length) { // when all scripts loaded
        clearInterval(window.loadingTimer);
        window.removeEventListener('error', handleWindowError);
      }
    });
    document.body.appendChild(scriptElement);
  });

  // Window errors

  window.addEventListener('error', handleWindowError);

  function handleWindowError(event) {
    if (event.filename.match(new RegExp(scripts.map(function(script) {
        return script.split('/').slice(-1)
      }).join('|')))) { // extract filename and match
      var errorData = {
        message: event.message,
        source: event.filename,
        lineno: event.lineno,
        colno: event.colno
      };
      handleLoadingErrors(JSON.stringify(errorData));
    }
  }
</script>