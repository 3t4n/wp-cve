<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

class HQRentalsBaseShortcode
{
    protected function setParams($params): void
    {
        foreach ($this->params as $key => $value) {
            $this->params[$key] = !empty($params[$key]) ? $params[$key] : $value;
        }
    }
    protected function getLabelForFeature($feature): string
    {
        try {
            $locale = get_locale();
            $lang = explode('_', $locale)[0];
            return !empty($feature->label_for_website->{$lang}) ? $feature->label_for_website->{$lang} : $feature->label;
        } catch (\Throwable $e) {
            return $feature->label;
        }
    }
}
