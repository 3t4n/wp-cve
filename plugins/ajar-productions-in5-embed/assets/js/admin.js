jQuery(document).ready(function () {
  jQuery(".customPath-trigger").on("click", function () {
    jQuery(this).slideUp('fast', function () {
      jQuery(".add_custom_path").stop().slideDown('fast', function () {
        if (localStorage.getItem("defaultPath") !== null) {
          var pathCustom = localStorage.getItem("defaultPath");
          jQuery("#customPathInput").val(pathCustom);
        }
        searchInput = jQuery("#customPathInput");
        var strLength = searchInput.val().length * 2;
        searchInput.focus();
        searchInput[0].setSelectionRange(strLength, strLength);
      });
    });
  });

  jQuery(".defPath").on("click", function () {
    jQuery("#customPathInput").val("");
    jQuery(".pathTip, .add_custom_path").slideUp('slow', function () {
      jQuery(".customPath-trigger").stop().slideDown();
    });
  });

  jQuery("#customPathInput").on("input", function () {
    if (
      jQuery(this)
      .val()
      .indexOf(".") !== -1 ||
      jQuery(this)
      .val()
      .indexOf(" ") !== -1
    ) {
      jQuery(".pathTip").slideDown();
    } else {
      jQuery(".pathTip").slideUp();
    }
  });

  var iframeData = "";
  var iframeCode = "";
  // Check if width/height is an integer or float
  function in5_is_number(value) {
    return (
      !isNaN(value) &&
      (function (x) {
        return (x | 0) === x;
      })(parseFloat(value))
    );
  }

  jQuery(document)
    .find(".in5-iframe-code")
    .wrap("<div></div>");

  // Ajax call to delete a file
  function in5_delete_permanently(id, directUrl) {
    var data = {
      action: "in5_delete_permanently",
      id: id,
      directUrl: directUrl,
      security: in5_ajax.security
    };
    jQuery.post(in5_ajax.ajaxurl, data, function(data){
      if( data == 'success' ) {
        jQuery(document).find('.in5-file-list ul li[data-in5-file-id='+id+']').remove();
        jQuery('.in5-library .side .side-content').hide();
        in5_deactivate_insert_button();
      } else {
        alert("An error occurred!");
      }
    });
  }

  // Trigger the delete process
  jQuery(document).on('click', '.in5-delete-file', function(e){
    e.preventDefault();
    if( confirm( 'Are you sure you want to do this? Changes cannot be undone!' ) ) {
      var fileID = jQuery(this).attr('data-fileid');
      var directUrl = jQuery(this).closest('.attachment-info').next().find('input.directUrl').val();
      in5_delete_permanently( fileID, directUrl );
    }
  });

  // Open the meta data to the sidebar
  function append_meta_to_side( id ) {
    jQuery('.in5-library .side .side-content').show();
    var numOfFiles = fileList['files'].length;
    var side = jQuery('.side .side-content');
    for( i=0; i<numOfFiles; i++ ) {
      var currentItem = fileList['files'][i];
      if (currentItem.id == id ) {
        var currentName = currentItem.name;
        currentName = currentName.replace(/\\/g, "");
        side.find(".in5-filename").val(currentName);
        side.find(".in5-filename").prop("readonly", "readonly");
        side
          .find(".in5-change-filename-wrap button")
          .prop("disabled", "disabled");
        side
          .find(".in5-change-filename-wrap button")
          .attr("data-fileid", currentItem.id);
        side.find(".uploaded").text(currentItem.date);
        side.find(".file-size").text(currentItem.size);
        side.find(".in5-delete-file").attr("data-fileid", currentItem.id);
        side
          .find(".in5-archive-settings")
          .find('input[type="text"].directUrl')
          .val(currentItem.directUrl);
        side
          .find(".in5-archive-settings")
          .find('input[type="text"].in5-iframe-width')
          .val(currentItem.width);
        side
          .find(".in5-archive-settings")
          .find('input[type="text"].in5-iframe-height')
          .val(currentItem.height);
      }
    }
  }

  // Enable insert button, so the iframe code can be inserted into post
  function in5_activate_insert_button() {
    jQuery('.in5-insert-button').removeAttr('disabled');
  }

  // If no selection is made or on error, deactivate the insert button
  function in5_deactivate_insert_button() {
    jQuery('.in5-insert-button').attr('disabled', 'disabled');
  }

  // Build the iframe data, this will be used not only for building the iframe code, but also for many other functions
  function in5_build_iframe_data( fileID ) {
    var directUrl = jQuery('.attachment-info').next().find('input.directUrl').val();
    directUrl = directUrl.replace( 'http:', '' );
    var width = jQuery('.attachment-info').next().find('input.in5-iframe-width').val();
    var height = jQuery('.attachment-info').next().find('input.in5-iframe-height').val();
    if( width.indexOf('%') >= 0 ) {
      var newWidth = width;
    }
    else {
      var newWidth = width + 'px';
    }
    if( height.indexOf('%') >= 0 ) {
      var newHeight = height;
    }
    else {
      var newHeight = height + 'px';
    }
    iframeData = {'fileID': fileID, 'directUrl':directUrl, 'width': width, 'height':height, 'newWindow': 'no', 'allowFull': 'no', 'disable_scrolling': 'no', 'hide_frame_border': 'no'};
    iframeCode = '<div class="in5-iframe-wrapper"><iframe class="in5-iframe-code" src="'+directUrl+'" width="'+width+'" height="'+height+'" style="width: '+newWidth+'; height: '+newHeight+';" data-open-in-new-window="no" data-allow-fullscreen="no"></div>';
  }

// Build the final iframe code
   function in5_build_iframe_code( iframeData ) {
    var iframeAttributes = '';
    var iframeStyles = '';
    if( iframeData.disable_scrolling !== 'no' ) {
      iframeAttributes = 'scrolling="no" ';
      iframeStyles = 'overflow:hidden; ';
    }
    if( iframeData.hide_frame_border !== 'no' ) {
      iframeAttributes = iframeAttributes + 'frameBorder="0" ';
      iframeStyles = iframeStyles + 'border:none; box-shadow:none; ';
    }
    var width = iframeData.width;
    var height = iframeData.height;
    if( width.indexOf('%') >= 0 ) {
      var newWidth = width;
    }
    else {
      var newWidth = width + 'px';
    }
    if( height.indexOf('%') >= 0 ) {
      var newHeight = height;
    }
    else if( height.toLowerCase() == 'auto' ) {
      var newHeight = height;
    }
    else {
      var newHeight = height + 'px';
    }
    iframeCode = '<div class="in5-iframe-wrapper"><iframe '+iframeAttributes+'class="in5-iframe-code" src="'+iframeData.directUrl+'" width="'+iframeData.width+'" height="'+iframeData.height+'" style="'+iframeStyles+'width: '+newWidth+'; height: '+newHeight+';" data-open-in-new-window="'+iframeData.newWindow+'" data-allow-fullscreen="'+iframeData.allowFull+'" data-responsive-h="'+iframeData.responsiveH+'" data-orig-height="'+iframeData.height+'"></iframe></div>';
  }
  
  // When "responsive height" is checked or unchecked, change the value in iframeData
  jQuery(document).on('change', 'input.in5-iframe-responsiveH', function(){
    iframeData.responsiveH = jQuery(this).is(':checked') ? 'yes' : 'no';
    in5_build_iframe_code(iframeData);
  });

  // When "open in new window" is checked or unchecked, change the value in iframeData
  jQuery(document).on('change', 'input.open_in_new_window', function(){
    if( jQuery(this).is(':checked') ) {
      iframeData.newWindow = 'yes';
    }
    else {
      iframeData.newWindow = 'no';
    }
    in5_build_iframe_code(iframeData);
  });

  // When "allow fullscreen" is checked or unchecked, change the value in iframeData
  jQuery(document).on("change", "input.allow_fullscreen", function () {
    if (jQuery(this).is(":checked")) {
      iframeData.allowFull = "yes";
    } else {
      iframeData.allowFull = "no";
    }
    in5_build_iframe_code(iframeData);
  });

  // When "disable scrolling" is checked or unchecked, change the value in iframeData
  jQuery(document).on("change", "input.disable_scrolling", function () {
    if (jQuery(this).is(":checked")) {
      iframeData.disable_scrolling = "yes";
    } else {
      iframeData.disable_scrolling = "no";
    }
    in5_build_iframe_code(iframeData);
  });

  // When "hide frame border" is checked or unchecked, change the value in iframeData
  jQuery(document).on("change", "input.hide_frame_border", function () {
    if (jQuery(this).is(":checked")) {
      iframeData.hide_frame_border = "yes";
    } else {
      iframeData.hide_frame_border = "no";
    }
    in5_build_iframe_code(iframeData);
  });

  // Reset the iframe code when the file is inserted
  function in5_clear_iframe_code() {
    iframeCode = '';
  }

  // When you click on a file in library, either deselect a selected file or select a file
  jQuery(document).on('click', '.in5-file-list li', function(e){
    jQuery('.in5-library .side .side-content').hide();
    in5_clear_iframe_code();
    if( jQuery(this).hasClass('active') ) {
      jQuery('.in5-file-list li').removeClass('active');
      in5_deactivate_insert_button();
    }
    else {
      jQuery('.in5-file-list li').removeClass('active');
      jQuery(this).addClass('active');
      var fileID = jQuery(this).data('in5-file-id');
      append_meta_to_side( fileID );
      in5_activate_insert_button();
      in5_build_iframe_data(fileID);
    }
  });

  // Trigger the upload on "select files"
  jQuery('.in5-select-button').on('click', function(e){
    e.preventDefault();
    jQuery('#in5-file-upload').trigger( 'click' );
    jQuery('.in5-file-list li').removeClass('active');
  });

  // Switch tabs
  jQuery('.in5-tabs .tab').on('click', function(e){
    e.preventDefault();
    var tab = jQuery(this).data('tab');
    var pane = '.'+tab;
    jQuery('.pane').hide();
    jQuery(pane).show();
    jQuery('.in5-tabs .tab').removeClass('active');
    jQuery(this).addClass('active');
  });

  // Reset state after upload
  function in5_after_upload() {
    var tab = jQuery('.tab-library');
    tab.trigger('click');
    jQuery('#in5-progress-bar').hide();
    jQuery('.in5-upload-overlay').hide();
    jQuery('#in5-progress-bar .bar').css( 'width', '0%' );
  }

  // Add the newly uploaded file to the file list and select it automatically
  function in5_add_uploaded_file_list( files ) {
    var parentElem = jQuery('.in5-file-list ul');
    parentElem.prepend('<li class="active" data-in5-file-id="'+files[0].id+'"><img src="'+pluginDirUrl+'assets/img/archive.png"><div class="in5-file-title"><span>'+files[0].name+'</span></div></li>');
    append_meta_to_side( files[0].id );
    in5_activate_insert_button();
    in5_build_iframe_data(files[0].id);
  }

  // Resets upload window on error
  function in5_reset_upload() {
    jQuery('#in5-progress-bar').hide();
    jQuery('.in5-upload-overlay').hide();
    jQuery('#in5-progress-bar .bar').css( 'width', '0%' );
  }

  jQuery("#in5-file-upload").bind("fileuploadsubmit", function (e, data) {
    data.formData = {
      customPath: jQuery("#customPathInput").val()
    };
    localStorage.setItem("defaultPath", jQuery("#customPathInput").val());
  });

  // Handle file upload
  jQuery(function($) {
    $('#in5-file-upload').fileupload({
      dropZone: jQuery('.in5-embed-popup'),
      dataType: 'json',
      start:function(e,data){
        $('.in5-tabs .tab-upload').not('.active').trigger('click');
      },
      progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.in5-upload-overlay').show();
        $('#in5-progress-bar').show();
        $('#in5-progress-bar .bar').css(
          'width',
          progress + '%'
        );
      },
      done: function (e, data) {
        alert("Archive uploaded");
        fileList["files"].push(data.result.files[0]);
        // localStorage.setItem("defaultPath", data.result.directory);
        // jQuery("#customPathInput").val(data.result.directory);
        in5_after_upload();
        in5_add_uploaded_file_list(data.result.files);
      },
      error: function (e, data) {
        alert("Error, please contact support@ajarproductions.com and provide this info:\r" + e.responseText);
        in5_reset_upload();
      }
    });
  });

  // Reset modal window state and reset all the values on successful upload
  function in5_reset_modal_state() {
    jQuery('.in5-file-list li').removeClass('active');
    jQuery('.in5-library .side .side-content').hide();
    in5_deactivate_insert_button();
    jQuery('.open_in_new_window').prop('checked', '');
    jQuery('.allow_fullscreen').prop('checked', '');
    jQuery('.disable_scrolling').prop('checked', '');
    jQuery('.hide_frame_border').prop('checked', '');
    jQuery('.in5-iframe-responsiveH').prop('checked', '');
    iframeData = '';
    iframeCode = '';
  }

  // Close the modal
  jQuery('.in5-popup-header .media-modal-close').on('click', function(e){
    e.preventDefault();
    jQuery('.in5-embed-popup').hide();
    jQuery('.media-modal-backdrop').hide();
  });

  // Open the modal and put focus on upload window if there are no uploaded files
  jQuery('.in5-media-button').on('click', function(e){
    e.preventDefault();
    jQuery('.in5-embed-popup').show();
    jQuery('.media-modal-backdrop').show();
    if( jQuery('.in5-file-list li').length < 1 ) {
      jQuery('.in5-library.pane').hide();
      jQuery('.in5-upload.pane').show();
      jQuery('.tab-library').removeClass('active');
      jQuery('.tab-upload').addClass('active');
    }
  });

  // Insert the iframe code and reset everything
  jQuery(document).on('click', '.in5-insert-button', function(){
    if( in5_set_width() === false ) return false;
    if( in5_set_height() === false ) return false;
    var isBlockEditor = (wp.blocks || jQuery('.gutenberg').length) && location.search.indexOf('classic-editor=1')<0;
    if(!isBlockEditor) window.parent.send_to_editor( iframeCode );
    window.parent.tb_remove();
    jQuery('.in5-embed-popup').hide();
    jQuery('.media-modal-backdrop').hide();

    if(isBlockEditor) {
      var newIframeCode = iframeCode.replace('<div class="in5-iframe-wrapper">', '').replace('</div>', '');

      var blocks = wp.data.select('core/editor').getBlocks(),
          clientId = '';
      for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].name === 'cgb/block-in5-wp-embed' && blocks[i].attributes.active === 'open') {
          clientId = blocks[i].clientId;
        }
      }

      wp.data.dispatch('core/editor').updateBlockAttributes(clientId, { content: newIframeCode, active: 'close', mode: 'preview' });
    }

    in5_save_values(iframeData.fileID);
    in5_reset_modal_state();
  });

  // Enable input and save button on file name change
  jQuery(document).on('click', '.in5-change-filename-wrap', function(){
    jQuery(this).find('.in5-filename').removeProp('readonly');
    jQuery(this).find('.in5-filename').focus();
    jQuery(this).find('button').removeProp('disabled');
  });

  // Ajax call to save the file name
  function in5_save_filename(fileID, fileName) {
    var data = {
      'action': 'in5_change_filename',
      'security': in5_ajax.security,
      'id': fileID,
      'fileName': fileName
    };

    if( fileName == '' ) {
      alert('File name cannot be empty!');
      return false;
    }

    jQuery.post(in5_ajax.ajaxurl, data, function(response){
      if( response !== "success" ) {
        alert('Please double check the file name you entered and try again!');
        return false;
      }
      jQuery('.in5-filename').prop('readonly', 'readonly');
      jQuery('button.in5-change-filename').prop('disabled', 'disabled');
      var numOfFiles = fileList['files'].length;
      for( i=0; i<numOfFiles; i++ ) {
        var currentItem = fileList['files'][i];
        if( fileID == currentItem.id ) {
          currentItem.name = fileName;
        }
      }
      jQuery('.in5-file-list ul li').each(function(index, el){
        if( jQuery(this).data('in5-file-id') == fileID ) {
          jQuery(this).find('.in5-file-title span').text(fileName);
          return false;
        }
      });
    });
  }

  // Trigger the file name save
  jQuery(document).on('click', 'button.in5-change-filename', function(e){
    e.preventDefault();
    var fileID = jQuery(this).attr('data-fileid');
    var fileName = jQuery(this).closest('.in5-change-filename-wrap').find('.in5-filename').val();
    in5_save_filename(fileID, fileName);
  });

  // Validate width and set it
  function in5_set_width() {
    var width = iframeData.width;
    if (in5_is_number(width)) {
      in5_activate_insert_button();
      in5_build_iframe_code(iframeData);
    } else {
      var currentWidth = iframeData.width;
      var newWidth = currentWidth.replace('%', '');
      if( in5_is_number(newWidth) ) {
        in5_activate_insert_button();
        in5_build_iframe_code(iframeData);
      } else {
        alert(
          "Invalid width value! Must be an integer, default unit is px, you can specify %."
        );
        in5_deactivate_insert_button();
        return false;
      }
    }
  }

  // Validate height and set it
  function in5_set_height() {
    var height = iframeData.height;
    if( in5_is_number(height) || (height.toLowerCase() == 'auto') ) {
      in5_activate_insert_button();
      in5_build_iframe_code(iframeData);
    } else {
      var currentHeight = iframeData.height;
      var newHeight = currentHeight.replace('%', '');
      if( in5_is_number(newHeight) ) {
        in5_activate_insert_button();
        in5_build_iframe_code(iframeData);
      } else {
        alert(
          "Invalid height value! Must be an integer, default unit is px, you can specify %."
        );
        in5_deactivate_insert_button();
        return false;
      }
    }
  }

  // When width input field is out of focus, set the new width in the iframe data
  jQuery(document).on('focusout', '.in5-iframe-width', function(){
    iframeData.width = jQuery(this).val().replace(/\s*px/i, '');
    in5_activate_insert_button();
  });

  // When height input field is out of focus, set the new height in the iframe data
  jQuery(document).on('focusout', '.in5-iframe-height', function(){
    iframeData.height = jQuery(this).val().replace(/\s*px/i, '');
    in5_activate_insert_button();
  });

  // Save width and height values in the json file
  function in5_save_values(fileID) {
    var width = iframeData.width;
    var height = iframeData.height;
    var data = {
      'action': 'in5_save_attributes',
      'security': in5_ajax.security,
      'id': fileID,
      'width': width,
      'height': height
    };

    jQuery.post(in5_ajax.ajaxurl, data, function (response) {
      if (response !== "success") {
        alert("The attributes you entered are incorrect!");
        return false;
      }
      var numOfFiles = fileList['files'].length;
      for( i=0; i<numOfFiles; i++ ) {
        var currentItem = fileList['files'][i];
        if( fileID == currentItem.id ) {
          currentItem.width = width;
          currentItem.height = height;
        }
      }
    });
  }
});