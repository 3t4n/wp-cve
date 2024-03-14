<?php
/**
 * QR code generating model.
 */

defined('ABSPATH') || exit;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;

if (!class_exists('WQM_Qr_Code_Generator')) {

    class WQM_Qr_Code_Generator
    {

        /**
         * @var array Of QR code parameters and fields
         */
        private $params;

        /**
         * WQM_Qr_Code_Generator constructor.
         *
         * @param array $params
         */
        public function __construct($params)
        {
            $this->params = $params;
        }

        /**
         * Generate QR code
         *
         * @param bool $just_code
         *
         * @return bool|false|string
         */
        public function build($just_code = false)
        {
            if (empty($this->params['wqm_type'])) {
                return false;
            }
            $type = $this->params['wqm_type'];
            switch ($type) {
                case 'mecard':
                    return $this->generate_mecard($just_code);
                case 'vcard':
                    return $this->generate_vcard($just_code);
                default:
                    return false;
            }
        }

        /**
         * Generate MeCard QR code
         *
         * @param $just_code
         *
         * @return false|string
         */
        private function generate_mecard($just_code)
        {
            $fields = $this->assign_fields($just_code);
            $text = 'MECARD:' . implode(';', $fields) . ';';
	        $text = str_replace(["\r\n", "\r"], "\n", $text);

            if ($just_code) {
                return $text;
            }

            $qr_code = new QrCode($text);

            return $this->generate_qr_code($qr_code);
        }

        /**
         * Generate vCard QR code
         *
         * @param $just_code
         *
         * @return false|string
         */
        private function generate_vcard($just_code)
        {
            $fields = $this->assign_fields($just_code);
            $fields = implode("\n", $fields);
            $text = <<<CARD
BEGIN:VCARD
VERSION:3.0
{$fields}
END:VCARD
CARD;

	        $text = str_replace(["\r\n", "\r"], "\n", $text);

            if ($just_code) {
                return $text;
            }

            $qr_code = new QrCode($text);

            return $this->generate_qr_code($qr_code);
        }

        /**
         * Prepare QR code settings
         *
         * @param QrCode $qr_code
         */
        private function assign_params(QrCode $qr_code)
        {

            if (!empty($this->params['wqm_size'])) {
                $qr_code->setSize($this->params['wqm_size']);
            }

            if (empty($this->params['wqm_img_type'])) {
                $this->params['wqm_img_type'] = 'png';
            }
            $qr_code->setWriterByName($this->params['wqm_img_type']);

            if (isset($this->params['wqm_margin'])) {
                $qr_code->setMargin($this->params['wqm_margin']);
            }

            if (!empty($this->params['wqm_encoding'])) {
                $qr_code->setEncoding($this->params['wqm_encoding']);
            }

            if (!empty($this->params['wqm_correction_level']) && in_array($this->params['wqm_correction_level'], array(
                    'LOW',
                    'MEDIUM',
                    'QUARTILE',
                    'HIGH'
                ))) {
                $qr_code->setErrorCorrectionLevel(ErrorCorrectionLevel::{$this->params['wqm_correction_level']}());
            }

            if (!empty($this->params['wqm_color_rgba'])) {
                $rgb = array_map('trim', explode(',', $this->params['wqm_color_rgba']));
                if (4 == count($rgb)) {
                    $qr_code->setForegroundColor(['r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => $rgb[3]]);
                }
                if (3 == count($rgb)) {
                    $qr_code->setForegroundColor(['r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => 1]);
                }
            }

            if (!empty($this->params['wqm_bg_rgba'])) {
                $rgb = array_map('trim', explode(',', $this->params['wqm_bg_rgba']));
                if (4 == count($rgb)) {
                    $qr_code->setBackgroundColor(['r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => $rgb[3]]);
                }
                if (3 == count($rgb)) {
                    $qr_code->setBackgroundColor(['r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => 1]);
                }
            }

            if (!empty($this->params['wqm_label'])) {
                $qr_code->setLabel($this->params['wqm_label'], 16, null, LabelAlignment::CENTER());
            }

            if (!empty($this->params['wqm_logo_path']) && file_exists($this->params['wqm_logo_path'])) {
                try {
                    $qr_code->setLogoPath($this->params['wqm_logo_path']);
                } catch (Exception $e) {
                    WQM_Common::print_error($e);
                    $this->params['wqm_logo_path'] = false;
                }

            }

            $qr_code->setValidateResult(false);
            $qr_code->setWriterOptions(['exclude_xml_declaration' => true]);

            if (empty($this->params['wqm_logo_width'])) {
                $this->params['wqm_logo_width'] = '10%';
            }

            if (empty($this->params['wqm_logo_height'])) {
                $this->params['wqm_logo_height'] = '10%';
            }

            if (!empty($this->params['wqm_logo_path'])) {
                $data = $qr_code->getData();

                $logo_width = $this->params['wqm_logo_width'];
                $logo_height = $this->params['wqm_logo_height'];

                if (false !== strpos($logo_width, '%')) { // if size set as percent
                    $logo_width = WQM_Common::clear_digits($logo_width) * intval($data['inner_width']) / 100;
                }

                if (false !== strpos($logo_height, '%')) { // if size set as percent
                    $logo_height = WQM_Common::clear_digits($logo_height) * intval($data['inner_height']) / 100;
                }

                $qr_code->setLogoSize(intval($logo_width), intval($logo_height));
            }

            if (!empty($this->params['wqm_bgcolor'])) {
                $colors = [];
                if (count(explode(',', $this->params['wqm_bgcolor'],)) < 4) {
                    $this->params['wqm_bgcolor'] = str_replace(')', ',1)', $this->params['wqm_bgcolor']);
                    $this->params['wqm_bgcolor'] = str_replace('rgb(', 'rgba(', $this->params['wqm_bgcolor']);
                }
                preg_match('@rgba\(([\d]+),([\d]+),([\d]+),([\d\.]+)\)@si', $this->params['wqm_bgcolor'], $colors);
                $qr_code->setBackgroundColor(['r' => $colors[1], 'g' => $colors[2], 'b' => $colors[3], 'a' => $colors[4]]);
            }
            if (!empty($this->params['wqm_fgcolor'])) {
                $colors = str_replace('#', '', $this->params['wqm_fgcolor']);
                $qr_code->setForegroundColor(['r' => hexdec(substr($colors, 0, 2)), 'g' => hexdec(substr($colors, 2, 2)), 'b' => hexdec(substr($colors, 2, 2))]);
            }
        }

        /**
         * Generate qr-code image
         *
         * @param QrCode $code
         *
         * @return string|false image path or false on error
         */
        public function generate_qr_code(QrCode $code)
        {
            $this->assign_params($code);
            $code = apply_filters('wqm_generate_qr_code_before', $code);

            // Save it to a file
            if ($this->params['wqm_is_static']) {
                $save_to = $this->params['wqm_is_static'];
            } else {
                $save_to = tempnam(sys_get_temp_dir(), 'qr-');
                if (!$save_to) {
                    $save_to = tempnam(session_save_path(), 'qr-');
                }
                $save_to .= '.' . $this->params['wqm_img_type'];
            }

            try {
                $code->writeFile($save_to);
            } catch (Exception $e) {
                WQM_Common::print_error($e);
            }

            return $save_to;
        }

        /**
         * Prepare QR code fields to build Xcard
         *
         * @param bool $just_code
         *
         * @return array
         */
        private function assign_fields(bool $just_code = false): array
        {
            error_reporting(0);
            $fields = array();
            foreach ($this->params as $name => $field) {
                if (in_array($name, WQM_QR_Code_Type::$custom_post_fields)) {
                    $name = str_replace('_', '-', str_replace('WQM_', '', strtoupper($name)));
                    if ($field == '') {
                        continue;
                    }
                    if ('PHOTO' == $name) {
                        $field = $this->params['wqm_photo_path'];
                    }
                    if ('ADR' == $name) {
                        foreach ($field as $adr) {
                            if (empty($adr)) {
                                continue;
                            }
                            if ('vcard' == $this->params['wqm_type']) {
                                $name = $this->patchVcard($name);
                                $adr = quoted_printable_encode($adr);
                            }

                            $fields[] = $name . ':' . $adr;
                        }
                    }
                    if ('TEL' == $name) {
                        foreach ($field as $tel) {
                            if (empty($tel['content'])) {
                                continue;
                            }

                            if (empty($tel['type'])) {
                                $fields[] = "{$name}:{$tel['content']}";
                            } else {
                                $fields[] = 'TEL;TYPE=' . implode(',', $tel['type']) . ':' . $tel['content'];
                            }
                        }
                    }
                    if ('URL' == $name) {
                        $pref = count($field) == 1;

                        foreach ($field as $m) {
                            if (empty($m)) {
                                continue;
                            }

                            if (!$pref) {
                                $pref = true;
                                $fields[] = 'URL;TYPE=pref:' . $m;
                            } else {
                                $fields[] = "{$name}:{$m}";
                            }
                        }
                    }
                    if ('EMAIL' == $name) {
                        $pref = count($field) == 1;

                        foreach ($field as $m) {
                            if (empty($m)) {
                                continue;
                            }

                            if (!$pref) {
                                $pref = true;
                                $fields[] = 'EMAIL;TYPE=pref:' . $m;
                            } else {
                                $fields[] = "{$name}:{$m}";
                            }
                        }
                    }
                    if ('vcard' == $this->params['wqm_type']) {
                        if (!is_array($field)) {
                            $name = $this->patchVcard($name);
                            $field = quoted_printable_encode($field);
                        }
                    }
                    if ('vcard' == $this->params['wqm_type'] && //for download vCard file
                        $just_code &&
                        in_array($name, ['PHOTO;VALUE=uri', 'LOGO;VALUE=uri'])
                    ) {
                            $img = file_get_contents($field);
                            $info = getimagesize($field);
                            $ext = explode('/', $info['mime'])[1];
                            $code = base64_encode($img);
                            $subname = str_replace(';VALUE=uri', ';ENCODING=b;TYPE=' . strtoupper($ext), $name);
                            if (strlen($code) > 0) {
                                $fields[] = "{$subname}:{$code}";
                            }
                    } else {
                        if (!is_array($field) && !empty($field)) {
                            $fields[] = "{$name}:{$field}";
                        }
                    }
                }
            }

            return $fields;
        }

        /**
         * Update fields for vCard format
         *
         * @param $name string
         *
         * @return string
         */
        private function patchVcard(string $name): string
        {
            switch ($name) {
                case 'LOGO':
                case 'PHOTO':
                    return $name .= ';VALUE=uri';
                case 'N':
                case 'NICKNAME':
                case 'ADR':
                case 'TITLE':
                case 'ORG':
                case 'NOTE':
                    return $name .= ';CHARSET=utf-8;ENCODING=QUOTED-PRINTABLE';
                default:
                    return $name;
            }
        }
    }
}