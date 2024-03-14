<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFOrderNotesField extends PDFSubFieldBase {
    public function GetTestFieldValue()
    {
        return "<p>* My order note</p>";
    }

    public function GetWCFieldName()
    {
        return "customer_note";
    }

    public function GetRealFieldValue($format=''){
        $notes=wc_get_order_notes([
            'order_id'=>$this->orderValueRetriever->order->get_id(),
            'type'=>$this->GetFieldOptions('orderType')
        ]);

        $list="<div>";
        foreach($notes as $currentNote)
        {
            $list.="<p style='margin-bottom: 5px'>* ".esc_html($currentNote->content)."</p>";
        }
        $list.="</div>";
        return $list;
    }
}