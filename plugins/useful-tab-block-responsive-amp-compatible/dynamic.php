<?php

namespace beginner_blogger_com_useful_tab_block_free;

defined('ABSPATH') || exit;

function beginner_blogger_useful_tab_block_free_get_color_class($color, $isBG = false, $isCheck = false)
{
    $colors = beginner_blogger_useful_tab_block_free_get_colors();

    $styleName = "";

    if ($isCheck) {
        $styleName .= "-check";
    }
    if ($isBG) {
        $styleName .= "-background";
    }

    for ($i = 0; $i < count($colors); $i++) {
        if ($colors[$i]["color"] == $color) {
            $slug = $colors[$i]["slug"];
            return "bbc-utb-free-has-{$slug}{$styleName}-color";
        }
    }
    return "";
}


function beginner_blogger_useful_tab_block_free_show_label_texts($labelArray, $dateID, $labelOrderArray, $n_tabs, $bgColorClass, $colorClass)
{
    $output = "";

    // input-tag: <input></input>
    for ($index = 0; $index < count($labelArray); $index++) {
        $id = "tab_id_{$dateID}_{$index}";
        $output .= "<input type='radio' name={$dateID} id={$id} class='save' ";

        if ($index == $labelOrderArray[0]) {
            $output .= "checked";
        }
        $output .= "></input>";
    }

    // label-tag: <label></label>
    for ($index = 0; $index < count($labelArray); $index++) {
        if ($index >= $n_tabs) break;
        $order_id = "tab_id_{$dateID}_{$labelOrderArray[$index]}";
        $class = "bbc-utb-free-tab-label {$bgColorClass} {$colorClass}";

        $output .= "<label class='{$class}' for={$order_id}>";
        $output .= "{$labelArray[$index]}";
        $output .= "</label>";
    }

    return $output;
}

function beginner_blogger_useful_tab_block_free_get_colors()
{
    $colors = [
        ["name" => "White", "slug" => "blanc", "color" => "#fefefe"],
        ["name" => "Black", "slug" => "noir", "color" => "#010101"],
        ["name" => 'Light Cyan', "slug" => "light_c", "color" => '#ddfff7'],
        ["name" => 'Middle Blue Green', "slug" => "middle_bg", "color" => '#93e1d8'],
        ["name" => 'Melon', "slug" => "melon", "color" => '#ffa69e'],
        ["name" => 'Irresistible', "slug" => "irresistible", "color" => '#aa4465'],
        ["name" => 'Russian Violet', "slug" => "russian_v", "color" => '#462255'],
        ["name" => 'Cyber Grape', "slug" => "cyber_g", "color" => '#49416d'],
        ["name" => "Cerise", "slug" => "cerise", "color" => "#db2763"],
        ["name" => "June Bud", "slug" => "june_b", "color" => "#b0db43"],
        ["name" => "Fluorescent Blue", "slug" => "fluorescent_b", "color" => "#12eaea"],
        ["name" => "Uranian Blue", "slug" => "uraniain_b", "color" => "#bce7fd"],
        ["name" => "Opera Mauve", "slug" => "opera_m", "color" => "#c492b1"],
        ["name" => "Raisin Black", "slug" => "raisin_b", "color" => "#252627"],
        ["name" => "Charleston Green", "slug" => "charleston_g", "color" => "#253031"],
        ["name" => "Dark Slate Gray", "slug" => "dark_s_g", "color" => "#315659"],
        ["name" => "Celadon Blue", "slug" => "celadon_b", "color" => "#2978a0"],
        ["name" => "Ecru", "slug" => "ecru", "color" => "#bcab79"],
        ["name" => "Beau Blue", "slug" => "beau_b", "color" => "#c6e0ff"],
        ["name" => "Columbia Blue", "slug" => "columbia_b", "color" => "#c1dbe3"],
        ["name" => "Tea Green", "slug" => "tea_g", "color" => "#c7dfc5"],
        ["name" => "Lemon Yellow Crayola", "slug" => "lemon_y_c", "color" => "#f6feaa"],
        ["name" => "Buff", "slug" => "buff", "color" => "#fce694"],
        ["name" => "Jet", "slug" => "jet", "color" => "#373737"],
        ["name" => "Maximum Blue", "slug" => "maximum_b", "color" => "#5eb1bf"],
        ["name" => "Rich Black", "slug" => "rich_b", "color" => "#042a2b"],
        ["name" => "Minion Yellow", "slug" => "minion_y", "color" => "#f4e04d"],
        ["name" => "Yellow Orange", "slug" => "yellow_o", "color" => "#f4ac45"],
        ["name" => "Coffee", "slug" => "coffee", "color" => "#694a38"],
        ["name" => "Vivid Burgundy", "slug" => "vivid_v", "color" => "#a61c3c"]
    ];
    return $colors;
}

