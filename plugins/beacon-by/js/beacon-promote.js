BN.showIssues = function() {

   var $ = jQuery,
      list = $('ul.issues'),
      issues = BN.issues,
      state,
      activeUrl,
      i; 

   if ( issues && issues.length ) {
    activeUrl = $('input[name="url"]').val().split('/').pop();

    for ( i = 0; i < issues.length; i += 1 ) {
      state = ( activeUrl == issues[i].url ) ?
        'active' : 'inactive';
      list.append('<li class="'+state+'" data-url="'+issues[i].url+'">'+issues[i].title+'</li>');
    }
   } else {
    $('<div class="info">You need to publish an issue before you can promote it</div>')
      .insertAfter(list);
   }

  $('ul.issues li').click(function(e) {

    var url = $(this).data('url');
    $('ul.issues li').removeClass('active');
    $(this).addClass('active');
    url = BN.url + '/' + url;
    $('input[name="url"]').val(url);
    BN.syncPromote('url');

  });
  
};

BN.syncPromote = function(el) {
    var $ = jQuery,
        inputs = {
          url: $('input[name="url"]'),
          headline: $('input[name="headline"]'),
          title: $('input[name="title"]'),
          button: $('input[name="button"]')
        },
        els = {
          url: $('.beacon-promote').find('iframe'),
          headline: $('.beacon-promote').find('h2'),
          title: $('.beacon-promote').find('h3'),
          button: $('.beacon-promote').find('button')
        }, 
        url = inputs.url.val(), 
        n, parts, issue_url, pub_url;

    BN.promoteData = BN.promoteData || $('#beacon-promote').serialize();

    if ( el === 'url' && url ) {
        parts = url.split('/');
        issue_url =  parts.pop(); 
        pub_url =  parts.pop(); 
        url = url.replace(pub_url, 'magazine/cover/'+pub_url);
        els.url.attr('src', url);
    }

    for ( n in els ) {
      if (n !== 'url') {
        els[n].text(inputs[n].val());
      } 
    }

    if ( BN.promoteData !== $('#beacon-promote').serialize() ) {
      $('.unsaved').slideDown('slow');
      $('.saved').slideUp('fast');
    }


    if ( url ) {
      $('.step2').css('opacity', 1);
      $('.step3').css('opacity', 1);
    }

};



jQuery(document).ready(function() {

  var $ = jQuery,
          unsaved =false;

  $('.beacon-promote form button').attr('disabled', true);

  var synch = function(el) {
    return BN.syncPromote(el);
  };

  synch();

  $('.beacon-promote-save').click(function(e) {
    $('#beacon-promote').submit();
  });

  $('input[name="headline"]').keyup(function(e) {
    synch();
  });

  $('input[name="title"]').keyup(function(e) {
    synch();
  });

  $('input[name="button"]').keyup(function(e) {
    synch();
  });

});

