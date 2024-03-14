jQuery(function($){

    // Contact Form 7 - Form Submit - wpcf7mailsent | wpcf7submit
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        var cf7_id = event.detail.contactFormId;
        var form_data = event.detail.inputs;
        const formData = new FormData();
        Object.entries(form_data).forEach(([key, value]) => {
            formData.append(value.name, value.value);
        });

        isCf7rzpActivated(cf7_id, formData);
    }, false );  

    function isCf7rzpActivated(cf7_id, form_data) {
        $.ajax({
            url : ajax_object_cf7rzp.ajax_url,
            type : "post",
            dataType : "json",
            data : {
                action: "is_cf7rzp_activated", 
                cf7_id: cf7_id
            },
            success : function(res){
                if(res.success){
                    cf7rzp_createOrder(cf7_id, form_data);
                }
                else
                {
                    if(res.error != "in_active")
                    {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: res.error
                        })
                    }
                }        
            },
            beforeSend: function(){
                $('.cf7rzp-loader').show()
            },
            complete: function(){
                $('.cf7rzp-loader').hide();
            }
        });
    }

    function cf7rzp_createOrder(cf7_id, form_data) {
        form_data.append('action', 'cf7rzp_create_order'); 
        form_data.append('cf7_id', cf7_id); 

        $.ajax({
            url : ajax_object_cf7rzp.ajax_url,
            type : "post",
            dataType : "json",
            processData: false,
            contentType: false, 
            data: form_data,
            success : function(res){
                var options = res;
                var cf7rzp_order_id = options.notes.cf7rzp_order_id;
                options.handler = function (response){
                    /*alert(response.razorpay_payment_id);
                    alert(response.razorpay_order_id);
                    alert(response.razorpay_signature)
                    alert("OID: "+cf7rzp_order_id);*/

                    //Verify Payment Signature
                    cf7rzp_verifyPayment(cf7rzp_order_id, response.razorpay_payment_id, response.razorpay_signature);
                };
                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response){
                    /*alert(response.error.code);
                    alert(response.error.description);
                    alert(response.error.source);
                    alert(response.error.step);
                    alert(response.error.reason);
                    alert(response.error.metadata.order_id);
                    alert(response.error.metadata.payment_id);*/

                    var error_msg = response.error.code+"|"+response.error.description+"|"+response.error.reason;
                    cf7rzp_updatePaymentStatus(cf7rzp_order_id, 'failure', error_msg);
                });
                rzp.open();       
            },
            beforeSend: function(){
                $('.cf7rzp-loader').show()
            },
            complete: function(){
                $('.cf7rzp-loader').hide();
            } 
        });
    }
    
    function cf7rzp_verifyPayment(cf7rzp_order_id, rzp_payment_id, rzp_signature){

        $.ajax({
            url : ajax_object_cf7rzp.ajax_url,
            type : "post",
            dataType : "json",
            data : {
                action: "cf7rzp_verify_payment", 
                cf7rzp_order_id: cf7rzp_order_id,
                rzp_payment_id: rzp_payment_id,
                rzp_signature: rzp_signature
            },
            success : function(res){
                if(res.success)
                    cf7rzp_updatePaymentStatus(cf7rzp_order_id, 'success');
                else    
                    cf7rzp_updatePaymentStatus(cf7rzp_order_id, 'failure', res.error);
            },
            beforeSend: function(){
                $('.cf7rzp-loader').show()
            },
            complete: function(){
                $('.cf7rzp-loader').hide();
            }
        });
    }

    function cf7rzp_updatePaymentStatus(cf7rzp_order_id, status, msg=""){

        $.ajax({
            url : ajax_object_cf7rzp.ajax_url,
            type : "post",
            dataType : "json",
            data : {
                action: "cf7rzp_update_payment_status", 
                cf7rzp_order_id: cf7rzp_order_id,
                status : status,
                msg : msg
            },
            success : function(res){
                if(res.success){
                    if(res.return_url != "")
                    {
                       window.location.href = res.return_url;
                    }
                    else{
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Payment Successful'
                        })
                    }         
                }
                else
                {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: res.error
                    })
                }        
            },
            beforeSend: function(){
                $('.cf7rzp-loader').show()
            },
            complete: function(){
                $('.cf7rzp-loader').hide();
            }
        });
    }

});