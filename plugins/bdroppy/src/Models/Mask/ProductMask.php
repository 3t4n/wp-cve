<?php namespace BDroppy\Models\Mask;


class ProductMask
{
    private $items;
    public function __construct($items)
    {
        if (is_null($items) || empty($items) || $items == "") return;
        $this->items = $items;
    }

    public function __set($name, $value)
    {
        $this->items[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->items->{$name}))
        {
            return $this->items->{$name};
        }
        else
        {
            return null;
        }
    }

    private function getLang($lang)
    {
        $defLang = $lang;
        switch (substr($lang,0,2))
        {
            case "it" : return 'it_IT';
            case "et" : return 'et_EE';
            case "ru" : return 'ru_RU';
            case "hu" : return 'hu_HU';
            case "sv" : return 'sv_SE';
            case "sk" : return 'sk_SK';
            case "cs" : return 'cs_CZ';
            case "pt" : return 'pt_PT';
            case "pl" : return 'pl_PL';
            case "en" : return 'en_US';
            case "fr" : return 'fr_FR';
            case "de" : return 'de_DE';
            case "es" : return 'es_ES';
            case "ro" : return 'ro_RO';
            case "nl" : return 'nl_NL';
            case "fi" : return 'fi_FI';
            case "bg" : return 'bg_BG';
            case "da" : return 'da_DK';
            case "lt" : return 'lt_LT';
            case "el" : return 'el_GR';
            default : return $defLang;
        }

    }

    public function getTagValue($name,$lang)
    {
        foreach ($this->items->tags as $tag)
        {
            if($tag->name === $name)
            {
                if (isset($tag->value->translations->{$this->getLang($lang)})){
                    return $tag->value->translations->{$this->getLang($lang)};
                }else{
                    return $tag->value->value;
                }
            }
        }
    }

    public function getTagCode($name)
    {
        foreach ($this->items->tags as $tag)
        {
            if($tag->name === $name)
            {
                return $tag->value->value;
            }
        }
    }

    public function getCategory($lang){
        return $this->getTagValue('category',$lang);
    }

    public function getSubCategory($lang){
        return $this->getTagValue('subcategory',$lang);
    }

    public function getBrand($lang){
        return $this->getTagValue('brand',$lang);
    }

    public function getGender($lang){
        return $this->getTagValue('gender',$lang);
    }

    public function getSeason($lang){
        return $this->getTagValue('season',$lang);
    }

    public function getColor($lang){
        return $this->getTagValue('color',$lang);
    }

    public function getName($lang){
        if(!empty($this->getTagValue('productname',$lang)))
        {
            return $this->getTagValue('productname',$lang);
        }else{
            return $this->name;
        }
    }

    public function getImage()
    {
        if(isset($this->pictures[0]->url))
            return 'https://www.mediabd.it/storage-foto/prod/'.$this->pictures[0]->url;
        else
            return BDROPPY_IMG .'no_image.png';
    }

    public function getDescriptions($lang)
    {
        if (isset($this->descriptions->{$this->getLang($lang)}))
        {
            return @$this->descriptions->{$this->getLang($lang)};
        }elseif(isset($this->descriptions->en_US)){
            return @$this->descriptions->en_US;
        }else{
            return '';
        }

    }

    public function isSimpleProduct()
    {
        if ( (string) $this->models[0]->size == 'NOSIZE' ) {
            return true;
        } else {
            return false;
        }
    }

}