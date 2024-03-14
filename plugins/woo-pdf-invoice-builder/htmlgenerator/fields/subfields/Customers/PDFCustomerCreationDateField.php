<?php


namespace rnwcinv\htmlgenerator\fields\subfields\Customers;


use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;

class PDFCustomerCreationDateField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return strtotime('today');
    }

    public function GetWCFieldName()
    {
        return "";
    }

    public function FormatValue($value,$format='')
    {
        $format=$this->GetFieldOptions('format');
        $formattedDate=date_i18n($format,$value);
        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }


    public function GetRealFieldValue($format='')
    {
        $customerId= $this->orderValueRetriever->order->get_customer_id();
        $user=\get_userdata($customerId);
        if($user==false)
            return 0;
        return strtotime($user->user_registered);

    }


}