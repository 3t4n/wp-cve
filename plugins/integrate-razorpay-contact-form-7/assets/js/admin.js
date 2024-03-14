function cf7rzp_getPaymentMoreInfo(e, post_id)
{
    e.preventDefault();
    
    jQuery.ajax({
        url : ajax_object_cf7rzp.ajax_url,
        type : "get",
        dataType : "json",
        data : {
            action: "cf7rzp_get_payment_more_info",
            post_id: post_id
        },
        success : function(res){

            var tbl_content = '<tr><th>Order Id:</th><td>'+res.order_id+'</td></tr>'+
                    '<tr><th>Item Id:</th><td>'+res.item_id+'</td></tr>'+
                    '<tr><th>Item Name:</th><td>'+res.item_name+'</td></tr>'+
                    '<tr><th>Item Price:</th><td>'+res.item_price+'</td></tr>'+
                    '<tr><th>Form Name:<td>'+res.form_name+'</td></tr>'+
                    '<tr><th>Gateway:</th><td>'+res.gateway+'</td></tr>'+
                    '<tr><th>Mode:</th><td>'+res.mode+'</td></tr>'+
                    '<tr><th>Status:</th><td>'+res.status_label+'</td></tr>'+
                    '<tr><th>Razorpay Order Id:</th><td>'+res.rzp_order_id+'</td></tr>';

            if(res.status == "cf7rzp_success")
                tbl_content += '<tr><th>Razorpay Payment Id:</th><td>'+res.rzp_payment_id+'</td></tr>';

            if(res.status == "cf7rzp_failure")
                tbl_content += '<tr><th>Failure Reason:</th><td>'+res.failure_reason+'</td></tr>';
                
            tbl_content += '<tr><th>Created At:</th><td>'+res.created_at+'</td></tr>';        

            Swal.fire({
                title: 'Payment Details',
                customClass: {
                    container: 'cf7rzp-payment-more-info'
                },    
                html: '<table>'+tbl_content+'</table>'
            })                
        }
    });    
}

jQuery(function($){
    var adminSubmenuCf7rzppaLink = $('#adminmenu .wp-submenu li a[href="admin.php?page=cf7rzp-get-premium"]');
    if (adminSubmenuCf7rzppaLink) {
        adminSubmenuCf7rzppaLink.attr('target', '_blank');
        adminSubmenuCf7rzppaLink.attr('href', 'https://cf7rzppa.codolin.com?utm_source=plugin_user&utm_medium=plugin&utm_campaign=upsell');
        adminSubmenuCf7rzppaLink.attr('class', 'cf7rzppa-admin-submenu-link');
        adminSubmenuCf7rzppaLink.css({'background-color': '#93003f','color': '#fff','font-weight': 'bold'});
    }
});