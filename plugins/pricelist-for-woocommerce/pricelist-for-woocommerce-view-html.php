<?php
if (!class_exists('pricelist_wc_generate_html')) {
    trait pricelist_wc_generate_html_trait {
        public function generateOutput() {
            parent::generateOutput();
            return $this->output;
        }
    }
    
    class pricelist_wc_generate_html extends pricelist_wc_generate_output {
        use pricelist_wc_generate_html_trait;
        
        public function __construct($controller) {
            parent::__construct($controller);
            $this->allowLocalImages = false;
            $this->forceLocalImages = false;
        }
    }
}
?>