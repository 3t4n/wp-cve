jQuery(document).ready(function() {
  if ( jQuery( 'input#_amb_vap_prod' ).is(':checked' ) && jQuery( 'select#product-type option:selected' ).text() == 'Variable product' ) {
    jQuery( '.amb_wpvap_opt' ).show();
    jQuery('.amb_wpvap_url').show();
    jQuery( '.ambv_wpvap_cart_text' ).show();
  } else if ( jQuery( 'select#product-type option:selected' ).text() == 'Variable product' ) {
    jQuery( '.amb_wpvap_url' ).hide();
    jQuery( '.ambv_wpvap_cart_text' ).hide();
    jQuery( '.amb_wpvap_opt' ).show();
  } else if ( jQuery( 'select#product-type option:selected' ).text() !== 'Variable product' ) {
    jQuery( '.amb_wpvap_opt' ).hide();
    jQuery( '.amb_wpvap_url' ).hide();
    jQuery( '.ambv_wpvap_cart_text' ).hide();
  } else {
    jQuery( '.amb_wpvap_url' ).hide();
    jQuery( '.ambv_wpvap_cart_text' ).hide();
  }

  jQuery('#_amb_vap_prod').click(function() {
    if ( this.checked ) {
      jQuery( '.amb_wpvap_url' ).show();
      jQuery( '.ambv_wpvap_cart_text' ).show();
    } else {
      jQuery( '.amb_wpvap_url' ).hide();
      jQuery( '.ambv_wpvap_cart_text' ).hide();
    }
  })  
});

jQuery( 'body' ).on( 'woocommerce-product-type-change', function( event, select_val, select ) {
  if ( select_val == 'variable' ) {
    jQuery( '.amb_wpvap_opt' ).show();
  } else {
    jQuery( '.amb_wpvap_opt' ).hide();
    jQuery('.amb_wpvap_url').hide();
    jQuery( '.ambv_wpvap_cart_text' ).hide();
  }
});