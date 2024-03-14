<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/6/2018
 * Time: 11:54 AM
 */

namespace rnwcinv\hooks\PDFCreated;


use rnDompdf\Dompdf;
use RednaoPDFGenerator;

class PDFCreatedHook
{
    /**
     * @var RednaoPDFGenerator
     */
    private $generator;

    /** @var Dompdf */


    public function __construct(RednaoPDFGenerator $generator)
    {

        $this->generator = $generator;
    }

    public function GetPDFOutput()
    {
        return $this->generator->GetOutput();
    }

    public function GetExtensionOptions($extensionId)
    {
        return $this->generator->GetExtensionOptions($extensionId);
    }

    public function GetInvoiceTemplateId(){
        return $this->generator->GetInvoiceTemplateId();
    }

    public function GetPDFName(){
        return $this->generator->GetFileName();
    }


}