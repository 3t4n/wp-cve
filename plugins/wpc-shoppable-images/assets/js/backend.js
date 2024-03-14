(function($) {
  'use strict';

  if (!$('#wpcsi-items').length) {
    return;
  }

  var items = JSON.parse($('#wpcsi-items').val());
  var metaItemTemplate = wp.template('wpcsi-meta-item');

  function onClickTag(element) {
    let $this = $(element), key = $this.data('key');
    $('#wpcsi-image .wpcsi-tag').removeClass('active');
    $this.addClass('active');
    $('#wpcsi-item-meta .item-meta').removeClass('active');
    $($('#wpcsi-item-meta .item-meta').get(key)).addClass('active');
  }

  var eventDrag = {
    containment: '#wpcsi-image', start: function() {
      onClickTag(this);
    }, drag: function() {
      let $this = $(this), $wrapper = $('#wpcsi-image img'),
          img_w = $wrapper.width(), img_h = $wrapper.height(),
          offsetX = $this.css('left').replace('px', ''),
          offsetY = $this.css('top').replace('px', ''),
          posTop = offsetY / img_h * 100, posLeft = offsetX / img_w * 100;

      posTop = Math.round((posTop + Number.EPSILON) * 100) / 100;
      posLeft = Math.round((posLeft + Number.EPSILON) * 100) / 100;

      $this.find('span').
          attr('aria-label', 'top: ' + posTop + '%; left: ' + posLeft + '%');
    }, stop: function() {
      let $wrapper = $('#wpcsi-image img'), $this = $(this);
      setPositionTag($wrapper, $this, $this.data('item'));
      $('#wpcsi-items').val(getTags());
    },
  };

  function generateTagSetting(count) {
    let $html = metaItemTemplate({key: count});
    $('#wpcsi-item-meta .item-meta').removeClass('active');
    $('#wpcsi-item-meta').append($html);
    $(document.body).trigger('wc-enhanced-select-init');
  }

  function getTags() {
    let items = [];
    let tags = $('#wpcsi-image .wpcsi-tag').get();
    for (let tag of tags) {
      items.push($(tag).data('item'));
    }
    return JSON.stringify(items);
  }

  function setPositionTag($wrapper, $item, item) {
    let img_w = $wrapper.width(), img_h = $wrapper.height(),
        offsetX = $item.css('left').replace('px', ''),
        offsetY = $item.css('top').replace('px', ''),
        posTop = offsetY / img_h * 100, posLeft = offsetX / img_w * 100;

    posTop = Math.round((posTop + Number.EPSILON) * 100) / 100;
    posLeft = Math.round((posLeft + Number.EPSILON) * 100) / 100;

    item.position = {top: posTop, left: posLeft};
    $item.css('top', posTop + '%').
        css('left', posLeft + '%').
        data('item', item);
    $item.find('span').
        attr('aria-label', 'top: ' + posTop + '%; left: ' + posLeft + '%');
  }

  $('#wpcsi-image .wpcsi-tag').draggable(eventDrag);

  $('#wpcsi-image').on('click', '.wpcsi-tag', function(event) {
    $('#wpcsi-image .wpcsi-tag').removeClass('active');
    $(this).addClass('active');
    onClickTag(this);
  }).on('dblclick', '.wpcsi-tag', function(event) {
    let $this = $(this), key = $this.data('key');
    $('html, body').animate({
      scrollTop: $('#wpcsi-meta-item-key-' + key).offset().top,
    }, 1000);
  });

  $('#wpcsi-image img').on('click', function(event) {
    let img_w = $(this).width(), img_h = $(this).height(),
        offsetX = event.offsetX - 12.5, offsetY = event.offsetY - 12.5,
        posTop = offsetY / img_h * 100, posLeft = offsetX / img_w * 100,
        item = {}, count = $('#wpcsi-image').data('count') + 1;

    posTop = Math.round((posTop + Number.EPSILON) * 100) / 100;
    posLeft = Math.round((posLeft + Number.EPSILON) * 100) / 100;

    item.position = {top: posTop, left: posLeft};
    items.push(item);

    $('#wpcsi-image .wpcsi-tag').removeClass('active');

    let $itemDom = $('<span class="wpcsi-tag active" data-key="' + (count - 1) +
        '"><span class="hint--top" aria-label="top: ' + posTop + '%; left: ' +
        posLeft + '%">' + count + '</span></span>').
        draggable(eventDrag).
        css({top: posTop + '%', left: posLeft + '%'}).
        data('item', item);
    $('#wpcsi-image').data('count', count);
    $('#wpcsi-image').append($itemDom);

    $('#wpcsi-items').val(JSON.stringify(items));

    generateTagSetting(count);
  });

  //accordion
  $('#wpcsi-item-meta').on('click', '.item-meta', function(e) {
    let $this = $(this), key = $this.data('key');
    $('#wpcsi-item-meta .item-meta').removeClass('active');
    $this.addClass('active');
    $('#wpcsi-image .wpcsi-tag').removeClass('active');
    $($('#wpcsi-image .wpcsi-tag').get(key)).addClass('active');
  });

  $('#wpcsi-item-meta').on('change', 'input,select,textarea', function(event) {
    let $this = $(this), name = $this.attr('name'), value = $this.val(),
        $item = $('#wpcsi-image .wpcsi-tag.active'), item = $item.data('item');
    if (typeof item.settings === 'undefined') {
      item.settings = {};
    }

    item.settings[name] = value;
    $item.data('item', item);
    $('#wpcsi-items').val(getTags());

    if (name === 'content') {
      if (value === 'text') {
        $this.closest('tr.wpcsi_configuration_tr').
            removeClass('content-products content-text').
            addClass('content-text');
      } else {
        $this.closest('tr.wpcsi_configuration_tr').
            removeClass('content-products content-text').
            addClass('content-products');
      }
    }

  }).on('click', '.item-remove', function(e) {
    e.preventDefault();
    let $this = $(this), $wrapper = $this.closest('.item-meta'),
        key = $wrapper.data('key');
    $wrapper.remove();
    $('#wpcsi-image .wpcsi-tag[data-key="' + key + '"]').remove();
    $('#wpcsi-items').val(getTags());
  });

  $('#wpcsi-preview').on('click touch', '.wpcsi-ruler-item', function(event) {
    event.preventDefault();
    $('.wpcsi-ruler-item').removeClass('active');

    let $this = $(this), width = $this.data('width');

    $this.addClass('active');

    $('#wpcsi-image').css('width', width + 'px');
  });

  var wpcsi_frame;

  $(document).on('click touch', '#wpcsi-image-add-btn', function(e) {
    e.preventDefault();

    // If the media frame already exists, reopen it.
    if (wpcsi_frame) {
      // Open frame
      wpcsi_frame.open();
      return;
    }

    // Create the media frame.
    wpcsi_frame = wp.media.frames.wpcsi_frame = wp.media({
      title: 'Select a image to upload', button: {
        text: 'Use this image',
      }, multiple: false,	// Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    wpcsi_frame.on('select', function() {
      // We set multiple to false so only get one image from the uploader
      var attachment = wpcsi_frame.state().
          get('selection').
          first().
          toJSON();

      // Do something with attachment.id and/or attachment.url here
      $('#wpcsi-image').addClass('has-image');
      $('#wpcsi-image-add-btn').html('Change image');
      $('#wpcsi-image img').attr('src', attachment.url);
      $('#_thumbnail_id').val(attachment.id);
    });

    // Finally, open the modal
    wpcsi_frame.open();
  });
})(jQuery);

