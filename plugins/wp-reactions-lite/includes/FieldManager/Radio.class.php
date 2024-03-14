<?php
namespace WP_Reactions\Lite\FieldManager;
use WP_Reactions\Lite\Helper;

class Radio extends Field {

    public $radios = [];
    public $checked = '';
    public $label_type = 'text';

    public function setChecked( $checked ) {
        $this->checked = $checked;
        return $this;
    }

    public function setLabelType( $label_type ) {
        $this->label_type = $label_type;
        return $this;
    }

    public function addRadio(RadioItem $radioItem) {
        $this->radios[] = $radioItem;
        return $this;
    }

    public function addRadios($radios) {
        $this->radios = $radios;
        return $this;
    }

    function build() {

        $is_disabled = '';
        if ( $this->disabled ) {
            $is_disabled = 'disabled';
        }

        /** @var RadioItem $radio */
        foreach ( $this->radios as $radio ) {
            if ( $this->checked == $radio->getValue() ) {
                $is_checked = 'checked';
            } else {
                $is_checked = '';
            }
            if ( $this->label_type == 'image' ) {
                $label = "<img src='{$radio->getLabel()}' alt='' />";
            } else {
                $label = $radio->getLabel();
            }
            $data_attrs = '';
            if ($radio->getData() != '') {
                foreach ($radio->getData() as $data_key => $data_val) {
                    $data_attrs .= 'data-' . $data_key . '="' . $data_val . '" ';
                }
            }
            echo "<div class='circle-radio {$this->classes} {$radio->getClasses()}' {$data_attrs}>";
            echo "<input type='radio' name='{$this->name}' id='{$radio->getId()}' value='{$radio->getValue()}' {$is_checked} {$is_disabled}>";
            echo "<label for='{$radio->getId()}'><span>{$label}</span></label>";
            if ($radio->getElemAfter() != '') {
                echo $radio->getElemAfter();
            }
            if ($radio->getTooltip() != '') {
                Helper::tooltip($radio->getTooltip());
            }
            echo "</div>";
        }
    }

}
