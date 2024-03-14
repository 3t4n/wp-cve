<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


use rnpdfimporter\PDFLib\core\document\PDFCrossRefSection;

class TrailerJsonItem extends JSONItemBase
{

    public function InternalGetText()
    {
        $str="\n\nstartxref\n";

        $xref=null;
        for($i=count($this->Generator->Items)-1;$i>=0;$i--)
        {
            if($this->Generator->Items[$i] instanceof CrossRefSection)
            {
                $xref = $this->Generator->Items[$i];
                break;
            }
        }
        $str.=$xref->Offset;
        $str.="\n%%EOF";
        $this->Offset=\mb_strlen($str);
        return $str;
    }
}