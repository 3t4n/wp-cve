<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF;

/**
 * Defines config for mPDF library.
 *
 * @package WPDesk\Library\WPCoupons\PDF
 */
final class Config
{
    /**
     * @var array
     */
    private $config = ['mode' => 'utf-8', 'format' => 'A4', 'orientation' => 'P', 'autoLangToFont' => \true, 'img_dpi' => 72, 'dpi' => 72, 'tempDir' => '', 'fontDir' => [], 'default_font' => 'dejavusanscondensed', 'default_font_size' => 9, 'fontdata' => [], 'margin_left' => 0, 'margin_right' => 0, 'margin_top' => 0, 'margin_bottom' => 0, 'margin_header' => 0, 'margin_footer' => 0];
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_mode(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['mode'] = $value;
        return $this;
    }
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_format(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['format'] = $value;
        return $this;
    }
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_orientation(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['orientation'] = $value;
        return $this;
    }
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_default_font(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['default_font'] = $value;
        return $this;
    }
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_default_font_size(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['default_font_size'] = $value;
        return $this;
    }
    /**
     * @param array $value
     *
     * @return Config
     */
    public function set_font_data(array $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['fontdata'] = $value;
        return $this;
    }
    /**
     * @param array $value
     *
     * @return Config
     */
    public function set_font_dir(array $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        foreach ($value as $dir) {
            $this->config['fontDir'][] = $dir;
        }
        return $this;
    }
    /**
     * @param string $value
     *
     * @return Config
     */
    public function set_temp_dir(string $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['tempDir'] = $value;
        return $this;
    }
    /**
     * @param bool $value
     *
     * @return Config
     */
    public function set_auto_script_to_lang(bool $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['autoScriptToLang'] = $value;
        return $this;
    }
    /**
     * @param bool $value
     *
     * @return Config
     */
    public function set_auto_lang_to_font(bool $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['autoLangToFont'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_img_dpi(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['img_dpi'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_dpi(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['dpi'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_left(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_left'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_right(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_right'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_top(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_top'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_bottom(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_bottom'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_header(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_header'] = $value;
        return $this;
    }
    /**
     * @param int $value
     *
     * @return Config
     */
    public function set_margin_footer(int $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config['margin_footer'] = $value;
        return $this;
    }
    /**
     * @param string $name  Option name.
     * @param mixed  $value Option value.
     *
     * @return Config
     */
    public function set_custom(string $name, $value) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\Config
    {
        $this->config[$name] = $value;
        return $this;
    }
    /**
     * @return array
     */
    public function get()
    {
        return $this->config;
    }
}
