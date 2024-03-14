
(function($){
        place_order = function(){
                $('.checkout').on('submit', function(){
                        $('#billing_state').attr('disabled',false);
                        $('#shipping_state').attr('disabled',false);
                });
        }
})(jQuery);
