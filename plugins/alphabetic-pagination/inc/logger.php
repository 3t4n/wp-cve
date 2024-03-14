<?php
 	add_action('wp_ajax_ap_clear_order_log', 'ap_clear_order_log');

    if(!function_exists('ap_clear_order_log')){
        function ap_clear_order_log(){

            if((!empty($_POST) && isset($_POST['ap_clear_log_field']))){

                if (
					! wp_verify_nonce( $_POST['ap_clear_log_field'], 'ap_clear_log_nonce' )
					
                ) {

                    _e('Sorry, your nonce did not verify.', 'alphabetic-pagination');
                    exit;

                } else {

                        delete_option('ap_debug_logger');
						_e('Order log removed!', 'alphabetic-pagination');

                }
            }

            wp_die();
        }
    }
	function ap_debug_logger($data=array(), $type='debug'){
		
		$ap_debug_logger = array();
		
		if(empty($data) && !in_array($type, array('order', 'debug', 'email'))){
			 $data = $type;
		}
		
		

		
		switch($type){
			case 'debug':
			default:
				
				$ap_debug_logger = ap_get_option('ap_debug_logger');
				
				$ap_debug_logger = is_array($ap_debug_logger)?$ap_debug_logger:array();

				if($data && $data!='debug'){

					$ap_debug_logger[] = $data;

					ap_update_option('ap_debug_logger', $ap_debug_logger);
				}
			break;		
		}
		
		return $ap_debug_logger;
	}
	
	function ap_debug_logger_display(){

	$ap_debug_logger = ap_debug_logger('debug');

	
?>
<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($){
		$('.ap_debug_clear_log').on('click', function (e) {

                e.preventDefault();
				$.blockUI({message:''});
                $('ul.debug_log').html('');

                var data = {

                    action: 'ap_clear_order_log',
                    ap_clear_log_field: ap_object.clear_log_nonce,
                }

                // console.log(data);
                $.post(ajaxurl, data, function (response, code) {

                    // console.log(response);
                    if (code == 'success') {

						$.unblockUI();
                        //
                    }

                });

        });			
	});
</script>
        
            <div><a class="ap_debug_clear_log" style="float: right"><?php _e('Clear Debug Log', 'alphabetic-pagination'); ?></a> </div>

        <br />
        <?php
		
        if(!empty($ap_debug_logger)){
            
            ?>
           
                <?php
				
			

				krsort($ap_debug_logger);
?>
<ul class="debug_log">
<?php				
                foreach($ap_debug_logger as $method_key=>$log){
                    ?>
                    
                    <li>
					<?php 
					if(is_array($log) || is_object($log)){
						pree($method_key);
						pree($log);
					}else{
						echo $log;
					}
					?>
                    </li>
                    <?php
                }
                ?>
</ul>            
            <?php
        }
        ?><br /><br />


    
<?php

	}