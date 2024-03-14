<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */
namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;

class PDFLink extends PDFFieldBase
{


    protected function InternalGetHTML()
    {
        $text=$this->GetPropertyValue('Text');
        $url=$this->GetPropertyValue('URL');

        return '<a target="_blank" href="'.esc_attr($url).'">'.esc_html($text).'</a>';

    }
}