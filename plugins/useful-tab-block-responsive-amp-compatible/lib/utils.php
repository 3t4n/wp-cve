<?php

namespace beginner_blogger_com_useful_tab_block_free;

defined('ABSPATH') || exit;


const X = "\x1A";

function _minify_css($input)
{
    // Keep important white-space(s) in `calc()`
    if (stripos($input, 'calc(') !== false) {
        $input = preg_replace_callback('#\b(calc\()\s*(.*?)\s*\)#i', '__replace_css_for_minify', $input);
    }
    // Minify ...
    return preg_replace(
        array(
            // Fix case for `#foo [bar="baz"]` and `#foo :first-child` [^1]
            '#(?<![,\{\}])\s+(\[|:\w)#',
            // Fix case for `[bar="baz"] .foo` and `@media (foo: bar) and (baz: qux)` [^2]
            '#\]\s+#', '#\b\s+\(#', '#\)\s+\b#',
            // Minify HEX color code ... [^3]
            '#\#([\da-f])\1([\da-f])\2([\da-f])\3\b#i',
            // Remove white-space(s) around punctuation(s) [^4]
            '#\s*([~!@*\(\)+=\{\}\[\]:;,>\/])\s*#',
            // Replace zero unit(s) with `0` [^5]
            '#\b(?:0\.)?0([a-z]+\b)#i',
            // Replace `0.6` with `.6` [^6]
            '#\b0+\.(\d+)#',
            // Replace `:0 0`, `:0 0 0` and `:0 0 0 0` with `:0` [^7]
            '#:(0\s+){0,3}0(?=[!,;\)\}]|$)#',
            // Replace `background(?:-position)?:(0|none)` with `background$1:0 0` [^8]
            '#\b(background(?:-position)?):(0|none)\b#i',
            // Replace `(border(?:-radius)?|outline):none` with `$1:0` [^9]
            '#\b(border(?:-radius)?|outline):none\b#i',
            // Remove empty selector(s) [^10]
            '#(^|[\{\}])(?:[^\{\}]+)\{\}#',
            // Remove the last semi-colon and replace multiple semi-colon(s) with a semi-colon [^11]
            '#;+([;\}])#',
            // Replace multiple white-space(s) with a space [^12]
            '#\s+#'
        ),
        array(
            // [^1]
            X . '\s$1',
            // [^2]
            ']' . X . '\s', X . '\s(', ')' . X . '\s',
            // [^3]
            '#$1$2$3',
            // [^4]
            '$1',
            // [^5]
            '0',
            // [^6]
            '.$1',
            // [^7]
            ':0',
            // [^8]
            '$1:0 0',
            // [^9]
            '$1:0',
            // [^10]
            '$1',
            // [^11]
            '$1',
            // [^12]
            ' '
        ),
        $input
    );
}


function __minifyv($input)
{
    return str_replace(array(X . '\n', X . '\t', X . '\s'), array("\n", "\t", ' '), $input);
}


function minify_css($input)
{
    if (!$input = trim($input)) {
        return $input;
    }
    global $SS, $CC;
    // Keep important white-space(s) between comment(s)
    $input = preg_replace('#(' . $CC . ')\s+(' . $CC . ')#', '$1' . X . '\s$2', $input);
    // Create chunk(s) of string(s), comment(s) and text
    $input = preg_split('#(' . $SS . '|' . $CC . ')#', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $output = "";

    foreach ($input as $v) {
        if (trim($v) === "") {
            continue;
        }
        if (
            ($v[0] === '"' && substr($v, -1) === '"') ||
            ($v[0] === "'" && substr($v, -1) === "'") ||
            (strpos($v, '/*') === 0 && substr($v, -2) === '*/')
        ) {
            // Remove if not detected as important comment ...
            if ($v[0] === '/' && strpos($v, '/*!') !== 0) {
                continue;
            }
            $output .= $v; // String or comment ...
        } else {
            $output .= _minify_css($v);
        }
    }
    // Remove quote(s) where possible ...
    $output = preg_replace(
        array(
            // '#(' . $CC . ')|(?<!\bcontent\:|[\s\(])([\'"])([a-z_][-\w]*?)\2#i',
            '#(' . $CC . ')|\b(url\()([\'"])([^\s]+?)\3(\))#i'
        ),
        array(
            // '$1$3',
            '$1$2$4$5'
        ),
        $output
    );
    return __minifyv($output);
}


function css_url_to_css_minify_code(string $local_file)
{
    $css = false;

    if (file_exists($local_file) && !is_dir($local_file)) {
        $css = file_get_contents($local_file);

        // Remove @charset.
        $css = preg_replace('{@charset[^;]+?;}i', '', $css);

        // Remove comments.
        $css = preg_replace('{/\*.+?\*/}is', '', $css);

        // Remove `!important`
        $css = preg_replace('{\s*!important}i', '', $css);

        // Minify CSS
        $css = minify_css($css);
    }
    return $css;
}
