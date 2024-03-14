<?php
/**
 * Plugin Name: SMSA Shipping (official)
 * Plugin URI: https://www.smsaexpress.com
 * Description: Ship and Print..
 * Author:  SMSA Express
 * Author URI: https://www.smsaexpress.com/about-us
 * Version: 1.6
 */
use setasign\Fpdi\Fpdi;

if (!defined('WPINC'))
{

    die;

}

/*
 * Check if WooCommerce is active
*/
// if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
// {


    require_once('smsa-express-shipping-class.php');
   
add_action( 'admin_footer', 'smsa_my_action_javascript' ); // Write our JS below here

function smsa_my_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        $('#create-all').click(function(){

   var list1 = new Array();
    $("input[name='id[]']:checked").each(function (index, obj) {
    list1.push('&order_ids[]='+$(this).val());
    });
    if(list1.length<1)
    {
     alert("Please select any order first.");
    }
    else
    {
        
        var url="<?php echo admin_url();?>admin.php?page=smsa-shipping-official/create_shipment.php"+list1.join("");
        window.open(url,"_blank");
    }
});

  $('#print-all').click(function(){

   var list = new Array();
    $("input[name='id[]']:checked").each(function (index, obj) {
    list.push($(this).val());
    });
    if(list.length<1)
    {
     alert("Please select any order first.");
    }
    else
    {
         $(this).html('Processing...');
        var data = {
        'action': 'print_all_label',
        'post_ids':list 
        };
        jQuery.post(ajaxurl, data, function(data) {
                 var json = $.parseJSON(data);
                    
                    if(json.response=="success")
                    {
                        var win = window.open(json.msg, '_blank');
                        if (win) {
                        //Browser has allowed it to be opened
                        win.focus();
                         setTimeout(function () {
                        win.print();
                    }, 2000);
                        } else {
                        //Browser has blocked it
                        alert('Please allow popups for this website');
                        }
                          setTimeout(function () {
                            var data = {
                            'action': 'delete_label',
                            'attach_url': json.msg,
                            'attach_path': json.path
                            };

     
        jQuery.post(ajaxurl, data, function(data) {
        });
                          }, 5000);

                    }
                    else
                    {
                        alert(json.msg);
                    }  
                    $('#print-all').html('Print All Label');    

        });
    }

  });
        $('.print_label').click(function(){
            $(this).html('Processing...');
        var data = {
            'action': 'generate_label',
            'awb_no': jQuery(this).attr('data-awb')
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(data) {
             var json = $.parseJSON(data);
                    
                    if(json.response=="success")
                    {
                        var win = window.open(json.msg, '_blank');
                        if (win) {
                        //Browser has allowed it to be opened
                        win.focus();
                         setTimeout(function () {
                        win.print();
                    }, 2000);
                        } else {
                        //Browser has blocked it
                        alert('Please allow popups for this website');
                        }
                         setTimeout(function () {
                            var data = {
                            'action': 'delete_label',
                            'attach_url': json.msg,
                            'attach_path': json.path
                            };

     
        jQuery.post(ajaxurl, data, function(data) {
        });
                          }, 5000);
                    }
                    else
                    {
                        alert(json.msg);
                    }      
$('.print_label').html('Print Label');
                      });
    });
    });
    </script> <?php
}

function smsa_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=wc-settings&tab=shipping&section=smsa-express-integration' ) ) );
    }
}
add_action( 'activated_plugin', 'smsa_activation_redirect' );

add_action( 'wp_ajax_print_all_label', 'smsa_print_all_label' );
function smsa_print_all_label() {
    error_reporting(E_ALL);
ini_set('display_errors', '1');

     $sett = get_option('woocommerce_smsa-express-integration_settings');
$body = array(
    'accountNumber' => $sett['smsa_account_no'],
    'username' => $sett['smsa_username'],
    'password' => $sett['smsa_password'],
);

$args = array(
    'body' => json_encode($body) ,
    'timeout' => '5',
    'redirection' => '5',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
        'Content-Type' => 'application/json; charset=utf-8'
    ) ,
    'cookies' => array() ,
);
$re = wp_remote_post('https://smsaopenapis.azurewebsites.net/api/Token', $args);

