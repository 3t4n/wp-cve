<?php
class PDFBillingAll extends PDFConverterBase {
    public function __toString()
    {
        $content='';


        if($this->GetBooleanValue('showCustomerName'))
            $this->ProcessField('CustomerName',$content);

        if($this->GetBooleanValue('showCompanyName'))
            $this->ProcessField('Company',$content);

        if($this->GetBooleanValue('showEmail'))
            $this->ProcessField('Email',$content);

        if($this->GetBooleanValue('showPhone'))
            $this->ProcessField('Phone',$content);


        if($this->GetBooleanValue('showAddress'))
            $this->ProcessField('Address',$content);

        $this->CreateCityStateZipRow($content);

        if($this->GetBooleanValue('showCountry'))
            $this->ProcessField('Country',$content);


        return $content;
    }


    private function ProcessField($type, &$table)
    {
        $value='';
        if($this->useTestData)
            $value=$this->GetTestValue($type);
        else
            $value=$this->GetValue($type);
        $table.=$this->CreateRow($type,$value);
    }

    private function CreateRow($type,$rowValue)
    {
        $textAlign='';
        if($this->GetStringValue('labelPosition')=='left')
            $textAlign='text-align:right;';
        $rowValue=str_replace('<br/>','',$rowValue);
        return '<p class="field_'.$type.'" style="margin:0;padding:0;'.$textAlign.'">'.htmlspecialchars($rowValue).'</p>';;
    }

    private function GetValue($type)
    {
        switch ($type)
        {
            case 'CustomerName':
                return $this->order->get('billing_first_name').' '. $this->order->get('billing_last_name');
            case 'Email':
                return $this->order->get('billing_email');
            case 'Phone':
                return $this->order->get('billing_phone');
            case 'Company':
                return $this->order->get('billing_company');
            case 'Address':
                $address=$this->order->get('billing_address_1');
                $address2=$this->order->get('billing_address_2');

                if(strlen(trim($address2))>0)
                    $address.=" ".$address2;
                return $address;
            case 'Country':
                return $this->order->get('billing_country');
            case 'City':
                return $this->order->get('billing_city');
            case 'State':
                return $this->order->get('billing_state');
            case 'Zip':
                return $this->order->get('billing_postcode');

        }
        return '';
    }

    private function GetTestValue($type)
    {
        switch ($type)
        {
            case 'CustomerName':
                return 'Customer Name';
            case 'Email':
                return 'Customer@email.com';
            case 'Phone':
                return '(555)555-555';
            case 'Company':
                return 'Awesome Company';
            case 'Address':
                return 'Address goes here #323';
            case 'Country':
                return 'Country';
            case 'City':
                return 'City';
            case 'State':
                return 'State';
            case 'Zip':
                return 'Zip';

        }
        return '';
    }

    private function CreateCityStateZipRow(&$table)
    {
        $city='';
        $state='';
        $zip='';
        if($this->useTestData)
        {
            $city=$this->GetTestValue('City');
            $state=$this->GetTestValue('State');
            $zip=$this->GetTestValue('Zip');
        }else
        {
            $city=$this->GetValue('City');
            $state=$this->GetValue('State');
            $zip=$this->GetValue('Zip');
        }

        $value='';

        if($this->GetBooleanValue('showCity'))
            $value=$city;
        if($this->GetBooleanValue('showState'))
        {
            if (strlen($value) > 0)
                $value.=", ";
            $value .= $state;
        }

        if($this->GetBooleanValue('showZip'))
        {
            if (strlen($value) > 0)
                $value.=", ";
            $value .= $zip;
        }


        $table.=$this->CreateRow('CityStateZip',$value);

    }

    public function GetTestFieldValue()
    {
        // TODO: Implement GetTestFieldValue() method.
    }

    public function GetWCFieldName()
    {
        // TODO: Implement GetWCFieldName() method.
    }
}