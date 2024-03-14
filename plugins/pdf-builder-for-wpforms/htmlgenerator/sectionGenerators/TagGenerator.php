<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/23/2018
 * Time: 6:36 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;


class TagGenerator
{
    public function StartTag($tagName,$className,$styles,$attributes)
    {
        $styleStr='';
        if($styles!=null)
            foreach($styles as $styleName=>$value){
                if($value==null)
                    continue;
                $styleStr.=$styleName.':'.$value.';';
            }
        $attributesTxt='';
        if($attributes!=null)
        {
            foreach ($attributes as $name => $value)
            {
                $attributesTxt .= $name . '="' . $value . '" ';
            }
        }

        return ' <'.$tagName.' class="'.$className.'" style="'.$styleStr.'" '.$attributesTxt.'>';
    }

}