<?php
/**
 * Created by PhpStorm.
 * Date: 6/4/18
 * Time: 4:33 PM
 */
namespace Hfd\Woocommerce;

class Setting extends DataObject
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->hasData($key)) {
            $option = get_option($key);
            $this->setData($key, $option);
        }

        return $this->_getData($key);
    }

    /**
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->get('betanet_epost_google_api_key');
    }

    public function initDefaultSetting()
    {
        $_settings = array(
            'betanet_epost_service_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ws_spotslist&ARGUMENTS=-Aall',
            'betanet_epost_google_api_key' => '',
            'betanet_epost_hfd_active' => 1,
            'betanet_epost_hfd_service_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx',
            'betanet_epost_hfd_shipping_method' => array(\Hfd\Woocommerce\Shipping\Epost::METHOD_ID),
            'betanet_epost_hfd_sender_name' => 'default',
            'betanet_epost_hfd_customer_number' => 0,
            'betanet_epost_hfd_print_pdf_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ship_print_ws&ARGUMENTS=-N{shipping_number}',
			'betanet_epost_hfd_track_shipment_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ship_locate_random&ARGUMENTS=-A{RAND}',
			'betanet_epost_hfd_cancel_shipment_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=bitul_mishloah&ARGUMENTS=-A{shipping_number},-A,-A,-A,-N',
			'betanet_epost_hfd_print_label_url' => 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ship_print_ws&ARGUMENTS=-N{RAND}',
			'hfd_order_auto_sync' => 'no',
        );

        foreach( $_settings as $name => $value ){
			$option_value = get_option( $name );
            if( !$option_value ){
                update_option( $name, $value );
            }else{
				if( !is_array( $option_value ) && strpos( $option_value, "http://" ) !== false ){
					update_option( $name, $value );
				}else if( $name == "betanet_epost_hfd_customer_number" && $option_value == 3399 ){
					update_option( $name, 0 );
				}
			}
        }
    }
}