(function($) {

    var Element_Ready_WC_Cart_PopUp = function($scope, $) {


        //===== Shopping Cart 

        var $container = $scope.find('.element-ready-shopping-cart-open');

        $container.on('click', function() {
            $('.element-ready-shopping-cart-canvas').addClass('open')
            $('.overlay').addClass('open')
        });

        $('.element-ready-shopping-cart-close').on('click', function() {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });
        $('.overlay').on('click', function() {
            $('.element-ready-shopping-cart-canvas').removeClass('open')
            $('.overlay').removeClass('open')
        });

        // remove product from cart

        $('body').on('click', '.element-ready-cart-item-remove', function() {

            let self = $(this);
            var product_key = self.attr('data-product');

            $.ajax({
                type: "post",
                url: element_ready_obj.ajax_url,
                data: { action: "element_ready_wc_cart_item_remove", cart_product_key: product_key },
                success: function(response, status) {
                 
                    if (response.success == true)  {

                        self.parents('li').remove();
                        $('.element-ready-wc-shopping-total-amount').text(response.data.total);
                        $('.element-ready-interface-cart-count span').text(response.data.count);
                    }

                }

            })

        });
        // add product to cart 
        $('.ajax_add_to_cart').on('click', function() {

            let self = $(this);
            let product_id = self.data('product_id');
            let quantity = self.data('quantity');
            
            setTimeout(function(){
                $.ajax({
                    type: "post",
                    url: element_ready_obj.ajax_url,
                    data: { action: "element_ready_wc_cart_item_add", product_id: product_id, product_quantity: quantity },
                    success: function(response, status) {
                          
                            var template = wp.template('element-ready-add-shopping-cart-item');
                            if (response.success == true) {
    
                                var element_ready_cart_data = response.data.items;
                                var element_ready_cart_count = parseInt( response.data.count );
                                var cart_total = element_ready_cart_data.cart_total;
                                delete element_ready_cart_data.cart_total;
                                var item_content = template(element_ready_cart_data);
                                $('.element-ready-shopping_cart-list-items ul').html(item_content);
                                $('.element-ready-interface-cart-count span').text(element_ready_cart_count);
                                $('.element-ready-wc-shopping-total-amount').text(cart_total);
                                $('.element-ready-shopping-cart-canvas').addClass('open');
    
                            } // response
    
                        } // success
    
                })
            },1000);
           
        });

    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-wc-shopping-cart-popup.default', Element_Ready_WC_Cart_PopUp);
    });
})(jQuery);