<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Outputter.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Plugin_Cli_Outputter
{
    public static function output($output, $foreground = null, $background = null)
    {
        if (ifw_is_cli() && (!empty($foreground) ||!empty($background))) {
            $output = IfwPsn_Wp_Plugin_Cli_Colors::getInstance()->getColoredString($output, $foreground, $background);
        }

        echo $output;

        if (ifw_is_cli() && ob_get_contents()) {
            ob_end_clean();
            flush();
        }
    }

    public static function outputWithLineBreak($output, $foreground = null, $background = null)
    {
        echo self::output($output, $foreground, $background);
        echo PHP_EOL;
    }

    public static function outputWithDoubleLineBreak($output, $foreground = null, $background = null)
    {
        echo self::output($output, $foreground, $background);
        echo PHP_EOL;
        echo PHP_EOL;
    }
}
 