<?php
require_once RednaoWooCommercePDFInvoice::$DIR.'smarty/PDFAbstractDataRetriever.php';
abstract class WrapperBase_deprecated{
    /**
     * @var WC_Abstract_Order
     */
    public $primaryOrder;


    public abstract function get($propertyName,$context='view');

    protected function GetValueFromObject($object,$propertyName,$context='view')
    {
        if(PDFAbstractDataRetriever::IsVersionGreathThan3_0())
        {
            if($propertyName=='order_date')
                return $object->get_date_created()->getOffsetTimestamp();
            if ( is_callable( array( $object, "get_{$propertyName}" ) ) ) {
                return $object->{"get_{$propertyName}"}( $context );
            }
        }else {
            if ( isset( WrapperBase::$compatibility_props[ $propertyName ] ) ) {
                $propertyName= WrapperBase::$compatibility_props[ $propertyName ];
            }

            $value='';
            if ( is_callable( array( $object, "get_{$propertyName}" ) ) && $context=='view') {
                $value= $object->{"get_{$propertyName}"}();
            } else {
                if(is_callable(array($object,$propertyName)))
                {
                    $value = $object->$propertyName;
                }
                else
                    if(isset($object->$propertyName))
                    {
                        $value = $object->$propertyName;
                    }
            }

            if(in_array($propertyName,array( 'date_completed', 'date_paid', 'date_modified', 'date_created' ), true ))
            {
                if ( is_numeric( $value ) ) {
                    $value = new WC_DateTime( "@{$value}", new \DateTimeZone( 'UTC' ) );
                    $value->setTimezone( new \DateTimeZone( wc_timezone_string() ) );
                } else {
                    if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $value, $date_bits ) ) {
                        $offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : wc_timezone_offset();
                        $timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
                    } else {
                        $timestamp = wc_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', wc_string_to_timestamp( $value ) ) ) );
                    }
                    $value = new WC_DateTime( "@{$timestamp}", new \DateTimeZone( 'UTC' ) );
                }

                if ( get_option( 'timezone_string' ) ) {
                    $value->setTimezone( new \DateTimeZone( wc_timezone_string() ) );
                } else {
                    $value->set_utc_offset( wc_timezone_offset() );
                }
            }

            return $value;

        }
        return '';
    }

    public function GetId(){
        if ( method_exists( $this->primaryOrder, 'get_id' ) ) {

            return $this->primaryOrder->get_id();

        } else {

            $idName='id';
            return isset($this->primaryOrder->$idName) ? $this->primaryOrder->$idName : false;
        }
    }
    public static function WrapOrder($order){
        if($order==null)
            return null;
        if ($order instanceof WC_Order)
        {
            require_once RednaoWooCommercePDFInvoice::$DIR.'smarty/wrappers/OrderWrapper.php';
            return  new OrderWrapper($order);
        } else
        {
            require_once RednaoWooCommercePDFInvoice::$DIR.'smarty/wrappers/RefundWrapper.php';
            return new RefundWrapper($order);
        }
    }

    public function GetTotal($totalType,$omitIfEmpty=false)
    {
        $totals=$this->primaryOrder->get_order_item_totals('excl');
        if(isset($totals["$totalType"]))
        {
            return $totals["$totalType"]['value'];
        }
        else
        {
            if($omitIfEmpty)
                return '';
            return wc_price(0, \apply_filters('rnwcinv_format_price',array('currency' => $this->GetValueFromObject($this->primaryOrder, 'currency'))));
        }

        /*
        if($totalType=='subtotal')
        {
            $totals=$this->primaryOrder->get_order_item_totals();
            if(isset($totals["cart_subtotal"]))
                return $totals["cart_subtotal"]['value'];
            else
                return wc_price(0, array( 'currency' => $this->GetValueFromObject($this->primaryOrder,'currency') ) );
        }

        if($totalType=='shipping')
        {
            $totals=$this->primaryOrder->get_order_item_totals();
            if(isset($totals["shipping"]))
                return $totals["shipping"]['value'];
            else
                return wc_price(0, array( 'currency' => $this->GetValueFromObject($this->primaryOrder,'currency') ) );
        }

        if($totalType=='discount')
        {
            $totals=$this->primaryOrder->get_order_item_totals();
            if(isset($totals["discount"]))
                return $totals["discount"]['value'];
            else
                return wc_price(0, array( 'currency' => $this->GetValueFromObject($this->primaryOrder,'currency') ) );
        }*/
    }

}