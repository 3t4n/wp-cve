<?php

namespace Flamix\Plugin\WP;

class Recommendations
{
    /**
     * WordPress recommended plugins.
     *
     * Use for "auto" recommendations in main view section.
     *
     * @return array
     */
    public static function plugins(): array
    {
        return [
            [
                'name' => 'WooCommerce (Orders)',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/woocommerce.php',
                'wp' => 'woocommerce/woocommerce.php',
                'flamix' => 'flamix-bitrix24-and-woo-integrations',
            ],
            [
                'name' => 'WooCommerce (Products)',
                'url' => 'https://flamix.solutions/bitrix24/warehouse/sync_products.php',
                'wp' => 'woocommerce/woocommerce.php',
                'flamix' => 'flamix-bitrix24-and-woo-products-sync',
            ],
            [
                'name' => 'WooCommerce (Rests)',
                'url' => 'https://flamix.solutions/bitrix24/warehouse/sync_rests.php',
                'wp' => 'woocommerce/woocommerce.php',
                'flamix' => 'flamix-bitrix24-and-woo-products-sync',
            ],
            [
                'name' => 'Contact Form 7',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/cf7.php',
                'wp' => 'contact-form-7/wp-contact-form-7.php',
                'flamix' => 'flamix-bitrix24-and-contact-forms-7-integrations',
            ],
            [
                'name' => 'Ninja Forms',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/ninja-forms.php',
                'wp' => 'ninja-forms/ninja-forms.php',
                'flamix' => 'flamix-bitrix24-and-ninja-forms-integration',
            ],
            [
                'name' => 'Elementor forms',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/elementor-forms.php',
                'wp' => 'elementor/elementor.php',
                'flamix' => 'flamix-bitrix24-and-elementor-forms-integration',
            ],
            [
                'name' => 'WPForms Lite',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/wpforms.php',
                'wp' => 'wpforms-lite/wpforms.php',
                'flamix' => 'flamix-bitrix24-and-wpforms-integration',
            ],
            [
                'name' => 'WPForms',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/wpforms.php',
                'wp' => 'wpforms/wpforms.php',
                'flamix' => 'flamix-bitrix24-and-wpforms-integration',
            ],
            [
                'name' => 'Fluent form',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/fluent-form.php',
                'wp' => 'fluentform/fluentform.php',
                'flamix' => 'flamix-bitrix24-and-fluent-form-integration',
            ],
            [
                'name' => 'Forminator',
                'url' => 'https://flamix.solutions/bitrix24/integrations/site/forminator.php',
                'wp' => 'forminator/forminator.php',
                'flamix' => 'flamix-bitrix24-and-forminator-integration',
            ],
        ];
    }

    /**
     * Get banner.
     *
     * @param string $code Module code. Will be use in utm_campaign.
     * @return string
     */
    public static function banner(string $code): string
    {
        $lang = explode('_', get_locale());
        $lang = $lang['0'] ?? 'en';
        $image = "https://pr.flamix.info/api/v1/banner/{$lang}/300/600/image?tag=wordpress";
        $link = "https://pr.flamix.info/api/v1/banner/{$lang}/300/600/link?tag=wordpress&utm_source=plugin&utm_medium=wordpress_plugin&utm_campaign=" . $code . "&utm_term=" . ($_SERVER['HTTP_HOST'] ?? '') . "&utm_content=banner";

        return "<a href='{$link}' target='_blank'><img src='{$image}' width='300px' /></a>";
    }
}