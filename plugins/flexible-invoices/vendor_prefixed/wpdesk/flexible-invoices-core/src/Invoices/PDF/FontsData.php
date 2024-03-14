<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF;

/**
 * Defines and return fonts data for mPDF.
 *
 * @package WPDesk\Library\WPCoupons\PDF
 */
final class FontsData
{
    /**
     * @var \string[][]
     */
    private $fonts_data = ['freeserif' => ['R' => 'FreeSerif.ttf', 'I' => 'FreeSerif.ttf', 'B' => 'FreeSerif-Bold.ttf', 'BI' => 'FreeSerif-Bold.ttf', 'useOTL' => 0xff, 'useKashida' => 75]];
    /**
     * @param string $slug Slug of font, used as default font or in css. Must be in lowercase.
     * @param string $name Name of font that can be found in the defined dirs.
     * @param array  $attr Custom attributes.
     *
     * @return $this
     */
    public function set_font(string $slug, string $name, array $attr = []) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\FontsData
    {
        $this->fonts_data[$slug] = ['R' => $name . '.ttf', 'I' => $name . '-Italic.ttf', 'B' => $name . '-Bold.ttf', 'BI' => $name . '-BoldItalic.ttf'];
        if (!empty($attr)) {
            $this->fonts_data[$slug] = $attr;
        }
        return $this;
    }
    /**
     * @param string $slug Slug of font, used as default font or in css. Must be in lowercase.
     * @param string $name Name of font that can be found in the defined dirs.
     * @param array  $attr Custom attributes.
     *
     * @return $this
     */
    public function set_font_without_italic(string $slug, string $name, array $attr = []) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\FontsData
    {
        $this->fonts_data[$slug] = ['R' => $name . '.ttf', 'I' => $name . '.ttf', 'B' => $name . '-Bold.ttf', 'BI' => $name . '-Bold.ttf'];
        if (!empty($attr)) {
            $this->fonts_data[$slug] = $attr;
        }
        return $this;
    }
    /**
     * @param string $slug Slug of font, used as default font or in css. Must be in lowercase.
     * @param string $name Name of font that can be found in the defined dirs.
     * @param array  $attr Custom attributes.
     *
     * @return $this
     */
    public function set_font_without_bold(string $slug, string $name, array $attr = []) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\FontsData
    {
        $this->fonts_data[$slug] = ['R' => $name . '.ttf', 'I' => $name . '-Italic.ttf', 'B' => $name . '.ttf', 'BI' => $name . '-Italic.ttf'];
        if (!empty($attr)) {
            $this->fonts_data[$slug] = $attr;
        }
        return $this;
    }
    /**
     * @param string $slug Slug of font, used as default font or in css. Must be in lowercase.
     * @param string $name Name of font that can be found in the defined dirs.
     * @param array  $attr Custom attributes.
     *
     * @return $this
     */
    public function set_font_without_bold_italic(string $slug, string $name, array $attr = []) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\PDF\FontsData
    {
        $this->fonts_data[$slug] = ['R' => $name . '.ttf', 'I' => $name . '.ttf', 'B' => $name . '.ttf', 'BI' => $name . '.ttf'];
        if (!empty($attr)) {
            $this->fonts_data[$slug] = $attr;
        }
        return $this;
    }
    /**
     * @return \string[][]
     */
    public function get()
    {
        return $this->fonts_data;
    }
}
