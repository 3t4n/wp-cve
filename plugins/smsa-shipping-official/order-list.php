 <html>
 <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

</head>
<body>
 <?php
$query = new WC_Order_Query( array(
    'limit' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'return' => 'ids',
     'type'=>'shop_order',

) );
$orders = $query->get_orders();




?>
<div id="smsa-order">
    <h1 class="wp-heading-inline">Orders</h1>
<div class="tablenav top">

               
            <div class="alignleft actions">

                <div class="alignright actions custom">
            <button id="print-all" type="button" style="height:32px;" class="button" value="">Print All Label</button>
                <button id="create-all" type="button" style="height:32px;" class="button" value="">Create All Shipment</button>
        </div>
     </div>
        
    </div>


    
<br>
<table id="example" class="table table-striped wp-list-table widefat fixed striped table-view-list orders wc-orders-list-table wc-orders-list-table-shop_order" style="width:100%">
        <thead>
            <tr>
                <th><input id="cb-select-all-1" type="checkbox"></th>
                <th>Order</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
                <th>Tracking Number</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order1)
            {
                $order = wc_get_order($order1);
                $order_id=$order->id;
                
                $f_date=date("d M, Y", strtotime($order->order_date));

                ?>
            <tr>
                <td><input id="cb-select-19" class="order_check" type="checkbox" name="id[]" value="<?php echo $order_id;?>"></td>
                <td><a href="admin.php?page=wc-orders&action=edit&id=<?php echo $order_id;?>">#<?php echo $order_id.' '.$order->get_billing_first_name().' '.$order->get_billing_last_name();?></a></td>
                <td><?php echo $f_date;?></td>
                <td><?php echo ucwords($order->status);?></td>
                <td><?php echo $order->order_total;?></td>
                <td><?php  $num = get_post_meta($order_id, 'smsa_awb_no');
            if (count($num) > 0)
            {

                if($num[0]!="")
                {
                echo '<a href="javascript:void(0)" class="smsa_action print_label" data-awb="'.$num['0'].'">Print Label</a>';
                echo '&nbsp;&nbsp;&nbsp;<a href="'.admin_url().'admin.php?page=smsa-shipping-official/track_order.php&awb_no='.$num['0'].'" class="smsa_action" target="_blank;">Track Order</a>';
                }
            }
            else
            {
                echo '<a href="'.admin_url().'admin.php?page=smsa-shipping-official/create_shipment.php&order_ids[]='.$order_id.'" class="smsa_action" target="_blank;">Create Shipment</a>';

            }
            ?></td>
                <td><?php 
                $num = get_post_meta($order_id, 'smsa_awb_no');
              if (count($num) > 0)
            {
                echo $num[0];
            }
            else
            {
                echo "";
            }
            ?>
                </td>
            </tr>
           <?php } ?>
        </tbody>
        
    </table>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
    $('#example').dataTable({
  "pageLength": 50
});

     $('#cb-select-all-1').click(function(){
         if($(this).prop("checked") == true){
            $('.order_check').prop('checked',true);
            
         }
         else
         {
            $('.order_check').prop('checked',false);
           
         }
        
    });

      $('.order_check').click(function(){
            if ($(".order_check:checkbox:checked").length > 0)
            {
              
                if($('.order_check:checkbox').length == $(".order_check:checkbox:checked").length)
                {
                    $('#cb-select-all-1').prop('checked', true);
                }
                else
                {
                    $('#cb-select-all-1').prop('checked', false);
                }
            }
            else
            {
               
                $('#cb-select-all-1').prop('checked', false);
            }
    });
</script>
<style>
    div#smsa-order {
    margin: 2% !important;
}
table#example th, td {
    text-align: center;
}
.wp-admin p input[type=checkbox], .wp-admin p input[type=radio], td>input[type=checkbox] {
    margin: 0% 0% 0% -3%;
}
</style>