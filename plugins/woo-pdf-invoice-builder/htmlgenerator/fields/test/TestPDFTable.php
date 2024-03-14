<?php
namespace  rnwcinv\htmlgenerator\fields\test;
use rnwcinv\htmlgenerator\fields\PDFTable;

class TestPDFTable extends PDFTable
{

    protected function GetItems()
    {
        $items=array();
        if(isset($this->orderValueRetriever->templateOptions->containerOptions->splitPDF)&&$this->orderValueRetriever->templateOptions->containerOptions->splitPDF)
        {
            $items= array(
                array(
                    'prod'=>'My Awesome Product',
                    'price'=>'$85.00',
                    'line_number'=>1,
                    'qty'=>2,
                    'sku'=>'MAP001',
                    'description'=>'Description Prod 1',
                    'discount'=>'$0.00',
                    'regular_price'=>'$85.00',
                    'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                    'short_description'=>'Desc Prod 1',
                    'vat'=>'$10.00',
                    'weight'=>'10',
                    'unit_price'=>'$42.50',
                    'total'=>'$95.00'
                )
            );
        }else
            $items= array(
            array(
                'prod'=>'My Awesome Product',
                'price'=>'$85.00',
                'line_number'=>1,
                'qty'=>2,
                'sku'=>'MAP001',
                'description'=>'Description Prod 1',
                'discount'=>'$0.00',
                'regular_price'=>'$85.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 1',
                'vat'=>'$10.00',
                'weight'=>'10kg',
                'unit_price'=>'$42.50',
                'total'=>'$95.00'
            ),
            array(
                'prod'=>'Another Awesome Product',
                'line_number'=>2,
                'price'=>'$150.00',
                'qty'=>1,
                'sku'=>'AAP_032',
                'description'=>'Description Prod 2',
                'discount'=>'-$5.00',
                'regular_price'=>'$155.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 2',
                'vat'=>'$20.00',
                'unit_price'=>'$150.00',
                'weight'=>'1kg',
                'total'=>'$165.0'
            ),
            array(
                'prod'=>'Ultimate Awesome Product',
                'line_number'=>3,
                'price'=>'$200.00',
                'qty'=>4,
                'sku'=>'UAP_EFJ',
                'description'=>'Description Prod 3',
                'discount'=>'-$5.00',
                'regular_price'=>'$205.00',
                'prod_thumbnail'=>\RednaoWooCommercePDFInvoice::$DIR.'images/ProductImage.png',
                'short_description'=>'Desc Prod 3',
                'vat'=>'$15.00',
                'unit_price'=>'$50.00',
                'weight'=>'1.5kg',
                'total'=>'$210.00'
            )
        );

        if($this->GetFeePosition()=='table')
        {
            $items[]=array(
                'prod'=>'Priority Fee',
                'price'=>'$10.00',
                'qty'=>1,
                'sku'=>'',
                'description'=>'',
                'discount'=>'0',
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
        if($totalType=='quantity')
        {
           return 7;
        }

        if($totalType=='weight')
        {
            return '12.5kg';
        }

        if($totalType=='shipping')
        {
            $excludeCarrier=$this->GetSubTotalProperty('Shipping','excludeCarrierName');
            $shipping='<p>$15.00</p>';
            if(!$excludeCarrier)
                $shipping.='<p>via DHL</p>';
            return $shipping;
        }

        if($totalType=='')
        {

        }

        if($totalType=='refund')
            if($showInNegativeNumber)
                return '-$10.00';
            else
                return '$10.00';

        if($totalType=='discount')
            if($showInNegativeNumber)
                return '-$10.00';
            else
                return '$10.00';

        if($totalType=='fee')
            return '$10.00';

        if($totalType=='subtotal')
            if($this->GetFeePosition()=='subtotal')
                return '$435.00';
            else
                return '$445.00';

        if($totalType=='total')
            return '$495.00';

        if($totalType=='discount')
            return '-10.00';

        throw new \Exception("invalid total");
    }

    protected function GetTaxes()
    {
        $includePercentage=$this->GetSubTotalProperty('Taxes','includePercentages');
        $label='Tax';
        if($includePercentage)
        {
            $label.=' 10%';
        }
        return array(array(
            'label'=>$label,
            'value'=>'$43.00'
        ));
    }

    protected function GetFees()
    {
        return array(array(
            'label'=>'Priority Fee',
            'value'=>'$10.00'
        ));
    }


    protected function GetTaxesRows()
    {
        return '<tr class="taxes">'.
                    '<th class="subTotalLabel">Tax 10%</th>'.
                    '<td class="subTotalValue">$45.00</td>'.
               '</tr>';
    }

    protected function GetFeeRows()
    {
        return '<tr class="taxes">'.
            '<th class="subTotalLabel">Priority Fee</th>'.
            '<td class="subTotalValue">$10.00</td>'.
            '</tr>';
    }
}