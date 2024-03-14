<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;


use rednaoformpdfbuilder\pr\Manager\TagManager;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFHtml extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $value= $this->options->HTML;

        if($this->Loader->IsPR())
        {
            $tagManager=new TagManager($this->entryRetriever);
            $value=$tagManager->Process($value);
        }

        return $value;

    }
}