<?php
namespace WP_Reactions\Lite\FieldManager;
use WP_Reactions\Lite\Helper;

class Checkbox extends Field {
    public $checkboxes = [];

    public function addCheckbox( $id, $value, $label, $checked = '', $lottieAfter = false, $elemAfter = false, $tooltip = false, $disabled = false) {
        $this->checkboxes[] = [
            'id' => $id,
            'value' => $value,
            'label' => $label,
            'checked' => $checked,
            'lottieAfter' => $lottieAfter,
            'elemAfter' => $elemAfter,
            'tooltip' => $tooltip,
            'disabled' => $disabled,
        ];
        return $this;
    }

    public function build() {
        foreach ($this->checkboxes as $checkbox) {
            if ( $checkbox['value'] == $checkbox['checked'] ) {
                $is_checked = 'checked';
            } else {
                $is_checked = '';
            }
            if ($checkbox['disabled']) {
                $is_disabled = 'disabled';
            } else {
                $is_disabled = '';
            }
            echo "<div class='{$this->classes}'>";
            echo "<div class='rectangle-checkbox'>";
            echo "<input type='checkbox' name='{$this->name}' id='{$checkbox["id"]}' value='{$checkbox["value"]}' {$is_checked} {$is_disabled}>";
            echo "<label for='{$checkbox["id"]}'><span>{$checkbox["label"]}</span></label>";
            if ($checkbox['lottieAfter']) {
                $emoji_name = str_replace('emojis_', '', $checkbox['id']);
                echo "<div class='lottie-element' data-emoji_name='{$emoji_name}'></div>";
            }
            if (is_array($checkbox['elemAfter'])) {
                echo "<div class='input-text-icon'>";
                $icon_url = Helper::getAsset("images/social/{$checkbox["elemAfter"]["icon"]}");
                echo "<img class='icon_{$checkbox["elemAfter"]["id"]}' src='{$icon_url}' alt='{$checkbox["elemAfter"]["id"]}'>";
                echo "<input type='text' id='{$checkbox["elemAfter"]["id"]}' class='form-control' value='{$checkbox["elemAfter"]["value"]}' placeholder='{$checkbox["elemAfter"]["placeholder"]}'>";
                echo "</div>";
            }
            else if ($checkbox['elemAfter']) {
                echo $checkbox['elemAfter'];
            }
            if ($checkbox['tooltip']) {
                Helper::tooltip($checkbox['tooltip']);
            }
            echo "</div>";
            echo "</div>";
        }
    }
}
