


  function getInternetExplorerVersion()
  // Returns the version of Internet Explorer or a -1
  // (indicating the use of another browser).
  {
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer')
    {
      var ua = navigator.userAgent;
      var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
      if (re.exec(ua) != null)
        rv = parseFloat( RegExp.$1 );
    }
    return rv;
  }


  function startAsteroids(color,address) { 
    var ver = getInternetExplorerVersion();
    if(ver>=0){
      color = typeof(color) != 'undefined' ? color : 'black';
      document.onkeydown = function(ev) {	
      var key;
      ev = ev || event;
      key = ev.keyCode;
          if(key == 37 || key == 38 || key == 39 || key == 40) {
          //e.cancelBubble is supported by IE - this will kill the bubbling process.
          ev.cancelBubble = true;
          ev.returnValue = false;
          }      
      }
      var s =document.createElement('script');
      s.type='text/javascript'
      document.body.appendChild(s);
      s.src = address;
      void(0);
      return false; 
    }
    else{
      color = typeof(color) != 'undefined' ? color : 'black';
      var s =document.createElement('script');
      s.type='text/javascript'
      document.body.appendChild(s);
      s.src = address;
      void(0);
      return false; 
    }
  }
