<?php

namespace FRFreeVendor\WPDesk\Library\FlexibleRefundsCore\Helpers;

/**
 * Email helper functions used in email templates.
 */
class EmailHelper
{
    /**
     * @return array
     */
    public static function allowed_tags() : array
    {
        return (array) \apply_filters('wpdesk/fr/email/allowed_tags', ['span' => ['class' => []], 'a' => ['style' => [], 'href' => [], 'target' => []], 'strong' => ['class' => []], 'h1' => ['style' => [], 'class' => []], 'h2' => ['style' => [], 'class' => []], 'h3' => ['style' => [], 'class' => []], 'table' => ['class' => [], 'id' => []], 'thead' => ['class' => [], 'id' => []], 'tbody' => ['class' => [], 'id' => []], 'tfoot' => ['class' => [], 'id' => []], 'th' => ['class' => [], 'scope' => []], 'tr' => ['class' => [], 'scope' => []], 'td' => ['class' => [], 'scope' => []], 'br' => [], 'p' => ['style' => []]]);
    }
}
