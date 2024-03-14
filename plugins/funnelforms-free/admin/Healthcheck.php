<?php

class Af2Healthcheck {

    private $healthchecks;

    public function __construct() {
        $this->build_healthchecks();
    }

    public function get_healthchecks() {
        return $this->healthchecks;
    }

    private function build_healthchecks() {
        $this->healthchecks = [];

        $path = FNSF_AF2_HEALTHCHECK_JSON;
        $jsonString = file_get_contents($path);
        $jsonData = json_decode($jsonString, true);

        if(isset($jsonData['plugins'])) {
            $plugins = $jsonData['plugins'];
            foreach($plugins as $plugin) {

                $isActive = is_plugin_active($plugin['path']);
                $checkPluginVersion = false;

                if($isActive) {
                    $currentPluginVersion = $wp_version;
                    $checkPluginVersion = false;

                    // if warning is true, ignore checks and issue a warning
                    if (isset($plugin['warning']) && $plugin['warning']) {
                        $passed = 2;
                        $message = "<strong>".$plugin['name']."</strong><br>".__("We have detected a caching plugin! Minified CSS or JavaScript can lead to conflicts with Funnelforms. <a href='https://help.funnelforms.io/das-formular-wird-auf-der-website-nicht-angezeigt'>Check more information here!</a>", "funnelforms-free");
                        if(isset($plugin['message'])) {
                            $message =  "<strong>".$plugin['name']."</strong><br>".__($plugin['message'], "funnelforms-free");
                        }
                        $this->healthchecks[] = [
                            "label" => $message,
                            "passed" => $passed
                        ];
                    } else {

                        // if version is required compare, else only installed is checked and version is always true

                        $message = "";
                        $plugin_data = null;

                        if (isset($plugin['version'])) {
                            $targetPluginVersion = $plugin['version'];
                            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . "/" . $plugin['path'], false, false);
                            $currentPluginVersion = $plugin_data['Version'];

                            if (strpos($targetPluginVersion, "-") !== false) {
                                $targetWpVersion = explode('-', $targetPluginVersion);
                            }

                            if (is_array($targetPluginVersion)) {
                                $checkPluginVersion = (version_compare($currentPluginVersion, $targetPluginVersion[0]) >= 0);
                                $checkPluginVersion = $checkPluginVersion && (version_compare($currentPluginVersion, $targetPluginVersion[1]) == -1);

                            } else {
                                $checkPluginVersion = (version_compare($currentPluginVersion, $targetPluginVersion) >= -1);
                            }

                            $message = "<strong>".$plugin['name']."</strong><br>".__("Version", "funnelforms-free").": ".$currentPluginVersion;
                            $message .= " (". __("Required" , "funnelforms-free"). ": ".$targetPluginVersion.")";
                            if(isset($plugin['message'])) {
                                $message .=  "<br>".__($plugin['message'], "funnelforms-free");
                            }

                        } else {
                            $checkPluginVersion = true;
                        }

                        $passed = ($isActive && $checkPluginVersion);


                        $this->healthchecks[] = [
                            "label" => $message,
                            "passed" => $passed
                        ];
                    }
                }

            }
        }

        if(isset($jsonData['wordpress'])) {

            global $wp_version;

            $currentWpVersion  = $wp_version;
            $targetWpVersion  = $jsonData['wordpress'];
            $checkWp = false;

            if(strpos($targetWpVersion, "-") !== false) {
                $targetWpVersion  = explode('-', $targetWpVersion);
            }

            if(is_array($targetWpVersion)) {
                $checkWp = (version_compare($currentWpVersion, $targetWpVersion[0]) >= 0);
                $checkWp = $checkWp && (version_compare($currentWpVersion, $targetWpVersion[1]) == -1);

            } else {
                $checkWp = (version_compare($currentWpVersion, $targetWpVersion) >= 0);
            }

            $message = "<strong>".__("WordPress Version", "funnelforms-free")."</strong><br>".__("Version", "funnelforms-free").": ".$currentWpVersion;
            $message .= " (". __("Required" , "funnelforms-free"). ": ".$jsonData['wordpress'].")";

            $this->healthchecks[] = [
                "label" => $message,
                "passed" => $checkWp
            ];
        }

