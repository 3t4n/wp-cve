<?php

if (!class_exists('pricelist_wc_controller')) {

    class pricelist_wc_controller {
        protected $data = null;
        protected $options = null;
        
        public function __construct() {
            global $pricelist_plugin;
            require_once $pricelist_plugin->include_file('profiler');
            require_once $pricelist_plugin->include_file('generate_output');
            require_once $pricelist_plugin->include_file('data');
        }
        
        public function getData() {
            global $pricelist_plugin;
            if ($this->data === null) {
                $this->data = $pricelist_plugin->instantiate('data');
                $this->data = $this->data->getData($this->getOptions());
            }
            return $this->data;
        }
        
        public function &getOptions() {
            return $this->options;
        }

        public static function pricelist_scripts() {
        }

        public function registerShortcode() {
            add_shortcode('pricelist', array(&$this, 'pricelist_wc_shortcode'));
        }
        
        public function pricelist_wc_shortcode($args) {
            pricelist_wc_option::Load('shortcode', $args);
            $options = $this->options = pricelist_wc_option::Values();
            $this->options['download_pdf'] = $options['output'] === 'dl' ? 'Download PDF' : 'View PDF';
            
            if ($options['output'] == 'pdf' || $options['output'] == 'dl')
            {
                $result = new pricelist_wc_generate_pdf_button($this);
            }
            elseif ($options['output'] == 'html')
            {
                $result = new pricelist_wc_generate_html($this);
            } else return;
            
            return $result->generateOutput();
        }
        
        public function generatePDF() {
            global $pricelist_plugin;
            pricelist_wc_option::Load('get', $_REQUEST);
            $this->options = pricelist_wc_option::Values();
            $options = &$this->options;
            $options['th'] = $options['table_header_color'];
            $options['td'] = $options['table_color'];
            $options['download'] = $options['output'] === 'dl';
            $pricelist_plugin->include_class('generate_pdf')::generatePDF($this);
        }
    }
}
?>