/**
* @preserve Smooth Page Scroll Up/Down Buttons 1.4.1 | @senff | GPL2 Licensed
*/

(function($) {
  $(document).ready(function($) {

    checkon = $('#psb_topbutton').is(':checked');
    setOptions(checkon);
    highlightOptions();

    $('#psb_topbutton').on('change',function(){
      checkon = $('#psb_topbutton').is(':checked');
      setOptions(checkon);
    });

    $('#psb_buttonsize').on('change keyup',function(){
      buttonsize = $('#psb_buttonsize').val();
      setButtonSize(buttonsize);
    });

    $('.positioning-buttons input:radio').on('change',function(){
      highlightOptions();
    });

  });


  function setOptions(withTopButton) {
    if(withTopButton) {
      $('.positioning-buttons').addClass('with-top-button');
    } else {
      $('.positioning-buttons').removeClass('with-top-button');
    }
  }

  function setButtonSize(size) {
    if (!size) {
      $('#psb_buttonsize').val('45');
      size = 45;
    }
    $('.button-example').width(size+'px').height(size+'px');
  }

  function highlightOptions() {
    $('.positioning-option').removeClass('selected');
    $('.positioning-buttons input:radio').each(function(i) {
      if ($(this).is(':checked')) { 
        $(this).parent().addClass('selected');  
      } 
    });
  }

}(jQuery));


