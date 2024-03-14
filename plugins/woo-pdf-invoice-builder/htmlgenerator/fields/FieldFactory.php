<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/23/2018
 * Time: 6:59 AM
 */

namespace rnwcinv\htmlgenerator\fields;


use Exception;
use rnwcinv\htmlgenerator\FieldDTO;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingAddress;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingAll;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingCity;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingCompany;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingCountry;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingCustomerName;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingEmail;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingPhone;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingState;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFBillingZip;
use rnwcinv\htmlgenerator\fields\subfields\BillingSubFields\PDFStoreAddress;
use rnwcinv\htmlgenerator\fields\subfields\Customers\PDFCustomerCreationDateField;
use rnwcinv\htmlgenerator\fields\subfields\Customers\PDFCustomerEmailField;
use rnwcinv\htmlgenerator\fields\subfields\Customers\PDFCustomerIDField;
use rnwcinv\htmlgenerator\fields\subfields\Customers\PDFCustomerNameField;
use rnwcinv\htmlgenerator\fields\subfields\Customers\PDFCustomerUsernameField;
use rnwcinv\htmlgenerator\fields\subfields\PDFCouponCodeSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFCustomerNotesSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFDateSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFDiscountTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFDueDate;
use rnwcinv\htmlgenerator\fields\subfields\PDFFeeTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFGrandTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFInvDate;
use rnwcinv\htmlgenerator\fields\subfields\PDFInvoiceNumberSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFOrderNotesField;
use rnwcinv\htmlgenerator\fields\subfields\PDFOrderNumberSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFOrderWeightSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFPaymentMethodSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFShippingMethod;
use rnwcinv\htmlgenerator\fields\subfields\PDFShippingTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFStatusSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\PDFTaxTotalSubField;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingAddress;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingAll;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingCity;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingCompany;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingCountry;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingCustomerName;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingEmail;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingPhone;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingState;
use rnwcinv\htmlgenerator\fields\subfields\ShippingSubFields\PDFShippingZip;
use rnwcinv\htmlgenerator\fields\test\TestPDFRefundTable;
use rnwcinv\htmlgenerator\fields\test\TestPDFTable;
use rnwcinv\htmlgenerator\OrderValueRetriever;
use rnwcinv\pr\fields\PDFCustomField;
use rnwcinv\pr\fields\PDFLink;
use rnwcinv\pr\fields\PDFQRCode;


class FieldFactory
{
    /**
     * @param $field FieldDTO
     * @param $orderValueRetriever OrderValueRetriever
     * @return
     * @throws Exception
     */
    public static function GetField($field,$orderValueRetriever)
    {

        $type = $field->type;
        switch ($type)
        {
            case 'image':
                return new PDFImage($field,$orderValueRetriever);
            case 'link':
                return new PDFLink($field,$orderValueRetriever);
            case 'qrcode':
                return new PDFQRCode($field,$orderValueRetriever);
            case 'table':
                if(!$orderValueRetriever->useTestData)
                    return new PDFTable($field,$orderValueRetriever);
                else
                    return new TestPDFTable($field,$orderValueRetriever);
            case 'custom':
                return new PDFCustomField($field,$orderValueRetriever);
            case 'field':
                return FieldFactory::GetSubField($field,$orderValueRetriever);
            case 'text':
                return new PDFText($field,$orderValueRetriever);
            case 'icon':
                return new PDFIcon($field,$orderValueRetriever);
            case 'separator':
                return new PDFSeparator($field,$orderValueRetriever);
            case "figure":
                return new PDFFigure($field,$orderValueRetriever);
            case 'refundtable':
                if(!$orderValueRetriever->useTestData)
                    return new PDFRefundTable($field,$orderValueRetriever);
                else
                    return new TestPDFRefundTable($field,$orderValueRetriever);

        }
        throw new Exception('Invalid field '.$type);

    }

