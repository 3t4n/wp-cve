<?php
namespace WP_Reactions\Lite\FieldManager;

class Text extends Field {
    public $type = 'text';
    private $placeholder = '';

    public function setType( $type ) {
        $this->type = $type;
        return $this;
    }

    public function setPlaceholder( $placeholder ) {
        $this->placeholder = $placeholder;
        return $this;
    }

    function build() {
        $type = $this->type;
        $is_disabled = '';
        if ( $this->disabled ) {
            $is_disabled = 'disabled';
        }
        $color_chooser_cls = '';
        if ( $this->type == 'color-chooser') {
            $color_chooser_cls = 'wpra-color-chooser';
            $type = 'text';
        }
        $out = '';
        if ( $this->type != 'hidden' and $this->label != '') {
            $out .= "<label for='{$this->id}'>{$this->label}</label>";
        }
        $out .= "<input type='{$type}' name='{$this->id}' id='{$this->id}' placeholder='{$this->placeholder}' class='form-control {$color_chooser_cls}' value=\"{$this->value}\" {$is_disabled}>";
        echo "<div class='{$this->classes}'>{$out}</div>";
    }

}
