<?php

namespace rnwcinv\htmlgenerator\fields\subfields;

class PDFDueDate extends PDFSubFieldBase {
   /* public function __toString()
    {
        $date=null;
        $date=$this->GetFieldValue();
        $format=$this->GetStringValue('format');
        $formattedDate=date($format,$date);
        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }*/
    public function FormatValue($value,$format='')
    {
        if(!is_numeric($value))
            return '';


        $unitType=$this->GetFieldOptions('unitType');
        $steps=$this->GetFieldOptions('value');
        if(!is_numeric($value))
            return '';

        $dateUnit='';
        switch ($unitType)
        {
            case 'day':
                $dateUnit='P'.abs($steps).'D';
                break;
            case 'week':
                $dateUnit='P'.abs($steps*7).'D';
                break;
            case 'month':
                $dateUnit='P'.abs($steps).'M';
                break;
            case 'year':
                $dateUnit='P'.abs($steps).'Y';
                break;
            default:
                return '';
        }

        $interval=new \DateInterval($dateUnit);
        if($steps<0)
            $interval->invert=1;

        $date=new \DateTime(date('c',$value));
        $date->add($interval);

        $format=$this->GetFieldOptions('format');
        $formattedDate=$date->format($format);

        if(!$formattedDate)
            return 'Invalid Format';
        return $formattedDate;
    }


    public function GetTestFieldValue()
    {
        return strtotime('today');
    }

    public function GetWCFieldName()
    {
        return "order_date";
    }

    public function GetRealFieldValue($format='')
    {
        if($this->orderValueRetriever->order->get_date_created()==null)
            return '';
        return (\DateTime::createFromFormat('F j, Y',$this->orderValueRetriever->order->get_date_created()->format('F j, Y')))->getTimestamp() ;
    }



}