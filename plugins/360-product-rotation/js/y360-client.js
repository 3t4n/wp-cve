/**
 * Yofla360 Client script
 */

/**
 * Resizes an element on page resize, maintaining its defiend aspect ratio and max-sizes
 *
 */
(function () {

  // resizes element to match required aspect-ratio
  var elements = document.getElementsByClassName('auto-aspect-ratio');

  if (elements.length) {
    for (var i = 0; i < elements.length; i++) {
      var element = elements[i]


      var resizeElement = function () {


        var modifyHeight = false; // force setting of width of image (keep height)
        var maxHeight = null;
        var maxWidth = null;

        var matches = this.className.match(/auto-aspect-ratio__(\d+)x(\d+)/);

        if (!matches[1] || !matches[2]) {
          return;
        }
        var ratio = matches[1] / matches[2];
        maxWidth = matches[1]
        maxHeight = matches[2]

        // w/h
        var matches_2 = this.className.match(/auto-aspect-ratio__modify-(\w+)-(\d+)/);
        if (matches_2 && matches_2[1] == 'height') {
          modifyHeight = true;
          maxHeight = matches_2[2]
        }

        if (modifyHeight) {
          // modify height on width change
          var width = Math.min(maxWidth,this.offsetWidth);
          var newHeight = Math.round(width / ratio);
          newHeight = maxHeight ? Math.min(newHeight, maxHeight) : newHeight;
          this.style.height = newHeight + 'px';
          this.style.maxHeight = newHeight + 'px';
          this.style.maxWidth = maxWidth + 'px';
        } else {
          // modify width
          var height = this.offsetHeight;
          var newWidth = Math.round(height * ratio);
          if (newWidth > 10) {
            this.style.width = newWidth + 'px';
          }
        }
      }

      var resizeFn = resizeElement.bind(element);
      resizeFn();
      window.addEventListener('resize', resizeFn)
    }
  }
})();

