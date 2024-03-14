<?php
namespace WP_Reactions\Lite\FieldManager;

class Switcher extends Field {
    private $checked;
    private $unchecked;

    public function setChecked( $checked ) {
        $this->checked = $checked;
        return $this;
    }

    public function setUnchecked($unchecked) {
        $this->unchecked = $unchecked;
        return $this;
    }

	function build() {
		if ($this->value == $this->checked) {
			$checked = 'checked';
		} else {
			$checked = '';
		}
		echo "<div class='wpe-switch-wrap {$this->classes}'>";
		if ($this->unchecked != '') {
			echo "<input type='hidden' name='{$this->id}' value='$this->unchecked'>";
		}
		if ($this->label != '') {
			echo "<p class='wpe-switch-title'>{$this->label}</p>";
		}
		$name = empty($this->name) ? $this->id : $this->name;

		$disabled = $this->disabled ? 'disabled' : '';

		echo '<label class="wpe-switch">';
		echo "<input id='{$this->id}' name='{$name}' type='checkbox' class='wpe-switch-input' {$checked} {$disabled} value='{$this->checked}'>";
		echo '<span class="wpe-switch-label" data-on="On" data-off="Off"></span>';
		echo '<span class="wpe-switch-handle"></span>';
		echo '</label></div>';
	}
}
