<?php
/*
* @Author 		yemlihakorkmaz
* Copyright: 	yemlihakorkmaz
*/



if (!defined('ABSPATH')) exit;  // if direct access

class korkmaz_contract_dompdf
{

    public $dompdf = null;
    public $options = null;
    public function __construct()
    {
        $path = plugin_dir_path(__DIR__) . 'assets/';
        include_once($path . 'dompdf/autoload.inc.php');
        $this->dompdf = new Dompdf\Dompdf();
        $this->options = $this->dompdf->getOptions();
    }

    public function generate_pdf($html, $order_id = 0, $save_pdf = true, $sozlesmeismi = "korkmaz")
    {
        $upload_dir = WP_CONTENT_DIR . '/uploads/korkmazsozlesme';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0700);
        }


        if ($order_id == 0) return new WP_Error('invalid_data', __('hata var 864346', 'korkmaz_contract'));

        $this->dompdf->tempDir = $upload_dir;
        $this->options->setDefaultFont('dejavu sans');
        $this->options->setIsHtml5ParserEnabled(true);
        $this->options->setIsFontSubsettingEnabled(true);
        $this->options->setIsRemoteEnabled(true);
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');


        // Render the HTML as PDF
        $this->dompdf->render();

        $this->dompdf->setOptions($this->options);


        $sozlesme_url = sprintf('%s/uploads/korkmazsozlesme/%s-contract-%s-%s.pdf', WP_CONTENT_DIR, $sozlesmeismi, $order_id, time());
        if ($save_pdf == true) {
            @file_put_contents($sozlesme_url, $this->dompdf->output());
        }

        return $sozlesme_url;
    }
}