    /**
     * @param $field FieldDTO|string
     * @param $orderValueRetriever
     */
    public static function GetSubField($field,$orderValueRetriever)
    {
        $fieldType='';
        $fieldData=null;
        if(\is_string($field))
        {
            $fieldType=$field;
        }else{
            $fieldType=$field->fieldOptions->fieldType;
            $fieldData=$field;
        }

        switch($fieldType)
        {
            case "due_date":
                return new PDFDueDate($fieldData,$orderValueRetriever);
            case "inv_number":
                return new PDFInvoiceNumberSubField($fieldData,$orderValueRetriever);
            case "order_date":
                return new PDFDateSubField($fieldData,$orderValueRetriever);
            case "order_number":
                return new PDFOrderNumberSubField($fieldData,$orderValueRetriever);
            case "shipping_method":
                return new PDFShippingMethod($fieldData,$orderValueRetriever);
            case "inv_date":
                return new PDFInvDate($fieldData,$orderValueRetriever);
            case "coupon_code":
                return new PDFCouponCodeSubField($fieldData,$orderValueRetriever);
            case "order_weight":
                return new PDFOrderWeightSubField($fieldData,$orderValueRetriever);
            case "payment_method":
                return new PDFPaymentMethodSubField($fieldData,$orderValueRetriever);
            case "billing_all":
                return new PDFBillingAll($fieldData,$orderValueRetriever);
            case "billing_address":
                return new PDFBillingAddress($fieldData,$orderValueRetriever);
            case "billing_company":
                return new PDFBillingCompany($fieldData,$orderValueRetriever);
            case "billing_customer_name":
                return new PDFBillingCustomerName($fieldData,$orderValueRetriever);
            case "billing_email":
                return new PDFBillingEmail($fieldData,$orderValueRetriever);
            case "billing_phone":
                return new PDFBillingPhone($fieldData,$orderValueRetriever);
            case "billing_country":
                return new PDFBillingCountry($fieldData,$orderValueRetriever);
            case "billing_state":
                return new PDFBillingState($fieldData,$orderValueRetriever);
            case "billing_city":
                return new PDFBillingCity($fieldData,$orderValueRetriever);
            case "billing_zip":
                return new PDFBillingZip($fieldData,$orderValueRetriever);
            case "shipping_all":
                return new PDFShippingAll($fieldData,$orderValueRetriever);
            case "shipping_address":
                return new PDFShippingAddress($fieldData,$orderValueRetriever);
            case "shipping_company":
                return new PDFShippingCompany($fieldData,$orderValueRetriever);
            case "shipping_customer_name":
                return new PDFShippingCustomerName($fieldData,$orderValueRetriever);
            case "shipping_email":
                return new PDFShippingEmail($fieldData,$orderValueRetriever);
            case "shipping_phone":
                return new PDFShippingPhone($fieldData,$orderValueRetriever);
            case "shipping_country":
                return new PDFShippingCountry($fieldData,$orderValueRetriever);
            case "shipping_state":
                return new PDFShippingState($fieldData,$orderValueRetriever);
            case "shipping_city":
                return new PDFShippingCity($fieldData,$orderValueRetriever);
            case "shipping_zip":
                return new PDFShippingZip($fieldData,$orderValueRetriever);
            case "customer_notes":
                return new PDFCustomerNotesSubField($fieldData,$orderValueRetriever);
            case 'order_notes':
                return new PDFOrderNotesField($fieldData,$orderValueRetriever);
            case "status":
                return new PDFStatusSubField($fieldData,$orderValueRetriever);
            case "grand_total":
                return new PDFGrandTotalSubField($fieldData,$orderValueRetriever);
            case "sub_total":
                return new PDFSubTotalSubField($fieldData,$orderValueRetriever);
            case "tax_total":
                return new PDFTaxTotalSubField($fieldData,$orderValueRetriever);
            case "shipping_total":
                return new PDFShippingTotalSubField($fieldData,$orderValueRetriever);
            case "fee_total":
                return new PDFFeeTotalSubField($fieldData,$orderValueRetriever);
            case "discount_total":
                return new PDFDiscountTotalSubField($fieldData,$orderValueRetriever);
            case 'customer_id':
                return new PDFCustomerIDField($fieldData,$orderValueRetriever);
            case 'customer_name':
                return new PDFCustomerNameField($fieldData,$orderValueRetriever);
            case 'customer_email':
                return new PDFCustomerEmailField($fieldData,$orderValueRetriever);
            case 'customer_username':
                return new PDFCustomerUsernameField($fieldData,$orderValueRetriever);
            case 'customer_creation_date':
                return new PDFCustomerCreationDateField($fieldData,$orderValueRetriever);
            case 'store_address':
                return new PDFStoreAddress($fieldData,$orderValueRetriever);
        }

        throw new Exception('Invalid field '.$field->fieldOptions->fieldType);

    }
}