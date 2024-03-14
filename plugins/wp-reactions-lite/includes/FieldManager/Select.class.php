<?php
namespace WP_Reactions\Lite\FieldManager;

class Select extends Field {
    public $values;
    public $selected;

    public function setValues( $values ) {
        $this->values = $values;
        return $this;
    }

    public function setDefault( $selected ) {
        $this->selected = $selected;
        return $this;
    }

    public function build() {
        $is_disabled = '';
        if ( $this->disabled ) {
            $is_disabled = 'disabled';
        }
        echo "<div class='{$this->classes}'>";
        if ( $this->label != '' ) {
            echo "<label for='{$this->id}'>{$this->label}</label>";
        }
        echo "<select name='$this->id' id='{$this->id}' class='wpra-custom-select form-control' {$is_disabled}>";
        foreach ( $this->values as $key => $value ) {
            if ( $this->selected == $key ) {
                $is_selected = 'selected';
            } else {
                $is_selected = '';
            }
            echo "<option value='{$key}' {$is_selected}>{$value}</option>";
        }
        echo "</select></div>";
    }
}
