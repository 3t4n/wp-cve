(function($){

  function initializeChosen(){

    let chosen_elements = [];

    for(let i=1;i<=10;i++){

        chosen_elements.push('#daexthrmal-default-language-' + i);
        chosen_elements.push('#daexthrmal-default-script-' + i);
        chosen_elements.push('#daexthrmal-default-locale-' + i);

    }

    chosen_elements.push('#daexthrmal-https');
    chosen_elements.push('#daexthrmal-detect-url-mode');
    chosen_elements.push('#daexthrmal-auto-trailing-slash');
    chosen_elements.push('#daexthrmal-auto-delete');
    chosen_elements.push('#daexthrmal-auto-alternate-pages');
    chosen_elements.push('#daexthrmal-show-log');

    $(chosen_elements.join(',')).chosen();

  }

  $(document).ready(function(){

    initializeChosen();

  });

})(jQuery);