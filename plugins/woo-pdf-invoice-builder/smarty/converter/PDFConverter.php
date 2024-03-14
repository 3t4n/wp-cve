<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/9/2017
 * Time: 11:13 AM
 */

class PDFConverter
{


    public static function GetConverterByType($type,$options,$useTestData=false,$order=null,$fieldOptions=null,$translator=null){
        require_once 'PDFConverterBase.php';
        if($type=='inv_date')
        {
            require_once 'PDFDateConverter.php';
            return new PDFDateConverter($options,$useTestData,$order,$translator);
        }

        if($type=='order_date')
        {
            require_once 'PDFOrderDateConverter.php';
            return new PDFOrderDateConverter($options,$useTestData,$order,$translator);
        }


        if($type=='field_label')
        {
            require_once 'PDFFieldLabelConverter.php';
            $field=null;
            foreach($fieldOptions as $option)
            {
                if($option['fieldID']==$options['fieldID'])
                {
                    $field = $option;
                    break;
                }
            }
            return new PDFFieldLabelConverter($options,$useTestData,$order,$field,$translator);
        }

        if($type=='text')
        {
            require_once 'PDFTextConverter.php';
            $field=null;
            foreach($fieldOptions as $option)
            {
                if($option['fieldID']==$options['fieldID'])
                {
                    $field = $option;
                    break;
                }
            }
            return new PDFTextConverter($options,$useTestData,$order,$field,$translator);
        }

        if($type=='inv_number')
        {
            require_once 'PDFInvoiceNumberConverter.php';
            return new PDFInvoiceNumberConverter($options,$useTestData,$order,$translator);
        }

        if($type=='coupon_code')
        {
            require_once 'PDFCouponCodeConverter.php';
            return new PDFCouponCodeConverter($options,$useTestData,$order,$translator);
        }

        if($type=='payment_method')
        {
            require_once 'PDFPaymentMethodConverter.php';
            return new PDFPaymentMethodConverter($options,$useTestData,$order,$translator);
        }


        if($type=='billing_all'){
            require_once 'BillingConverters/PDFBillingAll.php';
            return new PDFBillingAll($options,$useTestData,$order,$translator);
        }
        if($type=='billing_address'){
            require_once 'BillingConverters/PDFBillingAddressConverter.php';
            return new PDFBillingAddressConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_company'){
            require_once 'BillingConverters/PDFBillingCompanyConverter.php';
            return new PDFBillingCompanyConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_customer_name'){
            require_once 'BillingConverters/PDFBillingCustomerNameConverter.php';
            return new PDFBillingCustomerNameConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_email'){
            require_once 'BillingConverters/PDFBillingEmailConverter.php';
            return new PDFBillingEmailConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_phone'){
            require_once 'BillingConverters/PDFBillingPhoneConverter.php';
            return new PDFBillingPhoneConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_country'){
            require_once 'BillingConverters/PDFBillingCountryConverter.php';
            return new PDFBillingCountryConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_state'){
            require_once 'BillingConverters/PDFBillingStateConverter.php';
            return new PDFBillingStateConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_city'){
            require_once 'BillingConverters/PDFBillingCityConverter.php';
            return new PDFBillingCityConverter($options,$useTestData,$order,$translator);
        }
        if($type=='billing_zip'){
            require_once 'BillingConverters/PDFBillingZipConverter.php';
            return new PDFBillingZipConverter($options,$useTestData,$order,$translator);
        }



        if($type=='shipping_all'){
            require_once 'ShippingConverters/PDFShippingAll.php';
            return new PDFShippingAll($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_address'){
            require_once 'ShippingConverters/PDFShippingAddressConverter.php';
            return new PDFShippingAddressConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_company'){
            require_once 'ShippingConverters/PDFShippingCompanyConverter.php';
            return new PDFShippingCompanyConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_customer_name'){
            require_once 'ShippingConverters/PDFShippingCustomerNameConverter.php';
            return new PDFShippingCustomerNameConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_email'){
            require_once 'ShippingConverters/PDFShippingEmailConverter.php';
            return new PDFShippingEmailConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_phone'){
            require_once 'ShippingConverters/PDFShippingPhoneConverter.php';
            return new PDFShippingPhoneConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_country'){
            require_once 'ShippingConverters/PDFShippingCountryConverter.php';
            return new PDFShippingCountryConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_state'){
            require_once 'ShippingConverters/PDFShippingStateConverter.php';
            return new PDFShippingStateConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_city'){
            require_once 'ShippingConverters/PDFShippingCityConverter.php';
            return new PDFShippingCityConverter($options,$useTestData,$order,$translator);
        }
        if($type=='shipping_zip'){
            require_once 'ShippingConverters/PDFShippingZipConverter.php';
            return new PDFShippingZipConverter($options,$useTestData,$order,$translator);
        }

        if($type=='customer_notes'){
            require_once 'PDFCustomerNotesConverter.php';
            return new PDFCustomerNotesConverter($options,$useTestData,$order,$translator);
        }

        if($type=='order_number')
        {
            require_once 'PDFOrderNumber.php';
            return new PDFOrderNumber($options,$useTestData,$order,$translator);
        }


        if($type=='grand_total')
        {
            require_once 'PDFGrandTotalConverter.php';
            return new PDFGrandTotalConverter($options,$useTestData,$order,$translator);
        }

        if($type=='sub_total')
        {
            require_once 'PDFSubTotalConverter.php';
            return new PDFSubTotalConverter($options,$useTestData,$order,$translator);
        }

        if($type=='tax_total')
        {
            require_once 'PDFTaxTotalConverter.php';
            return new PDFTaxTotalConverter($options,$useTestData,$order,$translator);
        }

        if($type=='shipping_total')
        {
            require_once 'PDFShippingTotalConverter.php';
            return new PDFShippingTotalConverter($options,$useTestData,$order,$translator);
        }

        if($type=='fee_total')
        {
            require_once 'PDFFeeTotalConverter.php';
            return new PDFFeeTotalConverter($options,$useTestData,$order,$translator);
        }
        if($type=='discount_total')
        {
            require_once 'PDFDiscountTotalConverter.php';
            return new PDFDiscountTotalConverter($options,$useTestData,$order,$translator);
        }

        return null;
    }


}