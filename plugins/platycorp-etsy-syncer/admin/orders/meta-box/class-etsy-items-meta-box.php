<?php
use platy\etsy\EtsySyncerException;
use platy\etsy\orders\EtsyOrdersSyncer;
use platy\etsy\orders\EtsyOrderItem;

class PlatyEtsyOrderMetaBox{

    public static function output(){
        global $post, $thepostid, $theorder;

		if ( !empty($thepostid) && ! is_int( $thepostid ) ) {
			$thepostid = $post->ID;
		}

		if ( ! is_object( $theorder ) ) {
			$theorder = wc_get_order( $thepostid );
		}

		$order = $theorder;

		include __DIR__ . '/etsy-order-meta-box.php';

		
    }

	public static function save($post_id, $post){
		$order = wc_get_order( $post_id );
		$error_message = "";
		if ( ! empty( $_POST['wc_order_action'] )) {

			$action = wc_clean( wp_unslash( $_POST['wc_order_action'] ) );
			if($action == "resync_etsy_order" && EtsyOrdersSyncer::is_post_etsy_order($post_id)){
				try{
					$syncer = new EtsyOrdersSyncer();
					$syncer->sync_order(EtsyOrdersSyncer::get_etsy_order_receipt_id($post_id));
				}catch(EtsySyncerException $e){
					$error_message = $e->getMessage();
					
				}

			}

			if($action == "complete_etsy_order" && EtsyOrdersSyncer::is_post_etsy_order($post_id)){
				try{
					$tracking_num = $_REQUEST['etsy-tracking-number'];
					$carrier = $_REQUEST['etsy-carrier-name'];
					$syncer = new EtsyOrdersSyncer();
					$receipt_id = EtsyOrdersSyncer::get_etsy_order_receipt_id($post_id);
					$syncer->complete_order($receipt_id, $post_id, $tracking_num, $carrier);
					$order = wc_get_order($post_id);
					$order->update_meta_data( "plty_etsy_tracking", $tracking_num );
        			$order->update_meta_data( "plty_etsy_carrier", $carrier );
				}catch(EtsySyncerException $e){
					$error_message = $e->getMessage();
					
				}

			}
		}
		if($error_message){
			set_transient( "platy_etsy_error_transient", $error_message, 2 );
		}
    }
}