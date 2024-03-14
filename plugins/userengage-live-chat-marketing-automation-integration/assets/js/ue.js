jQuery(document).ready(function($) {

  if (userName !== '') {
    userengage('event.Register', {
      'userName': '"' + userName + '"',
      'email': '"' + userEmail + '"'
    });
  }
  $(document).on('click', '.add_to_cart_button', function() {

	  $this = $(this);
    var productId = $(this).attr('data-product_id');
  	var productName = $(this).closest('.product').find('.woocommerce-loop-product__title').text();
  	var productPrice = $(this).closest('.product').find('.woocommerce-Price-amount').text();
  	var productSku = $(this).attr('data-product_sku');
  	var obj = {
  	  id: productId,
  	  name: productName,
  	  price: productPrice,
      sku: productSku
  	};
    userengage('event.AddCart', obj);
  });
});
