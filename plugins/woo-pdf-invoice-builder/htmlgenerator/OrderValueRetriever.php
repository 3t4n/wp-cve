<?php


/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/23/2018
 * Time: 7:45 AM
 */

namespace rnwcinv\htmlgenerator;
use RednaoPDFGenerator;

use rnwcinv\pr\Translation\PDFTranslationBase;
use rnwcinv\pr\Translation\WPMLTranslator;
use WC_Order;

class OrderValueRetriever
{
    protected  $compatibility_props = array(
        'date_completed' => 'completed_date',
        'date_paid'      => 'paid_date',
        'date_modified'  => 'modified_date',
        'date_created'   => 'order_date',
        'customer_id'    => 'customer_user',
        'discount'       => 'cart_discount',
        'discount_tax'   => 'cart_discount_tax',
        'shipping_total' => 'total_shipping',
        'type'           => 'order_type',
        'currency'       => 'order_currency',
        'version'        => 'order_version',
    );
    public $useTestData;
    /** @var WC_Order */
    public $order;
    /** @var PDFTranslationBase */
    public $translator;
    public $templateOptions;

    /** @var RednaoPDFGenerator */
    private $pdfGenerator;


    public function GetId(){
        if($this->order==null)
            return 0;
        if ( method_exists( $this->order, 'get_id' ) ) {

            return $this->order->get_id();

        } else {

            $idName='id';
            return isset($this->primaryOrder->$idName) ? $this->order->$idName : false;
        }
    }

    public function GetFormattedInvoiceNumber()
    {
        if($this->useTestData)
            return 1;
        if($this->pdfGenerator==null)
            return '';

        return $this->pdfGenerator->GetFormattedInvoiceNumber();

    }

    public function GetInvoiceDate(){
        if($this->useTestData||$this->pdfGenerator==null)
            return current_time('timestamp');

        return $this->pdfGenerator->GetInvoiceDate();

    }

    /**
     * OrderValueRetriever constructor.
     * @param $pdfGenerator RednaoPDFGenerator
     * @param $templateOptions
     * @param $useTestData
     * @param $order
     * @param $translator
     */
    public function __construct($pdfGenerator, $templateOptions,$useTestData,$order,$translator)
    {
        $this->pdfGenerator=$pdfGenerator;
        $this->templateOptions=$templateOptions;
        $this->useTestData = $useTestData;
        $this->order = $order;
        $this->translator = $translator;
    }

    public  function TranslateText($fieldId,$propertyName,$default)
    {
        if($this->translator==null)
            return $default;

        $translation= $this->translator->GetTranslatedText($fieldId,$propertyName);
        if($translation!='')
            return $translation;

        return $default;
    }

    public function TranslateProductName($productName,$product)
    {
        if($this->translator==null)
            return $productName;

        return $this->translator->TranslateProductName($productName,$product);

    }

    public function GetTotal($totalType,$omitIfEmpty=false,$includeTax=false)
    {
        $totals=$this->order->get_order_item_totals($includeTax?'incl':'excl');
        if(isset($totals["$totalType"]))
        {
            return $totals["$totalType"]['value'];
        }
        else
        {
            if($omitIfEmpty)
                return '';
            return wc_price(0, \apply_filters('rnwcinv_format_price',array('currency' => $this->get('currency'))));
        }

    }

    public  function get($propertyName)
    {

        if($this->IsVersionGreathThan3_0())
        {
            if($propertyName=='order_date')
                return $this->order->get_date_created()->getOffsetTimestamp();
            if ( is_callable( array( $this->order, "get_{$propertyName}" ) ) ) {
                return $this->order->{"get_{$propertyName}"}( 'view' );
            }
        }else {
            if ( isset( $this->compatibility_props[ $propertyName ] ) ) {
                $propertyName= $this->compatibility_props[ $propertyName ];
            }

            $value='';
            if ( is_callable( array( $this->order, "get_{$propertyName}" ) )) {
                $value= $this->order->{"get_{$propertyName}"}();
            } else {
                if(is_callable(array($this->order,$propertyName)))
                {
                    $value = $this->order->$propertyName;
                }
                else
                    if(isset($order->$propertyName))
                    {
                        $value = $this->order->$propertyName;
                    }
            }

            if(in_array($propertyName,array( 'date_completed', 'date_paid', 'date_modified', 'date_created' ), true ))
            {
                if ( is_numeric( $value ) ) {
                    $value = new \WC_DateTime( "@{$value}", new \DateTimeZone( 'UTC' ) );
                    $value->setTimezone( new \DateTimeZone( wc_timezone_string() ) );
                } else {
                    if ( 1 === preg_match( '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(Z|((-|\+)\d{2}:\d{2}))$/', $value, $date_bits ) ) {
                        $offset    = ! empty( $date_bits[7] ) ? iso8601_timezone_to_offset( $date_bits[7] ) : wc_timezone_offset();
                        $timestamp = gmmktime( $date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1] ) - $offset;
                    } else {
                        $timestamp = wc_string_to_timestamp( get_gmt_from_date( gmdate( 'Y-m-d H:i:s', wc_string_to_timestamp( $value ) ) ) );
                    }
                    $value = new \WC_DateTime( "@{$timestamp}", new \DateTimeZone( 'UTC' ) );
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

    public  function IsVersionGreathThan3_0()
    {
        $version=$this->GetWCVersion();
        return $version && version_compare( $version, '3.0', '>=' );
    }

    private  function GetWCVersion(){
        return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
    }

    public  function IsVersionLessThan3_0()
    {
        $version=$this->GetWCVersion();
        return $version && version_compare( $version, '3.0', '<' );
    }




}