function beginner_blogger_useful_tab_block_free_get_border_color_class($color)
{
    $colors = beginner_blogger_useful_tab_block_free_get_colors();

    for ($i = 0; $i < count($colors); $i++) {
        if ($colors[$i]["color"] == $color) {
            return " bbc-utb-free-has-" . $colors[$i]["slug"] . "-border-color";
        }
    }
    return "";
}

function beginner_blogger_useful_tab_block_free_border_width_class($borderWidth)
{
    if ($borderWidth >= 3.5) {
        return " bbc-utb-free-border-width-35";
    } else if ($borderWidth >= 3.0) {
        return " bbc-utb-free-border-width-30";
    } else if ($borderWidth >= 2.5) {
        return " bbc-utb-free-border-width-25";
    } else if ($borderWidth >= 2.0) {
        return " bbc-utb-free-border-width-20";
    } else if ($borderWidth >= 1.5) {
        return " bbc-utb-free-border-width-15";
    } else if ($borderWidth >= 1.0) {
        return " bbc-utb-free-border-width-10";
    } else if ($borderWidth >= 0.5) {
        return " bbc-utb-free-border-width-05";
    }
}


// Tab Block
function beginner_blogger_useful_tab_block_free_dynamic_render($attr, $content)
{

    $customLabelColor = array_key_exists("customLabelColor", $attr) ? $attr["customLabelColor"] : "";
    $customBorderColor = array_key_exists("customBorderColor", $attr) ? $attr["customBorderColor"] : "";
    $customLabelBackgroundColor = array_key_exists("customLabelBackgroundColor", $attr) ? $attr["customLabelBackgroundColor"] : "";
    $customCheckedLabelBackgroundColor = array_key_exists("customCheckedLabelBackgroundColor", $attr) ? $attr["customCheckedLabelBackgroundColor"] : "";
    $customCheckedLabelColor = array_key_exists("customCheckedLabelColor", $attr) ? $attr["customCheckedLabelColor"] : "";
    $className = array_key_exists("className", $attr) ? $attr["className"] : "";

    [
        "n_tabs" => $n_tabs,
        "labelArray" => $labelArray,
        "labelOrderArray" => $labelOrderArray,
        "dateID" => $dateID,
        "borderType" => $borderType,
        "borderWidth" => $borderWidth,
        "hasMargin" => $hasMargin,
    ] = $attr;

    $tabClassName = "tabs-{$n_tabs}";
    $bgColorClass = beginner_blogger_useful_tab_block_free_get_color_class($customLabelBackgroundColor, true);
    $bgCheckColorClass = beginner_blogger_useful_tab_block_free_get_color_class($customCheckedLabelBackgroundColor, true, true);
    $colorClass = beginner_blogger_useful_tab_block_free_get_color_class($customLabelColor);
    $checkedColorClass = beginner_blogger_useful_tab_block_free_get_color_class($customCheckedLabelColor, false, true);

    $marginClass = !$hasMargin ? " no-margin" : "";

    // Start buffering
    ob_start();
?>

    <div class="wp-block-beginner-blogger-tab <?php echo $className; ?>">
        <div class="save bbc-utb-free-tab-label-wrapper <?php echo "{$tabClassName} {$bgCheckColorClass} {$checkedColorClass}"; ?>">
            <?php echo beginner_blogger_useful_tab_block_free_show_label_texts($labelArray, $dateID, $labelOrderArray, $n_tabs, $bgColorClass, $colorClass); ?>

            <div class="bbc-utb-free-tab-content-wrapper save <?php echo "bbc-utb-free-border-{$borderType}" . beginner_blogger_useful_tab_block_free_border_width_class($borderWidth) . " {$marginClass}" . beginner_blogger_useful_tab_block_free_get_border_color_class($customBorderColor); ?>">
                <?php echo do_blocks($content); ?>
            </div>
        </div>
    </div>

<?php
    // Buffer content
    $output = ob_get_contents();
    // Buffer cleaer
    ob_end_clean();
    // Output the buffere content
    return $output;
}


// Tab Content Block
function beginner_blogger_useful_tab_block_free_content_dynamic_render($attr, $content)
{

    // Start buffering
    ob_start();
?>
    <div class="bbc-utb-free-tab-content">
        <?php echo do_blocks($content); ?>
    </div>

<?php
    // Buffer content
    $output = ob_get_contents();
    // Buffer cleaer
    ob_end_clean();
    // Output the buffere content
    return $output;
}
