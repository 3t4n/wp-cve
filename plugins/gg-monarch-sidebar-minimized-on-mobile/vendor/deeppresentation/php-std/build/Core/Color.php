<?php namespace MSMoMDP\Std\Core;
class Color {
    public static $colorFormats = [
        'rgba' => 'rgba',
        'rgb' => 'rgb',
        'hex' => '#',
        'hsla' => 'hsla',
        'hsl' => 'hsl',
        'hsva' => 'hsva',
        'hsv' => 'hsv'
    ];
    public static $alphaFormats = [
        'rgba',
        'hsla',
        'hsva'
    ];
    private $color;
    private $format;
    public function __construct($color, $format = 'rgba') {
        $this->setColor($color);
        $this->setFormat($format);
    }
    public function __toArray() {
        return $this->toArray();
    }
    public function __toString() {
        return $this->toString();
    }
    public function toArray($format = null) {
        $format = $format == true ? $format : $this->getFormat();
        $format = static::standarizeFormat($format);
        if ($format == 'hex') { $format = 'rgba'; }
        $color = $this->color;
        if (in_array($format, ['hsl', 'hsla'])) {
            $color = static::rgba2hsla($color);
        } elseif (in_array($format, ['hsv', 'hsva'])) {
            $color = static::rgba2hsva($color);
        }
        if (!in_array($format, static::$alphaFormats)) {
            unset($color[3]);
        }
        return $color;
    }
    public function toString($format = null) {
        $format = $format == true ? $format : $this->getFormat();
        $format = static::standarizeFormat($format);
        return $this::rgba2text($this->getColor(), $format);
    }
    public function setFormat($format) {
        $this->format = static::standarizeFormat($format);
        return $this;
    }
    public function getFormat() { return $this->format; }
    public function setColor($color) {
        if (is_a($color, Color::class)) {
            $color = $color->toString();
        }
        if (is_string($color)) {
            $color = Color::text2rgba($color);
        } elseif (!is_array($color) && count($color) < 3) {
            $color = [0, 0, 0, 100];
        }
        if (!isset($color[3]) || $color[3] > 100) {
            $color[3] = 100;
        } elseif ($color[3] < 0) {
            $color[3] = 0;
        }
        $this->color = $color;
        return $this;
    }
    public function getColor() { 
        $color = $this->color;
        if (!isset($color[3]) || $color[3] > 100) { $color[3] = 100; }
        elseif ($color[3] < 0) { $color[3] = 0; }
        return $color;
    }
    public function setAlpha($alpha) {
        if ($alpha > 100) { $alpha = 100; }
        if ($alpha < 0) { $alpha = 0; }
        $this->color[3] = $alpha;
        return $this;
    }
    public function getAlpha() {
        $alpha = 100;
        if (isset($this->color[3])) {
            $alpha = $this->color[3];
        }
        return $alpha;
    }
    public function addAlpha($alpha) {
        return $this->setAlpha($this->getAlpha() + $alpha);
    }
    public function setLight($light) {
        $color = $this->getColor();
        $color = static::rgba2hsla($color);
        $color[2] += $light;
        if ($color[2] > 100) { $color[2] = 100; }
        if ($color[2] < 0) { $color[2] = 100; }
        $color = static::hsla2rgba($color);
        return $this->setColor($color);
    }
    public function getLight() {
        $color = $this->getColor();
        $color = static::rgba2hsla($color);
        return $color[2];
    }
    public function addLight($light) {
        return $this->setLight($this->getLight() + $light);
    }
    public function isWhite() {
        $color = $this->getColor();
        if ($color[0] == 255 && $color[1] == 255 && $color[2] == 255) {
            return true;
        }
        return false;
    }
    public function toRGB() {
        return $this->toArray('rgb');
    }
    public function toTextRGB() {
        return $this->toString('rgb');
    }
    public function toRGBA() {
        return $this->toArray('rgba');
    }
    public function toTextRGBA() {
        return $this->toString('rgba');
    }
    public function toHEX() {
        return $this->toString('hex');
    }
    public function toTextHEX() {
        return $this->toHEX();
    }
    public function toHSL() {
        return $this->toArray('hsl');
    }
    public function toTextHSL() {
        return $this->toString('hsl');
    }
    public function toHSLA() {
        return $this->toArray('hsla');
    }
    public function toTextHSLA() {
        return $this->toString('hsla');
    }
    public function toHSV() {
        return $this->toArray('hsv');
    }
    public function toTextHSV() {
        return $this->toString('hsv');
    }
    public function toHSVA() {
        return $this->toarray('hsva');
    }
    public function toTextHSVA() {
        return $this->toString('hsva');
    }
    public static function standarizeFormat($format) {
        $format = trim(strtolower($format));
        if (!in_array($format, array_keys(static::$colorFormats))) {
            $format = 'rgba';
        }
        return $format;
    }
    public static function detectFormat($color) {
        foreach (static::$colorFormats as $format => $detector) {
            if (strpos($color, $detector) !== false) {
                return $format;
            }
        }
        return 'unknow';
    }
    public static function text2rgba($color) {
        $color = trim(strtolower($color));
        $color = str_replace('%', '', $color);
        $format = Color::detectFormat($color);
        switch($format) {
            case 'rgba':
                $color = sscanf($color, 'rgba(%f,%f,%f,%f)');
                $color[3] *= 100;
                break;
            case 'rgb':
                $color = sscanf($color, 'rgb(%f,%f,%f)');
                break;
            case 'hex':
                $color = static::hex2rgba($color);
                break;
            case 'hsva':
                $color = sscanf($color, 'hsva(%f,%f,%f,%f)');
                $alpha = $color[3] * 100;
                $color = static::hsva2rgba($color);
                break;
            case 'hsv':
                $color = sscanf($color, 'hsv(%f,%f,%f)');
                $color = static::hsva2rgba($color);
                break;
            case 'hsla':
                $color = sscanf($color, 'hsla(%f,%f,%f,%f)');
                $alpha = $color[3] * 100;
                $color = static::hsla2rgba($color);
                break;
            case 'hsl':
                $color = sscanf($color, 'hsl(%f,%f,%f)');
                $color = static::hsla2rgba($color);
                break;
            default:
                $color = [0, 0, 0, 100];
                break;
        }
        if (!isset($color[3])) { $color[3] = 100; }
        return $color;
    }
    public static function rgba2text($color, $format = 'rgba') {
        $format = static::standarizeFormat($format);
        if (!in_array($format, static::$alphaFormats)) { unset($color[3]); }
        else { $color[3] /= 100; }
        switch($format) {
            case 'rgba':
                if (!isset($color[3])) {
                    $color[3] = 1;
                }
                return vsprintf('rgba(%s, %s, %s, %s)', $color);
            case 'rgb': return vsprintf('rgb(%s, %s, %s)', $color);
            case 'hex':
                if (isset($color[3])) {
                    unset($color[3]);
                }
                return static::rgba2hex($color);
            case 'hsv':
                return static::rgba2text($color, 'rgb');
            case 'hsva':
                return static::rgba2text($color, 'rgba');
            case 'hsla':
                $color = static::rgba2hsla($color);
                $color[1] = $color[1] . "%";
                $color[2] = $color[2] . "%";
                return vsprintf('hsla(%s, %s, %s, %s)', $color);
            case 'hsl':
                $color = static::rgba2hsla($color);
                $color[1] = $color[1] . "%";
                $color[2] = $color[2] . "%";
                return vsprintf('hsl(%s, %s, %s)', $color);
        }
        return "rgb(0, 0, 0)";
    }
    public static function hex2rgba($hex) {
        $color = [0, 0, 0, 100];
        switch(strlen($hex)) {
            case 4:
                $r = substr($hex, 1, 1);
                $g = substr($hex, 2, 1);
                $b = substr($hex, 3, 1);
                $hex = '#' . $r . $r . $g . $g . $b . $b;
            case 7:
                $hex .= 'ff';
            case 9:
                $color = sscanf($hex, "#%02x%02x%02x%02x");
        }
        return $color;
    }
    public static function rgba2hex($rgba) {
        $hex = '#';
        foreach ($rgba as $i => $segment) 
        { 
            $hexChannel = dechex($segment);
            if (strlen($hexChannel) == 1)
            {
                $hexChannel = '0'.$hexChannel;    
            }
            $hex .= $hexChannel; 
        }
        return $hex;
    }
    public static function hsva2rgba($hsva) {
        $h = $hsv[0];
        if ($h >= 360 || $h < 0) {
            $h = $h - (floor($h / 360) * 360);
        }
        $h /= 60;
        $s = $hsv[1] / 100;
        $v = $hsv[2] / 100 * 255;
        if ($s == 0) {
            $rgba = [$v, $v, $v];
        } else {
            $i = floor($h);
            $m = $v * (1 - $s);
            $n = $v * (1 - $s * ($h - $i));
            $k = $v * (1 - $s * (1 - ($h - $i)));
            switch ($i) {
                case 0: $rgba = [$v, $k, $m]; break;
                case 1: $rgba = [$n, $v, $m]; break;
                case 2: $rgba = [$m, $v, $k]; break;
                case 3: $rgba = [$m, $n, $v]; break;
                case 4: $rgba = [$k, $m, $v]; break;
                default: $rgba = [$v, $m, $n]; break;
            }
        }
        $rgba[0] = round($rgba[0]);
        $rgba[1] = round($rgba[1]);
        $rgba[2] = round($rgba[2]);
        $rgba[3] = 100;
        if (isset($hsva[3])) {
            $rgba[3] = $hsva[3];
        }
        return $rgba;
    }
    public static function rgba2hsva($rgba) {
        $r = $rgba[0];
        $g = $rgba[1];
        $b = $rgba[2];
        $m = min($r, $g, $b);
        $v = max($r, $g, $b);
        $d = $v - $m;
        $s = $v == 0.0 ? 0 : $d / $v;
        if ($v == 0) {
            $s = 0;
            $h = 0;    
        } else {
            $s = $d / $v;
            switch ($v) {
                case $r: $h = ($g - $b) / $d; break;
                case $g: $h = 2 + ($b - $r) / $d; break;
                case $b: $h = 4 + ($r - $g) / $d; break;
            }
            $h *= 60;
            if ($h < 0) { $h += 360; }
        }
        if ($h >= 360 || $h < 0) {
            $h = $h - (floor($h / 360) * 360);
        }
        $h = round($h, 1);
        $s = round($s * 100, 1);
        $v = round($v / 255 * 100, 1);
        $a = 100;
        if (isset($rgba[3])) { $a = $rgba[3]; }
        return [$h, $s, $v, $a];
    }
    public static function hsla2rgba($hsla) {
        $h = $hsla[0];
        $s = $hsla[1] / 100;
        $l = $hsla[2] / 100;
        if ($h >= 360 || $h < 0) {
            $h = $h - (floor($h / 360) * 360);
        }
        $h /= 360;
        $rgba = [$l, $l, $l];
        $v = $l <= 0.5 ? $l * (1.0 + $s) : $l + $s - $l * $s;
        if ($v > 0) {
            $m = ($l * 2) - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;
            switch ($sextant) {
                case 0: $rgba = [$v, $mid1, $m]; break;
                case 1: $rgba = [$mid2, $v, $m]; break;
                case 2: $rgba = [$m, $v, $mid1]; break;
                case 3: $rgba = [$m, $mid2, $v]; break;
                case 4: $rgba = [$mid1, $m, $v]; break;
                case 5: $rgba = [$v, $m, $mid2]; break;
            }
        }
        foreach ($rgba as $i => $segment) { $rgba[$i] = round($segment * 255); }
        $rgba[3] = 100;
        if (isset($hsla[3])) { $rgba[3] = $hsla[3]; }
        return $rgba;
    }
    public static function rgba2hsla($rgba) {
        $r = $rgba[0] / 255;
        $g = $rgba[1] / 255;
        $b = $rgba[2] / 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $d = $max - $min;
        $l = ($max + $min) / 2;
        if ($d == 0) {
            $h = $s = 0;
        } else {
            $s = $d / (1 - abs(2 * $l - 1));
            switch ($max) {
                case $r: $h = 60 * fmod((($g - $b) / $d), 6); $h = $b > $g ? $h += 360 : $h; break;
                case $g: $h = 60 * (($b - $r) / $d + 2); break;
                case $b: $h = 60 * (($r - $g) / $d + 4); break;
            }
        }
        if ($h >= 360 || $h < 0) {
            $h = $h - (floor($h / 360) * 360);
        }
        $s *= 100;
        $l *= 100;
        $hsla = [$h, $s, $l];
        foreach ($hsla as $i => $segment) { $hsla[$i] = round($segment); }
        $hsla[3] = 100;
        if (isset($rgba[3])) { $hsla[3] = $rgba[3]; }
        return $hsla;
    }
    public static function generatePalette($color, $type = 'light', $extras = []) {
        if (is_array($color)) {
            $color = $color;
            $format = 'rgba';
        } elseif (!is_a($color, Color::class)) {
            $format = static::detectFormat($color);
            $color = static::text2rgba($color);
        }
        if (!is_a($color, Color::class)) {
            $color = new Color($color, $format);
        }
        $format = $color->getFormat();
        $type = trim(strtolower($type));
        $palette = [];
        switch ($type) {
            case 'tri_close':
                if (!isset($extras['angle'])) {
                    $extras['angle'] = 30;
                }
                $hsl_color = $color->toHSLA();
                for($i = -1; $i <= 1; $i++) {
                    $h = $hsl_color[0] + ($i * $extras['angle']);
                    $s = $hsl_color[1];
                    $l = $hsl_color[2];
                    $a = $hsl_color[3];
                    $new_color = new Color(static::hsla2rgba([$h, $s, $l, $a]), $format);
                    $palette[$i + 1] = static::generatePalette($new_color, 'light')[0];
                }
                break;
            case 'tri_open':
                if (!isset($extras['angle'])) {
                    $extras['angle'] = 30;
                }
                $hsl_color = $color->toHSLA();
                for($i = -1; $i <= 1; $i++) {
                    $h = $hsl_color[0];
                    if ($i != 0) {
                        $h = $hsl_color[0] + 180 + ($i * $extras['angle']);
                    }
                    $s = $hsl_color[1];
                    $l = $hsl_color[2];
                    $a = $hsl_color[3];
                    $new_color = new Color(static::hsla2rgba([$h, $s, $l, $a]), $format);
                    $palette[$i + 1] = static::generatePalette($new_color, 'light')[0];
                }
                break;
            case 'light':
            default:
                $palette[0] = [];
                $palette[0][$color->getLight()] = $color->toString();
                for($i = 0; $i <= 10; $i++) {
                    $copy = $color;
                    $l = $i * 10;
                    $copy->setLight($l);
                    if (!$copy->isWhite()) {
                        $str = $copy->toString();
                        if (!in_array($str, array_values($palette[0]))) {
                            $palette[0][$l] = $str;
                        }
                    }
                }
        }
        return $palette;
    }
}