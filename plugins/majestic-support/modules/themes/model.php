<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_themesModel {

    function storeTheme($data) {
        $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $this->replaceString($filestring, 1, $data);
        $this->replaceString($filestring, 2, $data);
        $this->replaceString($filestring, 3, $data);
        $this->replaceString($filestring, 4, $data);
        $this->replaceString($filestring, 5, $data);
        $this->replaceString($filestring, 6, $data);
        $this->replaceString($filestring, 7, $data);
        if (file_put_contents($filepath, $filestring)) {
            update_option('ms_set_theme_colors', json_encode($data));
            MJTC_includer::MJTC_getModel('majesticsupport')->updateColorFile();
            MJTC_message::MJTC_setMessage(esc_html(__('The new theme has been applied', 'majestic-support')), 'updated');
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Error applying the new theme', 'majestic-support')), 'error');
        }
        return;
    }

    function replaceString(&$filestring, $colorNo, $data) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($filestring, '$color' . $colorNo)) {
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '$color' . $colorNo);
            $path2 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

    function getColorCode($filestring, $colorNo) {
        if (MJTC_majesticsupportphplib::MJTC_strstr($filestring, '$color' . $colorNo)) {
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '$color' . $colorNo);
            $path1 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, '#', $path1);
            $path2 = MJTC_majesticsupportphplib::MJTC_strpos($filestring, ';', $path1);
            $colorcode = MJTC_majesticsupportphplib::MJTC_substr($filestring, $path1, $path2 - $path1 - 1);
            return $colorcode;
        }
    }

    function getCurrentTheme() {
        $filepath = MJTC_PLUGIN_PATH . 'includes/css/style.php';
        $filestring = file_get_contents($filepath);
        $theme['color1'] = $this->getColorCode($filestring, 1);
        $theme['color2'] = $this->getColorCode($filestring, 2);
        $theme['color3'] = $this->getColorCode($filestring, 3);
        $theme['color4'] = $this->getColorCode($filestring, 4);
        $theme['color5'] = $this->getColorCode($filestring, 5);
        $theme['color6'] = $this->getColorCode($filestring, 6);
        $theme['color7'] = $this->getColorCode($filestring, 7);
        $theme = apply_filters('cm_theme_colors', $theme, 'majestic-support');
        majesticsupport::$_data[0] = $theme;
        return;
    }
}
?>
