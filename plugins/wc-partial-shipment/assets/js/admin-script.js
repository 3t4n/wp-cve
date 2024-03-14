jQuery(function($){
    var wxp_meta_boxes_order_items = {
        init:function(){
            $('#woocommerce-order-items').on('click','button.wxp-order-shipment',{view:this},this.wxp_order_shipment);
            $('#woocommerce-order-items').on('click','a.icon-wxp-set-shipping',{view:this},this.wxp_order_item_shipment);

            $(document).on('click','button.wxp-shipment-bulk-action',{view:this},this.wxp_shipment_bulk_action);
            $(document).on('click','button.wxp-item-action',{view:this},this.wxp_shipment_single_action);

        },
        wxp_order_shipment : function(e){
            e.stopPropagation();
            e.preventDefault();
            wxp_meta_boxes_order_items.block();
            $.ajax({
                type	: "POST",
                cache	: false,
                async: false,
                url     : wxp_partial_ship.wxp_ajax,
                dataType : 'json',
                data: {
                    'action' : 'wxp_order_shipment',
                    'order_id' : $(this).attr('data-order-id')
                },
                success: function(data){
                    var html='';
                    html = '<div class="wxp-order-shipment-table wrap"><table class="widefat" cellspacing="0">\
                    <thead><tr>\
                    <th class="manage-column column-cb check-column" scope="col"><input type="checkbox" name="wpx-check-top" class="wpx-check-top"></th>\
                    <th class="manage-column" scope="col">'+wxp_partial_ship.wxp_title+'</th>\
                    <th class="manage-column txt-ctr" scope="col">'+wxp_partial_ship.wxp_qty+'</th>\
                    <th class="manage-column txt-ctr" scope="col">'+wxp_partial_ship.wxp_ship+'</th>\
                    </tr></thead>\
                    <tbody>';
                    for(var i=0;i<data.products.length;i++){
                        html+='<tr class="alternate">\
                    <td class="column-cb check-column"><input type="checkbox" class="wxp-shipment-item-check" name="wxp-order-item['+data.products[i].id+']"></td>\
                    <td class="txt-ltr">'+data.products[i].name+'</td>\
                    <td class="txt-ctr">'+data.products[i].qty+'</td>';
                        if(data.products[i].virtual){
                            html+='<td class="txt-ctr"><input data-item-id="'+data.products[i].id+'" data-order-id="'+data.products[i].order_id+'" class="wxp-order-item-shipped" type="number" min="0" max="'+data.products[i].qty+'" step="1" value="'+data.products[i].qty+'" readonly></td>';
                        }
                        else
                        {
                            var shipped = data.init ? data.products[i].shipped : data.products[i].qty;
                            html+='<td class="txt-ctr"><input data-item-id="'+data.products[i].id+'" data-order-id="'+data.products[i].order_id+'" class="wxp-order-item-shipped" type="number" min="0" max="'+data.products[i].qty+'" step="1" value="'+shipped+'"></td>';
                        }
                        html+='</tr>';
                    }
                    html+='</tbody>\
                </table>';
                    html+='<div class="wxp_partial_ship_foot">\
                        <div class="wxp-select-foot">\
                        <select name="wxp_partial_ship_bulk_action" class="wxp_partial_ship_bulk_action">\
                        <option value="0">'+wxp_partial_ship.wxp_bulk_action+'</option>\
                        <option value="shipped">'+wxp_partial_ship.wxp_bulk_mark_shipped+'</option>\
                        <option value="not-shipped">'+wxp_partial_ship.wxp_bulk_mark_not_shipped+'</option>\
                        </select>\
                        </div>\
                        <div class="wxp-button-foot"><button type="button" data-order-id="'+data.order_id+'" class="button button-primary wxp-shipment-bulk-action" name="save">'+wxp_partial_ship.wxp_update+'</button></div>\
                        </div>\
                        </div>';
                    wxp_meta_boxes_order_items.unblock();
                    if(!$('.fancybox-is-open').length){
                        $.fancybox.open(html);
                    }
                }
            });
        },
        wxp_shipment_bulk_action : function(){
            var ship_type = $('.wxp_partial_ship_bulk_action').val();
            if(ship_type==='0'){
                $('.wxp_partial_ship_bulk_action').focus();
                return false;
            }
            var shipped_data = [];
            $('.wxp-order-shipment-table').find('table tbody tr').each(function(){
                if($(this).find('.wxp-shipment-item-check:checked').length){
                    var order_id = $(this).find('input.wxp-order-item-shipped').attr('data-order-id');
                    var item_id = $(this).find('input.wxp-order-item-shipped').attr('data-item-id');
                    var shipped = $(this).find('input.wxp-order-item-shipped').val();
                    var obj = {'order_id':order_id,'item_id':item_id,'shipped':shipped,'type':ship_type};
                    shipped_data.push(obj);
                }
            });
            $.fancybox.close();
            wxp_meta_boxes_order_items.block();
            var order_id = $(this).attr('data-order-id');
            $.ajax({
                type	: "POST",
                cache	: false,
                async: true,
                url     : wxp_partial_ship.wxp_ajax,
                dataType : 'json',
                data: {
                    'action' : 'wxp_order_set_shipped',
                    'order_id' : order_id,
                    'shipped' : shipped_data
                },
                success: function(data){
                    if(typeof data.status !== "undefined" && data.status!==''){
                        wxp_meta_boxes_order_items.set_status(data.status);
                    }
                    wxp_meta_boxes_order_items.reload_items(order_id);
                }
            });
        },
        wxp_order_item_shipment:function(){
            wxp_meta_boxes_order_items.block();
            $.ajax({
                type	: "POST",
                cache	: false,
                async: false,
                url     : wxp_partial_ship.wxp_ajax,
                dataType : 'json',
                data: {
                    'action' : 'wxp_order_item_shipment',
                    'order_id' : $(this).attr('data-order-id'),
                    'item_id' : $(this).attr('data-item-id')
                },
                success: function(data){
                    var html='';
                    html = '<div class="wxp-order-shipment-table wrap"><table class="widefat" cellspacing="0">\
                    <thead>\
                    <th class="manage-column" scope="col">'+wxp_partial_ship.wxp_title+'</th>\
                    <th class="manage-column txt-ctr" scope="col">'+wxp_partial_ship.wxp_qty+'</th>\
                    <th class="manage-column txt-ctr" scope="col">'+wxp_partial_ship.wxp_ship+'</th>\
                    </thead>\
                    <tbody>';
                    for(var i=0;i<data.products.length;i++){
                        html+='<tr class="alternate">\
                    <td class="txt-ltr">'+data.products[i].name+'</td>\
                    <td class="txt-ctr">'+data.products[i].qty+'</td>\
                    <td class="txt-ctr"><input data-item-id="'+data.products[i].id+'" data-order-id="'+data.products[i].order_id+'" class="wxp-order-item-shipped" type="number" min="0" max="'+data.products[i].qty+'" step="1" value="'+data.products[i].shipped+'"></td>\
                    </tr>';
                    }
                    html+='</tbody>\
                </table>';
                    html+='<div class="wxp_partial_ship_foot">\
                        <div class="wxp-select-foot"><button data-item-id="'+data.item_id+'" data-order-id="'+data.order_id+'" data-type="shipped" type="button" class="button button-primary wxp-item-action" name="save">'+wxp_partial_ship.wxp_bulk_mark_shipped+'</button></div>\
                        <div class="wxp-button-foot"><button data-item-id="'+data.item_id+'" data-order-id="'+data.order_id+'" data-type="not-shipped" type="button" class="button button-primary wxp-item-action" name="save">'+wxp_partial_ship.wxp_bulk_mark_not_shipped+'</button></div>\
                        </div>\
                        </div>';
                    wxp_meta_boxes_order_items.unblock();
                    $.fancybox.open(html);
                }
            });
        },
        wxp_shipment_single_action : function(){
            var $this = $(this);
            var shipped_data = [];
            $('.wxp-order-shipment-table').find('table tbody tr').each(function(){
                var order_id = $(this).find('input.wxp-order-item-shipped').attr('data-order-id');
                var item_id = $(this).find('input.wxp-order-item-shipped').attr('data-item-id');
                var shipped = $(this).find('input.wxp-order-item-shipped').val();
                var ship_type = $this.attr('data-type');
                var obj = {'order_id':order_id,'item_id':item_id,'shipped':shipped,'type':ship_type};
                shipped_data.push(obj);
            });
            //var instance = $.fancybox.getInstance();
            var order_id = $(this).attr('data-order-id');
            $.fancybox.close(true);
            wxp_meta_boxes_order_items.block();
            $.ajax({
                type	: "POST",
                cache	: false,
                async: true,
                url     : wxp_partial_ship.wxp_ajax,
                dataType : 'json',
                data: {
                    'action' : 'wxp_order_set_shipped',
                    'order_id' : order_id,
                    'shipped' : shipped_data
                },
                success: function(data){
                    if(typeof data.status !== "undefined" && data.status!==''){
                        wxp_meta_boxes_order_items.set_status(data.status);
                    }
                    wxp_meta_boxes_order_items.reload_items(order_id);
                }
            });
        },
        block: function(){
            $(document).find('#woocommerce-order-items').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        unblock: function() {
            $( '#woocommerce-order-items' ).unblock();
        },
        reload_items: function(order_id){
            var data = {
                order_id: order_id,
                action:   'woocommerce_load_order_items',
                security: wxp_partial_ship.wxp_order_nonce
            };

            wxp_meta_boxes_order_items.block();

            $.ajax({
                url:  wxp_partial_ship.wxp_ajax,
                data: data,
                async: false,
                type: 'POST',
                success: function(response){
                    $('#woocommerce-order-items').find('.inside').empty();
                    $('#woocommerce-order-items').find('.inside').append(response);
                    wxp_meta_boxes_order_items.unblock();
                }
            });
        },
        set_status:function(status){
            $('#order_status').val(status);
            $('#order_status').select2().trigger('change');
            $(document.body).trigger('wc-enhanced-select-init');
        },
    };
    wxp_meta_boxes_order_items.init();
});