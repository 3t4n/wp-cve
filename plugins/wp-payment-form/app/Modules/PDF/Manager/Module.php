<?php

namespace WPPayForm\App\Modules\PDF\Manager;

class Module
{
    public static $loaded = false;

    public function __construct()
    {
        if (!static::$loaded) {
            static::$loaded = true;
            $this->boot();
        }
    }

    protected function boot()
    {
        new \FluentPdf\Classes\Controller\PdfManager(WPPayFormPdfBuilder::class);
    }
}