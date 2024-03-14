<?php

function pmue_wpae_user_available_sections($available_sections)
{
    XmlExportUser::$is_export_shop_customer or $available_sections['other']['title'] = __("Advanced", "wp_all_export_plugin");
    XmlExportUser::$is_export_shop_customer or $available_sections['cf']['title'] = __("User Meta", "wp_all_export_plugin");

    return $available_sections;
}