<?php

use rnwcinv\pr\integrations\CustomFieldsIntegration\CustomFieldIntegrationManager;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/11/2018
 * Time: 11:17 AM
 */

class WCInspector{
    /** @var WC_Order */
    public $order;
    public function __construct($order)
    {
        if(is_scalar($order))
            $this->order = wc_get_order( trim($order) );
        else
            $this->order=$order;
    }

    public function InspectOrder(){
        error_reporting(E_ERROR);
        $data=array();
        $this->ProcessMetaValues($this->order->get_meta_data(),$data,'normal');
        $this->ProcessNormalArray($this->order->get_data(),$data,array(),'normal');


        $integration=new CustomFieldIntegrationManager();
        $integration->InspectOrder($this->order,$data);

        $fees=$this->order->get_fees();
        foreach($fees as $key=>$value)
        {
            $this->ProcessMetaValues($value->get_meta_data(), $data, 'fee',array('Id'=>$key,'Name'=>$value->get_name()));
            $this->ProcessNormalArray($value->get_data(), $data, array(), 'fee',array('Id'=>$key,'Name'=>$value->get_name()));
        }

        $productData=array();
        $imageFound=false;
        $tagsFound=false;
        foreach($this->order->get_items() as $item)
        {

            /** @var WC_Product $product */
            $product=$item->get_product();
            if($product==null)
                continue;

            $imageId=$product->get_image_id();
            if(!$tagsFound)
            {
                $tags = get_the_terms($product->get_id(),'product_tag');

                if(is_array($tags)&&count($tags)>=0)
                {
                    $tagList=array_map(function($x){return $x->name;},$tags);


                    $data[]=array(
                        'path'=>'tags',
                        'key'=>'tags',
                        'value'=>json_encode($tagList),
                        'dataType'=>'array',
                        'source'=>'fixed',
                        'fieldType'=>'product',
                        'subTypeData'=>null
                    );
                }
                $tagsFound=true;
            }
            if(!$imageFound&&$imageId!='')
            {
                $imageFound=true;
                $url=wp_get_attachment_image_url($imageId);
                $data[]=array(
                    'path'=>'thumbnail',
                    'key'=>'Thumbnail',
                    'value'=>$url,
                    'dataType'=>'',
                    'source'=>'fixed',
                    'fieldType'=>'product',
                    'subTypeData'=>null
                );



            }

            $this->ProcessMetaValues($product->get_meta_data(), $data, 'product');
            $this->ProcessNormalArray($product->get_data(), $data, array(), 'product');


        }


        foreach($this->order->get_items() as $item)
        {
            $formattedMeta=$item->get_formatted_meta_data();
            foreach($formattedMeta as $currentFormattedMeta)
            {
                if(count(array_filter($data,function ($item)use($currentFormattedMeta){return $item['fieldType']=='formatted_meta'&&$item['source']=='fixed'&&$item['key']==$currentFormattedMeta->key;}))==0)
                $data[]=array(
                    'path'=>$currentFormattedMeta->key,
                    'display_key'=>$currentFormattedMeta->display_key,
                    'key'=>$currentFormattedMeta->key,
                    'display_type'=>'raw',
                    'value'=>$currentFormattedMeta->display_value,
                    'dataType'=>'',
                    'source'=>'fixed',
                    'fieldType'=>'formatted_meta',
                    'subTypeData'=>null
                );
            }
        }

        $this->CheckFields($data);



            return $data;
    }

    public function InspectPossibleRows(){
        $dataToReturn=array();
        foreach($this->order->get_items() as $item)
        {
            foreach($item->get_meta_data() as $meta)
            {
                if(is_array($meta->get_data()['value'])&&!$this->isAssoc($meta->get_data()['value']))
                {
                    $dictionary=$this->CreateDictionary($meta->get_data()['value']);
                    if(count($dictionary)==0)
                        continue;
                    $dataToReturn[]=array('source'=>'meta','key'=>$meta->get_data()['key'],'value'=>$dictionary);
                }
            }
        }


        $this->CheckFields($dataToReturn);
        return $dataToReturn;


    }


