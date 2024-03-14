<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;



use rednaoformpdfbuilder\Utils\Sanitizer;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFFormImageItem extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $label=$this->GetPropertyValue('Label');
        $value='';

        $style='standard';
        if(isset($this->options->Style))
            $style=$this->options->Style;
        $paths=[];
        if($this->entryRetriever==null)
            $paths=[$this->Loader->DIR.'images/temporalImage.png'];
        else {
            $field = $this->entryRetriever->GetValueById($this->options->FieldId, $style);
            $url='';
            if($field!=null)
            {
                if(isset($field->URL))
                    $url=$field->URL;
                if(isset($field->Value)&&filter_var($field->Value,FILTER_VALIDATE_URL))
                    $url=$field->Value;
            }

            if($url!='')
            {
                $urls=$url;
                $urls=preg_split('/\r\n|\r|\n/', $urls);
                foreach($urls as $currentUrl)
                    if ($this->EndsWith($currentUrl, '.jpg') || $this->EndsWith($currentUrl, '.jpeg') || $this->EndsWith($currentUrl, '.png'))
                    {
                        $paths[]=$currentUrl;
                    }


            }
        }



        $width=$this->GetStyleValue('Width');
        $height=$this->GetStyleValue('Height');

        if(\strpos($width,'px')===false)
            $width.='px';
        if(\strpos($height,'px')===false)
            $height.='px';

        if($this->entryRetriever==null)
        {
            $html= '<img ' . $this->CreateStyleString(array(
                    'max-width' => $width,
                    'max-height' => $height

                )) . ' src="' . htmlspecialchars($paths[0]) . '"/>';
        }else {

            if (count($paths) == 0)
                return '';

            $html = '';
            foreach ($paths as $currentImage) {

                $html .= '<img ' . $this->CreateStyleString(array(
                        'max-width' => $width,
                        'max-height' => $height

                    )) . ' src="' . htmlspecialchars($currentImage) . '"/>';
            }

        }
        return '<div style="text-align:center;width: '.$width.'">'. $html.'</div>';


    }

    public function EndsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }




}