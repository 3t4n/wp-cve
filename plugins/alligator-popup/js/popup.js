document.addEventListener('DOMContentLoaded', function() {

  var popupLinks = document.querySelectorAll('.popup');

  popupLinks.forEach(function(popupLink) {

    popupLink.addEventListener('click', function(event) {
      event.preventDefault();

      var w = popupLink.dataset.width;
      var h = popupLink.dataset.height;
      var s = popupLink.dataset.scrollbars;

      var left = (window.screen.width / 2) - (w / 2);
      var top = (window.screen.height / 2) - (h / 2);

      var popupWindow = window.open(popupLink.href, '', 'scrollbars=' + s + ',resizable=yes,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);

      if (popupWindow) {
        popupWindow.focus();
      }
    });
  });
});
