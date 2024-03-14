<script>
  var bkRawUrl = function(){return window.location.href}();
  (function (win, doc, scriptPath, apiKey){
    function go(){
      if (doc.getElementById(apiKey)) {return;}
      var sc, node, today=new Date(),dd=today.getDate(),mm=today.getMonth()+1,yyyy=today.getFullYear();if(dd<10)dd='0'+dd;if(mm<10)mm='0'+mm;today=yyyy+mm+dd;
      window.BKShopApiKey =  apiKey;
      sc = doc.createElement("script");
      sc.src = scriptPath + '?' + today;
      sc.id = apiKey;
      node = doc.getElementsByTagName("script")[0];
      node.parentNode.insertBefore(sc, node);
    }
    if(win.addEventListener){win.addEventListener("load", go, false);}
    else if(win.attachEvent){win.attachEvent("onload", go);}
  })(window, document, '<?php echo $scriptPath; ?>', '<?php echo $shopApiKey; ?>');
</script>
