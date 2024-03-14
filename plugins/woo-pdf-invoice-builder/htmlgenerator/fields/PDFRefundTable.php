<?php
namespace  rnwcinv\htmlgenerator\fields;

use Automattic\WooCommerce\Admin\Overrides\OrderRefund;
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

class PDFRefundTable extends PDFFieldBase
{

    protected $items=array();
    /** @var CRow[] */
    public $CustomRows;
    /** @var \WC_Order */
    public $OrderToUse;

    public $SubtotalLabelWidth=20;
    public $SubtotalValueWidth=20;
    public $RemainingWidth=60;
    public $ShowNegativeNumbers=true;
    public function __construct($options, $orderValueRetriever)
    {
        parent::__construct($options, $orderValueRetriever);
        if(isset($options->HideRowsCondition)&&count($options->HideRowsCondition)>0&&$orderValueRetriever!=null&&!$this->orderValueRetriever->useTestData)
        {
            $this->OrderToUse=new SplittedOrder($orderValueRetriever->order);
            $this->OrderToUse->SetCondition($options->HideRowsCondition);

        }else
            $this->OrderToUse=$orderValueRetriever->order;

        $this->ShowNegativeNumbers=Sanitizer::GetValueFromPath($options,'ShowNegativeNumbers',true);

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

        if($this->GetBoolValue('ShowTotal'))
        {
            $totalsTable.=$this->CreateTotalRow('total','TotalLabel');
        }
        

        if(strlen($totalsTable)>0)
            $totalsTable='<table width="100%" style="page-break-inside: avoid" class="footerTable"><tbody>'.$totalsTable.'</tbody></table>';


        return $totalsTable;


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
            if($column->type=='custom')
            {
                $id='th_cust_'.$column->customProperties->id;
            }
            $header.='<th class="'.$id.'" style="width:'.$column->width.';">'.htmlspecialchars($this->orderValueRetriever->TranslateText($options->fieldID,$column->type,$column->header) ).'</th>';
        }
        $header.='</tr></thead>';
        return $header;
    }


    protected function CreateTabularRow($rows, $class){
        $table='';
        if(count($rows)>0)
        {
            $table='';
            foreach($rows as $row)
            {
                $table.='<tr class="'.esc_attr($class).' subTotalRow"><td width="'.$this->RemainingWidth.'%"></td><th class="subTotalLabel" width="'.$this->SubtotalLabelWidth.'%"><p style="margin:0;padding:0;">'.htmlspecialchars($row['label']).'</p></th>'.
                    '<td class="subTotalValue" width="'.$this->SubtotalValueWidth.'%"><p style="margin:0;padding:0;">'.$row['value'].'</p></td></tr>';

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
        return '<tr class="'.esc_attr($type).' subTotalRow">'.
                    '<td width="'.$this->RemainingWidth.'%"></td><th width="'.$this->SubtotalLabelWidth.'%" class="subTotalLabel"><p style="margin:0;padding:0;">'.htmlspecialchars($this->orderValueRetriever->TranslateText($options->fieldID,$labelProperty,$this->GetPropertyValue($labelProperty))).'</p></th>'.
                    '<td width="'.$this->SubtotalValueWidth.'%" class="subTotalValue"><p style="margin:0;padding:0;padding-left: 5px">'.$total.'</p></td></tr>';


    }





    protected function GetTaxes()
    {
        $includePercentage=$this->GetSubTotalProperty('Taxes','includePercentages');
        $taxes=array();
        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
            /** @var \WC_Order_Refund $order */
            foreach($this->GetOrders() as $order)
                foreach($order->get_taxes() as $tax){
                    $percentage='';

                    if($includePercentage)
                    {
                        $percentage=$tax->get_meta('rn_tax_percentage',true);
                        if($percentage!==false)
                        {
                            $percentage=' '.$percentage;
                        }
                    }

                    $taxes[]=array(
                        'label'=>htmlspecialchars_decode($tax->get_label()).$percentage,
                        'value'=>wc_price($this->MaybeInvertAmount($this->MaybeAdjustPriceSign((float) $tax->get_tax_total() + (float) $tax->get_shipping_tax_total())),array( 'currency' => $this->orderValueRetriever->get('currency') ))
                    );

                }
            return $taxes;
        }else{
            $percentage='';
            if($includePercentage)
            {
                foreach($this->GetOrders() as $order)
                $taxList=$order->get_taxes();
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


            $total=0;
            /** @var \WC_Order_Refund $order */
            foreach($this->GetOrders() as $order)
                $total+=$order->get_total_tax();

            $taxes[]=array(
                'label'=>$this->orderValueRetriever->TranslateText($this->options->fieldID,'TaxesLabel',$this->GetPropertyValue('TaxesLabel')).$percentage,
                'value'=>wc_price($this->MaybeInvertAmount($this->MaybeAdjustPriceSign($total)), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ) )
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

    public function GetOrderItems(){
        $items=[];
        /** @var \WC_Order_Refund[] $order */
        foreach($this->OrderToUse->get_items() as $currentItem)
        {
            if($this->IsFullRefund()|| $this->OrderToUse->get_qty_refunded_for_item($currentItem->get_id())!=0)
                $items[]=$currentItem;
        }
        return $items;
    }
    protected function GetTotalValue($totalType,$omitIfEmpty,$showInNegativeNumber)
    {
        if($totalType=='quantity')
        {
            if($this->IsFullRefund())
            {
                $items=$this->orderValueRetriever->order->get_items('line_item');
                $quantity=0;
                foreach($items as $currentItem)
                {
                    $quantity+=$currentItem->get_quantity();
                }
                return $quantity;
            }
            return $this->MaybeInvertAmount($this->OrderToUse->get_total_qty_refunded()*-1);
        }

        if($totalType=='weight')
        {
            /** @var \WC_Order_Item[] $items */
            $items=$this->GetOrderItems();
            $weight=0;
            foreach($items as $currentItem)
            {
                $product=$currentItem->get_product();
                $weight+=\floatval($product->get_weight()*$this->OrderToUse->get_qty_refunded_for_item($currentItem->get_id())*-1);
            }

            return $weight . get_option( 'woocommerce_weight_unit' );
        }

        if($totalType=='shipping')
        {
            $excludeCarrier=$this->GetSubTotalProperty('Shipping','excludeCarrierName');
            $priceToUse=0;
            $carrier=[];

                /** @var \WC_Order_Item_Shipping $item */
                foreach($this->GetRefundOrOrderItems('shipping') as $item)
                {
                    $carrier[]=esc_html($item->get_name());
                    $priceToUse += $item->get_total();

                    $includeShippingTax = $this->GetSubTotalProperty('SubTotal', 'includeTaxes') === true;
                    if ($includeShippingTax)
                    {
                        $priceToUse += $item->get_total_tax();
                    }
                }



            $price= \wc_price($this->MaybeInvertAmount($priceToUse),\apply_filters('rnwcinv_fornmat_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ));

            if($excludeCarrier)
                return $price;
            return $price.' '.__("via","wooinvoicebuilder").' '.implode(', ',$carrier);
        }

        if($totalType=='discount')
        {
            $includeTax=$this->GetSubTotalProperty('Discount','includeTaxes')===true;
            /** @var \WC_Order_Refund $order */
            $total=0;
            foreach($this->GetOrders() as $order)
            {
                $total += $order->get_discount_total();
                if ($includeTax)
                    $total += $this->orderValueRetriever->order->get_discount_tax();



            }

            if ($showInNegativeNumber)
                $total *= -1;
            if ($omitIfEmpty && $total == 0)
                return '';

            return wc_price($this->MaybeInvertAmount($total), \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }

        $orders=$this->GetOrders();


        if($totalType=='subtotal')
        {
            $includeTax=$this->GetSubTotalProperty('SubTotal','includeTaxes')===true;
            $includeDiscount=$this->GetSubTotalProperty('SubTotal','includeDiscount')===true;
            $total=0;

            foreach($orders as $order)
            {
                $total = $order->get_subtotal();
                if ($includeTax)
                    $total += $order->get_total_tax();
                if ($includeDiscount)
                    $total -= $order->get_total_discount();

                if ($this->GetFeePosition() == 'table')
                {
                    $fees = $this->GetOrderFees();
                    $totalFees = 0;
                    foreach ($fees as $fee)
                    {
                        $totalFees += floatval($fee->get_total());
                        if ($includeTax)
                            $totalFees += floatval($fee->get_total_tax());
                    }

                    $total += $totalFees*-1;

                }
            }
            return wc_price($this->MaybeInvertAmount($this->MaybeAdjustPriceSign($total)), \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }


        if($totalType=='total')
        {
            $total=0;
            /** @var \WC_Order_Refund $order */

            foreach($orders as $order)
                $total+=$order->get_total();
            return wc_price($this->MaybeInvertAmount($this->MaybeAdjustPriceSign($total)), \apply_filters('rnwcinv_format_price',array( 'currency' => $this->orderValueRetriever->get('currency') )));
        }
        return '';

    }

    protected function GetOrders(){
        if($this->IsFullRefund())
            $orders=[$this->OrderToUse];
        else
            $orders=$this->orderValueRetriever->order->get_refunds();
        return $orders;
    }

    protected function GetSubTotalProperty($type,$property)
    {
        $assoc=$this->GetAssoc($type.'AdditionalProperties');
        if(!isset($assoc->$property))
            return '';

        return $assoc->$property;

    }

    protected function GetItems()
    {
        $items = array();
        $imageOptions = $this->GetColumn('prod_thumbnail');
        $regularPriceIncludeTaxes = $this->GetAdditionalOptionsProperty('regular_price', 'includeTaxes');
        $unitPriceIncludeTaxes = $this->GetAdditionalOptionsProperty('unit_price', 'includeTaxes');
        $discountIncludeTaxes = $this->GetAdditionalOptionsProperty('discount', 'includeTaxes');
        $index = 0;

        /** @var OrderRefund[] $refunds */
        $refunds = $this->OrderToUse->get_refunds();

        foreach ($this->OrderToUse->get_items() as $item)
        {
            $qty=$this->GetLineRefundedQty($item);
            if($qty==0)
                continue;

            $discount=0;
                $product = $item->get_product();
                $sku = '';
                $weight = '';
                $regularPrice = '';
                $unitPrice = '';
                $totalWeight = 0;
                if (!empty($product))
                {
                    $sku = $product->get_sku();
                    $regularPrice = $product->get_regular_price();
                    if ($regularPriceIncludeTaxes)
                    {
                        $regularPrice = wc_get_price_including_tax($product, array('price' => $regularPrice));
                    }
                    $regularPrice = wc_price($this->MaybeInvertAmount($regularPrice), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency'))));

                    $unitPrice = $product->get_price();
                    if ($unitPriceIncludeTaxes)
                    {
                        $unitPrice = wc_get_price_including_tax($product, array('price' => $unitPrice));
                    }
                    $unitPrice = wc_price($unitPrice, \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency'))));
                    $weight = $product->get_weight();
                    $totalWeight = Sanitizer::SanitizeNumber($weight) * $qty;
                    if ($discountIncludeTaxes)
                    {
                        $discount = wc_get_price_including_tax($product, array('price' => $discount));
                    }

                }

                $price = $this->IsFullRefund()?$item->get_total():$this->OrderToUse->get_total_refunded_for_item($item->get_id());



                $productName = $item->get_name();


                $unitPrice = ($price / $qty);
                $newItem = array(
                    'type' => 'line',
                    'line_number' => ++$index,
                    'data' => $item,
                    'prod' => $item['name'],
                    'qty' => $this->MaybeInvertAmount($qty),
                    'price' => wc_price($this->MaybeInvertAmount($price), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency')))),
                    'vat' => wc_price($this->IsFullRefund()?$this->MaybeInvertAmount($item['line_tax']):$this->MaybeInvertAmount($this->GetTotalRefundedByItem($item->get_id())), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency')))),
                    'discount' => 0,
                    'sku' => $sku,
                    'regular_price' => $regularPrice,
                    'unit_price' => wc_price($this->MaybeInvertAmount($unitPrice), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency')))),
                    'weight' => $weight,
                    'total_weight' => $totalWeight,
                    'total_tax' =>$this->IsFullRefund()?$this->orderValueRetriever->order->get_line_tax($item): $this->GetTotalRefundedByItem($item->get_id()),
                    'total' => wc_price($this->MaybeInvertAmount($price), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency'))))
                );


                if ($imageOptions != null)
                {
                    $var = null;
                    $var = apply_filters('woocommerce_order_item_thumbnail', $var, $item);

                    /** @var WC_Product $product */
                    $product = $item->get_product();
                    $imagePath = '';
                    if ($product != false)
                        $imagePath = \get_attached_file($product->get_image_id());


                    $newItem['prod_thumbnail'] = $imagePath;
                }


                if ($this->GetColumn('description'))
                {
                    $newItem['description'] = get_post($item['product_id'])->post_content;

                }

                if ($this->GetColumn('short_description'))
                {
                    $newItem['short_description'] = get_post($item['product_id'])->post_excerpt;

                }

                $items[] = $newItem;

                foreach ($this->CustomRows as $customRow)
                {
                    CustomFieldValueRetriever::$lineItem = $item;
                    foreach ($customRow->GetItems() as $subItem)
                    {
                        $items[] = $subItem;
                    }

                }



            if ($this->GetFeePosition() == 'table')
            {
                foreach ($this->GetOrderFees() as $fee)
                {
                    $newItem = array(
                        'type' => 'fee',
                        'data' => null,
                        'prod' => $this->GetFeePropertyValue($fee, 'name'),
                        'qty' => $this->MaybeInvertAmount(-1),
                        'price' => wc_price($this->GetFeePropertyValue($fee, 'total'), \apply_filters('rnwcinv_format_price', array('currency' => $this->orderValueRetriever->get('currency')))),
                        'vat' => 0,
                        'discount' => 0,
                        'sku' => '',
                        'regular_price' => 0,
                        'weight' => 0
                    );
                    $items[] = $newItem;
                }


            }



            // return apply_filters('wcpdfi_get_items',$items);
        }
        return apply_filters('rnwcinv_get_refund_detail_items', $items, $this);
    }

    public function MaybeInvertAmount($amount)
    {
        if(!is_numeric($amount))
            return $amount;
        if($this->ShowNegativeNumbers)
            return $amount*-1;
        return $amount;
    }

    public function GetTotalRefundedByItem( $item_id,  $item_type = 'line_item' ) {
        $total = 0;
        foreach ( $this->GetOrders() as $refund ) {
            foreach ( $refund->get_items( $item_type ) as $refunded_item ) {
                $taxes  = $refunded_item->get_taxes();
                foreach($taxes['total'] as $currentTotal)
                    $total+=floatval($currentTotal);
            }
        }
        return wc_round_tax_total( $total ) * -1;
    }

    /**
     * @return ''
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
            if($this->orderValueRetriever->useTestData)
            {
                return 'test';
            }

            return $this->GetCustomColumnValue($column->customProperties->id,$i);
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

    private function GetCustomColumnValue($id,$index)
    {

        global $wpdb;
        $results=$wpdb->get_results($wpdb->prepare('select custom_field_text from '.\RednaoWooCommercePDFInvoice::$CUSTOM_FIELDS_TABLE.' where custom_field_id=%s',$id),'ARRAY_A');
        if($results!==false&&count($results)>0)
        {
            $order=$this->orderValueRetriever->order;

            /** @var \WC_Order_Item_Product $item */
            $item=$this->items[$index]['data'];

            if(!isset($item['sku']))
            {
                $item['sku']=$this->items[$index]['sku'];
            }
            if(!isset($item['weight']))
            {
                $item['weight']=$this->items[$index]['weight'];
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

                if(isset($this->items[$index]['type'])&&$this->items[$index]['type']=='fee')
                {
                    return '';
                }
                CustomFieldValueRetriever::$order=$order;
                CustomFieldValueRetriever::$lineItem=$this->items[$index]['data'];
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
        foreach($this->GetOrderFees() as $fee){
            $fees[]=array(
                'label'=>$this->GetFeePropertyValue($fee,'name'),
                'value'=>wc_price($this->GetFeePropertyValue($fee,'total'), \apply_filters('rnwcinv_format_price',array( 'currency' =>  $this->orderValueRetriever->get('currency')) ) )
            );
        }
        return $fees;
    }


    private function GetOrderFees()
    {
        $fees=[];
        /** @var \WC_Order_Refund $orderFee */
        foreach ($this->GetOrders() as $refunds)
            foreach($refunds->get_fees() as $orderFee)
                $fees[]=$orderFee;


        return $fees;

    }

    public function IsFullRefund(){
        return $this->orderValueRetriever->order->get_total_refunded()==$this->orderValueRetriever->order->get_total();
    }

    private function MaybeAdjustPriceSign($price)
    {
        if($this->IsFullRefund())
            return $price;
        else
            return $price*-1;
    }

    private function GetLineRefundedQty($item)
    {
        if($this->IsFullRefund())
            return $item->get_quantity();
        else
            return $this->OrderToUse->get_qty_refunded_for_item($item->get_id())*-1;

    }

    private function GetRefundOrOrderItems($lineType)
    {
        if($this->IsFullRefund())
            return $this->OrderToUse->get_items($lineType);
        else
        {
            $items=[];
            foreach($this->OrderToUse->get_refunds() as $refund)
            {
                foreach($refund->get_items($lineType) as $item)
                    $items[]=$item;
            }
            return $items;
        }


    }


}