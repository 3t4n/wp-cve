if ( typeof AUTOSHIP_SAVE_PAYMENT_ELEMENT_IDS !== "undefined" ){
  (function (checkboxIds) {
      checkboxIds.forEach(function (id) {
          var checkbox = document.getElementById(id);
          if (checkbox !== null) {
              checkbox.checked = true;
              checkbox.addEventListener('click', function() {
                  this.checked = true;
              });
          }
      });
  })( AUTOSHIP_SAVE_PAYMENT_ELEMENT_IDS );
}
