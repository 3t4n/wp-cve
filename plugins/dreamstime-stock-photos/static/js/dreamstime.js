
(function( $ ){

  dt_tab = function(index)
  {
    index = index - 1;
    $( "#dt_tabs" ).tabs({ active: index });
  }

  dt_error = function(error)
  {
    $('#error').html( error );
    $('#error').dialog('open');
  }

  dt_loading = function(action)
  {
    $('#loading').dialog(action);
  }

  dt_dialog = function (container, action, data)
  {
    var dialog = $('#'+container).dialog(action);
    if(data && data != undefined)
      $('#'+container).html(data);

    return dialog;
  }

  dt_checkUsername = function(username, nonce)
  {
    dt_loading('open');
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'checkUsername', username: username, _wpnonce: nonce},
      dataType: 'html'
    }).done(function(data){
        dt_loading('close');
        $('#availability').html(data);
      });
  }

  setUsername = function(username)
  {
    $('#username').val(username);
  }

  dt_getImage = function(image)
  {
    dt_loading('open');
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'getImage', image: image},
      dataType: 'html'
    }).done(function(data){
        dt_loading('close');
        var dialog = dt_dialog('image', 'open', data);
      });
  }

  dt_downloadImage = function(image, size, nonce1, nonce2)
  {
    dt_loading('open');
    var data = {action: 'downloadImage', image: image, _wpnonce: nonce1};
    if(size && size != undefined) {
      data.size = size;
    }

    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: data,
      dataType: 'html'
    }).done(function(data) {
      dt_loading('close');

      if (isGutenbergActive()) {
        const win = opener || parent || top;
        // go to Media Library tab
        win.wp.media.frame.content.mode('browse');

        const collection = win.wp.media.frame.content.get().collection;
        const selection = win.wp.media.frame.state().get('selection');
        selection.reset();

        // select downloaded image when collection is refreshed
        collection.off('attachments:received').on('attachments:received', function (attachments) {
          if (!selection.length) {
            selection.add(collection.first());
          }
        });

        // refresh collection
        collection._requery(true);
      } else {
        //display `Insert into post` modal
        dt_dialog('image', 'open', data);
        dt_refreshAccountInfo(nonce2);
      }

    });
  }

  dt_login = function()
  {
    dt_dialog('dt_login', 'open');
    $('#login_btn').click(function(){
      dt_loading('open');
      var data = $('#login-form').serialize();
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        dataType: 'json'
      }).done(function(data){
          dt_loading('close');
          dt_dialog('dt_login', 'close');
          dt_getImage($('.dt_image').attr('rel'));
          $('#dt_search_tab').html(data.search);
          $('#dt_my_account_tab').html(data.account);
        });
    });
  }

  dt_refreshAccountInfo = function(nonce)
  {
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'refreshAccountInfo', _wpnonce: nonce},
      dataType: 'html'
    }).done(function(data){
        $('.account_info').html(data);
      });
  }

  dt_toggleReferral = function(state, nonce)
  {
    dt_loading('open');
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'toggleReferral', state: state, _wpnonce: nonce}
    }).done(function(data){
        dt_loading('close');
      });
  }


  dt_search = function(keywords)
  {
    dt_loading('open');
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'ajxSearch', keywords: keywords}
    }).done(function(data){
      dt_loading('close');
      $('#dt_search_tab').html(data);
    });
  }

  dt_review = function(action)
  {
    dt_loading('open');
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {action: 'ajxReview', data: action}
    }).done(function(data){
      dt_loading('close');
      $('.review-note').remove();
    });
  }

  initialSearch = function(lastKeywords) {
    //search by post title
    let keywords = '';
    const postTitle = _getPostTitle();
    if(postTitle.length) {
      keywords = postTitle;
    } else {
      //search post by content
      const postContent = _getContentFromActiveEditor();
      keywords = _getMostFrequentlyWords(postContent);
    }
    if(keywords != lastKeywords || typeof lastKeywords === 'undefined') {
      $('#keywords').val(keywords);
      dt_search(keywords);
    }
  }

  _getPostTitle = function () {
    let postTitle = '';
    const win = opener || parent || top;
    if (isGutenbergActive()) {
      const editor = win.wp.data.select("core/editor");
      postTitle = $.trim(editor.getEditedPostAttribute('title'));
    } else {
      postTitle = win.document.getElementById('title');
      postTitle = $.trim($(postTitle).val());
      postTitle = $('<div/>').html(postTitle).text(); //strip html tags
    }
    return postTitle;
  }

  _getContentFromActiveEditor = function() {
    let postContent = '';
    const win = opener || parent || top;
    if (isGutenbergActive()) {
      const editor = win.wp.data.select("core/editor");
      postContent = _stripPostContent(editor.getEditedPostContent());
    } else {
      const editorWrap = win.document.getElementById('wp-content-wrap');
      const isTextEditor = $(editorWrap).hasClass('html-active');
      if (isTextEditor) {
        postContent = $(editorWrap).find('#content').val();
      } else {
        postContent = $(editorWrap).find('#content_ifr').contents().find('body#tinymce').html();
      }
    }
    return _stripPostContent(postContent);
  }

  _stripPostContent = function(postContent) {
    //strip dreamstime images credits
    postContent = postContent.replace(/<dd[\s\S]+class="wp-caption-dd"[\s\S]+<\/dd>/igm, '');

    //strip [shortcode ...] lorem ipsum [/shortcode] and [shortcode ...]
    postContent = postContent.replace(/\[[^\[]+\[\/[a-z]+\]|\[[^\]]+\]/igm, '');

    //strip html tags
    postContent = $('<div/>').html(postContent).text();

    //replace new lines with spaces
    postContent = postContent.replace(/\n/gi,' ');

    return postContent;
  }

  _getMostFrequentlyWords = function(postContent) {
    var contentArr = postContent.split(' ');
    var keywords = [];
    var values = [];
    $.each(contentArr, function(index, value){
      value = value.replace(/[^a-zA-Z]+/g, '');
      if(value.length >= 4) {
        var re = new RegExp(value, 'g');
        var matched = postContent.match(re);
        if(matched && values.indexOf(value) == -1) {
          values.push(value);
          var count = matched.length;
          keywords.push({count: count, value: value});
        }
      }
    });
    //order by count desc
    keywords.sort(function(a, b){
      return b.count - a.count;
    });

    var keywordsArr = [];
    if(keywords.length >= 3) {
      $.each(keywords, function(index, obj){
        if(index < 5){
          keywordsArr.push(obj.value);
        }
      });
    }

    return keywordsArr.join(' ');
  }

  isGutenbergActive = function() {
    const win = opener || parent || top;
    return win.document.body.classList.contains('block-editor-page');
  }

  dt_more = function(container_id, params)
  {

    var container = $('#'+container_id);
    $('#'+container_id).dtMore({
      'items': '.dt_image_th',
      'contentPage': ajaxurl,
      'contentData': params,
      'total_items': container.attr('rel'),
      'beforeLoad': function(){
        container.children('.dt_clear').hide();
        container.children('.dt_progressbar').show();
      },
      'afterLoad': function(data) {
        container.children('.dt_clear').show();
        container.children('.dt_progressbar').hide();
      }
    });
  }

  $.fn.dtMore = function(options)
  {
    var defaults = {
      more :        '.more',
      items:        '.items',
      total_items:  20
    };

    var opts = $.extend({}, defaults, options);

    return this.each(function() {
      var container = $(this);
      var more = container.find(opts.more);
      more.click(function(){
        $.fn.dtMore.loadContent(container, opts);
      });
    });
  };

  $.fn.dtMore.loadContent = function(obj, opts){
    if (opts.beforeLoad != null){
      opts.beforeLoad();
    }
    $.ajax({
      type: 'POST',
      url: opts.contentPage,
      data: opts.contentData,
      dataType: 'html'
    }).done(function(data){
        var more = obj.find(opts.more);
        more.parent().before(data);

        if (opts.afterLoad != null){
          opts.afterLoad(data);
        }
        if(obj.children(opts.items).length >= opts.total_items) {
          more.remove();
        }
      }).fail(function(data){
        opts.afterLoad(data);
      });
  };


  $(document).ready(function(){

    /**
     * Daryl Koopersmith:
     * wp.media.editor is used to manage instances of editor-specific media managers.
     * If you're looking to trigger an event when opening the default media modal,
     * you'll want to grab a reference to the media manager by calling wp.media.editor.add('content').
     * We're calling "add" here instead of "get" to make sure the modal exists, because "get" may
     * return undefined (and don't worry, "add" only creates the instance once).
     * You can then call the .on method on that object and your code will run just fine.
     */

    $('a.insert-media').click(function(){
      var mediaModal = wp.media.editor.get(wpActiveEditor);
      if(mediaModal && typeof mediaModal != 'undefined') {
        if(mediaModal.state().id == 'iframe:dreamstime'){
          if($('.media-iframe > iframe').contents().find('#dt_tabs').length == 0 || true) {//forcing ...
            mediaModal.setState('insert');
            mediaModal.setState('iframe:dreamstime');
          }
        }
      }
    });

    $('#dreamstime-media-button').click(function(){
      var mediaModal = wp.media.editor.open(wpActiveEditor);
      if(mediaModal.state().id == 'iframe:dreamstime'){
        if($('.media-iframe > iframe').contents().find('#dt_tabs').length == 0 || true) { //forcing ...
          mediaModal.setState('insert');
          mediaModal.setState('iframe:dreamstime');
        }
      } else {
        mediaModal.setState('iframe:dreamstime');
      }
    });

    $(document.body).on('click', 'a.wp-post-thumbnail' ,function(event){
      var thumbnailId = parseInt($(event.target).attr('id').substr($(event.target).attr('id').lastIndexOf('-') + 1));
      var alt = $('tr.post_excerpt textarea').val();
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'ajxSetPostThumbnailAlt',
          alt: alt,
          thumbnail_id: thumbnailId
        },
        dataType: 'html'
      })
    });
  });

}( jQuery ));
