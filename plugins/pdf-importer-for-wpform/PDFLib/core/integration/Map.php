<?php


namespace rnpdfimporter\PDFLib\core\integration;


class Map
{
    /** @var DictionaryItem[] */
    private $dictionary;

    public function __construct()
    {
        $this->dictionary=new ReferenceArray();
    }

    public function has($key)
    {
        foreach($this->dictionary as $item)
            if($item->key==$key)
                return true;
        return false;
    }

    public function get($key){
        foreach($this->dictionary as $item)
            if($item->key==$key)
                return $item->value;
        return null;
    }

    public function set($key,$value)
    {
        $item=$this->get($key);
        if($item==null)
        {
            $item=new DictionaryItem($key,$value);
            $this->dictionary[]=$item;
        }

        $item->value=$value;
    }

    public function delete($ref)
    {
        for($i=0;$i<count($this->dictionary);$i++)
        {
            if($this->dictionary[$i]->key==$ref)
            {
                unset($this->dictionary[$i]);
                $this->dictionary=\array_values($this->dictionary);
            }
        }
    }

    public function entries()
    {
        return $this->dictionary;
    }

    public function _clone(){
        $map=new Map();
        $map->dictionary=$this->dictionary->getArrayCopy();
        return $map;
    }
}

class DictionaryItem{
    public $key;
    public $value;

    public function __construct($key,$value)
    {
        $this->key=$key;
        $this->value=$value;
    }


}