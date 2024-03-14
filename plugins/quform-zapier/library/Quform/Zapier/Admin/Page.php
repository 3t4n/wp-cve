<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */
abstract class Quform_Zapier_Admin_Page extends Quform_Admin_Page
{
    /**
     * Get the admin sub navigation HTML
     *
     * @return string
     */
    public function getSubNavHtml()
    {
        $mdiPrefix = apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi');

        $links = array(
            array(
                'cap' => 'quform_zapier_list_integrations',
                'href' => admin_url('admin.php?page=quform.zapier'),
                'class' => 'integrations',
                'text' => __('Integrations', 'quform-zapier'),
                'icon' => "$mdiPrefix $mdiPrefix-view_stream"
            ),
            array(
                'cap' => 'quform_zapier_settings',
                'href' => admin_url('admin.php?page=quform.zapier&sp=settings'),
                'class' => 'settings',
                'text' => __('Settings', 'quform-zapier'),
                'icon' => "$mdiPrefix $mdiPrefix-settings"
            ),
            array(
                'cap' => 'quform_zapier_add_integrations',
                'href' => admin_url('admin.php?page=quform.zapier&sp=add'),
                'class' => 'add-integration',
                'text' => __('Add Integration', 'quform-zapier'),
                'icon' => "$mdiPrefix $mdiPrefix-add_circle"
            )
        );

        $visible = array();

        foreach ($links as $link) {
            if (current_user_can($link['cap'])) {
                $visible[] = $link;
            }
        }

        if ( ! count($visible)) {
            return '';
        }

        $output = '<ul class="qfb-tabs-nav qfb-tabs-nav-zapier qfb-cf">';

        foreach ($visible as $item) {
            $output .= sprintf(
                '<li class="qfb-tabs-nav-zapier-%s"><a href="%s"><i class="%s"></i><span>%s</span></a></li>',
                esc_attr($item['class']),
                esc_url($item['href']),
                esc_attr($item['icon']),
                esc_html($item['text'])
            );
        }

        $output .= '</ul>';

        return $output;
    }
}
