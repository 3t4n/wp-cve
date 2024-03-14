BN.showIssues = function() {

   var $ = jQuery,
      list = $('ul.issues'),
      issues = BN.issues,
      showHelp = $('a.show-help'),
      state,
      activeUrl,
      i; 

   if ( issues && issues.length ) {
      activeUrl = $('input[name="url"]').val().split('/').pop();
        for ( i = 0; i < issues.length; i +=  1) {
          state = ( activeUrl == issues[i].url ) ?
            'active' : 'inactive';
          list.append('<li class="'+state+'" data-url="'+issues[i].url+'">'+issues[i].title+'</li>');
        }
   } else {
    $('<div class="info">You need to create an upgrade before you can embed it</div>')
      .insertAfter(list);
   }

  $('.collapse a.close').click(function(e) {
    e.preventDefault();
    $(this).parent('div.collapse').slideUp('fast');
    return false;
  });

  $(showHelp).click(function(e) {
    e.preventDefault();
    $('.collapse.help-image').slideDown('fast');
    return false;
  });

  $('ul.issues li').click(function(e) {

    var url = $(this).data('url'),
        html;

    $('ul.issues li').removeClass('active');
    $(this).addClass('active');
    url = BN.url + '/' + url;
    $('input[name="url"]').val(url);
    $('iframe.beacon-iframe').attr('src', url);
    html = $.trim( $('.embed-preview').html() );
    $('.embed-code textarea').val(html);
    
    $('.step-2').show();
    $('.step-3').show();
  });
  
};

jQuery(document).ready(function() {
  jQuery('.step-2').hide();
  jQuery('.step-3').hide();
});
