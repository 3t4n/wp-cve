<?php 
function CPIW_PincodeCheckInDataTable($pincode){
    global $wpdb;
    $tablename=$wpdb->prefix.'cpiw_pincode';
    $pincodelen = strlen($pincode);
    $sqlwhere = '';
    for($i=1;$i<=$pincodelen;$i++){
        $sqlwhere .= " and  IF(SUBSTRING(pincode, ".$i.", 1) = '*', '".substr($pincode,$i-1,1)."', SUBSTRING(pincode, ".$i.", 1)) =  '".substr($pincode,$i-1,1)."'";
    }
    $sqlwhere .= 'AND LENGTH(pincode) = '.$pincodelen;
    $cntSQL = "SELECT * FROM {$tablename} where 1 ".$sqlwhere;
    return $wpdb->get_results($cntSQL, OBJECT);
}

function CPIW_PincodeCookieSet($pincode,$expiry){
    setcookie('Cpiw_Pincode', $pincode, $expiry , COOKIEPATH, COOKIE_DOMAIN);
}

function CPIW_CheckPincodeSingleProduct(){
        global $cpiw_comman;
            $Singleproductpagepincode = sanitize_text_field($_REQUEST['CheckPincode']);
            $expiry = strtotime('+7 day');
            CPIW_PincodeCookieSet($Singleproductpagepincode,$expiry);
            $Cpiwrecord = CPIW_PincodeCheckInDataTable($Singleproductpagepincode);
            $date = $Cpiwrecord[0]->ddate ?? null;
            $caseondilvery = $Cpiwrecord[0]->caseondilvery ?? null;
            $codtxt = "";
            $totalrec = count($Cpiwrecord);
            $showdate = $cpiw_comman['cpiw_dateshow'];
            $deliverydate= CPIW_DeliveryDate($date);
            $cpiw_cash_dilivery_shw = $cpiw_comman['cpiw_codshow'];
            $delivary_text= $cpiw_comman['cpiw_delivery_date_text'];

            $data = array();
            $data = array(
                'pincode'      => $Singleproductpagepincode,
                'deliverydate' => $deliverydate,
                'totalrec'     => $totalrec,
                'showdate'     => $showdate
            );
            
            $avai_msg = '';  


             if($totalrec == 1) {
                $avai_msg .= '<div class="cpiw_inner_inner">';
                $available_message1 = str_replace("{city_name}","<strong>".$Cpiwrecord[0]->city."</strong>","YES, We Deliver to {city_name}, {state_name}");
                $available_message2 = str_replace("{state_name}","<strong>".$Cpiwrecord[0]->state."</strong>",$available_message1);
                $avai_msg .= '<div class="pincode_city_and_state"><p>'.$available_message2.'</p>';
                $avai_msg .='<input type="button" name="cpiwbtn" class="cpiwcheckbtn" value="Change" style="color:'.$cpiw_comman['checkandchangetxtcolor'].';background-color:'.$cpiw_comman['checkandchangebackcolor'].';"></div>';

                $avai_msg .= '<div class="inner" style="background-color:'.$cpiw_comman['mainbackcolor'].';">';
                if(!empty($cpiw_comman['date_image'])){
                    $del_avail_icon = wp_get_attachment_url($cpiw_comman['date_image']);
                }else{
                    $del_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/schedule.png';
                }
                
                if($caseondilvery == 1) {
                    $caseondilvery_avail = 'Case On Delivery Available';
                    if(!empty($cpiw_comman['cpiw_cashondeliimg_form'])){
                        $caseondilvery_avail_icon = wp_get_attachment_url($cpiw_comman['cpiw_cashondeliimg_form']);
                    }else{
                         $caseondilvery_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/money.png';
                    }
                   
                } else {
                    $caseondilvery_avail = 'Case On Delivery Not Available';
                     if(!empty($cpiw_comman['cpiw_cashondelinotimg_form'])){
                        $caseondilvery_avail_icon = wp_get_attachment_url($cpiw_comman['cpiw_cashondelinotimg_form']);
                    }else{
                         $caseondilvery_avail_icon = CPIW_PLUGIN_DIR . '/assets/image/money-not.png';
                    }
                   
                }

                if($showdate == "enable") {
                    $avai_msg .= '<div class="cpiw_avaitxt"><span class="cpiw_delicons"><img src="'.$del_avail_icon.'"></span>';
                    $avai_msg .= '<div class="cpiw_avaddate" style="color:'.$cpiw_comman['deliverydatetextcolor'].';"><p>'.$delivary_text.' '.$deliverydate.'</p></div></div>';
                }
                if($cpiw_cash_dilivery_shw == "enable"){

                    $avai_msg .= '<div class="cpiw_dlvrytxt" style="color:'.$cpiw_comman['codtextcolor'].';"><span class="cpiw_tficon"><img src="'.$caseondilvery_avail_icon.'"></span>'.$caseondilvery_avail.'</div>';
                }
                $avai_msg .= '</div>';
                $avai_msg .= '</div>';
            }

        $data['avai_msg'] = $avai_msg;

        echo json_encode( $data );
        exit();
}
add_action( 'wp_ajax_CPIW_CheckPincodeSingleProduct', 'CPIW_CheckPincodeSingleProduct' );
add_action( 'wp_ajax_nopriv_CPIW_CheckPincodeSingleProduct', 'CPIW_CheckPincodeSingleProduct');


function CPIW_DeliveryDate($date){

    $string = "+".$date." days";
    $CPIWdeliverydate = Date('jS M', strtotime($string));
    $dayofweek = date('D', strtotime($string));
    $deliverydate = $dayofweek.', '.$CPIWdeliverydate;

    return $deliverydate;

}
