<?php

use rnwcinv\pr\Translation\PDFTranslationBase;

require_once 'wrappers/WrapperBase.php';
abstract class PDFAbstractDataRetriever_deprecated{
    /**
     * @var WrapperBase
     */
    protected $order;

    protected $translationDictionary;
    /**
     * @var PDFTranslationBase
     */
    protected $translator;

    public function __construct($order,$translator)
    {
        $this->translator=$translator;
        if($order!=null)
        {
            $this->order=WrapperBase::WrapOrder($order);
        }

    }


    public static function InitializeTranslationDictionary(){

    }



}
