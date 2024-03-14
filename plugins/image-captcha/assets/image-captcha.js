jQuery(document).ready(function($) {
  if($('.form-allowed-tags').length > 0) {
    after_element = '.form-allowed-tags';
  } else {
    after_element = '.comment-form-comment';
  }
  $('.comment-image-captcha').each(function() {
    $(this).insertAfter($(after_element));
  });
  $(".image-captcha").show();
  $('#image-captcha-refresh').click(function() {
    $.get("?act=refresh-image-captcha",
      function(html){
        $('#image-captcha-block').html(html);
      });
    return false;
  });
});