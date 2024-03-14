<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


class LinkFormatter extends PHPFormatterBase
{


    private $url;
    private $title;
    private $names;
    private $forceLinks;
    public function __construct($url, $title,$names=[],$field=null,$forceLinks=false)
    {
        parent::__construct($field);
        $this->forceLinks=$forceLinks;
        $this->url = $url;
        $this->title = $title;
        $this->names=$names;
    }


    public function __toString()
    {

        $urls=preg_split('/\r\n|\r|\n/', $this->url);

        if(is_array($urls))
        {
            $text='';
            $maxWidth='400px';
            if(count($urls)==1)
                $maxWidth='100%';

            for($i=0;$i<count($urls);$i++)
            {
                $currentUrl=$urls[$i];
                if (!\filter_var($currentUrl, \FILTER_VALIDATE_URL)&&!file_exists($currentUrl))
                    continue;

                if (!$this->forceLinks&&($this->endsWith($currentUrl, '.jpg') || $this->endsWith($currentUrl, '.jpeg') || $this->endsWith($currentUrl, '.png')))
                {
                    $currentUrl=$this->ToLocal($currentUrl);

                    $text .= '<img style="max-width:'.$maxWidth.';display:inline-block;" src="' . $currentUrl . '"/>';
                } else
                {
                    $linkName=$this->title!=''?$this->title:$currentUrl;
                    if(isset($this->names[$i]))
                        $linkName=$this->names[$i];
                    $text .= '<div class="fileUploadItem" ><a target="_blank" href="' . $currentUrl . '">' . \esc_html($linkName) . '</a></div>';
                }

            }
            return $text;
        }


        if($this->endsWith($this->url,'.jpg')||$this->endsWith($this->url,'.jpeg')||$this->endsWith($this->url,'.png'))
        {
            return '<img style="display:inline-block;max-width:400px;" src="'.esc_attr($this->url).'"/>';
        }

        return '<a target="_blank" href="'.$this->url.'">'.\esc_html($this->title).'</a>';
    }

    public function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }


    public function IsEmpty(){
        return trim($this->url)=='';
    }


    public function ToText()
    {
        return $this->url;
    }

    private function ToLocal($fileUrl)
    {
        $upload=wp_upload_dir();
        $url=$upload['baseurl'];

        if(strpos($fileUrl,$url)!==0)
            return $fileUrl;

        $relativeURL=str_replace($url,'',$fileUrl);
        $relativePath=parse_url($relativeURL);

        if($relativePath==null||!isset($relativePath['path']))
            return $fileUrl;

        $absolutePath=realpath($upload['basedir'].DIRECTORY_SEPARATOR.$relativePath['path']);
        if($absolutePath==false||strpos($absolutePath,$upload['baseurl'])!==false)
            return $fileUrl;

        if(!file_exists($absolutePath))
            return $fileUrl;

        $localURL=$absolutePath;
        $imageSize=getimagesize($localURL);
        if($imageSize==null)
            return $fileUrl;

        return $localURL;




    }
}