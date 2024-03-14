<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;
use rnwcinv\pr\utilities\AddressFormatter;

class PDFBillingAll extends PDFSubFieldBase {
    public function FormatValue($value,$format='')
    {
        $content='';

        if($this->GetStringValue('formatType')=='custom')
        {
            if(!class_exists('rnwcinv\pr\utilities\AddressFormatter'))
            {
                return 'Custom address format is only available in the full version';
            }

            $formatter=new AddressFormatter();
            if($this->orderValueRetriever->useTestData)
            {
                $formatter->SetAddress1('Address 1');
                $formatter->SetAddress2('Address 2');
                $formatter->SetCustomerName('Customer Name');
                $formatter->SetEmailAddress('Customer@email.com');
                $formatter->SetPhone('(555)555-555');
                $formatter->SetCompanyName('Awesome Company');
                $formatter->SetCountry('Country');
                $formatter->SetCity('City');
                $formatter->SetState('State');
                $formatter->SetZip('Zip');
            }else{
                $formatter->SetAddress1($this->orderValueRetriever->order->get_billing_address_1());
                $formatter->SetAddress2($this->orderValueRetriever->order->get_billing_address_2());
                $formatter->SetCustomerName($this->orderValueRetriever->get('billing_first_name').' '. $this->orderValueRetriever->get('billing_last_name'));
                $formatter->SetEmailAddress($this->orderValueRetriever->order->get_billing_email());
                $formatter->SetPhone($this->orderValueRetriever->order->get_billing_phone());
                $formatter->SetCompanyName($this->orderValueRetriever->order->get_billing_company());
                $formatter->SetCountry($this->orderValueRetriever->order->get_billing_country());
                $formatter->SetCity($this->orderValueRetriever->order->get_billing_city());
                $formatter->SetState($this->orderValueRetriever->order->get_billing_state());
                $formatter->SetZip($this->orderValueRetriever->order->get_billing_postcode());
            }
            return $formatter->Format($this->GetStringValue('customFormat'));

        }

        if($this->GetBooleanValue('showCustomerName',true))
            $this->ProcessField('CustomerName',$content,$format);

        if($this->GetBooleanValue('showCompanyName',true))
            $this->ProcessField('Company',$content,$format);

        if($this->GetBooleanValue('showEmail',true))
            $this->ProcessField('Email',$content,$format);

        if($this->GetBooleanValue('showPhone',true))
            $this->ProcessField('Phone',$content,$format);


        if($this->GetBooleanValue('showAddress',true))
        {
            $this->ProcessField('Address1', $content,$format);
            $this->ProcessField('Address2', $content,$format);
        }

        $this->CreateCityStateZipRow($content,$format);

        if($this->GetBooleanValue('showCountry',true))
            $this->ProcessField('Country',$content,$format);


        return $content;
    }

    public function GenerateFieldValueContainer($containerStyle, $valueStyle)
    {
        return  '<td class="fieldValueContainer fieldValue" style="'.$containerStyle.'">'.$this->GetInternalValueText().'</td>';
    }


    public function IsEmpty()
    {
        if($this->orderValueRetriever->useTestData)
            return false;

        return !$this->orderValueRetriever->order->has_billing_address();
    }

    private function ProcessField($type, &$table,$format='')
    {
        $value='';
        if($this->orderValueRetriever->useTestData)
            $value=$this->GetTestValue($type);
        else
            $value=$this->GetValue($type);
        if($format=='plain')
        {
            if(trim($value)!='')
            {
                if($table!='')
                    $table.=', ';
            }

            $table.=$value;
        }else
            $table.=$this->CreateRow($type,$value);
    }

    private function CreateRow($type,$rowValue)
    {
        $textAlign='';
        if($this->GetStringValue('labelPosition')=='left')
            $textAlign='text-align:right;';
        $rowValue=str_replace('<br/>','',$rowValue);
        if($type=='Address1'||$type=='Address2')
        {
            $type.=' field_Address';
        }
        return '<p class="field_'.$type.'" style="margin:0;padding:0;'.$textAlign.'">'.htmlspecialchars($rowValue).'</p>';
    }

    private function GetValue($type)
    {
        switch ($type)
        {
            case 'CustomerName':
                return $this->orderValueRetriever->get('billing_first_name').' '. $this->orderValueRetriever->get('billing_last_name');
            case 'Email':
                return $this->orderValueRetriever->get('billing_email');
            case 'Phone':
                return $this->orderValueRetriever->get('billing_phone');
            case 'Company':
                return $this->orderValueRetriever->get('billing_company');
            case 'Address':
                $address=$this->orderValueRetriever->get('billing_address_1');
                $address2=$this->orderValueRetriever->get('billing_address_2');

                if(strlen(trim($address2))>0)
                    $address.="\n ".$address2;
                return $address;

            case 'Address1':
                return $this->orderValueRetriever->get('billing_address_1');
            case 'Address2':
                return $this->orderValueRetriever->get('billing_address_2');
            case 'Country':
                $country= $this->orderValueRetriever->get('billing_country');
                if($this->GetBooleanValue('showFullName',false)&&isset(WC()->countries->countries[$country]))
                    $country=WC()->countries->countries[$country];

                return $country;

            case 'City':
                return $this->orderValueRetriever->get('billing_city');
            case 'State':
                $country= $this->orderValueRetriever->get('billing_country');
                $state= $this->orderValueRetriever->get('billing_state');
                if($this->GetBooleanValue('showStateFullName',false)&&isset(WC()->countries->get_states($country)[$state]))
                    $state=WC()->countries->get_states($country)[$state];

                return \html_entity_decode($state);
            case 'Zip':
                return $this->orderValueRetriever->get('billing_postcode');

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

    private function CreateCityStateZipRow(&$table,$format)
    {
        $city='';
        $state='';
        $zip='';
        if($this->orderValueRetriever->useTestData)
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
            if (strlen($value) > 0&&strlen($state)>0)
                $value.=", ";
            $value .= $state;
        }

        if($this->GetBooleanValue('showZip'))
        {
            if (strlen($value) > 0&&strlen($zip)>0)
                $value.=", ";
            $value .= $zip;
        }

        if($format=='plain')
        {
            if(trim($value)!='')
            {
                if($table!='')
                    $table.=', ';
            }

            $table.=$value;
        }else
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