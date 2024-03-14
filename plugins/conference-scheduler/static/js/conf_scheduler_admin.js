var conf_scheduler_admin = { selectedIDs: [] };


jQuery( document ).ready( function( $ ) {
  $body = $('body');
  var isSession = $body.hasClass('taxonomy-conf_sessions');
  var isOptions = $body.hasClass('conf_workshop_page_conf_scheduler_options');
  var isWorkshop = ($body.hasClass('post-type-conf_workshop') && ( $body.hasClass('post-php') || $body.hasClass('post-new-php') ) );
  if(isOptions) { isWorkshop = false; }

  // Setup date/time formats
  var locale = conf_scheduler_ldata.locale.substring(0,2);
  var phpTimeFormat = conf_scheduler_ldata.timeFormat.trim();
  var showMeridian = ['g','h'].indexOf(phpTimeFormat.substring(0,1));
  showMeridian = showMeridian != -1 ? true : false;
  var timeFormat = 'yyyy-mm-dd ';
  switch (phpTimeFormat.substring(0,1)) {
    case 'g':
      timeFormat += 'H'; break;
    case 'h':
      timeFormat += 'HH'; break;
    case 'G':
      timeFormat += 'h'; break;
    case 'H':
      timeFormat += 'hh'; break;
  }
  timeFormat += ':ii';
  if(showMeridian) timeFormat += ' p';

  if(isSession) {
    $('#start').cs_datetimepicker({
      format: timeFormat,
      todayBtn:  1,
  		autoclose: 1,
  		todayHighlight: 1,
  		forceParse: 0,
      language: locale,
      showMeridian: showMeridian,
      keyboardNavigation: false,
      pickerPosition: 'bottom-left'
    });

  }

  if(isWorkshop) {

    // Call select2 on sessions select
    $('#session').select2();

    if ($('#start_time').length) {
      $('#start_time').cs_datetimepicker({
        format: timeFormat,
        todayBtn:  1,
    		autoclose: 1,
    		todayHighlight: 1,
    		forceParse: 0,
        language: locale,
        showMeridian: showMeridian,
        keyboardNavigation: false,
        pickerPosition: 'bottom-left'
      });
    }

    // Uploading files
  	var file_frame;
  	var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id

    conf_scheduler_admin.renderAttachments = function() {
      $( '#file_attachments' ).html('');
      conf_scheduler_admin.selectedIDs.forEach(function(ID) {
        var a = wp.media.attachment(ID).attributes;
        var preview = a.sizes ? a.sizes.thumbnail.url : a.icon;
        $( '#file_attachments' ).append('<div class="workshop_file" data-workshopid="'+a.id+'"><div class="preview"><img src="'+preview+'"></div><span class="title">'+a.title+'</span>File Name: <a href="'+a.url+'">'+a.filename+'</a><br/>File Size: '+a.filesizeHumanReadable+'<a class="remove_file"></a></div>');
      });
      if (conf_scheduler_admin.selectedIDs.length == 0) {
        $( '#file_attachments' ).html('No attachments selected.');
      }
      $('.workshop_file .remove_file').click(function(event) {
        event.preventDefault();
        conf_scheduler_admin.selectedIDs = conf_scheduler_admin.selectedIDs.filter(e => e !== $(this).parent().data('workshopid'));
        $( '#file_attachments_id' ).val(conf_scheduler_admin.selectedIDs.join());
        $(this).parent().remove();
      });

      $( '#file_attachments_id' ).val( conf_scheduler_admin.selectedIDs.join() );

    }

    //preload selected attachments
    if($( '#file_attachments_id' ).val())
      conf_scheduler_admin.selectedIDs = $( '#file_attachments_id' ).val().split(',').map(Number);
    wp.media.query({ post__in: conf_scheduler_admin.selectedIDs })
      .more()
      .then(function() {
        conf_scheduler_admin.renderAttachments();
      });

  	jQuery('#upload_file_button').on('click', function( event ){
  		event.preventDefault();

  		// If the media frame already exists, reopen it.
  		if ( file_frame ) {
  			// Set the post ID to what we want
  			//file_frame.uploader.uploader.param( 'post_id', selected_media_id );
  			// Open frame
  			file_frame.open();
  			return;
  		} else {
  			// Set the wp.media post id so the uploader grabs the ID we want when initialised
  			//wp.media.model.settings.post.id = selected_media_id;

    		// Create the media frame.
    		file_frame = wp.media.frames.file_frame = wp.media({
    			title: 'Select workshop attachments',
    			button: {
    				text: 'Select',
    			},
    			multiple: true	// Set to true to allow multiple files to be selected
    		});

    		// When an image is selected, run a callback.
    		file_frame.on( 'select', function() {
    			// We set multiple to false so only get one image from the uploader
    			var attachments = file_frame.state().get('selection');
          conf_scheduler_admin.selectedIDs = attachments.models.map(function(a) { return a.attributes.id; });

          conf_scheduler_admin.renderAttachments();
    		});

        file_frame.on('open',function() {
          var selection = file_frame.state().get('selection');
          conf_scheduler_admin.selectedIDs.forEach(function(id) {
            // all attachments are prefetched at load or on select
            attachment = wp.media.attachment(id);
            selection.add( attachment ? [ attachment ] : [] );
          });
        });

  			file_frame.open();
      }
  	});
  }

  if(isOptions) {
    $('#show-system-status').click(function(e){
      e.preventDefault();
      $(this).hide();
      $('#cs-system-status').show().select();
    });

    $('.remove_data button').click(function(e){
      e.preventDefault();

      // Check to make sure it wasn't accidental
      var action = $(this).data('action');
      var proceed = window.confirm(conf_scheduler_ldata.i18n[action]);
      if (! proceed ) { return false; }

      $(this).find('span').removeClass('dashicons-trash').addClass('dashicons-update');
      $.ajax( {
          url : ajaxurl,
          type: 'POST',
          context: this,
          data: {
              action  : 'conf_scheduler_delete_data',
              dataType: $(this).data('action'),
              cs_nonce: conf_scheduler_admin.cs_nonce
          },
          success: function( response ) {
            var results = JSON.parse(response);
            if(results.error) {
              alert('Error: There was an error deleting the data. Please try again or contact support.');
            } else {
              $('.remove_data').after('<div class="updated settings-error notice is-dismissible cs-data-deleted"><p>'+results.msg+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
              $('#conf_scheduler_options .notice-dismiss').on("click.wp-dismiss-notice", function(e) {
                e.preventDefault();
                var p = $(this).parent();
                p.fadeTo(100, 0, function(){p.slideUp(100, function(){p.remove()})});
              });
              $(this).find('span').removeClass('dashicons-update').addClass('dashicons-trash');
            }
          },
      });
    });
  }
});
