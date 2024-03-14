<?php
if (!class_exists('pricelist_wc_outputter_pdf_button')) {
    global $pricelist_plugin;
    require_once $pricelist_plugin->include_file('outputter');
    trait pricelist_wc_outputter_pdf_button_trait {
        protected $cssClass = '';
        
        protected function style() {
            $this->output .= '<style>
            #pricelist-pdf-btn {
                width:200px;
                font-size: 15px;
                line-height: normal;
                padding: 15px;
                text-transform: none;
            }

            #pricelist-pdf-btn:focus {
                text-decoration: unset;
                outline: none;
            }

            #pricelist-pdf-btn:hover {
                text-decoration: unset;
            }
            </style>';
        }
        
        protected function getHiddenOptions() {
            return ['company', 'table_header_color', 'table_color', 'description', 'short_description', 'category_description', 'category_image', 'product_image', 'name', 'page', 'date1', 'date2', 'date3', 'output'];
        }
        
        public function formFields() {
            $options = pricelist_wc_option::Get(...$this->getHiddenOptions());
            
            $this->output .= '<input type="hidden" name="action" value="generate_pdf"/>';
            
            foreach ($options as $option) {
                $this->output .= '<input type="hidden" name="'.$option->get_name.'" value="' . $option->strValue() .'"/>';
            }
        }
        
        public function openPriceList() {
            $this->declaration();
            $this->style();
            $this->output .= '<div class="'.$this->cssClass.'">';
            $this->openForm();
        }
        
        protected function openForm() {
            $action = admin_url('admin-post.php');
            $this->output .= '<form method="post" target="_blank" action="'.$action.'">';
        }
        
        public function closePriceList() {
            $this->output .= '<input type="submit" id="pricelist-pdf-btn" class="button button-primary '.$this->cssClass.'" value="'.$this->options['download_pdf'].'"/>';
            $this->output .= '</form></div>';
        }
    }

    class pricelist_wc_outputter_pdf_button extends pricelist_wc_outputter {
        use pricelist_wc_outputter_pdf_button_trait;
    }
}
?>