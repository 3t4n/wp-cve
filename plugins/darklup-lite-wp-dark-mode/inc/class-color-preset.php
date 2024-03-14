<?php
namespace DarklupLite;

/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */

/**
 * Hooks class
 */
class Color_Preset
{

    public static function getColorPreset($preset = '1')
    {

        switch ($preset) {

            case '1':
                return self::color_preset_1();
                break;
            case '2':
                return self::color_preset_2();
                break;
            case '3':
                return self::color_preset_3();
                break;
            default:
                return self::color_preset_1();

        }

    }

 

    public static function color_preset_1()
    {
        return [
            'background-color' => 'rgb(39,40,39)',
            'secondary_bg' => 'rgb(36,37,37)',
            'tertiary_bg' => 'rgb(46,44,44)',
            'color' => '#e5e0d8',
            'anchor-color' => '#EDEDED',
            'anchor-hover-color' => '#3fb950',
            'input-bg-color' => '#353535',
            'border-color' => 'rgba(143, 132, 117, 0.3)',
            'btn-bg-color' => '#141414',
            'btn-color' => '#EDEDED',
        ];
    }

    public static function color_preset_2()
    {

        return [
            'background-color' => 'rgb(18,18,18)',
            'secondary_bg' => 'rgb(37,38,38)',
            'tertiary_bg' => 'rgb(45,43,43)',
            'color' => '#f7f7f7',
            'anchor-color' => '#749ae5',
            'anchor-hover-color' => '#749ae5',
            'input-bg-color' => '#353535',
            'border-color' => '#455465',
            'btn-bg-color' => '#141414',
            'btn-color' => '#f7f7f7',
        ];
    }

    public static function color_preset_3()
    {

        return [
            'background-color' => 'rgb(13,17,23)',
            'secondary_bg' => 'rgb(35,36,36)',
            'tertiary_bg' => 'rgb(47,45,45)',
            'color' => '#fff',
            'anchor-color' => '#ff8200',
            'anchor-hover-color' => '#ff8200',
            'input-bg-color' => '#353535',
            'border-color' => '#455465',
            'btn-bg-color' => '#141414',
            'btn-color' => '#fff',
        ];
    }

}