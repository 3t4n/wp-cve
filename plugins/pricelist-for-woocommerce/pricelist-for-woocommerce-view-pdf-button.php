<?php
if (!class_exists('pricelist_wc_generate_pdf_button')) {
    class pricelist_wc_generate_pdf_button extends pricelist_wc_generate_output {
        public function __construct($controller) {
            parent::__construct($controller);
            $this->allowLocalImages = true;
            $this->forceLocalImages = false;
        }
        public function generateOutput() {
            $this->outputter->openPriceList();
            $this->outputter->formFields();
            $this->outputter->closePriceList();
            return $this->output;
        }
    }
}
?>