    private function isAssoc($arr)
    {
        if (array() === $arr)
            return false;
        ksort($arr);
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function InspectOrderDetails()
    {
        $data=array();
        $lineItems=$this->order->get_data()['line_items'];
        foreach($lineItems as $item)
        {
            /** @var WC_Product $product */
            $this->ProcessMetaValues($item->get_meta_data(),$data,"table");
            $product=$item->get_product();
            $this->ProcessProductMeta($product->get_meta_data(),$data,'table');
            $this->ProcessNormalArray($item->get_data(),$data,array(),"table");
        }

        foreach($this->order->get_items() as $item)
        {

            /** @var WC_Product $product */
            $product=$item->get_product();

            $imageId=$product->get_image_id();
            if(!$tagsFound)
            {
                $tags = get_the_terms($product->get_id(),'product_tag');

                if(is_array($tags)&&count($tags)>=0)
                {
                    $tagList=array_map(function($x){return $x->name;},$tags);


                    $data[]=array(
                        'path'=>'tags',
                        'key'=>'tags',
                        'value'=>json_encode($tagList),
                        'dataType'=>'array',
                        'source'=>'fixed',
                        'fieldType'=>'product',
                        'subTypeData'=>null
                    );
                }
                $tagsFound=true;
            }
            if(!$imageFound&&$imageId!='')
            {
                $imageFound=true;
                $url=wp_get_attachment_image_url($imageId);
                $data[]=array(
                    'path'=>'thumbnail',
                    'key'=>'Thumbnail',
                    'value'=>$url,
                    'dataType'=>'',
                    'source'=>'fixed',
                    'fieldType'=>'product',
                    'subTypeData'=>null
                );



            }

            $this->ProcessMetaValues($product->get_meta_data(), $data, 'product');
            $this->ProcessNormalArray($product->get_data(), $data, array(), 'product');


        }

        return $data;
    }

    private function ProcessNormalArray($data,&$returnArray,$path,$fieldType,$subTypeData=null)
    {
        $ignoreList=array('parent_id','price_include_tax','version','cart_hash','order_key','meta_data','line_items','tax_lines','shipping_lines','fee_lines','coupon_lines');
        foreach($data as $key=>$value)
        {
            $newPath=$path;
            $newPath[]=$key;
            $type='';
            if(in_array($key,$ignoreList))
                continue;
            if(is_array($value))
            {
                $this->ProcessNormalArray($value, $returnArray, $newPath,$fieldType,$subTypeData);
                continue;
            }

            if($value instanceof WC_DateTime)
            {
                $type='WC_DateTime';
                $value=$value->format('F j, Y');
            }

            if($value instanceof WC_Product_Attribute)
            {
                if($value->is_taxonomy())
                {
                    $terms=$value->get_terms();
                    if(is_array($terms))
                    {
                        $termValues=[];
                        foreach ($terms as $currentTerm)
                        {
                            $termValues[]=$currentTerm->name;

                        }
                        $value = implode(', ', $termValues);
                    }
                }else
                {
                    $value = implode(', ', $value->get_options());
                }
            }

            if($value=='')
                continue;

            $found=false;
            foreach($returnArray as $raKey=>$raValue)
            {
                if($raValue['key']==$key&&$raValue['fieldType']==$fieldType)
                    $found=true;
            }


            if($found)
                continue;

            if(!is_scalar($value))
                $value=\json_encode($value);
            $returnArray[]=array(
                'path'=>implode("/", $newPath),
                'key'=>$key,
                'value'=>$value,
                'dataType'=>$type,
                'source'=>'data',
                'fieldType'=>$fieldType,
                'subTypeData'=>$subTypeData
            );
        }

    }

    private function ProcessMetaValues($metaData,&$returnArray,$fieldType,$subTypeData=null){

        /**
         * @var  $key
         * @var WC_Meta_Data $value
         */
        foreach($metaData as $key=>$value)
        {
            $data=$value->get_data();
            if($data['value']=='')
                continue;

            $found=false;
            foreach($returnArray as $raKey=>$raValue)
            {
                if($raValue['key']==$data['key'])
                    $found=true;
            }

            if($found)
                continue;
            if(!is_scalar($data['value']))
            {
                if(is_array($data['value']))
                {
                    $returnArray[]=array(
                        'path'=>$data['key'],
                        'key'=>$data['key'],
                        'value'=>json_encode($data['value']),
                        'dataType'=>'array',
                        'source'=>'meta',
                        'fieldType'=>$fieldType,
                        'subTypeData'=>$subTypeData
                    );
                }
                continue;
            }
            $returnArray[]=array(
                'path'=>$data['key'],
                'key'=>$data['key'],
                'value'=>$data['value'],
                'dataType'=>'',
                'source'=>'meta',
                'fieldType'=>$fieldType,
                'subTypeData'=>$subTypeData
            );
        }
    }

    private function ProcessProductMeta($metaData,&$returnArray,$fieldType)
    {
        /**
         * @var  $key
         * @var WC_Meta_Data $value
         */
        foreach($metaData as $value)
        {

            $data=$value->get_data();
            if($data['value']=='')
                continue;



            $found=false;
            foreach($returnArray as $raKey=>$raValue)
            {
                if($raValue['key']==$data['key'])
                    $found=true;
            }

            if($found)
                continue;

            if(!is_scalar($data['value']))
                continue;
            $returnArray[]=array(
                'path'=>$data['key'],
                'key'=>$data['key'],
                'value'=>$data['value'],
                'dataType'=>'',
                'source'=>'product_meta',
                'fieldType'=>$fieldType
            );
        }
    }

    private function CreateDictionary($values)
    {
        $dictionary=array();

        foreach($values as $value)
        {
            if(is_array($value)&&$this->isAssoc($value))
            {
                foreach($value as $key=>$propertyValue)
                {
                    if(!isset($dictionary[$key])&&is_scalar($propertyValue)&&$propertyValue!='')
                    {
                        $dictionary[]=array(
                            'key'=>$key,
                            'value'=>$propertyValue
                        );
                    }
                }
            }
        }

        return $dictionary;
    }

    private function CheckFields(&$dataToReturn)
    {
        $fields=array();
        if(class_exists('RNEPO'))
        {
            $fields=RNEPO::GetFieldsByOrder($this->order->get_id());
        }



        foreach($fields as $currentField)
        {
            $dataToReturn[]=array(
                'path'=>$currentField->GetFieldName(),
                'display_key'=>$currentField->GetLabel(),
                'key'=>$currentField->GetLabel(),
                'display_type'=>'raw',
                'value'=>$currentField->ToText(),
                'html'=>$currentField->ToHTML(),
                'dataType'=>'',
                'source'=>'RNEPO',
                'fieldType'=>'rnepo',
                'subTypeData'=>null
            );
        }

    }


}