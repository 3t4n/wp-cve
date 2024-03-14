<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

class HQBaseShortcode
{
    public function filledSnippetData($htmlCode, array $data)
    {
        foreach ($data as $key => $value) {
            if ($key != 'id') {
                if ($value) {
                    $pattern = '{data-' . $key . '=(".*?")}';
                    $replacement = 'data-' . $key . '="' . $value . '"';
                    if (preg_match($pattern, $htmlCode)) {
                        $htmlCode = preg_replace($pattern, $replacement, $htmlCode);
                    } else {
                        $delimeter = '></div>';
                        $tempHtml = explode($delimeter, $htmlCode);
                        $tempHtml = $tempHtml[0] . ' data-' . $key . '="' . $value . '"';
                        $htmlCode = $tempHtml . $delimeter;
                    }
                }
            }
        }
        return $htmlCode;
    }
}
