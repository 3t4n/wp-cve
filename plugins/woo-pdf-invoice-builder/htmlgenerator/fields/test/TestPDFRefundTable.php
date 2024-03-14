<?php
namespace  rnwcinv\htmlgenerator\fields\test;
use rnwcinv\htmlgenerator\fields\PDFTable;

class TestPDFRefundTable extends PDFTable
{

    protected function GetItems()
    {
        $showNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        $items=array();
        if(isset($this->orderValueRetriever->templateOptions->containerOptions->splitPDF)&&$this->orderValueRetriever->templateOptions->containerOptions->splitPDF)
        {
            $items= array(
                array(
                    'prod'=>'Refunded Product 1',
                    'price'=>$showNegativeNumber?'-$85.00':'$85.00',
                    'line_number'=>1,
                    'qty'=>$showNegativeNumber?-2:2,
                    'sku'=>'MAP001',
                    'description'=>'Description Prod 1',
                    'discount'=>$showNegativeNumber?'$0.00':'-$0.00',
                    'regular_price'=>$showNegativeNumber?'-$85.00':'$85.00',
                    'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                    'short_description'=>'Desc Prod 1',
                    'vat'=>$showNegativeNumber?'-$10.00':'$10.00',
                    'weight'=>'10',
                    'unit_price'=>$showNegativeNumber?'-$42.50':'$42.50',
                    'total'=>$showNegativeNumber?'-$95.00':'$95.00'
                )
            );
        }else
            $items= array(
            array(
                'prod'=>'Another Refunded Product',
                'price'=>$showNegativeNumber?'-$85.00':'$85.00',
                'line_number'=>1,
                'qty'=>$showNegativeNumber?-2:2,
                'sku'=>'MAP001',
                'description'=>'Description Prod 1',
                'discount'=>$showNegativeNumber?'$0.00':'-$0.00',
                'regular_price'=>'$85.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 1',
                'vat'=>$showNegativeNumber?'-$10.00':'$10.00',
                'weight'=>'10kg',
                'unit_price'=>$showNegativeNumber?'-$42.50':'$42.50',
                'total'=>$showNegativeNumber?'$95.00':'-$95.00'
            ),
            array(
                'prod'=>'Last Refunded Product',
                'line_number'=>2,
                'price'=>$showNegativeNumber?'-$150.00':'$150.00',
                'qty'=>$showNegativeNumber?-1:1,
                'sku'=>'AAP_032',
                'description'=>'Description Prod 2',
                'discount'=>$showNegativeNumber?'$5.00':'-$5.00',
                'regular_price'=>'$155.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 2',
                'vat'=>$showNegativeNumber?'-$20.00':'$20.00',
                'unit_price'=>$showNegativeNumber?'-$150.00':'$150.00',
                'weight'=>'1kg',
                'total'=>$showNegativeNumber?'-$165.00':'$165.00'
            ),
            array(
                'prod'=>'Ultimate Awesome Product',
                'line_number'=>3,
                'price'=>$showNegativeNumber?'-$200.00':'$200.00',
                'qty'=>$showNegativeNumber?-4:4,
                'sku'=>'UAP_EFJ',
                'description'=>'Description Prod 3',
                'discount'=>$showNegativeNumber?'$5.00':'-$5.00',
                'regular_price'=>$showNegativeNumber?'-$205.00':'$205.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 3',
                'vat'=>$showNegativeNumber?'-$15.00':'$15.00',
                'unit_price'=>$showNegativeNumber?'-$50.00':'$50.00',
                'weight'=>'1.5kg',
                'total'=>$showNegativeNumber?'-$210.00':'$210.00'
            )
        );

        if($this->GetFeePosition()=='table')
        {
            $items[]=array(
                'prod'=>'Priority Fee',
                'price'=>'-$10.00',
                'qty'=>-1,
                'sku'=>'',
                'description'=>'',
                'discount'=>0,
                'regular_price'=>'0',
                'prod_thumbnail'=>'',
                'short_description'=>'',
                'vat'=>'0',
                'weight'=>'0'
            );
        }

        return $items;
    }


    protected function GetTotalValue($totalType,$omitIfEmpty,$showInNegativeNumber)
    {
        $tableShowNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        if($totalType=='quantity')
        {
           return $tableShowNegativeNumber?-7:7;
        }

        if($totalType=='weight')
        {
            return '12.5kg';
        }

        if($totalType=='shipping')
        {
            $excludeCarrier=$this->GetSubTotalProperty('Shipping','excludeCarrierName');
            $shipping=$tableShowNegativeNumber?'<p>-$15.00</p>':'<p>$15.00</p>';
            if(!$excludeCarrier)
                $shipping.='<p>via DHL</p>';
            return $shipping;
        }

        if($totalType=='')
        {

        }

        if($totalType=='refund')
            if($showInNegativeNumber||$tableShowNegativeNumber)
                return '-$10.00';
            else
                return '$10.00';

        if($totalType=='discount')
            if($showInNegativeNumber||!$tableShowNegativeNumber)
                return '-$10.00';
            else
                return '$10.00';

        if($totalType=='fee')
            return '$10.00';

        if($totalType=='subtotal')
            if($this->GetFeePosition()=='subtotal')
                return $tableShowNegativeNumber?'-$435.00':'$435.00';
            else
                return $tableShowNegativeNumber?'-$445.00':'$445.00';

        if($totalType=='total')
            return $tableShowNegativeNumber?'-$495.00':'$495.00';

        if($totalType=='discount')
            return $tableShowNegativeNumber?'10.00':'-10.00';

        throw new \Exception("invalid total");
    }

    protected function GetTaxes()
    {
        $tableShowNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        $includePercentage=$this->GetSubTotalProperty('Taxes','includePercentages');
        $label='Tax';
        if($includePercentage)
        {
            $label.=' 10%';
        }
        return array(array(
            'label'=>$label,
            'value'=>$tableShowNegativeNumber?'-$43.00':'$43.00'
        ));
    }

    protected function GetFees()
    {
        $tableShowNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        return array(array(
            'label'=>'Priority Fee',
            'value'=>$tableShowNegativeNumber?'-$10.00':'$10.00'
        ));
    }


    protected function GetTaxesRows()
    {
        $tableShowNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        return '<tr class="taxes">'.
                    '<th class="subTotalLabel">Tax 10%</th>'.
                    '<td class="subTotalValue">'.($tableShowNegativeNumber?'-$45.00':'$45.00').'</td>'.
               '</tr>';
    }

    protected function GetFeeRows()
    {
        $tableShowNegativeNumber=$this->GetPropertyValue('ShowNegativeNumbers');
        return '<tr class="taxes">'.
            '<th class="subTotalLabel">Priority Fee</th>'.
            '<td class="subTotalValue">'.($tableShowNegativeNumber?'-$10.00':'$10.00').'</td>'.
            '</tr>';
    }
}