jQuery(document).ready(function( $ ) {
  var transparent = 'rgba(0, 0, 0, 0)';
  var transparentIE11 = 'transparent';

  function getBgColor(elem) {
    if (!elem) return transparent;

    var bgColor = window.getComputedStyle(elem).backgroundColor;
    if (bgColor === transparent || bgColor === transparentIE11) {
      return getBgColor(elem.parentElement);
    } else {
      return bgColor;
    }
  }

  function setBgColor() {
    const containerElem = document.querySelector('#pm-fygaro-container');
    const logoContainerElem = document.querySelector('#pm-fygaro-logo-container');

    if (containerElem && logoContainerElem) {
      const sectionBgColor = getBgColor(containerElem.parentElement);

      if (sectionBgColor !== transparent && sectionBgColor !== transparentIE11) {
        logoContainerElem.style.backgroundColor = sectionBgColor;

        containerElem.style.paddingLeft = "0";
        containerElem.style.paddingRight = "0";
        containerElem.style.backgroundColor = "transparent";
      }
    }
  }

  // Initial Run
  setBgColor();

  $(document.body).on('updated_checkout', function() {
    // WC reloads the html for various reasons, code needs to re-run
    setBgColor();
  });
});
