<?php

function generateCustomTextJson(){
    $customText = "{\n";
    foreach (Tac_admin::$customText as $key1 => $service) {
        $value = get_option($service['id'], "");
        if($value == ""){
            continue;
        }
        $customText .= "\t'" . $service['title'] . "' : '" . $value . "',\n";
    }
    $customText .= "};";
    return $customText;
}

class Tac_frontend
{

    public static function init()
    {

        add_action('wp_enqueue_scripts', array('Tac_frontend', 'customHeaderScript'));

        add_action('wp_enqueue_scripts', array('Tac_frontend', 'customFooterScript'));
    }

    public static function customHeaderScript()
    {
        wp_enqueue_script('tarteaucitronjshead', plugin_dir_url(__FILE__) . 'js/tarteaucitron/tarteaucitron.js', array(), '1.0', true);

        wp_enqueue_script('tarteaucitronjsheadmain', plugin_dir_url(__FILE__) . 'js/main.js', array(), '1.0', true);

        $initScript = get_option("tac_header_script_content", "");
        $initScript .= "\n\n";
        $lang = get_option("tac_init_tarteaucitronForceLanguage", "BrowserLanguage");

        if($lang != "BrowserLanguage"){
            $initScript .= "var tarteaucitronForceLanguage = '" . get_option("tac_init_tarteaucitronForceLanguage") . "';\n";
        }
        $initScript .= "var tarteaucitronForceExpire = '" . get_option("tac_init_tarteaucitronForceExpire", "365") . "';\n";
        $customText = generateCustomTextJson();
        $customText = $customText == "{};" ? "''" : $customText;
        $initScript .= "var tarteaucitronCustomText = " . $customText . ";\n";


        if(get_option("tac_init_useExternalCss") === "true" && get_option("tac_other_ExternalCssUrl") !== ""){
            $initScript .= "var linkElement = document.createElement('link');\n
            linkElement.rel = 'stylesheet';\n
            linkElement.type = 'text/css';\n
            linkElement.href = '". get_option("tac_other_ExternalCssUrl"). "';\n
            document.getElementsByTagName('head')[0].appendChild(linkElement);\n";
        }


        $initScript .= "tarteaucitron.init({";

        foreach (Tac_admin::$init as $key1 => $service) {
            $initScript .= "\n\t\"" . $service["title"] . "\" : ";

            $initScript .= $service["type"] == "boolean" ? "" : "\"";
            $initScript .= get_option($service["id"]) ? get_option($service["id"]) : $service["value"];
            $initScript .= $service["type"] == "boolean" ? "," : "\",";
        }



        $initScript .= "});";

        wp_add_inline_script('tarteaucitronjsheadmain', $initScript);
    }


    public static function customFooterScript()
    {

        wp_enqueue_script('tarteaucitronjsfootmain', plugin_dir_url(__FILE__) . 'js/main.js', array(), '1.0', true);


        $script = "\n" . get_option("tac_footer_script_content") . "\n";

        foreach (Tac_admin::$services as $key1 => $services) {
            $newSection = true;
            foreach ($services as $key2 => $service) {
                if ($newSection) {
                    $newSection = false;
                    $script .= "\n//Start Services $key1 \n";
                    continue;
                }
                $opt = get_option($service['id'], '');
                if ($opt != '') {
                    $script .= "(tarteaucitron.job = tarteaucitron.job || []).push('" . get_option($service['id']) . "');\n";
                }
            }
            $script .= "// End Services $key1 \n\n";
        }

        wp_add_inline_script('tarteaucitronjsfootmain', $script);
    }

}

?>