$resp = json_decode($re['body']);

            if (isset($resp->token))
            {
                 require_once('fpdf/fpdf.php');
                            require_once('fpdi/src/autoload.php');
                            class ConcatPdf extends Fpdi
                            {
                                public $files = array();

                                public function setFiles($files)
                                {
                                    $this->files = $files;
                                }

                                public function concat()
                                {
                                    foreach($this->files AS $file) 
                                    {
                                        $pageCount = $this->setSourceFile($file);
                                        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++)
                                         {
                                            $pageId = $this->ImportPage($pageNo);
                                            $s = $this->getTemplatesize($pageId);
                                            $this->AddPage($s['orientation'], $s);
                                            $this->useImportedPage($pageId);
                                        }
                                    }
                                }
                            }


                $all_files=array();
                $ids=wp_parse_list($_POST['post_ids']);
                $total_count=count($ids);
                $not_exist=0;
                foreach($ids as $id)
                {
                   
                      $awb= get_post_meta($id,'smsa_awb_no',true);
                      if($awb!=NULL)
                      {

                        $url = 'https://smsaopenapis.azurewebsites.net/api/Shipment/QueryB2CByAwb?awb=' .$awb;
                        $args = array(
                            'headers' => array(
                            'Authorization' => 'Bearer ' . $resp->token
                        )
                        );
                        $response = wp_remote_get($url, $args);
                        $json = json_decode( wp_remote_retrieve_body( $response ), true );

                       

                      
                        if (isset($json['waybills'][0]['label']))
                        {
                            $upload_dir = wp_upload_dir(); 

                            $upload_base_path=$upload_dir['path'];
                             $upload_base_url=$upload_dir['url'];

                            if(count($json['waybills'])<2)
                            {
                                $data = base64_decode($json['waybills'][0]['label']);
                                $name = $awb . '.pdf';
                                file_put_contents($upload_base_path.'/'.$name, $data);
                                 
                                
                            }
                            else
                            {
                                
                                $temp_files=array();
                                for($i=0;$i<count($json['waybills']);$i++)
                                {
                                $data = base64_decode($json['waybills'][$i]['label']);
                                $temp=$i.'_'.$json['waybills'][$i]['awb'].'.pdf';
                                file_put_contents($upload_base_path.'/'.$temp, $data);
                                array_push($temp_files,$upload_base_path.'/'.$temp);
                                }
                                    $name = $awb . '.pdf';
                                 $path=$upload_base_path.'/'.$name;
                               //  $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$path ";
                               //  //Add each pdf file to the end of the command
                               //  foreach($temp_files as $file) {
                               // $cmd .= $upload_base_path.'/'.$file." ";
                               //  }
                               //  $result = shell_exec($cmd);


                           
                            $pdf = new ConcatPdf();
                            $pdf->setFiles($temp_files);
                            $pdf->concat();

                            $pdf->Output($upload_base_path.'/'.$name,'F');
                                foreach($temp_files as $file) {
                                unlink($file);
                                }

                            }


                            array_push($all_files,$upload_base_path.'/'.$name);
                        }
                      }
                      else
                      {
                        $not_exist=$not_exist+1;

                      }

                }
                if($total_count!=$not_exist)
                {
                $name = 'all.pdf';
                $public_url=$upload_base_url.'/'.$name;
                $path=$upload_base_path.'/'.$name;
                // $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$path ";
                // //Add each pdf file to the end of the command
                // foreach($all_files as $file) {
                //    $cmd .= $upload_base_path.'/'.$file." ";
                // }
                // $result = shell_exec($cmd);

                

                            $pdf = new ConcatPdf();
                            $pdf->setFiles($all_files);
                            $pdf->concat();

                            $pdf->Output($upload_base_path.'/'.$name,'F');
                foreach($all_files as $file) {
                  unlink($file);
                }
                 

                        $ret = array();
                        $ret['response'] = 'success';
                        $ret['msg'] =  $public_url;
                        $ret['path'] =  $path;
                        echo json_encode($ret);
                       exit;
                   }
                   else
                   {
                        if($total_count==1)
                        {
                            $msg='This order was not shipped by SMSA Shipping.';
                        }
                        else
                        {
                            $msg='These orders was not shipped by SMSA Shipping.';
                        }
                        $ret = array();
                        $ret['response'] = 'error';
                        $ret['msg'] = $msg;
                        echo json_encode($ret);
                        exit;
                   }
            }
            else
            {
               $ret = array();
                $ret['response'] = 'error';
                $ret['msg'] = 'Please check your SMSA account credentials';
                echo json_encode($ret);
                exit;
            }
    }

