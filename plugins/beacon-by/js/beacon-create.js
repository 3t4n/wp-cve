//
// create eBook

jQuery(document).ready(function() {

  var $ = jQuery,
      cats = $('.beacon-by-admin-wrap span.toggle-cat'),
      tags = $('.beacon-by-admin-wrap span.toggle-tag'),
      filter = $('.beacon-by-admin-wrap input.filter'),
      toggleVisible = $('.beacon-by-admin-wrap input[name="toggle_visible"]'),
      form = $('.beacon-by-admin-wrap form'),
      errMsg = $('.beacon-by-admin-wrap .error-no-posts'),
      showAllCat = $('.beacon-by-admin-wrap .all-cat'),
      showAllTag = $('.beacon-by-admin-wrap .all-tag'),
      clearSearch = $('.beacon-by-admin-wrap span.clear'),
      runSearch = $('.beacon-by-admin-wrap .search form'),
      togglePost = $('.beacon-by-admin-wrap #toggle-post'),
      togglePage = $('.beacon-by-admin-wrap #toggle-page'),
      consent = $('input[name="beacon_consent_given"]'),
      MAXPOSTS = 30,
      ajaxErrorCount = 0;


  var handleConsent = function() {

    var hasContent = $(consent).is(':checked');

    if (hasContent) {
      $('require-login').show();
    } else {
      $('require-login').hide();
    }

  };

  handleConsent();


  var calculateTotalChecked = function() {
      return $('input.post_toggle:checked').length;
  }


  var activateFormElements = function() {

      togglePost = $('.beacon-by-admin-wrap #toggle-post');
      togglePage = $('.beacon-by-admin-wrap #toggle-page');


      $('.post_data').each(function() {
        $(this).attr('disabled', 'disabled');
      });

      $('.post_toggle').change(function() {

        var totalChecked = calculateTotalChecked();

        if (totalChecked > MAXPOSTS) {
          $('button.create').prop('disabled', true);
          $('.maxposts-warning').show();
        } else {
          $('button.create').prop('disabled', false);
          $('.maxposts-warning').hide();
        }

        var checked = $(this).is(':checked'),
            data = $(this).parent('div').find('.post_data');

        if ( checked )  {
          data.removeAttr('disabled');
          errMsg.fadeOut('slow');
        } else {
          data.attr('disabled', 'disabled');
        }
      });
  };
  activateFormElements();


  // accurately position create button
  var postionCreateButton = function() {

    $('.button.create').show();

    var isSafari = (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1);

    if (isSafari) {
      $('.button.create').removeClass('fixed');
      return;
    }


    $('.button.create').addClass('fixed');
    var fromTop = Math.ceil($('.beacon-by-admin-wrap .col:first').offset().top);
    $('button.create').css('top', fromTop);
  }
  postionCreateButton();



  var refreshCats = function() {

    var filtered = [],
        parent,
        found,
        tmp;

    $('.toggle-cat').each(function() {
      if ( $(this).hasClass('find') ) {
        filtered.push($(this).text());
      }
    });

    if ( filtered.length === 0 ) {
      return showAll();
    }

    filtered = filtered.join('|') + '|';

    $('.post_data').each(function() {

      var cats = $(this).data('cats').split(','),
          title = $(this).data('title') || 'blank',
          found = false, 
          i = cats.length,
          parent = $(this).parent('div').addClass('hide');

      $(this).parent('div').addClass('hide');

      while (i--) {
        tmp = cats[i] + '|';
        if (filtered.indexOf(tmp) !== -1) {
          found = true;
        }
      }

      if (found) {
        $(this).parent('div').removeClass('hide');
      } 

    });
  };


  var refreshAll = function() {
    var tags = [],
        cats = [];

    $('.toggle-tag.find').each(function() {
      tags.push($(this).text());
    });

    $('.toggle-cat.find').each(function() {
      cats.push($(this).text());
    });

    $('.post_data').each(function() {
      
      console.log(
        $(this).data('cats')
      );
      var postCats = $(this).data('cats').split(','),
          postTags = $(this).data('tags').split(','),
          title = $(this).data('title') || 'blank',
          found = false, 
          i = postCats.length,
          parent = $(this).parent('div').addClass('hide');

        while (i--) {
          tmp = postCats[i];
          if (cats.indexOf(tmp) !== -1) {
            found = true;
          }
        }

        i = postTags.length;
        while (i--) {
          tmp = postTags[i];
          if (tags.indexOf(tmp) !== -1) {
            found = true;
          }
        }

        if (found) {
          $(this).parent('div').removeClass('hide');
        } 

    });

    if (tags.length === 0 && cats.length === 0) {
      $('.post_data').each(function() {
          $(this).parent('div').removeClass('hide');
      });
    }

    searchFilter(filter.val());
  };

  var showAll = function(type) {

    if (type === 'cat') {
      $('.toggle-cat').each(function() {
        $(this).removeClass('find');
      });
    }


    if (type === 'tag') {
      $('.toggle-tag').each(function() {
        $(this).removeClass('find');
      });
    }

    $('.post_data').each(function() {
      
        $(this).parent('div').removeClass('hide');

    });


    searchFilter(filter.val());

  };

  var searchFilter = function(terms) {

    var title, 
        label,
        highlight = new RegExp(terms, 'gi'),
        str;

    terms = terms.toLowerCase();

    $('.post_data').each(function() {


      try {
        title = $(this).data('title').toLowerCase();
      } catch (e) {
        console.log('ERROR: ', e);
        title = '';
      }

      label = $(this).parent('div').find('label b');
      label.html(label.text());

      if ( title.indexOf(terms) === -1 ) {
        $(this).parent('div').hide();
      } else {
        $(this).parent('div').show();
        str = label.text();
        str = str.replace(highlight, function(str) {
          return '<i>'+str+'</i>';
        });
        label.html(str);
      }
    });
  };


  filter.keyup(function() {
    // searchFilter($(this).val());
  });
  
  
  runSearch.submit(function(e) {
    e.preventDefault();
    var terms = filter.val();
    searchFilter(terms);
    return false;
  });

  toggleVisible.change(function() {
  }); 




  $('.button.create').click(function(e) {
    e.preventDefault();
    var count = 0;
    document.querySelectorAll('.post_data').forEach((el) => {
      console.log(el.innerText);
    });
    $('.post_data').each(function() {
      // console.log($(this));
      if ( $(this).attr('disabled') ) {
      } else {
        count += 1;
      }
    });
    console.log(count);

    if ( count ) {
      form.submit();
    } else {
      errMsg.show()
          .slideDown('fast');
    }

  });


  tags.click(function(e) {
    e.preventDefault();
    $(this).toggleClass('find');
    refreshAll();
  });


  cats.click(function(e) {
    e.preventDefault();
    $(this).toggleClass('find');
    refreshAll();

  });

  showAllCat.click(function(e) {
    e.preventDefault();
    showAll('cat');
  });
  
  showAllTag.click(function(e) {
    e.preventDefault();
    showAll('tag');
  });

  clearSearch.click(function(e) {
    filter.val('');
    searchFilter('');
    filter.focus();
  });

  togglePost.click(function(e) {
    var checked = $(this).is(':checked') ? true : false;

    if (checked) {
      $('.form-row.type-post').show(); 
    } else {
      $('.form-row.type-post').hide(); 
    }
  });


  togglePage.click(function(e) {
    var checked = $(this).is(':checked') ? true : false;

    if (checked) {
      $('.form-row.type-page').show(); 
    } else {
      $('.form-row.type-page').hide(); 
    }
  });


  var perPage = BN.perPage;
  var postsRetrieved = [];

  var initGetPosts = function() {
    ajaxErrorCount = 0;
    $('.col2').hide();
    $('.beacon-by-admin-wrap form.select-posts .form-row').remove();
    $('<h1 class="loading-posts">Loading</h1>').insertAfter('.beacon-by-admin-wrap form.select-posts');
    $('<div class="loading-barWrap"><div class="loading-bar"></div></div>').insertAfter('h1.loading-posts');
    getPosts(0);
  }


  var getPosts = function(start, num) {

      start = start || 0;

      var step = ( start/perPage ) + 1,
          numSteps = Math.ceil( BN.totalPosts / perPage ),
          percentLoaded = Math.ceil( (step / numSteps) * 100 ) + '%';

      $('h1.loading-posts').text('Loading ' + percentLoaded);
      document.getElementsByClassName('loading-bar')[0].style.width = percentLoaded;


      var data = {
        'action': 'BN_get_posts',
        'from': start
      };

      $.post(ajaxurl, data)
        .done(function(response) {
          var data = $.parseJSON(response);
          ajaxErrorCount = 0;

          for (var i = 0; i < data.posts.length; i += 1) {
            postsRetrieved.push(data.posts[i]);
          }

          if (data.next < BN.totalPosts) {
             getPosts(data.next);
          } else {
            finishedGettingPosts();
          }
       })
      .fail(function(xhr, status, error) {
        ajaxErrorCount += 1;
        if (ajaxErrorCount < 3) {
          window.setTimeout(function() {
            getPosts(data.next);
          }, 2000);
        } else {
          var errorCode = typeof xhr !== 'undefined' && xhr.status || 'unknown',
              domain = window.location.hostname,
              subject = encodeURIComponent('WordPress Plugin: '+error+' on '+domain);
          $('h1.loading-posts').text('An error occured:');
          $('<h2>'+errorCode+' : '+error+'</h2><p>Please try again. If the problem persists contact <a href="mailto:eoin@beacon.by?subject='+subject+'">eoin@beacon.by</a> with the above error message</p>').insertAfter('h1.loading-posts')
          $('.loading-barWrap').hide();
          $('button.create').hide();
        }
      });

   }

  var finishedGettingPosts = function() {
    $('.beacon-by-admin-wrap h1.loading-posts').remove();
    $('.beacon-by-admin-wrap .loading-barWrap').remove();

    var tags = ['post_title', 'post_type', 'ID', 'encoded', 'main_image', 'tags', 'cats'];

    for (var i = 0; i < postsRetrieved.length; i += 1) {
      var template = parseTemplate('#formRow', tags, postsRetrieved[i]);
      $('.beacon-by-admin-wrap form.select-posts').append(template);
    }

    $('.col2').fadeIn('fast');
    ajaxErrorCount = 0;

    activateFormElements();
  }


  var parseTemplate = function(template, tags, data) {
    var i, re;
    template = $(template).html();
    for (i = 0; i < tags.length; i += 1) {
      re = new RegExp('{'+tags[i]+'}', 'g');
      template = template.replace(re, data[tags[i]]);
    }
    return template;
  }


  BN.initGetPosts = initGetPosts;

});


window.setTimeout(function() {
  BN.initGetPosts();
}, 1000);
