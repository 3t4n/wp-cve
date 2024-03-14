jQuery(function($) {
  var $posttypes = $('[name="posttypes"]');

  $('.post-type-option').on('click', function(e) {
    var $posttype = $(this);
    var posttype = $posttype.text();

    var value = $posttypes.val();
    var values = value.split('\n').filter(function(item) {
      return $.trim(item);
    });
    var index = values.indexOf(posttype);
    if(index === -1) {
      values.push(posttype);
    } else {
      values.splice(index, 1);
    }
    $posttypes.val(values.join('\n'));
    $posttypes.trigger('input');
  });

  $posttypes.on('input propertychange', function() {
    var values = $posttypes.val().split('\n')
      .map(function(item) {
        return $.trim(item);
      }).filter(function(item) {
        return item;
      });
    $('.posttypes-chooser code').each(function() {
      var $posttype = $(this);
      if(values.indexOf($posttype.text()) > -1) {
        $posttype.addClass('active');
      } else {
        $posttype.removeClass('active');
      }
    });
  });
  $posttypes.trigger('input');
});
