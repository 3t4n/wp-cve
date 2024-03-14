'use strict';
(function($) {
    /* Storage Handling */
    var $supports_html5_storage = true;
    var cart_hash_key = null;
    if (typeof wc_cart_fragments_params != "undefined") {
        cart_hash_key = wc_cart_fragments_params ? wc_cart_fragments_params.cart_hash_key : null;
        // Satrt cart Frageent  Object
        var $fragment_refresh = {
            url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
            type: 'POST',
            data: {
                time: new Date().getTime()
            },
            timeout: wc_cart_fragments_params.request_timeout,
            success: function(data) {
                if (data && data.fragments) {
                    $.each(data.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    if ($supports_html5_storage) {
                        sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(data.fragments));
                        set_cart_hash(data.cart_hash);
                    }
                    if (data.cart_hash) {
                        set_cart_creation_timestamp();
                    }
                    $(document.body).trigger('wc_fragments_refreshed');
                    $(document.body).trigger('update_checkout');
                }
            },
            error: function() {
                $(document.body).trigger('wc_fragments_ajax_error');
            }
        };
    }
    function set_cart_hash(cart_hash) {
        if ($supports_html5_storage) {
            localStorage.setItem(cart_hash_key, cart_hash);
            sessionStorage.setItem(cart_hash_key, cart_hash);
        }
    }
    function set_cart_creation_timestamp() {
        if ($supports_html5_storage) {
            sessionStorage.setItem('wc_cart_created', (new Date()).getTime());
        }
    }
    // ENd Cart Fragement
    // shipping Change
    $(document).on('change', '.wr-cart-checkout-shipping-method-wrapper input[name^="shipping_method"]', function() {
        var data = {
            shipping_method: $(this).val(),
            action: 'wr_woocommerce_shipping',
        }
        $.ajax({
            url: woocommerce_params.ajax_url,
            type: 'POST',
            data: data,
            success: function(data) {
                // location.reload();
            }
        });
    });
    $(document).on('updated_cart_totals', function() {
        $('.woo-raedy-cart-totals.cart_totals').hide();
        $('.woo-raedy-cart-totals.cart_totals:nth-of-type(1)').show();
    });
    var wr_checkout_order_review = {
        selectedPaymentMethod: false,
        $order_review: $('#order_review'),
        $checkout_form: $('form.checkout'),
        init: function() {
            $(document.body).on('click', '#ship-to-different-address-checkbox', this.shipping_change);
            // $(document.body).on('change', '.woo-ready-review-order input', this.order_qty_update);
        },
        shipping_change: function(e) {

            //$('.woo-ready-checkout-shipping-fields .shipping_address').toggle('slow');
   
        },
        order_qty_update: function() {

            var wr_checkout_key = $(this).attr('data-item_key');
            $fragment_refresh.data.cart = {
                'qty': $(this).val(),
                'key': wr_checkout_key,
            };
            $.ajax($fragment_refresh);
        },
        submit: function() {
            var $form = $(this);
            return false;
        },
    };

    wr_checkout_order_review.init();

    var checkout_multi_step_form = function($scope, $) {
  
      var $multi_step_container = $scope.find('.shopready-multistep-checkout');

      if($multi_step_container.length == 0){
        return;
      }

      $multi_step_container.find('.input-text' ).on( 'click' , function(){
        $(this).css({'outline-color':'transparent'});
     });

      var total_steps        = 0;
      var step_content_items = 0;
      var current_step       = 0;
      var is_last_step       = 0;
      var is_form_valid      = 0;
      var $steps             = $scope.find('.sr-steps-chk');
      var $progress_line     = $scope.find('.shop-ready-chk-progress-line');
      var $steps_content     = $scope.find('.shop-ready-steps-content');
      var $_button           = $scope.find('.shop-ready-next-prev');
      var $next_button       = $scope.find('.shop-ready-next-prev .next');
      var $previous_button   = $scope.find('.shop-ready-next-prev .previous');
          total_steps        = $steps.find('li').length;
      if($previous_button.attr('data-page') == "-1"){
        $previous_button.hide(); 
      }
      shop_ready_multi_preload_content();
      $next_button.on('click', function(e){
        e.preventDefault();
        var that_button = $(this);
        if(!shop_ready_is_form_valid(that_button)){
            return false;
        }
        current_step = parseInt( that_button.attr('data-page') );
        current_step++;
        that_button.attr('data-page',current_step);
        $.each( $steps.find('li'), function(index) {
            // nav
           if(current_step >= index){
            $(this).addClass('active');
           }

           if(current_step == total_steps-1){
            $next_button.hide(500);
            is_last_step = 1;
           }
   
        });
        // content
        shop_ready_multi_content();
        shop_ready_multi_progress();
        $previous_button.attr('data-page',current_step-1);
        if($previous_button.attr('data-page') >= 0){
            $previous_button.show();
        }else{
            $previous_button.hide();
        }
      });

      $previous_button.on('click' , function(e){
        e.preventDefault();
      
        var this_button  = $(this);
            current_step = parseInt( this_button.attr('data-page') );
        this_button.attr('data-page',current_step-1);
        $next_button.attr('data-page',current_step);

        $.each( $steps.find('li'), function(index) {

            if(current_step >= index){
                $(this).addClass('active');
            }else{
                $(this).removeClass('active');
            }

            if(current_step != total_steps-1){
                $next_button.show(500);
                is_last_step = 0;
            }

            if($previous_button.attr('data-page') >= 0){
                $previous_button.show();
            }else{
                $previous_button.hide();
            }
              
        });

        shop_ready_multi_content();
        shop_ready_multi_progress();

      });

      function shop_ready_is_form_valid(next_button){
        let checkout_wrapper = $scope.find('.shop-ready-checkout-multistep'); 
        let valid              = true;
        var color              = checkout_wrapper.attr('data-error_color');
        var required_pre_text  = checkout_wrapper.attr('data-error_before');
        var required_post_text = checkout_wrapper.attr('data-error_after');
        let $content_wrapper   = $steps_content.find('.step').eq(next_button.attr('data-page'));
        let fields             = $content_wrapper.find('.woocommerce-billing-fields__field-wrapper .validate-required input,.woocommerce-billing-fields__field-wrapper .validate-required select,.woocommerce-billing-fields__field-wrapper .validate-required textarea');
        let ship_fields        = $content_wrapper.find('.woocommerce-shipping-fields__field-wrapper .validate-required input,.woocommerce-shipping-fields__field-wrapper .validate-required select,.woocommerce-shipping-fields__field-wrapper .validate-required textarea');
        let shipping           = $content_wrapper.find('input[name=ship_to_different_address]');
        let error_wrapper      = $scope.find('#shop-ready-woo-checkout-error');
        var error_ul           = $("<ul></ul>");
        
        error_ul.addClass('woocommerce-error margin-top:30');
        error_wrapper.find('ul').remove();

        if( fields.length ){
            error_wrapper.append(error_ul);
            $.each(fields , function(index){
                if($(this).val().length == 0){
                    valid = false;
                    $(this).addClass('shop-ready-error-fld');
                    $(this).css({'outline':'1px solid '+color});
                    error_ul.append($("<li></li>").html(required_pre_text + $(this).attr('data-title') + required_post_text) );
                }
            });
        }
    
        if(shipping.is(':checked')){
            if( ship_fields.length){
                error_wrapper.append(error_ul);
                $.each(ship_fields , function(index){
                    if($(this).val().length == 0){
                       valid = false;
                       $(this).addClass('shop-ready-error-fld');
                       $(this).css({'outline':'1px solid '+color});
                       error_ul.append($("<li></li>").html( required_pre_text + $(this).attr('data-title') + required_post_text));
                    }
                 });
            }
        }

        return valid;
      } 
      function shop_ready_multi_progress(){
        var running_steps = $steps.find('li.active').length;
        var css_string = '';
        css_string = (running_steps / total_steps) * 100 + '%';
        $progress_line.find('.sr-progress').css({'width':css_string});
      }
      function shop_ready_multi_content(){
        // content
        $.each($steps_content.find('.step') , function(index){
                    
            if(current_step == index){
            $(this).show(200);
            }else{
                $(this).hide();
            }   
        });
      }
      function shop_ready_multi_preload_content(){
        // content
        $.each($steps_content.find('.step') , function(index){
                    
            if(index == 0){
            $(this).show(200);
            }else{
                $(this).hide();
            }  

        });

        $.each( $steps.find('li'), function(index) {
            // nav
           if(0 == index){
            $(this).addClass('active');
           }
       
        });
      }
   
    };
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/checkout_checkout.default', checkout_multi_step_form);
    });
})(jQuery);