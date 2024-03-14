<?php
namespace rnwcinv\htmlgenerator\fields\subfields\BillingSubFields;
use rnwcinv\htmlgenerator\fields\subfields\PDFSubFieldBase;
use rnwcinv\pr\utilities\AddressFormatter;

class PDFStoreAddress extends PDFSubFieldBase {
    public function FormatValue($value,$format='')
    {
        $formatToUse=$this->GetStringValue('customFormat');

        get_option( 'woocommerce_store_postcode' );

        $formatToUse=str_replace('{SiteName}',esc_html(get_bloginfo('name')),$formatToUse);
        $formatToUse=str_replace('{SiteEmail}',esc_html(get_bloginfo('admin_email')),$formatToUse);
        $formatToUse=str_replace('{Address1}',esc_html(get_option( 'woocommerce_store_address' )),$formatToUse);
        $formatToUse=str_replace('{Address2}',esc_html(get_option( 'woocommerce_store_address_2' )),$formatToUse);
        $formatToUse=str_replace('{Zip}',esc_html(get_option( 'woocommerce_store_postcode' )),$formatToUse);
        $formatToUse=str_replace('{City}',esc_html(get_option( 'woocommerce_store_city' )),$formatToUse);

        $rawStateAndCountry=get_option( 'woocommerce_default_country' );
        $split_country = explode( ":", $rawStateAndCountry );

        $country='';
        $countryFullName='';
        $state='';
        $stateFullName='';
        if(count($split_country)>0)
        {
            $country=$split_country[0];
            $countryFullName=$country;
            if(function_exists('WC')&&isset(WC()->countries->countries[$country])){
                $countryFullName= WC()->countries->countries[$country];
            }
        }

        if(count($split_country)>1)
        {
            $state=$split_country[1];
            $stateFullName=$state;
            $states=WC()->countries->get_states($country);

            if($states!=false)
            {
                if(isset($states[$state]))
                    $stateFullName=$states[$state];
            }
        }

        $formatToUse=str_replace('{State}',esc_html($state),$formatToUse);
        $formatToUse=str_replace('{Country}',esc_html($country),$formatToUse);
        $formatToUse=str_replace('{CountryFullName}',esc_html($countryFullName),$formatToUse);
        $formatToUse=str_replace('{StateFullName}',esc_html($stateFullName),$formatToUse);
        $lines=explode("\n",$formatToUse);

        $addressToReturn='';
        foreach($lines as $currentLine)
        {
            $newLine=$this->ParseLine($currentLine);
            if($newLine=='')
                continue;
            $addressToReturn.='<p>'.$newLine.'</p>';
        }
        return $addressToReturn;



    }

    private function ParseLine($currentLine)
    {
        $currentLine=preg_replace('/^([\s,]*)/','',$currentLine);
        $currentLine=preg_replace('/([\s,]*)$/','',$currentLine);
        $currentLine=preg_replace('/,\s*,/',',',$currentLine);
        return $currentLine;
    }

    public function GenerateFieldValueContainer($containerStyle, $valueStyle)
    {
        return  '<td class="fieldValueContainer fieldValue" style="'.$containerStyle.'">'.$this->GetInternalValueText().'</td>';
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