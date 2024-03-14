<?php


namespace rnpdfimporter\PDFLib\core\integration;


use ArrayObject;

class ReferenceArray extends ArrayObject
{
    public static function withSize($size,$fillWith=null)
    {
        $array=new ReferenceArray();
        for($i=0;$i<$size;$i++)
        {
            $array[]=$fillWith;
        }
        return $array;
    }

    public static function createFromArray($array)
    {
        $array=new ReferenceArray();
        foreach ($array as $value)
            $array[]=$value;

        return $array;
    }

    public function length(){
        return count($this);
    }

    public function push($item)
    {

        $this[]=$item;
    }

    public function Includes($item){
        return \in_array($item,(array)$this);
    }

    public function set($array)
    {
        for($i=0;$i<count($array);$i++)
            $this[$i]=$array[$i];
    }

    public function subarray($start,$end)
    {
        $end-=1;

        $ref=ReferenceArray::withSize($end-$start);
        for($i=$start;$i=$end;$i++)
        {
            $ref[]=&$this[$i];
        }

        return $ref;


    }

}