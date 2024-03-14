var jcrop_api;
var crop = false;
var rezize = false;
function ImageEditor(e){
   var xsize = jQuery('#target').width();
   var ysize = jQuery('#target').height();
   if(e == 'crop'){
	 jQuery('#crop-tool').addClass('tool-border');
	 jQuery('#resize-tool').removeClass('tool-border');
	 if(rezize){
		jQuery( "#target" ).resizable( "destroy" );
	 }
	 crop = true;
	 jQuery('#target').Jcrop({
	  onChange:   showCoords,
	  onSelect:   showCoords,
	  onRelease:  clearCoords,
	  aspectRatio: 0,
	  },function(){
		  jcrop_api = this;
	  });

	  jQuery('#aspr').show();
	  jQuery('.selected-tool').text('Cropping');
   } else {
	   jQuery('#crop-tool').removeClass('tool-border');
	   jQuery('#resize-tool').addClass('tool-border');
	   if(crop){
		   jcrop_api.destroy();
	   }
	 rezize = true;
	 jQuery( "#target" ).resizable({
		aspectRatio: xsize / ysize,
		resize: function( event, ui ) {
			jQuery('#rw').val(ui.size.width);
			jQuery('#rh').val(ui.size.height);
		}
	});

	jQuery('#aspr').hide();
	jQuery('.selected-tool').text('Resizing');
   }
}
function setAspectRatio(){
	var art = jQuery('#art').val();
	var ars = jQuery('#ars').val();
	if(art=='' || ars ==''){
		alert('Please enter values of aspect ratio');
		return;
	}
	if(art < 0){
		alert('First value must be >= 0');
		return;
	}
	if(ars < 1){
		alert('Second value must be > 0');
		return;
	}
	jcrop_api.setOptions({ aspectRatio: art / ars });
	jcrop_api.focus();
}
function showCoords(c) {
	jQuery('#x1').val(c.x);	jQuery('#y1').val(c.y);	jQuery('#x2').val(c.x2); jQuery('#y2').val(c.y2); jQuery('#w').val(c.w); jQuery('#h').val(c.h);
}
function clearCoords(){
	jQuery('#x1').val(''); jQuery('#y1').val(''); jQuery('#x2').val(''); jQuery('#y2').val('');	jQuery('#w').val(''); jQuery('#h').val('');
}
jQuery( function() {jQuery( ".tool-item" ).tooltip();});
function ap_thumb_upload(thumb,thumb_id){
	var file_frame;
	if ( file_frame ) {
	  file_frame.open();
	  return;
	}
	file_frame = wp.media.frames.file_frame = wp.media({
	  title: jQuery( this ).data( 'uploader_title' ),
	  button: {
		text: jQuery( this ).data( 'uploader_button_text' ),
	  },
	  multiple: false
	});
	file_frame.on( 'select', function() {
	var selection = file_frame.state().get('selection');
	selection.map( function( attachment ) {
		attachment = attachment.toJSON();
		jQuery('#'+thumb).html('<img src="'+attachment.url+'" class="wid-thumb"><p><a href="javascript:void(0);" onclick="ap_thumb_remove(\''+thumb+'\',\''+thumb_id+'\')">Remove</a></p>');
		jQuery('#'+thumb_id).val(attachment.id).trigger('change');
	});
	});
	file_frame.open();  
}
function ap_thumb_remove(thumb,thumb_id){
	jQuery('#'+thumb).html('');
	jQuery('#'+thumb_id).val('').trigger('change');
}