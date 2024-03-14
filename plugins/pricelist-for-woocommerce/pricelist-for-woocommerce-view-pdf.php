<?php
if (!class_exists('pricelist_wc_generate_pdf')) {
    trait pricelist_wc_generate_pdf_trait {
        protected $obj_pdf;
        
        private function writeHTML($html = null) {
            if ($html === null) {
                $html = $this->output;
                $this->output = '';
            }
            if (!empty($html)) $this->obj_pdf->writeHTML($html);
        }
        
        protected function newPage($bookmark = false) {
            $this->writeHTML();
            $this->obj_pdf->AddPage();
            if ($bookmark) $this->obj_pdf->Bookmark($bookmark, 0, 0, '', 'B', array(0,64,128));
        }
        
        public function generateOutput() {
            $options = &$this->options;
            $this->obj_pdf = new pricelist_wc_pdf($options);
            $this->outputter->setWidth($this->obj_pdf);
            
            parent::generateOutput();
            
            $this->writeHTML();
            $this->obj_pdf->Output($options['company'] .' '. $options['name'] .' generated on '.date('d-m-Y_H-i-s').'.pdf', $options['download'] ? 'D' : 'I');
            return $this->output;
        }
        public static function generatePDF($controller) {
            set_time_limit(0);
            ini_set( 'memory_limit', -1);
            
            $obj = new self($controller);
            $output = $obj->generateOutput();
            if ($limit = ini_get('max_execution_time'))
                set_time_limit($limit);

            return $output;
        }
    }
    
    class pricelist_wc_generate_pdf extends pricelist_wc_generate_output {
        use pricelist_wc_generate_pdf_trait;
        
        public function __construct($controller) {
            parent::__construct($controller);
            $this->allowLocalImages = true;
            $this->forceLocalImages = $this->options['local_images'];
        }
    }
}

if (!class_exists("TCPDF")) {
    global $pricelist_plugin;
    require_once $pricelist_plugin->include_file('tcpdf', false);
    
    class pricelist_wc_pdf extends TCPDF{
        protected $options;
        
        public function __construct($options) {
            parent::__construct('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $this->options = $options;
            $directory = sprintf('%s/libraries/tcpdf/fonts', dirname(__FILE__));
            
            $pdfFont = new TCPDF_FONTS('P', 'mm', 'A4', true, 'UTF-8', false);
            $newFont = $pdfFont->addTTFfont("$directory/NotoSans-Regular.ttf", 'TrueTypeUnicode');
            
            $this->SetCreator($options['plugin_declaration']);
            $this->SetAuthor($options['company']);
            $this->SetSubject($options['name'].' for ' . $options['company']);
            
            $this->SetTitle($options['company'].' '.$options['name'].' generated on: ' .date('d-m-Y_H-i-s'));
            $this->setHeaderFont(Array($newFont, '', PDF_FONT_SIZE_MAIN));  
            $this->setFooterFont(Array($newFont, '', PDF_FONT_SIZE_DATA));  
            $this->SetFooterMargin(PDF_MARGIN_FOOTER);
            $this->SetHeaderMargin(15);
            $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);   
            $this->SetAutoPageBreak(TRUE, 10);
            $this->setFontSubsetting(true);
            $this->setFont($newFont, '', 11, '', false);
            $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $this->SetAllowLocalFiles(true);
            $this->SetSkipImageChecks(false);
        }
        
        public function dateBuilder($date) {
            switch($date){
                case 0:
                    return '';
                case 1:
                    return ' '.date('j');
                case 2:
                    return ' '.ucfirst(wp_date('F'));
                case 3:
                    return ' '.date('Y');
            }
        }

        public function Header() {
            $options = $this->options;
            $this->setFontSubsetting(true);
            $this->Cell(0, 25, $options['company'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $this->Cell(0, 25, $options['name'] . $this->dateBuilder($options['date1']).$this->dateBuilder($options['date2']).$this->dateBuilder($options['date3']), 0, false, 'R', 0, '', 0, false, 'M', 'M');
        }
        
        public function Footer() {
            $options = $this->options;
            $this->SetY(-15);
            $this->setFontSubsetting(true);
            $this->Cell(0, 15, __($options['page'], get_locale()).' '.$this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
}
?>