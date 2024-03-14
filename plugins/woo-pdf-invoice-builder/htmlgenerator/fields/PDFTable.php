<?php
namespace  rnwcinv\htmlgenerator\fields;

use RednaoWooCommercePDFInvoice;
use rnwcinv\htmlgenerator\FieldDTO;
use rnwcinv\pr\CustomField\utilities\CustomFieldValueRetriever;
use rnwcinv\pr\CustomFieldV2\Wrappers\CRow;
use rnwcinv\pr\MultiplePagesPDFGenerator\SplittedOrder;
use rnwcinv\utilities\Sanitizer;


/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFTable extends PDFFieldBase
{

    protected $items=array();
    /** @var CRow[] */
    public $CustomRows;
    /** @var \WC_Order */
    public $OrderToUse;

    public $SubtotalLabelWidth=20;
    public $SubtotalValueWidth=20;
    public $RemainingWidth=60;
    public function __construct($options, $orderValueRetriever)
    {
        parent::__construct($options, $orderValueRetriever);
        if(isset($options->HideRowsCondition)&&count($options->HideRowsCondition)>0&&$orderValueRetriever!=null&&!$this->orderValueRetriever->useTestData)
        {
            $this->OrderToUse=new SplittedOrder($orderValueRetriever->order);
            $this->OrderToUse->SetCondition($options->HideRowsCondition);

        }else
            $this->OrderToUse=$orderValueRetriever->order;

        $this->CalculateSubTotalWidths();
    }


    private function CreateTotalsRows()
    {
        $totalsTable='';
        if($this->GetBoolValue('ShowTotalQuantity'))
        {
            $totalsTable.=$this->CreateTotalRow('quantity','TotalQuantityLabel');
        }

        if($this->GetBoolValue('ShowTotalWeight'))
        {
            $totalsTable.=$this->CreateTotalRow('weight','TotalWeightLabel');
        }


        if($this->GetBoolValue('ShowSubtotal'))
        {
            $totalsTable.=$this->CreateTotalRow('subtotal','SubTotalLabel');
        }

        if($this->GetBoolValue('ShowDiscount'))
        {
            $showWhenNoDiscount=$this->GetSubTotalProperty('Discount','ShowWhenNoDiscount')===true;;
            $showInNegativeNumber=$this->GetSubTotalProperty('Discount','showInNegativeNumber')===true;
            $totalsTable.=$this->CreateTotalRow('discount','DiscountLabel',!$showWhenNoDiscount,$showInNegativeNumber);
        }

        if($this->GetBoolValue('ShowRefund'))
        {
            $showInNegativeNumber=$this->GetSubTotalProperty('Refund','showInNegativeNumber')===true;
            $totalsTable.=$this->CreateTotalRow('refund','RefundLabel',true,$showInNegativeNumber);
        }

        if($this->GetBoolValue('ShowShipping'))
        {
            $shippingText=$this->CreateTotalRow('shipping','ShippingLabel',true);
            $shippingText=apply_filters('wcpdfi_set_shipping_text',$shippingText);
            $totalsTable.=$shippingText;
        }

        if($this->GetBoolValue('ShowFees')&&$this->GetFeePosition()=='subtotal')
        {
            $totalsTable.=$this->CreateTabularRow($this->GetFees(),'fees');
        }

        if($this->GetBoolValue('ShowTaxes'))
        {
            $totalsTable.=$this->CreateTabularRow($this->GetTaxes(),'taxes');
        }

        $totalsTable.=$this->CreateAdditionalTotals();

        if($this->GetBoolValue('ShowTotal'))
        {
            $totalsTable.=$this->CreateTotalRow('total','TotalLabel');
        }
        

        if(strlen($totalsTable)>0)
            $totalsTable='<table width="100%" style="page-break-inside: avoid" class="footerTable"><tbody>'.$totalsTable.'</tbody></table>';


        return $totalsTable;


    }

    public function ParseQuantity($amount){
        if(!is_numeric($amount))
            $amount=0;
        if($this->GetPropertyValue('ShowNegativeNumbers')==true)
            return $amount*-1;

        return $amount;
    }

    private function CreateDetailRows($columns)
    {
        $rows='<tbody>';
        $count=count($this->items);
        if(count($columns)==0)
            $rows.='<tr><td></td></tr>';
        else
            for($i=0;$i<$count;$i++)
            {
                $rows.='<tr class="invoiceDetailRow">';
                foreach ($columns as $column)
                {
                    $rows.=$this->CreateDetailColumn($column,$this->GetDetailValue($i,$column->type,$column));
                }
                $rows.='</tr>';
            }

        $rows.='</tbody>';
        return $rows;

    }




    protected function CreateDetailColumn($column,$value)
    {
        if($column->type=='prod_thumbnail')
        {

            $thumbnails=$this->GetColumn('prod_thumbnail');
            $width='75px';
            $height='75px';
            if($thumbnails!=null)
            {
                if(isset($thumbnails->additionalProperties))
                {
                    if(isset($thumbnails->additionalProperties->maxWidth))
                        $width=$thumbnails->additionalProperties->maxWidth;

                    if(isset($thumbnails->additionalProperties->maxHeight))
                        $height=$thumbnails->additionalProperties->maxHeight;
                }
            }
            return '<td class="'.esc_attr($column->type).'" style="text-align:center;width:'.esc_attr($column->width).'">'.
                    '<img style="max-width:'.$width.';max-height:'.$height.'" src="'.htmlspecialchars($value).'"/>'.
                '</td>';
        }




        $id=esc_attr($column->type);
        if($column->type=='custom')
        {
            $id='cust_'.$column->customProperties->id;
        }


        return '<td class="'.$id.'" style="width:'.esc_attr($column->width).'">'.
            $value.'</td>';

    }


    /**
     * @param $columns stdClass[]
     * @return string
     */
    private function CreateHeader($columns)
    {
        /** @var FieldDTO $options */
        $options=$this->options;
        $header='<thead><tr>';
        foreach($columns as $column)
        {
            $id=esc_attr($column->type);
            $columnId=$column->type;
            if($column->type=='custom')
            {
                $id='th_cust_'.$column->customProperties->id;
                $columnId='custom_'.$column->customProperties->id;
            }



            $header.='<th class="'.$id.'" style="width:'.$column->width.';">'.htmlspecialchars($this->orderValueRetriever->TranslateText($options->fieldID,$columnId,$column->header) ).'</th>';
        }
        $header.='</tr></thead>';
        return $header;
    }


    protected function CreateTabularRow($rows, $class){
        $table='';
        $useRTL=Sanitizer::GetValueFromPath($this->orderValueRetriever->templateOptions,['containerOptions','useRTL'],false);
        if(count($rows)>0)
        {
            $table='';
            foreach($rows as $row)
            {
                $remainingColumn='<td width="'.$this->RemainingWidth.'%"></td>';
                $labelColumn='<th class="subTotalLabel" width="'.$this->SubtotalLabelWidth.'%"><p style="margin:0;padding:0;">'.htmlspecialchars($row['label']).'</p></th>';
                $valueColumn='<td class="subTotalValue" width="'.$this->SubtotalValueWidth.'%"><p style="margin:0;padding:0;">'.$row['value'].'</p></td>';

                if($useRTL)
                    $table.='<tr class="'.esc_attr($class).' subTotalRow">'.$valueColumn.$labelColumn.$remainingColumn.'</tr>';
                else
                    $table.='<tr class="'.esc_attr($class).' subTotalRow">'.$remainingColumn.$labelColumn.$valueColumn.'</tr>';

            }
        }
        return $table;
    }

    private function CreateTotalRow($type,$labelProperty,$omitIfEmpty=false,$showInNegativeNumber=false)
    {


        if (!empty($this->options))
        {
            $options=$this->options;
        }
        $total=$this->GetTotalValue($type,$omitIfEmpty,$showInNegativeNumber);
        if($total==''&&$omitIfEmpty)
            return '';


        $remainingWidthColumn='<td width="'.$this->RemainingWidth.'%"></td>';
        $labelColumn='<th width="'.$this->SubtotalLabelWidth.'%" class="subTotalLabel"><p style="margin:0;padding:0;">'.htmlspecialchars($this->orderValueRetriever->TranslateText($options->fieldID,$labelProperty,$this->GetPropertyValue($labelProperty))).'</p></th>';
        $valueColumn='<td width="'.$this->SubtotalValueWidth.'%" class="subTotalValue"><p style="margin:0;padding:0;padding-left: 5px">'.$total.'</p></td>';

        if(Sanitizer::GetValueFromPath($this->orderValueRetriever->templateOptions,['containerOptions','useRTL'],false))
        {
            return '<tr class="'.esc_attr($type).' subTotalRow">'.$valueColumn.$labelColumn.$remainingWidthColumn.'</tr>';
        }else
            return '<tr class="'.esc_attr($type).' subTotalRow">'.$remainingWidthColumn.$labelColumn.$valueColumn.'</tr>';


    }





    protected function GetTaxes()
    {
        $includePercentage=$this->GetSubTotalProperty('Taxes','includePercentages');
        $taxes=array();
        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
            foreach($this->orderValueRetriever->order->get_taxes() as $tax){
                $percentage='';

                if($includePercentage)
                {
                    $percentage=$tax->get_meta('rn_tax_percentage',true);
                    if($percentage!==false)
                    {
                        $percentage=' '.$percentage;
                    }
                }

                $taxLabel=$tax->get_label();
                $taxLabel=$this->orderValueRetriever->TranslateText($this->options->fieldID,'Tax_'.$taxLabel,$taxLabel);

                $taxes[]=array(
                    'label'=>htmlspecialchars_decode( $taxLabel).$percentage,
                    'value'=>wc_price((float) $tax->get_tax_total() + (float) $tax->get_shipping_tax_total(),array( 'currency' => $this->orderValueRetriever->get('currency') ))
                );

            }
            return $taxes;
        }else{
            $percentage='';
            if($includePercentage)
            {
                $taxList=$this->orderValueRetriever->order->get_taxes();
                if(is_array($taxList)&&count($taxList)==1)
                {
                    $tax = current($taxList);
                    if ($tax != false)
                    {
                        $percentage = $tax->get_meta('rn_tax_percentage', true);
                        if ($percentage !== false)
                        {
                            $percentage = ' ' . $percentage;
                        }
                    }
                }

            }


            $taxes[]=array(
                'label'=>$this->orderValueRetriever->TranslateText($this->options->fieldID,'TaxesLabel',$this->GetPropertyValue('TaxesLabel')).$percentage,
                'value'=>wc_price($this->orderValueRetriever->order->get_total_tax(), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ) )
            );
        }
        return $taxes;
    }

    protected function GetFees()
    {
        $fees=array();
        foreach($this->orderValueRetriever->order->get_fees() as $fee){
            $fees[]=array(
                'label'=>$this->GetFeePropertyValue($fee,'name'),
                'value'=>wc_price($this->GetFeePropertyValue($fee,'total'), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ) )
            );
        }
        return $fees;
    }

    protected function GetFeePropertyValue($object,$propertyName)
    {
        if(!is_array($object))
        {
            if ( is_callable( array( $object, "get_{$propertyName}" ) ) ) {
                return $object->{"get_{$propertyName}"}();
            } else {
                if(is_callable(array($object,$propertyName)))
                    return $object->$propertyName;
            }
        }else{
            if(isset($object[$propertyName]))
                return $object[$propertyName];
            if(isset($object['line_'.$propertyName]))
                return $object['line_'.$propertyName];
        }
        return null;
    }

    protected function GetTotalValue($totalType,$omitIfEmpty,$showInNegativeNumber)
    {
        if($totalType=='quantity')
        {
            $items=$this->orderValueRetriever->order->get_items();
            $quantity=0;
            foreach($items as $currentItem)
            {
                $quantity+=$currentItem->get_quantity();
            }

            return $quantity;
        }

        if($totalType=='weight')
        {
            /** @var \WC_Order_Item[] $items */
            $items=$this->orderValueRetriever->order->get_items();
            $weight=0;
            foreach($items as $currentItem)
            {
                $product=$currentItem->get_product();
                $weight+=\floatval($product->get_weight()*$currentItem->get_quantity());
            }

            return $weight . get_option( 'woocommerce_weight_unit' );
        }

        if($totalType=='shipping')
        {
            $excludeCarrier=$this->GetSubTotalProperty('Shipping','excludeCarrierName');
            $priceToUse=$this->orderValueRetriever->order->get_shipping_total();

            $includeShippingTax=$this->GetSubTotalProperty('SubTotal','includeTaxes')===true;
            if($includeShippingTax)
            {
                $priceToUse+=$this->orderValueRetriever->order->get_shipping_tax();
            }


            if($excludeCarrier)
                return \wc_price($priceToUse,\apply_filters('rnwcinv_fornmat_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ));
            return str_replace('&nbsp;',' ',$this->orderValueRetriever->GetTotal('shipping', $omitIfEmpty,$includeShippingTax));
        }

        if($totalType=='discount')
        {
            $includeTax=$this->GetSubTotalProperty('Discount','includeTaxes')===true;
            $total=$this->orderValueRetriever->order->get_discount_total();
            if($omitIfEmpty&&$total==0)
                return '';
            if($includeTax)
                $total+=$this->orderValueRetriever->order->get_discount_tax();


            if($showInNegativeNumber)
                $total*=-1;


            return wc_price($total, \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }

        if($totalType=='subtotal')
        {
            $includeTax=$this->GetSubTotalProperty('SubTotal','includeTaxes')===true;
            $includeDiscount=$this->GetSubTotalProperty('SubTotal','includeDiscount')===true;
            $total=$this->orderValueRetriever->order->get_subtotal();
            if($includeTax)
                $total+=$this->orderValueRetriever->order->get_total_tax();
            if($includeDiscount)
                $total-=$this->orderValueRetriever->order->get_total_discount();

            if($this->GetFeePosition()=='table')
            {
                $fees=$this->orderValueRetriever->order->get_fees();
                $totalFees=0;
                foreach ($fees as $fee)
                {
                   $totalFees+=floatval($fee->get_total());
                   if($includeTax)
                       $totalFees+=floatval($fee->get_total_tax());
                }

                $total+=$totalFees;

            }
            return wc_price($total, \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }

        if($totalType=='refund')
        {
            $refundTotal= $this->orderValueRetriever->order->get_total_refunded();
            if($omitIfEmpty&&$refundTotal==0)
                return '';
            if(!$this->GetSubTotalProperty('Refund','includeTaxes')===true)
            {
                $refundTotal-=$this->orderValueRetriever->order->get_total_tax_refunded();
            }
            if($showInNegativeNumber)
                $refundTotal*=-1;
            return wc_price($refundTotal, \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }

        if($totalType=='total')
        {
            $showRefund=$this->GetSubTotalProperty('Total','showRefundedAmount',true);
            return \apply_filters('rnwcinv_format_grand_total_price', $this->orderValueRetriever->order->get_formatted_order_total('',$showRefund), $this->orderValueRetriever->order);
        }
        return '';

    }


    protected function GetSubTotalProperty($type,$property,$defaultValue='')
    {
        $assoc=$this->GetAssoc($type.'AdditionalProperties');
        if(!isset($assoc->$property))
            return $defaultValue;

        return $assoc->$property;

    }

    protected function GetItems()
    {
        $items=array();
        $imageOptions=$this->GetColumn('prod_thumbnail');
        $regularPriceIncludeTaxes=$this->GetAdditionalOptionsProperty('regular_price','includeTaxes');
        $unitPriceIncludeTaxes=$this->GetAdditionalOptionsProperty('unit_price','includeTaxes');
        $discountIncludeTaxes=$this->GetAdditionalOptionsProperty('discount','includeTaxes');
        $index=0;

        foreach($this->OrderToUse->get_items() as $orderItem)
        {
            $discount=floatval($orderItem['line_total'])-floatval($orderItem['line_subtotal']);
            $product=$orderItem->get_product();
            $sku='';
            $weight='';
            $regularPrice='';
            $unitPrice='';
            $totalWeight=0;
            if(!empty($product))
            {
                $sku=$product->get_sku();
                $regularPrice=$product->get_regular_price();
                if($regularPriceIncludeTaxes)
                {
                    $regularPrice=wc_get_price_including_tax($product,array('price'=>$regularPrice));
                }
                $regularPrice=wc_price($regularPrice, \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency') )) );

                $unitPrice=$product->get_price();
                if($unitPriceIncludeTaxes)
                {
                    $unitPrice=wc_get_price_including_tax($product,array('price'=>$unitPrice));
                }
                $unitPrice=wc_price($unitPrice, \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency') )) );
                $weight= $product->get_weight();
                $totalWeight=Sanitizer::SanitizeNumber($weight)*$orderItem->get_quantity();
                if($discountIncludeTaxes)
                {
                    $discount=wc_get_price_including_tax($product,array('price'=>$discount));
                }

            }

            $price=$this->GetPrice($orderItem,$unitPriceIncludeTaxes,false);




            $qty=$orderItem['qty'];

            if(!\is_numeric($qty))
                $qty=1;

            $productName=$orderItem['name'];


            if($qty==0)
                $unitPrice=0;
            else
                $unitPrice=$price/$qty;
            $newItem=array(
                'type'=>'line',
                'line_number'=>++$index,
                'data'=>$orderItem,
                'prod'=>$this->orderValueRetriever->TranslateProductName($orderItem['name'],$product),
                'qty'=>$orderItem['qty'],
                'price'=>wc_price($this->GetPrice($orderItem),\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') ))).$this->MaybeGetRefunds($orderItem),
                'vat'=>wc_price($orderItem['line_tax'],\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') ))),
                'discount'=>wc_price($discount,\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') ))),
                'sku'=>$sku,
                'regular_price'=>$regularPrice,
                'unit_price'=>wc_price($unitPrice,\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') ))),
                'weight'=> $weight,
                'total_weight'=>$totalWeight,
                'total_tax'=>wc_price($this->orderValueRetriever->order->get_line_tax($orderItem),\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') ))),
                'total'=>wc_price(floatval($orderItem['total'])+floatval($orderItem['total_tax']),\apply_filters('rnwcinv_format_price', array( 'currency' => $this->orderValueRetriever->get('currency') )))
            );


            if($imageOptions!=null)
            {
                $var=null;
                $var = apply_filters( 'woocommerce_order_item_thumbnail', $var, $orderItem );

                /** @var WC_Product $product */
                $product=$orderItem->get_product();
                $imagePath='';
                if($product!=false)
                    $imagePath = \get_attached_file( $product->get_image_id());


                $newItem['prod_thumbnail']=$imagePath;
            }


            if($this->GetColumn('description'))
            {
                $newItem['description'] = get_post($orderItem['product_id'])->post_content;

            }

            if($this->GetColumn('short_description'))
            {
                $newItem['short_description'] = get_post($orderItem['product_id'])->post_excerpt;

            }


            $columns=$this->GetPropertyValue('ColumnOptions');
            if($columns!='')
            {
                foreach($columns as $currentColumn)
                {
                    if($currentColumn->type=='custom')
                    {
                        $customFieldId=Sanitizer::GetStringValueFromPath($currentColumn,['customProperties','id'],'');
                        if($customFieldId=='')
                            continue;

                        if($this->orderValueRetriever->useTestData)
                        {
                            $newItem['custom_'.$customFieldId]='test';
                        }else
                            $newItem['custom_'.$customFieldId]=$this->GetCustomColumnValue($customFieldId,$newItem);

                    }
                }
            }
            $items[]=$newItem;
            foreach($this->CustomRows as $customRow)
            {
                CustomFieldValueRetriever::$lineItem=$orderItem;
                foreach($customRow->GetItems() as $subItem)
                {
                    $items[]=$subItem;
                }

            }

        }

        if($this->GetFeePosition()=='table')
        {
            foreach($this->orderValueRetriever->order->get_fees() as $fee){
                $newItem=array(
                    'type'=>'fee',
                    'data'=>null,
                    'prod'=>$this->GetFeePropertyValue($fee,'name'),
                    'qty'=>1,
                    'price'=>wc_price($this->GetFeePropertyValue($fee,'total'),\apply_filters('rnwcinv_format_price', array( 'currency' =>  $this->orderValueRetriever->get('currency') ) )),
                    'vat'=>0,
                    'discount'=>0,
                    'sku'=>'',
                    'regular_price'=>0,
                    'weight'=> 0
                );
                $items[]=$newItem;
            }



        }

        $items=$this->MaybeSortItems($items);

        return apply_filters('rnwcinv_get_invoice_detail_items',$items,$this);

       // return apply_filters('wcpdfi_get_items',$items);
    }

    /**
     * @return
     */
    protected function GetFeePosition(){
        if(!RednaoWooCommercePDFInvoice::IsPR()||(isset($this->options->FeesAdditionalProperties)&&$this->options->FeesAdditionalProperties->Position=='table'))
            return 'table';
        return 'subtotal';
    }

    protected function GetColumn($columnName)
    {
        foreach($this->GetArray('ColumnOptions') as $option)
        {
            if($option->type==$columnName)
            {
                return $option;
            }
        }
        return null;
    }

    protected function GetAdditionalOptionsProperty($type,$propertyName)
    {
        foreach($this->GetArray('ColumnOptions') as $option)
        {
            if($option->type==$type)
            {
                if(!isset($option->additionalProperties))
                    return '';

                if(!isset($option->additionalProperties->$propertyName))
                    return '';
                return $option->additionalProperties->$propertyName;
            }
        }
        return '';
    }







    protected function GetDetailValue($i, $type,$column)
    {

        if($type=='custom')
        {
            $customId=$column->customProperties->id;
            if(isset($this->items[$i]['custom_'.$customId]))
                return $this->items[$i]['custom_'.$customId];
            return '';
        }


        if($type=='prod')
        {
            if($this->ShouldIncludeMeta($column))
            {
                if(isset($this->items[$i]['data']))
                {
                    return apply_filters('rnpdf_process_order_item_meta',$this->items[$i][$type],$this->items[$i]);
                }
            }
        }


        return $this->items[$i][$type];

    }

    private function GetCustomColumnValue($id,$row)
    {

        global $wpdb;
        $results=$wpdb->get_results($wpdb->prepare('select custom_field_text from '.\RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE.' where custom_field_id=%s',$id),'ARRAY_A');
        if($results!==false&&count($results)>0)
        {
            /** @var \WC_Order $order */
            $order=$this->orderValueRetriever->order;

            /** @var \WC_Order_Item_Product $item */
            $item=$row['data'];




            if(!isset($item['sku']))
            {
                $item['sku']=$row['sku'];
            }
            if(!isset($item['weight']))
            {
                $item['weight']=$row['weight'];
            }
            $evalResult='';
            /** @noinspection PhpUnusedLocalVariableInspection  use on eval*/
            $actions=$this;
            try{
                $customCode=$results[0]['custom_field_text'];
                $customCode='use rnwcinv\pr\CustomField\CustomFieldFactory; use rnwcinv\pr\CustomField\utilities\CustomFieldValueRetriever;use rnwcinv\pr\CustomFieldV2;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CNumericField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CArrayField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CSimpleField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CCurrencyField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CImageField;
        use rnwcinv\pr\CustomFieldV2\Wrappers\CRow; '.$customCode;

                if(isset($row['type'])&&$row['type']=='fee')
                {
                    return '';
                }
                CustomFieldValueRetriever::$order=$order;
                CustomFieldValueRetriever::$lineItem=$row['data'];
                CustomFieldValueRetriever::$IsTableCustomField=true;
                $evalResult= eval($customCode);
            }catch(\Exception $ex)
            {
                echo $ex;
            }

            if($evalResult==null)
                return '';
            return $evalResult;
        }
        return '';



    }

    public function FormatCurrency($value){
        return wc_price($value, \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency') )) );
    }

    public function QRCode($string)
    {
        require_once \RednaoWooCommercePDFInvoice::$DIR.'vendor/phpqrcode/qrlib.php';
        $svgCode = \QRcode::svg($string,false,QR_ECLEVEL_L,3,0);
        return '<img   src="data:image/svg+xml;base64,' . base64_encode($svgCode).'"/>';
    }

    private function GetPrice($orderItem,$includeTaxes=null,$includeDiscount=null)
    {
        if($includeTaxes===null)
            $includeTaxes=$this->GetAdditionalOptionsProperty('price','includeTaxes');
        if($includeDiscount===null)
            $includeDiscount=$this->GetAdditionalOptionsProperty('price','includeDiscount');

        if($includeDiscount)
        {
            $value = floatval($orderItem['line_total']);
        }
        else
        {
            $value = floatval($orderItem['line_subtotal']);
        }

        if($includeTaxes)
        {
            $value = $value + floatval($orderItem['line_tax']);
        }

        return $value;
    }


    protected function InternalGetHTML()
    {
        $this->GetCustomRowsIfAny();
        $this->items=$this->GetItems();
        $columns=$this->GetArray('ColumnOptions');
        $html='<table class="pdfTable" style="width:100%">';
        $html.=$this->CreateHeader($columns);
        $html.=$this->CreateDetailRows($columns);
        $html.='</table>';
        $html.=$this->CreateTotalsRows();
        return $html;
    }



    private function GetCustomRowsIfAny()
    {
        $this->CustomRows=[];
        if(isset($this->options->CustomRows))
        {
            $ids=\implode(',',$this->options->CustomRows);
            $ids=str_replace('custom_','', $ids);
            global $wpdb;
            $rows=$wpdb->get_results($wpdb->prepare('select custom_field_text from '.\RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE.' where custom_field_id in (%d)',$ids));
            foreach($rows as $row)
            {
                $customRow=$this->ProcessCustomRowText($row->custom_field_text);
                if($customRow!=null)
                    $this->CustomRows[]=$customRow;
            }
        }
    }

    public function ProcessCustomRowText($text){
        if($this->orderValueRetriever==null)
            return null;
        $customField=
            'use rnwcinv\pr\CustomField\CustomFieldFactory; 
             use rnwcinv\pr\CustomField\utilities\CustomFieldValueRetriever;
             use rnwcinv\pr\CustomFieldV2;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CNumericField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CArrayField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CSimpleField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CCurrencyField;
        use rnwcinv\pr\CustomFieldV2\Wrappers\CRow;
         use rnwcinv\pr\CustomFieldV2\Wrappers\RawRow;';
        $order=$this->orderValueRetriever->order;
        if($order==null)
            return null;
        $text=str_replace("\\","\\\\",$text);
        $text=\str_replace("\n",'',$text);
        $customField.=$text;

        return  eval($customField);

    }

    /**
     * @param $orderItem \WC_Order_Item
     */
    private function MaybeGetRefunds($orderItem)
    {
        if($this->orderValueRetriever==null||$this->orderValueRetriever->order==null)
            return '';
        $price=$this->orderValueRetriever->order->get_total_refunded_for_item($orderItem->get_id());

        if($price<=0)
            return '';

        return ' <span style="color: red;margin-left: 3px;">'. wc_price($price*-1,array( 'currency' => $this->orderValueRetriever->get('currency') )).'</span>';


    }

    private function CalculateSubTotalWidths()
    {
        $valueWidth=$this->GetPropertyValue('SubtotalValueWidth');
        if($valueWidth==''||!is_numeric($valueWidth))
            $valueWidth='20';
        $labelWidth=$this->GetPropertyValue('SubtotalLabelWidth');
        if($labelWidth==''||!is_numeric($labelWidth))
            $labelWidth='20';

        $this->SubtotalValueWidth=intval($valueWidth);
        $this->SubtotalLabelWidth=intval($labelWidth);
        $this->RemainingWidth=100-$valueWidth-$labelWidth;

        if($this->RemainingWidth<0)
        {
            $this->RemainingWidth=60;
            $this->SubtotalLabelWidth=20;
            $this->SubtotalValueWidth=20;
        }
    }

    private function ShouldIncludeMeta($column)
    {
        if(!RednaoWooCommercePDFInvoice::IsPR()||$this->orderValueRetriever->useTestData)
            return false;


        if(isset($column->additionalProperties->includeMeta)&&$column->additionalProperties->includeMeta&&$column->additionalProperties->includeMeta==true)
            return true;

        return false;

    }

    private function GetShippingLines()
    {
        if(!RednaoWooCommercePDFInvoice::IsPR())
            return [];
        return [
            'label'=>'test',
            'value'=>1
        ];
        $fees=array();
        foreach($this->orderValueRetriever->order->get_fees() as $fee){
            $fees[]=array(
                'label'=>$this->GetFeePropertyValue($fee,'name'),
                'value'=>wc_price($this->GetFeePropertyValue($fee,'total'), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ) )
            );
        }
        return $fees;
    }

    private function CreateAdditionalTotals()
    {
        $customSubTotals=$this->GetPropertyValue('CustomSubTotals');
        if(empty($customSubTotals))
            return '';

        $totalRows='';
        foreach ($customSubTotals as $currentSubTotal)
        {
            $customFieldId=Sanitizer::GetStringValueFromPath($currentSubTotal,['custom_field_id']);
            $customFieldName=Sanitizer::GetStringValueFromPath($currentSubTotal,['custom_field_name']);

            $evalResult=wc_price(0);
            if(!$this->orderValueRetriever->useTestData)
            {
                global $wpdb;
                $results=$wpdb->get_results($wpdb->prepare('select custom_field_text from '.\RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE.' where custom_field_id=%s',$customFieldId),'ARRAY_A');

                if($results==false||count($results)==0)
                    continue;

                $order=$this->orderValueRetriever->order;
                try{
                    $customCode=$results[0]['custom_field_text'];
                    $customCode='use rnwcinv\pr\CustomField\CustomFieldFactory; use rnwcinv\pr\CustomField\utilities\CustomFieldValueRetriever;use rnwcinv\pr\CustomFieldV2;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CNumericField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CArrayField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CSimpleField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CCurrencyField;
             use rnwcinv\pr\CustomFieldV2\BasicFields\CImageField;
        use rnwcinv\pr\CustomFieldV2\Wrappers\CRow; '.$customCode;


                    CustomFieldValueRetriever::$order=$order;
                    CustomFieldValueRetriever::$lineItem=null;
                    CustomFieldValueRetriever::$IsTableCustomField=false;
                    $evalResult= eval($customCode);
                }catch(\Exception $ex)
                {
                    continue;
                }

            }


            $type='customst_'.$customFieldId;
            $remainingWidthColumn='<td width="'.$this->RemainingWidth.'%"></td>';
            $labelColumn='<th width="'.$this->SubtotalLabelWidth.'%" class="subTotalLabel"><p style="margin:0;padding:0;">'.esc_html($customFieldName).'</p></th>';
            $valueColumn='<td width="'.$this->SubtotalValueWidth.'%" class="subTotalValue"><p style="margin:0;padding:0;padding-left: 5px">'.$evalResult.'</p></td>';

            if(Sanitizer::GetValueFromPath($this->orderValueRetriever->templateOptions,['containerOptions','useRTL'],false))
            {
                $totalRows.= '<tr class="'.esc_attr($type).' subTotalRow">'.$valueColumn.$labelColumn.$remainingWidthColumn.'</tr>';
            }else
                $totalRows.= '<tr class="'.esc_attr($type).' subTotalRow">'.$remainingWidthColumn.$labelColumn.$valueColumn.'</tr>';

        }

        return $totalRows;



    }

    private function MaybeSortItems($items)
    {
        $sortBy=$this->GetPropertyValue('SortBy');
        if($sortBy=='')
            return $items;

        $sortDirection=$this->GetPropertyValue('SortDirection');

        if(count($items)==0||!isset($items[0][$sortBy]))
            return $items;

        $sortType=SORT_NATURAL;

        $keys=array_column($items,$sortBy);
        array_multisort($keys,$sortDirection=='asc'?SORT_ASC:SORT_DESC,$sortType,$items);
        return $items;
    }


}