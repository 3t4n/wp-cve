<?php

/**
 ***** DO NOT CALL ANY FUNCTIONS DIRECTLY FROM THIS FILE ******
 *
 * This file will be loaded even before the framework is loaded
 * so the $app is not available here, only declare functions here.
 */

$globalsDevFile = __DIR__ . '/globals_dev.php';

is_readable($globalsDevFile) && include $globalsDevFile;

function wpPayFormFormatMoney($amountInCents, $formId = false)
{
    if (!$formId) {
        $currencySettings = \WPPayForm\App\Services\GeneralSettings::getGlobalCurrencySettings();
    } else {
        $currencySettings = \WPPayForm\App\Models\Form::getCurrencySettings($formId);
    }
    if (empty($currencySettings['currency_sign'])) {
        $currencySettings['currency_sign'] = \WPPayForm\App\Services\GeneralSettings::getCurrencySymbol($currencySettings['currency']);
    }
    return wpPayFormFormattedMoney($amountInCents, $currencySettings);
}

function wpPayFormFormattedMoney($amountInCents, $currencySettings)
{
    //get exact currency symbol from currency code ex: get $ from &#36;
    $symbol = $currencySettings['currency_sign'];
    $position = $currencySettings['currency_sign_position'];
    $decmalSeparator = '.';
    $thousandSeparator = ',';
    if ($currencySettings['currency_separator'] != 'dot_comma') {
        $decmalSeparator = ',';
        $thousandSeparator = '.';
    }
    $decimalPoints = 2;
    if ($amountInCents % 100 == 0 && $currencySettings['decimal_points'] == 0) {
        $decimalPoints = 0;
    }

    $amount = number_format($amountInCents / 100, $decimalPoints, $decmalSeparator, $thousandSeparator);

    if ('left' === $position) {
        return $symbol . $amount;
    } elseif ('left_space' === $position) {
        return $symbol . ' ' . $amount;
    } elseif ('right' === $position) {
        return $amount . $symbol;
    } elseif ('right_space' === $position) {
        return $amount . ' ' . $symbol;
    }
    return $amount;
}

function wpPayFormConverToCents($amount)
{
    if (!$amount) {
        return 0;
    }
    $amount = floatval($amount);
    return round($amount * 100, 0);
}

function wppayformUpgradeUrl()
{
    return 'https://paymattic.com/#pricing/?utm_source=plugin&utm_medium=menu&utm_campaign=upgrade';
}

function wppayformPublicPath($assets_path)
{
    return WPPAYFORM_URL . '/assets/' . $assets_path;
}

function wppayform_sanitize_html($html)
{
    if(!$html) {
        return $html;
    }

    $tags = wp_kses_allowed_html('post');
    $tags['style'] = [
        'types' => [],
    ];
    // iframe
    $tags['iframe'] = [
        'width'           => [],
        'height'          => [],
        'src'             => [],
        'srcdoc'          => [],
        'title'           => [],
        'frameborder'     => [],
        'allow'           => [],
        'class'           => [],
        'id'              => [],
        'allowfullscreen' => [],
        'style'           => [],
    ];
    //button
    $tags['button']['onclick'] = [];

    //svg
    if (empty($tags['svg'])) {
        $svg_args = array(
            'svg'   => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true, // <= Must be lower case!
            ),
            'g'     => array('fill' => true),
            'title' => array('title' => true),
            'path'  => array(
                'd'    => true,
                'fill' => true,
            )
        );
        $tags = array_merge($tags, $svg_args);
    }

    $tags = apply_filters('payform_allowed_html_tags', $tags);

    return wp_kses($html, $tags);
}

/*
 * Utility function to echo only internal hard coded strings or already escaped strings
 */
function wpPayFormPrintInternal($string)
{
    echo wp_kses_post($string); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
}
