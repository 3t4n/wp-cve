<?php
/**
 * Plugin Name: WooCommerce Product License Manager
 * Plugin URI: https://appsbd.com/elite-licenser/
 * Description: It's a Elite Licenser Addon for WooCommerce.
 * Version: 1.9
 * Author: appsbd
 * Author URI: https://appsbd.com
 * Text Domain: woocommerce-elite-licenser-addon
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
require_once dirname( __FILE__ ) . "/inc/elite_licenser_woocommerce_base.php";
require_once dirname( __FILE__ ) . "/inc/EliteCaller.php";

class  EL_WOOCommerceAddon extends elite_licenser_woocommerce_base {

    public $eliteObject=null;
    public $isRemoteCall=false;
    function initialize() {
        $this->pluginFile    = __FILE__;
        $this->slug          = "woocommerce-elite-licenser-addon";
        $this->pluginVersion = "1.3";
        $this->pluginName = "WooCommerce Elite Licenser addon";
        $this->AddAjaxAction( "el_woo_settings", [ $this, 'SaveOption' ] );


    }
    public function Init() {
        parent::Init();
        add_action( 'woocommerce_product_options_pricing', [$this,'AddWooProductCustomField'] );
        add_action( 'woocommerce_variation_options_pricing', [$this,'AddWooVariationsCustomField'] ,10,3);

        add_action( 'woocommerce_process_product_meta', [$this,'save_order_custom_field']);
        add_action( 'woocommerce_save_product_variation', [$this,'save_order_custom_field_variations'], 10, 2 );

        add_action( 'woocommerce_order_details_before_order_table', [$this,'show_license_in_buyer_panel'], 20, 4 );
        add_action( 'woocommerce_subscription_details_table', [$this,'show_license_in_buyer_subscription_panel'], 20);
        add_action( 'woocommerce_admin_order_data_after_shipping_address', [$this,'show_license_in_buyer_panel_admin'], 10, 1 );
        add_action( 'woocommerce_admin_subscription_data_after_shipping_address', [$this,'show_license_in_buyer_panel_admin'], 10, 1 );
        add_action( 'woocommerce_email_order_details', [$this,'show_license_in_order_email'], 11, 1 );

        $wooEvent=$this->GetOption("el_ord_event","");


        if(!empty($wooEvent)){
            $eventName=$this->getEventName($wooEvent);
            if(!empty($eventName)) {
                add_action( $eventName, [ $this, 'generateLicenseKey' ] , 99, 1);
            }
        }
        $installType=$this->GetOption("installed_type","R");
        $apiKey=$this->GetOption("el_api_key","");
        $apiEndPoint=$this->GetOption("el_end_point","");

        if($installType=="R") {
            if ( ! empty( $apiEndPoint ) && ! empty( $apiKey ) ) {
                $this->isRemoteCall=true;
            }
        }
        add_action( 'woocommerce_subscription_status_updated', [$this,'status_updated_subscription'], 10, 3 );


        if($this->GetOption("is_on_refund","Y")=="Y"){
            add_action( 'woocommerce_order_status_refunded', [$this,'disable_liceense_on_refund'], 10, 1 );
        }
        if($this->GetOption("is_on_cancel","Y")=="Y"){
            add_action( 'woocommerce_order_status_cancelled', [$this,'disable_liceense_on_other_nagitive_status'], 10, 1 );
        }
        if($this->GetOption("is_on_failed","Y")=="Y"){
            add_action( 'woocommerce_order_status_failed', [$this,'disable_liceense_on_other_nagitive_status'], 10, 1 );
        }
    }
    function get_subscription_end_date($subscription){
	    $end_date="";
	    if ($subscription instanceof WC_Subscription) {
		    $end_date=$subscription->get_date('next_payment');
		    if(empty($end_date)){
			    $end_date = $subscription->get_date('schedule_end');
		    }
	    }
	    return $end_date;
    }
    function disable_liceense_on_refund($order_id){
        $this->disable_license($order_id,'R');
    }
    function disable_liceense_on_other_nagitive_status($order_id){
        $this->disable_license($order_id,'I');
    }
    function status_updated_subscription($subscription,$new_status,$old_status)
    {
        //pending-cancel
        //pending, active, on-hold, pending-cancel, cancelled, or expired
        if ($subscription instanceof WC_Subscription) {
            if (in_array($new_status, ['pending', 'on-hold', 'pending-cancel', 'cancelled', 'expired'])) {
                if($this->GetOption("is_on_cancel","Y")=="Y") {
                    $this->change_subscription($subscription, "I");
                }
            } elseif (in_array($new_status, ['active'])) {
                $this->change_subscription($subscription, "W");
            }
        }
    }
    function active_subscription($subscription)
    {
        if ($subscription instanceof WC_Subscription) {
            $this->change_subscription($subscription,"W");
        }
    }
    function cancel_subscription($subscription){
        if($subscription instanceof  WC_Subscription) {
            $this->change_subscription($subscription);
        }
    }

    /**
     * @param WC_Subscription $subscription
     * @param string $status
     */
    function change_subscription($subscription,$status='I',$end_date='') {
        $licensKeys=$this->getEliteLicenseKey($subscription);
        if(empty($end_date)){
            if($subscription instanceof  WC_Subscription) {
                $end_date=$this->get_subscription_end_date($subscription);
            }
        }
        $productList=[];
        $items=$subscription->get_items();
        foreach ($items as $item){
            $data=$item->get_data();
            if(!empty($data['product_id'])){
                $productList[]=$data['product_id'];
            }
        }
        $deleted_ids=[];
        if(!empty($productList)){
            //product license disable;
            $mainOrder=wc_get_order($subscription->get_parent_id());
            if(!empty($mainOrder) && !is_wp_error($mainOrder)){

                $items=$mainOrder->get_items();
                foreach ($items as $item){
                    $data=$item->get_data();
                    if(in_array($data['product_id'],$productList)){
                        $deleted_ids[]=$item->get_id();
                    }
                }
            }
        }
        if(empty($productList) && empty($deleted_ids)){
            //all disable
            foreach ($licensKeys as $licens_key) {
                if(!empty($licensKeys) && is_array($licens_key)){
                    foreach ($licens_key as $licenseItem){
                        $this->CallDisableLicense($licenseItem,$status,$end_date);
                    }
                }

            }
        }elseif(!empty($deleted_ids)){
            foreach ($licensKeys as $licenseItemName=>$licens_key) {
                if ( ! empty($licensKeys) && is_array($licens_key)) {
                    $Id = 0;
                    if (strlen($licenseItemName) > 3) {
                        $Id = (int)substr($licenseItemName, 3);
                    }
                    if (in_array($Id, $deleted_ids)) {
                        foreach ($licens_key as $licenseItem) {
                            $this->CallDisableLicense($licenseItem, $status,$end_date);
                        }
                    }
                }

            }
        }

    }
    function disable_license($order_id,$status='I',$end_date='') {
        $order = wc_get_order( $order_id );
        $orderMeta = $order->get_meta_data();
        $metaId = 0;
        $elmeta = $this->getElMeta( $orderMeta, $metaId );

        if ( ! empty( $elmeta ) ) {

            foreach ( $order->get_items() as $item_key => $item ) {
                $item_id = $item->get_id();
                if ( isset( $elmeta["el_{$item_id}"] ) ) {

                    foreach ( $elmeta["el_{$item_id}"] as $license ) {
                        $this->CallDisableLicense($license,$status,$end_date);
                    }
                }
            }
        }
    }
    function UpdateLicenseExpire($licenseKey,$expire_time){
        if($this->isRemoteCall){
            $apiKey=$this->GetOption("el_api_key","");
            $apiEndPoint=$this->GetOption("el_end_point","");
            $eliteObject=new EliteCaller($apiEndPoint,$apiKey);
            if($eliteObject->UpdateLicenseExpiry($licenseKey,$expire_time,$error)){
                return true;
            }else{
                error_log($error);
                return false;
            }
        }else{

            $lic=new Mapbd_license();
            if(!empty($expire_time)) {
                $lic->expiry_time($expire_time);
            }
            $lic->has_expiry('Y');

            $lic->status("W");
            $lic->SetWhereUpdate("purchase_key",$licenseKey);
            if($lic->Update()){
                return true;
            }else{
                return false;
            }

        }

    }
    function CallDisableLicense($licenseKey, $status='R', $expire_time='', $order_item_id=''){
        if($this->isRemoteCall){
            $apiKey=$this->GetOption("el_api_key","");
            $apiEndPoint=$this->GetOption("el_end_point","");
            $eliteObject=new EliteCaller($apiEndPoint,$apiKey);
            if($eliteObject->DisableLicenseLicense($licenseKey,$status,$error,$expire_time,$order_item_id)){
                return true;
            }else{
                error_log($error);
                return false;
            }

        }else{

            $lic=new Mapbd_license();
            if(!empty($expire_time)){
                $lic->expiry_time($expire_time);
                $lic->has_expiry('Y');
            }
            if(!empty($order_item_id)){
                $lic->extra_param($order_item_id);
            }
            $lic->status($status);
            $lic->SetWhereUpdate("purchase_key",$licenseKey);
            if($lic->Update()){
                return true;
            }else{
                return false;
            }

        }

    }
	function show_license_in_order_email( $order ) {

		$orderMeta = $order->get_meta_data();
		$metaId=0;
		$elmeta    = $this->getElMeta($orderMeta,$metaId);

		if(!empty($elmeta)){
			?>
            <h2 class="email-upsell-title"><?php $this->_e("Your Product License Codes") ; ?></h2>
            <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
                <thead>
                <tr>
                    <th class="td"><?php $this->_e("Product") ; ?></th>
                    <th class="td"><?php $this->_e("License(s)") ; ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				$totalLicense=0;
				foreach ( $order->get_items() as $item_key => $item ) {
					$item_id= $item->get_id();
					if(isset($elmeta["el_{$item_id}"])) {?>
                        <tr >
                            <td class="td">
								<?php echo esc_html($item->get_name()); ?> × <?php echo esc_html($item->get_quantity()); ?>
                            </td>
                            <td class="td">
								<?php
								$i= 1;
								$totalItemLicense = count( $elmeta["el_{$item_id}"] );
								foreach ( $elmeta["el_{$item_id}"] as $license ) {

									$totalLicense ++;
									echo ( $totalItemLicense > 1 ? $this->__("License Code %d",$i).":<br/>" : "" ) .'<span class="el-color-green">'. esc_html($license) . '</span><br/>';
									$i ++;
								}
								?>
                            </td>
                        </tr>
						<?php
					}
				}
				?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="td" scope="row"><?php $this->_e("Total") ; ?>:</th>
                    <td class="td"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php $this->_e("%s License(s)",$totalLicense); ?></span></td>
                </tr>
                </tfoot>
            </table>
            <br>
			<?php
		}

	}
    function show_license_in_buyer_panel( $order ) {

        $orderMeta = $order->get_meta_data();
        $metaId=0;
        $elmeta    = $this->getElMeta($orderMeta,$metaId);

        if(!empty($elmeta)){
            ?>
            <h2 class="email-upsell-title"><?php $this->_e("Your Product License Codes") ; ?></h2>
            <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

                <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name"><?php $this->_e("Product") ; ?></th>
                    <th class="woocommerce-table__product-table product-total"><?php $this->_e("License(s)") ; ?></th>
                </tr>
                </thead>

                <tbody>
                <?php
                $totalLicense=0;
                foreach ( $order->get_items() as $item_key => $item ) {

                    $item_id= $item->get_id();
                    if(isset($elmeta["el_{$item_id}"])) {?>
                        <tr class="woocommerce-table__line-item order_item">
                            <td class="woocommerce-table__product-name product-name">
                                <?php echo esc_html($item->get_name()); ?> × <?php echo esc_html($item->get_quantity()); ?>
                            </td>
                            <td class="woocommerce-table__product-total product-total">
                                <?php
                                $i= 1;
                                $totalItemLicense = count( $elmeta["el_{$item_id}"] );
                                foreach ( $elmeta["el_{$item_id}"] as $license ) {

                                    $totalLicense ++;
                                    echo ( $totalItemLicense > 1 ? $this->__("License Code %d",$i).":<br/>" : "" ) .'<span class="el-color-green">'. esc_html($license) . '</span><br/>';
                                    $i ++;
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th scope="row"><?php $this->_e("Total") ; ?>:</th>
                    <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php $this->_e("%s License(s)",$totalLicense); ?></span></td>
                </tr>
                </tfoot>
            </table>
            <?php
        }

    }

    function getSubscriptionLicense(&$licenses=[], $subscription,$already_checked=[])
    {
        if(!function_exists('wcs_is_subscription') || !function_exists('wcs_is_order')){
            return;
        }
        if(wcs_is_subscription($subscription)) {
            $relared_orders_ids_array = $subscription->get_related_orders();
            foreach ($relared_orders_ids_array as $order_id) {
                if(!in_array($order_id,$already_checked)) {
                    $already_checked[]=$order_id;
                    $order = wc_get_order($order_id);
                    $orderMeta = $order->get_meta_data();
                    $metaId = 0;
                    $elmeta = $this->getElMeta($orderMeta, $metaId);
                    if (!empty($elmeta)) {
                        $order->get_items();
                        foreach ($order->get_items() as $item_key => $item) {
                            $item_id = $item->get_id();
                            $product  = $item->get_product();
                            $product_id=$product->get_id();
                            if (!empty($elmeta["el_{$item_id}"])) {
                                $item_data = $item->get_data();
                                $obj = new stdClass();
                                $obj->item_id = $item->get_id();
                                $obj->item_product_id = $product_id;
                                $obj->item_order = $item->get_order_id();
                                $obj->item_name = $item->get_name();
                                $obj->quantity = $item->get_quantity();
                                $obj->licenses = $elmeta["el_{$item_id}"];
                                $licenses[] = $obj;
                            }
                        }
                    }
                    // $initial_subscriptions = wcs_get_subscriptions_for_order($order, array('subscription_status' => array('active')));
                    $initial_subscriptions = wcs_get_subscriptions_for_order($order);
                    foreach ($initial_subscriptions as $sub) {

                        $this->getSubscriptionLicense($licenses, $sub,$already_checked);
                    }
                }

            }
        }elseif(wcs_is_order($subscription)){
            $order_id=$subscription->get_id();
            if(!in_array($order_id,$already_checked)) {

                // $initial_subscriptions = wcs_get_subscriptions_for_order($subscription, array('subscription_status' => array('active')));
                $initial_subscriptions = wcs_get_subscriptions_for_order($subscription);
                foreach ($initial_subscriptions as $sub) {
                    $already_checked[]=$order_id;
                    $this->getSubscriptionLicense($licenses, $sub,$already_checked);
                }
            }
        }
    }

    /**
     * @param WC_Subscription $subscription
     */
    function show_license_in_buyer_subscription_panel( $subscription ) {
        $relared_orders_ids_array = $subscription->get_related_orders();
        $licenses=[];
        $this->getSubscriptionLicense($licenses,$subscription);
        if(!empty($licenses)){
                ?>
                <h2 class="email-upsell-title"><?php $this->_e("Your Product License Codes") ; ?></h2>
                <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

                    <thead>
                    <tr>
                        <th class="woocommerce-table__product-name product-name"><?php $this->_e("Product") ; ?></th>
                        <th class="woocommerce-table__product-table product-total"><?php $this->_e("License(s)") ; ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalLicense=0;
                    $viewedLicenses=[];
                    foreach ( $licenses as $licenseObj ) {
                        $checkId=$licenseObj->item_product_id."_".$licenseObj->quantity;
                        if(in_array($checkId,$viewedLicenses)) {
                            continue;
                        }
                        $viewedLicenses[]=$checkId;
?>
                            <tr class="woocommerce-table__line-item order_item">
                                <td class="woocommerce-table__product-name product-name">
                                    <?php echo esc_html($licenseObj->item_name); ?> × <?php echo esc_html($licenseObj->quantity); ?>
                                </td>
                                <td class="woocommerce-table__product-total product-total">
                                    <?php
                                    $i= 1;
                                    $totalItemLicense=count($licenseObj->licenses);
                                    foreach ( $licenseObj->licenses as $license ) {
                                        $totalLicense++;
                                        echo ($totalItemLicense > 1 ? $this->__("License Code %d", $i) . ":<br/>" : "") . '<span class="el-color-green">' . esc_html($license) . '</span><br/>';
                                        $i++;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php

                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th scope="row"><?php $this->_e("Total") ; ?>:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php $this->_e("%s License(s)",$totalLicense); ?></span></td>
                    </tr>
                    </tfoot>
                </table>
                <?php
        }

    }
    /**
     * @param WC_Subscription $order
     *
     * @return array|mixed|null
     */
    function getEliteLicenseKey($order){
        if(empty($order)){
            return null;
        }

        $isNeedToUpdate=false;
        $subscriptionOrder=null;
        if($order instanceof  WC_Subscription){
            $orderMeta = $order->get_meta_data();
            $elmeta = $this->getElMeta( $orderMeta, $metaId );
            if(empty($elmeta)) {
                $parent_post_id= $order->get_parent_id();
                $mainorder=wc_get_order($parent_post_id);
                $isNeedToUpdate=true;
                $subscriptionOrder=$order;
                $order=$mainorder;
            }else{
                return $elmeta;
            }
        }
        if(empty($order)){
            return null;
        }
        $metaId = 0;
        $orderMeta = $order->get_meta_data();
        $elmeta = $this->getElMeta( $orderMeta, $metaId );
        if(!empty($elmeta)){
            if($isNeedToUpdate){
                $subscriptionOrder->update_meta_data("_el_meta", serialize($elmeta), $metaId);
            }
            return $elmeta;
        }
        return null;
    }
    function show_license_in_buyer_panel_admin( $order ) {

        $elmeta=$this->getEliteLicenseKey($order);
        if($order instanceof  WC_Subscription){
            $parent_post_id= $order->get_parent_id();
            $order=wc_get_order($parent_post_id);
        }

        if ( ! empty( $elmeta ) ) {
            ?>
            <h3 ><?php $this->_e("License Codes") ?></h3>
            <?php
            $totalLicense = 0;
            foreach ( $order->get_items() as $item_key => $item ) {

                $item_id = $item->get_id();
                if ( isset( $elmeta["el_{$item_id}"] ) ) { ?>
                    <div class="el-buyer-admin-panel-wrapper">
                        <h4 class="el-buyer-admin-panel-license"><?php echo esc_html($item->get_name()); ?>-</h4>
                        <?php $i = 1;

                        $totalItemLicense = count( $elmeta["el_{$item_id}"] );
                        foreach ( $elmeta["el_{$item_id}"] as $license ) {
                            $totalLicense ++;
                            echo ( $totalItemLicense > 1 ? $this->__("License Code %d",$i)." :<br/>" : "" ) . esc_html($license)."<br/>";
                        } ?>


                    </div>
                    <?php
                }
            }
        }

    }
    function getElMeta(&$allMeta,&$metaid=0){
        foreach ($allMeta as $a){
            $d=$a->get_data();
            if(!empty($d['key']=='_el_meta')){
                $metaid=$d['id'];
                return !empty($d['value'])?unserialize($d['value']):[];
            }
        }
        return [];
    }
    function getLicenseCodeByProductId(&$licenses,$product_id){
        if(!empty($licenses) && is_array($licenses)){
            foreach ($licenses as $license) {
                if(!empty($license->item_product_id) && $license->item_product_id==$product_id){
                    return $license;
                }
            }
        }
        return null;
    }

    /**
     * @param WC_Order $order
     * @param WC_Subscription $subscription
     */
    function generateSubscriptionLicenseKey($order,$subscription) {
        $order_id=$order->get_id();
        $subscription_end_date=$this->get_subscription_end_date($subscription);
        $orderMeta = $order->get_meta_data();
        $licenses=[];
        $this->getSubscriptionLicense($licenses,$subscription);
        $metaId=0;
        $elmeta    = $this->getElMeta($orderMeta,$metaId);
        if(empty($elmeta) || is_string($elmeta)){
            $elmeta=[];
        }

        foreach ( $order->get_items() as $item_key => $item ) {
            $item_id = $item->get_id();
            $product  = $item->get_product();
            $item_qty = $item->get_quantity();
            //$product=new WC_Product();
            $product_id=$product->get_id();
            $old_license=$this->getLicenseCodeByProductId($licenses,$product_id);
            if(!empty($old_license)){
                $elmeta[ "el_" . $item_id ] = $old_license->licenses;
                foreach ($old_license->licenses as $licens) {
                    $this->UpdateLicenseExpire($licens,$subscription_end_date);
                }
            }else{
                $mappedProduct = $product->get_meta( "el_mapped_product" );
                if (!empty( $mappedProduct ) ) {
                    $mp = explode( "-", $mappedProduct );
                    if ( count( $mp ) == 2 ) {
                        if(!isset($elmeta[ "el_" . $item_id])) {
                            $licensePost                = [
                                'product_id'     => $mp[0],
                                'license_id'     => $mp[1],
                                'client_email'   => $order->get_billing_email(),
                                'client_name'    => $order->get_billing_first_name() . " " . $order->get_billing_last_name(),
                                'client_country' => $order->get_billing_country(),
                                'client_company' => $order->get_billing_company(),
                                'market'         => 'W',
                                'qty'            => $item_qty,
                                'extra_param'    => $order_id . "-" . $item_id,
                                'extra_param2'    => $subscription->get_id()

                            ];
                            $licenseCodes=$this->addNewLicense($licensePost);
                            if(!empty($licenseCodes)) {
                                $elmeta[ "el_" . $item_id ] = $licenseCodes;
                            }
                        }
                    }

                }
            }
        }
        $order->update_meta_data("_el_meta",serialize($elmeta),$metaId);
        $order->save();

    }
    function generateLicenseKey($order_id) {
        $order = wc_get_order( $order_id );
        //checking subscription
        if( function_exists('wcs_get_subscription')) {
            $subscription_id = $order->get_meta('_subscription_renewal_early');
            if (empty($subscription_id)) {
                $subscription_id = $order->get_meta('_subscription_renewal');
                if (empty($subscription_id)) {
                    $subscription_id = $order->get_meta('_subscription_resubscribe');
                }
            }
            if (!empty($subscription_id)) {
                $subscription = wcs_get_subscription($subscription_id);
                return $this->generateSubscriptionLicenseKey($order, $subscription);
            }
        }
        // it is not subscription
        $orderMeta = $order->get_meta_data();
        $metaId=0;
        $elmeta    = $this->getElMeta($orderMeta,$metaId);
        if(empty($elmeta) || is_string($elmeta)){
            $elmeta=[];
        }

        foreach ( $order->get_items() as $item_key => $item ) {
            $item_id = $item->get_id();
            $product  = $item->get_product();
            $item_qty = $item->get_quantity();
            $mappedProduct = $product->get_meta( "el_mapped_product" );
            if (!empty( $mappedProduct ) ) {
                $mp = explode( "-", $mappedProduct );
                if ( count( $mp ) == 2 ) {
                    if(!isset($elmeta[ "el_" . $item_id])) {
                        $licensePost                = [
                            'product_id'     => $mp[0],
                            'license_id'     => $mp[1],
                            'client_email'   => $order->get_billing_email(),
                            'client_name'    => $order->get_billing_first_name() . " " . $order->get_billing_last_name(),
                            'client_country' => $order->get_billing_country(),
                            'client_company' => $order->get_billing_company(),
                            'market'         => 'W',
                            'qty'            => $item_qty,
                            'extra_param'    => $order_id . "-" . $item_id

                        ];
                        $licenseCodes=$this->addNewLicense($licensePost);
                        if(!empty($licenseCodes)) {
                            $elmeta[ "el_" . $item_id ] = $licenseCodes;
                        }
                    }
                }

            }
        }
        $order->update_meta_data("_el_meta",serialize($elmeta),$metaId);
        $order->save();

    }
    function addNewLicense($licensePost){
        if($this->isRemoteCall){
            $apiKey=$this->GetOption("el_api_key","");
            $apiEndPoint=$this->GetOption("el_end_point","");
            $eliteObject=new EliteCaller($apiEndPoint,$apiKey);
            $response=$eliteObject->AddLicense($licensePost);
            if(!empty($response->license_code)){
                return $response->license_code;
            }else{
                return null;
            }
        }else{

            $client=Mapbd_client::FindBy("email",$licensePost['client_email']);
            $client_id="";
            if($client){
                $client_id=$client->id;
            }else{

                $nc=new Mapbd_client();
                $nc->email($licensePost['client_email']);
                $nc->name($licensePost['client_name']);
                $nc->status('A');
                $nc->country($licensePost['client_country']);
                $nc->company($licensePost['client_company']);
                if($nc->Save()){
                    $client_id=$nc->id;
                }
            }
            $licenseObj=Mapbd_license_type::FindBy("id",$licensePost['license_id' ]);
            $licenseCodes=[];
            for($i=1;$i<=$licensePost['qty' ];$i++){
                $mnp=new Mapbd_license();
                $mnp->product_id($licensePost['product_id' ]);
                $mnp->license_id($licensePost['license_id' ]);
                $mnp->client_id($client_id);
                $mnp->market($licensePost['market']);
                $mnp->extra_param($licensePost['extra_param']);
                $mnp->has_expiry( $licenseObj->has_expiry );
                $mnp->status( 'W');
                if ( $licenseObj->has_expiry == "Y" ) {
                    $mnp->expiry_time( date( 'Y-m-d H:i:s', strtotime( "+" . $licenseObj->days_of_expiry . " DAYS" ) ) );
                }
                $mnp->has_support( $licenseObj->has_support );
                if ( $licenseObj->has_support == "Y" ) {
                    $mnp->support_end_time( date( 'Y-m-d H:i:s', strtotime( "+" . $licenseObj->days_of_support . " DAYS" ) ) );
                }
                if($mnp->Save()){
                    $licenseCodes[]=$mnp->purchase_key;
                }
            }
            return $licenseCodes;
        }
    }
    function GetLocalProductList(){
        $pros=[''=>"Select Elite Product"];
        $totalProduct=null;
        $products=Mapbd_product::GetProductWithLicenseType('','','',$totalProduct);
        if(!empty($products) && is_array($products)){
            foreach ($products as $p){
                if(!empty($p->product_licenses)){
                    $p->product_licenses=(array)$p->product_licenses;
                    foreach ($p->product_licenses as $pl_key=>$pl){
                        $pros["{$p->id}-{$pl_key}"]=$p->product_name."-".$pl;
                    }
                }
            }
        }
        return $pros;
    }
    function AddWooProductCustomField(){
        $installType=$this->GetOption("installed_type","R");
        $apiKey=$this->GetOption("el_api_key","");
        $apiEndPoint=$this->GetOption("el_end_point","");

        $error="";
        $product_options=[];
        if($installType=="R"){
            if(empty($apiEndPoint) || empty($apiKey)){

            }else{
                $eliteObject=new EliteCaller($apiEndPoint,$apiKey);
                $product_options=$eliteObject->GetProductList($error);
            }
        }elseif($this->HasInstalledAndActiveEl()) {
            $product_options=$this->GetLocalProductList();
        }
        $args = array(
            'id' => 'el_mapped_product',
            'wrapper_class' => 'show_if_downloadable show_if_variation_downloadable',
            'desc_tip' => true,
            'description' =>$error. __('Please choose the elite product, if you want to generate a license key using Elite Licenser', $this->slug),
            'label' => __( 'Elite Licenser Product', $this->slug),
            'options' => $product_options
        );
        woocommerce_wp_select( $args );
    }
    function AddWooVariationsCustomField($loop, $variation_data, $variation){
        $installType=$this->GetOption("installed_type","R");
        $apiKey=$this->GetOption("el_api_key","");
        $apiEndPoint=$this->GetOption("el_end_point","");

        $error="";
        $product_options=[];
        if($installType=="R"){
            if(empty($apiEndPoint) || empty($apiKey)){

            }else{
                $eliteObject=new EliteCaller($apiEndPoint,$apiKey);
                $product_options=$eliteObject->GetProductList($error);
            }
        }elseif($this->HasInstalledAndActiveEl()) {
            $product_options=$this->GetLocalProductList();
        }
        $args = array(
            'id' => 'el_mapped_product['.$loop.']',
            'wrapper_class' => 'form-row show_if_downloadable show_if_variation_downloadable',
            'class' => 'short',
            'desc_tip' => true,
            'description' =>$error." -".$variation->ID."-". __('Please choose the elite product, if you want to generate a license key using Elite Licenser', $this->slug),
            'label' => __( 'Elite Licenser Product', $this->slug),
            'options' => $product_options,
            'value' => get_post_meta( $variation->ID, 'el_mapped_product', true )
        );
        woocommerce_wp_select( $args );
    }
    function save_order_custom_field( $post_id ) {
        $product = wc_get_product( $post_id );
        $mappedProduct = isset( $_POST['el_mapped_product'] ) ? sanitize_text_field($_POST['el_mapped_product']) : '';
        $product->update_meta_data( 'el_mapped_product',  $mappedProduct );
        $product->save();
    }
    function save_order_custom_field_variations( $variation_id, $i ) {

        $custom_field = !empty($_POST['el_mapped_product'][$i])?$_POST['el_mapped_product'][$i]:"";

        update_post_meta( $variation_id, 'el_mapped_product', esc_attr( $custom_field ) ) || add_post_meta( $variation_id, 'el_mapped_product', esc_attr( $custom_field ) );
    }
    function getEventArray() {
        $events = [
            'wpc' => 'woocommerce_payment_complete',
            'wsp' => 'woocommerce_order_status_processing',
            'wsc' => 'woocommerce_order_status_completed',
        ];
        return $events;

    }
    function getEventName($evt){
        $events=$this->getEventArray();
        if(isset($events[$evt])){
            return $events[$evt];
        }
        return '';
    }

    function AdminMenu() {
        add_submenu_page( 'woocommerce', 'Elite Licenser Settings', 'Elite Licenser Settings', 'manage_options', $this->slug, [
            $this,
            'OptionPage'
        ]);
    }

    function SaveOption() {
        $response   = new AppsbdAjaxConfirmResponse();
        $beforeSave = $this->options;
        $posts      = $_POST;
        if ( ! empty( $posts['action'] ) ) {
            unset( $posts['action'] );
        }
        foreach ( $posts as $key => $post ) {
            $this->options[ $key ] = sanitize_text_field($post);
        }
        if(isset($this->options['installed_type']) && $this->options['installed_type']=="R"){
            $eObj=new EliteCaller($this->options['el_end_point'],$this->options['el_api_key']);
            if(!$eObj->IsApiCanAddEditLicense($error)){
                $response->DisplayWithResponse( false, $this->__( $error ) );
                return;
            }
        }
        if ( $beforeSave === $this->options ) {
            $response->DisplayWithResponse( false, $this->__( "No change for update" ) );
        } else {
            $response->SetResponse( false, $this->__( "No change for update" ) );
            if ( $this->UpdateOption() ) {
                $response->DisplayWithResponse( true, $this->__( "Saved Successfully" ) );
            } else {
                $response->DisplayWithResponse( false, $this->__( "No change for update" ) );
            }
        }
        $response->Display();
    }
    function HasInstalledAndActiveEl(){
        $activates = get_option( 'active_plugins' );
        return in_array('elite-licenser/elitelicenser.php',$activates);
    }
    function OptionPage() {

        ?>
        <div id="APPSBDWP">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header"><h3
                                class="m-0"><?php $this->_e( "Elite Licenser - WooCommerce Addons Settings" ); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-7 pr-sm-0">
                                <div class="card ">
                                    <div class="card-body">
                                        <form method="post" role="form" class="apbd-module-form"
                                              action="<?php echo esc_html( $this->GetActionUrl( "el_woo_settings" ) ); ?>">
                                            <div class="form-group form-group-sm row">
                                                <label for="el_ord_event"
                                                       class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "License Generate Event" ); ?></label>
                                                <div class="col-sm">
                                                    <select class="custom-select custom-select-sm"
                                                            name="el_ord_event" id="el_ord_event"
                                                            data-bv-notempty="true"
                                                            data-bv-notempty-message="<?php $this->_ee( "%s is required", "License Generator Event" ); ?>">
                                                        <option value=""><?php $this->_e( "Select" ); ?></option>
                                                        <?php
                                                        $selected_ord_event = $this->GetOption( "el_ord_event" );
                                                        APBD_GetHTMLOption( "wpc", "On WooCommerce Order Payment Complete", $selected_ord_event );
                                                        APBD_GetHTMLOption( "wsp", "On WooCommerce Order Processing", $selected_ord_event );
                                                        APBD_GetHTMLOption( "wsc", "On WooCommerce Order Complete", $selected_ord_event );
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="inputEmail3"
                                                       class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Installed Type" ); ?></label>

                                                <div class="col-sm pt-2">
                                                    <?php
                                                    $status_selected   = esc_html($this->GetOption( "installed_type", "R" ));
                                                    APBD_GetHTMLRadio("Remote Server","R","installed_type","installed_type_r",true,$status_selected,false,"has_depend_fld");
                                                    if(!$this->HasInstalledAndActiveEl()){
                                                        APBD_GetHTMLRadio("In this APP [Not Installed or Activated]","S","installed_type","installed_type_s",true,$status_selected,true,"has_depend_fld");
                                                    }else{
                                                        APBD_GetHTMLRadio("In this APP","S","installed_type","installed_type_s",true,$status_selected,false,"has_depend_fld");
                                                    }

                                                    ?>
                                                    <span class="form-text mb-3 fld-installed-type fld-installed-type-s el-hidden" >
                                                            <i class=" animated faa-pulse fa fa-exclamation-circle"></i>
                                                            <?php $eliteLicenserText = '<span class="text-bold">' . $this->__( "Elite Licenser" ) . '</span>';
                                                            $this->_e( "Normally %s should be installed in remote server ( in another application), cause %s is for all type of product that can be sell from this application or from other application. %s", $eliteLicenserText, $eliteLicenserText, '<span class="text-bold">' . $this->__( "Remote server configuration is recommended " ) . '</span>' ); ?>
                                                        </span>
                                                </div>
                                            </div>
                                            <div class="fld-installed-type fld-installed-type-r">
                                                <div class="form-group form-group-sm row">
                                                    <label for="el_end_point"
                                                           class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Elite Licenser API End Point" ); ?></label>
                                                    <div class="col-sm">
                                                        <input type="text" name="el_end_point"
                                                               class="form-control form-control-sm"
                                                               id="el_end_point" value="<?php echo esc_html($this->GetOption( "el_end_point","" )); ?>"
                                                               placeholder="<?php $this->_e( "Elite Licenser API End Point" ); ?>"
                                                               data-bv-notempty="true"
                                                               data-bv-notempty-message="<?php $this->_ee( "%s is required", "Elite Licenser API End Point" ); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="fld-installed-type fld-installed-type-r">
                                                <div class="form-group form-group-sm row">
                                                    <label for="el_api_key"
                                                           class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Elite Licenser API Key" ); ?></label>
                                                    <div class="col-sm">
                                                        <input type="text" name="el_api_key" value="<?php echo esc_html($this->GetOption( "el_api_key","" )); ?>"
                                                               class="form-control form-control-sm" id="el_api_key"
                                                               placeholder="<?php $this->_e( "Elite Licenser API Key" ); ?>"
                                                               data-bv-notempty="true"
                                                               data-bv-notempty-message="<?php $this->_ee( "%s is required", "Elite Licenser API Key" ); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="">
                                                <h5  class=""><?php $this->_e( "Disable License key when" ); ?></h5>
                                            </div>
                                            <div class="form-group row">
                                                <label for="is_on_refund"  class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Order Refund" ); ?></label>
                                                <div class="col-sm pt-2 d-flex">
                                                    <?php
                                                    APBD_GetHTMLSwitchButton("is_on_refund","is_on_refund","N","Y",esc_html($this->GetOption("is_on_refund")));
                                                    ?>
                                                    <span class="form-text text-muted"><?php $this->_ee("If you enable it then generated key will be  disable automatically when a order will be refund");?></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="is_on_cancel"  class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Order Cancel" ); ?></label>
                                                <div class="col-sm pt-2 d-flex">
                                                    <?php
                                                    APBD_GetHTMLSwitchButton("is_on_cancel","is_on_cancel","N","Y",esc_html($this->GetOption("is_on_cancel")));
                                                    ?>
                                                    <span class="form-text text-muted"><?php $this->_ee("If you enable it then generated key will be  disable automatically when a order will be cancel");?></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="is_on_failed"  class="col-sm-4 col-form-label sm-text-right"><?php $this->_e( "Order Failed" ); ?></label>
                                                <div class="col-sm pt-2 d-flex">
                                                    <?php
                                                    APBD_GetHTMLSwitchButton("is_on_failed","is_on_failed","N","Y",esc_html($this->GetOption("is_on_failed")));
                                                    ?>
                                                    <span class="form-text text-muted"><?php $this->_ee("If you enable it then generated key will be  disable automatically when a order will be failed");?></span>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-row mt-3">
                                                <div class="form-group col-sm sm-text-right">
                                                    <button type="submit" class="btn btn-sm btn-success"><i
                                                                class="fa fa-save"></i> <?php $this->_e( "Save" ); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>


                            </div>
                            <div class="col-sm-5 mt-3 mt-sm-0">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php $this->_e('About Eite Licenser') ?></h5>
                                        <p class="card-text"><?php $this->_e('Elite Licenser is a WordPress plugin for any types of product licensing. It also manages product updates, auto generates license code, built in Envato licensing verification system, full license control and more. It has full set of API, so you can handle it by other applications as well. One app handles license of all your products. You can handle any language (PHP, .Net, Java, Android, etc.). Also you can add licensing to more than one WordPress plugin or theme and it can be installed on same WordPress.');?></p>

                                        <a href="https://link.appsbd.com/m0v5?f=elwaw" target="_blank" class="btn btn-sm btn-success"><?php $this->_e('View Details');?></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}

new EL_WOOCommerceAddon();