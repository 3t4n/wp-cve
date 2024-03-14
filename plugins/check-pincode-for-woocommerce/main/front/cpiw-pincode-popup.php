<?php 
function cpiw_popup_div_footer() { 
     global $cpiw_comman; 
     if( $cpiw_comman['cpiw_poupshow'] == "enable") {
     ?>
    	<div id="cpiwModal" class="cpiw-modal">
    		<div id="cpiw_pincode_popup" class="cpiw_pincode_popup_class"></div>
    		<div class="modal-content" style="background-color:<?php echo esc_attr($cpiw_comman['popupbackcolor']); ?>">
    			<span class="close" >&times;</span>
    			 	<div class="modalinner">
                        <div class="popup_oc_main">
                            <h5 class="cpiw_popup_header" style="color:<?php echo esc_attr($cpiw_comman['popuptextcolor']); ?>"><?php echo esc_attr($cpiw_comman['cpiw_checklocationtext_text']); ?></h5>
                            <div class="modal-body">
                                <form action="" method="post">
                                	<div class="popup_main">
        	                            <div class="cpiw_popup_check_div">
                                            <?php 
                                            if(!empty($cpiw_comman['popuppincode_image'])){
                                                $location_icon = wp_get_attachment_url($cpiw_comman['popuppincode_image']);
                                            }else{
                                                $location_icon = CPIW_PLUGIN_DIR . '/assets/image/location.png';
                                            }?>
        	                            	<img src="<?php echo esc_attr($location_icon); ?>">
        	                                <input type="text" name="cpiwopuppinzip" class="cpiwopuppinzip" placeholder="<?php echo esc_attr($cpiw_comman['cpiw_cpopplaceholder_text']); ?>" value="">
        	                                <input type="button" name="cpiwinzipsubmit" class="cpiwinzipsubmit" value="<?php echo esc_attr($cpiw_comman['cpiw_cpopsubmit_text']); ?>" style="background-color:<?php echo esc_attr($cpiw_comman['submitbackcolor']); ?>;color:<?php echo esc_attr($cpiw_comman['submittextcolor']); ?>" >
        	                            </div>
        	                            <div class="popuppincoderesponce" style="color:<?php echo esc_attr($cpiw_comman['popuptextcolor']); ?>">
        	                            </div>
        	                            <span class="wczp_empty"><?php echo esc_attr($cpiw_comman['cpiw_emptyfield_text']); ?></span>
        	                        </div>
                                </form>
                            </div>
                        </div>
                        <div class="cpiwc_maindiv_popup"></div>
                    </div>
    		</div>
    	</div>

	<?php 
        }
	}
    add_action('init','CPIW_enable_disable_plugin_popup_box');
    function CPIW_enable_disable_plugin_popup_box(){
        global $cpiw_comman;
        if ($cpiw_comman['cpiw_enable']=='enable') {
           add_action( 'wp_footer',  'cpiw_popup_div_footer' ); 
        }
    }


function CPIW_PopupCheckZipCode() {
    global $cpiw_comman; 
            $popup_postcode = sanitize_text_field($_REQUEST['popup_postcode']);
            $record =  CPIW_PincodeCheckInDataTable($popup_postcode); 
            $totalrec = count($record);
            $data = array();
            $data = array(
                'popup_pincode' => $popup_postcode,
                'totalrec'     => $totalrec   
            );

            $avai_msg = '';
                        
            $expiry = strtotime('+7 day');
           		CPIW_PopupCookieSet($popup_postcode,$expiry);
            	CPIW_PincodeCookieSet($popup_postcode,$expiry);

            if($totalrec == 1) {
                $avai_msg = $cpiw_comman['cpiw_popavailabletext'];
            }

            $data['avai_msg'] = $avai_msg;

            echo json_encode( $data );
            exit();
        }

  	add_action( 'wp_ajax_CPIW_PopupCheckZipCode', 'CPIW_PopupCheckZipCode');
  	add_action( 'wp_ajax_nopriv_CPIW_PopupCheckZipCode', 'CPIW_PopupCheckZipCode' );

  	function CPIW_PopupCookieSet($popup_postcode,$expiry){
        setcookie('Cpiw_Pincode', $popup_postcode, $expiry , COOKIEPATH, COOKIE_DOMAIN);
        setcookie('popup_cookkie', 'popusetp', $expiry , COOKIEPATH, COOKIE_DOMAIN);
    }