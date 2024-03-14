<?php


namespace rnpdfimporter\SlateGenerator\SlateTextGenerator;



use rnpdfimporter\core\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rnpdfimporter\core\Loader;

class SlateTextGenerator
{

    /** @var EntryRetrieverBase */
    public $Retriever;
    /** @var Loader */
    public $Loader;
    public function __construct($loader,$retriever)
    {
        $this->Loader=$loader;
        $this->Retriever=$retriever;
    }

    public function GetText($content)
    {
        if(!isset($content->document)||!isset($content->document->nodes))
            return '';

        $text='';

        foreach($content->document->nodes as $paragraph)
        {
            if($paragraph->type!='paragraph')
                continue;
            foreach ($paragraph->nodes as $node)
            {
                switch ($node->object)
                {
                    case 'text':
                        $text .= $this->GetValueFromTextNode($node);
                        break;
                    case 'inline':
                        $text .= $this->GetValueFromFieldNode($node);
                        break;
                }
            }
        }

        return $text;

    }

    private function GetValueFromTextNode($node)
    {
        if(!isset($node->leaves))
            return '';

        $text='';
        foreach($node->leaves as $leaf)
        {
            $text.=$leaf->text;
        }

        return $text;
    }

    private function GetValueFromFieldNode($node)
    {
        if(!$this->Loader->IsPR())
            return 'Not available in free version';

        return $this->Loader->PRLoader->GetFixedFieldValue($this->Retriever,$node);


    }


}