add_action( 'wp_ajax_delete_label', 'smsa_delete_label' );
function smsa_delete_label() {

    $url = esc_url($_POST['attach_url']);
    if (in_array("Content-Type: application/pdf", get_headers($url))) {
     unlink($_POST['attach_path']);
    }

    $ret = array();
        $ret['response'] = 'success';
        echo json_encode($ret);
        exit;
    }

add_action( 'wp_ajax_generate_label', 'smsa_generate_label' );
function smsa_generate_label() {
   


           $sett = get_option('woocommerce_smsa-express-integration_settings');
$body = array(
    'accountNumber' => $sett['smsa_account_no'],
    'username' => $sett['smsa_username'],
    'password' => $sett['smsa_password'],
);

$args = array(
    'body' => json_encode($body) ,
    'timeout' => '5',
    'redirection' => '5',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
        'Content-Type' => 'application/json; charset=utf-8'
    ) ,
    'cookies' => array() ,
);
$re = wp_remote_post('https://smsaopenapis.azurewebsites.net/api/Token', $args);

$resp = json_decode($re['body']);

            if (isset($resp->token))
            {
               $url = 'https://smsaopenapis.azurewebsites.net/api/Shipment/QueryB2CByAwb?awb='.sanitize_text_field($_POST['awb_no']);
                        $args = array(
                            'headers' => array(
                            'Authorization' => 'Bearer ' . $resp->token
                        )
                        );
                        $response = wp_remote_get($url, $args);
                        $json = json_decode( wp_remote_retrieve_body( $response ), true );

                if (isset($json['waybills'][0]['label']))
                {
                    $upload_dir = wp_upload_dir(); 

                            $upload_base_path=$upload_dir['path'];
                             $upload_base_url=$upload_dir['url'];   
                    if(count($json['waybills'])<2)
                    {

                        $data = base64_decode($json['waybills'][0]['label']);
                        $name = sanitize_text_field($_POST['awb_no']) . '.pdf';
                        file_put_contents($upload_base_path.'/'. $name, $data);
                        
                       $image_url = $upload_base_path.'/'.$name;

                        $public_url=$upload_base_url.'/'.$name;





                    }
                    else
                    {
                       
                        $temp_files=array();
                        for($i=0;$i<count($json['waybills']);$i++)
                        {
                            $data = base64_decode($json['waybills'][$i]['label']);
                            $temp=$i.'_'.$json['waybills'][$i]['awb'].'.pdf';
                              file_put_contents($upload_base_path.'/'.$temp, $data);
                              array_push($temp_files,$upload_base_path.'/'.$temp);
                        }
                             $name = sanitize_text_field($_POST['awb_no']).'.pdf';
                             $path=$upload_base_path.'/'.$name;
                             $public_url=$upload_base_url.'/'.$name;

                            // $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$path ";
                            // //Add each pdf file to the end of the command
                            // foreach($temp_files as $file) {
                            // $cmd .= $upload_base_path.'/'.$file." ";
                            // }
                            // $result = shell_exec($cmd);

                            require_once('fpdf/fpdf.php');
                            require_once('fpdi/src/autoload.php');
                            class ConcatPdf extends Fpdi
                            {
                                public $files = array();

                                public function setFiles($files)
                                {
                                    $this->files = $files;
                                }

                                public function concat()
                                {
                                    foreach($this->files AS $file) 
                                    {
                                        $pageCount = $this->setSourceFile($file);
                                        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++)
                                         {
                                            $pageId = $this->ImportPage($pageNo);
                                            $s = $this->getTemplatesize($pageId);
                                            $this->AddPage($s['orientation'], $s);
                                            $this->useImportedPage($pageId);
                                        }
                                    }
                                }
                            }

                            $pdf = new ConcatPdf();
                            $pdf->setFiles($temp_files);
                            $pdf->concat();

                            $pdf->Output($upload_base_path.'/'.$name,'F');

                            foreach($temp_files as $file) {
                            unlink($file);
                            }

                             $image_url = $upload_base_path.'/'.$name;
                       
                    }


                       
                        $ret = array();
                        $ret['response'] = 'success';
                        $ret['msg'] =  $public_url;
                        $ret['path'] =  $image_url;
                        echo json_encode($ret);
                       exit;
                   
                   
                    
                }
                else
                {
                    $ret = array();
                    $ret['response'] = 'error';
                    $ret['msg'] = 'Please try again in few minutes!';
                    echo json_encode($ret);
                   exit;
                }

            }
            else
            {
                $ret = array();
                $ret['response'] = 'error';
                $ret['msg'] = 'Please check your SMSA account credentials';
                echo json_encode($ret);
                exit;
            }
}


 add_action( 'admin_menu', 'smsa_smsa_tracking_page' );