        if(isset($jsonData['php'])) {

            $currentPhpVersion  = phpversion();
            $targetPhpVersion  = $jsonData['php'];
            $checkPhp = false;
            $message = "<strong>".$plugin['name']."</strong><br>".__("Version", "funnelforms-free").": ".$currentPluginVersion;

            if(strpos($targetPhpVersion, "-") !== false) {
                $targetPhpVersion  = explode('-', $targetPhpVersion);
            }

            if(is_array($targetPhpVersion)) {
                $checkPhp = (version_compare($currentPhpVersion, $targetPhpVersion[0]) >= 0);
                $checkPhp = $checkPhp && (version_compare($currentPhpVersion, $targetPhpVersion[1]) == -1);

            } else {
                $checkPhp = (version_compare($currentPhpVersion, $targetPhpVersion) >= 0);
            }

            $message = "<strong>".__("PHP Version", "funnelforms-free")."</strong><br>".__("Version", "funnelforms-free").": ".$currentPhpVersion;
            $message .= " (". __("Required" , "funnelforms-free"). ": ".$jsonData['php'].")";

            $this->healthchecks[] = [
                "label" => $message,
                "passed" => $checkPhp
            ];
        }

        if(isset($jsonData['jquery'])) {

            $targetjQueryVersion  = $jsonData['jquery'];
            $js = "";
            $unique = "check".uniqid();

            if(strpos($targetjQueryVersion, "-") !== false) {
                $targetjQueryVersion  = explode('-', $targetjQueryVersion);
            }

            if(is_array($targetjQueryVersion)) {
                $targetjQueryVersionA = $targetjQueryVersion[0];
                $targetjQueryVersionB = $targetjQueryVersion[1];
                $js = 'var jQueryVersion = jQuery().jquery; var jQueryTargetVersionA = "'.$targetjQueryVersionA.'"; var jQueryTargetVersionB = "'.$targetjQueryVersionB.'"; var jQueryVersionResultA = jQueryVersion.localeCompare("'.$targetjQueryVersionA.'");  var jQueryVersionResultB = jQueryVersion.localeCompare("'.$targetjQueryVersionB.'"); window.addEventListener("DOMContentLoaded", (event) => { if(jQueryVersionResultA >= 0 && jQueryVersionResultB == -1) { console.log(jQueryVersionResultA, jQueryVersionResultB); document.querySelector("#'.$unique.'").className = "af2_checklist_icon succeed"; document.querySelector("#'.$unique.' i").className = "fas fa-check"; }});';
            } else {
                $js = 'var jQueryVersion = jQuery().jquery; var jQueryTargetVersion = "'.$targetjQueryVersion.'"; var jQueryVersionResult = jQueryVersion.localeCompare("'.$targetjQueryVersion.'"); window.addEventListener("DOMContentLoaded", (event) => { if(jQueryVersionResult >= 0) { document.querySelector("#'.$unique.'").className = "af2_checklist_icon succeed"; document.querySelector("#'.$unique.' i").className = "fas fa-check"; }});';
            }

            $js .= 'window.addEventListener("DOMContentLoaded", (event) => { document.getElementById("af2_healthcheck_jquery_current_version").innerHTML = jQueryVersion; });';

            $message = "<strong>".__("jQuery Version", "funnelforms-free")."</strong><br>".__("Version", "funnelforms-free").": <span id='af2_healthcheck_jquery_current_version'></span>";
            $message .= " (". __("Required" , "funnelforms-free"). ": ".$jsonData['jquery'].")";

            $this->healthchecks[] = [
                "label" => $message,
                "passed" => null,
                "id" => $unique,
                "check" => $js
            ];

        }


        if(isset($jsonData['system'])) {
            $systems = $jsonData['system'];
            $targetMemory  = $systems['memory'];

            if(isset($systems['memory'])) {
                $memLimit = intval(ini_get('memory_limit'));

                $message = "<strong>".__("PHP Memory Limit", "funnelforms-free")."</strong><br>".__("Current", "funnelforms-free").": ".$memLimit. " MB";
                $message .= " (". __("Required" , "funnelforms-free"). ": ".$targetMemory." MB)";

                $this->healthchecks[] = [
                    "label" => $message,
                    "passed" => ($memLimit > $targetMemory)
                ];
            }
        }


    }

}
