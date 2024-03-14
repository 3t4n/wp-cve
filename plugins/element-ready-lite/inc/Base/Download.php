<?php 

namespace Element_Ready\Base;
use Element_Ready\Base\BaseController;

/*
* Woocommerce product download
*/
class Download extends BaseController
{
	public function register() {

         if ( !class_exists( 'WooCommerce' ) ) {
              return;
         }
         
         add_action( 'init', [$this,'download'] );
 	}
	 
  public function download(){
       
      if( 
          isset($_REQUEST['product_id'])
          && isset($_REQUEST['downloadtype'])
          && $_REQUEST['downloadtype'] == 'free'
          && isset($_REQUEST['download_id']) 
         ){
   
                $product_id_ = sanitize_text_field($_REQUEST['product_id']);
                $product = wc_get_product( $product_id_ );
                $theFile = $product->get_file_download_path(sanitize_text_field($_REQUEST['download_id']));
                 
                if( $product->get_price() == 0 || $product->get_price() =='' ) {
                    \WC_Download_Handler::download( $product->get_file_download_path( sanitize_text_field($_REQUEST['download_id'])  ),sanitize_text_field($_REQUEST['product_id']) );
                }
             
  
         }
       
         if( 
          isset($_REQUEST['product_id'])
          && isset($_REQUEST['downloadtype'])
          && $_REQUEST['downloadtype'] == 'pro'
          && isset($_REQUEST['download_id']) 
         ){
                   
                $product = wc_get_product( sanitize_text_field($_REQUEST['product_id']) );
                $theFile = $product->get_file_download_path(sanitize_text_field( $_REQUEST[ 'download_id' ]) );
               \WC_Download_Handler::download( $product->get_file_download_path( sanitize_text_field($_REQUEST[ 'download_id' ]) ),sanitize_text_field($_REQUEST[ 'product_id' ]) );
               
         }
      
  }
  
}