function smsa_smsa_tracking_page() {
 
  add_menu_page( 'Track Order', 'SMSA Track Order', 'manage_options', plugin_dir_path( __FILE__ ).'track_order.php', '', 'dashicons-welcome-widgets-menus', 90 );
  add_menu_page( 'Create Shipment', 'SMSA Create shipment', 'manage_options', plugin_dir_path( __FILE__ ).'create_shipment.php', '', 'dashicons-welcome-widgets-menus', 90 );
}
 add_action( 'admin_init','smsa_smsa_assets');
  add_action( 'init','smsa_smsa_assets');
                function smsa_smsa_assets() {
                     wp_register_style( 'smsa_style', plugin_dir_url( __FILE__ ).'css/smsa.css', array(), false, 'all' );
                        wp_enqueue_style( 'smsa_style' );
                }




    function smsa_sv_add_my_account_order_actions( $actions, $order ) {

        $ord_num= $order->get_order_number();

        $awb= get_post_meta($ord_num,'smsa_awb_no',true);
        if($awb!=NULL)
        {
             $action_slug = 'smsa_track_link';
            $actions[$action_slug] = array(
             'url'  => 'https://smsaexpress.com/trackingdetails?tracknumbers='. $awb,
          
            'name' => 'Track Order',
            );
            
        }
        return $actions;
    }
    add_filter( 'woocommerce_my_account_my_orders_actions', 'smsa_sv_add_my_account_order_actions', 10, 2 );
         // Jquery script
add_action( 'woocommerce_after_account_orders', 'smsa_action_after_account_orders_js');
function smsa_action_after_account_orders_js() {
    $action_slug = 'smsa_track_link';
    ?>
    <script>
    jQuery(function($){
        $('a.<?php echo $action_slug; ?>').each( function(){
            $(this).attr('target','_blank');
        })
    });
    </script>
    <?php
}




 function smsa_mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = null)
    {
        if (!$encoding) {
            $diff = strlen($input) - mb_strlen($input);
        }
        else {
            $diff = strlen($input) - mb_strlen($input, $encoding);
        }
        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }

 


//class close
//}


