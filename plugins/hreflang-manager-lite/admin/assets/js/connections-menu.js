(function($){

  function initializeChosen(){

    let chosen_elements = [];

    for(let i=1;i<=10;i++){

      if( $('#language' + i).length && $('#locale' + i).length ){

        chosen_elements.push('#language' + i);
        chosen_elements.push('#script' + i);
        chosen_elements.push('#locale' + i);

      }

    }

    $(chosen_elements.join(',')).chosen();

  }

  $(document).ready(function(){

    'use strict';

    initializeChosen();

    //Initialize an object wrapper in the global context
    window.DAEXTHRMAL = {};

    $(document.body).on('click', '.menu-icon.delete' , function( event ){
      event.preventDefault();
      window.DAEXTHRMAL.connectionToDelete = $(this).prev().val();
      $('#dialog-confirm').dialog('open');
    });

  });

  $(function() {
    $('#dialog-confirm').dialog({
      autoOpen: false,
      resizable: false,
      height: 'auto',
      width: 340,
      modal: true,
      buttons: {
        [objectL10n.deleteText]: function() {
          $('#form-delete-' + window.DAEXTHRMAL.connectionToDelete).submit();
        },
        [objectL10n.cancelText]: function() {
          $(this).dialog('close');
        },
      },
    });
  });

})(jQuery);