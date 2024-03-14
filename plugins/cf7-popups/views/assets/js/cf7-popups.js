jQuery(document).ready(function ($) {

$('.wpcf7-response-output').remove();

document.addEventListener( 'wpcf7invalid', function( event ) {
    
    swal({
  		title: '<strong>'+cf7_popups_val.msg1+'</strong>',
  		type: 'error',
  		html: cf7_popups_val.msg2,
  		showCloseButton: true,
  		showConfirmButton: false,
	})

    
}, false );


document.addEventListener( 'wpcf7spam', function( event ) {
    swal({
  		title: '<strong>'+cf7_popups_val.msg3+'</strong>',
  		type: 'warning',
  		html: cf7_popups_val.msg4,
  		showCloseButton: true,
  		showConfirmButton: false,
	})
}, false );

document.addEventListener( 'wpcf7mailfailed', function( event ) {
     swal({
  		title: '<strong>'+cf7_popups_val.msg3+'</strong>',
  		type: 'warning',
  		html: cf7_popups_val.msg5,
  		showCloseButton: true,
  		showConfirmButton: false,
	})
}, false );

document.addEventListener( 'wpcf7mailsent', function( event ) {
    swal({
  		title: '<strong>'+cf7_popups_val.msg6+'</strong>',
  		type: 'success',
  		html: cf7_popups_val.msg7,
  		showCloseButton: true,
  		showConfirmButton: false,
	})
}, false );



});