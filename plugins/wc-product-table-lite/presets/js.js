jQuery(function($){

  // reload page with preset slug param
  $('.wcpt-presets__item').on('click', function(){
    var $this = $(this),
        slug = $this.attr('data-wcpt-preset-slug');
        window.location.href = window.location.href + '&wcpt_preset=' + slug;
  })

  // dismiss preset applied message
  $('.wcpt-preset-applied-message__dismiss').on('click', function(){
    var $this = $(this);
    $this.closest('.wcpt-preset-applied-message').slideUp();
  })

  // copy shortcode
  $('.wcpt-preset-applied-message__shortcode-copy-button').on('click', function(){
    var $this = $(this);
        $input = $this.siblings('input');
    $input.select();
    document.execCommand("copy");
  })

})